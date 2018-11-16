<?php

namespace App\Http\Controllers;

use App\Article;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    private $config = [];

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->config['baseUrl'] = 'http://m.yourdomain.com';
        $this->config['titleSuffix'] = ' - YourCorp Instant Article';
    }

    /**
     * default page
     *
     * @return string
     */
    public function index()
    {
        $data = [];

        $data['title'] = '뉴스'. $this->config['titleSuffix'];
        $data['baseUrl'] = $this->config['baseUrl'];
        $data['currentUrl'] = $data['baseUrl'] .'/';

        $data['articles'] = Article::orderBy('uid', 'desc')->take(5)->get();

        return view('news', $data);
    }

    /**
     * show detail page
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $data = [];

        // 대표 이미지 기본값 설정
        $data['image'] = [
            'url' => 'http://placehold.it/320x240/?text=yourdomain',
            'width' => 320,
            'height' => 240,
        ];

        try {
            $data['article'] = Article::where('externUid', $id)
                ->where('externTypeCode', 'ndsoft__yourdomain')
                ->first();

            // 존재하지 않는 레코드이면
            if (!$data['article']) {
                abort(404, "데이터가 조회되지 않습니다. 존재하지 않을 가능성이 높습니다.");
            }

            $sData = json_decode($data['article']->sData);

            // 필자
            if ($data['article']->writerName == "") {
                $data['article']->writerName = $sData->writerNicknameInf->value;
            }
            $data['author'] = $data['article']->writerName;

            // $data['article']->bodyOrigin 에는 youtube 영상 링크 strip 되어 있기에 원본 본문 로딩
            if (isset($sData->pure1BodyInf)) {
                $data['article']->bodyOrigin = $sData->pure1BodyInf->value;

                // 본문 youtube 영상 치환
                $dom = HtmlDomParser::str_get_html($data['article']->bodyOrigin);
                foreach ($dom->find('iframe') as $iframe) {
                    $url = $iframe->src;
                    if (strpos($url, 'v=') !== false) {
                        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
                        $videoId = $my_array_of_vars['v'];
                    } else {
                        $parsedUrl = parse_url($url);
                        $videoId = str_replace('/embed/', '', $parsedUrl['path']);
                    }

                    $iframe->outertext = '<amp-youtube data-videoid="' . $videoId . '" layout="responsive" width="' . $iframe->width . '" height="' . $iframe->height . '"></amp-youtube>';
                }

                $data['article']->bodyOrigin = $dom;
            } else {
                $files = $data['article']->files;

                $i = 0;
                // 이미지 커스텀 태그 {img} 치환
                $data['article']->bodyOrigin = preg_replace_callback(
                    '/{img}/',
                    function($matches) use($files, &$i) {
                        $file = $files[$i]['attributes'];

                        if ($file['oriUrl'] != "") {
                            $imgInfo = getimagesize($file['oriUrl']);
                            $matches[0] = '<amp-img src="' . $file['oriUrl'] . '" width="' . $imgInfo[0] . '" height="' . $imgInfo[1] . '" layout="responsive"></amp-img>';
                        } else {
                            $matches[0] = '';
                        }

                        $i++;
                        return $matches[0];
                    },
                    $data['article']->bodyOrigin
                );

                if (count($files) > 0) {
                    $data['image'] = [
                        'url' => $files[0]->oriUrl,
                        'width' => $files[0]->width,
                        'height' => $files[0]->height,
                    ];
                } else {
                    $data['image'] = [
                        'url' => "http://static.yourdomain.com/static/img/logo/logo_for_light_background.png",
                        'width' => '480',
                        'height' => '50',
                    ];
                }
            }

            // 본문 허용 태그들만 남기기 https://github.com/ampproject/amphtml/blob/master/spec/amp-html-format.md
            $data['article']->bodyOrigin = strip_tags($data['article']->bodyOrigin, "<div><p><img><video><audio><button><a><svg><amp-youtube><amp-img><amp-iframe>");

            // 본문 <table> 제거 : 위의 strip_tags 함수 쓰지 않을 때 아래 주석 풀고 사용
            //$data['article']->bodyOrigin = preg_replace('/\<[\/]?(table|tbody|tr|td)([^\>]*)\>/i', '', $data['article']->bodyOrigin);

            // 허용되지 않는 속성 치환
            $data['article']->bodyOrigin = preg_replace('/t?arget="_self"/i', 'target="_blank"', $data['article']->bodyOrigin);

            // 금지속성 제거
            $data['article']->bodyOrigin = preg_replace('/(ng-href|style)="[^\"]*"/i', "", $data['article']->bodyOrigin);

            // 본문 '# 문장' 을 제목2 로 치환
            $data['article']->bodyOrigin = preg_replace('/(<p>)?# /', '<h2 class="tltBox">', $data['article']->bodyOrigin);
            $data['article']->bodyOrigin = preg_replace('/<h2 class="tltBox">([^\/]+)(<\/p>|<\/div>)/i', '<h2 class="tltBox">$1</h2>', $data['article']->bodyOrigin);

            // 본문 '◆ 문장' 또는 '## 문장' 을 제목3 으로 치환
            $data['article']->bodyOrigin = preg_replace('/(<p>)?(##|◆) /', '<h3 class="tltBox">', $data['article']->bodyOrigin);
            $data['article']->bodyOrigin = preg_replace('/<h3 class="tltBox">([^\/]+)(<\/p>|<\/div>)/i', '<h3 class="tltBox">$1</h3>', $data['article']->bodyOrigin);

            // 본문 <a href="#articleUrl"> 제거
            $data['article']->bodyOrigin = preg_replace('/<a href="#articleUrl">([^<]*)<\/a>/i', '$1', $data['article']->bodyOrigin);

            // 본문 삽입 이미지들 치환
            $dom = HtmlDomParser::str_get_html($data['article']->bodyOrigin);
            $i = 0;
            foreach($dom->find('img') as $img) {
                $imgInfo = getimagesize($img->src);
                $img->width = $imgInfo[0];
                $img->height = $imgInfo[1];

                $img->outertext = '<amp-img src="' . $img->src . '" width="' . $img->width . '" height="' . $img->height . '" layout="responsive"></amp-img>';

                // 첫 이미지를 대표 이미지로
                if ($i == 0) {
                    $data['image'] = [
                        'url' => $img->src,
                        'width' => $img->width,
                        'height' => $img->height,
                    ];
                }

                $i++;
            }
            $data['article']->bodyOrigin = $dom;
        } catch (ModelNotFoundException $e) {
            return Response::make('Not Found', 404);
            //dd(get_class_methods($e));
            //dd($e);
        }

        $data['title'] = $data['article']->title . $this->config['titleSuffix'];
        $data['baseUrl'] = $this->config['baseUrl'];
        $data['currentUrl'] = $data['baseUrl'] .'/news/articleView.html?idxno='. $id;
        $data['datePublished'] = str_replace(' ', 'T', $data['article']->externOccurDate) .'+09:00';
        $data['dateModified'] = str_replace(' ', 'T', $data['article']->modifyDate) .'+09:00';

        return view('news.view', $data);
    }
}
