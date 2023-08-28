<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyLandTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_land')->delete();
        
        \DB::table('tbl_company_land')->insert(array (
            0 => 
            array (
                'company_land_id' => 1,
                'company_land_name' => 'Titi - 10',
                'company_land_category_id' => 1,
                'company_id' => 1,
                'company_land_code' => 'sblj9',
                'company_land_created' => '2023-08-22 14:58:11',
                'company_land_updated' => '2023-08-22 15:00:29',
                'company_bank_id' => 1,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => '100.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            1 => 
            array (
                'company_land_id' => 2,
                'company_land_name' => 'San Lee - 63',
                'company_land_category_id' => 5,
                'company_id' => 1,
                'company_land_code' => '52ARP',
                'company_land_created' => '2022-02-26 12:15:47',
                'company_land_updated' => '2022-10-28 10:18:00',
                'company_bank_id' => 1,
                'company_land_total_tree' => 407,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            2 => 
            array (
                'company_land_id' => 3,
                'company_land_name' => 'Tras - 301',
                'company_land_category_id' => 5,
                'company_id' => 2,
                'company_land_code' => 'rMQzh',
                'company_land_created' => '2022-02-26 13:43:42',
                'company_land_updated' => '2023-05-09 12:46:09',
                'company_bank_id' => 2,
                'company_land_total_tree' => 2866,
                'company_land_total_acre' => '47.00',
                'is_overwrite_budget' => 1,
                'overwrite_budget_per_tree' => '410.00',
            ),
            3 => 
            array (
                'company_land_id' => 4,
                'company_land_name' => 'Tras - 9',
                'company_land_category_id' => 11,
                'company_id' => 1,
                'company_land_code' => 'ZW173',
                'company_land_created' => '2022-10-28 10:18:00',
                'company_land_updated' => '2022-10-28 10:18:00',
                'company_bank_id' => 1,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => '9.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            4 => 
            array (
                'company_land_id' => 5,
                'company_land_name' => 'Tras - 12R',
                'company_land_category_id' => 8,
                'company_id' => 2,
                'company_land_code' => 'ZSWu2',
                'company_land_created' => '2022-03-18 17:33:00',
                'company_land_updated' => '2022-12-22 19:09:11',
                'company_bank_id' => 2,
                'company_land_total_tree' => 270,
                'company_land_total_acre' => '12.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            5 => 
            array (
                'company_land_id' => 6,
                'company_land_name' => 'Titi - 10',
                'company_land_category_id' => 2,
                'company_id' => 3,
                'company_land_code' => '6AH3t',
                'company_land_created' => '2022-03-01 13:41:24',
                'company_land_updated' => '2023-08-23 08:10:52',
                'company_bank_id' => 3,
                'company_land_total_tree' => 0,
                'company_land_total_acre' => '9.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            6 => 
            array (
                'company_land_id' => 7,
                'company_land_name' => 'Asahan - 10',
                'company_land_category_id' => 3,
                'company_id' => 4,
                'company_land_code' => 'QmIwJ',
                'company_land_created' => '2022-03-01 13:42:20',
                'company_land_updated' => '2023-08-23 08:11:13',
                'company_bank_id' => 4,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => '9.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            7 => 
            array (
                'company_land_id' => 8,
                'company_land_name' => 'Pagoh - 24',
                'company_land_category_id' => 4,
                'company_id' => 4,
                'company_land_code' => 'fvpn6',
                'company_land_created' => '2022-03-01 13:42:45',
                'company_land_updated' => '2023-08-23 08:11:25',
                'company_bank_id' => 4,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => '24.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            8 => 
            array (
                'company_land_id' => 9,
                'company_land_name' => 'Tras - 301',
                'company_land_category_id' => 5,
                'company_id' => 5,
                'company_land_code' => 'nVPeC',
                'company_land_created' => '2022-03-02 20:00:48',
                'company_land_updated' => '2023-01-03 14:33:43',
                'company_bank_id' => 5,
                'company_land_total_tree' => 3879,
                'company_land_total_acre' => '56.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            9 => 
            array (
                'company_land_id' => 10,
                'company_land_name' => 'Tras - 49',
                'company_land_category_id' => 10,
                'company_id' => 2,
                'company_land_code' => 'sIlwI',
                'company_land_created' => '2022-08-03 11:44:34',
                'company_land_updated' => '2023-08-16 18:43:21',
                'company_bank_id' => 2,
                'company_land_total_tree' => 1471,
                'company_land_total_acre' => '49.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            10 => 
            array (
                'company_land_id' => 11,
                'company_land_name' => 'Tras - 9',
                'company_land_category_id' => 11,
                'company_id' => 2,
                'company_land_code' => 'In0yv',
                'company_land_created' => '2022-10-21 10:56:21',
                'company_land_updated' => '2023-08-16 18:34:04',
                'company_bank_id' => 2,
                'company_land_total_tree' => 1329,
                'company_land_total_acre' => '9.00',
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            11 => 
            array (
                'company_land_id' => 12,
                'company_land_name' => 'Tras - BD',
                'company_land_category_id' => 9,
                'company_id' => 5,
                'company_land_code' => 'XYT5O',
                'company_land_created' => '2022-06-18 21:32:10',
                'company_land_updated' => '2022-06-18 22:33:53',
                'company_bank_id' => 5,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            12 => 
            array (
                'company_land_id' => 13,
                'company_land_name' => 'Tras - 291',
                'company_land_category_id' => 13,
                'company_id' => 5,
                'company_land_code' => 'qCSz8',
                'company_land_created' => '2023-04-28 14:30:12',
                'company_land_updated' => '2023-04-28 14:30:48',
                'company_bank_id' => 5,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            13 => 
            array (
                'company_land_id' => 14,
                'company_land_name' => 'San Lee - 63',
                'company_land_category_id' => 4,
                'company_id' => 6,
                'company_land_code' => 'qUebb',
                'company_land_created' => '2022-03-03 11:11:34',
                'company_land_updated' => '2023-01-01 13:06:35',
                'company_bank_id' => 6,
                'company_land_total_tree' => 1366,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            14 => 
            array (
                'company_land_id' => 15,
                'company_land_name' => 'Tras - 11',
                'company_land_category_id' => 7,
                'company_id' => 6,
                'company_land_code' => 'VOGTD',
                'company_land_created' => '2022-03-19 18:46:01',
                'company_land_updated' => '2023-01-01 20:34:47',
                'company_bank_id' => 6,
                'company_land_total_tree' => 439,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
            15 => 
            array (
                'company_land_id' => 16,
                'company_land_name' => 'Tras - 77',
                'company_land_category_id' => 12,
                'company_id' => 7,
                'company_land_code' => 'dZDj9',
                'company_land_created' => '2022-11-22 10:57:08',
                'company_land_updated' => '2022-11-22 10:57:08',
                'company_bank_id' => 7,
                'company_land_total_tree' => NULL,
                'company_land_total_acre' => NULL,
                'is_overwrite_budget' => 0,
                'overwrite_budget_per_tree' => NULL,
            ),
        ));
        
        
    }
}