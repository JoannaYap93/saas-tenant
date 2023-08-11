<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Supplier;
use App\Libraries\Wfunction;
use Illuminate\Http\Request;
use App\Model\SettingExpense;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\SettingExpenseCategory;
use App\Model\SupplierExpensesReport;
use App\Exports\SupplierExpensesExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SupplierExpensesReportController extends Controller
{
    public function supplier_expenses_report(Request $request)
    {
        $validation = null;

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('supplier_expenses_report');
                    $search['year'] = $request->input('year');
                    $search['company_id'] = $request->input('company_id');
                    $search['supplier_id'] = $request->input('supplier_id');

                    Session::put('supplier_expenses_report', $search);
                    break;
                case 'reset':
                    Session::forget('supplier_expenses_report');
                    break;
                case 'export':
                    $search['year'] = $request->input('year');
                    $search['company_id'] = $request->input('company_id');
                    $search['supplier_id'] = $request->input('supplier_id');

                    $records = SupplierExpensesReport::get_supplier_expenses_report($search);

                    Session::put('supplier_expenses_report', $search);
                    $supplier_sel = Supplier::get_supplier_sel($search);
                    return Excel::download(new SupplierExpensesExport('components.supplier_expenses_report_component', $records, $supplier_sel, $search), 'Supplier_Expenses_Report '. $search['year'].'.xlsx');
                    break;
                }
             }

        $search = Session::has('supplier_expenses_report') ? Session::get('supplier_expenses_report') : array("year" => date('Y'));

        $wfunction = new Wfunction();
        $year_sel = $wfunction->year_sel();
        $month_sel = $wfunction->month_sel();
        $records = SupplierExpensesReport::get_supplier_expenses_report($search);
        $supplier_sel = Supplier::get_supplier_sel($search);
        $company_sel = Company::get_company_sel();

        return view('report.supplier_expenses_report', [
            'page_title' => 'Supplier Expenses Reporting',
            'submit' => route('supplier_expenses_report', ['tenant' => tenant('id')]),
            'search' => $search,
            'year_sel' => $year_sel,
            'month_sel' => $month_sel,
            'company_sel' => $company_sel,
            'supplierSel' => $supplier_sel,
            'current_year' => date('Y'),
            'records' => $records,
        ])->withErrors($validation);
    }

    public function supplier_expenses_report_detail($year, $month, $supplier_id, $company_id){

        $search['year'] = $year;
        $search['month'] = $month;
        $search['supplier'] = $supplier_id;
        if($company_id != 0){
            $search['company_id'] = $company_id;
        };

        $supplier_item_data = SupplierExpensesReport::get_supplier_expenses_report_detail($search);

        $day_array = [];
        $days_range = Carbon::now()->month($search['month'])->daysInMonth;
        for($i = 1; $i < $days_range+1; $i++)
        {
            $day_array[$i] = $i;
        }
        // dd($day_array);
        // Log::info($company_id);

        return view('report.supplier_expenses_report_detail', [
            'records' => $supplier_item_data,
            'page_title' => 'Supplier Expenses Detail',
            'daySel' => $day_array,
            'expense_category' => SettingExpenseCategory::get_expense_category_for_pnl_reporting(),
            'expense_item' => SettingExpense::get_expense_for_report(),
            'supplier' => Supplier::where('supplier_id', $supplier_id )->first()
        ]);
    }

}
