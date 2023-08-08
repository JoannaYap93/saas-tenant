<?php
    namespace App\Model;

    use App\Model\SettingExpense;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class PayrollItem extends Model
    {
        protected $table = 'tbl_payroll_item';
        protected $primaryKey = 'payroll_item_id';

        protected $dateFormat = 'Y-m-d H:i:s';
        const CREATED_AT = 'payroll_item_created';
        const UPDATED_AT = 'payroll_item_updated';

        protected $fillable = [
            'payroll_item_name',
            'payroll_item_status',
            'payroll_item_type',
            'is_compulsory',
            'is_employer',
            'is_deleted',
            'setting_expense_id'
        ];

        public static function get_records($search)
        {
            $query = PayrollItem::query();
            if (@$search['freetext']) {
                $freetext = $search['freetext'];
                $query->where(function ($q) use ($freetext) {
                    $q->orWhere('payroll_item_name', 'like', '%' . $freetext . '%');
                    $q->orWhere('payroll_item_status', 'like', '%' . $freetext . '%');
                });
            }

            if (@$search['payroll_item_status']) {
                $query->where('payroll_item_status', $search['payroll_item_status']);
            }

            if (@$search['payroll_item_type']) {
                $query->where('payroll_item_type', $search['payroll_item_type']);
            }

            if (@$search['worker_role_id']) {
                $query->whereHas('payroll_item_worker_role', function ($q) use($search){
                    $q->where('tbl_payroll_item_worker_role.worker_role_id', $search['worker_role_id']);
                });
            }

            $query->where('is_deleted', '!=', 1 );

            $query->orderBy('payroll_item_created', 'ASC');

            $result = $query->paginate(10);

            return $result;
        }

        public static function get_payroll_items($search = null)
        {
            $result = null;

            $query = PayrollItem::query();
            $query->where('payroll_item_status', 'Available');
            $query->where('is_deleted', '!=', 1);

            if(@$search['payroll_item_type']){
                $query->where('payroll_item_type', $search['payroll_item_type']);
            }

            $query->orderBy('payroll_item_name');
            $result = $query->get();
            return $result;
        }

        public static function get_enum_sel($column_name)
        {
            $sel_list = array();
            $query = "SHOW COLUMNS FROM tbl_payroll_item LIKE '{$column_name}'";
            $result = DB::select($query);

            if ($result) {
                $sel_list = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $result[0]->Type));
                $sel_list = array_combine($sel_list, $sel_list);
            }

            return $sel_list;
        }

        public function payroll_item_worker_role()
        {
            return $this->belongsToMany(WorkerRole::class, 'tbl_payroll_item_worker_role', 'payroll_item_id', 'worker_role_id');
        }

        public function setting_expense()
        {
            return $this->belongsTo(SettingExpense::class, 'setting_expense_id', 'setting_expense_id');
        }

    }
?>
