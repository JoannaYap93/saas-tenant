<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierBank extends Model
{
    protected $table = 'tbl_supplier_bank';

    protected $primaryKey = 'supplier_bank_id';

    const CREATED_AT = 'supplier_bank_created';
    const UPDATED_AT = 'supplier_bank_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_bank_acc_no',
        'supplier_bank_acc_name',
        'setting_bank_id',
        'supplier_id',
        'is_deleted',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function setting_bank()
    {
        return $this->belongsTo(SettingBank::class, 'setting_bank_id', 'setting_bank_id');
    }
}
