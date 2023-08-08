<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\SettingExpense;
use App\Model\SettingExpenseCategory;
use App\Model\SettingExpenseOverwrite;
use App\Model\SettingExpenseType;
use App\Model\Worker;
use App\Model\WorkerRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SettingExpenseExport;

class SettingExpenseController extends Controller
{
    // public function __construct()
    // {
    //     // $this->middleware(['auth', 'super_admin'], ['except' => ['listing', 'add']]);
    // }

    public function listing(Request $request)
    {
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['setting_expense_search' => [
                        'freetext' => $request->input('freetext'),
                        'setting_expense_category_id' => $request->input('setting_expense_category_id'),
                        'worker_role_id' => $request->input('worker_role_id'),
                        'is_subcon_allow' => $request->input('is_subcon_allow'),
                        'company_id' => $request->input('company_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('setting_expense_search');
                    break;
                case 'export':
                    session(['setting_expense_search' => [
                        'freetext' => $request->input('freetext'),
                        'setting_expense_category_id' => $request->input('setting_expense_category_id'),
                        'worker_role_id' => $request->input('worker_role_id'),
                        'is_subcon_allow' => $request->input('is_subcon_allow'),
                        'company_id' => $request->input('company_id'),
                    ]]);

                    $records = SettingExpense::get_records(session('setting_expense_search'));

                    return Excel::download(new SettingExpenseExport('export/setting_expense', $records), 'setting_expense.xlsx');
                    break;
            }
        }
        $search = session('setting_expense_search') ?? array();

        return view('setting_expense.listing', [
            'records' => SettingExpense::get_records($search, $perpage),
            'submit' => route('expense_listing'),
            'expense_category_sel' => ['' => 'Please Select Expense Category'] + SettingExpenseCategory::get_existing_expense_category_sel(),
            'worker_role_sel' => ['' => 'Please Select Worker Role'] + WorkerRole::get_worker_role_sel(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'is_subcon_allow' => ['' => 'Please select Subcon','1'=>'Subcon Allow','0'=>'No Subcon Allow'],
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;
        $arr = [];

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_expense_name_en' => 'required',
                'setting_expense_name_cn' => 'required',
                'setting_expense_type_id' => 'required',
                'worker_role_id' => 'required_if:setting_expense_category_id,2',
                'setting_expense_description' => 'nullable|max:200',
                'setting_expense_value' => 'required',
                'setting_expense_category_id' => 'required',
                'setting_expense_subcon' => 'required_if:is_subcon_allow,1',
            ], [
                'setting_expense_subcon.required_if' => 'The Expense Subcon field is required when Allow Subcon is On.',
                'worker_role_id.required_if' => 'The Worker Role field is required when Expense Category is Labour.',
            ])->setAttributeNames([
                'setting_expense_name_en' => 'Expense Name(EN)',
                'setting_expense_name_cn' => 'Expense Name(CN)',
                'setting_expense_type_id' => 'Expense Type',
                'worker_role_id' => 'Worker Role',
                'setting_expense_description' => 'Description',
                'setting_expense_value' => 'Expense Value',
                'setting_expense_category_id' => 'Expense Category',
                'setting_expense_subcon' => 'Expense Subcon',
            ]);
            if (!$validation->fails()) {
                $arr['en'] = $request->input('setting_expense_name_en');
                $arr['cn'] = $request->input('setting_expense_name_cn');

                SettingExpense::create([
                    'setting_expense_name' => json_encode($arr),
                    'company_id' => auth()->user()->company_id ?? 0,
                    'is_compulsory' => $request->input('is_compulsory') ?? 0,
                    'setting_expense_type_id' => $request->input('setting_expense_type_id'),
                    'worker_role_id' => $request->input('worker_role_id'),
                    'setting_expense_description' => $request->input('setting_expense_description'),
                    'setting_expense_value' => $request->input('setting_expense_value'),
                    'setting_expense_category_id' => $request->input('setting_expense_category_id'),
                    'is_subcon_allow' => $request->input('is_subcon_allow') ?? 0,
                    'is_excluded_payroll' => $request->input('is_excluded_payroll') ?? 0,
                    'setting_expense_subcon' => $request->input('setting_expense_subcon'),
                ]);

                Session::flash('success_msg', 'Successfully created a new expense');
                return redirect()->route('expense_listing');
            }

            $post = (object) $request->all();
        }

        return view('setting_expense.form', [
            'post' => $post,
            'expense_type_sel' => SettingExpenseType::get_expense_type_sel(),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'title' => 'Add',
            'submit' => route('expense_add'),
            'expense_category_sel' => ['' => 'Please Select Expense Category'] + SettingExpenseCategory::get_existing_expense_category_sel(),

        ])->withErrors($validation);
    }

    public function edit(Request $request, $setting_expense_name_id)
    {
        $post = $setting_expense = SettingExpense::find($setting_expense_name_id);
        $post->setting_expense_name_en = json_decode($setting_expense->setting_expense_name)->en;
        $post->setting_expense_name_cn = json_decode($setting_expense->setting_expense_name)->cn;

        $validation = null;
        $arr = [];

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'setting_expense_name_en' => 'required',
                'setting_expense_name_cn' => 'required',
                'setting_expense_type_id' => 'required',
                'worker_role_id' => 'required_if:setting_expense_category_id,2',
                'setting_expense_description' => 'nullable|max:200',
                'setting_expense_value' => 'required',
                'setting_expense_category_id' => 'required',
                'setting_expense_subcon' => 'required_if:is_subcon_allow,1',
            ], [
                'setting_expense_subcon.required_if' => 'The Expense Subcon field is required when Allow Subcon is On.',
                'worker_role_id.required_if' => 'The Worker Role field is required when Expense Category is Labour.',
            ])->setAttributeNames([
                'setting_expense_name_en' => 'Expense Name(EN)',
                'setting_expense_name_cn' => 'Expense(CN)',
                'setting_expense_type_id' => 'Expense Type',
                'worker_role_id' => 'Worker Role',
                'setting_expense_description' => 'Description',
                'setting_expense_value' => 'Expense Value',
                'setting_expense_category_id' => 'Expense Category',
                'setting_expense_subcon' => 'Expense Subcon',
            ]);
            if (!$validation->fails()) {
                $arr['en'] = $request->input('setting_expense_name_en');
                $arr['cn'] = $request->input('setting_expense_name_cn');

                SettingExpense::where('setting_expense_id',$setting_expense_name_id)
                ->update([
                    'setting_expense_name' => json_encode($arr),
                    'setting_expense_type_id' => $request->input('setting_expense_type_id'),
                    'is_compulsory' => $request->input('is_compulsory') ?? 0,
                    'setting_expense_description' => $request->input('setting_expense_description'),
                    'setting_expense_value' => $request->input('setting_expense_value'),
                    'setting_expense_category_id' => $request->input('setting_expense_category_id'),
                    'worker_role_id' => $request->input('worker_role_id'),
                    'is_subcon_allow' => $request->input('is_subcon_allow') ?? 0,
                    'is_excluded_payroll' => $request->input('is_excluded_payroll') ?? 0,
                    'setting_expense_subcon' => $request->input('setting_expense_subcon') ? $request->input('setting_expense_subcon') : null,
                ]);

                Session::flash('success_msg', 'Successfully Update ' . $request->input('setting_expense_name_en'));
                return redirect()->route('expense_listing');
            }

