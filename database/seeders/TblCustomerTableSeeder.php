<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCustomerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_customer')->delete();
        
        \DB::table('tbl_customer')->insert(array (
            0 => 
            array (
                'customer_id' => 1,
                'customer_company_name' => 'Hua Xin Berhad',
                'customer_name' => 'Felix',
                'customer_mobile_no' => '60121232421',
                'customer_address' => '3-1, JALAN MERBAH 1,',
                'customer_address2' => 'BANDAR PUCHONG JAYA,',
                'customer_state' => 'SELANGOR',
                'customer_city' => 'PUCHONG',
                'customer_postcode' => '47170',
                'customer_email' => 'ANNIE@HUAXIN.GLOBAL',
                'customer_code' => 'v9sda',
                'customer_country' => 'Malaysia',
                'customer_created' => '2023-01-14 19:35:22',
                'customer_updated' => '2023-01-14 19:35:22',
                'company_id' => 1,
                'customer_category_id' => 9,
                'warehouse_id' => NULL,
                'customer_acc_name' => 'Felix',
                'customer_acc_mobile_no' => '60102251851',
                'customer_credit' => NULL,
                'customer_status' => 'activate',
            ),
            1 => 
            array (
                'customer_id' => 2,
                'customer_company_name' => 'Megah Holdings Sdn Bhd',
                'customer_name' => 'Thomas',
                'customer_mobile_no' => '60193847382',
                'customer_address' => '2-3, Jalan Merbah 1',
                'customer_address2' => 'Bandar Puchong Jaya',
                'customer_state' => 'Selangor',
                'customer_city' => 'Puchong',
                'customer_postcode' => '47170',
                'customer_email' => 'thomas@megah.my',
                'customer_code' => 'thomas',
                'customer_country' => 'Malaysia',
                'customer_created' => '2023-08-24 20:30:54',
                'customer_updated' => '2023-08-24 20:30:54',
                'company_id' => 1,
                'customer_category_id' => 9,
                'warehouse_id' => 1,
                'customer_acc_name' => 'Thomas',
                'customer_acc_mobile_no' => '60193847382',
                'customer_credit' => NULL,
                'customer_status' => 'activate',
            ),
            2 => 
            array (
                'customer_id' => 3,
                'customer_company_name' => 'Petronas',
                'customer_name' => 'Steven',
                'customer_mobile_no' => '60134398392',
                'customer_address' => 'No. 1, Jalan Pahang 7/49Z',
                'customer_address2' => 'Seksyen 6',
                'customer_state' => 'Perak',
                'customer_city' => 'Lumut',
                'customer_postcode' => '31043',
                'customer_email' => 'steven@petronas.my',
                'customer_code' => 'steven',
                'customer_country' => 'Malaysia',
                'customer_created' => '2023-08-24 20:35:31',
                'customer_updated' => '2023-08-24 20:35:31',
                'company_id' => 1,
                'customer_category_id' => 9,
                'warehouse_id' => 4,
                'customer_acc_name' => 'Steven',
                'customer_acc_mobile_no' => '60134398392',
                'customer_credit' => NULL,
                'customer_status' => 'activate',
            ),
            3 => 
            array (
                'customer_id' => 4,
                'customer_company_name' => 'Hua Xin Berhad',
                'customer_name' => 'Steven',
                'customer_mobile_no' => '60134398392',
                'customer_address' => 'No. 1, Jalan Pahang 7/49Z',
                'customer_address2' => 'Seksyen 6',
                'customer_state' => 'Perak',
                'customer_city' => 'Lumut',
                'customer_postcode' => '31043',
                'customer_email' => 'steven@hauaxin.global',
                'customer_code' => 'stevenh',
                'customer_country' => 'Malaysia',
                'customer_created' => '2023-08-24 20:36:00',
                'customer_updated' => '2023-08-24 20:36:00',
                'company_id' => 1,
                'customer_category_id' => 9,
                'warehouse_id' => 4,
                'customer_acc_name' => 'Steven',
                'customer_acc_mobile_no' => '60136593691',
                'customer_credit' => NULL,
                'customer_status' => 'activate',
            ),
        ));
        
        
    }
}