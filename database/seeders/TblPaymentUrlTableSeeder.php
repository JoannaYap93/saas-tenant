<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPaymentUrlTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payment_url')->delete();
        
        \DB::table('tbl_payment_url')->insert(array (
            0 => 
            array (
                'payment_url_id' => 1,
                'customer_id' => 1,
                'payment_url_total' => '317.35',
                'payment_url_created' => '2023-08-25 15:49:31',
                'payment_url_updated' => '2023-08-25 15:49:31',
                'payment_url_status' => 'pending',
                'user_id' => 2,
            ),
            1 => 
            array (
                'payment_url_id' => 2,
                'customer_id' => 4,
                'payment_url_total' => '2294.70',
                'payment_url_created' => '2023-08-25 16:04:38',
                'payment_url_updated' => '2023-08-25 16:04:38',
                'payment_url_status' => 'pending',
                'user_id' => 2,
            ),
            2 => 
            array (
                'payment_url_id' => 3,
                'customer_id' => 3,
                'payment_url_total' => '654.60',
                'payment_url_created' => '2023-08-25 16:05:15',
                'payment_url_updated' => '2023-08-25 16:05:15',
                'payment_url_status' => 'pending',
                'user_id' => 2,
            ),
        ));
        
        
    }
}