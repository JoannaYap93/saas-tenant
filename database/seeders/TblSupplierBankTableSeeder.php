<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierBankTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier_bank')->delete();
        
        \DB::table('tbl_supplier_bank')->insert(array (
            0 => 
            array (
                'supplier_bank_id' => 1,
                'supplier_bank_acc_no' => '123456789',
                'supplier_bank_acc_name' => '123456789',
                'setting_bank_id' => 8,
                'supplier_id' => 1,
                'supplier_bank_created' => '2022-08-23 12:54:22',
                'supplier_bank_updated' => '2022-08-23 12:54:22',
                'is_deleted' => 0,
            ),
            1 => 
            array (
                'supplier_bank_id' => 2,
                'supplier_bank_acc_no' => '7067605046',
                'supplier_bank_acc_name' => 'TAN ZAI CHAI',
                'setting_bank_id' => 4,
                'supplier_id' => 3,
                'supplier_bank_created' => '2022-08-25 13:03:31',
                'supplier_bank_updated' => '2022-08-25 13:03:31',
                'is_deleted' => 0,
            ),
            2 => 
            array (
                'supplier_bank_id' => 3,
                'supplier_bank_acc_no' => '3186967525',
                'supplier_bank_acc_name' => 'LEE HING CHEONG',
                'setting_bank_id' => 8,
                'supplier_id' => 4,
                'supplier_bank_created' => '2022-08-26 16:38:37',
                'supplier_bank_updated' => '2022-08-26 16:38:37',
                'is_deleted' => 0,
            ),
            3 => 
            array (
                'supplier_bank_id' => 4,
                'supplier_bank_acc_no' => '8010849480',
                'supplier_bank_acc_name' => 'BDCATS SDN BHD',
                'setting_bank_id' => 4,
                'supplier_id' => 5,
                'supplier_bank_created' => '2022-08-29 10:14:46',
                'supplier_bank_updated' => '2022-08-29 10:14:46',
                'is_deleted' => 0,
            ),
            4 => 
            array (
                'supplier_bank_id' => 5,
                'supplier_bank_acc_no' => '7053270177',
                'supplier_bank_acc_name' => 'TAN WAN TING',
                'setting_bank_id' => 4,
                'supplier_id' => 6,
                'supplier_bank_created' => '2022-08-29 10:43:29',
                'supplier_bank_updated' => '2022-08-29 10:43:29',
                'is_deleted' => 0,
            ),
            5 => 
            array (
                'supplier_bank_id' => 6,
                'supplier_bank_acc_no' => '8010849492',
                'supplier_bank_acc_name' => 'DREAMVEST FRUITS NATION SDN BHD',
                'setting_bank_id' => 4,
                'supplier_id' => 7,
                'supplier_bank_created' => '2022-08-29 10:52:10',
                'supplier_bank_updated' => '2022-08-29 10:52:10',
                'is_deleted' => 0,
            ),
            6 => 
            array (
                'supplier_bank_id' => 7,
                'supplier_bank_acc_no' => '501048157046',
                'supplier_bank_acc_name' => 'NEW ENG LEE AGRICULTURE SDN BHD',
                'setting_bank_id' => 16,
                'supplier_id' => 9,
                'supplier_bank_created' => '2022-10-25 15:22:28',
                'supplier_bank_updated' => '2022-10-25 15:22:28',
                'is_deleted' => 0,
            ),
            7 => 
            array (
                'supplier_bank_id' => 8,
                'supplier_bank_acc_no' => '21413800136102',
                'supplier_bank_acc_name' => 'AERODYNE GEOSPATIAL SDN BHD',
                'setting_bank_id' => 9,
                'supplier_id' => 10,
                'supplier_bank_created' => '2022-12-06 17:13:33',
                'supplier_bank_updated' => '2022-12-06 17:13:33',
                'is_deleted' => 0,
            ),
        ));
        
        
    }
}