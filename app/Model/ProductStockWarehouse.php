<?php

namespace App\Model;

// use DOMDocument;
use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;

class ProductStockWarehouse extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_product_stock_warehouse';
    protected $primaryKey = 'product_stock_warehouse_id';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;
    // const CREATED_AT = 'product_updated';
    // const UPDATED_AT = 'product_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
        'warehouse_id', 'product_id', 'setting_product_size_id', 'product_stock_warehouse_qty_current',
    ];

    public static function get_records($search, $company_id)
    {
        $query = ProductStockWarehouse::query();
        $query->join('tbl_setting_warehouse', 'tbl_setting_warehouse.warehouse_id', 'tbl_product_stock_warehouse.warehouse_id');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('tbl_setting_warehouse.warehouse_name', 'like', '%' . $freetext . '%');
            });
        }
        if (@$search['warehouse_id']) {
            $query->where('tbl_setting_warehouse.warehouse_id', $search['warehouse_id']);
        }

        if (@$search['product_id']) {
            $query->where('tbl_product_stock_transfer.product_id', $search['product_id']);
        }

        if (@$company_id && $company_id != 0) {
            $query->where('tbl_setting_warehouse.company_id', $company_id);
        }

        if (@$search['company_id']) {
            $query->orWhere('company_id', $search['company_id']);
        }
        
        //
        // $query->with('setting_warehouse');
        // $query->with('setting_size');
        // $query->with('product');
        $query->get();
        $result = $query->orderBy('product_stock_warehouse_id', 'asc')->paginate(10);

        return $result;
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
    public static function get_by_product_id($product_id)
    {
        $query = ProductStockWarehouse::query();
        $query->where('product_id', $product_id);
        $query->with('setting_warehouse');
        // $query->where('is_deleted', '<>', 1);
        $query = $query->get();
        $list = [];
        if ($query) {
            foreach ($query as $key => $value) {
                array_push($list, array(
                    'id' => $value->product_stock_warehouse_id,
                    'label' => strtoupper($value->setting_warehouse->warehouse_name),
                    'value' => strtoupper($value->setting_warehouse->warehouse_name),
                ));
            }
        }
        return $list;
    }

    public static function get_by_product_stock_warehouse_id($warehouse_id, $product_id, $setting_product_size_id)
    {
        $query = ProductStockWarehouse::query();
        $query->where('warehouse_id', $warehouse_id);
        $query->where('product_id', $product_id);
        $query->where('setting_product_size_id', $setting_product_size_id);
        // $query->with('setting_warehouse');
        // $query->where('is_deleted', '<>', 1);
        $query = $query->get();
        $list = [];
        if ($query) {
            foreach ($query as $key => $value) {
                array_push($list, array(
                    'id' => $value->product_stock_warehouse_id,
                    // 'label' => strtoupper($value->setting_warehouse->warehouse_name),
                    'value' => strtoupper($value->product_stock_warehouse_qty_current),
                ));
            }
        }
        return $list;
    }

    public static function get_by_product_stock_warehouse_id_2($warehouse_id, $product_id, $setting_product_size_id)
    {
        $query = ProductStockWarehouse::where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->where('setting_product_size_id', $setting_product_size_id)
            // $query->with('setting_warehouse');
            // $query->where('is_deleted', '<>', 1);
            ->value('product_stock_warehouse_id');

        return $query;
    }

    public function setting_warehouse()
    {
        return $this->belongsTo(SettingWarehouse::class, 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
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
