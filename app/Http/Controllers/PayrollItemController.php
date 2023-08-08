<?php

    namespace App\Http\Controllers;

    use App\Model\PayrollItem;
    use App\Model\PayrollItemWorkerRole;
    use App\Model\SettingExpense;
    use App\Model\UserGroup;
    use App\Model\WorkerRole;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Validator;

    class PayrollItemController extends Controller
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
                        session(['payroll_item_search' => [
                            'freetext' => $request->input('freetext'),
                            'payroll_item_status' => $request->input('payroll_item_status'),
                            'worker_role_id' => $request->input('worker_role_id'),
                        ]]);
                        break;
                    case 'reset':
                        session()->forget('payroll_item_search');
                        break;
                }
            }

            $search = session('payroll_item_search') ?? array();

            return view('payroll_item.listing', [
                'submit' => route('payroll_item_listing'),
                'records' => PayrollItem::get_records($search),
                'search' => $search,
                'payroll_item_status_sel' => ['' => 'Please Select Status'] + PayrollItem::get_enum_sel('payroll_item_status'),
                'payroll_item_type_sel' => ['' => 'Please Select Type'] + PayrollItem::get_enum_sel('payroll_item_type'),
                'worker_role_sel' => WorkerRole::worker_role_sel(),
            ]);
        }

        public function add(Request $request)
        {
            $payroll_item = null;
            $validator = null;

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'payroll_item_name' => 'required',
                    'payroll_item_type' => 'required',
                ])->setAttributeNames([
                    'payroll_item_name' => "Monthly Worker Expense Item Name",
                    'payroll_item_type' => "Monthly Worker Expense Type",
                ]);

                if($request->input('is_compulsory')){
                    if(is_null($request->input('worker_role_id'))){
                        $validator->after(function ($validator) {
                            $validator->getMessageBag()->add('worker_role_id', 'Please select payroll item is compulsory for which worker role.');
                        });
                    }
                }

                if (!$validator->fails()) {
                    $payroll_item = PayrollItem::create([
                        'payroll_item_name' => $request->input('payroll_item_name'),
                        'payroll_item_status' => ($request->input('payroll_item_status') == 1 ? 'Available' : 'Unavailable'),
                        'payroll_item_type' => $request->input('payroll_item_type'),
                        'is_compulsory' => $request->input('is_compulsory') ?? 0,
                        'is_employer' => $request->input('is_employer') ?? 0,
                        'setting_expense_id' => $request->input('setting_expense_id') ?? 0,
                    ]);

                    foreach($request->input('worker_role_id') as $worker_role_id){
                        $payroll_item_worker_role = PayrollItemWorkerRole::create([
                            'payroll_item_id' => $payroll_item->payroll_item_id,
                            'worker_role_id' => $worker_role_id,
                        ]);

                    }
                    Session::flash('success_msg', 'Successfully added payroll item ' . $request->input('payroll_item_name')).'.';
                    return redirect()->route('payroll_item_listing');
                }
                $payroll_item = (object) $request->all();
            }

            return view('payroll_item.form', [
                'title' => "Add",
                'submit' => route('payroll_item_add'),
                'payroll_item' => $payroll_item,
                'setting_expense_sel' =>SettingExpense::get_item_expense_payroll_item(),
                'worker_roles' => WorkerRole::get(),
                'payroll_item_type_sel' => ['' => 'Please Select Type'] + PayrollItem::get_enum_sel('payroll_item_type'),
            ])->withErrors($validator);
        }

        public function edit(Request $request, $payroll_item_id)
        {
            $payroll_item = PayrollItem::find($payroll_item_id);
            $validator = null;

            if($payroll_item == null){
                Session::flash('fail_msg', 'Invalid Payroll Item, Please Try Again.');
                return redirect()->route('payroll_item_listing');
            }

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'payroll_item_name' => 'required',
                    'payroll_item_type' => 'required',
                ])->setAttributeNames([
                    'payroll_item_name' => "Monthly Worker Expense Item Name",
                    'payroll_item_type' => "Monthly Worker Expense Type",
                ]);

                if($request->input('is_compulsory')){
                    if(is_null($request->input('worker_role_id'))){
                        $validator->after(function ($validator) {
                            $validator->getMessageBag()->add('worker_role_id', 'Please select payroll item is compulsory for which worker role.');
                        });
                    }
                }

                if (!$validator->fails()) {

                    $payroll_item->update([
                        'payroll_item_name' => $request->input('payroll_item_name'),
                        'payroll_item_status' => ($request->input('payroll_item_status') == 1 ? 'Available' : 'Unavailable'),
                        'payroll_item_type' => $request->input('payroll_item_type'),
                        'is_compulsory' => $request->input('is_compulsory') ?? 0,
                        'is_employer' => $request->input('is_employer') ?? 0,
                        'setting_expense_id' => $request->input('setting_expense_id') ?? 0,
                    ]);

                    if (!is_null($request->input('worker_role_id'))) {
                        PayrollItemWorkerRole::where('payroll_item_id', $payroll_item_id)->delete();
                        foreach ($request->input('worker_role_id') as $worker_role_id) {
                            PayrollItemWorkerRole::create([
                                'payroll_item_id' => $payroll_item_id,
                                'worker_role_id' => $worker_role_id,
                            ]);
                        }
                    } else {
                        PayrollItemWorkerRole::where('payroll_item_id', $payroll_item_id)->delete();
                    }

                    Session::flash('success_msg', 'Successfully edited payroll item ' . $request->input('payroll_item_name'));
                    return redirect()->route('payroll_item_listing');
                }
                $payroll_item = (object) $request->all();
            }


            return view('payroll_item.form', [
                'title' => "Edit",
                'submit' => route('payroll_item_edit', $payroll_item_id),
                'worker_roles' => WorkerRole::get(),
                'setting_expense_sel' =>SettingExpense::get_item_expense_payroll_item(),
                'payroll_item' => $payroll_item,
                'payroll_item_type_sel' => ['' => 'Please Select Type'] + PayrollItem::get_enum_sel('payroll_item_type'),
            ]);
        }

        public function delete(Request $request)
        {
            $payroll = PayrollItem::find($request->input('payroll_item_id'));

            if(!$payroll){
                Session::flash('failed_msg', 'Error, Please try again later..');
                return redirect()->route('payroll_item_listing');
            }

            $payroll->update([
                'is_deleted' => 1,
            ]);

            Session::flash('success_msg', "Successfully deleted payroll item.");
            return redirect()->route('payroll_item_listing');
        }
    }
?>
