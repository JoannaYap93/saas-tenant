<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, HasSlug;
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'product_created';
    const UPDATED_AT = 'product_updated';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_name')
            ->saveSlugsTo('product_slug');
    }

    protected $fillable = [
        'product_name',
        'product_remarks',
        'product_description',
        'product_sku',
        'is_deleted',
        'product_slug',
        'product_status_id',
        'product_ranking',
        'product_category_id',
        'company_id'
    ];

    public static function get_records($search)
    {
        $query = Product::with('product_size_link.setting_size','product_company_land.company_land');
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
            $ids[$key] = $user_company->company_id;
            }
            $query->whereHas('product_company_land', function ($q) use ($ids) {
                $q->whereHas('company_land', function ($q2) use ($ids){
                    $q2->whereIn('company_id',$ids);
                });
        });
        }else if(auth()->user()->company_id != 0){
            $query->whereHas('product_company_land', function ($q) {
                $q->whereHas('company_land', function ($q2){
                    $q2->where('company_id',auth()->user()->company_id);
                });
        });
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('product_name', 'like', '%' . $freetext . '%');
                $q->orWhere('product_remarks', 'like', '%' . $freetext . '%');
                $q->orWhere('product_slug', 'like', '%' . $freetext . '%');
                $q->orWhere('product_sku', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['status']) {
            $query->where('product_status_id', $search['status']);
        }

        if (@$search['product_category_id']) {
            $query->where('product_category_id', $search['product_category_id']);
        }

        if (@$search['product_size']) {
            $setting_product_size_id = $search['product_size'];
            $query->whereHas('product_size_link', function ($q) use ($setting_product_size_id) {
                $q->where('setting_product_size_id', $setting_product_size_id);
            });
        }

        if (@$search['company_id']) {
            $ld = '';
            $land = DB::select('select company_land_id from tbl_company_land where company_id = ' . $search['company_id']);

            foreach ($land as $key => $l) {
                if ($key > 1) {
                    $ld .= ', ';
                }
                $ld .= "'" . $l->company_land_id . "'";
            }

            $product = DB::select('select product_id from tbl_product_company_land where company_land_id in (' . $ld . ')');

            foreach ($product as $key => $p) {
                $product[$key] = $p->product_id;
            }
            $query->whereIn('product_id', $product);
        }

        if (@$search['company_land_id']) {
            $product = DB::select('select product_id from tbl_product_company_land where company_land_id = ' . $search['company_land_id']);
            foreach ($product as $key => $pp) {
                $product[$key] = $pp->product_id;
            }
            $query->whereIn('product_id', $product);
        }

        $query->where('is_deleted', '!=', 1);

        $result = $query->orderBy('product_ranking', 'asc')->paginate(10);

        foreach ($result as $key => $value) {
            $value->setAttribute('product_land', ProductCompanyLand::get_by_product($value->product_id));
        }

        return $result;
    }

    public static function get_product_tree_age_pointer_report()
    {
        $result = Product::all();
        return $result;
    }

    public static function get_by_id($id, $ajax = false)
    {
        $size_arr = [0 => 'Please Select Size...'];
        $query = Product::find($id);
        $size_link = ProductSizeLink::query()->where('product_id', $id)->orderBy('setting_product_size_id', 'asc')->get();

        if ($size_link) {
            foreach ($size_link as $key => $size) {
                $size_arr[$key] = ['id' => $size->setting_size->setting_product_size_id, 'name' => $size->setting_size->setting_product_size_name];
            }
        } else {
            $size_arr = '';
        }

        if ($ajax == true) {
            return response()->json(['data' => $query, 'status' => $query ? true : false, 'size' => $size_arr]);
        } else {
            return $query;
        }
    }

    public static function get_by_name($name, $input_land = null)
    {
        $company_id = auth()->user()->company_id ?? null;
        if ($input_land == null) {
            $land = CompanyLand::query()->where('company_id', $company_id)->get();
            foreach ($land as $key => $value) {
                $land[$key] = $value->company_land_id;
            }
        } else {
            $land = [0 => $input_land];
        }

        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land)->pluck('product_id');

        $query = Product::query();
        $query->where('product_name', 'like', '%' . $name . '%');
        $query->whereIn('product_id', $pcl);
        $query->where('is_deleted', '<>', 1);
        $query = $query->get();
        $list = [];
        if ($query) {
            foreach ($query as $key => $value) {
                array_push($list, array(
                    'id' => $value->product_id,
                    'label' => strtoupper($value->product_name),
                    'value' => strtoupper($value->product_name),
                ));
            }
        }
        return $list;
    }

    public static function get_by_company_land($company_land_id)
    {
        $result = array();
        $product_id = ProductCompanyLand::query()->where('company_land_id', $company_land_id)->pluck('product_id');

        $product = Product::query()
            ->whereIn('product_id', $product_id)
            ->where('is_deleted', '<>', 1)
            ->get();
        if ($product) {
            foreach ($product as $key => $value) {
                array_push($result, array(
                    'product_id' => $value->product_id,
                    'product_label' => strtoupper($value->product_name),
                    'product_value' => strtoupper($value->product_name),
                ));
            }
        }
        return $result;
    }

    public static function get_by_product_category_id($product_category_id)
    {
        $product = array();
        $query = Product::query()->whereHas('product_category', function($q) use($product_category_id){
                        $q->where('product_category_id', $product_category_id);
                    })->get();
        if ($query) {
            foreach ($query as $key => $value) {
                array_push($product, array(
                    'id' => $value->product_id,
                    'label' => strtoupper($value->product_name),
                    'value' => strtoupper($value->product_name),
                ));
            }
        }

        return $product;
    }

    public static function get_by_product_category_id_land_id($product_category_id, $land_id)
    {
        $product = array();
        $query = Product::query();

        if($product_category_id){
           $query->whereHas('product_category', function($q) use($product_category_id){
                $q->where('product_category_id', $product_category_id);
            });
        }

        if($land_id){
            $query->whereHas('product_company_land', function($q) use($land_id){
                $q->where('company_land_id', $land_id);
            });
        }

        $result = $query->get();

        if ($result) {
            foreach ($result as $key => $value) {
                array_push($product, array(
                    'id' => $value->product_id,
                    'label' => strtoupper($value->product_name),
                    'value' => strtoupper($value->product_name),
                ));
            }
        }
        return $product;
    }

    public static function get_product_sel_for_stock_transfer()
    {
        $query = Product::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $query->where('company_id', auth()->user()->company_id);
        }
        $result = $query->where('is_deleted', '=', '0')->where('product_status_id', '=', '2')->get();
        $temp[''] = 'Please select Product';
        foreach ($result as $product) {
            $temp[$product->product_id] = $product->product_name;
        }
        return $temp;
    }

    public static function get_product_company()
    {
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $land = CompanyLand::query()->whereIn('company_id', $ids)->get();

        }else{
            $land = CompanyLand::query()->where('company_id', auth()->user()->company_id)->get();
        }
        foreach ($land as $key => $value) {
            $land[$key] = $value->company_land_id;
        }
        $query = ProductCompanyLand::query()->whereIn('company_land_id', $land)->get();
        if (count($query) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_product_company_report($company_id)
    {
        $land = CompanyLand::query()->where('company_id', $company_id)->get();
        foreach ($land as $key => $value) {
            $land[$key] = $value->company_land_id;
        }
        $query = ProductCompanyLand::query()->whereIn('company_land_id', $land)->get();
        if (count($query) > 0) {
            dd($query);
            return $query;
        } else {
            return false;
        }
    }

    public static function get_by_company_budget_estimate2($company_id)
    {
        $land = CompanyLand::query();

        if(@$company_id){
            $land->where('company_id',$company_id);
        }

        $result = $land->get();
        $land_result = array();

        foreach ($result as $key => $value) {
            $land_result[$key] = $value->company_land_id;
        }

        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
        $query = Product::whereIn('product_id', $pcl)->get();

        return $query;
    }

    public static function get_by_company()
    {
        $arr = ['' => 'Please Select Product'];
        $land = CompanyLand::query();
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $land->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $land->where('company_id', auth()->user()->company_id);
        }
        $result = $land->get();
        $land_result = array();

        foreach ($result as $key => $value) {
            $land_result[$key] = $value->company_land_id;
        }

        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
        $query = Product::whereIn('product_id', $pcl)->get();

        if ($query) {
            foreach ($query as $key => $value) {
                $arr[$value->product_id] = $value->product_name;
            }
            return $arr;
        } else {
            return [];
        }
    }

    public static function get_product_sel_by_company_land($company_land_id)
    {
        $arr = ['' => 'Please Select Product...'];

        $clt = CompanyLandTree::query()->where('company_land_id', $company_land_id)->pluck('product_id');
        $query = Product::whereIn('product_id', $clt)->get();

        if ($query) {
            foreach ($query as $key => $value) {
                $arr[$value->product_id] = $value->product_name;
            }
            return $arr;
        } else {
            return [];
        }
    }

    public static function get_by_company_land_DO_edit($company_land_id)
    {
        $pcl = ProductCompanyLand::query()->where('company_land_id', $company_land_id)->pluck('product_id');
        $query = Product::whereIn('product_id', $pcl)->get();
        return $query;
    }

    public static function get_sel()
    {
        $result = [];
        $query = Product::where(['product_status_id' => 2, 'is_deleted' => 0])->orderBy('product_name')->get();

        if ($query->first()) {
            $result = $query->pluck('product_name', 'product_id')->toArray();
        }
        return $result;
    }

    public static function get_sel_w_filter($search)
    {
        $arr = [];
        $query = Product::where(['product_status_id' => 2, 'is_deleted' => 0])->orderBy('product_name');
        if (isset($search['product_cb_id'])) {
            $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
        }

        $result = $query->get();
        foreach ($result as $key => $value) {
          $arr[$value->product_id] = $value->product_name;
        }

        return $arr;
    }

    public static function get_product_sel()
    {
        $result = [];
        $result = ['' => 'Please Select Product...'];
        $query = Product::where(['product_status_id' => 2, 'is_deleted' => 0])->orderBy('product_name')->get();
        foreach ($query as $key => $value) {
            $result[$value->product_id] = $value->product_name;
        }

        return $result;
    }

    public static function get_w_size()
    {
        $result = [];
        $query = Product::query()->where(['product_status_id' => 2, 'is_deleted' => 0])->get();
        if ($query) {
            foreach ($query as $key => $value) {
                $size_arr = [];
                if ($value->product_size_link) {
                    foreach ($value->product_size_link as $sk => $s) {
                        $size_arr[$s->setting_product_size_id] = $s->setting_product_size_id;
                    }
                }
                $result[$key] = ['id' => $value->product_id, 'name' => $value->product_name, 'size' => $size_arr];
            }
        }

        return $result;
    }

    public static function get_w_size_2($search)
    {
        $result = [];
        $query = Product::query()->with('product_size_link.setting_size')->where(['product_status_id' => 2, 'is_deleted' => 0]);

        if(isset($search['company_id'])){
            $land_result = [];
            $cl = CompanyLand::where('company_id', $search['company_id'])->get();
                foreach($cl as $key => $company_land){
                    $land_result[$key] = $company_land->company_land_id;
                }

            $pcl = ProductCompanyLand::whereIn('company_land_id', $land_result)->pluck('product_id');
            $query->whereIn('product_id', $pcl);
        }

        if(isset($search['company_cb_id'])){
            $land_result = [];
            $cl = CompanyLand::whereIn('company_id', $search['company_cb_id'])->get();
                foreach($cl as $key => $company_land){
                    $land_result[$key] = $company_land->company_land_id;
                }

            $pcl = ProductCompanyLand::whereIn('company_land_id', $land_result)->pluck('product_id');
            $query->whereIn('product_id', $pcl);
        }

        if(isset($search['product_id'])){
            $query->where('product_id', $search['product_id']);
        }

        if(isset($search['product_cb_id'])){
            $query->whereIn('product_id', $search['product_cb_id']);
        }

        if(isset($search['product_size_id'])){
            $query->whereHas('product_size_link', function($q) use($search) {
                $q->whereHas('setting_size', function($q2) use($search) {
                    $q2->where('setting_product_size_id', $search['product_size_id']);
                });
            });
        }
        $arr = $query->get();

        if ($query) {
            foreach ($arr as $key => $value) {
                $size_arr = [];
                if ($value->product_size_link) {
                    foreach ($value->product_size_link as $sk => $s) {
                        if(isset($search['product_size_id'])){
                            if($search['product_size_id'] == $s->setting_product_size_id){
                                $size_arr[$s->setting_product_size_id] = $s->setting_size->setting_product_size_name;
                            }
                        }else{
                            $size_arr[$s->setting_product_size_id] = $s->setting_size->setting_product_size_name;
                        }
                    }
                }
                $result[$key] = ['id' => $value->product_id, 'name' => $value->product_name, 'size' => $size_arr];
            }
        }

        return $result;
    }

    public static function get_product_for_do_list_export()
    {
        $temp = array();
        $product = Product::query();
        $result = $product->get();
        foreach ($result as $key => $products){
            $temp[$products->product_id] = $products->product_name;
        }
        return $temp;
    }

    public static function get_product_name_list($product_ids = null)
    {
        $query = DB::table('tbl_product')
            ->selectRaw('CONCAT(tbl_product.product_name, " - ", tbl_setting_product_size.setting_product_size_name) as product ')
            ->leftJoin('tbl_product_size_link', function ($join) {
                $join->on('tbl_product_size_link.product_id', '=', 'tbl_product.product_id')
                ->leftJoin('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', '=', 'tbl_product_size_link.setting_product_size_id');
            })
            ->orderByRaw('tbl_product.product_ranking, product')
            ->pluck('product')->toArray();
        return $query;
    }

    public static function get_product_name_list_2($search)
    {
        if(isset($search['product_cb_id'])) {
            $query = DB::table('tbl_product')
                ->selectRaw('CONCAT(tbl_product.product_name, " - ", tbl_setting_product_size.setting_product_size_name) as product ')
                ->leftJoin('tbl_product_size_link', function ($join) {
                    $join->on('tbl_product_size_link.product_id', '=', 'tbl_product.product_id')
                        ->leftJoin('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', '=', 'tbl_product_size_link.setting_product_size_id');
                })
                ->whereIn('tbl_product.product_id', $search['product_cb_id'])
                ->orderByRaw('tbl_product.product_ranking, product')
                ->pluck('product')->toArray();
            }
        else{
            $query = DB::table('tbl_product')
                ->selectRaw('CONCAT(tbl_product.product_name, " - ", tbl_setting_product_size.setting_product_size_name) as product ')
                ->leftJoin('tbl_product_size_link', function ($join) {
                    $join->on('tbl_product_size_link.product_id', '=', 'tbl_product.product_id')
                        ->leftJoin('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', '=', 'tbl_product_size_link.setting_product_size_id');
                })
                ->orderByRaw('tbl_product.product_ranking, product')
                ->pluck('product')->toArray();
            }
            return $query;
    }

    public static function get_products_multi_company($company_ids)
    {
        $arr = [];
            if($company_ids){
                $land = CompanyLand::query();
                $land->whereIn('company_id', $company_ids);
                $result = $land->get();
                $land_result = array();
                foreach ($result as $key => $value) {
                    $land_result[$key] = $value->company_land_id;
                }
                $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
                $query = Product::whereIn('product_id', $pcl)->groupBy('product_id')->get();

                if ($query) {
                    foreach ($query as $key => $value) {
                        $arr[$key] = ['id' => $value->product_id, 'name' => $value->product_name];
                    }
                    return $arr;
                } else {
                    return [];
                }
            }else{
                return $arr;
            }
    }

    public static function get_w_size_by_company($search){
        $arr = ['result' => [], 'count' => 0];

        if(@$search['product_id']){
            $size_arr = [];
            $product = Product::where('product_id',$search['product_id'])->first();
            if(@$search['product_size_id']){
                $size = SettingSize::where('setting_product_size_id', $search['product_size_id'])->first();
                $size_arr[$size->setting_product_size_id] = $size->setting_product_size_name;
            }else if ($product->product_size_link) {
                foreach ($product->product_size_link as $sk => $s) {
                    $size_name = SettingSize::where('setting_product_size_id', $s->setting_product_size_id)->value('setting_product_size_name');
                    $size_arr[$s->setting_product_size_id] = $size_name;
                }
            }
            $result[0] = ['id' => $product->product_id, 'name' => $product->product_name, 'size' => $size_arr];
            return ['result' => $result, 'count' => count($size_arr)];
        }

        $land = CompanyLand::query();
        if(@$search['company_land_id']){
            $land->where('company_land_id',$search['company_land_id']);
        }else if(@$search['company_id']){
            $land->where('company_id', @$search['company_id']);
        }else if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $land->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $land->where('company_id', auth()->user()->company_id);
        }

        $result = $land->get();

        $land_result = array();
        foreach ($result as $key => $value) {
            $land_result[$key] = $value->company_land_id;
        }
        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
        $query = Product::whereIn('product_id', $pcl)->groupBy('product_id')->get();
        if ($query) {
            $count = 0;
            foreach ($query as $key => $value) {
                $size_arr = [];
                if ($value->product_size_link) {
                    foreach ($value->product_size_link as $sk => $s) {
                        $size_name = SettingSize::where('setting_product_size_id', $s->setting_product_size_id)->value('setting_product_size_name');
                        $size_arr[$s->setting_product_size_id] = $size_name;
                    }
                }
                $result[$key] = ['id' => $value->product_id, 'name' => $value->product_name, 'size' => $size_arr];
                $count += count($size_arr);
            }
            return ['result' => $result, 'count' => $count];
        }
        return $arr;
    }

    public static function get_product_for_invoice_import($names){

        $result = array();
        $query = Product::query()
                ->selectRaw('CONCAT( tbl_product.product_name, " (", tbl_setting_product_size.setting_product_size_name, ")" ) as name,
                    tbl_product.product_name,
                    tbl_product.product_id,
                    tbl_product_size_link.product_size_link_id,
                    tbl_setting_product_size.setting_product_size_id ')
                ->leftJoin('tbl_product_size_link','tbl_product_size_link.product_id','tbl_product.product_id')
                ->join('tbl_setting_product_size','tbl_setting_product_size.setting_product_size_id','tbl_product_size_link.setting_product_size_id')
                ->havingRaw('name IN ("' . implode('","',$names) . '")');

        $temp = $query->get();

        foreach($temp as $rows){
            $result[$rows->name] = $rows;
        }
        return $result;
    }

    public static function get_product_list_for_invoice_import(){

        $result = null;
        $query = Product::query()
                ->selectRaw('CONCAT( tbl_product.product_name, " (", tbl_setting_product_size.setting_product_size_name, ")" ) as name')
                ->leftJoin('tbl_product_size_link','tbl_product_size_link.product_id','tbl_product.product_id')
                ->join('tbl_setting_product_size','tbl_setting_product_size.setting_product_size_id','tbl_product_size_link.setting_product_size_id')
                ->orderBy('name');

        $result = $query->get();

        return $result;
    }

    public static function get_products_by_company()
    {
        $products = array();
        $land = CompanyLand::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $company_ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $company_ids[$key] = $user_company->company_id;
            }
            $land->whereIn('company_id', $company_ids);
        }else if(auth()->user()->company_id != 0){
           $land->where('company_id', auth()->user()->company_id);
        }

        $company_lands = $land->get();
        $land_result = array();

        foreach ($company_lands as $key => $company_land) {
            $land_result[$key] = $company_land->company_land_id;
        }

        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
        $query = Product::query()
                        ->whereIn('product_id', $pcl)
                        ->where('is_deleted', 0)
                        ->where('product_status_id', 2)
                        ->orderBy('product_name');

        $products = $query->get();
        return $products;
    }

    public static function get_by_company_budget_estimate_list($data)
    {
        $arr = [];
        $land = CompanyLand::query();

        if(@$data){
            $land->where('company_id', $data);
        }

        $result = $land->get();
        $land_result = array();

        foreach ($result as $key => $value) {
            $land_result[$key] = $value->company_land_id;
        }

        $pcl = ProductCompanyLand::query()->whereIn('company_land_id', $land_result)->pluck('product_id');
        $query = Product::whereIn('product_id', $pcl)->get();

        return $query;
    }

    public static function get_records_no_paginate($search)
    {
        $query = Product::with('product_size_link.setting_size','product_company_land.company_land');
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
            $ids[$key] = $user_company->company_id;
            }
            $query->whereHas('product_company_land', function ($q) use ($ids) {
                $q->whereHas('company_land', function ($q2) use ($ids){
                    $q2->whereIn('company_id',$ids);
                });
        });
        }else if(auth()->user()->company_id != 0){
            $query->whereHas('product_company_land', function ($q) {
                $q->whereHas('company_land', function ($q2){
                    $q2->where('company_id',auth()->user()->company_id);
                });
        });
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('product_name', 'like', '%' . $freetext . '%');
                $q->orWhere('product_remarks', 'like', '%' . $freetext . '%');
                $q->orWhere('product_slug', 'like', '%' . $freetext . '%');
                $q->orWhere('product_sku', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['status']) {
            $query->where('product_status_id', $search['status']);
        }

        if (@$search['product_category_id']) {
            $query->where('product_category_id', $search['product_category_id']);
        }

        if (@$search['product_size']) {
            $setting_product_size_id = $search['product_size'];
            $query->whereHas('product_size_link', function ($q) use ($setting_product_size_id) {
                $q->where('setting_product_size_id', $setting_product_size_id);
            });
        }

        if (@$search['company_id']) {
            $ld = '';
            $land = DB::select('select company_land_id from tbl_company_land where company_id = ' . $search['company_id']);

            foreach ($land as $key => $l) {
                if ($key > 1) {
                    $ld .= ', ';
                }
                $ld .= "'" . $l->company_land_id . "'";
            }

            $product = DB::select('select product_id from tbl_product_company_land where company_land_id in (' . $ld . ')');

            foreach ($product as $key => $p) {
                $product[$key] = $p->product_id;
            }
            $query->whereIn('product_id', $product);
        }

        if (@$search['company_land_id']) {
            $product = DB::select('select product_id from tbl_product_company_land where company_land_id = ' . $search['company_land_id']);
            foreach ($product as $key => $pp) {
                $product[$key] = $pp->product_id;
            }
            $query->whereIn('product_id', $product);
        }

        if(@$search['product_id']){
            $query->where('product_id', $search['product_id']);
        }

        if(@$search['product_cb_id']){
            $query->whereIn('product_id', $search['product_cb_id']);
        }

        $query->where('is_deleted', '!=', 1);

        $result = $query->orderBy('product_name', 'asc')->get();

        foreach ($result as $key => $value) {
            $value->setAttribute('product_land', ProductCompanyLand::get_by_product($value->product_id));
        }

        return $result;
    }

    public static function get_product_for_forecast_report(){
        $query = Product::where('product_category_id', 2)->orwhere('product_category_id', 3)->get();

        return $query;
    }


    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id')->where('is_deleted', '!=', 1);
    }

    public function product_status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    public function product_size_link()
    {
        return $this->hasMany(ProductSizeLink::class, 'product_id');
    }

    public function product_tag_link()
    {
        return $this->hasMany(ProductTagLink::class, 'product_id');
    }

    public function product_company_land()
    {
        return $this->hasMany(ProductCompanyLand::class, 'product_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_media')
            ->singleFile()

            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    ->format('jpg')
                    ->apply();
                $this->addMediaConversion('thumb')
                    ->crop('crop-center', 300, 300)
                    ->format('jpg')
                    ->apply();
            });
    }
    public static function get_product_category_for_report()
    {

        $result = ProductCategory::query()->get();

        return $result;
    }
}
