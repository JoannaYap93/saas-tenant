<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SyncFormulaUsage extends Model
{
    // use InteractsWithMedia;

    protected $table = 'tbl_sync_formula_usage';
    protected $primaryKey = 'sync_formula_usage';
    const CREATED_AT = 'sync_formula_usage_created';
    const UPDATED_AT = 'sync_formula_usage_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_formula_id',
        'user_id',
        'company_id',
        'company_land_id',
        'sync_formula_usage_value',
        'sync_formula_usage_created',
        'sync_formula_usage_updated',
        'sync_formula_usage_status',
        'sync_formula_usage_type',
        'formula_usage_id',
        'sync_id'
    ];

    public static function get_records($search)
    {
      // dd($search);
      // dd(session()->all());

        $query = SyncFormulaUsage::query();
        $query->with('setting_formula');
        // $query->join('tbl_setting_warehouse', 'tbl_setting_warehouse.warehouse_id', 'tbl_product_stock_warehouse.warehouse_id');
        if (@$search['formula_usage_id']) {
            $query->where('formula_usage_id', $search['formula_usage_id']);
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->whereHas('setting_formula', function ($q) use ($freetext) {
                $q->where('setting_formula_name', 'like', '%' . $freetext . '%');
                // $q->orWhere('raw_material_value', 'like', '%' . $freetext . '%');
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
        //
        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if (@$search['company_land_id']) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (@$search['sync_formula_usage_type']) {
            $query->where('formula_usage_type', $search['formula_usage_type']);
        }

        if (@$search['sync_formula_usage_status']) {
            $query->where('formula_usage_status', $search['formula_usage_status']);
        }
        // if (@$search['raw_material_id']) {
        //     $query->where('raw_material_id', $search['raw_material_id']);
        // }
        if (@$search['sync_id']) {
            $query->where('sync_id', $search['sync_id']);
        }

        $query->get();
        $result = $query->orderBy('sync_formula_usage_created', 'desc')->paginate(10);
        // dd($result);
        return $result;
    }

    public static function get_sync_formula_usage_count($sync_id)
    {
        $result = 0;

        $query = SyncFormulaUsage::query()
                            ->selectRaw('COUNT(sync_formula_usage_id) as sync_formula_usage_count')
                            ->where('sync_id', $sync_id)
                            ->groupBy('sync_id')
                            ->first();

        if(!empty($query))
        {
            $result = $query->sync_formula_usage_count;
        }

        return $result;
    }

    public static function get_date($sync_id)
    {
        $array = array();

        $query = SyncFormulaUsage::query()
                ->selectRaw('sync_id, DATE(sync_formula_usage_created) as formula_usage_created')
                ->where('sync_id', $sync_id)
                ->get();

        if(!empty($query))
        {
            foreach($query as $result)
            {
                if(isset($array[$result->sync_id]))
                {

                    if(isset($array[$result->sync_id]['formula_usage_created']) && !str_contains($array[$result->sync_id]['formula_usage_created'], $result->formula_usage_created)){
                        $array[$result->sync_id]['formula_usage_created'] .= "<br>" . $result->formula_usage_created;
                    }

                    // if(isset($array[$result->sync_id]['collect_date']) && !str_contains($array[$result->sync_id]['collect_date'], $result->collect_date)){
                    //     $array[$result->sync_id]['collect_date'] .= "<br>" . $result->collect_date;
                    // }

                }else{
                    $array[$result->sync_id]['formula_usage_created'] = $result->formula_usage_created;
                    // $array[$result->sync_id]['collect_date'] = $result->collect_date;
                }

            }
        }

        return $array;
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

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    public function setting_formula()
    {
        return $this->belongsTo(SettingFormula::class, 'setting_formula_id');
    }

    public function sync_formula_usage_item()
    {
        return $this->hasMany(SyncFormulaUsageItem::class, 'sync_formula_usage_id', 'sync_formula_usage_id');
    }

    public function sync_formula_usage_product()
    {
        return $this->hasMany(SyncFormulaUsageProduct::class, 'sync_formula_usage_id', 'sync_formula_usage_id');
    }
}
