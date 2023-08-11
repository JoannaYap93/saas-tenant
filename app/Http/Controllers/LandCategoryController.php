<?php

namespace App\Http\Controllers;

use App\Model\CompanyLandCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Session;

class LandCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin'], ['except' => ['listing']]);
    }

    public function listing(Request $request)
    {
        $search = array();
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['land_category_search' => [
                        "freetext" =>  $request->input('freetext'),
                        "company_land_category_id" =>  $request->input('company_land_category_id'),
                        // 'company_id' => $request->input('company_id'),
                        // dd($request->all()),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('land_category_search');
                    break;
            }
        }
        $search = session('land_category_search') ?? $search;
        return view('land_category/listing', [
            'submit' => route('land_category_listing', ['tenant' => tenant('id')]),
            'records' => CompanyLandCategory::get_record($search, 15),
            'search' =>  $search,
            'company_land_category_sel' => CompanyLandCategory::get_land_category_sel(),

            // 'user_status_sel'=> ['' => 'Please select status', 'active' => 'Active', 'suspend' => 'Suspend', 'pending' => 'Pending'],
            // 'user_gender_sel'=> ['' => 'Please select gender', 'Male' => 'Male', 'Female ' => 'Female'],
        ]);
    }

    public function edit(Request $request, $company_land_category_id)
    {

        $validator = null;
        $post = $company = CompanyLandCategory::find($company_land_category_id);

        if ($request->isMethod('post')) {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'company_land_category_name' => 'required',
            ])->setAttributeNames([
                'company_land_category_name' => 'Company Land Category Name',
            ]);

            if (!$validator->fails()) {
                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');
                $update_detail = [
                    // dd($request->all()),
                    'company_land_category_name' =>  $request->input('company_land_category_name'),
                    'company_land_category_updated' => now(),
                    'compang_farm_id' => 0
                ];
                $company->update($update_detail);
                Session::flash('success_msg', 'Successfully Updated ');
                return redirect()->route('land_category_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('land_category/form', [
            'submit' => route('land_category_edit', ['tenant' => tenant('id'), 'id' => $company_land_category_id]),
            'title' => 'Edit',
            'post' => $post,
            'company_land_category_sel' => CompanyLandCategory::get_land_category_sel(),

        ])->withErrors($validator);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;


        if ($request->isMethod('post')) {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'company_land_category_name' => 'required',
            ])->setAttributeNames([
                'company_land_category_name' => 'Company Land Category Name',
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
                $land_category = CompanyLandCategory::create([
                    // 'company_id' => $company_id,
                    'company_land_category_name' =>  $request->input('company_land_category_name'),
                    'company_land_category_created' => now(),
                    'company_land_category_updated' => now(),
                    'compang_farm_id' => 0
                ]);
                Session::flash('success_msg', 'Successfully added ' . $request->input('company_land_category_name'));
                return redirect()->route('land_category_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('land_category/form', [
            'submit' => route('land_category_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'company_land_category_sel' => CompanyLandCategory::get_land_category_sel(),
        ])->withErrors($validator);
    }

    public function delete(Request $request)
    {
        $company_land_category_id = $request->input('company_land_category_id');
        $company_land_category = CompanyLandCategory::find($company_land_category_id);
        // $company_land_category_id = CompanyLandCategory::find($request->input('$company_land_category_id'));

        if ($company_land_category) {
            $company_land_category->update([
                'company_land_category_updated' => now(),
                'is_deleted' => 1
            ]);
            Session::flash('success_msg', 'Deleted successfully!');
            return redirect()->route('land_category_listing', ['tenant' => tenant('id')]);
        } else {
            Session::flash('fail_msg', 'Product not found!');
            return redirect()->route('land_category_listing', ['tenant' => tenant('id')]);
        }
    }
}
