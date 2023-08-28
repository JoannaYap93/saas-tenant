<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order_status')->delete();
        
        \DB::table('tbl_delivery_order_status')->insert(array (
            0 => 
            array (
                'delivery_order_status_id' => 1,
                'delivery_order_status_name' => 'Pending',
            ),
            1 => 
            array (
                'delivery_order_status_id' => 2,
                'delivery_order_status_name' => 'Approved',
            ),
            2 => 
            array (
                'delivery_order_status_id' => 3,
                'delivery_order_status_name' => 'Deleted',
            ),
            3 => 
            array (
                'delivery_order_status_id' => 4,
                'delivery_order_status_name' => 'Pending Verification',
            ),
            4 => 
            array (
                'delivery_order_status_id' => 5,
                'delivery_order_status_name' => 'Verified',
            ),
        ));
        
        
    }
}