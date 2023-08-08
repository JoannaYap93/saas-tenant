<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SettingCurrency extends Model
{
    protected $table = 'tbl_setting_currency';
    protected $primaryKey = 'setting_currency_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_currency_code',
        'setting_currency_name',
    ];

    public static function get_records($search, $perpage)
    {
        $query = SettingCurrency::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('setting_currency_code', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_currency_name', 'like', '%' . $freetext . '%');
            });
        }

        $query->orderBy('setting_currency_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function setting_currency_sel()
    {
        $result = null;
        $setting_currency_sel = array();

        $query = SettingCurrency::query();
        $result = $query->get();

        foreach($result as $setting_currency)
        {
            $setting_currency_sel[$setting_currency->setting_currency_id] = $setting_currency->setting_currency_name;
        }

        return $setting_currency_sel;
    }
}
