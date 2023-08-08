<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserCompany extends Model
{
    protected $table = 'tbl_user_company';
    protected $primaryKey = 'user_company_id';
    // const CREATED_AT = 'sync_collect_created';
    // const UPDATED_AT = 'sync_collect_updated';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

}
