<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandTreeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land_tree')->delete();
        
        \DB::table('tbl_company_land_tree')->insert(array (
            0 => 
            array (
                'company_land_tree_id' => 1,
                'company_land_id' => 9,
                'company_land_zone_id' => 1,
                'company_land_tree_no' => '1',
                'product_id' => 1,
                'company_land_tree_circumference' => 16.0,
                'company_land_tree_age' => NULL,
                'company_land_tree_year' => 1992,
                'is_sick' => 0,
                'is_bear_fruit' => 1,
                'company_land_tree_status' => 'alive',
                'company_pnl_sub_item_code' => 'A10',
            ),
            1 => 
            array (
                'company_land_tree_id' => 2,
                'company_land_id' => 13,
                'company_land_zone_id' => 1,
                'company_land_tree_no' => '2',
                'product_id' => 1,
                'company_land_tree_circumference' => 17.0,
                'company_land_tree_age' => NULL,
                'company_land_tree_year' => 1992,
                'is_sick' => 0,
                'is_bear_fruit' => 1,
                'company_land_tree_status' => 'alive',
                'company_pnl_sub_item_code' => 'A10',
            ),
            2 => 
            array (
                'company_land_tree_id' => 3,
                'company_land_id' => 12,
                'company_land_zone_id' => 2,
                'company_land_tree_no' => '1',
                'product_id' => 1,
                'company_land_tree_circumference' => 23.0,
                'company_land_tree_age' => NULL,
                'company_land_tree_year' => 1992,
                'is_sick' => 0,
                'is_bear_fruit' => 1,
                'company_land_tree_status' => 'alive',
                'company_pnl_sub_item_code' => 'A10',
            ),
        ));
        
        
    }
}