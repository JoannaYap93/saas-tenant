<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollUserItem extends Model
    {
        protected $table = 'tbl_payroll_user_item';
        protected $primaryKey = 'payroll_user_item_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_user_item_created';
        const UPDATED_AT = 'payroll_user_item_updated';

        protected $fillable = [
            'payroll_item_id',
            'payroll_user_id',
            'payroll_item_type',
            'payroll_user_item_amount',
            'payroll_user_item_created',
            'payroll_user_item_updated'
        ];

        public function payroll_item()
        {
            return $this->belongsTo(PayrollItem::class, 'payroll_item_id', 'payroll_item_id');
        }

        public function payroll_user()
        {
            return $this->belongsTo(PayrollUser::class, 'payroll_user_id', 'payroll_user_id');
        }
    }
?>
