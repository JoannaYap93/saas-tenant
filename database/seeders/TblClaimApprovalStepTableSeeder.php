<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblClaimApprovalStepTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_claim_approval_step')->delete();
        
        \DB::table('tbl_claim_approval_step')->insert(array (
            0 => 
            array (
                'claim_approval_step_id' => 1,
                'claim_approval_step_name' => 'Check',
            ),
            1 => 
            array (
                'claim_approval_step_id' => 2,
                'claim_approval_step_name' => 'Verify',
            ),
            2 => 
            array (
                'claim_approval_step_id' => 3,
                'claim_approval_step_name' => 'Approve',
            ),
            3 => 
            array (
                'claim_approval_step_id' => 4,
                'claim_approval_step_name' => 'Account Check',
            ),
            4 => 
            array (
                'claim_approval_step_id' => 5,
                'claim_approval_step_name' => 'Payment',
            ),
            5 => 
            array (
                'claim_approval_step_id' => 6,
                'claim_approval_step_name' => 'Completed',
            ),
            6 => 
            array (
                'claim_approval_step_id' => 7,
            'claim_approval_step_name' => 'Rejected (Resubmit)',
            ),
            7 => 
            array (
                'claim_approval_step_id' => 8,
            'claim_approval_step_name' => 'Rejected (Permanent)',
            ),
            8 => 
            array (
                'claim_approval_step_id' => 9,
                'claim_approval_step_name' => 'Cancelled',
            ),
        ));
        
        
    }
}