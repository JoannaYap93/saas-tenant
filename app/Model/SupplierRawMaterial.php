<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierRawMaterial extends Model
{
    protected $table = 'tbl_supplier_raw_material';

    protected $primaryKey = 'supplier_raw_material_id';

    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'raw_material_id',
    ];
}
