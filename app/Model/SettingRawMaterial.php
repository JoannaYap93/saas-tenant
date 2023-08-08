<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingRawMaterial extends Model
{
    protected $table = 'tbl_raw_material';
    protected $primaryKey = 'raw_material_id';
    const CREATED_AT = 'raw_material_created';
    const UPDATED_AT = 'raw_material_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'raw_material_name',
        'raw_material_category_id',
        'raw_material_status',
        'raw_material_quantity_unit',
        'raw_material_value_unit'
    ];

    public static function get_records($search)
    {
        $query = SettingRawMaterial::query();
        // ->whereHas('company', function($q){
        //     $q->where('is_display', '=', 1);
        //   });
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('raw_material_name', 'like', '%' . $freetext . '%');
                $q->orWhere('raw_material_quantity_unit', 'like', '%' . $freetext . '%');
                $q->orWhere('raw_material_value_unit', 'like', '%' . $freetext . '%');
                $q->orWhereHas('setting_raw_category', function ($q2) use ($freetext) {
                    $q2->where('raw_material_category_name', 'like', '%' . $freetext . '%');
                });
            });
        }

        if (@$search['raw_material_quantity_unit']) {
            $query->where('raw_material_quantity_unit', $search['raw_material_quantity_unit']);
        }

        if (@$search['raw_material_value_unit']) {
            $query->where('raw_material_value_unit', $search['raw_material_value_unit']);
        }

        if (@$search['raw_material_category_id']) {
            $query->where('raw_material_category_id', $search['raw_material_category_id']);
        }

        $query->orderBy('raw_material_created', 'DESC');

        $result = $query->paginate(10);

        // foreach($result as $row){
        //     dump($row);
        //     $company_list = array();
        //     foreach($row->raw_material_company as $raw_material_company){
        //         array_push($company_list, $raw_material_company->company->company_name);
        //     }
        //     $company_list_distinct = array_unique($company_list);
        //     dump($company_list_distinct);
        // }
        // dd('test');


        return $result;
    }

    public static function get_rm_sel()
    {
        $query = SettingRawMaterial::query()->where('raw_material_status', 'active')->get();
        $result = ['' => "Please Select Raw Material"];

        foreach($query as $key => $rm)
        {
          $result[$rm->raw_material_id] = json_decode($rm->raw_material_name)->en;
        }

        return $result;
    }

    public static function get_quantity_sel()
    {
        // $query = SettingRawMaterial::query()->get();
        $result = ['' => "Please Select Quantity Unit", 'bottle' => 'Bottle', 'pack' => 'Pack'];

        // foreach($query as $key => $quantity)
        // {
        //   $result[$quantity->raw_material_quantity_unit] = $quantity->raw_material_quantity_unit;
        // }

        return $result;
    }

    public static function get_value_sel()
    {
        // $query = SettingRawMaterial::query()->get();
        $result = ['' => "Please Select Value Unit", 'litre' => 'Litre', 'kg' => 'KG'];

        // foreach($query as $key => $value)
        // {
        //   $result[$value->raw_material_value_unit] = $value->raw_material_value_unit;
        // }

        return $result;
    }

    public static function get_selection(){

        return SettingRawMaterial::get()->where('raw_material_status', 'active')->pluck('raw_material_name','raw_material_id')->toArray();
    }

    public static function get_selection_2(){

        $query = SettingRawMaterial::query()->where('raw_material_status', 'active')->orderBy('raw_material_name', 'ASC')->get();
        $result = [];
        foreach($query as $key => $rm)
        {
          $result[$rm->raw_material_id] = json_decode($rm->raw_material_name)->en;
        }

        return $result;
    }

    public static function raw_material_checkbox_sel()
    {
        $query = SettingRawMaterial::query()
                ->where('raw_material_status', 'active')
                ->orderBy('raw_material_category_id')
                ->get();

        return $query;
    }

    public static function get_by_raw_material_category_id($raw_material_category_id, $supplier_id, $company_id, $raw_material_name)
    {
        $raw_material_name = $raw_material_name ?? '';

        $raw_material = array();
        $query = SettingRawMaterial::query();
        $query->selectRaw('DISTINCT(tbl_raw_material.raw_material_id), tbl_raw_material.raw_material_name');
        $query->join('tbl_raw_material_category', 'tbl_raw_material_category.raw_material_category_id', '=', 'tbl_raw_material.raw_material_category_id');
        $query->join('tbl_supplier_raw_material', 'tbl_supplier_raw_material.raw_material_id', '=', 'tbl_raw_material.raw_material_id');
        $query->join('tbl_supplier_company', 'tbl_supplier_company.supplier_id', '=', 'tbl_supplier_raw_material.supplier_id');

        if(!is_null($raw_material_category_id))
            $query->where('tbl_raw_material_category.raw_material_category_id', '=', $raw_material_category_id);

        if(!is_null($supplier_id))
            $query->where('tbl_supplier_raw_material.supplier_id', '=', $supplier_id);

        if(!is_null($company_id))
            $query->where('tbl_supplier_company.company_id', '=', $company_id);

        if($raw_material_name != "")
            $query->where('tbl_raw_material.raw_material_name', 'like', '%' . $raw_material_name . '%');

        $result = $query->get();

        if ($result) {
            foreach ($result as $raw_material_list) {
                array_push($raw_material, array(
                    'id' => $raw_material_list->raw_material_id,
                    'value' => json_decode($raw_material_list->raw_material_name)->en,
                ));
            }
        }

        return $raw_material;
    }

    public static function get_raw_material_details($raw_material_id)
    {
        $result = null;

        $query = SettingRawMaterial::query();
        $query->selectRaw('tbl_raw_material.raw_material_id,
                            tbl_raw_material.raw_material_name,
                            tbl_raw_material.raw_material_value_unit,
                            tbl_raw_material.raw_material_quantity_unit,
                            tbl_raw_material_category.raw_material_category_name');
        $query->join('tbl_raw_material_category', 'tbl_raw_material_category.raw_material_category_id', '=', 'tbl_raw_material.raw_material_category_id');
        $query->where('tbl_raw_material.raw_material_id', '=', $raw_material_id);
        $result = $query->first();

        return $result;
    }

    public static function get_material_for_report(){
        $query = SettingRawMaterial::query()
        ->where('raw_material_status', 'active');

        $result = $query->get();

        return $result;
    }

    public function setting_raw_category()
    {
        return $this->belongsTo('App\Model\SettingRawMaterialCategory', 'raw_material_category_id');
    }

    public function raw_material()
    {
        return $this->hasMany('App\Model\SettingRawMaterial', 'raw_material_id');
    }

    public function company()
    {
        return $this->hasMany('App\Model\Company', 'company_id');
    }

    public function raw_material_company()
    {
        return $this->hasMany(RawMaterialCompany::class, 'raw_material_id');
    }

    public function rmc()
    {
        return $this->belongsTo(RawMaterialCompany::class, 'company_id');
    }

    public function supplier()
    {
        return $this->belongsToMany(Supplier::class, 'tbl_supplier_raw_material', 'raw_material_id', 'supplier_id');
    }
}
