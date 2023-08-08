<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClaimItemLog extends Model
{
    protected $table = 'tbl_claim_item_log';
    protected $primaryKey = 'claim_item_log_id';
    const CREATED_AT = 'claim_item_log_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'claim_item_id', 'claim_item_log_action', 'claim_item_log_remark', 'claim_item_log_admin_id', 'claim_id'
    ];
}
