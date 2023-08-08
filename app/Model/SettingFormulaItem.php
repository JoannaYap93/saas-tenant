<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class SettingFormulaItem extends Model
{
    protected $table = 'tbl_setting_formula_item';
    protected $primaryKey = 'setting_formula_item_id';
    const CREATED_AT = 'setting_formula_item_created';
    const UPDATED_AT = 'setting_formula_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_formula_id', 'setting_formula_item_name', 'raw_material_id', 'setting_formula_item_value', 'setting_formula_item_created', 'setting_formula_item_updated'
    ];

    public static function get_setting_formula_Item($company_id, $setting_formula_id)
    {
        $raw_material = [];
        $query = SettingFormulaItem::query()
                ->join('tbl_raw_material', 'tbl_raw_material.raw_material_id', 'tbl_setting_formula_item.raw_material_id')
                ->join('tbl_raw_material_company', 'tbl_raw_material_company.raw_material_id', 'tbl_setting_formula_item.raw_material_id')
                ->where('tbl_raw_material_company.company_id', $company_id)
                ->where('tbl_raw_material_company.raw_material_company_status', '=', 'active')
                // ->whereHas('raw_material_company', function($q) use($company_id, $company_land_id){
                //   $q->where('company_id', $company_id);
                //   $q->where('company_land_id', $company_land_id);
                //   $q->where('raw_material_company_status', '=', 'active');
                // })
                ->where('tbl_setting_formula_item.setting_formula_id', $setting_formula_id)
                ->get();
        foreach($query as $key => $value){
            $raw_material[$key] = ['unit' => $value->setting_formula->setting_formula_unit,
                                    'value' => $value->setting_formula->setting_formula_measurement,
                                    'item' => $value];
        }
        return ['data' => $raw_material];
    }

    public static function get_setting_formula($raw_material_id)
    {
        $setting_formula = SettingFormulaItem::where('raw_material_id', $raw_material_id)->get();

        return $setting_formula;
    }

    public function setting_formula()
    {
        return $this->belongsTo(SettingFormula::class, 'setting_formula_id');
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    // public function raw_material_company()
    // {
    //     return $this->belongsTo(RawMaterialCompany::class, 'raw_material_id');
    // }

}
