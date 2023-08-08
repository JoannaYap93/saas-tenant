<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SyncCompanyExpenseWork extends Model
{
    // use InteractsWithMedia;

    protected $table = 'tbl_sync_company_expense_work';
    protected $primaryKey = 'sync_company_expense_work_id';
    const CREATED_AT = 'sync_company_expense_work_created';
    const UPDATED_AT = 'sync_company_expense_work_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'worker_id',
        'sync_company_expense_id',
        'sync_company_expense_worker_detail',
        'sync_company_expense_worker_total',
        'company_expense_worker_id',
    ];

    // public static function get_sync_company_expense_count($sync_id)
    // {
    //     $result = 0;
    //
    //     $query = SyncCompanyExpense::query()
    //                         ->selectRaw('COUNT(sync_company_expense_id) as sync_company_expense_count')
    //                         ->where('sync_id', $sync_id)
    //                         ->groupBy('sync_id')
    //                         ->first();
    //
    //     if(!empty($query))
    //     {
    //         $result = $query->sync_company_expense_count;
    //     }
    //
    //     return $result;
    // }
    //
    // public static function get_date($sync_id)
    // {
    //     $array = array();
    //
    //     $query = SyncCompanyExpense::query()
    //             ->selectRaw('sync_id, DATE(sync_company_expense_created) as company_expense_created,
    //                         sync_company_expense_day as company_expense_day,
    //                         sync_company_expense_month as company_expense_month,
    //                         sync_company_expense_year as company_expense_year')
    //             ->where('sync_id', $sync_id)
    //             ->get();
    //
    //     if(!empty($query))
    //     {
    //         foreach($query as $result)
    //         {
    //             if(isset($array[$result->sync_id]))
    //             {
    //                 if(isset($array[$result->sync_id]['company_expense_created']) && !str_contains($array[$result->sync_id]['company_expense_created'], $result->company_expense_created)){
    //                     $array[$result->sync_id]['company_expense_created'] .= "<br>" . $result->company_expense_created;
    //                 }
    //
    //                 if(isset($array[$result->sync_id]['company_expense_date']) && !str_contains($array[$result->sync_id]['company_expense_date'], $result->company_expense_day, $result->company_expense_month, $result->company_expense_year)){
    //                     $array[$result->sync_id]['company_expense_date'] .= "<br>" . date('Y-m-d', strtotime($result->company_expense_year . "-" . $result->company_expense_month . "-" .  $result->company_expense_day));
    //                 }
    //
    //             }else{
    //
    //                 $array[$result->sync_id]['company_expense_created'] = $result->company_expense_created;
    //                 $array[$result->sync_id]['company_expense_date'] = date('Y-m-d', strtotime($result->company_expense_year . "-" . $result->company_expense_month . "-" .  $result->company_expense_day));
    //             }
    //
    //         }
    //     }
    //     return $array;
    // }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function sync_company_expense()
    {
        return $this->belongsTo(SyncCompanyExpense::class, 'sync_company_expense_id', 'sync_company_expense_id');
    }
}
