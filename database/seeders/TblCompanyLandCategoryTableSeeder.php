<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_category')->delete();
        
        \DB::table('tbl_company_land_category')->insert(array (
            0 => 
            array (
                'company_land_category_id' => 1,
                'company_land_category_name' => '10',
                'company_land_category_created' => '2023-08-11 16:01:03',
                'company_land_category_updated' => '2023-08-11 16:01:03',
                'is_deleted' => 0,
                'company_farm_id' => 1,
            ),
            1 => 
            array (
                'company_land_category_id' => 2,
                'company_land_category_name' => '99',
                'company_land_category_created' => '2022-02-26 11:56:03',
                'company_land_category_updated' => '2022-03-17 18:52:41',
                'is_deleted' => 1,
                'company_farm_id' => 1,
            ),
            2 => 
            array (
                'company_land_category_id' => 3,
                'company_land_category_name' => '11',
                'company_land_category_created' => '2022-02-26 11:56:03',
                'company_land_category_updated' => '2022-03-17 18:52:41',
                'is_deleted' => 1,
                'company_farm_id' => 1,
            ),
            3 => 
            array (
                'company_land_category_id' => 4,
                'company_land_category_name' => '10',
                'company_land_category_created' => '2022-02-26 11:56:19',
                'company_land_category_updated' => '2022-02-26 11:56:19',
                'is_deleted' => 0,
                'company_farm_id' => 2,
            ),
            4 => 
            array (
                'company_land_category_id' => 5,
                'company_land_category_name' => '301',
                'company_land_category_created' => '2022-02-26 11:56:03',
                'company_land_category_updated' => '2022-03-17 18:52:41',
                'is_deleted' => 1,
                'company_farm_id' => 1,
            ),
            5 => 
            array (
                'company_land_category_id' => 7,
                'company_land_category_name' => '10',
                'company_land_category_created' => '2022-02-26 11:56:29',
                'company_land_category_updated' => '2022-02-26 11:56:29',
                'is_deleted' => 0,
                'company_farm_id' => 4,
            ),
            6 => 
            array (
                'company_land_category_id' => 8,
                'company_land_category_name' => '24',
                'company_land_category_created' => '2022-02-26 13:35:13',
                'company_land_category_updated' => '2022-02-26 13:35:13',
                'is_deleted' => 0,
                'company_farm_id' => 5,
            ),
            7 => 
            array (
                'company_land_category_id' => 9,
                'company_land_category_name' => '34',
                'company_land_category_created' => '2022-03-03 11:05:48',
                'company_land_category_updated' => '2022-03-17 18:52:41',
                'is_deleted' => 0,
                'company_farm_id' => 1,
            ),
            8 => 
            array (
                'company_land_category_id' => 10,
                'company_land_category_name' => '301',
                'company_land_category_created' => '2022-03-07 18:25:17',
                'company_land_category_updated' => '2023-04-28 14:29:08',
                'is_deleted' => 0,
                'company_farm_id' => 6,
            ),
            9 => 
            array (
                'company_land_category_id' => 11,
                'company_land_category_name' => '98',
                'company_land_category_created' => '2022-03-07 18:25:17',
                'company_land_category_updated' => '2023-04-28 14:29:08',
                'is_deleted' => 0,
                'company_farm_id' => 6,
            ),
            10 => 
            array (
                'company_land_category_id' => 12,
                'company_land_category_name' => '17',
                'company_land_category_created' => '2022-03-07 18:25:17',
                'company_land_category_updated' => '2023-04-28 14:29:08',
                'is_deleted' => 1,
                'company_farm_id' => 6,
            ),
            11 => 
            array (
                'company_land_category_id' => 13,
                'company_land_category_name' => '3',
                'company_land_category_created' => '2022-03-07 18:25:17',
                'company_land_category_updated' => '2023-04-28 14:29:08',
                'is_deleted' => 0,
                'company_farm_id' => 6,
            ),
        ));
        
        
    }
}