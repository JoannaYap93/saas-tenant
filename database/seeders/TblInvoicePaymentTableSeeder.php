<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblInvoicePaymentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_invoice_payment')->delete();
        
        \DB::table('tbl_invoice_payment')->insert(array (
            0 => 
            array (
                'invoice_payment_id' => 1,
                'invoice_id' => 1,
                'invoice_payment_amount' => '644.00',
                'invoice_payment_date' => '2022-07-25',
                'invoice_payment_created' => '2022-07-25 16:37:13',
                'invoice_payment_updated' => '2022-07-25 16:37:13',
                'invoice_payment_data' => '',
                'is_deleted' => 0,
                'setting_payment_id' => 1,
                'invoice_payment_remark' => NULL,
            ),
            1 => 
            array (
                'invoice_payment_id' => 11046,
                'invoice_id' => 7321,
                'invoice_payment_amount' => '1050.00',
                'invoice_payment_date' => '2023-08-23',
                'invoice_payment_created' => '2023-08-23 16:50:16',
                'invoice_payment_updated' => '2023-08-23 16:50:16',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 1,
                'invoice_payment_remark' => NULL,
            ),
            2 => 
            array (
                'invoice_payment_id' => 11047,
                'invoice_id' => 7321,
                'invoice_payment_amount' => '1050.00',
                'invoice_payment_date' => '2023-08-23',
                'invoice_payment_created' => '2023-08-23 16:51:26',
                'invoice_payment_updated' => '2023-08-23 16:51:26',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 2,
                'invoice_payment_remark' => NULL,
            ),
            3 => 
            array (
                'invoice_payment_id' => 11051,
                'invoice_id' => 7325,
                'invoice_payment_amount' => '654.60',
                'invoice_payment_date' => '2023-08-23',
                'invoice_payment_created' => '2023-08-23 21:07:07',
                'invoice_payment_updated' => '2023-08-23 21:07:07',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 1,
                'invoice_payment_remark' => NULL,
            ),
            4 => 
            array (
                'invoice_payment_id' => 11082,
                'invoice_id' => 7340,
                'invoice_payment_amount' => '32.50',
                'invoice_payment_date' => '2023-08-24',
                'invoice_payment_created' => '2023-08-24 14:54:04',
                'invoice_payment_updated' => '2023-08-24 14:54:04',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 1,
                'invoice_payment_remark' => NULL,
            ),
            5 => 
            array (
                'invoice_payment_id' => 11086,
                'invoice_id' => 7344,
                'invoice_payment_amount' => '317.35',
                'invoice_payment_date' => '2023-08-24',
                'invoice_payment_created' => '2023-08-24 15:13:12',
                'invoice_payment_updated' => '2023-08-24 15:13:12',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 3,
                'invoice_payment_remark' => NULL,
            ),
            6 => 
            array (
                'invoice_payment_id' => 11089,
                'invoice_id' => 7347,
                'invoice_payment_amount' => '510.20',
                'invoice_payment_date' => '2023-08-24',
                'invoice_payment_created' => '2023-08-24 16:56:59',
                'invoice_payment_updated' => '2023-08-24 16:56:59',
                'invoice_payment_data' => '0',
                'is_deleted' => 1,
                'setting_payment_id' => 4,
                'invoice_payment_remark' => NULL,
            ),
        ));
        
        
    }
}