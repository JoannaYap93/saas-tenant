<?php

namespace App\Http\Controllers;

use Svg\Tag\Rect;
use App\Model\Worker;
use App\Model\Company;
use App\Model\Reporting;
use Illuminate\Http\Request;
use App\Model\ClaimPendingReport;
use App\Exports\ClaimPendingExport;
use App\Model\SettingExpense;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\SettingExpenseCategory;
use App\Model\SettingRawMaterial;
use Illuminate\Support\Facades\Session;
use App\Model\SettingRawMaterialCategory;
use Illuminate\Support\Facades\Validator;

class ClaimPendingReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function claim_pending_report(Request $request)
    {
        $validation = null;
        $search['year'] = date('Y');

        if (isset($_GET['reset'])) {
            Session::forget('claim_pending_report');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('claim_pending_report');
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');
                        Session::put('claim_pending_report', $search);
                        break;
                    case 'reset':
                        Session::forget('claim_pending_report');
                        break;
                    case 'export':
                        $search['year'] = $request->input('year');
                        $search['company_id'] = $request->input('company_id');

                        $records = ClaimPendingReport::get_claim_pending_report($search);

                        Session::put('company_expense', $search);
                        return Excel::download(new ClaimPendingExport('components.claim_pending_report_component', $records, $search), 'Claim_Pending_Report '. $search['year'].'.xlsx');
                        break;
                    }
                }
            }

        $records = ClaimPendingReport::get_claim_pending_report($search);

        $month_sel = Reporting::get_month();

        return view('claim_pending.claim_pending_report', [
            'page_title' => 'All Claim Report',
            'submit' => route('claim_pending_report', ['tenant' => tenant('id')]),
            'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'title' => 'All Claim Report',
            'records' => $records,
            'month_sel' => $month_sel,
            'company' => Company::get_company_for_daily_report($search),
            'farm_manager' => Worker::get_farm_manager_sel_by_company($search),
        ]);
    }

    public function claim_pending_detail_report_admin(Request $request, $year, $month, $company_id, $farm_manager){

        $search['year'] = $year;
        $search['month'] = $month;
        $search['company_id'] = $company_id;
        $search['farm_manager'] = $farm_manager;

        $claim_item_data = ClaimPendingReport::get_claim_pending_report_detail_expense($search);

        return view('claim_pending.claim_pending_detail_report', [
            'records' => $claim_item_data,
            'page_title' => 'Claim Detail Pending',
            'expense_category' => SettingExpenseCategory::get_expense_category_for_pnl_reporting(),
            'material_category' => SettingRawMaterialCategory::get_material_category_for_report(),
            'expense_item' => SettingExpense::get_expense_for_report(),
            'material_item' => SettingRawMaterial::get_material_for_report(),
            'farm_manager' => Worker::get_farm_manager_by_id($search)
        ]);
    }

    public function claim_pending_detail_report_superadmin(Request $request, $year, $month, $company_id){

        $search['year'] = $year;
        $search['month'] = $month;
        $search['company_id'] = $company_id;

        $claim_item_data = ClaimPendingReport::get_claim_pending_report_detail_expense($search);

        return view('claim_pending.claim_pending_detail_report', [
            'records' => $claim_item_data,
            'page_title' => 'Claim Detail Pending',
            'expense_category' => SettingExpenseCategory::get_expense_category_for_pnl_reporting(),
            'material_category' => SettingRawMaterialCategory::get_material_category_for_report(),
            'expense_item' => SettingExpense::get_expense_for_report(),
            'material_item' => SettingRawMaterial::get_material_for_report(),
            'company' => Company::get_by_id($company_id)
        ]);
    }
}
