<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class BudgetEstimatedLog extends Model
{
    protected $table = 'tbl_budget_estimated_log';
    protected $primaryKey = 'budget_estimated_log_id';
    const CREATED_AT = 'budget_estimate_log_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'budget_estimated_id',
        'user_id',
        'budget_estimated_log_action',
        'budget_estimated_log_remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

}
