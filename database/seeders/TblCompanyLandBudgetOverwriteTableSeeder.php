<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandBudgetOverwriteTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_budget_overwrite')->delete();
        
        \DB::table('tbl_company_land_budget_overwrite')->insert(array (
            0 => 
            array (
                'company_land_budget_overwrite_id' => 16,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'formula',
                'company_land_budget_overwrite_value' => '102.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 1,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            1 => 
            array (
                'company_land_budget_overwrite_id' => 17,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'formula',
                'company_land_budget_overwrite_value' => '0.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 3,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            2 => 
            array (
                'company_land_budget_overwrite_id' => 18,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'formula',
                'company_land_budget_overwrite_value' => '72.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 4,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            3 => 
            array (
                'company_land_budget_overwrite_id' => 19,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'formula',
                'company_land_budget_overwrite_value' => '46.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 5,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            4 => 
            array (
                'company_land_budget_overwrite_id' => 20,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'expense',
                'company_land_budget_overwrite_value' => '132.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 2,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            5 => 
            array (
                'company_land_budget_overwrite_id' => 21,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'expense',
                'company_land_budget_overwrite_value' => '18.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 3,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
            6 => 
            array (
                'company_land_budget_overwrite_id' => 22,
                'company_land_id' => 3,
                'company_land_budget_overwrite_type' => 'expense',
                'company_land_budget_overwrite_value' => '40.00',
                'company_id' => 2,
                'company_land_budget_overwrite_type_id' => 4,
                'company_land_budget_overwrite_created' => '2023-03-18 23:00:28',
                'company_land_budget_overwrite_updated' => '2023-03-18 23:00:28',
                'user_id' => 3,
            ),
        ));
        
        
    }
}