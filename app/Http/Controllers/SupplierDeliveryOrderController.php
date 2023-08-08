<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\RawMaterialCompany;
use App\Model\RawMaterialCompanyUsage;
use App\Model\RawMaterialCompanyUsageLog;
use App\Model\RunningNumber;
use App\Model\SettingRawMaterialCategory;
use App\Model\Supplier;
use App\Model\SupplierDeliveryOrder;
use App\Model\SupplierDeliveryOrderItem;
use App\Model\SupplierDeliveryOrderLog;
use App\Model\SupplierDeliveryOrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupplierDeliveryOrderController extends Controller
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
                    session(['supplier_do_search' => [
                        "freetext" => $request->input('freetext'),
                        "supplier_id" => $request->input('supplier_id'),
                        "company_id" => $request->input('company_id'),
                        "raw_material_category_id" => $request->input('raw_material_category_id'),
                        "raw_material_id" => $request->input('raw_material_id'),
                    ]]);
                break;
                case 'reset':
                    session()->forget('supplier_do_search');
                break;
            }
        }
        $search = session('supplier_do_search') ?? $search;

        return view('supplier_delivery_order.listing', [
            'records' => SupplierDeliveryOrder::get_records($search),
            'submit' => route('supplier_do_listing'),
            'search' => $search,
            'company_sel' => Company::get_company_sel(),
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $user = auth()->user();
        $supplier_do = null;
        $validator = null;

        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                'supplier_id' => 'required',
                'company_id' => auth()->user()->company_id == 0 ? 'required' : 'nullable',
                'supplier_delivery_order_no' => 'required',
                'supplier_delivery_order_date' => 'required',
                'raw_material_id.*' => 'required',
                'supplier_delivery_order_item_value_per_qty.*' => 'required',
                'supplier_delivery_order_item_qty.*' => 'required',
                'supplier_delivery_order_item_price_per_qty.*' => 'required',
                'supplier_delivery_order_item_disc.*' => 'required',
                'supplier_delivery_order_item_amount.*' => 'required',
                'supplier_delivery_order_tax' => 'required',
                'supplier_do_media' => 'required',
            ])->setAttributeNames([
                'supplier_id' => 'Supplier Name',
                'company_id' => 'Company',
                'supplier_delivery_order_no' => 'Supplier Delivery Order Number',
                'supplier_delivery_order_date' => 'Stock In Date',
                'supplier_delivery_order_item_id.*' => 'Raw Material ',
                'supplier_delivery_order_item_value_per_qty.*' => 'Raw Material Value',
                'supplier_delivery_order_item_qty.*' => 'Raw Material Quantity',
                'supplier_delivery_order_item_price_per_qty.*' => 'Raw Material Unit Price',
                'supplier_delivery_order_item_disc.*' => 'Raw Material Discount',
                'supplier_delivery_order_item_amount.*' => 'Raw Material Amount',
                'supplier_delivery_order_tax' => 'Tax',
                'supplier_do_media' => 'Invoice',
            ]);

            if(is_null($request->input('raw_material_id'))){
                $validator->after(function ($validator) {
                    $validator->getMessageBag()->add('raw_material_id', 'Please add a delivery order item to continue.');
                });
            }

            if(!$validator->fails()){
                $company_id = (auth()->user()->company_id == 0 ? $request->input('company_id') : auth()->user()->company_id);
                $company = Company::find($company_id);

                $subtotal = 0;
                $discount = 0;
                $total = 0;
                $grandtotal = 0;

                $running_no = RunningNumber::get_running_code('supplier_delivery_order');

                $supplier_do = SupplierDeliveryOrder::create([
                    'supplier_delivery_order_no' => $request->input('supplier_delivery_order_no'),
                    'supplier_delivery_order_running_no' => 'SDO/' . $company->company_code . '/' . $user->user_unique_code . '/' . $running_no,
                    'supplier_delivery_order_subtotal' => 0,
                    'supplier_delivery_order_discount' => 0,
                    'supplier_delivery_order_total' => 0,
                    'supplier_delivery_order_tax' => $request->input('supplier_delivery_order_tax'),
                    'supplier_delivery_order_grandtotal' => 0,
                    'supplier_delivery_order_status' => 'completed',
                    'supplier_delivery_order_date' => $request->input('supplier_delivery_order_date'),
                    'supplier_id' => $request->input('supplier_id'),
                    'company_id' => $company_id,
                    'user_id' => $user->user_id,
                ]);

                if(!is_null($request->input('raw_material_id'))){
                    foreach($request->input('raw_material_id') as $key => $raw_material_id){
                        $supplier_delivery_order_item_qty = $request->input('supplier_delivery_order_item_qty');
                        $supplier_delivery_order_item_value_per_qty = $request->input('supplier_delivery_order_item_value_per_qty');
                        $supplier_delivery_order_item_price_per_qty = $request->input('supplier_delivery_order_item_price_per_qty');
                        $supplier_delivery_order_item_disc = $request->input('supplier_delivery_order_item_disc');

                        $price_per_qty_after_disc = $supplier_delivery_order_item_price_per_qty[$key] - ($supplier_delivery_order_item_disc[$key] / $supplier_delivery_order_item_qty[$key]);

                        $subtotal += $supplier_delivery_order_item_qty[$key] * $supplier_delivery_order_item_price_per_qty[$key];
                        $discount += $supplier_delivery_order_item_disc[$key];

                        $raw_material_company = RawMaterialCompany::query()
                                                                ->where('raw_material_id', $raw_material_id)
                                                                ->where('company_id', $company_id)
                                                                ->first();

                        if(is_null($raw_material_company)) {
                            $raw_material_company = RawMaterialCompany::create([
                                'raw_material_id' => $raw_material_id,
                                'company_id' => $company_id,
                                'raw_material_quantity' => 0,
                                'raw_material_value' => 0,
                                'raw_material_company_status' => 'active',
                            ]);
                        }

                        $raw_material_company_usage = RawMaterialCompanyUsage::create([
                            'raw_material_id' => $raw_material_id,
                            'raw_material_company_id' => $raw_material_company->raw_material_company_id,
                            'raw_material_company_usage_type' => 'stock in',
                            'raw_material_company_usage_qty' => $supplier_delivery_order_item_qty[$key],
                            'raw_material_company_usage_price_per_qty' => $price_per_qty_after_disc,
                            'raw_material_company_usage_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                            'raw_material_company_usage_total_price' => $price_per_qty_after_disc * $supplier_delivery_order_item_qty[$key] ,
                            'raw_material_company_usage_total_value' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                            'formula_usage_id' => 0,
                            'formula_usage_item_id' => 0,
                            'unit_price_per_value' => $price_per_qty_after_disc / $supplier_delivery_order_item_value_per_qty[$key],
                            'raw_material_company_usage_total_value_remaining' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                            'is_claim' => 0,
                            'claim_user_id' => '',
                            'raw_material_company_usage_date' => $request->input('supplier_delivery_order_date'),
                            'user_id' => $user->user_id,
                        ]);

                        $raw_material_company_usage_log = RawMaterialCompanyUsageLog::create([
                            'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                            'user_id' => auth()->user()->user_id,
                            'raw_material_company_usage_log_created' => now(),
                            'raw_material_company_usage_log_action' => "Add",
                            'raw_material_company_usage_log_description' => "Multi stock in",
                            'raw_material_company_usage_log_value_before' => 0,
                            'raw_material_company_usage_log_value_after' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                        ]);

                        $supplier_do_item = SupplierDeliveryOrderItem::create([
                            'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                            'raw_material_id' => $raw_material_id,
                            'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                            'supplier_delivery_order_item_qty' => $supplier_delivery_order_item_qty[$key],
                            'supplier_delivery_order_item_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                            'supplier_delivery_order_item_price_per_qty' => $supplier_delivery_order_item_price_per_qty[$key],
                            'supplier_delivery_order_item_disc' => $supplier_delivery_order_item_disc[$key],
                        ]);

                        $raw_material_company_update_details = ([
                            'raw_material_quantity' => $raw_material_company->raw_material_quantity + $supplier_delivery_order_item_qty[$key],
                            'raw_material_value' => $raw_material_company->raw_material_value + ($supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key]),
                        ]);

                        $raw_material_company->update($raw_material_company_update_details);
                    }

                    $total = $subtotal - $discount;
                    $grandtotal = $total + $request->input('supplier_delivery_order_tax');

                    $supplier_do->update([
                        'supplier_delivery_order_subtotal' => $subtotal,
                        'supplier_delivery_order_discount' => $discount,
                        'supplier_delivery_order_total' => $total,
                        'supplier_delivery_order_grandtotal' => $grandtotal,
                    ]);

                    $supplier_delivery_order_log = SupplierDeliveryOrderLog::create([
                        'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                        'supplier_delivery_order_log_action' => "Add",
                        'supplier_delivery_order_log_description' => "Stock in by " . auth()->user()->user_fullname,
                        'supplier_delivery_order_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);
                }

                if($request->file('supplier_do_media')){
                    $supplier_do->addMediaFromRequest('supplier_do_media')->toMediaCollection('supplier_delivery_order_media');
                }

                Session::flash('success_msg', 'Successfully added supplier delivery order.');
                return redirect()->route('supplier_do_listing');
            }

            $supplier_do = (object) $request->all();
        }

        return view('supplier_delivery_order.form', [
            'submit' => route('supplier_do_add'),
            'title' => 'Add',
            'supplier_do' => $supplier_do,
            'company_sel' => Company::get_company_sel(),
            'supplier_sel' => ['' => "Please Select Supplier"] + Supplier::supplier_sel(),
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ])->withErrors($validator);
    }

    public function edit(Request $request, $supplier_delivery_order_id)
    {
        $user = auth()->user();
        $validator = null;
        $supplier_do = SupplierDeliveryOrder::find($supplier_delivery_order_id);

        if($supplier_do == null){
            Session::flash('fail_msg', 'Invalid Supplier Delivery Order, Please Try Again');
            return redirect()->route('supplier_do_listing');
        }

        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                'supplier_id' => 'required',
                'company_id' => 'required',
                'supplier_delivery_order_no' => 'required',
                'supplier_delivery_order_date' => 'required',
                'raw_material_id.*' => 'required',
                'supplier_delivery_order_item_value_per_qty.*' => 'required',
                'supplier_delivery_order_item_qty.*' => 'required',
                'supplier_delivery_order_item_price_per_qty.*' => 'required',
                'supplier_delivery_order_item_disc.*' => 'required',
                'supplier_delivery_order_item_amount.*' => 'required',
                'supplier_delivery_order_tax' => 'required',
                // 'supplier_do_media' => 'required',
            ])->setAttributeNames([
                'supplier_id' => 'Supplier Name',
                'company_id' => 'Company',
                'supplier_delivery_order_no' => 'Supplier Delivery Order Number',
                'supplier_delivery_order_date' => 'Stock In Date',
                'supplier_delivery_order_item_id.*' => 'Raw Material ',
                'supplier_delivery_order_item_value_per_qty.*' => 'Raw Material Value',
                'supplier_delivery_order_item_qty.*' => 'Raw Material Quantity',
                'supplier_delivery_order_item_price_per_qty.*' => 'Raw Material Unit Price',
                'supplier_delivery_order_item_disc.*' => 'Raw Material Discount',
                'supplier_delivery_order_item_amount.*' => 'Raw Material Amount',
                'supplier_delivery_order_tax' => 'Tax',
                // 'supplier_do_media' => 'Invoice',
            ]);

            if(is_null($request->input('raw_material_id'))){
                $validator->after(function ($validator) {
                    $validator->getMessageBag()->add('raw_material_id', 'Please add a delivery order item to continue.');
                });
            }

            if(!$validator->fails()){
                $company_id = (auth()->user()->company_id == 0 ? $request->input('company_id') : auth()->user()->company_id);
                $company = Company::find($company_id);

                $subtotal = $discount = $total = $grandtotal = 0;

                $existing_supplier_do_items = SupplierDeliveryOrderItem::query()
                                                                        ->where('supplier_delivery_order_id', $supplier_delivery_order_id)
                                                                        ->get();

                $existing_raw_material_ids = $existing_supplier_do_items->pluck('raw_material_id')->toArray();
                $new_raw_material_ids = $request->input('raw_material_id');

                $remove_raw_materials = array_diff($existing_raw_material_ids, $new_raw_material_ids);

                if(!is_null($request->input('raw_material_id')))
                {
                    foreach($new_raw_material_ids as $key => $raw_material_id)
                    {
                        $supplier_delivery_order_item_qty = $request->input('supplier_delivery_order_item_qty');
                        $supplier_delivery_order_item_value_per_qty = $request->input('supplier_delivery_order_item_value_per_qty');
                        $supplier_delivery_order_item_price_per_qty = $request->input('supplier_delivery_order_item_price_per_qty');
                        $supplier_delivery_order_item_disc = $request->input('supplier_delivery_order_item_disc');

                        $price_per_qty_after_disc = $supplier_delivery_order_item_price_per_qty[$key] - ($supplier_delivery_order_item_disc[$key] / $supplier_delivery_order_item_qty[$key]);

                        $subtotal += $supplier_delivery_order_item_qty[$key] * $supplier_delivery_order_item_price_per_qty[$key];
                        $discount += $supplier_delivery_order_item_disc[$key];

                        $search_supplier_do_item = SupplierDeliveryOrderItem::query()
                                                                        ->where('supplier_delivery_order_id', $supplier_delivery_order_id)
                                                                        ->where('raw_material_id', $raw_material_id)
                                                                        ->first();

                        if ($search_supplier_do_item)
                        {
                            $raw_material_company_usage_id = $search_supplier_do_item->raw_material_company_usage_id;
                            $raw_material_company_usage = RawMaterialCompanyUsage::find($raw_material_company_usage_id);

                            if($raw_material_company_usage){
                                $current_raw_material_company_usage_qty = $raw_material_company_usage->raw_material_company_usage_qty;
                                $current_raw_material_company_usage_total_value = $raw_material_company_usage->raw_material_company_usage_total_value;

                                $raw_material_company_id = $raw_material_company_usage->raw_material_company_id;
                                $raw_material_company = RawMaterialCompany::find($raw_material_company_id);

                                if($raw_material_company){
                                    // deduct existing value and quantity, then add back the updated value
                                    $raw_material_company->update([
                                        'raw_material_quantity' => ($raw_material_company->raw_material_quantity - $current_raw_material_company_usage_qty) + $supplier_delivery_order_item_qty[$key],
                                        'raw_material_value' => ($raw_material_company->raw_material_value - $current_raw_material_company_usage_total_value) + ($supplier_delivery_order_item_qty[$key] * $supplier_delivery_order_item_value_per_qty[$key]),
                                    ]);

                                    $raw_material_company_usage->update([
                                        'raw_material_company_usage_qty' => $supplier_delivery_order_item_qty[$key],
                                        'raw_material_company_usage_price_per_qty' => $price_per_qty_after_disc,
                                        'raw_material_company_usage_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                                        'raw_material_company_usage_total_price' => $price_per_qty_after_disc * $supplier_delivery_order_item_qty[$key],
                                        'raw_material_company_usage_total_value' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                                        'unit_price_per_value' => $price_per_qty_after_disc / $supplier_delivery_order_item_value_per_qty[$key],
                                        'raw_material_company_usage_total_value_remaining' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                                        'raw_material_company_usage_date' => $request->input('supplier_delivery_order_date'),
                                    ]);

                                    $raw_material_company_usage_log = RawMaterialCompanyUsageLog::create([
                                        'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                                        'user_id' => $user->user_id,
                                        'raw_material_company_usage_log_created' => now(),
                                        'raw_material_company_usage_log_action' => "Edit",
                                        'raw_material_company_usage_log_description' => "Multi stock in edit amount",
                                        'raw_material_company_usage_log_value_before' => $current_raw_material_company_usage_total_value,
                                        'raw_material_company_usage_log_value_after' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                                    ]);

                                    $search_supplier_do_item->update([
                                        'supplier_delivery_order_item_qty' => $supplier_delivery_order_item_qty[$key],
                                        'supplier_delivery_order_item_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                                        'supplier_delivery_order_item_price_per_qty' => $supplier_delivery_order_item_price_per_qty[$key],
                                        'supplier_delivery_order_item_disc' => $supplier_delivery_order_item_disc[$key],
                                    ]);
                                }
                            }
                        }
                        else
                        {
                            $raw_material_company = RawMaterialCompany::query()
                                                                    ->where('raw_material_id', $raw_material_id)
                                                                    ->where('company_id', $company_id)
                                                                    ->first();
                            if(is_null($raw_material_company))
                            {
                                $raw_material_company = RawMaterialCompany::create([
                                    'raw_material_id' => $raw_material_id,
                                    'company_id' => $company_id,
                                    'raw_material_quantity' => 0,
                                    'raw_material_value' => 0,
                                    'raw_material_company_status' => 'active',
                                ]);
                            }

                            $raw_material_company_usage = RawMaterialCompanyUsage::create([
                                'raw_material_id' => $raw_material_id,
                                'raw_material_company_id' => $raw_material_company->raw_material_company_id,
                                'raw_material_company_usage_type' => 'stock in',
                                'raw_material_company_usage_qty' => $supplier_delivery_order_item_qty[$key],
                                'raw_material_company_usage_price_per_qty' => $price_per_qty_after_disc,
                                'raw_material_company_usage_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                                'raw_material_company_usage_total_price' => $price_per_qty_after_disc * $supplier_delivery_order_item_qty[$key],
                                'raw_material_company_usage_total_value' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                                'formula_usage_id' => 0,
                                'formula_usage_item_id' => 0,
                                'unit_price_per_value' => $price_per_qty_after_disc / $supplier_delivery_order_item_value_per_qty[$key],
                                'raw_material_company_usage_total_value_remaining' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                                'is_claim' => 0,
                                'claim_user_id' => '',
                                'raw_material_company_usage_date' => $request->input('supplier_delivery_order_date'),
                                'user_id' => $user->user_id,
                            ]);

                            $raw_material_company_usage_log = RawMaterialCompanyUsageLog::create([
                                'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                                'user_id' => $user->user_id,
                                'raw_material_company_usage_log_created' => now(),
                                'raw_material_company_usage_log_action' => "Edit",
                                'raw_material_company_usage_log_description' => "Multi stock in add new raw material",
                                'raw_material_company_usage_log_value_before' => 0,
                                'raw_material_company_usage_log_value_after' => $supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key],
                            ]);

                            $supplier_do_item = SupplierDeliveryOrderItem::create([
                                'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                                'raw_material_id' => $raw_material_id,
                                'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                                'supplier_delivery_order_item_qty' => $supplier_delivery_order_item_qty[$key],
                                'supplier_delivery_order_item_value_per_qty' => $supplier_delivery_order_item_value_per_qty[$key],
                                'supplier_delivery_order_item_price_per_qty' => $supplier_delivery_order_item_price_per_qty[$key],
                                'supplier_delivery_order_item_disc' => $supplier_delivery_order_item_disc[$key],
                            ]);

                            $raw_material_company_update_details = ([
                                'raw_material_quantity' => $raw_material_company->raw_material_quantity + $supplier_delivery_order_item_qty[$key],
                                'raw_material_value' => $raw_material_company->raw_material_value + ($supplier_delivery_order_item_value_per_qty[$key] * $supplier_delivery_order_item_qty[$key]),
                            ]);

                            $raw_material_company->update($raw_material_company_update_details);
                        }


                        if($remove_raw_materials)
                        {
                            foreach($remove_raw_materials as $key => $raw_material_id)
                            {
                                $delete_supplier_do_item = SupplierDeliveryOrderItem::query()
                                                                                ->where('raw_material_id', $raw_material_id)
                                                                                ->where('supplier_delivery_order_id', $supplier_do->supplier_delivery_order_id)
                                                                                ->first();

                                if(!is_null($delete_supplier_do_item))
                                {
                                    $raw_material_company_usage_id = $delete_supplier_do_item->raw_material_company_usage_id;
                                    $current_supplier_do_item_qty = $delete_supplier_do_item->supplier_delivery_order_item_qty;
                                    $current_supplier_do_item_value_per_qty = $delete_supplier_do_item->supplier_delivery_order_item_value_per_qty;

                                    $raw_material_company_usage = RawMaterialCompanyUsage::find($raw_material_company_usage_id);

                                    if($raw_material_company_usage)
                                    {
                                        $raw_material_company_id = $raw_material_company_usage->raw_material_company_id;
                                        $current_total_value_remaining = $raw_material_company_usage->raw_material_company_usage_total_value_remaining;

                                        $raw_material_company = RawMaterialCompany::find($raw_material_company_id);

                                        if($raw_material_company)
                                        {
                                            $current_raw_material_quantity = $raw_material_company->raw_material_quantity;
                                            $current_raw_material_value = $raw_material_company->raw_material_value;

                                            $raw_material_company->update([
                                                'raw_material_quantity' => $current_raw_material_quantity - $current_supplier_do_item_qty,
                                                'raw_material_value' => $current_raw_material_value - ($current_supplier_do_item_value_per_qty * $current_supplier_do_item_qty),
                                            ]);
                                        }

                                        RawMaterialCompanyUsageLog::create([
                                            'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                                            'user_id' => $user->user_id,
                                            'raw_material_company_usage_log_created' => now(),
                                            'raw_material_company_usage_log_action' => "Edit",
                                            'raw_material_company_usage_log_description' => "Multi stock in delete raw material",
                                            'raw_material_company_usage_log_value_before' => $current_total_value_remaining,
                                            'raw_material_company_usage_log_value_after' => 0,
                                        ]);

                                        $raw_material_company_usage->delete();
                                    }

                                    $delete_supplier_do_item->delete();
                                }
                            }
                        }
                    }

                    $total = $subtotal - $discount;
                    $grandtotal = $total + $request->input('supplier_delivery_order_tax');

                    $supplier_do->update([
                        'supplier_delivery_order_no' => $request->input('supplier_delivery_order_no'),
                        'supplier_delivery_order_subtotal' => $subtotal,
                        'supplier_delivery_order_discount' => $discount,
                        'supplier_delivery_order_total' => $total,
                        'supplier_delivery_order_grandtotal' => $grandtotal,
                        'supplier_delivery_order_date' => $request->input('supplier_delivery_order_date'),
                    ]);

                    $supplier_delivery_order_log = SupplierDeliveryOrderLog::create([
                        'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                        'supplier_delivery_order_log_action' => "Edit",
                        'supplier_delivery_order_log_description' => "Updated by " . auth()->user()->user_fullname,
                        'supplier_delivery_order_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);

                }

                if($request->file('supplier_do_media')){
                    $supplier_do->clearMediaCollection('supplier_delivery_order_media');
                    $supplier_do->addMediaFromRequest('supplier_do_media')->toMediaCollection('supplier_delivery_order_media');
                }

                Session::flash('success_msg', 'Successfully updated supplier delivery order.');
                return redirect()->route('supplier_do_listing');
            }

            $supplier_do = (object) $request->all();
        }

        return view('supplier_delivery_order.form', [
            'submit' => route('supplier_do_edit', $supplier_delivery_order_id),
            'title' => 'Edit',
            'supplier_do' => $supplier_do,
            'company_sel' => Company::get_company_sel(),
            'supplier_sel' => ['' => "Please Select Supplier"] + Supplier::supplier_sel(),
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ])->withErrors($validator);
    }

    public function return_supplier_do(Request $request, $supplier_delivery_order_id)
    {
        $validator = null;
        $supplier_do = SupplierDeliveryOrder::find($supplier_delivery_order_id);

        if($supplier_do == null){
            Session::flash('fail_msg', 'Invalid Supplier Delivery Order, Please Try Again');
            return redirect()->route('supplier_do_listing');
        }

        if($request->isMethod('post')){
            $check_changes = false;

            $validator = Validator::make($request->all(),[
                'return_qty.*' => 'required',
            ])->setAttributeNames([
                'return_qty' => 'Return Quantity',
            ]);

            foreach($request->input('supplier_delivery_order_item_id') as $key => $item){
                if($request->input('return_qty')[$key] > 0)
                    $check_changes = true;
            }

            if(!$check_changes){
                Session::flash('fail_msg', 'Supplier Delivery Order not updated. No changes detected');
                return redirect()->route('supplier_do_listing');
            }

            if(!$validator->fails())
            {
                if(!is_null($request->input('supplier_delivery_order_item_id')))
                {
                    $subtotal = $supplier_do->supplier_delivery_order_subtotal;
                    $discount = $supplier_do->supplier_delivery_order_discount;
                    $total = $supplier_do->supplier_delivery_order_total;
                    $tax = $supplier_do->supplier_delivery_order_tax;

                    foreach($request->input('supplier_delivery_order_item_id') as $key => $supplier_do_item_id)
                    {
                        $return_qty = $request->input('return_qty')[$key];

                        if($return_qty > 0)
                        {
                            $supplier_do_item = SupplierDeliveryOrderItem::find($supplier_do_item_id);

                            if(!is_null($supplier_do_item)){
                                $current_qty = $supplier_do_item->supplier_delivery_order_item_qty;
                                $raw_material_id = $supplier_do_item->raw_material_id;
                                $raw_material_company_usage_id = $supplier_do_item->raw_material_company_usage_id;
                                $price_per_qty = $supplier_do_item->supplier_delivery_order_item_price_per_qty;
                                $value_per_qty = $supplier_do_item->supplier_delivery_order_item_value_per_qty;
                                $supplier_do_item_disc = $supplier_do_item->supplier_delivery_order_item_disc;

                                $disc_per_qty = $supplier_do_item_disc / $current_qty;
                                $price_per_qty_after_disc = $price_per_qty - $disc_per_qty;

                                $subtotal -= $price_per_qty * $return_qty;
                                $discount -= ($supplier_do_item_disc / $current_qty) * $return_qty;
                                $total -= ($price_per_qty - ($supplier_do_item_disc / $current_qty)) * $return_qty;


                                $raw_material_company_usage = RawMaterialCompanyUsage::find($raw_material_company_usage_id);

                                if(!is_null($raw_material_company_usage)){
                                    $current_total_value_remaining = $raw_material_company_usage->raw_material_company_usage_total_value_remaining;
                                    $raw_material_company_id = $raw_material_company_usage->raw_material_company_id;

                                    $raw_material_company = RawMaterialCompany::find($raw_material_company_id);

                                    if(!is_null($raw_material_company)){
                                        $current_raw_material_quantity = $raw_material_company->raw_material_quantity;
                                        $current_raw_material_value = $raw_material_company->raw_material_value;

                                        $new_raw_material_company_usage = RawMaterialCompanyUsage::create([
                                            'raw_material_id' => $raw_material_id,
                                            'raw_material_company_id' => $raw_material_company_id,
                                            'raw_material_company_usage_type' => 'return',
                                            'raw_material_company_usage_qty' => $return_qty,
                                            'raw_material_company_usage_price_per_qty' => $price_per_qty_after_disc,
                                            'raw_material_company_usage_value_per_qty' => $value_per_qty,
                                            'raw_material_company_usage_total_price' => $price_per_qty_after_disc * $return_qty,
                                            'raw_material_company_usage_total_value' => $value_per_qty * $return_qty,
                                            'raw_material_company_usage_date' => date('Y-m-d'),
                                            'user_id' => auth()->user()->user_id,
                                        ]);

                                        $supplier_delivery_order_return = SupplierDeliveryOrderReturn::create([
                                            'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                                            'supplier_delivery_order_item_id' => $supplier_do_item->supplier_delivery_order_item_id,
                                            'supplier_delivery_order_return_qty' => $return_qty,
                                            'raw_material_company_usage_id' => $new_raw_material_company_usage->raw_material_company_usage_id,
                                            'user_id' => auth()->user()->user_id,
                                        ]);

                                        RawMaterialCompanyUsageLog::create([
                                            'raw_material_company_usage_id' => $new_raw_material_company_usage->raw_material_company_usage_id,
                                            'user_id' => auth()->user()->user_id,
                                            'raw_material_company_usage_log_created' => now(),
                                            'raw_material_company_usage_log_action' => "Return",
                                            'raw_material_company_usage_log_description' => "Return Supplier D.O",
                                            'raw_material_company_usage_log_value_before' => 0,
                                            'raw_material_company_usage_log_value_after' => $return_qty * $value_per_qty,
                                        ]);

                                        $raw_material_company->update([
                                            'raw_material_quantity' => $current_raw_material_quantity - $return_qty,
                                            'raw_material_value' => $current_raw_material_value - ($value_per_qty * $return_qty),
                                        ]);

                                        $raw_material_company_usage->update([
                                            'raw_material_company_usage_total_value_remaining' => $current_total_value_remaining - ($value_per_qty * $return_qty),
                                        ]);

                                        RawMaterialCompanyUsageLog::create([
                                            'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                                            'user_id' => auth()->user()->user_id,
                                            'raw_material_company_usage_log_created' => now(),
                                            'raw_material_company_usage_log_action' => "Update",
                                            'raw_material_company_usage_log_description' => "Update raw material company usage total value remaining due to return",
                                            'raw_material_company_usage_log_value_before' => $current_total_value_remaining,
                                            'raw_material_company_usage_log_value_after' => $current_total_value_remaining - ($return_qty * $value_per_qty),
                                        ]);

                                        $supplier_do_item->update([
                                            'supplier_delivery_order_item_qty' => $current_qty - $return_qty,
                                            'supplier_delivery_order_item_disc' => $supplier_do_item_disc - ($disc_per_qty * $return_qty),
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    $grandtotal = $total + $tax;
                    $status = "partially returned";

                    if($total == 0){
                        $grandtotal = $total;
                        $status = "returned";
                    }

                    $supplier_do->update([
                        'supplier_delivery_order_status' => $status,
                        'supplier_delivery_order_subtotal' => $subtotal,
                        'supplier_delivery_order_discount' => $discount,
                        'supplier_delivery_order_total' => $total,
                        'supplier_delivery_order_grandtotal' => $grandtotal,
                    ]);

                    $supplier_delivery_order_log = SupplierDeliveryOrderLog::create([
                        'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                        'supplier_delivery_order_log_action' => "Return",
                        'supplier_delivery_order_log_description' => "Returned by " . auth()->user()->user_fullname,
                        'supplier_delivery_order_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);
                }

                Session::flash('success_msg', 'Successfully returned supplier delivery order.');
                return redirect()->route('supplier_do_listing');
            }
        }

        return view('supplier_delivery_order.supplier_do_return', [
            'submit' => route('supplier_do_return', $supplier_delivery_order_id),
            'title' => 'Return',
            'supplier_do' => $supplier_do,
        ])->withErrors($validator);
    }

    public function delete(Request $request)
    {
        $supplier_delivery_order_id = $request->input('supplier_delivery_order_id');
        $supplier_do = SupplierDeliveryOrder::find($supplier_delivery_order_id);

        if($supplier_do == null){
            Session::flash('fail_msg', 'Invalid Supplier Delivery Order, Please Try Again');
            return redirect()->route('supplier_do_listing');
        }

        $supplier_do_items = SupplierDeliveryOrderItem::query()
                            ->where('supplier_delivery_order_id', $supplier_delivery_order_id)
                            ->get();

        if(!is_null($supplier_do_items))
        {
            //Check if the raw material had been used or not
            foreach($supplier_do_items as $supplier_do_item)
            {
                $raw_material_company_usage_id = $supplier_do_item->raw_material_company_usage_id;
                $supplier_do_item_total_value = $supplier_do_item->supplier_delivery_order_item_qty * $supplier_do_item->supplier_delivery_order_item_value_per_qty;
                $raw_material_company_usage = RawMaterialCompanyUsage::find($raw_material_company_usage_id);

                if($raw_material_company_usage)
                {
                    //If raw material had been used straight return to listing
                    if($raw_material_company_usage->raw_material_company_usage_total_value_remaining != $supplier_do_item_total_value)
                    {
                        Session::flash('fail_msg', 'Insufficient raw material to return.');
                        return redirect()->route('supplier_do_listing');
                    }
                }
            }

            //If raw material had not been used
            foreach($supplier_do_items as $supplier_do_item)
            {
                $raw_material_id = $supplier_do_item->raw_material_id;
                $raw_material_company_usage_id = $supplier_do_item->raw_material_company_usage_id;

                $qty = $supplier_do_item->supplier_delivery_order_item_qty;
                $price_per_qty = $supplier_do_item->supplier_delivery_order_item_price_per_qty;
                $value_per_qty = $supplier_do_item->supplier_delivery_order_item_value_per_qty;
                $disc = $supplier_do_item->supplier_delivery_order_item_disc;
                $disc_per_qty = $disc / $qty;
                $price_per_qty_after_disc = $price_per_qty - $disc_per_qty;

                $raw_material_company_usage = RawMaterialCompanyUsage::find($raw_material_company_usage_id);

                if(!is_null($raw_material_company_usage))
                {
                    $current_total_value_remaining = $raw_material_company_usage->raw_material_company_usage_total_value_remaining;
                    $raw_material_company_id = $raw_material_company_usage->raw_material_company_id;

                    $raw_material_company = RawMaterialCompany::find($raw_material_company_id);

                    if(!is_null($raw_material_company))
                    {
                        $current_raw_material_company_qty = $raw_material_company->raw_material_quantity;
                        $current_raw_material_company_value = $raw_material_company->raw_material_value;

                        $new_raw_material_company_usage = RawMaterialCompanyUsage::create([
                            'raw_material_id' => $raw_material_id,
                            'raw_material_company_id' => $raw_material_company_id,
                            'raw_material_company_usage_type' => 'delete',
                            'raw_material_company_usage_qty' => $qty,
                            'raw_material_company_usage_price_per_qty' => $price_per_qty_after_disc,
                            'raw_material_company_usage_value_per_qty' => $value_per_qty,
                            'raw_material_company_usage_total_price' => $price_per_qty_after_disc * $qty,
                            'raw_material_company_usage_total_value' => $value_per_qty * $qty,
                            'raw_material_company_usage_date' => date('Y-m-d'),
                            'user_id' => auth()->user()->user_id,
                        ]);

                        RawMaterialCompanyUsageLog::create([
                            'raw_material_company_usage_id' => $new_raw_material_company_usage->raw_material_company_usage_id,
                            'user_id' => auth()->user()->user_id,
                            'raw_material_company_usage_log_created' => now(),
                            'raw_material_company_usage_log_action' => "Delete",
                            'raw_material_company_usage_log_description' => "Delete Supplier D.O",
                            'raw_material_company_usage_log_value_before' => 0,
                            'raw_material_company_usage_log_value_after' => $qty * $value_per_qty,
                        ]);

                        $raw_material_company->update([
                            'raw_material_quantity' => $current_raw_material_company_qty - $qty,
                            'raw_material_value' => $current_raw_material_company_value - ($value_per_qty * $qty),
                        ]);

                        $raw_material_company_usage->update([
                            'raw_material_company_usage_total_value_remaining' => $current_total_value_remaining - ($value_per_qty * $qty),
                        ]);

                        RawMaterialCompanyUsageLog::create([
                            'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                            'user_id' => auth()->user()->user_id,
                            'raw_material_company_usage_log_created' => now(),
                            'raw_material_company_usage_log_action' => "Update",
                            'raw_material_company_usage_log_description' => "Update raw material company usage total value remaining due to deletion of Supplier D.O",
                            'raw_material_company_usage_log_value_before' => $current_total_value_remaining,
                            'raw_material_company_usage_log_value_after' => $current_total_value_remaining - ($qty * $value_per_qty),
                        ]);
                    }
                }
            }

            $supplier_do->update([
                'supplier_delivery_order_status' => 'deleted',
            ]);

            $supplier_delivery_order_log = SupplierDeliveryOrderLog::create([
                'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                'supplier_delivery_order_log_action' => "Delete",
                'supplier_delivery_order_log_description' => "Deleted by " . auth()->user()->user_fullname,
                'supplier_delivery_order_log_created' => now(),
                'user_id' => auth()->user()->user_id,
            ]);
        }

        Session::flash('success_msg', 'Successfully deleted supplier delivery order');
        return redirect()->route('supplier_do_listing');
    }
}

?>
