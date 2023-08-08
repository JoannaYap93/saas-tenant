<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SettingExpense extends Model
{
    protected $table = 'tbl_setting_expense';
    protected $primaryKey = 'setting_expense_id';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    protected $fillable = [
        'setting_expense_name', 'company_id', 'setting_expense_description',
        'worker_role_id', 'setting_expense_value', 'setting_expense_type_id',
        'is_compulsory', 'setting_expense_category_id', 'is_subcon_allow',
        'setting_expense_subcon', 'setting_expense_status', 'is_excluded_payroll'
    ];

    public static function get_records($search, $perpage = null)
    {
      // dd($perpage);
        $query = SettingExpense::query();

        if (auth()->user()->user_type_id != 1) {
            $query = $query->whereIn('company_id', [0, auth()->user()->company_id]);
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('setting_expense_name', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_expense_description', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_expense_value', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_expense_subcon', 'like', '%' . $freetext . '%');
                $q->orWhere('worker_role_id', 'like', '%' . $freetext . '%');
            });
        }
        if (@$search['setting_expense_category_id']) {
            $query = $query->where('setting_expense_category_id', $search['setting_expense_category_id']);
        }
        if (@$search['worker_role_id']){
            $query = $query->where('worker_role_id', $search['worker_role_id']);
        }

        if (@$search['is_subcon_allow']) {
            $query = $query->where('is_subcon_allow', $search['is_subcon_allow']);
        }

        if (@$search['company_id']) {
            $query = $query->where('company_id', $search['company_id']);
        }

        if($perpage != null){
          $result = $query->orderBy('setting_expense_id', 'DESC')->paginate(10);
        }else{
          $result = $query->orderBy('setting_expense_id', 'DESC')->get();
        }

        return $result;
    }

    public static function get_setting_expense_for_company_pnl()
    {
        $query = SettingExpense::query();
        $result = $query->orderBy('setting_expense_name', 'ASC')->get();

        return $result;
    }

    public static function get_item_expense_for_pnl_reporting()
    {
        $result = SettingExpense::query()->where('setting_expense_status', "active")
            ->get();

        return $result;

    }

    public static function get_sel()
    {
        $result = [];
        $query = SettingExpense::query()->whereIn('company_id', [0, auth()->user()->company_id])->orderBy('setting_expense_name', 'ASC')->get();
        foreach ($query as $key => $value) {
            $result[$value->setting_expense_id]['name'] = $value->setting_expense_name;
            $result[$value->setting_expense_id]['value'] = $value->setting_expense_value;
            $result[$value->setting_expense_id]['compulsory'] = $value->is_compulsory;
            $result[$value->setting_expense_id]['type_id'] = $value->setting_expense_type_id;
            $result[$value->setting_expense_id][$value->worker_role_id]['worker_role_id'] = $value->worker_role_id;
            $result[$value->setting_expense_id]['type_name'] = $value->setting_expense_type->setting_expense_type_name;

        }
        return $result;
    }

    public static function get_expense_sel()
    {
        $result[''] = 'Please Select Expense Name';
        $query = SettingExpense::query()->whereIn('company_id', [0, auth()->user()->company_id])
                ->orderBy('setting_expense_name', 'ASC')->get();
        foreach ($query as $key => $value) {
            $result[$value->setting_expense_id] = json_decode($value->setting_expense_name)->en;

        }
        return $result;
    }

    public static function get_item_expense_payroll_item()
    {
        $arr = ['' => 'Not Linked'];

        $result = SettingExpense::query()->
            where('setting_expense_status', "active")->orderBy('setting_expense_name', 'ASC')
            ->get();

        foreach ($result as $key => $value) {
            $arr[$value->setting_expense_id] = json_decode($value->setting_expense_name)->en;

        }
        return $arr;

    }

    public static function get_expense_by_upkeep($expense_category_id)
    {
        $result = [];
        $query = SettingExpense::query()
                // ->whereIn('company_id', [0, auth()->user()->company_id])
                ->whereHas('expense_category', function ($q) use($expense_category_id) {
                    $q->where('setting_expense_category_id', $expense_category_id);
                })
                ->get();

        $discount_option = SettingExpenseCategory::where('setting_expense_category_group', 'general')->first();

        foreach ($query as $key => $value) {
            $result[$key] =  ['id' => $value->setting_expense_id ,'name' => json_decode($value->setting_expense_name)->en];
        }

        foreach ($discount_option->setting_expense as $key => $value){
            array_push($result, ['id' => $value->setting_expense_id ,'name' => json_decode($value->setting_expense_name)->en]);
        }
        return $result;
    }

    public static function get_expense_by_daily_worker($worker_role_id)
    {
        $result = [];
        $query = SettingExpense::query()
                // ->whereIn('company_id', [0, auth()->user()->company_id])
                ->whereHas('expense_category', function ($q) {
                    $q->where('setting_expense_category_id', '=', 2 );
                })
                // ->whereHas('expense_overwrite', function ($q) {
                //     $q->where('setting_expense_id', '=', 2 );
                // })
                ->whereIn('worker_role_id', [$worker_role_id, 5])
                ->get();
        foreach ($query as $key => $value) {
                $result[$key] =  ['id' => $value->setting_expense_id,
                    'name' => json_decode($value->setting_expense_name)->en,
                    'value' => $value->expense_overwrite_2->setting_expense_overwrite_value ?? $value->setting_expense_value,
                    'expense_type_id' =>$value->setting_expense_type->setting_expense_type_id,
                    'expense_type_name' =>' ('.$value->setting_expense_type->setting_expense_type_name.')',
                    'overwrite_commission' =>$value->expense_overwrite_2->setting_expense_overwrite_commission ?? 0,
                ];

        }
        // dd($result);
        return $result;
    }

    public static function get_expense_by_subcon_worker($worker_role_id)
    {
        $result = [];
        $query = SettingExpense::query()
                // ->whereIn('company_id', [0, auth()->user()->company_id])
                ->whereHas('expense_category', function ($q) {
                    $q->where('setting_expense_category_id', '=', 2 );
                })
                ->where('is_subcon_allow', '=', 1)
                // ->whereHas('expense_overwrite', function ($q) {
                //     $q->where('setting_expense_id', '=', 2 );
                // })
                ->whereIn('worker_role_id', [$worker_role_id, 5])
                ->get();
        foreach ($query as $key => $value) {
                $result[$key] =  ['id' => $value->setting_expense_id,
                    'name' => json_decode($value->setting_expense_name)->en,
                    'value' => $value->expense_overwrite_2->setting_expense_overwrite_subcon ?? $value->setting_expense_subcon,
                    'expense_type_id' =>$value->setting_expense_type->setting_expense_type_id,
                    'expense_type_name' => '',
                    'overwrite_commission' => 0,
                ];

        }
        // dd($result);
        return $result;
    }

    public static function get_price_expense($expense_id)
    {
        $result = 0.00;
        $query = SettingExpense::query()
                ->where('setting_expense_id', '=', $expense_id )
                ->first();
        if(!empty($query)){
            if($query->is_subcon_allow == 1){
                $result = $query->setting_expense_subcon;

            }else{
                $result = $query->setting_expense_value;
            }
        }

        return $result;
    }


    public static function get_expense_for_report()
    {
        $query = SettingExpense::query()
                ->join('tbl_setting_expense_type', 'tbl_setting_expense_type.setting_expense_type_id', 'tbl_setting_expense.setting_expense_type_id')
                // ->join('tbl_product', 'tbl_product.setting_expense_id', 'tbl_product_company_land.setting_expense_id')
                ->orderBy('tbl_setting_expense_type.setting_expense_type_id');

        $result = $query->get();
        // dd($result);
        return $result;
    }
    public static function get_expense_for_do_expense()
    {
        $query = SettingExpense::query()
                ->join('tbl_setting_expense_type', 'tbl_setting_expense_type.setting_expense_type_id', 'tbl_setting_expense.setting_expense_type_id')
                ->where('setting_expense_category_id',1)
                // ->join('tbl_product', 'tbl_product.setting_expense_id', 'tbl_product_company_land.setting_expense_id')
                ->orderBy('tbl_setting_expense_type.setting_expense_type_id');

        $result = $query->get();
        // dd($result);
        return $result;
    }

    public static function get_test(){
        $builder = SettingExpense::query()
        ->where('setting_expense_category_id',2)
        ->get();
        return $builder;
    }

    public static function get_setting_expense_for_report()
    {
        $result = SettingExpense::query()
                ->get();

        return $result;
    }

    public static function get_setting_expense_by_setting_expense_category($setting_expense_category_id)
    {
        $result = SettingExpense::query()->where('setting_expense_category_id', $setting_expense_category_id)->get();
        return $result;
    }

    public static function get_setting_expense_for_worker()
    {
        $result = SettingExpense::query()->where('setting_expense_category_id', '=', 2)
                ->get();

        return $result;
    }

    public function setting_expense_type()
    {
        return $this->belongsTo('App\Model\SettingExpenseType', 'setting_expense_type_id');
    }

    public function worker_role()
    {
        return $this->belongsTo('App\Model\WorkerRole', 'worker_role_id');
    }

    public function expense_category()
    {
        return $this->hasOne('App\Model\SettingExpenseCategory', 'setting_expense_category_id', 'setting_expense_category_id');
    }

    public function expense_overwrite()
    {
        if(Auth::user()->user_type_id === 1){
            return $this->hasMany('App\Model\SettingExpenseOverwrite', 'setting_expense_id');
        }else{
            return $this->hasOne('App\Model\SettingExpenseOverwrite', 'setting_expense_id')->where('company_id', '=', Auth::user()->company_id);
        }
    }

    public function expense_overwrite_2()
    {
        if(Auth::user()->user_type_id === 1){
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            return $this->hasOne('App\Model\SettingExpenseOverwrite', 'setting_expense_id')->whereIn('company_id', $ids);
        }else{
            return $this->hasOne('App\Model\SettingExpenseOverwrite', 'setting_expense_id')->where('company_id', '=', Auth::user()->company_id);
        }
    }

    public function overwrite_company()
    {
        return $this->hasOne('App\Model\Company', 'company_id', 'company_id');
    }

    public function company_expense_item()
    {
        return $this->hasMany(CompanyExpenseItem::class, 'company_expense_item_id');
    }
}
