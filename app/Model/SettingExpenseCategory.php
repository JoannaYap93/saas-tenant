<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingExpenseCategory extends Model
{
    protected $table = 'tbl_setting_expense_category';
    protected $primaryKey = 'setting_expense_category_id';

    public $timestamps = false;

    protected $fillable = [
        'setting_expense_category_name', 'is_budget_limited', 'setting_expense_category_budget', 'is_backend_only', 'setting_expense_category_group'
    ];

    public static function get_records($search, $perpage){

        $query = SettingExpenseCategory::query();

        if (auth()->user()->user_type_id != 1) {
            $query = $query->whereIn('setting_expense_category_id', function ($subquery) {
                $subquery->select('setting_expense_category_id')
                    ->from('tbl_setting_expense')
                    ->whereIn('company_id', [0, auth()->user()->company_id]);
            });
        }

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('setting_expense_category_name', 'like', '%' . $search['freetext'] . '%');
            });
        }

        $query->orderBy('setting_expense_category_group')
              ->orderBy('setting_expense_category_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function get_expense_category_for_pnl_reporting()
    {
        $result = SettingExpenseCategory::query()->get();
        return $result;
    }

    public static function get_existing_expense_category_sel()
    {
        $query = SettingExpenseCategory::query();
        $query->orderBy('setting_expense_category_name');

        $result [''] = 'Please Select Expense Category';
        foreach ($query->get() as $name) {
            $result[$name->setting_expense_category_id] = json_decode($name->setting_expense_category_name)->en;
        }
        return $result;
    }

    public static function get_existing_expense_category_sel_wihtout_labour()
    {
        $query = SettingExpenseCategory::query()->where('setting_expense_category_id', '!=', 2);
        $query->orderBy('setting_expense_category_name');

        $result [''] = 'Please Select Expense Category';
        foreach ($query->get() as $name) {
            $result[$name->setting_expense_category_id] = json_decode($name->setting_expense_category_name)->en;
        }
        return $result;
    }

    public static function get_expense_category_for_company_expense_report(){
        $query = SettingExpenseCategory::query();
        $result = $query->orderBy('setting_expense_category_id')
                        ->get();

        return $result;
    }

    public static function get_expense_category_for_worker()
    {
        $query = SettingExpenseCategory::query()->where('setting_expense_category_id','=', 2);
        $result = $query->get();

        return $result;
    }

    public static function get_expense_category_by_setting_expense_cateogry_id($setting_expense_category_id)
    {
        $query = SettingExpenseCategory::query()->where('setting_expense_category_id','=', $setting_expense_category_id);
        $result = $query->get();

        return $result;
    }

    public static function get_expense_for_report($search)
    {
        $query = SettingExpenseCategory::query();
        // dd($search);
        if(isset($search['company_id'])){
            $query->whereHas('company_expense', function ($q) use($search){
                $q->where('company_id', $search['company_id']);
            });
        }
        if(isset($search['company_land_id'])){
            $query->whereHas('company_expense', function ($q) use($search){
                $q->where('company_land_id', $search['company_land_id']);
            });
        }
        if(isset($search['setting_expense_category_id'])){
            $query->whereHas('company_expense', function ($q) use($search){
                $q->where('setting_expense_category_id', $search['setting_expense_category_id']);
            });
        }

        $result = $query->get();
        return $result;
    }

    public function company_expense()
    {
        return $this->hasMany('App\Model\CompanyExpense', 'setting_expense_category_id');
    }

    public function setting_expense()
    {
        return $this->hasMany('App\Model\SettingExpense', 'setting_expense_category_id');
    }

    public static function get_expense_for_budget_estimate($data)
    {
        $query = SettingExpenseCategory::query();
        $result = $query->get();
        return $result;
    }

}
