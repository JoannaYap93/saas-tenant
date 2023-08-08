<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $table = 'tbl_invoice_payment';
    protected $primaryKey = 'invoice_payment_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'invoice_payment_created';
    const UPDATED_AT = 'invoice_payment_updated';

    protected $fillable = [
        'invoice_id',
        'invoice_payment_amount',
        'setting_payment_id',
        'invoice_payment_created',
        'invoice_payment_updated',
        'invoice_payment_data',
        'is_deleted'
    ];

    public function setting_payment()
    {
        return $this->belongsTo(SettingPayment::class, 'setting_payment_id');
    }

}
