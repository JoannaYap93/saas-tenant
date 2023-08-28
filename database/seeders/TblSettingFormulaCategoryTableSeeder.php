<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingFormulaCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_formula_category')->delete();
        
        \DB::table('tbl_setting_formula_category')->insert(array (
            0 => 
            array (
                'setting_formula_category_id' => 1,
                'setting_formula_category_name' => '{"en":"Fertilize","cn":"Fertilize"}',
                'is_budget_limited' => 1,
                'setting_formula_category_budget' => '23.00',
            ),
            1 => 
            array (
                'setting_formula_category_id' => 2,
                'setting_formula_category_name' => '{"en":"Pesticide","cn":"Pesticide"}',
                'is_budget_limited' => 0,
                'setting_formula_category_budget' => NULL,
            ),
            2 => 
            array (
                'setting_formula_category_id' => 3,
                'setting_formula_category_name' => '{"en":"Biodynamics","cn":"Biodynamics"}',
                'is_budget_limited' => 1,
                'setting_formula_category_budget' => '12.00',
            ),
            3 => 
            array (
                'setting_formula_category_id' => 4,
                'setting_formula_category_name' => '{"en":"Organic Fertilizer","cn":"Organic Fertilizer"}',
                'is_budget_limited' => 1,
                'setting_formula_category_budget' => '38.00',
            ),
        ));
        
        
    }
}