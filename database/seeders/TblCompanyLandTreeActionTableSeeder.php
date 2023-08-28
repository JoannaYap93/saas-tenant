<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandTreeActionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_tree_action')->delete();
        
        \DB::table('tbl_company_land_tree_action')->insert(array (
            0 => 
            array (
                'company_land_tree_action_id' => 1,
                'company_land_tree_action_name' => 'Sick',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-05-23 16:31:54',
                'company_land_tree_action_updated' => '2022-05-23 16:31:54',
                'user_id' => 1,
                'is_value_required' => 0,
            ),
            1 => 
            array (
                'company_land_tree_action_id' => 2,
                'company_land_tree_action_name' => 'Recover',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-05-23 16:32:02',
                'company_land_tree_action_updated' => '2022-05-23 16:32:02',
                'user_id' => 1,
                'is_value_required' => 0,
            ),
            2 => 
            array (
                'company_land_tree_action_id' => 3,
                'company_land_tree_action_name' => 'Vitamin',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-05-23 16:32:25',
                'company_land_tree_action_updated' => '2022-05-23 16:32:25',
                'user_id' => 1,
                'is_value_required' => 1,
            ),
            3 => 
            array (
                'company_land_tree_action_id' => 4,
                'company_land_tree_action_name' => 'Calcium',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-05-23 16:32:41',
                'company_land_tree_action_updated' => '2022-05-23 16:32:41',
                'user_id' => 1,
                'is_value_required' => 1,
            ),
            4 => 
            array (
                'company_land_tree_action_id' => 5,
                'company_land_tree_action_name' => 'Import',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-05-23 16:32:41',
                'company_land_tree_action_updated' => '2022-05-23 16:32:41',
                'user_id' => 1,
                'is_value_required' => 0,
            ),
            5 => 
            array (
                'company_land_tree_action_id' => 6,
                'company_land_tree_action_name' => 'Update',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-07-23 12:52:10',
                'company_land_tree_action_updated' => '2022-07-23 12:52:10',
                'user_id' => 1,
                'is_value_required' => 0,
            ),
            6 => 
            array (
                'company_land_tree_action_id' => 7,
                'company_land_tree_action_name' => 'Dead',
                'company_id' => 0,
                'company_land_tree_action_created' => '2022-12-22 14:31:25',
                'company_land_tree_action_updated' => '2022-12-22 14:31:25',
                'user_id' => 1,
                'is_value_required' => 0,
            ),
        ));
        
        
    }
}