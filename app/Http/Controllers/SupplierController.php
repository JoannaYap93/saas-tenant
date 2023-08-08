<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Supplier;
use App\Model\SettingBank;
use App\Model\SupplierBank;
use Illuminate\Http\Request;
use App\Model\SettingCurrency;
use App\Model\SupplierCompany;
use App\Model\SettingRawMaterial;
use App\Model\SupplierRawMaterial;
use Spatie\Permission\Models\Role;
use Log;
use Illuminate\Support\Facades\Session;
use App\Model\SettingRawMaterialCategory;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['supplier_search' => [
                        "freetext" => $request->input('freetext'),
                        "supplier_id" => $request->input('supplier_id'),
                        "company_id" => $request->input('company_id'),
                        "raw_material_category_id" => $request->input('raw_material_category_id'),
                        "raw_material_id" => $request->input('raw_material_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('supplier_search');
                    break;
            }
        }
        $search = session('supplier_search') ?? $search;

        return view('supplier.listing', [
            'submit' => route('supplier_listing'),
            'search' =>  $search,
            'records' => Supplier::get_records($search, 15),
            'company_sel' => Company::get_company_sel(),
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $supplier = null;

        if ($request->isMethod('post')) {
            $supplier_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('supplier_mobile_no'));
            if (substr($supplier_mobile_initial, 0, 1) == '0') {
                $supplier_mobile_no = '6' . $supplier_mobile_initial;
            } elseif (substr($supplier_mobile_initial, 0, 1) == '1') {
                $supplier_mobile_no = "60" . $supplier_mobile_initial;
            } else {
                $supplier_mobile_no = $supplier_mobile_initial;
            }

            if(!is_null($request->input('supplier_phone_no'))) {
                $supplier_phone_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('supplier_phone_no'));
                if (substr($supplier_phone_initial, 0, 1) == '0') {
                    $supplier_phone_no = '6' . $supplier_phone_initial;
                } elseif (substr($supplier_phone_initial, 0, 1) == '1') {
                    $supplier_phone_no = "60" . $supplier_phone_initial;
                } else {
                    $supplier_phone_no = $supplier_phone_initial;
                }
            }

            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required',
                'supplier_mobile_no' => 'nullable|min:8|max:12',
                'supplier_phone_no' => 'nullable|min:8|max:12',
                'supplier_email' => 'nullable',
                'supplier_address' => 'nullable',
                'supplier_address2' => 'nullable',
                'supplier_city' => 'nullable',
                'supplier_state' => 'nullable',
                'supplier_country' => 'nullable',
                'supplier_postcode' => 'nullable',
                'supplier_pic' => 'nullable',
                'supplier_currency' => 'nullable',
                'supplier_credit_term' => 'nullable',
                'supplier_credit_limit' => 'nullable',
                'setting_bank_id.*' => 'required_with:supplier_bank_acc_no.*|nullable',
                'supplier_bank_acc_name.*' => 'required_with:supplier_bank_acc_no.*|nullable',
                'supplier_bank_acc_no.*' => 'required_with:supplier_bank_acc_name.*, setting_bank_id.*|unique:tbl_supplier_bank,supplier_bank_acc_no|nullable',
                'company_id' => 'required'
            ])->setAttributeNames([
                'supplier_name' => 'Supplier Name',
                'supplier_mobile_no' => 'Supplier Mobile Number',
                'supplier_phone_no' => 'Supplier Phone Number',
                'supplier_email' => 'Supplier Email',
                'supplier_address' => 'Supplier Address Line 1',
                'supplier_address2' => 'Supplier Adddress Line 2',
                'supplier_city' => 'Supplier Address City',
                'supplier_state' => 'Supplier Address State',
                'supplier_country' => 'Supplier Address Country',
                'supplier_postcode' => 'Supplier Address Postcode',
                'supplier_pic' => 'Supplier Person In Charge Name',
                'supplier_currency' => 'Supplier Currency',
                'supplier_credit_term' => 'Supplier Credit Term',
                'supplier_credit_limit' => 'Supplier Credit Limit',
                'supplier_status' => 'Supplier Status',
                'setting_bank_id.*' => 'Bank Name',
                'company_bank_acc_name.*' => 'Bank Account Name',
                'company_bank_acc_no.*' => 'Bank Account No.'
            ]);

            // $mobile_exist = Supplier::check_supplier_mobile_exist($supplier_mobile_no, 0);
            // if ($mobile_exist) {
            //     $validator->after(function ($validation) {
            //         $validation->getMessageBag()->add('supplier_mobile_no', 'The Mobile Number has already been taken.');
            //     });
            // }

            // if(!is_null($request->input('supplier_phone_no'))) {
            //     $phone_exist = Supplier::check_supplier_phone_exist($supplier_phone_no, 0);
            //     if ($phone_exist) {
            //         $validator->after(function ($validation) {
            //             $validation->getMessageBag()->add('supplier_phone_no', 'The Phone Number has already been taken.');
            //         });
            //     }
            // }

            if (!$validator->fails()) {
                $supplier = Supplier::create([
                    'supplier_name' => $request->input('supplier_name'),
                    'supplier_mobile_no' => $supplier_mobile_no,
                    'supplier_phone_no' => $supplier_phone_no ?? '',
                    'supplier_email' => $request->input('supplier_email'),
                    'supplier_address' => $request->input('supplier_address'),
                    'supplier_address2' => $request->input('supplier_address2'),
                    'supplier_city' => $request->input('supplier_city'),
                    'supplier_state' => $request->input('supplier_state'),
                    'supplier_country' => $request->input('supplier_country'),
                    'supplier_postcode' => $request->input('supplier_postcode'),
                    'supplier_pic' => $request->input('supplier_pic'),
                    'supplier_currency' => $request->input('supplier_currency'),
                    'supplier_credit_term' => $request->input('supplier_credit_term'),
                    'supplier_credit_limit' => $request->input('supplier_credit_limit'),
                    'supplier_status' => (!is_null($request->input('supplier_status')) ? "Active" : "Inactive"),
                ]);

                if (is_null($request->input('raw_material_id')) == false) {
                    foreach ($request->input('raw_material_id') as $raw_material_id) {
                        $raw_material = SettingRawMaterial::where('raw_material_id', '=', $raw_material_id)
                                                            ->where('raw_material_status', 'active')
                                                            ->first();
                        if ($raw_material) {
                            SupplierRawMaterial::create([
                                'supplier_id' => $supplier->supplier_id,
                                'raw_material_id' => $raw_material_id,
                            ]);
                        }
                    }
                }

                if(auth()->user()->company_id == 0){
                    if (is_null($request->input('company_id')) == false) {
                        foreach ($request->input('company_id') as $company_id) {
                            $company = Company::find($company_id);
                            if ($company) {
                                SupplierCompany::create([
                                    'supplier_id' => $supplier->supplier_id,
                                    'company_id' => $company_id,
                                ]);
                            }
                        }
                    }
                }else{
                    SupplierCompany::create([
                        'supplier_id' => $supplier->supplier_id,
                        'company_id' => auth()->user()->company_id,
                    ]);
                }

                if (!is_null($request->input('supplier_bank_acc_no'))) {
                    for ($i = 0; $i < count($request->input('supplier_bank_acc_no')); $i++) {
                        $supplier_bank = SupplierBank::create([
                            'setting_bank_id' =>$request->input('setting_bank_id')[$i],
                            'supplier_bank_acc_name' =>$request->input('supplier_bank_acc_name')[$i],
                            'supplier_bank_acc_no' =>$request->input('supplier_bank_acc_no')[$i],
                            'supplier_id' => $supplier->supplier_id,
                            'is_deleted' => 0,
                        ]);
                    }
                }

                Session::flash('success_msg', 'Successfully added '.$request->input('supplier_name'));
                return redirect()->route('supplier_listing');
            }

            $supplier = (object) $request->all();
        }

        return view('supplier.form', [
            'submit' => route('supplier_add'),
            'title' => 'Add',
            'supplier' => $supplier,
            'company_sel' => Company::get_company_check_box(),
            'raw_material_category_checkbox_sel' => SettingRawMaterialCategory::raw_material_category_checkbox_sel(),
            'raw_material_checkbox_sel' => SettingRawMaterial::raw_material_checkbox_sel(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
            'setting_currency_sel' => SettingCurrency::setting_currency_sel(),
            'supplier_status_sel' => ['' => 'Please Select Status', 'Active' => 'Active', 'Inactive' => 'Inactive'],
        ])->withErrors($validator);
    }

    public function edit(Request $request, $supplier_id)
    {
        $validator = null;
        $supplier = Supplier::where('supplier_id', '=', $supplier_id)->first();

        if ($supplier == null) {
            Session::flash('fail_msg', 'Invalid Supplier, Please Try Again');
            return redirect()->route('supplier_listing');
        }

        if ($request->isMethod('post')) {
            $supplier_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('supplier_mobile_no'));
            if (substr($supplier_mobile_initial, 0, 1) == '0') {
                $supplier_mobile_no = '6' . $supplier_mobile_initial;
            } elseif (substr($supplier_mobile_initial, 0, 1) == '1') {
                $supplier_mobile_no = "60" . $supplier_mobile_initial;
            } else {
                $supplier_mobile_no = $supplier_mobile_initial;
            }

            if(!is_null($request->input('supplier_phone_no'))) {
                $supplier_phone_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('supplier_phone_no'));
                if (substr($supplier_phone_initial, 0, 1) == '0') {
                    $supplier_phone_no = '6' . $supplier_phone_initial;
                } elseif (substr($supplier_phone_initial, 0, 1) == '1') {
                    $supplier_phone_no = "60" . $supplier_phone_initial;
                } else {
                    $supplier_phone_no = $supplier_phone_initial;
                }
            }

            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required',
                'supplier_mobile_no' => 'nullable|min:8|max:12',
                'supplier_phone_no' => 'nullable|min:8|max:12',
                'supplier_email' => "nullable",
                'supplier_address' => 'nullable',
                'supplier_address2' => 'nullable',
                'supplier_city' => 'nullable',
                'supplier_state' => 'nullable',
                'supplier_country' => 'nullable',
                'supplier_postcode' => 'nullable',
                'supplier_pic' => 'nullable',
                'supplier_currency' => 'nullable',
                'supplier_credit_term' => 'nullable',
                'supplier_credit_limit' => 'nullable',
                'setting_bank_id.*' => 'required_with:supplier_bank_acc_no.*|nullable',
                'supplier_bank_acc_name.*' => 'required_with:supplier_bank_acc_no.*|nullable',
                'supplier_bank_acc_no.*' => "required_with:supplier_bank_acc_name.*, setting_bank_id.*|unique:tbl_supplier_bank,supplier_bank_acc_no,{$supplier_id},supplier_id|nullable",
                'company_id' => 'required'
            ])->setAttributeNames([
                'supplier_name' => 'Supplier Name',
                'supplier_mobile_no' => 'Supplier Mobile Number',
                'supplier_phone_no' => 'Supplier Phone Number',
                'supplier_email' => 'Supplier Email',
                'supplier_address' => 'Supplier Address Line 1',
                'supplier_address2' => 'Supplier Adddress Line 2',
                'supplier_city' => 'Supplier Address City',
                'supplier_state' => 'SupplierAddress State',
                'supplier_country' => 'Supplier Address Country',
                'supplier_postcode' => 'Supplier Address Postcode',
                'supplier_pic' => 'Supplier Person In Charge Name',
                'supplier_currency' => 'Supplier Currency',
                'supplier_credit_term' => 'Supplier Credit Term',
                'supplier_credit_limit' => 'Supplier Credit Limit',
                'supplier_status' => 'Supplier Status',
                'setting_bank_id.*' => 'Bank Name',
                'company_bank_acc_name.*' => 'Bank Account Name',
                'company_bank_acc_no.*' => 'Bank Account No.'
            ]);

            // $mobile_exist = Supplier::check_supplier_mobile_exist($supplier_mobile_no, $supplier_id);
            // if ($mobile_exist) {
            //     $validator->after(function ($validation) {
            //         $validation->getMessageBag()->add('supplier_mobile_no', 'The Mobile Number has already been taken.');
            //     });
            // }

            // if(!is_null($request->input('supplier_phone_no'))) {
            //     $phone_exist = Supplier::check_supplier_phone_exist($supplier_phone_no, $supplier_id);
            //     if ($phone_exist) {
            //         $validator->after(function ($validation) {
            //             $validation->getMessageBag()->add('supplier_phone_no', 'The Phone Number has already been taken.');
            //         });
            //     }
            // }

            if (!$validator->fails()) {
                $updated_supplier_details = ([
                    'supplier_name' => $request->input('supplier_name'),
                    'supplier_mobile_no' => $supplier_mobile_no,
                    'supplier_phone_no' => $supplier_phone_no ?? '',
                    'supplier_email' => $request->input('supplier_email'),
                    'supplier_address' => $request->input('supplier_address'),
                    'supplier_address2' => $request->input('supplier_address2'),
                    'supplier_city' => $request->input('supplier_city'),
                    'supplier_state' => $request->input('supplier_state'),
                    'supplier_country' => $request->input('supplier_country'),
                    'supplier_postcode' => $request->input('supplier_postcode'),
                    'supplier_pic' => $request->input('supplier_pic'),
                    'supplier_currency' => $request->input('supplier_currency'),
                    'supplier_credit_term' => $request->input('supplier_credit_term'),
                    'supplier_credit_limit' => $request->input('supplier_credit_limit'),
                    'supplier_status' => (!is_null($request->input('supplier_status')) ? "Active" : "Inactive"),
                ]);

                $supplier->update($updated_supplier_details);

                if(!is_null($request->input('supplier_bank_acc_no'))){
                    $supplier_bank_id = $request->input('supplier_bank_id');

                    $old_bank = SupplierBank::query()->where('supplier_id', $supplier->supplier_id)->pluck('supplier_bank_id')->toArray();
                    $new_bank = $supplier_bank_id;

                    $remove = array_diff($old_bank, $new_bank);

                    foreach($request->input('supplier_bank_acc_no') as $key => $bank_details){
                        $find_bank = SupplierBank::find($supplier_bank_id[$key]);

                        if ($find_bank) {
                            $find_bank->update([
                                'setting_bank_id' => $request->input('setting_bank_id')[$key],
                                'supplier_bank_acc_name' => $request->input('supplier_bank_acc_name')[$key],
                                'supplier_bank_acc_no' => $request->input('supplier_bank_acc_no')[$key],
                            ]);
                        } else {
                            $supplier_bank = SupplierBank::create([
                                'setting_bank_id' =>$request->input('setting_bank_id')[$key],
                                'supplier_bank_acc_name' =>$request->input('supplier_bank_acc_name')[$key],
                                'supplier_bank_acc_no' =>$request->input('supplier_bank_acc_no')[$key],
                                'supplier_id' => $supplier_id,
                                'is_deleted' => 0,
                            ]);
                        }

                        if ($remove) {
                            foreach ($remove as $del_supplier_bank_id) {
                                $delete = SupplierBank::find($del_supplier_bank_id);
                                $delete->update([
                                    'is_deleted' => 1,
                                ]);
                            }
                        }
                    }
                }

                if (is_null($request->input('raw_material_id')) == false) {
                    SupplierRawMaterial::where('supplier_id', $supplier_id)->delete();
                    foreach ($request->input('raw_material_id') as $raw_material_id) {
                        $raw_material = SettingRawMaterial::where('raw_material_id', '=', $raw_material_id)
                                                            ->where('raw_material_status', '=', 'active')
                                                            ->first();
                        if ($raw_material) {
                            SupplierRawMaterial::create([
                                'supplier_id' => $supplier_id,
                                'raw_material_id' => $raw_material_id,
                            ]);
                        }
                    }
                } else {
                    SupplierRawMaterial::where('supplier_id', $supplier_id)->delete();
                }

                if(auth()->user()->company_id == 0){
                    if (is_null($request->input('company_id')) == false) {
                        SupplierCompany::where('supplier_id', $supplier_id)->delete();
                        foreach ($request->input('company_id') as $company_id) {
                            $company = Company::find($company_id);
                            if ($company) {
                                SupplierCompany::create([
                                    'supplier_id' => $supplier_id,
                                    'company_id' => $company_id,
                                ]);
                            }
                        }
                    } else {
                        SupplierCompany::where('supplier_id', $supplier_id)->delete();
                    }
                }

                Session::flash('success_msg', 'Successfully Updated Supplier ' . $request->input('supplier_name'));
                return redirect()->route('supplier_listing');
            }
            $supplier = (object) $request->all();
        }

        return view('supplier.form', [
            'submit' => route('supplier_edit', $supplier_id),
            'title' => 'Edit',
            'supplier' => $supplier,
            'company_sel' => Company::get_company_check_box(),
            'raw_material_category_checkbox_sel' => SettingRawMaterialCategory::raw_material_category_checkbox_sel(),
            'raw_material_checkbox_sel' => SettingRawMaterial::raw_material_checkbox_sel(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
            'setting_currency_sel' => ['' => 'Please Select Currency'] + SettingCurrency::setting_currency_sel(),
            'supplier_status_sel' => ['' => 'Please Select Status', 'Active' => 'Active', 'Inactive' => 'Inactive'],
        ])->withErrors($validator);
    }

    public function ajax_get_raw_material_by_raw_material_category_id(Request $request)
    {
        $raw_material_name = $request->input('raw_material_name');
        $company_id = $request->input('company_id');
        $supplier_id = $request->input('supplier_id');
        $raw_material_category_id= $request->input('raw_material_category_id');
        $raw_material = SettingRawMaterial::get_by_raw_material_category_id($raw_material_category_id, $supplier_id, $company_id, $raw_material_name);
        return response()->json(['data' => $raw_material]);
    }

    public function ajax_get_raw_material_details(Request $request)
    {
        $raw_material_id = $request->input('raw_material_id');
        $raw_material_details = SettingRawMaterial::get_raw_material_details($raw_material_id);
        return response()->json(['data' => $raw_material_details, 'status' => $raw_material_details ? true : false]);
    }

    public function ajax_get_supplier_by_company_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $supplier = Supplier::get_supplier_by_company_id($company_id);
        return response()->json(['data' => $supplier]);
    }

    public function ajax_get_supplier_by_upkeep(Request $request)
    {
        $company_id = $request->input('company_id');
        $supplier = Supplier::get_supplier_by_company_id($company_id);
        return ['data' => $supplier];
    }
}
