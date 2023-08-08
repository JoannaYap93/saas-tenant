<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentUrlLog extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_payment_url_log';
    protected $primaryKey = 'payment_url_item_id';
    const CREATED_AT = 'payment_url_item_created';
    const UPDATED_AT = 'payment_url_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'payment_url_log_id',
        'payment_url_id',
        'payment_url_log_action',
        'payment_url_log_description',
        'payment_url_log_created',
        'user_id',
        'customer_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
}
