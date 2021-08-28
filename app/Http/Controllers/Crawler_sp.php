<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\Crawler;
use DB;
class Crawler_sp extends Controller
{
    //
    public static function crawl_shopee($keyword)
    {

//            $keyword_query = DB::table('keyword')->where('is_crawl', 0)->first();
//            $keyword = $keyword_query->keyword;
//            DB::table('keywords')
//                ->where('Keyword', $keyword)
//                ->update(['is_crawl' => 1]);
            $crawler = new Crawler($keyword);
            dispatch($crawler);



        return true;

    }
}
