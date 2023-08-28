<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblRawMaterialTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_raw_material')->delete();
        
        \DB::table('tbl_raw_material')->insert(array (
            0 => 
            array (
                'raw_material_id' => 49,
                'raw_material_name' => '{"en":"Platinum Plus","cn":"Platinum Plus"}',
                'raw_material_category_id' => 1,
                'raw_material_created' => '2022-07-20 01:13:28',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            1 => 
            array (
                'raw_material_id' => 50,
                'raw_material_name' => '{"en":"Gold Plus","cn":"Gold Plus"}',
                'raw_material_category_id' => 1,
                'raw_material_created' => '2022-07-20 01:13:45',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            2 => 
            array (
                'raw_material_id' => 51,
                'raw_material_name' => '{"en":"Rustica Plus","cn":"Rustica Plus"}',
                'raw_material_category_id' => 1,
                'raw_material_created' => '2022-07-20 01:14:00',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            3 => 
            array (
                'raw_material_id' => 52,
                'raw_material_name' => '{"en":"Yaraliva Nitrabor","cn":"Yaraliva Nitrabor"}',
                'raw_material_category_id' => 1,
                'raw_material_created' => '2022-07-20 01:14:28',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            4 => 
            array (
                'raw_material_id' => 53,
                'raw_material_name' => '{"en":"Yaramila","cn":"Yaramila"}',
                'raw_material_category_id' => 1,
                'raw_material_created' => '2022-07-20 01:14:45',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            5 => 
            array (
                'raw_material_id' => 54,
                'raw_material_name' => '{"en":"OM - Organic Fertilizer","cn":"OM - Organic Fertilizer"}',
                'raw_material_category_id' => 4,
                'raw_material_created' => '2022-07-20 01:15:09',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            6 => 
            array (
                'raw_material_id' => 55,
                'raw_material_name' => '{"en":"A-Cal Mix","cn":"A-Cal Mix"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:15:31',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'bottle',
                'raw_material_value_unit' => 'litre',
            ),
            7 => 
            array (
                'raw_material_id' => 56,
                'raw_material_name' => '{"en":"Alika ZC","cn":"Alika ZC"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:15:55',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'bottle',
                'raw_material_value_unit' => 'litre',
            ),
            8 => 
            array (
                'raw_material_id' => 57,
                'raw_material_name' => '{"en":"AminoMar","cn":"AminoMar"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:16:10',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'bottle',
                'raw_material_value_unit' => 'litre',
            ),
            9 => 
            array (
                'raw_material_id' => 58,
                'raw_material_name' => '{"en":"Ancob MX80","cn":"Ancob MX80"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:17:05',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            10 => 
            array (
                'raw_material_id' => 59,
                'raw_material_name' => '{"en":"Ancozeb M45","cn":"Ancozeb M45"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:17:27',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
            11 => 
            array (
                'raw_material_id' => 60,
                'raw_material_name' => '{"en":"Antrocol 70WP","cn":"Antrocol 70WP"}',
                'raw_material_category_id' => 2,
                'raw_material_created' => '2022-07-20 01:18:38',
                'raw_material_updated' => '2022-08-22 18:49:08',
                'raw_material_status' => 'active',
                'raw_material_quantity_unit' => 'pack',
                'raw_material_value_unit' => 'kg',
            ),
        ));
        
        
    }
}