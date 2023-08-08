<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingExpenseOverwrite extends Model
{
    protected $table = 'tbl_setting_expense_overwrite';
    protected $primaryKey = 'setting_expense_overwrite_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'setting_expense_overwrite_created';
    const UPDATED_AT = 'setting_expense_overwrite_updated';

    protected $fillable = [
        'setting_expense_type_id', 'setting_expense_overwrite_value', 'setting_expense_id', 'company_id', 'user_id', 'is_extra_commission', 'setting_expense_overwrite_commission', 'is_subcon_allow', 'setting_expense_overwrite_subcon'
    ];

    public function overwrite_expense()
    {
        return $this->hasOne('App\Model\SettingExpense', 'setting_expense_id', 'setting_expense_id');
    }

    public function overwrite_expense_type()
    {
        return $this->belongsTo('App\Model\SettingExpenseType', 'setting_expense_type_id');
    }

    public function overwrite_company()
    {
        return $this->hasOne('App\Model\Company', 'company_id', 'company_id');
    }

    public function overwrite_user()
    {
        return $this->hasOne('App\Model\User', 'user_id', 'user_id');
    }
}
