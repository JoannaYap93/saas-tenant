<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingBank extends Model
{
    protected $table = 'tbl_setting_bank';
    protected $primaryKey = 'setting_bank_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_bank_name'
    ];

    // public static function get_records($search, $perpage)
    // {
    //     $size = SettingSize::query();
    //     // dd($search);
    //     if (@$search['freetext']) {
    //         $freetext = $search['freetext'];
    //         $size->where(function ($q) use ($freetext) {
    //             $q->where('setting_product_size_name', 'like', '%' . $freetext . '%');
    //         });
    //     }
    //     $size->orderBy('setting_product_size_id', 'ASC');
    //
    //     return $size->paginate(10);
    // }

    // public static function get_size_sel_for_stock_warehouse()
    // {
    //   $result = SettingSize::get();
    //   $temp[''] = 'Please select Size';
    //   foreach($result as $size){
    //       $temp[$size->setting_product_size_id] = $size->setting_product_size_name;
    //   }
    //   return $temp;
    // }

    public static function get_setting_bank_sel()
    {
      $result = SettingBank::get();
      $temp[''] = 'Please Select Bank';
      foreach($result as $bank){
          $temp[$bank->setting_bank_id] = $bank->setting_bank_name;
      }
      return $temp;
    }

    // public static function get_size_sel()
    // {
    //     $result = [];
    //
    //     $query = SettingSize::query()->orderBy('setting_product_size_name')->get();
    //
    //     if ($query->first()) {
    //         $result = $query->pluck('setting_product_size_name', 'setting_product_size_id')->toArray();
    //     }
    //     return $result;
    // }

}
