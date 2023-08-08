<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    protected $table = 'tbl_product_status';
    protected $primaryKey = 'product_status_id';
    public $timestamps = false;

    public static function get_records() {
        $query = ProductStatus::query()->get();
        $status = ['' => 'Please Select Status...'];
        foreach ($query as $key => $value) {
            $status[$value->product_status_id] = $value->product_status_name;
        }
        return $status;
    }

}
