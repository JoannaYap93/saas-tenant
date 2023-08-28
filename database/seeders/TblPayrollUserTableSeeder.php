<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPayrollUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payroll_user')->delete();
        
        \DB::table('tbl_payroll_user')->insert(array (
            0 => 
            array (
                'payroll_user_id' => 4,
                'payroll_id' => 5,
                'worker_id' => 3,
                'payroll_user_amount' => '1860.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '0.00',
                'payroll_user_item_employer' => '611.25',
                'payroll_user_total' => '2471.25',
                'payroll_user_paid_out' => '2471.25',
                'payroll_user_created' => '2022-11-03 16:43:29',
                'payroll_user_updated' => '2022-11-08 18:12:58',
            ),
            1 => 
            array (
                'payroll_user_id' => 24,
                'payroll_id' => 5,
                'worker_id' => 3,
                'payroll_user_amount' => '1860.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '0.00',
                'payroll_user_item_employer' => '611.25',
                'payroll_user_total' => '2471.25',
                'payroll_user_paid_out' => '2471.25',
                'payroll_user_created' => '2022-11-08 18:09:57',
                'payroll_user_updated' => '2022-11-08 18:09:57',
            ),
            2 => 
            array (
                'payroll_user_id' => 76,
                'payroll_id' => 12,
                'worker_id' => 1,
                'payroll_user_amount' => '1440.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '220.00',
                'payroll_user_item_employer' => '150.00',
                'payroll_user_total' => '1590.00',
                'payroll_user_paid_out' => '1220.00',
                'payroll_user_created' => '2022-11-17 11:26:57',
                'payroll_user_updated' => '2022-11-17 11:51:13',
            ),
            3 => 
            array (
                'payroll_user_id' => 77,
                'payroll_id' => 12,
                'worker_id' => 2,
                'payroll_user_amount' => '600.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '175.00',
                'payroll_user_item_employer' => '150.00',
                'payroll_user_total' => '750.00',
                'payroll_user_paid_out' => '425.00',
                'payroll_user_created' => '2022-11-17 11:26:58',
                'payroll_user_updated' => '2022-11-17 11:51:13',
            ),
            4 => 
            array (
                'payroll_user_id' => 155,
                'payroll_id' => 15,
                'worker_id' => 3,
                'payroll_user_amount' => '1800.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '0.00',
                'payroll_user_item_employer' => '671.25',
                'payroll_user_total' => '2471.25',
                'payroll_user_paid_out' => '2471.25',
                'payroll_user_created' => '2022-11-17 22:01:37',
                'payroll_user_updated' => '2022-11-18 16:07:29',
            ),
            5 => 
            array (
                'payroll_user_id' => 178,
                'payroll_id' => 17,
                'worker_id' => 3,
                'payroll_user_amount' => '1927.50',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '127.50',
                'payroll_user_item_employer' => '600.00',
                'payroll_user_total' => '2527.50',
                'payroll_user_paid_out' => '2400.00',
                'payroll_user_created' => '2022-11-18 16:48:02',
                'payroll_user_updated' => '2022-11-18 17:15:00',
            ),
            6 => 
            array (
                'payroll_user_id' => 203,
                'payroll_id' => 18,
                'worker_id' => 3,
                'payroll_user_amount' => '1942.50',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '82.50',
                'payroll_user_item_employer' => '633.75',
                'payroll_user_total' => '2576.25',
                'payroll_user_paid_out' => '2493.75',
                'payroll_user_created' => '2022-11-18 17:24:23',
                'payroll_user_updated' => '2022-11-18 21:40:18',
            ),
            7 => 
            array (
                'payroll_user_id' => 227,
                'payroll_id' => 19,
                'worker_id' => 3,
                'payroll_user_amount' => '1860.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '0.00',
                'payroll_user_item_employer' => '611.25',
                'payroll_user_total' => '2471.25',
                'payroll_user_paid_out' => '2471.25',
                'payroll_user_created' => '2022-11-18 21:50:26',
                'payroll_user_updated' => '2022-11-18 21:59:47',
            ),
            8 => 
            array (
                'payroll_user_id' => 247,
                'payroll_id' => 20,
                'worker_id' => 3,
                'payroll_user_amount' => '1740.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '60.00',
                'payroll_user_item_employer' => '200.00',
                'payroll_user_total' => '1940.00',
                'payroll_user_paid_out' => '1880.00',
                'payroll_user_created' => '2022-11-18 22:12:43',
                'payroll_user_updated' => '2022-11-18 22:19:52',
            ),
            9 => 
            array (
                'payroll_user_id' => 263,
                'payroll_id' => 21,
                'worker_id' => 3,
                'payroll_user_amount' => '1680.00',
                'payroll_user_reward' => '0.00',
                'payroll_user_item_employee' => '0.00',
                'payroll_user_item_employer' => '260.00',
                'payroll_user_total' => '1940.00',
                'payroll_user_paid_out' => '1940.00',
                'payroll_user_created' => '2022-11-18 22:57:11',
                'payroll_user_updated' => '2022-11-18 23:09:11',
            ),
        ));
        
        
    }
}