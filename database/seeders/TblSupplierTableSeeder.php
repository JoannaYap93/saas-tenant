<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier')->delete();
        
        \DB::table('tbl_supplier')->insert(array (
            0 => 
            array (
                'supplier_id' => 1,
                'supplier_name' => 'ABC',
                'supplier_mobile_no' => '601110837350',
                'supplier_phone_no' => '',
                'supplier_email' => 'patrick@co3.co',
                'supplier_address' => 'NO.3-1, JALAN MERBAH 1,',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'Selangor',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'PATRICK',
                'supplier_currency' => 'MYR',
                'supplier_credit_limit' => '500000.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-23 12:54:21',
                'supplier_updated' => '2022-08-23 12:54:21',
            ),
            1 => 
            array (
                'supplier_id' => 2,
                'supplier_name' => 'HUA XIN BHD',
                'supplier_mobile_no' => '60102251851',
                'supplier_phone_no' => '',
                'supplier_email' => 'lin@silverock.com.my',
                'supplier_address' => '3-1 JALAN MERBAH 1',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'ANNIE',
                'supplier_currency' => 'MYR',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-23 14:27:02',
                'supplier_updated' => '2022-08-23 14:27:02',
            ),
            2 => 
            array (
                'supplier_id' => 3,
                'supplier_name' => 'TAN ZAI CHAI',
                'supplier_mobile_no' => '60139831343',
                'supplier_phone_no' => '',
                'supplier_email' => 'wanting8425@gmail.com',
                'supplier_address' => '3-1 JALAN MERBAH 1',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'TAN ZAI CHAI',
                'supplier_currency' => NULL,
                'supplier_credit_limit' => '9999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-25 13:03:29',
                'supplier_updated' => '2023-03-13 12:23:55',
            ),
            3 => 
            array (
                'supplier_id' => 4,
                'supplier_name' => 'LEE HING CHEONG',
                'supplier_mobile_no' => '601158695040',
                'supplier_phone_no' => '6093615243',
                'supplier_email' => 'nora@silverock.com.my',
                'supplier_address' => '24, 25, 26, MAIN STREET,',
                'supplier_address2' => 'TRAS',
                'supplier_city' => 'RAUB',
                'supplier_state' => 'PAHANG',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '27670',
                'supplier_status' => 'active',
                'supplier_pic' => 'ZAI CHAI',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-26 16:38:37',
                'supplier_updated' => '2022-10-25 14:35:46',
            ),
            4 => 
            array (
                'supplier_id' => 5,
                'supplier_name' => 'BDCATS SDN BHD',
                'supplier_mobile_no' => '60177057187',
                'supplier_phone_no' => '',
                'supplier_email' => 'johnny@silverock.com.my',
                'supplier_address' => '3-1 JALAN MERBAH 1',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'Johnny',
                'supplier_currency' => 'MYR',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-29 10:14:46',
                'supplier_updated' => '2022-08-29 10:14:46',
            ),
            5 => 
            array (
                'supplier_id' => 6,
                'supplier_name' => 'TAN WAN TING',
                'supplier_mobile_no' => '6013123456',
                'supplier_phone_no' => '',
                'supplier_email' => 'wanting@koreawallpaper.com',
                'supplier_address' => '3-1 JALAN MERBAH 1',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'wanting',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-29 10:43:29',
                'supplier_updated' => '2022-10-25 14:35:37',
            ),
            6 => 
            array (
                'supplier_id' => 7,
                'supplier_name' => 'DREAMVEST FRUITS NATION SDN BHD',
                'supplier_mobile_no' => '60193765224',
                'supplier_phone_no' => '',
                'supplier_email' => 'lin@koreawallpaper.com',
                'supplier_address' => '3-1 JALAN MERBAH 1',
                'supplier_address2' => 'BANDAR PUCHONG JAYA',
                'supplier_city' => 'PUCHONG',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '47170',
                'supplier_status' => 'active',
                'supplier_pic' => 'lin',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-29 10:52:10',
                'supplier_updated' => '2022-10-25 14:35:04',
            ),
            7 => 
            array (
                'supplier_id' => 8,
                'supplier_name' => 'PRECISION CULTIVATIONS SDN BHD',
                'supplier_mobile_no' => '6076638262',
                'supplier_phone_no' => '',
                'supplier_email' => 'nora@koreawallpaper.com',
                'supplier_address' => 'LOT 5322, JALAN PERPADUAN',
                'supplier_address2' => 'TAMAN MAS',
                'supplier_city' => 'KULAI',
                'supplier_state' => 'JOHOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '81000',
                'supplier_status' => 'active',
                'supplier_pic' => 'wanting',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 30,
                'supplier_created' => '2022-08-29 11:11:48',
                'supplier_updated' => '2022-10-25 14:34:48',
            ),
            8 => 
            array (
                'supplier_id' => 9,
                'supplier_name' => 'NEW ENG LEE AGRICULTURE SDN BHD',
                'supplier_mobile_no' => '6069727240',
                'supplier_phone_no' => '6069727240',
                'supplier_email' => 'nora@silverock.com.my',
                'supplier_address' => 'NO 83, KUNDANG ULU',
                'supplier_address2' => 'GERISEK',
                'supplier_city' => 'LEDANG',
                'supplier_state' => 'JOHOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '84700',
                'supplier_status' => 'inactive',
                'supplier_pic' => 'WANTING',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '99999.00',
                'supplier_credit_term' => 99,
                'supplier_created' => '2022-10-25 15:22:28',
                'supplier_updated' => '2022-10-25 15:22:28',
            ),
            9 => 
            array (
                'supplier_id' => 10,
                'supplier_name' => 'AERODYNE GEOSPATIAL SDN BHD',
                'supplier_mobile_no' => '60383228771',
                'supplier_phone_no' => '60383228771',
                'supplier_email' => 'wanting8425@gmail.com',
                'supplier_address' => 'CYBERVIEW 10',
                'supplier_address2' => 'PERSIARAN CYBER POINT SELATAN CYBER 8',
                'supplier_city' => 'CYBER 8',
                'supplier_state' => 'SELANGOR',
                'supplier_country' => 'Malaysia',
                'supplier_postcode' => '63000',
                'supplier_status' => 'active',
                'supplier_pic' => 'WANTING',
                'supplier_currency' => '1',
                'supplier_credit_limit' => '999999.00',
                'supplier_credit_term' => 9999,
                'supplier_created' => '2022-12-06 17:13:33',
                'supplier_updated' => '2022-12-06 17:13:33',
            ),
        ));
        
        
    }
}