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

class FormulaUsage extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_formula_usage';
    protected $primaryKey = 'formula_usage_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    // public $timestamps = false;
    const CREATED_AT = 'formula_usage_created';
    const UPDATED_AT = 'formula_usage_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
        'setting_formula_id', 'user_id', 'company_id', 'company_land_id',
        'company_land_zone_id', 'formula_usage_value', 'formula_usage_status',
        'formula_usage_type', 'formula_usage_date', 'formula_usage_total_price',
        'formula_usage_total_tree', 'formula_usage_average_price_per_tree'
    ];

    public static function get_records($search)
    {
        $query = FormulaUsage::query()
            ->with('setting_formula')
            ->whereHas('company', function($q){
                $q->where('is_display', '=', 1);
            });

        if (@$search['formula_usage_id']) {
            $query->where('formula_usage_id', $search['formula_usage_id']);
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->whereHas('setting_formula', function ($q) use ($freetext) {
                $q->where('setting_formula_name', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }else{
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);

            } else if (auth()->user()->company_id != 0) {
                $query->where('company_id', auth()->user()->company_id);
            } else {
                $query->where('company_id', '<>', 1);
            }
        }
        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if (@$search['company_land_id']) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (@$search['formula_usage_type']) {
            $query->where('formula_usage_type', $search['formula_usage_type']);
        }

        if (@$search['formula_usage_status']) {
            $query->where('formula_usage_status', $search['formula_usage_status']);
        }

        if (@$search['raw_material_id']) {
            $query->whereHas('formula_usage_item', function($q) use($search){
                $q->where('raw_material_id', $search['raw_material_id']);
            });
        }

        $result = $query->orderBy('formula_usage_created', 'desc')->paginate(10);
        return $result;
    }

    public static function formula_status_sel_glob()
    {
      $sel_arr = ['completed' => 'Completed', 'pending' => 'Pending'];

      return $sel_arr;
    }

    public static function formula_type_sel_glob()
    {
      // Default is Man!
      $sel_arr = ['man' => 'Man', 'drone' => 'Drone'];

      return $sel_arr;
    }

    public static function get_formula_usage_count($sync_id)
    {
        $result = 0;

        $query = FormulaUsage::query()
            ->selectRaw('sync_id,COUNT(formula_usage_id) as formula_usage_count')
            ->where('sync_id', $sync_id)
            ->groupBy('sync_id')
            ->first();

        if (!empty($query)) {
            $result = $query->formula_usage_count;
        }

        return $result;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function company_land_zone()
    {
        return $this->belongsTo(CompanyLandZone::class, 'company_land_zone_id');
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    public function setting_formula()
    {
        return $this->belongsTo(SettingFormula::class, 'setting_formula_id');
    }

    public function formula_usage_item()
    {
        return $this->hasMany(FormulaUsageItem::class, 'formula_usage_id');
    }

    public function formula_usage_product()
    {
        return $this->hasMany(FormulaUsageProduct::class, 'formula_usage_id');
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
