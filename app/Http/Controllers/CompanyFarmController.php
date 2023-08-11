<?php

namespace App\Http\Controllers;

use App\Model\CompanyFarm;
use App\Model\CompanyLandCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyFarmController extends Controller
{
    // public function __construct()
    // {
    //     return $this->middleware(['auth', 'super_admin']);
    // }

    public function listing(Request $request)
    {
        $perpage = 10;
        $search = array();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['land_category_search' => [
                        "freetext" =>  $request->input('freetext'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('land_category_search');
                    break;
            }
        }
        $search = session('land_category_search') ?? $search;

        return view('land_category/listing', [
            'submit' => route('company_farm_listing', ['tenant' => tenant('id')]),
            'title' => 'Farm Listing',
            'records' => CompanyFarm::get_records($search, $perpage),
            'search' =>  $search,
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_farm_name' => 'required|unique:tbl_company_farm,company_farm_name',
            ])->setAttributeNames([
                'company_farm_name' => 'Farm Name',
            ]);

            if (!$validator->fails()) {
                $company_farm = CompanyFarm::create([
                    'company_farm_name' => $request->input('company_farm_name'),
                ]);

                $cat = $request->input('category_name');
                if (is_array($cat)) {
                    for ($i = 0; $i < count($cat); $i++) {
                        CompanyLandCategory::create([
                            'company_land_category_name' => $cat[$i],
                            'company_land_category_created' => now(),
                            'company_land_category_updated' => now(),
                            'is_deleted' => 0,
                            'company_farm_id' => $company_farm->company_farm_id
                        ]);
                    }
                }

                Session::flash('success_msg', 'Successfully created new farm. ');
                return redirect()->route('company_farm_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('land_category/form', [
            'submit' => route('company_farm_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'company_land_category_sel' => CompanyLandCategory::get_land_category_sel(),

        ])->withErrors($validator);
    }

    public function edit(Request $request, $company_farm_id)
    {
        $validator = null;
        $post = $company_farm = CompanyFarm::find($company_farm_id);

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_farm_name' => "required|unique:tbl_company_farm,company_farm_name,{$company_farm_id},company_farm_id",
            ])->setAttributeNames([
                'company_farm_name' => 'Farm Name'
            ]);

            if (!$validator->fails()) {
                $company_farm->update([
                    'company_farm_name' => $request->input('company_farm_name'),
                ]);

                $cat = is_array($request->input('category_id')) ? $request->input('category_id') : [];
                $old_cat = CompanyLandCategory::query()->where('company_farm_id', $company_farm_id)->pluck('company_land_category_id')->toArray();
                $remove = array_diff($old_cat, $cat);

                foreach ($cat as $ck => $new_c) {
                    $check_cat = CompanyLandCategory::query()->where('company_land_category_id', $new_c)->where('company_farm_id', $company_farm_id)->first();
                    if ($check_cat) {
                        $exist_cat = CompanyLandCategory::find($check_cat->company_land_category_id);
                        $exist_cat->update([
                            'company_land_category_name' => $request->input('category_name')[$ck],
                            'company_land_category_updated' => now()
                        ]);
                    } else {
                        CompanyLandCategory::create([
                            'company_land_category_name' => $request->input('category_name')[$ck],
                            'company_land_category_created' => now(),
                            'company_land_category_updated' => now(),
                            'is_deleted' => 0,
                            'company_farm_id' => $company_farm->company_farm_id
                        ]);
                    }
                }

                if ($remove) {
                    foreach ($remove as $rmk => $rm) {
                        $rm_cat = CompanyLandCategory::find($rm);
                        $rm_cat->update([
                            'is_deleted' => 1,
                            'company_land_category_updated' => now()
                        ]);
                    }
                }

                Session::flash('success_msg', 'Successfully updated farm');
                return redirect()->route('company_farm_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('land_category.form', [
            'submit' => route('company_farm_edit', ['tenant' => tenant('id'), 'id' => $company_farm_id]),
            'title' => 'Edit',
            'post' => $post,
        ])->withErrors($validator);
    }

    public function ajax_get_farm(Request $request)
    {
        $farmid = $request->input('id');
        $result = CompanyLandCategory::get_farm_cat($farmid);
        return $result;
    }
}
