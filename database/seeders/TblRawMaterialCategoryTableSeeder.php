<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblRawMaterialCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_raw_material_category')->delete();
        
        \DB::table('tbl_raw_material_category')->insert(array (
            0 => 
            array (
                'raw_material_category_id' => 1,
                'raw_material_category_name' => '{"en":"Fertilize","cn":"Fertilize"}',
            ),
            1 => 
            array (
                'raw_material_category_id' => 2,
                'raw_material_category_name' => '{"en":"Pesticide","cn":"Pesticide"}',
            ),
            2 => 
            array (
                'raw_material_category_id' => 3,
                'raw_material_category_name' => '{"en":"Biodynamics","cn":"Biodynamics"}',
            ),
            3 => 
            array (
                'raw_material_category_id' => 4,
                'raw_material_category_name' => '{"en":"Organic Fertilizer","cn":"Organic Fertilizer"}',
            ),
        ));
        
        
    }
}