<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    protected $table = 'tbl_invoice_log';
    protected $primaryKey = 'invoice_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'invoice_log_created';

    protected $fillable = [
        'invoice_id',
        'invoice_log_created',
        'invoice_log_description',
        'invoice_log_action',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function get_payment($id)
    {
        $query = InvoiceLog::query()
            ->where('invoice_id', $id)
            ->where('invoice_log_action', 'Payment')
            ->orderBy('invoice_log_created', 'desc')->first();
        if ($query) {
            return $query->toArray();
        } else {
            return $query;
        }
    }
}
