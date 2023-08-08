<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Product;
use App\Model\Reporting;
use Illuminate\Http\Request;
use App\Model\SettingExpense;
use App\Model\BudgetEstimated;
use App\Model\ProductCategory;
use App\Model\BudgetEstimatedLog;
use App\Model\CompanyExpenseItem;
use App\Model\BudgetEstimatedItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\SettingExpenseCategory;
use App\Exports\BudgetEstimatedExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BudgetEstimateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $search = [];
        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    session(['budget_estimate_search' => [
                            'company_id' => $request->input('company_id'),
                            'year' => $request->input('year')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('budget_estimate_search');
                    break;
            }
        }

        $search = session('budget_estimate_search') ?? array();

        return view('budget_estimate_reporting.listing', [
            'search' => $search,
            'submit' => route('budget_estimate_report_listing'),
            'company_sel' => Company::get_company_sel(),
            'records' => BudgetEstimated::get_budget_estimated($search),
        ]);
    }

    public function listing_reporting(Request $request)
    {
        $search = [];
        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    session(['budget_estimate_search' => [
                            'company_id' => $request->input('company_id'),
                            'year' => $request->input('year')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('budget_estimate_search');
                    break;
                case 'export':
                        // $export = true;
                        break;
            }
        }


        $search = session('budget_estimate_search') ?? array();

        return view('report.budget_estimated_reporting.listing', [
            'search' => $search,
            'submit' => route('budget_estimate_report_listing_reporting'),
            'company_sel' => Company::get_company_sel(),
            'records' => BudgetEstimated::get_budget_estimated($search),
        ]);
    }

    public function view_monthly_budget_estimate_report(Request $request, $budget_estimated_id)
    {
        $company = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $tittle = $company->budget_estimated_title;
        $years = $company->budget_estimated_year;
        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    session(['budget_estimate_search' => [
                        'month' => $request->input('month'),
                        'year' => $company->budget_estimated_year
                    ]]);
                    break;
                case 'reset':
                    session()->forget('budget_estimate_search');
                    break;
                case 'export':
                    $search['month'] = $request->input('month');
                    $year = $years;
                    $products = Product::get_by_company_budget_estimate2($company->company_id);
                    $product_category = ProductCategory::get_product_category_for_report();
                    $expense_category = SettingExpenseCategory::get_expense_category_for_company_expense_report();
                    $expense_item = SettingExpense::get_setting_expense_for_company_pnl();
                    $company = Company::get_by_id($company->company_id);
                    $monthSel = Reporting::get_month_w_filter($search);

                     //Remark: Budget Estimate
                    $record_sales_budget = BudgetEstimated::get_sales_budget_estimate_report($search,$budget_estimated_id);
                    $record_expense_budget = BudgetEstimated::get_expense_budget_estimate_report($search,$budget_estimated_id);

                    //Remark: Actual
                    $record_sales = BudgetEstimated::get_sales_for_report($search,$budget_estimated_id);
                    $record_expense = BudgetEstimated::get_expense_for_report($search,$budget_estimated_id);
                    $record_expense_item = BudgetEstimated::get_expense_item_for_report($search,$budget_estimated_id);

                    Session::put('budget_estimate_search', $search);
                    return Excel::download(new BudgetEstimatedExport('components/budget_estimate_report_component', $search, $products, $product_category, $expense_category, $expense_item, $company, $monthSel, $record_sales_budget, $record_expense_budget, $record_sales, $record_expense,$record_expense_item, $year), $tittle.' '.$company->company_name .' Budget Estimated Report '. $year.'.xlsx');
                    break;
            }
        }

        $search = session('budget_estimate_search') ?? array();

        return view('report.budget_estimated_reporting.report', [
            'search' => $search,
            'year' => $years,
            'submit' => route('view_monthly_budget_estimate_report',$budget_estimated_id),
            'products' => Product::get_by_company_budget_estimate2($company->company_id),
            'product_category' =>ProductCategory::get_product_category_for_report(),
            'expense_category' => SettingExpenseCategory::get_expense_category_for_company_expense_report(),
            'expense_item' => SettingExpense::get_setting_expense_for_company_pnl(),
            'company' => Company::get_by_id($company->company_id),
            'monthSel' => Reporting::get_month_w_filter($search),

            //Remark: Budget Estimate
            'record_sales_budget' => BudgetEstimated::get_sales_budget_estimate_report($search,$budget_estimated_id),
            'record_expense_budget' => BudgetEstimated::get_expense_budget_estimate_report($search,$budget_estimated_id),

             //Remark: Actual
             'record_sales' => BudgetEstimated::get_sales_for_report($search,$budget_estimated_id),
             'record_expense' => BudgetEstimated::get_expense_for_report($search,$budget_estimated_id),
             'record_expense_item' => BudgetEstimated::get_expense_item_for_report($search,$budget_estimated_id),
        ]);
    }

    public function add(Request $request){
        $post = null;
        $validator = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'year' => 'required',
                'title' => 'required',
            ])->setAttributeNames([
                'company_id' => 'Company',
                'year' => 'Year',
                'title' => 'Title',
            ]);

            if (!$validator->fails()) {
                $budget_estimated = BudgetEstimated::create([
                    'budget_estimated_title' => $request->input('title'),
                    'budget_estimated_year' => $request->input('year'),
                    'company_id' =>  $request->input('company_id'),
                    'budget_estimated_amount' => 0,
                    'is_deleted' => 0,
                ]);

                BudgetEstimatedLog::create([
                    'budget_estimated_id' => $budget_estimated->budget_estimated_id,
                    'user_id' => auth()->user()->user_id,
                    'budget_estimated_log_action' => 'Budget Estimate Added by ' . auth()->user()->user_fullname,
                ]);

                Session::flash('success_msg', 'Successfully added ' . $request->input('title'));
                return redirect()->route('budget_estimate_report_listing');
            }
            $post = (object) $request->all();
        }

        return view('budget_estimate_reporting.form', [
            'post' => $post,
            'title' => 'Add',
            'company_sel' => Company::get_company_sel(),
        ])->withErrors($validator);
    }

    public function edit(Request $request, $budget_estimated_id){
        $data = BudgetEstimated::find($budget_estimated_id);

        if($request->ajax()){
            $data_item = BudgetEstimatedItem::where("budget_estimated_id", $request->budget_id)
            ->where("budget_estimated_item_month", $request->month)
            ->where("budget_estimated_item_year", $request->year)
            ->where("budget_estimated_item_type", (int)$request->type)
            ->where("budget_estimated_item_type_value", $request->type_value)->first();

            $amount = (double)$request->amount;
            if($amount <> null || $amount <> 0){
                if($data_item)
                {
                    $data_item->update([
                        'budget_estimated_item_amount' => $request->amount,
                    ]);
                }else{
                    BudgetEstimatedItem::create([
                        'budget_estimated_id' => $request->budget_id,
                        'company_id' => $request->company_id,
                        'budget_estimated_item_month' => $request->month,
                        'budget_estimated_item_year' => $request->year,
                        'budget_estimated_item_type' => $request->type,
                        'budget_estimated_item_type_value' => $request->type_value,
                        'budget_estimated_item_amount' => $amount,
                    ]);
                }
            }else{
                if($data_item){
                    $data_item->delete();
                }
            }

            BudgetEstimated::where("budget_estimated_id", $request->budget_id)
            ->update([
                'budget_estimated_amount' => $request->total,
            ]);

            return response()->json(['success' => true]);
        }

        return view('budget_estimate_reporting.edit', [
            'data' => $data,
            'company' => Company::find($data->company_id),
            'company_sel' => Company::get_company_sel(),
            'month_sel' => Reporting::get_month(),
            'product_category' => ProductCategory::get_product_category_for_report(),
            'product_sel' => Product::get_by_company_budget_estimate_list($data->company_id),
            'expense_category' => SettingExpenseCategory::get_expense_for_budget_estimate($data->company_id),
            'expense_item' => SettingExpense::get_setting_expense_for_company_pnl(),
            'budget_estimated_item' => BudgetEstimatedItem::get_items_by_budget_id($data),
        ]);
    }

    public function delete(Request $request){
        $budget_estimate = BudgetEstimated::find($request->input('budget_estimate_report_id'));

        if(!$budget_estimate){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('budget_estimate_report_listing');
        }

        $budget_estimate->update([
            'is_deleted' => 1,
        ]);

        BudgetEstimatedLog::insert([
            'budget_estimated_id' => $request->input('budget_estimate_report_id'),
            'user_id' => auth()->user()->user_id,
            'budget_estimate_log_created' => now(),
            'budget_estimated_log_action' => 'Budget Estimate Deleted by ' . auth()->user()->user_fullname,
        ]);

        Session::flash('success_msg', "Successfully Deleted Budget Estimate.");
        return redirect()->route('budget_estimate_report_listing');
    }
}
