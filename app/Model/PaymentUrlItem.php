<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentUrlItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_payment_url_item';
    protected $primaryKey = 'payment_url_item_id';
    const CREATED_AT = 'payment_url_item_created';
    const UPDATED_AT = 'payment_url_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'payment_url_id',
        'invoice_id',
        'invoice_total',
        'is_deleted'
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Model\Invoice', 'invoice_id');
    }
}
