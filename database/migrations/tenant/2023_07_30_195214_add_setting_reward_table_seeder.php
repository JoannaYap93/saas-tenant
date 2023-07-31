<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::table('tbl_setting_reward')->insert(array (
            0 => 
            array (
                'setting_reward_id' => 1,
                'setting_reward_name' => 'Attendance',
                'setting_reward_slug' => 'attendance',
                'setting_reward_description' => 'Attendance description',
                'setting_reward_category_id' => 1,
                'setting_reward_json' => '[{"day": "2", "tier": "1", "amount": "200", "full_attendance": "0"}, {"day": "", "tier": "2", "amount": "400", "full_attendance": "1"}]',
                'setting_reward_status' => 'active',
                'company_id' => 0,
                'is_default' => 1,
                'setting_reward_created' => '2022-08-25 14:09:06',
                'setting_reward_updated' => '2022-08-25 14:31:55',
            ),
            1 => 
            array (
                'setting_reward_id' => 2,
                'setting_reward_name' => 'Special',
                'setting_reward_slug' => '',
                'setting_reward_description' => 'Special description',
                'setting_reward_category_id' => 1,
                'setting_reward_json' => '[{"day": "2", "tier": "1", "amount": "200", "full_attendance": "0"}, {"day": null, "tier": "2", "amount": "450", "full_attendance": "1"}]',
                'setting_reward_status' => 'active',
                'company_id' => 0,
                'is_default' => 1,
                'setting_reward_created' => '2022-08-25 14:11:56',
                'setting_reward_updated' => '2022-08-25 14:32:20',
            ),
            2 => 
            array (
                'setting_reward_id' => 10,
                'setting_reward_name' => 'Testing Reward',
                'setting_reward_slug' => '',
                'setting_reward_description' => 'Testing',
                'setting_reward_category_id' => 1,
                'setting_reward_json' => '[{"day": "1", "tier": "1", "amount": "100", "full_attendance": "1"}, {"day": null, "tier": null, "amount": null, "full_attendance": "0"}]',
                'setting_reward_status' => '',
                'company_id' => 0,
                'is_default' => 0,
                'setting_reward_created' => NULL,
                'setting_reward_updated' => NULL,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
