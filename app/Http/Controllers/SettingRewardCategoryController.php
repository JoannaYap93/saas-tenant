<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingRewardCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingRewardCategoryController extends Controller
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
                    $search['freetext'] = $request->input('freetext');
                    Session::put('setting_reward_category', $search);
                    break;
                case 'reset':
                    Session::forget('setting_reward_category');
                    break;
                }
        }
        $search = session('setting_reward_category') ?? array();

        return view('setting_reward_category.listing', [
            'page_title' => 'Setting Reward Category',
            'submit' => route('setting_reward_category', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingRewardCategory::get_records($search, $perpage),
        ]);
    }


    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_reward_category_name' =>  'required|unique:tbl_setting_reward_category,setting_reward_category_name',
            ])->setAttributeNames([
                'setting_reward_category_name' => 'Reward Category Name',
            ]);

            if (!$validator->fails()) {
                SettingRewardCategory::create([
                    'setting_reward_category_name' => $request->input('setting_reward_category_name'),
                ]);

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_reward_category', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_reward_category.form', [
            'submit' => route('setting_reward_category_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);
    }

    public function edit(Request $request, $setting_reward_category_id)
    {
        $post = $setting_reward = SettingRewardCategory::find($setting_reward_category_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_reward_category_name' => "required|unique:tbl_setting_reward_category,setting_reward_category_name,{$setting_reward_category_id},setting_reward_category_id",
            ])->setAttributeNames([
                'setting_reward_category_name' => 'Reward Category Name',
            ]);

            if (!$validation->fails()) {
                $setting_reward->update([
                    'setting_reward_category_name' => $request->input('setting_reward_category_name'),
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_reward_category', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_reward_category.form', [
            'submit' => route('setting_reward_category_edit', ['tenant' => tenant('id'), 'id' => $setting_reward_category_id]),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $currency = SettingRewardCategory::find($request->input('setting_reward_category_id'));

        if(!$currency){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_reward_category', ['tenant' => tenant('id')]);
        }

        $currency->delete();

        Session::flash('success_msg', "Successfully deleted reward category.");
        return redirect()->route('setting_reward_category', ['tenant' => tenant('id')]);
    }
}
