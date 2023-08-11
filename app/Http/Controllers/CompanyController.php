<?php

namespace App\Http\Controllers;

use App\Model\ClaimApprovalStep;
use App\Model\CompanyLandCategory;
use App\Model\Company;
use App\Model\CompanyBank;
use App\Model\CompanyClaimApproval;
use App\Model\CompanyFarm;
use App\Model\CompanyLand;
use App\Model\CompanyLandTreeLog;
use App\Model\Product;
use App\Model\ProductCompanyLand;
use App\Model\User;
use App\Model\SettingBank;
use App\Model\Setting;
use App\Model\UserLand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['listing', 'edit_land', 'ajax_company_land_user', 'ajax_company_land_warehouse','add_company_bank','ajax_get_company_land', 'edit_pic']]);
    }

    public function listing(Request $request)
    {
        $search = array();
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['company_search' => [
                        "freetext" =>  $request->input('freetext'),
                        "company_id" =>  $request->input('company_id'),
                        "company_code" =>  $request->input('company_code'),
                        'category_id' => $request->input('category_id'),
                        'enable_gst' => $request->input('enable_gst'),
                        'enable_collect' => $request->input('enable_collect'),
                        'farm' => $request->input('farm'),
                        'setting_bank_id' => $request->input('setting_bank_id')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('company_search');
                    break;
            }
        }
        $search = session('company_search') ?? $search;

        return view('company.listing', [
            'submit' => route('company_listing', ['tenant' => tenant('id')]),
            'records' => Company::get_record($search, 10),
            'search' =>  $search,
            'title' => 'Add',
            'company_name_sel' => Company::get_company_sel(),
            'company_code_sel' => Company::get_company_code_sel(),
            'category' => CompanyLandCategory::get_land_category_sel(),
            'farm' => CompanyFarm::get_all_farm(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
            'last_updated' => CompanyLandTreeLog::get_last_updated(),
            'default_budget' => Setting::where('setting_slug', 'default_budget_per_tree')->pluck('setting_value')->first(),
            'user_pic' => CompanyClaimApproval::get_pic_approval(),
        ]);
    }

    public function edit(Request $request, $company_id)
    {
        $validator = null;
        $user = auth()->user();
        $post = Company::query()->where('company_id', $company_id);

        if ($user->company_id != 0) {
            $post = $post->where('company_id', $user->company_id)->first();
        } else {
            $post = $post->first();
        }

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Company, Please Try Again');
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }
        $company = Company::find($company_id);

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required',
                'company_farm' => 'required',
                'phone' => 'required',
                'email' => 'nullable',
                'reg_no' => 'required',
                'address' => 'required',
                'company_land_category_id' => 'required',
            ])->setAttributeNames([
                'company_name' => 'Fullname',
                'phone' => 'Phone',
                'email' => 'Email',
                'reg_no' => 'Registration No',
                'address' => 'Address',
                'company_farm' => 'Land',
                'company_land_category_id' => 'Category',
                'setting_bank_id' => 'Bank',
                'company_bank_acc_name' => 'Bank Account Name',
                'company_bank_acc_no' => 'Bank Account No.'
            ]);

            if (!$validator->fails()) {

                $update_detail = [
                    'company_name' =>  $request->input('company_name'),
                    'company_enable_gst' => $request->input('enable_gst') ?? 0,
                    'company_force_collect' => $request->input('collect_code') ?? 0,
                    'company_address' => $request->input('address') ?? '',
                    'company_email' => $request->input('email') ?? '',
                    'company_reg_no' => $request->input('reg_no') ?? '',
                    'company_phone' => $request->input('phone') ?? '',
                ];

                $post->update($update_detail);

                // if (auth()->user()->user_type_id == 1) {
                if(auth()->user()->can('company_land_manage')){
                    $land = $request->input('company_land_id');
                    if (count($land) > 0) {
                        $old_land = CompanyLand::query()->where('company_id', $company_id)->pluck('company_land_id')->toArray();
                        $new_land = $land;

                        $remove = array_diff($old_land, $new_land);

                        for ($i = 0; $i < count($land); $i++) {
                            $find_land = CompanyLand::find($land[$i]);
                            $farm = $request->input('company_farm');
                            $this_farm = CompanyFarm::find($farm[$i]);
                            $this_cat = CompanyLandCategory::find($request->input('company_land_category_id')[$i]);

                            if ($find_land) {
                                $find_land->update([
                                    'company_land_name' => $this_farm->company_farm_name . ' - ' . $this_cat->company_land_category_name,
                                    'company_land_category_id' => $request->input('company_land_category_id')[$i],
                                    'company_land_total_acre' => $request->input('company_land_total_acre')[$i]
                                ]);

                                $old_product = ProductCompanyLand::query()->where('company_land_id', $find_land->company_land_id)->pluck('product_id')->toArray();
                                $new_product = $request->input('product_id_' . ($i + 1));
                                if($new_product){
                                $remove_product = array_diff($old_product, $new_product);


                                for ($np = 0; $np < count($new_product); $np++) {
                                    $check_prd = ProductCompanyLand::query()->where('company_land_id', $find_land->company_land_id)->where('product_id', $new_product[$np])->first();
                                    if (!$check_prd) {
                                        ProductCompanyLand::create([
                                            'product_id' => $new_product[$np],
                                            'company_land_id' => $find_land->company_land_id
                                        ]);
                                    }
                                }
                              }
                                if ($new_product) {
                                    foreach ($remove_product as $rk => $rm) {
                                        $delete_p = ProductCompanyLand::query()->where('company_land_id', $find_land->company_land_id)->where('product_id', $rm);
                                        $delete_p->delete();
                                    }
                                }else if (!$new_product) {
                                  $delete_land = ProductCompanyLand::query()->where('company_land_id', $find_land->company_land_id)->where('product_id', $old_product);
                                  $delete_land->delete();
                                }
                            } else {
                                $nland = CompanyLand::create([
                                    'company_land_name' => $this_farm->company_farm_name . ' - ' . $this_cat->company_land_category_name,
                                    'company_land_category_id' => $request->input('company_land_category_id')[$i],
                                    'company_land_total_acre' => $request->input('company_land_total_acre')[$i],
                                    'company_id' => $company->company_id,
                                    'company_land_code' => Str::random(5),
                                    'company_land_created' => now(),
                                    'company_land_updated' => now()
                                ]);

                                for ($p = 0; $p < count($request->input('product_id_' . ($i + 1))); $p++) {
                                    ProductCompanyLand::create([
                                        'product_id' => $request->input('product_id_' . ($i + 1))[$p],
                                        'company_land_id' => $nland->company_land_id
                                    ]);
                                }
                            }
                        }
                        if ($remove) {
                            foreach ($remove as $key => $del) {
                                $delete = CompanyLand::find($del);
                                $delete->delete();
                            }
                        }
                    } else {
                        Session::flash('Error while adding company land. Please check again.');
                        return redirect()->route('company_listing', ['tenant' => tenant('id')]);
                    }

                    if(!is_null($request->input('company_bank_acc_no'))){
                        $company_bank_id = $request->input('company_bank_id');

                        $old_bank = CompanyBank::query()->where('company_id', $company->company_id)->pluck('company_bank_acc_no')->toArray();
                        $new_bank = $request->input('company_bank_acc_no');

                        $remove = array_diff($old_bank, $new_bank);

                        foreach($request->input('company_bank_acc_no') as $key => $bank_details){
                            $find_bank = CompanyBank::find($company_bank_id[$key]);

                            if ($find_bank) {
                                $find_bank->update([
                                    'setting_bank_id' => $request->input('setting_bank_id')[$key],
                                    'company_bank_acc_name' => $request->input('company_bank_acc_name')[$key],
                                    'company_bank_acc_no' => $request->input('company_bank_acc_no')[$key],
                                ]);
                            } else {
                                $company_bank = CompanyBank::create([
                                    'setting_bank_id' =>$request->input('setting_bank_id')[$key],
                                    'company_bank_acc_name' =>$request->input('company_bank_acc_name')[$key],
                                    'company_bank_acc_no' =>$request->input('company_bank_acc_no')[$key],
                                    'company_id' => $company_id,
                                    'is_deleted' => 0,
                                ]);
                            }

                            if ($remove) {
                                foreach ($remove as $key => $del) {
                                    $delete = CompanyBank::query()->where('company_bank_acc_no', $del);
                                    $delete->update([
                                        'is_deleted' => 1,
                                    ]);
                                }
                            }
                        }
                    }

                }

                if ($request->file('company_logo')) {
                    $company->addMediaFromRequest('company_logo')->toMediaCollection('company_logo');
                }

                Session::flash('success_msg', 'Successfully Updated ');
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('company/form', [
            'submit' => route('company_edit', ['tenant' => tenant('id'), 'id' => $company_id]),
            'title' => 'Edit',
            'post' => $post,
            'admin' => false,
            'company_media' => $company,
            'category' => CompanyLandCategory::get_land_category_sel(),
            'farm' => CompanyFarm::get_all_farm(),
            'product' => Product::get_sel(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
            'user_sel' => User::get_user_sel(),
        ])->withErrors($validator);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;



        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required',
                'phone' => 'required',
                'email' => 'nullable',
                'reg_no' => 'required',
                'address' => 'required',
                'company_farm' => 'required',
                'company_land_category_id' => 'required',
                'user_email' => 'required|unique:tbl_user,user_email',
                'user_fullname' => 'required',
                'user_mobile' => 'required|unique:tbl_user,user_mobile',
                'user_unique_code' => 'required',
                'user_dob' => 'required',
                'user_gender' => 'required',
                'setting_bank_id.*' => 'required_with:company_bank_acc_no.*|nullable',
                'company_bank_acc_name.*' => 'required_with:company_bank_acc_no.*|nullable',
                'company_bank_acc_no.*' => 'required_with:company_bank_acc_name.*, setting_bank_id.*|unique:tbl_company_bank,company_bank_acc_no|nullable'
            ])->setAttributeNames([
                'company_name' => 'Fullname',
                'phone' => 'Phone',
                'email' => 'Email',
                'reg_no' => 'Registration No',
                'address' => 'Address',
                'company_farm' => 'Land',
                'company_land_category_id', 'Category',
                'user_email' => 'Email',
                'user_fullname' => 'Fullname',
                'user_mobile' => 'Mobile No',
                'user_unique_code' => 'Unique Code',
                'user_dob' => 'Birth Date',
                'user_gender' => 'Gender',
                'setting_bank_id.*' => 'Bank Name',
                'company_bank_acc_name.*' => 'Bank Account Name',
                'company_bank_acc_no.*' => 'Bank Account No.'
            ]);

            if (!$validator->fails()) {
                $company = Company::create([
                    'company_name' =>  $request->input('company_name'),
                    'company_code' => $request->input('company_code') ?? Str::random(5),
                    'company_enable_gst' => $request->input('enable_gst') ?? 0,
                    'company_force_collect' => $request->input('collect_code') ?? 0,
                    'company_address' => $request->input('address') ?? '',
                    'company_email' => $request->input('email') ?? '',
                    'company_reg_no' => $request->input('reg_no') ?? '',
                    'company_phone' => $request->input('phone') ?? '',
                ]);

                $claim_pic = $request->input('pic_claim');

                $farm = $request->input('company_farm');
                if (count($farm)) {
                    for ($i = 0; $i < count($farm); $i++) {
                        $this_farm = CompanyFarm::find($farm[$i]);
                        $this_cat = CompanyLandCategory::find($request->input('company_land_category_id')[$i]);
                        $nland = CompanyLand::create([
                            'company_land_name' => $this_farm->company_farm_name . ' - ' . $this_cat->company_land_category_name,
                            'company_land_category_id' => $request->input('company_land_category_id')[$i],
                            'company_id' => $company->company_id,
                            'company_land_code' => Str::random(5),
                            'company_land_created' => now(),
                            'company_land_updated' => now()
                        ]);

                        for ($p = 0; $p < count($request->input('product_id_' . ($i + 1))); $p++) {
                            ProductCompanyLand::create([
                                'product_id' => $request->input('product_id_' . ($i + 1))[$p],
                                'company_land_id' => $nland->company_land_id
                            ]);
                        }
                    }
                }

                $bank_details = $request->input('company_bank_acc_no');
                if (count($bank_details)) {
                    for ($i = 0; $i < count($request->input('company_bank_acc_no')); $i++) {
                        $company_bank = CompanyBank::create([
                            'setting_bank_id' =>$request->input('setting_bank_id')[$i],
                            'company_bank_acc_name' =>$request->input('company_bank_acc_name')[$i],
                            'company_bank_acc_no' =>$request->input('company_bank_acc_no')[$i],
                            'company_id' => $company->company_id,
                            'is_deleted' => 0,
                        ]);
                    }
                }

                $user_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('user_mobile'));
                if (substr($user_mobile_initial, 0, 1) == '0') {
                    $user_mobile = '6' . $user_mobile_initial;
                } elseif (substr($user_mobile_initial, 0, 1) == '1') {
                    $user_mobile = "60" . $user_mobile_initial;
                } else {
                    $user_mobile = $user_mobile_initial;
                }

                $new_user = User::create([
                    'user_type_id' => 2,
                    'user_email' => $request->input('user_email'),
                    'password' => bcrypt(substr($request->input('user_mobile'), -8)),
                    'user_fullname' => $request->input('user_fullname'),
                    'user_nationality' => 'Malaysia',
                    'user_gender' => $request->input('user_gender'),
                    'user_dob' => $request->input('user_dob'),
                    'user_status' => 'active',
                    'user_cdate' => now(),
                    'user_udate' => now(),
                    'is_deleted' => 0,
                    'user_mobile' => $user_mobile,
                    'user_unique_code' => $request->input('user_unique_code'),
                    'company_id' => $company->company_id,
                    'user_join_date' => now()
                ]);

                $role = Role::findById(1);
                if ($role) {
                    $new_user->syncRoles($role->name);
                }
                $available_company_land=CompanyLand::get_by_company_id($company->company_id);
                if($available_company_land){
                    foreach($available_company_land as $land){
                        UserLand::create([
                            'user_id'=>$new_user->user_id,
                            'company_land_id'=>$land->company_land_id,
                        ]);
                    }
                }

                if ($request->file('company_logo')) {
                    $company->addMediaFromRequest('company_logo')->toMediaCollection('company_logo');
                }

                Session::flash('success_msg', 'Successfully added ' . $request->input('company_name'));
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
            $post->hasMedia = false;
        }

        return view('company.form', [
            'submit' => route('company_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'admin' => false,
            'company_media' => false,
            'category' => CompanyLandCategory::get_land_category_sel(),
            'farm' => CompanyFarm::get_all_farm(),
            'product' => Product::get_sel(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
            'user_sel' => User::get_user_sel(),
        ])->withErrors($validator);
    }

    public function edit_land(Request $request, $id)
    {
        $validation = null;
        $user = auth()->user();
        $company = Company::query()->where('company_id', $id);
        $setting_bank_sel = SettingBank::get_setting_bank_sel();

        if ($user->company_id != 0) {
            $company = $company->where('company_id', $user->company_id)->first();
        } else {
            $company = $company->first();
        }

        if (!$company) {
            Session::flash('fail_msg', 'Invalid Company! Please try another one.');
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }

        $company_media = Company::find($id);

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'nullable',
                'address' => 'required',
                'reg_no' => 'required',
                'setting_bank_id' => 'nullable',
                'company_bank_acc_name' => 'nullable',
                'company_bank_acc_no' => 'nullable',
            ])->setAttributeNames([
                'phone' => 'Phone',
                'email' => 'Email',
                'address' => 'Address',
                'reg_no' => 'Registration No.',
                'setting_bank_id' => 'Bank',
                'company_bank_acc_name' => 'Bank Account Name',
                'company_bank_acc_no' => 'Bank Account No.',
            ]);

            if (!$validation->fails()) {

                $cmp = Company::find($id);
                $cmp->update([
                    'company_enable_gst' => $request->input('enable_gst') ?? 0,
                    'company_force_collect' => $request->input('collect_code') ?? 0,
                    'company_phone' => $request->input('phone'),
                    'company_email' => $request->input('email')?? '',
                    'company_address' => $request->input('address'),
                    'company_reg_no' => $request->input('reg_no'),
                    'setting_bank_id' => $request->input('setting_bank_id')?? '',
                    'company_bank_acc_name' => $request->input('company_bank_acc_name')?? '',
                    'company_bank_acc_no' => $request->input('company_bank_acc_no')?? '',
                ]);

                $land = $request->input('company_land_id');
                for ($i = 0; $i < count($land); $i++) {
                    $old_product = ProductCompanyLand::query()->where('company_land_id', $land[$i])->pluck('product_id')->toArray();
                    $new_product = $request->input('product_id_' . ($i + 1));
                    $remove_product = array_diff($old_product, $new_product);

                    for ($np = 0; $np < count($new_product); $np++) {
                        $check_prd = ProductCompanyLand::query()->where('company_land_id', $land[$i])->where('product_id', $new_product[$np])->first();
                        if (!$check_prd) {
                            ProductCompanyLand::create([
                                'product_id' => $new_product[$np],
                                'company_land_id' => $land[$i]
                            ]);
                        }
                    }
                    if ($remove_product) {
                        foreach ($remove_product as $rk => $rm) {
                            $delete_p = ProductCompanyLand::query()->where('company_land_id', $land[$i])->where('product_id', $rm);
                            $delete_p->delete();
                        }
                    }
                }

                if ($request->file('company_logo')) {
                    $cmp->addMediaFromRequest('company_logo')->toMediaCollection('company_logo');
                }

                Session::flash('success_msg', 'Successfully updated company');
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }

            $company = (object) $request->all();
        }

        return view('company.form', [
            'submit' => route('company_edit_land', ['tenant' => tenant('id') , 'id' => $id]),
            'title' => 'Edit Details - ',
            'post' => $company,
            'admin' => true,
            'company_media' => $company_media,
            'category' => CompanyLandCategory::get_land_category_sel(),
            'farm' => CompanyFarm::get_all_farm(),
            'product' => Product::get_sel(),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
        ]);
    }


    public function company_status(Request $request)
    {
        $action = $request->input('action');
        $company_id = $request->input('company_id');
        $company = Company::find($company_id);
        if($company){
            $user = User::query()->where('company_id', $company_id)->get();
            if($user != []){
                foreach ($user as $key => $single_user){
                    $data1['user_status'] = $action;
                    $data1['is_deleted'] = $action == 'active' ? 0 : 1;
                    $single_user->update($data1);
                }
                $data2['company_status'] = $action;
                $company->update($data2);
                Session::flash('success_msg', "Successfully {$action} {$company->company_name} and all users.");
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }else{
                $data['company_status'] = $action;
                $company->update($data);
                Session::flash('success_msg', "Successfully {$action} {$company->company_name}.");
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
        }else{
            Session::flash('failed_msg', "Invalid Company");
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }
    }

    public function add_company_bank(Request $request, $company_id)
    {
        $validation = null;
        $company = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_bank_id' => 'required',
                'company_bank_acc_name' => 'required',
                'company_bank_acc_no' => 'required|unique:tbl_company_bank,company_bank_acc_no'
            ])->setAttributeNames([
                'setting_bank_id' => 'Bank Name',
                'company_bank_acc_name' => 'Bank Account Name',
                'company_bank_acc_no' => 'Bank Account No.',
            ]);

            if (!$validation->fails()) {
                $company_bank = CompanyBank::create([
                    'setting_bank_id' =>$request->input('setting_bank_id'),
                    'company_bank_acc_name' =>$request->input('company_bank_acc_name'),
                    'company_bank_acc_no' =>$request->input('company_bank_acc_no'),
                    'company_id' => $company_id,
                    'is_deleted' => 0,
                ]);

                if (is_null($request->input('company_land_id')) == false) {
                    foreach ($request->input('company_land_id') as $company_land_id) {
                        if (isset($company_land_id)) {
                            $company_land = CompanyLand::find($company_land_id);
                            if ($company_land) {
                                $company_land->update([
                                    'company_bank_id' => $company_bank->company_bank_id,
                                ]);
                            }
                        }
                    }
                }

                Session::flash('success_msg', 'Successfully added company bank details');
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }else{
                $company = (object) $request->all();
            }
        }
        return view('company_bank.form', [
            'submit' => route('add_company_bank', ['tenant' => tenant('id'), 'id' => $company_id]),
            'title' => 'Add',
            'company' => $company,
            'company_land' => CompanyLand::get_company_land_by_company_id($company_id),
            'setting_bank_sel' => SettingBank::get_setting_bank_sel(),
        ])->withErrors($validation);
    }

    public function edit_pic(Request $request, $id)
    {
        $validation = null;
        $user = auth()->user();
        $company = Company::query()->where('company_id', $id);

        if ($user->company_id != 0) {
            $company = $company->where('company_id', $user->company_id)->first();
        } else {
            $company = $company->first();
        }

        if (!$company) {
            Session::flash('fail_msg', 'Invalid Company! Please try another one.');
            return redirect()->route('company_listing', ['tenant' => tenant('id')]);
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
            ])->setAttributeNames([
            ]);

            if (!$validator->fails()) {
                $claim_pic = $request->input('step_claim');
                foreach($claim_pic as $cp_key => $cp){
                    $cp_key++;
                    if($cp){
                        $array_user_id[] = $request->input('claim_user_id_' . $cp_key);
                    }
                }

                $old_user_id = CompanyClaimApproval::where('company_id', $id)->pluck('user_id')->toArray();
                $removed_user_id = array_udiff($old_user_id,$array_user_id, function ($a, $b) { return (int)$a - (int)$b; });
                if(@$removed_user_id)
                {
                    foreach($removed_user_id as $removed){
                        $old_company_claim_approval = CompanyClaimApproval::where('user_id',$removed)->get();
                        foreach($old_company_claim_approval as $old_item){
                            $old_item->delete();
                        }
                    }
                }

                foreach($claim_pic as $pic_key => $pic)
                {
                    $pic_key++;

                    $company_claim_user_ids = $request->input('claim_user_id_' . $pic_key) ? $request->input('claim_user_id_' . $pic_key) : array();
                    $existing_company_claim = isset($company['claim_pic_user'][$pic_key]) ? $company['claim_pic_user'][$pic_key] : array();
                    $existing_company_claim_user_ids = array();
                    foreach ($existing_company_claim as $curr_company_claim) {
                        array_push($existing_company_claim_user_ids, $curr_company_claim['user_id']);
                    }
                    $company_claim_new = array_diff($company_claim_user_ids, $existing_company_claim_user_ids);
                    foreach ($company_claim_new as $new) {

                        $claim_approval = CompanyClaimApproval::Create([
                            'company_id' => $id,
                            'claim_approval_step_id' => $pic,
                            'user_id' => $new ?? 0,
                            'company_claim_approval_cdate' => now(),
                        ]);
                    }
                }

                Session::flash('success_msg', 'Successfully Updated PIC for '. $company->company_name);
                return redirect()->route('company_listing', ['tenant' => tenant('id')]);
            }
            $company = (object) $request->all();
        }

        return view('company.form_pic', [
            'submit' => route('claim_pic', ['tenant' => tenant('id'), 'id' => $company->company_id]),
            'title' => 'Edit',
            'post' => $company,
            'user_sel' => User::get_user_sel(),
            'claim_approval_step' =>ClaimApprovalStep::get_step_assign(),
        ])->withErrors($validation);
    }

    public function check_company_code(Request $request)
    {
        $code = $request->input('code');
        return Company::get_company_code($code);
    }

    public function ajax_company_land_user(Request $request)
    {
        $company = $request->input('id');
        $land_user = Company::get_land_user($company);
        return $land_user;
    }

    public function ajax_get_customer_by_category(Request $request)
    {
        $company = $request->input('company');
        $category = $request->input('category');
        if($company == 0){
            $customer = Company::get_customer_by_category_without_company($category);
        }else{
            $customer = Company::get_customer_by_category($company, $category);
        }
        return $customer;
    }

    public function ajax_get_company_land(Request $request)
    {
        $company_id = $request->input('company_id');
        return CompanyLand::get_by_user_company_id($company_id);
    }

    public function ajax_company_land_warehouse(Request $request)
    {
        $company = $request->input('id');
        $land_warehouse = Company::get_land_warehouse($company);
        return $land_warehouse;
    }
}
