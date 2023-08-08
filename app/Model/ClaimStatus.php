<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClaimStatus extends Model
{
    protected $table = 'tbl_claim_status';
    protected $primaryKey = 'claim_status_id';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_status_name', 'claim_approval_step_id_next', 'is_editable', 'is_edit_claim'
    ];



    public static function get_next_status($company_id, $current_status_id) {
        $result = ClaimStatus::query()
                ->join('tbl_company_claim_approval','tbl_company_claim_approval.claim_approval_step_id','tbl_claim_status.claim_approval_step_id_next')
                ->where('claim_status_id',$current_status_id)
                ->where('company_id',$company_id)
                ->first();

        if($result){
            return $result;
        }else{
            if ($current_status_id < 7) {
                return ClaimStatus::get_next_status($company_id, ($current_status_id + 1));
            } elseif ($current_status_id == 8) {
                return ClaimStatus::get_next_status($company_id, 1);
            } else {
                return $result;
            }
        }
    }

    public static function get_sel()
    {
        $query = ClaimStatus::query()->get();
        $result = ['' => 'Please Select Status'];
        foreach ($query as $rows) {
            $result[$rows->claim_status_id] = $rows->claim_status_name;
        }

        return $result;
    }

    public function company_claim_approval()
    {
        return $this->hasOne(CompanyClaimApproval::class, 'claim_approval_step_id', 'claim_approval_step_id_next')->where('user_id', Auth::id());
    }
}
