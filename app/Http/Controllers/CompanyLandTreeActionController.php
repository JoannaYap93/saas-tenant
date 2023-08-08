<?php

namespace App\Http\Controllers;

use App\Model\CompanyLandTreeAction;
use App\Model\Company;
use App\Model\User;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;


class CompanyLandTreeActionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'super_admin'], ['except' => ['listing', 'add_default', 'add_company','edit_default', 'ajax_get_tree_action']]);
    }

    public function listing(Request $request)
    {
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
            case 'search':
                session(['listing' => [
                    'freetext' => $request->input('freetext'),
                    'company_id' => $request->input('company_id'),
                    'user_id' => $request->input('user_id'),
                ]]);
                break;
            case 'reset':
                session()->forget('listing');
                break;
            }
        }

        $search = session('listing') ?? array();
        $action = CompanyLandTreeAction::get_records($search);
        $company = Company::get_company_sel();
        $user = User::get_user_sel();

        return view('company_landtree_action.listing', [
            'records' => $action,
            'submit' => route('company_landtree_listing'),
            'search' => $search,
            'company_sel' => Company::get_company_sel(),
            'company' => $company,
            'user' => $user,
        ]);
    }

    public function add_default(Request $request)
    {
        $validator = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_land_tree_action_id' => '',
                'company_land_tree_action_name' => 'required',
                'company_id' => '',
                'user_id' => '',
                'is_value_required' => '',
            ])->setAttributeNames([
                'company_land_tree_action_id' => 'ID',
                'company_land_tree_action_name' => 'Name',
                'company_id' => 'Company ID',
                'user_id' => 'User ID',
                'is_value_required' => 'Value'
            ]);

            if (!$validator->fails()) {
                CompanyLandTreeAction::create([
                    'company_land_tree_action_name' => $request->input('company_land_tree_action_name') ?? '',
                    'is_value_required' => $request->input('is_value_required') ?? '',
                    'company_id' => auth()->user()->company_id,
                    'user_id' => auth()->user()->user_id
                ]);

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('company_landtree_listing');
            }
        }
        return view('company_landtree_action.form', [
            'submit' => route('add_default'),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);

    }

    public function edit_default(Request $request, $company_land_tree_action_id)
    {
        $post = CompanyLandTreeAction::find($company_land_tree_action_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'company_land_tree_action_id' => '',
                'company_land_tree_action_name' => 'required',
                'company_id' => '',
                'user_id' => '',
                'is_value_required' => '',
            ])->setAttributeNames([
                'company_land_tree_action_id' => 'ID',
                'company_land_tree_action_name' => 'Name',
                'company_id' => 'Company ID',
                'user_id' => 'User ID',
                'is_value_required' => 'Value'
            ]);
            if (!$validation->fails()) {
                $post->update([
                    'company_land_tree_action_name' => $request->input('company_land_tree_action_name') ?? '',
                    'is_value_required' => $request->input('is_value_required') ?? '',
                    'company_id' => auth()->user()->company_id,
                    'user_id' => auth()->user()->user_id
                ]);

                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('company_landtree_listing');
            }
            $post = (object) $request->all();

        }
        return view('company_landtree_action.form', [
            'submit' => route('edit_default', $company_land_tree_action_id),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public static function ajax_get_tree_action(Request $request)
    {
        $actions = CompanyLandTreeAction::get_by_company_id();
        return $actions;
    }
}
