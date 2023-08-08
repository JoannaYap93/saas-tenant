<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use App\Model\CompanyExpenseWorker;
use Illuminate\Database\Eloquent\Model;

class WorkerAttendanceReport extends Model
{
    public static function get_worker_attendance_report($search)
    {
        $result = null;

        DB::statement('SET SESSION group_concat_max_len = 1000000');
        $query = CompanyExpenseWorker::query();
        $query->selectRaw('tbl_worker.worker_role_id,
                            tbl_company_expense_worker.worker_id,
                            tbl_company_expense.company_expense_id as id,
                            COUNT(tbl_company_expense_worker.worker_id) as workdays,
                            tbl_company_expense.company_expense_day as day,
                            SUM(tbl_company_expense_worker.company_expense_worker_total) as salary,
                            group_concat(tbl_company_expense_worker.company_expense_worker_detail) as json');
        $query->join('tbl_worker', 'tbl_worker.worker_id', 'tbl_company_expense_worker.worker_id')
            ->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_worker.company_expense_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
            ->where('tbl_company_expense_worker.company_expense_worker_total', '>',0)
            ->where('tbl_company_expense.company_expense_status', '<>', 'deleted');

        if (@$search['company_id']) {
            $query->where('tbl_company.company_id', $search['company_id']);
        } elseif (@$search['company_cb_id']) {
            $cb_id = $search['company_cb_id'];
            $query->whereIn('tbl_company.company_id', $cb_id);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[] = $user_company->company_id;
                }
                $query->whereIn('tbl_company.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_company_expense.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_company.company_id', '<>', 1);
            }
        }

        if(@$search['month']){
            $query->where('tbl_company_expense.company_expense_month', $search['month']);
        }

        if(@$search['year']){
            $query->where('tbl_company_expense.company_expense_year', $search['year']);
        }
        if(@$search['worker_role_id']){
            $query->where('tbl_worker.worker_role_id', $search['worker_role_id']);
        }
        // $query->where('tbl_company_expense_worker.worker_id', 557);

        $query->where('tbl_worker.is_suspended', '!=', 1);
        // $query->where('tbl_worker.worker_status_id', '!=', 3);

        $query->where('company_expense_worker_total','>',0);
        $query->groupBy('day','worker_id');

        $result = $query->get();
        // dd($result);
        $worker_day = [];
        foreach($result as $data){
            if(isset($worker_day[$data->worker_id][$data->day])){
                $worker_day[$data->worker_id][$data->day] = $data->json;
            }else{
                $worker_day[$data->worker_id][$data->day] = $data->json;
            }
        }
        $worker_salary = [];
        foreach($result as $data){
            if(isset($worker_salary[$data->worker_id])){
                $worker_salary[$data->worker_id] += $data->salary;
            }else{
                $worker_salary[$data->worker_id] = $data->salary;
            }
        }

        return ['worker_day' => $worker_day, 'worker_salary' => $worker_salary];
    }
}
