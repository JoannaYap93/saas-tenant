<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncFormulaUsageProductTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_formula_usage_product')->delete();
        
        \DB::table('tbl_sync_formula_usage_product')->insert(array (
            0 => 
            array (
                'sync_formula_usage_product_id' => 1,
                'product_id' => 1,
                'sync_formula_usage_id' => 1,
                'sync_formula_usage_product_created' => '2022-07-20 02:09:29',
                'formula_usage_product_id' => 1,
                'sync_formula_usage_product_json' => '[{"company_pnl_sub_item_code": "A10", "formula_usage_product_quantity": 3, "formula_usage_product_value_per_tree": 500}]',
            ),
        ));
        
        
    }
}