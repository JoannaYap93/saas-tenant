<?php

namespace App\Model;

// use DOMDocument;
use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;

class ProductStockTransfer extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_product_stock_transfer';
    protected $primaryKey = 'product_stock_transfer_id';
    // protected $dateFormat = 'Y-m-d H:i:s';
    // public $timestamps = false;
    const CREATED_AT = 'product_stock_transfer_created';
    const UPDATED_AT = 'product_stock_transfer_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
        'product_stock_transfer_description', 'product_stock_transfer_remark', 'product_stock_transfer_qty',
        'product_stock_transfer_qty_before', 'product_stock_transfer_qty_after', 'product_stock_transfer_status',
        'product_stock_transfer_action', 'product_stock_warehouse_id', 'product_stock_transfer_updated',
        'product_stock_transfer_created', 'setting_product_size_id', 'product_id'
    ];

    public static function get_records($search, $company_id)
    {
        $query = ProductStockTransfer::query();
        $query->join('tbl_product_stock_warehouse', 'tbl_product_stock_warehouse.product_stock_warehouse_id', 'tbl_product_stock_transfer.product_stock_warehouse_id');
        $query->join('tbl_setting_warehouse', 'tbl_setting_warehouse.warehouse_id', 'tbl_product_stock_warehouse.warehouse_id');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('product_stock_transfer_remark', 'like', '%' . $freetext . '%');
                $q->orWhere('product_stock_transfer_description', 'like', '%' . $freetext . '%');
                // $q->orWhere('warehouse_id', 'like', '%' . $freetext . '%');
            });
        }

        // if (@$search['product_stock_transfer_remark']) {
        //     $query->where('product_stock_transfer_remark', $search['product_stock_transfer_remark']);
        // }

        if (@$search['warehouse_id']) {
            $query->where('tbl_setting_warehouse.warehouse_id', $search['warehouse_id']);
        }

        if (@$search['product_id']) {
            $query->where('tbl_product_stock_transfer.product_id', $search['product_id']);
        }

        if (@$search['product_size_id']) {
            $query->where('tbl_product_stock_transfer.setting_product_size_id', $search['product_size_id']);
        }

        if (@$search['stock_from']) {
            $query->whereDate('product_stock_transfer_created', '>=', $search['stock_from']);
        }

        if (@$search['stock_to']) {
            $query->whereDate('product_stock_transfer_created', '<=', $search['stock_to']);
        }

        if (@$company_id && $company_id != 0) {
            $query->where('tbl_setting_warehouse.company_id', $company_id);
        }

        if (@$search['company_id']) {
            $query->orWhere('company_id', $search['company_id']);
        }

        $query->get();

        $result = $query->orderBy('product_stock_transfer_id', 'desc')->paginate(15);
        return $result;
        // $query = ProductStockTransfer::query();
        //
        // if (@$search['freetext']) {
        //     $freetext = $search['freetext'];
        //     $query->where(function ($q) use ($freetext) {
        //         $q->where('product_name', 'like', '%' . $freetext . '%');
        //         $q->orWhere('product_remarks', 'like', '%' . $freetext . '%');
        //         $q->orWhere('product_slug', 'like', '%' . $freetext . '%');
        //         $q->orWhere('product_sku', 'like', '%' . $freetext . '%');
        //     });
        // }
        //
        // if (@$search['warehouse_id']) {
        //     $query->where('warehouse_id', $search['warehouse_id']);
        // }
        //
        // $query->with('product_stock_warehouse', 'setting_size', 'product');
        // $result = $query->orderBy('product_stock_transfer_id', 'desc')->paginate(15);
        // dd($result);
        // return $result;
    }

    // public static function get_by_id($id, $ajax = false)
    // {
    //     $query = Product::find($id);
    //     if ($ajax == true) {
    //         return response()->json(['data' => $query, 'status' => $query ? true : false]);
    //     } else {
    //         return $query;
    //     }
    // }
    //
    // public static function get_by_name($name)
    // {
    //     $query = Product::query();
    //     $query->where('product_name', 'like', '%' . $name . '%');
    //     $query->where('is_deleted', '<>', 1);
    //     $query = $query->get();
    //     $list = [];
    //     if ($query) {
    //         foreach ($query as $key => $value) {
    //             array_push($list, array(
    //                 'id' => $value->product_id,
    //                 'label' => strtoupper($value->product_name),
    //                 'value' => strtoupper($value->product_name),
    //             ));
    //         }
    //     }
    //     return $list;
    // }

    public function setting_warehouse()
    {
        return $this->belongsTo(SettingWarehouse::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_stock_warehouse()
    {
        return $this->belongsTo(ProductStockWarehouse::class, 'product_stock_warehouse_id');
    }

    public function setting_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }
    //
    // public function product_status()
    // {
    //     return $this->belongsTo(ProductStatus::class, 'product_status_id');
    // }
    //
    // public function product_size()
    // {
    //     return $this->hasMany(ProductSizeLink::class, 'product_id');
    // }
    //
    // public function product_tag_link()
    // {
    //     return $this->hasMany(ProductTagLink::class, 'product_id');
    // }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('product_media')
    //         ->singleFile()
    //
    //         ->registerMediaConversions(function (Media $media) {
    //             $this->addMediaConversion('full')
    //                 ->format('jpg')
    //                 ->apply();
    //             $this->addMediaConversion('thumb')
    //                 ->crop('crop-center', 300, 300)
    //                 ->format('jpg')
    //                 ->apply();
    //         });
    // }
}
