<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderLog extends Model
{
    protected $table = 'tbl_delivery_order_log';
    protected $primaryKey = 'delivery_order_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'delivery_order_log_created';
    const UPDATED_AT = null;

    protected $fillable = [
        'delivery_order_id',
        'delivery_order_log_created',
        'delivery_order_log_action',
        'delivery_order_log_description',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
