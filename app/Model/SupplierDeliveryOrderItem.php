<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierDeliveryOrderItem extends Model
{
    protected $table = 'tbl_supplier_delivery_order_item';

    protected $primaryKey = 'supplier_delivery_order_item_id';

    const CREATED_AT = 'supplier_delivery_order_item_created';
    const UPDATED_AT = 'supplier_delivery_order_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_delivery_order_id',
        'raw_material_id',
        'raw_material_company_usage_id',
        'supplier_delivery_order_item_qty',
        'supplier_delivery_order_item_value_per_qty',
        'supplier_delivery_order_item_price_per_qty',
        'supplier_delivery_order_item_disc',
    ];

    public function supplier_delivery_order()
    {
        return $this->belongsTo(SupplierDeliveryOrder::class, 'supplier_delivery_order_id', 'supplier_delivery_order_id');
    }

    public function raw_material_company_usage()
    {
        return $this->belongsTo(RawMaterialCompanyUsage::class, 'raw_material_company_usage_id', 'raw_material_company_usage_id');
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id', 'raw_material_id');
    }
}
