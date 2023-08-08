<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Claim extends Model
{
    protected $table = 'tbl_claim';
    protected $primaryKey = 'claim_id';
    const CREATED_AT = 'claim_created';
    const UPDATED_AT = 'claim_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_start_date', 'claim_end_date', 'claim_remark', 'claim_admin_remark', 'user_id', 'claim_status_id',
        'approval_user_id', 'claim_amount', 'company_id', 'is_account_check', 'is_payment', 'admin_id', 'worker_id', 'claim_no','is_deleted'
    ];

    public static function get_records($search, $perpage)
    {
        $query = Claim::query()->whereHas('company', function($q){
                                    $q->where('is_display', '=', 1);
                                });

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('claim_remark', 'like', '%' . $search['freetext'] . '%');

                $q->orWhereHas('company', function ($q2) use ($search) {
                    $q2->where('company_name', 'like', '%' . $search['freetext'] . '%');
                });

                $q->orWhereHas('worker', function ($q3) use ($search) {
                    $q3->where('worker_name', 'like', '%' . $search['freetext'] . '%');
                });
            });
        }

        if (@$search['claim_start_date']) {
            $query->whereDate('claim_start_date', '>=', DATE($search['claim_start_date']) . ' 00:00:00');
        }

        if (@$search['claim_end_date']) {
            $query->whereDate('claim_end_date', '<=', DATE($search['claim_end_date']) . ' 23:59:59');
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }else{
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_claim.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_claim.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_claim.company_id', '<>', 1);
            }
        }

        // if(@$search['user_role_id'] && @$search['user_role_id'] > 0){
        //     $query->where('user_role_id',$search['user_role_id']);
        // }

        if(@$search['is_account_check']){
            $query->where('is_account_check',$search['is_account_check']);
        }

        if(@$search['claim_status_id']){
            $query->where('claim_status_id',$search['claim_status_id']);
        }

        if (@$search['user_role_id']) {
            $query->whereHas('user', function ($q) use($search){
                $q->whereHas('roles', function ($q2) use($search){
                  $q2->where('role_id', $search['user_role_id']);
                });
            });
        }
        $query->where('is_deleted', '!=', 1);

        $result = $query->orderBy('claim_created', 'DESC')->paginate($perpage);

        return $result;
    }

    public static function get_by_id($claim_id){
        $query = Claim::query()
                ->with(['user','company','claim_status','claim_item','claim_item_rejected','claim_log'])
                ->where('claim_id', $claim_id);

        return $query->first();
    }

    public function claim_status()
    {
        return $this->belongsTo(ClaimStatus::class, 'claim_status_id','claim_status_id');
    }

    public function claim_log()
    {
        return $this->hasMany(ClaimLog::class, 'claim_id','claim_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id','company_id');
    }

    public function claim_item()
    {
        return $this->hasMany(ClaimItem::class, 'claim_id','claim_id')->where('is_rejected',0)->where('is_deleted',0);
    }

    public function claim_item_rejected()
    {
        return $this->hasMany(ClaimItem::class, 'claim_id','claim_id')->where('is_rejected',1)->where('is_deleted',0);
    }

    public function getNextStepAttribute()
    {
        $company_id = $this->company_id;
        $claim_status_id = $this->claim_status_id;

        return ClaimStatus::get_next_status($company_id,$claim_status_id);
    }

    public function getClaimPendingAttribute()
    {
        $user_id = $this->admin_id;
        if(Auth::id() == $user_id){
            return [Auth::id()];
        }
    }

    public function getClaimCheckAttribute() #1 Check
    {
        $company_id = $this->company_id;
        $claim_status_id = 1;
        // $claim_approval_step_id = 'Check';
        $claim_approval_step_id = 1;
        return CompanyClaimApproval::get_pic_by_company_status($company_id,$claim_approval_step_id);
    }

    public function getClaimVerifyAttribute() #2 Verify
    {
        $company_id = $this->company_id;
        $claim_status_id = 2;
        // $claim_approval_step_id = 'Verify';
        $claim_approval_step_id = 2;
        return CompanyClaimApproval::get_pic_by_company_status($company_id,$claim_approval_step_id);
    }

    public function getClaimApproveAttribute() #3 Approve
    {
        $company_id = $this->company_id;
        $claim_status_id = 3;
        // $claim_approval_step_id = 'Approve';
        $claim_approval_step_id = 3;
        return CompanyClaimApproval::get_pic_by_company_status($company_id,$claim_approval_step_id);
    }

    public function getClaimAccountCheckAttribute() #4 Account Check
    {
        $company_id = $this->company_id;
        $claim_status_id = 4;
        // $claim_approval_step_id = 'AccountCheck';
        $claim_approval_step_id = 4;
        return CompanyClaimApproval::get_pic_by_company_status($company_id,$claim_approval_step_id);
    }

    public function getClaimPaymentAttribute() #5 Payment
    {
        $company_id = $this->company_id;
        $claim_status_id = 5;
        // $claim_approval_step_id = 'Payment';
        $claim_approval_step_id = 5;
        return CompanyClaimApproval::get_pic_by_company_status($company_id,$claim_approval_step_id);
    }
}
