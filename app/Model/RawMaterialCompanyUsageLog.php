<?php

namespace App\Model;

// use DOMDocument;
use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;
use Log;

class RawMaterialCompanyUsageLog extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_raw_material_company_usage_log';
    protected $primaryKey = 'raw_material_company_usage_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;
    // const CREATED_AT = 'raw_material_company_created';
    // const UPDATED_AT = 'raw_material_company_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
      'raw_material_company_usage_id', 'user_id', 'raw_material_company_usage_log_action', 'raw_material_company_usage_log_created',
      'raw_material_company_usage_log_description', 'raw_material_company_usage_log_value_before',
      'raw_material_company_usage_log_value_after', 'formula_usage_id', 'formula_usage_item_id',
    ];

}
