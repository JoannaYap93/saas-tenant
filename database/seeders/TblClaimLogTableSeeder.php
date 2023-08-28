<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblClaimLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_claim_log')->delete();
        
        \DB::table('tbl_claim_log')->insert(array (
            0 => 
            array (
                'claim_log_id' => 1,
                'claim_log_action' => 'Create Claim',
                'from_claim_status_id' => 1,
                'to_claim_status_id' => 1,
                'claim_log_created' => '2023-07-28 11:52:43',
                'claim_log_description' => 'Nora Created Claim',
                'claim_id' => 1,
                'claim_log_user_id' => 1,
            ),
        ));
        
        
    }
}