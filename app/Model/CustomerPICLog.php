<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerPICLog extends Model
{
    protected $table = 'tbl_customer_pic_log';
    protected $primaryKey = 'customer_pic_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'customer_pic_log_created';
    const UPDATED_AT = 'customer_pic_log_updated';

    protected $fillable = [
        'customer_pic_id',
        'customer_pic_log_created',
        'customer_pic_log_updated',
        'customer_pic_log_action',
        'customer_pic_log_description',
        'user_id',
    ];
}
