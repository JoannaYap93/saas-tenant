<?php

namespace App\Http\Controllers;

use App\Model\SettingWarehouse;
use App\Model\Company;
use App\Model\MessageTemplate;
use App\Model\MessageTemplateInvolve;
use App\Model\MessageTemplateInvolveLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Auth;

class MessageTemplateInvolveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $perpage = 15;
        $validation = null;

        if (isset($_GET['reset'])) {
            Session::forget('filter_message_template_involve');
        }
        if ($request->isMethod('post')) {

            $search['freetext'] = $request->input('freetext');
            Session::put('filter_message_template_involve', $search);
        }
        $search = Session::has('filter_message_template_involve') ? Session::get('filter_message_template_involve') : array();

        $records = MessageTemplateInvolve::get_records($search, $perpage);

        return view('message_template_involve.listing', [
            'page_title' => 'Message Template Involve Listing',
            'records' => $records,
            'search' =>  $search,
            'submit' => route('message_template_involve_listing'),
        ]);
    }

    public function add(Request $request)
    {
        $post = array();
        $perpage = 0;
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'message_template_involve_type' => 'required',
            ])->setAttributeNames([
                'message_template_involve_type' => 'Type',
            ]);
            if (!$validation->fails()) {

                $filtered_space_slug = str_replace(" ", "-", strtolower(trim($request->input('message_template_involve_type'))));
                $slug = str_replace(['(', ')', '/'], ['', '', ''], $filtered_space_slug);

                $template_id = MessageTemplateInvolve::insertGetId([
                    'message_template_involve_type' => $request->input('message_template_involve_type'),
                    'message_template_involve_slug' => $slug,
                    'message_template_involve_created' => now(),
                    'message_template_involve_updated' => now(),
                    'is_deleted' => 0
                ]);

                return redirect()->route('message_template_involve_listing');
            }
            $post = $request->all();
        }

        return view('message_template_involve.form', [
            'page_title' => 'Add Message Template Involve',
            'post' => $post,
            'submit' => route('message_template_involve_add'),
        ])->withErrors($validation);
    }


    public function edit(Request $request, $message_template_involve_id)
    {
        $post = array();
        $perpage = 0;
        $validation = null;

        $post = MessageTemplateInvolve::get_by_id($message_template_involve_id);

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'message_template_involve_type' => 'required',
            ])->setAttributeNames([
                'message_template_involve_type' => 'Type',
            ]);
            if (!$validation->fails()) {

                $filtered_space_slug = str_replace(" ", "-", strtolower(trim($request->input('message_template_involve_type'))));
                $slug = str_replace(['(', ')', '/'], ['', '', ''], $filtered_space_slug);

                MessageTemplateInvolve::where([
                    'message_template_involve_id' => $message_template_involve_id
                ])->update([
                    'message_template_involve_type' => $request->input('message_template_involve_type'),
                    'message_template_involve_slug' => $slug,
                    'message_template_involve_updated' => now(),
                ]);

                return redirect()->route('message_template_involve_listing');
            }
            $post = $request->all();
        }

        return view('message_template_involve.form', [
            'page_title' => 'Edit Message Involve Template',
            'post' => $post,
            'submit' => route('message_template_involve_edit', ['id' => $message_template_involve_id]),
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $message_template_involve_id = MessageTemplate::find($request->input('message_template_involve_id'));

        if(!$message_template_involve_id){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('message_template_involve_listing');
        }

        $message_template_involve_id->update([
            'is_deleted'=> 1,
        ]);


        Session::flash('success_msg', "Successfully delete Message Template Involve.");
        return redirect()->route('message_template_involve_listing');
    }
}
