<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyPnlItem extends Model{

    protected $table = 'tbl_company_pnl_item';
    protected $primaryKey = 'company_pnl_item_id';
    const CREATED_AT = 'company_pnl_item_created';
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_pnl_item_name',
        'company_pnl_item_code',
        'company_pnl_item_desc',
        'company_pnl_item_type',
        'company_pnl_item_json'
    ];

    public static function get_record($search, $perpage)
    {
        $query = CompanyPnlItem::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('company_pnl_item_name', 'like', '%' . $freetext . '%');
            });
        }

        $query->orderBy('company_pnl_item_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }
}
?>
