<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Customer;
use App\Model\CustomerCategory;
use App\Model\DeliveryOrder;
use App\Model\DeliveryOrderItem;
use App\Model\DeliveryOrderLog;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\InvoiceLog;
use App\Model\InvoicePayment;
use App\Model\InvoiceStatus;
use App\Exports\InvoiceExport;
use App\Exports\ProductListExport;
use App\Imports\InvoiceImport;
use App\Model\MessageTemplate;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\RunningNumber;
use App\Model\Setting;
use App\Model\SettingPayment;
use App\Model\SettingSize;
use App\Model\User;
use App\Model\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');

            switch ($submit) {
                case 'search':
                    session(['invoice_search' => [
                        'freetext' => $request->input('freetext'),
                        'iv_created_from' => $request->input('iv_created_from'),
                        'iv_created_to' => $request->input('iv_created_to'),
                        'iv_from' => $request->input('iv_from'),
                        'iv_to' => $request->input('iv_to'),
                        'invoice_id' => $request->input('invoice_id'),
                        'is_approved' => $request->input('is_approved'),
                        'invoice_status_id' => $request->input('invoice_status_id'),
                        'company_id' => $request->input('company_id'),
                        'company_land_id' => $request->input('company_land_id'),
                        'product_category_id' => $request->input('product_category_id'),
                        'product_id' => $request->input('product_id'),
                        'product_size_id' => $request->input('product_size_id'),
                        'user_id' => $request->input('user_id'),
                        'customer_id' => $request->input('customer_id'),
                        'customer_category_id' => $request->input('customer_category_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('invoice_search');
                    break;
                case 'export':
                        $export = true;
                        $search['freetext'] = $request->input('freetext');
                        $search['iv_created_from'] = $request->input('iv_created_from');
                        $search['iv_created_to'] = $request->input('iv_created_to');
                        $search['iv_from'] = $request->input('iv_from');
                        $search['iv_to'] = $request->input('iv_to');
                        $search['invoice_id'] = $request->input('invoice_id');
                        $search['is_approved'] = $request->input('is_approved');
                        $search['invoice_status_id'] = $request->input('invoice_status_id');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['product_category_id'] = $request->input('product_category_id');
                        $search['product_id'] = $request->input('product_id');
                        $search['product_size_id'] = $request->input('product_size_id');
                        $search['delivery_no'] = $request->input('delivery_no');
                        $search['delivery_order_id'] = $request->input('delivery_order_id');
                        $search['user_id'] = $request->input('user_id');
                        $search['customer_id'] = $request->input('customer_id');

                        $invoice = Invoice::get_records($search, $export);
                        Session::put('listing', $search);
                        return Excel::download(new InvoiceExport('export/iv_export', $invoice, $search), 'IV_Export.xlsx');
                        break;
            }
        }

        $search = session('invoice_search') ?? array();
        $invoice = Invoice::get_records($search);
        $invoice_total_amount = Invoice::get_total_amount_w_status($search);
        $is_approved = ['' => 'Please Select Approval...', '0' => 'Not Yet Approved', '1' => 'Approved'];
        $message_templates = MessageTemplate::get_by_slug(['invoice-listing']);
        $iv_status = InvoiceStatus::all();

        $ids = [1, 5, 6, 2, 4, 3];
        $iv_sorted = $iv_status->sortBy(function($model) use ($ids){
            return array_search($model->getKey(), $ids);
        });

        return view('invoice.listing', [
            'records' => $invoice,
            'invoice_total_amount' => $invoice_total_amount,
            'search' => $search,
            'submit' => route('invoice_listing'),
            'iv_status' => $iv_sorted,
            'iv_status_2' => InvoiceStatus::query()->whereIn('invoice_status_id', [1, 4, 5])->get(),
            'is_approved' => $is_approved,
            'message_templates' => $message_templates,
            'count_status' => Invoice::count_status($search, InvoiceStatus::all()),
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
            'product' => Product::get_by_company(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $user = auth()->user();
        $total_sub = 0;
        $total_dis = 0;
        $total = 0;
        $gst = 0;
        $grandtotal = 0;

        if ($request->isMethod('post')) {
            $validate = Validator::make($request->all(), [
                'customer_name' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'country' => 'required',
                'payment_method' => 'required',
                'company_land_id' => 'required'
            ]);
            $company_bank_id = CompanyLand::where('company_land_id', $request->input('company_land_id'))->value('company_bank_id');

            if (!$validate->fails()) {

                if(!$company_bank_id){
                    $msg = 'Please add Company Bank for the Land. Go to<a href="'. route('product_company_land_add', $request->input('company_land_id')) . '"> Add Company Bank </a>';
                    Session::flash('fail_msg_with_html', $msg);
                    return redirect()->route('do_issue_invoice')->withErrors($validate);
                }

                if ($user->company_id != 0) {
                    $company = Company::find($user->company_id);
                } else {
                    $company = Company::find($request->input('company_id'));
                }

                if ($request->input('customer_id') == null) {
                    $customer_id = Customer::insertGetId([
                        'customer_company_name' => $company->company_name,
                        'customer_name' => $request->input('customer_name'),
                        'customer_mobile_no' => $request->input('customer_mobile'),
                        'customer_address' => $request->input('address'),
                        'customer_address2' => $request->input('address2') ?? '',
                        'customer_state' => $request->input('state'),
                        'customer_city' => $request->input('city'),
                        'customer_code' => Str::random(5),
                        'customer_country' => $request->input('country'),
                        'customer_created' => now(),
                        'customer_updated' => now(),
                        'company_id' => $company->company_id,
                        'customer_email' => $request->input('customer_email'),
                        'customer_postcode' => $request->input('postcode'),
                        'customer_category_id' => CustomerCategory::get_first_category()->customer_category_id
                    ]);
                } else {
                    $customer_id = $request->input('customer_id');
                }

                $do_id_date = $request->input('do_id');

                for ($key = 0; $key < count($do_id_date); $key++) {
                    $do_id_date_result[$key] = DeliveryOrder::query()->where('delivery_order_id', $do_id_date[$key])->orderBy('delivery_order_created', 'ASC')->get();
                }
                $running_no = RunningNumber::get_running_code_invoice('invoice', date('n', strtotime($do_id_date_result[0][0]->delivery_order_created)));

                if ($do_id_date_result[0][0]->delivery_order_type_id == 1){
                    $invoice_date = $do_id_date_result[0][0]->delivery_order_created;
                }else{
                    $invoice_date = date('Y-m-d H:i:s');
                }

                $invoice = Invoice::create([
                    'customer_id' => $customer_id,
                    'customer_name' => $request->input('customer_name'),
                    'customer_address' => $request->input('address'),
                    'customer_address2' => $request->input('address2'),
                    'customer_state' => $request->input('state'),
                    'customer_city' => $request->input('city'),
                    'customer_postcode' => $request->input('postcode'),
                    'customer_country' => $request->input('country'),
                    'invoice_subtotal' => 0,
                    'invoice_total_discount' => 0,
                    'invoice_total' => 0,
                    'invoice_total_gst' => 0,
                    'invoice_grandtotal' => 0,
                    'company_id' => $company->company_id,
                    'company_land_id' => $request->input('company_land_id'),
                    'company_bank_id' => $company_bank_id,
                    'user_id' => $user->user_id,
                    'invoice_status_id' => 1,
                    'invoice_no' => 'IN/' . $company->company_code . '/' . $running_no,
                    'invoice_created' => now(),
                    'invoice_updated' => now(),
                    'invoice_date' => $invoice_date,
                    'invoice_remark' => @$request->input('invoice_remark'),
                ]);

                if (count($request->input('do_id')) > 0) {
                    $do_id = $request->input('do_id');
                    for ($d = 0; $d < count($do_id); $d++) {
                        DeliveryOrder::find($do_id[$d])->update([
                            'invoice_id' => $invoice->invoice_id,
                            'delivery_order_updated' => now(),
                            'delivery_order_status_id' => 5
                        ]);
                    }
                }

                if (count($request->input('products')) > 0) {
                    $product = $request->input('products');
                    $size = $request->input('size');
                    for ($i = 0; $i < count($product); $i++) {
                        $product_detail = Product::find($product[$i]);
                        if ($product_detail) {
                            $quantity = $request->input('product_quantity_' . $i . '_0');
                            $price = $request->input('product_price_' . $i . '_0');
                            $discount = $request->input('product_dis_' . $i . '_0');
                            $sub_total = ($quantity * $price) - ($discount * $quantity);

                            $invoice_item = InvoiceItem::create([
                                'invoice_id' => $invoice->invoice_id,
                                'product_id' => $product[$i],
                                'setting_product_size_id' => $size[$i],
                                'invoice_item_name' => $product_detail->product_name,
                                'invoice_item_price' => $price,
                                'invoice_item_quantity' => $quantity,
                                'invoice_item_subtotal' => $price * $quantity,
                                'invoice_item_discount' => $discount,
                                'invoice_item_total' => $sub_total,
                                'invoice_item_created' => now(),
                                'invoice_item_updated' => now(),
                                'delivery_order_item_id' => 0
                            ]);

                            if (count($request->input('do_id')) > 0) {
                                $do_ids = $request->input('do_id');
                                for ($d = 0; $d < count($do_ids); $d++) {
                                    $do_item = DB::table('tbl_delivery_order_item')->where('delivery_order_id', $do_ids[$d])->where('product_id', $product[$i])->where('setting_product_size_id', $size[$i])->get();
                                    if ($do_item) {
                                        foreach($do_item as $item){
                                            DeliveryOrderItem::find($item->delivery_order_item_id)->update([
                                                'invoice_item_id' => $invoice_item->invoice_item_id,
                                                'delivery_order_item_updated' => now(),
                                                'delivery_order_item_price_per_kg' => $price
                                            ]);
                                        }
                                    }
                                }
                            }

                            $total_sub += ($price * $quantity);
                            $total_dis += ($discount * $quantity);
                            $total += $sub_total;
                        } else {
                            return redirect()->back();
                        }
                    }
                }
                $round_up_down = $request->input('invoice_total_round_up') ?? 0;
                $gst_val = Setting::get_by_slug('g_s_t');

                if (auth()->user()->company->company_enable_gst != 1) {
                    $gst_val = 0;
                }

                $gst = ($total * $gst_val) / 100;
                $grandtotal = ($total + $gst) + $round_up_down;

                Invoice::find($invoice->invoice_id)->update([
                    'invoice_subtotal' => $total_sub,
                    'invoice_total_discount' => $total_dis,
                    'invoice_total' => $total,
                    'invoice_total_gst' => $gst,
                    'invoice_grandtotal' => $grandtotal,
                    'invoice_total_round_up' => $round_up_down,
                ]);

                InvoicePayment::create([
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_payment_amount' => $grandtotal,
                    'setting_payment_id' => $request->input('payment_method'),
                    'invoice_payment_created' => now(),
                    'invoice_payment_updated' => now(),
                    'invoice_payment_data' => '',
                    'is_deleted' => 0
                ]);

                InvoiceLog::insert([
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_log_created' => now(),
                    'invoice_log_description' => 'Invoice Created By ' . $user->user_fullname,
                    'invoice_log_action' => 'Add',
                    'user_id' => $user->user_id
                ]);

                Session::flash('success_msg', 'Successfully Added New Invoice');
                return redirect()->route('invoice_listing');
            } else {
                Session::flash('fail_msg', 'Error when creating invoice...');
                return redirect()->route('do_issue_invoice')->withErrors($validate);
            }
        } else {
            Session::flash('fail_msg', 'Error Creating Invoice...');
            return redirect()->route('do_listing');
        }
    }

    public function edit(Request $request, $id)
    {
        $validation = null;
        $user = auth()->user();
        $invoice = Invoice::query()->where('invoice_id', $id);

        if ($user->company_id != 0) {
            $invoice = $invoice->where('company_id', $user->company_id)->first();
        } else {
            $invoice = $invoice->first();
        }

        if ($invoice == null) {
            Session::flash('fail_msg', 'Invalid Invoice');
            return redirect()->route('invoice_listing');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'customer_name' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'country' => 'required',
                'payment_method' => 'required',
                'company_land_id' => 'required'
            ]);

            if (!$validation->fails()) {
                if ($user->company_id != 0) {
                    $company_id = $user->company_id;
                } else {
                    $company_id = $request->input('company_id');
                }

                $company_bank_id = CompanyLand::where('company_land_id', $request->input('company_land_id'))->value('company_bank_id');

                $invoice->update([
                    'customer_address' => $request->input('address'),
                    'customer_address2' => $request->input('address2'),
                    'customer_state' => $request->input('state'),
                    'customer_city' => $request->input('city'),
                    'customer_postcode' => $request->input('postcode'),
                    'customer_country' => $request->input('country'),
                    'invoice_updated' => now(),
                    'company_id' => $company_id,
                    'company_land_id' => $request->input('company_land_id'),
                    'company_bank_id' => $company_bank_id,
                    'invoice_subtotal' => $request->input('invoice_subtotal'),
                    'invoice_total_discount' => $request->input('invoice_total_discount'),
                    'invoice_total' => $request->input('invoice_total'),
                    'invoice_total_gst' => $request->input('invoice_total_gst'),
                    'invoice_grandtotal' => $request->input('invoice_grandtotal'),
                    'invoice_total_round_up' => $request->input('invoice_total_round_up'),
                    'invoice_remark' => @$request->input('invoice_remark'),
                ]);

                $item_ids = $request->input('invoice_item_id');

                foreach($item_ids as $item_id){
                    $invoice_item = InvoiceItem::find($item_id);
                    $invoice_item->update([
                        'invoice_item_price' => $request->input('product_price_'.$item_id),
                        'invoice_item_subtotal' => $request->input('invoice_item_subtotal_'.$item_id),
                        'invoice_item_discount' => $request->input('discount_'.$item_id),
                        'invoice_item_total' => $request->input('invoice_item_total_'.$item_id),
                    ]);

                    DeliveryOrderItem::where('invoice_item_id', $invoice_item->invoice_item_id)->update([
                      'delivery_order_item_price_per_kg' => $request->input('product_price_'.$item_id)
                    ]);
                }

                InvoicePayment::query()->where('invoice_id', $id)->update([
                    'setting_payment_id' => $request->input('payment_method')
                ]);

                InvoiceLog::insert([
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_log_created' => now(),
                    'invoice_log_description' => 'Invoice Update By ' . $user->user_fullname,
                    'invoice_log_action' => 'Update',
                    'user_id' => $user->user_id
                ]);

                Session::flash('success_msg', 'Successfully Update Invoice');
                return redirect()->route('invoice_listing');
            }
            $invoice = (object) $request->all();
        }

        $payment_method = SettingPayment::get_sel();
        $do = DeliveryOrder::query()->where('invoice_id', $id)->get();
        $customer = Customer::find($invoice->customer_id);
        return view('invoice.form', [
            'invoice' => $invoice,
            'payment_method' => $payment_method,
            'type' => 'Edit',
            'do_txt' => $do,
            'customer' => $customer,
            'gst' => Setting::setting_name('g_s_t'),
            'submit' => route('invoice_edit', $id),
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
        ])->withErrors($validation);
    }

    public function paid_edit(Request $request, $id)
    {
        $validation = null;
        $user = auth()->user();
        $invoice = Invoice::query()->where('invoice_id', $id);

        if ($user->company_id != 0) {
            $invoice = $invoice->where('company_id', $user->company_id)->first();
        } else {
            $invoice = $invoice->first();
        }

        if ($invoice == null) {
            Session::flash('fail_msg', 'Invalid Invoice');
            return redirect()->route('invoice_listing');
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'customer_name' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'country' => 'required',
                'payment_method' => 'required',
                'company_land_id' => 'required'
            ]);

            if (!$validation->fails()) {
                if ($user->company_id != 0) {
                    $company_id = $user->company_id;
                } else {
                    $company_id = $request->input('company_id');
                }

                $company_bank_id = CompanyLand::where('company_land_id', $request->input('company_land_id'))->value('company_bank_id');

                $invoice->update([
                    'customer_address' => $request->input('address'),
                    'customer_address2' => $request->input('address2'),
                    'customer_state' => $request->input('state'),
                    'customer_city' => $request->input('city'),
                    'customer_postcode' => $request->input('postcode'),
                    'customer_country' => $request->input('country'),
                    'invoice_updated' => now(),
                    'company_id' => $company_id,
                    'company_land_id' => $request->input('company_land_id'),
                    'company_bank_id' => $company_bank_id,
                    'invoice_subtotal' => $request->input('invoice_subtotal'),
                    'invoice_total_discount' => $request->input('invoice_total_discount'),
                    'invoice_total' => $request->input('invoice_total'),
                    'invoice_total_gst' => $request->input('invoice_total_gst'),
                    'invoice_grandtotal' => $request->input('invoice_grandtotal'),
                    'invoice_total_round_up' => $request->input('invoice_total_round_up')
                ]);

                if($invoice->invoice_grandtotal > $invoice->invoice_amount_paid){
                    $invoice->update([
                        'invoice_status_id' => 6,
                    ]);
                }

                $item_ids = $request->input('invoice_item_id');

                foreach($item_ids as $item_id){
                    $invoice_item = InvoiceItem::find($item_id);
                    $invoice_item->update([
                        'invoice_item_price' => $request->input('product_price_'.$item_id),
                        'invoice_item_subtotal' => $request->input('invoice_item_subtotal_'.$item_id),
                        'invoice_item_discount' => $request->input('discount_'.$item_id),
                        'invoice_item_total' => $request->input('invoice_item_total_'.$item_id),
                    ]);

                    DeliveryOrderItem::where('invoice_item_id', $invoice_item->invoice_item_id)->update([
                      'delivery_order_item_price_per_kg' => $request->input('product_price_'.$item_id)
                    ]);
                }

                InvoicePayment::query()->where('invoice_id', $id)->update([
                    'setting_payment_id' => $request->input('payment_method')
                ]);

                InvoiceLog::insert([
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_log_created' => now(),
                    'invoice_log_description' => 'Invoice Update By ' . $user->user_fullname,
                    'invoice_log_action' => 'Update',
                    'user_id' => $user->user_id
                ]);

                Session::flash('success_msg', 'Successfully Update Invoice');
                return redirect()->route('invoice_listing');
            }
            $invoice = (object) $request->all();
        }

        $payment_method = SettingPayment::get_sel();
        $do = DeliveryOrder::query()->where('invoice_id', $id)->get();
        $customer = Customer::find($invoice->customer_id);
        return view('invoice.form', [
            'invoice' => $invoice,
            'payment_method' => $payment_method,
            'type' => 'Edit',
            'do_txt' => $do,
            'customer' => $customer,
            'gst' => Setting::setting_name('g_s_t'),
            'submit' => route('paid_invoice_edit', $id),
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
        ])->withErrors($validation);
    }

    public function delete(Request $request){

        $invoice_id = $request->input('invoice_id');
        $invoice_log_description = $request->input('invoice_log_description');
        $invoice = Invoice::find($invoice_id);

        if (!$invoice) {
            Session::flash("fail_msg", "Error, please try again later...");
            return redirect()->route('invoice_listing');
        }

        if (!$invoice_log_description) {
            Session::flash("fail_msg", "Remark field is required");
            return redirect()->route('invoice_listing');
        }

        $invoice->update([
            'invoice_status_id' => 3 //cancelled
        ]);

        InvoiceLog::insert([
            'invoice_id' => $invoice_id,
            'invoice_log_created' => now(),
            'invoice_log_description' => $invoice_log_description,
            'invoice_log_action' => "Cancel Invoice",
            'user_id' => Auth::id(),
        ]);

        //unlink invoice from do
        $delivery_order = DeliveryOrder::where('invoice_id', $invoice_id)->first();
        if($delivery_order){
            $delivery_order->update([
                'invoice_id' => 0
            ]);

            DeliveryOrderLog::insert([
                'delivery_order_id' => $delivery_order->delivery_order_id,
                'delivery_order_log_created' => now(),
                'delivery_order_log_action' => 'Delete Invoice',
                'delivery_order_log_description' => 'Invoice '.$invoice->invoice_no.' has been cancelled',
                'user_id' => Auth::id()
            ]);
        }

        Session::flash('success_msg', 'Successfully cancelled invoice - '.$invoice->invoice_no);
        return redirect()->route('invoice_listing');
    }

    public function approve_reject(Request $request)
    {
        $user = Auth::user();
        $invoice_id = $request->input('invoice_id');
        $status = $request->input('status');
        $invoice_media = $request->input('invoice_image') ?? [];

        if (!$invoice_id) {
            Session::flash('failed_msg', "Invalid Invoice, Please Try Again...");
            return redirect()->route('invoice_listing');
        }

        if ($status == 'approve') {
            $query = Invoice::find($invoice_id);
            if(@$query->invoice_status_id == 5){
              $query->update([
                  'invoice_status_id' => $query->invoice_amount_remaining > 0 ? 6 : 2,
                  'is_approved' => 1,
                  'is_approved_date' => now()
              ]);
              InvoiceLog::insert([
                  'invoice_id' => $invoice_id,
                  'invoice_log_created' => now(),
                  'invoice_log_description' => 'Invoiced Approved by ' . $user->user_fullname,
                  'invoice_log_action' => "Approve",
                  'user_id' => $user->user_id,
              ]);
            }

            Session::flash('success_msg', "Invoice Approved");
            return redirect()->route('invoice_listing');
        } elseif ($status == 'reject') {
            $query = Invoice::find($invoice_id);

            if(@$query->invoice_status_id == 5 || @$query->invoice_status_id == 6){
              $query->update([
                  'invoice_status_id' => 1,
                  'invoice_amount_paid' => 0,
                  'invoice_amount_remaining' => 0,
              ]);

              InvoiceLog::insert([
                  'invoice_id' => $invoice_id,
                  'invoice_log_created' => now(),
                  'invoice_log_description' => 'Invoiced Rejected by ' . $user->user_fullname,
                  'invoice_log_action' => "Reject",
                  'user_id' => $user->user_id,
              ]);

              if(count($invoice_media) > 0){
                foreach ($invoice_media as $key => $media_id) {
                  Media::query()
                      ->where('id', $media_id)
                      ->where('model_id',$invoice_id)
                      ->where('collection_name', 'payment_slip')
                      ->delete();
                }
              }
            }

            Session::flash('success_msg', "Invoice Rejected");
            return redirect()->route('invoice_listing');
        }
    }

    public function ajax_find_invoice_with_customer_id(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $do = Invoice::get_records_by_customer_id($customer_id);
        return $do;
    }

    public function ajax_find_invoice_with_id(Request $request)
    {

        $invoice_id = $request->input('invoice_id');
        // $invoice_id = 203;
        $invoice = Invoice::get_invoice_details_by_id($invoice_id);
        return $invoice;
    }

    public function send_invoice(Request $request)
    {
        $invoice = Invoice::find($request->input('invoice_id'));
        $customer = Customer::find($invoice->customer_id);
        $user_mobile = $customer->customer_mobile_no;
        $encryp = md5($invoice->invoice_id . env('ENCRYPTION_KEY'));

        if (substr($user_mobile, 0, 1) == '0') {
            $profile_mobile = '6' . $user_mobile;
        } elseif (substr($user_mobile, 0, 1) == '1') {
            $profile_mobile = "60" . $user_mobile;
        } elseif (substr($user_mobile, 0, 3) == '600') {
            $profile_mobile = "6" . substr($user_mobile, strpos($user_mobile, '600') + 2);
        } else {
            $profile_mobile = $user_mobile;
        }
        $domain = env('APP_URL');
        $url = 'https://api.whatsapp.com/send?phone=60167078855&text=Click below to view your invoice. ' . $domain . '/' . 'view_invoice/' . $invoice->invoice_id . '/' . md5($invoice->invoice_id . env('ENCRYPTION_KEY'));
        // return redirect('https://api.whatsapp.com/send?phone=' . $profile_mobile . '&text=' . env('APP_URL') . 'view_invoice/' . $invoice->invoice_id . '/' . md5($invoice->invoice_id . env('ENCRYPTION_KEY')));
        // return redirect('https://api.whatsapp.com/send?phone=60167078855&text=Click below to view your invoice.' . env("APP_URL") . '/');
        return redirect($url);
        // return redirect()->route('view_invoice', ['id' => $invoice->invoice_id, 'encryption' => $encryp]);
    }

    public function view_invoice(Request $request, $id, $encryption)
    {
        $invoice = Invoice::find($id);
        $encryp = md5($invoice->invoice_id . env('ENCRYPTION_KEY'));

        $validation = null;
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'payment_slip' => ['required', function ($attr, $val, $fail) use ($request) {
                    $is_img = Validator::make(['upload' => $val], ['upload' => 'image'])->passes();
                    $is_pdf = Validator::make(['upload' => $val], ['upload' => 'mimetypes:application/pdf'])->passes();

                    if (!$is_img && !$is_pdf) {
                        return $fail(__('Payment slip must be Image or PDF format'));
                    }

                    if ($is_img) {
                        $validator = Validator::make(
                            ['image' => $val],
                            ['image' => "max:2048"]
                        );
                        if ($validator->fails()) {
                            return $fail(__('Image must be one megabyte or less.'));
                        }
                    }
                    if ($is_pdf) {
                        $validator = Validator::make(
                            ['image' => $val],
                            ['image' => "max:10240"]
                        );
                        if ($validator->fails()) {
                            return $fail(__('PDF must be ten megabyte or less.'));
                        }
                    }
                }]
            ])->setAttributeNames([
                'payment_slip' => 'Payment Slip'
            ]);

            if (!$validation->fails()) {
                if ($request->file('payment_slip')) {
                    $invoice->addMediaFromRequest('payment_slip')->toMediaCollection('payment_slip', 'public');
                }

                InvoiceLog::insert([
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_log_created' => now(),
                    'invoice_log_description' => 'Bank Slip Uploaded by Client',
                    'invoice_log_action' => "Payment",
                    // 'user_id' => $user->user_id,
                ]);

                return view('export.upload_done');
            }

            return redirect()->back()->withErrors($validation);
        }

        if ($encryp == $encryption) {
            if ($invoice) {
                $company = Company::find($invoice->company_id);
                $do = DeliveryOrder::query()->where('invoice_id', $invoice->invoice_id)->pluck('delivery_order_no');
                return view('export.invoice', [
                    'invoice' => $invoice,
                    'company' => $company,
                    'do' => $do,
                    'submit' => route('view_invoice', ['id' => $id, 'encryption' => $encryp])
                ]);
            } else {
                return view('pages-404');
            }
        } else {
            return view('pages-404');
        }
    }

    public function export_invoice($id, $encryp)
    {
        $invoice = Invoice::find($id);
        $encryp = md5($invoice->invoice_id . env('ENCRYPTION_KEY'));
        if ($encryp == $encryp) {
            if ($invoice) {
                $pdf = PDF::loadView('export.view_invoice', [
                    'invoice' => $invoice
                ]);

                // return $pdf->stream('Invoice #' . $invoice->invoice_no . '.pdf');
                return $pdf->download('Invoice #' . $invoice->invoice_no . '.pdf');
                // return view('export.view_invoice', ['invoice' => $invoice]);
            } else {
                return view('pages-404');
            }
        } else {
            return view('pages-404');
        }
    }

    public function view_invoice_pdf($id, $encryption)
    {
        $invoice = Invoice::find($id);
        $company_invoice = Invoice::query()->where('invoice_id', $id)->where('company_id', auth()->user()->company_id);
        if (!$company_invoice && !$invoice) {
            Session::flash('fail_msg', 'Invalid Invoice. Please try another.');
            return redirect()->route('invoice_listing');
        }

        $encryp = md5($invoice->invoice_id . env('ENCRYPTION_KEY'));
        if ($encryption == $encryp) {
            if ($invoice) {
                $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('export.view_invoice', [
                    'invoice' => $invoice,
                    'company_logo' => Setting::get_by_slug('website_logo'),
                    'company_name' => Setting::get_by_slug('company_name'),
                    'company_reg_no' => Setting::get_by_slug('company_reg_no'),
                    'company_address' => Setting::get_by_slug('company_address'),
                    'company_website' => Setting::get_by_slug('company_website'),
                    'company_email' => Setting::get_by_slug('company_email'),
                    'company_phone' => Setting::get_by_slug('company_phone'),
                ]);

                return $pdf->stream('Invoice #' . $invoice->invoice_no . '.pdf');
                // return $pdf->download('Invoice #' . $invoice->invoice_no . '.pdf');
                // return view('export.view_invoice', ['invoice' => $invoice]);
            } else {
                return view('pages-404');
            }
        } else {
            return view('pages-404');
        }
    }

    public function ajax_get_invoice_by_payment_url(Request $request)
    {
        $search = $request->input('search');
        $query = Invoice::with('invoice_item.product','invoice_item.setting_product_size')
            ->whereIn('invoice_status_id', [1,4,6]);

        if(@$search['customer_id']){
            $query->where('customer_id',$search['customer_id']);
        }
        if(@$search['company_land_id']){
            $query->where('company_land_id', $search['company_land_id']);
        }
        if(@$search['date_from']){
            $query->whereDate('invoice_date', '>=', $search['date_from']);
        }
        if(@$search['date_to']){
            $query->whereDate('invoice_date', '<=', $search['date_to']);
        }
        if(@$search['date_created_from']){
            $query->whereDate('invoice_created', '>=', $search['date_created_from']);
        }
        if(@$search['date_created_to']){
            $query->whereDate('invoice_created', '<=', $search['date_created_to']);
        }
        $result = $query->get();
        return $result;
    }

    public function ajax_get_mobile_no_by_id(Request $request){
        $invoice_id = $request->input('invoice_id');
        $invoice = Invoice::get_by_id($invoice_id);
        //$delivery_order_id = $request->input('delivery_order_id');
        //$invoice = DeliveryOrder::get_by_id($delivery_order_id);
        $customer_name = @$invoice->customer->customer_name;
        $customer_mobile_no = @$invoice->customer->customer_mobile_no;
        $company_acc_name = @$invoice->company->company_acc_name;
        $company_acc_mobile_no = @$invoice->company->company_acc_mobile_no;
        $customer_pic_name = $customer_pic_mobile_no = null;
        if(@$invoice->delivery_order->customer_pic){
            $customer_pic = $invoice->delivery_order->customer_pic->where('customer_pic_ic',$invoice->delivery_order->customer_ic)->first();
            if($customer_pic){
                $customer_pic_name = $customer_pic->customer_pic_name;
                $customer_pic_mobile_no = $customer_pic->customer_pic_mobile_no;
            }
        }

        return response()->json([
            'customer_mobile_no' => $customer_mobile_no,
            'company_acc_mobile_no' => $company_acc_mobile_no,
            'company_acc_name' => $company_acc_name,
            'customer_name' => $customer_name,
            'customer_pic_name' => $customer_pic_name,
            'customer_pic_mobile_no' => $customer_pic_mobile_no,
        ]);
    }

    public function listing_id($invoice_id)
    {
        $search['invoice_id'] = $invoice_id;
        \Illuminate\Support\Facades\Session::put('invoice_search', $search);
        return redirect()->route('invoice_listing');
    }

    public function import(Request $request){
        $post = array();
        $validation = null;
        $remark = NULL;
        $problem_msg = NULL;
        $big_problem = FALSE;
        $result = array();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'company_land_id' => 'required',
                'invoice_file' => 'required|mimes:xls,xlsx',

            ])->setAttributeNames([
                'company_id' => 'Company',
                'company_land_id' => 'Company Land',
                'invoice_file' => 'File',
            ]);
            if(!$validation->fails()){

                $selected_company = Company::get_by_id($request->input('company_id'));
                $selected_company_land = CompanyLand::get_by_id($request->input('company_land_id'));

                if(!@$selected_company_land->company_bank_id){
                    $msg = 'Please add Company Bank for the Land. Go to<a href="'. route('product_company_land_add', $request->input('company_land_id')) . '"> Add Company Bank </a>';
                    Session::flash('fail_msg_with_html', $msg);
                    return redirect()->route('invoice_import')->withErrors($validation);
                }

                if ($request->hasFile('invoice_file')) {
                    if ($request->file('invoice_file')) {

                        $file = $request->file('invoice_file');
                        $sheets = (new InvoiceImport)->toCollection($file);

                        if($sheets){
                            foreach ($sheets as $collections) {
                                foreach ($collections as $key => $rows) {
                                    $total = 0;
                                    switch ($key) {
                                        case 0:
                                            $product_list = array_diff($rows->toArray(), array("","日期","顾客","单号","总 (KG)","总额 (RM)"));
                                            $product_list = array_values($product_list);

                                            if(count($product_list) == 0){
                                                $big_problem = TRUE;
                                                $remark = 'no product to import';
                                                break;
                                            }

                                            $db_product = Product::get_product_for_invoice_import($product_list);

                                            if(count($product_list) != count($db_product)){
                                                $problem_product_list=array_diff_key(array_flip($product_list),$db_product);
                                                $big_problem = TRUE;
                                                $remark = 'product not exist : <br>' . implode('<br>',array_flip($problem_product_list));
                                                break;
                                            }
                                            break;
                                        case 1:
                                        case count($collections)-1:
                                            break;
                                        default:

                                            $problem_msg = '';
                                            $validate = Validator::make($rows->toArray(), [
                                                '0' => 'required',
                                                '1' => 'required',
                                                '2' => 'required',
                                            ])->setAttributeNames([
                                                '0' => 'rows '. ($key+4) .' Date',
                                                '1' => 'rows '. ($key+4) .' Name',
                                                '2' => 'rows '. ($key+4) .' Order',
                                            ]);

                                            if(!$validate->fails()){

                                                $rows_validation = TRUE;
                                                $invoice_date = $this->transformDate($rows[0]);
                                                $customer_company_name = $rows[1];
                                                $invoice_remark = $rows[2];
                                                $user = auth()->user();

                                                $customer = Customer::get_customer_by_company_id_and_customer_company_name($selected_company->company_id,$customer_company_name);

                                                if(!$customer){
                                                    $rows_validation = FALSE;
                                                    $problem_msg .= "- Customer company name not exist<br>";
                                                }

                                                $existing_invoice = Invoice::get_by_invoice_remark($invoice_remark);

                                                if($existing_invoice){
                                                    $rows_validation = FALSE;
                                                    $problem_msg .= "- Invoice already imported<br>";
                                                }

                                                if($rows_validation == TRUE){


                                                    $running_no = RunningNumber::get_running_code_invoice('invoice', date('n', strtotime($invoice_date)));

                                                    $invoice = Invoice::create([
                                                        'customer_id' => $customer->customer_id,
                                                        'customer_name' => $customer->customer_name,
                                                        'customer_address' => $customer->customer_address,
                                                        'customer_address2' => $customer->customer_address2,
                                                        'customer_state' => $customer->customer_state,
                                                        'customer_city' => $customer->customer_city,
                                                        'customer_postcode' => $customer->customer_postcode,
                                                        'customer_country' => $customer->customer_country,
                                                        'invoice_subtotal' => 0,
                                                        'invoice_total_discount' => 0,
                                                        'invoice_total' => 0,
                                                        'invoice_total_gst' => 0,
                                                        'invoice_grandtotal' => 0,
                                                        'company_id' => $selected_company->company_id,
                                                        'company_land_id' => $selected_company_land->company_land_id,
                                                        'company_bank_id' => $selected_company_land->company_bank_id,
                                                        'user_id' => $user->user_id,
                                                        'invoice_status_id' => 2,
                                                        'invoice_no' => 'IN/' . $selected_company->company_code . '/' . $running_no,
                                                        'invoice_created' => now(),
                                                        'invoice_updated' => now(),
                                                        'invoice_date' => $invoice_date,
                                                        'invoice_remark' => @$invoice_remark
                                                    ]);

                                                    foreach($product_list as $index => $import_product){
                                                        $column = ($index+1) * 3;

                                                        if($rows[$column] && $rows[$column+1]){
                                                            $quantity = (float)$rows[$column];
                                                            $price = (float)$rows[$column+1];
                                                            $sub_total = $quantity * $price;

                                                            InvoiceItem::create([
                                                                'invoice_id' => $invoice->invoice_id,
                                                                'product_id' => $db_product[$import_product]['product_id'],
                                                                'setting_product_size_id' => $db_product[$import_product]['setting_product_size_id'],
                                                                'invoice_item_name' => $db_product[$import_product]['product_name'],
                                                                'invoice_item_price' => $price,
                                                                'invoice_item_quantity' => $quantity,
                                                                'invoice_item_subtotal' => $sub_total,
                                                                'invoice_item_discount' => 0,
                                                                'invoice_item_total' => $sub_total,
                                                                'invoice_item_created' => now(),
                                                                'invoice_item_updated' => now(),
                                                                'delivery_order_item_id' => 0
                                                            ]);

                                                            $total += $sub_total;
                                                        }
                                                    }

                                                    $invoice_total_round_up = $request->input('invoice_total_round_up') ?? 0;
                                                    $gst_val = Setting::get_by_slug('g_s_t');

                                                    if ($selected_company->company_enable_gst != 1) {
                                                        $gst_val = 0;
                                                    }

                                                    $invoice_total_gst = ($total * $gst_val) / 100;
                                                    $invoice_grandtotal = ($total + $invoice_total_gst) + $invoice_total_round_up;

                                                    Invoice::where('invoice_id',$invoice->invoice_id)->update([
                                                        'invoice_subtotal' => $total,
                                                        'invoice_total_discount' => 0,
                                                        'invoice_total' => $total,
                                                        'invoice_total_gst' => $invoice_total_gst,
                                                        'invoice_grandtotal' => $invoice_grandtotal,
                                                        'invoice_total_round_up' => $invoice_total_round_up,
                                                    ]);

                                                    InvoicePayment::create([
                                                        'invoice_id' => $invoice->invoice_id,
                                                        'invoice_payment_amount' => $invoice_grandtotal,
                                                        'setting_payment_id' => 1, //remark: hardcoded to CASH
                                                        'invoice_payment_created' => now(),
                                                        'invoice_payment_updated' => now(),
                                                        'invoice_payment_data' => '',
                                                        'is_deleted' => 0
                                                    ]);

                                                    InvoiceLog::insert([
                                                        'invoice_id' => $invoice->invoice_id,
                                                        'invoice_log_created' => now(),
                                                        'invoice_log_description' => 'Invoice Created By ' . $user->user_fullname,
                                                        'invoice_log_action' => 'Add',
                                                        'user_id' => $user->user_id
                                                    ]);

                                                    $result[$key+4]['status'] = 'green';
                                                    $result[$key+4]['invoice_date'] = @$rows[0] ? $this->transformDate($rows[0]) : '';
                                                    $result[$key+4]['customer_company_name'] =  @$rows[1] ?? '';
                                                    $result[$key+4]['invoice_remark'] = $rows[2] ?? '';
                                                }
                                                else{
                                                    if($existing_invoice){
                                                        $result[$key+4]['status'] = 'green';
                                                        $result[$key+4]['invoice_date'] = @$rows[0] ? $this->transformDate($rows[0]) : '';
                                                        $result[$key+4]['customer_company_name'] =  @$rows[1] ?? '';
                                                        $result[$key+4]['invoice_remark'] = $rows[2] ?? '';
                                                        $result[$key+4]['remark'] = $problem_msg;
                                                    }else{
                                                        $result[$key+4]['status'] = 'red';
                                                        $result[$key+4]['invoice_date'] = @$rows[0] ? $this->transformDate($rows[0]) : '';
                                                        $result[$key+4]['customer_company_name'] =  @$rows[1] ?? '';
                                                        $result[$key+4]['invoice_remark'] = $rows[2] ?? '';
                                                        $result[$key+4]['remark'] = $problem_msg;
                                                    }
                                                }
                                            }else{
                                                $rows_validation = FALSE;
                                                $result[$key+4]['invoice_date'] = '-';
                                                $result[$key+4]['customer_company_name'] = '-';
                                                $result[$key+4]['invoice_remark'] =  '-';
                                                $result[$key+4]['status'] = 'yellow';
                                                $result[$key+4]['remark'] = '- Blank space';
                                            }
                                            break;
                                        }

                                    if($big_problem){
                                        Session::flash("fail_msg", $remark);
                                        return redirect()->route('invoice_import');
                                        die();
                                    }
                                }
                            }
                        }else{
                            Session::flash("fail_msg", 'Empty file');
                            return redirect()->route('invoice_import');
                            die();
                        }
                    }
                } else {
                    Session::flash("fail_msg", "Please upload a file.");
                    return redirect()->route('invoice_import');
                    die();
                }
            }
            $post = $request->all();
        }

        return view('invoice.import', [
            'title' => 'Invoice Import',
            'company_sel' => Company::get_company_sel(),
            'post' => $post,
            'submit' => route('invoice_import'),
            'reamrk' => $remark,
            'result' => $result
        ])->withErrors($validation);
    }

    public function export_product_list(){
        return Excel::download(new ProductListExport, 'product_list.xlsx');
    }

    public function seo_friendly_url($string){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        return strtolower(trim($string, '-'));
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function invoice_by_price($price, $product, $company_land_name, $date)
    {
        $product = explode(' - ',$product);
        $product_id = Product::where('product_name', $product[0])->value('product_id');
        $product_size_id = SettingSize::where('setting_product_size_name', $product[1])->value('setting_product_size_id');
        $search['invoice_item_price'] = $price;
        $search['company_land_name'] = $company_land_name;
        $search['iv_created_from'] = $search['iv_created_to'] = $date;
        $search['product_id'] = $product_id;
        $search['product_size_id'] = $product_size_id;
        \Illuminate\Support\Facades\Session::put('invoice_search', $search);
        return redirect()->route('invoice_listing');
    }

}
