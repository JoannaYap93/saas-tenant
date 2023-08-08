<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CompanyFarm extends Model
{
    protected $table = 'tbl_company_farm';

    protected $primaryKey = 'company_farm_id';

    const CREATED_AT = 'company_farm_created';
    const UPDATED_AT = 'company_farm_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_farm_name', 'company_farm_created', 'company_farm_updated'
    ];

    public static function get_records($search, $perpage)
    {
        $query = CompanyFarm::query();

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('company_farm_name', 'like', '%' . $search['freetext'] . '%');
            });
        }

        $result = $query->orderBy('company_farm_created', 'desc')->paginate($perpage);
        return $result;
    }


    public static function get_all_farm()
    {
        $query = CompanyFarm::all();
        $result = ['' => 'Please Select Farm'];
        foreach ($query as $key => $value) {
            $result[$value->company_farm_id] = $value->company_farm_name;
        }

        return $result;
    }

    public static function get_farm()
    {
        $result = [];
        $query = CompanyFarm::query()->get();
        foreach ($query as $key => $value) {
            $result[$key] = ['id' => $value->company_farm_id, 'name' => $value->company_farm_name];
        }
        return $result;
    }

    public static function get_company_farm()
    {
        return CompanyFarm::query()->pluck('company_farm_name');

    }

    public static function get_company_farm_sel()
    {
        $result = ['' => 'Please Select Farm...'];
        $query = CompanyFarm::query()->get();
        foreach ($query as $key => $value) {
            $result[$value->company_farm_id] = $value->company_farm_name;
        }

        return $result;
    }

    public function company_land_category()
    {
        return $this->hasMany(CompanyLandCategory::class, 'company_farm_id')->where('is_deleted', 0);
    }

    public static function get_land_name_by_company_farm_id($company_farm_id)
    {
      $query = DB::table('tbl_company_land as cl')
        ->select('cl.*')
        ->join('tbl_company_land_category as clc','clc.company_land_category_id','cl.company_land_category_id')
        ->join('tbl_company_farm as cf','cf.company_farm_id','clc.company_farm_id')
        ->where('cf.company_farm_id', $company_farm_id)->get();
      $company_land_id_arr = [];
      if ($query) {
          foreach ($query as $key => $value) {
              array_push($company_land_id_arr, array(
                  'id' => $value->company_land_id,
                  'label' => $value->company_land_name,
                  'value' => $value->company_land_name,
              ));
          }
      }

      return $company_land_id_arr;
    }

    public static function get_farm_by_company_id($company_id)
    {
        $query = CompanyFarm::query()
        ->join('tbl_company_land_category','tbl_company_land_category.company_farm_id','tbl_company_farm.company_farm_id')
        ->join('tbl_company_land','tbl_company_land.company_land_category_id','tbl_company_land_category.company_land_category_id')
        ->join('tbl_company','tbl_company.company_id','tbl_company_land.company_id')
        ->where('tbl_company.company_id', $company_id)
        ->groupBy('tbl_company_farm.company_farm_id')
        ->get();

        if($query){
            $result = [];

            foreach ($query as $company_farm) {
                array_push($result, array(
                    'id' => $company_farm->company_farm_id,
                    'label' => $company_farm->company_farm_name,
                    'value' => $company_farm->company_farm_name,
                ));
            }

            return $result;
        }
        return null;
    }
}
