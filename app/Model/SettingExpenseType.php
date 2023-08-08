<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingExpenseType extends Model
{
    protected $table = 'tbl_setting_expense_type';
    protected $primaryKey = 'setting_expense_type_id';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    protected $fillable = [
        'setting_expense_type_name'
    ];

    public static function get_expense_type_sel()
    {
        $result[''] = 'Please Select Expense Type';
        $query = SettingExpenseType::query()->get();
        foreach ($query as $key => $value) {
            $result[$value->setting_expense_type_id] = $value->setting_expense_type_name;
        }
        return $result;
    }
}
