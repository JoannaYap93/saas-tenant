<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Reporting;
use Illuminate\Http\Request;
use App\Model\SettingWarehouse;
use App\Model\WarehouseReporting;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseReportExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WarehouseReportingController extends Controller
{
    public function warehouse_reporting(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('warehouse_reporting');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('warehouse_reporting');
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');
                        Session::put('warehouse_reporting', $search);
                        break;
                    case 'reset':
                        Session::forget('warehouse_reporting');
                        break;
                    case 'export':
                        $search['year'] = $request->input('year');
                        $search['month'] = $request->input('month');
                        $search['company_id'] = $request->input('company_id');

                        $wh_sales_rm = WarehouseReporting::get_sales_rm_report($search);
                        $wh_sales_kg = WarehouseReporting::get_sales_kg_report($search);
                        $wh_stock = WarehouseReporting::get_stock_report($search);

                        Session::put('warehouse_reporting', $search);
                        // if (auth()->user()->company_id == 0){
                            return Excel::download(new WarehouseReportExport('components.warehouse_sa_report', $wh_sales_rm, $wh_sales_kg, $wh_stock, $search), 'Warehouse_Report.xlsx');
                            break;
                        // }else{
                        //     return Excel::download(new WarehouseReportExport('components.warehouse_report', $wh_sales_rm, $wh_sales_kg, $wh_stock, $search), 'Warehouse_Report.xlsx');
                        //     break;
                        // }
                    }
                }
            }

        $wh_sales_rm = WarehouseReporting::get_sales_rm_report($search);
        $wh_sales_kg = WarehouseReporting::get_sales_kg_report($search);
        $wh_stock = WarehouseReporting::get_stock_report($search);

        return view('report.warehouse_reporting', [
            'page_title' => 'Warehouse Reporting',
            'submit' => route('warehouse_reporting', ['tenant' => tenant('id')]),
            'company_sel' => Company::get_company_sel_warehouse(),
            // 'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'title' => 'Warehouse Report',
            'wh_sales_rm' => $wh_sales_rm,
            'wh_sales_kg' => $wh_sales_kg,
            'wh_stock' => $wh_stock,
            'warehouse' => SettingWarehouse::get_warehouse_for_expense(),
            'month_sel' => Reporting::get_month_w_filter($search),
            'company' => Company::get_company_for_warehouse_report($search),
            // 'company' => Company::get_company_for_daily_report($search),
        ]);
    }
}
