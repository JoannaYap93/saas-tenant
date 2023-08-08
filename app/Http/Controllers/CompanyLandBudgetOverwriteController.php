<?php

namespace App\Http\Controllers;

use App\Model\CompanyFarm;
use App\Model\CompanyLand;
use App\Model\CompanyLandCategory;
use App\Model\CompanyLandBudgetOverwrite;
use App\Model\Setting;
use App\Model\SettingExpenseCategory;
use App\Model\SettingFormulaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyLandBudgetOverwriteController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth', 'super_admin'], ['except' => ['overwrite']]);
    }

    public function overwrite(Request $request, $company_id, $company_land_id)
    {
        $validator = null;
        // $post = $company = CompanyLandCategory::find($company_land_category_id);
        $post = null;
        $company_land = CompanyLand::where('company_land_id', $company_land_id)
                        ->where('company_id', $company_id)
                        ->first();

        $setting_expense_category = SettingExpenseCategory::where('is_budget_limited', 1)->get();
        $setting_formula_category = SettingFormulaCategory::where('is_budget_limited', 1)->get();

        $existing_overwrite = CompanyLandBudgetOverwrite::where('company_land_id', $company_land_id)
                              ->where('company_id', $company_id)
                              ->get();

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'overwrite_budget_per_tree' => $request->input('is_overwrite_budget') == 1 ? 'required' : 'nullable',
                'total_overwrite' => function($attribute, $value, $fail) use($request){
                    if($value != $request->input('overwrite_budget_per_tree')) {
                        return $fail(__("The Total Overwrite value doesn't match the Overwrite Budget value."));
                    }
                },
            ])->setAttributeNames([
                'overwrite_budget_per_tree' => 'Overwrite Budget',
                'total_overwrite' => 'Total Overwrite',
            ]);

            if (!$validator->fails()) {
                $company_land->update([
                  'is_overwrite_budget' => $request->input('is_overwrite_budget') ?? 0,
                  'overwrite_budget_per_tree' => $request->input('overwrite_budget_per_tree'),
                ]);

                foreach($request->input('company_land_budget_overwrite_value')['formula'] as $fkey => $formula_value){
                  if($formula_value != null){
                    $check_existing_formula_overwrite = CompanyLandBudgetOverwrite::where('company_land_id', $company_land_id)
                                                        ->where('company_id', $company_id)
                                                        ->where('company_land_budget_overwrite_type', 'formula')
                                                        ->where('company_land_budget_overwrite_type_id', $request->input('company_land_budget_overwrite_type_id')['formula'][$fkey])
                                                        ->first();

                    if($check_existing_formula_overwrite){
                      $check_existing_formula_overwrite->update([
                        'company_land_budget_overwrite_value' => $formula_value,
                        'user_id' => auth()->user()->user_id
                      ]);
                    }else{

                      CompanyLandBudgetOverwrite::create([
                        'company_land_id' => $company_land_id,
                        'company_land_budget_overwrite_type' => 'formula',
                        'company_land_budget_overwrite_value' => $formula_value,
                        'company_id' => $company_id,
                        'company_land_budget_overwrite_type_id' => $request->input('company_land_budget_overwrite_type_id')['formula'][$fkey],
                        'user_id' => auth()->user()->user_id
                      ]);
                    }
                  }
                }

                foreach($request->input('company_land_budget_overwrite_value')['expense'] as $ekey => $expense_value){
                  if($expense_value!=null){
                    $check_existing_expense_overwrite = CompanyLandBudgetOverwrite::where('company_land_id', $company_land_id)
                                                        ->where('company_id', $company_id)
                                                        ->where('company_land_budget_overwrite_type', 'expense')
                                                        ->where('company_land_budget_overwrite_type_id', $request->input('company_land_budget_overwrite_type_id')['expense'][$ekey])
                                                        ->first();

                    if($check_existing_expense_overwrite){
                      $check_existing_expense_overwrite->update([
                        'company_land_budget_overwrite_value' => $expense_value,
                        'user_id' => auth()->user()->user_id
                      ]);
                    }else{
                      CompanyLandBudgetOverwrite::create([
                        'company_land_id' => $company_land_id,
                        'company_land_budget_overwrite_type' => 'expense',
                        'company_land_budget_overwrite_value' => $expense_value,
                        'company_id' => $company_id,
                        'company_land_budget_overwrite_type_id' => $request->input('company_land_budget_overwrite_type_id')['expense'][$ekey],
                        'user_id' => auth()->user()->user_id
                      ]);
                    }
                  }
                }

                Session::flash('success_msg', 'Overwrite Successfull. ');
                return redirect()->route('company_listing');
            }
            $post = (object) $request->all();
        }

        return view('company.budget_overwrite_form', [
            'submit' => route('budget_overwrite', ['company_id'=>$company_id, 'company_land_id' => $company_land_id]),
            'title' => 'Add',
            'post' => $post,
            'company_land_category_sel' => CompanyLandCategory::get_land_category_sel(),
            'company_land' => $company_land,
            'setting_expense_category' => $setting_expense_category,
            'setting_formula_category' => $setting_formula_category,
            'existing_overwrite' => $existing_overwrite,
            'default_budget' => Setting::where('setting_slug', 'default_budget_per_tree')->pluck('setting_value')->first(),

        ])->withErrors($validator);
    }

}
