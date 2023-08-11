<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Session;

class UserRoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request)
    {
        $roles = Role::query();

        if (auth()->user()->company_id != 0) {
            $roles = $roles->where('company_id', auth()->user()->company_id);
        } else {
            $roles = $roles;
        }

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    session(['role_search' => [
                        "freetext" =>  $request->input('freetext'),
                        "company_id" =>  $request->input('company_id'),
                    ]]);
                    break;
                case 'reset':
                    session()->forget('role_search');
                    break;
            }
        }

        $search = session('role_search') ?? array();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $roles = $roles->where(function ($r) use ($freetext) {
                $r->where('name', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['company_id']) {
            $roles = $roles->where('company_id', $search['company_id']);
        }

        $roles = $roles->get();

        $user_role_count = array();
        $company = array();
        foreach ($roles as $role) {
            $user_role_count[$role->id] = User::role($role->name)->count();
            $company[$role->id] = Company::get_company_name_by_id($role->company_id);
        }
        $company_sel = Company::get_company_sel();
        return view('user_role/listing', [
            'roles' => $roles, 
            'user_role_count' => $user_role_count, 
            'company' => $company, 
            'company_sel' => $company_sel
        ]);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;
        if ($request->isMethod('post')) {

            $user = auth()->user();

            if ($user->company_id != 0) {
                $company_id = $user->company_id;
            } else {
                $company_id = $request->input('company_id');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:tbl_user_role,name',
                'permissions' => 'required'
            ])->setAttributeNames([
                'name' => 'Role Name',
                'permissions' => 'Permission',
            ]);
            if (!$validator->fails()) {
                $role = Role::create(['name' =>  $request->input('name'), 'company_id' => $company_id]);
                $permissions = $request->input('permissions');
                $role->givePermissionTo($permissions);
                return redirect()->route('user_role_listing', ['tenant' => tenant('id')])->with('message', 'Successfully added New Role');
            }
            $post = (object) $request->all();
        }
        return view('user_role/form', [
            'submit' => route('user_role_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'permissions' => Permission::orderBy('group_name', 'asc')->get(),
        ])->withErrors($validator);
    }

    public function edit(Request $request, $role_id)
    {
        $validator = null;
        $user = auth()->user();
        $post = $role =  Role::query()->where('role_id', $role_id);

        if ($user->company_id != 0) {
            $role = $role->where('company_id', $user->company_id)->first();
        } else {
            $role = $role->first();
        }

        if (!$role) {
            Session::flash('fail_msg', 'Invalid Roles, Please try again later.');
            return redirect('/');
        }

        if ($request->isMethod('post')) {

            if ($user->company_id != 0) {
                $company_id = $user->company_id;
            } else {
                $company_id = $request->input('company_id');
            }

            $validator = Validator::make($request->all(), [
                'name' => "required|unique:tbl_user_role,name,{$role_id}",
                'permissions' => 'required'
            ])->setAttributeNames([
                'name' => 'Role Name',
                'permissions' => 'Permission',
            ]);
            
            if (!$validator->fails()) {
                $role->update([
                    'name' => $request->input('name'),
                    'company_id' => $company_id
                ]);
                $permissions = $request->input('permissions');
                $role->syncPermissions($permissions);
                Session::flash('success_msg', 'Successfully updaterd ' . $request->input('name') . ' role.');
                return redirect()->route('user_role_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('user_role/form', [
            'submit' => route('user_role_edit', ['tenant' => tenant('id'), 'id' => $role_id]),
            'title' => 'Edit',
            'post' => $post,
            'permissions' => Permission::orderBy('group_name', 'asc')->get(),
            'role_permissions' => $role->permissions()->pluck('name')->toArray(),
        ])->withErrors($validator);
    }
}
