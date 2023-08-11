<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Model\Company;
use App\Model\Invoice;
use App\Model\Product;
use App\Model\Reporting;
use App\Model\CompanyFarm;
use App\Model\CompanyLand;
use App\Model\ProductInfo;
use App\Model\SettingSize;
use App\Model\DeliveryOrder;
use Illuminate\Http\Request;
use App\Model\SettingExpense;
use App\Model\ProductCategory;
use App\Model\ProductSizeLink;
use App\Model\CustomerCategory;
use App\Model\SettingWarehouse;
use App\Model\ProfitLossReporting;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\SettingExpenseCategory;
use App\Exports\ProfitLossReportExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Exports\ProfitLossReportExport_y2y;

class DashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function dashboard(Request $request, $search_date=null)
    {
        $search['date'] = @$search_date ?? date('Y-m-d');

        if ($request->isMethod('post')) {
            $search['date'] = $request->input('search_date');
        }

        if (auth()->user()->user_type_id == 1) {
            if (count(Product::all()) > 0) {
                $product = true;
            } else {
                $product = false;
            }
        } else {
            $product = Product::get_product_company();
        }

        $price_information = Invoice::get_price_information($search);
        return view('dashboard.price_information', [
            'submit' => route('dashboard', ['tenant' => tenant('id')]),
            'search' => $search,
            'customer_category' => CustomerCategory::get_customer_category_sel(),
            'product_category' => ProductCategory::get_product_category_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
            'product' => $product,
            'farm' => CompanyFarm::get_company_farm(),
            'warehouse' => SettingWarehouse::get_warehouse_sel(),
            'price_information' => $price_information,
            'product' => Reporting::get_product_details(),
        ]);
    }

    public function dashboard_price_analysis(Request $request)
    {
        $validation = null;
        $search['date_from'] = date('Y-m-01');
        $search['date_to'] = date('Y-m-t');
        //after submit
        if (isset($_GET['reset'])) {
            Session::forget('dashboard_price_analysis');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'date_from' => 'required',
                'date_to' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('dashboard_price_analysis');
                            $search['date_from'] = $request->input('date_from');
                            $search['date_to'] = $request->input('date_to');
                            $search['product_id'] = $request->input('product_id');
                            $search['product_size_id'] = $request->input('product_size_id');
                            $search['company_id'] = $request->input('company_id');
                            $search['company_land_id'] = $request->input('company_land_id');
                            $search['company_farm_id'] = $request->input('company_farm_id');

                        Session::put('dashboard_price_analysis', $search);
                        break;
                    case 'reset':
                        Session::forget('dashboard_price_analysis');
                        break;
                }
            }
        }

        $product_sel = Product::get_product_sel();
        $company_sel = Company::get_company_sel();
        $company_land_sel = CompanyLand::get_company_land_sel();
        $company_farm_sel = CompanyFarm::get_company_farm_sel();
        $product_size_sel = SettingSize::get_product_size_sel();
        $price_analysis = Invoice::get_price_analysis($search);


        return view('dashboard.price_analysis', [
            'submit' => route('dashboard_price_analysis', ['tenant' => tenant('id')]),
            'search' => $search,
            'product_sel' => $product_sel,
            'product_size_sel' => $product_size_sel,
            'company_sel' => $company_sel,
            'company_land_sel' => $company_land_sel,
            'company_farm_sel' => $company_farm_sel,
        ]);
    }

    public function dashboard_sales_analysis(Request $request)
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
                            $search['company_id'] = $request->input('company_id');
                            $search['company_cb_id'] = $request->input('company_cb_id');

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
        $company_sel = Company::get_company_sel_dashboard();
        // $company_land_sel = CompanyLand::get_company_land_sel();
        // $company_farm_sel = CompanyFarm::get_company_farm_sel();
        // $product_size_sel = SettingSize::get_product_size_sel();
        // $sales_analysis_do = DeliveryOrder::get_sales_analysis($search);
        $sales_analysis_invoice = Invoice::get_sales_analysis($search);
        $sales_analysis_do = DeliveryOrder::get_sales_analysis($search);
        // dd($sales_analysis_invoice, $sales_analysis_do);

        return view('dashboard.sales_analysis', [
            'submit' => route('dashboard_sales_analysis', ['tenant' => tenant('id')]),
            'search' => $search,
            'company' => $company,
            // 'sales_analysis_do' => $sales_analysis_do,
            'sales_analysis_invoice' => $sales_analysis_invoice,
            'sales_analysis_do' => $sales_analysis_do,
            // 'product_sel' => $product_sel,
            // 'product_size_sel' => $product_size_sel,
            'company_sel' => $company_sel,
            'company_cb' => Company::get_company_check_box(),
            'product_cb' => Product::all(),
            // 'company_land_sel' => $company_land_sel,
            // 'company_farm_sel' => $company_farm_sel,
        ]);
    }

    public function dashboard_profit_loss_analysis(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');
        $search['setting_reporting_template_id'] = 1;
        $search['level_view_id'] = 1;

        if (isset($_GET['reset'])) {
            Session::forget('dashboard_profit_loss_analysis');
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
                        Session::forget('dashboard_profit_loss_analysis');
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
                        Session::put('dashboard_profit_loss_analysis', $search);
                        break;
                    case 'reset':
                        Session::forget('dashboard_profit_loss_analysis');
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

                                Session::put('dashboard_profit_loss_analysis', $search);

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

                                Session::put('dashboard_profit_loss_analysis', $search);

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

                                Session::put('dashboard_profit_loss_analysis', $search);

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
                                Session::put('dashboard_profit_loss_analysis', $search);
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

        if (Session::has('dashboard_profit_loss_analysis')) {
            $search = Session::get('dashboard_profit_loss_analysis');
        }

        $return_arr = [
            'submit' => route('dashboard_profit_loss_analysis', ['tenant' => tenant('id')]),
            'title' => 'Dashboard Profit & Loss Analysis',
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_cb' => Company::get_company_check_box(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'product_cb' => Product::where('is_deleted', 0)->get(),
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

    public function ajax_price_analysis(Request $request){

        $search['date_from'] = $request->input('date_from');
        $search['date_to'] = $request->input('date_to');
        $search['company_id'] = $request->input('company_id');
        $search['company_farm_id'] = $request->input('company_farm_id');
        $search['company_land_id'] = $request->input('company_land_id');
        $search['product_id'] = $request->input('product_id');
        $search['product_size_id'] = $request->input('product_size_id');

        $price_analysis = Invoice::get_price_analysis($search);
        return response()->json(['data'=>$price_analysis]);
    }

    public function ajax_get_farm_by_company_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $result = CompanyFarm::get_farm_by_company_id($company_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_land_by_company_farm_id(Request $request)
    {
        $company_farm_id = $request->input('company_farm_id');
        $result = CompanyFarm::get_land_name_by_company_farm_id($company_farm_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_product_id_by_company_land(Request $request)
    {
        $company_land_id = $request->input('company_land_id');
        $result = ProductCompanyLand::get_products_name_by_company_land_id($company_land_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_setting_size_by_product_id(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = ProductSizeLink::get_size_name_by_product_id($product_id);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function price_information_add(Request $request, $search_date=null, $company_farm_name=null, $product = null){
        $validation = $company_farm_id = $post = null;
        $detail = [
            'search_date' => @$search_date,
            'company_farm_name' => $company_farm_name,
            'product' => $product,
        ];
        $product_id = Product::where('product_name', explode(' - ',$product)[0])->value('product_id');
        $size_id = SettingSize::where('setting_product_size_name', explode(' - ',$product)[1])->value('setting_product_size_id');

        if($request->isMethod('post')){
            $validation = Validator::make($request->all(), [
                'search_date' => 'required',
                'company_farm_id' => 'required',
                'product_id' => 'required',
                'setting_product_size_id' => 'required',
                'product_info_price' => 'required'
            ])->setAttributeNames([
                'search_date' => 'Date',
                'company_farm_id' => 'Farm',
                'product_id' => 'Product',
                'setting_product_size_id' => 'Grade',
                'product_info_price' => 'Price'
            ]);

            if(!$validation->fails()) {
                ProductInfo::insert([
                    'product_id'=> $request->input('product_id'),
                    'setting_product_size_id' => $request->input('setting_product_size_id'),
                    'company_farm_id' => $request->input('company_farm_id'),
                    'product_info_date' => $request->input('search_date'),
                    'product_info_price' => $request->input('product_info_price'),
                    'product_info_created' => now(),
                ]);

                Session::flash('success_msg', 'Successfully added price information');
                return redirect()->route('dashboard', ['tenant' => tenant('id'), 'search' => $request->input('search_date')]);
            }
        }
        if($company_farm_name){
            $company_farm_id = CompanyFarm::where('company_farm_name', $company_farm_name)->first()->company_farm_id;
        }

        $post = (object) $request->all();

        return view('price_information.form',[
            'submit' => route('price_information_add', ['tenant' => tenant('id'), 'search_date' => @$search_date, 'company_farm_name' => $company_farm_name, 'product' => $product]),
            'post' => $post,
            'title' => 'Add',
            'search_date' => $search_date,
            'company_farm_id' => $company_farm_id,
            'product_id' => $product_id,
            'size_id' => $size_id,
            'company_farm_sel' => CompanyFarm::get_company_farm_sel(),
            'product_sel' => Product::get_sel(),
            'product_size_sel' => SettingSize::get_size_sel(),
        ])->withErrors($validation);
    }

    public function min_max_detail($search_date, $product,$company_farm_name)
    {
        $data = Invoice::get_min_max_detail($search_date, $product, $company_farm_name);
        return view('dashboard.min_max_detail', [
            // 'submit' => route('dashboard', ['tenant' => tenant('id')]),
            'data' => $data,
            'search_date' => $search_date,
            'product' => $product,
            'company' => Company::get(),
            'company_land' => Company::get(),

        ]);
    }
}
