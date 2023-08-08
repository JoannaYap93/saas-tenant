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

class RawMaterialCompany extends Model
{
    // use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_raw_material_company';
    protected $primaryKey = 'raw_material_company_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    // public $timestamps = false;
    const CREATED_AT = 'raw_material_company_created';
    const UPDATED_AT = 'raw_material_company_updated';

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('product_name')
    //         ->saveSlugsTo('product_slug');
    // }

    protected $fillable = [
        'raw_material_id', 'company_id', 'company_land_id', 'raw_material_quantity', 'raw_material_value', 'raw_material_company_status'
    ];

    public static function get_records($search)
    {
        $query = RawMaterialCompany::selectRaw('*,GROUP_CONCAT(raw_material_company_id) as raw_material_company_ids')
                                    ->whereHas('company', function($q){
                                        $q->where('is_display', '=', 1);
                                    })
                                    ->where('tbl_raw_material_company.raw_material_id','!=',null)
                                    ->groupBy('tbl_raw_material_company.raw_material_id', 'tbl_raw_material_company.company_id');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                    $q->where('raw_material_quantity', 'like', '%' . $freetext . '%');
                    $q->orWhere('raw_material_value', 'like', '%' . $freetext . '%');

                    $q->orWhereHas('raw_material', function ($q2) use ($freetext) {
                        $q2->where('raw_material_name', 'like', '%' . $freetext . '%');
                    });

                    $q->orWhereHas('raw_material', function($q2) use($freetext){
                        $q2->whereHas('setting_raw_category', function($q3) use($freetext){
                        $q3->where('raw_material_category_name', 'like', '%' . $freetext . '%');
                    });
                });
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

        if (@$search['company_land_id']) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (@$search['raw_material_id']) {
            $query->where('raw_material_id', $search['raw_material_id']);
        }

        if (@$search['raw_material_category_id']) {
            $query->whereHas('raw_material', function ($q) use($search){
                $q->where('raw_material_category_id', $search['raw_material_category_id']);
            });
        }

        $result = $query->paginate(10);

        return $result;
    }

    public static function check_existing_rmc($raw_material_id, $raw_material_company_id, $company_id)
    {
        $result = false;
        $query = RawMaterialCompany::query();
        if($raw_material_id && $company_id){
          $query->where('raw_material_id', $raw_material_id);
          $query->where('raw_material_company_id', '!=', $raw_material_company_id);
          $query->where('company_id', $company_id);
          $rmc = $query->get();
          if(count($rmc) > 0){
            $result = true;
          }else{
            $result = false;
          }
        }
        $query2 = SettingRawMaterial::find($raw_material_id);
        return ['result' => $result, 'data' => $query2];
    }

    public static function get_by_company_land($company_id, $raw_material_id)
    {
      $result = false;
      $raw_material = null;
      $query = RawMaterialCompany::where('company_id', $company_id)
              ->where('raw_material_company_status', '=', 'active')
              ->where('raw_material_id', $raw_material_id)
              ->first();
      if($query){
        $result = true;
        $raw_material = $query->raw_material;
        // dd($raw_material);
      }
      // foreach($query as $key => $value){
      //   $result[$value->raw_material_id] = $value->raw_material->raw_material_name;
      // }
      // dd($query);
      return ['data' => $query, 'result' => $result, 'raw_material' => $raw_material];
    }

    public static function get_by_company_sel($company_id)
    {

      $query = RawMaterialCompany::where('raw_material_company_status', '=', 'active')
              ->where('company_id', $company_id)
              ->get();

      foreach($query as $key => $value){
        $result[$value->raw_material_id] = $value->raw_material->raw_material_name;
      }
    //   dd($query);

      return $result;
    }


    public static function get_raw_material($raw_material_id)
    {
        $raw_material = RawMaterialCompany::where('raw_material_id', $raw_material_id)->get();

        return $raw_material;
    }

    public function setting_raw_category()
    {
        return $this->belongsTo('App\Model\SettingRawMaterialCategory', 'raw_material_category_id');
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

    public function getSubRawMaterialCompanyAttribute(){
        $result = null;
        if(!empty($this->raw_material_company_ids)){
            $raw_material_company_ids = explode(",",$this->raw_material_company_ids);
            $result = RawMaterialCompany::query()
                    ->whereIn('raw_material_company_id',$raw_material_company_ids)->get();
        }
        return $result;
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
