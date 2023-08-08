<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClaimApproval extends Model
{
    protected $table = 'tbl_claim_approval';
    protected $primaryKey = 'claim_approval_id';
    const CREATED_AT = 'claim_approval_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_approval_step_id', 'approval_user_id', 'claim_id', 'claim_approval_remark', 'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'approval_user_id', 'user_id');
    }
}
