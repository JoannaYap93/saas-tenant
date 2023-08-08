<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Customer;
use App\Model\CustomerCategory;
use App\Model\DeliveryOrder;
use App\Model\DeliveryOrderItem;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\InvoiceLog;
use App\Model\InvoicePayment;
use App\Model\InvoiceStatus;
use App\Model\MessageTemplate;
use App\Model\PaymentUrl;
use App\Model\PaymentUrlItem;
use App\Model\PaymentUrlLog;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\RunningNumber;
use App\Model\Setting;
use App\Model\SettingPayment;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class PaymentUrlController extends Controller
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
                    session(['payment_url_search' => [
                        'freetext' => $request->input('freetext'),
                        'pu_from' => $request->input('pu_from'),
                        'pu_to' => $request->input('pu_to'),
                        'customer_id' => $request->input('customer_id'),
                        'customer_category_id' => $request->input('customer_category_id'),
                        'payment_url_status' => $request->input('payment_url_status'),
                        'company_id' => $request->input('company_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('payment_url_search');
                    break;
            }
        }

        $search = session('payment_url_search') ?? array();
        $payment_url = PaymentUrl::get_records($search);
        // $is_approved = ['' => 'Please Select Approval...', '0' => 'Not Yet Approved', '1' => 'Approved'];
        $message_templates = MessageTemplate::get_by_slug('payment-url-listing');

        return view('payment_url.listing', [
            'records' => $payment_url,
            'search' => $search,
            'submit' => route('payment_url_listing'),
            'pu_status' => PaymentUrl::get_payment_url_status(),
            'count_status' => PaymentUrl::count_status($search),
            'customer_sel' => ['' => 'Please Select Customer'] + Customer::get_customer_sel(),
            'customer_category_sel' => CustomerCategory::customer_category_for_report_sel(),
            'company_sel' => Company::get_company_sel(),
            'expiry_period' => Setting::get_by_slug('payment_url_expiry_period'),
            'message_templates' => $message_templates
        ]);
    }

    public function add(Request $request, $customer_id = null, $invoice_id = null)
    {
        $user = auth()->user();
        $validate = null;
        if ($request->isMethod('post')) {
            $validate = Validator::make($request->all(), [
                'customer_id' => 'required',
                'invoice_ids' => 'required|array|min:1',
            ]);

            if (!$validate->fails()) {
                $payment_url = PaymentUrl::create([
                    'customer_id' => $request->input('customer_id'),
                    'payment_url_created' => now(),
                    'payment_url_updated' => now(),
                    'payment_url_status' => 'Pending',
                    'user_id' => $user->user_id
                ]);
                $payment_url_total = 0;
                foreach($request->input('invoice_ids') as $invoice_id){
                    $invoice = Invoice::find($invoice_id);
                    if($invoice){
                        PaymentUrlItem::create([
                            'payment_url_id' => $payment_url->payment_url_id,
                            'invoice_id' => $invoice_id,
                            'invoice_total' => $invoice->invoice_grandtotal,
                            'payment_url_item_created' => now(),
                        ]);
                    }
                    $payment_url_total += $invoice->invoice_grandtotal;
                }

                $payment_url->update([
                    'payment_url_total' => $payment_url_total
                ]);

                PaymentUrlLog::insert([
                    'payment_url_id' => $payment_url->payment_url_id,
                    'payment_url_log_action' => 'Create',
                    'payment_url_log_description' => 'Payment Url created by '. $user->user_name,
                    'payment_url_log_created' => now(),
                    'user_id' => $user->user_id,
                    'customer_id' => $payment_url->customer_id
                ]);

                Session::flash('success_msg', 'Successfully Added New Payment Url');
                Session::forget('payment_url_search');
                    return redirect()->route('payment_url_listing');
            } else {
                Session::flash('fail_msg', 'Error when creating payment_url...');
                return redirect()->back()->withErrors($validate);
            }
        }
        $customer = Customer::find($customer_id);
        $invoice = Invoice::find($invoice_id);
        return view('payment_url.form', [
            'title' => 'Add',
            'customer_sel' => Customer::get_customer_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'submit' => route('payment_url_add'),
            'customer' => $customer,
            'invoice_id' => $invoice_id,
            'invoice' => $invoice,

        ])->withErrors($validate);
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
            // dd($request->all());
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
                ]);

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
            'submit' => route('invoice_edit', $id),
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
        ])->withErrors($validation);
    }

    public function approve_reject(Request $request)
    {
        $user = Auth::user();
        $payment_url_id = $request->input('payment_url_id');
        $status = $request->input('status');

        if (!$payment_url_id) {
            Session::flash('failed_msg', "Invalid Invoice, Please Try Again...");
            return redirect()->route('payment_url_listing');
        }

        $payment_url = PaymentUrl::get_payment_url_details_by_id($payment_url_id);

        if ($status == 'approve') {
            $payment_url->update([
                'payment_url_updated' => now(),
                'payment_url_status' => 'Paid'
            ]);

            PaymentUrlLog::insert([
                'payment_url_id' => $payment_url->payment_url_id,
                'payment_url_log_created' => now(),
                'payment_url_log_action' => "Approve",
                'payment_url_log_description' => 'Payment Approved by ' . $user->user_fullname,
                'user_id' => $user->user_id,
                'customer_id' => $payment_url->customer->customer_id
            ]);
            foreach($payment_url->payment_url_item as $item){
                $invoice = $item->invoice;
                if(@$invoice->invoice_status_id == 5){
                    $invoice->update([
                        'invoice_status_id' => $invoice->invoice_amount_remaining > 0 ? 6 : 2,
                        'is_approved' => 1,
                        'is_approved_date' => now()
                    ]);
                    InvoiceLog::insert([
                        'invoice_id' => $invoice->invoice_id,
                        'invoice_log_created' => now(),
                        'invoice_log_description' => 'Invoice Approved by ' . $user->user_fullname . 'from Payment Url #'. $payment_url->payment_url_id,
                        'invoice_log_action' => "Approve",
                        'user_id' => $user->user_id,
                    ]);
                }
            }

            Session::flash('success_msg', "Payment Approved");
            return redirect()->route('payment_url_listing');

        } elseif ($status == 'reject') {
            $payment_url->update([
                'payment_url_updated' => now(),
                'payment_url_status' => 'Pending'
            ]);

            PaymentUrlLog::insert([
                'payment_url_id' => $payment_url->payment_url_id,
                'payment_url_log_created' => now(),
                'payment_url_log_action' => "Reject",
                'payment_url_log_description' => 'Payment Rejected by ' . $user->user_fullname,
                'user_id' => $user->user_id,
                'customer_id' => $payment_url->customer->customer_id
            ]);

            foreach($payment_url->payment_url_item as $item){
                $invoice = $item->invoice;
                if(@$invoice->invoice_status_id == 5 || @$invoice->invoice_status_id == 6){

                    $invoice->update([
                        'invoice_status_id' => 1,
                        'invoice_amount_paid' => 0,
                        'invoice_amount_remaining' => 0,
                    ]);

                    InvoiceLog::insert([
                        'invoice_id' => $invoice->invoice_id,
                        'invoice_log_created' => now(),
                        'invoice_log_description' => 'Payment Rejected by ' . $user->user_fullname .'for Payment Url #'.$payment_url->payment_url_id,
                        'invoice_log_action' => "Reject",
                        'user_id' => $user->user_id,
                    ]);
                }
            }
            Session::flash('success_msg', "Payment Rejected");
            return redirect()->route('payment_url_listing');
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
        return redirect($url);
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
        // dd($encryp, $encryption);
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
                // dd($pdf);
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
                // dd($pdf);
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

    public function ajax_find_payment_url_with_id(Request $request)
    {
        $payment_url_id = $request->input('payment_url_id');
        return PaymentUrl::get_payment_url_details_by_id($payment_url_id, true);
    }

    public function ajax_get_mobile_no_by_payment_url_id(Request $request){
        $payment_url_id = $request->input('payment_url_id');
        $payment_url = PaymentUrl::find($payment_url_id);

        $customer_name = @$payment_url->customer->customer_name;
        $customer_mobile_no = @$payment_url->customer->customer_mobile_no;
        $customer_acc_name = @$payment_url->customer->customer_acc_name;
        $customer_acc_mobile_no = @$payment_url->customer->customer_acc_mobile_no;
        $company_acc_name = @$payment_url->company->company_acc_name;
        $company_acc_mobile_no = @$payment_url->company->company_acc_mobile_no;

        return response()->json([
            'customer_mobile_no' => $customer_mobile_no,
            'customer_name' => $customer_name,
            'company_acc_mobile_no' => $company_acc_mobile_no,
            'company_acc_name' => $company_acc_name,
            'customer_acc_mobile_no' => $customer_acc_mobile_no,
            'customer_acc_name' => $customer_acc_name,
        ]);
    }

    public function cancel(Request $request){
        $payment_url_id = $request->input('payment_url_id');
        $payment_url = PaymentUrl::find($payment_url_id);
        if(@$payment_url->payment_url_status == 'Pending'){
            $payment_url->update([
                'payment_url_status' => 'Cancelled',
            ]);
            PaymentUrlLog::insert([
                'payment_url_id' => $payment_url->payment_url_id,
                'payment_url_log_action' => 'Cancelled',
                'payment_url_log_description' => @$request->input('payment_url_log_description'),
                'payment_url_log_created' => now(),
                'user_id' => Auth::id(),
                'customer_id' => $payment_url->customer_id,
            ]);
            Session::flash('success_msg', "Succefully Cancelled Payment Url");
            return redirect()->route('payment_url_listing');
        }else{
            Session::flash('failed_msg', "Invalid Payment Url");
            return redirect()->route('payment_url_listing');
        }
    }
}
