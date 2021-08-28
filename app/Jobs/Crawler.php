<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Crawler implements ShouldQueue
{
    public $keyword;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($keyword)
    {
        //
        $this->keyword = $keyword;
        print_r($keyword);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        for ($x = 0; $x<=120; $x++ ) {
            $url_search_shoppe = 'https://shopee.vn/api/v4/search/search_items?by=relevancy&keyword='.urlencode($this->keyword).'&limit=100&newest='.($x*100).'&order=desc&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2';
            $data_response = json_decode(file_get_contents($url_search_shoppe));
            $items = $data_response->items;
            if($items != ''){
                foreach ($items as $item) {
                    if($item->item_basic->shopid != ''
                        && $item->item_basic->itemid != ''
                    )
                    {
                        if($item->item_basic->tier_variations !='') {
                            $nature = $item->item_basic->tier_variations;
                            $nature = json_decode(json_encode($nature[0]), true);
                            $nature = implode('|', array_map(
                                function ($v, $k) {
                                    if ($v != '' and $v != '0') {
                                        if (is_array($v)) {
                                            return implode(',', $v);
                                        } else {
                                            return $v;
                                        }
                                    }
                                },
                                $nature,
                                array_keys($nature)
                            ));
                        }
                        $url = 'https://shopee.vn/'.implode('-', explode(' ',$item->item_basic->name)).'-i.'.$item->item_basic->shopid.'.'.$item->item_basic->itemid;
                        DB::table('product')->insertOrIgnore([
                            [
                                'shop_id'               => $item->item_basic->shopid,
                                'item_id'               => $item->item_basic->itemid,
                                'keyword'               => $this->keyword,
                                'url'                   => $url,
                                'name'                  => $item->item_basic->name,
                                'image_cover'           => $item->item_basic->image,
                                'images'                => implode("|", $item->item_basic->images ),
                                'view_count'            => $item->item_basic->view_count,
                                'brand'                 => $item->item_basic->brand,
                                'price'                 => $item->item_basic->price,
                                'price_before_discount' => $item->item_basic->price_before_discount,
                                'nature'                => $nature,
                                'item_rating'           => $item->item_basic->item_rating->rating_star,
                                'shop_location'         => $item->item_basic->shop_location,
                                'created_at'            => date("Y-m-d h:i:sa", time())
                            ],
                        ]);
                    }
                }
            }else {

                break;
            }

        }
        DB::table('keywords')
            ->where('Keyword', $this->keyword)
            ->update(['is_crawl' => 1]);
    }
}
