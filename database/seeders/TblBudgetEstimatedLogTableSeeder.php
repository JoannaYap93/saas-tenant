<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblBudgetEstimatedLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_budget_estimated_log')->delete();
        
        \DB::table('tbl_budget_estimated_log')->insert(array (
            0 => 
            array (
                'budget_estimated_log_id' => 1,
                'budget_estimated_id' => 2,
                'budget_estimate_log_created' => '2023-08-22 18:38:36',
                'user_id' => 1,
                'budget_estimated_log_action' => 'Budget Estimate Added by dwa',
                'budget_estimated_log_remark' => NULL,
            ),
        ));
        
        
    }
}