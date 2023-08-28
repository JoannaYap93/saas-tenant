<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSettingRewardTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_setting_reward')->delete();
        
        \DB::table('tbl_setting_reward')->insert(array (
            0 => 
            array (
                'setting_reward_id' => 1,
                'setting_reward_name' => 'OVERTIME',
                'setting_reward_slug' => 'ot',
                'setting_reward_description' => 'OVERTIME',
                'setting_reward_category_id' => 1,
                'setting_reward_json' => '[{"day": 2, "tier": 1, "amount": 14, "full_attendance": "0"}]',
                'setting_reward_status' => 'active',
                'company_id' => 0,
                'is_default' => 1,
                'setting_reward_created' => NULL,
                'setting_reward_updated' => NULL,
            ),
        ));
        
        
    }
}