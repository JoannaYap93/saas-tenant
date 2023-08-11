<?php

namespace App\Http\Controllers;

use App\Model\CompanyLand;
use App\Model\CompanyBank;
use App\Model\Product;
use App\Model\ProductCompanyLand;
use App\Model\CompanyLandZone;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandTreeLog;
use App\Model\Company;
use App\Exports\ZoneSampleExcel;
use App\Imports\ZoneTreeImport;
use App\Model\SettingTreeAge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductCompanyLandController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function add_product(Request $request, $company_land_id)
    {
        if (auth()->user()->user_type_id == 2) {
            $check_land = CompanyLand::query()->where('company_id', auth()->user()->company_id)->where('company_land_id', $company_land_id)->first();
            if (!$check_land) {
                Session::flash('fail_msg', 'Invalid Company Land. Please choose another.');
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
        }

        $validator = null;
        $post = $company_land = CompanyLand::query()->with(['company', 'product_company_land', 'company_land_zone'])->where('company_land_id', $company_land_id)->first();
        $user = auth()->user();

        if ($request->isMethod('post')) {
          // dd($request->all());
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
                'company_bank_id' => 'required',
                // 'company_land_zone_name' => 'required',
                // 'company_land_zone_total_tree' => 'required'
            ])->setAttributeNames([
                'company_land_name' => 'Company Land Name',
                'product_id' => 'Product ID',
                'company_bank_id' => 'Bank Account',
                'company_land_zone_name' => 'Zone Name',
                'company_land_zone_total_tree' => 'Tree Amount'
            ]);
            if (!$validator->fails()) {

                $company_land->update([
                    'company_bank_id' => $request->input('company_bank_id'),
                    'company_land_total_acre' => $request->input('company_land_total_acre')
                ]);

                if (is_null($request->input('product_id')) == false) {
                    ProductCompanyLand::where('company_land_id', $company_land_id)->delete();
                    foreach ($request->input('product_id') as $product_id) {
                        if (isset($product_id)) {
                            $product = Product::where('product_id', '=', $product_id)->get()->first();
                            if ($product) {
                                ProductCompanyLand::create([
                                    'company_land_id' => $company_land_id,
                                    'product_id' => $product_id,
                                ]);
                            }
                        }
                    }
                }
                $excel_files = $request->file('import_excel');
                $company_id = auth()->user()->company_id;
                // dd($excel_files);

                $old_zone = CompanyLandZone::query()->where('company_id', $company_id)->where('company_land_id', $company_land_id)->pluck('company_land_zone_id')->toArray();
                $new_zone = $request->input('company_land_zone_id') ?? array();
                $remove = array_udiff($old_zone,$new_zone, function ($a, $b) { return (int)$a - (int)$b; });
                if(@$remove)
                {
                    foreach($remove as $removed){
                        $delete = CompanyLandZone::query()->where('company_land_zone_id', $removed)->where('company_id', $company_id)
                            ->where('company_land_id', $company_land_id)->get();
                        foreach($delete as $old_item){
                            $old_item->update([
                                'is_delete' => 1,
                            ]);
                        }
                    }
                }
                if(!is_null($request->input('company_land_zone_name'))){
                    // $company_land_zone_id = $request->input('company_land_zone_id');

                    // $old_zone = CompanyLandZone::query()->where('company_id', $company_id)->where('company_land_id', $company_land_id)->pluck('company_land_zone_id')->toArray();
                    // $new_zone = $request->input('company_land_zone_id');
                    // $remove = array_diff($old_zone, $new_zone);
                    // dd($old_zone, $new_zone);
                    foreach($request->input('company_land_zone_id') as $key => $zone_details){
                        $find_zone = CompanyLandZone::where('company_land_zone_id', $zone_details)
                            ->where('company_id', $company_id)
                            ->where('company_land_id', $company_land_id)
                            ->first();
                        if ($find_zone) {
                            $find_zone->update([
                                'company_land_zone_name' => $request->input('company_land_zone_name')[$key],
                                // 'company_land_zone_total_tree' => $request->input('company_land_zone_total_tree')[$key],
                                // 'company_land_zone' => $request->input('company_bank_acc_no')[$key],
                            ]);
                          $product =  Product::where('product_name', '101')->pluck('product_id')->first();
                          // dd($product);
                            if(count($find_zone->company_land_tree) == 0){
                              if($excel_files){
                                foreach($excel_files as $ekey => $files){
                                  // dd($ekey == $key);
                                  if($ekey == $key){
                                    $sheets = (new ZoneTreeImport)->toCollection($files);
                                    // dd($sheets);
                                    foreach ($sheets as $collections) {
                                      foreach($collections as $key_row => $row){
                                        // dd($row);
                                        if($row['tree_no'] == null){
                                          break;
                                        }
                                        $existing_pcl = ProductCompanyLand::where('company_land_id', $company_land_id)->where('product_id', Product::where('product_name', $row['product'])->pluck('product_id')->first())->first();
                                        if($existing_pcl == null){
                                          $product_id = Product::where('product_name', $row['product'])->pluck('product_id')->first();
                                          if($product_id){
                                            ProductCompanyLand::create([
                                              'company_land_id' => $company_land_id,
                                              'product_id' => $product_id,
                                            ]);
                                          }
                                        }

                                        $inch = 1;
                                        if($row['inch'] > 0){
                                          $inch = $row['inch'];
                                        }elseif($row['cm'] > 0){
                                          $inch = number_format(($row['cm']/2.54), 2);
                                        }

                                        $age=SettingTreeAge::get_age_by_circumference($inch);

                                        if($row['code'] <> 'KB' && $row['code'] <> 'KM'){
                                          if($age->setting_tree_age <= 1){
                                            $row['code'] = 'K1';
                                          }elseif($age->setting_tree_age > 1 && $age->setting_tree_age <= 10){
                                            $row['code'] = 'B10';
                                          }else{
                                            $row['code'] = 'A10';
                                          }
                                        }

                                      if(trim(@$row['status']) == 'alive'){
                                        $tree_status = trim(@$row['status']);
                                      }elseif(trim(@$row['status']) == 'dead'){
                                        $tree_status = trim(@$row['status']);
                                      }elseif(trim(@$row['status']) == 'sawoff'){
                                        $tree_status = "saw off";
                                      }

                                        $company_land_tree = CompanyLandTree::create([
                                          'company_land_id' => $company_land_id,
                                          'company_land_zone_id' => $find_zone->company_land_zone_id,
                                          'company_land_tree_no' => $row['tree_no'],
                                          'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first(),
                                          'company_land_tree_circumference' => $inch,
                                          'company_land_tree_age' => $age->setting_tree_age,
                                          'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                                          'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                                          'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                                          'company_land_tree_status' => @$tree_status,
                                          'company_pnl_sub_item_code' => $row['code'],
                                        ]);

                                        if(isset($request->input('import_date')[$key])){
                                            CompanyLandTreeLog::create([
                                              'company_land_tree_id' => $company_land_tree->company_land_tree_id,
                                              'company_land_tree_action_id' => 5,
                                              'company_land_tree_log_description' => 'Initial import.',
                                              'company_land_tree_log_date' => $request->input('import_date')[$key],
                                              'company_land_tree_log_value' => null,
                                              'user_id' => auth()->user()->user_id,
                                            ]);
                                        }
                                      }
                                    }
                                  }
                                }
                              }
                            }
                            $company_land_tree_total = CompanyLandTree::selectRaw('count(company_land_tree_id) as total_tree_zone')->where('company_land_zone_id', $find_zone->company_land_zone_id)->first();
                            // dd($company_land->company_land_zone_id);
                            // dd($company_land_tree_total);
                            $find_zone->update([
                              'company_land_zone_total_tree' => $company_land_tree_total->total_tree_zone,
                            ]);
                        } else {
                            $company_zone = CompanyLandZone::create([
                                'company_land_zone_name' =>$request->input('company_land_zone_name')[$key],
                                'company_land_zone_total_tree' => 0,
                                'company_land_id' =>$company_land_id,
                                'company_id' => $company_id,
                            ]);
                            if($excel_files){
                              foreach($excel_files as $ekey => $files){
                                if($ekey == $key){
                                  // dd($ekey);
                                  $sheets = (new ZoneTreeImport)->toCollection($files);
                                  // dd($sheets);
                                  foreach ($sheets as $collections) {
                                    foreach($collections as $key_row => $row){
                                      // dd($row);
                                      if($row['tree_no'] == null){
                                        break;
                                      }
                                      $existing_pcl = ProductCompanyLand::where('company_land_id', $company_land_id)->where('product_id', Product::where('product_name', $row['product'])->pluck('product_id')->first())->first();
                                      if($existing_pcl == null){
                                        $product_id = Product::where('product_name', $row['product'])->pluck('product_id')->first();
                                        if($product_id){
                                          ProductCompanyLand::create([
                                            'company_land_id' => $company_land_id,
                                            'product_id' => $product_id,
                                          ]);
                                        }
                                      }

                                      $inch = 1;
                                      if($row['inch'] > 0){
                                        $inch = $row['inch'];
                                      }elseif($row['cm'] > 0){
                                        $inch = number_format(($row['cm']/2.54), 2);
                                      }

                                      $age=SettingTreeAge::get_age_by_circumference($inch);

                                      if($row['code'] <> 'KB' && $row['code'] <> 'KM'){
                                        if($age->setting_tree_age <= 1){
                                          $row['code'] = 'K1';
                                        }elseif($age->setting_tree_age > 1 && $age->setting_tree_age <= 10){
                                          $row['code'] = 'B10';
                                        }else{
                                          $row['code'] = 'A10';
                                        }
                                      }
                                      if(trim(@$row['status']) == 'alive'){
                                        $tree_status = trim(@$row['status']);
                                      }elseif(trim(@$row['status']) == 'dead'){
                                        $tree_status = trim(@$row['status']);
                                      }elseif(trim(@$row['status']) == 'sawoff'){
                                        $tree_status = "saw off";
                                      }
                                      

                                      $company_land_tree = CompanyLandTree::create([
                                        'company_land_id' => $company_land_id,
                                        'company_land_zone_id' => $company_zone->company_land_zone_id,
                                        'company_land_tree_no' => $row['tree_no'],
                                        'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first(),
                                        'company_land_tree_circumference' => $inch,
                                        'company_land_tree_age' => $age->setting_tree_age,
                                        'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                                        'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                                        'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                                        'company_land_tree_status' => @$tree_status,
                                        'company_pnl_sub_item_code' => $row['code'],
                                      ]);
                                      if(isset($request->input('import_date')[$key])){
                                          CompanyLandTreeLog::create([
                                            'company_land_tree_id' => $company_land_tree->company_land_tree_id,
                                            'company_land_tree_action_id' => 5,
                                            'company_land_tree_log_description' => 'Initial import for tree data.',
                                            'company_land_tree_log_date' => $request->input('import_date')[$key],
                                            'company_land_tree_log_value' => null,
                                            'user_id' => auth()->user()->user_id,
                                          ]);
                                      }
                                    }
                                  }
                                }
                              }
                            }
                            $company_land_tree_total = CompanyLandTree::selectRaw('count(company_land_tree_id) as total_tree_zone')->where('company_land_zone_id', $company_zone->company_land_zone_id)->first();
                            $company_zone->update([
                                'company_land_zone_total_tree' => $company_land_tree_total->total_tree_zone,
                            ]);
                        }
                    }
                    $total_tree_amount_land = CompanyLandZone::selectRaw('SUM(company_land_zone_total_tree) as total')
                        ->where('company_id', $company_id)
                        ->where('company_land_id', $company_land_id)
                        ->first();
                    // dd($total_tree_amount_land->total);
                    $company_land->update([
                      'company_land_total_tree' => $total_tree_amount_land->total,
                    ]);
                }

                Session::flash('success_msg', 'Successfully added product');
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
            $company_land = (object) $request->all();
            // dd($company_land);
        }
        return view('product_company_land.form', [
            'submit' => route('product_company_land_add', ['tenant' => tenant('id'), 'id' => $company_land_id]),
            'title' => 'Add',
            'company_land' => $company_land,
            'post' => $post,
            'products' => Product::get_sel(),
            'company_bank_sel' => CompanyBank::get_company_bank_details($post->company_id),
        ])->withErrors($validator);
    }

    public function ajax_find_product_company_land_with_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $company_land = CompanyLand::get_company_land_details_by_company_id($company_id);
        return $company_land;
    }

    public function ajax_download_excel_sample_zone(Request $request, $company_land_id)
    {
      $product_company_land = ProductCompanyLand::where('company_land_id', $company_land_id)->get();
      $zone_trees = null;
      return Excel::download(new ZoneSampleExcel('components/zone_sample_excel', $zone_trees, $product_company_land), 'zone_sample_excel.xlsx');
    }
}
