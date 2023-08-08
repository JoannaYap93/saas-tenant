<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\RawMaterialCompany;
use App\Model\SettingRawMaterial;
use App\Model\SettingRawMaterialCategory;
// use App\Model\Product;
// use App\Model\ProductCategory;
// use App\Model\ProductStatus;
// use App\Model\ProductTag;
// use App\Model\ProductTagLink;
// use App\Model\ProductStockWarehouse;
// use App\Model\SettingWarehouse;
// use App\MOdel\SettingSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RawMaterialCompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $user = Auth::user();
        $search = array();

        // $setting_warehouse = SettingWarehouse::get_warehouse_sel_by_company($user->company_id);
        // $product_id = Product::get_product_sel_for_stock_transfer();

        if ($request->isMethod('post')) {
          // dd($request->all());
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                        $search['freetext'] = $request->input('freetext');
                        $search['company_id'] = $request->input('company_id');
                        // $search['company_land_id'] = $request->input('company_land_id');
                        $search['raw_material_id'] = $request->input('raw_material_id');
                        $search['raw_material_category_id'] = $request->input('raw_material_category_id');
                        Session::put('raw_material_company_search', $search);
                    break;
                case 'reset':
                    session()->forget('raw_material_company_search');
                    break;
            }
        }
        $raw_material_company = RawMaterialCompany::get_records($search);
        $search = session('raw_material_company_search') ?? array();
        return view('raw_material_company.listing', [
            'submit' => route('raw_material_company_listing'),
            'records' => $raw_material_company,
            // 'status' => ProductStatus::get_records(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'category_sel' => SettingRawMaterialCategory::get_category_sel(),
        ]);
    }

    public function add(Request $request)
    {
        $records = null;
        $validation = null;

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validation = Validator::make($request->all(), [
                // 'company_land_id' => 'required',
                'raw_material_id' => 'required',
            ])->setAttributeNames([
                // 'company_land_id' => 'Company Land',
                'raw_material_id' => 'Raw Material'
            ]);
            if (!$validation->fails()) {
                // dd($request->all());
                 RawMaterialCompany::create([
                  'company_id' => auth()->user()->company_id,
                //   'company_land_id' => $request->input('company_land_id'),
                  'raw_material_id' => $request->input('raw_material_id'),
                  'raw_material_quantity' => $request->input('raw_material_quantity')??0,
                  'raw_material_value' => $request->input('raw_material_value')??0,
                  'raw_material_company_status' => $request->input('raw_material_company_status'),
                ]);

                Session::flash('success_msg', 'Raw Material Company Added Successfully!');

                return redirect()->route('raw_material_company_listing');
            }
            $records = (object) $request->all();
            // dd($product_stk_warehse);
        }

        // $category = ProductCategory::get_category_sel();
        // $tags = ProductTag::get_sel();
        return view('raw_material_company.form', [
            // 'status' => ProductStatus::get_records(),
            'records' => $records,
            'submit' => route('raw_material_company_add'),
            // 'records' => $raw_material_company,
            'status' => ['' => 'Please Select Status', 'active' => 'Active', 'inactive' => 'Inactive'],
            'edit' => true,
            'type' => 'Add',
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            // 'category' => $category,
            // 'tags' => $tags
        ])->withErrors($validation);
    }

    public function edit(Request $request, $id)
    {
        $raw_material_company = RawMaterialCompany::find($id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                // 'company_land_id' => 'required',
                'raw_material_id' => 'required',
            ])->setAttributeNames([
                // 'company_land_id' => 'Company Land',
                'raw_material_id' => 'Raw Material'
            ]);
            if (!$validation->fails()) {
                $raw_material_company->update([
                    'company_id' => auth()->user()->company_id,
                    // 'company_land_id' => $request->input('company_land_id'),
                    'raw_material_id' => $request->input('raw_material_id'),
                    'raw_material_quantity' => $request->input('raw_material_quantity')??0,
                    'raw_material_value' => $request->input('raw_material_value')??0,
                    'raw_material_company_status' => $request->input('raw_material_company_status'),
                ]);

                Session::flash('success_msg', 'Raw Material Company Updated Successfully!');
                return redirect()->route('raw_material_company_listing');
            }

            $product = (object) $request->all();
        }

        // $category = ProductCategory::get_category_sel();
        // $tags = ProductTag::get_sel();
        return view('raw_material_company.form', [
            'submit' => route('raw_material_company_edit', $id),
            'records' => $raw_material_company,
            'status' => ['' => 'Please Select Status', 'active' => 'Active', 'inactive' => 'Inactive'],
            'edit' => true,
            'type' => 'Edit',
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            // 'tags' => $tags
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

    public function ajax_check_existing_raw_material_company(Request $request)
    {
        $rm_id = $request->input('rm_id');
        $rmc_id = $request->input('rmc_id');
        $company_id = $request->input('company_id');
        // $rm_id = 1;
        // $company_id = 1;
        // $company_land_id = 1;
        $result = RawMaterialCompany::check_existing_rmc($rm_id, $rmc_id, $company_id);
        return $result;
    }

    public function ajax_get_by_company_sel(Request $request)
    {   $result = array();
        $id = auth()->user()->company_id;

        $result = RawMaterialCompany::get_by_company_sel($id);
        // dd($result);
        return ['material' => $result];
    }
}
