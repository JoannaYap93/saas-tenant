<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPaymentUrlItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payment_url_item')->delete();
        
        \DB::table('tbl_payment_url_item')->insert(array (
            0 => 
            array (
                'payment_url_item_id' => 1,
                'payment_url_id' => 1,
                'invoice_id' => 7344,
                'invoice_total' => '317.35',
                'payment_url_item_created' => '2023-08-25 15:49:31',
                'payment_url_item_updated' => '2023-08-25 15:49:31',
                'is_deleted' => 0,
            ),
            1 => 
            array (
                'payment_url_item_id' => 2,
                'payment_url_id' => 2,
                'invoice_id' => 7340,
                'invoice_total' => '32.50',
                'payment_url_item_created' => '2023-08-25 16:04:38',
                'payment_url_item_updated' => '2023-08-25 16:04:38',
                'is_deleted' => 0,
            ),
            2 => 
            array (
                'payment_url_item_id' => 3,
                'payment_url_id' => 2,
                'invoice_id' => 7348,
                'invoice_total' => '2262.20',
                'payment_url_item_created' => '2023-08-25 16:04:38',
                'payment_url_item_updated' => '2023-08-25 16:04:38',
                'is_deleted' => 0,
            ),
            3 => 
            array (
                'payment_url_item_id' => 4,
                'payment_url_id' => 3,
                'invoice_id' => 7325,
                'invoice_total' => '654.60',
                'payment_url_item_created' => '2023-08-25 16:05:15',
                'payment_url_item_updated' => '2023-08-25 16:05:15',
                'is_deleted' => 0,
            ),
        ));
        
        
    }
}