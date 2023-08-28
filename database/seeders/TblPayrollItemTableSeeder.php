<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPayrollItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payroll_item')->delete();
        
        \DB::table('tbl_payroll_item')->insert(array (
            0 => 
            array (
                'payroll_item_id' => 1,
                'payroll_item_name' => 'EPF',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 1,
                'payroll_item_created' => '2022-10-27 15:23:17',
                'payroll_item_updated' => '2023-08-07 13:01:42',
                'is_deleted' => 0,
                'setting_expense_id' => 23,
            ),
            1 => 
            array (
                'payroll_item_id' => 2,
                'payroll_item_name' => 'SOCSO',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 1,
                'payroll_item_created' => '2022-10-27 15:23:38',
                'payroll_item_updated' => '2023-08-07 13:01:49',
                'is_deleted' => 0,
                'setting_expense_id' => 27,
            ),
            2 => 
            array (
                'payroll_item_id' => 3,
                'payroll_item_name' => 'EIS',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 1,
                'payroll_item_created' => '2022-10-27 15:24:01',
                'payroll_item_updated' => '2023-08-07 13:01:57',
                'is_deleted' => 0,
                'setting_expense_id' => 28,
            ),
            3 => 
            array (
                'payroll_item_id' => 4,
                'payroll_item_name' => 'PCB',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-10-27 15:24:16',
                'payroll_item_updated' => '2022-11-16 12:30:11',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            4 => 
            array (
                'payroll_item_id' => 5,
                'payroll_item_name' => 'Allowance',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-10-27 15:24:39',
                'payroll_item_updated' => '2022-10-27 15:24:39',
                'is_deleted' => 0,
                'setting_expense_id' => NULL,
            ),
            5 => 
            array (
                'payroll_item_id' => 13,
                'payroll_item_name' => 'OVERTIME',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-03 16:40:49',
                'payroll_item_updated' => '2022-11-03 17:31:50',
                'is_deleted' => 0,
                'setting_expense_id' => 29,
            ),
            6 => 
            array (
                'payroll_item_id' => 14,
                'payroll_item_name' => 'LOAN DEDUCTION',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-08 15:49:04',
                'payroll_item_updated' => '2022-11-08 15:49:04',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            7 => 
            array (
                'payroll_item_id' => 15,
                'payroll_item_name' => 'INSURANCE',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-08 18:09:33',
                'payroll_item_updated' => '2022-11-08 18:09:33',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            8 => 
            array (
                'payroll_item_id' => 16,
                'payroll_item_name' => 'NPL',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-16 12:26:49',
                'payroll_item_updated' => '2022-11-16 12:26:49',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            9 => 
            array (
                'payroll_item_id' => 17,
                'payroll_item_name' => 'REWARD',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-17 10:41:49',
                'payroll_item_updated' => '2022-11-17 10:41:49',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            10 => 
            array (
                'payroll_item_id' => 18,
                'payroll_item_name' => 'ARREAR BASIC',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-17 22:03:18',
                'payroll_item_updated' => '2022-11-17 22:03:18',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            11 => 
            array (
                'payroll_item_id' => 19,
                'payroll_item_name' => 'DEDUCTION',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-17 22:03:41',
                'payroll_item_updated' => '2022-11-17 22:03:41',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            12 => 
            array (
                'payroll_item_id' => 20,
                'payroll_item_name' => 'ADVANCE',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2022-11-23 15:20:35',
                'payroll_item_updated' => '2022-11-23 15:20:35',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            13 => 
            array (
                'payroll_item_id' => 21,
                'payroll_item_name' => 'BONUS',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2023-01-31 15:55:30',
                'payroll_item_updated' => '2023-01-31 15:55:30',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            14 => 
            array (
                'payroll_item_id' => 22,
            'payroll_item_name' => 'LEVY (HRDF)',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 1,
                'payroll_item_created' => '2023-08-07 13:02:39',
                'payroll_item_updated' => '2023-08-07 16:42:56',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            15 => 
            array (
                'payroll_item_id' => 23,
                'payroll_item_name' => 'ARREAR BASIC-1',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2023-08-07 16:41:19',
                'payroll_item_updated' => '2023-08-07 16:47:34',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            16 => 
            array (
                'payroll_item_id' => 24,
                'payroll_item_name' => 'RTK DEDUCTION',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2023-08-07 16:42:40',
                'payroll_item_updated' => '2023-08-08 20:11:40',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            17 => 
            array (
                'payroll_item_id' => 25,
                'payroll_item_name' => 'CENT ADJ',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Add',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2023-08-07 18:10:27',
                'payroll_item_updated' => '2023-08-07 18:10:27',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
            18 => 
            array (
                'payroll_item_id' => 26,
                'payroll_item_name' => 'CENT ADJ-1',
                'payroll_item_status' => 'Available',
                'payroll_item_type' => 'Deduct',
                'is_compulsory' => 0,
                'is_employer' => 0,
                'payroll_item_created' => '2023-08-07 19:13:40',
                'payroll_item_updated' => '2023-08-07 19:13:40',
                'is_deleted' => 0,
                'setting_expense_id' => 0,
            ),
        ));
        
        
    }
}