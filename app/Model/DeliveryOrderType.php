<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderType extends Model
{
    protected $table = 'tbl_delivery_order_type';
    protected $primaryKey = 'delivery_order_type_id';
    protected $timestamp = false;

    protected $fillable = ['delivery_order_type_name'];

    public static function get_sel()
    {
        $result[''] = 'Please Select Order Type...';
        $query = DeliveryOrderType::query()->get();
        foreach ($query as $key => $value) {
            $result[$value->delivery_order_type_id] = $value->delivery_order_type_name;
        }
        return $result;
    }

}
