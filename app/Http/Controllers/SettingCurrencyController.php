<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingCurrencyController extends Controller
{
    public function construct()
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
                    Session::forget('setting_currency_search');
                    $search['freetext'] = $request->input('freetext');

                    Session::put('setting_currency_search', $search);
                    break;
                case 'reset':
                    Session::forget('setting_currency_search');
                    break;
            }
        }

        $search = session('listing') ?? array();

        return view('setting_currency.listing', [
            'page_title' => 'Setting Currency',
            'submit' => route('setting_currency', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingCurrency::get_records($search, $perpage),
        ]);
    }


    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_currency_code' => 'required|unique:tbl_setting_currency,setting_currency_code',
                'setting_currency_name' => 'required',
            ])->setAttributeNames([
                'setting_currency_code' => 'Currency Code',
                'setting_currency_name' => 'Currency Name',
            ]);

            if (!$validator->fails()) {
                SettingCurrency::create([
                    'setting_currency_code' => $request->input('setting_currency_code') ?? '',
                    'setting_currency_name' => $request->input('setting_currency_name') ?? '',
                ]);

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_currency', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_currency.form', [
            'submit' => route('setting_currency_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);
    }

    public function edit(Request $request, $setting_currency_id)
    {
        $post = $setting_currency = SettingCurrency::find($setting_currency_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_currency_code' =>  "required|unique:tbl_setting_currency,setting_currency_code,{$setting_currency_id},setting_currency_id",
                'setting_currency_name' => 'required',
            ])->setAttributeNames([
                'setting_currency_code' => 'Currency Code',
                'setting_currency_name' => 'Currency Name',
            ]);
            if (!$validation->fails()) {
                $setting_currency->update([
                    'setting_currency_code' => $request->input('setting_currency_code'),
                    'setting_currency_name' => $request->input('setting_currency_name'),
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_currency', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_currency.form', [
            'submit' => route('setting_currency_edit', ['tenant' => tenant('id'), 'id' => $setting_currency_id]),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $currency = SettingCurrency::find($request->input('setting_currency_id'));

        if(!$currency){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_currency', ['tenant' => tenant('id')]);
        }

        $currency->delete();

        Session::flash('success_msg', "Successfully deleted currency.");
        return redirect()->route('setting_currency', ['tenant' => tenant('id')]);
    }
}
