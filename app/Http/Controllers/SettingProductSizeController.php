<?php

namespace App\Http\Controllers;

use App\Model\SettingSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class SettingProductSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin'], ['except' => ['listing']]);
    }

    public function listing(Request $request)
    {
        $perpage = 10;
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['setting_product_size_search' => [
                        'freetext' => $request->input('freetext'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('setting_product_size_search');
                    break;
            }
        }

        $search = session('setting_product_size_search') ? session('setting_product_size_search') : $search;

        return view('setting_product_size.listing', [
            'submit' => route('setting_product_size_listing'),
            'title' => 'Product Grade Listing',
            'records' =>  SettingSize::get_records($search, $perpage),
            'search' => $search,
        ]);
    }

    public function add(Request $request)
    {
        $post = null;
        $validation = null;
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_product_size_name' => 'required|unique:tbl_setting_product_size,setting_product_size_name',
            ])->setAttributeNames([
                'setting_product_size_name' => 'Name',
            ]);
            if (!$validation->fails()) {
                SettingSize::create([
                    'setting_product_size_name' => $request->input('setting_product_size_name')
                ]);

                Session::flash('success_msg', 'Successfully created new size for product');
                return redirect()->route('setting_product_size_listing');
            }
            $post = (object) $request->all();
        }

        return view('setting_product_size.form', [
            'post' => $post,
            'title' => 'Add',
            'submit' => route('setting_product_size_add'),
        ])->withErrors($validation);
    }

    public function edit(Request $request, $setting_product_size_id)
    {
        $post = $setting_size = SettingSize::find($setting_product_size_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_product_size_name' => "required|unique:tbl_setting_product_size,setting_product_size_name,{$setting_product_size_id},setting_product_size_id",
            ])->setAttributeNames([
                'setting_product_size_name' => 'Name',
            ]);

            if (!$validation->fails()) {
                $post->update([
                    'setting_product_size_name' => $request->input('setting_product_size_name')
                ]);

                Session::flash('success_msg', 'Successfully Update Size Name');
                return redirect()->route('setting_product_size_listing');
            }
            $post = (object) $request->all();
        }

        return view('setting_product_size.form', [
            'post' => $post,
            'title' => 'Edit',
            'submit' => route('setting_product_size_edit', $setting_product_size_id),
        ])->withErrors($validation);
    }
}
