<?php

namespace App\Model;

// use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use Log;

class FormulaUsageProduct extends Model
{
    protected $table = 'tbl_formula_usage_product';
    protected $primaryKey = 'formula_usage_product_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;
    // const CREATED_AT = 'formula_usage_product_created';
    // const UPDATED_AT = 'formula_usage_item_updated';


    protected $fillable = [
        'product_id', 'formula_usage_id', 'formula_usage_product_created', 'formula_usage_product_json'
    ];

    // public static function get_records($search)
    // {
    //   // dd($search);
    //     $query = RawMaterialCompany::query();
    //     // $query->join('tbl_setting_warehouse', 'tbl_setting_warehouse.warehouse_id', 'tbl_product_stock_warehouse.warehouse_id');
    //
    //     if (@$search['freetext']) {
    //         $freetext = $search['freetext'];
    //         $query->where(function ($q) use ($freetext) {
    //             $q->where('raw_material_quantity', 'like', '%' . $freetext . '%');
    //             $q->orWhere('raw_material_value', 'like', '%' . $freetext . '%');
    //         });
    //     }
    //
    //     if (@$search['company_id']) {
    //         $query->where('company_id', $search['company_id']);
    //     }else{
    //       if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
    //           $ids = array();
    //           foreach (auth()->user()->user_company as $key => $user_company) {
    //               $ids[$key] = $user_company->company_id;
    //           }
    //           $query->whereIn('company_id', $ids);
    //
    //       } else if (auth()->user()->company_id != 0) {
    //           $query->where('company_id', auth()->user()->company_id);
    //       } else {
    //           $query->where('company_id', '<>', 1);
    //       }
    //     }
    //
    //     if (@$search['company_land_id']) {
    //         $query->where('company_land_id', $search['company_land_id']);
    //     }
    //
    //     if (@$search['raw_material_id']) {
    //         $query->where('raw_material_id', $search['raw_material_id']);
    //     }
    //
    //     $query->get();
    //     $result = $query->orderBy('raw_material_company_id', 'asc')->paginate(10);
    //     // dd($result);
    //     return $result;
    // }

    public function formula_usage()
    {
        return $this->belongsTo(FormulaUsage::class, 'formula_usage_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
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
