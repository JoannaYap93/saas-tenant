<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingProductSizeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_product_size')->delete();
        
        \DB::table('tbl_setting_product_size')->insert(array (
            0 => 
            array (
                'setting_product_size_id' => 1,
                'setting_product_size_name' => 'AA',
            ),
            1 => 
            array (
                'setting_product_size_id' => 2,
                'setting_product_size_name' => 'A',
            ),
            2 => 
            array (
                'setting_product_size_id' => 3,
                'setting_product_size_name' => 'AB',
            ),
            3 => 
            array (
                'setting_product_size_id' => 4,
                'setting_product_size_name' => 'B',
            ),
            4 => 
            array (
                'setting_product_size_id' => 5,
                'setting_product_size_name' => 'BB',
            ),
            5 => 
            array (
                'setting_product_size_id' => 6,
                'setting_product_size_name' => 'BC',
            ),
            6 => 
            array (
                'setting_product_size_id' => 7,
                'setting_product_size_name' => 'C',
            ),
            7 => 
            array (
                'setting_product_size_id' => 8,
                'setting_product_size_name' => 'D',
            ),
            8 => 
            array (
                'setting_product_size_id' => 9,
                'setting_product_size_name' => 'C-',
            ),
            9 => 
            array (
                'setting_product_size_id' => 10,
                'setting_product_size_name' => 'Standard',
            ),
            10 => 
            array (
                'setting_product_size_id' => 11,
                'setting_product_size_name' => 'CC',
            ),
        ));
        
        
    }
}