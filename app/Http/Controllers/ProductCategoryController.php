<?php

namespace App\Http\Controllers;

use App\Model\ProductCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Session;

class ProductCategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'super_admin']);
    // }

    public function listing(Request $request)
    {
        $search = array();
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_product_category' => [
                        'freetext' => $request->input('freetext'),
                        'product_category_status' => $request->input('product_category_status'),
                        // 'product_category_slug' => $request->input('product_category_slug'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_product_category');
                    break;
            }
        }
        $search = session('filter_product_category') ??  $search;
        $product_category = ProductCategory::get_records($search);
        return view('product_category/listing', [
            'submit' => route('product_category_listing'),
            'records' => $product_category,
            // 'product_category_sel'=> ProductCategory::get_product_category_sel(),
            'product_category_sel' => ['' => 'Please Select Category Status', 'drafted' => 'Drafted', 'published' => 'Published'],
            'product_category_slug_sel' => ProductCategory::get_product_category_slug(),
            'search' => $search
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;
        $status = ['' => 'Please select status', 'draft' => 'Draft', 'published' => 'Published'];

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'product_category_name' => 'required',
                'product_category_status' => 'required',
                'product_category_ranking' => 'required'
            ])->setAttributeNames([
                'product_category_name' => 'Product Category Name',
                'product_category_status' => 'Product Category Status',
                'product_category_ranking' => 'Product Category Ranking'
            ]);
            if (!$validation->fails()) {
                $productCategory = ProductCategory::create([
                    'product_category_parent_id' => $request->input('product_category_parent') ?? 0,
                    'product_category_name' => $request->input('product_category_name'),
                    'product_tag_created' => now(),
                    'product_tag_updated' => now(),
                    'product_category_status' => $request->input('product_category_status'),
                    'product_category_slug' => $request->input('product_category_slug'),
                    'product_category_ranking' => $request->input('product_category_ranking') ?? 0,
                    'company_id' => auth()->user()->company_id
                ]);

                Session::flash('success_msg', 'Successfully added ' . $request->input('product_category_name'));
                return redirect()->route('product_category_listing');
            }
            $post = (object) $request->all();
        }

        $parent_id = ProductCategory::get_category_sel();
        return view('product_category.form', [
            'submit' => route('product_category_add'),
            'edit' => false,
            'post' => $post,
            'title' => 'Add',
            'status' => $status,
            'parent_id' => $parent_id
        ])->withErrors($validation);
    }

    public function edit(Request $request, $product_category_id)
    {
        $validator = null;
        $status = ['' => 'Please select status', 'draft' => 'Draft', 'published' => 'Published'];
        $post = $product_category = ProductCategory::where('product_category_id', $product_category_id);

        if (auth()->user()->company_id != 0) {
            $post = $post->where('company_id', auth()->user()->company_id)->first();
        } else {
            $post = $post->first();
        }

        if (!$post) {
            Session::flash('fail_msg', 'Invalid Product Category, Please try another one.');
            return redirect()->route('product_category_listing');
        }

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'product_category_name' => 'required',
                'product_category_status' => 'required',
                'product_category_ranking' => 'required'
            ])->setAttributeNames([
                'product_category_name' => 'Product Category Name',
                'product_category_status' => 'Product Category Status',
                'product_category_ranking' => 'Product Category Ranking'
            ]);

            if (!$validator->fails()) {
                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');
                $update_detail = [
                    'product_category_parent_id' => $request->input('product_category_parent') ?? 0,
                    'product_category_name' =>  $request->input('product_category_name'),
                    'product_category_status' =>  $request->input('product_category_status'),
                    'product_category_ranking' => $request->input('product_category_ranking') ?? 0
                ];
                $product_category->update($update_detail);
                Session::flash('success_msg', 'Successfully Updated ');
                return redirect()->route('product_category_listing');
            }
            $post = (object) $request->all();
        }

        $parent_id = ProductCategory::get_category_sel();
        return view('product_category/form', [
            'submit' => route('product_category_edit', $product_category_id),
            'title' => 'Edit',
            'post' => $post,
            'status' => $status,
            'parent_id' => $parent_id
            // 'company_land_category_sel'=> ProductCategory::get_land_category_sel(),

        ])->withErrors($validator);
    }

    public function delete(Request $request)
    {
        $product_category_id = $request->input('product_category_id');
        $post = $product_category = ProductCategory::where('product_category_id', $product_category_id);

        if (auth()->user()->company_id != 0) {
            $post = $post->where('company_id', auth()->user()->company_id)->first();
        } else {
            $post = $post->first();
        }

        if (!$post) {
            Session::flash('fail_msg', 'Invalid Product Category, Please try another one.');
            return redirect()->route('product_category_listing');
        }
        // dd($product_category_id);

        if ($product_category) {
            $product_category->update([
                'product_category_updated' => now(),
                'is_deleted' => 1
            ]);
            Session::flash('success_msg', 'Deleted successfully!');
            return redirect()->route('product_category_listing');
        } else {
            Session::flash('fail_msg', 'Product not found!');
            return redirect()->route('product_category_listing');
        }
    }
}
