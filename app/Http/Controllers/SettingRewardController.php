<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingReward;
use App\Model\SettingRewardCategory;
use App\Model\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingRewardController extends Controller
{
    public function construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('setting_reward_search');
                    $search['freetext'] = $request->input('freetext');
                    $search['setting_reward_category_id'] = $request->input('setting_reward_category_id');
                    $search['setting_reward_status'] = $request->input('setting_reward_status');

                    Session::put('setting_reward_search', $search);
                    break;
                case 'reset':
                    Session::forget('setting_reward_search');
                    break;
                }
        }

        $search = session('setting_reward_search') ?? array();

        return view('setting_reward.listing', [
            'page_title' => 'Setting Reward',
            'submit' => route('setting_reward', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingReward::get_records($search, $perpage),
            'setting_reward_category_sel' => ['' => 'Please select reward category'] +  SettingRewardCategory::get_sel_setting_reward(),
            'setting_reward_status_sel' => ['' => 'Please select status', 'active' => 'Active', 'pending' => 'Pending'],
        ]);
    }


    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_reward_name' => 'required',
                'setting_reward_description' => 'nullable|max:250',
                'setting_reward_category_id'=>'required',
                'setting_reward_status' => 'required',
                'add_setting_reward_data_to_json' => 'required',
            ])->setAttributeNames([
                'setting_reward_name' => 'Name',
                'setting_reward_description' => 'Description',
                'setting_reward_category_id'=>'Category',
                'setting_reward_status' => 'Status',
                'add_setting_reward_data_to_json' => 'Details',
            ]);
            if (!$validator->fails()) {
                $reward_json = $request->input('add_setting_reward_data_to_json');
                $json = str_replace("'", "\'", json_encode($reward_json));
                $reward_json_list = json_decode($json, true);
                foreach ($reward_json_list as $key => $value) {
                    $reward_json[$key]['full_attendance'] = isset($value['full_attendance'][0]) ? $value['full_attendance'][0] : "0" ;
                }
                $result = str_replace("'", "\'", json_encode($reward_json));


                SettingReward::create([
                    'setting_reward_name' => $request->input('setting_reward_name') ?? '',
                    'setting_reward_description' => $request->input('setting_reward_description') ?? '',
                    'setting_reward_category_id'=> $request->input('setting_reward_category_id') ?? '',
                    'setting_reward_json' => $result,
                    'setting_reward_status' => $request->input('setting_reward_status') ?? '',
                    'company_id' => auth()->user()->company_id ?? 0,
                    'is_default'  => $request->input('is_default') ?? '',
                ]);

                Session::flash('success_msg', 'Successfully Created');
                return redirect()->route('setting_reward', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_reward.form', [
            'submit' => route('setting_reward_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'setting_reward_category_sel' => ['' => 'Please select reward category'] +  SettingRewardCategory::get_sel_setting_reward(),
            'company_sel' => Company::get_company_sel(),
            'setting_reward_status_sel' => ['' => 'Please select status', 'active' => 'Active', 'pending' => 'Pending'],
        ])->withErrors($validator);
    }

    public function edit(Request $request, $setting_reward_id)
    {
        $post = $setting_reward = SettingReward::find($setting_reward_id);
        $validation = null;

        if($setting_reward->setting_reward_json){
            $post->add_setting_reward_data_to_json = json_decode($setting_reward->setting_reward_json, true);
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_reward_name' => 'required',
                'setting_reward_description' => 'nullable|max:250',
                'setting_reward_category_id'=>'required',
                'setting_reward_status' => 'required',
                'add_setting_reward_data_to_json' => 'required',
            ])->setAttributeNames([
                'setting_reward_name' => 'Name',
                'setting_reward_description' => 'Description',
                'setting_reward_category_id'=>'Category',
                'setting_reward_status' => 'Status',
                'add_setting_reward_data_to_json' => 'Details',
            ]);
            if (!$validation->fails()) {


                $reward_json = $request->input('add_setting_reward_data_to_json');
                $json = str_replace("'", "\'", json_encode($reward_json));
                $reward_json_list = json_decode($json, true);
                foreach ($reward_json_list as $key => $value) {
                    $reward_json[$key]['full_attendance'] = isset($value['full_attendance'][0]) ? $value['full_attendance'][0] : "0" ;
                }
                $result = str_replace("'", "\'", json_encode($reward_json));

                SettingReward::where('setting_reward_id',$setting_reward_id)
                ->update([
                    'setting_reward_name' => $request->input('setting_reward_name'),
                    'setting_reward_description' => $request->input('setting_reward_description') ?? '',
                    'setting_reward_category_id'=> $request->input('setting_reward_category_id') ?? '',
                    'setting_reward_json' => $result,
                    'setting_reward_status' => $request->input('setting_reward_status') ?? '',
                    'company_id' => auth()->user()->company_id ?? 0,
                    'is_default'  => $request->input('is_default') ?? '',
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_reward', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_reward.form', [
            'submit' => route('setting_reward_edit', ['tenant' => tenant('id'), 'id' => $setting_reward_id]),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
            'setting_reward_category_sel' => ['' => 'Please select reward category'] +  SettingRewardCategory::get_sel_setting_reward(),
            'company_sel' => Company::get_company_sel(),
            'setting_reward_status_sel' => ['' => 'Please select status', 'active' => 'Active', 'pending' => 'Pending'],
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $currency = SettingReward::find($request->input('setting_reward_id'));

        if(!$currency){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_reward', ['tenant' => tenant('id')]);
        }

        $currency->delete();

        Session::flash('success_msg', "Successfully deleted reward.");
        return redirect()->route('setting_reward', ['tenant' => tenant('id')]);
    }
}
