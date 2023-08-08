<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerLog extends Model
{
    protected $table = 'tbl_customer_log';
    protected $primaryKey = 'customer_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'customer_log_created';
    const UPDATED_AT = 'customer_log_updated';

    protected $fillable = [
        'customer_id',
        'customer_log_created',
        'customer_log_updated',
        'customer_log_action',
        'customer_log_description',
        'customer_id',
    ];
}
