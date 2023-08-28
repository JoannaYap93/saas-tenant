<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPaymentUrlLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payment_url_log')->delete();
        
        \DB::table('tbl_payment_url_log')->insert(array (
            0 => 
            array (
                'payment_url_log_id' => 1,
                'payment_url_id' => 1,
                'payment_url_log_action' => 'Create',
                'payment_url_log_description' => 'Payment Url created by ',
                'payment_url_log_created' => '2023-08-25 15:49:31',
                'user_id' => 1,
                'customer_id' => 1,
            ),
            1 => 
            array (
                'payment_url_log_id' => 2,
                'payment_url_id' => 2,
                'payment_url_log_action' => 'Create',
                'payment_url_log_description' => 'Payment Url created by ',
                'payment_url_log_created' => '2023-08-25 16:04:38',
                'user_id' => 1,
                'customer_id' => 4,
            ),
            2 => 
            array (
                'payment_url_log_id' => 3,
                'payment_url_id' => 3,
                'payment_url_log_action' => 'Create',
                'payment_url_log_description' => 'Payment Url created by ',
                'payment_url_log_created' => '2023-08-25 16:05:15',
                'user_id' => 1,
                'customer_id' => 3,
            ),
        ));
        
        
    }
}