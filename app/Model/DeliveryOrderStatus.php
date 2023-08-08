<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderStatus extends Model
{
    protected $table = 'tbl_delivery_order_status';
    protected $primaryKey = 'delivery_order_status_id';
    protected $timestamp = false;

    protected $fillable = ['delivery_order_status_name'];

    public static function get_sel()
    {
        $result = [0 => 'Please Select Status...'];
        $query = DeliveryOrderStatus::query()->get();
        foreach ($query as $key => $value) {
            $result[$value->delivery_order_status_id] = $value->delivery_order_status_name;
        }

        return $result;
    }

    public static function get_all_sel() {
        $query = DeliveryOrderStatus::query()->get();
        return $query;
    }
}
