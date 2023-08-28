<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCollectTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_collect')->delete();
        
        \DB::table('tbl_collect')->insert(array (
            0 => 
            array (
                'collect_id' => 1,
                'product_id' => 1,
                'setting_product_size_id' => 2,
                'collect_quantity' => '600.0000',
                'collect_created' => '2022-07-20 02:13:20',
                'collect_updated' => '2022-07-20 02:13:20',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-07-20 02:14:38',
                'collect_code' => 'ZW001',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 1,
                'collect_remark' => NULL,
            ),
            1 => 
            array (
                'collect_id' => 1383,
                'product_id' => 2,
                'setting_product_size_id' => 9,
                'collect_quantity' => '43.1000',
                'collect_created' => '2022-04-11 14:22:41',
                'collect_updated' => '2022-04-11 14:22:41',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:41',
                'collect_code' => 'ZW012',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            2 => 
            array (
                'collect_id' => 1384,
                'product_id' => 3,
                'setting_product_size_id' => 2,
                'collect_quantity' => '51.6500',
                'collect_created' => '2022-04-11 14:22:24',
                'collect_updated' => '2022-04-11 14:22:24',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:41',
                'collect_code' => 'ZW011',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            3 => 
            array (
                'collect_id' => 1385,
                'product_id' => 2,
                'setting_product_size_id' => 3,
                'collect_quantity' => '45.8500',
                'collect_created' => '2022-04-11 14:22:05',
                'collect_updated' => '2022-04-11 14:22:05',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:41',
                'collect_code' => 'ZW010',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            4 => 
            array (
                'collect_id' => 1386,
                'product_id' => 3,
                'setting_product_size_id' => 2,
                'collect_quantity' => '13.6500',
                'collect_created' => '2022-04-11 14:21:47',
                'collect_updated' => '2022-04-11 14:21:47',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:41',
                'collect_code' => 'ZW009',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            5 => 
            array (
                'collect_id' => 1387,
                'product_id' => 2,
                'setting_product_size_id' => 3,
                'collect_quantity' => '13.5000',
                'collect_created' => '2022-04-11 14:21:33',
                'collect_updated' => '2022-04-11 14:21:33',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW008',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            6 => 
            array (
                'collect_id' => 1388,
                'product_id' => 3,
                'setting_product_size_id' => 7,
                'collect_quantity' => '28.7500',
                'collect_created' => '2022-04-11 14:21:13',
                'collect_updated' => '2022-04-11 14:21:13',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW007',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            7 => 
            array (
                'collect_id' => 1389,
                'product_id' => 1,
                'setting_product_size_id' => 4,
                'collect_quantity' => '44.7000',
                'collect_created' => '2022-04-11 14:20:57',
                'collect_updated' => '2022-04-11 14:20:57',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW006',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            8 => 
            array (
                'collect_id' => 1390,
                'product_id' => 3,
                'setting_product_size_id' => 2,
                'collect_quantity' => '3.0000',
                'collect_created' => '2022-04-11 14:20:43',
                'collect_updated' => '2022-04-11 14:20:43',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW005',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            9 => 
            array (
                'collect_id' => 1391,
                'product_id' => 2,
                'setting_product_size_id' => 4,
                'collect_quantity' => '12.6500',
                'collect_created' => '2022-04-11 14:20:27',
                'collect_updated' => '2022-04-11 14:20:27',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW004',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            10 => 
            array (
                'collect_id' => 1392,
                'product_id' => 1,
                'setting_product_size_id' => 2,
                'collect_quantity' => '18.6500',
                'collect_created' => '2022-04-11 14:20:08',
                'collect_updated' => '2022-04-11 14:20:08',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW003',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
            11 => 
            array (
                'collect_id' => 1393,
                'product_id' => 3,
                'setting_product_size_id' => 2,
                'collect_quantity' => '32.5000',
                'collect_created' => '2022-04-11 14:19:51',
                'collect_updated' => '2022-04-11 14:19:51',
                'company_id' => 1,
                'company_land_id' => 1,
                'collect_date' => '2022-04-11 14:23:42',
                'collect_code' => 'ZW002',
                'collect_status' => 'completed',
                'user_id' => 1,
                'sync_id' => 596,
                'collect_remark' => NULL,
            ),
        ));
        
        
    }
}