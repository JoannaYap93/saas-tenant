<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandZone;
use App\Model\CompanyPnlSubItem;
use App\Model\FormulaUsage;
use App\Model\FormulaUsageItem;
use App\Model\FormulaUsageProduct;
use App\Model\RawMaterialCompany;
use App\Model\RawMaterialCompanyUsage;
use App\Model\RawMaterialCompanyUsageLog;
use App\Model\SettingRawMaterial;
use App\Model\SettingFormula;
use App\Model\SettingFormulaCategory;
use App\Model\SettingFormulaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Log;

class FormulaUsageController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                        $search['freetext'] = $request->input('freetext');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['raw_material_id'] = $request->input('raw_material_id');
                        $search['user_id'] = $request->input('user_id');
                        $search['formula_usage_type'] = $request->input('formula_usage_type');
                        $search['formula_usage_status'] = $request->input('formula_usage_status');
                        Session::put('formula_usage_search', $search);
                    break;
                case 'reset':
                    session()->forget('formula_usage_search');
                    break;
            }
        }

        $search = session('formula_usage_search') ?? array();
        return view('formula_usage.listing', [
            'submit' => route('formula_usage_listing', ['tenant' => tenant('id')]),
            'records' => FormulaUsage::get_records($search),
            // 'status' => ProductStatus::get_records(),
            'product_tree' => CompanyLandTree::get_tree_w_product_by_land(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'search' => $search,
            'company' => Company::get_company_sel(),
            'company_land' => CompanyLand::get_company_land_sel(),
            'company_pnl_sub_item' => CompanyPnlSubItem::all(),
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'formula_usage_status_sel' => ['' => 'Please Select Status', 'completed' => 'Completed', 'pending' => 'Pending', 'deleted' => 'Deleted'],
            'formula_usage_type_sel' => ['' => 'Please Select Type', 'man' => 'Man', 'drone' => 'Drone'],
        ]);
    }

    public function add(Request $request)
    {
        $records = null;
        $validation = null;
        $company = null;

        if(auth()->user()->company_id == 0){
            $company = $request->input('company_id_sel');
        }else{
            $company = auth()->user()->company_id;
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'company_land_id' => 'required',
                'setting_formula_id' => 'required',
                'formula_usage_value' => $request->input('setting_formula_category_id') == 2 || $request->input('setting_formula_category_id') == 3 ? 'required' : '',
                'formula_usage_type' => $request->input('setting_formula_category_id') == 2 ? 'required' : '',
                'formula_usage_item_qty' => 'required_if:setting_category_id,1',
                'formula_usage_item_value' => 'required',
                'formula_usage_item_total' => 'required',
                'product_id' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 2 ? 'required' : '',
                'formula_usage_product_value_per_tree' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 2 ? 'required' : '',
            ])->setAttributeNames([
                'company_land_id' => 'Company Land',
                'setting_formula_id' => 'Formula',
                'formula_usage_value' => 'Usage Value',
                'formula_usage_type' => 'Usage Type',
                'formula_usage_item_qty' => 'Item Quantity',
                'formula_usage_item_value' => 'Item Value',
                'formula_usage_item_total' => 'Item Total',
                'product_id' => 'Product Tree',
                'formula_usage_product_value_per_tree' => 'Value Per Tree',
            ]);
            if (!$validation->fails()) {
                if($request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 3){
                    $formula_usage_value_for_ferilize = 0;
                    $sum_total_value = 0;
                    foreach ($request->input('formula_usage_item_total') as $key => $total) {
                        $formula_usage_value_for_ferilize += $total;
                        $sum_total_value += $total;
                    }
                }

                $formula_usage = FormulaUsage::create([
                  'setting_formula_id' => $request->input('setting_formula_id'),
                    'user_id' => auth()->user()->user_id,
                    'company_id' => $company,
                    'company_land_id' => $request->input('company_land_id'),
                    'company_land_zone_id' => $request->input('company_land_zone_id'),
                    'formula_usage_value' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $formula_usage_value_for_ferilize : $request->input('formula_usage_value'),
                    'formula_usage_type' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 3 ? 'man' : $request->input('formula_usage_type'),
                    'formula_usage_status' => 'completed',
                    'formula_usage_date' => $request->input('formula_usage_date'),
                ]);

                foreach ($request->input('formula_usage_item_value') as $key => $each_item) {
                    $formula_usage_item = FormulaUsageItem::create([
                        'formula_usage_id' => $formula_usage->formula_usage_id,
                        'raw_material_id' => $request->input('raw_material_id')[$key],
                        'formula_usage_item_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_qty')[$key] : 0,
                        'formula_usage_item_value' => $request->input('formula_usage_item_value')[$key],
                        'formula_usage_item_rounding' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? 0 : $request->input('formula_usage_item_rounding')[$key],
                        'formula_usage_item_total' => $request->input('formula_usage_item_total')[$key],
                    ]);

                    $raw_material_company = RawMaterialCompanyUsage::check_existing_rmc_formula_usage($request->input('raw_material_id')[$key], $company, $request->input('company_land_id'));

                    if($raw_material_company){
                        RawMaterialCompanyUsage::create([
                            'raw_material_id' => $request->input('raw_material_id')[$key],
                            'raw_material_company_id' => $raw_material_company->raw_material_company_id,
                            'raw_material_company_usage_type' => 'usage',
                            'raw_material_company_usage_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_qty')[$key] : 0,
                            'raw_material_company_usage_price_per_qty' => 0,
                            'raw_material_company_usage_value_per_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_value')[$key] : 0,
                            'raw_material_company_usage_total_price' => 0,
                            'raw_material_company_usage_total_value' => $request->input('formula_usage_item_total')[$key],
                            'user_id' => auth()->user()->user_id,
                            'formula_usage_id' => $formula_usage->formula_usage_id,
                            'formula_usage_item_id' => $formula_usage_item->formula_usage_item_id,
                            'raw_material_company_usage_date' => $formula_usage->formula_usage_date,
                        ]);

                        $raw_material_company_qty_after = $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $raw_material_company->raw_material_quantity - $request->input('formula_usage_item_qty')[$key] : 'not_fertilizer';
                        $raw_material_company_value_after = $raw_material_company->raw_material_value - $request->input('formula_usage_item_total')[$key];

                        if($raw_material_company_qty_after == 'not_fertilizer'){
                            $raw_material_company->update([
                                'raw_material_value' => $raw_material_company_value_after,
                            ]);
                        }else{
                            $raw_material_company->update([
                                'raw_material_quantity' => $raw_material_company_qty_after,
                                'raw_material_value' => $raw_material_company_value_after,
                            ]);
                        }
                    }
                }

                $input_formula_usage_json = array();
                foreach ($request->input('product_id') as $pkey => $each_product_tree) {
                    $setting_pnl_sub_item = CompanyPnlSubItem::pluck('company_pnl_sub_item_code')->toArray();
                    $insert_formula_usage_json = array();

                    foreach ($setting_pnl_sub_item as $key => $code) {
                        if(isset($request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code]) && isset($request->input('formula_usage_product_quantity')[$each_product_tree][$code])){
                            $input_formula_usage_json = array(
                            'company_pnl_sub_item_code' => $code,
                            'formula_usage_product_quantity' => $request->input('formula_usage_product_quantity')[$each_product_tree][$code] ? (int) $request->input('formula_usage_product_quantity')[$each_product_tree][$code] : 0,
                            'formula_usage_product_value_per_tree' => $request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code] ? (float) $request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code] : 0,
                            );
                            array_push($insert_formula_usage_json, $input_formula_usage_json);
                        }
                    }

                    $formula_usage_product = FormulaUsageProduct::create([
                    'product_id' => $request->input('product_id')[$pkey],
                    'formula_usage_id' => $formula_usage->formula_usage_id,
                    // 'formula_usage_product_value_per_tree' => $request->input('formula_usage_product_value_per_tree')[$key],
                    'formula_usage_product_created' => now(),
                    'formula_usage_product_json' => json_encode($insert_formula_usage_json),
                    // 'formula_usage_product_quantity' => $request->input('formula_usage_product_quantity')[$key],
                    ]);
                }
                // }elseif($request->input('setting_formula_category_id') == 3){
                //   $land_trees = CompanyLandTree::find_tree_w_product_by_land($request->input('company_land_id'));
                //   $per_land = $sum_total_value / $land_trees['company_land_tree_total'];
                //   // dd($per_land);
                //   foreach ($land_trees['data'] as $key => $tree) {
                //     // $per_product_tree = $per_land * $tree->total_product_tree;
                //     // dd($per_product_tree);
                //     $formula_usage_product = FormulaUsageProduct::create([
                //       'product_id' => $tree->product_id,
                //       'formula_usage_id' => $formula_usage->formula_usage_id,
                //       'formula_usage_product_value_per_tree' => $per_land,
                //       'formula_usage_product_created' => now(),
                //       'formula_usage_product_quantity' => $tree->total_product_tree,
                //     ]);
                //   }
                // }
                $user_id = auth()->user()->user_id;

                $query = <<<GQL
                    mutation {
                        updateFormulaUsage(formula_usage_id: $formula_usage->formula_usage_id user_id: $user_id)
                    }
                    GQL;
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post(env('GRAPHQL_API').'/graphql', [
                    'query' => $query
                ]);
                $data = json_decode($response->getBody()->getContents());

                Session::flash('success_msg', 'Formula Usage Added Successfully!');

                return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
            }
            $records = (object) $request->all();
        }

        return view('formula_usage.form', [
            'records' => $records,
            'submit' => route('formula_usage_add', ['tenant' => tenant('id')]),
            'edit' => true,
            'type' => 'Add',
            'setting_formula_sel' => SettingFormula::get_by_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'formula_type_sel' => FormulaUsage::formula_type_sel_glob(),
            'formula_category_sel' => SettingFormulaCategory::get_formula_category_sel(),
            'formula_status_sel' => FormulaUsage::formula_status_sel_glob(),
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'company_sel' => Company::get_company_sel(),
        ])->withErrors($validation);
    }

    public function edit(Request $request, $formula_usage_id)
    {
        $formula_usage = FormulaUsage::find($formula_usage_id);
        $validation = null;

        $company = null;
        if(auth()->user()->company_id == 0){
            $company = $request->input('company_id_sel');
        }else{
            $company = auth()->user()->company_id;
        }
        if ($request->isMethod('post')) {
          // dd($request->all());

          $validation = Validator::make($request->all(), [
              'company_land_id' => 'required',
              'setting_formula_id' => 'required',
              'formula_usage_value' => $request->input('setting_formula_category_id') == 2 ||  $request->input('setting_formula_category_id') == 3 ? 'required' : '',
              'formula_usage_type' => $request->input('setting_formula_category_id') == 2 ? 'required' : '',
              'formula_usage_item_qty' => 'required_if:setting_category_id,1',
              'formula_usage_item_value' => 'required',
              'formula_usage_item_total' => 'required',
              'product_id' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 2 ? 'required' : '',
              'formula_usage_product_value_per_tree' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 2 ? 'required' : '',
          ])->setAttributeNames([
              'company_land_id' => 'Company Land',
              'setting_formula_id' => 'Formula',
              'formula_usage_value' => 'Usage Value',
              'formula_usage_type' => 'Usage Type',
              'formula_usage_item_qty' => 'Item Quantity',
              'formula_usage_item_value' => 'Item Value',
              'formula_usage_item_total' => 'Item Total',
              'product_id' => 'Product Tree',
              'formula_usage_product_value_per_tree' => 'Value Per Tree',
          ]);
          if (!$validation->fails()) {
              // dd($request->all());
              // Add back initial formula usage back to raw material company
              foreach ($formula_usage->formula_usage_item as $key => $fuiab) {
                $raw_material_company_addback = RawMaterialCompanyUsage::check_existing_rmc_formula_usage($fuiab->raw_material_id, $formula_usage->company_id, $formula_usage->company_land_id);

                $raw_material_company_qty_after_addback = $raw_material_company_addback->raw_material_quantity + $fuiab->formula_usage_item_qty;
                $raw_material_company_value_after_addback = $raw_material_company_addback->raw_material_value + $fuiab->formula_usage_item_value;

                $raw_material_company_addback->update([
                  'raw_material_quantity' => $raw_material_company_qty_after_addback,
                  'raw_material_value' => $raw_material_company_value_after_addback,
                ]);
              }
              if($request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 3){
                $formula_usage_value_for_ferilize = 0;
                $sum_total_value = 0;
                foreach ($request->input('formula_usage_item_total') as $key => $total) {
                  $formula_usage_value_for_ferilize += $total;
                  $sum_total_value += $total;
                }
              }
              // dd($formula_usage_value_for_ferilize);
              $formula_usage->update([
                'setting_formula_id' => $request->input('setting_formula_id'),
                'user_id' => auth()->user()->user_id,
                'company_id' => $company,
                'company_land_id' => $request->input('company_land_id'),
                'company_land_zone_id' => $request->input('company_land_zone_id'),
                'formula_usage_value' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $formula_usage_value_for_ferilize : $request->input('formula_usage_value'),
                'formula_usage_type' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 || $request->input('setting_formula_category_id') == 3 ? 'man' : $request->input('formula_usage_type'),
                'formula_usage_status' => 'completed',
                'formula_usage_date' => $request->input('formula_usage_date'),
              ]);

              foreach ($formula_usage->formula_usage_item as $key => $each_item) {
                $each_item->update([
                  'formula_usage_id' => $formula_usage->formula_usage_id,
                  'raw_material_id' => $request->input('raw_material_id')[$key],
                  'formula_usage_item_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_qty')[$key] : 0,
                  'formula_usage_item_value' => $request->input('formula_usage_item_value')[$key],
                  'formula_usage_item_rounding' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? 0 : $request->input('formula_usage_item_rounding')[$key],
                  'formula_usage_item_total' => $request->input('formula_usage_item_total')[$key],
                ]);

                $raw_material_company = RawMaterialCompanyUsage::check_existing_rmc_formula_usage($request->input('raw_material_id')[$key], $company, $request->input('company_land_id'));

                if($raw_material_company){
                  $raw_material_company_usage_log = RawMaterialCompanyUsageLog::where('formula_usage_id', $formula_usage->formula_usage_id)->where('formula_usage_item_id', $each_item->formula_usage_item_id)->first();

                  $raw_material_company_usage = RawMaterialCompanyUsage::where('formula_usage_item_id', $each_item->formula_usage_item_id)->first();
                  $raw_material_company_usage->update([
                    'raw_material_id' => $request->input('raw_material_id')[$key],
                    'raw_material_company_id' => $raw_material_company->raw_material_company_id,
                    'raw_material_company_usage_type' => 'usage',
                    'raw_material_company_usage_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_qty')[$key] : 0,
                    'raw_material_company_usage_price_per_qty' => 0,
                    'raw_material_company_usage_value_per_qty' => $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $request->input('formula_usage_item_value')[$key] : 0,
                    'raw_material_company_usage_total_price' => 0,
                    'raw_material_company_usage_total_value' => $request->input('formula_usage_item_total')[$key],
                    'user_id' => auth()->user()->user_id,
                    'formula_usage_id' => $formula_usage->formula_usage_id,
                    'formula_usage_item_id' => $each_item->formula_usage_item_id,
                    'raw_material_company_usage_total_value_remaining' => @$raw_material_company_usage_log->raw_material_company_usage_log_value_before,
                    'raw_material_company_usage_date' => $formula_usage->formula_usage_date,
                  ]);

                  RawMaterialCompanyUsageLog::create([
                    'raw_material_company_usage_id' => $raw_material_company_usage->raw_material_company_usage_id,
                    'user_id' => auth()->user()->user_id,
                    'raw_material_company_usage_log_created' => now(),
                    'raw_material_company_usage_log_action' => 'Add',
                    'raw_material_company_usage_log_description' => 'Added back due to usage edit.',
                    'raw_material_company_usage_log_value_before' => $raw_material_company_usage_log->raw_material_company_usage_log_value_after,
                    'raw_material_company_usage_log_value_after' => $raw_material_company_usage_log->raw_material_company_usage_log_value_before,
                    'formula_usage_id' => $formula_usage->formula_usage_id,
                    'formula_usage_item_id' => $each_item->formula_usage_item_id,
                  ]);

                  $raw_material_company_qty_after = $request->input('setting_formula_category_id') == 1 || $request->input('setting_formula_category_id') == 4 ? $raw_material_company->raw_material_quantity - $request->input('formula_usage_item_qty')[$key] : 'not_fertilizer';
                  $raw_material_company_value_after = $raw_material_company->raw_material_value - $request->input('formula_usage_item_total')[$key];
                  // dd($raw_material_company_qty_after, $raw_material_company_value_after);
                  if($raw_material_company_qty_after == 'not_fertilizer'){
                    $raw_material_company->update([
                      'raw_material_value' => $raw_material_company_value_after,
                    ]);
                  }else{
                    $raw_material_company->update([
                      'raw_material_quantity' => $raw_material_company_qty_after,
                      'raw_material_value' => $raw_material_company_value_after,
                    ]);
                  }
                }
              }
              FormulaUsageProduct::where('formula_usage_id', $formula_usage->formula_usage_id)->delete();
              $input_formula_usage_json = array();
              foreach ($request->input('product_id') as $pkey => $each_product_tree) {
                $setting_pnl_sub_item = CompanyPnlSubItem::pluck('company_pnl_sub_item_code')->toArray();
                // dd($setting_pnl_sub_item);
                $insert_formula_usage_json = array();

                foreach ($setting_pnl_sub_item as $key => $code) {
                  // dd($request->input('formula_usage_product_value_per_tree'));
                  if(isset($request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code]) && isset($request->input('formula_usage_product_quantity')[$each_product_tree][$code])){
                    $input_formula_usage_json = array(
                      'company_pnl_sub_item_code' => $code,
                      'formula_usage_product_quantity' => $request->input('formula_usage_product_quantity')[$each_product_tree][$code] ? (int) $request->input('formula_usage_product_quantity')[$each_product_tree][$code] : 0,
                      'formula_usage_product_value_per_tree' => $request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code] ? (float) $request->input('formula_usage_product_value_per_tree')[$each_product_tree][$code] : 0,
                    );
                    array_push($insert_formula_usage_json, $input_formula_usage_json);
                  }
                }
                // dd($insert_formula_usage_json);
                $formula_usage_product = FormulaUsageProduct::create([
                  'product_id' => $request->input('product_id')[$pkey],
                  'formula_usage_id' => $formula_usage->formula_usage_id,
                  // 'formula_usage_product_value_per_tree' => $request->input('formula_usage_product_value_per_tree')[$key],
                  'formula_usage_product_created' => now(),
                  'formula_usage_product_json' => json_encode($insert_formula_usage_json),
                  // 'formula_usage_product_quantity' => $request->input('formula_usage_product_quantity')[$key],
                ]);
              }

              $user_id = auth()->user()->user_id;
              // dd($user_id);
              $query = <<<GQL
                  mutation {
                      updateFormulaUsage(formula_usage_id: $formula_usage->formula_usage_id user_id: $user_id)
                  }
                  GQL;
              $response = Http::withHeaders([
                  'Content-Type' => 'application/json',
              ])->post(env('GRAPHQL_API').'/graphql', [
                  'query' => $query
              ]);
              $data = json_decode($response->getBody()->getContents());

              Session::flash('success_msg', 'Formula Usage Updated Successfully!');

              return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
          }

            $product = (object) $request->all();
        }

        // $category = ProductCategory::get_category_sel();
        // $tags = ProductTag::get_sel();
        return view('formula_usage.form', [
            'submit' => route('formula_usage_edit', ['tenant' => tenant('id'), 'id' => $formula_usage_id]),
            'records' => $formula_usage,
            // 'status' => ['' => 'Please Select Status', 'active' => 'Active', 'inactive' => 'Inactive'],
            'edit' => true,
            'type' => 'Edit',
            'setting_formula_sel' => SettingFormula::get_by_company_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'formula_type_sel' => FormulaUsage::formula_type_sel_glob(),
            'formula_category_sel' => SettingFormulaCategory::get_formula_category_sel(),
            'formula_status_sel' => FormulaUsage::formula_status_sel_glob(),
            'raw_material_sel' => SettingRawMaterial::get_rm_sel(),
            'company_sel' => Company::get_company_sel(),
        ])->withErrors($validation);
    }

    public function delete_formula_usage(Request $request)
    {
        $formula_usage_id = $request->input('formula_usage_id');
        $formula_usage = FormulaUsage::find($formula_usage_id);
        $formula_usage_item = FormulaUsageItem::where('formula_usage_id', $formula_usage_id)->get();
        // dd($formula_usage_item);

        if ($formula_usage) {
            $formula_usage->update([
                'formula_usage_updated' => now(),
                'formula_usage_status' => 'deleted'
            ]);
            if($formula_usage_item){
              foreach ($formula_usage_item as $key => $fui) {
                $raw_material_company = RawMaterialCompanyUsage::check_existing_rmc_formula_usage($fui->raw_material_id, $formula_usage->company_id, $formula_usage->company_land_id);
                RawMaterialCompanyUsage::create([
                  'raw_material_id' => $fui->raw_material_id,
                  'raw_material_company_id' => $raw_material_company->raw_material_company_id,
                  'raw_material_company_usage_type' => 'formula usage restock',
                  'raw_material_company_usage_qty' => $fui->formula_usage_item_qty,
                  'raw_material_company_usage_price_per_qty' => 0,
                  'raw_material_company_usage_value_per_qty' => $fui->formula_usage_item_value,
                  'raw_material_company_usage_total_price' => 0,
                  'raw_material_company_usage_total_value' => $fui->formula_usage_item_total,
                  'user_id' => auth()->user()->user_id,
                  'formula_usage_id' => $fui->formula_usage_id,
                  'formula_usage_item_id' => $fui->formula_usage_item_id,
                  'raw_material_company_usage_date' => $formula_usage->formula_usage_date,
                ]);

                $raw_material_company_qty_after = $raw_material_company->raw_material_quantity + $fui->formula_usage_item_qty;
                $raw_material_company_value_after = $raw_material_company->raw_material_value + $fui->formula_usage_item_value;
                // dd($raw_material_company_qty_after, $raw_material_company_value_after);
                  $raw_material_company->update([
                    'raw_material_quantity' => $raw_material_company_qty_after,
                    'raw_material_value' => $raw_material_company_value_after,
                  ]);
              }
            }
            Session::flash('success_msg', 'Deleted successfully!');
            return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
        } else {
            Session::flash('fail_msg', 'Formula Usage not found!');
            return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
        }
    }

    public function ajax_get_formula_by_category(Request $request)
    {
        $setting_formula_category_id = $request->input('setting_formula_category_id');
        // $company_land_id = $request->input('company_land_id');
        $company_id = $request->input('company_id');
        // $setting_formula_category_id = 2;
        // $company_id = 4;
        $result = SettingFormula::get_formula_by_category($setting_formula_category_id, $company_id);
        return $result;
    }

    public function ajax_get_setting_formula_item(Request $request)
    {
        $company_id = $request->input('company_id');
        // $company_land_id = $request->input('company_land_id');
        $setting_formula_id = $request->input('setting_formula_id');
        // Log::info([$company_id, $company_land_id, $setting_formula_id]);
        // $company_id = 4;
        // $company_land_id = 6;
        // $setting_formula_id = 16;
        $result = SettingFormulaItem::get_setting_formula_Item($company_id, $setting_formula_id);
        // dd($result);
        return $result;
    }

    public function ajax_find_tree_w_product_by_land(Request $request)
    {
      $company_land_id = $request->input('company_land_id');
      $company_land_zone_id = $request->input('company_land_zone_id');
      // $company_land_id = 6;
      // $company_land_zone_id = 0;
      $result = CompanyLandTree::find_tree_w_product_by_land($company_land_id, $company_land_zone_id);
      // dd($result);
      return $result;
    }

    public function ajax_find_formula_usage_item_details(Request $request)
    {
      $formula_usage_id = $request->input('formula_usage_id');
      $user_id = $request->input('user_id');

      // $formula_usage_id = 61;
      // $user_id = 27;
      $result = FormulaUsageItem::get_by_formula_id($formula_usage_id, $user_id);

      return $result;
    }

    public function ajax_get_zone_by_land(Request $request)
    {
      $company_land_id = $request->input('land_id');
      $company_id = $request->input('company_id');

      // $company_land_id = 6;
      $result = CompanyLandZone::get_by_land($company_land_id, $company_id);
      // dd($result);

      return $result;
    }

    public function listing_formula_by_id($formula_usage_id)
    {
        $search['formula_usage_id'] = $formula_usage_id;
        \Illuminate\Support\Facades\Session::put('formula_usage_search', $search);
        return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
    }
    public function listing_by_rm_id($raw_material_id)
    {
        $search['raw_material_id'] = $raw_material_id;
        \Illuminate\Support\Facades\Session::put('formula_usage_search', $search);
        return redirect()->route('formula_usage_listing', ['tenant' => tenant('id')]);
    }
}
