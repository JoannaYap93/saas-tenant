<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_company';

    protected $primaryKey = 'company_id';

    const CREATED_AT = 'company_created';
    const UPDATED_AT = 'company_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_id', 'company_name', 'company_code', 'company_enable_gst', 'company_force_collect',
        'company_address', 'company_email', 'company_reg_no', 'company_phone', 'company_status', 'is_display'
    ];

    public static function get_all_company_sel()
    {
        $company = Company::query();
        if ((auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) || auth()->user()->company_id != 0) {
        
        } else {
            $company->where('company_id', '<>', 1);
        }

        $result = $company->orderBy('company_name')->get();
        $temp[''] = 'Please Select Company';

        foreach ($result as $value) {
            $temp[$value->company_id] = $value->company_name;
        }

        return $temp;
    }
    public static function get_company_sel()
    {
        $company = Company::query();
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $company->whereIn('company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $company->where('company_id', auth()->user()->company_id);
        } else {
            $company->where('company_id', '<>', 1);
        }

        $result = $company->orderBy('company_name')->get();
        $temp[''] = 'Please Select Company';

        foreach ($result as $value) {
            $temp[$value->company_id] = $value->company_name;
        }

        return $temp;
    }

    public static function get_company_sel_warehouse()
    {
        $company = Company::query()->where('is_display', 1);

        $result = $company->orderBy('company_name')->get();
        $temp[''] = 'Please Select Company';

        foreach ($result as $value) {
            $temp[$value->company_id] = $value->company_name;
        }

        return $temp;
    }
    
    public static function get_company_sel_dashboard()
    {
        $company = Company::query();
        $result = $company->orderBy('company_name')->get();
        $temp[''] = 'Please Select Company';

        foreach ($result as $value) {
            $temp[$value->company_id] = $value->company_name;
        }

        return $temp;
    }

    public static function get_company_check_box()
    {
        $company = Company::where('is_display', 1);

        // if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
        //     $ids = array();
        //     foreach(auth()->user()->user_company as $key => $user_company){
        //         $ids[$key] = $user_company->company_id;
        //     }
        //     $company->whereIn('company_id', $ids);
        // }
        $company = $company->orderBy('company_name', 'ASC')->get();
        $result = array();
        foreach($company as $value){
            $result[$value->company_id] = $value->company_name;
        }
        return $result;
    }

    public static function get_company_for_sales_analysis($search)
    {
        $company = Company::where('is_display', 1)->orderBy('company_name', 'ASC');

        if (@$search['company_id']) {
            $company->where('company_id', $search['company_id']);
        }
        // else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //         $ids = array();
        //         foreach (auth()->user()->user_company as $key => $user_company) {
        //             $ids[$key] = $user_company->company_id;
        //         }
        //         $company->whereIn('company_id', $ids);
        //     } else if (auth()->user()->company_id != 0) {
        //         $company->where('company_id', auth()->user()->company_id);
        //     } else {
        //         $company->where('company_id', '<>', 1);
        //     }
        // }

        if(isset($search['company_cb_id'])){
            $company->whereIn('company_id', $search['company_cb_id']);
        }

        $comp = $company->get();
        $result = array();

        foreach($comp as $value){
            $result[$value->company_id] = $value->company_name;
        }
        return $result;
    }

    public static function get_company_for_report()
    {
        $company = Company::query();
        $company->join('tbl_delivery_order', 'tbl_delivery_order.company_id', 'tbl_company.company_id');
        $company->join('tbl_delivery_order_expense', 'tbl_delivery_order_expense.delivery_order_id', 'tbl_delivery_order.delivery_order_id');
        $company->groupBy('tbl_company.company_id');
        $result = $company->get();

        return $result;
    }

    public static function get_company_name_by_id($company_id)
    {
        return Company::where('company_id', $company_id)->value('company_name');
    }

    public static function get_company()
    {
        $company = Company::query()->where('is_display', 1)->get();
        return $company;
    }

    public static function get_company_for_daily_report($search)
    {
        $query = Company::where('is_display', 1);

        if(isset($search['company_cb_id'])){
            $query->whereIn('company_id', $search['company_cb_id']);
        }else if(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }else{
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              $ids = array();
              foreach (auth()->user()->user_company as $key => $user_company) {
                  $ids[$key] = $user_company->company_id;
              }
              $query->whereIn('tbl_company.company_id', $ids);
          } else if (auth()->user()->company_id != 0) {
              $query->where('tbl_company.company_id', auth()->user()->company_id);
          } else {
              $query->where('tbl_company.company_id', '<>', 1);
          }
        }

        $query->orderBy('tbl_company.company_name');

        $result = $query->get();
        return $result;
    }

    public static function get_company_for_warehouse_report($search)
    {
        $query = Company::where('is_display', 1);

        if(isset($search['company_cb_id'])){
            $query->whereIn('company_id', $search['company_cb_id']);
        }else if(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }

        $query->orderBy('tbl_company.company_name');

        $result = $query->get();
        return $result;
    }

    public static function get_company_for_sales_product_company_report($search)
    {
        $arr = array();
        $company_id = auth()->user()->company_id;
        $query = Company::where('is_display', 1);

        if(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }else{
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              $ids = array();
              foreach (auth()->user()->user_company as $key => $user_company) {
                  $ids[$key] = $user_company->company_id;
              }
              $query->whereIn('company_id', $ids);
          } else if (auth()->user()->company_id != 0) {
              $query->where('company_id', auth()->user()->company_id);
          } else {
              $query->where('company_id', '<>', 1);
          }
        }

        if(isset($search['company_cb_id'])) {
            $query->whereIn('company_id', $search['company_cb_id']);
        }

        $result = $query->orderBy('company_name', 'ASC')->get();
        foreach($result as $key => $company){
            $arr[$company->company_id] = $company->company_name;
        }
        return $arr;
    }

    public static function get_company_code_sel()
    {
        $company_codes = Company::get();
        $temp[''] = 'Please select type';
        foreach ($company_codes as $company_code) {
            $temp[$company_code->company_code] = $company_code->company_code;
        }

        return $temp;
    }

    public static function get_record($search, $perpage)
    {
        $company = Company::query();
        $company->with('company_land', 'company_land.company_land_tree', 'company_bank');

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $company->where(function ($q) use ($freetext) {
                $q->where('tbl_company.company_id', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_company.company_name', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_company.company_code', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['company_id']) {
            $company = $company->where('tbl_company.company_id', $search['company_id']);
        }

        if (@$search['company_code']) {
            $company = $company->where('tbl_company.company_code', $search['company_code']);
        }

        if (@$search['farm']) {
            $cat = DB::select('select company_land_category_id from tbl_company_land_category where company_farm_id = ' . $search['farm']);
            foreach ($cat as $key => $value) {
                $cat[$key] = $value->company_land_category_id;
            }
            $land = CompanyLand::query()->whereIn('company_land_category_id', $cat)->pluck('company_id');
            $company = $company->whereIn('company_id', $land);
        }

        if (@$search['setting_bank_id']) {
            $company->whereHas('company_bank', function ($query) use ($search) {
                $query->where('setting_bank_id', $search['setting_bank_id']);
            });
        }

        if (@$search['category_id']) {
            $category = $search['category_id'];

            $anotherL = CompanyLand::query()->where('company_land_category_id', $category)->pluck('company_id');
            $company = $company->whereIn('company_id', $anotherL);
        }

        if (@$search['enable_gst']) {
            $gst =  $search['enable_gst'] == 'yes' ? 1 : 0;
            $company = $company->where('company_enable_gst', $gst);
        }

        if (@$search['enable_collect']) {
            $col =  $search['enable_collect'] == 'yes' ? 1 : 0;
            $company = $company->where('company_force_collect', $col);
        }

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $company->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $company->where('company_id', auth()->user()->company_id);
        }

        $company->orderBy('company_name', 'asc');

        $result = $company->paginate($perpage);
        return $result;
    }

    public static function get_company_code($code)
    {
        if (@$code) {
            $query = Company::query()->where('company_code', $code)->first();
            if ($query) {
                return 'false';
            } else {
                return 'true';
            }
        } else {
            return false;
        }
    }

    public static function get_land_user($id)
    {
        $land = CompanyLand::query()->where('company_id', $id)->get();
        $user = User::query()->where('company_id', $id)->where('user_type_id', 2)->get();
        $customer = Customer::query()->where('company_id', $id)->get();
        return ['land' => $land, 'user' => $user, 'customer' => $customer];
    }

    public static function get_customer_by_category($company, $category)
    {
        $customer = Customer::query()->where('company_id', $company)->where('customer_category_id', $category)->get();

        return $customer;
    }

    public static function  get_customer_by_category_without_company($category)
    {
        $customer = Customer::where('customer_category_id', $category)->get();

        return $customer;
    }

    public static function get_land_warehouse($id)
    {
        $land = CompanyLand::query()->where('company_id', $id)->get();
        $warehouse = SettingWarehouse::query()->where('company_id', $id)->where('is_deleted', '!=', 1)->get();
        return ['land' => $land, 'warehouse' => $warehouse];
    }

    public static function get_by_id($company_id)
    {
        $query = Company::query();
        return $query
            ->where('company_id', '=', $company_id)
            ->first();
    }

    public function company_land()
    {
        return $this->hasMany('App\Model\CompanyLand', 'company_id');
    }

    public function setting_warehouse()
    {
        return $this->hasMany('App\Model\SettingWarehouse', 'company_id')->where('is_deleted', 0);
    }

    public function setting_expense()
    {
        return $this->hasMany('App\Model\SettingExpense', 'company_id');
    }

    public function delivery_order()
    {
        return $this->hasMany('App\Model\DeliveryOrder', 'company_id');
    }

    public function company_land_category()
    {
        return $this->belongsTo('App\Model\CompanyLandCategory', 'company_land_category_id');
    }

    public function company_bank()
    {
        return $this->hasMany('App\Model\CompanyBank', 'company_id');
    }

    public function company_claim_approval()
    {
        return $this->hasMany('App\Model\CompanyClaimApproval', 'company_id');
    }

    public function company_land_tree()
    {
        return $this->hasMany(CompanyLandTree::class, 'company_land_id');
    }

    public function supplier()
    {
        return $this->belongsToMany(Supplier::class, 'tbl_supplier_company', 'company_id', 'supplier_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_logo')
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
}
