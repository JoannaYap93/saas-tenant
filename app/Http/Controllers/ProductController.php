<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\ProductStatus;
use App\Model\ProductTag;
use App\Model\ProductTagLink;
use App\Model\SettingSize;
use App\Model\ProductSizeLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth', 'super_admin'], ['except' => ['ajax_check_product_sku', 'ajax_get_product_detail', 'ajax_search_by_name']]);
    // }

    public function listing(Request $request)
    {
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_product' => [
                        'freetext' => $request->input('freetext'),
                        'status' => $request->input('product_status'),
                        'product_category_id' => $request->input('product_category_id'),
                        'product_size' => $request->input('product_size'),
                        'company_id' => $request->input('company_id'),
                        'company_land_id' => $request->input('company_land_id')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_product');
                    break;
            }
        }
        $search = session('filter_product') ?? array();
        $product = Product::get_records($search);

        return view('product.listing', [
            'submit' => route('product_listing', ['tenant' => tenant('id')], ['tenant' => tenant('id')]),
            'records' => $product,
            'status' => ProductStatus::get_records(),
            'product_size_sel' => ['' => 'Please select sizes'] + SettingSize::get_size_sel(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'product_category_sel' => ProductCategory::get_category_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $product = (object) null;
        $user = auth()->user();
        $company_id = $user->company_id != 0 ? $user->company_id : $request->input('company_id');
        $validation = null;
        $category = ProductCategory::get_category_sel();

        if (count($category) > 1) {
            if ($request->isMethod('post')) {
                $validation = Validator::make($request->all(), [
                    'product_name' => 'required|max:45',
                    'product_sku' => 'required|unique:tbl_product,product_sku|max:45',
                    'product_description' => 'required',
                    'product_category' => 'required',
                    'product_ranking' => 'required',
                    'product_status' => 'required',
                    'product_remarks' => 'nullable|max:45',
                    'product_image' => 'nullable|image'
                ])->setAttributeNames([
                    'product_name' => 'Product Name',
                    'product_sku' => 'Product SKU',
                    'product_description' => 'Product Description',
                    'product_category' => 'Product Category',
                    'product_ranking' => 'Product Ranking',
                    'product_status' => 'Product Status',
                    'product_remarks' => 'Product Remarks',
                    'product_image' => 'Product Image'
                ]);
                if (!$validation->fails()) {
                    $product = Product::create([
                        'product_name' => $request->input('product_name'),
                        'product_remarks' => $request->input('product_remarks') ?? '',
                        'product_description' => $request->input('product_description') ?? '',
                        'product_sku' => $request->input('product_sku'),
                        'is_deleted' => 0,
                        'product_created' => now(),
                        'product_updated' => now(),
                        'product_status_id' => $request->input('product_status'),
                        'product_ranking' => $request->input('product_ranking') ?? 1,
                        'product_category_id' => $request->input('product_category') ?? 0,
                        'company_id' => $company_id
                    ]);

                    if ($request->input('product_tag') && count($request->input('product_tag')) > 0) {
                        foreach ($request->input('product_tag') as $key => $tags) {
                            $find_tag = ProductTag::product_tag_id_name($tags, $tags);
                            if (!$find_tag) {
                                $tag_id = ProductTag::create([
                                    'product_tag_name' => $tags,
                                    'product_tag_created' => now(),
                                    'product_tag_updated' => now(),
                                    'product_tag_status' => 'published'
                                ]);
                                ProductTagLink::create([
                                    'product_id' => $product->product_id,
                                    'product_tag_id' => $tag_id->product_tag_id
                                ]);
                            } else {
                                ProductTagLink::create([
                                    'product_id' => $product->product_id,
                                    'product_tag_id' => $tags
                                ]);
                            }
                        }
                    }

                    if (is_null($request->input('setting_product_size_id')) == false) {
                        foreach ($request->input('setting_product_size_id') as $setting_product_size_id) {
                            $product_size = SettingSize::where('setting_product_size_id', '=', $setting_product_size_id)->first();
                            if ($product_size) {
                                ProductSizeLink::create([
                                    'product_id' => $product->product_id,
                                    'setting_product_size_id' => $setting_product_size_id,
                                ]);
                            }
                        }
                    }

                    if ($request->file('product_image')) {
                        $product->addMediaFromRequest('product_image')->toMediaCollection('product_media');
                    }

                    Session::flash('success_msg', 'Product Added Successfully!');

                    return redirect()->route('product_listing', ['tenant' => tenant('id')], ['tenant' => tenant('id')])->with('message', 'Product ' . $product->product_name . ' created');
                }
                $product = (object) $request->all();
            }
        } else if (count($category) <= 1) {
            return redirect()->route('product_category_add', ['tenant' => tenant('id')])->with('failed_msg', 'Product Category is currently empty, please add a category to proceed.');
        }

        $tags = ProductTag::get_sel();
        return view('product.form', [
            'status' => ProductStatus::get_records(),
            'submit' => route('product_add', ['tenant' => tenant('id')]),
            'edit' => false,
            'product' => $product,
            'type' => 'Add',
            'category' => $category,
            'tags' => $tags,
            'product_size_sel' => SettingSize::get_size_sel(),
            'company' => Company::get_company_sel()
        ])->withErrors($validation);
    }

    public function edit(Request $request, $id)
    {
        $user = auth()->user();
        $company_id = $user->company_id != 0 ? $user->company_id : $request->input('company_id');
        $product = Product::query()->where('product_id', $id);

        if ($user->company_id != 0) {
            $product = $product->where('company_id', $user->company_id)->first();
        } else {
            $product = $product->first();
        }

        if ($product == null) {
            Session::flash('fail_msg', 'Invalid Product');
            return redirect()->route('product_listing', ['tenant' => tenant('id')]);
        }

        $validation = null;
        $category = ProductCategory::get_category_sel();

        if (count($category) > 1) {
            if ($request->isMethod('post')) {
                $p = Product::find($id);
                $validation = Validator::make($request->all(), [
                    'product_name' => 'required|max:45',
                    // 'product_sku' => 'required||unique:tbl_product,product_sku|max:45',
                    'product_description' => 'required',
                    'product_category' => 'required',
                    'product_ranking' => 'required',
                    'product_status' => 'required',
                    'product_remarks' => 'nullable|max:45',
                    'product_image' => 'nullable|image'
                ])->setAttributeNames([
                    'product_name' => 'Product Name',
                    // 'product_sku' => 'Product SKU',
                    'product_description' => 'Product Description',
                    'product_category' => 'Product Category',
                    'product_ranking' => 'Product Ranking',
                    'product_status' => 'Product Status',
                    'product_remarks' => 'Product Remarks',
                    'product_image' => 'Product Image'
                ]);
                if (!$validation->fails()) {
                    $product->update([
                        'product_name' => $request->input('product_name'),
                        'product_remarks' => $request->input('product_remarks') ?? '',
                        'product_description' => $request->input('product_description') ?? '',
                        'product_updated' => now(),
                        'product_status_id' => $request->input('product_status'),
                        'product_ranking' => $request->input('product_ranking') ?? 1,
                        'product_category_id' => $request->input('product_category') ?? 0,
                        'company_id' => $company_id
                    ]);

                    $tag_link = ProductTagLink::where('product_id', $id)->pluck('product_tag_id')->toArray();
                    $input_tag = $request->input('product_tag') ?? array();
                    $remove_old = array_diff($tag_link, $input_tag);
                    if ($request->input('product_tag') && count($request->input('product_tag')) > 0) {
                        foreach ($input_tag as $key => $inputTag) {
                            $find_tag = ProductTag::product_tag_id_name($inputTag, $inputTag);
                            if (!$find_tag) {
                                $tag_id = ProductTag::create([
                                    'product_tag_name' => $inputTag,
                                    'product_tag_created' => now(),
                                    'product_tag_updated' => now(),
                                    'product_tag_status' => 'published'
                                ]);
                                ProductTagLink::create([
                                    'product_id' => $id,
                                    'product_tag_id' => $tag_id->product_tag_id
                                ]);
                            } else {
                                $not_exist = ProductTagLink::query()->where('product_id', $id)->where('product_tag_id', $inputTag)->get();
                                if ($not_exist->isEmpty()) {
                                    ProductTagLink::create([
                                        'product_id' => $id,
                                        'product_tag_id' => $inputTag
                                    ]);
                                }
                            }
                        }
                    }
                    if ($remove_old) {
                        foreach ($remove_old as $key => $ptlid) {
                            ProductTagLink::query()->where('product_tag_id', $ptlid)->where('product_id', $id)->delete();
                        }
                    }

                    if (is_null($request->input('setting_product_size_id')) == false) {
                        ProductSizeLink::where('product_id', $id)->delete();
                        foreach ($request->input('setting_product_size_id') as $setting_product_size_id) {
                            $product_size = SettingSize::where('setting_product_size_id', '=', $setting_product_size_id)->first();
                            if ($product_size) {
                                ProductSizeLink::create([
                                    'product_id' => $id,
                                    'setting_product_size_id' => $setting_product_size_id,
                                ]);
                            }
                        }
                    } else {
                        ProductSizeLink::where('product_id', $id)->delete();
                    }

                    if ($request->file('product_image')) {
                        $product->addMediaFromRequest('product_image')->toMediaCollection('product_media');
                    }

                    Session::flash('success_msg', 'Product Updated Successfully!');
                    return redirect()->route('product_listing', ['tenant' => tenant('id')]);
                }

                $product = (object) $request->all();
                $product->media = $p->hasMedia('product_media') ?? 0;
            }
        } else if (count($category) <= 1) {
            return redirect()->route('product_category_add', ['tenant' => tenant('id')])->with('failed_msg', 'Product Category is currently empty, please add a category to proceed.');
        }


        $tags = ProductTag::get_sel();
        return view('product.form', [
            'submit' => route('product_edit', ['tenant' => tenant('id'), 'id' => $id]),
            'product' => $product,
            'status' => ProductStatus::get_records(),
            'product_size_sel' => SettingSize::get_size_sel(),
            'edit' => true,
            'type' => 'Edit',
            'category' => $category,
            'tags' => $tags,
            'company' => Company::get_company_sel()
        ])->withErrors($validation);
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

    public function ajax_search_by_name(Request $request)
    {
        $product_name = $request->input('product_name');
        $land = $request->input('land');
        $result = Product::get_by_name($product_name, $land);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function ajax_get_product_detail(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = Product::get_by_id($product_id, true);
        return $result;
    }

    public function ajax_check_product_sku(Request $request)
    {
        $status = false;
        $validator = null;

        $product_id = $request->input('product_id');
        $product_sku = $request->input('product_sku');

        $validator = Validator::make($request->all(), [
            'product_sku' => "required|unique:tbl_product,product_sku,{$product_id},product_id",
        ])->setAttributeNames([
            'product_sku' => "Product SKU",
        ]);

        if (!$validator->fails()) {
            $status = true;
        }

        return response()->json(['status' => $status]);
    }
}
