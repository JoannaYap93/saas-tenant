<?php

namespace App\Http\Controllers;

use Session;
use App\Model\Claim;
use App\Model\Company;
use App\Model\ClaimLog;
use App\Model\UserRole;
use App\Model\ClaimItem;
use App\Model\ClaimStatus;
use App\Model\ClaimItemLog;
use App\Model\ClaimApproval;
use Illuminate\Http\Request;
use App\Model\CompanyExpenseItem;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Model\SettingExpenseCategory;
use App\Model\RawMaterialCompanyUsage;
use App\Model\SettingRawMaterialCategory;
use Illuminate\Support\Facades\Validator;

class ClaimItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request, $claim_id) {
        if (!$claim_id || !is_numeric($claim_id)) {
            Session::flash('fail_msg', 'Selected Claim Item Not Valid.');
            return redirect()->route('claim_listing');
        }

        $claim = Claim::get_by_id($claim_id);

        if (!$claim) {
            Session::flash('fail_msg', 'Claim is Not Valid.');
            return redirect()->route('claim_listing');
        }

        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'submit':
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

                case 'update':
                    $expense_amount = 0;
                    if($request->input('expense_item_ids')){
                        $company_expense_item = CompanyExpenseItem::whereIn('company_expense_item_id', $request->input('expense_item_ids'))->get();


                        if($company_expense_item){
                            foreach($company_expense_item as $item){
                                $expense_amount += $item->claim_remaining_amount;
                                $claim_item = ClaimItem::create([
                                    'claim_id' => $claim_id,
                                    'claim_item_date' => date('Y:m:d', strtotime($item->company_expense_item_created)),
                                    'claim_item_name' => @$item->expense->setting_expense_name,
                                    'claim_item_value' => $item->company_expense_item_unit_price,
                                    'claim_item_amount' => $item->company_expense_item_total,
                                    'claim_item_amount_claim' => $item->claim_remaining_amount,
                                    'claim_item_rejected_by' => '',
                                    'claim_item_rejected_date' => '',
                                    'claim_item_rejected_remark' => '',
                                    'claim_item_type' => 'company_expense_item_id',
                                    'claim_item_type_value' => $item->company_expense_item_id
                                ]);

                                ClaimItemLog::create([
                                    'claim_item_id' => $claim_item->claim_item_id,
                                    'claim_item_log_action' => 'Add Claim Item',
                                    'claim_item_log_remark' => Auth::user()->user_fullname . " added new Claim item - " . @$item->expense->setting_expense_name,
                                    'claim_item_log_admin_id' => Auth::id(),
                                    'claim_id' => $claim_id
                                ]);

                                if($item->hasMedia('company_expense_item_media')){
                                    $media = $item->getMedia('company_expense_item_media');
                                    if($media){
                                        foreach($media as $m){
                                            try {
                                                getimagesize($m->getUrl());
                                                $m->copy($claim_item, 'claim_item_media', 'digitalocean');
                                            } catch (\Throwable $th) {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $stock_in_amount = 0;
                    if($request->input('stock_in')){
                        $raw_material_company_usage = RawMaterialCompanyUsage::whereIn('raw_material_company_usage_id', $request->input('stock_in'))->get();
                        if($raw_material_company_usage){
                            foreach($raw_material_company_usage as $rows){
                                $stock_in_amount += $rows->claim_remaining_amount;
                                $claim_item = ClaimItem::create([
                                    'claim_id' => $claim_id,
                                    'claim_item_date' => date('Y:m:d', strtotime($rows->raw_material_company_usage_created)),
                                    'claim_item_name' => $rows->raw_material->raw_material_name,
                                    'claim_item_value' => $rows->raw_material_company_usage_price_per_qty,
                                    'claim_item_amount' => $rows->raw_material_company_usage_value_per_qty,
                                    'claim_item_amount_claim' => $rows->claim_remaining_amount,
                                    'claim_item_rejected_by' => '',
                                    'claim_item_rejected_date' => '',
                                    'claim_item_rejected_remark' => '',
                                    'claim_item_type' => 'raw_material_company_usage_id',
                                    'claim_item_type_value' => $rows->raw_material_company_usage_id
                                ]);

                                ClaimItemLog::create([
                                    'claim_item_id' => $claim_item->claim_item_id,
                                    'claim_item_log_action' => 'Add Claim Item',
                                    'claim_item_log_remark' => Auth::user()->user_fullname . " added new Claim item - " . @$rows->raw_material->raw_material_name,
                                    'claim_item_log_admin_id' => Auth::id(),
                                    'claim_id' => $claim_id
                                ]);
                            }
                        }

                    }

                    $claim->update([
                        'claim_amount' => $claim->claim_amount + $expense_amount + $stock_in_amount
                    ]);

                    Session::flash('success_msg', 'Successfully Added Claim Item');
                    return redirect()->route('claim_item_listing',$claim_id);
                    break;
            }
        }
        $is_remark = 0;

        switch ($claim->claim_status_id) {
            case '1'://Pending
                $submit = route('claim_item_listing', $claim_id);
                $title = "Submit My Claim";
                $step = 'claim_pending';
                break;
            case '2'://Awaiting for Checking
                $submit = route('claim_approve_checking', $claim_id);
                $title = "Claim Checked";
                $step = 'claim_check';
                $is_remark = 1;
                break;
            case '3'://Awaiting for Verify
                $submit = route('claim_approve_verify', $claim_id);
                $title = "Confirm Verified";
                $step = 'claim_verify';
                $is_remark = 1;
                break;
            case '4'://Awaiting Approval
                $submit = route('claim_approve_approval', $claim_id);
                $title = "Approve";
                $step = 'claim_approve';
                $is_remark = 1;
                break;
            case '5'://Approved
                $submit = route('claim_account_check', $claim_id);
                $title = "Account Checked";
                $step = 'claim_account_check';
                if ($claim->is_account_check=='1') {
                    $submit = route('claim_payment', $claim_id);
                    $title = "Payment";
                    $step = 'claim_payment';
                }
                break;
            case '8'://Rejected (Resubmit)
                $submit = route('claim_item_listing', $claim_id);
                $title = "Submit My Claim";
                $step = 'claim_pending';
                break;
            case '6'://Completed
            case '7'://Cancelled
            default :
                $submit = "";
                $title = "Claim Item Listing";
                $step = '';
                break;
        }

        $company_expense_item = CompanyExpenseItem::get_company_expense_item_by_claim($claim);
        $raw_material_company_usage = RawMaterialCompanyUsage::get_by_claim($claim);

        return view('claim_item.listing', [
            'submit' => $submit,
            'title' => $title,
            'claim_status_id' => $claim->claim_status_id,
            'claim'=> $claim,
            'step' => $step,
            'is_remark' => $is_remark,
            'company_expense_item' => $company_expense_item,
            'raw_material_company_usage' => $raw_material_company_usage,
            'user_id' => Auth::id(),
        ]);
    }

    public function add(Request $request, $claim_id)
    {
        $validation = null;
        $post = null;
        $claim = Claim::find($claim_id);

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                'claim_id' => 'exists:tbl_claim,claim_id,'.$claim_id,
                'claim_item_date' => 'required',
                'claim_item_name' => 'required',
                'claim_item_value' => 'required',
                'claim_item_amount' => 'required',
                'claim_item_amount_claim' => 'required',
                'claim_item_type' => 'required',
                'manually_company_expense_item_category_id' => 'required_if:claim_item_type,manually_company_expense_item_category_id',
                'manually_raw_material_company_usage_category_id' => 'required_if:claim_item_type,manually_raw_material_company_usage_category_id',
            ])->setAttributeNames([
                'claim_id' => 'Claim',
                'claim_item_date' => 'Date',
                'claim_item_name' => 'Name',
                'claim_item_value' => 'Value',
                'claim_item_amount' => 'Amount',
                'claim_item_amount_claim' => 'Amount Claim',
                'claim_item_type' => 'Claim Item Type',
                'manually_company_expense_item_category_id' => 'Expense Item Category',
                'manually_raw_material_company_usage_category_id' => 'Raw Material Category',
            ]);

            if (!$validation->fails()) {

                $claim_item = ClaimItem::create([
                    'claim_id' => $claim_id,
                    'claim_item_date' => date('Y:m:d', strtotime($request->input('claim_item_date'))),
                    'claim_item_name' => $request->input('claim_item_name'),
                    'claim_item_value' => $request->input('claim_item_value'),
                    'claim_item_amount' => $request->input('claim_item_amount'),
                    'claim_item_amount_claim' => $request->input('claim_item_amount_claim'),
                    'claim_item_rejected_by' => '',
                    'claim_item_rejected_date' => '',
                    'claim_item_rejected_remark' => '',
                    'claim_item_type' => $request->input('claim_item_type'),
                    'claim_item_type_value' => $request->input($request->input('claim_item_type'))
                ]);

                ClaimItemLog::create([
                    'claim_item_id' => $claim_item->claim_item_id,
                    'claim_item_log_action' => 'Add Claim Item',
                    'claim_item_log_remark' => Auth::user()->user_fullname . " added new Claim item - " . @$request->input('claim_item_name'),
                    'claim_item_log_admin_id' => Auth::id(),
                    'claim_id' => $claim_id
                ]);

                $claim->update([
                    'claim_amount' => $claim->claim_amount + $request->input('claim_item_amount_claim')
                ]);

                if ($request->file('claim_item_media')) {
                    foreach ($request->file('claim_item_media') as $claim_item_media) {
                        $claim_item->addMedia($claim_item_media)->toMediaCollection('claim_item_media');
                    }
                }

                Session::flash('success_msg', 'Successfully Added Claim Item');
                return redirect()->route('claim_item_listing',$claim_id);
            }
            $post = (object) $request->all();
        }

        $claim_type_sel = ['' => 'Please Select Type', 'manually_company_expense_item_category_id' => 'Company Expenses', 'manually_raw_material_company_usage_category_id' => 'Raw Material'];
        $raw_material_category_sel = SettingRawMaterialCategory::get_category_sel();
        $setting_expense_category_sel = SettingExpenseCategory::get_existing_expense_category_sel();


        return view('claim_item.form', [
            'submit' => route('claim_item_add',$claim_id),
            'title' => 'Add',
            'post' => $post,
            'claim_type_sel' => $claim_type_sel,
            'raw_material_category_sel' => $raw_material_category_sel,
            'setting_expense_category_sel' => $setting_expense_category_sel,
            'claim' => $claim,
        ])->withErrors($validation);
    }

    public function claim_log_detail($claim_id){
        $claim = Claim::find($claim_id);

        if(!$claim) {
            Session::flash('fail_msg', 'Invalid Claim');
            return redirect()->route('/');
        }

        $record = ClaimLog::get_log_by_claim_id($claim_id);

        return view('claim_item/claim_log_detail', [
            'records' => $record,
        ]);
    }

    public function reject(Request $request) {
        $claim_item_id = $request->input('claim_item_id');
        $claim_item = ClaimItem::get_by_id($claim_item_id);
        if(!$claim_item){
            Session::flash('fail_msg', 'Invalid Claim Item');
            return redirect()->route('claim_listing');
        }
        if ($request->method('POST')) {
            $validation = Validator::make($request->all(), [
                'remark' => 'required',
            ])->setAttributeNames([
                'remark' => 'Remark',
            ]);
            if (!$validation->fails()) {
                $claim_item->update([
                    'is_rejected' => 1,
                    'claim_item_rejected_by' => Auth::id(),
                    'claim_item_rejected_date' => now(),
                    'claim_item_rejected_remark' => $request->input('remark'),
                ]);

                ClaimItemLog::create([
                    'claim_item_id' => $claim_item->claim_item_id,
                    'claim_item_log_action' => 'Claim Item Rejected',
                    'claim_item_log_remark' => "Rejected by " . Auth::user()->user_fullname . " due to " . $request->input('remark'),
                    'claim_item_log_admin_id' => Auth::id(),
                    'claim_id' => $claim_item->claim_id
                ]);
                $new_amount = 0;
                if($claim_item->claim->claim_amount > 0){
                    $new_amount = $claim_item->claim->claim_amount - $claim_item->claim_item_amount_claim;
                }
                $claim_item->claim->update([
                    'claim_amount' => $new_amount
                ]);
                Session::flash('success_msg', 'Rejected Claim Item');
                return redirect()->route('claim_item_listing',$claim_item->claim_id);
            }
        }
    }

    public function delete(Request $request, $claim_item_id) {
        $claim_item = ClaimItem::get_by_id($claim_item_id);
        if(!$claim_item){
            Session::flash('fail_msg', 'Invalid Claim Item');
            return redirect()->route('claim_listing');
        }
        $claim_item->update([
            'is_deleted' => 1,
        ]);

        ClaimItemLog::create([
            'claim_item_id' => $claim_item->claim_item_id,
            'claim_item_log_action' => 'Delete Claim Item',
            'claim_item_log_remark' => "Claim item deleted by " . Auth::user()->user_fullname,
            'claim_item_log_admin_id' => Auth::id(),
            'claim_id' => $claim_item->claim_id
        ]);

        $new_amount = 0;
        if($claim_item->claim->claim_amount > 0){
            $new_amount = $claim_item->claim->claim_amount - $claim_item->claim_item_amount_claim;
        }
        $claim_item->claim->update([
            'claim_amount' => $new_amount
        ]);
        Session::flash('success_msg', 'Deleted Claim Item');
        return redirect()->route('claim_item_listing',$claim_item->claim_id);
    }
    public static function ajax_get_price_expense_item(Request $request)
    {
        $expense_item_id = $request->input('expense_item_id');
        Log::info($expense_item_id);
        $price_data = CompanyExpenseItem::where('company_expense_item_id', $expense_item_id)->first();
        Log::info($price_data);
        return $price_data;
    }
    public static function ajax_get_price_raw_material_item(Request $request)
    {
        $raw_material_company_usage_id = $request->input('raw_material_company_usage_id');
        $price_data = RawMaterialCompanyUsage::where('raw_material_company_usage_id', $raw_material_company_usage_id)->first();
        Log::info($price_data);
        return $price_data;
    }

    public function ajax_get_image_by_company_expense_item_id(Request $request)
    {
        $items = null;
        $expense_id = $request->input('expense_item_id');
        $items = CompanyExpenseItem::get_img_expense_item_by_id($expense_id);

        return response()->json([
            'items' => $items,
        ]);
    }

    public function  ajax_get_image_by_claim_item_id(Request $request)
    {
        $items = null;
        $claim_item_id = $request->input('claim_item_id');
        $items = ClaimItem::get_img_claim_item_by_id($claim_item_id);

        return response()->json([
            'items' => $items,
        ]);
    }
}
