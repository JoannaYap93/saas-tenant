<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SettingRace extends Model
{
    protected $table = 'tbl_setting_race';
    protected $primaryKey = 'setting_race_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_race_name',
    ];
    //REMARK: if you add or remove race please do update the import worker function and file also later on

    public static function get_records($search ,$perpage)
    {
        $query = SettingRace::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('setting_race_name', 'like', '%' . $freetext . '%');
            });
        }

        $query->orderBy('setting_race_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function get_sel_setting_race()
    {
        $result = [];
        $query = SettingRace::query()->get();

        foreach($query as $key => $value)
        {
            $result[$value->setting_race_id] = $value->setting_race_name;
        }

        return $result;
    }
}
