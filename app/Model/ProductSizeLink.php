<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Log;

class ProductSizeLink extends Model
{
    protected $table = 'tbl_product_size_link';
    protected $primaryKey = 'product_size_link_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'setting_product_size_id'
    ];

    public static function get_size_name_by_product_id($product_id)
    {
      $query = ProductSizeLink::query();
      $query->where('product_id', $product_id);
      $query->with('setting_size');
      // $query->where('is_deleted', '<>', 1);
      $query = $query->get();
      $list = [];
      if ($query) {
          foreach ($query as $key => $value) {
              array_push($list, array(
                  'id' => $value->setting_size->setting_product_size_id,
                  'label' => strtoupper($value->setting_size->setting_product_size_name),
                  'value' => strtoupper($value->setting_size->setting_product_size_name),
              ));
          }
      }
      return $list;
    }

    public static function get_size_by_product_id($product_id){
        $query = Product::query()-where('product_id', $product_id)->first();
        return $query;
    }

    public static function get_size_by_product_id2($product_id)
    {
        $arr = array();
        $query = ProductSizeLink::query()->where('product_id', $product_id)->get();
        if($query){
          foreach($query as $size){
            array_push($arr, array(
              'size_id'=>$size->setting_product_size_id,
              'size_name'=>$size->setting_size->setting_product_size_name
            ));
          }
        }
        return $arr;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function setting_size()
    {
        return $this->belongsTo(SettingSize::class, 'setting_product_size_id');
    }
}
