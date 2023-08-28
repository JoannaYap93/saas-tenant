<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncFormulaUsageTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_formula_usage')->delete();
        
        \DB::table('tbl_sync_formula_usage')->insert(array (
            0 => 
            array (
                'sync_formula_usage_id' => 1,
                'setting_formula_id' => 51,
                'user_id' => 1,
                'company_id' => 1,
                'company_land_id' => 1,
                'company_land_zone_id' => 1,
                'sync_formula_usage_value' => '0.00',
                'sync_formula_usage_created' => '2022-07-20 02:09:29',
                'sync_formula_usage_updated' => '2022-07-20 02:09:29',
                'sync_formula_usage_status' => 'completed',
                'sync_formula_usage_type' => 'man',
                'formula_usage_id' => 12,
                'sync_id' => 1987,
                'sync_formula_usage_date' => '2022-07-20',
                'is_executed' => 1,
            ),
        ));
        
        
    }
}