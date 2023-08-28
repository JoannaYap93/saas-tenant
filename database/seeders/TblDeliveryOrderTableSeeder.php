<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order')->delete();
        
        \DB::table('tbl_delivery_order')->insert(array (
            0 => 
            array (
                'delivery_order_id' => 1,
                'delivery_order_no' => 'DO/webby/ZW/22070002',
                'delivery_order_created' => '2022-07-20 02:13:02',
                'delivery_order_updated' => '2022-07-25 16:37:13',
                'customer_id' => 1,
                'customer_name' => 'Felix',
                'customer_mobile_no' => '60193222121',
                'customer_ic' => '931102566029',
                'delivery_order_total_quantity' => '272.0000',
                'sync_id' => 1,
                'delivery_order_status_id' => 2,
                'company_id' => 1,
                'company_land_id' => 1,
                'invoice_id' => 1,
                'delivery_order_type_id' => 2,
                'user_id' => 1,
                'warehouse_id' => 1,
                'delivery_order_remark' => NULL,
            ),
            1 => 
            array (
                'delivery_order_id' => 2054,
                'delivery_order_no' => 'DO/DFN/FM01/23080026',
                'delivery_order_created' => '2023-08-24 16:29:26',
                'delivery_order_updated' => '2023-08-24 16:56:58',
                'customer_id' => 2,
                'customer_name' => 'Hu Boon ',
                'customer_mobile_no' => '60198273648',
                'customer_ic' => '861117566413',
                'delivery_order_total_quantity' => '56.8500',
                'sync_id' => NULL,
                'delivery_order_status_id' => 5,
                'company_id' => 6,
                'company_land_id' => 2,
                'invoice_id' => 7347,
                'delivery_order_type_id' => 2,
                'user_id' => 2,
                'warehouse_id' => NULL,
                'delivery_order_remark' => NULL,
            ),
            2 => 
            array (
                'delivery_order_id' => 10517,
                'delivery_order_no' => 'DO/HJF/HYH/23080005',
                'delivery_order_created' => '2023-08-24 15:10:21',
                'delivery_order_updated' => '2023-08-24 15:13:12',
                'customer_id' => 3,
                'customer_name' => 'Wei Yuan',
                'customer_mobile_no' => '60128374983',
                'customer_ic' => '890809566067',
                'delivery_order_total_quantity' => '6.0500',
                'sync_id' => NULL,
                'delivery_order_status_id' => 5,
                'company_id' => 7,
                'company_land_id' => 3,
                'invoice_id' => 7344,
                'delivery_order_type_id' => 1,
                'user_id' => 3,
                'warehouse_id' => NULL,
                'delivery_order_remark' => NULL,
            ),
            3 => 
            array (
                'delivery_order_id' => 10519,
                'delivery_order_no' => 'DO/CTF/CT/23080005',
                'delivery_order_created' => '2023-08-24 14:53:31',
                'delivery_order_updated' => '2023-08-24 14:54:04',
                'customer_id' => 3,
                'customer_name' => 'Foong Mei',
                'customer_mobile_no' => '60173849238',
                'customer_ic' => '621119085681',
                'delivery_order_total_quantity' => '46.4000',
                'sync_id' => NULL,
                'delivery_order_status_id' => 5,
                'company_id' => 8,
                'company_land_id' => 4,
                'invoice_id' => 7340,
                'delivery_order_type_id' => 1,
                'user_id' => 1,
                'warehouse_id' => NULL,
                'delivery_order_remark' => NULL,
            ),
            4 => 
            array (
                'delivery_order_id' => 10535,
                'delivery_order_no' => 'DO/DFN/FM01/23080025',
                'delivery_order_created' => '2023-08-23 21:04:32',
                'delivery_order_updated' => '2023-08-23 21:07:07',
                'customer_id' => 2,
                'customer_name' => 'Hu Boon ',
                'customer_mobile_no' => '60198273648',
                'customer_ic' => '861117566413',
                'delivery_order_total_quantity' => '48.9500',
                'sync_id' => NULL,
                'delivery_order_status_id' => 5,
                'company_id' => 6,
                'company_land_id' => 5,
                'invoice_id' => 7325,
                'delivery_order_type_id' => 1,
                'user_id' => 5,
                'warehouse_id' => NULL,
                'delivery_order_remark' => NULL,
            ),
            5 => 
            array (
                'delivery_order_id' => 10536,
                'delivery_order_no' => 'DO/HJF/HYH/23080004',
                'delivery_order_created' => '2023-08-23 16:49:26',
                'delivery_order_updated' => '2023-08-23 16:50:16',
                'customer_id' => 4,
                'customer_name' => 'Yee Hi',
                'customer_mobile_no' => '60188299283',
                'customer_ic' => '881209064329',
                'delivery_order_total_quantity' => '29.9000',
                'sync_id' => NULL,
                'delivery_order_status_id' => 5,
                'company_id' => 7,
                'company_land_id' => 6,
                'invoice_id' => 7321,
                'delivery_order_type_id' => 1,
                'user_id' => 1,
                'warehouse_id' => NULL,
                'delivery_order_remark' => NULL,
            ),
        ));
        
        
    }
}