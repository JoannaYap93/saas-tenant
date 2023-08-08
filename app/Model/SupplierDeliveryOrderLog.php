<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierDeliveryOrderLog extends Model
{
    protected $table = 'tbl_supplier_delivery_order_log';

    protected $primaryKey = 'supplier_delivery_order_log_id';

    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_delivery_order_log_id',
        'supplier_delivery_order_id',
        'supplier_delivery_order_log_action',
        'supplier_delivery_order_log_description',
        'supplier_delivery_order_log_created',
        'user_id',
    ];

    public function supplier_delivery_order()
    {
        return $this->belongsTo(SupplierDeliveryOrder::class, 'supplier_delivery_order_id', 'supplier_delivery_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
?>
