<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\Model\User;
use App\Model\Claim;
use App\Model\Company;
use App\Model\ClaimLog;
use App\Model\UserRole;
use App\Model\ClaimItem;
use App\Model\ClaimStatus;
use App\Model\ClaimItemLog;
use App\Exports\ClaimExport;
use App\Model\ClaimApproval;
use App\Model\RunningNumber;
use Illuminate\Http\Request;
use App\Model\SettingFormula;
use App\Model\CompanyExpenseItem;
use App\Model\SettingRawMaterial;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\SettingExpenseCategory;
use App\Model\SettingFormulaCategory;
use App\Model\SettingRawMaterialCategory;
use Illuminate\Support\Facades\Validator;

class ClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request){

        $perpage = 15;
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    $search['freetext'] = $request->input('freetext');
                    $search['claim_start_date'] = $request->input('claim_start_date');
                    $search['claim_end_date'] = $request->input('claim_end_date');

                    $search['claim_status_id'] = $request->input('claim_status_id');
                    $search['company_id'] = $request->input('company_id');
                    $search['user_role_id'] = $request->input('user_role_id');
                    $search['is_account_check'] = $request->input('is_account_check');

                    Session::put('claim_search', $search);
                    break;
                case 'reset':
                    Session::forget('claim_search');
                    break;
            }
        }
        $search = Session::has('claim_search') ? Session::get('claim_search') : array();
        $records = Claim::get_records($search, $perpage);


        $claim_status_sel = ClaimStatus::get_sel();
        $user_role_sel = UserRole::get_sel();
        $company_sel = Company::get_company_sel();

        return view('claim.listing', [
            'submit' => route('claim_listing'),
            'title' => 'Claim',
            'records' => $records,
            'search' =>  $search,
            'claim_status_sel' => $claim_status_sel,
            'user_role_sel' => $user_role_sel,
            'company_sel' => $company_sel,
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;

        if(auth()->user()->user_type_id != 1){
            $next_status = ClaimStatus::get_next_status(auth()->user()->company_id, 1);
            if(!$next_status){
                Session::flash('fail_msg', 'Company PIC for Approval is required');
                return redirect()->route('claim_listing');
            }
        }

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'worker_id' => 'required',
                'company_id' => auth()->user()->user_type_id == 1 ? 'required' : 'nullable',
                'month_year' => 'required',
            ])->setAttributeNames([
                'worker_id' => 'Farm Manager',
                'company_id' => 'Company',
                'month_year' => 'Claim Month',
            ]);
            if (!$validation->fails()) {
                $running_no = RunningNumber::get_running_code_expense('claim');

                 if (auth()->user()->company_id != 0) {
                    $company = Company::find(auth()->user()->company_id);
                } else {
                    $company = Company::find($request->input('company_id'));
                }

                $claim_id = Claim::insertGetId([
                    'claim_start_date' => date($request->input('month_year').'-01'),
                    'claim_end_date' => date("Y-m-t", strtotime($request->input('month_year'))),
                    'claim_remark' => $request->input('remark'),
                    'claim_admin_remark' => '',
                    'claim_created' => now(),
                    'claim_updated' => now(),
                    'worker_id' => $request->input('worker_id'),
                    'claim_status_id' => 1, //pending
                    'claim_amount' => '0.00',
                    'company_id' => auth()->user()->user_type_id == 1 ? $request->input('company_id') : auth()->user()->company_id,
                    'admin_id' => Auth::id(),
                    'claim_number' => 'CL/' . $company->company_code . '/' . auth()->user()->user_unique_code . '/' . $running_no,
                ]);
                ClaimLog::create([
                    'claim_log_action' => 'Create Claim',
                    'from_claim_status_id' => 1,
                    'to_claim_status_id' => 1,
                    'claim_log_description' => Auth::user()->user_fullname. " Created Claim",
                    'claim_id' => $claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);
                return redirect()->route('claim_item_listing',$claim_id);
            }
            $post = (object) $request->all();
        }

        return view('claim.form', [
            'submit' => route('claim_add'),
            'title' => 'Add',
            'post'=> $post,
            'farm_manager_sel' => ['' => 'Please Select Farm Manager'],
            'company_sel' => Company::get_company_sel(),
        ])->withErrors($validation);
    }

    public function submit_claim(Request $request, $claim_id){
        $claim = Claim::get_by_id($claim_id);
        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }

        $claim_amount = 0;
        foreach ($claim->claim_item as $claim_item) {
            $claim_amount += $claim_item->claim_item_amount_claim;
        }

        if ($claim_amount == 0) {
            Session::flash('fail_msg', 'Claim Item is empty!');
            return redirect()->route('claim_item_listing', $claim_id);
        }

        $current_claim_status_id = $claim->claim_status_id;
        $claim->update([
            'claim_status_id' => 2, //Checking
            'claim_amount' => $claim_amount,
        ]);

        ClaimLog::create([
            'claim_log_action' => 'Submit Claim',
            'from_claim_status_id' => $current_claim_status_id,
            'to_claim_status_id' => $claim->claim_status_id,
            'claim_log_description' => 'Claim submitted by ' . Auth::user()->user_fullname,
            'claim_id' => $claim->claim_id,
            'claim_log_user_id' => Auth::id()
        ]);

        Session::flash('success_msg', 'Claim has been submitted');
        return redirect()->route('claim_item_listing', $claim_id);
    }

    public function approve_checking(Request $request, $claim_id) {
        $validation = null;
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }

        if(!@$claim->claim_status->company_claim_approval){
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect()->route('claim_listing');
        }

        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 3,//Verify
                ]);
                ClaimApproval::create([
                    'claim_approval_step_id' => 2,
                    'approval_user_id' => Auth::id(),
                    'claim_id' => $claim->claim_id,
                    'claim_approval_remark' => $request->input('remark'),
                    'company_id' => $claim->company_id
                ]);
                ClaimLog::create([
                    'claim_log_action' => 'Checked',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Checked the Claim - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);
                Session::flash('success_msg', 'Claim has been checked');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function approve_verify(Request $request, $claim_id) {
        $validation = null;
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }

        if(!@$claim->claim_status->company_claim_approval){
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect()->route('claim_listing');
        }

        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 4, //Approval
                ]);
                ClaimApproval::create([
                    'claim_approval_step_id' => 3,
                    'approval_user_id' => Auth::id(),
                    'claim_id' => $claim->claim_id,
                    'claim_approval_remark' => $request->input('remark'),
                    'company_id' => $claim->company_id
                ]);
                ClaimLog::create([
                    'claim_log_action' => 'Verified',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Approved the Claim - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);

                Session::flash('success_msg', 'Claim has been verified');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function approve_approval(Request $request, $claim_id) {
        $validation = null;
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }

        if(!@$claim->claim_status->company_claim_approval){
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect()->route('claim_listing');
        }

        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 5, //Approved
                ]);
                ClaimApproval::create([
                    'claim_approval_step_id' => 4,
                    'approval_user_id' => Auth::id(),
                    'claim_id' => $claim->claim_id,
                    'claim_approval_remark' => $request->input('remark'),
                    'company_id' => $claim->company_id
                ]);
                ClaimLog::create([
                    'claim_log_action' => 'Approve Claim',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Approved Claim - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);

                Session::flash('success_msg', 'Claim has been approved');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function account_check(Request $request, $claim_id) {
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }
        if(!@$claim->claim_status->company_claim_approval){
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect()->route('claim_listing');
        }

        $claim->update([
            'is_account_check' => 1,
        ]);
        ClaimApproval::create([
            'claim_approval_step_id' => 4,
            'approval_user_id' => Auth::id(),
            'claim_id' => $claim->claim_id,
            'claim_approval_remark' => 'Claim detail checked by ' . Auth::user()->user_fullname,
            'company_id' => $claim->company_id
        ]);
        ClaimLog::create([
            'claim_log_action' => 'Approve Claim',
            'from_claim_status_id' => $claim->claim_status_id,
            'to_claim_status_id' => $claim->claim_status_id,
            'claim_log_description' => Auth::user()->user_fullname . ' have Checked the Claim',
            'claim_id' => $claim->claim_id,
            'claim_log_user_id' => Auth::id()
        ]);

        Session::flash('success_msg', 'Account has been checked');
        return redirect()->route('claim_item_listing', $claim_id);
    }

    public function payment(Request $request, $claim_id) {
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }

        if(!@$claim->claim_status->company_claim_approval){
            Session::flash('fail_msg', 'Forbidden! Permission is not allowed.');
            return redirect()->route('claim_listing');
        }

        if($claim->claim_status_id == 6){
            Session::flash('fail_msg', 'Selected Claim is Completed');
            return redirect()->route('claim_item_listing', $claim_id);
        }
        $current_claim_status_id = $claim->claim_status_id;
        $claim->update([
            'is_payment' => 1,
            'claim_status_id' => 6, //Completed
        ]);
        ClaimApproval::create([
            'claim_approval_step_id' => 5,
            'approval_user_id' => Auth::id(),
            'claim_id' => $claim->claim_id,
            'claim_approval_remark' => 'Claim payment proceeded by ' . Auth::user()->user_fullname,
            'company_id' => $claim->company_id
        ]);
        ClaimLog::create([
            'claim_log_action' => 'Payment',
            'from_claim_status_id' => $current_claim_status_id,
            'to_claim_status_id' => $claim->claim_status_id,
            'claim_log_description' => Auth::user()->user_fullname . ' proceeded the payment',
            'claim_id' => $claim->claim_id,
            'claim_log_user_id' => Auth::id()
        ]);

        Session::flash('success_msg', 'Claim has been updated to paid');
        return redirect()->route('claim_item_listing', $claim_id);
    }

    public function permanent_reject(Request $request, $claim_id) {
        $validation = null;
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }
        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 7, //Rejected (Permanent)
                    'claim_admin_remark' => $request->input('remark'),
                ]);

                ClaimLog::create([
                    'claim_log_action' => 'Reject Claim (Permanent)',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Reject Claim (Permanent) - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);

                Session::flash('success_msg', 'Claim has been rejected');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function resubmit_reject(Request $request) {
        $validation = null;
        $claim_id = $request->input('claim_id');
        $claim = Claim::get_by_id($claim_id);

        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }
        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 8, // Rejected (Resubmit)
                    'claim_admin_remark' => $request->input('remark'),
                ]);

                ClaimLog::create([
                    'claim_log_action' => 'Rejected (Resubmit)',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Rejected the Claim - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);

                Session::flash('success_msg', 'Claim has been rejected (resubmit)');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function cancel_submission(Request $request) {
        $validation = null;
        $claim_id = $request->input('claim_id');
        $claim = Claim::get_by_id($claim_id);
        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }
        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $current_claim_status_id = $claim->claim_status_id;
                $claim->update([
                    'claim_status_id' => 1, // Pending
                    'claim_admin_remark' => $request->input('remark'),
                ]);

                ClaimLog::create([
                    'claim_log_action' => 'Cancel Submission',
                    'from_claim_status_id' => $current_claim_status_id,
                    'to_claim_status_id' => $claim->claim_status_id,
                    'claim_log_description' => Auth::user()->user_fullname.' Cancelled Submission - ' . $request->input('remark'),
                    'claim_id' => $claim->claim_id,
                    'claim_log_user_id' => Auth::id()
                ]);

                Session::flash('success_msg', 'Claim submission has been cancelled');
                return redirect()->route('claim_item_listing', $claim_id);
            }
            return redirect()->route('claim_item_listing', $claim_id)->withErrors($validation);
        }
    }

    public function view_claim_pdf(Request $request, $claim_id, $encryption){
        $check_encrypt = md5($claim_id.env('ENCRYPTION_KEY'));
        if($encryption == $check_encrypt){
        $claim = Claim::find($claim_id);
        $claim_item = ClaimItem::get_claim_item_for_pdf($claim_id);
        $claim_category_expense = SettingExpenseCategory::get_expense_category_for_pnl_reporting();
        $claim_category_material = SettingRawMaterialCategory::get_material_category_for_report();

        // dd($claim_item);
        if ($claim) {
            $claim_approval_verify = ClaimApproval::where('claim_id', $claim_id)->where('claim_approval_step_id', 2)->orderBy('claim_approval_created', "DESC")->first();
            $claim_approval_approve = ClaimApproval::where('claim_id', $claim_id)->where('claim_approval_step_id', 3)->orderBy('claim_approval_created', "DESC")->first();
            $pdf = PDF::loadView('claim.claim_pdf', [
                'claim_item' => $claim_item,
                'claim' => $claim,
                'claim_category_material' => $claim_category_material,
                'claim_category_expense' => $claim_category_expense,
                'claim_approval_verify' => $claim_approval_verify,
                'claim_approval_approve' => $claim_approval_approve,
            ])->setPaper('A4', 'landscape');
            return $pdf->stream();
            // return view('claim.claim_pdf', [
            //     'claim_item' => $claim_item,
            //     'claim' => $claim,
            // ]);
        } else {
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }
      }else{
        Session::flash('fail_msg', 'Invalid Claim Encryption');
        return redirect()->route('claim_listing');
      }
    }

    public function export_claim(Request $request, $claim_id){
      $claim = Claim::find($claim_id);
      $claim_item = ClaimItem::get_claim_item_for_pdf($claim_id);

      if ($claim) {
          $excel = true;   
          $claim_category_expense = SettingExpenseCategory::get_expense_category_for_pnl_reporting();
          $claim_category_material = SettingRawMaterialCategory::get_material_category_for_report();
          $claim_approval_verify = ClaimApproval::where('claim_id', $claim_id)->where('claim_approval_step_id', 2)->orderBy('claim_approval_created', "DESC")->first();
          $claim_approval_approve = ClaimApproval::where('claim_id', $claim_id)->where('claim_approval_step_id', 3)->orderBy('claim_approval_created', "DESC")->first();
          return Excel::download(new ClaimExport('claim/claim_pdf', $claim_item, $claim, $claim_approval_verify, $claim_approval_approve, $excel, $claim_category_material, $claim_category_expense ), 'Claim_'. $claim_id .'.xlsx');
      } else {
          Session::flash('fail_msg', 'Invalid Claim');
          return redirect()->route('claim_listing');
      }
    }

    public function delete_claim(Request $request){

        $claim = Claim::find($request->input('claim_id'));
        $claim_item = ClaimItem::where('claim_id',$request->input('claim_id'))->pluck('claim_item_id')->toArray();

        if($claim_item){
            ClaimItem::whereIn('claim_item_id', $claim_item)->delete();
        }
  
        if(!$claim){
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('claim_listing');
        }



        $claim->update([
           'is_deleted' => 1
       ]);

        Session::flash('success_msg', "Successfully deleted claim.");
        return redirect()->route('claim_listing');
      }

    public function ajax_check_company_pic(Request $request){
        $company_id = $request->input('company_id');
        $claim_status_id = $request->input('claim_status_id');

        $result = ClaimStatus::get_next_status($company_id, $claim_status_id);

        return response()->json(['data' => $result, 'status' => $result ? true : false]);
    }
}
