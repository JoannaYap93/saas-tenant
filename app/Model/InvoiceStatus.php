<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $table = 'tbl_invoice_status';
    protected $primaryKey = 'invoice_status_id';
    public $timestamps = false;

    protected $fillable = [
        'invoice_status_name'
    ];

    public static function get_sel()
    {
        $query = InvoiceStatus::query()->get();
        $result = ['' => 'Please Select Status'];
        foreach ($query as $key => $value) {
            $result[$value->invoice_status_id] = $value->invoice_status_name;
        }
        return $result;
    }
}
