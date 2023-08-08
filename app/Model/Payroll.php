<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class Payroll extends Model
    {
        protected $table = 'tbl_payroll';
        protected $primaryKey = 'payroll_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_created';
        const UPDATED_AT = 'payroll_updated';

        protected $fillable = [
            'company_id',
            'payroll_month',
            'payroll_year',
            'payroll_total_amount',
            'payroll_total_reward',
            'payroll_grandtotal',
            'payroll_total_user_item_employee',
            'payroll_total_user_item_employer',
            'payroll_total_paid_out',
            'payroll_status',
        ];

        public static function get_records($search)
        {
            $payroll = Payroll::query();

            if (@$search['company_id']) {
                $payroll->where('company_id', $search['company_id']);
            }else{
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = array();
                    foreach (auth()->user()->user_company as $key => $user_company) {
                        $ids[$key] = $user_company->company_id;
                    }
                    $payroll->whereIn('company_id', $ids);

                } else if (auth()->user()->company_id != 0) {
                    $payroll->where('company_id', auth()->user()->company_id);
                } else {
                    $payroll->where('company_id', '<>', 1);
                }
            }

            if(@$search['payroll_year']){
                $payroll->where('payroll_year', $search['payroll_year']);
            }

            if(@$search['payroll_month']){
                $payroll->where('payroll_month', $search['payroll_month']);
            }

            $payroll->where('payroll_status', '<>', 'Deleted');

            $payroll->orderBy('payroll_created', 'DESC');
            return $payroll->paginate(15);
        }

        public static function check_payroll_by_company($company_id, $payroll_month, $payroll_year)
        {
            $result = null;
            $query = Payroll::query();
            $query->where('company_id', $company_id);
            $query->where('payroll_month', $payroll_month);
            $query->where('payroll_year', $payroll_year);
            $query->where('payroll_status', '<>', 'Deleted');
            $result = $query->first();

            if($result){
                return true;
            }

            return false;
        }

        public static function get_payroll_details($payroll_id)
        {
            $payroll_details = array();

            $query = "SELECT tbl_worker.worker_role_id,
                        tbl_worker.worker_id,
                        tbl_worker.worker_name,
                        tbl_payroll.payroll_total_amount,
                        tbl_payroll.payroll_total_reward,
                        tbl_payroll.payroll_grandtotal,
                        tbl_payroll.payroll_total_paid_out,
                        tbl_payroll_user.payroll_user_amount,
                        tbl_payroll_user.payroll_user_reward,
                        tbl_payroll_user.payroll_user_total,
                        tbl_payroll_user.payroll_user_paid_out,
                        tbl_payroll_user_item.payroll_item_id,
                        tbl_payroll_user_item.payroll_item_type,
                        tbl_payroll_user_item.payroll_user_item_amount,
                        tbl_payroll_item.payroll_item_name
                        FROM tbl_payroll
                        INNER JOIN tbl_payroll_user
                        ON tbl_payroll_user.payroll_id = tbl_payroll.payroll_id
                        INNER JOIN tbl_worker
                        ON tbl_worker.worker_id = tbl_payroll_user.worker_id
                        INNER JOIN tbl_payroll_user_item
                        ON tbl_payroll_user_item.payroll_user_id = tbl_payroll_user.payroll_user_id
                        INNER JOIN tbl_payroll_item
                        ON tbl_payroll_item.payroll_item_id = tbl_payroll_user_item.payroll_item_id
                        WHERE tbl_payroll.payroll_id = " . $payroll_id . ";";

            $result = DB::select($query);

            foreach($result as $data){
                if(!isset($payroll_details[$data->worker_id])){
                    $payroll_details[$data->worker_id] = ["worker_role_id" => $data->worker_role_id , "worker_id" => $data->worker_id , "worker_name" => $data->worker_name, "payroll_user_amount" => $data->payroll_user_amount, "payroll_user_reward" => $data->payroll_user_reward, "payroll_user_total" => $data->payroll_user_total, "payroll_user_paid_out" => $data->payroll_user_paid_out];
                }

                if(!isset($payroll_details[$data->worker_id][$data->payroll_item_id][$data->payroll_item_type])){
                    $payroll_details[$data->worker_id][$data->payroll_item_id][$data->payroll_item_type] = $data->payroll_user_item_amount;
                }
            }

            return $payroll_details;
        }

        public function company()
        {
            return $this->belongsTo(Company::class, 'company_id', 'company_id');
        }

        public function payroll_user()
        {
            return $this->hasMany(PayrollUser::class, 'payroll_id', 'payroll_id');
        }

        public function payroll_user_reward()
        {
            return $this->hasMany(PayrollUserReward::class, 'payroll_id', 'payroll_id');
        }

        public function payroll_log()
        {
            return $this->hasMany(PayrollLog::class, 'payroll_id','payroll_id');
        }
    }
?>
