<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\CompanyPnlItem;
use App\Model\SettingExpense;
use App\Model\ProductCategory;
use App\Model\CompanyPnlSubItem;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Session;


class CompanyPnlItemController extends Controller
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
                    Session::forget('company_pnl_item_search');
                    $search['freetext'] = $request->input('freetext');

                    Session::put('company_pnl_item_search', $search);
                    break;
                case 'reset':
                    Session::forget('company_pnl_item_search');
                    break;
                }
             }

            $search = session('company_pnl_item_search') ?? array();

        return view('company_pnl_item.listing', [
            'submit' => route('company_pnl_item_listing', ['tenant' => tenant('id')]),
            'records' => CompanyPnlItem::get_record($search, $perpage),
            'search' => $search,
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;

        $post = (object) array();
        $post->is_extra_new_tree = null;
        $post->setting_expense_id = [];
        $post->product_category_id = null;
        $post->company_pnl_sub_item_code = [];

        if ($request->isMethod('post')) {

            $pnl_item_type= implode(',',$request->input('pnl_type'));
            $pnl_item_json = $this->add_pnl_type_data_to_json($request);

            $validation = Validator::make($request->all(), [
                'company_pnl_item_name' => 'required|max:100',
                'company_pnl_item_code' => 'required|max:10',
                'company_pnl_item_desc' => 'nullable|max:255',
                'pnl_type' => 'required',
                'setting_expense_id' =>
                    Rule::requiredIf(function () use($pnl_item_type) {
                        if($pnl_item_type == 'expense')
                            return true;
                }),
                'product_category_id' =>
                    Rule::requiredIf(function () use($pnl_item_type){
                        if($pnl_item_type == 'product_category'){
                            return true;
                        }
                }),
                'company_pnl_sub_item_code' =>
                    Rule::requiredIf(function () use($pnl_item_type){
                        if($pnl_item_type == 'tree_category')
                            return true;
                }),
                'is_extra_new_tree' => 'nullable'

            ])->setAttributeNames([
                'company_pnl_item_name' => 'Profit & Loss Item Name',
                'company_pnl_item_code' => 'Profit & Loss Item Code',
                'company_pnl_item_desc' => 'Profit & Loss Item Description',
                'pnl_type' => 'Profit & Loss Type',
                'setting_expense_id' => 'Profit & Loss Expense',
                'product_category_id' => 'Profit & Loss Product Category',
                'company_pnl_sub_item_code' => 'Profit & Loss Sub Item',
                'is_extra_new_tree' => 'Extra New Tree'
            ]);

            if(!$validation->fails()){
                CompanyPnlItem::create([
                    'company_pnl_item_name' => $request->input('company_pnl_item_name') ?? '',
                    'company_pnl_item_code' => $request->input('company_pnl_item_code') ?? '',
                    'company_pnl_item_desc' => $request->input('company_pnl_item_desc') ?? '',
                    'company_pnl_item_type' => $pnl_item_type,
                    'company_pnl_item_json' => $pnl_item_json
                ]);

                Session::flash('success_msg', 'Successfully Added! ');
                return redirect()->route('company_pnl_item_listing', ['tenant' => tenant('id')]);
            }

            $post = (object) $request->all();
        }

        return view('company_pnl_item.form', [
            'submit' => route('company_pnl_item_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'company_pnl_item_type' => $this->get_pnl_type_enum(),
            'expenses' => SettingExpense::get_setting_expense_for_company_pnl(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'company_pnl_item_sub_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_sel(),

        ])->withErrors($validation);
    }

    public function edit(Request $request, $company_pnl_item_id)
    {
        $validation = null;
        $post = $company_pnl_item = CompanyPnlItem::query()->where('company_pnl_item_id', $company_pnl_item_id)->first();

        if($company_pnl_item->company_pnl_item_json){
            $post->is_extra_new_tree = json_decode($company_pnl_item->company_pnl_item_json)->is_extra_new_tree != 0 ?json_decode($company_pnl_item->company_pnl_item_json)->is_extra_new_tree : null;
            $post->setting_expense_id = json_decode($company_pnl_item->company_pnl_item_json)->setting_expense_id != '' ? json_decode($company_pnl_item->company_pnl_item_json)->setting_expense_id :[];
            $post->product_category_id = json_decode($company_pnl_item->company_pnl_item_json)->product_category_id != 0 ? json_decode($company_pnl_item->company_pnl_item_json)->product_category_id : null;
            $post->company_pnl_sub_item_code = json_decode($company_pnl_item->company_pnl_item_json)->company_pnl_sub_item_code != '' ? json_decode($company_pnl_item->company_pnl_item_json)->company_pnl_sub_item_code : [];
        }

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Company Profit & Loss Item, Please Try Again');
            return redirect()->route('company_pnl_item_listing', ['tenant' => tenant('id')]);
        }

        if ($request->isMethod('post')) {
            $pnl_item_type= implode(',',$request->input('pnl_type'));
            $pnl_item_json = $this->add_pnl_type_data_to_json($request);

            $validation = Validator::make($request->all(), [
                'company_pnl_item_name' => 'required|max:100',
                'company_pnl_item_code' => 'required|max:10',
                'company_pnl_item_desc' => 'nullable|max:255',
                'pnl_type' => 'required',
                'setting_expense_id' =>
                    Rule::requiredIf(function () use($pnl_item_type) {
                        if($pnl_item_type == 'expense')
                            return true;
                }),
                'product_category_id' =>
                    Rule::requiredIf(function () use($pnl_item_type){
                        if($pnl_item_type == 'product_category'){
                            return true;
                        }
                }),
                'company_pnl_sub_item_code' =>
                    Rule::requiredIf(function () use($pnl_item_type){
                        if($pnl_item_type == 'tree_category')
                            return true;
                }),
                'is_extra_new_tree' => 'nullable'

            ])->setAttributeNames([
                'company_pnl_item_name' => 'Profit & Loss Item Name',
                'company_pnl_item_code' => 'Profit & Loss Item Code',
                'company_pnl_item_desc' => 'Profit & Loss Item Description',
                'pnl_type' => 'Profit & Loss Type',
                'setting_expense_id' => 'Profit & Loss Expense',
                'product_category_id' => 'Profit & Loss Product Category',
                'company_pnl_sub_item_code' => 'Profit & Loss Sub Item',
                'is_extra_new_tree' => 'Extra New Tree'
            ]);
            if(!$validation->fails()){
                CompanyPnlItem::where('company_pnl_item_id', $company_pnl_item_id)
                ->update([
                    'company_pnl_item_name' => $request->input('company_pnl_item_name'),
                    'company_pnl_item_code' => $request->input('company_pnl_item_code'),
                    'company_pnl_item_desc' => $request->input('company_pnl_item_desc'),
                    'company_pnl_item_type' => $pnl_item_type,
                    'company_pnl_item_json' => $pnl_item_json
                ]);

                Session::flash('success_msg', 'Successfully Updated!');
                return redirect()->route('company_pnl_item_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('company_pnl_item.form', [
            'submit' => route('company_pnl_item_edit', ['tenant' => tenant('id'), 'id' => $company_pnl_item_id]),
            'title' => 'Edit',
            'post' => $post,
            'company_pnl_item_type' => $this->get_pnl_type_enum(),
            'expenses' => SettingExpense::get_setting_expense_for_company_pnl(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'company_pnl_item_sub_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
        ])->withErrors($validation);

    }

    public function add_pnl_type_data_to_json($request)
    {
        $data = collect();
        $setting_expense_id = $request->input('setting_expense_id') ?? '';
        $product_category_id = $request->input('product_category_id') ?? 0;
        $company_pnl_sub_item_code = $request->input('company_pnl_sub_item_code') ?? '';
        $is_extra_new_tree = $request->input('is_extra_new_tree') ?? 0;
        $pnl_item_json_list = $this->get_pnl_item_json_key();

        foreach ($pnl_item_json_list as $pnl_type) {
            switch($pnl_type) {
                case('setting_expense_id'):
                    $data->put($pnl_type,$setting_expense_id);
                break;
                case('product_category_id'):
                    $data->put($pnl_type,$product_category_id);
                break;
                case('company_pnl_sub_item_code'):
                    $data->put($pnl_type,$company_pnl_sub_item_code);
                break;
                case('is_extra_new_tree'):
                    $data->put($pnl_type,$is_extra_new_tree);
                break;
            }
        }

        return $data->toJson();
    }

    public static function get_pnl_type_enum(){
        return['expense'=> 'Expense', 'product_category'=>'Product Category', 'tree_category'=>'Tree Category'];
    }

    public static function get_pnl_item_json_key(){
        return ['setting_expense_id', 'product_category_id', 'company_pnl_sub_item_code', 'is_extra_new_tree'];
    }

    public static function get_company_pnl_sub_item_code_by_sub_item_id($company_pnl_sub_item_id){
        $query = CompanyPnlSubItem::query()
                                ->where('company_pnl_sub_item_id', $company_pnl_sub_item_id );
        $result = $query->first();

        return $result;
    }

    public static function get_company_pnl_sub_item_id_by_sub_item_code($company_pnl_sub_item_code){
        $query = CompanyPnlSubItem::query()
                ->where('company_pnl_sub_item_code', $company_pnl_sub_item_code );
        $result = $query->value('company_pnl_sub_item_id');

        return $result;
    }

}
?>
