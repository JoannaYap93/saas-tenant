<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncDeliveryOrderLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_delivery_order_log')->delete();
        
        \DB::table('tbl_sync_delivery_order_log')->insert(array (
            0 => 
            array (
                'sync_delivery_order_log_id' => 1,
                'sync_delivery_order_id' => 1,
                'sync_delivery_order_log_created' => '2022-07-20 02:13:02',
                'sync_delivery_order_log_action' => 'Add Delivery Order',
                'sync_delivery_order_log_description' => 'Add Delivery Order Description',
                'user_id' => 1,
            ),
        ));
        
        
    }
}