<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyLandTreeLog extends Model
{
    protected $table = 'tbl_company_land_tree_log';
    protected $primaryKey = 'company_land_tree_log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'company_land_tree_log_created';
    const UPDATED_AT = null;

    protected $fillable = [
        'company_land_tree_id',
        'company_land_tree_action_id',
        'company_land_tree_log_description',
        'company_land_tree_log_date',
        'company_land_tree_log_value',
        'user_id',
        'is_value_required',
    ];

    public function company_land_tree()
    {
      return $this->belongsTo(CompanyLandTree::class, 'company_land_tree_id');
    }

    public function company_land_tree_action()
    {
        return $this->belongsTo(CompanyLandTreeAction::class, 'company_land_tree_action_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public static function get_records($search, $company_land_id,  $company_land_tree_id)
    {
        $query = CompanyLandTreeLog::with('company_land_tree.company_land_zone.company_land','company_land_tree_action','user');
        if($company_land_tree_id != 0 && $company_land_id == 0){
            $query->where('company_land_tree_id', '=', $company_land_tree_id);
        }elseif($company_land_tree_id == 0 && $company_land_id != 0){
          $query->whereHas('company_land_tree', function($q) use($company_land_id){
              $q->whereHas('company_land_zone', function($q2) use($company_land_id){
                $q2->whereHas('company_land', function($q3) use($company_land_id){
                  $q3->where('company_land_id', $company_land_id);
                });
              });
          });
        }
        // $search['company_land_tree_id'] = $company_land_tree_id;
        // if($search['company_land_tree_id']){
        //     $query->where('company_land_tree_id', $search['company_land_tree_id']);
        // }

        if (@$search['start_date']) {
            $query->whereDate('company_land_tree_log_date', '>=', $search['start_date']);
        }

        if (@$search['end_date']) {
            $query->whereDate('company_land_tree_log_date', '<=', $search['end_date']);
        }

        if (@$search['start_date_created']) {
            $query->whereDate('company_land_tree_log_created', '>=', $search['start_date_created']);
        }

        if (@$search['end_date_created']) {
            $query->whereDate('company_land_tree_log_created', '<=', $search['end_date_created']);
        }

        if (@$search['user_id']) {
            $query->where('user_id', '=', $search['user_id']);
        }

        if (@$search['action_id']) {
            $query->where('company_land_tree_action_id', '=', $search['action_id']);
        }

        if (@$search['company_land_tree_id']) {
            $query->where('company_land_tree_id', '=', $search['company_land_tree_id']);
        }

        if (@$search['company_land_zone_id']) {
            $query->whereHas('company_land_tree', function ($q) use($search){
                $q->where('company_land_zone_id', $search['company_land_zone_id']);
            });
        }

        if (@$search['product_id']) {
            $query->whereHas('company_land_tree', function ($q) use($search){
                $q->where('product_id', $search['product_id']);
            });
        }

        $result = $query->orderBy('company_land_tree_log_created', 'DESC')
                ->get();
        return $result;
    }

    public static function get_log($company_land_tree_log_id)
    {
        $query = CompanyLandTreeLog::with('company_land_tree','company_land_tree_action','user')
                ->where('company_land_tree_log_id', '=', $company_land_tree_log_id);


        $result = $query->get();
        return $result;
    }

    public static function get_last_treatment_by_tree($company_land_tree_id)
    {
        $query = CompanyLandTreeLog::where('company_land_tree_id', $company_land_tree_id)
        ->whereHas('company_land_tree_action', function ($query){
            $query->where('is_value_required',1);
        });
        $result = $query->latest('company_land_tree_log_date')->first();
        return $result;
    }

    public static function get_tree_no_by_land($company_land_id)
    {
      $arr = ['' => 'Please Select Tree No.'];
      $query = CompanyLandTreeLog::query()->with('company_land_tree.company_land_zone.company_land');

      $query->whereHas('company_land_tree', function($q) use($company_land_id){
          $q->whereHas('company_land_zone', function($q2) use($company_land_id){
            $q2->whereHas('company_land', function($q3) use($company_land_id){
              $q3->where('company_land_id', $company_land_id);
            });
          });
      });

      $result = $query->groupBy('company_land_tree_id')->get();

      foreach($result as $key => $logs){
        $arr[$logs->company_land_tree->company_land_tree_id] = $logs->company_land_tree->company_land_tree_no;
      }
      return $arr;
    }

    public static function get_tree_no_sel($company_land_id)
    {
        $query = CompanyLandTree::query()

            ->where('company_land_id', $company_land_id)
            ->orderBy('company_land_tree_no', 'ASC')
            ->groupBy('company_land_tree_no')
            ->get();

            $company_land_tree_id_arr = ['' => 'Please Select Tree No.'];
            if ($query) {
                foreach ($query as $key => $value) {
                    $company_land_tree_id_arr[$value->company_land_tree_id] = $value->company_land_tree_no;
                }
            }
            return $company_land_tree_id_arr;
    }


    public static function get_tree_action_by_id($company_land_tree_action_id)
    {
        // $action = CompanyLandTreeAction::query()->where('company_land_tree_action_id', $company_land_tree_action_id)->first();
        $action = CompanyLandTreeAction::find($company_land_tree_action_id);
        return ['action' => $action];
    }

    public static function get_last_updated()
    {
        $query = 'SELECT clt.company_land_id as company_land_id,
                  MAX(cltl.company_land_tree_log_created) as log_date
                  FROM tbl_company_land_tree_log as cltl
                  join tbl_company_land_tree as clt on cltl.company_land_tree_id = clt.company_land_tree_id
                  join tbl_company_land as cl on clt.company_land_id = cl.company_land_id';

        $query .= ' GROUP BY company_land_id';

        $result = DB::select($query);
        $arr = [];
        // dd($result);
        foreach($result as $key => $data){
          $arr[$data->company_land_id] = $data->log_date;
        }

        $query2 = 'SELECT clt.company_land_zone_id as company_land_zone_id,
                  MAX(cltl.company_land_tree_log_created) as log_date
                  FROM tbl_company_land_tree_log as cltl
                  join tbl_company_land_tree as clt on cltl.company_land_tree_id = clt.company_land_tree_id
                  join tbl_company_land as cl on clt.company_land_id = cl.company_land_id';

        $query2 .= ' GROUP BY company_land_zone_id';

        $result2 = DB::select($query2);
        $arr2 = [];
        // dd($result);
        foreach($result2 as $key => $data){
          $arr2[$data->company_land_zone_id] = $data->log_date;
        }
        // dd($arr);
        return ['company_land' => $arr , 'company_land_zone' => $arr2];
    }
}
