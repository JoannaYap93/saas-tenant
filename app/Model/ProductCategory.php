<?php

namespace App\Model;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasSlug;
    protected $table = 'tbl_product_category';
    protected $primaryKey = 'product_category_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'product_category_created';
    const UPDATED_AT = 'product_category_updated';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_category_name')
            ->saveSlugsTo('product_category_slug');
    }

    protected $fillable = [
        'product_category_parent_id',
        'product_category_name',
        'product_category_ranking',
        'is_deleted',
        'product_category_created',
        'product_category_updated',
        'product_category_slug',
        'product_category_status',
        'company_id'
    ];

    public static function get_category_sel()
    {
        $result = ['' => 'Please Select Category...'];
        $query = ProductCategory::query()->where('is_deleted', '!=', 1);

        // if (auth()->user()->company_id != 0) {
        //     $query = $query->where('company_id', auth()->user()->company_id);
        // }

        $query = $query->get();

        foreach ($query as $q) {
            $result[$q->product_category_id] = $q->product_category_name;
        }
        return $result;
    }

    public static function get_records($search)
    {
        $query = ProductCategory::query();
        // $query->where('product_category_status', 'published');

        // if (auth()->user()->company_id != 0) {
        //     $query = $query->where('company_id', auth()->user()->company_id);
        // }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('tbl_product_category.product_category_id', 'like', '%' . $freetext . '%');
                $q->orWhere('tbl_product_category.product_category_name', 'like', '%' . $freetext . '%');
                $q->orWhere('tbl_product_category.product_category_status', 'like', '%' . $freetext . '%');
                $q->orWhere('tbl_product_category.product_category_slug', 'like', '%' . $freetext . '%');
                // $q->orWhere('product_sku', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['product_category_status']) {
            $query->where('product_category_status', $search['product_category_status']);
        }

        // if (@$search['product_category_slug']) {
        //     $query->where('product_category_slug', $search['product_category_slug']);
        // }

        $query->where('is_deleted', '!=', 1);

        $result = $query->orderBy('product_category_created', 'desc')->paginate(10);

        return $result;
    }

    public static function get_product_category_sel()
    {
        $product_categorys = ProductCategory::query();

        // if (auth()->user()->company_id != 0) {
        //     $product_categorys = $product_categorys->where('company_id', auth()->user()->company_id);
        // }

        $product_categorys = $product_categorys->get();
        $temp[''] = 'Please select status';
        foreach ($product_categorys as $product_category) {
            // $temp[$land_code->company_land_code] ;
            $temp[$product_category->product_category_status] = $product_category->product_category_status;
        }
        // dd($temp);
        return $temp;
    }

    public static function get_product_category_for_pnl_reporting()
    {

        $result = ProductCategory::query()->get();
   
        return $result;
    }

    public static function get_product_category_slug()
    {
        $product_categorys = ProductCategory::query();

        // if (auth()->user()->company_id != 0) {
        //     $product_categorys = $product_categorys->where('company_id', auth()->user()->company_id);
        // }

        $product_categorys = $product_categorys->get();

        $temp[''] = 'Please select Slug';
        foreach ($product_categorys as $product_category) {
            // $temp[$land_code->company_land_code] ;
            $temp[$product_category->product_category_id] = $product_category->product_category_slug;
        }
        // dd($temp);
        return $temp;
    }

    public static function get_product_category_for_report()
    {

        $result = ProductCategory::query()->get();

        return $result;
    }

    // public static function product_category_name($product_category_name) {
    //     $productCategory = ProductCategory::query();
    //     $productCategory->where('product_category_name', $product_category_name);
    //     $result = optional($productCategory->first())->product_category_slug;

    //     return $result;
    // }
    // public static function get_product_category_pnl_reporting($search)
    // {
    //     $company_id = auth()->user()->company_id;
    
    //     $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
    //                     tbl_product.product_id as product_id,
    //                     tbl_product_category.product_category_id as product_category_id,
    //                     tbl_company.company_id as company_id
    //                 FROM tbl_invoice_item
    //                 LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
    //                 LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
    //                 LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
    //                 LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
    //                 LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
    //                 LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
    //                 WHERE DATE(tbl_invoice.invoice_date) >= DATE(?) AND tbl_company.is_display = 1
    //                 AND DATE(tbl_invoice.invoice_date) <= DATE(?) AND tbl_invoice.invoice_status_id <> 3';
 
    //     if (isset($search['company_id'])) {
    //         $query .= " AND tbl_invoice.company_id = {$search['company_id']}";

    //     } elseif(isset($search['company_cb_id'])) {
    //         $cb_id = implode(",", $search['company_cb_id']);
    //         $query .= " AND tbl_invoice.company_id IN ({$cb_id})";

    //     } else {
    //         if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
    //             $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
    //             $query .= " AND tbl_invoice.company_id IN ({$ids})";

    //         } else if (auth()->user()->company_id != 0) {
    //             $query .= " AND tbl_invoice.company_id = {$company_id}";

    //         } else {
    //             $query .= " AND tbl_invoice.company_id <> 1";
    //         }
    //     }

    //     $query->
    //      = .= " GROUP BY tbl_product_category.product_category_name, tbl_company.company_id
    //                 ORDER BY tbl_product.product_ranking ASC";
    //     // dd($query);

    //     $result = la
    //     DB::select(DB::raw($query), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);
    //     dd($result);

    //     return ['productCategory' => $result];      
    // }

    public static function get_product_category_for_forecast_report(){

        $query = ProductCategory::query()->where('product_category_id', 2)->orWhere('product_category_id', 3)->get();

        return $query;
    }
}
