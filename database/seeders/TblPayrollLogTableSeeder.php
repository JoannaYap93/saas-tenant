<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPayrollLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payroll_log')->delete();
        
        \DB::table('tbl_payroll_log')->insert(array (
            0 => 
            array (
                'payroll_log_id' => 7,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-03 16:43:30',
                'user_id' => 2,
            ),
            1 => 
            array (
                'payroll_log_id' => 9,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-04 17:24:12',
                'user_id' => 2,
            ),
            2 => 
            array (
                'payroll_log_id' => 10,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 15:48:36',
                'user_id' => 2,
            ),
            3 => 
            array (
                'payroll_log_id' => 11,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 15:55:39',
                'user_id' => 2,
            ),
            4 => 
            array (
                'payroll_log_id' => 12,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 15:58:37',
                'user_id' => 2,
            ),
            5 => 
            array (
                'payroll_log_id' => 13,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 16:00:20',
                'user_id' => 2,
            ),
            6 => 
            array (
                'payroll_log_id' => 14,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 16:01:29',
                'user_id' => 2,
            ),
            7 => 
            array (
                'payroll_log_id' => 15,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 17:30:35',
                'user_id' => 2,
            ),
            8 => 
            array (
                'payroll_log_id' => 16,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 18:09:57',
                'user_id' => 2,
            ),
            9 => 
            array (
                'payroll_log_id' => 17,
                'payroll_id' => 5,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-08 18:12:59',
                'user_id' => 2,
            ),
            10 => 
            array (
                'payroll_log_id' => 24,
                'payroll_id' => 12,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by George',
                'payroll_log_remark' => 'Payroll added by George',
                'payroll_log_created' => '2022-11-17 11:26:59',
                'user_id' => 1,
            ),
            11 => 
            array (
                'payroll_log_id' => 25,
                'payroll_id' => 12,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by George',
                'payroll_log_remark' => 'Payroll updated by George',
                'payroll_log_created' => '2022-11-17 11:29:11',
                'user_id' => 1,
            ),
            12 => 
            array (
                'payroll_log_id' => 26,
                'payroll_id' => 12,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by George',
                'payroll_log_remark' => 'Payroll updated by George',
                'payroll_log_created' => '2022-11-17 11:43:24',
                'user_id' => 1,
            ),
            13 => 
            array (
                'payroll_log_id' => 27,
                'payroll_id' => 12,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by George',
                'payroll_log_remark' => 'Payroll updated by George',
                'payroll_log_created' => '2022-11-17 11:50:56',
                'user_id' => 1,
            ),
            14 => 
            array (
                'payroll_log_id' => 28,
                'payroll_id' => 12,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by George',
                'payroll_log_remark' => 'Payroll updated by George',
                'payroll_log_created' => '2022-11-17 11:51:14',
                'user_id' => 1,
            ),
            15 => 
            array (
                'payroll_log_id' => 30,
                'payroll_id' => 5,
                'payroll_log_action' => 'delete',
                'payroll_log_description' => 'Payroll deleted by Chang Hui Nian',
                'payroll_log_remark' => 'wrong',
                'payroll_log_created' => '2022-11-17 12:29:16',
                'user_id' => 2,
            ),
            16 => 
            array (
                'payroll_log_id' => 46,
                'payroll_id' => 15,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll added by Chang Hui Nian',
                'payroll_log_created' => '2022-11-17 22:01:37',
                'user_id' => 2,
            ),
            17 => 
            array (
                'payroll_log_id' => 47,
                'payroll_id' => 15,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-17 22:06:52',
                'user_id' => 2,
            ),
            18 => 
            array (
                'payroll_log_id' => 48,
                'payroll_id' => 15,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 16:01:36',
                'user_id' => 2,
            ),
            19 => 
            array (
                'payroll_log_id' => 49,
                'payroll_id' => 15,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 16:06:22',
                'user_id' => 2,
            ),
            20 => 
            array (
                'payroll_log_id' => 50,
                'payroll_id' => 15,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 16:07:30',
                'user_id' => 2,
            ),
            21 => 
            array (
                'payroll_log_id' => 54,
                'payroll_id' => 17,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll added by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 16:48:03',
                'user_id' => 2,
            ),
            22 => 
            array (
                'payroll_log_id' => 55,
                'payroll_id' => 17,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:07:15',
                'user_id' => 2,
            ),
            23 => 
            array (
                'payroll_log_id' => 56,
                'payroll_id' => 17,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:08:16',
                'user_id' => 2,
            ),
            24 => 
            array (
                'payroll_log_id' => 57,
                'payroll_id' => 17,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:12:02',
                'user_id' => 2,
            ),
            25 => 
            array (
                'payroll_log_id' => 58,
                'payroll_id' => 17,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:15:02',
                'user_id' => 2,
            ),
            26 => 
            array (
                'payroll_log_id' => 59,
                'payroll_id' => 18,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll added by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:24:24',
                'user_id' => 2,
            ),
            27 => 
            array (
                'payroll_log_id' => 60,
                'payroll_id' => 18,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 17:27:52',
                'user_id' => 2,
            ),
            28 => 
            array (
                'payroll_log_id' => 61,
                'payroll_id' => 18,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 21:40:19',
                'user_id' => 2,
            ),
            29 => 
            array (
                'payroll_log_id' => 62,
                'payroll_id' => 19,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll added by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 21:50:26',
                'user_id' => 2,
            ),
            30 => 
            array (
                'payroll_log_id' => 63,
                'payroll_id' => 19,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 21:59:47',
                'user_id' => 2,
            ),
            31 => 
            array (
                'payroll_log_id' => 64,
                'payroll_id' => 20,
                'payroll_log_action' => 'Add',
                'payroll_log_description' => 'Payroll added by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll added by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 22:12:43',
                'user_id' => 2,
            ),
            32 => 
            array (
                'payroll_log_id' => 65,
                'payroll_id' => 20,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 22:19:53',
                'user_id' => 2,
            ),
            33 => 
            array (
                'payroll_log_id' => 66,
                'payroll_id' => 21,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 22:57:12',
                'user_id' => 2,
            ),
            34 => 
            array (
                'payroll_log_id' => 67,
                'payroll_id' => 21,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 23:05:54',
                'user_id' => 2,
            ),
            35 => 
            array (
                'payroll_log_id' => 68,
                'payroll_id' => 21,
                'payroll_log_action' => 'Update',
                'payroll_log_description' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_remark' => 'Payroll updated by Chang Hui Nian',
                'payroll_log_created' => '2022-11-18 23:09:12',
                'user_id' => 2,
            ),
        ));
        
        
    }
}