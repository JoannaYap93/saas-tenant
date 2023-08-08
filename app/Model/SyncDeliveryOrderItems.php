<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SyncDeliveryOrderItems extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_sync_delivery_order_item';
    protected $primaryKey = 'sync_delivery_order_item_id';
    const CREATED_AT = 'sync_delivery_order_item_created';
    const UPDATED_AT = 'sync_delivery_order_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'sync_delivery_order_id',
        'product_id',
        'setting_product_size_id',
        'sync_delivery_order_item_quantity',
        'sync_delivery_order_item_created',
        'sync_delivery_order_item_updated',
        'sync_delivery_order_item_collect_no',
        'delivery_order_item_id',
        'no_collect_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function setting_product_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }

    public function sync_delivery_order()
    {
        return $this->belongsTo(SyncDeliveryOrder::class, 'sync_delivery_order_id');
    }

    public static function get_records($search)
    {
        $syncDeliveryOrderItems = SyncDeliveryOrderItems::query();

        if (@$search['sync_delivery_order_id']){
            $freetext = $search['sync_delivery_order_id'];
            $syncDeliveryOrderItems->where(function ($q) use ($freetext) {
                $q->where('sync_delivery_order_id', $freetext);

            });
        }


        $syncDeliveryOrderItems->orderBy('sync_delivery_order_id', 'DESC');
        return $syncDeliveryOrderItems->paginate(10);
    }

}
