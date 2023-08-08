<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClaimApprovalStep extends Model
{
    protected $table = 'tbl_claim_approval_step';
    protected $primaryKey = 'claim_approval_step_id';
    public $timestamps = false;

    protected $fillable = [
        'claim_approval_step_name'
    ];

    public static function get_step_assign(){
        $query = ClaimApprovalStep::query()->get();
        $result= null;

        if($query)
        {
            foreach($query as $step_key => $step)
            {
                $result[$step->claim_approval_step_id] =  $step->claim_approval_step_name;

                if($step_key >= 4){
                    break;
                }
            }
            return $result;
        }
    }
}
