<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'tbl_setting';
    protected $primaryKey = 'setting_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_slug', 'setting_value', 'setting_description', 'is_editable', 'setting_type'
    ];

    public static function get_record($search, $perpage)
    {
        $query = Setting::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('setting_slug', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_value', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_description', 'like', '%' . $freetext . '%');
            });
        }
        return $query->paginate($perpage);
    }

    public static function setting_name($setting_slug)
    {
        $setting = Setting::query();
        $setting->where('setting_slug', $setting_slug);
        $result = optional($setting->first())->setting_value;

        return $result;
    }

    public static function get_by_slug($setting_slug) {
        return Setting::where('setting_slug', $setting_slug)->value('setting_value');
    }

    public static function get_time_slot() {

        $result = [];
        $time_arr =[];
        $temp_arr = [];
        $data = [];

        $query = Setting::where('setting_slug', '=', 'worker_time_slots')->value('setting_value');
        $result = str_replace (array('[', ']','{', '}', '"', ' '), '' , $query);

        $time_arr = explode(",", $result);

        foreach(array_chunk($time_arr, 2) as $label_value) {
            $label = explode(":", $label_value[0])[1];
            $value = explode(":", $label_value[1])[1];
            $data[$value] = $label;
        }
        return $data;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('setting')
            ->singleFile();
    }
}
