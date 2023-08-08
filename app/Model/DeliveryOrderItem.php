<?php

namespace App\Model;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class DeliveryOrderItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_delivery_order_item';
    protected $primaryKey = 'delivery_order_item_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'delivery_order_item_created';
    const UPDATED_AT = 'delivery_order_item_updated';

    protected $fillable = [
        'delivery_order_id',
        'product_id',
        'setting_product_size_id',
        'delivery_order_item_quantity',
        'delivery_order_item_created',
        'delivery_order_item_updated',
        'delivery_order_item_collect_no',
        'delivery_order_item_price_per_kg',
        'invoice_item_id',
        'no_collect_code'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function setting_product_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public static function get_order_by_do_id($id)
    {
        $item_arr = [];
        $query = DeliveryOrderItem::query()->where('delivery_order_id', $id)->orderBy('product_id')->orderBy('setting_product_size_id')->get();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $do_item) {

                //check if decimal is all 0
                if(fmod($do_item->delivery_order_item_quantity, 1) === 0.0000){
                    $delivery_order_item_quantity = number_format($do_item->delivery_order_item_quantity, 2);
                } else {
                    $delivery_order_item_quantity = number_format($do_item->delivery_order_item_quantity, 2);
                }

                $item_arr[$key] = [$do_item->product->product_name, $delivery_order_item_quantity, $do_item->setting_product_size->setting_product_size_name, $do_item->delivery_order_item_price_per_kg];
            }
        }

        return $item_arr;
    }

    public static function get_collect_no()
    {
        $log = DB::table('tbl_delivery_order_log')->where('user_id', auth()->id())->where('delivery_order_log_action', 'Add')->pluck('delivery_order_id');

        $do = DB::table('tbl_delivery_order')->whereIn('delivery_order_id', $log)
            ->whereDate('delivery_order_created', date('Y-m-d'))->pluck('delivery_order_id');

        $collect = DB::table('tbl_delivery_order_item')->whereIn('delivery_order_id', $do)
            ->where('delivery_order_item_collect_no', '!=', '')->pluck('delivery_order_item_id');

        // dd(count($collect));
        return count($collect) + 1;
    }

    public static function get_do_item_details_by_id($do_id)
    {
        $item_arr = [];
        $query = DeliveryOrderItem::query()->where('delivery_order_id', $do_id)->orderBy('product_id')->orderBy('setting_product_size_id')->get();

        if ($query->isNotEmpty()) {
            foreach ($query as $key => $item) {
                $product_total = number_format($item->delivery_order_item_quantity * $item->delivery_order_item_price_per_kg, 2);
                $item_arr[$key] = [$item->product->product_name, $item->setting_product_size->setting_product_size_name, number_format($item->delivery_order_item_quantity, 2),
                                   $item->delivery_order_item_price_per_kg, $product_total];
            }
        }

        return $item_arr;
    }


    public static function get_do_item_by_do_id($do_id)
    {
        $query = DeliveryOrderItem::with('product','setting_product_size')
            ->where('delivery_order_id', $do_id)->get();
        $media_url = [];
        $query->map(function($q) {
            if($q->hasMedia('delivery_order_item_media')){
                foreach($q->getMedia('delivery_order_item_media') as $mkey => $media){
                    $media_url[$mkey] = $media->getUrl();
                }
                // $q->media_url = $q->getMedia('delivery_order_item_media');
                Log::info($media_url);
                $q->media_url = $media_url;
            }
        });
        return $query;

    }
}
