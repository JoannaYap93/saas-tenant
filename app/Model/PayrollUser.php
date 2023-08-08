<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollUser extends Model
    {
        protected $table = 'tbl_payroll_user';
        protected $primaryKey = 'payroll_user_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_user_created';
        const UPDATED_AT = 'payroll_user_updated';

        protected $fillable = [
            'payroll_id',
            'worker_id',
            'payroll_user_amount',
            'payroll_user_reward',
            'payroll_user_total',
            'payroll_user_item_employee',
            'payroll_user_item_employer',
            'payroll_user_paid_out',
        ];

        public function worker()
        {
            return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
        }

        public function payroll_user_item()
        {
            return $this->hasMany(PayrollUserItem::class, 'payroll_user_id', 'payroll_user_id');
        }
    }
?>
