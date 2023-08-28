<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblClaimItemLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_claim_item_log')->delete();
        
        \DB::table('tbl_claim_item_log')->insert(array (
            0 => 
            array (
                'claim_item_log_id' => 1,
                'claim_item_id' => 1,
                'claim_item_log_created' => '2023-07-28 11:53:46',
                'claim_item_log_action' => 'Add Claim Item',
                'claim_item_log_remark' => 'Nora added new Claim item - {"en":"Staff Meal","cn":"\\u5458\\u5de5\\u805a\\u9910"}',
                'claim_item_log_admin_id' => 1,
                'claim_id' => 1,
            ),
            1 => 
            array (
                'claim_item_log_id' => 2,
                'claim_item_id' => 2,
                'claim_item_log_created' => '2023-07-28 11:53:47',
                'claim_item_log_action' => 'Add Claim Item',
                'claim_item_log_remark' => 'Nora added new Claim item - {"en":"Wash Car","cn":"\\u6d17\\u8f66"}',
                'claim_item_log_admin_id' => 1,
                'claim_id' => 1,
            ),
            2 => 
            array (
                'claim_item_log_id' => 3,
                'claim_item_id' => 3,
                'claim_item_log_created' => '2023-07-28 11:53:48',
                'claim_item_log_action' => 'Add Claim Item',
                'claim_item_log_remark' => 'Nora added new Claim item - {"en":"TNB","cn":"\\u7535\\u8d39"}',
                'claim_item_log_admin_id' => 1,
                'claim_id' => 1,
            ),
        ));
        
        
    }
}