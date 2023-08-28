<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblProductCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_product_category')->delete();
        
        \DB::table('tbl_product_category')->insert(array (
            0 => 
            array (
                'product_category_id' => 1,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Durian',
                'product_category_ranking' => 1,
                'is_deleted' => 0,
                'product_category_created' => '2022-02-26 11:57:55',
                'product_category_updated' => '2022-02-26 11:57:55',
                'product_category_slug' => 'durian',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
            1 => 
            array (
                'product_category_id' => 2,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Durian - 名种',
                'product_category_ranking' => 1,
                'is_deleted' => 0,
                'product_category_created' => '2022-03-08 17:39:16',
                'product_category_updated' => '2022-03-08 17:39:16',
                'product_category_slug' => 'durian-1',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
            2 => 
            array (
                'product_category_id' => 3,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Durian - 杂名',
                'product_category_ranking' => 1,
                'is_deleted' => 0,
                'product_category_created' => '2022-03-08 17:39:50',
                'product_category_updated' => '2022-03-08 17:39:50',
                'product_category_slug' => 'durian-2',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
            3 => 
            array (
                'product_category_id' => 4,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Durian - Kampung',
                'product_category_ranking' => 1,
                'is_deleted' => 0,
                'product_category_created' => '2022-03-08 17:47:05',
                'product_category_updated' => '2022-03-08 17:47:05',
                'product_category_slug' => 'durian-kampung',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
            4 => 
            array (
                'product_category_id' => 5,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Mangosteen',
                'product_category_ranking' => 2,
                'is_deleted' => 0,
                'product_category_created' => '2022-03-08 17:49:57',
                'product_category_updated' => '2022-03-08 17:49:57',
                'product_category_slug' => 'mangosteen',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
            5 => 
            array (
                'product_category_id' => 6,
                'product_category_parent_id' => 0,
                'product_category_name' => 'Coconut',
                'product_category_ranking' => 5,
                'is_deleted' => 0,
                'product_category_created' => '2022-03-23 12:58:18',
                'product_category_updated' => '2022-03-23 12:58:18',
                'product_category_slug' => 'coconut',
                'product_category_status' => 'published',
                'company_id' => 0,
            ),
        ));
        
        
    }
}