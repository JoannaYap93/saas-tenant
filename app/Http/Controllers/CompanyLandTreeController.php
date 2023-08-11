<?php

namespace App\Http\Controllers;

use Session;
use App\Model\Company;
use App\Model\Product;
use App\Model\Setting;
use Illuminate\Http\Request;
use App\Model\SettingTreeAge;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandZone;
use App\Model\CompanyPnlSubItem;
use App\Model\CompanyLandTreeLog;
use App\Model\CompanyLandTreeAction;
use Illuminate\Support\Facades\Validator;

class CompanyLandTreeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request ,$company_land_zone_id = null){
        $land_zone = CompanyLandZone::find($company_land_zone_id);
        if(!$land_zone){
            Session::flash('fail_msg', 'Invalid Tree, Please try again later.');
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }

        $search = array();
        $search['company_land_zone_id'] = $company_land_zone_id;

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['company_land_tree_search' => [
                        'freetext' => $request->input('freetext'),
                        'product_id' => $request->input('product_id'),
                        'company_land_tree_status' => $request->input('company_land_tree_status'),
                        'company_land_tree_category' => $request->input('company_land_tree_category'),
                        'company_land_zone_id' => $request->input('company_land_zone_id'),
                        'tree_circumference_lower' => $request->input('tree_circumference_lower'),
                        'tree_circumference_upper' => $request->input('tree_circumference_upper')

                    ]]);
                    $company_land_zone_id = $search['company_land_zone_id'] = $request->input('company_land_zone_id');
                    break;
                case 'reset':
                    session()->forget('company_land_tree_search');
                    break;
            }
        }
        $this_zone = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)->first();
        $company_land = $this_zone->company_land;

        $search = session('company_land_tree_search') ?? $search;
        $product_sel = Product::get_by_company();
        return view('land_tree.listing', [
            'submit' => route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]),
            'records' => CompanyLandTree::get_records($search),
            'search' => $search,
            'company_land_zone_id' => $company_land_zone_id,
            'product_sel' => $product_sel,
            'zone_sel' => CompanyLandZone::get_zone_sel($this_zone->company_land_id),
            'status_sel' => ['' => 'Please Select Status', 'alive' => 'Alive', 'dead' => 'Dead', 'saw off' => 'Saw Off'],
            'company_land' => $company_land,
            'company_land_tree_cate_sel' => CompanyLandTree::get_tree_category_global_sel(),
            'company_zone_detail'=>$this_zone
        ]);
    }

    public function add(Request $request, $company_land_zone_id = null)
    {
        $land_zone = CompanyLandZone::find($company_land_zone_id);
        if(!$land_zone){
            Session::flash('fail_msg', 'Invalid Tree, Please try again later.');
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }

        $validator = null;
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_land_tree_no' => 'required',
                'company_land_tree_circumference' => 'required',
                'product_id'=> 'required',
                'company_pnl_sub_item_code' => 'required',
            ]);
            if(!$validator->fails()){
                $company_land_id = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)->pluck('company_land_id')->first();
                $age=SettingTreeAge::get_age_by_circumference($request->input('company_land_tree_circumference'));
                $tree_year = date('Y') - $age->setting_tree_age;
                $graft_bear_period = Setting::where('setting_slug', '=', 'graft_tree_bear_period')->pluck('setting_value')->first();
                $new_bear_period = Setting::where('setting_slug', '=', 'new_tree_bear_period')->pluck('setting_value')->first();

                if($request->input('company_pnl_sub_item_code') == 'K1'){
                    if($request->input('company_land_tree_age') >= $new_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }elseif($request->input('company_pnl_sub_item_code') == 'KM' || $request->input('company_pnl_sub_item_code') == 'KB'){
                    if($request->input('company_land_tree_age') >= $graft_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }else{
                    $is_bear_fruit = $request->input('is_bear_fruit')  ?? 0;
                }

                $company_land_tree = CompanyLandTree::create([
                    'company_land_tree_no' => $request->input('company_land_tree_no'),
                    'company_land_id' => $company_land_id,
                    'company_land_zone_id' => $company_land_zone_id,
                    'company_land_tree_year' => $tree_year,
                    'company_land_tree_age' => $age->setting_tree_age,
                    'company_land_tree_circumference' => $request->input('company_land_tree_circumference'),
                    'product_id' => $request->input('product_id'),
                    'is_bear_fruit' => $is_bear_fruit,
                    'company_pnl_sub_item_code' => $request->input('company_pnl_sub_item_code'),
                ]);

                CompanyLandTreeLog::create([
                  'company_land_tree_id' => $company_land_tree->company_land_tree_id,
                  'company_land_tree_action_id' => 5,
                  'company_land_tree_log_description' => 'Tree details added.',
                  'company_land_tree_log_date' => date('Y-m-d'),
                  'company_land_tree_log_value' => null,
                  'user_id' => auth()->user()->user_id,
                ]);

                Session::flash('success_msg', 'Successfully Added tree');
                return redirect()->route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]);
            }
        }
        $product_sel = Product::get_by_company();
        return view('land_tree.form', [
            'records' => null,
            'submit' => route('land_tree_add', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]),
            'type' => "Add",
            'product_sel' => $product_sel,
            'status_sel' => ['' => 'Please Select Status', 'alive' => 'Alive', 'dead' => 'Dead', 'saw off' => 'Saw Off'],
            'company_pnl_sub_item_code_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
        ])->withErrors($validator);
    }

    public function edit(Request $request, $company_land_tree_id)
    {
        $tree= CompanyLandTree::find($company_land_tree_id);

        $validator = null;
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_land_tree_no' => 'required',
                'company_land_tree_circumference' => 'required',
                'product_id'=> 'required',
                'company_land_tree_status' => 'required',
            ]);
            if(!$validator->fails()){
                $age=SettingTreeAge::get_age_by_circumference($request->input('company_land_tree_circumference'));
                $tree_year = date('Y') - $age->setting_tree_age;
                $graft_bear_period = Setting::where('setting_slug', '=', 'graft_tree_bear_period')->pluck('setting_value')->first();
                $new_bear_period = Setting::where('setting_slug', '=', 'new_tree_bear_period')->pluck('setting_value')->first();

                if($request->input('company_pnl_sub_item_code') == 'K1'){
                    if($request->input('company_land_tree_age') >= $new_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }elseif($request->input('company_pnl_sub_item_code') == 'KM' || $request->input('company_pnl_sub_item_code') == 'KB'){
                    if($request->input('company_land_tree_age') >= $graft_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }else{
                    $is_bear_fruit = $request->input('is_bear_fruit') ?? 0;
                }

                $tree->update([
                    'company_land_tree_no' => $request->input('company_land_tree_no'),
                    'company_land_tree_year' => $tree_year,
                    'company_land_tree_age' => $age->setting_tree_age,
                    'company_land_tree_circumference' => $request->input('company_land_tree_circumference'),
                    'product_id' => $request->input('product_id'),
                    'is_bear_fruit' => $is_bear_fruit,
                    'company_pnl_sub_item_code' => $request->input('company_pnl_sub_item_code'),
                    'company_land_tree_status' => $request->input('company_land_tree_status'),
                ]);

                CompanyLandTreeLog::create([
                  'company_land_tree_id' => $company_land_tree_id,
                  'company_land_tree_action_id' => 6,
                  'company_land_tree_log_description' => 'Tree details edited',
                  'company_land_tree_log_date' => date('Y-m-d'),
                  'company_land_tree_log_value' => null,
                  'user_id' => auth()->user()->user_id,
                ]);

                Session::flash('success_msg', 'Successfully updated tree');
                return redirect()->route('land_tree_listing', ['tenant' => tenant('id'), 'company_land_zone_id' =>$request->input('company_land_zone_id')]);
            }
        }
        $product_sel = Product::get_by_company();
        return view('land_tree.form', [
            'records' => CompanyLandTree::where('company_land_tree_id', $company_land_tree_id)->first(),
            'submit' => route('land_tree_edit', ['tenant' => tenant('id'), 'id' => $company_land_tree_id]),
            'type' => "Edit",
            'product_sel' => $product_sel,
            'company_land_tree_id' => $company_land_tree_id,
            'status_sel' =>  ['' => 'Please Select Status', 'alive' => 'Alive', 'dead' => 'Dead', 'saw off' => 'Saw Off'],
            'company_pnl_sub_item_code_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
        ])->withErrors($validator);
    }

    public function manage_tree(Request $request, $company_land_zone_id)
    {
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                // 'selected_land_tree_id_2' => 'required',
                'company_land_tree_action_id' => 'required',
                'company_land_tree_log_description' => 'required',
                'company_land_tree_log_date' => 'required',
                'company_land_tree_log_value' => 'nullable',
            ])->setAttributeNames([
                // 'selected_land_tree_id_2' => 'Selected Tree ID',
                'company_land_tree_action_id' => 'Action ID',
                'company_land_tree_log_description' => 'Description',
                'company_land_tree_log_date' => 'Date',
                'company_land_tree_log_value' => 'Value',
            ]);
            if (!$validation->fails()) {
                if($request->input('selected_land_tree_id_2')){
                  // dd($request->all());
                    $selected_land_tree_id= explode(',',$request->input('selected_land_tree_id_2'));
                    $company_land_tree = CompanyLandTree::whereIn('company_land_tree_id', $selected_land_tree_id)->get();
                    if($company_land_tree){
                        foreach($company_land_tree as $rows){
                            CompanyLandTreeLog::create([
                                'company_land_tree_id' => $rows->company_land_tree_id,
                                'company_land_tree_action_id' => $request->input('company_land_tree_action_id'),
                                'company_land_tree_log_description' => $request->input('company_land_tree_log_description'),
                                'company_land_tree_log_date' => $request->input('company_land_tree_log_date'),
                                'company_land_tree_log_value' => $request->input('company_land_tree_log_value'),
                                'user_id' => auth()->user()->user_id,
                            ]);
                            $company_land_tree = CompanyLandTree::find($rows->company_land_tree_id);
                            if($request->input('company_land_tree_action_id') == 1){
                                $company_land_tree->update([
                                'is_sick' => 1,
                                ]);
                            }elseif($request->input('company_land_tree_action_id') == 2){
                                $company_land_tree->update([
                                'is_sick' =>0,
                                ]);
                            }

                            $tree_action = CompanyLandTreeAction::find($request->input('company_land_tree_action_id'))->company_land_tree_action_name;
                            if($tree_action == 'Dead'){
                                $company_land_tree->update([
                                    'company_land_tree_status' => 2,
                                ]);
                            }
                        }
                    }
                }
                Session::flash('success_msg', 'Successfully Managed Tree');
                return redirect()->route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]);
            }
            $post = (object) $request->all();
        }
        Session::flash('fail_msg', 'Invalid Tree');
        return redirect()->route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]);

        // return view('land_tree.listing', [
        //     'post' => $post,
        //     'company_land_zone_id' => $company_land_zone_id
        // ])->withErrors($validation);
    }

    public function search_listing(Request $request){
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'company_land_zone_id' => 'required',
            ])->setAttributeNames([
                'company_land_zone_id' => 'Zone',
            ]);

            if(!$validation->fails()){
                $company_land_zone_id = $request->input('company_land_zone_id');
                return redirect()->route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id]);
            }
        }

        return view('land_tree.search_form',[
            'company_sel' => Company::get_company_sel(),
            'company_land_sel' => ['' => 'Please Select Land'],
        ])->withErrors($validation);
    }

    public function company_land_tree_report_detail(Request $request, $company_land_id){
        $search = null;


        return view('components.company_land_tree_report_detail', [
            'page_title' => 'Company Land Tree Report Details',
            'search' => $search,
            'records' => CompanyLandTree::get_tree_w_product_by_zone($company_land_id),
            'product' => Product::get_by_company_land($company_land_id),
            'records_age' => CompanyLandTree::get_tree_by_setting_tree_age_report($company_land_id),
            'pnl_sub_item' => CompanyPnlSubItem::all(),
            'tree_age' => SettingTreeAge::get_tree_age()
        ]);
    }

    public function company_land_tree_report_sick_tree_detail($company_land_id, $product_id){

        return view('components.company_land_tree_report_sick_tree_detail', [
            'page_title' => 'Company Land Tree Report Sick Tree Details',
            'records' => CompanyLandTree::get_sick_tree_w_product_report($company_land_id, $product_id),
            'product' => Product::get_by_company_land($company_land_id),
            'company_zone' => CompanyLandZone::get_by_land_id($company_land_id),
        ]);
    }

    public function company_land_tree_pointer_age_report_detail(Request $request, $company_id){
        $search = null;

        return view('components.company_land_tree_pointer_age_report_detail', [
            'page_title' => 'Company Tree Age Report Details',
            'search' => $search,
            // 'records' => CompanyLandTree::get_tree_w_product_by_zone($company_id),
            'product' => Product::get_by_company_budget_estimate2($company_id),
            'records_age' => CompanyLandTree::get_tree_by_setting_tree_age_report_company($company_id),
            // 'pnl_sub_item' => CompanyPnlSubItem::all(),
            'tree_age' => SettingTreeAge::get_tree_age()
        ]);
    }
}
