<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncCompanyExpenseWorkerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_company_expense_worker')->delete();
        
        \DB::table('tbl_sync_company_expense_worker')->insert(array (
            0 => 
            array (
                'sync_company_expense_worker_id' => 1,
                'worker_id' => 1,
                'sync_company_expense_id' => 2,
                'sync_company_expense_worker_detail' => '{"task": [{"qty": 0, "expense_id": 11, "expense_total": 250, "expense_value": 50, "setting_expense_overwrite_commission": 0}], "type": "Daily", "status": 2, "timing": "AM"}',
                'sync_company_expense_worker_total' => '250.00',
                'sync_company_expense_worker_created' => '2022-07-20 02:12:12',
                'sync_company_expense_worker_updated' => '2022-07-20 02:12:12',
                'company_expense_worker_id' => 1,
            ),
            1 => 
            array (
                'sync_company_expense_worker_id' => 2,
                'worker_id' => 2,
                'sync_company_expense_id' => 2,
                'sync_company_expense_worker_detail' => '{"task": [], "type": "Daily", "status": 4, "timing": ""}',
                'sync_company_expense_worker_total' => '0.00',
                'sync_company_expense_worker_created' => '2022-07-20 02:12:12',
                'sync_company_expense_worker_updated' => '2022-07-20 02:12:12',
                'company_expense_worker_id' => 2,
            ),
            2 => 
            array (
                'sync_company_expense_worker_id' => 3,
                'worker_id' => 3,
                'sync_company_expense_id' => 2,
                'sync_company_expense_worker_detail' => '{"task": [{"qty": 0, "expense_id": 11, "expense_total": 400, "expense_value": 50, "setting_expense_overwrite_commission": 0}], "type": "Daily", "status": 1, "timing": ""}',
                'sync_company_expense_worker_total' => '400.00',
                'sync_company_expense_worker_created' => '2022-07-20 02:12:12',
                'sync_company_expense_worker_updated' => '2022-07-20 02:12:12',
                'company_expense_worker_id' => 3,
            ),
        ));
        
        
    }
}