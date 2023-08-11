<?php

namespace App\Http\Controllers;

use App\Model\CustomerTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerTermController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth', 'user_type']);
    // }

    public function listing(Request $request)
    {
        $perpage = 15;
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['customer_term_search' => [
                        "freetext" =>  $request->input('freetext'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('customer_term_search');
                    break;
            }
        }
        $search = session('customer_term_search') ? session('customer_term_search') : $search;

        return view('customer_term.listing', [
            'submit' => route('customer_term_listing', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'records' =>  CustomerTerm::get_record($search, $perpage),
            'search' =>  $search,
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'customer_term_name' => 'required',
            ])->setAttributeNames([
                'customer_term_name' => 'Term Name',
            ]);

            if (!$validator->fails()) {

                $customer = CustomerTerm::create([
                    'customer_term_name' => $request->input('customer_term_name'),
                    'customer_term_created' => now(),
                    'customer_term_created' => now(),
                    'company_id' => auth()->user()->company_id
                ]);

                Session::flash('success_msg', 'Successfully added ' . $request->input('customer_term_name'));
                return redirect()->route('customer_term_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('customer_term.form', [
            'submit' => route('customer_term_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);
    }

    public function edit(Request $request, $customer_term_id)
    {
        $validator = null;

        $post = CustomerTerm::where('customer_term_id', $customer_term_id);

        if (auth()->user()->company_id != 0) {
            $post = $post->where('company_id', auth()->user()->company_id)->first();
        } else {
            $post = $post->first();
        }

        if (!$post) {
            Session::flash('fail_msg', 'Invalid Customer Term, Please try another one.');
            return redirect()->route('customer_term_listing', ['tenant' => tenant('id')]);
        }

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'customer_term_name' => 'required',
            ])->setAttributeNames([
                'customer_term_name' => 'Term Name',
            ]);

            if (!$validator->fails()) {

                $update_detail = [
                    'customer_term_name' => $request->input('customer_term_name'),
                    'customer_term_updated' => now(),
                ];

                $post->update($update_detail);

                Session::flash('success_msg', 'Successfully edited ' . $request->input('customer_term_name'));
                return redirect()->route('customer_term_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('customer_term.form', [
            'submit' => route('customer_term_edit', ['tenant' => tenant('id'), 'id' => $customer_term_id]),
            'title' => 'Edit',
            'post' => $post,
        ])->withErrors($validator);
    }

    public function delete(Request $request)
    {
        $customer_term_id = CustomerTerm::where('customer_term_id', $request->input('customer_term_id'));

        if (auth()->user()->company_id != 0) {
            $customer_term_id = $customer_term_id->where('company_id', auth()->user()->company_id)->first();
        } else {
            $customer_term_id = $customer_term_id->first();
        }

        if (!$customer_term_id) {
            Session::flash('fail_msg', 'Invalid Customer Term, Please try another one.');
            return redirect()->route('customer_term_listing', ['tenant' => tenant('id')]);
        }

        $customer_term_id->update([
            'is_deleted' => 1,
        ]);

        Session::flash('success_msg', "Successfully deleted Term.");
        return redirect()->route('customer_term_listing', ['tenant' => tenant('id')]);
    }
}
