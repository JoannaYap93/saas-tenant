<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Log;

class Supplier extends Model
{
    protected $table = 'tbl_supplier';

    protected $primaryKey = 'supplier_id';

    const CREATED_AT = 'supplier_created';
    const UPDATED_AT = 'supplier_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_id',
        'supplier_name',
        'supplier_mobile_no',
        'supplier_phone_no',
        'supplier_email',
        'supplier_address',
        'supplier_address2',
        'supplier_city',
        'supplier_state',
        'supplier_country',
        'supplier_postcode',
        'supplier_pic',
        'supplier_currency',
        'supplier_credit_term',
        'supplier_credit_limit',
        'supplier_status',
    ];

    public static function get_records($search)
    {
        $user = auth()->user();
        $supplier = Supplier::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $supplier->where(function ($query) use ($freetext) {
                $query->where('tbl_supplier.supplier_name', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_supplier.supplier_mobile_no', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_supplier.supplier_phone_no', 'like', '%' . $freetext . '%')
                    ->orwhere('tbl_supplier.supplier_email', 'like', '%' . $freetext . '%')
                    ->orWhere('tbl_supplier.supplier_pic', 'like', '%' . $freetext . '%');
            });
        }

        if(@$search['company_id']){
            $supplier->whereHas('supplier_company', function($query) use($search){
                $query->where('tbl_supplier_company.company_id', $search['company_id']);
            });
        }

        if(@$search['company_id']){
            $supplier->whereHas('supplier_company', function($query) use($search){
                $query->where('tbl_supplier_company.company_id', $search['company_id']);
            });
        }else{
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $supplier->whereHas('supplier_company', function($query) use($ids){
                    $query->whereIn('tbl_supplier_company.company_id', $ids);
                });
            }else if(auth()->user()->company_id != 0){
                $company_id = auth()->user()->company_id;
                $supplier->whereHas('supplier_company', function($query) use($company_id){
                    $query->where('tbl_supplier_company.company_id', $company_id);
                });
            }else {
                $supplier->whereHas('supplier_company', function($query){
                    $query->where('tbl_company.is_display', '=', 1);
                });
            }
        }


        if(@$search['raw_material_category_id']){
            $supplier->whereHas('raw_material', function($query) use($search){
                $query->whereHas('setting_raw_category', function($query2) use($search){
                    $query2->where('tbl_raw_material_category.raw_material_category_id', $search['raw_material_category_id']);
                    if (@$search['raw_material_id']) {
                        $query2->where('tbl_raw_material.raw_material_id', $search['raw_material_id']);
                    }
                });
            });
        }

        $supplier->orderBy('supplier_created', 'DESC');
        return $supplier->paginate(15);
    }

    public static function supplier_sel()
    {
        $supplier_list = array();

        $query = Supplier::query();

        if(auth()->user()->company_id == 0 && !is_null(auth()->user()->user_company))
        {
            $company_id = UserCompany::query()->where('user_id', auth()->user()->user_id)->pluck('company_id')->toArray();

            $query->whereHas('supplier_company', function($q) use($company_id){
                $q->whereIn('tbl_supplier_company.company_id', $company_id);
            });
        }
        else if(auth()->user()->company_id != 0)
        {
            $query->whereHas('supplier_company', function($q) {
                $q->where('tbl_supplier_company.company_id', auth()->user()->company_id);
            });
        }

        $query->where('supplier_status', '=', 'Active');
        $result = $query->get();

        foreach($result as $supplier)
        {
            $supplier_list[$supplier->supplier_id] = $supplier->supplier_name;
        }

        return $supplier_list;
    }

    public static function get_supplier_sel($search)
    {
        $supplier_list = array();

        $query = Supplier::query();

        if(@$search['supplier_id']){
                $query->where('tbl_supplier.supplier_id', $search['supplier_id']);
        }
        else{
            if(auth()->user()->company_id == 0 && !is_null(auth()->user()->user_company))
        {
            $company_id = UserCompany::query()->where('user_id', auth()->user()->user_id)->pluck('company_id')->toArray();

            $query->whereHas('supplier_company', function($q) use($company_id){
                $q->whereIn('tbl_supplier_company.company_id', $company_id);
            });
        }
        else if(auth()->user()->company_id != 0)
        {
            $query->whereHas('supplier_company', function($q) {
                $q->where('tbl_supplier_company.company_id', auth()->user()->company_id);
            });
        }
    }
        $query->where('supplier_status', '=', 'Active');

        // if(@$search['company_id']){
        //     $supplier->whereHas('supplier_company', function($query) use($search){
        //         $query->where('tbl_supplier_company.company_id', $search['company_id']);
        //     });
        // }

        $result = $query->get();

        return $result;
    }

    public static function check_supplier_mobile_exist($supplier_mobile_no, $supplier_id = 0)
    {
        $result = false;
        $query = Supplier::where([
            'supplier_mobile_no' => $supplier_mobile_no,
        ]);
        if ($supplier_id > 0) {
            $query->where('supplier_id', '!=', $supplier_id);
        }
        $check_supplier = $query->first();

        if ($check_supplier) {
            $result = true;
        }
        return $result;
    }

    public static function check_supplier_phone_exist($supplier_phone_no, $supplier_id = 0)
    {
        $result = false;
        $query = Supplier::where([
            'supplier_phone_no' => $supplier_phone_no,
        ]);
        if ($supplier_id > 0) {
            $query->where('supplier_id', '!=', $supplier_id);
        }
        $check_supplier = $query->first();

        if ($check_supplier) {
            $result = true;
        }
        return $result;
    }

    public static function get_supplier_by_company_id($company_id)
    {
        $result = null;
        $supplier_list = array();
        log::info($company_id);
        $query = Supplier::query();
        $query->whereHas('supplier_company', function($q) use($company_id){
            $q->where('tbl_supplier_company.company_id', $company_id);
        });
        $query->where('supplier_status', '=', 'Active');
        $result = $query->get();

        if($result){
            foreach ($result as $supplier) {
                array_push($supplier_list, array(
                    'id' => $supplier->supplier_id,
                    'value' => $supplier->supplier_name,
                ));
            }
        }

        return $supplier_list;
    }

    public function supplier_company()
    {
        return $this->belongsToMany(Company::class, 'tbl_supplier_company', 'supplier_id', 'company_id');
    }

    public function raw_material()
    {
        return $this->belongsToMany(SettingRawMaterial::class, 'tbl_supplier_raw_material', 'supplier_id', 'raw_material_id');
    }

    public function supplier_bank()
    {
        return $this->hasMany(SupplierBank::class, 'supplier_id', 'supplier_id');
    }

    public function supplier_delivery_order()
    {
        return $this->hasMany(SupplierDeliveryOrder::class, 'supplier_id', 'supplier_id');
    }
}
