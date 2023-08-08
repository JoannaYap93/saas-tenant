<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingReward extends Model
{
    protected $table = 'tbl_setting_reward';
    protected $primaryKey = 'setting_reward_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_reward_name',
        'setting_reward_description',
        'setting_reward_category_id',
        'setting_reward_json',
        'setting_reward_status',
        'company_id',
        'is_default',
    ];

    public static function get_records($search, $perpage)
    {
        $query = SettingReward::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('setting_reward_name', 'like', '%' . $freetext . '%');
                $q->orWhereHas('setting_reward_category', function ($q2) use ($freetext) {
                    $q2->where('setting_reward_category_name', 'like', '%' . $freetext . '%');
                });
            });
        }

        // if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //     $ids = array();
        //     foreach (auth()->user()->user_company as $key => $user_company) {
        //         $ids[$key] = $user_company->company_id;
        //     }
        //     $query->whereIn('company_id', $ids);
        // } else if (auth()->user()->company_id != 0) {
        //     $query->where('company_id', auth()->user()->company_id);
        // } else {
        //     $query->where('company_id', '<>', 1);
        // }

        if (@$search['setting_reward_category_id']) {
            $query->where('setting_reward_category_id', $search['setting_reward_category_id']);
        }

        if (@$search['setting_reward_status']) {
            $query->where('setting_reward_status', $search['setting_reward_status']);
        }

        $query->orderBy('setting_reward_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function get_reward_sel_by_company($company_id)
    {
        $result = [];
        $query = SettingReward::query()
            ->where('setting_reward_status', '=', 'active')
            ->orwhere('company_id' ,'=', $company_id)
            ->orwhere('company_id' ,'=', 0)
            ->get();

        foreach($query as $key => $reward)
        {
          $result[$key] = ['id' => $reward->setting_reward_id, 'name' => $reward->setting_reward_name];
        }

        return $result;
    }


    public function setting_reward_category()
    {
        return $this->belongsTo('App\Model\SettingRewardCategory', 'setting_reward_category_id');
    }
}
