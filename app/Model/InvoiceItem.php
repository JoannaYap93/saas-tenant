<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'tbl_invoice_item';
    protected $primaryKey = 'invoice_item_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'invoice_item_created';
    const UPDATED_AT = 'invoice_item_updated';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'setting_product_size_id',
        'invoice_item_name',
        'invoice_item_price',
        'invoice_item_quantity',
        'invoice_item_subtotal',
        'invoice_item_discount',
        'invoice_item_total',
        'invoice_item_created',
        'invoice_item_updated',
        'delivery_order_item_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function setting_product_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }

    public function delivery_order_item()
    {
        return $this->hasOne(DeliveryOrderItem::class, 'invoice_item_id');
    }

    public static function get_item_by_invoice_id($id)
    {
        $item_arr = [];
        $query = InvoiceItem::query()->where('invoice_id', $id)->get();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $item) {
                $item_arr[$key] = [$item->product->product_name, $item->setting_product_size->setting_product_size_name];
            }
        }

        return $item_arr;
    }
}
