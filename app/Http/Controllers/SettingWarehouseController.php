<?php

namespace App\Http\Controllers;

use App\Model\SettingWarehouse;
use App\Model\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Auth;

class SettingWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $perpage = 10;
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['filter_setting_warehouse' => [
                        'freetext' => $request->input('freetext'),
                        'warehouse_status' => $request->input('warehouse_status'),
                        'company_id' => $request->input('company_id'),
                        'warehouse_id' => $request->input('warehouse_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('filter_setting_warehouse');
                    break;
            }
        }

        $search = session('filter_setting_warehouse') ? session('filter_setting_warehouse') : $search;

        return view('setting_warehouse.listing', [
            'submit'=> route('setting_warehouse_listing'),
            'title'=> 'Add',
            'warehouse_status_sel'=>[''=>'Please select status','active' =>'Active', 'inactive' => 'Inactive'],
            'company_sel' => Company::get_company_sel(),
            'records'=>  SettingWarehouse::get_records($search,$perpage),
            'search' => $search,
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;
        $user = auth()->user();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'warehouse_name' => 'required',
                'warehouse_status' => 'required',
                'warehouse_ranking' => 'required',
                // 'company_id' => 'required'
            ])->setAttributeNames([
                'warehouse_name' => 'Warehouse Name',
                'warehouse_status' => 'Status',
                'warehouse_ranking' => 'Ranking',
                // 'company_id' => 'Company'
            ]);
            if (!$validation->fails()) {
                SettingWarehouse::create([
                    'warehouse_name' => $request->input('warehouse_name'),
                    'warehouse_status' => $request->input('warehouse_status'),
                    'warehouse_ranking' => $request->input('warehouse_ranking'),
                    'company_id' => $user->company_id ?? $request->input('company_id')
                ]);

                Session::flash('success_msg', 'Successfully added '.$request->input('warehouse_name'));
                return redirect()->route('setting_warehouse_listing');
            }
            $post = (object) $request->all();
        }

        return view('setting_warehouse.form', [
            'submit' => route('setting_warehouse_add'),
            'edit' => false,
            'post'=> $post,
            'title' => 'Add',
            'warehouse_status_sel'=>[''=>'Please select status','active' =>'Active', 'inactive' => 'Inactive'],
            'company_sel' => Company::get_company_sel()
        ])->withErrors($validation);
    }


    public function edit(Request $request, $warehouse_id)
    {
        $user = auth()->user();
        $post = SettingWarehouse::query()->where('warehouse_id', $warehouse_id);

        if ($user->company_id != 0) {
            $post = $post->where('company_id', $user->company_id)->first();
        } else {
            $post = $post->first();
        }

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Warehouse, Please Try Again');
            return redirect()->route('setting_warehouse_listing');
        }

        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'warehouse_name' => 'required',
                'warehouse_status' => 'required',
                'warehouse_ranking' => 'required',
                //   'company_id' => 'required'
            ])->setAttributeNames([
                'warehouse_name' => 'Warehouse Name',
                'warehouse_status' => 'Status',
                'warehouse_ranking' => 'Ranking',
                //   'company_id' => 'Company'
            ]);
            if (!$validation->fails()) {
                $post->update([
                    'warehouse_name' => $request->input('warehouse_name'),
                    'warehouse_status' => $request->input('warehouse_status'),
                    'warehouse_ranking' => $request->input('warehouse_ranking'),
                    'company_id' => $user->company_id ?? $request->input('company_id'),
                ]);

                Session::flash('success_msg', 'Successfully edited '.$request->input('warehouse_name'));
                return redirect()->route('setting_warehouse_listing');
            }
            $post = (object) $request->all();
        }

        return view('setting_warehouse.form', [
            'submit' => route('setting_warehouse_edit', $warehouse_id),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
            'warehouse_status_sel'=>[''=>'Please select status','active' =>'Active', 'inactive' => 'Inactive'],
            'company_sel' => Company::get_company_sel()
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $setting_warehouse_id = SettingWarehouse::find($request->input('warehouse_id'));

        if(!$setting_warehouse_id){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_warehouse_listing');
        }

        $setting_warehouse_id->update([
            'is_deleted'=> 1,
        ]);

        Session::flash('success_msg', "Successfully deleted Warehouse.");
        return redirect()->route('setting_warehouse_listing');
    }

    public function view_setting_warehouse_by_id($warehouse_id)
    {
        $search['warehouse_id'] = $warehouse_id;
        Session::put('filter_setting_warehouse', $search);
        return redirect()->route('setting_warehouse_listing');
    }

}
