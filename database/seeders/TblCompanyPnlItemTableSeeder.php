<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyPnlItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_pnl_item')->delete();
        
        \DB::table('tbl_company_pnl_item')->insert(array (
            0 => 
            array (
                'company_pnl_item_id' => 1,
                'company_pnl_item_name' => 'Wash Tree',
                'company_pnl_item_code' => 'A0',
                'company_pnl_item_desc' => 'Number of tree need to wash',
                'company_pnl_item_created' => '2022-06-03 14:47:09',
                'company_pnl_item_type' => 'expense',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": ["11"], "product_category_id": 0, "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 0,
                'company_pnl_item_yearly_increase_value' => NULL,
                'company_pnl_item_max_value' => NULL,
                'company_pnl_item_initial_value' => NULL,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            1 => 
            array (
                'company_pnl_item_id' => 2,
                'company_pnl_item_name' => 'Old Tree',
                'company_pnl_item_code' => 'A1',
                'company_pnl_item_desc' => 'Old Musang and black thorn',
                'company_pnl_item_created' => '2022-06-03 14:50:08',
                'company_pnl_item_type' => 'product_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": "", "product_category_id": "1", "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 0,
                'company_pnl_item_yearly_increase_value' => 10,
                'company_pnl_item_max_value' => 120,
                'company_pnl_item_initial_value' => 70,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            2 => 
            array (
                'company_pnl_item_id' => 3,
                'company_pnl_item_name' => 'Old Kampung Tree',
                'company_pnl_item_code' => 'A2',
                'company_pnl_item_desc' => 'Old Kampung Tree',
                'company_pnl_item_created' => '2022-06-03 14:51:19',
                'company_pnl_item_type' => 'product_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": "", "product_category_id": "4", "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 0,
                'company_pnl_item_yearly_increase_value' => 10,
                'company_pnl_item_max_value' => 120,
                'company_pnl_item_initial_value' => 70,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            3 => 
            array (
                'company_pnl_item_id' => 4,
                'company_pnl_item_name' => 'Graft Tree',
                'company_pnl_item_code' => 'A3',
                'company_pnl_item_desc' => 'Old Kampung Grafted Musang or Black Thorn',
                'company_pnl_item_created' => '2022-06-03 14:52:13',
                'company_pnl_item_type' => 'product_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": "", "product_category_id": "1", "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 4,
                'company_pnl_item_yearly_increase_value' => 15,
                'company_pnl_item_max_value' => 120,
                'company_pnl_item_initial_value' => 30,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            4 => 
            array (
                'company_pnl_item_id' => 5,
                'company_pnl_item_name' => 'New Tree',
                'company_pnl_item_code' => 'A4',
                'company_pnl_item_desc' => 'New planted tree',
                'company_pnl_item_created' => '2022-06-03 14:53:53',
                'company_pnl_item_type' => 'product_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": "", "product_category_id": "1", "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 5,
                'company_pnl_item_yearly_increase_value' => 10,
                'company_pnl_item_max_value' => 90,
                'company_pnl_item_initial_value' => 10,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            5 => 
            array (
                'company_pnl_item_id' => 6,
                'company_pnl_item_name' => 'Extra New Tree',
                'company_pnl_item_code' => 'A5',
                'company_pnl_item_desc' => 'Extra New Tree Plant Next Year',
                'company_pnl_item_created' => '2022-06-03 14:54:51',
                'company_pnl_item_type' => 'tree_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": "1", "setting_expense_id": "", "product_category_id": 0, "company_pnl_sub_item_code": "K1"}',
                'company_pnl_item_start_year' => 5,
                'company_pnl_item_yearly_increase_value' => 10,
                'company_pnl_item_max_value' => 90,
                'company_pnl_item_initial_value' => 10,
                'company_pnl_item_value_per_kg' => NULL,
            ),
            6 => 
            array (
                'company_pnl_item_id' => 7,
                'company_pnl_item_name' => 'Gunung Ledang',
                'company_pnl_item_code' => 'A6',
                'company_pnl_item_desc' => 'New Tree',
                'company_pnl_item_created' => '2022-06-03 14:55:42',
                'company_pnl_item_type' => 'product_category',
                'company_pnl_item_json' => '{"is_extra_new_tree": 0, "setting_expense_id": "", "product_category_id": "7", "company_pnl_sub_item_code": ""}',
                'company_pnl_item_start_year' => 0,
                'company_pnl_item_yearly_increase_value' => NULL,
                'company_pnl_item_max_value' => NULL,
                'company_pnl_item_initial_value' => NULL,
                'company_pnl_item_value_per_kg' => NULL,
            ),
        ));
        
        
    }
}