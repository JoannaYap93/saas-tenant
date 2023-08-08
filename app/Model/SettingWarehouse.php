<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SettingWarehouse extends Model
{
    use HasSlug;
    protected $table = 'tbl_setting_warehouse';
    protected $primaryKey = 'warehouse_id';
    const CREATED_AT = 'warehouse_cdate';
    const UPDATED_AT = 'warehouse_udate';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'warehouse_name', 'warehouse_status', 'warehouse_slug', 'warehouse_ranking', 'warehouse_cdate', 'warehouse_udate',
        'company_id', 'is_deleted'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('warehouse_name')
        ->saveSlugsTo('warehouse_slug');
    }

    public static function get_records($search, $perpage)
    {
        $setting_warehouse = SettingWarehouse::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $setting_warehouse->where(function ($q) use ($freetext) {
                $q->where('warehouse_name', 'like', '%' . $freetext . '%');
            });
        }
        if (@$search['warehouse_status']) {
            $setting_warehouse->where('warehouse_status', $search['warehouse_status']);
        }
        if (@$search['company_id']) {
            $setting_warehouse->where('company_id', $search['company_id']);
        }

        if (@$search['warehouse_id']) {
            $setting_warehouse->where('warehouse_id', $search['warehouse_id']);
        }

        // if (auth()->user()->company_id != 0) {
        //   $setting_warehouse->where('company_id', auth()->user()->company_id);
        // }
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                // $company->where('company_id', $user_company->company_id);
                $ids[$key] = $user_company->company_id;
            }
            $setting_warehouse->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $setting_warehouse->where('company_id', auth()->user()->company_id);
        }
        //
        // if (isset($search['gateway'])) {
        //     $settingPayment->where('is_payment_gateway', $search['gateway']);
        // }

        $setting_warehouse->with('company');
        $setting_warehouse->orderBy('warehouse_id', 'DESC');

        return $setting_warehouse->paginate($perpage);
    }

  public static function get_all_warehouse_sel()
  {
    $query = SettingWarehouse::query()->get();

    $temp[''] = 'Please select Warehouse';
    foreach ($query as $warehouse) {
      $temp[$warehouse->warehouse_id] = $warehouse->warehouse_name;
    }
    return $temp;
  }

  public static function get_warehouse_sel()
  {
    $company_id = auth()->user()->company_id ?? null;
    $query = SettingWarehouse::query();

    // if (@$company_id && $company_id != 0) {
    //   $query->where('company_id', $company_id);
    // }
    if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
      $ids = array();
      foreach(auth()->user()->user_company as $key => $user_company){
        // $company->where('company_id', $user_company->company_id);
        $ids[$key] = $user_company->company_id;
        // dd($ids[$key]);
      }
      $query->whereIn('company_id', $ids);
    }else if(auth()->user()->company_id != 0){
       $query->where('company_id', auth()->user()->company_id);
    }

    $result = $query->get();

    $temp[''] = 'Please select Warehouse';
    foreach ($result as $warehouse) {
      $temp[$warehouse->warehouse_id] = $warehouse->warehouse_name;
    }
    return $temp;
  }

  public static function get_warehouse_sel_by_company($company_id)
  {
    $query = SettingWarehouse::query();

    if (@$company_id && $company_id != 0) {
      $query->where('company_id', $company_id);
    }

    $result = $query->where('is_deleted', '=', 0)->get();

    $temp[''] = 'Please Select Warehouse';

    foreach ($result as $value) {
      $temp[$value->warehouse_id] = $value->warehouse_name;
    }
    return $temp;
  }

  public static function get_warehouse_for_expense(){
    $query = SettingWarehouse::query();
    $result = $query->where('is_deleted', '=', 0)->get();
    return $result;
  }

  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }
}
