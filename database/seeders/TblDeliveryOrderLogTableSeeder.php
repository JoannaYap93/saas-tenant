<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblDeliveryOrderLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_delivery_order_log')->delete();
        
        \DB::table('tbl_delivery_order_log')->insert(array (
            0 => 
            array (
                'delivery_order_log_id' => 1,
                'delivery_order_id' => 1,
                'delivery_order_log_created' => '2022-07-20 02:13:02',
                'delivery_order_log_action' => 'Add Delivery Order',
                'delivery_order_log_description' => 'Add Delivery Order Description',
                'user_id' => 1,
            ),
            1 => 
            array (
                'delivery_order_log_id' => 2,
                'delivery_order_id' => 1,
                'delivery_order_log_created' => '2022-07-20 14:10:59',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By Webby',
                'user_id' => 1,
            ),
            2 => 
            array (
                'delivery_order_log_id' => 3,
                'delivery_order_id' => 1,
                'delivery_order_log_created' => '2022-07-20 18:13:43',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By Webby',
                'user_id' => 1,
            ),
            3 => 
            array (
                'delivery_order_log_id' => 3971,
                'delivery_order_id' => 2054,
                'delivery_order_log_created' => '2022-07-20 02:13:02',
                'delivery_order_log_action' => 'Add Delivery Order',
                'delivery_order_log_description' => 'Add Delivery Order Description',
                'user_id' => 3,
            ),
            4 => 
            array (
                'delivery_order_log_id' => 3983,
                'delivery_order_id' => 2054,
                'delivery_order_log_created' => '2022-07-20 14:10:59',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By ISAAC TAN',
                'user_id' => 2,
            ),
            5 => 
            array (
                'delivery_order_log_id' => 3987,
                'delivery_order_id' => 2054,
                'delivery_order_log_created' => '2022-07-20 18:13:43',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By ISAAC TAN',
                'user_id' => 2,
            ),
            6 => 
            array (
                'delivery_order_log_id' => 19728,
                'delivery_order_id' => 10517,
                'delivery_order_log_created' => '2023-08-23 16:49:26',
                'delivery_order_log_action' => 'Add',
                'delivery_order_log_description' => 'Order Added By Hee Yu Hock',
                'user_id' => 5,
            ),
            7 => 
            array (
                'delivery_order_log_id' => 19738,
                'delivery_order_id' => 10519,
                'delivery_order_log_created' => '2023-08-23 21:03:16',
                'delivery_order_log_action' => 'Add',
                'delivery_order_log_description' => 'Order Added By Ng Kim Pei',
                'user_id' => 4,
            ),
            8 => 
            array (
                'delivery_order_log_id' => 19739,
                'delivery_order_id' => 10519,
                'delivery_order_log_created' => '2023-08-23 21:04:33',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By Ng Kim Pei',
                'user_id' => 4,
            ),
            9 => 
            array (
                'delivery_order_log_id' => 19740,
                'delivery_order_id' => 10519,
                'delivery_order_log_created' => '2023-08-23 21:06:00',
                'delivery_order_log_action' => 'Update Price Per KG',
                'delivery_order_log_description' => 'Price of Musang-AB updated from RM  per KG to RM 38.00 per KGPrice of Musang-B updated from RM  per KG to RM 31.00 per KGPrice of Musang-C updated from RM  per KG to RM 16.00 per KGPrice of Musang-C- updated from RM  per KG to RM 9 per KGPrice of Tekka 竹脚-A updated from RM  per KG to RM 18 per KGPrice of D24-AB updated from RM  per KG to RM 7.00 per KGPrice of ZAI-C updated from RM  per KG to RM 2.00 per KG',
                'user_id' => 4,
            ),
            10 => 
            array (
                'delivery_order_log_id' => 19741,
                'delivery_order_id' => 10519,
                'delivery_order_log_created' => '2023-08-23 21:06:30',
                'delivery_order_log_action' => 'Price Verification Approve',
                'delivery_order_log_description' => 'Price Verification Approved by Ng Kim Pei',
                'user_id' => 4,
            ),
            11 => 
            array (
                'delivery_order_log_id' => 19765,
                'delivery_order_id' => 10534,
                'delivery_order_log_created' => '2023-08-24 14:53:04',
                'delivery_order_log_action' => 'Add',
                'delivery_order_log_description' => 'Order Added By Calvin Teoh',
                'user_id' => 6,
            ),
            12 => 
            array (
                'delivery_order_log_id' => 19766,
                'delivery_order_id' => 10534,
                'delivery_order_log_created' => '2023-08-24 14:53:31',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By Calvin Teoh',
                'user_id' => 6,
            ),
            13 => 
            array (
                'delivery_order_log_id' => 19770,
                'delivery_order_id' => 10535,
                'delivery_order_log_created' => '2023-08-24 15:09:41',
                'delivery_order_log_action' => 'Add',
                'delivery_order_log_description' => 'Order Added By Hee Yu Hock',
                'user_id' => 5,
            ),
            14 => 
            array (
                'delivery_order_log_id' => 19771,
                'delivery_order_id' => 10535,
                'delivery_order_log_created' => '2023-08-24 15:10:21',
                'delivery_order_log_action' => 'Update',
                'delivery_order_log_description' => 'Order Updated By Hee Yu Hock',
                'user_id' => 5,
            ),
            15 => 
            array (
                'delivery_order_log_id' => 19779,
                'delivery_order_id' => 10536,
                'delivery_order_log_created' => '2023-08-24 16:29:26',
                'delivery_order_log_action' => 'Add',
                'delivery_order_log_description' => 'Order Added By Ng Kim Pei',
                'user_id' => 4,
            ),
            16 => 
            array (
                'delivery_order_log_id' => 19780,
                'delivery_order_id' => 10536,
                'delivery_order_log_created' => '2023-08-24 16:31:05',
                'delivery_order_log_action' => 'Update Price Per KG',
                'delivery_order_log_description' => 'Price of Musang-B updated from RM  per KG to RM 31.00 per KGPrice of Musang-C updated from RM  per KG to RM 16.00 per KGPrice of Musang-CC updated from RM  per KG to RM 9 per KGPrice of Tekka 竹脚-B updated from RM  per KG to RM 10 per KGPrice of D24-AB updated from RM  per KG to RM 7.00 per KGPrice of ZAI-C updated from RM  per KG to RM 2.00 per KG',
                'user_id' => 4,
            ),
            17 => 
            array (
                'delivery_order_log_id' => 19781,
                'delivery_order_id' => 10536,
                'delivery_order_log_created' => '2023-08-24 16:56:23',
                'delivery_order_log_action' => 'Price Verification Approve',
                'delivery_order_log_description' => 'Price Verification Approved by Ng Kim Pei',
                'user_id' => 4,
            ),
        ));
        
        
    }
}