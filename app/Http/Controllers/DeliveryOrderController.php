<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\User;
use App\Model\Media;
use App\Model\Collect;
use App\Model\Company;
use App\Model\Invoice;
use App\Model\Product;
use App\Model\Setting;
use App\Model\Customer;
use App\Model\InvoiceLog;
use App\Model\CompanyLand;
use App\Model\CustomerPIC;
use App\Model\SettingSize;
use Illuminate\Support\Str;
use App\Model\DeliveryOrder;
use App\Model\RunningNumber;
use Illuminate\Http\Request;
use App\Model\SettingExpense;
use App\Model\SettingPayment;
use App\Model\MessageTemplate;
use App\Model\ProductCategory;
use App\Model\ProductSizeLink;
use App\Model\CustomerCategory;
use App\Model\DeliveryOrderLog;
use App\Model\SettingWarehouse;
use App\Model\DeliveryOrderItem;
use App\Model\DeliveryOrderType;
use App\Model\DeliveryOrderStatus;
use App\Model\DeliveryOrderExpense;
use App\Model\ProductStockTransfer;
use App\Exports\DeliveryOrderExport;
use App\Model\ProductStockWarehouse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DeliveryOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['view_pdf']]);
    }

    public function listing(Request $request)
    {
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_do' => [
                        'freetext' => $request->input('freetext'),
                        'company' => $request->input('company'),
                        'do_from' => $request->input('do_from'),
                        'do_to' => $request->input('do_to'),
                        'order_type_id' => $request->input('order_type_id'),
                        'order_status_id' => $request->input('order_status_id'),
                        'warehouse_id' => $request->input('warehouse_id'),
                        'company_land_id' => $request->input('company_land_id'),
                        'product_category_id' => $request->input('product_category_id'),
                        'product_id' => $request->input('product_id'),
                        'product_size_id' => $request->input('product_size_id'),
                        'delivery_no' => $request->input('delivery_no'),
                        'delivery_order_id' => $request->input('delivery_order_id'),
                        'user_id' => $request->input('user_id'),
                        'customer_id' => $request->input('customer_id'),
                        'customer_category_id' => $request->input('customer_category_id'),
                        'customer_company' => $request->input('customer_company'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_do');
                    break;
                case 'export':
                    $search['freetext'] = $request->input('freetext');
                    $search['company'] = $request->input('company');
                    $search['do_from'] = $request->input('do_from');
                    $search['do_to'] = $request->input('do_to');
                    $search['order_type_id'] = $request->input('order_type_id');
                    $search['order_status_id'] = $request->input('order_status_id');
                    $search['warehouse_id'] = $request->input('warehouse_id');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['product_category_id'] = $request->input('product_category_id');
                    $search['product_id'] = $request->input('product_id');
                    $search['product_size_id'] = $request->input('product_size_id');
                    $search['delivery_no'] = $request->input('delivery_no');
                    $search['delivery_order_id'] = $request->input('delivery_order_id');
                    $search['user_id'] = $request->input('user_id');
                    $search['customer_id'] = $request->input('customer_id');
                    $search['customer_company'] = $request->input('customer_company');

                    $do = DeliveryOrder::get_records($search);
                    Session::put('listing', $search);
                    return Excel::download(new DeliveryOrderExport('export/do_export', $do, $search), 'DO_Export.xlsx');
                    break;
            }
        }
        $search = session('filter_do') ?? array();
        $do = DeliveryOrder::get_records($search);
        $company = Company::get_company_sel();
        $order_type = DeliveryOrderType::get_sel();
        $do_status = DeliveryOrderStatus::get_all_sel();
        $warehouse = SettingWarehouse::get_warehouse_sel();
        $message_templates = MessageTemplate::get_by_slug('do-listing');

        $ids = [1, 4, 2, 5, 3];
        $do_sorted = $do_status->sortBy(function($model) use ($ids){
            return array_search($model->getKey(), $ids);
        });

        return view('delivery_order.listing', [
            'records' => $do,
            'submit' => route('do_listing'),
            'company' => $company,
            'search' => $search,
            'order_type' => $order_type,
            'do_status' => $do_sorted,
            'message_templates' => $message_templates,
            'count_status' => DeliveryOrder::count_status($search, $do_status),
            'warehouse' => $warehouse,
            'company_land' => CompanyLand::get_company_land_sel(),
            'product' => Product::get_by_company(),
            'expense' => SettingExpense::get_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $user = auth()->user();

        if (Product::get_product_company() == false) {
            Session::flash('fail_msg', 'Please add a product before continue');
            return redirect()->route('do_listing');
        }

        $do = null;
        $validation = null;
        $collect_count = DeliveryOrderItem::get_collect_no();
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'customer_ic' => 'required_if:delivery_order_type_id,1',
                'customer_mobile' => 'required_if:delivery_order_type_id,1',
                'customer_name' => 'required_if:delivery_order_type_id,1',
                'customer_mobile_no' =>'required_if:delivery_order_type_id,1',
                'delivery_order_type_id' => 'required',
                'expense' => 'nullable:delivery_order_type_id,2',
                'product_id' => 'required|array|min:1',
                'product_size.*' => 'required',
                'product_weight.*' => 'required',
                'email' => 'required_if:customer_id,null',
                'address1' => 'required_if:customer_id,null',
                'state' => 'required_if:customer_id,null',
                'postcode' => 'required_if:customer_id,null',
                'city' => 'required_if:customer_id,null',
                'country' => 'required_if:customer_id,null',
                'company_land_id' => 'required',
                'warehouse_id' => $request->input('customer_id') != null ? 'required_if:delivery_order_type_id,2' : 'nullable',
                'collect_no.*' => $user->company->company_force_collect ? 'required' : 'nullable',
                'no_collect_code.*' => 'nullable',
                'delivery_order_remark' => 'nullable',
                'delivery_order_created' => 'required',
                // 'expense_value_1' => 'required_if:expense,1',
                // 'expense_value_2' => 'required_if:expense,2',
            ])->setAttributeNames([
                'customer_ic' => 'Customer IC',
                'customer_mobile' => 'Customer Mobile',
                'customer_name' => 'Customer Name',
                'customer_mobile_no' => 'PIC Mobile',
                'delivery_order_type_id' => 'Order Type',
                'expense' => 'Expenses',
                'product_id' => 'Item Details',
                'product_size.*' => 'Item Size',
                'product_weight.*' => 'Item Weight',
                'email' => 'Email',
                'address1' => 'Address 1',
                'state' => 'State',
                'postcode' => 'Postcode',
                'city' => 'City',
                'country' => 'Country',
                'company_land_id' => 'Company Land',
                'warehouse_id' => 'Warehouse',
                'collect_no.*' => 'Collect Code',
                'no_collect_code.*' => 'No Collect Code',
                'delivery_order_remark' => "Delivery Order Remark",
                'delivery_order_created' => 'Delivery Order Created',
                // 'expense_value_1' => 'Rental Fee',
                // 'expense_value_2' => 'Fronzen Fee',
            ]);

            if (!$validation->fails()) {
                if ($user->company_id != 0) {
                    $company = Company::find($user->company_id);
                } else {
                    $company = Company::find($request->input('company_id'));
                }

                $customer = Customer::query()->where('customer_id', $request->input('customer_id'))->first();
                $warehouse = $request->input('warehouse_id');
                if (!$customer) {
                    $user_mobile = $request->input('customer_mobile');
                    if (substr($user_mobile, 0, 1) == '0') {
                        $profile_mobile = '6' . $user_mobile;
                    } elseif (substr($user_mobile, 0, 1) == '1') {
                        $profile_mobile = "60" . $user_mobile;
                    } elseif (substr($user_mobile, 0, 3) == '600') {
                        $profile_mobile = "6" . substr($user_mobile, strpos($user_mobile, '600') + 2);
                    } else {
                        $profile_mobile = $user_mobile;
                    }

                    if (count(CustomerCategory::query()->where('company_id', $user->company_id)->get()) > 0) {
                        $category = CustomerCategory::where('company_id', $user->company_id)->first();
                    } else {
                        $category = CustomerCategory::create([
                            'customer_category_name' => 'Category',
                            'customer_category_created' => now(),
                            'customer_category_updated' => now(),
                            'customer_category_status' => 'active',
                            'is_deleted' => 0,
                            'customer_category_priority' => 1,
                            'company_id' => $user->company_id
                        ]);
                    }

                    if ($request->input('delivery_order_type_id') == 2) {
                        if ($request->input('warehouse_id')) {
                            $warehouse = $request->input('warehouse_id');
                        } else {
                            $warehouse = SettingWarehouse::create([
                                'warehouse_name' => $request->input('warehouse_name'),
                                'warehouse_status' => 'active',
                                'warehouse_ranking' => 1,
                                'company_id' => $user->company_id,
                                'warehouse_cdate' => now(),
                                'warehouse_udate' => now()
                            ]);
                            $warehouse = $warehouse->warehouse_id;
                        }
                    } else {
                        $warehouse = 0;
                    }

                    $customer = Customer::create([
                        'customer_company_name' => $company->company_name,
                        'customer_name' => $request->input('cname'),
                        'customer_mobile_no' => $profile_mobile,
                        'customer_address' => $request->input('address1'),
                        'customer_address2' => $request->input('address2') ?? '',
                        'customer_state' => $request->input('state'),
                        'customer_city' => $request->input('city'),
                        'customer_postcode' => $request->input('postcode'),
                        'customer_email' => $request->input('email'),
                        'customer_code' => Str::random(5),
                        'customer_country' => $request->input('country'),
                        'customer_created' => now(),
                        'customer_updated' => now(),
                        'company_id' => $company->company_id,
                        'customer_category_id' => $category->customer_category_id,
                        'warehouse_id' => $warehouse
                    ]);
                }

                $pic = CustomerPIC::get_pic_cid($request->input('customer_ic'), $customer->customer_id);

                $pic_mobile_check = $request->input('customer_mobile_no');

                if (substr($pic_mobile_check, 0, 1) == '0') {
                    $pic_mobile = '6' . $pic_mobile_check;
                } elseif (substr($pic_mobile_check, 0, 1) == '1') {
                    $pic_mobile = "60" . $pic_mobile_check;
                } elseif (substr($pic_mobile_check, 0, 3) == '600') {
                    $pic_mobile = "6" . substr($pic_mobile_check, strpos($pic_mobile_check, '600') + 2);
                } else {
                    $pic_mobile = $pic_mobile_check;
                }

                if (!$pic) {
                    CustomerPIC::create([
                        'customer_pic_name' => $request->input('customer_name'),
                        'customer_pic_created' => now(),
                        'customer_pic_updated' => now(),
                        'customer_pic_mobile_no' => $pic_mobile,
                        'customer_pic_ic' => $request->input('customer_ic'),
                        'customer_id' => $request->input('customer_id') ?? $customer->customer_id,
                    ]);
                }

                $total_weight = 0;
                foreach ($request->input('product_weight') as $key => $weight) {
                    $total_weight += $weight;
                }

                $running_no = RunningNumber::get_running_code('delivery_order');

                $new_do = DeliveryOrder::create([
                    'delivery_order_no' =>  'DO/' . $company->company_code . '/' . $user->user_unique_code . '/' . $running_no,
                    'delivery_order_created' => $request->input('delivery_order_created') . ' ' . date("H:i:s"),
                    'delivery_order_updated' => now(),
                    'customer_id' => $request->input('customer_id') ?? @$customer->customer_id,
                    'customer_ic' => $request->input('customer_ic'),
                    'customer_name' => $request->input('customer_name'),
                    'customer_mobile_no' => $pic_mobile,
                    'delivery_order_total_quantity' => $total_weight,
                    'delivery_order_status_id' => 2,
                    'company_id' => $company->company_id,
                    'company_land_id' => $request->input('company_land_id'),
                    'delivery_order_type_id' => $request->input('delivery_order_type_id'),
                    'user_id' => $user->user_id,
                    'warehouse_id' => $warehouse ?? null,
                    'delivery_order_remark' => $request->input('delivery_order_remark'),
                ]);

                if ($request->input('delivery_order_type_id') == 2) {
                    if (@$request->input('expense') && count($request->input('expense')) > 0) {

                        $expense = $request->input('expense');
                        foreach ($expense as $key => $exp) {
                            $exp_id = $request->input('expense')[$key];
                            $value = $request->input('setting_expense_value')[$exp];
                            $kg = $request->input('expense_kg')[$exp] ?? 0;
                            $day = $request->input('expense_day')[$exp] ?? 0;

                            if($kg != 0 && $day != 0){
                              $total = ($value * $kg) * $day;
                            }elseif($kg != 0 && $day == 0){
                              $total = $value * $kg;
                            }elseif($kg == 0 && $day != 0){
                              $total = $value * $day;
                            }else{
                              $total = $value;
                            }

                            DeliveryOrderExpense::create([
                              'delivery_order_expense_value' => $request->input('setting_expense_value')[$exp],
                              'delivery_order_expense_kg' => $request->input('expense_kg')[$exp] ?? 0,
                              'delivery_order_expense_day' => $request->input('expense_day')[$exp] ?? 0,
                              'delivery_order_expense_total' => $total,
                                'setting_expense_id' => $exp,
                                'delivery_order_id' => $new_do->delivery_order_id,
                                'delivery_order_expense_created' => now(),
                                'delivery_order_expense_updated' => now()
                            ]);
                        }
                    }
                }

                foreach ($request->input('product_id') as $key => $product_id) {
                    $delivery_order_item = DeliveryOrderItem::create([
                        'delivery_order_id' => $new_do->delivery_order_id,
                        'product_id' => $product_id,
                        'setting_product_size_id' => $request->input('product_size')[$key],
                        'delivery_order_item_quantity' => $request->input('product_weight')[$key],
                        'delivery_order_item_created' => now(),
                        'delivery_order_item_updated' => now(),
                        'delivery_order_item_collect_no' => $request->input('collect_no')[$key] ?? '',
                        'no_collect_code' => $request->input('no_collect_code')[$key] ?? 0,
                    ]);

                    if(@$request->file('delivery_order_item_media_' . $product_id . '_' . $key)){
                        $files = $request->file('delivery_order_item_media_' . $product_id . '_' . $key);

                        foreach($files as $keyfile => $file)
                        {
                            $delivery_order_item->addMedia($file)->toMediaCollection('delivery_order_item_media');
                        }
                    }

                    // @dd($request->file());
                    // if($request->file('delivery_order_item_media_' . $product_id . '_' . $key)){
                    //     foreach
                    //     $delivery_order_item->addMediaFromRequest('delivery_order_item_media_' . $product_id . '_' . $key)->toMediaCollection('delivery_order_item_media');
                    // }

                    if ($request->input('delivery_order_type_id') == 2) {

                        $product_stk_transfer_w = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse, $product_id, $request->input('product_size')[$key]);

                        if ($product_stk_transfer_w) {

                            $update_warehse = ProductStockWarehouse::find($product_stk_transfer_w);
                            $qty_atfer = $request->input('product_weight')[$key] + $update_warehse->product_stock_warehouse_qty_current;
                            ProductStockTransfer::create([
                                'product_stock_transfer_remark' => 'Transfer',
                                'product_stock_transfer_description' =>  '#' . $new_do->delivery_order_no ?? '',
                                'product_id' => $product_id,
                                'product_stock_warehouse_id' => $product_stk_transfer_w,
                                'setting_product_size_id' => $request->input('product_size')[$key],
                                'product_stock_transfer_qty_before' => $update_warehse->product_stock_warehouse_qty_current ?? 0,
                                'product_stock_transfer_qty_after' => $qty_atfer,
                                'product_stock_transfer_qty' => $request->input('product_weight')[$key],
                            ]);

                            $update_warehse->update([
                                'product_stock_warehouse_qty_current' => $qty_atfer,
                            ]);
                        } else {

                            $new_product_stk_warehse = ProductStockWarehouse::create([
                                'warehouse_id' => $warehouse,
                                'product_id' => $product_id,
                                'setting_product_size_id' => $request->input('product_size')[$key],
                                'product_stock_warehouse_qty_current' => $request->input('product_weight')[$key],
                            ]);

                            ProductStockTransfer::create([
                                'product_stock_transfer_remark' => 'Transfer',
                                'product_stock_transfer_description' =>  '#' . $new_do->delivery_order_no ?? '',
                                'product_id' => $product_id,
                                'product_stock_warehouse_id' => $new_product_stk_warehse->product_stock_warehouse_id,
                                'setting_product_size_id' => $request->input('product_size')[$key],
                                'product_stock_transfer_qty_before' => 0,
                                'product_stock_transfer_qty_after' => $request->input('product_weight')[$key],
                                'product_stock_transfer_qty' => $request->input('product_weight')[$key],
                            ]);
                        }
                    }
                }

                DeliveryOrderLog::insert([
                    'delivery_order_id' => $new_do->delivery_order_id,
                    'delivery_order_log_created' => now(),
                    'delivery_order_log_action' => 'Add',
                    'delivery_order_log_description' => 'Order Added By ' . $user->user_fullname,
                    'user_id' => Auth::id()
                ]);

                session()->forget('filter_do');

                Session::flash('success_msg', 'Added new D.O. successfully!');
                return redirect()->route('do_listing');
            }
            $do = (object) $request->all();
            $i= 0;
            if (@$do->product_id) {
                foreach ($do->product_id as $pid) {
                    $do->product[$i] = Product::get_by_id($pid);
                    $i++;
                }
            }
        }

        $order_type = DeliveryOrderType::get_sel();
        $expense = SettingExpense::get_sel();
        $land = CompanyLand::get_company_land_sel();
        $warehouse = SettingWarehouse::get_warehouse_sel();
        return view('delivery_order.form', [
            'type' => 'Add',
            'submit' => route('do_add'),
            'order_type' => $order_type,
            'records' => $do,
            'expense' => $expense,
            'collect_count' => $collect_count,
            'land' => $land,
            'warehouse' => $warehouse,
            'company' => Company::get_company_sel(),
            'product' => Product::get_product_company()
        ])->withErrors($validation);
    }

    public function edit(Request $request, $id)
    {

        $old_doe = DeliveryOrderExpense::where('delivery_order_id', $id)->pluck('setting_expense_id')->toArray();

        $user = auth()->user();
        $do = DeliveryOrder::query()->where('delivery_order_id', $id);

        if ($user->company_id != 0) {
            $do = $do->where('company_id', $user->company_id)->first();
        } else {
            $do = $do->first();
        }

        if ($do == null) {
            Session::flash('fail_msg', 'Invalid Delivery Order');
            return redirect()->route('do_listing');
        }

        $check_do = DeliveryOrder::find($id);

        $validation = null;
        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'customer_ic' => 'required_if:delivery_order_type_id,1',
                'customer_mobile' => 'required_if:delivery_order_type_id,1',
                'customer_name' => 'required_if:delivery_order_type_id,1',
                'customer_mobile_no' => 'required_if:delivery_order_type_id,1',
                'delivery_order_type_id' => 'required',
                'expense' => 'nullable:delivery_order_type_id,2',
                // 'product_id' => 'required|array|min:1',
                // 'product_size.*' => 'required',
                // 'product_weight.*' => 'required',
                'email' => 'required_if:customer_id,null',
                'address1' => 'required_if:customer_id,null',
                'state' => 'required_if:customer_id,null',
                'postcode' => 'required_if:customer_id,null',
                'city' => 'required_if:customer_id,null',
                'country' => 'required_if:customer_id,null',
                'company_land_id' => 'required',
                'warehouse_id' => $request->input('customer_id') != null ? 'required_if:delivery_order_type_id,2' : 'nullable',
                'collect_no.*' => $user->company->company_force_collect ? 'required' : '',
                'no_collect_code.*' => 'nullable',
                'delivery_order_remark' => 'nullable',
                'delivery_order_created' => 'required',
                // 'delivery_order_item_id' => 'required',
                // 'expense_value_1' => 'required_if:expense,1',
                // 'expense_value_2' => 'required_if:expense,2',
            ])->setAttributeNames([
                'customer_ic' => 'Customer IC',
                'customer_mobile' => 'Customer Mobile',
                'customer_name' => 'Customer Name',
                'customer_mobile_no' => 'PIC Mobile',
                'delivery_order_type_id' => 'Order Type',
                'expense' => 'Expenses',
                // 'product_id' => 'Item Details',
                // 'product_size.*' => 'Item Size',
                // 'product_weight.*' => 'Item Weight',
                'email' => 'Email',
                'address1' => 'Address 1',
                'state' => 'State',
                'postcode' => 'Postcode',
                'city' => 'City',
                'country' => 'Country',
                'company_land_id' => 'Company Land',
                'warehouse_id' => 'Warehouse',
                'collect_no.*' => 'Collect Code',
                'no_collect_code.*' => 'No Collect Code',
                'delivery_order_remark' => 'Delivery Order Remark',
                'delivery_order_created' => 'Delivery Order Created',
                // 'delivery_order_item_id' => 'Delivery Order Item',
                // 'expense_value_1' => 'Rental Fee',
                // 'expense_value_2' => 'Fronzen Fee',
            ]);
            if (!$validation->fails()) {

                if ($user->company_id != 0) {
                    $company = Company::find($user->company_id);
                } else {
                    $company = Company::find($request->input('company_id'));
                }

                $customer = Customer::query()->where('customer_id', $request->input('customer_id'))->first();
                $warehouse = $request->input('warehouse_id');

                if (!$customer) {

                    $user_mobile = $request->input('customer_mobile');
                    if (substr($user_mobile, 0, 1) == '0') {
                        $profile_mobile = '6' . $user_mobile;
                    } elseif (substr($user_mobile, 0, 1) == '1') {
                        $profile_mobile = "60" . $user_mobile;
                    } elseif (substr($user_mobile, 0, 3) == '600') {
                        $profile_mobile = "6" . substr($user_mobile, strpos($user_mobile, '600') + 2);
                    } else {
                        $profile_mobile = $user_mobile;
                    }

                    if (count(CustomerCategory::query()->where('company_id', $user->company_id)->get()) > 0) {
                        $category = CustomerCategory::where('company_id', $user->company_id)->first();
                    } else {
                        $category = CustomerCategory::create([
                            'customer_category_name' => 'Category',
                            'customer_category_created' => now(),
                            'customer_category_updated' => now(),
                            'customer_category_status' => 'active',
                            'is_deleted' => 0,
                            'customer_category_priority' => 1,
                            'company_id' => $user->company_id
                        ]);
                    }

                    if ($request->input('delivery_order_type_id') == 2) {
                        if ($request->input('warehouse_id')) {
                            $warehouse = $request->input('warehouse_id');
                        } else {
                            $warehouse = SettingWarehouse::create([
                                'warehouse_name' => $request->input('warehouse_name'),
                                'warehouse_status' => 'active',
                                'warehouse_ranking' => 1,
                                'company_id' => $user->company_id,
                                'warehouse_cdate' => now(),
                                'warehouse_udate' => now()
                            ]);
                            $warehouse = $warehouse->warehouse_id;
                        }
                    } else {
                        $warehouse = 0;
                    }

                    $customer = Customer::create([
                        'customer_company_name' => $company->company_name,
                        'customer_name' => $request->input('cname'),
                        'customer_mobile_no' => $profile_mobile,
                        'customer_address' => $request->input('address1'),
                        'customer_address2' => $request->input('address2') ?? '',
                        'customer_state' => $request->input('state'),
                        'customer_city' => $request->input('city'),
                        'customer_postcode' => $request->input('postcode'),
                        'customer_email' => $request->input('email'),
                        'customer_code' => Str::random(5),
                        'customer_country' => $request->input('country'),
                        'customer_created' => now(),
                        'customer_updated' => now(),
                        'company_id' => $company->company_id,
                        'customer_category_id' => $category->customer_category_id,
                        'warehouse_id' => $warehouse
                    ]);
                }

                $pic = CustomerPIC::get_pic_cid($request->input('customer_ic'), $customer->customer_id);

                $pic_mobile_check = $request->input('customer_mobile_no');
                if (substr($pic_mobile_check, 0, 1) == '0') {
                    $pic_mobile = '6' . $pic_mobile_check;
                } elseif (substr($pic_mobile_check, 0, 1) == '1') {
                    $pic_mobile = "60" . $pic_mobile_check;
                } elseif (substr($pic_mobile_check, 0, 3) == '600') {
                    $pic_mobile = "6" . substr($pic_mobile_check, strpos($pic_mobile_check, '600') + 2);
                } else {
                    $pic_mobile = $pic_mobile_check;
                }

                if (!$pic) {
                    CustomerPIC::create([
                        'customer_pic_name' => $request->input('customer_name'),
                        'customer_pic_created' => now(),
                        'customer_pic_updated' => now(),
                        'customer_pic_mobile_no' => $pic_mobile,
                        'customer_pic_ic' => $request->input('customer_ic'),
                        'customer_id' => $request->input('customer_id') ?? $customer->customer_id,
                    ]);
                }

                $update_customer = Customer::find($customer->customer_id);
                if ($request->input('delivery_order_type_id') == 2) {
                    $update_customer->update([
                        'warehouse_id' => $warehouse
                    ]);
                }


                if ($request->input('delivery_order_type_id') == 2) {
                    if (@$request->input('expense') && count($request->input('expense')) > 0) {
                        $old_doe = DeliveryOrderExpense::where('delivery_order_id', $id)->pluck('setting_expense_id')->toArray();
                        $doe_arr = $request->input('expense');
                        $remove = array_diff($old_doe, $doe_arr);

                        if ($remove) {
                            foreach ($remove as $key => $rm) {
                                DeliveryOrderExpense::where('delivery_order_id', $id)->where('setting_expense_id', $rm)->delete();
                            }
                        }

                        foreach ($doe_arr as $key => $exp_id) {
                            $exist_doe = DeliveryOrderExpense::where('setting_expense_id', $exp_id)->where('delivery_order_id', $id)->first();
                            $value = $request->input('setting_expense_value')[$exp_id];
                            $kg = $request->input('expense_kg')[$exp_id] ?? 0;

                            $day = $request->input('expense_day')[$exp_id] ?? 0;
                            if($kg != 0 && $day != 0){
                                $total = ($value * $kg) * $day;
                            }elseif($kg != 0 && $day == 0){
                                $total = $value * $kg;
                            }elseif($kg == 0 && $day != 0){
                                $total = $value * $day;
                            }else{
                                $total = $value;
                            }

                            if ($exist_doe) {
                                DeliveryOrderExpense::where('delivery_order_id', $id)->where('setting_expense_id', $exp_id)->update([
                                    'delivery_order_expense_value' => $request->input('setting_expense_value')[$exp_id],
                                    'delivery_order_expense_kg' => $request->input('expense_kg')[$exp_id] ?? 0,
                                    'delivery_order_expense_day' => $request->input('expense_day')[$exp_id] ?? 0,
                                    'delivery_order_expense_total' => $total,
                                    'setting_expense_id' => $exp_id,
                                    'delivery_order_expense_updated' => now()
                                ]);
                            } else {
                                DeliveryOrderExpense::create([
                                    'delivery_order_expense_value' => $request->input('setting_expense_value')[$exp_id],
                                    'delivery_order_expense_kg' => $request->input('expense_kg')[$exp_id] ?? 0,
                                    'delivery_order_expense_day' => $request->input('expense_day')[$exp_id] ?? 0,
                                    'delivery_order_expense_total' => $total,
                                    'setting_expense_id' => $exp_id,
                                    'delivery_order_id' => $id,
                                    'delivery_order_expense_created' => now(),
                                    'delivery_order_expense_updated' => now()
                                ]);
                            }
                        }
                    }
                }
                $doi_delete = DeliveryOrderItem::where('delivery_order_id', $id)->pluck('delivery_order_item_id')->toArray();
                $total_weight = 0;
                $item_id = $request->input('delivery_order_item_id') ?? null;
                if($item_id){
                    foreach($item_id as $delivery_order_item_id){
                        $delivery_order_item = DeliveryOrderItem::where('delivery_order_item_id',$delivery_order_item_id)->first();
                        if ($delivery_order_item) {
                            $delivery_order_item->update([
                                'product_id' => $request->input('product_id_'.$delivery_order_item_id),
                                'setting_product_size_id' => $request->input('product_size_'.$delivery_order_item_id),
                                'delivery_order_item_updated' => now(),
                                'delivery_order_item_quantity' => $request->input('product_weight_'.$delivery_order_item_id),
                                'delivery_order_item_collect_no' => $request->input('collect_no_'.$delivery_order_item_id) ?? '',
                                'no_collect_code' => $request->input('no_collect_code_'.$delivery_order_item_id) ?? 0,
                            ]);

                            if(@$request->file('delivery_order_item_media_'.$delivery_order_item_id)){
                                $files = $request->file('delivery_order_item_media_'.$delivery_order_item_id);

                                foreach($files as $keyfile => $file)
                                {
                                    $delivery_order_item->addMedia($file)->toMediaCollection('delivery_order_item_media');
                                }
                            }

                            if ($request->input('delivery_order_type_id') == 2) {

                                $product_stk_transfer_w = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse, $request->input('product_id_'.$delivery_order_item_id), $request->input('product_size_'.$delivery_order_item_id));

                                if ($product_stk_transfer_w) {
                                    $update_warehse = ProductStockWarehouse::find($product_stk_transfer_w);
                                    $qty_atfer =$request->input('product_weight_'.$delivery_order_item_id) + $update_warehse->product_stock_warehouse_qty_current;
                                    ProductStockTransfer::create([
                                        'product_stock_transfer_remark' => 'Transfer',
                                        'product_stock_transfer_description' =>  '#' . $do->delivery_order_no ?? '',
                                        'product_id' => $request->input('product_id_'.$delivery_order_item_id),
                                        'product_stock_warehouse_id' => $product_stk_transfer_w,
                                        'setting_product_size_id' => $request->input('product_size_'.$delivery_order_item_id),
                                        'product_stock_transfer_qty_before' => $update_warehse->product_stock_warehouse_qty_current ?? 0,
                                        'product_stock_transfer_qty_after' => $qty_atfer,
                                        'product_stock_transfer_qty' => $request->input('product_weight_'.$delivery_order_item_id),
                                    ]);

                                    $update_warehse->update([
                                        'product_stock_warehouse_qty_current' => $qty_atfer,
                                    ]);
                                } else {
                                    $new_product_stk_warehse = ProductStockWarehouse::create([
                                        'warehouse_id' => $warehouse,
                                        'product_id' => $request->input('product_id_'.$delivery_order_item_id),
                                        'setting_product_size_id' => $request->input('product_size_'.$delivery_order_item_id),
                                        'product_stock_warehouse_qty_current' => $request->input('product_weight_'.$delivery_order_item_id),
                                    ]);

                                    ProductStockTransfer::create([
                                        'product_stock_transfer_remark' => 'Transfer',
                                        'product_stock_transfer_description' =>  '#' . $do->delivery_order_no ?? '',
                                        'product_id' => $request->input('product_id_'.$delivery_order_item_id),
                                        'product_stock_warehouse_id' => $new_product_stk_warehse->product_stock_warehouse_id,
                                        'setting_product_size_id' => $request->input('product_size_'.$delivery_order_item_id),
                                        'product_stock_transfer_qty_before' => 0,
                                        'product_stock_transfer_qty_after' => $request->input('product_weight_'.$delivery_order_item_id),
                                        'product_stock_transfer_qty' => $request->input('product_weight_'.$delivery_order_item_id),
                                    ]);
                                }
                            }
                        }
                        $total_weight += $request->input('product_weight_'.$delivery_order_item_id);
                    }
                }
                if($item_id){
                    $old_doi = DeliveryOrderItem::where('delivery_order_id', $id)->pluck('delivery_order_item_id')->toArray();
                    foreach($old_doi as $old_delivery_order_item_id){
                        if(!in_array($old_delivery_order_item_id,$item_id)){
                            $old_item = DeliveryOrderItem::where('delivery_order_item_id', $old_delivery_order_item_id)->first();
                            if ($request->input('delivery_order_type_id') == 2) {
                                $product_stk_transfer_w = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse, $old_item->product_id, $old_item->setting_product_size_id);

                                if ($product_stk_transfer_w) {
                                    $update_warehse = ProductStockWarehouse::find($product_stk_transfer_w);
                                    $qty_atfer = $update_warehse->product_stock_warehouse_qty_current - $old_item->delivery_order_item_quantity;
                                    ProductStockTransfer::create([
                                        'product_stock_transfer_remark' => 'Deduct',
                                        'product_stock_transfer_description' => 'Product Stock Deduct from warehouse',
                                        'product_id' => $old_item->product_id,
                                        'product_stock_warehouse_id' => $product_stk_transfer_w,
                                        'setting_product_size_id' => $old_item->setting_product_size_id,
                                        'product_stock_transfer_qty_before' => $update_warehse->product_stock_warehouse_qty_current ?? 0,
                                        'product_stock_transfer_qty_after' => $qty_atfer,
                                        'product_stock_transfer_qty' => $old_item->delivery_order_item_quantity,
                                    ]);

                                    $update_warehse->update([
                                        'product_stock_warehouse_qty_current' => $qty_atfer,
                                    ]);
                                }
                            }
                            $old_item->delete();
                        }
                    }
                }else{
                    $old_doi = DeliveryOrderItem::where('delivery_order_id', $id)->pluck('delivery_order_item_id')->toArray();
                        foreach($old_doi as $old_delivery_order_item_id){
                            if(in_array($old_delivery_order_item_id,$doi_delete)){
                                $old_item = DeliveryOrderItem::where('delivery_order_item_id', $old_delivery_order_item_id)->first();
                                if ($request->input('delivery_order_type_id') == 2) {
                                    $product_stk_transfer_w = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse, $old_item->product_id, $old_item->setting_product_size_id);

                                    if ($product_stk_transfer_w) {
                                        $update_warehse = ProductStockWarehouse::find($product_stk_transfer_w);
                                        $qty_atfer = $update_warehse->product_stock_warehouse_qty_current - $old_item->delivery_order_item_quantity;
                                        ProductStockTransfer::create([
                                            'product_stock_transfer_remark' => 'Deduct',
                                            'product_stock_transfer_description' => 'Product Stock Deduct from warehouse',
                                            'product_id' => $old_item->product_id,
                                            'product_stock_warehouse_id' => $product_stk_transfer_w,
                                            'setting_product_size_id' => $old_item->setting_product_size_id,
                                            'product_stock_transfer_qty_before' => $update_warehse->product_stock_warehouse_qty_current ?? 0,
                                            'product_stock_transfer_qty_after' => $qty_atfer,
                                            'product_stock_transfer_qty' => $old_item->delivery_order_item_quantity,
                                        ]);

                                        $update_warehse->update([
                                            'product_stock_warehouse_qty_current' => $qty_atfer,
                                        ]);
                                    }
                                }
                                $old_item->delete();
                            }
                        }
                    }


                if($request->input('product_id')){
                    foreach ($request->input('product_id') as $key => $product_id) {
                        $product_size = $request->input('product_size')[$key];
                        $delivery_order_item = DeliveryOrderItem::create([
                            'delivery_order_id' => $id,
                            'product_id' => $product_id,
                            'setting_product_size_id' => $product_size,
                            'delivery_order_item_quantity' => $request->input('product_weight')[$key],
                            'delivery_order_item_created' => now(),
                            'delivery_order_item_updated' => now(),
                            'delivery_order_item_collect_no' => $request->input('collect_no')[$key] ?? '',
                            'no_collect_code' => $request->input('no_collect_code')[$key] ?? 0,
                        ]);

                        if(@$request->file('delivery_order_item_media_' . $product_id . '_' . $key)){
                            $files = $request->file('delivery_order_item_media_' . $product_id . '_' . $key);

                            foreach($files as $keyfile => $file)
                            {
                                $delivery_order_item->addMedia($file)->toMediaCollection('delivery_order_item_media');
                            }
                        }

                        if ($request->input('delivery_order_type_id') == 2) {

                            $product_stk_transfer_w = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse, $product_id, $request->input('product_size')[$key]);

                            if ($product_stk_transfer_w) {
                                $update_warehse = ProductStockWarehouse::find($product_stk_transfer_w);
                                $qty_atfer = $request->input('product_weight')[$key] + $update_warehse->product_stock_warehouse_qty_current;
                                ProductStockTransfer::create([
                                    'product_stock_transfer_remark' => 'Transfer',
                                    'product_stock_transfer_description' =>  '#' . $do->delivery_order_no ?? '',
                                    'product_id' => $product_id,
                                    'product_stock_warehouse_id' => $product_stk_transfer_w,
                                    'setting_product_size_id' => $request->input('product_size')[$key],
                                    'product_stock_transfer_qty_before' => $update_warehse->product_stock_warehouse_qty_current ?? 0,
                                    'product_stock_transfer_qty_after' => $qty_atfer,
                                    'product_stock_transfer_qty' => $request->input('product_weight')[$key],
                                ]);

                                $update_warehse->update([
                                    'product_stock_warehouse_qty_current' => $qty_atfer,
                                ]);
                            } else {
                                $new_product_stk_warehse = ProductStockWarehouse::create([
                                    'warehouse_id' => $warehouse,
                                    'product_id' => $product_id,
                                    'setting_product_size_id' => $request->input('product_size')[$key],
                                    'product_stock_warehouse_qty_current' => $request->input('product_weight')[$key],
                                ]);

                                ProductStockTransfer::create([
                                    'product_stock_transfer_remark' => 'Transfer',
                                    'product_stock_transfer_description' =>  '#' . $do->delivery_order_no ?? '',
                                    'product_id' => $product_id,
                                    'product_stock_warehouse_id' => $new_product_stk_warehse->product_stock_warehouse_id,
                                    'setting_product_size_id' => $request->input('product_size')[$key],
                                    'product_stock_transfer_qty_before' => 0,
                                    'product_stock_transfer_qty_after' => $request->input('product_weight')[$key],
                                    'product_stock_transfer_qty' => $request->input('product_weight')[$key],
                                ]);
                            }
                        }
                        $total_weight += $request->input('product_weight')[$key];
                    }
                }


                DeliveryOrder::find($id)->update([
                    'delivery_order_created' => $request->input('delivery_order_created') . ' ' . date("H:i:s"),
                    'delivery_order_updated' => now(),
                    'customer_id' => $request->input('customer_id') ?? @$customer->customer_id,
                    'customer_ic' => $request->input('customer_ic') ?? '',
                    'customer_name' => $request->input('customer_name') ?? '',
                    'customer_mobile_no' =>$pic_mobile,
                    'delivery_order_total_quantity' => $total_weight ?? 0,
                    'company_id' => $company->company_id,
                    'company_land_id' => $request->input('company_land_id'),
                    'delivery_order_type_id' => $request->input('delivery_order_type_id'),
                    'warehouse_id' => $warehouse,
                    'delivery_order_remark' => $request->input('delivery_order_remark'),
                ]);

                DeliveryOrderLog::insert([
                    'delivery_order_id' => $id,
                    'delivery_order_log_created' => now(),
                    'delivery_order_log_action' => 'Update',
                    'delivery_order_log_description' => 'Order Updated By ' . $user->user_fullname,
                    'user_id' => Auth::id()
                ]);

                session()->forget('filter_do');

                Session::flash('success_msg', 'Updated successfully!');
                return redirect()->route('do_listing');


            }else{
                $do = (object) $request->all();
                $customer = Customer::query()->where('customer_id', $request->input('customer_id'))->first();
                $do->company_name = $customer->customer_company_name;
                $i = 0;
                if(@$do->delivery_order_item_id){
                    foreach ($do->delivery_order_item_id as $doi_id){
                        $do->delivery_order_items[$i] = DeliveryOrderItem::where('delivery_order_item_id', $doi_id)->first();
                        $i++;
                    }
                }
                if (@$do->product_id) {
                    foreach ($do->product_id as $key => $pid) {
                        $do->product[$key] = Product::get_by_id($pid);
                    }
                }
                $do->delivery_order_status_id = $check_do->delivery_order_status_id;
            }
        }
        $order_type = DeliveryOrderType::get_sel();
        $expense = SettingExpense::get_sel();
        $land = CompanyLand::get_company_land_sel();
        $warehouse = SettingWarehouse::get_warehouse_sel();
        return view('delivery_order.form', [
            'type' => 'Edit',
            'submit' => route('do_edit', $id),
            'order_type' => $order_type,
            'expense' => $expense,
            'records' => $do,
            'land' => $land,
            'warehouse' => $warehouse,
            'company' => Company::get_company_sel(),
            'product' => Product::get_product_company(),
            'products' => Product::get_by_company_land_DO_edit($do->company_land_id),
        ])->withErrors($validation);
    }

    public function approve_do(Request $request)
    {
        $do = DeliveryOrder::find($request->input('do_id'));
        if ($do) {
            $do->update([
                'delivery_order_status_id' => 2,
                'delivery_order_updated' => now()
            ]);
            Session::flash('success_msg', 'Approved Delivery Order #' . $do->delivery_order_no);
        } else {
            Session::flash('fail_msg', 'Invalid Delivery Order!');
        }
        return redirect()->route('do_listing');
    }

    public function ajax_get_product_size_edit_do(Request $request)
    {
        $product_id = $request->input('product_id');
        $product_size = ProductSizeLink::get_size_by_product_id2($product_id);
        return response()->json(['data' => $product_size, 'status' => $product_size ? true : false]);
    }

    public function ajax_find_delivery_with_customer_id(Request $request)
    {
        $arr = array();
        foreach ($request->all() as $key => $value) {
            $arr[$key] = $value;
        }
        $do = DeliveryOrder::get_records_by_customer_id($arr);
        return $do;
    }

    public function ajax_find_do_quantity_with_id(Request $request)
    {
        $do_id = $request->input('do_id');
        $do = DeliveryOrder::get_do_quantity($do_id);
        return $do;
    }

    public function issue_invoice(Request $request)
    {
        Session::put('issue_invoice', $request->input('delivery_order') ?? session('issue_invoice'));
        $order_ids = session('issue_invoice') ?? [];

        if ($order_ids != []) {
            $orders = DeliveryOrder::query()->whereIn('delivery_order_id', $order_ids);
            $orders = $orders->where(function($q){
                            $q->where('invoice_id', '=', null);
                            $q->orWhere('invoice_id', '=', 0);
                        });
            $orders = $orders->get();
            if ($orders->isNotEmpty()) {
                $customer = Customer::find($orders[0]->customer_id);
            } else {
                Session::flash('fail_msg', 'Delivery Order has issued to invoice.');
                return redirect()->route('do_listing');
            }
        } else {
            Session::flash('fail_msg', 'Invalid Delivery Order! Please try again.');
            return redirect()->route('do_listing');
        }

        $payment_method = SettingPayment::get_sel();
        return view('invoice.from_do', [
            'orders' => $orders,
            'type' => 'Issue',
            'products' =>Product::get_product_tree_age_pointer_report(),
            'customer' => $customer,
            'payment_method' => $payment_method,
            'gst' => Setting::setting_name('g_s_t'),
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel()
        ]);
    }

    public function view_invoice_do($iv_id)
    {
        $search['invoice_id'] = $iv_id;
        Session::put('invoice_search', $search);
        return redirect()->route('invoice_listing');
    }

    public function view_pdf(Request $request, $id, $encrypt)
    {
        $check_encrypt = md5($id.env('ENCRYPTION_KEY'));
        if($encrypt == $check_encrypt){
        $do = DeliveryOrder::find($id);
        $product = Product::get_product_tree_age_pointer_report();
        $grade = SettingSize::get_size_setting();

        if ($do) {
            $pdf = PDF::loadView('delivery_order.do_pdf', [
                        'do' => $do,
                        'grade' => $grade,
                        'product' => $product
                    ])->setPaper('a4');
            return $pdf->stream();
        } else {
            Session::flash('fail_msg', 'Invalid Delivery Order');
            return redirect()->route('do_listing');
        }
      }else{
        Session::flash('fail_msg', 'Invalid Delivery Order Encryption');
        return redirect()->route('do_listing');
      }
    }

    public function add_expense(Request $request)
    {
        $expense = $request->input('setting_expense_id');
        $id = $request->input('do_id');
        $exp_value = $request->input('setting_expense_value');
        if ($expense && count($expense) > 0) {
            foreach ($expense as $key => $ex) {
                $value = $request->input('setting_expense_value')[$key];
                $kg = $request->input('expense_kg')[$key] ?? 0;
                $day = $request->input('expense_day')[$key] ?? 0;
                if($kg != 0 && $day != 0){
                    $total = ($value * $kg) * $day;
                }elseif($kg != 0 && $day == 0){
                    $total = $value * $kg;
                }elseif($kg == 0 && $day != 0){
                    $total = $value * $day;
                }else{
                    $total = $value;
                }
                DeliveryOrderExpense::create([
                    'delivery_order_expense_value' => $exp_value[$key],
                    'delivery_order_expense_kg' => $request->input('expense_kg')[$key] ?? 0,
                    'delivery_order_expense_day' => $request->input('expense_day')[$key] ?? 0,
                    'delivery_order_expense_total' => $total,
                    'setting_expense_id' => $ex,
                    'delivery_order_id' => $id,
                    'delivery_order_expense_created' => now(),
                    'delivery_order_expense_updated' => now()
                ]);
            }
            Session::flash('success_msg', 'Added Expense for Order.');
        } else {
            Session::flash('fail_msg', 'Unable to add expense to order.');
        }

        return redirect()->route('do_listing');
    }

    public function price_verification_approve_reject(Request $request)
    {
        $user = Auth::user();
        $do_id = $request->input('delivery_order_id');
        $status = $request->input('delivery_order_status_id');

        $do = DeliveryOrder::find($do_id);


        if (!$do) {
            Session::flash('failed_msg', "Invalid Delivery Order, Please Try Again...");
            return redirect()->route('do_listing');
        }

        if ($status == 'approve') {
            $do->update([
                'delivery_order_status_id' => 5,
                'delivery_order_updated' => now()
            ]);

            DeliveryOrderLog::insert([
                'delivery_order_id' => $do_id,
                'delivery_order_log_created' => now(),
                'delivery_order_log_description' => 'Price Verification Approved by ' . $user->user_fullname,
                'delivery_order_log_action' => "Price Verification Approve",
                'user_id' => $user->user_id,
            ]);

            Session::flash('success_msg', "Delivery Order Price Verification Approved");
            return redirect()->route('do_listing');

        } elseif ($status == 'reject') {
            $do->update([
                'delivery_order_status_id' => 2,
                'delivery_order_updated' => now()
            ]);

            DeliveryOrderLog::insert([
                'delivery_order_id' => $do_id,
                'delivery_order_log_created' => now(),
                'delivery_order_log_description' => 'Price Verification Rejected by ' . $user->user_fullname,
                'delivery_order_log_action' => "Price Verification Reject",
                'user_id' => $user->user_id,
            ]);

            return redirect()->route('get_rejected_do', ['id' => $do_id]);
        }
    }

    public function delete(Request $request){

        $delivery_order_id = $request->input('delivery_order_id');
        $delivery_order_log_description = $request->input('delivery_order_log_description');
        $delivery_order = DeliveryOrder::find($delivery_order_id);

        if (!$delivery_order || @$delivery_order->invoice_id) {
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('do_listing');
        }

        if (!$delivery_order_log_description) {
            Session::flash("fail_msg", "Remark field is required");
            return redirect()->route('do_listing');
        }

        $delivery_order->update([
            'delivery_order_status_id' => 3 //deleted
        ]);

        DeliveryOrderLog::insert([
            'delivery_order_id' => $delivery_order_id,
            'delivery_order_log_created' => now(),
            'delivery_order_log_action' => 'Delete Delivery Order',
            'delivery_order_log_description' => $delivery_order_log_description,
            'user_id' => Auth::id()
        ]);

        Session::flash('success_msg', 'Successfully deleted delivery order - '.$delivery_order->delivery_order_no);
        return redirect()->route('do_listing');
    }

    public function revert(Request $request){

        $delivery_order_id = $request->input('delivery_order_id');
        $delivery_order_log_description = $request->input('delivery_order_log_description');
        $delivery_order = DeliveryOrder::find($delivery_order_id);
        $invoice = Invoice::find($delivery_order->invoice_id);

        if (!$delivery_order) {
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('do_listing');
        }
        //
        if (!$delivery_order_log_description) {
            Session::flash("fail_msg", "Remark field is required");
            return redirect()->route('do_listing');
        }

        if ($invoice) {
            $invoice->update([
                'invoice_status_id' => 3 //cancelled
            ]);
            InvoiceLog::insert([
                'invoice_id' => $invoice->invoice_id,
                'invoice_log_created' => now(),
                'invoice_log_description' => "Delivery Order Reverted",
                'invoice_log_action' => "Cancel Invoice",
                'user_id' => Auth::id(),
            ]);
        }

        $delivery_order->update([
            'delivery_order_status_id' => 2, //approved
            'invoice_id' => 0
        ]);

        DeliveryOrderLog::insert([
            'delivery_order_id' => $delivery_order_id,
            'delivery_order_log_created' => now(),
            'delivery_order_log_action' => 'Revert Delivery Order',
            'delivery_order_log_description' => $delivery_order_log_description,
            'user_id' => Auth::id()
        ]);

        Session::flash('success_msg', 'Successfully reverted delivery order - '.$delivery_order->delivery_order_no);
        return redirect()->route('do_listing');
    }

    public function ajax_find_do_with_id(Request $request)
    {
        $do_id = $request->input('id');
        $do_item = DeliveryOrder::get_do_details_by_id($do_id);
        return $do_item;
    }

    public function get_rejected_do($do_id)
    {
        $search['delivery_order_id'] = $do_id;
        Session::put('filter_do', $search);
        Session::flash('fail_msg', "Delivery Order Price Verification Rejected. Please send the price verification form link to customer again");
        return redirect()->route('do_listing');
    }

    public function ajax_get_mobile_no_by_do_id(Request $request)
    {
        $do_id = $request->input('do_id');
        $do = DeliveryOrder::get_by_id($do_id);
        $customer_name = @$do->customer_details->customer_name;
        $customer_mobile_no = @$do->customer_details->customer_mobile_no;
        $company_acc_name = @$do->company->company_acc_name;
        $company_acc_mobile_no = @$do->company->company_acc_mobile_no;
        $customer_pic_name = @$do->customer_name;
        $customer_pic_mobile_no = @$do->customer_mobile_no;

        return response()->json([
            'customer_mobile_no' => $customer_mobile_no,
            'company_acc_mobile_no' => $company_acc_mobile_no,
            'company_acc_name' => $company_acc_name,
            'customer_name' => $customer_name,
            'customer_pic_name' => $customer_pic_name,
            'customer_pic_mobile_no' => $customer_pic_mobile_no
        ]);
    }

    public function ajax_get_image_by_do_id(Request $request)
    {
        $items = null;
        $delivery_order_id = $request->input('do_id');
        $items = DeliveryOrderItem::get_do_item_by_do_id($delivery_order_id);

        return response()->json([
            'items' => $items,
        ]);
    }

    public function ajax_get_collect_code_from_do(Request $request)
    {
      $status = false;
      $company_id = $request->input('company_id');
      $company_land_id = $request->input('company_land_id');
      $product_id = $request->input('product_id');
      $setting_product_size_id = $request->input('size_id');
      $date = $request->input('date');

      $collect = Collect::get_collect_code_from_do($company_id, $company_land_id, $product_id, $setting_product_size_id, $date);

      if($collect){
        $status = true;
      }else{
        $status = false;
      }
      return $status;
    }

    public function ajax_delete_image_by_media_do_item_id(Request $request)
    {
        $delivery_order_item_id = $request->input('delivery_order_item_id');
        $media_id = $request->input('media_id');
        Media::query()
            ->where('id', $media_id)
            ->where('model_id',$delivery_order_item_id)
            ->where('collection_name', 'delivery_order_item_media')
            ->delete();
    }

    public function ajax_get_image_by_do_item_id(Request $request)
    {
        $items = null;
        $company_expense_item_id = $request->input('do_item_id');
        $items = DeliveryOrderItem::get_do_item_by_do_id($company_expense_item_id);

        return response()->json([
            'items' => $items,
        ]);
    }
}
