<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingRace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingRaceController extends Controller
{
    public function construct()
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
                    session(['setting_race_search' => [
                        'freetext' => $request->input('freetext'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('setting_race_search');
                    break;
            }
        }

        $search = session('setting_race_search') ? session('setting_race_search') : $search;

        return view('setting_race.listing', [
            'page_title' => 'Setting Race',
            'submit' => route('setting_race', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingRace::get_records($search, $perpage),
        ]);
    }


    public function add(Request $request)
    {
        $post = null;
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_race_name' => 'required|unique:tbl_setting_race,setting_race_name',
            ])->setAttributeNames([
                'setting_race_name' => 'Race Name',
            ]);

            if (!$validation->fails()) {
                SettingRace::create([
                    'setting_race_name' => $request->input('setting_race_name') ?? '',
                ]);

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_race', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_race.form', [
            'submit' => route('add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validation);
    }

    public function edit(Request $request, $setting_race_id)
    {
        $post = $setting_race = SettingRace::find($setting_race_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
              'setting_race_name' => "required|unique:tbl_setting_race,setting_race_name,{$setting_race_id},setting_race_id",
            ])->setAttributeNames([
              'setting_race_name' => 'Race Name',
            ]);
            if (!$validation->fails()) {
                $setting_race->update([
                  'setting_race_name' => $request->input('setting_race_name'),
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_race', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_race.form', [
            'submit' => route('edit', ['tenant' => tenant('id'), 'id' => $setting_race_id]),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $race = SettingRace::find($request->input('setting_race_id'));

        if(!$race){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_race', ['tenant' => tenant('id')]);
        }

        $race->delete();

        Session::flash('success_msg', "Successfully deleted race.");
        return redirect()->route('setting_race', ['tenant' => tenant('id')]);
    }
}
