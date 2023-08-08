<?php

namespace App\Model;

// use DOMDocument;
use Log;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class RawMaterialCompanyUsage extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_raw_material_company_usage';
    protected $primaryKey = 'raw_material_company_usage_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    // public $timestamps = false;
    const CREATED_AT = 'raw_material_company_usage_created';
    const UPDATED_AT = 'raw_material_company_usage_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
        'raw_material_id', 'raw_material_company_id', 'raw_material_company_usage_type',
        'raw_material_company_usage_total_value', 'raw_material_company_usage_qty', 'raw_material_company_usage_price_per_qty',
        'raw_material_company_usage_total_price', 'user_id', 'formula_usage_id', 'formula_usage_item_id',
        'raw_material_company_usage_value_per_qty', 'unit_price_per_value', 'raw_material_company_usage_total_value_remaining',
        'is_claim', 'claim_user_id', 'raw_material_company_usage_date','claim_worker_id', 'claim_remaining_amount'
    ];

    public static function get_records($search)
    {
      // dd($search);
        $query = RawMaterialCompanyUsage::query()->with('raw_material_company');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->whereHas('raw_material_company', function ($q) use($freetext){
                    $q->whereHas('company', function ($q2) use ($freetext) {
                        $q2->where('company_name', 'like', '%' . $freetext . '%');
                    });
                });
            });
        }

        if (@$search['company_id']) {
          $query->whereHas('raw_material_company', function ($q) use($search){
              $q->where('tbl_raw_material_company.company_id', $search['company_id']);
          });
        }else{
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              $ids = array();
              foreach (auth()->user()->user_company as $key => $user_company) {
                  $ids[$key] = $user_company->company_id;
              }
              $query->whereHas('raw_material_company', function ($q) use($ids){
                  $q->whereIn('tbl_raw_material_company.company_id', $ids);
              });
          } else if (auth()->user()->company_id != 0) {
            $com_id = auth()->user()->company_id;
            $query->whereHas('raw_material_company', function ($q) use($com_id){
                $q->where('tbl_raw_material_company.company_id', $com_id);
            });
          } else {
            $query->whereHas('raw_material_company', function ($q) use($search){
                $q->where('tbl_raw_material_company.company_id', '<>', 1);
            });
          }
        }

        if (@$search['company_land_id']) {
          $query->whereHas('raw_material_company', function ($q) use($search){
              $q->where('company_land_id', $search['company_land_id']);
          });
        }

        if (@$search['raw_material_id']) {
            $query->where('raw_material_id', $search['raw_material_id']);
        }

        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if(@$search['raw_material_company_usage_id']){
            $query->whereIn('raw_material_company_usage_id', $search['raw_material_company_usage_id']);
        }

        $query->get();
        $result = $query->orderBy('raw_material_company_usage_created', 'desc')->paginate(10);
        // dd($result);
        return $result;
    }
    public static function check_existing_rmc($raw_material_id, $raw_material_company_id, $company_id, $company_land_id)
    {
        $result = false;
        $query = RawMaterialCompany::query();
        if($raw_material_id && $company_id){
          $query->where('raw_material_id', $raw_material_id);
          $query->where('raw_material_company_id', '!=', $raw_material_company_id);
          $query->where('company_id', $company_id);
          $rmc = $query->where('company_land_id', $company_land_id)->get();
          if(count($rmc) > 0){
            $result = true;
          }else{
            $result = false;
          }
        }
        $query2 = SettingRawMaterial::find($raw_material_id);
        return ['result' => $result, 'data' => $query2];
    }

    public static function check_existing_rmc_formula_usage($raw_material_id, $company_id, $company_land_id)
    {
        $query = RawMaterialCompany::query();
        if($raw_material_id && $company_id){
          $query->where('raw_material_id', $raw_material_id);
          // $query->where('raw_material_company_id', '!=', $raw_material_company_id);
          $query->where('company_id', $company_id);
          $rmc = $query->first();

        }
        // $query2 = SettingRawMaterial::find($raw_material_id);
        return $rmc;
    }

    public static function get_by_claim($claim){
        $raw_material_company_usage_id = ClaimItem::where([
            'claim_item_type' => 'raw_material_company_usage_id',
            'is_deleted' => 0
        ])->pluck('claim_item_type_value');

        // dd($raw_material_company_usage_id);
        $month_data = Carbon::parse($claim->claim_start_date)->format('m');
        $year_data = Carbon::parse($claim->claim_start_date)->format('Y');
        $month = ltrim($month_data, "0");


        $query = RawMaterialCompanyUsage::with('raw_material_company', 'raw_material')
                ->whereHas('raw_material_company', function($q) use($claim, $month, $year_data){
                    $q->where('company_id',$claim->company_id)
                        ->whereMonth('raw_material_company_usage_date', $month)
                        ->whereYear('raw_material_company_usage_date', $year_data);
                });

                if($raw_material_company_usage_id){
                    $query->whereNotIn('raw_material_company_usage_id',$raw_material_company_usage_id);
                }

        $query->where('is_claim', 1)
        ->where('claim_worker_id', $claim->worker_id)
        ->where('raw_material_company_usage_type','stock in')
        ->where('claim_remaining_amount', '>', 0);

        return $query->get();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    public function raw_material_company()
    {
        return $this->belongsTo(RawMaterialCompany::class, 'raw_material_company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
