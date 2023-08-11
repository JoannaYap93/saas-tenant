<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\RawMaterialCompany;
use App\Model\RawMaterialCompanyUsage;
use App\Model\RawMaterialCompanyUsageLog;
use App\Model\RunningNumber;
use App\Model\SettingRawMaterial;
use App\Model\SettingRawMaterialCategory;
use App\Model\Supplier;
use App\Model\SupplierDeliveryOrder;
use App\Model\SupplierDeliveryOrderItem;
use App\Model\SupplierDeliveryOrderLog;
use App\Model\SupplierDeliveryOrderReturn;
use App\Model\Worker;
use App\Model\WorkerWalletHistory;
// use App\Model\Product;
// use App\Model\ProductCategory;
// use App\Model\ProductStatus;
// use App\Model\ProductTag;
// use App\Model\ProductTagLink;
// use App\Model\ProductStockWarehouse;
// use App\Model\SettingWarehouse;
// use App\MOdel\SettingSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RawMaterialCompanyUsageController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $user = Auth::user();
        $search = array();
        $claim_user = [];
        // $setting_warehouse = SettingWarehouse::get_warehouse_sel_by_company($user->company_id);
        // $product_id = Product::get_product_sel_for_stock_transfer();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['raw_material_company_usage_search' => [
                        'freetext' => $request->input('freetext'),
                        'company_id' => $request->input('company_id'),
                        'company_land_id' => $request->input('company_land_id'),
                        'user_id' => $request->input('user_id'),
                        'raw_material_id' => $request->input('raw_material_id'),
                        'raw_material_company_usage_id' => $request->input('raw_material_company_usage_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('raw_material_company_usage_search');
                    break;
            }
        }
        $search = session('raw_material_company_usage_search') ?? $search;

        $raw_material_company_usage = RawMaterialCompanyUsage::get_records($search);

        foreach($raw_material_company_usage as $rmcu){
            $claim_user[$rmcu->raw_material_company_usage_id] = Worker::find($rmcu->claim_worker_id);
        }

        $search = session('raw_material_company_usage_search') ?? array();
        return view('raw_material_company_usage.listing', [
            'submit' => route('raw_material_company_usage_listing', ['tenant' => tenant('id')]),
            'records' => $raw_material_company_usage,
            'claim_user' => $claim_user,
            // 'status' => ProductStatus::get_records(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'usage_type_sel' => ['' => 'Please Select Type', 'stock in' => 'Stock In', 'usage' => 'Usage'],
            'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'manager_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_land_sel(),
            'supplier_sel' => ['' => "Please Select Supplier"] + Supplier::supplier_sel(),
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ]);
    }

    public function stock_in(Request $request)
    {
        if($request->input('raw_material_company_id')){
            $raw_material_company = RawMaterialCompany::find($request->input('raw_material_company_id'));
            if($raw_material_company){
                $company_id = (auth()->user()->company_id == 0 ? $request->input('stock_in_company_id') : auth()->user()->company_id);
                $company = Company::find($company_id);
                $user = auth()->user();

                $supplier_do = SupplierDeliveryOrder::query()
                                                    ->where('supplier_delivery_order_no', $request->input('supplier_delivery_order_no'))
                                                    ->where('supplier_id', $request->input('supplier_id'))
                                                    ->where('company_id', $company_id)
                                                    ->first();
                if(!is_null($supplier_do)){
                    $current_sdo_subtotal = $supplier_do->supplier_delivery_order_subtotal;
                    $current_sdo_discount = $supplier_do->supplier_delivery_order_discount;
                    $current_sdo_total = $supplier_do->supplier_delivery_order_total;
                    $current_sdo_tax = $supplier_do->supplier_delivery_order_tax;
                    $current_sdo_grandtotal = $supplier_do->supplier_delivery_order_total;

                    $new_sdo_subtotal = $current_sdo_subtotal + ($request->input('supplier_delivery_order_item_qty') * $request->input('supplier_delivery_order_item_price_per_qty'));
                    $new_sdo_total = $new_sdo_subtotal - $current_sdo_discount;
                    $new_sdo_grandtotal = $new_sdo_total + $current_sdo_tax;

                    $supplier_do->update([
                        'supplier_delivery_order_subtotal' => $new_sdo_subtotal,
                        'supplier_delivery_order_total' => $new_sdo_total,
                        'supplier_delivery_order_grandtotal' => $new_sdo_grandtotal,
                    ]);

                    SupplierDeliveryOrderLog::create([
                        'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                        'supplier_delivery_order_log_action' => "Add",
                        'supplier_delivery_order_log_description' => "Stock in by" . auth()->user()->user_fullname,
                        'supplier_delivery_order_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);

                    $supplier_do_item = SupplierDeliveryOrderItem::query()
                                                                ->where('supplier_delivery_order_id', $supplier_do->supplier_delivery_order_id)
                                                                ->where('raw_material_id', $request->input('stock_in_raw_material_id'))
                                                                ->first();

                    if(!is_null($supplier_do_item)){
                        Session::flash("fail_msg", "Supplier Delivery Order Item exist. Please update from supplier delivery order listing");
                        return redirect()->route('raw_material_company_usage_listing', ['tenant' => tenant('id')]);
                    }

                }else{
                    $running_no = RunningNumber::get_running_code('supplier_delivery_order');

                    $supplier_do = SupplierDeliveryOrder::create([
                        'supplier_delivery_order_no' => $request->input('supplier_delivery_order_no'),
                        'supplier_delivery_order_running_no' => 'SDO/' . $company->company_code . '/' . $user->user_unique_code . '/' . $running_no,
                        'supplier_delivery_order_subtotal' => $request->input('supplier_delivery_order_item_qty') * $request->input('supplier_delivery_order_item_price_per_qty'),
                        'supplier_delivery_order_discount' => 0,
                        'supplier_delivery_order_total' => $request->input('supplier_delivery_order_item_qty') * $request->input('supplier_delivery_order_item_price_per_qty'),
                        'supplier_delivery_order_tax' => 0,
                        'supplier_delivery_order_grandtotal' => $request->input('supplier_delivery_order_item_qty') * $request->input('supplier_delivery_order_item_price_per_qty'),
                        'supplier_delivery_order_status' => 'completed',
                        'supplier_delivery_order_date' => $request->input('raw_material_company_usage_date'),
                        'supplier_id' => $request->input('supplier_id'),
                        'company_id' => $company_id,
                        'user_id' => $user->user_id,
                    ]);

                    SupplierDeliveryOrderLog::create([
                        'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                        'supplier_delivery_order_log_action' => "Add",
                        'supplier_delivery_order_log_description' => "Stock in by" . auth()->user()->user_fullname,
                        'supplier_delivery_order_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);
                }

                $raw_material_company_usage = RawMaterialCompanyUsage::create([
                    'raw_material_id' => $request->input('stock_in_raw_material_id'),
                    'raw_material_company_id' => $request->input('raw_material_company_id'),
                    'raw_material_company_usage_type' => 'stock in',
                    'raw_material_company_usage_qty' => $request->input('raw_material_company_usage_qty'),
                    'raw_material_company_usage_price_per_qty' => $request->input('raw_material_company_usage_price_per_qty'),
                    'raw_material_company_usage_value_per_qty' => $request->input('raw_material_company_usage_value_per_qty'),
                    'raw_material_company_usage_total_price' => $request->input('raw_material_company_usage_total_price'),
                    'raw_material_company_usage_total_value' => $request->input('raw_material_company_usage_total_value'),
                    'user_id' => auth()->user()->user_id,
                    'formula_usage_id' => 0,
                    'formula_usage_item_id' => 0,
                    'unit_price_per_value' => $request->input('raw_material_company_usage_price_per_qty') / $request->input('raw_material_company_usage_value_per_qty'),
                    'raw_material_company_usage_total_value_remaining' => $request->input('raw_material_company_usage_total_value'),
                    'is_claim' => $request->input('is_claim') ? $request->input('is_claim') : 0,
                    'claim_worker_id' => $request->input('claim_worker_id') ? $request->input('claim_worker_id') : '',
                    'raw_material_company_usage_date' => $request->input('raw_material_company_usage_date'),
                ]);

                RawMaterialCompanyUsageLog::create([
                    'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                    'user_id' => auth()->user()->user_id,
                    'raw_material_company_usage_log_action' => 'Add',
                    'raw_material_company_usage_log_created' => now(),
                    'raw_material_company_usage_log_description' => 'Stock in.',
                    'raw_material_company_usage_log_value_before' => 0,
                    'raw_material_company_usage_log_value_after' => $request->input('raw_material_company_usage_total_value'),
                ]);

                $supplier_do_item = SupplierDeliveryOrderItem::create([
                    'supplier_delivery_order_id' => $supplier_do->supplier_delivery_order_id,
                    'raw_material_id' => $request->input('stock_in_raw_material_id'),
                    'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                    'supplier_delivery_order_item_qty' => $request->input('supplier_delivery_order_item_qty'),
                    'supplier_delivery_order_item_value_per_qty' => $request->input('supplier_delivery_order_item_value_per_qty'),
                    'supplier_delivery_order_item_price_per_qty' => $request->input('supplier_delivery_order_item_price_per_qty'),
                    'supplier_delivery_order_item_disc_per_qty' => 0,
                ]);

                if($raw_material_company_usage->is_claim){
                    WorkerWalletHistory::update_worker_wallet_history($raw_material_company_usage->claim_worker_id, 'raw_material_company_usage_id', $raw_material_company_usage->raw_material_company_usage_id, auth()->user()->user_id);
                }

                $raw_material_company->update([
                    'raw_material_quantity' => $request->input('raw_material_qty'),
                    'raw_material_value' => $request->input('raw_material_value'),
                ]);

                Session::flash('success_msg', 'Stock In Successfully!');
                return redirect()->route('raw_material_company_usage_listing', ['tenant' => tenant('id')]);
            }
            Session::flash('fail_msg', 'Raw Material Company Not Found!');
            return redirect()->route('raw_material_company_usage_listing', ['tenant' => tenant('id')]);
        }
        Session::flash('fail_msg', 'Something Went Wrong...');
        return redirect()->route('raw_material_company_usage_listing', ['tenant' => tenant('id')]);
    }

    public function delete(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = Product::find($product_id);

        if ($product) {
            $product->update([
                'product_updated' => now(),
                'is_deleted' => 1
            ]);
            Session::flash('success_msg', 'Deleted successfully!');
            return redirect()->route('product_listing', ['tenant' => tenant('id')]);
        } else {
            Session::flash('fail_msg', 'Product not found!');
            return redirect()->route('product_listing', ['tenant' => tenant('id')]);
        }
    }

    public function view_raw_material_company_usage_listing($supplier_delivery_order_id)
    {
        $raw_material_company_usage_ids = array();
        $sdoi_result = SupplierDeliveryOrderItem::query()->where('supplier_delivery_order_id', $supplier_delivery_order_id)->pluck('raw_material_company_usage_id')->toArray();
        $sdor_result = SupplierDeliveryOrderReturn::query()->where('supplier_delivery_order_id', $supplier_delivery_order_id)->pluck('raw_material_company_usage_id')->toArray();
        $array_size = max(sizeof($sdoi_result), sizeof($sdor_result));

        for($i = 0; $i < $array_size; $i++)
        {
            if(isset($sdoi_result[$i])){
                array_push($raw_material_company_usage_ids, $sdoi_result[$i]);
            }

            if(isset($sdor_result[$i])){
                array_push($raw_material_company_usage_ids, $sdor_result[$i]);
            }
        }

        $search['raw_material_company_usage_id'] = $raw_material_company_usage_ids;
        Session::put('raw_material_company_usage_search', $search);
        return redirect()->route('raw_material_company_usage_listing', ['tenant' => tenant('id')]);
    }

    public function ajax_get_existing_raw_material_company(Request $request)
    {
        $company_id = $request->input('company_id');
        // $company_land_id = $request->input('company_land_id');
        $raw_material_id = $request->input('raw_material_id');
        //
        // $company_id = 1;
        // $company_land_id = 1;
        // $raw_material_id = 1;
        $result = RawMaterialCompany::get_by_company_land($company_id, $raw_material_id);
        // dd($result);
        return $result;
    }

    public function ajax_check_existing_supplier_delivery_order_items(Request $request)
    {
        $supplier_id = $request->input('supplier_id');
        $company_id = $request->input('company_id');
        $supplier_delivery_order_no = $request->input('supplier_delivery_order_no');
        $raw_material_id = $request->input('raw_material_id');
        $result = SupplierDeliveryOrder::check_existing_supplier_delivery_order_items($supplier_id, $company_id, $supplier_delivery_order_no, $raw_material_id);
        return $result;
    }
}
