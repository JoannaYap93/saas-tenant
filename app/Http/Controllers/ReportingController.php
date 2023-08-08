<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Worker;
use App\Model\Company;
use App\Model\Invoice;
use App\Model\Product;
use App\Model\Setting;
use App\Model\Customer;
use App\Model\UserLand;
use App\Model\Reporting;
use Carbon\CarbonPeriod;
use App\Model\MessageLog;
use App\Model\CompanyFarm;
use App\Model\CompanyLand;
use App\Model\SettingSize;
use App\Model\FormulaUsage;
use App\Libraries\Wfunction;
use App\Model\DeliveryOrder;
use Illuminate\Http\Request;
use App\Model\CompanyExpense;
use App\Model\SettingExpense;
use App\Exports\DetailsExport;
use App\Exports\DODailyExport;
use App\Exports\ExpenseReport;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandZone;
use App\Model\MessageTemplate;
use App\Model\ProductCategory;
use App\Exports\LandTreeReport;
use App\Model\CustomerCategory;
use App\Model\SettingWarehouse;
use App\Model\DeliveryOrderType;
use App\Model\ProductCompanyLand;
use App\Exports\FarmDetailsExport;
use App\Exports\FormulaUsageAdmin;
use App\Exports\PnLForecastExport;
use App\Model\CompanyLandCategory;
use App\Exports\BudgetReportExport;
use App\Exports\DifferentialReport;
use App\Exports\InvoiceDailyExport;
use App\Model\CompanyExpenseWorker;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CollectDODailyExport;
use App\Exports\CompanyExpenseExport;
use App\Exports\FormulaUsageSAExport;
use App\Exports\InvoiceMonthlyExport;
use App\Exports\SyncAttendanceExport;
use App\Model\MessageTemplateInvolve;
use App\Model\SettingExpenseCategory;
use App\Model\SettingFormulaCategory;
use App\Exports\CollectDOYearlyExport;
use App\Exports\TreeTargetReportExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Exports\BudgetReportDetailExport;
use App\Exports\SalesSummaryByFarmExport;
use App\Model\CompanyLandBudgetOverwrite;
use Illuminate\Support\Facades\Validator;
use App\Exports\AverageSalesSummaryExport;
use App\Exports\CompanyExpenseReportExport;
use App\Exports\FarmManagerWorkerExpenseExport;
use App\Exports\SalesSummaryByProductCompanyExport;
use App\Exports\SalesSummaryByProductCompanyExportNoGrade;

class ReportingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function collect_do_variance_report_yearly(Request $request)
    {

        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('collect_do_variance_report_yearly');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('collect_do_variance_report_yearly');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');

                        Session::put('collect_do_variance_report_yearly', $search);
                        break;
                    case 'reset':
                        Session::forget('collect_do_variance_report_yearly');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');

                        $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                        $collect_details = Reporting::get_collect_details($search);

                        Session::put('collect_do_variance_report_yearly', $search);
                        return Excel::download(new CollectDOYearlyExport('components/collect_do_report', $delivery_order_item_details, $collect_details, $search), 'collect_do_yearly_export.xlsx');
                        break;
                }
            }
        }

        if (Session::has('collect_do_variance_report_yearly')) {
            $search = Session::get('collect_do_variance_report_yearly');
        }

        return view('report.collect_do_report', [
            'month_sel' => Reporting::get_month(),
            'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
            'collect_details' => Reporting::get_collect_details($search),
            'products' => Reporting::get_product_details(),
            'company_land' => CompanyLand::get_company_land_name($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
            'submit' => route('collect_do_variance'),
            'search' => $search,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function collect_do_variance_report_daily(Request $request)
    {

        $validation = null;
        $search['start_date'] = date('Y-m-01');
        $search['end_date'] = date('Y-m-d');
        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);

        if (isset($_GET['reset'])) {
            Session::forget('collect_do_variance_report_daily');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('collect_do_variance_report_daily');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');

                        Session::put('collect_do_variance_report_daily', $search);
                        break;
                    case 'reset':
                        Session::forget('collect_do_variance_report_daily');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $delivery_order_item_details = Reporting::get_delivery_order_item_details($search, true);
                        $collect_details = Reporting::get_collect_details($search, true);
                        Session::put('collect_do_variance_report_daily', $search);
                        return Excel::download(new CollectDODailyExport('components/collect_do_daily_report', $delivery_order_item_details, $collect_details, $search, $date_range), 'collect_do_daily_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('collect_do_variance_report_daily')) {
            $search = Session::get('collect_do_variance_report_daily');
        }

        return view('report.collect_do_daily_report', [
            'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search, true),
            'collect_details' => Reporting::get_collect_details($search, true),
            'products' => Reporting::get_product_details(),
            'company' => Company::get_company_for_daily_report($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'date_range' => $date_range,
            'submit' => route('collect_do_variance_daily'),
            'search' => $search,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
        ])->withErrors($validation);
    }

    public function collect_daily_report(Request $request)
    {

        $validation = null;
        $search['start_date'] = date('Y-m-01');
        $search['end_date'] = date('Y-m-d');
        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);

        if (isset($_GET['reset'])) {
            Session::forget('collect_daily_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('collect_daily_report');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');

                        Session::put('collect_daily_report', $search);
                        break;
                    case 'reset':
                        Session::forget('collect_daily_report');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');

                        $delivery_order_item_details = Reporting::get_delivery_order_item_details($search, true);
                        $collect_details = Reporting::get_collect_details($search, true);

                        Session::put('collect_daily_report', $search);
                        return Excel::download(new CollectDODailyExport('components/collect_daily_report', $delivery_order_item_details, $collect_details, $search, $date_range), 'collect_daily_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('collect_daily_report')) {
            $search = Session::get('collect_daily_report');
        }

        return view('report.collect_daily_report', [
            'collect_details' => Reporting::get_collect_details($search, true),
            'products' => Reporting::get_product_details(),
            'company' => Company::get_company_for_daily_report($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
            'date_range' => $date_range,
            'submit' => route('collect_daily_report'),
            'search' => $search,
            'title' => 'collect_daily_report',
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function do_daily_report(Request $request)
    {

        $validation = null;
        $search['start_date'] = date('Y-m-01');
        $search['end_date'] = date('Y-m-d');
        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);

        if (isset($_GET['reset'])) {
            Session::forget('do_daily_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if (!$validation->fails()) {
                // dd($request->all());
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('do_daily_report');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['customer_category_id'] = $request->input('customer_category_id');
                        $search['company_id'] = $request->input('company_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['order_type'] = $request->input('order_type');

                        Session::put('do_daily_report', $search);
                        break;
                    case 'reset':
                        Session::forget('do_daily_report');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['order_type'] = $request->input('order_type');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');
                        $delivery_order_item_details = Reporting::get_delivery_order_item_details($search, true);

                        Session::put('do_daily_report', $search);
                        return Excel::download(new DODailyExport('components/do_daily_report', $delivery_order_item_details, $search, $date_range), 'do_daily_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('do_daily_report')) {
            $search = Session::get('do_daily_report');
        }

        return view('report.do_daily_report', [
            'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search, true),
            'products' => Reporting::get_product_details(),
            'company' => Company::get_company_for_daily_report($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
            'date_range' => $date_range,
            'submit' => route('do_daily_report'),
            'order_type' => DeliveryOrderType::get_sel(),
            'search' => $search,
            'title' => 'do_daily_report',
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function invoice_report_monthly(Request $request)
    {

        $validation = null;
        $search['year'] = date('Y');
        $search['company_id']= null;
        $search['company_land_id']= null;
        $search['user_id']= null;

        if (isset($_GET['reset'])) {
            Session::forget('invoice_monthly_report');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('invoice_monthly_report');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['customer_id'] = $request->input('customer_id');

                        Session::put('invoice_monthly_report', $search);
                        break;
                    case 'reset':
                        Session::forget('invoice_monthly_report');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');

                        $product = Product::get_product_name_list_2($search);
                        $invoice_information = Reporting::get_invoice_monthly_report($search);

                        Session::put('invoice_monthly_report', $search);
                        return Excel::download(new InvoiceMonthlyExport('components/invoice_monthly_report', $product, $invoice_information, $search), 'invoice_monthly_export.xlsx');
                        break;
                }
            }
        }

        if (Session::has('invoice_monthly_report')) {
            $search = Session::get('invoice_monthly_report');
        }

        $invoice_information = Reporting::get_invoice_monthly_report($search);
        // dd($invoice_information);
        return view('report.invoice_monthly_report', [
            'month_sel' => Reporting::get_month(),
            'submit' => route('invoice_monthly_report'),
            'search' => $search,
            'product' => Product::get_product_name_list_2($search),
            'company_sel' => Company::get_company_sel(),
            'data' => $invoice_information,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
        ])->withErrors($validation);
    }

    public function sales_summary_by_product_report(Request $request)
    {
        $validation = null;
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');


        if (isset($_GET['reset'])) {
            Session::forget('sales_summary_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'sales_from' => 'required',
                'sales_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('sales_summary_by_product_report');
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');
                        $search['no_grade'] = $request->input('no_grade');
                        $search['product_category_id'] = $request->input('product_category_id');
                        Session::put('sales_summary_by_product_report', $search);
                        break;
                    case 'reset':
                        Session::forget('sales_summary_by_product_report');
                        break;
                    case 'today':
                        $search['sales_from'] = date('Y-m-d');
                        $search['sales_to'] = date('Y-m-d');
                        Session::put('sales_summary_by_product_report', $search);
                        break;
                    case 'yesterday':
                        $search['sales_from'] = date('Y-m-d',strtotime("-1 days"));
                        $search['sales_to'] = date('Y-m-d',strtotime("-1 days"));
                        Session::put('sales_summary_by_product_report', $search);
                        break;
                    case 'export':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');
                        $search['no_grade'] = $request->input('no_grade');
                        $search['product_category_id'] = $request->input('product_category_id');
                        $sales_summary_details_by_product = Reporting::get_sales_summary_details_by_product($search);
                        $records = $sales_summary_details_by_product;
                        Session::put('sales_summary_by_product_report', $search);
                        return Excel::download(new DetailsExport('components/sales_summary_by_product_report', $records, $search), 'sales_summary_by_product.xlsx');
                        break;
                    case 'whatsapp':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');
                        $search['no_grade'] = $request->input('no_grade');
                        $search['report'] = $request->input('report_id');
                        $search['product_category_id'] = $request->input('product_category_id');
                        Session::put('sales_summary_by_product_report', $search);
                        if($request->input('no_grade') == 1){
                          $sales_summary_details_by_product_company_no_grade = Reporting::get_sales_summary_details_by_product_company($search);
                          $text = '';
                          $total_sales = 0;
                          $total_qty = 0;
                          // dd($sales_summary_details_by_product_company_no_grade['whatsapp_use']);
                            foreach ($sales_summary_details_by_product_company_no_grade['whatsapp_use'] as $row) {
                              $total_sales += $row['total'];
                              $total_qty += $row['quantity'];
                            }

                            if(isset($search['report'])){
                                $text .= 'Report ' . $search['report'] . ':%0a';
                            }

                            if(isset($search['product_id']) || isset($search['product_cb_id'])){
                                $sales_summary_details_product_name = Product::get_records_no_paginate($search);
                                foreach ($sales_summary_details_product_name as $key => $product){
                                    $text .= $product->product_name . ', ';
                                }
                            }else{
                                $text .= '全部品种, ';
                            }

                            $text .= '华盛, Pahang / ';

                            if(isset($search['sales_from'])){
                                $text .= $search['sales_from'] . ' to ' . $search['sales_to'] . '%0a%0a';
                            }

                            foreach ($sales_summary_details_by_product_company_no_grade['whatsapp_use'] as $key => $value) {
                              $sales_percentage = $value['total']/$total_sales * 100;
                              $quantity_percentage = $value['quantity']/$total_qty * 100;

                              $text .= $value['product_name'] . ':%0a';
                              $text .= number_format($value['quantity'], 2). 'KG (' .  number_format($quantity_percentage, 2) . '%)%0a';
                              $text .= 'RM' . number_format($value['total'],2) . ' (' .  number_format($sales_percentage, 2) . '%)%0a';
                              $text .= '%0a';
                            }

                            if($total_sales > 0){
                              $text .= 'Total: %0a';
                              $text .= number_format($total_qty, 2). 'KG (' .  number_format($total_qty/$total_qty*100, 2) . '%)%0a';
                              $text .=  'RM' . number_format($total_sales,2) . ' (' .  number_format($total_sales/$total_sales*100, 2) . '%)';
                            }

                          return redirect()->away('whatsapp://send?text='. $text);
                        }else{
                          $sales_summary_details_by_product = Reporting::get_sales_summary_details_by_product($search);

                          $text = '';
                          $total_sales = 0;
                          $total_qty = 0;
                            foreach ($sales_summary_details_by_product['result'] as $row)
                            {
                                $total_sales += $row->total;
                                $total_qty += $row->quantity;
                            }

                            if(isset($search['report'])){
                                $text .= 'Report ' . $search['report'] . ':%0a';
                            }

                            if(isset($search['product_id']) || isset($search['product_cb_id'])){
                                $sales_summary_details_product_name = Product::get_records_no_paginate($search);
                                foreach ($sales_summary_details_product_name as $key => $product){
                                    $text .= $product->product_name . ', ';
                                }
                            }else{
                                $text .= '全部品种, ';
                            }

                            $text .= '华盛, Pahang / ';

                            if(isset($search['sales_from'])){
                                $text .= $search['sales_from'] . ' to ' . $search['sales_to'] . '%0a%0a';
                            }

                            foreach ($sales_summary_details_by_product['result'] as $key => $value) {
                              $sales_percentage = $value->total/$total_sales * 100;
                              $quantity_percentage = $value->quantity/$total_qty * 100;
                              $avg_price = $value->total/$value->quantity;

                              $text .= $value->product_name . ' ' . $value->setting_product_size_name . ':%0a';
                              $text .= number_format($value->quantity, 2). 'KG (' .  number_format($quantity_percentage, 2) . '%)%0a';
                              $text .= 'RM' . number_format($value->total,2) . ' (' .  number_format($sales_percentage, 2) . '%)%0a';
                              $text .= 'Average: RM ' . number_format($avg_price, 2) . '%0a';
                              $text .= '%0a';
                            }

                            if($total_sales > 0){
                              $text .= 'Total: %0a';
                              $text .= number_format($total_qty, 2). 'KG (' .  number_format($total_qty/$total_qty*100, 2) . '%)%0a';
                              $text .=  'RM' . number_format($total_sales,2) . ' (' .  number_format($total_sales/$total_sales*100, 2) . '%)';
                            }

                          return redirect()->away('whatsapp://send?text='. $text);
                        }
                        break;
                }
            }
        }

        if (Session::has('sales_summary_by_product_report')) {
            $search = Session::get('sales_summary_by_product_report');
        }

        return view('report.sales_summary_by_product_report', [
            'submit' => route('sales_summary_by_product_report'),
            'search' => $search,
            'sales_summary_details_by_product' => Reporting::get_sales_summary_details_by_product($search),
            'product_sel' => Product::get_by_company(),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'farm_sel' => CompanyFarm::get_all_farm(),
        ])->withErrors($validation);
    }

    public function sales_summary_by_product_report_company(Request $request)
    {
        $validation = null;
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');


        if (isset($_GET['reset'])) {
            Session::forget('sales_summary_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'sales_from' => 'required',
                'sales_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('sales_summary_by_product_report_company');
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');

                        Session::put('sales_summary_by_product_report_company', $search);
                        break;
                    case 'reset':
                        Session::forget('sales_summary_by_product_report_company');
                        break;
                    case 'export':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');
                        $sales_summary_details_by_product_company = Reporting::get_sales_summary_details_by_product_company($search);
                        $records = $sales_summary_details_by_product_company;
                        Session::put('sales_summary_by_product_report_company', $search);
                        return Excel::download(new SalesSummaryByProductCompanyExport('components/sales_summary_by_product_report_company', $records, $search), 'sales_summary_by_product_report_company.xlsx');
                        break;
                }
            }
        }

        if (Session::has('sales_summary_by_product_report_company')) {
            $search = Session::get('sales_summary_by_product_report_company');
        }
        // dd(Product::get_w_size($search));
        return view('report.sales_summary_by_product_report_company', [
            'submit' => route('sales_summary_by_product_report_company'),
            'search' => $search,
            'sales_summary_details_by_product_company' => Reporting::get_sales_summary_details_by_product_company($search),
            'product' => Product::get_w_size_2($search),
            'company' => Company::get_company_for_sales_product_company_report($search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'farm_sel' => CompanyFarm::get_all_farm(),
        ])->withErrors($validation);
    }

    public function sales_summary_by_product_report_company_no_grade(Request $request)
    {
        $validation = null;
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');


        if (isset($_GET['reset'])) {
            Session::forget('sales_summary_by_product_report_company_no_grade');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'sales_from' => 'required',
                'sales_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('sales_summary_by_product_report_company_no_grade');
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');

                        Session::put('sales_summary_by_product_report_company_no_grade', $search);
                        break;
                    case 'reset':
                        Session::forget('sales_summary_by_product_report_company_no_grade');
                        break;
                    case 'export':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['company_farm_id'] = $request->input('company_farm_id');
                        $sales_summary_details_by_product_company_no_grade = Reporting::get_sales_summary_details_by_product_company($search);
                        $records = $sales_summary_details_by_product_company_no_grade;
                        Session::put('sales_summary_by_product_report_company_no_grade', $search);
                        return Excel::download(new SalesSummaryByProductCompanyExportNoGrade('components/sales_summary_by_product_report_company_no_grade', $records, $search), 'sales_summary_details_by_product_company_no_grade.xlsx');
                        break;
                }
            }
        }

        if (Session::has('sales_summary_by_product_report_company_no_grade')) {
            $search = Session::get('sales_summary_by_product_report_company_no_grade');
        }
        // dd(Product::get_w_size($search));
        return view('report.sales_summary_by_product_report_company_no_grade', [
            'submit' => route('sales_summary_by_product_report_company_no_grade'),
            'search' => $search,
            'sales_summary_details_by_product_company' => Reporting::get_sales_summary_details_by_product_company($search),
            'product' => Product::get_w_size_2($search),
            'company' => Company::get_company_for_sales_product_company_report($search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'farm_sel' => CompanyFarm::get_all_farm(),
        ])->withErrors($validation);
    }

    public function average_summary_report(Request $request)
    {
        $validation = null;
        $records = null;
        $search['date_from'] = date('Y-m-01');
        $search['date_to'] = date('Y-m-d');

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'date_from' => 'required',
                'date_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        $search['date_from'] = $request->input('date_from');
                        $search['date_to'] = $request->input('date_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['customer_ids'] = $request->input('customer_ids');
                        $search['product_category_id'] = $request->input('product_category_id');
                        // $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['type'] = $submit;
                        $records = Reporting::get_average_summary($search);
                        Session::put('average_summary_report', $search);
                        break;
                    case 'reset':
                        Session::forget('average_summary_report');
                        break;
                    case 'export':
                        $search['date_from'] = $request->input('date_from');
                        $search['date_to'] = $request->input('date_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['customer_ids'] = $request->input('customer_ids');
                        $search['product_category_id'] = $request->input('product_category_id');
                        $search['type'] = $submit;
                        // $search['product_cb_id'] = $request->input('product_cb_id');
                        $records = Reporting::get_average_summary($search);
                        Session::put('average_summary_report', $search);
                        return Excel::download(new AverageSalesSummaryExport('components/average_summary_report', $records, $search), 'average_summary_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('average_summary_report')) {
            $search = Session::get('average_summary_report');
        }
        return view('report.average_summary_report', [
            'submit' => route('average_summary_report'),
            'search' => $search,
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'customer_list' => Customer::get_customer_by_company($search),
            'records' => $records,
            'product_list' => Product::get_w_size_by_company($search),
            'date_range' => CarbonPeriod::create($search['date_from'], $search['date_to']),
            'company_name' => Company::get_company_name_by_id(@$search['company_id']),
            'company_land_name' => CompanyLand::get_company_land_name_by_id(@$search['company_land_id'])
        ]);
    }

    public function product_detail_report(Request $request)
    {
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');

        if (Session::has('sales_summary_by_product_report')) {
            $search = Session::get('sales_summary_by_product_report');
        }
        $records = Reporting::get_product_detail_report($search);
        $records->excel = false;

        if ($request->isMethod('post')) {
            $data = $records;
            $data->from = @$search['sales_from'] ?? null;
            $data->to = @$search['sales_to'] ?? null;
            return Excel::download(new DetailsExport('components.sales_summary_report', $data), 'product_detail_report.xlsx');
        }

        return view('report.product_detail_report', [
            'records' => $records,
        ]);
    }

    public function invoice_daily_report(Request $request, $year=null, $month=null, $company_id=null, $land_id=null, $user_id=null)
    {
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-t');


        if($year != null && $month != null) {
            $startOfMonth = Carbon::parse($year . '-'. $month . '-01')->toDateString();
            $endOfMonth = Carbon::parse($year . '-'. $month . '-01')->endOfMonth()->toDateString();

            $search['sales_from'] = date($startOfMonth);
            $search['sales_to'] = date($endOfMonth);
            $search['company_id'] = $company_id;
            $search['company_land_id'] = $land_id;
            $search['user_id'] = $user_id;
        }
        // $search['year'] = $year;
        // $search['month'] = $month;

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'sales_from' => 'required',
                'sales_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('invoice_daily_report');
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['company_ids'] = [];
                        $search['customer_id'] = $request->input('customer_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');
                        if (is_null($request->input('company_ids')) == false) {
                            foreach ($request->input('company_ids') as $company_id) {
                                array_push($search['company_ids'], $company_id);
                            }
                        }
                        $search['user_id'] = $request->input('user_id');
                        // $search['checkbox_company'] = $request->input('checkbox_company[]');
                        // $search['product_id'] = $request->input('product_id');
                        // $search['product_size_id'] = $request->input('product_size_id');
                        Session::put('invoice_daily_report', $search);
                        break;
                    case 'reset':
                        Session::forget('invoice_daily_report');
                        break;
                    case 'export':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['company_ids'] = [];
                        $search['customer_id'] = $request->input('customer_id');
                        if (is_null($request->input('company_ids')) == false) {
                            foreach ($request->input('company_ids') as $company_id) {
                                array_push($search['company_ids'], $company_id);
                            }
                        }
                        $search['user_id'] = $request->input('user_id');
                        // $search['product_id'] = $request->input('product_id');
                        // $search['product_size_id'] = $request->input('product_size_id');
                        $records = Reporting::get_invoice_daily_report($search);
                        Session::put('invoice_daily_report', $search);
                        $date_range = CarbonPeriod::create($search['sales_from'], $search['sales_to']);
                        return Excel::download(new InvoiceDailyExport('components/invoice_daily_report', $records, $search, $date_range), 'invoice_daily_report.xlsx');
                        break;
                }
            }
        }
        if (Session::has('invoice_daily_report')) {
            $search = Session::get('invoice_daily_report');
        }
        $date_range = CarbonPeriod::create($search['sales_from'], $search['sales_to']);
        $records = Reporting::get_invoice_daily_report($search);
        //dd($records);

        return view('report.invoice_daily_report', [
            'search' => $search,
            'records' => $records,
            'date_range' => $date_range,
            'product' => Product::get_product_name_list_2($search),
            'submit' => route('invoice_daily_report'),
            'company_sel' => Company::get_company_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
        ]);
    }

    public function sales_summary_by_farm_report(Request $request)
    {
        $validation = null;
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');


        if (isset($_GET['reset'])) {
            Session::forget('sales_summary_by_farm_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'sales_from' => 'required',
                'sales_to' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('sales_summary_by_farm_report');
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['product_category_id'] = $request->input('product_category_id');

                        Session::put('sales_summary_by_farm_report', $search);
                        break;
                    case 'reset':
                        Session::forget('sales_summary_by_farm_report');
                        break;
                    case 'export':
                        $search['sales_from'] = $request->input('sales_from');
                        $search['sales_to'] = $request->input('sales_to');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['product_category_id'] = $request->input('product_category_id');

                        $search['customer_id'] = $request->input('customer_id');
                        $records = Reporting::get_sales_summary_details_by_farm($search);

                        Session::put('sales_summary_by_farm_report', $search);
                        return Excel::download(new DetailsExport('components.sales_summary_by_farm_report', $records, $search), 'sales_summary_by_farm.xlsx');
                        break;
                }
            }
        }

        if (Session::has('sales_summary_by_farm_report')) {
            $search = Session::get('sales_summary_by_farm_report');
        }

        return view('report.farm_report', [
            'submit' => route('sales_summary_by_farm_report'),
            'search' => $search,
            'sales_summary_details_by_farm' => Reporting::get_sales_summary_details_by_farm($search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'product_sel' => Product::get_by_company(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
        ])->withErrors($validation);
    }

    public function detail_farm_sales_summary_report(Request $request, $company_farm_id)
    {
        $search['sales_from'] = date('Y-m-01');
        $search['sales_to'] = date('Y-m-d');

        if (Session::has('sales_summary_by_farm_report')) {
            $search = Session::get('sales_summary_by_farm_report');
        }

        $company_farm = CompanyFarm::find($company_farm_id);
        $records = Reporting::get_detailed_farm_sales_summary_details($search, $company_farm_id);

        if ($request->isMethod('post')) {
            $data = $records;
            $land = CompanyLand::get_company_land_by_farm_id($search, $company_farm_id);
            $company = CompanyLand::get_company_by_farm_id($search, $company_farm_id);

            return Excel::download(new FarmDetailsExport('components.detailed_sales_summary_report_by_farm', $data, $land, $company), 'farm_detail_report.xlsx');
        }

        return view('report.farm_detail_report', [
            'records' => $records,
            'company_land' => CompanyLand::get_company_land_by_farm_id($search, $company_farm_id),
            'company' => CompanyLand::get_company_by_farm_id($search, $company_farm_id),
            'company_farm' => $company_farm,
        ]);
    }

    public function message_template_report_by_year(Request $request)
    {
        $validation = null;

        if (isset($_GET['reset'])) {
            Session::forget('filter_message_template_report');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);
            if (!$validation->fails()) {
                $search['year'] = $request->input('year');
                $search['message_template_id'] = $request->input('message_template_id') ?? 0;
                $search['company_cb_id'] = $request->input('company_cb_id');
                $search['product_cb_id'] = $request->input('product_cb_id');
                $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                $search['customer_id'] = $request->input('customer_id');


                Session::put('filter_message_template_report', $search);
            }
        }
        $search = Session::has('filter_message_template_report') ? Session::get('filter_message_template_report') : array("year" => date('Y'));

        $wfunction = new Wfunction();
        $records = MessageLog::get_by_month($search);
        $year_sel = $wfunction->year_sel();
        $month_sel = $wfunction->month_sel();
        $template_sel = MessageTemplate::get_reporting();

        if ($records) {
            $message_templates = MessageTemplate::get_by_ids(array_keys($records));
        } else {
            $message_templates = array();
        }

        return view('report.message_template_report_by_year', [
            'page_title' => 'WhatsApp Reporting (Years)',
            'submit' => route('message_template_report_by_year'),
            'search' => $search,
            'year_sel' => $year_sel,
            'month_sel' => $month_sel,
            'template_sel' => $template_sel,
            'current_year' => date('Y'),
            'records' => $records,
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'message_templates' => $message_templates,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function message_template_report_by_month(Request $request, $year, $month)
    {
        $validation = null;

        if (isset($_GET['reset'])) {
            return Redirect::to(route('message_template_report_by_month', [
                "year" => date('Y'),
                "month" => date('m')
            ]));
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
                'month' => 'required',
            ]);
            if (!$validation->fails()) {
                $search['year'] = $request->input('year');
                $search['month'] = $request->input('month');
                // $search['message_template_id'] = $request->input('message_template_id') ?? 0;

                return Redirect::to(route('message_template_report_by_month', [
                    "year" => $search['year'],
                    "month" => $search['month']
                ]));
            }
        }

        $search = array();
        $search['year'] = $year;
        $search['month'] = $month;
        // $search['message_template_id'] = $id;

        $wfunction = new Wfunction();
        $year_sel = $wfunction->year_sel();
        $month_sel = $wfunction->month_sel();
        $template_sel = MessageTemplate::get_reporting();

        $records = MessageLog::get_by_day($search);
        if ($records) {
            $message_templates = MessageTemplate::get_by_ids(array_keys($records));
        } else {
            $message_templates = array();
        }

        return view('report.message_template_report_by_month', [
            'page_title' => 'WhatsApp Reporting (Months)',
            'message_templates' => $message_templates,
            'search' => $search,
            'records' => $records,
            'year_sel' => $year_sel,
            'month_sel' => $month_sel,
            'template_sel' => $template_sel,
            'submit' => route('message_template_report_by_month', [
                "year" => $search['year'],
                "month" => $search['month']
            ]),
        ])->withErrors($validation);
    }

    public function message_template_report_by_day($year, $month, $day, $id)
    {
        $validation = null;
        $search = array();
        $search['year'] = $year;
        $search['month'] = $month;
        $search['day'] = $day;
        $search['message_template_id'] = $id;

        $records = MessageLog::get_by_user($search);

        if ($records) {
            $user_ids = array_unique($records->pluck('user_id')->toArray());
            $message_template_ids = array_unique($records->pluck('message_template_id')->toArray());
            $users = User::get_by_ids($user_ids);
            $message_templates = MessageTemplate::get_by_ids($message_template_ids);
        } else {
            $message_templates = array();
            $users = array();
        }

        return view('report.message_template_report_by_day', [
            'page_title' => 'WhatsApp Reporting (Days)',
            'users' => $users,
            'message_templates' => $message_templates,
            'search' => $search,
            'records' => $records,
        ])->withErrors($validation);
    }

    public function sync_attendance_report(Request $request)
    {
        Session::forget('sync_attendance_report');

        $validation = null;
        $search['start_date'] = date('Y-m-01');
        $search['end_date'] = date('Y-m-d');
        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);



        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required'
            ])->setAttributeNames([
                'start_date' => 'Start Date',
                'end_date' => 'End Date'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('sync_attendance_report');
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['user_id'] = $request->input('user_id');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        //$search['product_cb_id'] = $request->input('product_cb_id');

                        Session::put('sync_attendance_report', $search);
                        break;
                    case 'reset':
                        Session::forget('sync_attendance_report');
                        break;
                    case 'export':
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['user_id'] = $request->input('user_id');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');

                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $sync_collect_details = Reporting::get_sync_collect_details_by_land_user($search);
                        $sync_do_details = Reporting::get_sync_do_details_by_land_user($search);
                        $collect_details = Reporting::get_collect_details_by_land_user($search);
                        $do_details = Reporting::get_do_details_by_land_user($search);

                        Session::put('sync_attendance_report', $search);
                        return Excel::download(new SyncAttendanceExport('components/sync_attendance_report', $date_range, $sync_collect_details, $sync_do_details, $collect_details, $do_details, $search), 'sync_attendance_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('sync_attendance_report')) {
            $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
            $search = Session::get('sync_attendance_report');
        }

        return view('report.sync_attendance_report', [
            'page_title' => 'Sync Attendance Report',
            'submit' => route('sync_attendance_report'),
            'search' => $search,
            'date_range' => $date_range,
            'sync_collect_details' => Reporting::get_sync_collect_details_by_land_user($search),
            'sync_do_details' => Reporting::get_sync_do_details_by_land_user($search),
            'collect_details' => Reporting::get_collect_details_by_land_user($search),
            'do_details' => Reporting::get_do_details_by_land_user($search),
            'company_land' => CompanyLand::get_company_land_name($search),
            'users' => User::get_fullname_by_company_id($search),
            'company_sel' => Company::get_company_sel(),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
        ])->withErrors($validation);
    }

    public function differentiate_report_2(Request $request)
    {
        Session::forget('differential_report_search');

        $validation = null;
        $search['start_date'] = date('Y-m-01');
        $search['end_date'] = date('Y-m-d');
        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required'
            ])->setAttributeNames([
                'start_date' => 'Start Date',
                'end_date' => 'End Date'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('differential_report_search');
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');
                        $search['customer_category_id'] = $request->input('customer_category_id');
                        Session::put('differential_report_search', $search);
                        break;
                    case 'reset':
                        Session::forget('differential_report_search');
                        break;
                    case 'export':
                        $search['start_date'] = $request->input('start_date');
                        $search['end_date'] = $request->input('end_date');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search["company_land_cb_id"] = $request->input('company_land_cb_id');
                        $search['customer_id'] = $request->input('customer_id');

                        $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
                        $collect_details = Reporting::get_collect_details_by_land_product($search);
                        $do_details = Reporting::get_do_details_by_land_product($search);
                        $size = SettingSize::get_size_sel();
                        $product = ProductCompanyLand::get_land_product_2($search);

                        Session::put('differential_report_search', $search);
                        return Excel::download(new DifferentialReport('components/differentiate_reporting', $date_range, $size, $product, $collect_details, $do_details, $search,), 'differential_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('differential_report_search')) {
            $date_range = CarbonPeriod::create($search['start_date'], $search['end_date']);
            $search = Session::get('differential_report_search');
        }
        // dd($search['company_land_id']);

        // $records = Reporting::differentiate_detail($search);
        // $land = ProductCompanyLand::get_land_product();
        // foreach($records['do'] as $key => $value){
        // dd($records);
        // }
        // dd($records['do']);
        return view('report.differential_report2', [
            // 'records' => $records,
            'collect_details' => Reporting::get_collect_details_by_land_product($search),
            'do_details' => Reporting::get_do_details_by_land_product($search),
            'date_range' => $date_range,
            'product_company_land' => ProductCompanyLand::get_land_product_2($search),
            'size' => SettingSize::get_size_sel(),
            'submit' => '',
            'search' => $search,
            'company_land' => CompanyLand::get_company_land_name($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(auth()->user()->company_id),
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
        ]);
    }

    public function ajax_get_product_id_by_company_land(Request $request)
    {
        $company_land_id = $request->input('company_land_id');
        $result = ProductCompanyLand::get_products_name_by_company_land_id($company_land_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_land_product_size(Request $request)
    {
        $company_id = $request->input('id');
        $land_user = Reporting::get_land_product_size($company_id);
        return $land_user;
    }

    public function ajax_get_customer_list_by_company_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $result = Reporting::get_customer_list_by_company_id($company_id);

        return response()->json(['data' => $result]);
    }

    public function differentiate_report(Request $request)
    {
        $validation = null;
        $search['month_year'] = date('m-Y');
        $search['start_date'] = date('Y-m-d');
        $search['end_date'] = date('Y-m-d');

        if (isset($_GET['reset'])) {
            Session::forget('differential_report_search');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'month_year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('differential_report_search');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['month_year'] = $request->input('month_year');
                        // $search['start_date'] = $request->input('start_date');
                        // $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');

                        Session::put('differential_report_search', $search);
                        break;
                    case 'reset':
                        Session::forget('differential_report_search');
                        break;
                    case 'export':
                    $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                    $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;
                        $search['month_year'] = $request->input('month_year');
                        // $search['start_date'] = $request->input('start_date');
                        // $search['end_date'] = $request->input('end_date');
                        $search['company_id'] = $request->input('company_id');

                        // $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                        // $collect_details = Reporting::get_collect_details($search);
                        $diff_report = Reporting::differentiate_detail($search);
                        Session::put('differential_report_search', $search);
                        break;
                }
            }
        }

        if (Session::has('differential_report_search')) {
            $search = Session::get('differential_report_search');
        }
        // dd($search['company_land_id']);

        $records = Reporting::differentiate_detail($search);
        $land = ProductCompanyLand::get_land_product();
        // foreach($records['do'] as $key => $value){
        // dd($records);
        // }
        // dd($records['do']);
        return view('report.differential_report', [
            'records' => $records,
            'submit' => '',
            'search' => $search,
            'product' => Product::get_w_size(),
            'size' => SettingSize::get_size_sel(),
            'farm' => CompanyFarm::get_farm(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(auth()->user()->company_id),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'category' => CompanyLandCategory::get_all_cat(),
            'land' => $land
        ]);
    }

    public static function ajax_get_users_by_land(Request $request)
    {
        $company_land_id = $request->input('company_land_id');
        $users = UserLand::query()
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_user_land.user_id')
            ->where('tbl_user_land.company_land_id', $company_land_id)
            ->get();
        return response()->json(['users' => $users]);
    }

    public function expense_report(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('expense_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('expense_report');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['warehouse_id'] = $request->input('warehouse_id');
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');

                        Session::put('expense_report', $search);
                        break;
                    case 'reset':
                        Session::forget('expense_report');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['warehouse_id'] = $request->input('warehouse_id');
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');

                        // $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                        // $collect_details = Reporting::get_collect_details($search);
                        $do_expense = Reporting::get_do_expense_details($search);

                        Session::put('expense_report', $search);
                        return Excel::download(new ExpenseReport('components/expense_report', $do_expense, $search), 'expense_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('expense_report')) {
            $search = Session::get('expense_report');
        }

        return view('report.expense_report', [
            'month_sel' => Reporting::get_month(),
            // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
            // 'collect_details' => Reporting::get_collect_details($search),
            // 'products' => Reporting::get_product_details(),
            'do_expense' => Reporting::get_do_expense_details($search),
            'expense_w_type' => SettingExpense::get_expense_for_do_expense(),
            'company_land' => CompanyLand::get_company_land_name($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'submit' => route('expense_report'),
            'search' => $search,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function detail_expense_report(Request $request, $year, $month)
    {
        // dd($year, $month);
        $search['year'] = $year;
        // $search['sales_to'] = date('Y-12-31');

        if (Session::has('detail_expense_report')) {
            $search = Session::get('detail_expense_report');
        }

        // $company_farm = CompanyFarm::find($company_farm_id);
        $records = Reporting::get_do_expense_details_2($search, $month);

        // if ($request->isMethod('post')) {
        //     $data = $records;
        //     $land = CompanyLand::get_company_land_by_farm_id($search, $company_farm_id);
        //     $company = CompanyLand::get_company_by_farm_id($search, $company_farm_id);
        //
        //     return Excel::download(new FarmDetailsExport('components.detailed_sales_summary_report_by_farm', $data, $land, $company), 'farm_detail_report.xlsx');
        // }

        return view('report.expense_detail_report', [
            'records' => $records,
            'month_sel' => Reporting::get_month(),
            'month' => $month,
            'warehouse_sel' => SettingWarehouse::get_warehouse_for_expense(),
            'do' => DeliveryOrder::get_do_for_expense_report(),
            'expense_w_type' => SettingExpense::get_expense_for_report(),
            'company_sel' => Company::get_company_for_report(),
            'search' => $search,
            // 'company_land' => CompanyLand::get_company_land_by_farm_id($search, $company_farm_id),
            // 'company' => CompanyLand::get_company_by_farm_id($search, $company_farm_id),
            // 'company_farm' => $company_farm,
        ]);
    }

    public function listing_invoice_daily(Request $request)
    {
        \Illuminate\Support\Facades\Session::forget('invoice_daily_report');
        Session::forget('invoice_daily_report');
        $search['sales_from'] = $request->input('sales_from');
        $search['sales_to'] = $request->input('sales_to');
        $search['company_id'] = $request->input('company_id');
        // $search['company_land_id'] = $request->input('company_land_id');
        // $search['product_id'] = $request->input('product_id');
        // $search['product_size_id'] = $request->input('product_size_id');
        // $search['user_id'] = $request->input('user_id');
        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
        $search['user_id'] = $request->input('user_id') != 'Loading...' ? $request->input('user_id') : null;

        \Illuminate\Support\Facades\Session::put('invoice_daily_report', $search);
        return redirect()->route('invoice_daily_report');
    }

    public function ajax_get_products_multi_company(Request $request)
    {
      $company_ids = $request->input('company_id');
      // $company_ids = [6, 7, 8];
      $products = Product::get_products_multi_company($company_ids);
      return $products;
    }

    public function sales_analysis_do(Request $request)
    {
        $validation = null;
        $search['date_from'] = date('Y-01-01');
        $search['date_to'] = date('Y-m-d');
        //after submit
        if (isset($_GET['reset'])) {
            Session::forget('dashboard_sales_analysis');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'date_from' => 'required',
                'date_to' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                // dd($request->all());
                switch ($submit) {
                    case 'search':
                        Session::forget('dashboard_sales_analysis');
                            $search['date_from'] = $request->input('date_from');
                            $search['date_to'] = $request->input('date_to');
                            // $search['product_id'] = $request->input('product_id');
                            // $search['product_size_id'] = $request->input('product_size_id');
                            $search['company_id'] = $request->input('company_id');
                            $search['company_cb_id'] = $request->input('company_cb_id');
                            // $search['company_land_id'] = $request->input('company_land_id');
                            // $search['company_farm_id'] = $request->input('company_farm_id');

                        Session::put('dashboard_sales_analysis', $search);
                        break;
                    case 'reset':
                        Session::forget('dashboard_sales_analysis');
                        break;
                    case 'today':
                        Session::forget('dashboard_sales_analysis');
                            $search['date_from'] = date('Y-m-d');
                            $search['date_to'] = date('Y-m-d');
                            // $search['company_id'] = $request->input('company_id');
                            // $search['company_cb_id'] = $request->input('company_cb_id');
                            Session::put('dashboard_sales_analysis', $search);
                        break;
                    case 'yesterday':
                        Session::forget('dashboard_sales_analysis');
                                $search['date_from'] = date('Y-m-d',strtotime("-1 days"));
                                $search['date_to'] = date('Y-m-d',strtotime("-1 days"));
                                Session::put('dashboard_sales_analysis', $search);
                        break;
                }
            }
        }
        $company = Company::get_company_for_sales_analysis($search);
        // dd($company);
        // $product_sel = Product::get_product_sel();
        $company_sel = Company::get_company_sel();
        // $company_land_sel = CompanyLand::get_company_land_sel();
        // $company_farm_sel = CompanyFarm::get_company_farm_sel();
        // $product_size_sel = SettingSize::get_product_size_sel();
        $sales_analysis_do = DeliveryOrder::get_sales_analysis($search);
        $sales_analysis_invoice = Invoice::get_sales_analysis($search);
        // dd($sales_analysis_invoice, $sales_analysis_do);

        return view('report.sales_analysis_do', [
            'submit' => route('sales_analysis_do'),
            'search' => $search,
            'company' => $company,
            'sales_analysis_do' => $sales_analysis_do,
            'sales_analysis_invoice' => $sales_analysis_invoice,
            // 'product_sel' => $product_sel,
            // 'product_size_sel' => $product_size_sel,
            'company_sel' => $company_sel,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            // 'company_land_sel' => $company_land_sel,
            // 'company_farm_sel' => $company_farm_sel,
        ]);
    }

    public function company_expense_report(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('company_expense');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('company_expense');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['setting_expense_category_id'] = $request->input('setting_expense_category_id');

                        Session::put('company_expense', $search);
                        break;
                    case 'reset':
                        Session::forget('company_expense');
                        break;
                    case 'export':
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['setting_expense_category_id'] = $request->input('setting_expense_category_id');

                        $records = Reporting::get_company_expense_report($search);

                        Session::put('company_expense', $search);
                        return Excel::download(new CompanyExpenseExport('components.company_expense_report', $records, $search), 'company_expense_report.xlsx');
                        break;
                    }
                }
            }

        $records = Reporting::get_company_expense_report($search);

        return view('report.company_expense_report', [
            'submit' => route('company_expense'),
            'search' => $search,
            'month_sel' => Reporting::get_month_w_filter($search),
            'company' => Company::get_company_for_daily_report($search),
            'company_cb' => Company::get_company_check_box(),
            'company_land' => CompanyLand::get_company_land_name($search),
            'expense_category' => SettingExpenseCategory::get_expense_for_report($search),
            'company_expense_records' => $records,
            'company_sel' => Company::get_company_sel(),
            'category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
        ]);
    }

    public function company_expense_report_land_product($company_land_id, $setting_expense_category_id, $year, $month_num){

        $month_sel = Reporting::get_month();

        if($month_num > 0){
            $current_month[$month_num] = $month_sel[$month_num];
        }

        $records = Reporting::get_company_expense_report_land_product($company_land_id, $setting_expense_category_id, $year, $month_num);


        return view('report.company_expense_report_land_product', [
            'submit' => route('company_expense_report_land_product'),
            'company_expense_land_product' => $records,
            'month_sel' => $current_month,
            'company_land' => CompanyLand::get_company_land_name_for_report($company_land_id),
            'land_product' => ProductCompanyLand::get_product_by_company_land_id_for_report($company_land_id),
            'expense_category' => SettingExpenseCategory::get_expense_category_by_setting_expense_cateogry_id($setting_expense_category_id),
            'setting_expense' => SettingExpense::get_setting_expense_by_setting_expense_category($setting_expense_category_id)
        ]);
    }

    public function company_expense_report_land_product_total($company_land_id, $setting_expense_category_id, $year){


        $records = Reporting::get_company_expense_report_land_product_total($company_land_id, $setting_expense_category_id, $year);


        return view('report.company_expense_report_land_product_total', [
            'submit' => route('company_expense_report_land_product_total'),
            'company_expense_land_product' => $records,
            'company_land' => CompanyLand::get_company_land_name_for_report($company_land_id),
            'land_product' => ProductCompanyLand::get_product_by_company_land_id_for_report($company_land_id),
            'expense_category' => SettingExpenseCategory::get_expense_category_by_setting_expense_cateogry_id($setting_expense_category_id),
            'setting_expense' => SettingExpense::get_setting_expense_by_setting_expense_category($setting_expense_category_id)
        ]);
    }

    public function detail_company_expense_report(Request $request, $month_num, $setting_expense_category_id)
    {
        $validation = null;
        $search['year'] = date('Y');
        $search['month'] = $month_num;
        $search['setting_expense_category_id'] = $setting_expense_category_id;
        // dd($search['setting_expense_category_id']);

        if (isset($_GET['reset'])) {
            Session::forget('company_expense_detail');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('company_expense_detail');
                        $search['month_year'] = $request->input('month_year');
                        $search['company_id'] = $request->input('company_id');
                        $search['setting_expense_category_id'] = $setting_expense_category_id;

                        Session::put('company_expense_detail', $search);
                        break;
                    case 'reset':
                        Session::forget('company_expense_detail');
                        break;
                    }
                }
            }

            if (Session::has('company_expense_detail')) {
                $search = Session::get('company_expense_detail');
            }

            // if (Session::has('company_expense')) {
            //     $search = Session::get('company_expense');
            // }

            $month_sel = Reporting::get_month();

            if($month_num > 0){
                $current_month[$month_num] = $month_sel[$month_num];
            }

        $records = Reporting::get_company_expense_detail_report($search, $month_num, $setting_expense_category_id);

        return view('report.company_expense_detail_report', [
            'submit' => route('company_expense_detail'),
            'search' => $search,
            'company_expense_detail' => $records,
            'month_sel' => $current_month,
            'company' => Company::get_company_for_daily_report($search),
            'company_land' => CompanyLand::get_company_land_name($search),
            'expense_category' => SettingExpenseCategory::get_expense_for_report($search),
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
        ]);
    }

    public function company_expense_reporting(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');
        // $search['month_year'] = date('m-Y');

        if (isset($_GET['reset'])) {
            Session::forget('company_expense_reporting');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'month_year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('company_expense_reporting');
                        $search['month_year'] = $request->input('month_year');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['setting_expense_category_id'] = $request->input('setting_expense_category_id');

                        Session::put('company_expense_reporting', $search);
                        break;
                    case 'reset':
                        Session::forget('company_expense_reporting');
                        break;
                    case 'export':
                        $search['month_year'] = $request->input('month_year');
                        $search['company_land_id'] = $request->input('company_id');
                        $search['setting_expense_category_id'] = $request->input('setting_expense_category_id');

                        $company_expense = Reporting::company_expense_reporting($search);

                        Session::put('company_expense', $search);
                        return Excel::download(new CompanyExpenseReportExport('components.company_expense_report', $company_expense, $search), 'company_expense_reporting.xlsx');
                        break;
                    }
                }
            }

        $company_expense = Reporting::company_expense_reporting($search);

        return view('report.company_expense_reporting', [
            'submit' => route('company_expense_reporting'),
            'search' => $search,
            'company_expense' => $company_expense,
            'month_sel' => Reporting::get_month(),
            'company_land' => CompanyLand::get_company_land_name($search),
            'category' => SettingExpenseCategory::get_expense_for_report($search),
            'expense' => CompanyExpense::get_company_expense_for_report(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
        ]);
    }

    public function company_expense_reporting_details(Request $request, $month_num, $setting_expense_category_id)
    {
        $validation = null;
        $search = null;
        $search['month_year'] = date('m-Y');
        $search['setting_expense_category_id'] = $setting_expense_category_id;

        $month_sel = Reporting::get_month();

        if($month_num > 0){
            $current_month[$month_num] = $month_sel[$month_num];
        }


        $records = Reporting::get_company_expense_detail_report_component($search, $month_num);

        return view('report.company_expense_reporting_details', [
            'submit' => route('company_expense_reporting'),
            'search' => $search,
            'company_expense_detail' => $records,
            'month_sel' => $current_month,
            'company_land' => CompanyLand::get_company_land_name($search),
            'expense_category' => SettingExpenseCategory::get_expense_for_report($search),
            'setting_expense' => SettingExpense::get_setting_expense_for_report(),
        ]);
    }

    public function company_land_tree_report(Request $request, $company_land_zone_id = null){
        $search = array();
        $search['company_land_zone_id'] = $company_land_zone_id;
        $search['company_id'] = auth()->user()->company_id != 0 ? auth()->user()->company_id : null;
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');

            switch ($submit_type) {
                case 'search':
                    session(['company_land_tree_report_search' => [
                        'freetext' => $request->input('freetext'),
                        'product_id' => $request->input('product_id'),
                        'company_id' => $request->input('company_id'),
                        'company_land_id' => $request->input('company_land_id')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('company_land_tree_report_search');
                    break;
                case 'export':
                    $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                    $search['company_id'] = $request->input('company_id');
                    $search['product_id'] = $request->input('product_id') != 'Loading...' ? $request->input('product_id') : null;

                    $land_tree_information = Reporting::get_company_land_tree($search);

                    Session::put('company_land_tree_report_search', $search);
                    return Excel::download(new LandTreeReport('components/company_land_tree_report', $land_tree_information, $search), 'company_land_tree_report.xlsx');
                    break;

                    // return view('components.company_land_tree_report', [
                    //     'search' => $search,
                    //     'records' => $land_tree_information,
                    // ]);
                    // break;
            }
        }

        $search = session('company_land_tree_report_search') ?? $search;

        return view('report.company_land_tree_report', [
            'submit' => route('land_tree_listing', $company_land_zone_id),
            'records' => Reporting::get_company_land_tree($search),
            'search' => $search,
            'company_sel' => [''=> 'All Company'] + Company::get_all_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
        ]);
    }

    public function formula_usage_report_sa(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('formula_usage_report_sa');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);
            if (!$validation->fails()) {
            $submit = $request->input('submit');
            switch ($submit) {
                    case 'search':
                        Session::forget('formula_usage_report');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['warehouse_id'] = $request->input('warehouse_id');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');

                        Session::put('formula_usage_report_sa', $search);
                    break;
                    case 'reset':
                        Session::forget('formula_usage_report_sa');
                    break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['warehouse_id'] = $request->input('warehouse_id');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');

                    // $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                    // $collect_details = Reporting::get_collect_details($search);
                    // $do_expense = Reporting::get_do_expense_details($search);

                    Session::put('formula_usage_report_sa', $search);
                    return Excel::download(new FormulaUsageSAExport('components/formula_usage_report_sa', $search), 'Formula_Usage_Report.xlsx');
                    break;
                    }
            }
        }

      if (Session::has('formula_usage_report_sa')) {
      $search = Session::get('formula_usage_report_sa');
      }

      return view('report.formula_usage_report_sa', [
        'month_sel' => Reporting::get_month_w_filter($search),
        'setting_formula_usage_category' => SettingFormulaCategory::get_selection(),
        'formula_usage' => Reporting::get_formula_usage_sa($search),
        // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
        // 'collect_details' => Reporting::get_collect_details($search),
        // 'products' => Reporting::get_product_details(),
        // 'do_expense' => Reporting::get_do_expense_details($search),
        // 'expense_w_type' => SettingExpense::get_expense_for_report(),
        'company' => Company::get_company_for_sales_product_company_report($search),
        'company_land_sel' => CompanyLand::get_company_land_sel(),
        'company_sel' => Company::get_company_sel(),
        'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
        'submit' => route('formula_usage_report_sa'),
        'search' => $search,
        'company_cb' => Company::get_company_check_box(),
        'product_cb' => Product::all(),
      ])->withErrors($validation);
    }

    public function formula_usage_report_sa_detail(Request $request, $year, $month_num, $setting_formula_category_id)
    {
        $validation = null;
        $search['year'] = $year;
        $search['month'] = $month_num;

        if (isset($_GET['reset'])) {
            Session::forget('formula_usage_report_sa');
        }

            if ($request->isMethod('post')) {
                $validation = Validator::make($request->all(), [
                    'year' => 'required',
                ]);
                    if (!$validation->fails()) {
                    $submit = $request->input('submit');
                    switch ($submit) {
                        case 'search':
                        Session::forget('formula_usage_report');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['warehouse_id'] = $request->input('warehouse_id');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');

                        Session::put('formula_usage_report_sa', $search);
                        break;
                        case 'reset':
                        Session::forget('formula_usage_report_sa');
                        break;
                        // case 'export':
                        //   $search['company_land_id'] = $request->input('company_land_id');
                        //   $search['warehouse_id'] = $request->input('warehouse_id');
                        //   $search['year'] = $request->input('year');
                        //   $search['month'] = $request->input('month');
                        //   $search['company_id'] = $request->input('company_id');
                        //   $search['company_cb_id'] = $request->input('company_cb_id');
                        //   $search['product_cb_id'] = $request->input('product_cb_id');
                        //
                        // // $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                        // // $collect_details = Reporting::get_collect_details($search);
                        // // $do_expense = Reporting::get_do_expense_details($search);
                        //
                        // Session::put('formula_usage_report_sa', $search);
                        // return Excel::download(new ExpenseReport('components/expense_report', $do_expense, $search), 'expense_report.xlsx');
                        // break;
                    }
                }
            }

            if (Session::has('formula_usage_report_sa_detail')) {
            $search = Session::get('formula_usage_report_sa_detail');
            }

            if (Session::has('formula_usage_report_sa')) {
            $search = Session::get('formula_usage_report_sa');
            }

            $month_arr = [
                '1' => 'January',
                '2' => 'Febuary',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December',
            ];

            foreach ($month_arr as $key => $value) {
                if($key == $month_num){
                    $month_name = $value;
                }
            }
        return view('report.formula_usage_report_sa_detail', [
        // 'month_sel' => Reporting::get_month_w_filter($search),
        // 'setting_formula_usage_category' => SettingFormulaCategory::get_selection(),
        'formula_usage' => Reporting::get_formula_usage_sa_detail($search, $year, $month_num, $setting_formula_category_id),
        // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
        // 'collect_details' => Reporting::get_collect_details($search),
        'month_name' => $month_name,
        'setting_formula_category_name' => SettingFormulaCategory::where('setting_formula_category_id', $setting_formula_category_id)->pluck('setting_formula_category_name')->first(),
        'products' => Product::get_sel_w_filter($search),
        // 'do_expense' => Reporting::get_do_expense_details($search),
        // 'expense_w_type' => SettingExpense::get_expense_for_report(),
        'company' => Company::get_company_for_sales_product_company_report($search),
        'company_land' => CompanyLand::get_land($search),
        // 'company_land_sel' => CompanyLand::get_company_land_sel(),
        // 'company_sel' => Company::get_company_sel(),
        // 'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
        'submit' => route('formula_usage_report_sa'),
        'search' => $search,
        // 'company_cb' => Company::get_company_check_box(),
        // 'product_cb' => Product::all(),
        ])->withErrors($validation);

    }

    public function ajax_get_product_sel_by_company_land_id(Request $request)
    {
        $company_land_id = $request->input('company_land_id');
        $result = Product::get_by_company_land($company_land_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_setting_size_by_product_id(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = ProductSizeLink::get_size_name_by_product_id($product_id);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function formula_usage_report_admin(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('formula_usage_report_admin');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);
            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                    Session::forget('formula_usage_report');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['warehouse_id'] = $request->input('warehouse_id');
                    $search['year'] = $request->input('year');
                    $search['month'] = $request->input('month');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_cb_id'] = $request->input('company_cb_id');
                    $search['product_cb_id'] = $request->input('product_cb_id');

                    Session::put('formula_usage_report_admin', $search);
                    break;
                    case 'reset':
                    Session::forget('formula_usage_report_admin');
                    break;
                    case 'export':
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['warehouse_id'] = $request->input('warehouse_id');
                    $search['year'] = $request->input('year');
                    $search['month'] = $request->input('month');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_cb_id'] = $request->input('company_cb_id');
                    $search['product_cb_id'] = $request->input('product_cb_id');

                    $formula_usage_info = Reporting::get_formula_usage_admin($search);

                    Session::put('formula_usage_report_admin', $search);
                    return Excel::download(new FormulaUsageAdmin('components/formula_usage_report_admin', $formula_usage_info, $search), 'formula_usage_report.xlsx');
                    break;
                    }
            }
        }

        if (Session::has('formula_usage_report_admin')) {
        $search = Session::get('formula_usage_report_admin');
        }

        return view('report.formula_usage_report_admin', [
        'month_sel' => Reporting::get_month_w_filter($search),
        'setting_formula_usage_category' => SettingFormulaCategory::get_selection(),
        'formula_usage' => Reporting::get_formula_usage_admin($search),
        // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
        // 'collect_details' => Reporting::get_collect_details($search),
        // 'products' => Reporting::get_product_details(),
        // 'do_expense' => Reporting::get_do_expense_details($search),
        // 'expense_w_type' => SettingExpense::get_expense_for_report(),
        'company' => Company::get_company_for_sales_product_company_report($search),
        'company_land_sel' => CompanyLand::get_company_land_sel(),
        'company_sel' => Company::get_company_sel(),
        'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
        'submit' => route('formula_usage_report_admin'),
        'search' => $search,
        'company_cb' => Company::get_company_check_box(),
        'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function formula_usage_report_admin_detail(Request $request, $year, $month_num, $setting_formula_category_id)
    {
        $validation = null;
        $search['year'] = $year;
        $search['month'] = $month_num;

        if (isset($_GET['reset'])) {
            Session::forget('formula_usage_report_admin');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
                ]);
                if (!$validation->fails()) {
                    $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                    Session::forget('formula_usage_report_admin');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['warehouse_id'] = $request->input('warehouse_id');
                    $search['year'] = $request->input('year');
                    $search['month'] = $request->input('month');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_cb_id'] = $request->input('company_cb_id');
                    $search['product_cb_id'] = $request->input('product_cb_id');

                    Session::put('formula_usage_report_admin', $search);
                    break;
                    case 'reset':
                    Session::forget('formula_usage_report_admin');
                    break;
                    // case 'export':
                    // $search['company_land_id'] = $request->input('company_land_id');
                    // $search['warehouse_id'] = $request->input('warehouse_id');
                    // $search['year'] = $request->input('year');
                    // $search['month'] = $request->input('month');
                    // $search['company_id'] = $request->input('company_id');
                    // $search['company_cb_id'] = $request->input('company_cb_id');
                    // $search['product_cb_id'] = $request->input('product_cb_id');

                    // // $delivery_order_item_details = Reporting::get_delivery_order_item_details($search);
                    // // $collect_details = Reporting::get_collect_details($search);
                    // // $do_expense = Reporting::get_do_expense_details($search);

                    // Session::put('formula_usage_report_admin_detail', $search);
                    // return Excel::download(new ExpenseReport('components/expense_report', $do_expense, $search), 'expense_report.xlsx');
                    // break;
                    }
                }
        }

        $month_arr = [
            '1' => 'January',
            '2' => 'Febuary',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        foreach ($month_arr as $key => $value) {
            if($key == $month_num){
                $month_name = $value;
            }
        }

        if (Session::has('formula_usage_report_admin_detail')) {
            $search = Session::get('formula_usage_report_admin_detail');
        }

        if (Session::has('formula_usage_report_admin')) {
            $search = Session::get('formula_usage_report_admin');
        }

        return view('report.formula_usage_report_admin_detail', [
        // 'month_sel' => Reporting::get_month_w_filter($search),
        // 'setting_formula_usage_category' => SettingFormulaCategory::get_selection(),
        'formula_usage' => Reporting::get_formula_usage_admin_detail($search, $year, $month_num, $setting_formula_category_id),
        // 'delivery_order_item_details' => Reporting::get_delivery_order_item_details($search),
        // 'collect_details' => Reporting::get_collect_details($search),
        'month_name' => $month_name,
        'setting_formula_category_name' => SettingFormulaCategory::where('setting_formula_category_id', $setting_formula_category_id)->pluck('setting_formula_category_name')->first(),
        'products' => Product::get_sel_w_filter($search),
        // 'do_expense' => Reporting::get_do_expense_details($search),
        // 'expense_w_type' => SettingExpense::get_expense_for_report(),
        'company' => Company::get_company_for_sales_product_company_report($search),
        'company_land' => CompanyLand::get_land($search),
        // 'company_land_sel' => CompanyLand::get_company_land_sel(),
        // 'company_sel' => Company::get_company_sel(),
        // 'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
        'submit' => route('formula_usage_report_admin'),
        'search' => $search,
        // 'company_cb' => Company::get_company_check_box(),
        // 'product_cb' => Product::all(),
        ])->withErrors($validation);
    }

    public function farm_manager_worker_expense_report(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('farm_manager_worker_expense');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

        if (!$validation->fails()) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('farm_manager_worker_expense');
                    $search['year'] = $request->input('year');
                    $search['month'] = $request->input('month');
                    $search['company_id'] = $request->input('company_id');
                    $search['user_id'] = $request->input('user_id');

                    Session::put('farm_manager_worker_expense', $search);
                    break;
                case 'reset':
                    Session::forget('farm_manager_worker_expense');
                    break;
                case 'export':
                    $search['year'] = $request->input('year');
                    $search['month'] = $request->input('month');
                    $search['company_id'] = $request->input('company_id');
                    $search['user_id'] = $request->input('user_id');

                    $company_expense_worker = Reporting::company_farm_worker_expense_report($search);

                    Session::put('farm_manager_worker_expense', $search);
                    return Excel::download(new FarmManagerWorkerExpenseExport('components/farm_manager_worker_expense_report', $company_expense_worker, $search), 'farm_manager_worker_expense_report.xlsx');
                    break;
                }
            }
        }
        if (Session::has('farm_manager_worker_expense')) {
            $search = Session::get('farm_manager_worker_expense');
        }

        $company = Company::get_company_for_daily_report($search);
        $farm_managers = User::get_farm_manager_name_by_company($search);
        $company_expense_worker = Reporting::company_farm_worker_expense_report($search);
        $worker = CompanyExpenseWorker::get_company_expense_worker_id();

        return view('report.farm_manager_worker_expense_report',[
            'submit' => route('farm_manager_worker_expense'),
            'search' => $search,
            'month' => Reporting::get_month_w_filter($search),
            'company' => $company,
            'manager' => $farm_managers,
            'company_expense_worker' => $company_expense_worker,
            'worker' => $worker,
            'company_sel' => Company::get_company_sel(),
        ])->withErrors($validation);
    }

    public function ajax_get_farm_manager_sel_by_company(Request $request)
    {
        $company_id = $request->input('company_id');
        return User::get_farm_manager_sel_by_company($company_id);
        return User::get_user_land_sel();
    }

    public function farm_manager_worker_expense_report_detail(Request $request, $year, $month_num, $company_id, $user_id)
    {
        $validation = null;
        $search['year'] = $year;
        $search['month'] = $month_num;
        $search['company_id'] = $company_id;
        $search['user_id'] = $user_id;

        if (isset($_GET['reset'])) {
            Session::forget('farm_manager_worker_expense_detail');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('farm_manager_worker_expense_detail');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        $search['user_id'] = $request->input('user_id');

                        Session::put('farm_manager_worker_expense_detail', $search);
                        break;
                    case 'reset':
                        Session::forget('farm_manager_worker_expense_detail');
                        break;
                }
            }
        }

        if (Session::has('farm_manager_worker_expense_detail')) {
            $search = Session::get('farm_manager_worker_expense_detail');
        }
        if (Session::has('farm_manager_worker_expense')) {
            $search = Session::get('farm_manager_worker_expense');
        }

        return view('report.farm_manager_worker_expense_report_detail',[
            'submit' => route('farm_manager_worker_expense_detail'),
            'search' => $search,
            'company' => Company::find($company_id),
            'farm_manager' => User::find($user_id),
            'company_expense_worker' => Reporting::company_farm_worker_expense_report_detail($year, $month_num, $company_id, $user_id),
            'workers' => Worker::get_worker_by_farm_manager($user_id),
            'setting_expense' => SettingExpense::get_setting_expense_for_worker(),
            'setting_expense_category' => SettingExpenseCategory::get_expense_category_for_worker(),
        ])->withErrors($validation);
    }

    public function budget_report(Request $request){
        $validation = null;
        $year = date('Y');

        $search = array();
        if (isset($_GET['reset'])) {
            Session::forget('budget_report_search');
        }
        $search['year'] = date('Y');

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('budget_report_search');
                        $search['year'] = $request->input('year');
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;
                        $search['budget_category_id'] = $request->input('budget_category_id');

                        Session::put('budget_report_search', $search);
                        break;
                    case 'reset':
                        Session::forget('budget_report_search');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;
                        $search['year'] = $request->input('year');

                        $actual_budget_expense = Reporting::get_company_expense($search);

                        Session::put('budget_report_search', $search);
                        return Excel::download(new BudgetReportExport('components/budget_report', $actual_budget_expense, $search), 'budget_report.xlsx');
                        break;
                    case 'export-admin':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['budget_category_id'] = $request->input('budget_category_id');
                        $search['year'] = $request->input('year');

                        $overwrite_budget = Reporting::get_budget_company_formula_expense($search);

                        Session::put('budget_report_search', $search);
                        return Excel::download(new BudgetReportDetailExport('components.budget_report_detail', $overwrite_budget, $search), 'budget_report_admin.xlsx');
                        break;
                    }
                }
            }

        if (Session::has('budget_report_search')) {
            $search = Session::get('budget_report_search');
        }

        $actual_budget_expense = Reporting::get_company_expense($search);
        $actual_budget_formula = Reporting::get_formula_usage_item($search);
        $overwrite_budget = Reporting::get_budget_company_formula_expense($search);

        return view('report.budget_report', [
            'submit' => route('budget_report'),
            'search' => $search,
            'default_budget' => Setting::where('setting_slug', 'default_budget_per_tree')->pluck('setting_value')->first(),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'budget_category_sel' => ['' => 'Please Select Category']+Reporting::get_budget_sel(),
            'company' => Company::get_company_for_daily_report($search),
            'company_land' => CompanyLand::get_company_land_name($search),
            'actual_expense' => $actual_budget_expense,
            'actual_formula' => $actual_budget_formula,
            'budget_category' => Reporting::get_budget_category_name($search),
            'overwrite_budget' => $overwrite_budget,
        ])->withErrors($validation);
    }

    //SUPER ADMIN
    public static function budget_report_detail(Request $request, $company_id, $year){
        $search['year'] = $year;
        $overwrite_budget = Reporting::get_budget_company_formula_expense($search);

        return view('report.budget_report_detail', [
            'budget_category' => Reporting::get_budget_category_name(null),
            'search' => $search,
            'company_land' => CompanyLand::get_by_company_id($company_id),
            'overwrite_budget' => $overwrite_budget,
        ]);
    }

    public function claim_report(Request $request)
    {
        $validation = null;

        if (isset($_GET['reset'])) {
            Session::forget('claim_report_search');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);
            if (!$validation->fails()) {
                $search['year'] = $request->input('year');
                $search['company_id'] = $request->input('company_id');
                Session::put('claim_report_search', $search);
            }
        }
        $search = Session::has('claim_report_search') ? Session::get('claim_report_search') : array("year" => date('Y'));

        $wfunction = new Wfunction();
        $year_sel = $wfunction->year_sel();
        $month_sel = $wfunction->month_sel();

        $records = Reporting::get_claim_by_year($search); //test
        $company_sel = Company::get_company_sel();

        return view('report.claim_report', [
            'page_title' => 'Staff Claim Reporting (Years)',
            'submit' => route('claim_report'),
            'search' => $search,
            'year_sel' => $year_sel,
            'month_sel' => $month_sel,
            'company_sel' => $company_sel,
            'current_year' => date('Y'),
            'records' => $records,
        ])->withErrors($validation);
    }

    public function claim_detail_report($year = 0, $month = 0, $company_id = 0, $claim_status_id = 0)
    {
        if($company_id == 0 || $year == 0 || $month == 0 || $claim_status_id == 0){
            redirect(route('claim_report'));
        }

        $search = array();
        $search['company_id'] = $company_id;
        $search['year'] = $year;
        $search['month'] = $month;
        $search['claim_status_id'] = $claim_status_id;

        $wfunction = new Wfunction();
        $year_sel = $wfunction->year_sel();
        $month_sel = $wfunction->month_sel();

        $records = Reporting::get_claim_by_company($search);
        $company_sel = Company::get_company_sel();
        unset($company_sel['']);

        return view('report.claim_detail_report', [
            'page_title' => 'Staff Claim Reporting (Users)',
            'submit' => route('claim_report'),
            'search' => $search,
            'year_sel' => $year_sel,
            'month_sel' => $month_sel,
            'company_sel' => $company_sel,
            'current_year' => date('Y'),
            'records' => $records,
        ]);
    }

    public static function tree_target_report(Request $request){
        $validation = $search = null;
        if (isset($_GET['reset'])) {
            Session::forget('tree_target_report_search');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [

            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('tree_target_report_search');
                            $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;
                            $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;

                        Session::put('tree_target_report_search', $search);
                        break;
                    case 'reset':
                        Session::forget('tree_target_report_search');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;

                        Session::put('tree_target_report_search', $search);
                        return Excel::download(new TreeTargetReportExport('components/tree_target_report', $search), 'tree_target_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('tree_target_report_search')) {
            $search = Session::get('tree_target_report_search');
        }

        return view('report.tree_target_report',[
            'submit' => route('tree_target_report'),
            'search' => $search,
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company' => Company::get_company_for_daily_report($search),
            'company_land' => CompanyLand::get_company_land_name($search),
            'number_of_tree_per_acre' => Setting::where('setting_slug', 'number_of_tree_per_acre')->pluck('setting_value')->first(),
            'total_tree_planted' => Reporting::get_number_tree_planted(),
            'small_tree_planted' => Reporting::get_small_tree_planted(),
            'baby_tree_planted' => Reporting::get_baby_tree_planted(),
        ])->withErrors($validation);

    }

    public static function forecast_report(Request $request){
        $validation = $search = null;
        if (isset($_GET['reset'])) {
            Session::forget('forecast_report_search');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [

            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('forecast_report_search');
                            $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;
                            $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                            $search['company_cb_id'] = $request->input('company_cb_id');
                            $search['company_land_cb_id'] = $request->input('company_land_cb_id');

                        Session::put('forecast_reportt_search', $search);
                        break;
                    case 'reset':
                        Session::forget('forecast_report_search');
                        break;
                    case 'export':
                        $search['company_land_id'] = $request->input('company_land_id') != 'Loading...' ? $request->input('company_land_id') : null;
                        $search['company_id'] = $request->input('company_id') != 'Loading...' ? $request->input('company_id') : null;
                        $search['company_cb_id'] = $request->input('company_cb_id');
                        $search['company_land_cb_id'] = $request->input('company_land_cb_id');

                        Session::put('forecast_report_search', $search);
                        return Excel::download(new PnLForecastExport('components/forecast_report', $search), 'pnl_forecast_report.xlsx');
                        break;
                }
            }
        }

        if (Session::has('forecast_report_search')) {
            $search = Session::get('forecast_report_search');
        }

        return view('report.forecast_report',[
            'submit' => route('forecast_report'),
            'search' => $search,
            'price' => Reporting::get_average_price_for_forecast_report($search),
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_pnl_item' => Reporting::get_pnl_item(),
            'setting' => Reporting::get_setting_forecast(),
            'forecast_report_result' => Reporting::get_forecast_report_result($search),
            'company_cb' => Company::get_company_check_box(),
        ])->withErrors($validation);

    }

    public function ajax_get_land_by_company_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $result = CompanyLand::get_by_company_id_checkbox($company_id);

        return $result;
    }
}
