<?php

namespace App\Http\Controllers;

use Session;
use App\Model\User;
use App\Model\Company;
use App\Model\Invoice;
use App\Model\MessageLog;
use App\Model\PaymentUrl;
use App\Model\DeliveryOrder;
use Illuminate\Http\Request;
use App\Model\MessageTemplate;
use App\Model\SettingWarehouse;
use Illuminate\Support\Facades\Auth;
use App\Model\MessageTemplateInvolve;
use App\Model\MessageTemplateInvolveLink;
use Illuminate\Support\Facades\Validator;

class MessageTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $perpage = 10;
        $validation = null;

        if (isset($_GET['reset'])) {
            Session::forget('message_template_search');
        }
        if ($request->isMethod('post')) {

            $search['freetext'] = $request->input('freetext');
            $search['message_template_involve_id'] = $request->input('message_template_involve_id');

            Session::put('message_template_search', $search);
        }
        $search = Session::has('message_template_search') ? Session::get('message_template_search') : array();

        return view('message_template.listing', [
            'page_title' => 'Message Template Listing',
            'records' => MessageTemplate::get_records($search, $perpage),
            'involve_sel' => MessageTemplateInvolve::get_as_selection(),
            'search' =>  $search,
            'submit' => route('message_template_listing', ['tenant' => tenant('id')]),
        ]);
    }

    public function add(Request $request)
    {
        $user = auth()->user();
        $validation = null;

        $post = array();
        $post['message_template_involve_id'] = [];

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'message_template_name' => 'required',
                'message_template_content' => 'required',
                'message_template_mobile_no' => 'required',
            ])->setAttributeNames([
                'message_template_name' => 'Name',
                'message_template_content' => 'Contents',
                'message_template_mobile_no' => 'Mobile No',

            ]);
            if (!$validation->fails()) {

                $mobile_no = str_replace(['-', '+', ' '], ['', '', ''], trim($request->input('message_template_mobile_no')));
                if (substr($mobile_no, 0, 1) == '0') {
                    $mobile_no = '6' . $mobile_no;
                }

                $message_template_id = MessageTemplate::insertGetId([
                    'message_template_name' => $request->input('message_template_name'),
                    'message_template_content' => $request->input('message_template_content'),
                    'message_template_mobile_no' => $mobile_no,
                    'message_template_status' => $request->input('message_template_status') ? $request->input('message_template_status') : 0,
                    'message_template_created' => now(),
                    'message_template_updated' => now(),
                    'admin_id' => $user->user_id,
                    'is_reporting' => $request->input('is_reporting') ? $request->input('is_reporting') : 0,
                    'is_deleted' => 0
                ]);

                if ($request->input('message_template_involve_id')) {
                    foreach ($request->input('message_template_involve_id') as $message_template_involve_id) {
                        MessageTemplateInvolveLink::insert([
                            'message_template_involve_id' => $message_template_involve_id,
                            'message_template_id' => $message_template_id
                        ]);
                    }
                }

                return redirect()->route('message_template_listing', ['tenant' => tenant('id')]);
            }
            $post = $request->all();
            $post['message_template_involve_id'] = $request->input('message_template_involve_id') ? $request->input('message_template_involve_id') : [];
        }

        $involve_sel =  MessageTemplateInvolve::get_as_selection();

        return view('message_template.form', [
            'page_title' => 'Add Message Template',
            'involve_sel' => $involve_sel,
            'post' => $post,
            'submit' => route('message_template_add', ['tenant' => tenant('id')]),
        ])->withErrors($validation);
    }

    public function edit(Request $request, $message_template_id)
    {
        $user = auth()->user();
        $validation = null;

        $post = array();
        $post = MessageTemplate::get_by_id($message_template_id);
        $post['message_template_involve_id'] = $post->message_template_involve_link ? $post->message_template_involve_link->pluck('message_template_involve_id')->toArray() : [];

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'message_template_name' => 'required',
                'message_template_content' => 'required',
                'message_template_mobile_no' => 'required',
            ])->setAttributeNames([
                'message_template_name' => 'Name',
                'message_template_content' => 'Contents',
                'message_template_mobile_no' => 'Mobile No',
            ]);
            if (!$validation->fails()) {

                $mobile_no = str_replace(['-', '+', ' '], ['', '', ''], trim($request->input('message_template_mobile_no')));
                if (substr($mobile_no, 0, 1) == '0') {
                    $mobile_no = '6' . $mobile_no;
                }

                MessageTemplate::where([
                    'message_template_id' => $message_template_id
                ])->update([
                    'message_template_name' => $request->input('message_template_name'),
                    'message_template_content' => $request->input('message_template_content'),
                    'message_template_mobile_no' => $mobile_no,
                    'message_template_status' => $request->input('message_template_status') ? $request->input('message_template_status') : 0,
                    'message_template_updated' => now(),
                    'is_reporting' => $request->input('is_reporting') ? $request->input('is_reporting') : 0,
                    'admin_id' => $user->user_id,
                ]);

                $initial_involve = array();

                if ($post['message_template_involve_id']) {
                    foreach ($post['message_template_involve_id'] as $involve) {
                        array_push($initial_involve, $involve);
                    }
                }

                $new_involve_array = $request->input('message_template_involve_id');

                $involve_delete_array = [];
                $involve_add_array = [];
                $involve_delete_array = $new_involve_array ? array_diff($initial_involve, $new_involve_array) : $initial_involve;
                $involve_add_array = $new_involve_array ?  array_diff($new_involve_array, $initial_involve) : [];

                if (isset($involve_delete_array)) {
                    foreach ($involve_delete_array as $message_template_involve_id) {
                        MessageTemplateInvolveLink::where([
                            'message_template_involve_id' => $message_template_involve_id,
                            'message_template_id' => $message_template_id
                        ])->delete();
                    }
                }

                if (isset($involve_add_array)) {
                    foreach ($involve_add_array as $message_template_involve_id) {
                        MessageTemplateInvolveLink::firstOrCreate(
                            ['message_template_involve_id' => $message_template_involve_id, 'message_template_id' => $message_template_id],
                            ['message_template_involve_id' => $message_template_involve_id, 'message_template_id' => $message_template_id]
                        );
                    }
                }

                return redirect()->route('message_template_listing', ['tenant' => tenant('id')]);
            }
            $post = $request->all();
            $post['message_template_involve_id'] = $request->input('message_template_involve_id') ? $request->input('message_template_involve_id') : [];
        }

        $involve_sel =  MessageTemplateInvolve::get_as_selection();

        return view('message_template.form', [
            'page_title' => 'Edit Message Template',
            'involve_sel' => $involve_sel,
            'post' => $post,
            'submit' => route('message_template_edit', ['tenant' => tenant('id'), 'id' => $message_template_id]),
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $message_template_id = MessageTemplate::find($request->input('message_template_id'));

        if (!$message_template_id) {
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('message_template_listing', ['tenant' => tenant('id')]);
        }

        $message_template_id->update([
            'is_deleted' => 1,
        ]);

        Session::flash('success_msg', "Successfully delete Message Template.");
        return redirect()->route('message_template_listing', ['tenant' => tenant('id')]);
    }

    public function send_whatsapp_template($id, $slug, $message_template_id, $user_mobile = null)
    {
        $admin = Auth::user();
        // $user = Auth::user();
        $message_template = MessageTemplate::get_by_id($message_template_id);
        switch ($slug) {
            case 'do-listing':
                $records = DeliveryOrder::get_by_id($id);
                $encryption = md5($records->delivery_order_id . env('ENCRYPTION_KEY'));
                $message_template->message_template_content = str_replace("[ADMIN_NAME]", @$admin->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[ADMIN_MOBILE_NO]", @$admin->user_mobile, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[CUSTOMER_NAME]", @$records->customer->customer_name, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[SHORT_CUSTOMER_NAME]", substr(@$records->customer->customer_name, 0, 8) . "..", $message_template->message_template_content);

                $message_template->message_template_content = str_replace("[VERIFY_PRICE_URL]", env('GRAPHQL_API') . '/'. tenant('id') . '/price_verification/' . $id . '/' . $encryption, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[DO_DETAILS]", route('do_pdf', ['tenant' => tenant('id'), 'id' => $records->delivery_order_id, 'encryption' => md5($records->delivery_order_id . env('ENCRYPTION_KEY'))]), $message_template->message_template_content);
                $user_mobile = $user_mobile ?? @$records->customer->customer_mobile_no;

                // $user_mobile = $user_mobile ? $user_mobile : $records->customer->customer_mobile_no;

                if ($user_mobile[0] == "+") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . str_replace("+", "", $user_mobile) . "&text=" . $message_template->message_template_content;
                } else if ($user_mobile[0] == "0") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=6" . $user_mobile . "&text=" . $message_template->message_template_content;
                } else if ($user_mobile[0] == "6") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . $user_mobile . "&text=" . $message_template->message_template_content;
                } else {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . $user_mobile . "&text=" . $message_template->message_template_content;
                }

                $message_ref_id = $records->delivery_order_id;
                $user_id = $admin->user_id;
                $customer_id = $records->customer_details->customer_id;

                break;
            case 'followup':
                $records = DeliveryOrder::get_by_id($id);
                $encryption = md5($records->delivery_order_id . env('ENCRYPTION_KEY'));
                $message_template->message_template_content = str_replace("[ADMIN_NAME]", @$admin->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[ADMIN_MOBILE_NO]", @$admin->user_mobile, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[CUSTOMER_NAME]", @$records->customer->customer_name, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[SHORT_CUSTOMER_NAME]", substr(@$records->customer->customer_name, 0, 8) . "..", $message_template->message_template_content);

                $message_template->message_template_content = str_replace("[VERIFY_PRICE_URL]", env('GRAPHQL_API') . '/'. tenant('id') . '/price_verification/' . $id . '/' . $encryption, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[DO_DETAILS]", route('do_pdf', ['tenant' => tenant('id'), 'id' => $records->delivery_order_id, 'encryption' => md5($records->delivery_order_id . env('ENCRYPTION_KEY'))]), $message_template->message_template_content);
                $user_mobile = $user_mobile ?? @$records->customer->customer_mobile_no;

                // $user_mobile = $user_mobile ? $user_mobile : $records->customer->customer_mobile_no;

                if ($user_mobile[0] == "+") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . str_replace("+", "", $user_mobile) . "&text=" . $message_template->message_template_content;
                } else if ($user_mobile[0] == "0") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=6" . $user_mobile . "&text=" . $message_template->message_template_content;
                } else if ($user_mobile[0] == "6") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . $user_mobile . "&text=" . $message_template->message_template_content;
                } else {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . $user_mobile . "&text=" . $message_template->message_template_content;
                }

                $message_ref_id = $records->delivery_order_id;
                $user_id = $admin->user_id;
                $customer_id = $records->customer_details->customer_id;

                break;
            case 'invoice-listing':

                $records = Invoice::get_by_id($id);
                $encryption = md5($records->invoice_id . env('ENCRYPTION_KEY'));

                $message_template->message_template_content = str_replace("[ADMIN_NAME]", @$admin->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[ADMIN_MOBILE_NO]", @$admin->user_mobile, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[CUSTOMER_NAME]", @$records->customer->customer_name, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[SHORT_CUSTOMER_NAME]", substr(@$records->customer->customer_name, 0, 8) . "..", $message_template->message_template_content);

                $message_template->message_template_content = str_replace("[INVOICE_URL]", env('GRAPHQL_API') . '/'. tenant('id') . '/invoice/' . $records->invoice_id . '/' . $encryption, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[RECEIPT_URL]", env('GRAPHQL_API') . '/'. tenant('id') . '/receipt/' . $records->invoice_id . '/' . $encryption, $message_template->message_template_content);

                // $message_template->message_template_content = str_replace("[INVOICE_URL]", route('view_invoice_pdf', ['tenant' => tenant('id'), 'id' => $records->invoice_id, 'encryption' => $encryption]), $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[PAYMENT_URL]",  env('GRAPHQL_API') . '/'. tenant('id') . '/view_invoice/' . $records->invoice_id . '/' . $encryption, $message_template->message_template_content);

                if (@$user_mobile[0] == "+") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . str_replace("+", "", @$user_mobile) . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "0") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=6" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "6") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                }

                $message_ref_id = $records->invoice_id;
                $user_id = $admin->user_id;
                $customer_id = $records->customer->customer_id;

                break;

            case 'payment-url-listing':

                $records = PaymentUrl::find($id);
                $encryption = md5($records->payment_url_id . env('ENCRYPTION_KEY'));

                $message_template->message_template_content = str_replace("[ADMIN_NAME]", @$admin->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[ADMIN_MOBILE_NO]", @$admin->user_mobile, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[CUSTOMER_NAME]", @$records->customer->customer_name, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[SHORT_CUSTOMER_NAME]", substr(@$records->customer->customer_name, 0, 8) . "..", $message_template->message_template_content);

                $message_template->message_template_content = str_replace("[RECEIPT_URL]",  env('GRAPHQL_API') . '/'. tenant('id') . '/payment-url-receipt/' . $records->payment_url_id . '/' . $encryption, $message_template->message_template_content);

                if (@$user_mobile[0] == "+") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . str_replace("+", "", @$user_mobile) . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "0") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=6" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "6") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                }

                $message_ref_id = $records->payment_url_id;
                $user_id = $admin->user_id;
                $customer_id = $records->customer->customer_id;

                break;

            default:
                $user = User::find($id);
                $message_template->message_template_content = str_replace("[ADMIN_NAME]", @$admin->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[ADMIN_MOBILE_NO]", @$admin->user_mobile, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[CUSTOMER_NAME]", @$user->user_fullname, $message_template->message_template_content);
                $message_template->message_template_content = str_replace("[SHORT_CUSTOMER_NAME]", substr(@$user->user_fullname, 0, 8) . "..", $message_template->message_template_content);

                if (@$user_mobile[0] == "+") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . str_replace("+", "", @$user_mobile) . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "0") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=6" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else if (@$user_mobile[0] == "6") {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                } else {
                    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . @$user_mobile . "&text=" . $message_template->message_template_content;
                }

                $message_ref_id = null;
                $slug = null;
                $user_id = $admin->user_id;
                // $user_id = $user->user_id;
                $customer_id = 0;
        }

        $message_log_id = MessageLog::create([
            'user_id' => $user_id,
            'customer_id' => $customer_id,
            'message_template_id' => $message_template_id,
            'message_log_contents' => $message_template->message_template_content,
            'message_log_status' => 1,
            'message_log_slug' => $slug,
            'message_log_ref_id' => $message_ref_id,
            'message_log_created' => now()
        ]);

        return view('message_template.redirect', [
            'whatsapp_url' => $whatsapp_url,
        ]);
    }
}
