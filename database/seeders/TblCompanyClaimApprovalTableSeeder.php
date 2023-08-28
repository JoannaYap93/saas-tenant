<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblCompanyClaimApprovalTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_company_claim_approval')->delete();
        
        \DB::table('tbl_company_claim_approval')->insert(array (
            0 => 
            array (
                'company_claim_approval_id' => 1,
                'user_id' => 2,
                'claim_approval_step_id' => 3,
                'company_claim_approval_cdate' => '2022-07-20 02:44:08',
                'company_id' => 1,
                'claim_status_id_involved' => 0,
            ),
            1 => 
            array (
                'company_claim_approval_id' => 2,
                'user_id' => 1,
                'claim_approval_step_id' => 3,
                'company_claim_approval_cdate' => '2023-08-04 15:45:13',
                'company_id' => 2,
                'claim_status_id_involved' => 0,
            ),
            2 => 
            array (
                'company_claim_approval_id' => 3,
                'user_id' => 3,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 15:45:13',
                'company_id' => 2,
                'claim_status_id_involved' => 0,
            ),
            3 => 
            array (
                'company_claim_approval_id' => 4,
                'user_id' => 4,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:48:00',
                'company_id' => 8,
                'claim_status_id_involved' => 0,
            ),
            4 => 
            array (
                'company_claim_approval_id' => 5,
                'user_id' => 5,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:48:38',
                'company_id' => 8,
                'claim_status_id_involved' => 0,
            ),
            5 => 
            array (
                'company_claim_approval_id' => 6,
                'user_id' => 6,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:48:58',
                'company_id' => 5,
                'claim_status_id_involved' => 0,
            ),
            6 => 
            array (
                'company_claim_approval_id' => 7,
                'user_id' => 7,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:49:21',
                'company_id' => 6,
                'claim_status_id_involved' => 0,
            ),
            7 => 
            array (
                'company_claim_approval_id' => 8,
                'user_id' => 8,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:50:11',
                'company_id' => 7,
                'claim_status_id_involved' => 0,
            ),
            8 => 
            array (
                'company_claim_approval_id' => 9,
                'user_id' => 9,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:50:28',
                'company_id' => 4,
                'claim_status_id_involved' => 0,
            ),
            9 => 
            array (
                'company_claim_approval_id' => 10,
                'user_id' => 10,
                'claim_approval_step_id' => 4,
                'company_claim_approval_cdate' => '2023-08-04 18:50:47',
                'company_id' => 3,
                'claim_status_id_involved' => 0,
            ),
        ));
        
        
    }
}