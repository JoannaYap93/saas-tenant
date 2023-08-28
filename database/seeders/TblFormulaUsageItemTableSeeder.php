<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblFormulaUsageItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_formula_usage_item')->delete();
        
        \DB::table('tbl_formula_usage_item')->insert(array (
            0 => 
            array (
                'formula_usage_item_id' => 12,
                'formula_usage_id' => 8,
                'raw_material_id' => 49,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '50.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '2500.00',
                'formula_usage_item_created' => '2022-07-20 02:05:33',
                'formula_usage_item_updated' => '2022-07-20 02:05:33',
                'formula_usage_item_unit_price' => '0.00',
                'formula_usage_item_total_price' => '0.00',
            ),
            1 => 
            array (
                'formula_usage_item_id' => 13,
                'formula_usage_id' => 9,
                'raw_material_id' => 49,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '50.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '2500.00',
                'formula_usage_item_created' => '2022-07-20 02:05:33',
                'formula_usage_item_updated' => '2022-07-20 02:05:33',
                'formula_usage_item_unit_price' => '0.00',
                'formula_usage_item_total_price' => '0.00',
            ),
            2 => 
            array (
                'formula_usage_item_id' => 14,
                'formula_usage_id' => 10,
                'raw_material_id' => 49,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '50.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '2500.00',
                'formula_usage_item_created' => '2022-07-20 02:05:33',
                'formula_usage_item_updated' => '2022-07-20 02:05:33',
                'formula_usage_item_unit_price' => '0.00',
                'formula_usage_item_total_price' => '0.00',
            ),
            3 => 
            array (
                'formula_usage_item_id' => 15,
                'formula_usage_id' => 11,
                'raw_material_id' => 49,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '50.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '2500.00',
                'formula_usage_item_created' => '2022-07-20 02:05:33',
                'formula_usage_item_updated' => '2022-07-20 02:05:33',
                'formula_usage_item_unit_price' => '33.35',
                'formula_usage_item_total_price' => '1667.50',
            ),
            4 => 
            array (
                'formula_usage_item_id' => 16,
                'formula_usage_id' => 12,
                'raw_material_id' => 50,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '2.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '100.00',
                'formula_usage_item_created' => '2022-07-20 02:09:29',
                'formula_usage_item_updated' => '2022-07-20 02:09:29',
                'formula_usage_item_unit_price' => '50.00',
                'formula_usage_item_total_price' => '100.00',
            ),
            5 => 
            array (
                'formula_usage_item_id' => 17,
                'formula_usage_id' => 16,
                'raw_material_id' => 49,
                'formula_usage_item_qty' => 50,
                'formula_usage_item_value' => '5.00',
                'formula_usage_item_rounding' => '0.00',
                'formula_usage_item_total' => '250.00',
                'formula_usage_item_created' => '2022-07-20 02:28:16',
                'formula_usage_item_updated' => '2022-07-20 02:28:16',
                'formula_usage_item_unit_price' => '33.35',
                'formula_usage_item_total_price' => '166.75',
            ),
        ));
        
        
    }
}