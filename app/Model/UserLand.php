<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserLand extends Model
{
    protected $table = 'tbl_user_land';
    protected $primaryKey = 'user_land_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'company_land_id'
    ];
}