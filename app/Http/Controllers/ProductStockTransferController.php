<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\ProductStatus;
use App\Model\ProductTag;
use App\Model\ProductTagLink;
use App\Model\ProductSizeLink;
use App\Model\ProductStockWarehouse;
use App\Model\ProductStockTransfer;
use App\Model\SettingWarehouse;
use App\Model\SettingSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductStockTransferController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'user_type'], ['except' => ['ajax_get_product_by_product_category_id', 'ajax_get_setting_size_by_product_id', 'ajax_get_product_sel_by_company_land_id','ajax_get_product_by_product_category_id_land_id']]);
    }

    public function listing(Request $request)
    {
        $user = Auth::user();
        // dd($user->company_id);
        $setting_warehouse = SettingWarehouse::get_warehouse_sel_by_company($user->company_id);
        $product_id = Product::get_product_sel_for_stock_transfer();
        $product_size_id = SettingSize::get_size_sel_for_stock_warehouse();
        $product_stk_warehse = ProductStockWarehouse::find($request->input('product_stock_warehouse_id'));

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['product_stock_transfer_search' => [
                        'freetext' => $request->input('freetext'),
                        'product_stock_transfer_remark' => $request->input('product_stock_transfer_remark'),
                        'warehouse_id' => $request->input('warehouse_id'),
                        'product_id' => $request->input('product_id'),
                        'company_id' => $request->input('company_id'),
                        'product_size_id' => $request->input('product_size_id'),
                        'stock_from' => $request->input('stock_from'),
                        'stock_to' => $request->input('stock_to')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('product_stock_transfer_search');
                    break;
            }
        }
        $search = session('product_stock_transfer_search') ?? array();
        // dd($search);
        $product_stk_transfer = ProductStockTransfer::get_records($search, $user->company_id);
        // dd($product);
        return view('product_stock_transfer.listing', [
            'submit' => route('product_stock_transfer_listing'),
            'records' => $product_stk_transfer,
            'product_id_sel' => $product_id,
            'product_size_id_sel' => $product_size_id,
            'product_stk_transfer_remark_sel' => [
                '' => 'Please Select Remark',
                'Manual Changes' => 'Manual Changes',
                'Warehouse to Warehouse' => 'Warehouse to Warehouse'
            ],
            'setting_warehouse_sel' => $setting_warehouse,
            // 'status' => ProductStatus::get_records(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $product_stk_transfer = null;
        // $user_id = Auth::id();
        $validation = null;
        // $warehouse_id = SettingWarehouse::get_warehouse_sel();
        // $product_id = Product::get_product_sel_for_stock_transfer();
        // $product_size_id = SettingSize::get_size_sel_for_stock_warehouse();

        $warehouse_id = $request->input('warehouse_id');
        $product_id = $request->input('product_id');
        $setting_product_size_id = $request->input('setting_product_size_id');

        $product_stk_warehse_id = ProductStockWarehouse::get_by_product_stock_warehouse_id_2($warehouse_id, $product_id, $setting_product_size_id);
        // dd($product_stk_warehse);
        // if(!$product_stk_warehse){
        //   dd("not exist");
        // }else{
        //   dd("exist");
        // }

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validation = Validator::make($request->all(), [
                'product_stock_transfer_remark' => 'required',
                'product_stock_transfer_description' => 'required',
                'product_id' => 'required',
                // 'product_stock_warehouse_id' => 'required',
                'setting_product_size_id' => 'required',
                // 'product_stock_warehouse_id' => 'required',
                // 'product_stock_transfer_action' => 'required',
                'product_stock_transfer_qty_before' => 'required',
                'product_stock_transfer_qty_after' => 'required',
                'product_stock_transfer_qty' => 'required',
            ])->setAttributeNames([
                'product_stock_transfer_remark' => 'Remark',
                'product_stock_transfer_description' => 'Description',
                'product_id' => 'Product',
                // 'product_stock_warehouse_id' => 'Stock Warehouse',
                'setting_product_size_id' => 'Size',
                // 'product_stock_warehouse_id' => 'Stock Warehouse Id',
                // 'product_stock_transfer_action' => 'Action',
                'product_stock_transfer_qty_before' => 'Current Quantity',
                'product_stock_transfer_qty_after' => 'Quantity After',
                'product_stock_transfer_qty' => 'Insert Quantity',
            ]);

            if (!$validation->fails()) {
                // dd($request->all());
                if ($product_stk_warehse_id) {
                    $update_warehse = ProductStockWarehouse::find($product_stk_warehse_id);

                    $product_stk_transfer = ProductStockTransfer::create([
                        'product_stock_transfer_remark' => $request->input('product_stock_transfer_remark'),
                        'product_stock_transfer_description' => $request->input('product_stock_transfer_description'),
                        'product_id' => $request->input('product_id'),
                        'product_stock_warehouse_id' => $product_stk_warehse_id,
                        'setting_product_size_id' => $request->input('setting_product_size_id'),
                        // 'product_stock_transfer_action' => $request->input('product_stock_transfer_action'),
                        'product_stock_transfer_qty_before' => $request->input('product_stock_transfer_qty_before'),
                        'product_stock_transfer_qty_after' => $request->input('product_stock_transfer_qty_after'),
                        'product_stock_transfer_qty' => $request->input('product_stock_transfer_qty'),
                    ]);

                    $update_warehse->update([
                        // 'setting_product_size_id' => $request->input('setting_product_size_id'),
                        'product_stock_warehouse_qty_current' => $request->input('product_stock_transfer_qty_after'),
                    ]);
                } else {
                    $new_product_stk_warehse = ProductStockWarehouse::create([
                        'warehouse_id' => $warehouse_id,
                        'product_id' => $product_id,
                        'setting_product_size_id' => $setting_product_size_id,
                        'product_stock_warehouse_qty_current' => $request->input('product_stock_transfer_qty_after'),
                    ]);
                    // dd($new_product_stk_warehse->product_stock_warehouse_id);
                    $product_stk_transfer = ProductStockTransfer::create([
                        'product_stock_transfer_remark' => $request->input('product_stock_transfer_remark'),
                        'product_stock_transfer_description' => $request->input('product_stock_transfer_description'),
                        'product_id' => $request->input('product_id'),
                        'product_stock_warehouse_id' => $new_product_stk_warehse->product_stock_warehouse_id,
                        'setting_product_size_id' => $request->input('setting_product_size_id'),
                        // 'product_stock_transfer_action' => $request->input('product_stock_transfer_action'),
                        'product_stock_transfer_qty_before' => $request->input('product_stock_transfer_qty_before'),
                        'product_stock_transfer_qty_after' => $request->input('product_stock_transfer_qty_after'),
                        'product_stock_transfer_qty' => $request->input('product_stock_transfer_qty'),
                    ]);
                    // dd($product_stk_transfer);
                }


                // foreach ($request->input('product_tag') as $key => $tags) {
                //     ProductTagLink::create([
                //         'product_id' => $product->product_id,
                //         'product_tag_id' => $tags
                //     ]);
                // }

                // dd($product->product_id);
                // Product::summernote($request->input('product_description'), $product->product_id);

                Session::flash('success_msg', 'Product Stock Transfer Successfully!');

                return redirect()->route('product_stock_transfer_listing');
            }
            $product_stk_transfer = (object) $request->all();
            // dd($product_stk_transfer);

        }

        // $category = ProductCategory::get_category_sel();
        // $tags = ProductTag::get_sel();
        Session::flash('fail_msg', 'Please insert required information!');
        return redirect()->route('product_stock_transfer_listing')->withErrors($validation);
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
                return redirect()->route('product_listing');
            }

            $product = (object) $request->all();
        }

        $category = ProductCategory::get_category_sel();
        $tags = ProductTag::get_sel();
        return view('product.form', [
            'submit' => route('product_edit', $id),
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
            return redirect()->route('product_listing');
        } else {
            Session::flash('fail_msg', 'Product not found!');
            return redirect()->route('product_listing');
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
        $result = Product::get_by_id($product_id,  true);
        return $result;
    }

    public function ajax_check_stock_warehouse(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = ProductStockWarehouse::get_by_product_id($product_id);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function ajax_get_current_warehouse_qty(Request $request)
    {
        $warehouse_id = $request->input('warehouse_id');
        $product_id = $request->input('product_id');
        $setting_product_size_id = $request->input('setting_product_size_id');
        $result = ProductStockWarehouse::get_by_product_stock_warehouse_id($warehouse_id, $product_id, $setting_product_size_id);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function ajax_get_setting_size_by_product_id(Request $request)
    {
        $product_id = $request->input('product_id');
        $result = ProductSizeLink::get_size_name_by_product_id($product_id);
        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }

    public function find_do_by_no($no)
    {
        $search['delivery_no'] = $no;
        Session::put('filter_do', $search);
        return redirect()->route('do_listing');
    }

    public function ajax_get_product_sel_by_company_land_id(Request $request)
    {
        $company_land_id = $request->input('company_land_id');
        $result = Product::get_by_company_land($company_land_id);
        return response()->json(['data' => $result]);
    }

    public function ajax_get_product_by_product_category_id(Request $request)
    {
        $product_category_id= $request->input('product_category_id');
        $product = Product::get_by_product_category_id($product_category_id);

        return response()->json(['data' => $product]);
    }

    public function ajax_get_product_by_product_category_id_land_id(Request $request)
    {
        $product_category_id= $request->input('product_category_id');
        $company_land_id = $request->input('land_id');


        $product = Product::get_by_product_category_id_land_id($product_category_id, $company_land_id);
        return response()->json(['data' => $product]);
    }
}
