<?php

namespace App\Http\Controllers;

use Log;
use Session;
use App\Model\User;
use App\Model\Company;
use App\Model\Product;
use App\Model\CompanyLand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\CompanyLandTree;
use App\Model\CompanyLandZone;
use App\Imports\ZoneTreeImport;
use App\Exports\ZoneSampleExcel;
use App\Model\CompanyLandTreeLog;
use App\Model\ProductCompanyLand;
use Spatie\Permission\Models\Role;
use App\Model\CompanyLandTreeAction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CompanyLandTreeLogController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'super_admin'], ['except' => ['listing', 'edit_land', 'ajax_company_land_user', 'ajax_company_land_warehouse','add_company_bank']]);
    // }

    public function listing(Request $request, $company_land_id = 0, $company_land_tree_id = 0)
    {
        session()->forget('company_land_tree_log_search');
        $search = array();
        $search['company_land_tree_id'] = $company_land_tree_id;
        $this_tree = CompanyLandTree::where('company_land_tree_id', $company_land_tree_id)->first();

        if($this_tree){
          $zone = $this_tree->company_land_zone;
          $land = null;
          $tree_sel = null;
          $company = Company::get_company_name_by_id($this_tree->company_land_zone->company_id);
        }else{
          $zone = null;
          $land = CompanyLand::where('company_land_id', $company_land_id)->first();
          $tree_sel = CompanyLandTreeLog::get_tree_no_sel($company_land_id);
          $company = null;
        }
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');

            switch ($submit_type) {
                case 'search':
                    session(['company_land_tree_log_search' => [
                        'start_date' => $request->input('start_date'),
                        'end_date' => $request->input('end_date'),
                        'start_date_created' => $request->input('start_date_created'),
                        'end_date_created' => $request->input('end_date_created'),
                        'user_id' => $request->input('user_id'),
                        'action_id' => $request->input('action_id'),
                        'company_land_tree_id' => $request->input('company_land_tree_id'),
                        'company_land_zone_id' => $request->input('company_land_zone_id'),
                        'product_id' => $request->input('product_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('company_land_tree_log_search');
                    break;
            }
        }
        Log::info(session()->all());
        $search = session('company_land_tree_log_search') ?? $search;

        return view('land_tree_log/listing', [
            'records' => CompanyLandTreeLog::get_records($search, $company_land_id, $company_land_tree_id),
            'search' => $search,
            'action_sel' => CompanyLandTreeAction::get_action_sel(),
            'company_land_tree_id' => $company_land_tree_id,
            'company' => $company,
            'zone' => $zone,
            'land' => $land,
            'tree_sel' => $tree_sel,
            'company_land_detail'=>CompanyLand::where('company_land_id',$company_land_id)->with('company')->first(),
            'zone_sel' => CompanyLandZone::get_zone_sel($company_land_id),
            'product_sel' => Product::get_product_sel_by_company_land($company_land_id),
            'user_sel' => ['' => 'Please Select User...'] + User::get_user_sel(),
        ]);
    }

    public function edit(Request $request, $company_land_tree_log_id)
    {

        $log= CompanyLandTreeLog::find($company_land_tree_log_id);

        $validator = null;
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_land_tree_log_description' => 'required',
                'company_land_tree_log_date'=> 'required',
                'company_land_tree_action_id' => 'required',
            ]);

            if(!$validator->fails())
            {
                $log->update([
                    'company_land_tree_log_description' => $request->input('company_land_tree_log_description'),
                    'company_land_tree_log_date'=> $request->input('company_land_tree_log_date'),
                    'company_land_tree_action_id' => $request->input('company_land_tree_action_id'),
                    'company_land_tree_log_value' => $request->input('company_land_tree_log_value'),
                ]);

                $tree_id = $request->input('company_land_tree_id');
                Session::flash('success_msg', 'Successfully updated tree');
                return redirect()->route('land_tree_log_listing', $tree_id);
            }

        }
        return view('land_tree_log.form', [
            'records' => CompanyLandTreeLog::where('company_land_tree_log_id', $company_land_tree_log_id)->first(),
            'action_sel' => CompanyLandTreeAction::get_action_sel(),
            'company_land_tree_log_id' => $company_land_tree_log_id,
        ])->withErrors($validator);
    }

    public function ajax_get_tree_action_by_id(Request $request)
    {
        $action_id = $request->input('action_id');
        $result = CompanyLandTreeLog::get_tree_action_by_id($action_id);
        return $result;
    }

    public function ajax_company_land_tree_log_by_zone(Request $request)
    {
        $company_land_zone_id = $request->input('company_land_zone_id');
        $result = CompanyLandTree::get_tree_log_by_zone_id($company_land_zone_id);
        return $result;
    }

}
