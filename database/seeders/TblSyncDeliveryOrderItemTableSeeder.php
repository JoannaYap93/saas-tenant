<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncDeliveryOrderItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_delivery_order_item')->delete();
        
        \DB::table('tbl_sync_delivery_order_item')->insert(array (
            0 => 
            array (
                'sync_delivery_order_item_id' => 1,
                'sync_delivery_order_id' => 1,
                'product_id' => 1,
                'setting_product_size_id' => 2,
                'sync_delivery_order_item_quantity' => '50.0000',
                'sync_delivery_order_item_created' => '2022-07-20 02:12:41',
                'sync_delivery_order_item_updated' => '2022-07-20 02:12:41',
                'sync_delivery_order_item_collect_no' => '555',
                'delivery_order_item_id' => 1,
                'no_collect_code' => 0,
            ),
            1 => 
            array (
                'sync_delivery_order_item_id' => 2,
                'sync_delivery_order_id' => 1,
                'product_id' => 2,
                'setting_product_size_id' => 7,
                'sync_delivery_order_item_quantity' => '222.0000',
                'sync_delivery_order_item_created' => '2022-07-20 02:12:56',
                'sync_delivery_order_item_updated' => '2022-07-20 02:12:56',
                'sync_delivery_order_item_collect_no' => NULL,
                'delivery_order_item_id' => 2,
                'no_collect_code' => 0,
            ),
        ));
        
        
    }
}