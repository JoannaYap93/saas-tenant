<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblClaimTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_claim')->delete();
        
        \DB::table('tbl_claim')->insert(array (
            0 => 
            array (
                'claim_id' => 1,
                'claim_start_date' => '2023-01-01',
                'claim_end_date' => '2023-01-31',
                'claim_remark' => NULL,
                'claim_admin_remark' => '',
                'claim_created' => '2023-02-07 15:10:19',
                'claim_updated' => '2023-05-12 21:49:12',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '113.00',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 1,
                'claim_number' => NULL,
                'is_deleted' => 1,
            ),
            1 => 
            array (
                'claim_id' => 2,
                'claim_start_date' => '2023-04-01',
                'claim_end_date' => '2023-04-30',
                'claim_remark' => NULL,
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-09 12:49:12',
                'claim_updated' => '2023-05-09 12:49:40',
                'user_id' => 0,
                'claim_status_id' => 2,
                'approval_user_id' => 0,
                'claim_amount' => '624.05',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 3,
                'worker_id' => 2,
                'claim_number' => 'CL/ER/WT/23050022',
                'is_deleted' => 0,
            ),
            2 => 
            array (
                'claim_id' => 3,
                'claim_start_date' => '2023-06-01',
                'claim_end_date' => '2023-06-30',
                'claim_remark' => NULL,
                'claim_admin_remark' => '',
                'claim_created' => '2023-07-28 11:52:43',
                'claim_updated' => '2023-07-28 11:53:50',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '308.00',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 3,
                'worker_id' => 3,
                'claim_number' => 'CL/ER/NORA/23070006',
                'is_deleted' => 0,
            ),
            3 => 
            array (
                'claim_id' => 4,
                'claim_start_date' => '2023-01-01',
                'claim_end_date' => '2023-01-31',
                'claim_remark' => 'test',
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-12 21:08:55',
                'claim_updated' => '2023-05-12 21:24:27',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '20648.45',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 2,
                'claim_number' => 'CL/ER/GT/23050002',
                'is_deleted' => 0,
            ),
            4 => 
            array (
                'claim_id' => 5,
                'claim_start_date' => '2023-01-01',
                'claim_end_date' => '2023-01-31',
                'claim_remark' => 'test',
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-12 21:28:01',
                'claim_updated' => '2023-05-12 21:41:03',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '10981.13',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 3,
                'claim_number' => 'CL/ER/GT/23050003',
                'is_deleted' => 0,
            ),
            5 => 
            array (
                'claim_id' => 6,
                'claim_start_date' => '2023-04-01',
                'claim_end_date' => '2023-04-30',
                'claim_remark' => 'test',
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-08 17:52:12',
                'claim_updated' => '2023-05-12 21:32:30',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '26724.67',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 3,
                'claim_number' => 'CL/ER/WTH/23050009',
                'is_deleted' => 1,
            ),
            6 => 
            array (
                'claim_id' => 7,
                'claim_start_date' => '2023-07-01',
                'claim_end_date' => '2023-07-31',
                'claim_remark' => NULL,
                'claim_admin_remark' => '',
                'claim_created' => '2023-08-15 16:49:29',
                'claim_updated' => '2023-08-15 16:54:31',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '10739.73',
                'company_id' => 3,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 3,
                'claim_number' => 'CL/NMP/JOEY/23080003',
                'is_deleted' => 0,
            ),
            7 => 
            array (
                'claim_id' => 8,
                'claim_start_date' => '2023-04-01',
                'claim_end_date' => '2023-04-30',
                'claim_remark' => NULL,
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-12 21:32:48',
                'claim_updated' => '2023-05-12 21:35:35',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '26423.67',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 1,
                'worker_id' => 2,
                'claim_number' => 'CL/ER/GT/23050004',
                'is_deleted' => 0,
            ),
            8 => 
            array (
                'claim_id' => 9,
                'claim_start_date' => '2023-04-01',
                'claim_end_date' => '2023-04-30',
                'claim_remark' => 'test',
                'claim_admin_remark' => '',
                'claim_created' => '2023-05-08 17:20:11',
                'claim_updated' => '2023-05-08 17:35:34',
                'user_id' => 0,
                'claim_status_id' => 1,
                'approval_user_id' => 0,
                'claim_amount' => '26724.67',
                'company_id' => 2,
                'is_account_check' => 0,
                'is_payment' => 0,
                'admin_id' => 2,
                'worker_id' => 1,
                'claim_number' => 'CL/ER/WTH/23050006',
                'is_deleted' => 1,
            ),
        ));
        
        
    }
}