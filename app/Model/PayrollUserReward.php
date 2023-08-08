<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollUserReward extends Model
    {
        protected $table = 'tbl_payroll_user_reward';
        protected $primaryKey = 'payroll_user_reward_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_user_reward_created';
        const UPDATED_AT = 'payroll_user_reward_updated';

        protected $fillable = [
            'payroll_user_id',
            'payroll_id',
            'setting_reward_id',
            'setting_reward_tier',
            'payroll_user_reward_amount',
        ];
    }
?>




