<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SyncCompanyExpense extends Model
{
    // use InteractsWithMedia;

    protected $table = 'tbl_sync_company_expense';
    protected $primaryKey = 'sync_company_expense_id';
    const CREATED_AT = 'sync_company_expense_created';
    const UPDATED_AT = 'sync_company_expense_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_expense_category_id',
        'sync_company_expense_number',
        'company_land_id',
        'user_id',
        'sync_company_expense_total',
        'company_id',
        'sync_company_expense_type',
        'sync_company_expense_created',
        'sync_company_expense_updated',
        'sync_company_expense_day',
        'sync_company_expense_month',
        'sync_company_expense_year',
        'company_expense_id',
        'sync_id',
    ];

    public static function get_records($search)
    {
        $query = SyncCompanyExpense::with('sync_company_expense_item');
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('sync_company_expense_number', 'like', '%' . $freetext . '%');
                });
        }

        if (isset($search['company_id'])) {
            $query->where('company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('company_id', auth()->user()->company_id);
            } else {
                $query->where('company_id', '<>', 1);
            }
        }

        if(isset($search['company_land_id'])){
            $query->where('company_land_id', $search['company_land_id']);
        }

        if(isset($search['expense_category_id'])){
            $query->where('setting_expense_category_id', $search['expense_category_id']);
        }

        if(isset($search['comp_expense_type'])){
            $query->where('sync_company_expense_type', $search['comp_expense_type']);
        }

        if(isset($search['expense_id'])){
            $query->where('setting_expense_id', $search['expense_id']);
        }

        if(isset($search['user_id'])){
            $query->where('user_id', $search['user_id']);
        }

        if(isset($search['sync_id'])){
            $query->where('sync_id', $search['sync_id']);
        }

        $query = $query->where('sync_company_expense_status', '!=' , 'deleted');

        $query = $query->orderBy('sync_company_expense_id', 'desc');

        $query = $query->paginate(10);



        return $query;
    }

    public static function get_sync_company_expense_count($sync_id)
    {
        $result = 0;

        $query = SyncCompanyExpense::query()
                            ->selectRaw('COUNT(sync_company_expense_id) as sync_company_expense_count')
                            ->where('sync_id', $sync_id)
                            ->groupBy('sync_id')
                            ->first();

        if(!empty($query))
        {
            $result = $query->sync_company_expense_count;
        }

        return $result;
    }

    public static function get_date($sync_id)
    {
        $array = array();

        $query = SyncCompanyExpense::query()
                ->selectRaw('sync_id, DATE(sync_company_expense_created) as company_expense_created,
                            sync_company_expense_day as company_expense_day,
                            sync_company_expense_month as company_expense_month,
                            sync_company_expense_year as company_expense_year')
                ->where('sync_id', $sync_id)
                ->get();

        if(!empty($query))
        {
            foreach($query as $result)
            {
                if(isset($array[$result->sync_id]))
                {
                    if(isset($array[$result->sync_id]['company_expense_created']) && !str_contains($array[$result->sync_id]['company_expense_created'], $result->company_expense_created)){
                        $array[$result->sync_id]['company_expense_created'] .= "<br>" . $result->company_expense_created;
                    }

                    if(isset($array[$result->sync_id]['company_expense_date']) && !str_contains($array[$result->sync_id]['company_expense_date'], (date('Y-m-d', strtotime($result->company_expense_year . "-" . $result->company_expense_month . "-" .  $result->company_expense_day))))){
                        $array[$result->sync_id]['company_expense_date'] .= "<br>" . date('Y-m-d', strtotime($result->company_expense_year . "-" . $result->company_expense_month . "-" .  $result->company_expense_day));
                    }

                }else{

                    $array[$result->sync_id]['company_expense_created'] = $result->company_expense_created;
                    $array[$result->sync_id]['company_expense_date'] = date('Y-m-d', strtotime($result->company_expense_year . "-" . $result->company_expense_month . "-" .  $result->company_expense_day));
                }

            }
        }
        return $array;
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    // public function company_expense_land()
    // {
    //     return $this->hasMany(CompanyExpenseLand::class, 'company_expense_id');
    // }

    public function company_expense_land()
    {
        return $this->hasMany(CompanyExpenseLand::class, 'company_expense_id', 'company_expense_id');
    }

    public function expense_category()
    {
        return $this->belongsTo(SettingExpenseCategory::class, 'setting_expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sync_company_expense_item(){
        return $this->hasMany(SyncCompanyExpenseItem::class, 'sync_company_expense_id', 'sync_company_expense_id');
    }

    public function sync_company_expense_worker(){
        return $this->hasMany(SyncCompanyExpenseWorker::class, 'sync_company_expense_id', 'sync_company_expense_id');
    }
}
