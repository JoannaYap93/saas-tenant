<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingExpenseTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_expense')->delete();
        
        \DB::table('tbl_setting_expense')->insert(array (
            0 => 
            array (
                'setting_expense_id' => 1,
                'setting_expense_name' => '{"en":"Storage Fee","cn":"Storage Fee"}',
                'setting_expense_description' => 'Per Day Per Kg',
                'setting_expense_value' => '10.00',
                'company_id' => 0,
                'setting_expense_type_id' => 4,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            1 => 
            array (
                'setting_expense_id' => 2,
                'setting_expense_name' => '{"en":"Export Fee","cn":"Export Fee"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '20.00',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            2 => 
            array (
                'setting_expense_id' => 3,
                'setting_expense_name' => '{"en":"Sales Comission","cn":"Sales Comission"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '6.00',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            3 => 
            array (
                'setting_expense_id' => 4,
                'setting_expense_name' => '{"en":"\\u88f8\\u88c5","cn":"\\u88f8\\u88c5"}',
                'setting_expense_description' => '1. Nitrogen RM3.50
2. Non Vacuum RM2.0',
                'setting_expense_value' => '5.50',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            4 => 
            array (
                'setting_expense_id' => 5,
                'setting_expense_name' => '{"en":"\\u88f8\\u88c5\\uff08\\u7f51\\uff09","cn":"\\u88f8\\u88c5\\uff08\\u7f51\\uff09"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '6.50',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            5 => 
            array (
                'setting_expense_id' => 6,
                'setting_expense_name' => '{"en":"\\u91d1\\u8272\\u771f\\u7a7a\\u888b","cn":"\\u91d1\\u8272\\u771f\\u7a7a\\u888b"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '7.50',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            6 => 
            array (
                'setting_expense_id' => 7,
                'setting_expense_name' => '{"en":"Missing Expense","cn":"Mowing \\u5272\\u8349"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '7.50',
                'company_id' => 0,
                'setting_expense_type_id' => 2,
                'setting_expense_category_id' => 1,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            7 => 
            array (
                'setting_expense_id' => 8,
                'setting_expense_name' => '{"en":"Mowing \\u5272\\u8349","cn":"Mowing \\u5272\\u8349"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            8 => 
            array (
                'setting_expense_id' => 9,
                'setting_expense_name' => '{"en":"Rental Car","cn":"Rental Car"}',
                'setting_expense_description' => 'Per Unit',
                'setting_expense_value' => '2000.00',
                'company_id' => 0,
                'setting_expense_type_id' => 6,
                'setting_expense_category_id' => 4,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            9 => 
            array (
                'setting_expense_id' => 10,
                'setting_expense_name' => '{"en":"Bedsheet","cn":"Bedsheet"}',
                'setting_expense_description' => 'bed',
                'setting_expense_value' => '30.00',
                'company_id' => 1,
                'setting_expense_type_id' => 5,
                'setting_expense_category_id' => 8,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            10 => 
            array (
                'setting_expense_id' => 11,
                'setting_expense_name' => '{"en":"Wash Tree \\u6d17\\u6811","cn":"Wash Tree \\u6d17\\u6811"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 1,
                'setting_expense_subcon' => '200.00',
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            11 => 
            array (
                'setting_expense_id' => 12,
                'setting_expense_name' => '{"en":"Hoe","cn":"Hoe"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '20.00',
                'company_id' => 0,
                'setting_expense_type_id' => 5,
                'setting_expense_category_id' => 5,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            12 => 
            array (
                'setting_expense_id' => 13,
                'setting_expense_name' => '{"en":"Knife","cn":"Knife"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '10.00',
                'company_id' => 0,
                'setting_expense_type_id' => 1,
                'setting_expense_category_id' => 5,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 5,
                'is_excluded_payroll' => 0,
            ),
            13 => 
            array (
                'setting_expense_id' => 14,
                'setting_expense_name' => '{"en":"Petrol","cn":"Petrol"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '40.00',
                'company_id' => 0,
                'setting_expense_type_id' => 1,
                'setting_expense_category_id' => 4,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            14 => 
            array (
                'setting_expense_id' => 15,
                'setting_expense_name' => '{"en":"Rope","cn":"Rope"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '10.00',
                'company_id' => 0,
                'setting_expense_type_id' => 1,
                'setting_expense_category_id' => 6,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => NULL,
                'is_excluded_payroll' => 0,
            ),
            15 => 
            array (
                'setting_expense_id' => 16,
                'setting_expense_name' => '{"en":"Tie Tree \\u7ed1\\u6811","cn":"Tie Tree \\u7ed1\\u6811"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 1,
                'setting_expense_subcon' => '50.00',
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            16 => 
            array (
                'setting_expense_id' => 17,
                'setting_expense_name' => '{"en":"Crop-Dusting \\u55b7\\u836f","cn":"Crop-Dusting \\u55b7\\u836f"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            17 => 
            array (
                'setting_expense_id' => 18,
                'setting_expense_name' => '{"en":"Fertilize 2 \\u65bd\\u5316\\u80a5","cn":"Fertilize 2 \\u65bd\\u5316\\u80a5"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            18 => 
            array (
                'setting_expense_id' => 19,
                'setting_expense_name' => '{"en":"Building \\u5efa\\u7b51","cn":"Building \\u5efa\\u7b51"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 1,
                'setting_expense_subcon' => '60.00',
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            19 => 
            array (
                'setting_expense_id' => 20,
                'setting_expense_name' => '{"en":"Piping \\u6c34\\u7b51","cn":"Piping \\u6c34\\u7b51"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            20 => 
            array (
                'setting_expense_id' => 21,
                'setting_expense_name' => '{"en":"Cut Tree \\u952f\\u6811","cn":"Cut Tree \\u952f\\u6811"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            21 => 
            array (
                'setting_expense_id' => 22,
                'setting_expense_name' => '{"en":"Plant Tree \\u79cd\\u6811","cn":"Plant Tree \\u79cd\\u6811"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 1,
                'setting_expense_subcon' => '5.00',
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            22 => 
            array (
                'setting_expense_id' => 23,
                'setting_expense_name' => '{"en":"Tree Dr \\u533b\\u6811","cn":"Tree Dr \\u533b\\u6811"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            23 => 
            array (
                'setting_expense_id' => 24,
                'setting_expense_name' => '{"en":"Fertilize 8 \\u65bd\\u6709\\u673a\\u80a5","cn":"Fertilize 8 \\u65bd\\u6709\\u673a\\u80a5"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            24 => 
            array (
                'setting_expense_id' => 25,
                'setting_expense_name' => '{"en":"BD liquid compost \\/ BD \\u6db2\\u4f53\\u80a5","cn":"BD liquid compost \\/ BD \\u6db2\\u4f53\\u80a5"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            25 => 
            array (
                'setting_expense_id' => 26,
                'setting_expense_name' => '{"en":"BD 500 \\/ \\u725b\\u89d2\\u7caa\\u80a5","cn":"BD 500 \\/ \\u725b\\u89d2\\u7caa\\u80a5"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            26 => 
            array (
                'setting_expense_id' => 27,
                'setting_expense_name' => '{"en":"BD 501 \\/ \\u725b\\u89d2\\u7845","cn":"BD 501 \\/ \\u725b\\u89d2\\u7845"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            27 => 
            array (
                'setting_expense_id' => 28,
                'setting_expense_name' => '{"en":"cow manure \\/ \\u725b\\u7caa","cn":"cow manure \\/ \\u725b\\u7caa"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
            28 => 
            array (
                'setting_expense_id' => 29,
                'setting_expense_name' => '{"en":"OT","cn":"OT"}',
                'setting_expense_description' => 'Overtime',
                'setting_expense_value' => '11.25',
                'company_id' => 0,
                'setting_expense_type_id' => 3,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 1,
            ),
            29 => 
            array (
                'setting_expense_id' => 30,
                'setting_expense_name' => '{"en":"Pagoh Durian Season","cn":"Pagoh \\u62fe\\u679c"}',
                'setting_expense_description' => NULL,
                'setting_expense_value' => '60.00',
                'company_id' => 2,
                'setting_expense_type_id' => 1,
                'setting_expense_category_id' => 2,
                'is_compulsory' => 0,
                'is_subcon_allow' => 0,
                'setting_expense_subcon' => NULL,
                'setting_expense_status' => 'active',
                'worker_role_id' => 1,
                'is_excluded_payroll' => 0,
            ),
        ));
        
        
    }
}