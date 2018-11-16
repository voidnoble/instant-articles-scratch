<?php namespace App;
/**
 * Created by PhpStorm.
 * User: yjkwak
 * Date: 2016. 2. 23.
 * Time: 오전 10:44
 *
 # [news.article.sData] JSON String 데이터 샘플

    + pure3BodyInf
    - dGenDate : "2016-02-22 11:45:01"
    - value : "내용들......"

    + writerNicknameInf
    - dGenDate : "2016-02-22 11:45:51"
    - value : "독일=이완특파원"

    + writerInf
    - dGenDate : "2016-02-22 11:45:51"
    - email : "xxx@yourdomain.com"

    + pure1BodyInf
    - dGenDate : "2016-02-22 11:45:51"
    - value : "내용들......"

    + ndsoft__yourdomain__relOnkItemInfs[0]
    - dGenDate : "2016-02-22 11:50:01"
    - title : "[제네바모터쇼] 알핀 비전 쿠페 콘셉트, 부활한 르노의 스포츠카"
    - url : "http://www.yourdomain.com/news/articleView.html?idxno=8761"
    - glUid : "254679"
    - uidx : "8761"

    + aLinkInfsInf
    - dGenDate : "2016-02-22 17:12:43"
    - value[0]
    - aLink : "#articleUrl"
    - type : "img"
    - type2 : "bodyImg"
    - type3 : "bodyImg"
    - value[1]
    - aLink : "#articleUrl"
    - type : "img"
    - type2 : "bodyImg"
    - type3 : "bodyImg"
    ......
 */

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';

    /**
     * Custom Primary Key field
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * Can bulk inserting fields
     *
     * @var array
     */
    protected $fillable = [
        'externUid',
        'externTypeCode',
        'externType2Code',
        'title',
        'body',
        'occurDate',
        'url',
        'bodyUpdateDate',
        'externOccurDate',
        'externOccur2Date',
        'nickname',
        'recCnt',
        'oppositCnt',
        'hitCnt',
        'originSource',
        'typeCode',
        'type2Code',
        'externNickname',
        'externRecCnt',
        'externOppositCnt',
        'externHitCnt',
        'externOriginSource',
        'rawBody',
        'publRawBody',
        'publStatus',
        'publDate',
        'writerUid',
        'writerTypeCode',
        'delStatus',
        'delDate',
        'siteUid',
        'subTitle',
        'miniTitle',
        'keywordsStr',
        'externUrl',
        'externBody',
        'writerEmail',
        'primeOneDepthCateItemUid',
        'makerUsrTypeCode',
        'makerUsrUid',
        'parentMdModelName',
        'parentMdUid',
        'modifyDate',
        'primeGroupCodesStr',
        'publReadyStatus',
        'externPublStatus',
        'externOccur3Date',
        'externDelStatus',
        'title2',
        'writerName',
        'externType3Code',
        'sData',
        'movieCode',
        'bodyOrigin',
    ];

    /**
     * Using Eloquent ORM's created_at, published_at fields
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * ex) Password field
     *
     * @var array
     */
    //protected $hidden = [''];

    /**
     * Get the files for the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\ArticleFile', 'articleUid', 'uid');
    }
}