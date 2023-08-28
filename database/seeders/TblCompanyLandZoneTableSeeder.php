<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandZoneTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_zone')->delete();
        
        \DB::table('tbl_company_land_zone')->insert(array (
            0 => 
            array (
                'company_land_zone_id' => 1,
                'company_land_zone_name' => 'Titi Zone A',
                'company_id' => 1,
                'company_land_id' => 1,
                'company_land_zone_total_tree' => 2,
                'company_land_zone_created' => '2022-07-20 01:44:18',
                'company_land_zone_updated' => '2022-07-20 01:44:35',
                'is_delete' => 0,
            ),
            1 => 
            array (
                'company_land_zone_id' => 2,
                'company_land_zone_name' => 'Titi Zone B',
                'company_id' => 1,
                'company_land_id' => 2,
                'company_land_zone_total_tree' => 1,
                'company_land_zone_created' => '2022-07-20 01:52:37',
                'company_land_zone_updated' => '2022-07-20 01:52:37',
                'is_delete' => 0,
            ),
            2 => 
            array (
                'company_land_zone_id' => 3,
                'company_land_zone_name' => 'Zon F',
                'company_id' => 2,
                'company_land_id' => 3,
                'company_land_zone_total_tree' => 116,
                'company_land_zone_created' => '2023-08-16 18:34:03',
                'company_land_zone_updated' => '2023-08-16 18:36:59',
                'is_delete' => 0,
            ),
            3 => 
            array (
                'company_land_zone_id' => 4,
                'company_land_zone_name' => 'Zon A',
                'company_id' => 2,
                'company_land_id' => 5,
                'company_land_zone_total_tree' => 116,
                'company_land_zone_created' => '2023-08-16 18:37:27',
                'company_land_zone_updated' => '2023-08-16 18:37:28',
                'is_delete' => 0,
            ),
            4 => 
            array (
                'company_land_zone_id' => 5,
                'company_land_zone_name' => 'Zon B',
                'company_id' => 2,
                'company_land_id' => 10,
                'company_land_zone_total_tree' => 170,
                'company_land_zone_created' => '2023-08-16 18:39:55',
                'company_land_zone_updated' => '2023-08-16 18:39:56',
                'is_delete' => 0,
            ),
            5 => 
            array (
                'company_land_zone_id' => 6,
                'company_land_zone_name' => 'Zon C',
                'company_id' => 2,
                'company_land_id' => 11,
                'company_land_zone_total_tree' => 213,
                'company_land_zone_created' => '2023-08-16 18:40:38',
                'company_land_zone_updated' => '2023-08-16 18:40:42',
                'is_delete' => 0,
            ),
            6 => 
            array (
                'company_land_zone_id' => 7,
                'company_land_zone_name' => 'Zon D',
                'company_id' => 3,
                'company_land_id' => 6,
                'company_land_zone_total_tree' => 279,
                'company_land_zone_created' => '2023-08-16 18:41:21',
                'company_land_zone_updated' => '2023-08-16 18:41:23',
                'is_delete' => 0,
            ),
            7 => 
            array (
                'company_land_zone_id' => 8,
                'company_land_zone_name' => 'Zon E',
                'company_id' => 4,
                'company_land_id' => 7,
                'company_land_zone_total_tree' => 197,
                'company_land_zone_created' => '2023-08-16 18:41:50',
                'company_land_zone_updated' => '2023-08-16 18:41:51',
                'is_delete' => 0,
            ),
            8 => 
            array (
                'company_land_zone_id' => 9,
                'company_land_zone_name' => 'ZOn G',
                'company_id' => 4,
                'company_land_id' => 8,
                'company_land_zone_total_tree' => 265,
                'company_land_zone_created' => '2023-08-16 18:43:19',
                'company_land_zone_updated' => '2023-08-16 18:43:21',
                'is_delete' => 0,
            ),
            9 => 
            array (
                'company_land_zone_id' => 10,
                'company_land_zone_name' => 'Zon A',
                'company_id' => 5,
                'company_land_id' => 9,
                'company_land_zone_total_tree' => 116,
                'company_land_zone_created' => '2023-08-16 18:37:27',
                'company_land_zone_updated' => '2023-08-16 18:37:28',
                'is_delete' => 0,
            ),
            10 => 
            array (
                'company_land_zone_id' => 11,
                'company_land_zone_name' => 'Zon B',
                'company_id' => 5,
                'company_land_id' => 12,
                'company_land_zone_total_tree' => 170,
                'company_land_zone_created' => '2023-08-16 18:39:55',
                'company_land_zone_updated' => '2023-08-16 18:39:56',
                'is_delete' => 0,
            ),
            11 => 
            array (
                'company_land_zone_id' => 12,
                'company_land_zone_name' => 'Zon C',
                'company_id' => 5,
                'company_land_id' => 13,
                'company_land_zone_total_tree' => 213,
                'company_land_zone_created' => '2023-08-16 18:40:38',
                'company_land_zone_updated' => '2023-08-16 18:40:42',
                'is_delete' => 0,
            ),
            12 => 
            array (
                'company_land_zone_id' => 13,
                'company_land_zone_name' => 'Zon D',
                'company_id' => 6,
                'company_land_id' => 14,
                'company_land_zone_total_tree' => 279,
                'company_land_zone_created' => '2023-08-16 18:41:21',
                'company_land_zone_updated' => '2023-08-16 18:41:23',
                'is_delete' => 0,
            ),
            13 => 
            array (
                'company_land_zone_id' => 14,
                'company_land_zone_name' => 'Zon E',
                'company_id' => 6,
                'company_land_id' => 15,
                'company_land_zone_total_tree' => 197,
                'company_land_zone_created' => '2023-08-16 18:41:50',
                'company_land_zone_updated' => '2023-08-16 18:41:51',
                'is_delete' => 0,
            ),
            14 => 
            array (
                'company_land_zone_id' => 15,
                'company_land_zone_name' => 'ZOn G',
                'company_id' => 7,
                'company_land_id' => 16,
                'company_land_zone_total_tree' => 265,
                'company_land_zone_created' => '2023-08-16 18:43:19',
                'company_land_zone_updated' => '2023-08-16 18:43:21',
                'is_delete' => 0,
            ),
        ));
        
        
    }
}