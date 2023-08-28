<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSupplierDeliveryOrderLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_supplier_delivery_order_log')->delete();
        
        \DB::table('tbl_supplier_delivery_order_log')->insert(array (
            0 => 
            array (
                'supplier_delivery_order_log_id' => 1,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Add',
                'supplier_delivery_order_log_description' => 'Stock in by Nora',
                'supplier_delivery_order_log_created' => '2022-08-24 18:07:26',
                'user_id' => 1,
            ),
            1 => 
            array (
                'supplier_delivery_order_log_id' => 2,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 11:16:31',
                'user_id' => 1,
            ),
            2 => 
            array (
                'supplier_delivery_order_log_id' => 3,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 11:21:30',
                'user_id' => 1,
            ),
            3 => 
            array (
                'supplier_delivery_order_log_id' => 4,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 11:30:16',
                'user_id' => 1,
            ),
            4 => 
            array (
                'supplier_delivery_order_log_id' => 5,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 11:31:33',
                'user_id' => 1,
            ),
            5 => 
            array (
                'supplier_delivery_order_log_id' => 6,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 12:31:26',
                'user_id' => 1,
            ),
            6 => 
            array (
                'supplier_delivery_order_log_id' => 7,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 15:43:36',
                'user_id' => 1,
            ),
            7 => 
            array (
                'supplier_delivery_order_log_id' => 8,
                'supplier_delivery_order_id' => 2,
                'supplier_delivery_order_log_action' => 'Add',
                'supplier_delivery_order_log_description' => 'Stock in by Nora',
                'supplier_delivery_order_log_created' => '2022-08-25 15:59:37',
                'user_id' => 1,
            ),
            8 => 
            array (
                'supplier_delivery_order_log_id' => 9,
                'supplier_delivery_order_id' => 2,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 16:07:16',
                'user_id' => 1,
            ),
            9 => 
            array (
                'supplier_delivery_order_log_id' => 10,
                'supplier_delivery_order_id' => 2,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated byNora',
                'supplier_delivery_order_log_created' => '2022-08-25 16:08:28',
                'user_id' => 1,
            ),
            10 => 
            array (
                'supplier_delivery_order_log_id' => 11,
                'supplier_delivery_order_id' => 3,
                'supplier_delivery_order_log_action' => 'Add',
                'supplier_delivery_order_log_description' => 'Stock in by Nora',
                'supplier_delivery_order_log_created' => '2022-08-25 16:09:23',
                'user_id' => 1,
            ),
            11 => 
            array (
                'supplier_delivery_order_log_id' => 12,
                'supplier_delivery_order_id' => 4,
                'supplier_delivery_order_log_action' => 'Add',
                'supplier_delivery_order_log_description' => 'Stock in by Nora',
                'supplier_delivery_order_log_created' => '2022-08-26 16:47:16',
                'user_id' => 1,
            ),
            12 => 
            array (
                'supplier_delivery_order_log_id' => 13,
                'supplier_delivery_order_id' => 5,
                'supplier_delivery_order_log_action' => 'Add',
                'supplier_delivery_order_log_description' => 'Stock in by Nora',
                'supplier_delivery_order_log_created' => '2022-08-29 09:51:53',
                'user_id' => 1,
            ),
            13 => 
            array (
                'supplier_delivery_order_log_id' => 29,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated by Nora',
                'supplier_delivery_order_log_created' => '2022-10-25 15:18:31',
                'user_id' => 1,
            ),
            14 => 
            array (
                'supplier_delivery_order_log_id' => 30,
                'supplier_delivery_order_id' => 1,
                'supplier_delivery_order_log_action' => 'Edit',
                'supplier_delivery_order_log_description' => 'Updated by Nora',
                'supplier_delivery_order_log_created' => '2022-10-25 15:18:36',
                'user_id' => 1,
            ),
        ));
        
        
    }
}