<?php

namespace App\Model;

// use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use Log;

class FormulaUsageItem extends Model
{
    protected $table = 'tbl_formula_usage_item';
    protected $primaryKey = 'formula_usage_item_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'formula_usage_item_created';
    const UPDATED_AT = 'formula_usage_item_updated';


    protected $fillable = [
        'formula_usage_id', 'raw_material_id', 'formula_usage_item_qty',
        'formula_usage_item_value', 'formula_usage_item_rounding', 'formula_usage_item_total',
        'formula_usage_item_unit_price', 'formula_usage_item_total_price'
    ];

    // public static function get_records($search)
    // {
    //   // dd($search);
    //     $query = RawMaterialCompany::query();
    //     // $query->join('tbl_setting_warehouse', 'tbl_setting_warehouse.warehouse_id', 'tbl_product_stock_warehouse.warehouse_id');
    //
    //     if (@$search['freetext']) {
    //         $freetext = $search['freetext'];
    //         $query->where(function ($q) use ($freetext) {
    //             $q->where('raw_material_quantity', 'like', '%' . $freetext . '%');
    //             $q->orWhere('raw_material_value', 'like', '%' . $freetext . '%');
    //         });
    //     }
    //
    //     if (@$search['company_id']) {
    //         $query->where('company_id', $search['company_id']);
    //     }else{
    //       if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
    //           $ids = array();
    //           foreach (auth()->user()->user_company as $key => $user_company) {
    //               $ids[$key] = $user_company->company_id;
    //           }
    //           $query->whereIn('company_id', $ids);
    //
    //       } else if (auth()->user()->company_id != 0) {
    //           $query->where('company_id', auth()->user()->company_id);
    //       } else {
    //           $query->where('company_id', '<>', 1);
    //       }
    //     }
    //
    //     if (@$search['company_land_id']) {
    //         $query->where('company_land_id', $search['company_land_id']);
    //     }
    //
    //     if (@$search['raw_material_id']) {
    //         $query->where('raw_material_id', $search['raw_material_id']);
    //     }
    //
    //     $query->get();
    //     $result = $query->orderBy('raw_material_company_id', 'asc')->paginate(10);
    //     // dd($result);
    //     return $result;
    // }
    public static function get_by_formula_id($formula_usage_id, $user_id)
    {
        $query = FormulaUsageItem::selectRaw('*,tbl_raw_material.raw_material_name as raw_material_name');
        $query->join('tbl_raw_material', 'tbl_raw_material.raw_material_id', 'tbl_formula_usage_item.raw_material_id');
        $query->where('formula_usage_id', $formula_usage_id);
        $result = $query->get();
        // dd($result);
        $query2 = FormulausageItem::selectRaw('sum(formula_usage_item_rounding) as sum, tbl_user.user_fullname as user_fullname');
        $query2->join('tbl_formula_usage', 'tbl_formula_usage.formula_usage_id', 'tbl_formula_usage_item.formula_usage_id');
        $query2->join('tbl_user', 'tbl_user.user_id', 'tbl_formula_usage.user_id');
        $query2->where('tbl_formula_usage.user_id', $user_id);
        $query2->where('tbl_formula_usage.formula_usage_id', $formula_usage_id);
        $total_rounding = $query2->get();
        // dd($total_rounding);
        return ['result' => $result, 'total_rounding' => $total_rounding];
    }

    public function raw_material()
    {
        return $this->belongsTo(SettingRawMaterial::class, 'raw_material_id');
    }

    public function formula_usage()
    {
        return $this->belongsTo(FormulaUsage::class, 'formula_usage_id');
    }
}
