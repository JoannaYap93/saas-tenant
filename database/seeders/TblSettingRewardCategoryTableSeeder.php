<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingRewardCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_reward_category')->delete();
        
        \DB::table('tbl_setting_reward_category')->insert(array (
            0 => 
            array (
                'setting_reward_category_id' => 1,
                'setting_reward_category_name' => 'Worker Rewards',
            ),
            1 => 
            array (
                'setting_reward_category_id' => 2,
                'setting_reward_category_name' => 'Farm Manager Reward',
            ),
        ));
        
        
    }
}