<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyPnlSubItem extends Model{

    protected $table = 'tbl_company_pnl_sub_item';
    protected $primaryKey = 'company_pnl_sub_item_id';
    // const CREATED_AT = 'company_pnl_item_created';
    // const UPDATED_AT = null;
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    protected $fillable = [
        'company_pnl_sub_item_name',
        'company_pnl_item_id',
        'company_pnl_sub_item_code',
    ];

    public static function get_record($search)
    {
        $query = CompanyPnlItem::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('company_pnl_item_name', 'like', '%' . $freetext . '%');
            });
        }

        $query->orderBy('company_pnl_item_id', 'ASC');

        $result = $query->paginate(10);

        return $result;
    }

    public static function get_company_pnl_sub_item_sel(){
        $query = CompanyPnlSubItem::query()->get();
        $array[''] = 'Please Select Profit & Loss Sub Item...';
        foreach ($query as $item) {
            $array[$item->company_pnl_sub_item_id] = $item->company_pnl_sub_item_name;
        }
        return $array;
    }

    public static function get_company_pnl_sub_item_code_sel(){
        $query = CompanyPnlSubItem::query()->get();
        $array[''] = 'Please Select Pnl Code';
        foreach ($query as $item) {
            $array[$item->company_pnl_sub_item_code] = $item->company_pnl_sub_item_name;
        }
        return $array;
    }

    public function company_land_tree(){
        return $this->hasMany(CompanyLandTree::class, 'company_pnl_sub_item_code','company_pnl_sub_item_code');
    }
}
?>
