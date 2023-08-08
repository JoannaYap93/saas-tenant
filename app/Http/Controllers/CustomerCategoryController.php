<?php

namespace App\Http\Controllers;

use App\Model\CustomerCategory;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function listing(Request $request)
    {
        $perpage = 10;
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_customer_category' => [
                        'freetext' => $request->input('freetext'),
                        'is_deleted' => $request->input('is_deleted'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_customer_category');
                    break;
            }
        }

        $search = session('filter_customer_category') ? session('filter_customer_category') : $search;
        $customerCategory = CustomerCategory::get_records($search, 10);

        return view('customer_category.listing', [
            'submit' => route('customer_category_listing'),
            'records' => $customerCategory,
            'title' => 'Add',
            'customerCategory' =>  $customerCategory, //User::get_record($search, 15, $pageNumber, [1]),
            'customer_is_deleted_sel' => ['' => 'Please select status', '0' => 'No', '1' => 'Yes'],
        ]);
    }


    public function add(Request $request)
    {
        $validation = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'customer_category_name' => 'required',
                'customer_category_status' => 'required',
                'customer_category_priority' => 'required|numeric|max:5',
            ])->setAttributeNames([
                'customer_category_name' => 'Name',
                'customer_category_status' => 'Status',
                'customer_category_priority' => 'Priority',
            ]);
            if (!$validation->fails()) {
                $customerCategory = CustomerCategory::create([
                    'customer_category_name' => $request->input('customer_category_name'),
                    'customer_category_created' => now(),
                    'customer_category_updated' => now(),
                    'customer_category_status' => $request->input('customer_category_status'),
                    'is_deleted' => 0,
                    'customer_category_priority' => $request->input('customer_category_priority'),
                    'company_id' => auth()->user()->company_id
                ]);

                Session::flash('success_msg', 'Successfully added ' . $request->input('customer_category_name'));
                return redirect()->route('customer_category_listing');
            }
            $post = (object) $request->all();
        }

        return view('customer_category.form', [
            'submit' => route('customer_category_add'),
            'edit' => false,
            'post' => $post,
            'title' => 'Add',
            'status' => ['' => 'Please select status', 'inactive' => 'Inactive', 'active' => 'Active']
        ])->withErrors($validation);
    }

    public function edit(Request $request, $customer_category_id)
    {
        $post = CustomerCategory::where('customer_category_id', $customer_category_id)->where('company_id', auth()->user()->company_id)->first();

        if (!$post) {
            Session::flash('fail_msg', 'Invalid Customer Category! Please try another one.');
            return redirect()->route('customer_category_listing');
        }

        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'customer_category_name' => 'required',
                'customer_category_status' => 'required',
                'customer_category_priority' => 'required|numeric|max:5',
            ])->setAttributeNames([
                'customer_category_name' => 'Name',
                'customer_category_status' => 'Status',
                'customer_category_priority' => 'Priority',
            ]);
            if (!$validation->fails()) {
                $post->update([
                    'customer_category_name' => $request->input('customer_category_name'),
                    'customer_category_priority' => $request->input('customer_category_priority'),
                    'customer_category_status' => $request->input('customer_category_status'),
                    'customer_term_updated' => now(),
                ]);

                Session::flash('success_msg', 'Successfully edited ' . $request->input('customer_category_name'));
                return redirect()->route('customer_category_listing');
            }
            $post = (object) $request->all();
        }

        return view('customer_category.form', [
            'submit' => route('customer_category_edit', $customer_category_id),
            'edit' => true,
            'title' => 'Edit',
            'post' => $post,
            'status' => ['' => 'Please select status', 'inactive' => 'Inactive', 'active' => 'Active']
        ])->withErrors($validation);
    }


    public function delete(Request $request)
    {
        $customer_category_id = CustomerCategory::where('customer_category_id', $request->input('customer_category_id'))
            ->where('company_id', auth()->user()->company_id)->first();

        if (!$customer_category_id) {
            Session::flash('fail_msg', 'Invalid Customer Category! Please try another one.');
            return redirect()->route('customer_category_listing');
        }

        $customer_category_id->update([
            'is_deleted' => 1,
        ]);

        Session::flash('success_msg', "Successfully deleted Customer Category.");
        return redirect()->route('customer_category_listing');
    }
}
