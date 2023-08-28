<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierDeliveryOrderTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier_delivery_order')->delete();
        
        \DB::table('tbl_supplier_delivery_order')->insert(array (
            0 => 
            array (
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_no' => 'HXB IV/0011/22',
                'supplier_delivery_order_running_no' => 'SDO/ER/NORA/22080001',
                'supplier_delivery_order_subtotal' => '47090.20',
                'supplier_delivery_order_discount' => '15.20',
                'supplier_delivery_order_total' => '47075.00',
                'supplier_delivery_order_tax' => '0.00',
                'supplier_delivery_order_grandtotal' => '47075.00',
                'supplier_delivery_order_status' => 'completed',
                'supplier_delivery_order_date' => '2022-01-31',
                'supplier_id' => 2,
                'company_id' => 2,
                'user_id' => 36,
                'supplier_delivery_order_created' => '2022-08-24 18:07:26',
                'supplier_delivery_order_updated' => '2022-10-25 15:18:36',
            ),
            1 => 
            array (
                'supplier_delivery_order_id' => 2,
                'supplier_delivery_order_no' => 'APR2022CLAIM-ZAICHAI',
                'supplier_delivery_order_running_no' => 'SDO/ER/NORA/22080002',
                'supplier_delivery_order_subtotal' => '340.00',
                'supplier_delivery_order_discount' => '0.00',
                'supplier_delivery_order_total' => '340.00',
                'supplier_delivery_order_tax' => '0.00',
                'supplier_delivery_order_grandtotal' => '340.00',
                'supplier_delivery_order_status' => 'completed',
                'supplier_delivery_order_date' => '2022-04-30',
                'supplier_id' => 3,
                'company_id' => 2,
                'user_id' => 36,
                'supplier_delivery_order_created' => '2022-08-25 15:59:36',
                'supplier_delivery_order_updated' => '2022-08-25 16:08:28',
            ),
            2 => 
            array (
                'supplier_delivery_order_id' => 3,
                'supplier_delivery_order_no' => 'APR2022CLAIM-ZAICHAI',
                'supplier_delivery_order_running_no' => 'SDO/ER/NORA/22080003',
                'supplier_delivery_order_subtotal' => '1300.00',
                'supplier_delivery_order_discount' => '0.00',
                'supplier_delivery_order_total' => '1300.00',
                'supplier_delivery_order_tax' => '0.00',
                'supplier_delivery_order_grandtotal' => '1300.00',
                'supplier_delivery_order_status' => 'completed',
                'supplier_delivery_order_date' => '2022-04-30',
                'supplier_id' => 3,
                'company_id' => 2,
                'user_id' => 36,
                'supplier_delivery_order_created' => '2022-08-25 16:09:21',
                'supplier_delivery_order_updated' => '2022-08-25 16:09:22',
            ),
            3 => 
            array (
                'supplier_delivery_order_id' => 4,
                'supplier_delivery_order_no' => '19800',
                'supplier_delivery_order_running_no' => 'SDO/ER/NORA/22080004',
                'supplier_delivery_order_subtotal' => '5460.00',
                'supplier_delivery_order_discount' => '0.00',
                'supplier_delivery_order_total' => '5460.00',
                'supplier_delivery_order_tax' => '0.00',
                'supplier_delivery_order_grandtotal' => '5460.00',
                'supplier_delivery_order_status' => 'completed',
                'supplier_delivery_order_date' => '2022-05-11',
                'supplier_id' => 4,
                'company_id' => 2,
                'user_id' => 36,
                'supplier_delivery_order_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_updated' => '2022-08-26 16:47:16',
            ),
            4 => 
            array (
                'supplier_delivery_order_id' => 5,
                'supplier_delivery_order_no' => 'MAY22CLAIM-ZAICHAI',
                'supplier_delivery_order_running_no' => 'SDO/ER/NORA/22080005',
                'supplier_delivery_order_subtotal' => '80.00',
                'supplier_delivery_order_discount' => '0.00',
                'supplier_delivery_order_total' => '80.00',
                'supplier_delivery_order_tax' => '0.00',
                'supplier_delivery_order_grandtotal' => '80.00',
                'supplier_delivery_order_status' => 'completed',
                'supplier_delivery_order_date' => '2022-05-25',
                'supplier_id' => 3,
                'company_id' => 2,
                'user_id' => 36,
                'supplier_delivery_order_created' => '2022-08-29 09:51:53',
                'supplier_delivery_order_updated' => '2022-08-29 09:51:53',
            ),
        ));
        
        
    }
}