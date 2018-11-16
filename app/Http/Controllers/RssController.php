<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Sunra\PhpSimple\HtmlDomParser;

class RssController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * default page
     *
     * @return string
     */
    public function index()
    {
        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        $data = [];
        $url = "http://www.yourdomain.com/rss/allArticle.xml";

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();

        // Now set some options (most are optional)

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set a referer
        //curl_setopt($ch, CURLOPT_REFERER, "http://www.yourdomain.com");

        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36");

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        // Close the cURL resource, and free system resources
        curl_close($ch);

        $data['output'] = mb_convert_encoding($output, 'utf-8', 'euc-kr');
        $data['output'] = str_replace("euc-kr", "utf-8", $data['output']);

        $content = View::make('rss', $data);
        $response = response($content, 200)
            ->header('Content-Type', 'application/rss+xml');

        return $response;
    }
}
