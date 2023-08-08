<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Models\Role;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CompanyExpense extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'tbl_company_expense';
    protected $primaryKey = 'company_expense_id';
    const CREATED_AT = 'company_expense_created';
    const UPDATED_AT = 'company_expense_updated';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $monthFormat = 'm';
    protected $yearFormat = 'Y';

    protected $fillable = [
        'setting_expense_category_id', 'company_expense_number', 'company_land_id',
         'user_id', 'company_expense_total', 'company_id', 'setting_expense_id',
         'company_expense_type', 'company_expense_updated', 'company_expense_day',
         'company_expense_month', 'company_expense_year', 'company_expense_status',
         'worker_role_id', 'worker_id'
    ];

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function expense_category()
    {
        return $this->belongsTo(SettingExpenseCategory::class, 'setting_expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_expense_item(){
        return $this->hasMany(CompanyExpenseItem::class, 'company_expense_id');
    }

    public function company_expense_worker(){
        return $this->hasMany(CompanyExpenseWorker::class, 'company_expense_id');
    }

    public function company_expense_log()
    {
        return $this->hasMany(CompanyExpenseLog::class, 'company_expense_id');
    }

    public function company_expense_land()
    {
        return $this->hasMany(CompanyExpenseLand::class, 'company_expense_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_expense_item_media')
            // ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    // ->format('jpg')
                    ->apply();
                $this->addMediaConversion('thumb')
                    ->format('jpg')
                    ->crop('crop-center', 300, 300)
                    ->apply();
            });
    }

    public static function get_records($search)
    {
        $query = CompanyExpense::with('company_expense_item.expense')
                                ->whereHas('company', function($q){
                                    $q->where('is_display', '=', 1);
                                });

        if (@$search['freetext']) {
                $freetext = $search['freetext'];
                $query->whereHas('company_expense_item', function ($q1) use ($freetext) {
                    $q1->where('company_expense_number', 'like', '%' . $freetext . '%')
                        ->orWhereHas('expense', function ($q2) use ($freetext) {
                        $q2->where('setting_expense_name', 'like', '%' . $freetext . '%');
                    });
                });
        }

        if (@$search['date_from']) {
            $query->where('company_expense_year', '>=', substr($search['date_from'], 0, 4));
            $query->where('company_expense_month', '>=', substr($search['date_from'], 5, 2));
            $query->where('company_expense_day', '>=', substr($search['date_from'], 8, 2));
        }

        if (@$search['date_to']) {
            $query->where('company_expense_year', '<=', substr($search['date_to'], 0, 4));
            $query->where('company_expense_month', '<=', substr($search['date_to'], 5, 2));
            $query->where('company_expense_day', '<=', substr($search['date_to'], 8, 2));
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

        if(@$search['company_land_id']){
            $company_land_id = $search['company_land_id'];
            $query->whereHas('company_expense_land', function ($q1) use ($search){
                $q1->where('company_land_id', $search['company_land_id']);
            });
        }

        if(isset($search['expense_category_id'])){
            $query->where('setting_expense_category_id', $search['expense_category_id']);
        }

        if(isset($search['comp_expense_type'])){
            $query->where('company_expense_type', $search['comp_expense_type']);
        }

        if(isset($search['expense_id'])){
            $query->where('setting_expense_id', $search['expense_id']);
        }

        if(isset($search['user_id'])){
            $query->where('user_id', $search['user_id']);
        }

        if(@$search['worker_id']){
            $worker_id = $search['worker_id'];
            $query->whereHas('company_expense_worker', function ($q1) use ($search){
                $q1->where('worker_id', $search['worker_id']);
            });
        }

        $query = $query->where('company_expense_status', '!=' , 'deleted');

        $query = $query->orderByRaw('company_expense_year desc, company_expense_month desc, company_expense_day desc');

        $query = $query->paginate(10);



        return $query;
    }

    public static function get_company_expense_count($sync_id)
    {
        $result = 0;

        $query = CompanyExpense::query()
            ->selectRaw('sync_id,COUNT(company_expense_id) as company_expense_count')
            ->where('sync_id', $sync_id)
            ->groupBy('sync_id')
            ->first();

        if (!empty($query)) {
            $result = $query->company_expense_count;
        }

        return $result;
    }

    public static function get_company_expense_for_report()
    {
        $query = CompanyExpense::query();

        $result = $query->get();

        return $result;
    }

    public static function get_company_expense_for_report_detail($company_id, $setting_expense_id, $year)
    {
        $query = CompanyExpense::query()
                ->join('tbl_company_expense_item', 'tbl_company_expense_item.company_expense_id', 'tbl_company_expense.company_expense_id')
                ->join('tbl_user', 'tbl_user.user_id', 'tbl_company_expense.user_id')
                ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
                ->join('tbl_setting_expense', 'tbl_setting_expense.setting_expense_id', 'tbl_company_expense_item.setting_expense_id')
                ->where('tbl_company.company_id', '=', $company_id)
                ->where('tbl_setting_expense.setting_expense_id', '=', $setting_expense_id)
                ->where('tbl_company_expense.company_expense_year', '=', $year)
                ->get();

                return $query;
    }

    public static function get_company_expense_for_y2y_report_detail($company_id, $setting_expense_id)
    {
        $query = CompanyExpense::query()
                ->join('tbl_company_expense_item', 'tbl_company_expense_item.company_expense_id', 'tbl_company_expense.company_expense_id')
                ->join('tbl_user', 'tbl_user.user_id', 'tbl_company_expense.user_id')
                ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
                ->join('tbl_setting_expense', 'tbl_setting_expense.setting_expense_id', 'tbl_company_expense_item.setting_expense_id')
                ->where('tbl_company.company_id', '=', $company_id)
                ->where('tbl_setting_expense.setting_expense_id', '=', $setting_expense_id)
                ->get();

                return $query;
    }
}
