<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    protected $table = 'tbl_product_info';

    protected $primaryKey = 'product_info_id';

    const CREATED_AT = 'product_info_created';
    const UPDATED_AT = NULL;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'product_id', 'setting_product_size_id', 'company_farm_id', 'product_info_date', 'product_info_price', 'product_info_created', 'is_deleted'
    ];


}
