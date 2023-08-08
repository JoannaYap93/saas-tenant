<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollLog extends Model
    {
        protected $table = 'tbl_payroll_log';
        protected $primaryKey = 'payroll_log_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_log_created';
        const UPDATED_AT = null;

        protected $fillable = [
            'payroll_id',
            'payroll_log_action',
            'payroll_log_description',
            'payroll_log_remark',
            'payroll_log_created',
            'user_id'
        ];

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id', 'user_id');
        }
    }
?>
