<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductTag extends Model
{
    use HasSlug;
    protected $table = 'tbl_product_tag';
    protected $primaryKey = 'product_tag_id';
    const CREATED_AT = 'product_tag_created';
    const UPDATED_AT = 'product_tag_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_tag_name')
            ->saveSlugsTo('product_tag_slug');
    }

    protected $fillable = [
        'product_tag_name',
        'product_tag_created',
        'product_tag_updated',
        'product_tag_status',
        'product_tag_slug'
    ];

    public static function get_sel()
    {
        $result = [];
        $query = ProductTag::query()->get();
        foreach ($query as $key => $q) {
            $result[$q->product_tag_id] = $q->product_tag_name;
        }
        return $result;
    }

    public static function get_records($search, $perpage)
    {
        $query = ProductTag::query()
        ->where('product_tag_status','!=','deleted');

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('product_tag_name', 'like', '%' . $search['freetext'] . '%');
            });
        }

        $result = $query->orderBy('product_tag_created', 'desc')->paginate($perpage);
        return $result;
    }

    public static function product_tag_name($product_tag_name)
    {
        $productTag = ProductTag::query();
        $productTag->where('product_tag_name', $product_tag_name);
        $result = optional($productTag->first())->product_tag_slug;

        return $result;
    }

    public static function product_tag_id_name($id, $name)
    {
        $query = ProductTag::query();
        $query->where(function ($q) use ($id, $name) {
            $q->where('product_tag_id', $id);
            $q->orWhere('product_tag_name', $name);
        });
        $result = $query->first();
        return $result;
    }
}
