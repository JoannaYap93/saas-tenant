<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    protected $table = 'tbl_invoice_type';
    protected $primaryKey = 'invoice_type_id';
    public $timestamps = false;

    protected $fillable = [
        'invoice_type_name'
    ];
}
