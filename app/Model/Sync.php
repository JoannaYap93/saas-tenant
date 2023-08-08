<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Sync extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $table = 'tbl_sync';
    protected $primaryKey = 'sync_id';
    const CREATED_AT = 'sync_created';
    const UPDATED_AT = 'sync_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'user_id',
        'company_id',
        'sync_file_identity',
        'is_reverted',
    ];

    public static function get_records($search)
    {
        $query = Sync::query()
            ->with([
                'user',
                'company.company_land',
                'sync_collect.company_land',
                'sync_delivery_order.company_land',
                'sync_formula_usage.company_land',
                'sync_company_expense.company_land'
                ])
            ->whereHas('company', function($q){
                    $q->where('is_display', '=', 1);
                });

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhereHas('company', function($q2) use ($freetext){
                    $q2->where('company_name', 'like', '%' . $freetext . '%');
                });
                $q->orWhereHas('user', function($q2) use ($freetext){
                    $q2->where('user_fullname', 'like', '%' . $freetext . '%');
                });
                $q->orWhere('sync_id', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['sync_id']) {
            $query->where('sync_id', $search['sync_id']);
        }

        if (@$search['company_id']) {
            $query->whereHas('company', function ($q) use($search){
                $q->where('company_id', $search['company_id']);
            });
        }

        if (@$search['company_land_id']) {
            $query->where(function ($q) use ($search){
                $q->orWhereHas('sync_delivery_order', function($q2) use($search){
                    $q2->where('company_land_id', $search['company_land_id']);
                });
                $q->orwhereHas('sync_collect', function ($q2) use($search){
                    $q2->where('company_land_id', $search['company_land_id']);
                });
            });
        }

        if(@$search['user_id']){
            $query->where('user_id', '=', $search['user_id']);
        }

        if (@$search['sync_from']) {
            $query->where('sync_created', '>=', $search['sync_from'] . ' 00:00:00');
        }

        if (@$search['sync_from']) {
            $query->where('sync_created', '<=', $search['sync_to'] . ' 23:59:59');
        }

        // if (auth()->user()->company_id != 0) {
        //     $query->where('company_id', auth()->user()->company_id);
        // }
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
          $ids = array();
          foreach(auth()->user()->user_company as $key => $user_company){
            // $company->where('company_id', $user_company->company_id);
            $ids[$key] = $user_company->company_id;
            // dd($ids[$key]);
          }
          $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $query->where('company_id', auth()->user()->company_id);
        }

        $query->orderBy('sync_created', 'DESC');

        $result = $query->paginate(10);

        return $result;
    }

    public static function get_sync_details($search)
    {
        $array = array();


        $data = Sync::get_records($search);


        foreach($data as $records)
        {
            $array[$records->sync_id] = [
                'do_count' => DeliveryOrder::get_do_count($records->sync_id),
                'sync_do_count' => SyncDeliveryOrder::get_count($records->sync_id),
                'collect_count' => Collect::get_count($records->sync_id),
                'sync_collect_count' => SyncCollect::get_count($records->sync_id),
                'formula_usage_count' => FormulaUsage::get_formula_usage_count($records->sync_id),
                'sync_formula_usage_count' => SyncFormulaUsage::get_sync_formula_usage_count($records->sync_id),
                'company_expense_count' => CompanyExpense::get_company_expense_count($records->sync_id),
                'sync_company_expense_count' => SyncCompanyExpense::get_sync_company_expense_count($records->sync_id),
                'sync_do_date' => SyncDeliveryOrder::get_date($records->sync_id),
                'sync_collect_date' => SyncCollect::get_date($records->sync_id),
                'sync_formula_usage_date' => SyncFormulaUsage::get_date($records->sync_id),
                'sync_company_expense_date' => SyncCompanyExpense::get_date($records->sync_id),
                'is_revertable' => DeliveryOrder::check_is_revertable($records->sync_id)
            ];
        }

        return $array;
    }

    public function getZipFilesMediaUrlAttribute()
    {
        return $this->getFirstMediaUrl('zip_files');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function sync_collect()
    {
        return $this->hasMany('App\Model\SyncCollect', 'sync_id');
    }

    public function sync_delivery_order()
    {
        return $this->hasMany('App\Model\SyncDeliveryOrder', 'sync_id');
    }

    public function sync_formula_usage()
    {
        return $this->hasMany('App\Model\SyncFormulaUsage', 'sync_id');
    }

    public function sync_company_expense()
    {
        return $this->hasMany('App\Model\SyncCompanyExpense', 'sync_id');
    }
}
