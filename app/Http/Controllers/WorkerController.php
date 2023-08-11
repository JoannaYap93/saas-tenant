<?php

namespace App\Http\Controllers;

use Session;
use App\Model\User;
use App\Model\Worker;
use App\Model\Company;
use App\Model\WorkerRole;
use App\Model\WorkerType;
use App\Model\SettingRace;
use App\Model\WorkerStatus;
use Illuminate\Support\Arr;
use App\Model\SettingReward;
use Illuminate\Http\Request;
use App\Imports\WorkerImport;
use App\Model\CompanyExpense;
use Illuminate\Validation\Rule;
use App\Model\WorkerWalletHistory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function listing(Request $request){

        $search['worker_availability'] = 0;
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    $search['freetext'] = $request->input('freetext');
                    $search['worker_status_id'] = $request->input('worker_status_id');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['user_id'] = $request->input('user_id');
                    $search['setting_reward_id'] = $request->input('setting_reward_id');
                    $search['worker_type_id'] = $request->input('worker_type_id');
                    $search['worker_role_id'] = $request->input('worker_role_id');
                    $search['is_attendance_reward'] = $request->input('is_attendance_reward');
                    $search['setting_race_id'] = $request->input('setting_race_id');
                    $search['worker_availability'] = $request->input('worker_availability');
                    Session::put('worker_search', $search);
                    break;
                case 'reset':
                    Session::forget('worker_search');
                    break;
            }
        }
        $search = Session::has('worker_search') ? Session::get('worker_search') : array();
        $records = Worker::get_records($search);

        return view('worker.listing', [
            'submit' => route('worker_listing', ['tenant' => tenant('id')]),
            'title' => 'Worker Listing',
            'records' => $records,
            'worker_status_sel' => ['' => 'Please Select Worker Status']+ WorkerStatus::get_sel_worker_status(),
            'worker_availability' => Worker::get_availability(),
            'setting_race_sel' => ['' => 'Please Select Worker Race']+ SettingRace::get_sel_setting_race(),
            'company_sel' => Company::get_company_sel(),
            'worker_type_sel' => ['' => 'Please Select Worker Type']+ WorkerType::get_sel_worker_type(),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'search' =>  $search,
        ]);
    }

    public function add(Request $request)
    {

        $validation = null;
        $post = null;

        if ($request->isMethod('post')) {
            $worker_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('worker_mobile'));
            if (substr($worker_mobile_initial, 0, 1) == '0') {
                $worker_mobile = '6' . $worker_mobile_initial;
            } elseif (substr($worker_mobile_initial, 0, 1) == '1') {
                $worker_mobile = "60" . $worker_mobile_initial;
            } else {
                $worker_mobile = $worker_mobile_initial;
            }
            $validation = Validator::make($request->all(), [
                  'user_id' => Rule::requiredIf($request->input('worker_role_id') == 1),
                  'setting_reward_id' => Rule::requiredIf($request->input('is_attendance_reward') == 1),
                  'worker_name' => 'required',
                  'worker_mobile' => 'required|min:8|max:12',
                  'worker_ic' => 'required',
                  'company_id' => 'required',
                  'worker_status_id' => 'required',
                  'worker_role_id' => 'required',
                  'worker_type_id' => 'required',
                  'setting_race_id' => 'required',
                  'worker_photo' => 'nullable|image',
                  'worker_start_date' => 'required',

            ])->setAttributeNames([
                'user_id' => 'Farm manager',
                'setting_reward_id' => 'Reward',
                'worker_name' => 'Worker name',
                'worker_mobile' => 'Worker mobile number',
                'worker_ic' => 'Worker IC',
                'company_id' => 'Company',
                'worker_status_id' => 'Worker status',
                'worker_type_id' => 'Worker type',
                'setting_race_id' => 'Worker Race',
                'worker_photo' => 'Worker Photo',
                'worker_role_id' => 'Worker Role',
                'worker_start_date' => 'Worker Start Date',
            ]);
            $mobile_exist = Worker::check_worker_mobile_exist($worker_mobile, 0);
            if ($mobile_exist) {
                $validation->after(function ($validation) {
                    $validation->getMessageBag()->add('worker_mobile', 'The Mobile Number has already been taken.');
                });
            }

            if (!$validation->fails()) {
                $worker = Worker::create([
                    'user_id' => $request->input('user_id'),
                    'setting_reward_id' => $request->input('setting_reward_id'),
                    'worker_name' => $request->input('worker_name'),
                    'worker_mobile' => $worker_mobile,
                    'worker_ic' => $request->input('worker_ic'),
                    'company_id' => $request->input('company_id'),
                    'worker_status_id' => $request->input('worker_status_id'),
                    'is_attendance_reward' => $request->input('is_attendance_reward') ?? 0,
                    'worker_created' => now(),
                    'worker_updated' => now(),
                    'worker_start_date' => $request->input('worker_start_date'),
                    'is_suspended'=> 0,
                    'worker_type_id' => $request->input('worker_type_id'),
                    'setting_race_id' => $request->input('setting_race_id'),
                    'worker_role_id' => $request->input('worker_role_id'),
                ]);
                if ($request->file('worker_photo')) {
                    $worker->addMediaFromRequest('worker_photo')->toMediaCollection('worker_media');
                }

                Session::flash('success_msg', 'Successfully added '.$request->input('worker_name'));
                return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('worker.form', [
            'submit' => route('worker_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post'=> $post,
            'edit' => false,
            'worker_id' => 0,
            'worker_status_sel' => ['' => 'Please Select Worker Status']+ WorkerStatus::get_sel_worker_status(),
            'setting_race_sel' => ['' => 'Please Select Worker Race']+ SettingRace::get_sel_setting_race(),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'company_sel' => Company::get_company_sel(),
            'worker_type_sel' => ['' => 'Please Select Worker Type']+ WorkerType::get_sel_worker_type(),
        ])->withErrors($validation);
    }

    public function edit(Request $request, $worker_id)
    {
        $post = Worker::find($worker_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $worker_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $request->input('worker_mobile'));
            if (substr($worker_mobile_initial, 0, 1) == '0') {
                $worker_mobile = '6' . $worker_mobile_initial;
            } elseif (substr($worker_mobile_initial, 0, 1) == '1') {
                $worker_mobile = "60" . $worker_mobile_initial;
            } else {
                $worker_mobile = $worker_mobile_initial;
            }
            $validation = Validator::make($request->all(), [
                'user_id' => Rule::requiredIf($request->input('worker_role_id') == 1),
                'setting_reward_id' => Rule::requiredIf($request->input('is_attendance_reward') == 1),
                'worker_name' => 'required',
                'worker_mobile' => 'required|min:8|max:12',
                'worker_ic' => 'required',
                'company_id' => 'required',
                'worker_status_id' => 'required',
                'worker_role_id' => 'required',
                'worker_type_id' => 'required',
                'setting_race_id' => 'required',
                'worker_photo' => 'nullable|image',
                'worker_start_date' => 'required',
          ])->setAttributeNames([
              'user_id' => 'Farm manager',
              'setting_reward_id' => 'Reward',
              'worker_name' => 'Worker name',
              'worker_mobile' => 'Worker mobile number',
              'worker_ic' => 'Worker IC',
              'company_id' => 'Company',
              'worker_status_id' => 'Worker status',
              'worker_type_id' => 'Worker type',
              'setting_race_id' => 'Worker Race',
              'worker_photo' => 'Worker Photo',
              'worker_role_id' => 'Worker Role',
              'worker_start_date' => 'Worker Start Date',
          ]);
            $mobile_exist = Worker::check_worker_mobile_exist($worker_mobile, $worker_id);
            if ($mobile_exist) {
                $validation->after(function ($validation) {
                    $validation->getMessageBag()->add('worker_mobile', 'The Mobile Number has already been taken.');
                });
            }

            $worker_resigned_date = null;
            $is_suspended = 0;
            if($request->input('worker_status_id') != null & $request->input('worker_status_id') == 3){
                $worker_resigned_date = now();
                $is_suspended = 1;
            }
            if($request->input('worker_role_id') == 1){
                $user_id = $request->input('user_id');
            }else{
                $user_id = null;
            }

            if (!$validation->fails()) {
                $post->update([
                    'user_id' => $user_id,
                    'setting_reward_id' => $request->input('setting_reward_id'),
                    'worker_name' => $request->input('worker_name'),
                    'worker_mobile' => $worker_mobile,
                    'worker_ic' => $request->input('worker_ic'),
                    'company_id' => $request->input('company_id'),
                    'worker_status_id' => $request->input('worker_status_id'),
                    'is_attendance_reward' => $request->input('is_attendance_reward') ?? 0,
                    'is_suspended'=> $is_suspended,
                    'worker_updated' => now(),
                    'worker_start_date' => $request->input('worker_start_date'),
                    'worker_resigned_date' => $worker_resigned_date,
                    'worker_type_id' => $request->input('worker_type_id'),
                    'worker_role_id' => $request->input('worker_role_id'),
                    'setting_race_id' => $request->input('setting_race_id'),
                ]);


                if ($request->file('worker_photo')) {
                    $post->addMediaFromRequest('worker_photo')->toMediaCollection('worker_media');
                }

                Session::flash('success_msg', 'Successfully edited '.$request->input('worker_name'));
                return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
            $worker = Worker::find($worker_id);
            $post->media = $worker->hasMedia('worker_media') ?? '';
        }

        return view('worker.form', [
            'submit' => route('worker_edit', ['tenant' => tenant('id'), 'id' => $worker_id]),
            'title' => 'Edit',
            'post'=> $post,
            'edit' => true,
            'worker_id' => $worker_id,
            'worker_status_sel' => ['' => 'Please Select Worker Status']+ WorkerStatus::get_sel_worker_status(),
            'setting_race_sel' => ['' => 'Please Select Worker Race']+ SettingRace::get_sel_setting_race(),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'company_sel' => Company::get_company_sel(),
            'worker_type_sel' => ['' => 'Please Select Worker Type']+ WorkerType::get_sel_worker_type(),
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $worker = Worker::find($request->input('worker_id'));
        // dd($worker);

        if(!$worker){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
        }

       $worker->delete();

        Session::flash('success_msg', "Successfully deleted worker.");
        return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
    }

    public function import(Request $request){
        $post = array();
        $validation = null;

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'user_id' => 'required',
                'worker_file' => 'mimes:xls,xlsx',

            ])->setAttributeNames([
                'company_id' => 'Company',
                'user_id' => 'Farm Manager',
                'worker_file' => 'File',
            ]);
            if(!$validation->fails()){

                if ($request->hasFile('worker_file')) {
                    if ($request->file('worker_file')) {
                        #check to make upload correct file with heading
                        $file = $request->file('worker_file');
                        // $headings = (new HeadingRowImport)->toArray(request()->file('worker_file'));

                        // $file_title = array(
                        //     // "worker_name", "worker_ic", "worker_mobile", "worker_race1_chinese_2_bumiputera_3myanmar_4indonesian", "worker_type1_daily_2_subcon_3monthly", "worker_role1_worker_2_farm_manager_3management_4director",
                        //     // "worker_status1_whole_day_2_half_day_3resigned_4rest", "start_date", "resigned_date", "attendance1_yes_0_no"
                        //     "worker_name", "worker_ic", "worker_mobile", "worker_race1_chinese_2_bumiputera_3myanmar_4indonesian", "worker_status1_whole_day_2_half_day_3resigned_4rest", "worker_type1_daily_2_subcon_3monthly", "worker_role1_worker_2_farm_manager_3management_4director",
                        //     "start_date", "resigned_date", "attendance1_yes_0_no"
                        // );

                        // foreach ($headings as $heading) {
                        //     foreach ($heading as $row) {
                        //         $uploaded_file_title = $row;

                        //         if ($uploaded_file_title == $file_title) {
                        //             $validate = TRUE;
                        //         } else {
                        //             $validate = FALSE;
                        //         }
                        //     }
                        // }

                        // if ($validate) {
                            $sheets = (new WorkerImport)->toCollection($file);
                            foreach ($sheets as $collections) {
                                foreach ($collections as $key => $rows) {
                                    $validation = Validator::make($rows->toArray(), [
                                        'worker_name' => 'required',
                                        'worker_ic' => 'required',
                                        'worker_mobile' => 'required',
                                        'worker_race1_chinese_2_bumiputera_3myanmar_4indonesian' => 'required',
                                        'worker_status1_whole_day_2_half_day_3resigned_4rest' => 'required',
                                        'worker_type1_daily_2_subcon_3monthly' => 'required',
                                        'worker_role1_worker_2_farm_manager_3management_4director' => 'required',
                                        'start_date' => 'required',
                                        'resigned_date' => 'required_if:worker_status1_whole_day_2_half_day_3resigned_4rest,3',
                                        'attendance1_yes_0_no' => 'required',
                                    ],[
                                        'required_if' => 'The :attribute field is required because already resigned.'
                                    ])->setAttributeNames([
                                        'worker_name' => 'Worker rows '. ($key+2) .' Name',
                                        'worker_ic' => 'Worker rows '. ($key+2) .' IC',
                                        'worker_mobile' => 'Worker rows '. ($key+2) .' Mobile No',
                                        'worker_race1_chinese_2_bumiputera_3myanmar_4indonesian' => 'Worker rows '. ($key+2) .'  Race',
                                        'worker_status1_whole_day_2_half_day_3resigned_4rest' => 'Worker rows '. ($key+2) .'  Status',
                                        'worker_type1_daily_2_subcon_3monthly' => 'Worker rows '. ($key+2) .'  Type',
                                        'worker_role1_worker_2_farm_manager_3management_4director' => 'Worker rows '. ($key+2) .'  Role',
                                        'start_date' => 'Worker rows '. ($key+2) .' Start Date',
                                        'resigned_date' => 'Worker rows '. ($key+2) .' Resigned Date',
                                        'attendance1_yes_0_no' => 'Worker rows '. ($key+2) .'  Attendance',
                                    ]);

                                    // if($validation->fails()){
                                    //     $validate = FALSE;
                                    //     break;
                                    // }
                                }
                            }
                        // }
                            // if($validate){
                                // $sheets = (new WorkerImport)->toCollection($file);
                            if(!$validation->fails()){
                                foreach ($sheets as $collections) {
                                    foreach ($collections as $rows) {
                                        $is_suspended = 0;
                                        $worker_resigned_date = null;

                                        $worker_mobile_initial = str_replace(['-', ' ', '+'], ['', '', ''], $rows['worker_mobile']);
                                        if (substr($worker_mobile_initial, 0, 1) == '0') {
                                            $worker_mobile = '6' . $worker_mobile_initial;
                                        } elseif (substr($worker_mobile_initial, 0, 1) == '1') {
                                            $worker_mobile = "60" . $worker_mobile_initial;
                                        } else {
                                            $worker_mobile = $worker_mobile_initial;
                                        }

                                        if($rows['worker_status1_whole_day_2_half_day_3resigned_4rest'] == 3){
                                            $worker_resigned_date = $this->transformDate($rows['resigned_date']);
                                            $is_suspended = 1;
                                        }

                                        Worker::create([
                                            'user_id' => $request->input('user_id'),
                                            'setting_reward_id' => $request->input('setting_reward_id'),
                                            'worker_name' => $rows['worker_name'],
                                            'worker_mobile' => $worker_mobile,
                                            'worker_ic' => $rows['worker_ic'],
                                            'company_id' => $request->input('company_id'),
                                            'worker_status_id' => $rows['worker_status1_whole_day_2_half_day_3resigned_4rest'],
                                            'worker_role_id' => $rows['worker_role1_worker_2_farm_manager_3management_4director'],
                                            'worker_type_id' => $rows['worker_type1_daily_2_subcon_3monthly'],
                                            'is_attendance_reward' => $rows['attendance1_yes_0_no'] ?? 0,
                                            'worker_created' => now(),
                                            'worker_updated' => now(),
                                            'worker_start_date' => $this->transformDate($rows['start_date']),
                                            'is_suspended'=> $is_suspended,
                                            'setting_race_id' => $rows['worker_race1_chinese_2_bumiputera_3myanmar_4indonesian'],
                                            'worker_resigned_date' => $worker_resigned_date
                                        ]);
                                    }
                                }

                                Session::flash("success_msg", "Imported successfully.");
                                return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
                                die();
                            }
                        // }
                        else {
                            Session::flash("fail_msg", "Upload failed. Try again later.");
                            return redirect()->route('worker_import', ['tenant' => tenant('id')]);
                            die();
                        }
                    }
                } else {
                    Session::flash("fail_msg", "Please upload a file.");
                    return redirect()->route('worker_import', ['tenant' => tenant('id')]);
                    die();
                }
            }
            $post = $request->all();
        }

        return view('worker.import', [
            'title' => 'Import Worker',
            'company_sel' => Company::get_company_sel(),
            'post' => $post,
            'submit' => route('worker_import', ['tenant' => tenant('id')]),
        ])->withErrors($validation);
    }

    public function adjustment(Request $request, $worker_id)
    {
        $validator = null;
        $worker = Worker::find($worker_id);
        $user = auth()->user();

        if($worker_id != 0 && !$worker){
            Session::flash('fail_msg', 'Invalid user wallet.');
            return redirect()->route('admin_listing', ['tenant' => tenant('id')]);
        }

        if ($request->isMethod('post')) {
            $wallet_max_rule = $request->input('worker_wallet_history_action') == 'deduct' && (optional($worker)->worker_wallet_amount - $request->input('wallet')) < 0 ? '|max:' . optional($worker)->worker_wallet_amount : '';
            $validator = Validator::make($request->all(), [
                'worker_wallet_history_action' => "required",
                'wallet' => 'required|numeric|min:1' . $wallet_max_rule,
                'worker_wallet_description' => 'required',
            ],[
                'worker_wallet_history_action.required' => 'Please choose Action between Add or Deduct',
                'wallet.min' => 'Amount is required.',
                'wallet.max' => 'Amount have reached the limit.'
            ])->setAttributeNames([
                'worker_wallet_history_action' => 'Action',
                'wallet' => 'Credit Amount',
                'worker_wallet_description' => 'Wallet Remark',
            ]);
            if (!$validator->fails()) {

                $worker_wallet_before = $worker->worker_wallet_amount;
                $worker_wallet_after = $request->input('worker_wallet_history_action') == 'add' ? $worker_wallet_before + $request->input('wallet') : $worker_wallet_before - $request->input('wallet');

                $worker->update([
                    'worker_wallet_amount' => $worker_wallet_after
                ]);

                WorkerWalletHistory::create([
                    'worker_wallet_history_before' => $worker_wallet_before > 0 ? $worker_wallet_before : 0.00 ,
                    'worker_wallet_history_after' => $worker_wallet_after,
                    'worker_wallet_history_value' => $request->input('wallet'),
                    'worker_wallet_history_remark' => $request->input('worker_wallet_description') ?? '',
                    'worker_wallet_history_status' => 'success',
                    'worker_wallet_history_action' => $request->input('worker_wallet_history_action'),
                    'worker_id' => $worker_id,
                    'user_id' => $user->user_id,
                ]);

                Session::flash('success_msg', 'Successfully adjust '. $worker->worker_name .' wallet.');
                return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
            }
            $worker = (object) array_merge(collect($worker)->toArray(), collect($worker->company)->toArray(), $request->all());
        }

        return view('worker.adjust_wallet', [
            'submit' => route('worker_wallet_adjustment', ['tenant' => tenant('id'), 'id' => $worker_id]),
            'title' => 'Adjustment',
            'worker' => $worker,
            'user' => $user,
        ])->withErrors($validator);
    }

    public function worker_wallet_detail($worker_id)
    {
        $worker = Worker::find($worker_id);

        if(!$worker) {
            Session::flash('fail_msg', 'Invalid worker');
            return redirect()->route('worker_listing', ['tenant' => tenant('id')]);
        }

        $record = WorkerWalletHistory::get_credit_history_by_worker_id($worker_id);

        return view('worker.wallet_history_detail', [
            'records' => $record,
            'worker' => $worker,
        ]);
    }

    public function ajax_get_worker_by_farm_manager(Request $request)
    {
        $manager_id = $request->input('manager_id');

        $result = Worker::get_worker_by_farm_manager($manager_id);

        return ['data' => $result];
    }

    public function ajax_get_worker_list(Request $request)
    {
        $company_id = $request->input('company_id');
        $manager_id = $request->input('manager_id');
        $expense_type = $request->input('expense_type');
        $worker_role_id = $request->input('worker_role_id');
        $is_edit = $request->input('is_edit');
        $company_expense_id = $request->input('company_expense_id');
        $result = Worker::get_worker_list($manager_id, $expense_type, $company_id, $worker_role_id, $is_edit, $company_expense_id);

        return ['data' => $result];
    }

    public function ajax_get_user_list_sel_by_company(Request $request)
    {
        $company_id = $request->input('company_id');
        return User::get_user_list_sel_by_company($company_id);
    }
    public function ajax_get_worker_list_sel_by_company(Request $request)
    {
        $company_id = $request->input('company_id');
        return Worker::get_worker_list_sel_by_company($company_id);
    }
    public function ajax_get_worker_list_sel_by_company_without_user_id(Request $request)
    {
        $company_id = $request->input('company_id');
        return Worker::get_farm_manager_list_sel_by_company($company_id);
    }
    public function ajax_get_reward_sel_by_company(Request $request)
    {
        $company_id = $request->input('company_id');
        return SettingReward::get_reward_sel_by_company($company_id);
    }
    public function ajax_get_user_sel_by_company(Request $request)
    {
        $company_id = $request->input('company_id');
        return User::get_user_by_company_id($company_id);
    }

    public function ajax_check_worker_ic(Request $request){
        $data = null;
        $status = false;
        $search = [];

        $search['worker_ic'] = $request->input('worker_ic');
        $search['worker_id'] = $request->input('worker_id');

        if($search) {
            $worker = Worker::get_check_worker_ic($search);

            if($worker){
                $data = $worker->worker_name . ' ('. $worker->company->company_name .')';
            }else{
                $data = 'unique';
            }

            $status = true;
        }

        return response()->json(['data' => $data, 'status' => $status]);
    }

    public function ajax_get_farm_manager_by_company_id(Request $request)
    {
        $company_id = $request->input('company_id');
        $result = Worker::get_farm_manager_by_company_id($company_id);
        return response()->json(['data' => $result]);
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function ajax_get_user_id_by_worker_id(Request $request) {
        $worker = Worker::find($request->id);
        return $worker;
    }
}
