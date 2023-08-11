<?php

namespace App\Http\Controllers;

use App\Model\SettingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Auth;

class SettingPaymentController extends Controller
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
                    session(['filter_setting_payment' => [
                        'freetext' => $request->input('freetext'),
                        'status' => $request->input('setting_payment_status'),
                        'gateway' => $request->input('is_payment_gateway')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_setting_payment');
                    break;
            }
        }

        $search = session('filter_setting_payment') ? session('filter_setting_payment') : $search;

        return view('setting_payment.listing', [
            'submit' => route('setting_payment_listing', ['tenant' => tenant('id')]),
            'records' => SettingPayment::get_records($search, $perpage),
            'title' => 'Setting Payment Listing',
            'status' => ['' => 'Please select status', '0' => 'Active', '1' => 'Inactive'],
            'gateway' => ['' => 'Please select gateway', '0' => 'No', '1' => 'Yes'],
            'server' => ['' => 'Please select server', '0' => 'Offline', '1' => 'Online'],
            'search' => $search,
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_payment_name' => 'required|unique:tbl_setting_payment,setting_payment_name',
                'is_payment_gateway' => 'required',
                'is_offline' => 'required',
            ])->setAttributeNames([
                'setting_payment_name' => 'Setting Payment Name',
                'is_payment_gateway' => 'Payment Gateway',
                'is_offline' => 'Service',
            ]);
            if (!$validation->fails()) {
                SettingPayment::create([
                    'setting_payment_name' => $request->input('setting_payment_name'),
                    'is_payment_gateway' => $request->input('is_payment_gateway'),
                    'is_offline' => $request->input('is_offline'),
                    'setting_payment_status' => 0,
                ]);

                Session::flash('success_msg', 'Successfully added ' . $request->input('setting_payment_name'));
                return redirect()->route('setting_payment_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('setting_payment.form', [
            'submit' => route('setting_payment_add', ['tenant' => tenant('id')]),
            'edit' => false,
            'post' => $post,
            'title' => 'Add',
            'status' => ['' => 'Please select status', '0' => 'Active', '1' => 'Inactive'],
            'is_gateway' => ['' => 'Please select gateway', '0' => 'No', '1' => 'Yes'],
            'is_offline' => ['' => 'Please select server status', '0' => 'Offline', '1' => 'Online'],
        ])->withErrors($validation);
    }


    public function edit(Request $request, $setting_payment_id)
    {
        $post = $setting_payment = SettingPayment::find($setting_payment_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_payment_name' => "required|unique:tbl_setting_payment,setting_payment_name,{$setting_payment_id},setting_payment_id",
                'is_payment_gateway' => 'required',
                'is_offline' => 'required',
            ])->setAttributeNames([
                'setting_payment_name' => 'required',
                'is_payment_gateway' => 'required',
                'is_offline' => 'required',
            ]);
            if (!$validation->fails()) {
                $setting_payment->update([
                    'setting_payment_name' => $request->input('setting_payment_name'),
                    'is_payment_gateway' => $request->input('is_payment_gateway'),
                    'is_offline' => $request->input('is_offline'),
                    'customer_category_status' => $request->input('customer_category_status'),
                ]);

                Session::flash('success_msg', 'Successfully edited ' . $request->input('setting_payment_name'));
                return redirect()->route('setting_payment_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('setting_payment.form', [
            'submit' => route('setting_payment_edit', ['tenant' => tenant('id'), 'setting_payment_id' => $setting_payment_id]),
            'edit' => true,
            'title' => 'Edit',
            'post' => $post,
            'status' => ['' => 'Please select status', '0' => 'Active', '1' => 'Inactive'],
            'is_gateway' => ['' => 'Please select gateway', '0' => 'No', '1' => 'Yes'],
            'is_offline' => ['' => 'Please select server', '0' => 'Offline', '1' => 'Online'],
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $setting_payment_id = SettingPayment::find($request->input('setting_payment_id'));

        if (!$setting_payment_id) {
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_payment_listing', ['tenant' => tenant('id')]);
        }

        $setting_payment_id->update([
            'setting_payment_status' => '1',
        ]);


        Session::flash('success_msg', "Successfully deleted Customer Category.");
        return redirect()->route('setting_payment_listing', ['tenant' => tenant('id')]);
    }
}
