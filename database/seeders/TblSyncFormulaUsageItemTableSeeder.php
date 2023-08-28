<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncFormulaUsageItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_formula_usage_item')->delete();
        
        \DB::table('tbl_sync_formula_usage_item')->insert(array (
            0 => 
            array (
                'sync_formula_usage_item_id' => 1,
                'sync_formula_usage_id' => 1,
                'raw_material_id' => 50,
                'sync_formula_usage_item_qty' => 50,
                'sync_formula_usage_item_value' => '2.00',
                'sync_formula_usage_item_rounding' => '0.00',
                'sync_formula_usage_item_total' => '100.00',
                'sync_formula_usage_item_created' => '2022-07-20 02:09:29',
                'sync_formula_usage_item_updated' => '2022-07-20 02:09:29',
                'formula_usage_item_id' => 16,
            ),
        ));
        
        
    }
}