<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyExpenseWorkerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_expense_worker')->delete();
        
        \DB::table('tbl_company_expense_worker')->insert(array (
            0 => 
            array (
                'company_expense_worker_id' => 1,
                'worker_id' => 1,
                'company_expense_id' => 2,
                'company_expense_worker_detail' => '{"task": [{"qty": 0, "expense_id": 11, "expense_total": 250, "expense_value": 50, "setting_expense_overwrite_commission": 0}], "type": "Daily", "status": 2, "timing": "AM"}',
                'company_expense_worker_total' => '250.00',
                'company_expense_worker_created' => '2022-07-20 02:12:12',
                'company_expense_worker_updated' => '2022-07-20 02:12:12',
            ),
            1 => 
            array (
                'company_expense_worker_id' => 2,
                'worker_id' => 2,
                'company_expense_id' => 2,
                'company_expense_worker_detail' => '{"task": [], "type": "Daily", "status": 4, "timing": ""}',
                'company_expense_worker_total' => '0.00',
                'company_expense_worker_created' => '2022-07-20 02:12:12',
                'company_expense_worker_updated' => '2022-07-20 02:12:12',
            ),
            2 => 
            array (
                'company_expense_worker_id' => 3,
                'worker_id' => 3,
                'company_expense_id' => 2,
                'company_expense_worker_detail' => '{"task": [{"qty": 0, "expense_id": 11, "expense_total": 400, "expense_value": 50, "setting_expense_overwrite_commission": 0}], "type": "Daily", "status": 1, "timing": ""}',
                'company_expense_worker_total' => '400.00',
                'company_expense_worker_created' => '2022-07-20 02:12:12',
                'company_expense_worker_updated' => '2022-07-20 02:12:12',
            ),
        ));
        
        
    }
}