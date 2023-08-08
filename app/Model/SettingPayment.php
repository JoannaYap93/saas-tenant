<?php

namespace App\Model;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SettingPayment extends Model
{
    protected $table = 'tbl_setting_payment';
    protected $primaryKey = 'setting_payment_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_payment_name', 'is_payment_gateway', 'is_offline', 'setting_payment_status'
    ];


    public static function get_records($search, $perpage)
    {
        $settingPayment = SettingPayment::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $settingPayment->where(function ($q) use ($freetext) {
                $q->where('setting_payment_name', 'like', '%' . $freetext . '%');
            });
        }

        if (isset($search['gateway'])) {
            $settingPayment->where('is_payment_gateway', $search['gateway']);
        }

        $settingPayment->where('setting_payment_status', 0)->orderBy('setting_payment_id', 'DESC');

        return $settingPayment->paginate($perpage);
    }

    public static function get_sel()
    {
        $result = ['' => 'Please Select Method...'];
        $query = SettingPayment::query()->where('is_offline', '<>', 1)->get();
        foreach ($query as $key => $value) {
            $result[$value->setting_payment_id] = $value->setting_payment_name;
        }

        return $result;
    }
}
