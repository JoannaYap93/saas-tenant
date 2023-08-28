<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order_item')->delete();
        
        \DB::table('tbl_delivery_order_item')->insert(array (
            0 => 
            array (
                'delivery_order_item_id' => 1,
                'delivery_order_id' => 1,
                'product_id' => 1,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '222.0000',
                'delivery_order_item_created' => '2022-07-20 02:12:56',
                'delivery_order_item_updated' => '2022-07-25 16:37:13',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 8529,
                'delivery_order_item_price_per_kg' => '2.00',
                'no_collect_code' => 0,
            ),
            1 => 
            array (
                'delivery_order_item_id' => 2,
                'delivery_order_id' => 1,
                'product_id' => 2,
                'setting_product_size_id' => 2,
                'delivery_order_item_quantity' => '50.0000',
                'delivery_order_item_created' => '2022-07-20 02:12:41',
                'delivery_order_item_updated' => '2022-07-25 16:37:13',
                'delivery_order_item_collect_no' => '555',
                'invoice_item_id' => 8530,
                'delivery_order_item_price_per_kg' => '4.00',
                'no_collect_code' => 0,
            ),
            2 => 
            array (
                'delivery_order_item_id' => 12033,
                'delivery_order_id' => 2054,
                'product_id' => 4,
                'setting_product_size_id' => 2,
                'delivery_order_item_quantity' => '50.0000',
                'delivery_order_item_created' => '2022-07-20 02:12:41',
                'delivery_order_item_updated' => '2022-07-25 16:37:13',
                'delivery_order_item_collect_no' => '555',
                'invoice_item_id' => 8530,
                'delivery_order_item_price_per_kg' => '4.00',
                'no_collect_code' => 0,
            ),
            3 => 
            array (
                'delivery_order_item_id' => 12034,
                'delivery_order_id' => 2054,
                'product_id' => 1,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '222.0000',
                'delivery_order_item_created' => '2022-07-20 02:12:56',
                'delivery_order_item_updated' => '2022-07-25 16:37:13',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 8529,
                'delivery_order_item_price_per_kg' => '2.00',
                'no_collect_code' => 0,
            ),
            4 => 
            array (
                'delivery_order_item_id' => 80557,
                'delivery_order_id' => 10517,
                'product_id' => 2,
                'setting_product_size_id' => 4,
                'delivery_order_item_quantity' => '29.9000',
                'delivery_order_item_created' => '2023-08-23 16:49:26',
                'delivery_order_item_updated' => '2023-08-23 16:50:16',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36777,
                'delivery_order_item_price_per_kg' => '35.00',
                'no_collect_code' => 0,
            ),
            5 => 
            array (
                'delivery_order_item_id' => 80565,
                'delivery_order_id' => 10519,
                'product_id' => 2,
                'setting_product_size_id' => 3,
                'delivery_order_item_quantity' => '4.6000',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36801,
                'delivery_order_item_price_per_kg' => '38.00',
                'no_collect_code' => 0,
            ),
            6 => 
            array (
                'delivery_order_item_id' => 80566,
                'delivery_order_id' => 10519,
                'product_id' => 2,
                'setting_product_size_id' => 4,
                'delivery_order_item_quantity' => '7.0000',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36802,
                'delivery_order_item_price_per_kg' => '31.00',
                'no_collect_code' => 0,
            ),
            7 => 
            array (
                'delivery_order_item_id' => 80567,
                'delivery_order_id' => 10519,
                'product_id' => 2,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '5.7500',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36803,
                'delivery_order_item_price_per_kg' => '16.00',
                'no_collect_code' => 0,
            ),
            8 => 
            array (
                'delivery_order_item_id' => 80568,
                'delivery_order_id' => 10519,
                'product_id' => 2,
                'setting_product_size_id' => 9,
                'delivery_order_item_quantity' => '3.7500',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36804,
                'delivery_order_item_price_per_kg' => '9.00',
                'no_collect_code' => 0,
            ),
            9 => 
            array (
                'delivery_order_item_id' => 80569,
                'delivery_order_id' => 10519,
                'product_id' => 4,
                'setting_product_size_id' => 2,
                'delivery_order_item_quantity' => '2.3500',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36805,
                'delivery_order_item_price_per_kg' => '18.00',
                'no_collect_code' => 0,
            ),
            10 => 
            array (
                'delivery_order_item_id' => 80570,
                'delivery_order_id' => 10519,
                'product_id' => 21,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '16.7500',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36807,
                'delivery_order_item_price_per_kg' => '2.00',
                'no_collect_code' => 0,
            ),
            11 => 
            array (
                'delivery_order_item_id' => 80571,
                'delivery_order_id' => 10519,
                'product_id' => 7,
                'setting_product_size_id' => 3,
                'delivery_order_item_quantity' => '8.7500',
                'delivery_order_item_created' => '2023-08-23 21:03:16',
                'delivery_order_item_updated' => '2023-08-23 21:07:07',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36806,
                'delivery_order_item_price_per_kg' => '7.00',
                'no_collect_code' => 0,
            ),
            12 => 
            array (
                'delivery_order_item_id' => 80692,
                'delivery_order_id' => 10534,
                'product_id' => 24,
                'setting_product_size_id' => 10,
                'delivery_order_item_quantity' => '46.4000',
                'delivery_order_item_created' => '2023-08-24 14:53:04',
                'delivery_order_item_updated' => '2023-08-24 14:54:04',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36886,
                'delivery_order_item_price_per_kg' => '0.70',
                'no_collect_code' => 0,
            ),
            13 => 
            array (
                'delivery_order_item_id' => 80693,
                'delivery_order_id' => 10535,
                'product_id' => 1,
                'setting_product_size_id' => 2,
                'delivery_order_item_quantity' => '3.7500',
                'delivery_order_item_created' => '2023-08-24 15:09:41',
                'delivery_order_item_updated' => '2023-08-24 15:13:12',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36909,
                'delivery_order_item_price_per_kg' => '65.00',
                'no_collect_code' => 0,
            ),
            14 => 
            array (
                'delivery_order_item_id' => 80694,
                'delivery_order_id' => 10535,
                'product_id' => 1,
                'setting_product_size_id' => 4,
                'delivery_order_item_quantity' => '2.3000',
                'delivery_order_item_created' => '2023-08-24 15:09:41',
                'delivery_order_item_updated' => '2023-08-24 15:13:12',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36910,
                'delivery_order_item_price_per_kg' => '32.00',
                'no_collect_code' => 0,
            ),
            15 => 
            array (
                'delivery_order_item_id' => 80695,
                'delivery_order_id' => 10536,
                'product_id' => 2,
                'setting_product_size_id' => 4,
                'delivery_order_item_quantity' => '5.1500',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:58',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36930,
                'delivery_order_item_price_per_kg' => '31.00',
                'no_collect_code' => 0,
            ),
            16 => 
            array (
                'delivery_order_item_id' => 80696,
                'delivery_order_id' => 10536,
                'product_id' => 2,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '5.0500',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:58',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36931,
                'delivery_order_item_price_per_kg' => '16.00',
                'no_collect_code' => 0,
            ),
            17 => 
            array (
                'delivery_order_item_id' => 80697,
                'delivery_order_id' => 10536,
                'product_id' => 2,
                'setting_product_size_id' => 11,
                'delivery_order_item_quantity' => '8.1000',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:58',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36932,
                'delivery_order_item_price_per_kg' => '9.00',
                'no_collect_code' => 0,
            ),
            18 => 
            array (
                'delivery_order_item_id' => 80698,
                'delivery_order_id' => 10536,
                'product_id' => 4,
                'setting_product_size_id' => 4,
                'delivery_order_item_quantity' => '2.0000',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:59',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36933,
                'delivery_order_item_price_per_kg' => '10.00',
                'no_collect_code' => 0,
            ),
            19 => 
            array (
                'delivery_order_item_id' => 80699,
                'delivery_order_id' => 10536,
                'product_id' => 7,
                'setting_product_size_id' => 3,
                'delivery_order_item_quantity' => '20.7500',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:59',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36934,
                'delivery_order_item_price_per_kg' => '7.00',
                'no_collect_code' => 0,
            ),
            20 => 
            array (
                'delivery_order_item_id' => 80700,
                'delivery_order_id' => 10536,
                'product_id' => 21,
                'setting_product_size_id' => 7,
                'delivery_order_item_quantity' => '15.8000',
                'delivery_order_item_created' => '2023-08-24 16:29:26',
                'delivery_order_item_updated' => '2023-08-24 16:56:59',
                'delivery_order_item_collect_no' => '',
                'invoice_item_id' => 36935,
                'delivery_order_item_price_per_kg' => '2.00',
                'no_collect_code' => 0,
            ),
        ));
        
        
    }
}