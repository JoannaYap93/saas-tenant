<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClaimLog extends Model
{
    protected $table = 'tbl_claim_log';
    protected $primaryKey = 'claim_log_id';
    const CREATED_AT = 'claim_log_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_log_action', 'from_claim_status_id', 'to_claim_status_id', 'claim_log_description', 'claim_id', 'claim_log_user_id'
    ];

    public static function get_log_by_claim_id($claim_id){

        $query = ClaimLog::query()
            ->where('claim_id','=', $claim_id)
            ->orderBy('claim_log_created','desc')->get();

        return $query;
    }
}
