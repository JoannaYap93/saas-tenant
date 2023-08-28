<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingExpenseCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_expense_category')->delete();
        
        \DB::table('tbl_setting_expense_category')->insert(array (
            0 => 
            array (
                'setting_expense_category_id' => 1,
                'setting_expense_category_name' => '{"en":"Warehouse & Packaging","cn":"Warehouse & Packaging"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 1,
                'setting_expense_category_group' => 'Expense',
            ),
            1 => 
            array (
                'setting_expense_category_id' => 2,
                'setting_expense_category_name' => '{"en":"Labour","cn":"\\u52b3\\u5de5\\u8d39"}',
                'setting_expense_category_budget' => '132.00',
                'is_budget_limited' => 1,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            2 => 
            array (
                'setting_expense_category_id' => 4,
                'setting_expense_category_name' => '{"en":"Car Related Expenses","cn":"\\u6469\\u6258\\uff0c\\u6c7d\\u8f66\\uff0c\\u4ea4\\u901a\\u8d39"}',
                'setting_expense_category_budget' => '40.00',
                'is_budget_limited' => 1,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            3 => 
            array (
                'setting_expense_category_id' => 5,
                'setting_expense_category_name' => '{"en":"Reusable Tools","cn":"\\u53ef\\u5faa\\u73af\\u519c\\u8015\\u5de5\\u5177"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            4 => 
            array (
                'setting_expense_category_id' => 6,
                'setting_expense_category_name' => '{"en":"Non-Reusable Tools","cn":"\\u4e0d\\u53ef\\u5faa\\u73af\\u519c\\u8015\\u5de5\\u5177"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            5 => 
            array (
                'setting_expense_category_id' => 7,
                'setting_expense_category_name' => '{"en":"UPKEEP","cn":"\\u4fee\\u7406"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            6 => 
            array (
                'setting_expense_category_id' => 8,
                'setting_expense_category_name' => '{"en":"REFRESHMENT, FOOD AND BEVERAGE","cn":"\\u4f19\\u98df"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            7 => 
            array (
                'setting_expense_category_id' => 9,
                'setting_expense_category_name' => '{"en":"OTHER\\uff08ALL LAND\\uff09","cn":"\\u6574\\u4f53\\u8d39\\u7528"}',
                'setting_expense_category_budget' => '100.00',
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            8 => 
            array (
                'setting_expense_category_id' => 10,
                'setting_expense_category_name' => '{"en":"FERTILIZER","cn":"FERTILIZER"}',
                'setting_expense_category_budget' => '102.00',
                'is_budget_limited' => 1,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            9 => 
            array (
                'setting_expense_category_id' => 11,
                'setting_expense_category_name' => '{"en":"PESTICIDE","cn":"PESTICIDE"}',
                'setting_expense_category_budget' => '46.00',
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            10 => 
            array (
                'setting_expense_category_id' => 12,
                'setting_expense_category_name' => '{"en":"BIODYNAMIC","cn":"BIODYNAMIC"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            11 => 
            array (
                'setting_expense_category_id' => 13,
                'setting_expense_category_name' => '{"en":"ORGANIC FERTILIZER","cn":"ORGANIC FERTILIZER"}',
                'setting_expense_category_budget' => '72.00',
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            12 => 
            array (
                'setting_expense_category_id' => 14,
                'setting_expense_category_name' => '{"en":"WATER SYSTEM","cn":"\\u6c34\\u5229\\u704c\\u6e89"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            13 => 
            array (
                'setting_expense_category_id' => 15,
                'setting_expense_category_name' => '{"en":"PLANT AND MACHINERY","cn":"\\u673a\\u5668"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            14 => 
            array (
                'setting_expense_category_id' => 16,
            'setting_expense_category_name' => '{"en":"HOSTEL (BUILD) MANAGEMENT","cn":"\\u7ba1\\u7406\\u5c42\\u5bbf\\u820d\\u5efa\\u8bbe"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            15 => 
            array (
                'setting_expense_category_id' => 17,
            'setting_expense_category_name' => '{"en":"HOSTEL (BUILD) LABOUR","cn":"\\u52b3\\u5de5\\u5bbf\\u820d\\u5efa\\u8bbe"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            16 => 
            array (
                'setting_expense_category_id' => 18,
            'setting_expense_category_name' => '{"en":"OFFICE (BUILD)","cn":"\\u529e\\u516c\\u5ba4\\u5efa\\u8bbe"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            17 => 
            array (
                'setting_expense_category_id' => 19,
                'setting_expense_category_name' => '{"en":"ROAD AND BRIDGE","cn":"\\u9053\\u8def\\u53ca\\u6865\\u6881"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            18 => 
            array (
                'setting_expense_category_id' => 20,
            'setting_expense_category_name' => '{"en":"BIRD HOUSE (BUILD)","cn":"\\u9e1f\\u5c4b\\u5efa\\u8bbe"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            19 => 
            array (
                'setting_expense_category_id' => 21,
                'setting_expense_category_name' => '{"en":"OFFICE EXPENSES","cn":"\\u529e\\u516c\\u5ba4\\u6742\\u8d39"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            20 => 
            array (
                'setting_expense_category_id' => 22,
                'setting_expense_category_name' => '{"en":"HOSTEL LABOUR","cn":"\\u52b3\\u5de5\\u5bbf\\u820d\\u6742\\u8d39"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            21 => 
            array (
                'setting_expense_category_id' => 23,
                'setting_expense_category_name' => '{"en":"REPAIR AND MAINATENANCE","cn":"\\u7ef4\\u62a4\\u4fee\\u7406"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            22 => 
            array (
                'setting_expense_category_id' => 24,
                'setting_expense_category_name' => '{"en":"HOSTEL MANAGEMENT","cn":"\\u7ba1\\u7406\\u5c42\\u5bbf\\u820d\\u6742\\u8d39"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            23 => 
            array (
                'setting_expense_category_id' => 25,
                'setting_expense_category_name' => '{"en":"GIFT AND DONATION","cn":"\\u6350\\u6b3e"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            24 => 
            array (
                'setting_expense_category_id' => 26,
                'setting_expense_category_name' => '{"en":"STAFF COST","cn":"STAFF COST"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            25 => 
            array (
                'setting_expense_category_id' => 27,
                'setting_expense_category_name' => '{"en":"SALES & MARKETING","cn":"SALES & MARKETING"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            26 => 
            array (
                'setting_expense_category_id' => 28,
                'setting_expense_category_name' => '{"en":"PROFESSIONAL FEE","cn":"PROFESSIONAL FEE"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            27 => 
            array (
                'setting_expense_category_id' => 29,
                'setting_expense_category_name' => '{"en":"RENTAL","cn":"RENTAL"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            28 => 
            array (
                'setting_expense_category_id' => 30,
                'setting_expense_category_name' => '{"en":"UTILITIES","cn":"UTILITIES"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            29 => 
            array (
                'setting_expense_category_id' => 31,
                'setting_expense_category_name' => '{"en":"OTHER","cn":"OTHER"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Expense',
            ),
            30 => 
            array (
                'setting_expense_category_id' => 32,
                'setting_expense_category_name' => '{"en":"ELECTRICAL INSTALLATION","cn":"ELECTRICAL INSTALLATION"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            31 => 
            array (
                'setting_expense_category_id' => 33,
                'setting_expense_category_name' => '{"en":"RENOVATION","cn":"RENOVATION"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            32 => 
            array (
                'setting_expense_category_id' => 34,
                'setting_expense_category_name' => '{"en":"MOTOR VEHICLE","cn":"MOTOR VEHICLE"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            33 => 
            array (
                'setting_expense_category_id' => 35,
                'setting_expense_category_name' => '{"en":"DURIAN PLANTATION","cn":"DURIAN PLANTATION"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'Cost',
            ),
            34 => 
            array (
                'setting_expense_category_id' => 36,
                'setting_expense_category_name' => '{"en":"General","cn":"GENERAL"}',
                'setting_expense_category_budget' => NULL,
                'is_budget_limited' => 0,
                'is_backend_only' => 0,
                'setting_expense_category_group' => 'General',
            ),
        ));
        
        
    }
}