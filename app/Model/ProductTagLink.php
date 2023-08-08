<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductTagLink extends Model
{
    protected $table = 'tbl_product_tag_link';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'product_tag_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_tag()
    {
        return $this->belongsTo(ProductTag::class, 'product_tag_id');
    }
}
