<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyLand extends Model
{
    protected $table = 'tbl_company_land';

    protected $primaryKey = 'company_land_id';

    const CREATED_AT = 'company_land_created';
    const UPDATED_AT = 'company_land_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_land_name', 'company_land_category_id', 'company_id', 'company_land_code', 'company_bank_id', 'company_land_total_tree', 'company_land_total_acre',
        'is_overwrite_budget', 'overwrite_budget_per_tree'
    ];

    public static function get_record( $search,$perpage)
    {
        $company = CompanyLand::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $company->where(function ($q) use ($freetext) {
                $q->where('tbl_company_land.company_land_id', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_company_land.company_land_name', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_company_land.company_land_code', 'like', '%' . $freetext . '%');
            });
        }

        $company->leftJoin('tbl_company as c', 'tbl_company_land.company_id', '=', 'c.company_id');
        $company->leftJoin('tbl_company_land_category as clc', 'tbl_company_land.company_land_category_id', '=', 'clc.company_land_category_id');

        if (@$search['company_id']) {
            $company = $company->where('c.company_id', $search['company_id']);
        }else{
            $company = $company->whereHas('company', function($q){
                $q->where('is_display', '=', 1);
            });
        }

        if (@$search['company_code']) {
            $company = $company->where('c.company_code', $search['company_code']);
        }

        if (@$search['company_land_category_id']) {
            $company = $company->where('clc.company_land_category_id', $search['company_land_category_id']);
        }

        if (@$search['company_land_id']) {
            $company = $company->where('tbl_company_land.company_land_id', $search['company_land_id']);
        }

        if (@$search['company_land_code']) {
            $company = $company->where('tbl_company_land.company_land_code', $search['company_land_code']);
        }

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
        foreach(auth()->user()->user_company as $key => $user_company){
            $ids[$key] = $user_company->company_id;
        }
            $company->whereIn('tbl_company_land.company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $company->where('tbl_company_land.company_id', auth()->user()->company_id);
        }

        $company->orderBy('company_land_created', 'DESC');
        return $company->paginate($perpage);
    }

    public static function get_company_land_code_sel(){
        $land_codes = CompanyLand::get();
        $temp[''] = 'Please select type';
        foreach($land_codes as $land_code){
            $temp[$land_code->company_land_code] = $land_code->company_land_code;
        }

        return $temp;
    }

    public static function get_company_land_sel()
    {
        $land_names = CompanyLand::query();
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();

            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $land_names->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $land_names->where('company_id', auth()->user()->company_id);
        }

        $result = $land_names->get();
        $temp = ['' => 'Please Select Company Land'];
        foreach ($result as $land_name) {
            $temp[$land_name->company_land_id] = $land_name->company_land_name;
        }
        return $temp;
    }

    public static function get_by_company_id($company_id)
    {
        $query = CompanyLand::where('company_id',$company_id);
        $result = $query->get();
        return $result;
    }

    public static function get_by_user_company_id($company_id)
    {
        $query = CompanyLand::query();
        if($company_id){
            $query->where('company_id', $company_id);
        }else{
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            }else if(auth()->user()->company_id != 0){
                $query->where('company_id', auth()->user()->company_id);
            }else{
                $query->where('company_id', '<>', 1);
            }
        }
        $result = $query->get();

        return $result;
    }

    public static function get_by_company_id_checkbox($company_id)
    {
        $arr = [];
        if($company_id){
            $land = CompanyLand::query();
            $land->whereIn('company_id', $company_id);
            $result = $land->groupBy('company_land_id')->get();
            $land_result = array();

            if($result){
                foreach ($result as $key => $value) {
                    $land_result[$key] = ['id' => $value->company_land_id, 'land_name' => $value->company_land_name ];
                }
                return $land_result;
            }else{
                return [];
            }
        }else{
            return $arr;
        }
    }

    public static function get_company_land_name_for_report($company_land_id)
    {
        $query = CompanyLand::where('company_land_id', $company_land_id);
        $result = $query->get();
        return $result;
    }

    public static function get_company_land_name($search)
    {
        $query = CompanyLand::query();

        $query->whereHas('company', function($q){
            $q->where('is_display', 1);
        });

        if(isset($search['company_land_id'])){
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (isset($search["company_land_cb_id"])) {
            $query->whereIn('company_land_id', $search['company_land_cb_id']);
        }

        if(isset($search['company_cb_id'])){
            $query->whereIn('company_id', $search['company_cb_id']);
        } elseif(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }else{
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            }else if(auth()->user()->company_id != 0){
                $query->where('company_id', auth()->user()->company_id);
            }else{
                $query->where('company_id', '<>', 1);
            }
        }

        $result = $query->get();
        return $result;
    }

    public static function get_company_land_tree_age_pointer($search)
    {
        $query = CompanyLand::query();

        $query->whereHas('company', function($q){
            $q->where('is_display', 1);
        });

        if(isset($search['company_land_id'])){
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (isset($search["company_land_cb_id"])) {
            $query->whereIn('company_land_id', $search['company_land_cb_id']);
        }

        if(isset($search['company_cb_id'])){
            $query->whereIn('company_id', $search['company_cb_id']);
        } elseif(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }else{
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            }else if(auth()->user()->company_id != 0){
                $query->where('company_id', auth()->user()->company_id);
            }else{
                $query->where('company_id', '<>', 1);
            }
        }

        $result = $query->get();

        $company_land = [];

        foreach($result as $data){
            $company_land[$data->company_id][$data->company_land_id] = $data->company_land_name;
        }

        return $company_land;
    }

    public static function get_company_land_details_by_company_id($company_id)
    {
        $land = CompanyLand::query()->where('company_id', $company_id)->get();

        foreach($land as $row){
            $row->setAttribute('items', ProductCompanyLand::get_product_by_company_land_id($row->company_land_id));
        }

        return response()->json($land);
    }

    public static function get_company_land_by_company_id($company_id)
    {
        $result = [];

        $land = CompanyLand::query()->where('company_id', $company_id)->get();

        if ($land->first()) {
            $result = $land->pluck('company_land_name', 'company_land_id')->toArray();
        }

        return $result;
    }

    public static function get_company_land_by_farm_id($search, $company_farm_id)
    {
        $result = array();

        $query = CompanyLand::query();
        $query->whereHas('company_land_category', function($q) use($company_farm_id){
                    $q->where('company_farm_id', $company_farm_id);
                });

        if (isset($search['company_id'])) {
            $query->where('company_id', $search['company_id']);
        }

        if (isset($search['company_land_id'])) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (isset($search['product_id'])) {
            $product_id = $search['product_id'];

            $query->whereHas('product_company_land', function($q) use($product_id){
                $q->whereHas('product', function($q2) use ($product_id){
                    $q2->where('product_id', $product_id);
                });
            });
        }

        if (isset($search['product_size_id'])) {
            $product_size_id = $search['product_size_id'];

            $query->whereHas('product_company_land', function($q) use($product_size_id){
                $q->whereHas('product', function($q2) use ($product_size_id){
                    $q2->whereHas('product_size_link', function($q3) use($product_size_id){
                        $q3->where('setting_product_size_id', $product_size_id);
                    });
                });
            });
        }

        $query->groupBy('company_land_name');

        $result = $query->get();

        return $result;
    }

    public static function get_company_by_farm_id($search, $company_farm_id)
    {
        $array = array();

        $query = CompanyLand::query()->with('company');
        $query->whereHas('company_land_category', function($q) use($company_farm_id){
            $q->where('company_farm_id', $company_farm_id);
        });

        if (isset($search['company_id'])) {
            $query->where('company_id', $search['company_id']);
        }

        if (isset($search['company_land_id'])) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if (isset($search['product_id'])) {
            $product_id = $search['product_id'];

            $query->whereHas('product_company_land', function($q) use($product_id){
                $q->whereHas('product', function($q2) use ($product_id){
                    $q2->where('product_id', $product_id);
                });
            });
        }

        if (isset($search['product_size_id'])) {
            $product_size_id = $search['product_size_id'];

            $query->whereHas('product_company_land', function($q) use($product_size_id){
                $q->whereHas('product', function($q2) use ($product_size_id){
                    $q2->whereHas('product_size_link', function($q3) use($product_size_id){
                        $q3->where('setting_product_size_id', $product_size_id);
                    });
                });
            });
        }

        $result = $query->get();

        foreach($result as $data){
            if(isset($array[$data->company_land_name][$data->company->company_id])){
                $array[$data->company_land_name][$data->company->company_id] += $data->company->company_name;
            }else{
                $array[$data->company_land_name][$data->company->company_id] = $data->company->company_name;
            }
        }

        return $array;
    }

    public static function get_land()
    {
        $arr = [];
        $company_land = CompanyLand::query()->get();
        foreach ($company_land as $key => $land) {
            $arr[$land->company_id][$land->company_land_id] = $land->company_land_name;
        }

      return $arr;
    }
    

    public static function get_company_land_name_by_id($company_land_id)
    {
        return CompanyLand::where('company_land_id', $company_land_id)->value('company_land_name');
    }

    public static function get_by_id($company_land_id)
    {
        $query = CompanyLand::query();
        return $query
            ->where('company_land_id', '=', $company_land_id)
            ->first();
    }

    public function company_land_category()
    {
        return $this->belongsTo('App\Model\CompanyLandCategory', 'company_land_category_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function worker()
    {
        return $this->hasMany('App\Model\Worker', 'worker_id');
    }

    public function product_company_land()
    {
        return $this->hasMany(ProductCompanyLand::class, 'company_land_id');
    }

    public function company_bank()
    {
        return $this->belongsTo('App\Model\CompanyBank', 'company_bank_id');
    }

    public function company_land_zone()
    {
        return $this->hasMany(CompanyLandZone::class, 'company_land_id')->where('is_delete', 0);
    }

    public function company_land_tree()
    {
        return $this->hasMany(CompanyLandTree::class, 'company_land_id')->where('company_land_tree_status', 'alive');
    }

    public function company_land_budget_overwrite()
    {
        return $this->hasMany(CompanyLandBudgetOverwrite::class, 'company_land_id');
    }
}
