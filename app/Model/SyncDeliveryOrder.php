<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SyncDeliveryOrder extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_sync_delivery_order';
    protected $primaryKey = 'sync_delivery_order_id';
    const CREATED_AT = 'sync_delivery_order_date';
    const UPDATED_AT = 'sync_delivery_order_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'sync_delivery_order_no',
        'sync_delivery_order_total_quantity',
        'customer_id',
        'customer_mobile_no',
        'customer_name',
        'sync_delivery_order_created',
        'sync_delivery_order_updated',
        'sync_id',
        'is_executed',
        'customer_ic',
        'sync_delivery_order_date',
        'sync_delivery_order_type_id',
        'company_land_id',
        'delivery_order_id'
    ];


    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function delivery_order_type()
    {
        return $this->belongsTo(DeliveryOrderType::class, 'sync_delivery_order_type_id');
    }

    public function sync_delivery_order_items()
    {
        return $this->hasMany(SyncDeliveryOrderItems::class, 'sync_delivery_order_id');
    }



    public static function get_records($search)
    {
        $syncDeliveryOrder = SyncDeliveryOrder::query();
        $user = Auth::user();
        $syncDeliveryOrder->leftJoin('tbl_company_land as cl', 'cl.company_land_id', '=', 'tbl_sync_delivery_order.company_land_id');

        if (@$search['freetext']){
            $freetext = $search['freetext'];
            $syncDeliveryOrder->where(function ($q) use ($freetext) {
                $q->where('sync_delivery_order_no', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_name', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_ic', 'like', '%' . $freetext . '%');

                $q->orWhereHas('delivery_order_type', function ($q2) use ($freetext) {
                    $q2->where('delivery_order_type_name', 'like', '%' . $freetext . '%');
                });
                $q->orWhere('sync_id', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['delivery_order_type_id']) {
            $syncDeliveryOrder ->where('sync_delivery_order_type_id', $search['delivery_order_type_id']);
        }

        if (@$search['company_land_id']) {
            $syncDeliveryOrder->where('cl.company_land_id', $search['company_land_id']);
        }

        if (@$search['cc_from']) {
            $syncDeliveryOrder->where('sync_delivery_order_date', '>=', $search['cc_from'] . ' 00:00:00');
        }

        if (@$search['cc_from']) {
            $syncDeliveryOrder->where('sync_delivery_order_date', '<=', $search['cc_to'] . ' 23:59:59');
        }

        if (@$search['sync_id']) {
            $syncDeliveryOrder->where('sync_id', $search['sync_id']);
        }

        // if (auth()->user()->company_id != 0) {
        //     $syncDeliveryOrder->where('company_id', auth()->user()->company_id);
        //   }
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
          $ids = array();
          foreach(auth()->user()->user_company as $key => $user_company){
            // $company->where('company_id', $user_company->company_id);
            $ids[$key] = $user_company->company_id;
            // dd($ids[$key]);
          }
          $syncDeliveryOrder->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $syncDeliveryOrder->where('company_id', auth()->user()->company_id);
        }

        $syncDeliveryOrder->orderBy('sync_delivery_order_created', 'DESC');
        return $syncDeliveryOrder->paginate(10);
    }

    public static function get_count($sync_id)
    {
        $result = 0;

        $query = SyncDeliveryOrder::query()
                ->selectRaw('COUNT(sync_delivery_order_id) as sync_delivery_order_count')
                ->where('sync_id', $sync_id)
                ->groupBy('sync_id')
                ->first();

        if(!empty($query))
        {
            $result = $query->sync_delivery_order_count;
        }
        return $result;
    }

    public static function get_date($sync_id)
    {
        $array = array();

        $query = SyncDeliveryOrder::query()
                ->selectRaw('sync_id, DATE(sync_delivery_order_created) as do_created, DATE(sync_delivery_order_date) as do_date')
                ->where('sync_id', $sync_id)
                ->get();

        if(!empty($query))
        {
            foreach($query as $result)
            {
                if(isset($array[$result->sync_id]))
                {
                    if(isset($array[$result->sync_id]['do_created']) && !str_contains($array[$result->sync_id]['do_created'], $result->do_created)){
                        $array[$result->sync_id]['do_created'] .= "<br>" . $result->do_created;
                    }

                    if(isset($array[$result->sync_id]['do_date']) && !str_contains($array[$result->sync_id]['do_date'], $result->do_date)){
                        $array[$result->sync_id]['do_date'] .= "<br>" . $result->do_date;
                    }
                }else{
                    $array[$result->sync_id]['do_created'] = $result->do_created;
                    $array[$result->sync_id]['do_date'] = $result->do_date;
                }
            }
        }

        return $array;
    }
}
