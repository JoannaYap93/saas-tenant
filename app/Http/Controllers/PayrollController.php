<?php

    namespace App\Http\Controllers;

    use App\Exports\PayrollExport;
    use App\Model\Company;
    use App\Model\CompanyExpenseWorker;
    use App\Model\Payroll;
    use App\Model\PayrollItem;
    use App\Model\PayrollLog;
    use App\Model\PayrollUser;
    use App\Model\PayrollUserItem;
    use App\Model\PayrollUserReward;
    use App\Model\Worker;
    use App\Model\WorkerRole;
    use App\Model\SettingExpense;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Validator;
    use Maatwebsite\Excel\Facades\Excel;
    class PayrollController extends Controller
    {
        public function __construct()
        {
            $this->middleware(['auth']);
        }

        public function listing(Request $request)
        {
            if ($request->isMethod('post')) {
                $submit_type = $request->input('submit');
                switch ($submit_type) {
                    case 'search':
                        session(['payroll_search' => [
                            'freetext' => $request->input('freetext'),
                            'company_id' => $request->input('company_id'),
                            'payroll_year' => $request->input('payroll_year'),
                            'payroll_month' => $request->input('payroll_month'),
                        ]]);
                        break;
                    case 'reset':
                        session()->forget('payroll_search');
                        break;
                }
            }

            $search = session('payroll_search') ?? array();

            return view('payroll.listing', [
                'submit' => route('payroll_listing', ['tenant' => tenant('id')]),
                'search' => $search,
                'records' => Payroll::get_records($search),
                'company_sel' => Company::get_company_sel(),
                'payroll_details' => Payroll::get_payroll_details(1),
            ]);
        }

        public function generate(Request $request)
        {
            $validator = null;

            if ($request->isMethod('post')) {
                if(!is_null($request->input('company_id')) && !is_null($request->input('payroll_date'))){
                    $company_id = $request->input('company_id');
                    $payroll_date = strtotime($request->input('payroll_date'));
                    $payroll_month = date('m', $payroll_date);
                    $payroll_year = date('Y', $payroll_date);

                    $payroll = Payroll::query()
                                        ->where('company_id', $company_id)
                                        ->where('payroll_month', $payroll_month)
                                        ->where('payroll_year', $payroll_year)
                                        ->where('payroll_status', '<>', 'deleted')
                                        ->first();

                    if(is_null($payroll)){
                        $payroll = Payroll::create([
                            'company_id' => $company_id,
                            'payroll_month' => $payroll_month,
                            'payroll_year' => $payroll_year,
                            'payroll_status' => 'Pending',
                        ]);

                        return redirect()->route('payroll_add', ['tenant' => tenant('id'), 'id' => $payroll->payroll_id]);

                    }else{
                        Session::flash('fail_msg', 'Payroll exists for the selected company and month.');
                        return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
                    }
                }

                Session::flash('fail_msg', 'Please select a company and month to continue.');
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }
        }

        public function add(Request $request, $payroll_id)
        {
            $validator = null;
            $payroll = Payroll::find($payroll_id);

            if(is_null($payroll)){
                Session::flash('fail_msg', 'Invalid payroll. Please try again.');
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }

            $search['company_id'] = $payroll->company_id;
            $search['payroll_month'] = $payroll->payroll_month;
            $search['payroll_year'] = $payroll->payroll_year;

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'worker_id.*' => 'required',
                    'payroll_user_amount.*' => 'required',
                    'payroll_user_reward.*' => 'required',
                ])->setAttributeNames([
                    "worker_id" => "Worker",
                    "payroll_user_amount" => "Salary",
                    "payroll_user_reward" => "Reward",
                ]);

                if(!$validator->fails()){
                    $payroll_total_amount = 0;
                    $payroll_total_reward = 0;
                    $payroll_grandtotal = 0;
                    $payroll_total_user_item_employee = 0;
                    $payroll_total_user_item_employer = 0;
                    $payroll_total_paid_out = 0;


                    foreach($request->input('worker_id') as $key => $worker_id)
                    {
                        $worker = Worker::find($worker_id);

                        if($worker)
                        {
                            $payroll_user_amount = 0;
                            $payroll_user_reward_total = 0;
                            $payroll_user_item_employee = 0;
                            $payroll_user_item_type_add_employee = 0;
                            $payroll_user_item_type_deduct_employee = 0;
                            $payroll_user_item_employer = 0;
                            $payroll_user_item_type_add_employer = 0;
                            $payroll_user_item_type_deduct_employer = 0;

                            $payroll_user = PayrollUser::create([
                                'payroll_id' => $payroll->payroll_id,
                                'worker_id' => $worker_id,
                                'payroll_user_amount' => $request->input('payroll_user_amount')[$key],
                                'payroll_user_reward' => $request->input('payroll_user_reward')[$key] ?? 0,
                            ]);

                            $payroll_user_amount += $request->input('payroll_user_amount')[$key];
                            $payroll_user_reward_total += $request->input('payroll_user_reward')[$key] ?? 0;

                            if($request->input('payroll_user_reward')[$key] > 0){
                                $setting_reward_id = $request->input('setting_reward_id')[$worker_id];
                                foreach($request->input('payroll_user_reward_amount')[$worker_id] as $tier => $reward_amount){
                                    $payroll_user_reward = PayrollUserReward::create([
                                        'payroll_user_id' => $payroll_user->payroll_user_id,
                                        'payroll_id' => $payroll->payroll_id,
                                        'setting_reward_id' => $setting_reward_id,
                                        'setting_reward_tier' => $tier,
                                        'payroll_user_reward_amount' => $reward_amount,
                                    ]);
                                }
                            }

                            if(isset($request->input('payroll_user_item_amount')[$worker_id])){
                                foreach($request->input('payroll_user_item_amount')[$worker_id] as $payroll_item_id => $payroll_user_item){
                                    $payroll_item = PayrollItem::find($payroll_item_id);
                                    if($payroll_item){
                                        foreach($payroll_user_item as $payroll_item_type => $payroll_user_item_amount){
                                            $payroll_user_item = PayrollUserItem::create([
                                                'payroll_item_id' => $payroll_item_id,
                                                'payroll_user_id' => $payroll_user->payroll_user_id,
                                                'payroll_item_type' => $payroll_item_type,
                                                'payroll_user_item_amount' => $payroll_user_item_amount,
                                            ]);

                                            if($payroll_item_type == "employee"){
                                                $payroll_user_item_employee += $payroll_user_item_amount;

                                                // if($payroll_item->payroll_item_type == "Add"){
                                                //     $payroll_user_item_type_add_employee += $payroll_user_item_amount;
                                                // }else if($payroll_item->payroll_item_type == "Deduct"){
                                                //     $payroll_user_item_type_deduct_employee += $payroll_user_item_amount;
                                                // }
                                            }else if ($payroll_item_type == "employer"){
                                                if($payroll_item->payroll_item_type == "Add"){
                                                    $payroll_user_item_type_add_employer += $payroll_user_item_amount;
                                                }
                                                // else if($payroll_item->payroll_item_type == "Deduct"){
                                                //     $payroll_user_item_type_deduct_employer += $payroll_user_item_amount;
                                                // }
                                                $payroll_user_item_employer += $payroll_user_item_amount;
                                            }
                                        }
                                    }
                                }
                            }

                            $payroll_user->update([
                                'payroll_user_item_employee' => $payroll_user_item_employee,
                                'payroll_user_item_employer' => $payroll_user_item_employer,
                                'payroll_user_paid_out' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total - $payroll_user_item_employee + $payroll_user_item_type_add_employer,
                                'payroll_user_total' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total + $payroll_user_item_employer,
                            ]);

                            $payroll_total_amount += $payroll_user_amount;
                            $payroll_total_reward += $payroll_user_reward_total;
                            $payroll_grandtotal += $payroll_user->payroll_user_total;
                            $payroll_total_user_item_employee += $payroll_user_item_employee;
                            $payroll_total_user_item_employer += $payroll_user_item_employer;
                            $payroll_total_paid_out += $payroll_user->payroll_user_paid_out;
                        }
                    }

                    $payroll->update([
                        'payroll_total_amount' => $payroll_total_amount,
                        'payroll_total_reward' => $payroll_total_reward,
                        'payroll_grandtotal' => $payroll_grandtotal,
                        'payroll_total_user_item_employee' => $payroll_total_user_item_employee,
                        'payroll_total_user_item_employer' => $payroll_total_user_item_employer,
                        'payroll_total_paid_out' => $payroll_total_paid_out,
                        'payroll_status' => "In Progress",
                    ]);

                    $payroll_log = PayrollLog::create([
                        'payroll_id' => $payroll->payroll_id,
                        'payroll_log_action' => "Add",
                        'payroll_log_description' => "Payroll added by " . auth()->user()->user_fullname,
                        'payroll_log_remark' => "Payroll added by " . auth()->user()->user_fullname,
                        'payroll_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);

                    Session::flash('success_msg', 'Successfully Added Payroll');
                    return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
                }

                $payroll = (object) $request->all();
            }

            return view('payroll.form', [
                'title' => "Add",
                'submit' => route('payroll_add', ['tenant' => tenant('id'), 'id' => $payroll_id]),
                'payroll' => $payroll,
                'company_sel' => Company::get_company_sel(),
                'company_expense_worker_list' => CompanyExpenseWorker::get_company_expense_worker_by_company($search),
                'setting_expense' => SettingExpense::get_setting_expense_for_company_pnl(),
                'worker_role_list' => WorkerRole::all(),
                'deduct_payroll_items_sel' => PayrollItem::get_payroll_items(['payroll_item_type' => 'deduct']),
                'add_payroll_items_sel' => PayrollItem::get_payroll_items(['payroll_item_type' => 'add']),
            ])->withErrors($validator);
        }

        public function edit(Request $request, $payroll_id)
        {
            $validator = null;
            $payroll = Payroll::find($payroll_id);

            if(is_null($payroll)){
                Session::flash('fail_msg', 'Invalid payroll. Please try again.');
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }

            $search['company_id'] = $payroll->company_id;
            $search['payroll_month'] = $payroll->payroll_month;
            $search['payroll_year'] = $payroll->payroll_year;

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'worker_id.*' => 'required',
                    'payroll_user_amount.*' => 'required',
                    'payroll_user_reward.*' => 'required',
                ])->setAttributeNames([
                    "worker_id" => "Worker",
                    "payroll_user_amount" => "Salary",
                    "payroll_user_reward" => "Reward",
                ]);

                if(!$validator->fails()){
                    $payroll_total_amount = 0;
                    $payroll_total_reward = 0;
                    $payroll_grandtotal = 0;
                    $payroll_total_user_item_employee = 0;
                    $payroll_total_user_item_employer = 0;
                    $payroll_total_paid_out = 0;

                    foreach($request->input('worker_id') as $key => $worker_id)
                    {
                        $payroll_user_amount = 0;
                        $payroll_user_reward_total = 0;
                        $payroll_user_item_employee = 0;
                        $payroll_user_item_type_add_employee = 0;
                        $payroll_user_item_type_deduct_employee = 0;
                        $payroll_user_item_employer = 0;
                        $payroll_user_item_type_add_employer = 0;
                        $payroll_user_item_type_deduct_employer = 0;

                        $payroll_user = PayrollUser::query()
                                        ->where('payroll_id', $payroll_id)
                                        ->where('worker_id', $worker_id)
                                        ->first();

                        if($payroll_user)
                        {
                            $payroll_user_amount += $request->input('payroll_user_amount')[$key];
                            $payroll_user_reward_total += $request->input('payroll_user_reward')[$key] ?? 0;

                            $payroll_user->update([
                                'payroll_user_amount' => $request->input('payroll_user_amount')[$key],
                                'payroll_user_reward' => $request->input('payroll_user_reward')[$key] ?? 0,
                            ]);

                            if($request->input('payroll_user_reward')[$key] > 0)
                            {
                                PayrollUserReward::query()->where('payroll_user_id', $payroll_user->payroll_user_id)
                                                            ->where('payroll_id', $payroll_id)
                                                            ->delete();

                                $setting_reward_id = $request->input('setting_reward_id')[$worker_id];
                                foreach($request->input('payroll_user_reward_amount')[$worker_id] as $tier => $reward_amount)
                                {
                                    $payroll_user_reward = PayrollUserReward::create([
                                        'payroll_user_id' => $payroll_user->payroll_user_id,
                                        'payroll_id' => $payroll->payroll_id,
                                        'setting_reward_id' => $setting_reward_id,
                                        'setting_reward_tier' => $tier,
                                        'payroll_user_reward_amount' => $reward_amount,
                                    ]);
                                }
                            }

                            if(isset($request->input('payroll_user_item_amount')[$worker_id]))
                            {
                                PayrollUserItem::query()->where('payroll_user_id', $payroll_user->payroll_user_id)->delete();
                                foreach($request->input('payroll_user_item_amount')[$worker_id] as $payroll_item_id => $payroll_user_item){
                                    $payroll_item = PayrollItem::find($payroll_item_id);
                                    if($payroll_item){
                                        foreach($payroll_user_item as $payroll_item_type => $payroll_user_item_amount){
                                            $payroll_user_item = PayrollUserItem::create([
                                                'payroll_item_id' => $payroll_item_id,
                                                'payroll_user_id' => $payroll_user->payroll_user_id,
                                                'payroll_item_type' => $payroll_item_type,
                                                'payroll_user_item_amount' => $payroll_user_item_amount,
                                            ]);

                                            if($payroll_item_type == "employee"){
                                                $payroll_user_item_employee += $payroll_user_item_amount;

                                                // if($payroll_item->payroll_item_type == "Add"){
                                                //     $payroll_user_item_type_add_employee += $payroll_user_item_amount;
                                                // }else if($payroll_item->payroll_item_type == "Deduct"){
                                                //     $payroll_user_item_type_deduct_employee += $payroll_user_item_amount;
                                                // }
                                            }else if ($payroll_item_type == "employer"){
                                                if($payroll_item->payroll_item_type == "Add"){
                                                    $payroll_user_item_type_add_employer += $payroll_user_item_amount;
                                                }
                                                // else if($payroll_item->payroll_item_type == "Deduct"){
                                                //     $payroll_user_item_type_deduct_employer += $payroll_user_item_amount;
                                                // }
                                                $payroll_user_item_employer += $payroll_user_item_amount;
                                            }
                                        }
                                    }
                                }
                            }

                            $payroll_user->update([
                                'payroll_user_item_employee' => $payroll_user_item_employee,
                                'payroll_user_item_employer' => $payroll_user_item_employer,
                                'payroll_user_paid_out' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total - $payroll_user_item_employee + $payroll_user_item_type_add_employer,
                                'payroll_user_total' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total + $payroll_user_item_employer,
                            ]);

                            $payroll_total_amount += $payroll_user_amount;
                            $payroll_total_reward += $payroll_user_reward_total;
                            $payroll_grandtotal += $payroll_user->payroll_user_total;
                            $payroll_total_user_item_employee += $payroll_user_item_employee;
                            $payroll_total_user_item_employer += $payroll_user_item_employer;
                            $payroll_total_paid_out += $payroll_user->payroll_user_paid_out;
                        } else {
                            foreach($request->input('worker_id') as $key => $worker_id)
                            {
                                $worker = Worker::find($worker_id);

                                if($worker)
                                {
                                    $payroll_user_amount = 0;
                                    $payroll_user_reward_total = 0;
                                    $payroll_user_item_employee = 0;
                                    $payroll_user_item_type_add_employee = 0;
                                    $payroll_user_item_type_deduct_employee = 0;
                                    $payroll_user_item_employer = 0;
                                    $payroll_user_item_type_add_employer = 0;
                                    $payroll_user_item_type_deduct_employer = 0;

                                    $payroll_user = PayrollUser::create([
                                        'payroll_id' => $payroll->payroll_id,
                                        'worker_id' => $worker_id,
                                        'payroll_user_amount' => $request->input('payroll_user_amount')[$key],
                                        'payroll_user_reward' => $request->input('payroll_user_reward')[$key] ?? 0,
                                    ]);

                                    $payroll_user_amount += $request->input('payroll_user_amount')[$key];
                                    $payroll_user_reward_total += $request->input('payroll_user_reward')[$key] ?? 0;

                                    if($request->input('payroll_user_reward')[$key] > 0){
                                        $setting_reward_id = $request->input('setting_reward_id')[$worker_id];
                                        foreach($request->input('payroll_user_reward_amount')[$worker_id] as $tier => $reward_amount){
                                            $payroll_user_reward = PayrollUserReward::create([
                                                'payroll_user_id' => $payroll_user->payroll_user_id,
                                                'payroll_id' => $payroll->payroll_id,
                                                'setting_reward_id' => $setting_reward_id,
                                                'setting_reward_tier' => $tier,
                                                'payroll_user_reward_amount' => $reward_amount,
                                            ]);
                                        }
                                    }

                                    if(isset($request->input('payroll_user_item_amount')[$worker_id])){
                                        foreach($request->input('payroll_user_item_amount')[$worker_id] as $payroll_item_id => $payroll_user_item){
                                            $payroll_item = PayrollItem::find($payroll_item_id);
                                            if($payroll_item){
                                                foreach($payroll_user_item as $payroll_item_type => $payroll_user_item_amount){
                                                    $payroll_user_item = PayrollUserItem::create([
                                                        'payroll_item_id' => $payroll_item_id,
                                                        'payroll_user_id' => $payroll_user->payroll_user_id,
                                                        'payroll_item_type' => $payroll_item_type,
                                                        'payroll_user_item_amount' => $payroll_user_item_amount,
                                                    ]);

                                                    if($payroll_item_type == "employee"){
                                                        $payroll_user_item_employee += $payroll_user_item_amount;
                                                    }else if ($payroll_item_type == "employer"){
                                                        if($payroll_item->payroll_item_type == "Add"){
                                                            $payroll_user_item_type_add_employer += $payroll_user_item_amount;
                                                        }
                                                        $payroll_user_item_employer += $payroll_user_item_amount;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $payroll_user->update([
                                        'payroll_user_item_employee' => $payroll_user_item_employee,
                                        'payroll_user_item_employer' => $payroll_user_item_employer,
                                        'payroll_user_paid_out' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total - $payroll_user_item_employee + $payroll_user_item_type_add_employer,
                                        'payroll_user_total' => $request->input('payroll_user_amount')[$key] + $payroll_user_reward_total + $payroll_user_item_employer,
                                    ]);

                                    $payroll_total_amount += $payroll_user_amount;
                                    $payroll_total_reward += $payroll_user_reward_total;
                                    $payroll_grandtotal += $payroll_user->payroll_user_total;
                                    $payroll_total_user_item_employee += $payroll_user_item_employee;
                                    $payroll_total_user_item_employer += $payroll_user_item_employer;
                                    $payroll_total_paid_out += $payroll_user->payroll_user_paid_out;
                                }
                            }
                        }
                    }

                    $payroll->update([
                        'payroll_total_amount' => $payroll_total_amount,
                        'payroll_total_reward' => $payroll_total_reward,
                        'payroll_grandtotal' => $payroll_grandtotal,
                        'payroll_total_user_item_employee' => $payroll_total_user_item_employee,
                        'payroll_total_user_item_employer' => $payroll_total_user_item_employer,
                        'payroll_total_paid_out' => $payroll_total_paid_out,
                        'payroll_status' => "In Progress",
                    ]);

                    $payroll_log = PayrollLog::create([
                        'payroll_id' => $payroll->payroll_id,
                        'payroll_log_action' => "Update",
                        'payroll_log_description' => "Payroll updated by " . auth()->user()->user_fullname,
                        'payroll_log_remark' => "Payroll updated by " . auth()->user()->user_fullname,
                        'payroll_log_created' => now(),
                        'user_id' => auth()->user()->user_id,
                    ]);

                    Session::flash('success_msg', 'Successfully Updated Payroll');
                    return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
                }
            }

            return view('payroll.form', [
                'title' => "Edit",
                'submit' => route('payroll_edit', ['tenant' => tenant('id'), 'id' => $payroll_id]),
                'payroll' => $payroll,
                'company_sel' => Company::get_company_sel(),
                'company_expense_worker_list' => CompanyExpenseWorker::get_company_expense_worker_by_company($search),
                'setting_expense' => SettingExpense::get_setting_expense_for_company_pnl(),
                'worker_role_list' => WorkerRole::all(),
                'deduct_payroll_items_sel' => PayrollItem::get_payroll_items(['payroll_item_type' => 'deduct']),
                'add_payroll_items_sel' => PayrollItem::get_payroll_items(['payroll_item_type' => 'add']),
            ])->withErrors($validator);
        }

        public function view_details($payroll_id)
        {
            $payroll = Payroll::find($payroll_id);

            if (!$payroll) {
                Session::flash("fail_msg", "Invalid payroll! Please try again.");
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }

            $search['company_id'] = $payroll->company_id;
            $search['payroll_month'] = $payroll->payroll_month;
            $search['payroll_year'] = $payroll->payroll_year;

            return view('payroll.view_details', [
                'title' => 'View',
                'submit' => route('payroll_view', ['tenant' => tenant('id'), 'id' => $payroll_id]),
                'payroll' => $payroll,
                'payroll_details' => Payroll::get_payroll_details($payroll_id),
                'worker_role_list' => WorkerRole::all(),
                'payroll_items_type_deduct' => PayrollItem::get_payroll_items(['payroll_item_type' => 'deduct']),
                'payroll_items_type_add' => PayrollItem::get_payroll_items(['payroll_item_type' => 'add']),
                'ot_value' => SettingExpense::find(29),
                'worker' => CompanyExpenseWorker::get_company_expense_worker_detail($search),
                'setting_expense' => SettingExpense::get_setting_expense_for_company_pnl(),
            ]);
        }

        public function status(Request $request)
        {
            $action = $request->input('action');
            $payroll_id = $request->input('payroll_id');

            $payroll = Payroll::find($payroll_id);

            if (!$payroll) {
                Session::flash("fail_msg", "Invalid payroll! Please try again.");
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }

            switch($action){
                case 'delete':
                    $payroll_log_remark = $request->input('payroll_log_remark');

                    if (!$payroll_log_remark) {
                        Session::flash("fail_msg", "Remark field is required");
                        return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
                    }
                    $payroll->update([
                        'payroll_status' => 'Deleted',
                    ]);
                break;
                case 'complete':
                    $payroll->update([
                        'payroll_status' => 'Completed',
                    ]);
                break;
            }

            $payroll_log = PayrollLog::create([
                'payroll_id' => $payroll->payroll_id,
                'payroll_log_action' => $action,
                'payroll_log_description' => "Payroll " . $action . "d by " . auth()->user()->user_fullname,
                'payroll_log_remark' => $payroll_log_remark ?? "",
                'payroll_log_created' => now(),
                'user_id' => auth()->user()->user_id,
            ]);

            Session::flash('success_msg', 'Successfully ' . $action . 'd payroll');
            return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
        }

        public function export($payroll_id)
        {
            $payroll = Payroll::find($payroll_id);

            if (!$payroll) {
                Session::flash("fail_msg", "Invalid payroll! Please try again.");
                return redirect()->route('payroll_listing', ['tenant' => tenant('id')]);
            }

            $search['company_id'] = $payroll->company_id;
            $search['payroll_month'] = $payroll->payroll_month;
            $search['payroll_year'] = $payroll->payroll_year;

            $payroll_details = Payroll::get_payroll_details($payroll->payroll_id);
            return Excel::download(new PayrollExport('export/payroll', $payroll, $payroll_details, $search), 'payroll.xlsx');
        }

        public static function ajax_check_payroll_exists(Request $request)
        {
            $company_id = $request->input('company_id');
            $payroll_date = strtotime($request->input('payroll_date'));
            $payroll_month = date('m', $payroll_date);
            $payroll_year = date('Y', $payroll_date);
            $result = Payroll::check_payroll_by_company($company_id, $payroll_month, $payroll_year);
            return response()->json(['data' => $result]);
        }

        public static function ajax_get_payroll_item(Request $request)
        {
            $payroll_item_id = $request->input('payroll_item_id');
            $result = PayrollItem::find($payroll_item_id);
            return response()->json(['data' => $result]);
        }
    }
?>
