<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyExpenseLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_expense_log')->delete();
        
        \DB::table('tbl_company_expense_log')->insert(array (
            0 => 
            array (
                'company_expense_log_id' => 1,
                'company_expense_id' => 1,
                'company_expense_log_created' => '2023-04-07 11:08:36',
                'company_expense_log_description' => 'Expenses Added By Webby',
                'user_id' => 1,
            ),
            1 => 
            array (
                'company_expense_log_id' => 2,
                'company_expense_id' => 2,
                'company_expense_log_created' => '2023-04-07 11:10:14',
                'company_expense_log_description' => 'Expenses Added By Webby',
                'user_id' => 1,
            ),
            2 => 
            array (
                'company_expense_log_id' => 3,
                'company_expense_id' => 3,
                'company_expense_log_created' => '2023-04-07 11:10:42',
                'company_expense_log_description' => 'Expenses Added By Webby',
                'user_id' => 1,
            ),
        ));
        
        
    }
}