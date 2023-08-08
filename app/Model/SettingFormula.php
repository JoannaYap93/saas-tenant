<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class SettingFormula extends Model
{
    protected $table = 'tbl_setting_formula';
    protected $primaryKey = 'setting_formula_id';
    const CREATED_AT = 'setting_formula_created';
    const UPDATED_AT = 'setting_formula_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_formula_name', 'setting_formula_category_id', 'setting_formula_status', 'setting_formula_measurement', 'company_id', 'setting_formula_created', 'setting_formula_updated',  'setting_formula_unit'
    ];

    public static function get_records($search, $perpage)
    {
        $query = SettingFormula::query()->where('setting_formula_status','!=' ,'deleted')
                                        ->whereHas('company', function($q){
                                            $q->where('is_display', '=', 1);
                                        });

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('setting_formula_name', 'like', '%' . $search['freetext'] . '%');
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
                $query->whereIn('tbl_setting_formula.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_setting_formula.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_setting_formula.company_id', '<>', 1);
            }
        }

        if(@$search['setting_formula_category_id'] && @$search['setting_formula_category_id'] > 0){
            $query->where('setting_formula_category_id',$search['setting_formula_category_id']);
        }

        if(@$search['setting_formula_measurement']){
            $query->where('setting_formula_measurement',$search['setting_formula_measurement']);
        }

        if(@$search['setting_formula_status']){
            $query->where('setting_formula_status',$search['setting_formula_status']);
        }

        if (@$search['raw_material_id']) {
            $query->with('setting_formula_item')->whereHas('setting_formula_item', function ($q) use($search){
                $q->where('raw_material_id', $search['raw_material_id']);
            });
        }

        $result = $query->orderBy('setting_formula_name', 'ASC')->paginate($perpage);

        return $result;
    }

    public static function get_by_id($setting_formula_id){
        $query = SettingFormula::query()
                ->with('setting_formula_item');
        return $query
            ->where('setting_formula_id', $setting_formula_id)
            ->where('setting_formula_status','!=' ,'deleted')
            ->first();
    }

    public static function get_measurement_sel()
    {
        $query = SettingFormula::query();

        $result= $query->get();
        $temp[''] = 'Please Select Measurement';
        foreach ($result as $value) {
            $temp[$value->setting_formula_measurement] = $value->setting_formula_measurement;
        }

        return $temp;
    }

    public static function get_status_sel()
    {
        $query = SettingFormula::query();

        $result= $query->get();
        $temp[''] = 'Please Select Status';
        foreach ($result as $value) {
            $temp[$value->setting_formula_status] = $value->setting_formula_status;
        }

        return $temp;
    }

    public static function get_formula_sel()
    {
        $query = SettingFormula::query()->where('setting_formula_status', 'active')->get();
        $result = ['' => "Please Select Formula"];

        foreach($query as $key => $formula)
        {
          $result[$formula->setting_formula_id] = $formula->setting_formula_name;
        }

        return $result;
    }

    public static function get_by_company_sel()
    {
        $query = SettingFormula::query();
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        }

        $output = $query->get();
        $result = ['' => "Please Select Formula"];
        foreach($output as $key => $sf)
        {
          $result[$sf->setting_formula_id] = $sf->setting_formula_name;
        }
    }

    public static function get_formula_by_category($setting_category_id, $company_id)
    {
      $query = SettingFormula::query()
                // ->join('tbl_setting_formula_item', 'tbl_setting_formula_item.setting_formula_id', 'tbl_setting_formula.setting_formula_id')
                // ->join('tbl_raw_material_company', 'tbl_raw_material_company.raw_material_id', 'tbl_setting_formula_item.raw_material_id')
                ->where('tbl_setting_formula.setting_formula_category_id', $setting_category_id)
                ->where('tbl_setting_formula.company_id', $company_id)
                // ->where('tbl_raw_material_company.company_id', $company_id)
                // ->where('tbl_raw_material_company.company_land_id', $company_land_id)
                ->where('tbl_setting_formula.setting_formula_status', 'active')
                ->get();

      $arr = [];
      foreach ($query as $key => $value) {
        $empty_count = 0;

        // dump($value->setting_formula_item);
        foreach ($value->setting_formula_item as $key2 => $value2) {
          // dump($value2);
          $check_stock_value = RawMaterialCompany::where('raw_material_id', $value2->raw_material_id)
                                ->where('company_id', $company_id)
                                ->first();
                                // dump($check_stock_value);
          if($check_stock_value == null || $check_stock_value->raw_material_value <= 0){
            $empty_count++;
          }
        }

        if($empty_count == 0){
          array_push($arr, $value);
        }
      }
      // dd('stop');
      // dd($arr);
      return ['data' => $arr];
    }

    public static function get_setting_formula($raw_material_id)
    {
        $setting_formula = SettingFormula::where('setting_formula_id', $raw_material_id)->get();

        return $setting_formula;
    }

    public function setting_formula_item()
    {
        return $this->hasMany(SettingFormulaItem::class, 'setting_formula_id');
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    public function setting_formula_category()
    {
        return $this->belongsTo(SettingFormulaCategory::class, 'setting_formula_category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function raw_material_company()
    {
        return $this->belongsTo(RawMaterialCompany::class, 'company_id');
    }
}
