<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandTreeLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_tree_log')->delete();
        
        \DB::table('tbl_company_land_tree_log')->insert(array (
            0 => 
            array (
                'company_land_tree_log_id' => 1,
                'company_land_tree_id' => 1,
                'company_land_tree_action_id' => 5,
                'company_land_tree_log_description' => 'Initial import.',
                'company_land_tree_log_date' => '2022-07-20',
                'company_land_tree_log_created' => '2022-07-20 01:44:35',
                'user_id' => 1,
                'company_land_tree_log_value' => NULL,
            ),
            1 => 
            array (
                'company_land_tree_log_id' => 2,
                'company_land_tree_id' => 1,
                'company_land_tree_action_id' => 3,
                'company_land_tree_log_description' => 'TEST',
                'company_land_tree_log_date' => '2022-08-22',
                'company_land_tree_log_created' => '2022-08-22 19:04:17',
                'user_id' => 1,
                'company_land_tree_log_value' => '300.00',
            ),
            2 => 
            array (
                'company_land_tree_log_id' => 3,
                'company_land_tree_id' => 1,
                'company_land_tree_action_id' => 5,
                'company_land_tree_log_description' => 'Updated tree from import',
                'company_land_tree_log_date' => '2022-08-22',
                'company_land_tree_log_created' => '2022-08-22 19:05:03',
                'user_id' => 1,
                'company_land_tree_log_value' => NULL,
            ),
            3 => 
            array (
                'company_land_tree_log_id' => 4,
                'company_land_tree_id' => 1,
                'company_land_tree_action_id' => 3,
                'company_land_tree_log_description' => 'Vitamin C',
                'company_land_tree_log_date' => '2023-08-22',
                'company_land_tree_log_created' => '2023-08-22 19:19:57',
                'user_id' => 1,
                'company_land_tree_log_value' => '40.00',
            ),
            4 => 
            array (
                'company_land_tree_log_id' => 5,
                'company_land_tree_id' => 2,
                'company_land_tree_action_id' => 3,
                'company_land_tree_log_description' => 'Vitamin C',
                'company_land_tree_log_date' => '2023-07-22',
                'company_land_tree_log_created' => '2023-08-22 19:21:23',
                'user_id' => 1,
                'company_land_tree_log_value' => '40.00',
            ),
            5 => 
            array (
                'company_land_tree_log_id' => 6,
                'company_land_tree_id' => 3,
                'company_land_tree_action_id' => 3,
                'company_land_tree_log_description' => 'Vitamin C',
                'company_land_tree_log_date' => '2023-06-22',
                'company_land_tree_log_created' => '2023-08-22 19:21:27',
                'user_id' => 1,
                'company_land_tree_log_value' => '40.00',
            ),
        ));
        
        
    }
}