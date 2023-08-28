<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPayrollTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payroll')->delete();
        
        \DB::table('tbl_payroll')->insert(array (
            0 => 
            array (
                'payroll_id' => 5,
                'company_id' => 2,
                'payroll_month' => 10,
                'payroll_year' => '2022',
                'payroll_total_amount' => '38560.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '1837.27',
                'payroll_total_user_item_employer' => '1744.60',
                'payroll_grandtotal' => '40304.60',
                'payroll_total_paid_out' => '38412.73',
                'payroll_status' => 'deleted',
                'payroll_created' => '2022-11-03 15:24:15',
                'payroll_updated' => '2022-11-17 12:29:16',
            ),
            1 => 
            array (
                'payroll_id' => 12,
                'company_id' => 1,
                'payroll_month' => 11,
                'payroll_year' => '2022',
                'payroll_total_amount' => '17240.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '1045.00',
                'payroll_total_user_item_employer' => '850.00',
                'payroll_grandtotal' => '18090.00',
                'payroll_total_paid_out' => '16195.00',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-17 11:25:35',
                'payroll_updated' => '2022-11-17 11:51:14',
            ),
            2 => 
            array (
                'payroll_id' => 15,
                'company_id' => 2,
                'payroll_month' => 10,
                'payroll_year' => '2022',
                'payroll_total_amount' => '38500.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '3057.02',
                'payroll_total_user_item_employer' => '4200.95',
                'payroll_grandtotal' => '42700.95',
                'payroll_total_paid_out' => '37542.98',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-17 21:53:26',
                'payroll_updated' => '2022-11-18 16:07:30',
            ),
            3 => 
            array (
                'payroll_id' => 17,
                'company_id' => 2,
                'payroll_month' => 9,
                'payroll_year' => '2022',
                'payroll_total_amount' => '39235.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '3867.02',
                'payroll_total_user_item_employer' => '4038.65',
                'payroll_grandtotal' => '43273.65',
                'payroll_total_paid_out' => '37242.98',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-18 16:42:40',
                'payroll_updated' => '2022-11-18 17:15:02',
            ),
            4 => 
            array (
                'payroll_id' => 18,
                'company_id' => 2,
                'payroll_month' => 8,
                'payroll_year' => '2022',
                'payroll_total_amount' => '36737.50',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '3509.62',
                'payroll_total_user_item_employer' => '4057.90',
                'payroll_grandtotal' => '40795.40',
                'payroll_total_paid_out' => '35209.13',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-18 17:20:50',
                'payroll_updated' => '2022-11-18 21:40:19',
            ),
            5 => 
            array (
                'payroll_id' => 19,
                'company_id' => 2,
                'payroll_month' => 7,
                'payroll_year' => '2022',
                'payroll_total_amount' => '30600.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '4018.47',
                'payroll_total_user_item_employer' => '4621.00',
                'payroll_grandtotal' => '35221.00',
                'payroll_total_paid_out' => '29786.53',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-18 21:46:46',
                'payroll_updated' => '2022-11-18 21:59:47',
            ),
            6 => 
            array (
                'payroll_id' => 20,
                'company_id' => 2,
                'payroll_month' => 6,
                'payroll_year' => '2022',
                'payroll_total_amount' => '28720.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '1568.42',
                'payroll_total_user_item_employer' => '2304.30',
                'payroll_grandtotal' => '31024.30',
                'payroll_total_paid_out' => '28151.58',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-18 22:09:28',
                'payroll_updated' => '2022-11-18 22:19:53',
            ),
            7 => 
            array (
                'payroll_id' => 21,
                'company_id' => 2,
                'payroll_month' => 5,
                'payroll_year' => '2022',
                'payroll_total_amount' => '18550.00',
                'payroll_total_reward' => '0.00',
                'payroll_total_user_item_employee' => '908.30',
                'payroll_total_user_item_employer' => '2279.30',
                'payroll_grandtotal' => '20829.30',
                'payroll_total_paid_out' => '18701.70',
                'payroll_status' => 'in progress',
                'payroll_created' => '2022-11-18 22:26:25',
                'payroll_updated' => '2022-11-18 23:09:12',
            ),
        ));
        
        
    }
}