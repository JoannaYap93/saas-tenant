<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyBankTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_bank')->delete();
        
        \DB::table('tbl_company_bank')->insert(array (
            0 => 
            array (
                'company_bank_id' => 1,
                'company_bank_acc_name' => 'WEBBY SDN BHD',
                'company_bank_acc_no' => '112932012121',
                'setting_bank_id' => 16,
                'company_id' => 1,
                'company_bank_created' => '2023-08-22 14:58:11',
                'company_bank_updated' => '2023-08-22 15:00:29',
                'is_deleted' => 0,
            ),
            1 => 
            array (
                'company_bank_id' => 2,
                'company_bank_acc_name' => 'ECORYS SDN BHD',
                'company_bank_acc_no' => '8010849494',
                'setting_bank_id' => 4,
                'company_id' => 2,
                'company_bank_created' => '2022-03-10 11:10:59',
                'company_bank_updated' => '2022-03-17 19:05:17',
                'is_deleted' => 0,
            ),
            2 => 
            array (
                'company_bank_id' => 3,
                'company_bank_acc_name' => 'NOMOS PLUS SDN BHD',
                'company_bank_acc_no' => '8010849535',
                'setting_bank_id' => 4,
                'company_id' => 3,
                'company_bank_created' => '2022-03-17 18:58:02',
                'company_bank_updated' => '2022-03-17 18:58:02',
                'is_deleted' => 0,
            ),
            3 => 
            array (
                'company_bank_id' => 4,
                'company_bank_acc_name' => 'BDCATS SDN BHD',
                'company_bank_acc_no' => '8010849456',
                'setting_bank_id' => 4,
                'company_id' => 4,
                'company_bank_created' => '2022-03-17 19:00:44',
                'company_bank_updated' => '2022-03-17 19:00:44',
                'is_deleted' => 0,
            ),
            4 => 
            array (
                'company_bank_id' => 5,
                'company_bank_acc_name' => 'CT FRUITCHAIN SDN BHD',
                'company_bank_acc_no' => '8010869467',
                'setting_bank_id' => 4,
                'company_id' => 5,
                'company_bank_created' => '2022-03-17 19:08:14',
                'company_bank_updated' => '2022-03-17 19:08:14',
                'is_deleted' => 0,
            ),
            5 => 
            array (
                'company_bank_id' => 6,
                'company_bank_acc_name' => 'DO REES SDN BHD',
                'company_bank_acc_no' => '8010849478',
                'setting_bank_id' => 4,
                'company_id' => 6,
                'company_bank_created' => '2022-03-17 19:09:24',
                'company_bank_updated' => '2022-03-17 19:09:24',
                'is_deleted' => 0,
            ),
            6 => 
            array (
                'company_bank_id' => 7,
                'company_bank_acc_name' => 'DREAMVEST  FRUITS NATION SDN BHD',
                'company_bank_acc_no' => '8010849489',
                'setting_bank_id' => 4,
                'company_id' => 7,
                'company_bank_created' => '2022-03-17 19:10:54',
                'company_bank_updated' => '2022-03-17 19:10:54',
                'is_deleted' => 0,
            ),
        ));
        
        
    }
}