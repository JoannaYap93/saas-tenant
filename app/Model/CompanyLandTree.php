<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Support\Facades\DB;

class CompanyLandTree extends Model
{
    protected $table = 'tbl_company_land_tree';

    protected $primaryKey = 'company_land_tree_id';

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_land_id', 'company_land_zone_id', 'company_land_tree_no', 'product_id','company_land_tree_circumference','company_land_tree_age',
        'company_land_tree_year', 'is_sick', 'is_bear_fruit', 'company_land_tree_status', 'company_land_tree_category',
        'company_pnl_sub_item_code'
    ];

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company_land_zone()
    {
        return $this->belongsTo(CompanyLandZone::class, 'company_land_zone_id');
    }

    public function pnl_code(){
        return $this->belongsTo(CompanyPnlSubItem::class, 'company_pnl_sub_item_code','company_pnl_sub_item_code');
    }

    public function company_land_tree_log()
    {
        return $this->hasMany(CompanyLandTreeLog::class, 'company_land_tree_id');
    }

    public static function get_records($search){
        // $query = "CAST(company_land_tree_no AS int) asc";
        $query = CompanyLandTree::with('product','company_land','company_land_zone')->orderBy('company_pnl_sub_item_code')->orderByRaw("CAST(company_land_tree_no AS UNSIGNED) ASC");


        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('company_land_tree_no', 'like', '%' . $freetext . '%');
                $q->orWhere('company_land_tree_year', 'like', '%' . $freetext . '%');
                // $q->orWhere('product_slug', 'like', '%' . $freetext . '%');
                // $q->orWhere('product_sku', 'like', '%' . $freetext . '%');
            });
        }

        if(@$search['product_id']){
            $query->where('product_id', $search['product_id']);
        }

        if(@$search['company_land_tree_status']){
            $query->where('company_land_tree_status', $search['company_land_tree_status']);
        }

        if(@$search['company_land_tree_category']){
            $query->where('company_land_tree_category', $search['company_land_tree_category']);
        }

        if(@$search['company_land_zone_id']){
            $query->where('company_land_zone_id', $search['company_land_zone_id']);
        }

        if(@$search['tree_circumference_upper']){
            $query->where('company_land_tree_circumference', '<=', $search['tree_circumference_upper']);
        }

        if(@$search['tree_circumference_lower']){
            $query->where('company_land_tree_circumference', '>=', $search['tree_circumference_lower']);
        }

        // $result = $query->get();
        $result = $query->paginate(30);
        foreach($result as $row){
            $row->last_treatment=CompanyLandTreeLog::get_last_treatment_by_tree($row->company_land_tree_id);
        }
        return $result;
    }

    public static function get_records_by_id($company_land_tree_id){
        $query = CompanyLandTree::query()->where('company_land_tree_id', $company_land_tree_id )
                -> with('product','company_land','company_land_zone');


        $result = $query->get();
        return $result;
    }

    public static function get_land_tree_null_product($search)
    {
        $query = CompanyLandTree::with('product','company_land','company_land_zone')
            ->where('company_land_zone_id', $search['company_land_zone_id'])
            ->where('product_id', null);

        if(@$search['company_land_tree_status']){
            $query->where('company_land_tree_status', $search['company_land_tree_status']);
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('company_land_tree_no', 'like', '%' . $freetext . '%');
                $q->orWhere('company_land_tree_year', 'like', '%' . $freetext . '%');
            });
        }

        if(@$search['tree_circumference_upper']){
            $query->where('company_land_tree_circumference', '<=', $search['tree_circumference_upper']);
        }

        if(@$search['tree_circumference_lower']){
            $query->where('company_land_tree_circumference', '>=', $search['tree_circumference_lower']);
        }

        $result = $query->paginate(10);

        return $result;
    }

    public static function get_tree_category_global_sel()
    {
      $arr = [
        '' => 'Please Select Tree Category',
        'new' => 'New',
        'existing' => 'Existing',
        'graft' => 'Graft'
      ];

      return $arr;
    }

    public function getCompanyLandTreeAgeAttribute()
    {
        return date('Y') - $this->company_land_tree_year;
    }

    public static function get_by_zone_id($company_land_zone_id)
    {
      return $query = CompanyLandTree::query()
          ->where('company_land_zone_id', $company_land_zone_id)
          ->orderBy('company_land_tree_id', 'ASC')
          ->get();
    }
    public static function get_total_tree_by_product($company_land_zone_id)
    {
        $query=CompanyLandTree::query()
                ->selectRaw('count(company_land_tree_no) as total_tree,product_id')
                ->with('product')->where('company_land_zone_id',$company_land_zone_id)
                ->where('company_land_tree_status', 1)
                ->groupBy('product_id')->orderBy('product_id', 'desc')->get();
        return $query;
    }

    public static function find_tree_w_product_by_land($company_land_id, $company_land_zone_id)
    {
      $total_trees_land = 0;
      // dd($company_land_id);
      $query = CompanyLandTree::with('company_land_zone', 'product')
          ->whereHas('company_land_zone',function($q){
              $q->where('is_delete',0);
          });

      if($company_land_id){
          $query->where('company_land_id',$company_land_id);
      }

      if($company_land_zone_id){
          $query->where('company_land_zone_id',$company_land_zone_id);
      }

      $result = $query->get()->unique('product_id');

      $result->map(function($q) use($company_land_id,$company_land_zone_id){

          $pnl_codes = CompanyPnlSubItem::select('tbl_company_pnl_sub_item.company_pnl_sub_item_code', 'company_pnl_sub_item_name', 'tbl_company_land_tree.product_id')
              ->join('tbl_company_land_tree','tbl_company_land_tree.company_pnl_sub_item_code','=',DB::raw('tbl_company_pnl_sub_item.company_pnl_sub_item_code collate utf8mb4_general_ci'))
              ->where('tbl_company_land_tree.company_land_id', $company_land_id)
              ->where('tbl_company_land_tree.product_id', $q->product_id);

              if($company_land_zone_id){
                $pnl_codes->where('tbl_company_land_tree.company_land_zone_id', $company_land_zone_id);
              }
          $q->pnl_codes = $pnl_codes->get()->unique('company_pnl_sub_item_code');

  // dd($q);
          $q->pnl_codes->map(function($pnl_codes) use($company_land_id,$company_land_zone_id){
              $product_tree = CompanyLandTree::with('company_land_zone')
                  ->where('product_id', $pnl_codes->product_id)
                  ->where('company_pnl_sub_item_code', $pnl_codes->company_pnl_sub_item_code)
                  ->whereHas('company_land_zone', function($zone){
                      $zone->where('is_delete',0);
                  });

              if($company_land_id){
                  $product_tree->where('company_land_id', $company_land_id);
              }
              if($company_land_zone_id){
                  $product_tree->where('company_land_zone_id', $company_land_zone_id);
              }

              $pnl_codes->tree_count = $product_tree->count();

              return $product_tree;
          });

          $product_tree_total = CompanyLandTree::where('product_id', $q->product_id);
          if($company_land_id){
              $product_tree_total->where('company_land_id', $company_land_id);
          }
          if($company_land_zone_id){
              $product_tree_total->where('company_land_zone_id', $company_land_zone_id);
          }

          $q->total_tree_count = $product_tree_total->count();

          return $q;
      });

      // dd($result);

      // $query = DB::table('tbl_company_land_tree as clt')
      //     ->selectRaw('p.product_name as product_name,
      //                   p.product_id as product_id,
      //                   clt.company_pnl_sub_item_code as pnl_sub_item_code,
      //                   count(clt.product_id) as total_product_tree');
      // $query->join('tbl_product as p', 'p.product_id', '=', 'clt.product_id');
      // $query->join('tbl_company_land_zone as clz', 'clz.company_land_zone_id', '=', 'clt.company_land_zone_id');
      // $query->where('clz.is_delete', '=', 0);
      // $query->where('clt.company_land_id', $company_land_id);
      // $query->groupBy('clt.product_id', 'clt.company_pnl_sub_item_code');
      // $result = $query->orderBy('p.product_name', 'asc')->get();
      // dd($result);
      // foreach ($result as $key => $value) {
      //   $total_trees_land += $value->total_product_tree;
      // }
      // dd($result);
      return ['data' => $result];
    }

    public static function get_tree_log_by_zone_id($company_land_zone_id)
    {
      $query = CompanyLandTree::query()

          ->where('company_land_zone_id', $company_land_zone_id)
          ->orderBy('company_land_tree_id', 'ASC')
          ->get();

          $company_land_tree_id_arr = [];
          if ($query) {
              foreach ($query as $key => $value) {
                  array_push($company_land_tree_id_arr, array(
                      'id' => $value->company_land_tree_id,
                      'label' => $value->company_land_tree_no,
                  ));
              }
          }
          return $company_land_tree_id_arr;
    }

    public static function get_tree_w_product_by_land()
    {
      $arr = [];
      $query = DB::table('tbl_company_land_tree as clt')
          ->selectRaw('p.product_name as product_name,
                        p.product_id as product_id,
                        clt.company_land_id as company_land_id,
                        count(clt.product_id) as total_product_tree');
      $query->join('tbl_product as p', 'p.product_id', '=', 'clt.product_id');
      $query->groupBy('clt.company_land_id', 'clt.product_id');
      $result = $query->orderBy('p.product_name', 'asc')->get();

      foreach ($result as $key => $value) {
        if(isset($arr[$value->company_land_id][$value->product_id]['total_product_tree'])){
          $arr[$value->company_land_id][$value->product_id]['total_product_tree'] += $value->total_product_tree;
        }else{
          $arr[$value->company_land_id][$value->product_id]['total_product_tree'] = $value->total_product_tree;
        }
      }
      // dd($arr);
      return $arr;
    }

    public static function get_tree_by_setting_tree_age_report($company_land_id){

        $query = CompanyLandTree::selectRaw('tbl_company_land_tree.product_id as product,
                                            tbl_company_land_tree.company_land_tree_age as tree_age,
                                            p.product_name as product_name,
                                            tbl_company_land_tree.product_id as product_id,
                                            cl.company_land_id as company_land_id,
                                            count(tbl_company_land_tree.company_land_tree_id) as total_tree')
        ->join('tbl_product as p', 'p.product_id', 'tbl_company_land_tree.product_id')
        ->join('tbl_company_land as cl', 'cl.company_land_id', 'tbl_company_land_tree.company_land_id')
        ->join('tbl_company_land_zone as lz', 'lz.company_land_zone_id', 'tbl_company_land_tree.company_land_zone_id')
        ->where('p.is_deleted', 0)
        ->where('lz.is_delete', '!=', 1)
        ->where('tbl_company_land_tree.company_land_tree_status', 'alive')
        ->where('tbl_company_land_tree.company_land_id', $company_land_id)
        ->groupBy('tbl_company_land_tree.company_land_tree_age', 'tbl_company_land_tree.product_id');

        $result = $query->get();
        // dd($result);
        $tree_age_records = [];
        $total_by_age = [];
        foreach($result as $data){
            // $tree_age_records[$data->tree_age]['age'] = $data->tree_age;
            // $tree_age_records[$data->product_id]['product'] = $data->product_name;
                if ($data->product_id){
                if($data->tree_age){
                    if(@$tree_age_records[$data->product_id][$data->tree_age]){
                        $tree_age_records[$data->product_id][$data->tree_age] += $data->total_tree;
                        $total_by_age[$data->tree_age] = $data->total_tree;
                    }else{
                        $tree_age_records[$data->product_id][$data->tree_age] = $data->total_tree;
                        $total_by_age[$data->tree_age] = $data->total_tree;
                    }
                }else{
                    if(@$tree_age_records[$data->product_id][1]){
                        $tree_age_records[$data->product_id][1] += $data->total_tree;
                        $total_by_age[$data->tree_age] += $data->total_tree;
                    }else{
                        $tree_age_records[$data->product_id][1] = $data->total_tree;
                        $total_by_age[$data->tree_age] = $data->total_tree;
                    }
                }

            }
        }
        // dd($tree_age_records, $total_by_age);
        return $tree_age_records;
    }

    public static function get_tree_by_setting_tree_age_report_company($company_id){

        $query = CompanyLandTree::selectRaw('tbl_company_land_tree.product_id as product,
                tbl_company_land_tree.company_land_tree_age as tree_age,
                p.product_name as product_name,
                tbl_company_land_tree.product_id as product_id,
                count(tbl_company_land_tree.product_id) as total_tree')
        ->join('tbl_product as p', 'p.product_id', 'tbl_company_land_tree.product_id')
        ->join('tbl_company_land as cl', 'cl.company_land_id', 'tbl_company_land_tree.company_land_id')
        ->join('tbl_company_land_zone as lz', 'lz.company_land_zone_id', 'tbl_company_land_tree.company_land_zone_id')
        ->join('tbl_company as c', 'c.company_id', 'cl.company_id')
        ->where('p.is_deleted', 0)
        ->where('lz.is_delete', '!=', 1)
        ->where('tbl_company_land_tree.company_land_tree_status', 'alive')
        ->where('c.company_id', $company_id)
        ->groupBy('tbl_company_land_tree.company_land_tree_age', 'p.product_id');

        $result = $query->get();
        // dd($result);
        $tree_age_records = [];
        $total_by_age = [];
        foreach($result as $data){
            if ($data->product_id){
                if($data->tree_age){
                    if(@$tree_age_records[$data->product_id][$data->tree_age]){
                        $tree_age_records[$data->product_id][$data->tree_age] += $data->total_tree;
                        $total_by_age[$data->tree_age] += $data->total_tree;
                    }else{
                        $tree_age_records[$data->product_id][$data->tree_age] = $data->total_tree;
                        $total_by_age[$data->tree_age] = $data->total_tree;
                    }
                }else{
                    if(@$tree_age_records[$data->product_id][1]){
                        $tree_age_records[$data->product_id][1] += $data->total_tree;
                        $total_by_age[1] += $data->total_tree;
                    }else{
                        $tree_age_records[$data->product_id][1] = $data->total_tree;
                        $total_by_age[1] = $data->total_tree;
                    }
                }
            }
        }
        // dd($tree_age_records, $total_by_age);
        return $tree_age_records;

    }

    public static function get_tree_w_product_by_zone($company_land_id){
        $query = CompanyLandTree::selectRaw('tbl_company_land_tree.company_land_zone_id as zone,
                                            tbl_company_land_tree.company_land_id as land,
                                            clz.company_land_zone_name as zone_name,
                                            cl.company_land_name as land_name,
                                            tbl_company_land_tree.product_id as product,
                                            p.product_name as product_name,
                                            cpsi.company_pnl_sub_item_id as sub_item,
                                            tbl_company_land_tree.company_pnl_sub_item_code as sub_item_code,
                                            count(tbl_company_land_tree.product_id) as total_tree')
        ->join('tbl_product as p', 'p.product_id', 'tbl_company_land_tree.product_id')
        ->join('tbl_company_land_zone as clz', 'clz.company_land_zone_id', 'tbl_company_land_tree.company_land_zone_id')
        ->join('tbl_company_land as cl', 'cl.company_land_id', 'tbl_company_land_tree.company_land_id')
        ->join('tbl_company_pnl_sub_item as cpsi', 'cpsi.company_pnl_sub_item_code', 'tbl_company_land_tree.company_pnl_sub_item_code')
        ->where('clz.is_delete', 0)
        ->where('p.is_deleted', 0)
        ->where('tbl_company_land_tree.company_land_tree_status', 'alive')
        ->where('tbl_company_land_tree.company_land_id', $company_land_id)
        ->groupBy('tbl_company_land_tree.company_land_zone_id', 'tbl_company_land_tree.product_id', 'tbl_company_land_tree.company_pnl_sub_item_code')
        ->orderBy('clz.company_land_zone_name', 'asc');

        $result = $query->get();
        $total_tree_by_land = array();
        $zone = array();
        $total_by_zone = array();
        $total_by_product = array();
        $total_tree = array();
        $total_tree_by_sub_item = array();
        $total_by_sub_item = array();
        $land = array();

        foreach($result as $data){
            if(@$total_tree_by_land[$data->zone][$data->product]){
                $total_tree_by_land[$data->zone][$data->product] += $data->total_tree;
            }else{
                $total_tree_by_land[$data->zone][$data->product] = $data->total_tree;
            }

            $zone[$data->zone] = $data->zone_name;

            $land[$data->land] = $data->land_name;

            if(@$total_by_zone[$data->zone]){
                $total_by_zone[$data->zone] += $data->total_tree;
            }else{
                $total_by_zone[$data->zone] = $data->total_tree;
            }

            if(@$total_by_product[$data->product]){
                $total_by_product[$data->product] += $data->total_tree;
            }else{
                $total_by_product[$data->product] = $data->total_tree;
            }

            if(@$total_tree){
                $total_tree += $data->total_tree;
            }else{
                $total_tree = $data->total_tree;
            }

            if(@$total_tree_by_sub_item[$data->zone][$data->sub_item]){
                $total_tree_by_sub_item[$data->zone][$data->sub_item] += $data->total_tree;
            }else{
                $total_tree_by_sub_item[$data->zone][$data->sub_item] = $data->total_tree;
            }

            if(@$total_by_sub_item[$data->sub_item]){
                $total_by_sub_item[$data->sub_item] += $data->total_tree;
            }else{
                $total_by_sub_item[$data->sub_item] = $data->total_tree;
            }

        }

        return ['total_tree_by_land' => $total_tree_by_land, 'land' => $land, 'zone' => $zone, 'total_by_zone' => $total_by_zone, 'total_by_product' => $total_by_product, 'total_tree' => $total_tree, 'total_tree_by_sub_item' => $total_tree_by_sub_item, 'total_by_sub_item' => $total_by_sub_item];
    }

    public static function get_sick_tree_w_product_report($company_land_id, $product_id){

        $query = CompanyLandTree::selectRaw('tbl_company_land_tree.company_land_id as land,
                                            tbl_company_land_tree.company_land_tree_id as id,
                                            p.product_name as product_name,
                                            cl.company_land_name as land_name,
                                            tbl_company_land_tree.company_land_tree_no as tree,
                                            tbl_company_land_tree.company_land_zone_id as zone,
                                            lz.company_land_zone_name as zone_name,
                                            p.product_name as product_name,
                                            tbl_company_land_tree.product_id as product_id,
                                            cl.company_land_id as company_land_id,
                                            count(tbl_company_land_tree.company_land_tree_id) as total_tree')
        ->join('tbl_product as p', 'p.product_id', 'tbl_company_land_tree.product_id')
        ->join('tbl_company_land as cl', 'cl.company_land_id', 'tbl_company_land_tree.company_land_id')
        ->join('tbl_company_land_zone as lz', 'lz.company_land_zone_id', 'tbl_company_land_tree.company_land_zone_id')
        ->where('p.is_deleted', 0)
        ->where('lz.is_delete', '!=', 1)
        ->where('tbl_company_land_tree.is_sick', 1)
        ->where('tbl_company_land_tree.company_land_tree_status', 'alive')
        ->where('tbl_company_land_tree.company_land_id', $company_land_id)
        ->where('tbl_company_land_tree.product_id', $product_id)
        ->orderBy('tbl_company_land_tree.company_land_zone_id', 'asc')
        ->groupBy('tbl_company_land_tree.company_land_tree_id');

        $result = $query->get();

        $land = [];
        $zone = [];
        $tree = [];
        $product = [];
        $total_sick_tree = [];
        foreach($result as $data){

            $tree[$data->id] = $data->tree;

            $land[$data->land] = $data->land_name;

            $product[$data->product_id] = $data->product_name;

            $zone[$data->id] = $data->zone_name;


            if (@$total_sick_tree[$data->product_id]) {
                $total_sick_tree[$data->product_id] += $data->total_tree;
            } else {
                $total_sick_tree[$data->product_id] = $data->total_tree;
            }

        }

        return ['total_sick_tree' => $total_sick_tree, 'zone' => $zone, 'tree' => $tree, 'land' => $land, 'product' => $product];
    }

}
