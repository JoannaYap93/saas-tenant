<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Product;
use App\Model\Reporting;
use App\Model\CompanyLand;
use Illuminate\Http\Request;
use App\Model\CompanyExpense;
use App\Model\SettingExpense;
use App\Model\MessageTemplate;
use App\Model\ProductCategory;
use Illuminate\Validation\Rule;
use App\Model\CompanyExpenseItem;
use App\Model\ProfitLossReporting;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\MessageTemplateInvolve;
use App\Model\SettingExpenseCategory;
use App\Exports\ProfitLossReportExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Exports\ProfitLossReportExport_y2y;

class ProfitLossReportingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function profit_loss_reporting(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');
        $search['setting_reporting_template_id'] = 1;
        $search['level_view_id'] = 1;

        if (isset($_GET['reset'])) {
            Session::forget('profit_loss_reporting');
        }

        if ($request->isMethod('post')) {
            if(auth()->user()->user_type_id == 1){
                $validation = Validator::make($request->all(), [
                    'year' => 'required_if:setting_reporting_template_id,2 |required_if:setting_reporting_template_id,3|required_if:setting_reporting_template_id,4',
                    'company_id' => 'required_if:setting_reporting_template_id,2',
                    'level_view_id' => 'required'

                ],[
                    'required_if' => 'The :attribute field is required.'
                ])->setAttributeNames([
                    'year' => 'Year',
                    'company_id' => 'Company',
                    'level_view_id' => 'Level View'
                ]);
            }else{
                $validation = Validator::make($request->all(), [
                    'year' => 'required_if:setting_reporting_template_id,2 |required_if:setting_reporting_template_id,3|required_if:setting_reporting_template_id,4',
                    'level_view_id' => 'required'

                ],[
                    'required_if' => 'The :attribute field is required.'
                ])->setAttributeNames([
                    'year' => 'Year',
                    'level_view_id' => 'Level View'
                ]);
            }

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('profit_loss_reporting');
                        if($request->input('setting_reporting_template_id') == 1){
                            $search['year'] = date('Y');
                        }else{
                            $search['year'] = $request->input('year');
                        }
                        $search['last_year'] = date('Y', strtotime($search['year']."-01-01".'-1 year'));
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['level_view_id'] = $request->input('level_view_id');
                        $search['setting_reporting_template_id'] = $request->input('setting_reporting_template_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        Session::put('profit_loss_reporting', $search);
                        break;
                    case 'reset':
                        Session::forget('profit_loss_reporting');
                        break;
                    case 'export':
                        if($request->input('setting_reporting_template_id') == 1){
                            $search['year'] = date('Y');
                        }else{
                            $search['year'] = $request->input('year');
                        }
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['last_year'] = date('Y', strtotime($search['year']."-01-01".'-1 year'));
                        $search['level_view_id'] = $request->input('level_view_id');
                        $search['setting_reporting_template_id'] = $request->input('setting_reporting_template_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');

                        switch($search['setting_reporting_template_id']){
                            case '1':
                                    $sales = ProfitLossReporting::get_sales_profit_loss_reporting($search);
                                    $expense = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search);
                                    $expense_items = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search);

                                Session::put('profit_loss_reporting', $search);

                                switch($search['level_view_id']){
                                    case '1':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl1_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_this_year_level1.xlsx');
                                        break;
                                    case '2':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl2_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_this_year_level2.xlsx');
                                        break;
                                    case '3':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl3_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_this_year_level3.xlsx');
                                        break;
                                }
                                break;
                            case '2':
                                $sales = ProfitLossReporting::get_sales_profit_loss_reporting($search, false);
                                $expense = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, false);
                                $expense_items = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, false);
                                $sales_last_year = ProfitLossReporting::get_sales_profit_loss_reporting($search, true);
                                $expense_last_year = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, true);
                                $expense_items_last_year = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, true);

                                Session::put('profit_loss_reporting', $search);

                                switch($search['level_view_id']){
                                    case '1':
                                        return Excel::download(new ProfitLossReportExport_y2y('components.pnlreporting.profit_loss_reporting_temp_y2y_lvl1_component', $sales, $expense, $expense_items, $sales_last_year, $expense_last_year, $expense_items_last_year, $search), 'profit_loss_reporting_year_to_year_level1.xlsx');
                                        break;
                                    case '2':
                                        return Excel::download(new ProfitLossReportExport_y2y('components.pnlreporting.profit_loss_reporting_temp_y2y_lvl2_component', $sales, $expense, $expense_items, $sales_last_year, $expense_last_year, $expense_items_last_year, $search), 'profit_loss_reporting_year_to_year_level2.xlsx');
                                        break;
                                    case '3':
                                        return Excel::download(new ProfitLossReportExport_y2y('components.pnlreporting.profit_loss_reporting_temp_y2y_lvl3_component', $sales, $expense, $expense_items, $sales_last_year, $expense_last_year, $expense_items_last_year, $search), 'profit_loss_reporting_year_to_year_level3.xlsx');
                                        break;
                                }
                                break;
                            case '3':
                                $sales = ProfitLossReporting::get_sales_profit_loss_reporting($search);
                                $expense = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search);
                                $expense_items = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search);

                                Session::put('profit_loss_reporting', $search);

                                switch($search['level_view_id']){
                                    case '1':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl1_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_company_to_company_level1.xlsx');
                                        break;
                                    case '2':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl2_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_company_to_company_level2.xlsx');
                                        break;
                                    case '3':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_1year_lvl3_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_company_to_company_level3.xlsx');
                                        break;
                                }
                                break;
                            case '4':
                                $sales = ProfitLossReporting::get_sales_profit_loss_reporting_month($search);
                                $expense = ProfitLossReporting::get_cost_sales_profit_loss_reporting_month($search);
                                $expense_items = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting_month($search);
                                Session::put('profit_loss_reporting', $search);
                                switch($search['level_view_id']){
                                    case '1':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_m2m_lvl1_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_month_to_month_level1.xlsx');
                                        break;
                                    case '2':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_m2m_lvl2_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_month_to_month_level2.xlsx');
                                        break;
                                    case '3':
                                        return Excel::download(new ProfitLossReportExport('components.pnlreporting.profit_loss_reporting_temp_m2m_lvl3_component', $sales, $expense, $expense_items, $search), 'profit_loss_reporting_month_to_month_level3.xlsx');
                                        break;
                                }
                                break;
                        }
                        break;
                }
            }
        }

        if (Session::has('profit_loss_reporting')) {
            $search = Session::get('profit_loss_reporting');
        }

        $return_arr = [
            'submit' => route('profit_loss_reporting', ['tenant' => tenant('id')]),
            'title' => 'Profit & Loss Reporting',
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'level_sel' => ProfitLossReporting::get_level_sel_for_pnl_reporting(),
            'temp_sel' => ProfitLossReporting::get_temp_sel_for_pnl_reporting(),
            'company_data' => Company::get_company_for_daily_report($search),
            'sales_product_category_data' =>ProductCategory::get_product_category_for_pnl_reporting(),
            'cost_sale_category_data' => SettingExpenseCategory::get_expense_category_for_pnl_reporting(),
            'cost_sales_item_data' => SettingExpense::get_item_expense_for_pnl_reporting(),
        ];


        if(isset($search['setting_reporting_template_id'])){
            switch($search['setting_reporting_template_id']){
                case '1':
                    //Remark: Cost Sales
                    $return_arr['sales'] = ProfitLossReporting::get_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales_item'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, false);
                    break;
                case '2':
                    //Remark: Cost Sales
                    $return_arr['sales'] = ProfitLossReporting::get_sales_profit_loss_reporting($search, false);
                    $return_arr['last_year_sales'] = ProfitLossReporting::get_sales_profit_loss_reporting($search, true);
                    $return_arr['cost_sales'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_last_year_sales'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, true);
                    $return_arr['cost_sales_item'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, false);
                    $return_arr['cost_last_year_sales_item'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, true);
                    break;
                case '3':
                    //Remark: Cost Sales
                    $return_arr['sales'] = ProfitLossReporting::get_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales_item'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, false);
                    break;
                case '4':
                     //Remark: For Year 2 Year
                    $return_arr['month_sel'] = Reporting::get_month();
                    $return_arr['sales_month'] = ProfitLossReporting::get_sales_profit_loss_reporting_month($search);
                    $return_arr['cost_sales_month'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting_month($search);
                    $return_arr['cost_sales_item_month'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting_month($search);
                    break;
                default:
                    //Remark: Cost Sales
                    $return_arr['sales'] = ProfitLossReporting::get_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales'] = ProfitLossReporting::get_cost_sales_profit_loss_reporting($search, false);
                    $return_arr['cost_sales_item'] = ProfitLossReporting::get_cost_sales_item_profit_loss_reporting($search, false);
                    break;
            }
        }

        return view('profit_loss_reporting.listing', $return_arr)->withErrors($validation);
    }

    public function profit_loss_reporting_detail($company_id, $setting_expense_id, $year){

        $records = ProfitLossReporting::profit_loss_reporting_detail($company_id, $setting_expense_id, $year);

        return view('report.profit_loss_reporting_detail', [
            'submit' => route('profit_loss_reporting_detail', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_report_detail($company_id, $setting_expense_id, $year),
        ]);
    }

    public function profit_loss_y2y_reporting_detail($company_id, $setting_expense_id, $year){

        $search['year'] = date('Y');
        $search['last_year'] = date('Y', strtotime($search['year']."-01-01".'-1 year'));
        $search['year'] = $year;
        $search['setting_expense_id'] = $setting_expense_id;
        $search['company_id'] = $company_id;
        $records = ProfitLossReporting::profit_loss_y2y_reporting_detail($search, false);
        $recordslastyear = ProfitLossReporting::profit_loss_y2y_reporting_detail($search, true);

        return view('report.profit_loss_y2y_reporting_detail', [
            'submit' => route('profit_loss_y2y_reporting_detail', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'profitloss' => $recordslastyear,
            'search' => $search,
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_y2y_report_detail($company_id, $setting_expense_id)
        ]);
    }

    public function profit_loss_m2m_reporting_detail($company_id, $setting_expense_id, $year, $month){

        $search['year'] = date('Y');
        $search['company_id'] = $company_id;
        $records = ProfitLossReporting::profit_loss_m2m_reporting_detail($company_id, $setting_expense_id, $year, $month);

        return view('report.profit_loss_m2m_reporting_detail', [
            'submit' => route('profit_loss_m2m_reporting_detail', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'search' => $search,
            'month_sel' => Reporting::get_month(),
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_report_detail($company_id, $setting_expense_id, $year),
        ]);
    }

    public function profit_loss_reporting_detail_by_land($company_id, $company_land_id, $setting_expense_id, $year){

        $search['company_land_id'] = $company_land_id;
        $records = ProfitLossReporting::profit_loss_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year);

        return view('report.profit_loss_reporting_detail_by_land', [
            'submit' => route('profit_loss_reporting_detail_by_land', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_report_detail($company_id, $setting_expense_id, $year),
        ]);
    }

    public function profit_loss_y2y_reporting_detail_by_land($company_id, $company_land_id, $setting_expense_id, $year){

        $search['year'] = date('Y');
        $search['last_year'] = date('Y', strtotime($search['year']."-01-01".'-1 year'));
        $search['year'] = $year;
        $search['setting_expense_id'] = $setting_expense_id;
        $search['company_id'] = $company_id;
        $search['company_land_id'] = $company_land_id;
        $records = ProfitLossReporting::profit_loss_y2y_reporting_detail_by_land($search, false);
        $recordslastyear = ProfitLossReporting::profit_loss_y2y_reporting_detail_by_land($search, true);

        return view('report.profit_loss_y2y_reporting_detail_by_land', [
            'submit' => route('profit_loss_y2y_reporting_detail_by_land', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'profitloss' => $recordslastyear,
            'search' => $search,
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_y2y_report_detail($company_id, $setting_expense_id)
        ]);
    }

    public function profit_loss_m2m_reporting_detail_by_land($company_id, $company_land_id, $setting_expense_id, $year, $month){

        $search['year'] = date('Y');
        $search['company_id'] = $company_id;
        $search['company_land_id'] = $company_land_id;
        $records = ProfitLossReporting::profit_loss_m2m_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year, $month);

        return view('report.profit_loss_m2m_reporting_detail_by_land', [
            'submit' => route('profit_loss_m2m_reporting_detail_by_land', ['tenant' => tenant('id')]),
            'profit_loss' => $records,
            'search' => $search,
            'month_sel' => Reporting::get_month(),
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
            'company_expense' => CompanyExpense::get_company_expense_for_report_detail($company_id, $setting_expense_id, $year),
        ]);
    }
}
