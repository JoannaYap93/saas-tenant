<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblBudgetEstimatedTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_budget_estimated')->delete();
        
        \DB::table('tbl_budget_estimated')->insert(array (
            0 => 
            array (
                'budget_estimated_id' => 1,
                'budget_estimated_title' => 'Budget 2023',
                'budget_estimated_year' => 2023,
                'company_id' => 1,
                'budget_estimated_amount' => '0.00',
                'budget_estimated_created' => '2023-08-22 18:38:36',
                'budget_estimated_updated' => '2023-08-22 18:38:36',
                'is_deleted' => 0,
            ),
        ));
        
        
    }
}