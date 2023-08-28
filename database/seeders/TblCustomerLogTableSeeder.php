<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCustomerLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_customer_log')->delete();
        
        \DB::table('tbl_customer_log')->insert(array (
            0 => 
            array (
                'customer_log_id' => 1,
                'customer_id' => 1,
                'customer_log_created' => '2023-08-24 20:30:54',
                'customer_log_updated' => '2023-08-24 20:30:54',
                'customer_log_action' => 'Add',
                'customer_log_description' => 'Customer Added By Webby',
                'user_id' => 1,
            ),
            1 => 
            array (
                'customer_log_id' => 2,
                'customer_id' => 2,
                'customer_log_created' => '2023-08-24 20:36:00',
                'customer_log_updated' => '2023-08-24 20:36:00',
                'customer_log_action' => 'Add',
                'customer_log_description' => 'Customer Added By Webby',
                'user_id' => 1,
            ),
            2 => 
            array (
                'customer_log_id' => 3,
                'customer_id' => 3,
                'customer_log_created' => '2023-08-24 20:36:00',
                'customer_log_updated' => '2023-08-24 20:36:00',
                'customer_log_action' => 'Add',
                'customer_log_description' => 'Customer Added By Webby',
                'user_id' => 1,
            ),
            3 => 
            array (
                'customer_log_id' => 4,
                'customer_id' => 4,
                'customer_log_created' => '2023-08-24 20:36:00',
                'customer_log_updated' => '2023-08-24 20:36:00',
                'customer_log_action' => 'Add',
                'customer_log_description' => 'Customer Added By Webby',
                'user_id' => 1,
            ),
        ));
        
        
    }
}