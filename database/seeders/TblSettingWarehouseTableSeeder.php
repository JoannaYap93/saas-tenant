<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingWarehouseTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_warehouse')->delete();
        
        \DB::table('tbl_setting_warehouse')->insert(array (
            0 => 
            array (
                'warehouse_id' => 1,
                'warehouse_name' => 'WH Puchong',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-02-26 12:22:29',
                'warehouse_udate' => '2022-02-26 12:22:29',
                'warehouse_slug' => 'wh-puchong',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            1 => 
            array (
                'warehouse_id' => 2,
            'warehouse_name' => 'PHG Ever Fresh Food (M) Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-02-26 13:51:20',
                'warehouse_udate' => '2022-12-31 18:10:00',
                'warehouse_slug' => 'phg-ever-fresh-food-m-sdn-bhd',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            2 => 
            array (
                'warehouse_id' => 3,
                'warehouse_name' => 'WH Puchong',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-03-03 11:21:10',
                'warehouse_udate' => '2022-03-03 11:21:10',
                'warehouse_slug' => 'wh-puchong-1',
                'warehouse_ranking' => 1,
                'is_deleted' => 1,
                'company_id' => 1,
            ),
            3 => 
            array (
                'warehouse_id' => 4,
                'warehouse_name' => 'Benum Hill Fruits Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-27 10:18:26',
                'warehouse_udate' => '2022-12-27 10:18:26',
                'warehouse_slug' => 'benum-hill-fruits-sdn-bhd',
                'warehouse_ranking' => 2,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            4 => 
            array (
                'warehouse_id' => 5,
                'warehouse_name' => 'Benum Hill Fruits Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-27 22:18:32',
                'warehouse_udate' => '2022-12-27 22:18:32',
                'warehouse_slug' => 'benum-hill-fruits-sdn-bhd-1',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            5 => 
            array (
                'warehouse_id' => 6,
                'warehouse_name' => 'Weecan Fruitchain Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-27 22:34:28',
                'warehouse_udate' => '2022-12-27 22:44:59',
                'warehouse_slug' => 'weecan-fruitchain-sdn-bhd',
                'warehouse_ranking' => 2,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            6 => 
            array (
                'warehouse_id' => 7,
                'warehouse_name' => 'WeeCan Fruitchain Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-27 22:40:49',
                'warehouse_udate' => '2022-12-27 22:44:39',
                'warehouse_slug' => 'weecan-fruitchain-sdn-bhd-1',
                'warehouse_ranking' => 3,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            7 => 
            array (
                'warehouse_id' => 8,
                'warehouse_name' => 'Klau Durian City Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-29 11:02:37',
                'warehouse_udate' => '2022-12-29 11:02:37',
                'warehouse_slug' => 'klau-durian-city-sdn-bhd',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            8 => 
            array (
                'warehouse_id' => 9,
                'warehouse_name' => 'Benum Hill Fruits Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-30 22:45:08',
                'warehouse_udate' => '2022-12-30 22:45:08',
                'warehouse_slug' => 'benum-hill-fruits-sdn-bhd-2',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
            9 => 
            array (
                'warehouse_id' => 10,
                'warehouse_name' => 'Weecan Fruitchain Sdn Bhd',
                'warehouse_status' => 'active',
                'warehouse_cdate' => '2022-12-30 22:49:00',
                'warehouse_udate' => '2022-12-30 22:49:00',
                'warehouse_slug' => 'weecan-fruitchain-sdn-bhd-2',
                'warehouse_ranking' => 1,
                'is_deleted' => 0,
                'company_id' => 1,
            ),
        ));
        
        
    }
}