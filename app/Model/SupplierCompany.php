<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierCompany extends Model
{
    protected $table = 'tbl_supplier_company';

    protected $primaryKey = 'supplier_company_id';

    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
