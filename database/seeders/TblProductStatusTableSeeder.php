<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblProductStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_product_status')->delete();
        
        \DB::table('tbl_product_status')->insert(array (
            0 => 
            array (
                'product_status_id' => 1,
                'product_status_name' => 'Draft',
            ),
            1 => 
            array (
                'product_status_id' => 2,
                'product_status_name' => 'Publish',
            ),
            2 => 
            array (
                'product_status_id' => 3,
                'product_status_name' => 'Pending',
            ),
        ));
        
        
    }
}