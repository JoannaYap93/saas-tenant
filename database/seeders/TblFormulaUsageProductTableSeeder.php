<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblFormulaUsageProductTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_formula_usage_product')->delete();
        
        \DB::table('tbl_formula_usage_product')->insert(array (
            0 => 
            array (
                'formula_usage_product_id' => 12,
                'product_id' => 2,
                'formula_usage_id' => 11,
                'formula_usage_product_created' => '2022-07-20 02:05:33',
                'formula_usage_product_json' => '[{"company_pnl_sub_item_code": "A10", "formula_usage_product_quantity": 3, "formula_usage_product_value_per_tree": 50}]',
            ),
            1 => 
            array (
                'formula_usage_product_id' => 13,
                'product_id' => 2,
                'formula_usage_id' => 12,
                'formula_usage_product_created' => '2022-07-20 02:09:29',
                'formula_usage_product_json' => '[{"company_pnl_sub_item_code": "A10", "formula_usage_product_quantity": 3, "formula_usage_product_value_per_tree": 500}]',
            ),
            2 => 
            array (
                'formula_usage_product_id' => 14,
                'product_id' => 2,
                'formula_usage_id' => 16,
                'formula_usage_product_created' => '2022-07-20 02:28:16',
                'formula_usage_product_json' => '[{"company_pnl_sub_item_code": "A10", "formula_usage_product_quantity": 4, "formula_usage_product_value_per_tree": 500}]',
            ),
        ));
        
        
    }
}