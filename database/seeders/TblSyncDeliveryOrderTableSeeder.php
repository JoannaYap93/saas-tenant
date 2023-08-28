<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncDeliveryOrderTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync_delivery_order')->delete();
        
        \DB::table('tbl_sync_delivery_order')->insert(array (
            0 => 
            array (
                'sync_delivery_order_id' => 1,
                'sync_delivery_order_no' => 'DO/webby/ZW/22070002',
                'sync_delivery_order_total_quantity' => '272.0000',
                'customer_id' => '1',
                'customer_mobile_no' => '64646464',
                'customer_name' => 'Felix',
                'sync_delivery_order_created' => '2022-07-20 02:13:02',
                'sync_delivery_order_updated' => '2022-07-20 02:13:02',
                'sync_id' => 1,
                'sync_delivery_order_status_id' => 2,
                'is_executed' => 1,
                'customer_ic' => '646464949',
                'sync_delivery_order_date' => '2022-07-20 02:14:38',
                'sync_delivery_order_type_id' => 2,
                'company_land_id' => 1,
                'delivery_order_id' => 1,
                'warehouse_id' => NULL,
                'sync_delivery_order_remark' => NULL,
            ),
        ));
        
        
    }
}