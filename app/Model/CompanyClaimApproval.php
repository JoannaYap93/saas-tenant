<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyClaimApproval extends Model
{
    protected $table = 'tbl_company_claim_approval';
    protected $primaryKey = 'company_claim_approval_id';
    const CREATED_AT = 'company_claim_approval_cdate';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'user_id', 'claim_approval_step_id', 'company_id'
    ];

    public static function get_pic_approval(){
        $query = CompanyClaimApproval::query();
        $result = $query->get();

        return $result;
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function claim_approval_step()
    {
        return $this->belongsTo('App\Model\ClaimApprovalStep', 'claim_approval_step_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public static function get_pic_by_company_status($company_id,$claim_approval_step_id){
        return CompanyClaimApproval::query()
            ->select('user_id')
            ->where('claim_approval_step_id',$claim_approval_step_id)
            ->where('company_id',$company_id)
            ->pluck('user_id')->toArray();
    }
}
