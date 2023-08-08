<?php

namespace App\Http\Controllers;

use Session;
use App\Model\User;
use App\Model\Media;
use App\Model\Worker;
use App\Model\Company;
use App\Model\Setting;
use App\Model\Supplier;
use App\Model\WorkerRole;
use App\Model\CompanyLand;
use App\Model\WorkerStatus;
use App\Model\RunningNumber;
use Illuminate\Http\Request;
use App\Model\CompanyExpense;
use App\Model\SettingExpense;
use App\Model\SettingFormula;
use App\Model\CompanyExpenseLog;
use App\Model\CompanyExpenseItem;
use App\Model\CompanyExpenseLand;
use App\Model\RawMaterialCompany;
use App\Model\SettingExpenseType;
use App\Model\SettingFormulaItem;
use App\Model\SettingRawMaterial;
use App\Model\WorkerWalletHistory;
use App\Model\CompanyExpenseWorker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Model\SettingExpenseCategory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;

class CompanyExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('listing');
                    $search['date_from'] = $request->input('date_from');
                    $search['date_to'] = $request->input('date_to');
                    $search['freetext'] = $request->input('freetext');
                    $search['company_id'] = $request->input('company_id');
                    $search['company_land_id'] = $request->input('company_land_id');
                    $search['expense_category_id'] = $request->input('expense_category_id');
                    $search['expense_id'] = $request->input('expense_id');
                    $search['comp_expense_type'] = $request->input('comp_expense_type');
                    $search['user_id'] = $request->input('user_id');

                    Session::put('listing', $search);
                    break;
                case 'reset':
                    Session::forget('listing');
                    break;
            }
        }

        $search = session('listing') ?? array();

        return view('company_expense.listing', [
            'submit' => route('company_expense_listing'),
            'search' => $search,
            'records' => CompanyExpense::get_records($search),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'user_sel' => ['' => 'Please Select User'] + User::get_user_sel(),
            'expense_type_sel' => [ ''=> 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
            'worker_by_company' => Worker::get_worker_by_company(),
        ]);
    }

    public function edit(Request $request, $company_expense_id)
    {
        $post = CompanyExpense::query()->where('company_expense_id', $company_expense_id)->first();
        $post_exp_item = CompanyExpenseItem::find($company_expense_id);
        $validation = null;
        $expense_data = [];
        $worker_data = [];
        $worker_json = [];
        $expense_json = [];
        $user = auth()->user();

        if(@$post->setting_expense_category_id == 2){
            $isworker = true;
            foreach($post->company_expense_worker as $key => $data){
                $worker_json[$data->worker_id] = @$data->company_expense_worker_detail ? json_decode(@$data->company_expense_worker_detail, true) : null;
            }
            foreach($post->company_expense_item as $key => $data){
                $expense_json[$data->expense_id] = @$data->company_expense_item_detail ? json_decode(@$data->company_expense_item_detail, true) : null;
            }
        }else{
            $isworker =false;
        }

        foreach($worker_json as $key => $data){
            $worker_data[$key] = $data;
        }

        foreach($expense_json as $key => $data){
            $expense_data[$key] = $data;
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'expense_type' => 'required',
                'expense_category_id' => 'required',
                'expense_date' => 'required',
            ])->setAttributeNames([
                'company_expense_number' => 'company_expense_number',
                'manager_id' => 'manager_id',
                'worker_type' => 'worker_type',
                'expense_type' => 'expense_type',
                'expense_category_id' => 'expense_category_id',
                'company_expense_total' => 'company_expense_total',
                'expense_date' => 'expense_date',
                'grand_total_expense' => 'grand_total_expense',
            ]);

            if (!$validation->fails()) {
                //remark; to update staff costing
                $worker_input = $request->input('worker');
                $input = $request->input('expense_date');
                $date = strtotime($input);
                $user = auth()->user();
                $farm_manager = Worker::find($request->input('manager_id'));

                if ($user->company_id != 0) {
                    $company = Company::find($user->company_id);
                } else {
                    $company = Company::find($request->input('company_id_sel'));
                }

                if($worker_input){
                    $final_grand_total = 0;
                    $post->update([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $request->input('manager_id') ?? 0,
                        'worker_role_id' => 0,
                        'company_id' => $company->company_id,
                        'company_land_id' => $request->input('company_land_id'),
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                    ]);

                    CompanyExpenseLand::where('company_expense_id', $company_expense_id)->delete();

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }
                    CompanyExpenseLog::insert([
                        'company_expense_id' => $company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Updated By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    foreach($worker_input as $wi){
                        if(isset($wi['selected'])){
                            $array_worker_id[] = $wi['worker_id'];
                        }
                    }
                    $old_worker_id = CompanyExpenseworker::where('company_expense_id', $company_expense_id)->pluck('worker_id')->toArray();
                    $removed_worker_id = array_udiff($old_worker_id,$array_worker_id, function ($a, $b) { return (int)$a - (int)$b; });
                    if(@$removed_worker_id)
                    {
                        foreach($removed_worker_id as $removed){
                            $old_company_worker_item = CompanyExpenseWorker::where('worker_id',$removed)->get();
                            foreach($old_company_worker_item as $old_item){
                                $old_item->delete();
                            }
                        }
                    }

                    foreach($worker_input as $wkey => $rows){
                        $grand_total = 0;
                        $worker_array = [];
                        $worker_data =[];

                        if(@$rows['selected']){
                            if($rows['selected'] == 1){
                                // remark; to calculate total expense for tbl.company_expense_item
                                $task_data = array();
                                $task_data = [];
                                $task_array = [];
                                if(isset($rows['task'])){

                                    foreach($rows['task'] as $key => $val){
                                        $final_total = 0;
                                        $comp_exp_total = 0;

                                        if(@$val['selected']){
                                            if($val['selected'] == 1){
                                                // remark; to calculate total expense for tbl.company_expense_worker
                                                $exp_total = @(float)$val['expense_total'];
                                                $total_exp_val = $exp_total;
                                                $final_total = $total_exp_val;
                                                $comp_exp_total += $final_total;
                                                $task_data = array(
                                                    "expense_id" => @$key,
                                                    "expense_name" => @$val['setting_expense_name'],
                                                    "expense_value" => @$val['expense_value'],
                                                    'qty' => @$val['qty'],
                                                    "setting_expense_overwrite_commission" => @$val['setting_expense_overwrite_commission'],
                                                    "expense_total" => (float)$final_total,
                                                    "expense_type" => @$val['setting_expense_type_id'],
                                                );
                                                array_push($task_array, $task_data);
                                            }
                                        }
                                        $grand_total += $final_total;
                                    }
                                }
                                $final_grand_total += $grand_total;
                                $worker_data = array(
                                    "worker_name" => @$rows['worker_name'],
                                    "type" => @$rows['type'],
                                    "status" => @$rows['status'],
                                    "time_slot" => @$rows['time_slot'],
                                    "task" => @$task_array,
                                );

                                CompanyExpenseWorker::updateOrCreate([
                                    'company_expense_id' => $post->company_expense_id,
                                    'worker_id' => $wkey,
                                ],[
                                    'company_expense_id' => $post->company_expense_id,
                                    'worker_id' => $wkey,
                                    'company_expense_worker_detail' => json_encode($worker_data),
                                    'company_expense_item_total' => $grand_total,
                                    'company_expense_worker_created' => $request->input('date_created'),
                                    'company_expense_worker_updated' => now(),
                                ]);
                            }
                        }
                    }

                    $post->update([
                        'company_expense_total' => $final_grand_total,
                    ]);

                    $task_update = $task_output = array();
                    $expense_item_total = array();

                    foreach($worker_input as $key => $value){
                        if(@$value['selected']){
                            if(@$value['selected'] == 1){
                                foreach(@$value['task'] as $task_key => $task){
                                    if(@$task['selected']){
                                        if(@$task['selected'] == 1){
                                            if(isset($task_output[$task_key][$value['worker_id']])){
                                                array_push($task_output[$task_key][$value['worker_id']]['detail'], $task_update);
                                            }else{
                                                $task_output[$task_key][$value['worker_id']]['worker_id'] = $value['worker_id'];
                                                // $task_output[$task_key][$value['worker_id']]['detail'] = $task;
                                                $task_output[$task_key][$value['worker_id']]['detail'] = array(
                                                    'expense_name' => $task['expense_name'],
                                                    'expense_value' => $task['expense_value'],
                                                    'qty' => @$task['qty'],
                                                    'setting_expense_overwrite_commission' => @$task['setting_expense_overwrite_commission'],
                                                    'expense_total' => $task['expense_total'],
                                                    'setting_expense_type' => $task['setting_expense_type'],
                                                );
                                                if(isset($expense_item_total[$task_key])){
                                                    $expense_item_total[$task_key] += @$task['expense_total'];
                                                }else{
                                                    $expense_item_total[$task_key] = @$task['expense_total'];
                                                }
                                                $task_output[$task_key]['exp_total'] = $expense_item_total[$task_key];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    foreach($worker_input as $wi){
                        if(isset($wi['selected'])){
                            if(isset($wi['task'])){
                                foreach($wi['task'] as $key => $task){
                                    $array_expense_id[] = $key;
                                }
                            }
                        }
                    }
                        $old_expense_id = CompanyExpenseItem::where('company_expense_id', $company_expense_id)->pluck('setting_expense_id')->toArray();
                        $removed_expense_id = array_udiff($old_expense_id,$array_expense_id, function ($a, $b) { return (int)$a - (int)$b; });

                        if(@$removed_expense_id)
                        {
                            foreach($removed_expense_id as $removed){
                                $old_company_expense_item = CompanyExpenseItem::where('setting_expense_id',$removed)->get();
                                foreach($old_company_expense_item as $old_item){
                                    $old_item->delete();
                                }
                            }
                        }

                    foreach($task_output as $output_key => $output){
                        CompanyExpenseItem::updateOrCreate([
                            'company_expense_id' => $post->company_expense_id,
                            'setting_expense_id' => $output_key,
                        ],[
                            'company_expense_id' => $post->company_expense_id,
                            'setting_expense_id' => $output_key,
                            'company_expense_item_detail' => json_encode($output),
                            'company_expense_item_total' => $task_output[$output_key]['exp_total'],
                            'company_expense_item_created' => $request->input('date_created'),
                            'company_expense_item_updated' => now(),
                        ]);
                    }

                }

                //remark; update for upkeep
                $expense = $request->input('expense_id_hidden');
                if($expense){
                    $post->update([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $request->input('manager_id') ?? 0,
                        'worker_role_id' => 0,
                        'company_id' => $company->company_id,
                        'company_land_id' => $request->input('company_land_id'),
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                    ]);

                    CompanyExpenseLand::where('company_expense_id', $company_expense_id)->delete();

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }

                    CompanyExpenseLog::insert([
                        'company_expense_id' => $company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Updated By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    $old_expense_id = CompanyExpenseItem::where('company_expense_id', $company_expense_id)->pluck('setting_expense_id')->toArray();
                    $removed_expense_id = array_udiff($old_expense_id,$expense, function ($a, $b) { return (int)$a - (int)$b; });

                    if(@$removed_expense_id)
                    {
                        foreach($removed_expense_id as $removed){
                            $old_company_expense_item = CompanyExpenseItem::where('setting_expense_id',$removed)
                                                        ->where('company_expense_id', $company_expense_id)
                                                        ->get();
                            foreach($old_company_expense_item as $old_item){
                                if($old_item->hasMedia('company_expense_item_media')){
                                    $old_item->clearMediaCollection('company_expense_item_media');
                                }
                                $old_item->delete();
                            }
                        }
                    }

                    $expense_total = 0;
                    foreach($expense as $key => $exp){
                        $total_grand_price = 0;
                        $price = 0;
                        $quantity = 0;
                        $price_upkeep_by_row = 0;
                        $price = $request->input('item_price')[$key];
                        $quantity = $request->input('item_quantity')[$key];

                        $price_upkeep_by_row = $price * $quantity;

                        $total_grand_price += $price_upkeep_by_row;
                        $expense_total += $total_grand_price;
                        //------------
                        $find_exp_item = CompanyExpenseItem::query()
                            ->where('company_expense_id', $post->company_expense_id)
                            ->where('setting_expense_id',  $request->input('expense_id_hidden')[$key])
                            ->first();

                        $company_expense_item = CompanyExpenseItem::updateOrCreate([
                            'company_expense_id' => $post->company_expense_id,
                            'setting_expense_id' => $request->input('expense_id_hidden')[$key],
                        ],[
                            'company_expense_id' => $post->company_expense_id,
                            'setting_expense_id' => $request->input('expense_id_hidden')[$key],
                            'company_expense_item_unit' => $quantity,
                            'company_expense_item_unit_price' => $price,
                            'company_expense_item_total' => $total_grand_price,
                            'company_expense_item_created' => now(),
                            'company_expense_item_updated' => now(),
                            'supplier_id' => $request->input('supplier_id_hidden')[$key],
                            'remark' => $request->input('remark')[$key],

                        ]);

                        if(isset($request->input('is_claim')['claim'][$key])){
                            if($request->input('is_claim')['claim'][$key] == 1)
                            {
                                $company_expense_item->update([
                                    'is_claim' => 1
                                ]);

                                WorkerWalletHistory::update_worker_wallet_history($post->worker_id, 'company_expense_item_id', $company_expense_item->company_expense_item_id, Auth::id());
                            }
                        }else{
                            $company_expense_item->update([
                                'is_claim' => 0
                            ]);

                            WorkerWalletHistory::revert_worker_wallet_history($post->worker_id, 'company_expense_item_id', $company_expense_item->company_expense_item_id, Auth::id());
                        }

                        if(isset($request->file('expense_item_media')[$key+1])){
                            $files = $request->file('expense_item_media')[$key+1];

                            foreach($files as $keyfile => $file)
                            {
                                $company_expense_item->addMedia($file)->toMediaCollection('company_expense_item_media');
                            }
                        }

                    }
                        $post->update([
                            'company_expense_total' => $expense_total,
                        ]);

                        //testing calculation starts
                        //each expense_land
                        // $total_tree_from_lands = 0;
                        // $company_expense_land_2 = CompanyExpenseLand::where('company_expense_id', $company_expense_id)->get();
                        // foreach ($company_expense_land_2 as $key => $company_expense_land_2_1) {
                        //     $total_tree_from_lands += $company_expense_land_2_1->company_land->company_land_total_tree;
                        // }
                        // //each expense_item
                        // $total_avg_price_per_tree = 0;
                        // $company_expense_item_2 = CompanyExpenseItem::where('company_expense_id', $company_expense_id)->get();
                        // foreach ($company_expense_item_2 as $key => $each_item) {
                        //   $avg_price_per_tree = $each_item->company_expense_item_total / $total_tree_from_lands;
                        //   $total_avg_price_per_tree += $avg_price_per_tree;
                        //   $each_item->update([
                        //     'company_expense_item_average_price_per_tree' => $avg_price_per_tree,
                        //   ]);
                        // }
                        //
                        // foreach ($company_expense_land_2 as $key => $company_expense_land_2_2) {
                        //   $company_expense_land_total_price = $company_expense_land_2_2->company_land->company_land_total_tree * $total_avg_price_per_tree;
                        //   $company_expense_land_2_2->update([
                        //     'company_expense_land_total_tree' => $company_expense_land_2_2->company_land->company_land_total_tree,
                        //     'company_expense_land_total_price' => $company_expense_land_total_price,
                        //   ]);
                        // }
                        //testing calculation ends
                        $query = <<<GQL
                            mutation {
                                updateCompanyExpense(company_expense_id: $company_expense_id)
                            }
                            GQL;
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                        ])->post(env('GRAPHQL_API').'/graphql', [
                            'query' => $query
                        ]);
                }

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('company_expense_listing');
            }

            $post = (object) $request->all();

        }

        return view('company_expense.form', [
            'submit' => route('company_expense_edit', $company_expense_id),
            'title' => 'Edit',
            'worker' => $isworker,
            'records'=> $post,
            'supplier_sel' => Supplier::supplier_sel(),
            'expense_item' => $expense_data,
            'worker_item' => $worker_data,
            'worker_jquery' => array_keys($worker_data),
            'material_sel' => SettingRawMaterial::get_rm_sel(),
            'formula_sel' => SettingFormula::get_formula_sel(),
            // 'manager_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_land_sel(),
            'manager_sel' => ['' => 'Please Select Farm Manager'] + Worker::get_farm_manager_by_company_id(auth()->user()->company_id, false),

            'worker_status_sel' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            'worker_status_sel2' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel_wihtout_labour(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            // 'user_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_sel(),
            'user_sel' => ['' => 'Please Select Farm Manager'] + Worker::get_farm_manager_by_company_id(auth()->user()->company_id, false),

            'time_slot' => ['' => 'Please Select Time Slot'] +  Setting::get_time_slot(),
            'expense_type_sel' => ['' => 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
            'company_sel' => Company::get_company_sel(),
            'worker_role_cb' => WorkerRole::get_worker_role(),
        ])->withErrors($validation);
    }


    public function add(Request $request)
    {
        $validator = null;
        $company_expense = null;
        $input = $request->input('expense_date');
        $date = strtotime($input);
        $user = auth()->user();
        $farm_manager = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'manager_id' => 'required',
                'expense_type' => 'required',
                'expense_category_id' => 'required',
                'company_land_id' => 'required',
                'expense_date' => 'required',
            ])->setAttributeNames([
                'company_expense_number' => 'company_expense_number',
                'manager_id' => 'manager_id',
                'worker_type' => 'worker_type',
                'expense_type' => 'expense_type',
                'expense_category_id' => 'expense_category_id',
                'company_land_id' => 'Company Land',
                'company_expense_total' => 'company_expense_total',
                'expense_date' => 'expense_date',
                'grand_total_expense' => 'grand_total_expense',
            ]);

            if (!$validator->fails()) {
                //remark; store into db for staff costing
                $running_no = RunningNumber::get_running_code('company_expense');
                $worker_input = $request->input('worker');
                $user = auth()->user();
                $farm_manager = Worker::find($request->input('manager_id'));

                if ($user->company_id != 0) {
                    $company = Company::find($user->company_id);
                } else {
                    $company = Company::find($request->input('company_id_sel'));
                }

                if($worker_input){
                    $comp_expense = CompanyExpense::create([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        // 'company_expense_number' => $request->input('company_expense_number'),
                        'company_expense_number' => 'EP/' . $company->company_code . '/' . $running_no,
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $request->input('manager_id') ?? 0,
                        'worker_role_id' => $request->input('worker_role_id'),
                        'company_id' => $company->company_id,
                        'company_land_id' => $request->input('company_land_id'),
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_created' => now(),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                    ]);

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $comp_expense->company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }

                    CompanyExpenseLog::insert([
                        'company_expense_id' => $comp_expense->company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Added By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    $final_grand_total = 0;
                    foreach($worker_input as $wkey => $rows){
                        $grand_total = 0;
                        $worker_array = array();
                        $worker_data = array();

                        if(@$rows['selected']){
                            if($rows['selected'] == 1){
                                // remark; to calculate total expense for tbl.company_expense_item
                                $task_data = array();
                                $task_array = array();

                                foreach($rows['task'] as $key => $val){
                                    $final_total = 0;
                                    $comp_exp_total = 0;

                                    if(@$val['selected']){
                                        if($val['selected'] == 1){
                                            // remark; to calculate total expense for tbl.company_expense_worker
                                            $exp_total = @(float)$val['expense_total'];
                                            $first_total = $exp_total;
                                            $final_total = $first_total;
                                            $comp_exp_total += $final_total;
                                            $task_data = array(
                                                "expense_id" => @$key,
                                                "expense_name" => @$val['expense_name'],
                                                "expense_value" => @$val['expense_value'],
                                                'qty' => @$val['qty'],
                                                "setting_expense_overwrite_commission" => @$val['setting_expense_overwrite_commission'],
                                                "expense_total" => @$val['expense_total'],
                                                "expense_type" => @$val['setting_expense_type'],
                                            );
                                            array_push($task_array, $task_data);
                                        }
                                    }
                                    $grand_total += $final_total;
                                }
                                $final_grand_total += $grand_total;
                                $worker_data = array(
                                    "worker_name" => @$rows['worker_name'],
                                    "type" => @$rows['type'],
                                    "status" => @$rows['status'],
                                    "time_slot" => @$rows['time_slot'],
                                    "task" => @$task_array,
                                );

                                $company_expense_worker = CompanyExpenseWorker::create([
                                    'company_expense_id' => $comp_expense->company_expense_id,
                                    'worker_id' => @$rows['worker_id'],
                                    'company_expense_worker_detail' => json_encode($worker_data),
                                    'company_expense_worker_total' => $grand_total,
                                    'company_expense_worker_created' => now(),
                                    'company_expense_worker_updated' => now(),

                                ]);
                            }
                        }
                    }

                    $comp_expense->update([
                        'company_expense_total' => $final_grand_total,
                    ]);

                    $task = $task_output = array();
                    $expense_item_total = array();

                    foreach(@$worker_input as $key => $value){
                        foreach($value['task'] as $task_key => $task){
                            if(@$task['selected']){
                                if(isset($task_output[$task_key][$value['worker_id']])){
                                    array_push($task_output[$task_key][$value['worker_id']]['detail'], $task);
                                }else{
                                    $task_output[$task_key][$value['worker_id']]['worker_id'] = $value['worker_id'];
                                    $task_output[$task_key][$value['worker_id']]['detail'] = array(
                                        'expense_name' => @$task['expense_name'],
                                        'expense_value' => @$task['expense_value'],
                                        'qty' => @$task['qty'],
                                        'setting_expense_overwrite_commission' => @$task['setting_expense_overwrite_commission'],
                                        'expense_total' => @$task['expense_total'],
                                        "time_slot" => @$value['time_slot'],
                                        'setting_expense_type' => @$task['setting_expense_type']
                                    );
                                    if(isset($expense_item_total[$task_key])){
                                        $expense_item_total[$task_key] += @$task['expense_total'];
                                    }else{
                                        $expense_item_total[$task_key] = @$task['expense_total'];
                                    }
                                    $task_output[$task_key]['exp_total'] = $expense_item_total[$task_key];
                                }
                            }
                        }
                    }

                    foreach($task_output as $output_key => $output){
                        CompanyExpenseItem::create([
                            'setting_expense_id' => $output_key,
                            'company_expense_id' => $comp_expense->company_expense_id,
                            'company_expense_item_detail' => json_encode($output),
                            // 'company_expense_item_unit_price' =>@$task['expense_value'],
                            'company_expense_item_total' => $task_output[$output_key]['exp_total'],
                            'company_expense_item_created' => now(),
                            'company_expense_item_updated' => now(),
                        ]);
                    }
                }

                //remark; store into db for upkeep
                $expense = $request->input('expense_id_hidden');
                if($expense){
                    $comp_expense = CompanyExpense::create([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        'company_expense_number' => 'EP/' . $company->company_code . '/' . $running_no,
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $request->input('manager_id') ?? 0,
                        'worker_role_id' => 0,
                        'company_id' => $company->company_id,
                        'company_land_id' => 0,
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_created' => now(),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                    ]);

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $comp_expense->company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }

                    CompanyExpenseLog::insert([
                        'company_expense_id' => $comp_expense->company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Added By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    $expense_total = 0;
                    foreach($expense as $key => $exp){
                        $total_grand_price = 0;
                        $price = 0;
                        $quantity = 0;
                        $price_upkeep_by_row = 0;

                        $price = $request->input('item_price')[$key];
                        $quantity = $request->input('item_quantity')[$key];

                        $price_upkeep_by_row = $price * $quantity;
                        $total_grand_price += $price_upkeep_by_row;
                        $expense_total += $total_grand_price;

                        $company_expense_item = CompanyExpenseItem::create([
                            'company_expense_id' => $comp_expense->company_expense_id,
                            'setting_expense_id' => $request->input('expense_id_hidden')[$key],
                            'company_expense_item_unit' => $request->input('item_quantity')[$key],
                            'company_expense_item_unit_price' => $request->input('item_price')[$key],
                            'company_expense_item_total' => $total_grand_price,
                            'company_expense_item_created' => now(),
                            'company_expense_item_updated' => now(),
                            'supplier_id' => $request->input('supplier_id_hidden')[$key],
                            'remark' => $request->input('remark')[$key],

                        ]);

                        if(isset($request->input('is_claimed')['claim'][$key+1])){
                            if($request->input('is_claimed')['claim'][$key+1] == 1){
                                $company_expense_item->update([
                                    'is_claim' => 1,
                                ]);
                                WorkerWalletHistory::update_worker_wallet_history($comp_expense->worker_id, 'company_expense_item_id', $company_expense_item->company_expense_item_id, Auth::id());
                            }
                        }else{
                            $company_expense_item->update([
                                'is_claim' => 0,
                            ]);
                        }

                        if(isset($request->file('expense_item_media')[$key+1])){
                            $files = $request->file('expense_item_media')[$key+1];
                            foreach($files as $keyfile => $file){
                                $company_expense_item->addMedia($file)->toMediaCollection('company_expense_item_media');
                            }
                        }
                    }
                    $comp_expense->update([
                        'company_expense_total' => $expense_total,
                    ]);

                    //testing calculation starts
                    //each expense_land
                    // $total_tree_from_lands = 0;
                    // $company_expense_land_2 = CompanyExpenseLand::where('company_expense_id', $comp_expense->company_expense_id)->get();
                    // foreach ($company_expense_land_2 as $key => $company_expense_land_2_1) {
                    //     $total_tree_from_lands += $company_expense_land_2_1->company_land->company_land_total_tree;
                    // }
                    // //each expense_item
                    // $total_avg_price_per_tree = 0;
                    // $company_expense_item_2 = CompanyExpenseItem::where('company_expense_id', $comp_expense->company_expense_id)->get();
                    // foreach ($company_expense_item_2 as $key => $each_item) {
                    //   $avg_price_per_tree = $each_item->company_expense_item_total / $total_tree_from_lands;
                    //   $total_avg_price_per_tree += $avg_price_per_tree;
                    //   $each_item->update([
                    //     'company_expense_item_average_price_per_tree' => $avg_price_per_tree,
                    //   ]);
                    // }
                    //
                    // foreach ($company_expense_land_2 as $key => $company_expense_land_2_2) {
                    //   $company_expense_land_total_price = $company_expense_land_2_2->company_land->company_land_total_tree * $total_avg_price_per_tree;
                    //   $company_expense_land_2_2->update([
                    //     'company_expense_land_total_tree' => $company_expense_land_2_2->company_land->company_land_total_tree,
                    //     'company_expense_land_total_price' => $company_expense_land_total_price,
                    //   ]);
                    // }
                    //testing calculation ends
                    $query = <<<GQL
                        mutation {
                            updateCompanyExpense(company_expense_id: $comp_expense->company_expense_id)
                        }
                        GQL;
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post(env('GRAPHQL_API').'/graphql', [
                        'query' => $query
                    ]);
                }
                Session::flash('success_msg', 'Successfully Added');
                return redirect()->route('company_expense_listing');
            }
        }

        return view('company_expense.form', [
            'submit' => route('company_expense_add'),
            'title' => 'Add',
            'records' => $company_expense,
            'supplier_sel' => Supplier::supplier_sel(),
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel_wihtout_labour(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'worker_status_sel' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            // 'manager_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_land_sel(),
            'manager_sel' => ['' => 'Please Select Farm Manager'] + Worker::get_farm_manager_by_company_id(auth()->user()->company_id, false),

            'time_slot' => Setting::get_time_slot(),
            'expense_type_sel' => ['' => 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'worker_role_cb' => WorkerRole::get_worker_role(),
        ])->withErrors($validator);

    }

    public function add_labour(Request $request)
    {
        $validator = null;
        $company_expense = null;
        $input = $request->input('expense_date');
        $date = strtotime($input);
        $user = auth()->user();
        $farm_manager = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'manager_id' => 'nullable',
                'expense_type' => 'required',
                'expense_category_id' => 'required',
                'company_land_id' => 'required',
                'expense_date' => 'required',
            ])->setAttributeNames([
                'company_expense_number' => 'company_expense_number',
                'manager_id' => 'manager_id',
                'worker_type' => 'worker_type',
                'expense_type' => 'expense_type',
                'expense_category_id' => 'expense_category_id',
                'company_land_id' => 'Company Land',
                'company_expense_total' => 'company_expense_total',
                'expense_date' => 'expense_date',
                // 'grand_total_expense' => 'grand_total_expense',
            ]);

            if (!$validator->fails()) {
                //remark; store into db for staff costing
                $running_no = RunningNumber::get_running_code('company_expense');
                // $farm_manager = User::find($request->input('manager_id'));
                $farm_manager = $request->input('mansager_id') ? User::find($request->input('manager_id')) : 0;
                $input = $request->input('expense_date');
                $date = strtotime($input);

                if ($user->company_id != 0) {
                    $company = Company::find(auth()->user()->company_id);
                } else {
                    $company = Company::find($request->input('company_id_sel'));
                }

                $fm_worker_id = $farm_manager ? Worker::query()->where('user_id', $farm_manager->user_id)->where('worker_role_id', 2)->pluck('worker_id')->first() : 0;

                if(is_array($request->input('worker_id_'))){
                    $company_expense = CompanyExpense::create([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        // 'company_expense_number' => $request->input('company_expense_number'),
                        'company_expense_number' => 'EP/' . $company->company_code . '/' . $running_no,
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $fm_worker_id,
                        'worker_role_id' => $request->input('worker_role_id'),
                        'company_id' => $company->company_id,
                        'company_land_id' => 0,
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_created' => now(),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                        'company_expense_total' => $request->input('company_expense_total'),
                    ]);

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }

                    CompanyExpenseLog::insert([
                        'company_expense_id' => $company_expense->company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Added By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    $company_expense_item_detail_json = [];
                    if($request->input('expense_id_')){
                      foreach ($request->input('expense_id_') as $worker_id_2 => $expense_by_worker) {
                        foreach ($expense_by_worker as $key => $expense_id_2) {
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['qty'] = isset($request->input('qty_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('qty_')[$worker_id_2][$expense_id_2];
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['time_slot'] = isset($request->input('worker_timing_')[$worker_id_2]) == false ? '' : $request->input('worker_timing_')[$worker_id_2];
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_name'] = null;
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_total'] = isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_value'] = isset($request->input('expense_value_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_value_')[$worker_id_2][$expense_id_2];
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['setting_expense_overwrite_commission'] = isset($request->input('setting_expense_commission_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('setting_expense_commission_')[$worker_id_2][$expense_id_2];
                            $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['worker_id'] = $worker_id_2;
                            if(isset($company_expense_item_detail_json[$expense_id_2]['exp_total'])){
                              $company_expense_item_detail_json[$expense_id_2]['exp_total'] += isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                            }else{
                              $company_expense_item_detail_json[$expense_id_2]['exp_total'] = isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                            }
                        }
                      }
                        //   dd($company_expense_item_detail_json, $request->input('expense_id_'));

                      foreach ($company_expense_item_detail_json as $expense_id_3 => $details) {
                        $company_expense_item = CompanyExpenseItem::create([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'setting_expense_id' => $expense_id_3,
                          'company_expense_item_detail' => json_encode($details),
                          'company_expense_item_total' => $details['exp_total'],
                          'company_expense_item_created' => now(),
                          'company_expense_item_updated' => now(),
                        ]);
                      }
                    }

                    foreach ($request->input('worker_id_') as $key => $worker_id) {
                      $company_expense_worker_detail_json = [];
                      $all_task_json ['task']= [];
                      $each_task_arr = [];
                      $get_worker = Worker::find($worker_id);

                      if(isset($request->input('expense_id_')[$worker_id])){
                        foreach ($request->input('expense_id_')[$worker_id] as $ekey => $expense_id) {
                          $each_task_arr['qty'] = isset($request->input('qty_')[$worker_id][$expense_id]) == false ? 0 : $request->input('qty_')[$worker_id][$expense_id];
                          $each_task_arr['expense_id'] = $expense_id;
                          $each_task_arr['expense_total'] = isset($request->input('expense_total_')[$worker_id][$expense_id]) == false ? 0 : $request->input('expense_total_')[$worker_id][$expense_id];
                          $each_task_arr['expense_value'] = isset($request->input('expense_value_')[$worker_id][$expense_id]) == false ? 0 : $request->input('expense_value_')[$worker_id][$expense_id];
                          $each_task_arr['setting_expense_overwrite_commission'] = isset($request->input('setting_expense_commission_')[$worker_id][$expense_id]) == false ? 0 : $request->input('setting_expense_commission_')[$worker_id][$expense_id];

                          array_push($all_task_json['task'], $each_task_arr);
                        }
                      }

                      if($request->input('worker_status_')[$worker_id] != null && $request->input('worker_status_')[$worker_id] == 3){
                        $get_worker->update([
                          'worker_status_id' => 3,
                        ]);
                      }

                      $all_task_json['type'] = $request->input('worker_type_name_')[$worker_id];
                      $all_task_json['status'] = $request->input('worker_status_')[$worker_id] == null ? 0 : $request->input('worker_status_')[$worker_id];

                      $setting_time_slot = Setting::where('setting_slug', '=', 'worker_time_slots')->pluck('setting_value')->first();

                      if(isset($request->input('worker_timing_')[$worker_id]) == false){
                        $all_task_json['time'] = '';
                      }else{
                        foreach (json_decode($setting_time_slot) as $key => $worker_time_slots) {
                          if(preg_replace('/\s+/', '', $worker_time_slots->label) == $request->input('worker_timing_')[$worker_id]){
                            $all_task_json['timing'] = $worker_time_slots->value;
                          }
                        }
                      }

                      $company_expense_worker = CompanyExpenseWorker::create([
                        'worker_id' => $worker_id,
                        'company_expense_id' => $company_expense->company_expense_id,
                        'company_expense_worker_detail' => json_encode($all_task_json),
                        'company_expense_worker_total' => $request->input('worker_total_')[$worker_id] == null ? 0 : $request->input('worker_total_')[$worker_id],
                        'company_expense_worker_created' => now(),
                        'company_expense_worker_updated' => now(),
                      ]);
                    }

                    //testing calculation starts
                    //each expense_land
                    // $total_tree_from_lands = 0;
                    // $company_expense_land_2 = CompanyExpenseLand::where('company_expense_id', 20)->get();
                    // foreach ($company_expense_land_2 as $key => $company_expense_land_2_1) {
                    //     $total_tree_from_lands += $company_expense_land_2_1->company_land->company_land_total_tree;
                    // }
                    // //each expense_item
                    // $total_avg_price_per_tree = 0;
                    // $company_expense_item_2 = CompanyExpenseItem::where('company_expense_id', 20)->get();
                    // foreach ($company_expense_item_2 as $key => $each_item) {
                    //   $avg_price_per_tree = $each_item->company_expense_item_total / $total_tree_from_lands;
                    //   $total_avg_price_per_tree += $avg_price_per_tree;
                    //   $each_item->update([
                    //     'company_expense_item_average_price_per_tree' => $avg_price_per_tree,
                    //   ]);
                    // }
                    //
                    // foreach ($company_expense_land_2 as $key => $company_expense_land_2_2) {
                    //   $company_expense_land_total_price = $company_expense_land_2_2->company_land->company_land_total_tree * $total_avg_price_per_tree;
                    //   $company_expense_land_2_2->update([
                    //     'company_expense_land_total_tree' => $company_expense_land_2_2->company_land->company_land_total_tree,
                    //     'company_expense_land_total_price' => $company_expense_land_total_price,
                    //   ]);
                    // }
                    //testing calculation ends
                    $query = <<<GQL
                        mutation {
                            updateCompanyExpense(company_expense_id: $company_expense->company_expense_id)
                        }
                        GQL;
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post(env('GRAPHQL_API').'/graphql', [
                        'query' => $query
                    ]);
                    Session::flash('success_msg', 'Successfully Added');
                    return redirect()->route('company_expense_listing');
                }
            }
        }

        return view('company_expense.add_labour_form', [
            'submit' => route('company_expense_add_labour'),
            'title' => 'Add',
            'records' => $company_expense,
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'worker_status_sel' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            'manager_sel' => User::get_user_land_sel(),
            'time_slot' => ['' => 'Please Select Time Slot'] + Setting::get_time_slot(),
            'expense_type_sel' => ['' => 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'company_sel' => Company::get_company_sel(),
            'worker_role_cb' => WorkerRole::get_worker_role(),
        ])->withErrors($validator);

    }

    public function edit_labour(Request $request, $company_expense_id)
    {

        $company_expense = CompanyExpense::query()->where('company_expense_id', $company_expense_id)->first();
        $company_expense_worker = CompanyExpenseWorker::where('company_expense_id', $company_expense_id)->get();
        $validation = null;
        $user = auth()->user();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'expense_type' => 'required',
                'expense_category_id' => 'required',
                'expense_date' => 'required',
                'worker_id_'  => 'required',
            ])->setAttributeNames([
                'company_expense_number' => 'company_expense_number',
                'manager_id' => 'manager_id',
                'worker_type' => 'worker_type',
                'expense_type' => 'expense_type',
                'expense_category_id' => 'expense_category_id',
                'company_expense_total' => 'company_expense_total',
                'expense_date' => 'expense_date',
                'worker_id_'  => 'Worker',
                // 'grand_total_expense' => 'grand_total_expense',
            ]);

            if (!$validation->fails()) {
                //remark; to update staff costing
                $input = $request->input('expense_date');
                $date = strtotime($input);
                $farm_manager = $request->input('manager_id') ? User::find($request->input('manager_id')) : 0;
                $fm_worker_id = $farm_manager ? Worker::query()->where('user_id', $farm_manager->user_id)->where('worker_role_id', 2)->pluck('worker_id')->first() : 0;


                // dd($request->input());

                if(count($request->input('worker_id_')) > 0){

                    $company_expense->update([
                        'setting_expense_category_id' => $request->input('expense_category_id'),
                        // 'company_expense_number' => $request->input('company_expense_number'),
                        'user_id' => auth()->user()->user_id,
                        'worker_id' => $fm_worker_id ?? 0,
                        'worker_role_id' => $request->input('worker_role_id'),
                        'company_land_id' => 0,
                        'company_expense_type' => $request->input('expense_type'),
                        'company_expense_updated' => now(),
                        'company_expense_day' => date('d', $date),
                        'company_expense_month' => date('m', $date),
                        'company_expense_year' => date('Y', $date),
                        'company_expense_total' => $request->input('company_expense_total'),
                    ]);

                    CompanyExpenseLand::where('company_expense_id', $company_expense_id)->delete();

                    foreach ($request->input('company_land_id') as $key => $land_id) {
                      $company_expense_land = CompanyExpenseLand::create([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'company_land_id' => $land_id,
                          'company_expense_land_total_tree' => 0,
                          'company_expense_land_total_price' => 0
                      ]);
                    }
                    CompanyExpenseLog::insert([
                        'company_expense_id' => $company_expense->company_expense_id,
                        'company_expense_log_created' => now(),
                        'company_expense_log_description' => 'Expenses Edited By ' . $user->user_fullname,
                        'user_id' => Auth::id()
                    ]);

                    $company_expense_item_detail_json = [];
                    foreach ($request->input('expense_id_') as $worker_id_2 => $expense_by_worker) {
                      foreach ($expense_by_worker as $key => $expense_id_2) {
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['qty'] = isset($request->input('qty_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('qty_')[$worker_id_2][$expense_id_2];
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['time_slot'] = isset($request->input('worker_timing_')[$worker_id_2]) == false ? '' : $request->input('worker_timing_')[$worker_id_2];
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_name'] = null;
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_total'] = isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['expense_value'] = isset($request->input('expense_value_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_value_')[$worker_id_2][$expense_id_2];
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['detail']['setting_expense_overwrite_commission'] = isset($request->input('setting_expense_commission_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('setting_expense_commission_')[$worker_id_2][$expense_id_2];
                          $company_expense_item_detail_json[$expense_id_2][$worker_id_2]['worker_id'] = $worker_id_2;
                          if(isset($company_expense_item_detail_json[$expense_id_2]['exp_total'])){
                            $company_expense_item_detail_json[$expense_id_2]['exp_total'] += isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                          }else{
                            $company_expense_item_detail_json[$expense_id_2]['exp_total'] = isset($request->input('expense_total_')[$worker_id_2][$expense_id_2]) == false ? 0 : $request->input('expense_total_')[$worker_id_2][$expense_id_2];
                          }
                      }
                    }
                    // dd($company_expense_item_detail_json, $request->input('expense_id_'));
                    $company_expense_item_expense_id_arr_existing = CompanyExpenseItem::where('company_expense_id', $company_expense_id)->pluck('setting_expense_id')->toArray();
                    $company_expense_item_expense_id_arr_input = [];

                    foreach ($company_expense_item_detail_json as $id => $data) {
                      array_push($company_expense_item_expense_id_arr_input, $id);
                    }

                    $removed_items = array_diff($company_expense_item_expense_id_arr_existing, $company_expense_item_expense_id_arr_input);

                    foreach ($removed_items as $key => $setting_expense_id_delete) {
                      CompanyExpenseItem::where('company_expense_id', $company_expense_id)->where('setting_expense_id', $setting_expense_id_delete)->delete();
                    }

                    foreach ($company_expense_item_detail_json as $expense_id_3 => $details) {
                      $company_expense_item = CompanyExpenseItem::where('company_expense_id', $company_expense_id)->where('setting_expense_id', $expense_id_3)->first();
                      if($company_expense_item){
                        $company_expense_item->update([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'setting_expense_id' => $expense_id_3,
                          'company_expense_item_detail' => json_encode($details),
                          'company_expense_item_total' => $details['exp_total'],
                          'company_expense_item_updated' => now(),
                        ]);
                      }else{
                        CompanyExpenseItem::create([
                          'company_expense_id' => $company_expense->company_expense_id,
                          'setting_expense_id' => $expense_id_3,
                          'company_expense_item_detail' => json_encode($details),
                          'company_expense_item_total' => $details['exp_total'],
                          'company_expense_item_created' => now(),
                          'company_expense_item_updated' => now(),
                        ]);
                      }
                    }

                    foreach ($request->input('worker_id_') as $key => $worker_id) {
                      $company_expense_worker_detail_json = [];
                      $all_task_json ['task']= [];
                      $each_task_arr = [];

                      if(isset($request->input('expense_id_')[$worker_id])){
                        foreach ($request->input('expense_id_')[$worker_id] as $ekey => $expense_id) {
                          $each_task_arr['qty'] = isset($request->input('qty_')[$worker_id][$expense_id]) == false ? 0 : $request->input('qty_')[$worker_id][$expense_id];
                          $each_task_arr['expense_id'] = $expense_id;
                          $each_task_arr['expense_total'] = isset($request->input('expense_total_')[$worker_id][$expense_id]) == false ? 0 : $request->input('expense_total_')[$worker_id][$expense_id];
                          $each_task_arr['expense_value'] = isset($request->input('expense_value_')[$worker_id][$expense_id]) == false ? 0 : $request->input('expense_value_')[$worker_id][$expense_id];
                          $each_task_arr['setting_expense_overwrite_commission'] = isset($request->input('setting_expense_commission_')[$worker_id][$expense_id]) == false ? 0 : $request->input('setting_expense_commission_')[$worker_id][$expense_id];

                          array_push($all_task_json['task'], $each_task_arr);
                        }
                      }

                      $all_task_json['type'] = $request->input('worker_type_name_')[$worker_id];
                      $all_task_json['status'] = $request->input('worker_status_')[$worker_id] == null ? 0 : $request->input('worker_status_')[$worker_id];

                      $setting_time_slot = Setting::where('setting_slug', '=', 'worker_time_slots')->pluck('setting_value')->first();

                      if(isset($request->input('worker_timing_')[$worker_id]) == false){
                        $all_task_json['time'] = '';
                      }else{
                        foreach (json_decode($setting_time_slot) as $key => $worker_time_slots) {
                          if(preg_replace('/\s+/', '', $worker_time_slots->label) == $request->input('worker_timing_')[$worker_id]){
                            $all_task_json['timing'] = $worker_time_slots->value;
                          }
                        }
                      }

                      $find_each_company_expense_worker = CompanyExpenseWorker::where('company_expense_id', $company_expense_id)->where('worker_id', $worker_id)->first();
                      if($find_each_company_expense_worker){
                        $find_each_company_expense_worker->update([
                          'worker_id' => $worker_id,
                          'company_expense_id' => $company_expense->company_expense_id,
                          'company_expense_worker_detail' => json_encode($all_task_json),
                          'company_expense_worker_total' => $request->input('worker_total_')[$worker_id] == null ? 0 : $request->input('worker_total_')[$worker_id],
                          'company_expense_worker_updated' => now(),
                        ]);
                      }else{
                        CompanyExpenseWorker::create([
                          'worker_id' => $worker_id,
                          'company_expense_id' => $company_expense->company_expense_id,
                          'company_expense_worker_detail' => json_encode($all_task_json),
                          'company_expense_worker_total' => $request->input('worker_total_')[$worker_id] == null ? 0 : $request->input('worker_total_')[$worker_id],
                          'company_expense_worker_created' => now(),
                          'company_expense_worker_updated' => now(),
                        ]);
                      }
                    }

                    $query = <<<GQL
                        mutation {
                            updateCompanyExpense(company_expense_id: $company_expense_id)
                        }
                        GQL;
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post(env('GRAPHQL_API').'/graphql', [
                        'query' => $query
                    ]);

                    Session::flash('success_msg', 'Successfully Updated');
                    return redirect()->route('company_expense_listing');
            }
            $company_expense = (object) $request->all();

        }
      }

        return view('company_expense.add_labour_form', [
            'submit' => route('company_expense_edit_labour', $company_expense_id),
            'title' => 'Edit',
            'records'=> $company_expense,
            'records_worker'=>$company_expense_worker,
            'manager_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_land_sel(),
            'worker_status_sel' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            'worker_status_sel2' => ['' => 'Please Select Worker Status'] + WorkerStatus::get_sel_worker_status(),
            'expense_category_sel' => SettingExpenseCategory::get_existing_expense_category_sel(),
            'expense_sel' => SettingExpense::get_expense_sel(),
            'company_land_sel' => CompanyLand::get_company_land_sel(),
            'user_sel' => ['' => 'Please Select Farm Manager'] + User::get_user_sel(),
            'time_slot' => ['' => 'Please Select Time Slot'] +  Setting::get_time_slot(),
            'expense_type_sel' => ['' => 'Please Select Expense Type', 'daily' => 'Daily', 'monthly' => 'Monthly'],
            'company_sel' => Company::get_company_sel(),
            'worker_role_cb' => WorkerRole::get_worker_role(),
        ])->withErrors($validation);
    }

    public function sync_cost(Request $request){
      $company_id = $request->input('company_id');
      if(!$company_id){
        Session::flash('failed_msg', 'Company ID is required!');
        return redirect()->route('company_listing');
      }else{
        $company_expense = CompanyExpense::where('company_id', $company_id)->get();
        if($company_expense){
          foreach ($company_expense as $key => $value) {
            $query = <<<GQL
                mutation {
                    updateCompanyExpense(company_expense_id: $value->company_expense_id)
                }
                GQL;
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env('GRAPHQL_API').'/graphql', [
                'query' => $query
            ]);
          }
          Session::flash('success_msg', 'Successfully Updated Cost!');
          return redirect()->route('company_listing');
        }
      }
    }

    public function delete(Request $request)
    {
        $company_expense = CompanyExpense::find($request->input('company_expense_id'));
        $company_expense_id = $request->input('company_expense_id');

        if(!$company_expense){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('company_expense_listing');
        }

       $company_expense->update([
           'company_expense_status' => 'deleted',
       ]);

       CompanyExpenseLog::insert([
            'company_expense_id' => $company_expense_id,
            'company_expense_log_created' => now(),
            'company_expense_log_description' => 'Delete Company Expense',
            'user_id' => Auth::id()
        ]);

        Session::flash('success_msg', "Successfully deleted Company Expense.");
        return redirect()->route('company_expense_listing');
    }

    public function ajax_get_image_by_ce_item_id(Request $request)
    {
        $items = null;
        $company_expense_item_id = $request->input('ce_item_id');
        $items = CompanyExpenseItem::get_ce_item_by_ce_item_id($company_expense_item_id);

        return response()->json([
            'items' => $items,
        ]);
    }

    public function ajax_delete_image_by_media_item_id(Request $request)
    {
        $company_expense_item_id = $request->input('company_expense_item_id');
        $media_id = $request->input('media_id');
        Media::query()
            ->where('id', $media_id)
            ->where('model_id',$company_expense_item_id)
            ->where('collection_name', 'company_expense_item_media')
            ->delete();
    }

}
