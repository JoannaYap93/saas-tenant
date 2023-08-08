<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierDeliveryOrderReturn extends Model
{
    protected $table = 'tbl_supplier_delivery_order_return';

    protected $primaryKey = 'supplier_delivery_order_return_id';

    const CREATED_AT = 'supplier_delivery_order_return_created';
    const UPDATED_AT = 'supplier_delivery_order_return_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_delivery_order_return_id',
        'supplier_delivery_order_id',
        'supplier_delivery_order_item_id',
        'supplier_delivery_order_return_qty',
        'supplier_delivery_order_return_created',
        'supplier_delivery_order_return_updated',
        'raw_material_company_usage_id',
        'user_id',
    ];
}
?>
