<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\SettingExpense;
use App\Model\SettingExpenseCategory;
use App\Model\SettingExpenseOverwrite;
use App\Model\SettingExpenseType;
use App\Model\WorkerRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingExpenseOverwriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function overwrite(Request $request, $company_id, $expense_id)
    {
        $expense = SettingExpense::find($expense_id);
        $company = Company::find($company_id);
        $overwrite = SettingExpenseOverwrite::where('setting_expense_id', '=', $expense_id)->where('company_id', $company_id)->first();
        $validation = null;
        $setting_type_sel = SettingExpenseType::get_expense_type_sel();
        $worker_role_sel = WorkerRole::get_worker_role_sel();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_expense_type_id' => 'required',
                // 'worker_role_id' => 'required',
                'setting_expense_overwrite_value' => 'required',
                'setting_expense_overwrite_subcon' => 'required_if:is_subcon_allow,1',
                'setting_expense_overwrite_commission' => 'required_if:is_extra_commission,1',
            ], [
                'setting_expense_overwrite_subcon.required_if' => 'The Expense Subcon field is required when Allow Subcon is On.',
                'setting_expense_overwrite_commission.required_if' => 'The Extra Commission field is required when Extra Commission is On.',
            ])->setAttributeNames([
                'setting_expense_type_id' => 'Expense Type',
                // 'worker_role_id' => 'Worker Role',
                'setting_expense_overwrite_value' => 'Expense Value',
                'setting_expense_overwrite_subcon' => 'Expense Subcon',
                'setting_expense_overwrite_commission' => 'Extra Commission',
            ]);

            if (!$validation->fails()) {

                SettingExpenseOverwrite::updateOrCreate(['setting_expense_id' => $expense_id, 'company_id' => $company_id],[
                    'setting_expense_type_id' => $request->input('setting_expense_type_id'),
                    // 'worker_role_id' => $request->input('worker_role_id'),
                    'setting_expense_overwrite_value' => $request->input('setting_expense_overwrite_value'),
                    'setting_expense_id' => $expense_id,
                    'company_id' => $company_id,
                    'user_id' => auth()->user()->user_id,
                    'is_extra_commission' => $request->input('is_extra_commission') ?? 0,
                    'setting_expense_overwrite_commission' => $request->input('setting_expense_overwrite_commission') ? $request->input('setting_expense_overwrite_commission') : null,
                    'setting_expense_category_id' => $request->input('setting_expense_category_id'),
                    'is_subcon_allow' => $request->input('is_subcon_allow') ?? 0,
                    'setting_expense_overwrite_subcon' => $request->input('setting_expense_overwrite_subcon') ? $request->input('setting_expense_overwrite_subcon') : null,
                ]);

                Session::flash('success_msg', 'Successfully Overwrite Expense for '.$company->company_name);
                return redirect()->route('expense_listing');
            }
            $request->request->add(['company_id' => $company_id]);
            $overwrite = (object) $request->all();
        }

        return view('setting_expense.expense_overwrite', [
            'expense' => $expense,
            'company' => $company,
            'overwrite' => $overwrite,
            'expense_type_sel' => $setting_type_sel,
            'worker_role_sel' => $worker_role_sel,
            'title' => 'Overwrite',
            'submit' => route('expense_overwrite', $company_id."/".$expense_id),
            'expense_category_sel' => ['' => 'Please Select Expense Category'] + SettingExpenseCategory::get_existing_expense_category_sel(),

        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $expense_id = $request->input('setting_expense_id');
        SettingExpenseOverwrite::where('setting_expense_id', $expense_id)->where('company_id', Auth::user()->company_id)->delete();

        Session::flash('success_msg', 'Successfully Deleted Expense Overwrite Details');
        return redirect()->route('expense_listing');
    }
}
