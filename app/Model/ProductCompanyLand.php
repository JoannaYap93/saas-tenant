<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductCompanyLand extends Model
{
    protected $table = 'tbl_product_company_land';
    protected $primaryKey = 'product_company_land_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'company_land_id'
    ];

    public static function get_products_by_company_land_id($company_land_id)
    {
        $product_arr = [];
        $query = ProductCompanyLand::query()->where('company_land_id', $company_land_id)->get();
        if ($query->isNotEmpty()) {
            foreach ($query as $item) {
                $product_arr[$item->product->product_id] = ['name' => $item->product->product_name, 'size' => []];
                if(isset($product_arr[$item->product->product_id])){
                    foreach($item->product->product_size_link as $size){
                        array_push($product_arr[$item->product->product_id]['size'], ['id' => $size->setting_product_size_id, 'name'=>$size->setting_size->setting_product_size_name]);
                    }
                }

            }
        }

        return $product_arr;
    }

    public static function get_product_by_company_land_id($company_land_id)
    {
        $product_arr = [];

        $query = ProductCompanyLand::query()
                ->selectRaw('tbl_product.product_name, tbl_product_category.product_category_name')
                ->leftJoin('tbl_product', 'tbl_product.product_id', 'tbl_product_company_land.product_id')
                ->leftJoin('tbl_product_category', 'tbl_product_category.product_category_id', 'tbl_product.product_category_id')
                ->where('tbl_product_company_land.company_land_id', $company_land_id)
                ->orderBy('tbl_product_category.product_category_ranking')
                ->orderBy('tbl_product.product_ranking')
                ->get();

        if ($query->isNotEmpty()) {
            foreach ($query as $key => $item) {
                if(isset($product_arr[$item->product_category_name][$key])){
                    $product_arr[$item->product_category_name][$key] += $item->product_name;
                }else{
                    $product_arr[$item->product_category_name][$key] = $item->product_name;
                }
            }
        }

        return $product_arr;
    }

    public static function get_product_by_company_land_id_for_report($company_land_id)
    {
        $query = ProductCompanyLand::query()
                ->selectRaw('tbl_product.product_id, tbl_product.product_name, tbl_product_category.product_category_name')
                ->leftJoin('tbl_product', 'tbl_product.product_id', 'tbl_product_company_land.product_id')
                ->leftJoin('tbl_product_category', 'tbl_product_category.product_category_id', 'tbl_product.product_category_id')
                ->where('tbl_product_company_land.company_land_id', $company_land_id)
                ->groupBy('tbl_product.product_id')
                ->orderBy('tbl_product_category.product_category_ranking')
                ->orderBy('tbl_product.product_ranking')
                ->get();

        return $query;
    }

    public static function get_by_product($id)
    {
        $query = ProductCompanyLand::query()->where('product_id', $id)
            ->join('tbl_company_land', 'tbl_company_land.company_land_id', '=', 'tbl_product_company_land.company_land_id')
            ->join('tbl_company', 'tbl_company.company_id', '=', 'tbl_company_land.company_id')->where('tbl_company.is_display','1');
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_company.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_company.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_company.company_id', '<>', 1);
            }
        $result = $query->get();
        foreach ($result as $key => $value) {
            $value->setAttribute('land', '<b>' . @$value->company_land->company->company_name . '</b> - ' . @$value->company_land->company_land_name);
        }
        return $result;
    }

    public static function get_products_name_by_company_land_id($company_land_id)
    {
      $query = ProductCompanyLand::query()->where('company_land_id', $company_land_id)->with('product')->get();
      $product_arr = [];
      if ($query) {
          foreach ($query as $key => $value) {
              array_push($product_arr, array(
                  'id' => $value->product->product_id,
                  'label' => strtoupper($value->product->product_name),
                  'value' => strtoupper($value->product->product_name),
              ));
          }
      }
      return $product_arr;
    }

    public static function get_product_size_count($land_id)
    {
        $c = 0;
        $product = ProductCompanyLand::query()->where('company_land_id', $land_id)->get();
        foreach ($product as $key => $value) {
            $size = ProductSizeLink::query()->where('product_id', $value->product_id)->get();
            foreach ($size as $sk => $s) {
                $c++;
            }
        }
        return $c;
    }

    public static function get_land_product()
    {
        $result = [];
        $query = ProductCompanyLand::query()->orderBy('company_land_id')->get();
        // if(isset($search['company_id'])){
        //   $query->join('tbl_company_land as cl', 'cl.company_land_id', 'tbl_product_company_land.company_land_id');
        //   $query->join('tbl_company as c', 'c.company_id', 'cl.company_id');
        //   if
        //   $query->where('c.company_id', $search['company_id']);
        // }
        // dd($query);
        foreach ($query as $key => $value) {
          // dd($value->company_land);

            // $result[$key] = ['company'=> @$value->company_land->company_id, 'land' => @$value->company_land_id, 'land_name' => @$value->company_land->company_land_name,'farm' => @$value->company_land->company_land_category->company_farm_id ,'category' => @$value->company_land->company_land_category_id, 'product' => $value->product_id, 'count' => ProductCompanyLand::get_product_size_count($value->company_land_id)];
            $result[$key]['company'] = @$value->company_land->company_id;
            $result[$key]['land'] = @$value->company_land_id;
            $result[$key]['land_name'] =  @$value->company_land->company_land_name;
            $result[$key]['farm'] = @$value->company_land->company_land_category->company_farm_id ;
            $result[$key]['category'] =  @$value->company_land->company_land_category_id;
            $result[$key]['product'] = $value->product_id;
            $result[$key]['count'] = ProductCompanyLand::get_product_size_count($value->company_land_id);
        }
        // dd($result);
        return $result;
    }

    public static function get_land_product_2($search)
    {
        $query = ProductCompanyLand::query()
                ->join('tbl_company_land', 'tbl_company_land.company_land_id', 'tbl_product_company_land.company_land_id')
                ->join('tbl_product', 'tbl_product.product_id', 'tbl_product_company_land.product_id')
                ->orderBy('tbl_product.product_ranking')
                ->orderBy('tbl_product_company_land.company_land_id');

            if(isset($search['product_cb_id'])) {
              $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
            }

        $result = $query->get();
        return $result;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }
}
