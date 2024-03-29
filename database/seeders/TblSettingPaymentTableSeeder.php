<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingPaymentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_payment')->delete();
        
        \DB::table('tbl_setting_payment')->insert(array (
            0 => 
            array (
                'setting_payment_id' => 1,
                'setting_payment_name' => 'Transfer',
                'is_payment_gateway' => 1,
                'is_offline' => 0,
                'setting_payment_status' => 0,
                'setting_payment_slug' => 'transfer',
            ),
            1 => 
            array (
                'setting_payment_id' => 2,
                'setting_payment_name' => 'Stripe',
                'is_payment_gateway' => 1,
                'is_offline' => 1,
                'setting_payment_status' => 0,
                'setting_payment_slug' => 'stripe',
            ),
            2 => 
            array (
                'setting_payment_id' => 3,
                'setting_payment_name' => 'Cash',
                'is_payment_gateway' => 0,
                'is_offline' => 0,
                'setting_payment_status' => 1,
                'setting_payment_slug' => 'cash',
            ),
            3 => 
            array (
                'setting_payment_id' => 4,
                'setting_payment_name' => 'Cash',
                'is_payment_gateway' => 0,
                'is_offline' => 1,
                'setting_payment_status' => 0,
                'setting_payment_slug' => 'cash-1',
            ),
            4 => 
            array (
                'setting_payment_id' => 5,
                'setting_payment_name' => 'Credit',
                'is_payment_gateway' => 0,
                'is_offline' => 0,
                'setting_payment_status' => 0,
                'setting_payment_slug' => 'credit',
            ),
        ));
        
        
    }
}