<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company')->delete();
        
        \DB::table('tbl_company')->insert(array (
            0 => 
            array (
                'company_id' => 1,
                'company_name' => 'Webby Sdn Bhd',
                'company_code' => 'WBZ',
                'company_created' => '2023-08-18 11:34:58',
                'company_updated' => '2023-08-22 14:55:07',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '2-3, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'support@webby.com.my',
                'company_reg_no' => '201922312',
                'company_phone' => '60116283478',
                'setting_bank_id' => 2,
                'company_bank_acc_name' => 'Webby Sdn Bhd',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            1 => 
            array (
                'company_id' => 2,
                'company_name' => 'HUA XIN HOLDINGS SDN BHD',
                'company_code' => 'HX',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-17 16:02:52',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'annie@huaxin.global',
            'company_reg_no' => '202201001331 (1447028-D)',
                'company_phone' => '60102251851',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'HUA XIN HOLDINGS SDN.BHD.',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            2 => 
            array (
                'company_id' => 3,
                'company_name' => 'ECORYS SDN BHD',
                'company_code' => 'ER',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'info@ecorys.com',
            'company_reg_no' => '202201001346 (1447043-U)',
                'company_phone' => '60196581392',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'ECORYS SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            3 => 
            array (
                'company_id' => 4,
                'company_name' => 'NOMOS PLUS SDN BHD',
                'company_code' => 'NP',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'Patric.lcy@gmail.com',
            'company_reg_no' => '202201001323 (1447020-M)',
                'company_phone' => '60136541393',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'NOMOS PLUS SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            4 => 
            array (
                'company_id' => 5,
                'company_name' => 'BDCATS SDN BHD',
                'company_code' => 'BD',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'bdcats131@gmail.com',
            'company_reg_no' => '202201001331 (1447028-D)',
                'company_phone' => '60184581394',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'BDCATS SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            5 => 
            array (
                'company_id' => 6,
                'company_name' => 'CT FRUITCHAIN SDN BHD',
                'company_code' => 'CT',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'ctfruitchain@gmail.com',
            'company_reg_no' => '202201001331 (1447028-D)',
                'company_phone' => '60166581423',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'CT FRUITCHAIN SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            6 => 
            array (
                'company_id' => 7,
                'company_name' => 'DO REES SDN BHD',
                'company_code' => 'DO',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'dorees419@gmail.com',
            'company_reg_no' => '202201001346 (1447043-U)',
                'company_phone' => '601116684392',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'DO REES SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
            7 => 
            array (
                'company_id' => 8,
                'company_name' => 'DREAMVEST  FRUITS NATION SDN BHD',
                'company_code' => 'DF',
                'company_created' => '2022-03-01 13:41:24',
                'company_updated' => '2022-03-01 13:41:24',
                'company_enable_gst' => 0,
                'company_force_collect' => 0,
                'company_address' => '3-1, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor',
                'company_email' => 'info@dvfruits.com',
            'company_reg_no' => '202201001331 (1447028-D)',
                'company_phone' => '601362581390',
                'setting_bank_id' => 4,
                'company_bank_acc_name' => 'DREAMVEST  FRUITS NATION SDN BHD',
                'company_bank_acc_no' => '562889003409',
                'company_status' => 'active',
                'is_display' => 1,
            ),
        ));
        
        
    }
}