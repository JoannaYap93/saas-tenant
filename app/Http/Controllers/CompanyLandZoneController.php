<?php

namespace App\Http\Controllers;

use Log;
use Session;
use App\Model\Product;
use App\Model\Setting;
use App\Model\CompanyLand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\SettingTreeAge;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandZone;
use App\Imports\ZoneTreeImport;
use App\Exports\ZoneSampleExcel;
use App\Model\CompanyPnlSubItem;
use App\Model\CompanyLandTreeLog;
use App\Model\ProductCompanyLand;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CompanyLandZoneController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'super_admin'], ['except' => ['listing', 'edit_land', 'ajax_company_land_user', 'ajax_company_land_warehouse','add_company_bank']]);
    // }

    public function listing(Request $request, $company_id, $company_land_id)
    {
        session()->forget('zone_search');
        // $search = array();
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['zone_search' => [
                        "freetext" =>  $request->input('freetext'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('zone_search');
                    break;
                // case 'export':


                //         Session::put('zone_search');
                //         return Excel::download(new ZoneExportTree('components', $search), 'Zone_Export_Tree_Excel.xlsx');
                //     break;
            }
        }
        $search = session('zone_search') ?? array();

        return view('land_zone/listing', [
            'records' => CompanyLandZone::get_record($search , $company_id, $company_land_id),
            'company_id' => $company_id,
            'company_land_id' => $company_land_id,
            'company_land_detail'=>CompanyLand::where('company_land_id',$company_land_id)->with('company')->first(),
            'company_pnl_sub_item' => CompanyPnlSubItem::all(),
            'last_updated' => CompanyLandTreeLog::get_last_updated(),
            // 'search' =>  $search,
            // 'title' => 'Add',
            // 'company_name_sel' => Company::get_company_sel(),
            // 'company_code_sel' => Company::get_company_code_sel(),
            // 'category' => CompanyLandCategory::get_land_category_sel(),
            // 'farm' => CompanyFarm::get_all_farm(),
            // 'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
        ]);
    }

    public function edit(Request $request, $company_land_zone_id)
    {
        $zone= CompanyLandZone::find($company_land_zone_id);

        $validator = null;
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'company_land_zone_name' => 'required',
                'company_land_zone_total_tree' => 'required'
            ]);

            if($zone)
            {
                $zone->update([
                    'company_land_zone_name' => $request->input('company_land_zone_name'),
                    'company_land_zone_total_tree' => $request->input('company_land_zone_total_tree'),
                ]);

                Session::flash('success_msg', 'Successfully updated zone');
                return redirect()->route('land_zone_listing',['company_id'=>$request->input('company_id'), 'company_land_id' =>$request->input('company_land_id')]);
            }

        }
        return view('land_zone.form', [
            'record' => CompanyLandZone::get_record_by_id($company_land_zone_id),
            'company_land_zone_id' => $company_land_zone_id,
        ]
        )->withErrors($validator);
    }

    public function ajax_download_zone_tree_data(Request $request, $company_land_zone_id, $company_land_id)
    {
      // $company_land_zone_id = $request->input('zone_id');
      $product_company_land = ProductCompanyLand::where('company_land_id', $company_land_id)->get();
      $zone_name = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)
          ->pluck('company_land_zone_name')
          ->first();
      $zone_trees = CompanyLandTree::get_by_zone_id($company_land_zone_id);
      $file_name = count($zone_trees) > 0 ? 'Zone_'.$zone_name.'.xlsx' : 'Zone_example_data.xlsx';
      return Excel::download(new ZoneSampleExcel('components/zone_sample_excel', $zone_trees, $product_company_land), $file_name);
    }

    public function restructure_zone_tree(Request $request)
    {
      $company_land_zone_id = $request->input('company_land_zone_id');
      $company_land_id = $request->input('company_land_id');
      // Log::info($company_land_zone_id);
      $new_data_tree_ids = [];
      $zone_trees = CompanyLandTree::query()->where('company_land_zone_id', $company_land_zone_id)->pluck('company_land_tree_id')->toArray();
      $excel_files = $request->file('import_excel');
      $extension = strtolower($excel_files->getClientOriginalExtension());
      // dd(in_array($extension, ['xls', 'xlsx']));
      if(count($zone_trees) > 0){
          if($excel_files && in_array($extension, ['xls', 'xlsx'])){
              // if($ekey == $key){
                $sheets = (new ZoneTreeImport)->toCollection($excel_files);
                // dd($sheets);
                foreach ($sheets as $collections) {
                  // dd($collections);
                  foreach($collections as $key => $row){
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
                    if($row['id'] == null){
                      $age=SettingTreeAge::get_age_by_circumference($row['inch']);

                      if($row['code'] <> 'KB' && $row['code'] <> 'KM'){
                        if($age->setting_tree_age <= 1){
                          $row['code'] = 'K1';
                        }elseif($age->setting_tree_age > 1 && $age->setting_tree_age <= 10){
                          $row['code'] = 'B10';
                        }else{
                          $row['code'] = 'A10';
                        }
                      }

                      if(@$row['status'] == 'alive'){
                        $tree_status = 1;
                      }elseif(@$row['status'] == 'dead'){
                        $tree_status = 2;
                      }elseif(@$row['status'] == 'saw off'){
                        $tree_status = 3;
                      }

                      $new_trees = CompanyLandTree::create([
                                'company_land_id' => $company_land_id,
                                'company_land_zone_id' => $company_land_zone_id,
                                'company_land_tree_no' => $row['tree_no'],
                                'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first(),
                                'company_land_tree_circumference' => $row['inch'],
                                'company_land_tree_age' => $age->setting_tree_age,
                                'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                                'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                                'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                                'company_land_tree_status' => @$tree_status,
                                'company_pnl_sub_item_code' => $row['code'],
                              ]);
                      CompanyLandTreeLog::create([
                        'company_land_tree_id' => $new_trees->company_land_tree_id,
                        'company_land_tree_action_id' => 5,
                        'company_land_tree_log_description' => 'New tree added from import',
                        'company_land_tree_log_date' => $request->input('import_date'),
                        'company_land_tree_log_value' => null,
                        'user_id' => auth()->user()->user_id,
                      ]);

                    }elseif($row['id'] != null){
                      $new_data_tree_ids[$key] = $row['id'];
                      $existing_tree = CompanyLandTree::where('company_land_tree_id', $row['id'])
                                        ->where('company_land_id', $company_land_id)
                                        ->where('company_land_zone_id', $company_land_zone_id)
                                        ->first();
                        // dd($existing_tree != null);
                      if($existing_tree != null){
                        $age=SettingTreeAge::get_age_by_circumference($row['inch']);

                        if($row['code'] <> 'KB' && $row['code'] <> 'KM'){
                          if($age->setting_tree_age <= 1){
                            $row['code'] = 'K1';
                          }elseif($age->setting_tree_age > 1 && $age->setting_tree_age <= 10){
                            $row['code'] = 'B10';
                          }else{
                            $row['code'] = 'A10';
                          }
                        }

                        if(@$row['status'] == 'alive'){
                          $tree_status = 1;
                        }elseif(@$row['status'] == 'dead'){
                          $tree_status = 2;
                        }elseif(@$row['status'] == 'saw off'){
                          $tree_status = 3;
                        }

                        $existing_tree->update([
                          'company_land_tree_no' => $row['tree_no'],
                          'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first() ? Product::where('product_name', $row['product'])->pluck('product_id')->first() : null,
                          'company_land_tree_circumference' => $row['inch'],
                          'company_land_tree_age' => $age->setting_tree_age,
                          'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                          'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                          'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                          'company_land_tree_status' => @$tree_status,
                          'company_pnl_sub_item_code' => $row['code'],
                        ]);

                        CompanyLandTreeLog::create([
                          'company_land_tree_id' => $existing_tree->company_land_tree_id,
                          'company_land_tree_action_id' => 5,
                          'company_land_tree_log_description' => 'Updated tree from import',
                          'company_land_tree_log_date' => $request->input('import_date'),
                          'company_land_tree_log_value' => null,
                          'user_id' => auth()->user()->user_id,
                        ]);

                      }else{
                        $age=SettingTreeAge::get_age_by_circumference($row['inch']);

                        if($row['code'] <> 'KB' && $row['code'] <> 'KM'){
                          if($age->setting_tree_age <= 1){
                            $row['code'] = 'K1';
                          }elseif($age->setting_tree_age > 1 && $age->setting_tree_age <= 10){
                            $row['code'] = 'B10';
                          }else{
                            $row['code'] = 'A10';
                          }
                        }

                        if(@$row['status'] == 'alive'){
                          $tree_status = 1;
                        }elseif(@$row['status'] == 'dead'){
                          $tree_status = 2;
                        }elseif(@$row['status'] == 'saw off'){
                          $tree_status = 3;
                        }

                        $new_trees_2 = CompanyLandTree::create([
                                'company_land_id' => $company_land_id,
                                'company_land_zone_id' => $company_land_zone_id,
                                'company_land_tree_no' => $row['tree_no'],
                                'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first() ? Product::where('product_name', $row['product'])->pluck('product_id')->first() : null,
                                'company_land_tree_circumference' => $row['inch'],
                                'company_land_tree_age' => $age->setting_tree_age,
                                'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                                'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                                'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                                'company_land_tree_status' => @$tree_status,
                                'company_pnl_sub_item_code' => $row['code'],
                              ]);

                              CompanyLandTreeLog::create([
                                'company_land_tree_id' => $new_trees_2->company_land_tree_id,
                                'company_land_tree_action_id' => 5,
                                'company_land_tree_log_description' => 'New tree added from import',
                                'company_land_tree_log_date' => $request->input('import_date'),
                                'company_land_tree_log_value' => null,
                                'user_id' => auth()->user()->user_id,
                              ]);
                      }
                    }
                }
                $remove_data_tree_ids = array_diff($zone_trees ?? [], $new_data_tree_ids ?? []);
                if($remove_data_tree_ids){
                  CompanyLandTree::whereIn('company_land_tree_id',$remove_data_tree_ids)->delete();
                }
                // dd($test);
              }
            }else{
              Session::flash('failed_msg', 'Wrong file type! Please ensure to insert excel files');
              return redirect()->route('land_zone_listing',['company_id'=>auth()->user()->company_id, 'company_land_id' =>$company_land_id]);
            }

            $company_land_tree_total = CompanyLandTree::selectRaw('count(company_land_tree_id) as total_tree_zone')->where('company_land_zone_id', $company_land_zone_id)->first();
            $company_land_zone = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)->first();
            $company_land_zone->update([
              'company_land_zone_total_tree' => $company_land_tree_total->total_tree_zone,
            ]);

        Session::flash('success_msg', 'Successfully Imported.');
        return redirect()->route('land_zone_listing',['company_id'=>auth()->user()->company_id, 'company_land_id' =>$company_land_id]);
      }else{
        if($excel_files && in_array($extension, ['xls', 'xlsx'])){
            // if($ekey == $key){
              $sheets = (new ZoneTreeImport)->toCollection($excel_files);
              // dd($sheets);
              foreach ($sheets as $collections) {
                foreach($collections as $row){
                  // dd($collections);
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

                  if(@$row['status'] == 'alive'){
                    $tree_status = 1;
                  }elseif(@$row['status'] == 'dead'){
                    $tree_status = 2;
                  }elseif(@$row['status'] == 'saw off'){
                    $tree_status = 3;
                  }

                  $company_land_tree = CompanyLandTree::create([
                    'company_land_id' => $company_land_id,
                    'company_land_zone_id' => $company_land_zone_id,
                    'company_land_tree_no' => $row['tree_no'],
                    'product_id' => Product::where('product_name', $row['product'])->pluck('product_id')->first(), 'company_land_tree_circumference' => $inch,
                    'company_land_tree_age' => $age->setting_tree_age,
                    'company_land_tree_year' => date("Y") - $age->setting_tree_age,
                    'is_sick' => $row['sick'] == 'yes' ? 1 : 0,
                    'is_bear_fruit' => $row['bear_fruits'] == 'yes' ? 1 : 0,
                    'company_land_tree_status' => @$tree_status,
                    'company_pnl_sub_item_code' => $row['code'],
                  ]);

                  CompanyLandTreeLog::create([
                    'company_land_tree_id' => $company_land_tree->company_land_tree_id,
                    'company_land_tree_action_id' => 5,
                    'company_land_tree_log_description' => 'Initial import.',
                    'company_land_tree_log_date' => $request->input('import_date'),
                    'company_land_tree_log_value' => null,
                    'user_id' => auth()->user()->user_id,
                  ]);
              }
            }
          }

          $company_land_tree_total = CompanyLandTree::selectRaw('count(company_land_tree_id) as total_tree_zone')->where('company_land_zone_id', $company_land_zone_id)->first();
          $company_land_zone = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)->first();
          $company_land_zone->update([
            'company_land_zone_total_tree' => $company_land_tree_total->total_tree_zone,
          ]);
        Session::flash('success_msg', 'Successfully Imported.');
        return redirect()->route('land_zone_listing',['company_id'=>auth()->user()->company_id, 'company_land_id' =>$company_land_id]);
      }
    }

    public function fix_product_data_listing(Request $request, $company_land_zone_id)
    {
      $land_zone = CompanyLandZone::find($company_land_zone_id);
        if(!$land_zone){
            Session::flash('fail_msg', 'Invalid Tree, Please try again later.');
            return redirect()->route('company_listing');
        }

        $search = array();
        $search['company_land_zone_id'] = $company_land_zone_id;

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['fix_product_data_listing_search' => [
                        'freetext' => $request->input('freetext'),
                        'company_land_tree_status' => $request->input('company_land_tree_status'),
                        'tree_circumference_lower' => $request->input('tree_circumference_lower'),
                        'tree_circumference_upper' => $request->input('tree_circumference_upper'),
                        'company_land_zone_id' => $company_land_zone_id
                    ]]);
                    break;
                case 'reset':
                    session()->forget('fix_product_data_listing_search');
                    break;
            }
        }

        $this_zone = CompanyLandZone::where('company_land_zone_id', $company_land_zone_id)->first();
        $company_land = $this_zone->company_land;

        $search = session('fix_product_data_listing_search') ?? $search;
        $product_sel = Product::get_by_company();
        return view('land_tree.fix_listing', [
            'submit' => route('fix_product_data_listing', $company_land_zone_id),
            'records' => CompanyLandTree::get_land_tree_null_product($search),
            'search' => $search,
            'type' => "Fix",
            'company_land_zone_id' => $company_land_zone_id,
            'product_sel' => $product_sel,
            'zone_sel' => CompanyLandZone::get_zone_sel($this_zone->company_land_id),
            'status_sel' => ['' => 'Please Select Status', 'alive' => 'Alive', 'dead' => 'Dead', 'saw off' => 'Saw Off'],
            'company_land' => $company_land,
            'company_zone_detail'=>$this_zone
        ]);
    }

    public function fix_product_data(Request $request, $company_land_tree_id)
    {
        $tree= CompanyLandTree::find($company_land_tree_id);

        $validator = null;
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_land_tree_no' => 'required',
                'company_land_tree_circumference' => 'required',
                'product_id'=> 'required',
                'company_land_tree_status' => 'required',
            ])->setAttributeNames([
                'company_land_tree_no' => 'Tree No',
                'company_land_tree_circumference' => 'Tree Circumference',
                'product_id'=> 'Product',
                'company_land_tree_status' => 'Tree Status',
          ]);
            if(!$validator->fails()){
                $age=SettingTreeAge::get_age_by_circumference($request->input('company_land_tree_circumference'));
                $tree_year = date('Y') - $age->setting_tree_age;
                $graft_bear_period = Setting::where('setting_slug', '=', 'graft_tree_bear_period')->pluck('setting_value')->first();
                $new_bear_period = Setting::where('setting_slug', '=', 'new_tree_bear_period')->pluck('setting_value')->first();

                if($request->input('company_pnl_sub_item_code') == 'K1'){
                    if($request->input('company_land_tree_age') >= $new_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }elseif($request->input('company_pnl_sub_item_code') == 'KM' || $request->input('company_pnl_sub_item_code') == 'KB'){
                    if($request->input('company_land_tree_age') >= $graft_bear_period){
                        $is_bear_fruit = 1;
                    }else{
                        $is_bear_fruit = 0;
                    }
                }else{
                    $is_bear_fruit = $request->input('is_bear_fruit') ?? 0;
                }

                $tree->update([
                    'company_land_tree_no' => $request->input('company_land_tree_no'),
                    'company_land_tree_year' => $tree_year,
                    'company_land_tree_age' => $age->setting_tree_age,
                    'company_land_tree_circumference' => $request->input('company_land_tree_circumference'),
                    'product_id' => $request->input('product_id'),
                    'is_bear_fruit' => $is_bear_fruit,
                    'company_land_tree_status' => $request->input('company_land_tree_status'),
                ]);

                CompanyLandTreeLog::create([
                  'company_land_tree_id' => $company_land_tree_id,
                  'company_land_tree_action_id' => 6,
                  'company_land_tree_log_description' => 'Tree details Fixed',
                  'company_land_tree_log_date' => date('Y-m-d'),
                  'company_land_tree_log_value' => null,
                  'user_id' => auth()->user()->user_id,
                ]);

                Session::flash('success_msg', 'Successfully Fixed tree');
                return redirect()->route('fix_product_data_listing',['company_land_zone_id' =>$request->input('company_land_zone_id')]);
            }
        }
        $product_sel = Product::get_by_company();
        return view('land_tree.form', [
            'records' => CompanyLandTree::where('company_land_tree_id', $company_land_tree_id)->first(),
            'submit' => route('fix_product_data', $company_land_tree_id),
            'type' => "Fix",
            'product_sel' => $product_sel,
            'company_land_tree_id' => $company_land_tree_id,
            'status_sel' =>  ['' => 'Please Select Status', 'alive' => 'Alive', 'dead' => 'Dead', 'saw off' => 'Saw Off'],
            'company_pnl_sub_item_code_sel' => CompanyPnlSubItem::get_company_pnl_sub_item_code_sel(),
        ])->withErrors($validator);
    }

    public function tree_delete_fix(Request $request)
    {
        $company_land_tree_id = $request->input('company_land_tree_id');
        $tree = CompanyLandTree::find($company_land_tree_id);

        if ($tree) {
            $tree->delete();
            Session::flash('success_msg', 'Tree Deleted successfully!');
            return redirect()->route('fix_product_data_listing',['company_land_zone_id' =>$request->input('company_land_zone_id')]);
        } else {
            Session::flash('fail_msg', 'Tree not found!');
            return redirect()->route('fix_product_data_listing',['company_land_zone_id' =>$request->input('company_land_zone_id')]);
        }
    }

    public function ajax_get_land_zone(Request $request){
        $company_id = $request->input('company_id');
        $company_land_id = $request->input('company_land_id');

        return CompanyLandZone::where('company_id', $company_id)->where('company_land_id', $company_land_id)->where('is_delete',0)->orderBy('company_land_zone_name', 'ASC')->get();
    }

    public function ajax_tree_total_code_zone(Request $request){
        $company_land_zone_id = $request->input('company_land_zone_id');
        $company_land_id = $request->input('company_land_id');
        // $company_land_zone_id = 5;
        // $company_land_id = 6;
        $result = CompanyLandTree::find_tree_w_product_by_land($company_land_id, $company_land_zone_id);
        // dd($result);
        return $result;
    }
}
