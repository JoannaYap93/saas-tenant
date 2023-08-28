<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderExpenseTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order_expense')->delete();
        
        \DB::table('tbl_delivery_order_expense')->insert(array (
            0 => 
            array (
                'delivery_order_expense_id' => 1,
                'delivery_order_expense_value' => '50.00',
                'delivery_order_expense_kg' => '0.00',
                'delivery_order_expense_day' => 0,
                'delivery_order_expense_total' => '70.00',
                'setting_expense_id' => 4,
                'delivery_order_id' => 1,
                'delivery_order_expense_created' => '2022-07-20 18:13:43',
                'delivery_order_expense_updated' => '2022-07-20 18:13:43',
            ),
            1 => 
            array (
                'delivery_order_expense_id' => 2,
                'delivery_order_expense_value' => '40.00',
                'delivery_order_expense_kg' => '0.00',
                'delivery_order_expense_day' => 0,
                'delivery_order_expense_total' => '140.00',
                'setting_expense_id' => 5,
                'delivery_order_id' => 1,
                'delivery_order_expense_created' => '2022-07-20 18:14:07',
                'delivery_order_expense_updated' => '2022-07-20 18:14:07',
            ),
            2 => 
            array (
                'delivery_order_expense_id' => 8,
                'delivery_order_expense_value' => '50.00',
                'delivery_order_expense_kg' => '0.00',
                'delivery_order_expense_day' => 0,
                'delivery_order_expense_total' => '50.00',
                'setting_expense_id' => 18,
                'delivery_order_id' => 2054,
                'delivery_order_expense_created' => '2022-07-20 18:13:43',
                'delivery_order_expense_updated' => '2022-07-20 18:13:43',
            ),
            3 => 
            array (
                'delivery_order_expense_id' => 9,
                'delivery_order_expense_value' => '40.00',
                'delivery_order_expense_kg' => '0.00',
                'delivery_order_expense_day' => 0,
                'delivery_order_expense_total' => '40.00',
                'setting_expense_id' => 14,
                'delivery_order_id' => 2054,
                'delivery_order_expense_created' => '2022-07-20 18:14:07',
                'delivery_order_expense_updated' => '2022-07-20 18:14:07',
            ),
        ));
        
        
    }
}