<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class SettingFormulaCategory extends Model
{
    protected $table = 'tbl_setting_formula_category';
    protected $primaryKey = 'setting_formula_category_id';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_formula_category_name', 'is_budget_limited', 'setting_formula_category_budget'
    ];

    public static function get_records($search)
    {
        $query = SettingFormulaCategory::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('setting_formula_category_name', 'like', '%' . $freetext . '%');
                });
        }

        $query->orderBy('setting_formula_category_id', 'DESC');
        $result = $query->paginate(10);

        return $result;
    }

    public static function get_formula_category_sel()
    {
        $result = array();

        $query = SettingFormulaCategory::query();

        $result = $query->orderBy('setting_formula_category_name')->get();
        $data = ['' => 'Please Select Category'];
        if ($result) {
            foreach($result as $row){
                $data[$row->setting_formula_category_id] = json_decode($row->setting_formula_category_name)->en;
            }
        }
        return $data;
    }

    public static function get_selection(){
        return SettingFormulaCategory::get()->pluck('setting_formula_category_name','setting_formula_category_id')->toArray();
    }
}
