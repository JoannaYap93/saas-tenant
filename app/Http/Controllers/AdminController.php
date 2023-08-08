<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\EventDate;
use App\Model\Transaction;
use App\Model\User;
use App\Model\UserPlatform;
use App\Model\UserType;
use App\Model\UserCompany;
use App\Model\Company;
use Carbon\Carbon;
use App\Model\Worker;
use Illuminate\Http\Request;
use App\Model\CompanyLand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Session;
use Illuminate\Support\Str;
use DateInterval;
use DatePeriod;
use DateTime;
use App\Model\Setting;
use App\Model\SettingOutlet;
use App\Model\UserDesignation;
use App\Model\UserLand;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class AdminController extends Controller
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
        $user_type = UserType::get_user_type_sel();

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['user_search' => [
                        "freetext" =>  $request->input('freetext'),
                        "user_status" =>  $request->input('user_status'),
                        "user_type_id" =>  $request->input('user_type_id'),
                        "user_gender" =>  $request->input('user_gender'),
                        "user_role_id" => $request->input('user_role_id'),
                        'company_id' => $request->input('company_id')
                    ]]);
                    break;
                case 'reset':
                    session()->forget('user_search');
                    break;
            }
        }
        $search = session('user_search') ? session('user_search') : $search;
        $users = User::get_record($search, [1]);
        $perpage = 15;

        return view('admin.listing', [
            'submit' => route('admin_listing'),
            'title' => 'Add',
            'users' =>  $users,
            'search' =>  $search,
            'user_type_sel' => $user_type,
            'user_role_sel' => UserType::get_user_role_sel(auth()->user()->user_type_id),
            'user_status_sel' => ['' => 'Please select status', 'active' => 'Active', 'suspended' => 'Suspend', 'pending' => 'Pending'],
            'user_gender_sel' => ['' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'],
            'company' => Company::get_company_sel()
        ]);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $validator = null;
        $post = null;
        $user_type = UserType::get_user_type_radio();
        $company = Company::get_company_sel();
        $company_cb = Company::get_company_check_box();

        if ($request->isMethod('post')) {
            $user_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('user_mobile'));
            if (substr($user_mobile_initial, 0, 1) == '0') {
                $user_mobile = '6' . $user_mobile_initial;
            } elseif (substr($user_mobile_initial, 0, 1) == '1') {
                $user_mobile = "60" . $user_mobile_initial;
            } else {
                $user_mobile = $user_mobile_initial;
            }

            $request->merge([
                'user_nric' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', strval($request->input('user_nric')))),
            ]);
            $validator = Validator::make($request->all(), [
                'worker_id' =>Rule::requiredIf($request->input('user_role_id') == 13),
                'user_email' => 'required|max:100|unique:tbl_user,user_email',
                'password' => 'min:8|max:100|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required|min:8|same:password',
                'user_fullname' => 'required|regex:/^[a-zA-Z0-9. -_]+$/u|max:100',
                // 'user_nric' => 'required|unique:tbl_user,user_nric', // |digits:12
                // 'user_unique_code' => 'nullable|unique:tbl_user,user_unique_code',
                'user_nationality' => 'required',
                'user_gender' => 'required',
                'user_profile_photo' => 'nullable',
                'user_mobile' => 'required|min:8|max:12', // phone_number|unique:tbl_user,user_mobile
                'user_address' => "nullable|max:150",
                'user_address2' => "nullable|max:150",
                'user_state' => "nullable",
                'user_city' => "nullable",
                'user_postcode' => 'nullable', // |digits:5
                'user_unique_code' => 'required',
                'user_type' => 'required',
                'company_id'=>Rule::requiredIf($request->input('user_type') != 1 || $user->company_id != 0),
                'company_cb_id' => Rule::requiredIf($request->input('user_type') == 1)
            ])->setAttributeNames([
                'user_email' => 'Email',
                'worker_id' => 'Worker ID',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'user_fullname' => 'Fullname',
                // 'user_nric' => 'NRIC',
                // 'user_unique_code' => 'Unique Code',
                'user_nationality' => 'Nationality',
                'user_gender' => 'Gender',
                'user_dob' => 'Date of Birth',
                'user_profile_photo' => 'Profile Picture',
                'user_mobile' => 'Mobile Number',
                'user_address' => "Address",
                'user_address2' => "Address",
                'user_state' => "State",
                'user_city' => "City",
                'user_postcode' => 'Postcode',
                'user_unique_code' => 'Unique Code',
                'user_type' => 'Admin type',
                'company_id'=>'Company',
                'company_cb_id'=>'Company(s)'
            ]);

            $mobile_exist = User::check_user_mobile_exist($user_mobile, 0);
            if ($mobile_exist) {
                $validator->after(function ($validator) {
                    $validator->getMessageBag()->add('user_mobile', 'The Mobile Number has already been taken.');
                });
            }

            if (!$validator->fails()) {

                $user_type_id = $request->input('user_type_id') ?? auth()->user()->user_type_id;
                $user_role_id = $request->input('user_role_id');

                $unique_code = '';
                if ($user->company_id != 0) {
                    $company_id=$user->company_id;
                } else {
                    $company_id=$request->input('company_id');
                }
                $users = User::create([
                    'user_email' => $request->input('user_email'),
                    // 'worker_id' => $request->input('worker_id'),
                    'password' => bcrypt($request->input('password')),
                    'user_fullname' =>  $request->input('user_fullname'),
                    'user_nationality' => $request->input('user_nationality'),
                    'user_gender' => $request->input('user_gender'),
                    'user_dob' => $request->input('user_dob'),
                    'user_mobile' => $user_mobile,
                    'user_type_id' =>  $request->input('user_type'),
                    'user_logindate' => now(),
                    'user_cdate' => now(),
                    'user_udate' => now(),
                    'user_join_date' => now(),
                    'user_ip' => '',
                    'user_profile_photo' => '',
                    'user_unique_code' => strtoupper($request->input('user_unique_code')),
                    // 'user_platform_id' => '1',
                    'user_address' => $request->input('user_address') ? $request->input('user_address') : '',
                    'user_address2' => $request->input('user_address2') ? $request->input('user_address2') : '',
                    'user_city' => $request->input('user_city') ? $request->input('user_city') : '',
                    'user_state' => $request->input('user_state') ? $request->input('user_state') : '',
                    'user_postcode' => $request->input('user_postcode') ? $request->input('user_postcode') : '',
                    // 'user_credit' => 0,
                    'user_slug' => $request->input('user_slug'),
                    'company_id' => $request->input('user_type') == 1 ? 0 : $company_id,
                    // 'default_outlet_id' => $request->input('default_outlet_id'),
                    // 'user_designation_id' => $request->input('user_designation_id'),
                ]);

                $post_worker = Worker::where('worker_id', $request->input('worker_id'));
                $post_worker->update([
                    'user_id' => $users->user_id,
                ]);

                if($request->input('user_type') == 1){
                  $selected_company = $request->input('company_cb_id');
                  foreach($selected_company as $key => $selected_company_id){
                    $user_company = UserCompany::create([
                      'user_id' => $users->user_id,
                      'company_id' => $selected_company_id,
                    ]);
                  }
                }

                if ($request->file('user_profile_photo')) {
                    $user->addMediaFromRequest('user_profile_photo')->toMediaCollection('user_profile_photo');
                }

                if (is_null($request->input('company_land_id')) == false) {
                    foreach ($request->input('company_land_id') as $company_land_id) {
                        UserLand::create([
                            'company_land_id' => $company_land_id,
                            'user_id' => $users->user_id,
                        ]);
                    }
                }

                if ($user_role_id > 0) {
                    $role = Role::findById($user_role_id);
                    if ($role) {
                        $users->syncRoles($role->name);
                    }
                }

                Session::flash('success_msg', 'Successfully added ' . $request->input('user_fullname'));
                return redirect()->route('admin_listing');
            }
            $post = (object) $request->all();
        }
        return view('admin.form', [
            'submit' => route('admin_add'),
            'title' => 'Add',
            'edit' => false,
            'post' => $post,
            'user_type_radio' => $user_type,
            'user_role_sel' => UserType::get_user_role_sel(),
            'user_gender_sel' => array('' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'),
            'company_sel' => $company,
            'company_cb' => $company_cb,
            'lands'=>CompanyLand::get_by_company_id($user->company_id),
            'user' => $user,
        ])->withErrors($validator);
    }

    public function edit(Request $request, $user_id)
    {
        // dd( $request->input('worker_id_existing'));
        $user = Auth::user();
        $validator = null;
        $worker = Worker::where('user_id', $user_id)->where('worker_role_id',2)->get();
        // dd($worker);
        // $worker_name = Worker::where('user_id', $user_id)->where('worker_role_id',2)->first();
        $post = User::query()->where('user_id', $user_id)->with('user_land')->with('user_company');
        $user_type = UserType::get_user_type_radio();
        $company = Company::get_company_sel();
        $company_cb = Company::get_company_check_box();
        $admin = User::find($user_id);
        if ($user->company_id != 0) {
            $post = $post->where('company_id', $user->company_id);
        }

        if(!$worker->isEmpty()){
            foreach ($worker as $data1){
            $worker_data = $data1->worker_id;
        }
        }else{
            $worker_data = null;
        }


        $post = $post->first();

        if ($post == null) {
            Session::flash('fail_msg', 'Invalid Admin, Please Try Another.');
            return redirect()->route('admin_listing');
        }

        $admin = User::find($user_id);

        $user_role = optional($post->roles)->first();

        if ($request->isMethod('post')) {
            $ad = User::find($user_id);
            $user_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('user_mobile'));
            if (substr($user_mobile_initial, 0, 1) == '0') {
                $user_mobile = '6' . $user_mobile_initial;
            } elseif (substr($user_mobile_initial, 0, 1) == '1') {
                $user_mobile = "60" . $user_mobile_initial;
            } else {
                $user_mobile = $user_mobile_initial;
            }

            $request->merge([
                'user_nric' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', strval($request->input('user_nric')))),
            ]);
            $validator = Validator::make($request->all(), [
                'worker_id' =>Rule::requiredIf($request->input('user_role_id') == 13),
                'user_email' => "required|unique:tbl_user,user_email,{$user_id},user_id",
                'password' => 'nullable|min:8|max:100|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'nullable|min:8|same:password',
                'user_fullname' => 'required|regex:/^[a-zA-Z0-9. -_]+$/u|max:100',
                // 'user_nric' => 'required|unique:tbl_user,user_nric', // |digits:12
                // 'user_unique_code' => 'nullable|unique:tbl_user,user_unique_code',
                'user_nationality' => 'required',
                'user_gender' => 'required',
                'user_profile_photo' => 'nullable',
                'user_mobile' => 'required|min:8|max:12', // phone_number|unique:tbl_user,user_mobile
                'user_address' => "nullable|max:150",
                'user_address2' => "nullable|max:150",
                'user_state' => "nullable",
                'user_city' => "nullable",
                'user_postcode' => 'nullable', // |digits:5
                // 'user_unique_code' => 'required',
                'user_type' => 'required',
                'company_id'=>Rule::requiredIf($request->input('user_type') != 1 || $user->company_id != 0),
                'company_cb_id' => Rule::requiredIf($request->input('user_type') == 1)
            ])->setAttributeNames([
                'user_email' => 'Email',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'user_fullname' => 'Fullname',
                'worker_id' => 'Worker ID',
                // 'user_nric' => 'NRIC',
                // 'user_unique_code' => 'Unique Code',
                'user_nationality' => 'Nationality',
                'user_gender' => 'Gender',
                'user_dob' => 'Date of Birth',
                'user_profile_photo' => 'Profile Picture',
                'user_mobile' => 'Mobile Number',
                'user_address' => "Address",
                'user_address2' => "Address",
                'user_state' => "State",
                'user_city' => "City",
                'user_postcode' => 'Postcode',
                // 'user_unique_code' => 'Unique Code',
                'user_type' => 'Admin type',
                'company_id'=>'Company',
                'company_cb_id'=>'Company(s)'
            ]);
            // if ($user->company_id != 0) {
            //     $validator = Validator::make($request->all() + ['user_type_id' => Auth::user()->user_type_id], [
            //         'user_email' => "required|unique:tbl_user,user_email,{$user_id},user_id",
            //         'password' => 'nullable|min:8|max:100|required_with:confirm_password|same:confirm_password',
            //         'confirm_password' => 'nullable|min:8|same:password',
            //         'user_fullname' => 'required|regex:/^[a-zA-Z0-9. -_]+$/u|max:100',
            //         // 'user_nric' => "required||digits:12|unique:tbl_user,user_nric,{$user_id},user_id",
            //         'user_nationality' => 'required',
            //         'user_unique_code' => "nullable|unique:tbl_user,user_unique_code,{$user_id},user_id",
            //         'user_gender' => 'required',
            //         'user_profile_photo' => 'nullable',
            //         'user_mobile' => "required", // |unique:tbl_user,user_mobile,{$user_id},user_id
            //         'user_address' => "nullable|max:150",
            //         'user_address2' => "nullable|max:150",
            //         'user_state' => "nullable",
            //         'user_city' => "nullable",
            //         'user_postcode' => 'nullable', // |digits:5
            //     ])->setAttributeNames([
            //         'user_email' => 'Email',
            //         'password' => 'Password',
            //         'confirm_password' => 'Confirm Password',
            //         'user_fullname' => 'Fullname',
            //         // 'user_nric' => 'NRIC',
            //         'user_nationality' => 'Nationality',
            //         'user_unique_code' => 'Unique Code',
            //         'user_gender' => 'Gender',
            //         'user_dob' => 'Date of Birth',
            //         'user_profile_photo' => 'Profile Picture',
            //         'user_mobile' => 'Mobile No',
            //         'user_address' => "Address",
            //         'user_address2' => "Address",
            //         'user_state' => "State",
            //         'user_city' => "City",
            //         'user_postcode' => 'Postcode',
            //     ]);
            // } else {
            //     $validator = Validator::make($request->all() + ['user_type_id' => Auth::user()->user_type_id], [
            //         'user_email' => "required|unique:tbl_user,user_email,{$user_id},user_id",
            //         'password' => 'nullable|min:8|max:100|required_with:confirm_password|same:confirm_password',
            //         'confirm_password' => 'nullable|min:8|same:password',
            //         'user_fullname' => 'required|regex:/^[a-zA-Z0-9. -_]+$/u|max:100',
            //         // 'user_nric' => "required||digits:12|unique:tbl_user,user_nric,{$user_id},user_id",
            //         'user_nationality' => 'required',
            //         'user_unique_code' => "nullable|unique:tbl_user,user_unique_code,{$user_id},user_id",
            //         'user_gender' => 'required',
            //         'user_profile_photo' => 'nullable',
            //         'user_mobile' => "required", // |unique:tbl_user,user_mobile,{$user_id},user_id
            //         'user_address' => "nullable|max:150",
            //         'user_address2' => "nullable|max:150",
            //         'user_state' => "nullable",
            //         'user_city' => "nullable",
            //         'user_postcode' => 'nullable', // |digits:5
            //         'company_id' => 'required'
            //     ])->setAttributeNames([
            //         'user_email' => 'Email',
            //         'password' => 'Password',
            //         'confirm_password' => 'Confirm Password',
            //         'user_fullname' => 'Fullname',
            //         // 'user_nric' => 'NRIC',
            //         'user_nationality' => 'Nationality',
            //         'user_unique_code' => 'Unique Code',
            //         'user_gender' => 'Gender',
            //         'user_dob' => 'Date of Birth',
            //         'user_profile_photo' => 'Profile Picture',
            //         'user_mobile' => 'Mobile No',
            //         'user_address' => "Address",
            //         'user_address2' => "Address",
            //         'user_state' => "State",
            //         'user_city' => "City",
            //         'user_postcode' => 'Postcode',
            //         'company_id' => 'Company'
            //     ]);
            // }

            $mobile_exist = User::check_user_mobile_exist($user_mobile, $user_id);
            if ($mobile_exist) {
                $validator->after(function ($validator) {
                    $validator->getMessageBag()->add('user_mobile', 'The Mobile Number has already been taken.');
                });
            }

            if (!$validator->fails()) {
              if($request->input('user_type') == 1){
                $existing_cb_id = UserCompany::where('user_id', $user_id)->pluck('company_id')->toArray();
                $selected_cb_id = $request->input('company_cb_id') ?? array();
                $remove_cb = array_diff($existing_cb_id, $selected_cb_id);
                $add_cb = array_diff($selected_cb_id, $existing_cb_id);

                if($add_cb != null && $remove_cb == null){
                  foreach ($add_cb as $index => $id) {
                    UserCompany::create([
                      'user_id' => $user_id,
                      'company_id' => $id
                    ]);
                  }
                }elseif($add_cb == null && $remove_cb != null){
                  foreach ($remove_cb as $index => $id){
                    UserCompany::query()->where('company_id', $id)->where('user_id', $user_id)->delete();
                  }
                }elseif($add_cb != null && $remove_cb != null){
                  foreach($remove_cb as $index => $id){
                    UserCompany::query()->where('company_id', $id)->where('user_id', $user_id)->delete();
                  }
                  foreach($add_cb as $index => $id){
                    UserCompany::create([
                      'user_id' => $user_id,
                      'company_id' => $id
                    ]);
                  }
                }
              }

                $user_role_id = $request->input('user_role_id');
                $update_detail = [
                  'user_email' => $request->input('user_email'),
                  // 'password' => bcrypt($request->input('password')),
                  'user_fullname' =>  $request->input('user_fullname'),
                  'user_nationality' => $request->input('user_nationality'),
                  'user_gender' => $request->input('user_gender'),
                  'user_dob' => $request->input('user_dob'),
                  'user_mobile' => $user_mobile,
                  'user_type_id' =>  $request->input('user_type'),
                  'user_logindate' => now(),
                  'user_cdate' => now(),
                  'user_udate' => now(),
                  'user_join_date' => now(),
                  'user_ip' => '',
                  'user_profile_photo' => '',
                  // 'user_unique_code' => strtoupper($request->input('user_unique_code')),
                  // 'user_platform_id' => '1',
                  'user_address' => $request->input('user_address') ? $request->input('user_address') : '',
                  'user_address2' => $request->input('user_address2') ? $request->input('user_address2') : '',
                  'user_city' => $request->input('user_city') ? $request->input('user_city') : '',
                  'user_state' => $request->input('user_state') ? $request->input('user_state') : '',
                  'user_postcode' => $request->input('user_postcode') ? $request->input('user_postcode') : '',
                  // 'user_credit' => 0,
                  'user_slug' => $request->input('user_slug'),
                  'company_id' => $request->input('user_type') == 1 ? 0 : $request->input('company_id'),
                ];

                // dd($user_id);

                if($request->input('user_role_id') == 13){
                    if($request->input('worker_id') != $request->input('worker_id_existing')){
                        $post_worker_existing = Worker::where('worker_id', $request->input('worker_id_existing'));
                        $post_worker_existing->update([
                        'user_id' => null,
                        ]);
                    }
                        $post_worker = Worker::where('worker_id', $request->input('worker_id'));
                        $post_worker->update([
                        'user_id' => $user_id,
                        ]);
                    }
                //  else{
                //     if(!empty($worker_data)){
                //         $post_worker = Worker::where('worker_id', $worker_data);
                //         $post_worker->update([
                //         'user_id' => 0,
                //     ]);
                //     }
                //  }

                // $unique_code = '';
                //
                // if ($request->input('user_unique_code')) {
                //     $unique_code = $request->input('user_unique_code');
                // } else {
                //     $unique_code = User::get_unique_code();
                // }
                // if ($user->company_id != 0) {
                //     $update_detail = [
                //         // 'parent_user_id' => $request->input('parent_user_id') ?? 0,
                //         // 'user_unique_code' => $unique_code,
                //         'user_email' => $request->input('user_email'),
                //         'user_fullname' =>  $request->input('user_fullname'),
                //         // 'user_nric' => $request->input('user_nric'),
                //         'user_nationality' => $request->input('user_nationality'),
                //         'user_gender' => $request->input('user_gender'),
                //         'user_dob' => $request->input('user_dob'),
                //         'user_mobile' => $user_mobile,
                //         'user_type_id' =>  $user_type_id,
                //         'user_udate' => now(),
                //         'user_address' => $request->input('user_address') ? $request->input('user_address') : '',
                //         'user_address2' => $request->input('user_address2') ? $request->input('user_address2') : '',
                //         'user_city' => $request->input('user_city') ? $request->input('user_city') : '',
                //         'user_state' => $request->input('user_state') ? $request->input('user_state') : '',
                //         'user_postcode' => $request->input('user_postcode') ? $request->input('user_postcode') : '',
                //         // 'user_slug' => $request->input('user_slug'),
                //         // 'default_outlet_id' => $request->input('default_outlet_id'),
                //         // 'user_designation_id' => $request->input('user_designation_id'),
                //     ];
                // } else {
                //     $update_detail = [
                //         // 'parent_user_id' => $request->input('parent_user_id') ?? 0,
                //         // 'user_unique_code' => $unique_code,
                //         'user_email' => $request->input('user_email'),
                //         'user_fullname' =>  $request->input('user_fullname'),
                //         // 'user_nric' => $request->input('user_nric'),
                //         'user_nationality' => $request->input('user_nationality'),
                //         'user_gender' => $request->input('user_gender'),
                //         'user_dob' => $request->input('user_dob'),
                //         'user_mobile' => $user_mobile,
                //         'user_udate' => now(),
                //         'user_address' => $request->input('user_address') ? $request->input('user_address') : '',
                //         'user_address2' => $request->input('user_address2') ? $request->input('user_address2') : '',
                //         'user_city' => $request->input('user_city') ? $request->input('user_city') : '',
                //         'user_state' => $request->input('user_state') ? $request->input('user_state') : '',
                //         'user_postcode' => $request->input('user_postcode') ? $request->input('user_postcode') : '',
                //         'company_id' => $request->input('company_id'),
                //         // 'user_slug' => $request->input('user_slug'),
                //         // 'default_outlet_id' => $request->input('default_outlet_id'),
                //         // 'user_designation_id' => $request->input('user_designation_id'),
                //     ];
                // }

                if ($request->input('password') != null && $post->password != bcrypt($request->input('password'))) {
                    $update_detail['password'] = bcrypt($request->input('password'));
                }

                $post->update($update_detail);

                if ($request->file('user_profile_photo')) {
                    $post->addMediaFromRequest('user_profile_photo')->toMediaCollection('user_profile_photo');
                }

                if (is_null($request->input('company_land_id')) == false) {
                    UserLand::where('user_id', $user_id)->delete();
                    foreach ($request->input('company_land_id') as $company_land_id) {
                        UserLand::create([
                            'company_land_id' => $company_land_id,
                            'user_id' => $user_id,
                        ]);
                    }
                } else {
                    UserLand::where('user_id', $user_id)->delete();
                }

                if ($user_role_id > 0 && $user_role_id  != ($user_role ? $user_role->id : '')) {
                    $role = Role::findById($user_role_id);
                    if ($role) {
                        $post->syncRoles($role->name);
                    }
                }

                Session::flash('success_msg', 'Successfully updated ' . $request->input('user_email') . ' admin.');
                return redirect()->route('admin_listing');
            }
            $post = (object) $request->all();
            $post->media = $ad->hasMedia('user_profile_photo') ?? '';
        }

        return view('admin.form', [
            'submit' => route('admin_edit', $user_id),
            'title' => 'Edit',
            'post' => $post,
            'edit' => true,
            'worker' =>  $worker_data,
            'user_role' => $user_role,
            'user_type_radio' => $user_type,
            'company_cb' => $company_cb,
            'lands'=>CompanyLand::get_by_company_id($user->company_id),
            'user_role_sel' => UserType::get_user_role_sel(),
            'company_sel' => $company,
            'user_gender_sel' => array('' => 'Please select gender', 'male' => 'Male', 'female' => 'Female'),
            'user' => $user,
            'admin' => $admin
        ])->withErrors($validator);
    }

    public function assign_permission(Request $request, $user_id)
    {
        $validator = null;
        $post = $user =  User::find($user_id);
        if (!$user) {
            Session::flash('fail_msg', 'Invalid User, Please try again later.');
            return redirect('/user/listing');
        }
        $user_role = optional($user->roles)->first();
        $role_permissions = $user_role ? Role::findById($user_role->id)->permissions()->pluck('name')->toArray() : [];
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
            ])->setAttributeNames([
                'role_id' => 'User Role',
            ]);

            if (!$validator->fails()) {
                $submit_type = $request->input('submit');
                $role_id = $request->input('role_id');
                switch ($submit_type) {
                    case 'update':
                        $assign_permission = array();
                        if ($request->input('permissions') && $role_permissions) {
                            foreach ($request->input('permissions') as $permission) {
                                if (!in_array($permission, $role_permissions)) {
                                    $assign_permission[] = $permission;
                                }
                            }
                        }
                        $user->syncPermissions($assign_permission);
                        Session::flash('success_msg', 'Successfully updated ' . $user->user_email . ' permission.');
                        return redirect()->route('admin_listing');
                        break;
                    case 'reset':
                        $role = Role::findById($role_id);
                        $user->syncRoles($role->name);
                        Session::flash('success_msg', 'Successfully reset ' . $user->user_email . ' permission.');
                        return redirect()->route('assign_permission', $user_id);
                        break;
                }
            }
            $post = (object) $request->all();
        }

        $roles = Role::query()->where('company_id', auth()->user()->company_id)->orWhere('company_id','0')->get();

        return view('admin/assign_permission', [
            'submit' => route('assign_permission', $user_id),
            'title' => 'Assign Permission',
            'user' => $user,
            'user_role' => $user_role,
            'roles' => $roles,
            'permissions' => Permission::orderBy('group_name', 'asc')->get(),
            'user_permission' => $user->getAllPermissions() ?  $user->getAllPermissions()->pluck('name')->toArray() : [],
            'role_permissions' => $role_permissions,
        ])->withErrors($validator);
    }

    public function update_user_to_admin(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        if (!$user) {
            Session::flash('fail_msg', 'Invalid User, Please try again later.');
            return redirect('/admin/listing');
        }
        $user->update(['user_type_id' => 1]);

        Session::flash('success_msg', 'Successfully upgraded ' . $user->user_fullname . ' to admin.');
        return redirect()->route('admin_edit', $user_id);
    }

    public function status(Request $request)
    {
        $action = $request->input('action');
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($user){
          if($action == 'active'){
            $user->update([
              'user_status' => $action,
            ]);
            Session::flash('success_msg', "Successfully {$action} {$user->user_email} admin.");
            return redirect()->route('admin_listing');
          }elseif($action == 'suspended'){
            $user->update([
              'user_status' => $action,
            ]);
            Session::flash('success_msg', "Successfully {$action} {$user->user_email} admin.");
            return redirect()->route('admin_listing');
          }elseif($action == 'delete'){
            $user->update([
              'user_status' => 'suspended',
              'is_deleted' => 1
            ]);
            Session::flash('success_msg', "Successfully {$action} {$user->user_email} admin.");
            return redirect()->route('admin_listing');
          }else{
            Session::flash('failed_msg', "Something went wrong...");
            return redirect()->route('admin_listing');
          }
        }else{
          Session::flash('failed_msg', "User not found");
          return redirect()->route('admin_listing');
        }
        // $data['user_status'] = $action;
        // $data['is_deleted'] = $action == 'activate' ? 0 : 1;
        // Session::flash('success_msg', "Successfully {$action} {$user->user_email} admin.");
        // return redirect()->route('admin_listing');
    }

    public function ajax_get_farm_manager_list(Request $request)
    {
      $company_id = $request->input('company_id');
      // $expense_type = $request->input('expense_type');
      // $company_id = 4;

      $farm_manager = User::get_farm_manager_list($company_id);

      return $farm_manager;
    }

    public function ajax_check_user_mobile(Request $request)
    {
        $data = [];
        $status = false;

        $user_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('user_mobile'));
        if (substr($user_mobile_initial, 0, 1) == '0') {
            $user_mobile = '6' . $user_mobile_initial;
        } elseif (substr($user_mobile_initial, 0, 1) == '1') {
            $user_mobile = "60" . $user_mobile_initial;
        } else {
            $user_mobile = $user_mobile_initial;
        }

        $user = User::where([
            'user_mobile' => $user_mobile,
            'is_deleted' => 0
        ])->first();

        if ($user) {
            $data['user_id'] = $user->user_id;
            $data['user_fullname'] = $user->user_fullname;
            $data['user_email'] = $user->user_email;
            $data['user_type'] = $user->user_type->user_type_name;
            $data['user_cdate'] = date('d-m-Y', strtotime($user->user_cdate));
            $status = true;
        }

        return response()->json(['data' => $data, 'status' => $status]);
    }

    public function ajax_check_user_slug(Request $request)
    {
        $data = null;
        $status = false;
        $search = [];

        $search['user_slug'] = $request->input('user_slug');
        $search['user_id'] = $request->input('user_id');

        if ($search) {
            $user = User::get_check_user_slug($search);
            if ($user) {
                $data = 'not_unique';
            } else {
                $data = 'unique';
            }

            $status = true;
        }

        return response()->json(['data' => $data, 'status' => $status]);
    }

    public function ajax_check_user_unique_code(Request $request)
    {
        $data = [];
        $status = false;
        $user_unique_code = strtoupper($request->input('user_unique_code'));

        if ($user_unique_code == null) {
            return false;
        }

        $checked_user_unique_code = User::where('user_unique_code', '=', $user_unique_code)->first();
        // dd($checked_user_unique_code);
        if ($checked_user_unique_code != null) {
            $status = true;
        }
        // $user_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('user_mobile'));
        // if (substr($user_mobile_initial, 0, 1) == '0') {
        //     $user_mobile = '6' . $user_mobile_initial;
        // } elseif (substr($user_mobile_initial, 0, 1) == '1') {
        //     $user_mobile = "60" . $user_mobile_initial;
        // } else{
        //     $user_mobile = $user_mobile_initial;
        // }
        //
        // $user = User::where([
        //     'user_mobile' => $user_mobile,
        //     'is_deleted' => 0
        // ])->first();
        //
        // if($user) {
        //     $data['user_id'] = $user->user_id;
        //     $data['user_fullname'] = $user->user_fullname;
        //     $data['user_email'] = $user->user_email;
        //     $data['user_type'] = $user->user_type->user_type_name;
        //     $data['user_cdate'] = date('d-m-Y', strtotime($user->user_cdate));
        //     $status = true;
        // }

        return response()->json(['status' => $status]);
    }

    public function ajax_check_email(Request $request)
    {
        $email = $request->input('user_email');
        return User::get_user_email($email);
    }
}
