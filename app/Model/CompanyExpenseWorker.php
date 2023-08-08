<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CompanyExpenseWorker extends Model
{
    protected $table = 'tbl_company_expense_worker';
    protected $primaryKey = 'company_expense_worker_id';
    const CREATED_AT = 'company_expense_worker_created';
    const UPDATED_AT = 'company_expense_worker_updated';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $monthFormat = 'm';
    protected $yearFormat = 'Y';

    protected $fillable = [
        'worker_id', 'company_expense_id', 'company_expense_worker_detail', 'company_expense_worker_total', 'company_expense_worker_created', 'company_expense_worker_updated',
    ];

    public function company_expense()
    {
        return $this->belongsTo(CompanyExpense::class, 'company_expense_id');
    }

    public function company_expenses()
    {
        return $this->hasMany(CompanyExpense::class, 'company_expense_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
    }

    // public function company_expense_item()
    // {
    //     return $this->hasMany(CompanyExpenseItem::class, 'company_expense_id');
    // }

    public static function get_records($search)
    {
        $query = CompanyExpenseWorker::query();


        $query = $query->paginate(10);

        return $query;
    }

    public static function get_company_expense_worker_id()
    {
        $query = CompanyExpenseWorker::with('company_expenses');
        $query->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_worker.company_expense_id')
              ->groupByRaw('tbl_company_expense_worker.company_expense_worker_id')
              ->orderBy('tbl_company_expense_worker.company_expense_worker_id', 'ASC');
        $query->whereHas('company_expenses', function($q) {
            $q->where('company_expense_status', '<>', 'deleted');
        });

        $worker = $query->get();
        return $worker;
    }

    public static function get_company_expense_worker_by_company($search)
    {
        $result = null;

        DB::statement('SET SESSION group_concat_max_len = 1000000');
        $query = CompanyExpenseWorker::query();
        $query->selectRaw('tbl_worker.worker_role_id,
                            tbl_company_expense_worker.worker_id,
                            COUNT(tbl_company_expense_worker.worker_id) as workdays,
                            SUM(tbl_company_expense_worker.company_expense_worker_total) as salary,
                            group_concat(tbl_company_expense_worker.company_expense_worker_detail) as json');
        $query->join('tbl_worker', 'tbl_worker.worker_id', 'tbl_company_expense_worker.worker_id');
        $query->whereHas('company_expense', function($q) use($search){
            $q->where('setting_expense_category_id', 2);
            $q->where('company_id', $search['company_id']);
            $q->where('company_expense_month', $search['payroll_month']);
            $q->where('company_expense_year', $search['payroll_year']);
            $q->where('company_expense_status', '<>', 'deleted');
        });
        $query->where('company_expense_worker_total','>',0);
        $query->groupBy('worker_id');

        $result = $query->get();

        return $result;
    }

    public static function get_company_expense_worker_detail($search)
    {
        $query = CompanyExpenseWorker::query();
        $query->selectRaw('tbl_company_expense_worker.company_expense_id, 
                            tbl_company_expense_worker.worker_id, 
                            tbl_company_expense_worker.company_expense_worker_detail, 
                            tbl_company_expense_worker.company_expense_worker_total, 
                            tbl_company_expense.company_expense_day, 
                            tbl_company_expense.company_expense_month, 
                            tbl_company_expense.company_expense_year')
        ->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_worker.company_expense_id')
        ->where('tbl_company_expense.company_expense_status', '<>', 'deleted');

        if(@$search['company_id']){
            $query->where('tbl_company_expense.company_id', $search['company_id']);
        }

        if(@$search['payroll_month']){
            $query->where('tbl_company_expense.company_expense_month', $search['payroll_month']);
        }

        if(@$search['payroll_year']){
            $query->where('tbl_company_expense.company_expense_year', $search['payroll_year']);
        }

        $worker = $query->get();
        return $worker;
    }
}
