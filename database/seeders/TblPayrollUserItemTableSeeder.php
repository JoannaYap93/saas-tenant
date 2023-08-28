<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblPayrollUserItemTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_payroll_user_item')->delete();
        
        \DB::table('tbl_payroll_user_item')->insert(array (
            0 => 
            array (
                'payroll_user_item_id' => 899,
                'payroll_item_id' => 5,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            1 => 
            array (
                'payroll_user_item_id' => 900,
                'payroll_item_id' => 13,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '11.25',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            2 => 
            array (
                'payroll_user_item_id' => 901,
                'payroll_item_id' => 1,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            3 => 
            array (
                'payroll_user_item_id' => 902,
                'payroll_item_id' => 1,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            4 => 
            array (
                'payroll_user_item_id' => 903,
                'payroll_item_id' => 2,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            5 => 
            array (
                'payroll_user_item_id' => 904,
                'payroll_item_id' => 2,
                'payroll_user_id' => 24,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:09:57',
                'payroll_user_item_updated' => '2022-11-08 18:09:57',
            ),
            6 => 
            array (
                'payroll_user_item_id' => 992,
                'payroll_item_id' => 5,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            7 => 
            array (
                'payroll_user_item_id' => 993,
                'payroll_item_id' => 13,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '11.25',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            8 => 
            array (
                'payroll_user_item_id' => 994,
                'payroll_item_id' => 1,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            9 => 
            array (
                'payroll_user_item_id' => 995,
                'payroll_item_id' => 1,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            10 => 
            array (
                'payroll_user_item_id' => 996,
                'payroll_item_id' => 2,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            11 => 
            array (
                'payroll_user_item_id' => 997,
                'payroll_item_id' => 2,
                'payroll_user_id' => 4,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-08 18:12:58',
                'payroll_user_item_updated' => '2022-11-08 18:12:58',
            ),
            12 => 
            array (
                'payroll_user_item_id' => 1298,
                'payroll_item_id' => 3,
                'payroll_user_id' => 76,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '70.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            13 => 
            array (
                'payroll_user_item_id' => 1299,
                'payroll_item_id' => 1,
                'payroll_user_id' => 76,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '50.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            14 => 
            array (
                'payroll_user_item_id' => 1300,
                'payroll_item_id' => 1,
                'payroll_user_id' => 76,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '50.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            15 => 
            array (
                'payroll_user_item_id' => 1301,
                'payroll_item_id' => 2,
                'payroll_user_id' => 76,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '100.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            16 => 
            array (
                'payroll_user_item_id' => 1302,
                'payroll_item_id' => 2,
                'payroll_user_id' => 76,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '100.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            17 => 
            array (
                'payroll_user_item_id' => 1303,
                'payroll_item_id' => 3,
                'payroll_user_id' => 77,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '25.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            18 => 
            array (
                'payroll_user_item_id' => 1304,
                'payroll_item_id' => 1,
                'payroll_user_id' => 77,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '50.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            19 => 
            array (
                'payroll_user_item_id' => 1305,
                'payroll_item_id' => 1,
                'payroll_user_id' => 77,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '50.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            20 => 
            array (
                'payroll_user_item_id' => 1306,
                'payroll_item_id' => 2,
                'payroll_user_id' => 77,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '100.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            21 => 
            array (
                'payroll_user_item_id' => 1307,
                'payroll_item_id' => 2,
                'payroll_user_id' => 77,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '100.00',
                'payroll_user_item_created' => '2022-11-17 11:51:13',
                'payroll_user_item_updated' => '2022-11-17 11:51:13',
            ),
            22 => 
            array (
                'payroll_user_item_id' => 4437,
                'payroll_item_id' => 13,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '11.25',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            23 => 
            array (
                'payroll_user_item_id' => 4438,
                'payroll_item_id' => 18,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '60.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            24 => 
            array (
                'payroll_user_item_id' => 4439,
                'payroll_item_id' => 17,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            25 => 
            array (
                'payroll_user_item_id' => 4440,
                'payroll_item_id' => 3,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            26 => 
            array (
                'payroll_user_item_id' => 4441,
                'payroll_item_id' => 3,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            27 => 
            array (
                'payroll_user_item_id' => 4442,
                'payroll_item_id' => 1,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            28 => 
            array (
                'payroll_user_item_id' => 4443,
                'payroll_item_id' => 1,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            29 => 
            array (
                'payroll_user_item_id' => 4444,
                'payroll_item_id' => 2,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            30 => 
            array (
                'payroll_user_item_id' => 4445,
                'payroll_item_id' => 2,
                'payroll_user_id' => 155,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 16:07:29',
                'payroll_user_item_updated' => '2022-11-18 16:07:29',
            ),
            31 => 
            array (
                'payroll_user_item_id' => 5281,
                'payroll_item_id' => 17,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            32 => 
            array (
                'payroll_user_item_id' => 5282,
                'payroll_item_id' => 3,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            33 => 
            array (
                'payroll_user_item_id' => 5283,
                'payroll_item_id' => 3,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            34 => 
            array (
                'payroll_user_item_id' => 5284,
                'payroll_item_id' => 1,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            35 => 
            array (
                'payroll_user_item_id' => 5285,
                'payroll_item_id' => 1,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            36 => 
            array (
                'payroll_user_item_id' => 5286,
                'payroll_item_id' => 2,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            37 => 
            array (
                'payroll_user_item_id' => 5287,
                'payroll_item_id' => 2,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            38 => 
            array (
                'payroll_user_item_id' => 5288,
                'payroll_item_id' => 19,
                'payroll_user_id' => 178,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '127.50',
                'payroll_user_item_created' => '2022-11-18 17:15:00',
                'payroll_user_item_updated' => '2022-11-18 17:15:00',
            ),
            39 => 
            array (
                'payroll_user_item_id' => 5752,
                'payroll_item_id' => 17,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            40 => 
            array (
                'payroll_user_item_id' => 5753,
                'payroll_item_id' => 13,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '33.75',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            41 => 
            array (
                'payroll_user_item_id' => 5754,
                'payroll_item_id' => 3,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            42 => 
            array (
                'payroll_user_item_id' => 5755,
                'payroll_item_id' => 3,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            43 => 
            array (
                'payroll_user_item_id' => 5756,
                'payroll_item_id' => 1,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            44 => 
            array (
                'payroll_user_item_id' => 5757,
                'payroll_item_id' => 1,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            45 => 
            array (
                'payroll_user_item_id' => 5758,
                'payroll_item_id' => 2,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            46 => 
            array (
                'payroll_user_item_id' => 5759,
                'payroll_item_id' => 2,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            47 => 
            array (
                'payroll_user_item_id' => 5760,
                'payroll_item_id' => 19,
                'payroll_user_id' => 203,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '82.50',
                'payroll_user_item_created' => '2022-11-18 21:40:18',
                'payroll_user_item_updated' => '2022-11-18 21:40:18',
            ),
            48 => 
            array (
                'payroll_user_item_id' => 6044,
                'payroll_item_id' => 13,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '11.25',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            49 => 
            array (
                'payroll_user_item_id' => 6045,
                'payroll_item_id' => 17,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '600.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            50 => 
            array (
                'payroll_user_item_id' => 6046,
                'payroll_item_id' => 3,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            51 => 
            array (
                'payroll_user_item_id' => 6047,
                'payroll_item_id' => 3,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            52 => 
            array (
                'payroll_user_item_id' => 6048,
                'payroll_item_id' => 1,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            53 => 
            array (
                'payroll_user_item_id' => 6049,
                'payroll_item_id' => 1,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            54 => 
            array (
                'payroll_user_item_id' => 6050,
                'payroll_item_id' => 2,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            55 => 
            array (
                'payroll_user_item_id' => 6051,
                'payroll_item_id' => 2,
                'payroll_user_id' => 227,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 21:59:47',
                'payroll_user_item_updated' => '2022-11-18 21:59:47',
            ),
            56 => 
            array (
                'payroll_user_item_id' => 6280,
                'payroll_item_id' => 17,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '200.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            57 => 
            array (
                'payroll_user_item_id' => 6281,
                'payroll_item_id' => 3,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            58 => 
            array (
                'payroll_user_item_id' => 6282,
                'payroll_item_id' => 3,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            59 => 
            array (
                'payroll_user_item_id' => 6283,
                'payroll_item_id' => 1,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            60 => 
            array (
                'payroll_user_item_id' => 6284,
                'payroll_item_id' => 1,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            61 => 
            array (
                'payroll_user_item_id' => 6285,
                'payroll_item_id' => 2,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            62 => 
            array (
                'payroll_user_item_id' => 6286,
                'payroll_item_id' => 2,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            63 => 
            array (
                'payroll_user_item_id' => 6287,
                'payroll_item_id' => 19,
                'payroll_user_id' => 247,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '60.00',
                'payroll_user_item_created' => '2022-11-18 22:19:52',
                'payroll_user_item_updated' => '2022-11-18 22:19:52',
            ),
            64 => 
            array (
                'payroll_user_item_id' => 6648,
                'payroll_item_id' => 18,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '60.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            65 => 
            array (
                'payroll_user_item_id' => 6649,
                'payroll_item_id' => 17,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '200.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            66 => 
            array (
                'payroll_user_item_id' => 6650,
                'payroll_item_id' => 3,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            67 => 
            array (
                'payroll_user_item_id' => 6651,
                'payroll_item_id' => 3,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            68 => 
            array (
                'payroll_user_item_id' => 6652,
                'payroll_item_id' => 1,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            69 => 
            array (
                'payroll_user_item_id' => 6653,
                'payroll_item_id' => 1,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            70 => 
            array (
                'payroll_user_item_id' => 6654,
                'payroll_item_id' => 2,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employee',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
            71 => 
            array (
                'payroll_user_item_id' => 6655,
                'payroll_item_id' => 2,
                'payroll_user_id' => 263,
                'payroll_item_type' => 'employer',
                'payroll_user_item_amount' => '0.00',
                'payroll_user_item_created' => '2022-11-18 23:09:11',
                'payroll_user_item_updated' => '2022-11-18 23:09:11',
            ),
        ));
        
        
    }
}