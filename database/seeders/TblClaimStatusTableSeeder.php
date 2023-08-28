<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblClaimStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_claim_status')->delete();
        
        \DB::table('tbl_claim_status')->insert(array (
            0 => 
            array (
                'claim_status_id' => 1,
                'claim_status_name' => 'Pending',
                'claim_approval_step_id_next' => '1',
                'is_editable' => 0,
                'is_edit_claim' => 1,
            ),
            1 => 
            array (
                'claim_status_id' => 2,
                'claim_status_name' => 'Awaiting for Checking',
                'claim_approval_step_id_next' => '2',
                'is_editable' => 1,
                'is_edit_claim' => 0,
            ),
            2 => 
            array (
                'claim_status_id' => 3,
                'claim_status_name' => 'Awaiting for Verify',
                'claim_approval_step_id_next' => '3',
                'is_editable' => 1,
                'is_edit_claim' => 1,
            ),
            3 => 
            array (
                'claim_status_id' => 4,
                'claim_status_name' => 'Awaiting Approval',
                'claim_approval_step_id_next' => '4',
                'is_editable' => 1,
                'is_edit_claim' => 0,
            ),
            4 => 
            array (
                'claim_status_id' => 5,
                'claim_status_name' => 'Approved',
                'claim_approval_step_id_next' => '5',
                'is_editable' => 0,
                'is_edit_claim' => 1,
            ),
            5 => 
            array (
                'claim_status_id' => 6,
                'claim_status_name' => 'Completed',
                'claim_approval_step_id_next' => '6',
                'is_editable' => 0,
                'is_edit_claim' => 0,
            ),
            6 => 
            array (
                'claim_status_id' => 7,
            'claim_status_name' => 'Rejected (Permanent)',
                'claim_approval_step_id_next' => '7',
                'is_editable' => 0,
                'is_edit_claim' => 0,
            ),
            7 => 
            array (
                'claim_status_id' => 8,
            'claim_status_name' => 'Rejected (Resubmit)',
                'claim_approval_step_id_next' => '8',
                'is_editable' => 1,
                'is_edit_claim' => 0,
            ),
            8 => 
            array (
                'claim_status_id' => 9,
                'claim_status_name' => 'Cancelled',
                'claim_approval_step_id_next' => '9',
                'is_editable' => 0,
                'is_edit_claim' => 0,
            ),
        ));
        
        
    }
}