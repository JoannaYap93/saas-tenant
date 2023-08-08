<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Product;
use App\Model\CompanyLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\SettingTreeAge;
use App\Model\TreePointerReporting;
use App\Http\Controllers\Controller;
use App\Model\ProductCompanyLand;
use App\Model\ProductCategory;
use App\Exports\TreeAgePointerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class TreePointerReportingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function tree_pointer_reporting(Request $request)
    {
        $search =null;
        $validation = null;

        if (isset($_GET['reset'])) {
            Session::forget('tree_pointer_reporting');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('tree_pointer_reporting');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['setting_tree_age_upper'] = $request->input('setting_tree_age_upper');
                        $search['setting_tree_age_lower'] = $request->input('setting_tree_age_lower');
                        Session::put('tree_pointer_reporting', $search);
                        break;
                    case 'reset':
                        Session::forget('tree_pointer_reporting');
                        break;
                    case 'export':
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['setting_tree_age_upper'] = $request->input('setting_tree_age_upper');
                        $search['setting_tree_age_lower'] = $request->input('setting_tree_age_lower');
                        $record = TreePointerReporting::record_tree_age_pointer_report($search);
                        $recordpointer = TreePointerReporting::record_pointer_tree_age_pointer_report($search);
                        $age = SettingTreeAge::all();
                        Session::put('tree_pointer_reporting', $search);
                        return Excel::download(new TreeAgePointerExport('components/tree_pointer_report_component', $record, $recordpointer, $age, $search), 'Tree Age Pointer Report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('tree_pointer_reporting')) {
            $search = Session::get('tree_pointer_reporting');
        }

        return view('report.tree_pointer_reporting', [
            'submit' => route('tree_pointer_reporting'),
            'search' => $search,
            'record' => TreePointerReporting::record_tree_age_pointer_report($search),
            'recordpointer' => TreePointerReporting::record_pointer_tree_age_pointer_report($search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_sel' => Product::get_by_company(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'age' => SettingTreeAge::all(),

        ])->withErrors($validation);
    }

    public function tree_pointer_reporting_details(Request $request, $company_land_id, $setting_tree_age_upper, $setting_tree_age_lower)
    {
        $search = null;
        $search['month_year'] = date('m-Y');
        $search['company_land_id'] = $company_land_id;
        $search['setting_tree_age_upper'] = $setting_tree_age_upper;
        $search['setting_tree_age_lower'] = $setting_tree_age_lower;

        return view('components.tree_age_pointer_reporting.tree_pointer_reporting_details', [
            'search' => $search,
            'records' => TreePointerReporting::record_pointer_tree_age_pointer_report_details($search),
            'products' =>TreePointerReporting::record_pointer_tree_age_pointer_report_details_get_product($search),
            'ages' => SettingTreeAge::get_age_report($search),
            'title' => 'land',
            // 'company_expense_detail' => $records,
            // 'month_sel' => $current_month,
            'company_land' => CompanyLand::get_company_land_name($search),
            // 'expense_category' => SettingExpenseCategory::get_expense_for_report($search),
            // 'setting_expense' => SettingExpense::get_setting_expense_for_report(),
        ]);
    }

    public function tree_pointer_reporting_details_total(Request $request, $company_id, $setting_tree_age_upper, $setting_tree_age_lower)
    {
        $search = null;
        $search['month_year'] = date('m-Y');
        $search['company_id'] = $company_id;
        $search['setting_tree_age_upper'] = $setting_tree_age_upper;
        $search['setting_tree_age_lower'] = $setting_tree_age_lower;

        return view('components.tree_age_pointer_reporting.tree_pointer_reporting_details', [
            'search' => $search,
            'records' => TreePointerReporting::record_pointer_tree_age_pointer_report_details($search),
            'products' =>TreePointerReporting::record_pointer_tree_age_pointer_report_details_get_product($search),
            'ages' => SettingTreeAge::get_age_report($search),
            'title' => 'company',
            // 'company_expense_detail' => $records,
            // 'month_sel' => $current_month,
            'company' => Company::get_company_name_by_id($search['company_id']),
            'company_land' => CompanyLand::get_company_land_name($search),
            // 'expense_category' => SettingExpenseCategory::get_expense_for_report($search),
            // 'setting_expense' => SettingExpense::get_setting_expense_for_report(),
        ]);
    }
}