            $post = (object) $request->all();
        }

        return view('setting_expense.form', [
            'post' => $post,
            'title' => 'Edit',
            'expense_type_sel' => SettingExpenseType::get_expense_type_sel(),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'submit' => route('expense_edit', $setting_expense_name_id),
            'expense_category_sel' => ['' => 'Please Select Expense Category'] + SettingExpenseCategory::get_existing_expense_category_sel(),
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $expense_id = $request->input('setting_expense_id');
        SettingExpense::find($expense_id)->delete();

        Session::flash('success_msg', 'Successfully Deleted Expense');
        return redirect()->route('expense_listing');
    }

    public function expense_activation(Request $request, $id, $status)
    {
      $setting_expense = SettingExpense::find($id);

      $setting_expense->update([
        'setting_expense_status' => $status == 'active' ? 'active' : 'inactive'
      ]);

      Session::flash('success_msg', 'Successfully Updated Expense Status');
      return redirect()->route('expense_listing');
    }

    public function ajax_get_expense_overwrite_detail_modal(Request $request)
    {
        $expense_id = $request->input('setting_expense_id');
        $company_id = $request->input('company_id');

        $overwrite_detail = SettingExpenseOverwrite::where('setting_expense_id', $expense_id)->where('company_id', $company_id)->get();

        $company_name = Company::find($company_id)->company_name;

        return view('setting_expense.expense_overwrite_detail_modal', [
            'overwrite_detail' => $overwrite_detail,
            'company' => $company_name,

        ]);
    }

    public function ajax_get_expense_by_upkeep(Request $request)
    {
        $expense_category_id = $request->input('expense_category_id');
        $result = SettingExpense::get_expense_by_upkeep($expense_category_id);

        return ['data' => $result];
    }

    public function ajax_get_expense_by_staff_costing(Request $request)
    {

        $result = SettingExpense::get_expense_by_staff_costing();

        return ['data' => $result];
    }

    public function ajax_get_price_expense(Request $request)
    {
        $expense_id = $request->input('expense_id');

        $result = SettingExpense::get_price_expense($expense_id);

        return $result;
    }
}
