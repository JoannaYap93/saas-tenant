<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\ProductStatus;
use App\Model\ProductTag;
use App\Model\ProductTagLink;
use App\Model\ProductStockWarehouse;
use App\Model\SettingWarehouse;
use App\MOdel\SettingSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductStockWarehouseController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','user_type']);
    }

    public function listing(Request $request)
    {
        $user = Auth::user();
        $setting_warehouse = SettingWarehouse::get_warehouse_sel_by_company($user->company_id);
        $product_id = Product::get_product_sel_for_stock_transfer();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['product_stock_warehouse_search' => [
                        'freetext' => $request->input('freetext'),
                        'warehouse_id' => $request->input('warehouse_id'),
                        'product_id' => $request->input('product_id'),
                        'company_id' => $request->input('company_id')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('product_stock_warehouse_search');
                    break;
            }
        }
        $search = session('product_stock_warehouse_search') ?? array();
        // dd($search);
        $product_stk_warehse = ProductStockWarehouse::get_records($search, $user->company_id);
        // dd($product);
        return view('product_stock_warehouse.listing', [
            'submit' => route('product_stock_warehouse_listing', ['tenant' => tenant('id')]),
            'records' => $product_stk_warehse,
            'setting_warehouse_sel' => $setting_warehouse,
            'product_id_sel' => $product_id,
            // 'status' => ProductStatus::get_records(),
            'search' => $search,
            'company' => Company::get_company_sel()
        ]);
    }

    public function add(Request $request)
    {
        $product_stk_warehse = null;
        // $user_id = Auth::id();
        $validation = null;
        $warehouse_id = SettingWarehouse::get_warehouse_sel();
        $product_id = Product::get_product_sel_for_stock_warehouse();
        $product_size_id = SettingSize::get_size_sel_for_stock_warehouse();

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validation = Validator::make($request->all(), [
                'warehouse_id' => 'required',
                'product_id' => 'required',
                'setting_product_size_id' => 'required',
                'product_stock_warehouse_qty_current' => 'required',
            ])->setAttributeNames([
                'warehouse_id' => 'Warehouse',
                'product_id' => 'Product',
                'setting_product_size_id' => 'Product Size',
                'product_stock_warehouse_qty_current' => 'Product Stock Quantity',
            ]);

            if (!$validation->fails()) {
                // dd($request->all());
                $product_stk_warehse = ProductStockWarehouse::create([
                  'warehouse_id' => $request->input('warehouse_id'),
                  'product_id' => $request->input('product_id'),
                  'setting_product_size_id' => $request->input('setting_product_size_id'),
                  'product_stock_warehouse_qty_current' => $request->input('product_stock_warehouse_qty_current')
                ]);

                // foreach ($request->input('product_tag') as $key => $tags) {
                //     ProductTagLink::create([
                //         'product_id' => $product->product_id,
                //         'product_tag_id' => $tags
                //     ]);
                // }

                // dd($product->product_id);
                // Product::summernote($request->input('product_description'), $product->product_id);

                Session::flash('success_msg', 'Product Stock Warehouse Added Successfully!');

                return redirect()->route('product_stock_warehouse_listing', ['tenant' => tenant('id')]);
            }
            $product_stk_warehse = (object) $request->all();
            // dd($product_stk_warehse);
        }

        // $category = ProductCategory::get_category_sel();
        // $tags = ProductTag::get_sel();
        return view('product_stock_warehouse.form', [
            // 'status' => ProductStatus::get_records(),
            'submit' => route('product_stock_warehouse_add', ['tenant' => tenant('id')]),
            'edit' => false,
            'product_stk_warehse' => $product_stk_warehse,
            'warehouse_id_sel'=> $warehouse_id,
            'product_id_sel'=> $product_id,
            'product_size_id_sel'=> $product_size_id,
            'type' => 'Add',
            // 'category' => $category,
            // 'tags' => $tags
        ])->withErrors($validation);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'product_name' => 'required',
                'product_sku' => 'required',
            ])->setAttributeNames([
                'product_name' => 'Product Name',
                'product_sku' => 'Product SKU'
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
                ]);

                $tag_link = ProductTagLink::where('product_id', $id)->pluck('product_tag_id')->toArray();
                $input_tag = $request->input('product_tag');
                $remove_old = array_diff($tag_link, $input_tag);
                foreach ($input_tag as $key => $inputTag) {
                    $not_exist = ProductTagLink::query()->where('product_id', $id)->where('product_tag_id', $inputTag)->get();
                    if ($not_exist->isEmpty()) {
                        ProductTagLink::create([
                            'product_id' => $id,
                            'product_tag_id' => $inputTag
                        ]);
                    }
                }
                if ($remove_old) {
                    foreach ($remove_old as $key => $ptlid) {
                        ProductTagLink::query()->where('product_tag_id', $ptlid)->where('product_id', $id)->delete();
                    }
                }

                Session::flash('success_msg', 'Product Updated Successfully!');
                return redirect()->route('product_listing', ['tenant' => tenant('id')]);
            }

            $product = (object) $request->all();
        }

        $category = ProductCategory::get_category_sel();
        $tags = ProductTag::get_sel();
        return view('product.form', [
            'submit' => route('product_edit', ['tenant' => tenant('id'), 'id' => $id]),
            'product' => $product,
            'status' => ProductStatus::get_records(),
            'edit' => true,
            'type' => 'Edit',
            'category' => $category,
            'tags' => $tags
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
        $result = Product::get_by_name($product_name);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function ajax_get_product_detail(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = Product::get_by_id($product_id, true);
        return $result;
    }
}
