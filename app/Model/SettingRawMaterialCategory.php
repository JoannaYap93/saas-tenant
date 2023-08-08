<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingRawMaterialCategory extends Model
{
    protected $table = 'tbl_raw_material_category';
    protected $primaryKey = 'raw_material_category_id';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'raw_material_category_name'
    ];

    public static function get_records($search)
    {
        $query = SettingRawMaterialCategory::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('raw_material_category_name', 'like', '%' . $freetext . '%');
                });
        }

        $query->orderBy('raw_material_category_id', 'DESC');

        $result = $query->paginate(10);

        return $result;
    }

    public static function get_material_category_for_report(){

        $result = SettingRawMaterialCategory::query()->get();

        return $result;
    }

    public static function get_category_sel()
    {
        $query = SettingRawMaterialCategory::query()->get();
        $result = ['' => "Please Select Category"];

        foreach($query as $key => $category)
        {
          $result[$category->raw_material_category_id] = json_decode($category->raw_material_category_name)->en;
        }

        return $result;
    }

    public static function raw_material_category_checkbox_sel()
    {
        $query = SettingRawMaterialCategory::query()->orderBy('raw_material_category_name')->get();

        foreach($query as $raw_material_categories)
        {
          $result[$raw_material_categories->raw_material_category_id] = $raw_material_categories->raw_material_category_name;
        }

        return $result;
    }
}
