<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order_type')->delete();
        
        \DB::table('tbl_delivery_order_type')->insert(array (
            0 => 
            array (
                'delivery_order_type_id' => 1,
                'delivery_order_type_name' => 'Standard',
            ),
            1 => 
            array (
                'delivery_order_type_id' => 2,
                'delivery_order_type_name' => 'Warehouse',
            ),
        ));
        
        
    }
}