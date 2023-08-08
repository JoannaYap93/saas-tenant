<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollItemWorkerRole extends Model
    {
        protected $table = 'tbl_payroll_item_worker_role';
        protected $primaryKey = 'payroll_item_worker_role_id';
        public $timestamps = false;

        protected $fillable = [
            'payroll_item_id',
            'worker_role_id'
        ];
    }
?>
