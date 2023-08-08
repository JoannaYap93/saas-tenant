<?php

namespace App\Http\Controllers;

use Session;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Company;
use App\Model\Setting;
use App\Model\Customer;
use App\Model\UserType;
use App\Model\EventDate;
use App\Model\CustomerLog;
use App\Model\CustomerPIC;
use App\Model\Transaction;
use App\Model\UserPlatform;
use Illuminate\Support\Str;
use App\Model\SettingOutlet;
use Illuminate\Http\Request;
use App\Model\CustomerPICLog;
use App\Model\UserDesignation;
use App\Model\CustomerCategory;
use App\Model\SettingWarehouse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Model\CustomerCreditHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $pageNumber = 1;
        if (isset($_GET['page'])) {
            $pageNumber = $_GET['page'];
        }
        $search = array();
        $company = Company::get_company_sel();
        $customer_category = CustomerCategory::get_customer_category_sel();
        // $user_type = UserType::get_user_type_sel();
        // unset($user_type[2], $user_type[3]);

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['customer_search' => [
                        "freetext" =>  $request->input('freetext'),
                        "company_id" =>  $request->input('company_id'),
                        "customer_category_id" => $request->input('customer_category_id'),
                        "customer_status" => $request->input('customer_status'),
                        // "user_type_id"=>  $request->input('user_type_id'),
                        // "user_gender"=>  $request->input('user_gender'),
                        // "user_role_id"=> $request->input('user_role_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('customer_search');
                    break;
            }
        }
        $search = session('customer_search') ? session('customer_search') : $search;
        $customers = Customer::get_record($search, [1]);
        $customer_status = Customer::get_customer_status_sel();

        // $users = User::get_record($search, 15, $pageNumber, [1]);
        $perpage = 15;
        $total_records = count($customers);
        $start = ($pageNumber * $perpage) - $perpage;
        // $users = array_slice($users->toArray(), $start, $perpage, true);
        // $users = $users->slice($start,$perpage);
        // $users = new Paginator($users, $total_records,$perpage, $pageNumber,[
        //     'path' => $request->url(),
        //     'query' => $request->query(),
        // ]);
        return view('customer/listing', [
            'submit' => route('customer_listing'),
            'title' => 'Add',
            'customers' =>  $customers, //User::get_record($search, 15, $pageNumber, [1]),
            'search' =>  $search,
            'company_sel' => $company,
            'customer_category_sel' => $customer_category,
            'customer_status_sel' => ['' => 'Please Select Customer Status'] + $customer_status,
            // 'user_type_sel'=> $user_type,
            // 'user_role_sel'=> UserType::get_user_role_sel(),
            // 'user_status_sel'=> ['' => 'Please select status', 'active' => 'Active', 'suspend' => 'Suspend', 'pending' => 'Pending'],
            // 'user_gender_sel'=> ['' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'],
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;
        $user = auth()->user();
        $company = Company::get_company_sel();
        $customer_category = CustomerCategory::get_customer_category_sel();
        // dd($company);
        // $user_type = UserType::get_user_type_sel();
        // unset($user_type[2], $user_type[3]);
        if(count($company) > 1 && count($customer_category) > 1) {
        if ($request->isMethod('post')) {

            $customer_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_mobile_no'));
            if (substr($customer_mobile_initial, 0, 1) == '0') {
                $customer_mobile = '6' . $customer_mobile_initial;
            } elseif (substr($customer_mobile_initial, 0, 1) == '1') {
                $customer_mobile = "60" . $customer_mobile_initial;
            } else {
                $customer_mobile = $customer_mobile_initial;
            }

            $customer_acc_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_acc_mobile_no'));
            if (substr($customer_acc_mobile_initial, 0, 1) == '0') {
                $customer_acc_mobile_no = '6' . $customer_acc_mobile_initial;
            } elseif (substr($customer_acc_mobile_initial, 0, 1) == '1') {
                $customer_acc_mobile_no = "60" . $customer_acc_mobile_initial;
            } else {
                $customer_acc_mobile_no = $customer_acc_mobile_initial;
            }

            // $request->merge([
            //     'user_nric' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', strval($request->input('user_nric')))),
            // ]);

            $validator = Validator::make($request->all(), [
                'customer_email' => 'max:100',
                // 'password' => 'min:8|max:100|required_with:confirm_password|same:confirm_password',
                // 'confirm_password' => 'required|min:8',
                'customer_name' => 'required|max:100',
                // 'customer_company_name' => 'required',
                'customer_code' => 'required|min:4|max:6',
                'company_id' => Rule::requiredIf(auth()->user()->user_type_id == 1),
                // 'user_nric' => 'required|unique:tbl_user,user_nric', // |digits:12
                // 'user_unique_code' => 'nullable|unique:tbl_user,user_unique_code',
                // 'user_nationality' => 'required',
                // 'user_gender' => 'required',
                // 'user_dob' => 'required',
                // 'user_profile_photo' => 'nullable',
                'customer_mobile_no' => 'required|min:8|max:12', // phone_number|unique:tbl_user,user_mobile
                'customer_address' => "required|max:150",
                'customer_address2' => "nullable|max:150",
                'customer_state' => "required",
                'customer_city' => "required",
                'customer_postcode' => 'required', // |digits:5
                'customer_country' => 'required',
                'customer_category_id' => 'required',
                'customer_company_name' => 'required',
                'warehouse_id' => Rule::requiredIf($request->input('customer_category_id') == 10),
                'customer_acc_name' => 'nullable|max:100',
                'customer_acc_mobile_no' => 'nullable|min:8|max:12',
            ])->setAttributeNames([
                'customer_email' => 'Customer Email',
                // 'password' => 'Password',
                // 'confirm_password' => 'Confirm Password',
                'customer_name' => 'Customer Name',
                'customer_code' => 'Customer Code',
                // 'customer_company_name' => 'Company Name',
                // 'user_nric' => 'NRIC',
                // 'user_unique_code' => 'Unique Code',
                // 'user_nationality' => 'Nationality',
                // 'user_gender' => 'Gender',
                // 'user_dob' => 'Date of Birth',
                // 'user_profile_photo' => 'Profile Picture',
                'customer_mobile_no' => 'Mobile Number',
                'customer_address' => "Address",
                'customer_address2' => "Address 2",
                'customer_state' => "State",
                'customer_city' => "City",
                'customer_postcode' => 'Postcode',
                'customer_country' => 'Country',
                'customer_category_id' => 'Category',
                'customer_pic_ic' => 'Person In Charge IC',
                'customer_pic_name' => 'Person In Charge Name',
                'customer_company_name' => 'Company Name',
                'warehouse_id' => 'Warehouse',
                'customer_acc_name' => 'Account Name',
                'customer_acc_mobile_no' => 'Account Mobile Number',
                'company_id' => 'Company Name'
            ]);

            // $mobile_exist = Customer::check_customer_mobile_exist($customer_mobile, 0);
            // if ($mobile_exist) {
            //     $validator->after(function ($validator) {
            //         $validator->getMessageBag()->add('customer_mobile_no', 'The Mobile Number has already been taken.');
            //     });
            // }
            // dd($validator);
            if(auth()->user()->user_type_id == 1){
                $company_id = $request->input('company_id');
            }else{
                  $company_id = $user->company_id;
            }
              

    
            // $company_name = Company::get_company_name_by_id($company_id);

            // dd($company_name);
            if (!$validator->fails()) {

                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');
                //
                // $unique_code = '';

                // if ($request->input('user_unique_code')) {
                //     $unique_code = $request->input('user_unique_code');
                // } else {
                //     $unique_code = User::get_unique_code();
                // }
                // $referer_user_id = Setting::get_by_slug('default_parent_id');
                $customer = Customer::create([
                    // 'parent_user_id' => $referer_user_id,
                    // 'user_unique_code' => $request->input('user_unique_code') ?? Str::random(6),
                    // 'user_unique_code' => $unique_code,
                    'customer_email' => $request->input('customer_email'),
                    // 'password' => bcrypt($request->input('password')),
                    'customer_name' =>  $request->input('customer_name'),
                    // 'customer_company_name' => $company_name,
                    'company_id' => $company_id,
                    // 'user_nationality' => $request->input('user_nationality'),
                    // 'user_gender' => $request->input('user_gender'),
                    // 'user_dob' => $request->input('user_dob'),
                    'customer_mobile_no' => $customer_mobile,
                    'customer_code' => $request->input('customer_code'),
                    // 'user_type_id' =>  $user_type_id,
                    // 'user_logindate' => now(),
                    'customer_created' => now(),
                    'customer_updated' => now(),
                    // 'user_join_date' => now(),
                    // 'user_ip' => '',
                    // 'user_profile_photo' => '',
                    // 'user_platform_id' => '1',
                    'customer_address' => $request->input('customer_address') ? $request->input('customer_address') : '',
                    'customer_address2' => $request->input('customer_address2') ? $request->input('customer_address2') : '',
                    'customer_city' => $request->input('customer_city') ? $request->input('customer_city') : '',
                    'customer_state' => $request->input('customer_state') ? $request->input('customer_state') : '',
                    'customer_postcode' => $request->input('customer_postcode') ? $request->input('customer_postcode') : '',
                    'customer_country' => $request->input('customer_country'),
                    'customer_category_id' => $request->input('customer_category_id'),
                    'customer_company_name' => $request->input('customer_company_name'),
                    'warehouse_id' => $request->input('warehouse_id'),
                    'customer_acc_name' => $request->input('customer_acc_name'),
                    'customer_acc_mobile_no' => $customer_acc_mobile_no,
                    // 'user_credit' => 0,
                    // 'user_slug' => $request->input('user_slug'),
                    // 'default_outlet_id' => $request->input('default_outlet_id'),
                    // 'user_designation_id' => $request->input('user_designation_id'),
                ]);
                $customer_log = CustomerLog::insert([
                    'customer_id' => $customer->customer_id,
                    'customer_log_created' => now(),
                    'customer_log_updated' => now(),
                    'customer_log_action' => "Add",
                    'customer_log_description' => 'Customer Added By ' . $user->user_fullname,
                    'user_id' => Auth::id()
                ]);

                if(is_null($request->input('customer_pic_ic')) == false){
                    foreach($request->input('customer_pic_ic') as $key => $customer_pic_ic){
                        $customer_pic_name = $request->input('customer_pic_name')[$key];
                        $customer_pic_mobile_no_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_pic_mobile_no')[$key]);
                        if (substr($customer_pic_mobile_no_initial, 0, 1) == '0') {
                            $customer_pic_mobile_no = '6' . $customer_pic_mobile_no_initial;
                        } elseif (substr($customer_mobile_initial, 0, 1) == '1') {
                            $customer_pic_mobile_no = "60" . $customer_pic_mobile_no_initial;
                        } else {
                            $customer_pic_mobile_no = $customer_pic_mobile_no_initial;
                        }
                        $customer_pic = CustomerPIC::create([
                            'customer_pic_ic' => $customer_pic_ic,
                            'customer_pic_name' => $customer_pic_name,
                            'customer_pic_mobile_no' => $customer_pic_mobile_no,
                            'customer_pic_created' => now(),
                            'customer_pic_updated' => now(),
                            'customer_id' => $customer->customer_id,
                        ]);
                        CustomerPICLog::insert([
                            'customer_pic_id' => $customer_pic->customer_pic_id,
                            'customer_pic_log_created' => now(),
                            'customer_pic_log_updated' => now(),
                            'customer_pic_log_action' => "Add",
                            'customer_pic_log_description' => 'Customer PIC Added By ' . $user->user_fullname,
                            'user_id' => Auth::id()
                        ]);
                    }
                }



                // if ($user_type_id == 1 && $user_role_id > 0) {
                //     $role = Role::findById($user_role_id);
                //     if ($role) {
                //         $user->syncRoles($role->name);
                //     }
                // }

                Session::flash('success_msg', 'Successfully added ' . $request->input('user_fullname'));
                return redirect()->route('customer_listing');
            }
            $post = (object) $request->all();
        }
      }else if(count($company) <= 1){
          Session::flash('fail_msg', 'Company is currently empty, please add a company to proceed.');
        return redirect()->route('company_add');
      }else if(count($customer_category) <= 1){
          Session::flash('fail_msg', 'Customer Category is currently empty, please add a category to proceed.');
        return redirect()->route('customer_category_add');
        // return redirect()->route('customer_category_add')->with('failed_msg', 'Customer Category is currently empty, please add a category to proceed.');
      }
        return view('customer/form', [
            'submit' => route('customer_add'),
            'title' => 'Add',
            'edit_code' => false,
            'post' => $post,
            'company_sel' => $company,
            'customer_category_sel' => $customer_category,
            'warehouse_sel' => SettingWarehouse::get_all_warehouse_sel(),
            // 'user_type_sel'=> $user_type,
            // 'setting_outlet_sel' =>SettingOutlet::get_sel(),
            // 'user_role_sel'=> UserType::get_user_role_sel(),
            // 'user_designation_sel' => UserDesignation::get_as_sel(),
            // 'user_gender_sel'=> array('' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'),
            'customer_id' => 0,
        ])->withErrors($validator);
    }

    public function edit(Request $request, $customer_id)
    {
        $validator = null;
        $user = auth()->user();
        $post = Customer::query()->where('customer_id', $customer_id)->with('customer_pic');
        $customer = Customer::find($customer_id);
        $edit_data = false;

        if ($user->company_id != 0) {
            $post = $post->where('company_id', $user->company_id)->first();
        } else {
            $post = $post->first();
        }

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Customer, Please Try Again');
            return redirect()->route('customer_listing');
        }

        // $user_type = UserType::get_user_type_sel();
        $company = Company::get_company_sel();
        $customer_category = CustomerCategory::get_customer_category_sel();
        // unset($user_type[2], $user_type[3]);

        // if(!$user || $user->user_type_id != 1){
        //     Session::flash('fail_msg', 'Invalid Admin, Please try again later.');
        //     return redirect('/admin/listing');
        // }
        // $post->password = 'xxxxxxxx';
        // $user_role = optional($user->roles)->first();
        if(count($company) > 1 && count($customer_category) > 1) {
        if ($request->isMethod('post')) {

            $customer_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_mobile_no'));
            if (substr($customer_mobile_initial, 0, 1) == '0') {
                $customer_mobile = '6' . $customer_mobile_initial;
            } elseif (substr($customer_mobile_initial, 0, 1) == '1') {
                $customer_mobile = "60" . $customer_mobile_initial;
            } else {
                $customer_mobile = $customer_mobile_initial;
            }

            $customer_acc_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_acc_mobile_no'));
            if (substr($customer_acc_mobile_initial, 0, 1) == '0') {
                $customer_acc_mobile_no = '6' . $customer_acc_mobile_initial;
            } elseif (substr($customer_acc_mobile_initial, 0, 1) == '1') {
                $customer_acc_mobile_no = "60" . $customer_acc_mobile_initial;
            } else {
                $customer_acc_mobile_no = $customer_acc_mobile_initial;
            }

            // $request->merge([
            //     'user_nric' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', strval($request->input('user_nric')))),
            // ]);

            $validator = Validator::make($request->all() + ['user_type_id' => Auth::user()->user_type_id], [
                'customer_email' => "max:100",
                // 'password' => ['required', 'min:8'],
                'customer_name' => 'required|max:100',
                'company_id' => Rule::requiredIf(auth()->user()->user_type_id == 1),
                // 'user_nric' => "required||digits:12|unique:tbl_user,user_nric,{$user_id},user_id",
                // 'user_nationality' => 'required',
                // 'customer_code' => "required|min:4|max:6|unique:tbl_customer,customer_code,{$customer->customer_id},customer_id",
                // 'user_gender' => 'required',
                // 'user_dob' => 'required',
                // 'user_profile_photo' => 'nullable',
                'customer_mobile_no' => 'required|min:8|max:12', // phone_number|unique:tbl_user,user_mobile
                'customer_address' => "required|max:150",
                'customer_address2' => "nullable|max:150",
                'customer_state' => "required",
                'customer_city' => "required",
                'customer_postcode' => 'required', // |digits:5
                'customer_country' => 'required',
                'customer_category_id' => 'required',
                'warehouse_id' => Rule::requiredIf($request->input('customer_category_id') == 10),
                'customer_company_name' => 'required',
                'customer_acc_name' => 'nullable|max:100',
                'customer_acc_mobile_no' => 'nullable|min:8|max:12',
                // 'user_type_id' => 'required'
            ])->setAttributeNames([
                'customer_email' => 'Email',
                // 'password' => 'Password',
                'customer_name' => 'Fullname',
                // 'user_nric' => 'NRIC',
                // 'user_nationality' => 'Nationality',
                'user_unique_code' => 'Unique Code',
                // 'user_gender' => 'Gender',
                // 'user_dob' => 'Date of Birth',
                // 'user_profile_photo' => 'Profile Picture',
                'customer_mobile_no' => 'Mobile No',
                'customer_address' => "Address",
                'customer_address2' => "Address 2",
                'customer_state' => "State",
                'customer_city' => "City",
                'customer_postcode' => 'PostCode', // |digits:5
                'customer_country' => 'Country',
                'customer_category_id' => 'Category',
                'customer_acc_name' => 'Account Name',
                'warehouse_id' => 'Warehouse',
                'customer_acc_mobile_no' => 'Account Mobile Number',
                'company_id' => 'Company Name'
                // 'user_type_id' => 'User Type'
            ]);

            // $mobile_exist = Customer::check_customer_mobile_exist($customer_mobile, $customer_id);
            // if ($mobile_exist) {
            //     $validator->after(function ($validator) {
            //         $validator->getMessageBag()->add('customer_mobile_no', 'The Mobile Number has already been taken.');
            //     });
            // }

            if(auth()->user()->user_type_id == 1){
                $company_id = $request->input('company_id');
            }else{
                  $company_id = $user->company_id;
            }
              
            // $company_name = Company::get_company_name_by_id($company_id);
            $customer_category_id = $request->input('customer_category_id');

            if (!$validator->fails()) {
                // $user_type_id = $request->input('user_type_id');
                // $user_role_id = $request->input('user_role_id');

                // $unique_code = '';
                //
                // if ($request->input('user_unique_code')) {
                //     $unique_code = $request->input('user_unique_code');
                // } else {
                //     $unique_code = User::get_unique_code();
                // }

                $update_detail = [
                    // 'parent_user_id' => $request->input('parent_user_id') ?? 0,
                    // 'user_unique_code' => $unique_code,
                    // 'user_email' => $request->input('user_email'),
                    // 'user_fullname' =>  $request->input('user_fullname'),
                    // 'user_nric' => $request->input('user_nric'),
                    // 'user_nationality' => $request->input('user_nationality'),
                    // 'user_gender' => $request->input('user_gender'),
                    // 'user_dob' => $request->input('user_dob'),
                    // 'user_mobile' => $user_mobile,
                    // 'user_type_id' =>  $user_type_id,
                    // 'user_udate' => now(),
                    'customer_email' => $request->input('customer_email'),
                    'customer_name' =>  $request->input('customer_name'),
                    // 'customer_company_name' => $company_name,
                    'company_id' => $company_id,
                    'customer_mobile_no' => $customer_mobile,
                    'customer_code' => $request->input('customer_code'),
                    'customer_updated' => now(),
                    'customer_address' => $request->input('customer_address') ? $request->input('customer_address') : '',
                    'customer_address2' => $request->input('customer_address2') ? $request->input('customer_address2') : '',
                    'customer_city' => $request->input('customer_city') ? $request->input('customer_city') : '',
                    'customer_state' => $request->input('customer_state') ? $request->input('customer_state') : '',
                    'customer_postcode' => $request->input('customer_postcode') ? $request->input('customer_postcode') : '',
                    'customer_country' => $request->input('customer_country'),
                    'customer_category_id' => $customer_category_id,
                    'customer_company_name' => $request->input('customer_company_name'),
                    'warehouse_id' => $request->input('warehouse_id'),
                    'customer_acc_name' => $request->input('customer_acc_name'),
                    'customer_acc_mobile_no' => $customer_acc_mobile_no,
                    // 'user_address' => $request->input('user_address') ? $request->input('user_address') : '',
                    // 'user_address2' => $request->input('user_address2') ? $request->input('user_address2') : '',
                    // 'user_city' => $request->input('user_city') ? $request->input('user_city') : '',
                    // 'user_state' => $request->input('user_state') ? $request->input('user_state') : '',
                    // 'user_postcode' => $request->input('user_postcode') ? $request->input('user_postcode') : '',
                    // 'user_slug' => $request->input('user_slug'),
                    // 'default_outlet_id' => $request->input('default_outlet_id'),
                    // 'user_designation_id' => $request->input('user_designation_id'),
                ];
                // if ($request->input('password') != 'xxxxxxxx') {
                //     $update_detail['password'] = bcrypt($request->input('password'));
                // }
                // dd($update_detail);

                // $existing_customer = Customer::where('customer_id', $customer->customer_id)->get();
                // if ($existing_customer->isNotEmpty()){
                //     CustomerLog::create([
                //         'customer_id' => $customer->customer_id,
                //         'customer_log_created' => now(),
                //         'customer_log_updated' => now(),
                //         'customer_log_description' => 'Customer Added By ' . $user->user_fullname,
                //         'user_id' => Auth::id()
                //     ]);
                // }

                $customer->update($update_detail);
                CustomerLog::where('customer_id', $customer->customer_id)->update([
                    'customer_id' => $customer->customer_id,
                    'customer_log_created' => now(),
                    'customer_log_updated' => now(),
                    'customer_log_action' => "Edit",
                    'customer_log_description' => 'Customer Added By ' . $user->user_fullname,
                    'user_id' => Auth::id()
                ]);

                // if ($request->file('user_profile_photo')) {
                //     $user->addMediaFromRequest('user_profile_photo')->toMediaCollection('user_profile_photo');
                // }
                // if (optional($user->user_type)->user_type_group == 'Administrator' && $user_role_id > 0 && $user_role_id  != ($user_role ? $user_role->id : '')) {
                // if ($user_role_id > 0 && $user_role_id  != ($user_role ? $user_role->id : '')) {
                //     $role = Role::findById($user_role_id);
                //     if ($role) {
                //         $user->syncRoles($role->name);
                //     }
                // }

                // $existing_pic = CustomerPIC::where('customer_id', $customer->customer_id)->get();
                $customer_pic_id = CustomerPIC::where('customer_id', $customer->customer_id)->first();
                if(is_null($request->input('customer_pic_ic')) == false){
                    CustomerPIC::where('customer_id', $customer->customer_id)->delete();
                    CustomerPICLog::where('customer_pic_id', @$customer_pic_id->customer_pic_id)->delete();
                    foreach($request->input('customer_pic_ic') as $key => $customer_pic_ic){
                        $customer_pic_name = $request->input('customer_pic_name')[$key];
                        // $customer_pic_mobile_no = $request->input('customer_pic_mobile_no')[$key];
                        $customer_pic_mobile_no_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('customer_pic_mobile_no')[$key]);
                        if (substr($customer_pic_mobile_no_initial, 0, 1) == '0') {
                            $customer_pic_mobile_no = '6' . $customer_pic_mobile_no_initial;
                        } elseif (substr($customer_mobile_initial, 0, 1) == '1') {
                            $customer_pic_mobile_no = "60" . $customer_pic_mobile_no_initial;
                        } else {
                            $customer_pic_mobile_no = $customer_pic_mobile_no_initial;
                        }
                        $customer_pic = CustomerPIC::create([
                            'customer_pic_ic' => $customer_pic_ic,
                            'customer_pic_name' => $customer_pic_name,
                            'customer_pic_created' => now(),
                            'customer_pic_updated' => now(),
                            'customer_id' => $customer->customer_id,
                            'customer_pic_mobile_no' => $customer_pic_mobile_no,
                        ]);
                        CustomerPICLog::insert([
                            'customer_pic_id' => $customer_pic->customer_pic_id,
                            'customer_pic_log_created' => now(),
                            'customer_pic_log_updated' => now(),
                            'customer_pic_log_action' => "Add",
                            'customer_pic_log_description' => 'Customer PIC Added By ' . $user->user_fullname,
                            'user_id' => Auth::id()
                        ]);
                    }
                }
                // elseif ($request->filled('customer_pic_ic')) {
                //     $customer_pic = CustomerPIC::where('customer_id', $customer->customer_id)->update([
                //         'customer_pic_ic' => $customer_pic_ic,
                //         'customer_pic_name' => $customer_pic_name,
                //         'customer_pic_created' => now(),
                //         'customer_pic_updated' => now(),
                //         'customer_id' => $customer->customer_id,
                //         'customer_pic_mobile_no' => $customer_pic_mobile_no,
                //     ]);
                //     CustomerPICLog::where('customer_pic_id', @$customer_pic_id->customer_pic_id)->update([
                //         'customer_pic_id' => $post->customer_pic_id,
                //         'customer_pic_log_created' => now(),
                //         'customer_pic_log_updated' => now(),
                //         'customer_pic_log_action' => "Edit",
                //         'customer_pic_log_description' => 'Customer PIC Added By ' . $user->user_fullname,
                //         'user_id' => Auth::id()
                //     ]);
                // }
                else {
                    CustomerPIC::where('customer_id', $customer->customer_id)->delete();
                    CustomerPICLog::where('customer_pic_id', @$customer_pic_id->customer_pic_id)->delete();
                }

                Session::flash('success_msg', 'Successfully updated ' . $request->input('customer_email') . ' customer.');
                return redirect()->route('customer_listing');
            }
            $post = (object) $request->all();
            $edit_data = true;

        }
      }else if(count($company) <= 1){
          Session::flash('fail_msg', 'Company is currently empty, please add a company to proceed.');
        return redirect()->route('company_add');
      }else if(count($customer_category) <= 1){
          Session::flash('fail_msg', 'Customer Category is currently empty, please add a category to proceed.');
        return redirect()->route('customer_category_add');
      }

        return view('customer/form', [
            'submit' => route('customer_edit', $customer_id),
            'title' => 'Edit',
            'post' => $post,
            'edit_code' => $edit_data,
            // 'user_role'=> $user_role,
            // 'user_type_sel'=> $user_type,
            'company_sel' => $company,
            'customer_category_sel' => $customer_category,
            'warehouse_sel' => SettingWarehouse::get_warehouse_sel(),
            // 'setting_outlet_sel' =>SettingOutlet::get_sel(),
            // 'user_role_sel'=> UserType::get_user_role_sel(),
            // 'user_designation_sel' => UserDesignation::get_as_sel(),
            // 'user_gender_sel'=> array('' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'),
            'customer_id' => $customer_id,
        ])->withErrors($validator);
    }

    public function ajax_search_customer_by_mobile_no(Request $request)
    {
        if ($request->isMethod('post')) {
            $customer_mobile_no = $request->input('customer_mobile_no');
            $result = Customer::get_by_mobile_no($customer_mobile_no);
            return response()->json(['data' => $result, 'status' => $result ? true : false]);
        } else {
            $customer_mobile_no = $request->input('term');
            $result = Customer::get_by_mobile_no($customer_mobile_no, true);
            // dd(response()->json(['data' => $result, 'status' => $result ? true : false]));
            return response()->json(['results' => $result, 'status' => $result ? true : false]);
        }
    }

    public function ajax_search_customer_by_customer_id(Request $request)
    {
        if ($request->isMethod('post')) {
            $customer_id = $request->input('customer_id');
            $result = Customer::get_by_customer_id($customer_id);
            // log::info($customer_id);
            return response()->json(['data' => $result, 'status' => $result ? true : false]);
        } else {
            $customer_mobile_no = $request->input('term');
            // log::info($customer_mobile_no);
            $result = Customer::get_by_customer_id($customer_mobile_no, true);
            // dd(response()->json(['data' => $result, 'status' => $result ? true : false]));
            return response()->json(['results' => $result, 'status' => $result ? true : false]);
        }
    }

    public function ajax_search_customer_by_customer_name(Request $request)
    {
        if ($request->isMethod('post')) {
            $customer_name = $request->input('customer_name');
            $result = Customer::get_by_customer_name($customer_name);
            return response()->json(['data' => $result, 'status' => $result ? true : false]);
        } else {
            $customer_name = $request->input('term');
            $result = Customer::get_by_customer_name($customer_name, true);
            return response()->json(['results' => $result, 'status' => $result ? true : false]);
        }
    }

    public function ajax_find_pic_with_customer_id(Request $request)
    {
      $customer_id = $request->input('customer_id');
      $pic = CustomerPIC::get_pic_customer_id($customer_id);
      return $pic;
    }

    public function credit_adjustment(Request $request, $customer_id)
    {
        $validator = null;
        $post = null;
        $customer = Customer::find($customer_id);

        if($customer_id != 0 && !$customer){
            Session::flash('fail_msg', 'Invalid customer credit.');
            return redirect()->route('customer_listing');
        }

        if($customer){
            $post = (object) $post;
            $post->customer_id = $customer->customer_id;
            $post->customer_credit = $customer->customer_credit;
        }

        if ($request->isMethod('post')) {

            $credit_max_rule = $request->input('customer_credit_history_action') == 'deduct' && (optional($customer)->customer_credit - $request->input('credit')) < 0 ? '|max:' . optional($customer)->customer_credit : '';
            $validator = Validator::make($request->all(), [
                'customer_credit_history_action' => "required",
                'credit' => 'required|numeric|min:1' . $credit_max_rule,
                'customer_credit_remark' => 'required',
            ],[
                'customer_credit_history_action.required' => 'Please choose Action between Add or Deduct',
                'credit.min' => 'Amount is required.',
                'credit.max' => 'Amount have reached the limit.'
            ])->setAttributeNames([
                'customer_credit_history_action' => 'Action',
                'credit' => 'Credit Amount',
                'customer_credit_remark' => 'Wallet Remark',
            ]);
            if (!$validator->fails()) {

                $customer_credit_before = $customer->customer_credit;
                $customer_credit_after = $request->input('customer_credit_history_action') == 'add' ? $customer_credit_before + $request->input('credit') : $customer_credit_before - $request->input('credit');

                $customer->update([
                    'customer_credit' => $customer_credit_after
                ]);

                CustomerCreditHistory::create([
                    'customer_credit_history_value_before' => $customer_credit_before > 0 ? $customer_credit_before : 0.00 ,
                    'customer_credit_history_value_after' => $customer_credit_after,
                    'customer_credit_history_remark' => $request->input('customer_credit_remark') ?? '',
                    'customer_credit_history_status' => 'success',
                    'customer_credit_history_action' => $request->input('customer_credit_history_action'),
                    'customer_id' => $customer_id,
                    'customer_credit_history_created' => now(),
                    'customer_credit_history_updated' => now(),
                    'customer_credit_history_description' => "Credit adjustment in Customer Listing",
                ]);

                Session::flash('success_msg', 'Successfully adjust '. $customer->customer_name .' credit.');
                return redirect()->route('customer_listing');
            }
            $post = (object) $request->all();
        }

        $submit = $customer_id ? route('credit_adjustment', $customer_id) : route('credit_adjustment');
        $cancel = $customer_id ? route('customer_listing') : route('customer_listing');
        return view('customer/adjust_credit', [
            'submit' => $submit,
            'cancel' => $cancel,
            'title' => 'Credit Adjustment',
            'customer' => $customer,
            'post' => $post,
        ])->withErrors($validator);
    }

    public function inactivate(Request $request)
    {
        $customer = Customer::find($request->input('customer_id'));

        if(!$customer){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('customer_listing');
        }

        $customer->update(['customer_status' => 2]);

        Session::flash('success_msg', "Successfully inactivated customer.");
        return redirect()->route('customer_listing');
    }

    public function activate(Request $request)
    {
        $customer = Customer::find($request->input('customer_id'));
        // dd($request->input('customer_id'));

        if(!$customer){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('customer_listing');
        }

        $customer->update(['customer_status' => 1]);

        Session::flash('success_msg', "Successfully activated customer.");
        return redirect()->route('customer_listing');
    }

    public function customer_credit_detail($customer_id){
        $customer = Customer::find($customer_id);

        if(!$customer) {
            Session::flash('fail_msg', 'Invalid Customer!');
            return redirect()->route('/');
        }

        $record = CustomerCreditHistory::get_credit_history_by_customer_id($customer_id);

        return view('customer/customer_credit_history_detail', [
            'records' => $record,
            'customer' => $customer,
        ]);
    }
}
