<?php

namespace App\Http\Controllers;

use App\Model\ProductTag;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductTagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request){
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    $search['freetext'] = $request->input('freetext');
                    Session::put('product_tag_search', $search);
                    break;
                case 'reset':
                    Session::forget('product_tag_search');
                    break;
            }
        }
        $search = Session::has('product_tag_search') ? Session::get('product_tag_search') : array();
        $records =  ProductTag::get_records($search, $perpage);


        return view('product_tag.listing', [
            'submit'=> route('product_tag_listing'),
            'records' => $records,
            'title'=> 'Product Tag Listing',
        ]);
    }


    public function add(Request $request)
    {
        $validation = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'product_tag_name' => 'required',
                'product_tag_status' => 'required',
            ])->setAttributeNames([
                'product_tag_name' => 'Product Tag Name',
                'product_tag_status' => 'Product Tag Status',
            ]);
            if (!$validation->fails()) {
                ProductTag::create([
                    'product_tag_name' => $request->input('product_tag_name'),
                    'product_tag_created' => now(),
                    'product_tag_updated' => now(),
                    'product_tag_status' => $request->input('product_tag_status'),
                ]);

                Session::flash('success_msg', 'Successfully added '.$request->input('product_tag_name'));
                return redirect()->route('product_tag_listing');
            }
            $post = (object) $request->all();
        }

        return view('product_tag.form', [
            'submit' => route('product_tag_add'),
            'edit' => false,
            'post'=> $post,
            'title' => 'Add',
            'status' => [''=>'Please select status' , 'draft' => 'Draft', 'published' => 'Published'],
        ])->withErrors($validation);
    }

    public function edit(Request $request, $product_tag_id)
    {
        $post = ProductTag::find($product_tag_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'product_tag_name' => 'required',
                'product_tag_status' => 'required',
            ])->setAttributeNames([
                'product_tag_name' => 'Product Tag Name',
                'product_tag_status' => 'Product Tag Status',
            ]);
            if (!$validation->fails()) {
                $post->update([
                    'product_tag_name' => $request->input('product_tag_name'),
                    'product_tag_status' => $request->input('product_tag_status'),
                    'product_tag_updated' => now(),
                ]);

                Session::flash('success_msg', 'Successfully edited '.$request->input('product_tag_name'));
                return redirect()->route('product_tag_listing');
            }
            $post = (object) $request->all();
        }

        return view('product_tag.form', [
            'submit' => route('product_tag_edit', $product_tag_id),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
            'status'=> [''=>'please select status' , 'draft' => 'Draft', 'published' => 'Published'],
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $product_tag_id = ProductTag::find($request->input('product_tag_id'));

        if(!$product_tag_id){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('product_tag_listing');
        }

        $product_tag_id->update([
            'product_tag_status'=>'deleted',
        ]);

        Session::flash('success_msg', "Successfully deleted  Product Category.");
        return redirect()->route('product_tag_listing');
    }

}
