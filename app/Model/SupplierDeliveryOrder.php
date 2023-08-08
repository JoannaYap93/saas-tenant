<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SupplierDeliveryOrder extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_supplier_delivery_order';

    protected $primaryKey = 'supplier_delivery_order_id';

    const CREATED_AT = 'supplier_delivery_order_created';
    const UPDATED_AT = 'supplier_delivery_order_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'supplier_delivery_order_running_no',
        'supplier_delivery_order_no',
        'supplier_delivery_order_subtotal',
        'supplier_delivery_order_discount',
        'supplier_delivery_order_total',
        'supplier_delivery_order_tax',
        'supplier_delivery_order_grandtotal',
        'supplier_delivery_order_status',
        'supplier_delivery_order_date',
        'supplier_id',
        'company_id',
        'user_id',
    ];

    public static function get_records($search)
    {
        $user = auth()->user();
        $query = SupplierDeliveryOrder::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('tbl_supplier_delivery_order.supplier_delivery_order_no', 'like', '%' . $freetext . '%');
                $q->where('tbl_supplier_delivery_order.supplier_delivery_order_running_no', 'like', '%' . $freetext . '%');
                $q->orWhereHas('supplier', function($q2) use($freetext){
                    $q2->where('supplier_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('supplier_mobile_no', 'like', '%' . $freetext . '%');
                    $q2->orWhere('supplier_phone_no', 'like', '%' . $freetext . '%');
                    $q2->orWhere('supplier_email', 'like', '%' . $freetext . '%');
                });
            });
        }

        if(@$search['company_id']){
            $query->whereHas('company', function($q) use($search){
                $q->where('company_id', $search['company_id']);
            });
        }else{
            if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
                $ids = array();
                foreach(auth()->user()->user_company as $key => $user_company){
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereHas('company', function($q) use($ids){
                    $q->whereIn('company_id', $ids);
                });
            }else if(auth()->user()->company_id != 0){
                $company_id = auth()->user()->company_id;
                $query->whereHas('company', function($q) use($company_id){
                    $q->where('company_id', $company_id);
                });
            }else {
                $query->whereHas('company', function($q){
                    $q->where('is_display', '=', 1);
                });
            }
        }

        if(@$search['raw_material_category_id']){
            $query->whereHas('supplier_delivery_order_item', function($q) use($search){
                $q->whereHas('raw_material', function($q2) use($search){
                    $q2->whereHas('setting_raw_category', function($q3) use($search){
                        $q3->where('tbl_raw_material_category.raw_material_category_id', $search['raw_material_category_id']);
                        if (@$search['raw_material_id']) {
                            $q3->where('tbl_raw_material.raw_material_id', $search['raw_material_id']);
                        }
                    });
                });
            });
        }
        // $query->where('supplier_delivery_order_status', '<>', 'deleted');
        $query->orderBy('supplier_delivery_order_updated', 'desc');

        $result = $query->paginate(15);

        return $result;
    }

    public static function check_existing_supplier_delivery_order_items($supplier_id, $company_id, $supplier_delivery_order_no, $raw_material_id)
    {
        $result = null;
        $rmc_query = null;
        $raw_material = null;
        $do_check = false;
        $rmc_check = false;

        $query = SupplierDeliveryOrder::query();
        $query->where('supplier_id', $supplier_id);
        $query->where('company_id', $company_id);
        $query->where('supplier_delivery_order_no', $supplier_delivery_order_no);
        $query->whereHas('supplier_delivery_order_item', function($q) use($raw_material_id){
            $q->where('raw_material_id', $raw_material_id);
        });
        $result = $query->get();

        if(count($result) > 0){
            $do_check = true;
        }else{
            $rmc_query = RawMaterialCompany::where('company_id', $company_id)
                        ->where('raw_material_company_status', '=', 'active')
                        ->where('raw_material_id', $raw_material_id)
                        ->first();
            if($rmc_query){
                $rmc_check = true;
                $raw_material = $rmc_query->raw_material;
            }
        }

        return ["do_check" => $do_check, "rmc_check" => $rmc_check, "data" => $rmc_query, 'raw_material' => $raw_material];
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function supplier_company()
    {
        return $this->belongsToMany(Company::class, 'tbl_supplier_company', 'supplier_id', 'company_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function supplier_delivery_order_item()
    {
        return $this->hasMany(SupplierDeliveryOrderItem::class, 'supplier_delivery_order_id', 'supplier_delivery_order_id');
    }

    public function supplier_delivery_order_log()
    {
        return $this->hasMany(SupplierDeliveryOrderLog::class, 'supplier_delivery_order_id', 'supplier_delivery_order_id');
    }
}
