<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyLandZone extends Model
{
    protected $table = 'tbl_company_land_zone';

    protected $primaryKey = 'company_land_zone_id';

    const CREATED_AT = 'company_land_zone_created';
    const UPDATED_AT = 'company_land_zone_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_land_zone_name', 'company_id', 'company_land_id', 'company_land_zone_total_tree', 'is_delete'
    ];

    public function company_land_tree()
    {
      return $this->hasMany(CompanyLandTree::class, 'company_land_zone_id')->where('company_land_tree_status', 'alive');
    }

    public static function get_record($search, $company_id , $company_land_id)
    {
        $zone = CompanyLandZone::query()->where('company_id', $company_id )
                ->where('company_land_id', $company_land_id )
                ->where('is_delete', '!=', 1);
        $zone->with('company_land');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $zone->where(function ($q) use ($freetext) {
                $q->where('company_land_zone_name', 'like', '%' . $freetext . '%');
            });
        }

        $zone->orderByRaw('LENGTH(company_land_zone_name) ASC')->orderBy('company_land_zone_name', 'ASC');
        $result = $zone->get();
        foreach($result as $row){
            $row->tree_detail=CompanyLandTree::get_total_tree_by_product($row->company_land_zone_id);
        }
        return $result;
    }

    public static function get_zone_sel($company_land_id)
    {
        $result = [];
        $result = ['' => 'Please Select Zone...'];
        $query = CompanyLandZone::where('company_land_id',$company_land_id)->where('is_delete',0)->orderBy('company_land_zone_name', 'ASC')->get();
        foreach ($query as $key => $value) {
            $result[$value->company_land_zone_id] = $value->company_land_zone_name;
        }
        return $result;
    }

    public static function get_record_by_id($company_land_zone_id)
    {
        $zone = CompanyLandZone::query()->where('company_land_zone_id', $company_land_zone_id );
        $zone-> with('company_land');

        $zone -> orderBy('company_land_zone_name', 'asc');
        $result = $zone->get();
        return $result;
    }

    public static function get_by_land($company_land_id, $company_id)
    {
        if(@$company_id){
            $query = CompanyLandZone::where('company_land_id', $company_land_id)
                ->where('company_id', $company_id)
                ->where('is_delete', 0)
                ->get();
            return ['data' => $query];

        }else{
            $query = CompanyLandZone::where('company_land_id', $company_land_id)
                ->where('company_id', auth()->user()->company_id)
                ->where('is_delete', 0)
                ->get();
            return ['data' => $query];

        }
    }

    public static function get_by_land_id($company_land_id)
    {
            $query = CompanyLandZone::where('company_land_id', $company_land_id)
                ->get();
            return ['data' => $query];
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

}
