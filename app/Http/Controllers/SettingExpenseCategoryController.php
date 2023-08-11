<?php

namespace App\Http\Controllers;

use App\Model\SettingExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('setting_expense_category_search');
                    $search['freetext'] = $request->input('freetext');

                    Session::put('setting_expense_category_search', $search);
                    break;
                case 'reset':
                    Session::forget('setting_expense_category_search');
                    break;
            }
        }

        $search = session('setting_expense_category_search') ?? array();

        return view('setting_expense_category.listing', [
            'page_title' => 'Setting Expense Category',
            'submit' => route('expense_category_listing', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingExpenseCategory::get_records($search, $perpage),
        ]);
    }

    public function add(Request $request)
    {
        $post = null;
        $validator = null;
        $arr = [];
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_expense_category_name_en' => 'required',
                'setting_expense_category_name_cn' => 'required',
                'setting_expense_category_group' => 'required',
            ])->setAttributeNames([
                'setting_expense_category_name_en' => 'Expenses Category Name (EN)',
                'setting_expense_category_name_cn' => 'Expenses Category Name (CN)',
                'setting_expense_category_group' => 'Expense Category Group',
            ]);

            if (!$validator->fails()) {

                $arr['en'] = $request->input('setting_expense_category_name_en');
                $arr['cn'] = $request->input('setting_expense_category_name_cn');

                SettingExpenseCategory::create([
                    'setting_expense_category_name' => json_encode($arr),
                    'is_budget_limited' => $request->input('is_budget_limited') ?? 0,
                    'is_backend_only' => $request->input('is_backend_only') ?? 0,
                    'setting_expense_category_budget' => $request->input('setting_expense_category_budget'),
                    'setting_expense_category_group' => $request->input('setting_expense_category_group'),
                ]);

                Session::flash('success_msg', 'Successfully Created A New Expense Category');
                return redirect()->route('expense_category_listing', ['tenant' => tenant('id')]);
            }
            $post = (object)$request->all();
        }

        return view('setting_expense_category.form', [
            'post' => $post,
            'title' => 'Add',
            'submit' => route('expense_category_add', ['tenant' => tenant('id')]),
            'setting_expense_category_group_sel' => ['' => 'Please Select Expense Category Group', 'Cost' => 'Cost', 'Expense' => 'Expense'],

        ])->withErrors($validator);
    }

    public function edit(Request $request, $setting_expense_category_id){
        $post = $expense_category = SettingExpenseCategory::find($setting_expense_category_id);

        $post->setting_expense_category_name_en = json_decode($expense_category->setting_expense_category_name)->en;
        $post->setting_expense_category_name_cn = json_decode($expense_category->setting_expense_category_name)->cn;

        $validator = null;
        $arr = [];
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_expense_category_name_en' => 'required',
                'setting_expense_category_name_cn' => 'required',
                'setting_expense_category_group' => 'required',
            ])->setAttributeNames([
                'setting_expense_category_name_en' => 'Expenses Category Name (EN)',
                'setting_expense_category_name_cn' => 'Expenses Category Name (CN)',
                'setting_expense_category_group' => 'Expense Category Group',
            ]);
            if (!$validator->fails()) {
                $arr['en'] = $request->input('setting_expense_category_name_en');
                $arr['cn'] = $request->input('setting_expense_category_name_cn');

                SettingExpenseCategory::where('setting_expense_category_id',$setting_expense_category_id)
                ->update([
                    'setting_expense_category_name' => json_encode($arr),
                    'is_budget_limited' => $request->input('is_budget_limited') ?? 0,
                    'is_backend_only' => $request->input('is_backend_only') ?? 0,
                    'setting_expense_category_budget' => $request->input('setting_expense_category_budget'),
                    'setting_expense_category_group' => $request->input('setting_expense_category_group'),
                ]);

                Session::flash('success_msg', 'Successfully Update ' . $request->input('setting_expense_category_name_en'));
                return redirect()->route('expense_category_listing', ['tenant' => tenant('id')]);
            }

            $post = (object) $request->all();
        }

        return view('setting_expense_category.form', [
            'post' => $post,
            'title' => 'Edit',
            'submit' => route('expense_category_edit', ['tenant' => tenant('id'), 'id' => $setting_expense_category_id]),
            'setting_expense_category_group_sel' => ['' => 'Please Select Expense Category Group', 'Cost' => 'Cost', 'Expense' => 'Expense'],

        ])->withErrors($validator);
    }

    public function delete(Request $request)
    {
        $new_car_tag_id = $request->input('new_car_tag_id');
        $new_car_tag = NewCarTag::find($new_car_tag_id);

        if(!$new_car_tag){
            Session::flash('fail_msg', 'Error, Please try again later..');
            return redirect()->route('new_car_tag_listing', ['tenant' => tenant('id')]);
        }

        $new_car_tag->update([
            'is_deleted' => 1
        ]);

        Session::flash('success_msg', "Successfully delete new car tag.");
        return redirect()->route('new_car_tag_listing', ['tenant' => tenant('id')]);
    }
}
