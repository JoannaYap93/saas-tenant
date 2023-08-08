<?php

namespace App\Http\Controllers;

use App\Model\CompanyLandCategory;
use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\CompanyPnlSubItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Session;

class CompanyLandController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth','user_type']);
    }

    public function listing(Request $request)
    {
        $search = array();
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['company_land_search' => [
                        "freetext"=>  $request->input('freetext'),
                        "company_land_category_id"=>  $request->input('company_land_category_id'),
                        "company_land_code"=>  $request->input('company_land_code'),
                        "company_land_id"=>  $request->input('company_land_id'),
                        'company_id' => $request->input('company_id'),
                        // dd($request->all()),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('company_land_search');
                    break;
            }
        }
        $search = session('company_land_search') ?? $search;
        return view('company_land/listing', [
            'submit'=> route('company_land_listing'),
            'records'=> CompanyLand::get_record($search,15),
            'search'=>  $search,
            'company_name_sel'=> Company::get_company_sel(),
            'company_pnl_sub_item' => CompanyPnlSubItem::all(),
        ]);
    }

    public function edit(Request $request, $company_land_id)
    {
        $validator = null;
        $user = auth()->user();
        $post = $company = CompanyLand::query()->where('company_land_id', $company_land_id);

        if ($user->company_id != 0) {
            $post = $post->where('company_id', $user->company_id)->first();
        } else {
            $post = $post->first();
        }

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Company Land, Please Try Again');
            return redirect()->route('land_area_listing');
        }

        if($request->isMethod('post')){
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'company_land_name' => 'required',
                'company_land_category_id' => 'required',
                'company_id' => 'required',
            ])->setAttributeNames([
                'company_land_name' => 'Company Land Name',
                'company_land_category_id' => 'Company Land Category',
                'company_id' => 'Company',
            ]);

            if (!$validator->fails()) {
                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');
                $update_detail = [
                    // 'user_email' => $request->input('user_email'),
                    // dd($request->all()),
                    'company_land_name' =>  $request->input('company_land_name'),
                    'company_land_code' => $request->input('company_land_code'),
                    'company_id' => $user->company_id ?? $request->input('company_id'),
                ];
                $company->update($update_detail);
                // $company_land = $company->company_land;
                // $company_land->company_land_name = $request->input('company_land_name');
                // $company_land->company_land_category_id = $request->input('company_land_category_id');
                // $company_land->save();
                Session::flash('success_msg', 'Successfully Updated ');
                return redirect()->route('land_area_listing');
            }
            $post = (object) $request->all();
        }

        return view('land_area/form', [
            'submit'=>route('land_area_edit',$company_land_id),
            'title'=> 'Edit',
            'post'=> $post,
            'company_land_category_sel'=> CompanyLandCategory::get_land_category_sel(),
            'company_name_sel'=> Company::get_company_sel(),


        ])->withErrors($validator);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;
        $user = auth()->user();
        
        if($request->isMethod('post')){
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'company_land_name' => 'required',
                'company_land_category_id' => 'required',
                'company_id' => 'required',
            ])->setAttributeNames([
                'company_land_name' => 'Company Land Name',
                'company_land_category_id' => 'Company Land Category',
                'company_id' => 'Company',
            ]);
            if (!$validator->fails()) {
                // dd($request->all());
                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');
                // $company = Company::create([
                //     'company_name' =>  $request->input('company_name'),
                //     'company_code' => $request->input('company_code'),
                // ]);
                // $company_id = $company->company_id;
                // dd($company);
                $company_land = CompanyLand::create([
                    // 'company_id' => $company_id,
                    'company_land_category_id' => $request->input('company_land_category_id'),
                    'company_land_name' => $request->input('company_land_name'),
                    'company_land_code' => $request->input('company_land_code'),
                    'company_id' => $user->company_id ?? $request->input('company_id'),
                ]);
                Session::flash('success_msg', 'Successfully added '.$request->input('company_name'));
                return redirect()->route('land_area_listing');
            }
            $post = (object) $request->all();
        }
        return view('land_area/form', [
            'submit'=> route('land_area_add'),
            'title'=> 'Add',
            'post'=> $post,
            'company_land_category_sel'=> CompanyLandCategory::get_land_category_sel(),
            'company_name_sel'=> Company::get_company_sel(),
        ])->withErrors($validator);
    }
}
