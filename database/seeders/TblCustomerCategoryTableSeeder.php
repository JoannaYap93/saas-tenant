<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCustomerCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_customer_category')->delete();
        
        \DB::table('tbl_customer_category')->insert(array (
            0 => 
            array (
                'customer_category_id' => 1,
                'customer_category_name' => 'Standard',
                'customer_category_slug' => 'standard',
                'customer_category_created' => '2022-02-26 12:17:20',
                'customer_category_updated' => '2022-02-26 12:17:20',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 1,
            ),
            1 => 
            array (
                'customer_category_id' => 2,
                'customer_category_name' => 'General',
                'customer_category_slug' => 'general',
                'customer_category_created' => '2022-02-26 13:45:48',
                'customer_category_updated' => '2022-02-26 13:45:48',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 2,
            ),
            2 => 
            array (
                'customer_category_id' => 3,
                'customer_category_name' => 'Standard',
                'customer_category_slug' => 'standard-1',
                'customer_category_created' => '2022-03-03 11:14:46',
                'customer_category_updated' => '2022-03-03 11:14:46',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 6,
            ),
            3 => 
            array (
                'customer_category_id' => 4,
                'customer_category_name' => 'WONG SON TRADING',
                'customer_category_slug' => 'wong-son-trading',
                'customer_category_created' => '2022-03-04 20:19:50',
                'customer_category_updated' => '2022-03-04 20:25:27',
                'customer_category_status' => 'active',
                'is_deleted' => 1,
                'customer_category_priority' => 0,
                'company_id' => 4,
            ),
            4 => 
            array (
                'customer_category_id' => 5,
                'customer_category_name' => 'WONG SON TRADING',
                'customer_category_slug' => 'wong-son-trading-1',
                'customer_category_created' => '2022-03-04 20:25:59',
                'customer_category_updated' => '2022-03-07 16:25:20',
                'customer_category_status' => 'active',
                'is_deleted' => 1,
                'customer_category_priority' => 0,
                'company_id' => 4,
            ),
            5 => 
            array (
                'customer_category_id' => 6,
                'customer_category_name' => 'Standard',
                'customer_category_slug' => 'standard-2',
                'customer_category_created' => '2022-03-07 16:25:15',
                'customer_category_updated' => '2022-03-07 16:25:15',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 4,
            ),
            6 => 
            array (
                'customer_category_id' => 7,
                'customer_category_name' => 'EcoRys Sdn Bhd',
                'customer_category_slug' => 'ecorys-sdn-bhd',
                'customer_category_created' => '2022-03-16 12:14:38',
                'customer_category_updated' => '2022-03-16 12:14:38',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 10,
            ),
            7 => 
            array (
                'customer_category_id' => 8,
                'customer_category_name' => 'Cash',
                'customer_category_slug' => 'cash',
                'customer_category_created' => '2022-03-17 17:55:48',
                'customer_category_updated' => '2022-03-17 17:55:48',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 0,
            ),
            8 => 
            array (
                'customer_category_id' => 9,
                'customer_category_name' => 'Standard',
                'customer_category_slug' => 'standard',
                'customer_category_created' => '2022-03-17 17:55:48',
                'customer_category_updated' => '2022-03-17 17:55:48',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 0,
            ),
            9 => 
            array (
                'customer_category_id' => 10,
                'customer_category_name' => 'Warehouse',
                'customer_category_slug' => 'warehouse',
                'customer_category_created' => '2022-03-17 17:55:48',
                'customer_category_updated' => '2022-03-17 17:55:48',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 0,
            ),
            10 => 
            array (
                'customer_category_id' => 11,
                'customer_category_name' => 'Category',
                'customer_category_slug' => 'category',
                'customer_category_created' => '2023-01-04 23:33:14',
                'customer_category_updated' => '2023-01-04 23:33:14',
                'customer_category_status' => 'active',
                'is_deleted' => 0,
                'customer_category_priority' => 1,
                'company_id' => 9,
            ),
        ));
        
        
    }
}