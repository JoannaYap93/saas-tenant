<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingRewardCategory extends Model
{
    protected $table = 'tbl_setting_reward_category';
    protected $primaryKey = 'setting_reward_category_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_reward_category_name',
    ];

    public static function get_records($search, $perpage)
    {
        $query = SettingRewardCategory::query();

        if (@$search['freetext']) {
            $query->where(function($q) use($search){
                $q->where('setting_reward_category_name', 'like', '%' . $search['freetext'] . '%');
            });
        }

        $query->orderBy('setting_reward_category_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function get_sel_setting_reward()
    {
        $result = [];
        $query = SettingRewardCategory::query()->get();

        foreach($query as $key => $value)
        {
            $result[$value->setting_reward_category_id] = $value->setting_reward_category_name;
        }

        return $result;
    }
}
