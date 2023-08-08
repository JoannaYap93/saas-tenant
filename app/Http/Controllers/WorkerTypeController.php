<?php

namespace App\Http\Controllers;

use App\Model\WorkerType;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Validator;

class WorkerTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request)
    {
        $perpage = 10;

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    $search['freetext'] = $request->input('freetext');
                    Session::put('worker_type_search', $search);
                    break;
                case 'reset':
                    Session::forget('worker_type_search');
                    break;
            }
        }
        $search = Session::has('worker_type_search') ? Session::get('worker_type_search') : array();

        return view('worker_type.listing', [
            'submit' => route('worker_type_listing'),
            'title' => 'Worker Type Listing',
            'records' => WorkerType::get_records($search, $perpage),
            'search' =>  $search,
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'worker_type_name' => 'required',
            ])->setAttributeNames([
                'worker_type_name' => 'Name',
            ]);

            if (!$validation->fails()) {
                WorkerType::create([
                    'worker_type_name' => $request->input('worker_type_name'),
                ]);

                Session::flash('success_msg', 'Successfully added '.$request->input('worker_type_name'));
                return redirect()->route('worker_type_listing');
            }
            $post = (object) $request->all();
        }

        return view('worker_type.form', [
            'submit' => route('worker_type_add'),
            'title' => 'Add',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public function edit(Request $request, $worker_type_id)
    {
        $post = $worker = WorkerType::find($worker_type_id);
        $validation = null;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'worker_type_name' => 'required',
            ])->setAttributeNames([
                'worker_type_name' => 'Name',
            ]);
            if (!$validation->fails()) {
                $worker->update([
                    'worker_type_name' => $request->input('worker_type_name'),
                ]);

                Session::flash('success_msg', 'Successfully edited '.$request->input('worker_type_name'));
                return redirect()->route('worker_type_listing');
            }
            $post = (object) $request->all();
        }

        return view('worker_type.form', [
            'submit' => route('worker_type_edit', $worker_type_id),
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $worker_type = WorkerType::find($request->input('worker_type_id'));

        if(!$worker_type){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('worker_type_listing');
        }

       $worker_type->delete();

        Session::flash('success_msg', "Successfully deleted worker type.");
        return redirect()->route('worker_type_listing');
    }

}
