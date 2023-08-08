<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingSize extends Model
{
    protected $table = 'tbl_setting_product_size';
    protected $primaryKey = 'setting_product_size_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_product_size_name'
    ];

    public static function get_records($search, $perpage)
    {
        $query = SettingSize::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('setting_product_size_name', 'like', '%' . $freetext . '%');
            });
        }
        $query->orderBy('setting_product_size_id', 'DESC');

        $result = $query->paginate($perpage);

        return $result;
    }

    public static function get_size_sel_for_stock_warehouse()
    {
      $result = SettingSize::get();
      $temp[''] = 'Please select Size';
      foreach($result as $size){
          $temp[$size->setting_product_size_id] = $size->setting_product_size_name;
      }
      return $temp;
    }

    public static function get_product_size_sel()
    {
      $result = SettingSize::get();
      $temp[''] = 'Please Select Grade';
      foreach($result as $size){
          $temp[$size->setting_product_size_id] = $size->setting_product_size_name;
      }
      return $temp;
    }

    public static function get_size_sel()
    {
        $result = [];

        $query = SettingSize::query()->orderBy('setting_product_size_name')->get();

        if ($query->first()) {
            $result = $query->pluck('setting_product_size_name', 'setting_product_size_id')->toArray();
        }
        return $result;
    }

    public static function get_size_setting()
    {

        $query = SettingSize::query()->get();

        return $query;
    }
}
