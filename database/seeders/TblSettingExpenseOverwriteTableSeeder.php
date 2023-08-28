<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingExpenseOverwriteTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_expense_overwrite')->delete();
        
        \DB::table('tbl_setting_expense_overwrite')->insert(array (
            0 => 
            array (
                'setting_expense_overwrite_id' => 2,
                'setting_expense_type_id' => 3,
                'setting_expense_overwrite_value' => '60.00',
                'setting_expense_id' => 17,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-08-03 12:03:59',
                'setting_expense_overwrite_updated' => '2022-08-03 12:03:59',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            1 => 
            array (
                'setting_expense_overwrite_id' => 3,
                'setting_expense_type_id' => 3,
                'setting_expense_overwrite_value' => '60.00',
                'setting_expense_id' => 18,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-08-03 12:04:16',
                'setting_expense_overwrite_updated' => '2022-08-03 12:04:16',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            2 => 
            array (
                'setting_expense_overwrite_id' => 4,
                'setting_expense_type_id' => 3,
                'setting_expense_overwrite_value' => '60.00',
                'setting_expense_id' => 8,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-08-03 12:04:40',
                'setting_expense_overwrite_updated' => '2022-08-03 12:04:40',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            3 => 
            array (
                'setting_expense_overwrite_id' => 5,
                'setting_expense_type_id' => 3,
                'setting_expense_overwrite_value' => '60.00',
                'setting_expense_id' => 16,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-08-03 12:05:20',
                'setting_expense_overwrite_updated' => '2022-08-03 12:07:42',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            4 => 
            array (
                'setting_expense_overwrite_id' => 6,
                'setting_expense_type_id' => 3,
                'setting_expense_overwrite_value' => '60.00',
                'setting_expense_id' => 11,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-08-03 12:05:41',
                'setting_expense_overwrite_updated' => '2022-08-03 12:05:41',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            5 => 
            array (
                'setting_expense_overwrite_id' => 8,
                'setting_expense_type_id' => 1,
                'setting_expense_overwrite_value' => '1.00',
                'setting_expense_id' => 30,
                'company_id' => 2,
                'user_id' => 3,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-11-08 14:27:16',
                'setting_expense_overwrite_updated' => '2022-11-08 14:27:16',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            6 => 
            array (
                'setting_expense_overwrite_id' => 9,
                'setting_expense_type_id' => 5,
                'setting_expense_overwrite_value' => '2000.00',
                'setting_expense_id' => 30,
                'company_id' => 4,
                'user_id' => 2,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2022-12-20 16:43:54',
                'setting_expense_overwrite_updated' => '2022-12-20 16:43:54',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            7 => 
            array (
                'setting_expense_overwrite_id' => 11,
                'setting_expense_type_id' => 6,
                'setting_expense_overwrite_value' => '7.50',
                'setting_expense_id' => 8,
                'company_id' => 7,
                'user_id' => 2,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2023-03-19 18:56:19',
                'setting_expense_overwrite_updated' => '2023-03-19 18:58:26',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
            8 => 
            array (
                'setting_expense_overwrite_id' => 12,
                'setting_expense_type_id' => 6,
                'setting_expense_overwrite_value' => '7.50',
                'setting_expense_id' => 30,
                'company_id' => 6,
                'user_id' => 2,
                'is_extra_commission' => 0,
                'setting_expense_overwrite_commission' => NULL,
                'setting_expense_overwrite_created' => '2023-04-03 21:55:19',
                'setting_expense_overwrite_updated' => '2023-04-03 21:55:19',
                'is_subcon_allow' => 0,
                'setting_expense_overwrite_subcon' => NULL,
            ),
        ));
        
        
    }
}