<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblWorkerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_worker')->delete();
        
        \DB::table('tbl_worker')->insert(array (
            0 => 
            array (
                'worker_id' => 1,
                'user_id' => 2,
                'worker_name' => 'Ali',
                'worker_mobile' => '60126635562',
                'worker_ic' => '931102102399',
                'company_id' => 1,
                'worker_type_id' => 1,
                'worker_status_id' => 1,
                'is_attendance_reward' => 0,
                'setting_reward_id' => NULL,
                'worker_created' => '2022-07-20 01:01:59',
                'worker_updated' => '2023-08-23 17:34:24',
                'setting_race_id' => 2,
                'worker_start_date' => '2022-07-20',
                'worker_resigned_date' => NULL,
                'is_suspended' => 0,
                'worker_default' => '{"company_land_id": 1, "company_land_name": "San Lee - 63", "company_expense_worker_detail": {"task": [{"qty": 0, "expense_id": 8, "expense_name": {"cn": "Mowing 割草", "en": "Mowing 割草"}, "expense_total": 480, "expense_value": 60, "setting_expense_overwrite_commission": 0}], "type": "", "status": 1, "timing": "", "status_name": {"cn": "Whole Day", "en": "Whole Day"}}}',
                'worker_role_id' => 1,
                'worker_wallet_amount' => NULL,
            ),
            1 => 
            array (
                'worker_id' => 2,
                'user_id' => 2,
                'worker_name' => 'Ah Meng',
                'worker_mobile' => '60198827221',
                'worker_ic' => '850102082399',
                'company_id' => 1,
                'worker_type_id' => 1,
                'worker_status_id' => 1,
                'is_attendance_reward' => 0,
                'setting_reward_id' => NULL,
                'worker_created' => '2022-07-20 01:02:53',
                'worker_updated' => '2023-08-23 17:35:28',
                'setting_race_id' => 1,
                'worker_start_date' => '2022-07-20',
                'worker_resigned_date' => NULL,
                'is_suspended' => 0,
                'worker_default' => '{"company_land_id": 1, "company_land_name": "Raub - 34", "company_expense_worker_detail": {"task": [{"qty": 0, "expense_id": 8, "expense_name": {"cn": "Mowing 割草", "en": "Mowing 割草"}, "expense_total": 480, "expense_value": 60, "setting_expense_overwrite_commission": 0}], "type": "", "status": 1, "timing": "", "status_name": {"cn": "Whole Day", "en": "Whole Day"}}}',
                'worker_role_id' => 1,
                'worker_wallet_amount' => NULL,
            ),
            2 => 
            array (
                'worker_id' => 3,
                'user_id' => 2,
                'worker_name' => 'Mutu',
                'worker_mobile' => '60192888271',
                'worker_ic' => '990927064521',
                'company_id' => 1,
                'worker_type_id' => 1,
                'worker_status_id' => 1,
                'is_attendance_reward' => 0,
                'setting_reward_id' => NULL,
                'worker_created' => '2022-07-20 01:13:26',
                'worker_updated' => '2023-08-23 17:59:03',
                'setting_race_id' => 5,
                'worker_start_date' => '2022-07-20',
                'worker_resigned_date' => NULL,
                'is_suspended' => 0,
                'worker_default' => '{"company_land_id": 2, "company_land_name": "Titi - 10", "company_expense_worker_detail": {"task": [{"qty": 0, "expense_id": 17, "expense_name": {"cn": "Crop-Dusting 喷药", "en": "Crop-Dusting 喷药"}, "expense_total": 300, "expense_value": 60, "setting_expense_overwrite_commission": 0}], "type": "", "status": 2, "timing": "AM", "time_slot": "7 AM - 12 PM", "status_name": {"cn": "Half Day", "en": "Half Day"}}}',
                'worker_role_id' => 1,
                'worker_wallet_amount' => NULL,
            ),
            3 => 
            array (
                'worker_id' => 4,
                'user_id' => 2,
                'worker_name' => 'Barsha',
                'worker_mobile' => '60192818275',
                'worker_ic' => '790927034524',
                'company_id' => 1,
                'worker_type_id' => 1,
                'worker_status_id' => 1,
                'is_attendance_reward' => 0,
                'setting_reward_id' => NULL,
                'worker_created' => '2022-07-20 01:13:26',
                'worker_updated' => '2023-08-23 17:59:03',
                'setting_race_id' => 4,
                'worker_start_date' => '2022-07-20',
                'worker_resigned_date' => NULL,
                'is_suspended' => 0,
                'worker_default' => '{"company_land_id": 2, "company_land_name": "Titi - 10", "company_expense_worker_detail": {"task": [{"qty": 0, "expense_id": 17, "expense_name": {"cn": "Crop-Dusting 喷药", "en": "Crop-Dusting 喷药"}, "expense_total": 300, "expense_value": 60, "setting_expense_overwrite_commission": 0}], "type": "", "status": 2, "timing": "AM", "time_slot": "7 AM - 12 PM", "status_name": {"cn": "Half Day", "en": "Half Day"}}}',
                'worker_role_id' => 1,
                'worker_wallet_amount' => NULL,
            ),
        ));
        
        
    }
}