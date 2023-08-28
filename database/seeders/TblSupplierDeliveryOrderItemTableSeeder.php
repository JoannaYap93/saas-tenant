<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierDeliveryOrderItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier_delivery_order_item')->delete();
        
        \DB::table('tbl_supplier_delivery_order_item')->insert(array (
            0 => 
            array (
                'supplier_delivery_order_item_id' => 1,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 54,
                'raw_material_company_usage_id' => 55,
                'supplier_delivery_order_item_qty' => 509,
                'supplier_delivery_order_item_value_per_qty' => '40.00',
                'supplier_delivery_order_item_price_per_qty' => '22.80',
                'supplier_delivery_order_item_disc' => '15.20',
                'supplier_delivery_order_item_created' => '2022-08-24 18:07:26',
                'supplier_delivery_order_item_updated' => '2022-10-25 15:18:30',
            ),
            1 => 
            array (
                'supplier_delivery_order_item_id' => 2,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 51,
                'raw_material_company_usage_id' => 56,
                'supplier_delivery_order_item_qty' => 67,
                'supplier_delivery_order_item_value_per_qty' => '50.00',
                'supplier_delivery_order_item_price_per_qty' => '188.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-24 18:07:26',
                'supplier_delivery_order_item_updated' => '2022-08-24 18:07:26',
            ),
            2 => 
            array (
                'supplier_delivery_order_item_id' => 3,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 56,
                'raw_material_company_usage_id' => 57,
                'supplier_delivery_order_item_qty' => 51,
                'supplier_delivery_order_item_value_per_qty' => '25.00',
                'supplier_delivery_order_item_price_per_qty' => '62.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-24 18:07:26',
                'supplier_delivery_order_item_updated' => '2022-08-24 18:07:26',
            ),
            3 => 
            array (
                'supplier_delivery_order_item_id' => 4,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 58,
                'raw_material_company_usage_id' => 58,
                'supplier_delivery_order_item_qty' => 3,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '56.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 11:30:16',
                'supplier_delivery_order_item_updated' => '2022-08-25 11:30:16',
            ),
            4 => 
            array (
                'supplier_delivery_order_item_id' => 7,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 56,
                'raw_material_company_usage_id' => 61,
                'supplier_delivery_order_item_qty' => 15,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '48.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 12:31:26',
                'supplier_delivery_order_item_updated' => '2022-08-25 12:31:26',
            ),
            5 => 
            array (
                'supplier_delivery_order_item_id' => 8,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 58,
                'raw_material_company_usage_id' => 62,
                'supplier_delivery_order_item_qty' => 35,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '69.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 12:31:26',
                'supplier_delivery_order_item_updated' => '2022-08-25 12:31:26',
            ),
            6 => 
            array (
                'supplier_delivery_order_item_id' => 9,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 55,
                'raw_material_company_usage_id' => 63,
                'supplier_delivery_order_item_qty' => 28,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '86.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 12:31:26',
                'supplier_delivery_order_item_updated' => '2022-08-25 12:31:26',
            ),
            7 => 
            array (
                'supplier_delivery_order_item_id' => 10,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 49,
                'raw_material_company_usage_id' => 64,
                'supplier_delivery_order_item_qty' => 8,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '70.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 12:31:26',
                'supplier_delivery_order_item_updated' => '2022-08-25 12:31:26',
            ),
            8 => 
            array (
                'supplier_delivery_order_item_id' => 11,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 49,
                'raw_material_company_usage_id' => 65,
                'supplier_delivery_order_item_qty' => 25,
                'supplier_delivery_order_item_value_per_qty' => '5.00',
                'supplier_delivery_order_item_price_per_qty' => '230.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 12:31:26',
                'supplier_delivery_order_item_updated' => '2022-08-25 12:31:26',
            ),
            9 => 
            array (
                'supplier_delivery_order_item_id' => 12,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 58,
                'raw_material_company_usage_id' => 66,
                'supplier_delivery_order_item_qty' => 6,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '44.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 15:43:36',
                'supplier_delivery_order_item_updated' => '2022-08-25 15:43:36',
            ),
            10 => 
            array (
                'supplier_delivery_order_item_id' => 13,
                'supplier_delivery_order_id' => 1,
                'raw_material_id' => 49,
                'raw_material_company_usage_id' => 67,
                'supplier_delivery_order_item_qty' => 122,
                'supplier_delivery_order_item_value_per_qty' => '0.50',
                'supplier_delivery_order_item_price_per_qty' => '61.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 15:43:36',
                'supplier_delivery_order_item_updated' => '2022-08-25 15:43:36',
            ),
            11 => 
            array (
                'supplier_delivery_order_item_id' => 14,
                'supplier_delivery_order_id' => 2,
                'raw_material_id' => 51,
                'raw_material_company_usage_id' => 68,
                'supplier_delivery_order_item_qty' => 2,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '170.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 15:59:36',
                'supplier_delivery_order_item_updated' => '2022-08-25 15:59:36',
            ),
            12 => 
            array (
                'supplier_delivery_order_item_id' => 16,
                'supplier_delivery_order_id' => 3,
                'raw_material_id' => 60,
                'raw_material_company_usage_id' => 70,
                'supplier_delivery_order_item_qty' => 5,
                'supplier_delivery_order_item_value_per_qty' => '5.00',
                'supplier_delivery_order_item_price_per_qty' => '260.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-25 16:09:22',
                'supplier_delivery_order_item_updated' => '2022-08-25 16:09:22',
            ),
            13 => 
            array (
                'supplier_delivery_order_item_id' => 17,
                'supplier_delivery_order_id' => 4,
                'raw_material_id' => 52,
                'raw_material_company_usage_id' => 71,
                'supplier_delivery_order_item_qty' => 5,
                'supplier_delivery_order_item_value_per_qty' => '3.00',
                'supplier_delivery_order_item_price_per_qty' => '250.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_item_updated' => '2022-08-26 16:47:16',
            ),
            14 => 
            array (
                'supplier_delivery_order_item_id' => 18,
                'supplier_delivery_order_id' => 4,
                'raw_material_id' => 53,
                'raw_material_company_usage_id' => 72,
                'supplier_delivery_order_item_qty' => 18,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '65.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_item_updated' => '2022-08-26 16:47:16',
            ),
            15 => 
            array (
                'supplier_delivery_order_item_id' => 19,
                'supplier_delivery_order_id' => 4,
                'raw_material_id' => 54,
                'raw_material_company_usage_id' => 73,
                'supplier_delivery_order_item_qty' => 18,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '55.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_item_updated' => '2022-08-26 16:47:16',
            ),
            16 => 
            array (
                'supplier_delivery_order_item_id' => 20,
                'supplier_delivery_order_id' => 4,
                'raw_material_id' => 55,
                'raw_material_company_usage_id' => 74,
                'supplier_delivery_order_item_qty' => 2,
                'supplier_delivery_order_item_value_per_qty' => '2.00',
                'supplier_delivery_order_item_price_per_qty' => '300.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_item_updated' => '2022-08-26 16:47:16',
            ),
            17 => 
            array (
                'supplier_delivery_order_item_id' => 21,
                'supplier_delivery_order_id' => 4,
                'raw_material_id' => 56,
                'raw_material_company_usage_id' => 75,
                'supplier_delivery_order_item_qty' => 1,
                'supplier_delivery_order_item_value_per_qty' => '25.00',
                'supplier_delivery_order_item_price_per_qty' => '1450.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-26 16:47:16',
                'supplier_delivery_order_item_updated' => '2022-08-26 16:47:16',
            ),
            18 => 
            array (
                'supplier_delivery_order_item_id' => 22,
                'supplier_delivery_order_id' => 5,
                'raw_material_id' => 57,
                'raw_material_company_usage_id' => 76,
                'supplier_delivery_order_item_qty' => 1,
                'supplier_delivery_order_item_value_per_qty' => '1.00',
                'supplier_delivery_order_item_price_per_qty' => '80.00',
                'supplier_delivery_order_item_disc' => '0.00',
                'supplier_delivery_order_item_created' => '2022-08-29 09:51:53',
                'supplier_delivery_order_item_updated' => '2022-08-29 09:51:53',
            ),
        ));
        
        
    }
}