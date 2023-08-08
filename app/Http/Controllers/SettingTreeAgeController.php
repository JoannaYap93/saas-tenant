<?php

namespace App\Http\Controllers;

use App\Model\CompanyPnlSubItem;
use App\Model\Product;
use App\Model\SettingTreeAge;
use App\Model\SettingTreeAgePointer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingTreeAgeController extends Controller
{
    public function listing(Request $request)
    {
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('setting_tree_age_listing');
                    $search['freetext'] = $request->input('freetext');

                    Session::put('setting_tree_age_listing', $search);
                    break;
                case 'reset':
                    Session::forget('setting_tree_age_listing');
                    break;
            }
        }

        $search = session('setting_tree_age_listing') ?? [];

        return view('setting_tree_age.listing', [
            'page_title' => 'Setting Tree Age',
            'submit' => route('setting_tree_age_listing'),
            'search' => $search,
            'records' => SettingTreeAge::get_records($search, $perpage),
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_tree_age' => 'required',
                'setting_tree_age_lower_circumference' => 'required',
                'setting_tree_age_upper_circumference' => 'required',
                'setting_tree_age_pointer_value.*' => 'required',
                'company_pnl_sub_item_code' => 'required',
            ])->setAttributeNames([
                'setting_tree_age' => 'Age',
                'setting_tree_age_lower_circumference' => 'Lower Circumference',
                'setting_tree_age_upper_circumference' => 'Upper Circumference',
                'setting_tree_age_pointer_value.*' => 'Pointer Value',
                'company_pnl_sub_item_code' => 'Profit & Loss Sub Item',
            ]);

            if (!$validator->fails()) {
                $setting_tree_age = SettingTreeAge::create([
                    'setting_tree_age' => $request->input('setting_tree_age'),
                    'setting_tree_age_lower_circumference' => $request->input('setting_tree_age_lower_circumference') ?? 0,
                    'setting_tree_age_upper_circumference' => $request->input('setting_tree_age_upper_circumference') ?? 0,
                    'company_pnl_sub_item_code' => $request->input('company_pnl_sub_item_code'),
                ]);

                if (!is_null($request->input('product_id'))) {
                    foreach ($request->input('product_id') as $key => $product_id) {
                        $product = Product::find($product_id);
                        if ($product) {
                            SettingTreeAgePointer::create([
                                'product_id' => $product->product_id,
                                'setting_tree_age_id' => $setting_tree_age->setting_tree_age_id,
                                'setting_tree_age_pointer_value' => $request->input('setting_tree_age_pointer_value')[$key],
                            ]);
                        }
                    }
                }

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_tree_age_listing');
            }

            $post = (object) $request->all();
        }
        return view('setting_tree_age.form', [
            'submit' => route('setting_tree_age_add'),
            'title' => 'Add',
            'post' => $post,
            'company_pnl_sub_item_code_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
            'products' => Product::get_products_by_company(),
        ])->withErrors($validator);
    }

    public function edit(Request $request, $setting_tree_age_id)
    {
        $post = $setting_tree_age = SettingTreeAge::find($setting_tree_age_id);
        $validator = null;

        if (is_null($setting_tree_age)) {
            Session::flash('fail_msg', 'Invalid Setting Tree Age, Please Try Again');
            return redirect()->route('setting_tree_age_listing');
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_tree_age' => 'required',
                'setting_tree_age_lower_circumference' => 'required',
                'setting_tree_age_upper_circumference' => 'required',
                'company_pnl_sub_item_code' => 'required',
                'setting_tree_age_pointer_value.*' => 'required',
            ])->setAttributeNames([
                'setting_tree_age' => 'Age',
                'setting_tree_age_lower_circumference' => 'Lower Circumference',
                'setting_tree_age_upper_circumference' => 'Upper Circumference',
                'setting_tree_age_pointer_value.*' => 'Pointer Value',
                'company_pnl_sub_item_code' => 'Profit & Loss Sub Item',
            ]);

            if (!$validator->fails()) {
                $setting_tree_age->update([
                    'setting_tree_age' => $request->input('setting_tree_age'),
                    'setting_tree_age_lower_circumference' => $request->input('setting_tree_age_lower_circumference') ?? 0,
                    'setting_tree_age_upper_circumference' => $request->input('setting_tree_age_upper_circumference') ?? 0,
                    'company_pnl_sub_item_code' => $request->input('company_pnl_sub_item_code'),
                ]);

                if (!is_null($request->input('product_id'))) {
                    foreach ($request->input('product_id') as $key => $product_id) {
                        $product = Product::find($product_id);
                        if ($product) {
                            $setting_tree_age_pointer = SettingTreeAgePointer::query()
                                                                            ->where('product_id', $product_id)
                                                                            ->where('setting_tree_age_id', $setting_tree_age_id)
                                                                            ->first();
                            if($setting_tree_age_pointer){
                                $setting_tree_age_pointer->update([
                                    'setting_tree_age_pointer_value' => $request->input('setting_tree_age_pointer_value')[$key],
                                ]);
                            }else{
                                SettingTreeAgePointer::create([
                                    'product_id' => $product->product_id,
                                    'setting_tree_age_id' => $setting_tree_age->setting_tree_age_id,
                                    'setting_tree_age_pointer_value' => $request->input('setting_tree_age_pointer_value')[$key],
                                ]);
                            }
                        }
                    }
                }

                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_tree_age_listing');
            }
            $setting_tree_age = (object) $request->all();
        }
        return view('setting_tree_age.form', [
            'submit' => route('setting_tree_age_edit', $setting_tree_age_id),
            'title' => 'Edit',
            'post' => $post,
            'company_pnl_sub_item_code_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
            'products' => Product::get_products_by_company(),
        ])->withErrors($validator);
    }

    public function pointer(Request $request){
        $search = [];
        $validator = null;

        if($request->ajax()){
            SettingTreeAgePointer::find($request->pk)
            ->update([
                'setting_tree_age_pointer_value' => $request->value
            ]);

            return response()->json(['success' => true]);
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            ])->setAttributeNames([

            ]);

            if (!$validator->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('setting_tree_age_pointer');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_cb_id'] = $request->input('product_cb_id');
                        $search['age_id'] = $request->input('age_id');
                        $search['age_cb_id'] = $request->input('age_cb_id');

                        Session::put('setting_tree_age_pointer', $search);
                        break;
                    case 'reset':
                        Session::forget('setting_tree_age_pointer');
                        break;
                }
                return redirect()->route('setting_tree_age_pointer');
            }
        }

        $search = session('setting_tree_age_pointer') ?? [];
        $setting_tree_age = SettingTreeAge::get_records($search,false);
        $get_product = Product::get_records_no_paginate($search);

        return view('setting_tree_age.pointer', [
            'search' => $search,
            'submit' => route('setting_tree_age_pointer'),
            'title' => 'Setting Tree Age Pointer',
            'products' => $get_product,
            'records' => $setting_tree_age,
            'product_sel' => Product::get_by_company(),
            'age_sel' => SettingTreeAge::get_age(),
            'productCb' => Product::get_sel(),
            'ageCb' => SettingTreeAge::all(),
        ])->withErrors($validator);
    }
}
