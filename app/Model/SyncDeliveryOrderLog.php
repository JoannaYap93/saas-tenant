<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SyncDeliveryOrderLog extends Model
{
    protected $table = 'tbl_sync_delivery_order_log';
    protected $primaryKey = 'sync_delivery_order_log_id';
    const CREATED_AT = 'sync_delivery_order_log_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'sync_delivery_order_id',
        'sync_delivery_order_log_action',
        'sync_delivery_order_log_description',
        'user_id'
    ];
}
