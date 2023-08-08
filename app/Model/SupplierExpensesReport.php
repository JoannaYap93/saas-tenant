<?php

namespace App\Model;

use Aws\Result;
use App\Model\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SupplierExpensesReport extends Model
{

    public static function get_supplier_expenses_report($search)
    {
        $company_id = auth()->user()->company_id;

            $query = "(SELECT SUM(tbl_company_expense_item.company_expense_item_total) as total_expenses,
                tbl_supplier.supplier_id as supplier,
                tbl_company_expense.company_id as company,
                tbl_company_expense.company_expense_month as month
                FROM tbl_company_expense_item
                JOIN tbl_company_expense on tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
                JOIN tbl_supplier on tbl_supplier.supplier_id = tbl_company_expense_item.supplier_id
                WHERE tbl_company_expense_item.supplier_id is NOT NULL
                AND tbl_company_expense.company_expense_status != 'deleted'";

        if(isset($search['supplier_id'])){
            $query .= " AND tbl_company_expense_item.supplier_id = {$search['supplier_id']}";
        }

        if (isset($search['year'])) {
            $query .= " AND tbl_company_expense.company_expense_year = {$search['year']}";
        }

        if (@$search['company_id']) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";
            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";;
            }
        }

        $query .=" GROUP BY month, supplier)";

        $result = DB::select($query);

        $expense_data_supplier = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($expense_data_supplier[$data->month][$data->supplier])) {
                    $expense_data_supplier[$data->month][$data->supplier] += $data->total_expenses;
                }else{
                    $expense_data_supplier[$data->month][$data->supplier] = $data->total_expenses;
                }
            }
        }

        $total_by_month_expense_data_supplier = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_month_expense_data_supplier[$data->month])) {
                    $total_by_month_expense_data_supplier[$data->month] += $data->total_expenses;
                }else{
                    $total_by_month_expense_data_supplier[$data->month] = $data->total_expenses;
                }
            }
        }

        $total_by_supplier_expense_data_supplier = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_supplier_expense_data_supplier[$data->supplier])) {
                    $total_by_supplier_expense_data_supplier[$data->supplier] += $data->total_expenses;
                }else{
                    $total_by_supplier_expense_data_supplier[$data->supplier] = $data->total_expenses;
                }
            }
        }

        $total_by_all_supplier = 0;

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_all_supplier)) {
                    $total_by_all_supplier += $data->total_expenses;
                }else{
                    $total_by_all_supplier = $data->total_expenses;
                }
            }
        }
        return ['expense_data_supplier' => $expense_data_supplier, 'total_by_month_expense_data_supplier' => $total_by_month_expense_data_supplier, 'total_by_supplier_expense_data_supplier' => $total_by_supplier_expense_data_supplier, 'total_by_all_supplier' => $total_by_all_supplier];
    }

    public static function get_supplier_expenses_report_detail($search)
    {
        $company_id = auth()->user()->company_id;

        $query_expense = "SELECT SUM(tbl_company_expense_item.company_expense_item_total) as total_expenses_item,
        tbl_company_expense.company_id as company,
        tbl_company_expense_item.setting_expense_id as setting_expense,
        tbl_company_expense.setting_expense_category_id as setting_expense_category,
        tbl_company_expense_item.supplier_id as supplier,
        tbl_company_expense.company_expense_day as day,
        tbl_company_expense.company_expense_month as company_expense_month
        FROM tbl_company_expense_item
        JOIN tbl_company_expense on tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
        WHERE tbl_company_expense_item.supplier_id is NOT NULL
        AND tbl_company_expense.company_expense_status != 'deleted'";

        if(isset($search['supplier'])){
            $query_expense .= " AND tbl_company_expense_item.supplier_id = {$search['supplier']}";
        }

        if(isset($search['year'])){
            $query_expense .= " AND tbl_company_expense.company_expense_year = {$search['year']}";
        }

        if(isset($search['month'])){
            $query_expense .= " AND tbl_company_expense.company_expense_month = {$search['month']}";
        }

        if (@$search['company_id']) {
            $query_expense .= " AND tbl_company_expense.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query_expense .= " AND tbl_company_expense.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query_expense .= " AND tbl_company_expense.company_id = {$company_id}";
            } else {
                $query_expense .= " AND tbl_company_expense.company_id <> 1";;
            }
        }

        // $query_expense .= " GROUP BY tbl_company_expense.company_expense_day, tbl_company_expense_item.setting_expense_id, tbl_company_expense.setting_expense_category_id";
        // $query_expense.= "groupBy day, setting_expense, setting_expense_category";
        $query_expense .=" GROUP BY day, setting_expense, setting_expense_category";

        $result_expense = DB::select($query_expense);
        // dd($result_expense);

        $daily_expenses = [];

        if ($result_expense){
            foreach($result_expense as $data){
                if (isset($daily_expenses[$data->day][$data->setting_expense_category])) {
                        $daily_expenses[$data->day][$data->setting_expense_category] += $data->total_expenses_item;
                }else{
                    $daily_expenses[$data->day][$data->setting_expense_category] = $data->total_expenses_item;
                }
            }
        }
        // dd($daily_expenses);

        $total_by_expense_category = [];

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (@$total_by_expense_category[$data->setting_expense_category]) {
                    $total_by_expense_category[$data->setting_expense_category] += $data->total_expenses_item;
                } else {
                    $total_by_expense_category[$data->setting_expense_category] = $data->total_expenses_item;
                }
            }
        }
        // dd($total_by_expense_category);

        $supplier_item_expense = [];

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (isset($supplier_item_expense[$data->setting_expense_category][$data->setting_expense])) {
                    $supplier_item_expense[$data->setting_expense_category][$data->setting_expense] += $data->total_expenses_item;
                }else{
                    $supplier_item_expense[$data->setting_expense_category][$data->setting_expense] = $data->total_expenses_item;
                }
            }
        }

        $total_supplier_item_expense = 0;

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (isset($total_supplier_item_expense)) {
                    $total_supplier_item_expense += $data->total_expenses_item;
                }else{
                    $total_supplier_item_expense = $data->total_expenses_item;
                }
            }
        }

        $total_by_day_supplier_item_expense = [];

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (isset($total_by_day_supplier_item_expense[$data->day])) {
                    $total_by_day_supplier_item_expense[$data->day] += $data->total_expenses_item;
                }else{
                    $total_by_day_supplier_item_expense[$data->day] = $data->total_expenses_item;
                }
            }
        }



        return ['daily_expenses' => $daily_expenses, 'supplier_item_expense' => $supplier_item_expense, 'total_supplier_item_expense' => $total_supplier_item_expense, 'total_by_day_supplier_item_expense' => $total_by_day_supplier_item_expense, 'total_by_expense_category' => $total_by_expense_category];
    }
}
