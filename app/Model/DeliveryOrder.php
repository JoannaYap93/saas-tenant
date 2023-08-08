<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeliveryOrder extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_delivery_order';
    protected $primaryKey = 'delivery_order_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'delivery_order_created';
    const UPDATED_AT = 'delivery_order_updated';

    protected $fillable = [
        'delivery_order_no',
        'delivery_order_created',
        'delivery_order_updated',
        'customer_id',
        'customer_name',
        'customer_mobile_no',
        'customer_ic',
        'delivery_order_total_quantity',
        'sync_id',
        'delivery_order_status_id',
        'company_id',
        'company_land_id',
        'invoice_id',
        'delivery_order_type_id',
        'user_id',
        'warehouse_id',
        'delivery_order_remark',
        'delivery_order_created'
    ];

    public function delivery_order_expense()
    {
        return $this->hasMany(DeliveryOrderExpense::class, 'delivery_order_id');
    }

    public function delivery_order_items()
    {
        return $this->hasMany(DeliveryOrderItem::class, 'delivery_order_id')->orderBy('product_id')->orderBy('setting_product_size_id');
    }

    public function delivery_order_status()
    {
        return $this->belongsTo(DeliveryOrderStatus::class, 'delivery_order_status_id');
    }

    public function delivery_order_log()
    {
        return $this->hasMany(DeliveryOrderLog::class, 'delivery_order_id');
    }

    public function customer_details()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function delivery_order_type()
    {
        return $this->belongsTo(DeliveryOrderType::class, 'delivery_order_type_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(SettingWarehouse::class, 'warehouse_id');
    }

    public function customer_pic()
    {
        return $this->hasMany(CustomerPIC::class, 'customer_id', 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('delivery_order_signature')
            // ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    // ->format('jpg')
                    ->apply();
                $this->addMediaConversion('thumb')
                    ->format('jpg')
                    ->crop('crop-center', 300, 300)
                    ->apply();
            });
    }

    public static function get_records($search)
    {
        $query = DeliveryOrder::query()->whereHas('company', function($q){
            $q->where('is_display', '=', 1);
        });

        // $company_id = auth()->user()->company_id ?? null;
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
          $ids = array();
          foreach(auth()->user()->user_company as $key => $user_company){
            // $company->where('company_id', $user_company->company_id);
            $ids[$key] = $user_company->company_id;
            // dd($ids[$key]);
          }
          $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
           $company_id = auth()->user()->company_id;
        }

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('tbl_delivery_order.delivery_order_id', 'like', '%' . $freetext . '%');
                $q->orWhere('delivery_order_no', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_ic', 'like', '%' . $freetext . '%');

                $q->orWhereHas('customer_details', function ($q2) use ($freetext) {
                    $q2->where('customer_company_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_name', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_mobile_no', 'like', '%' . $freetext . '%');
                    $q2->orWhere('customer_email', 'like', '%' . $freetext . '%');
                });
            });
        }

        if (@$search['do_from']) {
            $query->whereDate('delivery_order_created', '>=', DATE($search['do_from']) . ' 00:00:00');
        }

        if (@$search['do_to']) {
            $query->whereDate('delivery_order_created', '<=', DATE($search['do_to']) . ' 23:59:59');
        }

        if (@$search['order_type_id']) {
            $query->where('delivery_order_type_id', @$search['order_type_id']);
        }

        if (@$search['order_status_id']) {
            $query->where('delivery_order_status_id', @$search['order_status_id']);
        } else {
            $query->where('delivery_order_status_id', '!=', 3);
            $query->where('delivery_order_status_id', '!=', 0);
        }

        if (@$search['warehouse_id']) {
            $query->where('warehouse_id', $search['warehouse_id']);
        }

        if (@$search['company']) {
            $query->where('company_id', $search['company']);
        }

        if (@$search['company_land_id']) {
            $query->where('company_land_id', $search['company_land_id']);
        }

        if(@$search['product_category_id']) {
            $product_category_id = $search['product_category_id'];

            $query->whereHas('delivery_order_items', function($q) use($product_category_id){
                $q->whereHas('product', function($q2) use($product_category_id){
                    $q2->where('product_category_id', $product_category_id);
                });
            });
        }

        if (@$search['product_id']) {
            $product = $search['product_id'];
            $items = DB::select('Select delivery_order_id from tbl_delivery_order_item where product_id = ' . $product);
            foreach ($items as $key => $value) {
                $items[$key] = $value->delivery_order_id;
            }
            // dd($items);
            $query->whereIn('delivery_order_id', $items);
        }

        if (@$search['product_size_id']) {
            $size = DB::select('select delivery_order_id from tbl_delivery_order_item where product_id = ' . $search['product_id'] . ' and setting_product_size_id = ' . $search['product_size_id']);
            foreach ($size as $key => $value) {
                $size[$key] = $value->delivery_order_id;
            }
            $query->whereIn('delivery_order_id', $size);
        }

        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if (@$search['customer_id']) {
            $query->where('customer_id', $search['customer_id']);
        }

        if (@$company_id && $company_id != 0) {
            $query->where('company_id', $company_id);
        }

        if (@$search['delivery_no']) {
            $query->where('delivery_order_no', $search['delivery_no']);
        }

        if(@$search['delivery_order_id']){
            $query->where('delivery_order_id', $search['delivery_order_id']);
        }

        // if (@$search['customer_company']) {
        //     $cust_company = $search['customer_company'];

        //     $query->whereHas('customer_details', function($q) use($cust_company){
        //         $q->where('customer_company_name', $cust_company);
        //     });
        // }

        $query->orderBy('delivery_order_created', 'desc');

        $result = $query->paginate(15);

        return $result;
    }

    public static function count_status($search, $status)
    {
        $result = null;
        $company_id = auth()->user()->company_id ?? null;
        foreach ($status as $key => $stat) {
            $query = DeliveryOrder::query()->where('delivery_order_status_id', $stat->delivery_order_status_id);

            if (@$search['freetext']) {
                $freetext = $search['freetext'];
                $query->where(function ($q) use ($freetext) {
                    $q->where('tbl_delivery_order.delivery_order_id', 'like', '%' . $freetext . '%');
                    $q->orWhere('delivery_order_no', 'like', '%' . $freetext . '%');
                    $q->orWhere('customer_ic', 'like', '%' . $freetext . '%');

                    $q->orWhereHas('customer_details', function ($q2) use ($freetext) {
                        $q2->where('customer_company_name', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_name', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_mobile_no', 'like', '%' . $freetext . '%');
                        $q2->orWhere('customer_email', 'like', '%' . $freetext . '%');
                    });
                });
            }

            if (@$search['do_from']) {
                $query->whereDate('delivery_order_created', '>=', DATE($search['do_from']) . ' 00:00:00');
            }

            if (@$search['do_to']) {
                $query->whereDate('delivery_order_created', '<=', DATE($search['do_to']) . ' 23:59:59');
            }

            if (@$search['order_type_id']) {
                $query->where('delivery_order_type_id', @$search['order_type_id']);
            }

            if (@$search['warehouse_id']) {
                $query->where('warehouse_id', $search['warehouse_id']);
            }

            if (@$search['company']) {
                $query->where('company_id', $search['company']);
            }

            if (@$search['company_land_id']) {
                $query->where('company_land_id', $search['company_land_id']);
            }

            if (@$search['user_id']) {
                $query->where('user_id', $search['user_id']);
            }

            if (@$search['customer_id']) {
                $query->where('customer_id', $search['customer_id']);
            }

            if(@$search['product_category_id']) {
                $product_category_id = $search['product_category_id'];

                $query->whereHas('delivery_order_items', function($q) use($product_category_id){
                    $q->whereHas('product', function($q2) use($product_category_id){
                        $q2->where('product_category_id', $product_category_id);
                    });
                });
            }

            if (@$company_id && $company_id != 0) {
                $query->where('company_id', $company_id);
            }

            if (@$search['product_id']) {
                $product = $search['product_id'];
                $items = DB::select('Select delivery_order_id from tbl_delivery_order_item where product_id = ' . $product);
                foreach ($items as $key => $value) {
                    $items[$key] = $value->delivery_order_id;
                }
                // dd($items);
                $query->whereIn('delivery_order_id', $items);
            }

            if (@$search['product_size_id']) {
                $size = DB::select('select delivery_order_id from tbl_delivery_order_item where product_id = ' . $search['product_id'] . ' and setting_product_size_id = ' . $search['product_size_id']);
                foreach ($size as $key => $value) {
                    $size[$key] = $value->delivery_order_id;
                }
                $query->whereIn('delivery_order_id', $size);
            }

            if (@$search['delivery_no']) {
                $query->where('delivery_order_no', $search['delivery_no']);
            }

            // if (@$search['customer_company']) {
            //     $cust_company = $search['customer_company'];

            //     $query->whereHas('customer_details', function($q) use($cust_company){
            //         $q->where('customer_company_name', $cust_company);
            //     });
            // }

            // $query->where('delivery_order_status_id', '!=', 0)->orderBy('delivery_order_created', 'desc');

            $result[$stat->delivery_order_status_id] = $query->count('tbl_delivery_order.delivery_order_id');
        }
        if ($result) {
            $result['default'] = $result[1] + $result[2] + $result[4] + $result[5];
        }

        return $result;
    }

    public static function get_records_by_customer_id($arr, $ajax = false)
    {
        $query = DeliveryOrder::query();

        foreach ($arr as $key => $value) {
            if ($key == 'customer_id') {
                $query = $query->where('customer_id', $value);
            }

            if ($key == 'date') {
                $query = $query->whereDate('delivery_order_created', date('Y-m-d', strtotime($value)));
            }

            if ($key == 'land') {
                $query = $query->where('company_land_id', $value);
            }

            if ($key == 'invoice') {
                // $query = $query->where('invoice_id', 0);
                $query = $query->where(function($q){
                  $q->where('invoice_id', '=', null);
                  $q->orWhere('invoice_id', '=', 0);
                });
                // $query = $query->where('invoice_id', '=', null);
                // $query = $query->where('invoice_id', '=', 0);
            }
        }

        // if (@$land) {
        //     $query = $query->where('company_land_id', $land)->where('invoice_id', null);
        // }

        // if (@$date) {
        //     $query = $query->whereDate('delivery_order_created', date('Y-m-d', strtotime($date)));
        // }

        $result = $query->orderBy('delivery_order_created', 'desc')->where('delivery_order_status_id', '!=', 3)->get();
        foreach ($result as $row) {
            $row->setAttribute('items', DeliveryOrderItem::get_order_by_do_id($row->delivery_order_id));
            $row->setAttribute('customer', Customer::get_by_customer_id($row->customer_id));
            $row->setAttribute('expense', DeliveryOrderExpense::get_expenses_by_order_id($row->delivery_order_id));
            $row->setAttribute('invoice', Invoice::get_invoice_by_id($row->invoice_id));
            $row->setAttribute('warehouse', SettingWarehouse::find($row->warehouse_id));
        }

        if ($ajax == true) {
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return $result;
        }
    }

    public static function get_do_quantity($do_id){
      $query = DeliveryOrder::query()
            ->select('delivery_order_total_quantity')
            ->where('delivery_order_id', '=', $do_id);

      $result = $query->get();
      return $result;
    }

    public static function get_do_count($sync_id)
    {
        $result = 0;

        $query = DeliveryOrder::query()
            ->selectRaw('sync_id,COUNT(delivery_order_id) as do_count')
            ->where('sync_id', $sync_id)
            ->groupBy('sync_id')
            ->first();

        if (!empty($query)) {
            $result = $query->do_count;
        }

        return $result;
    }

    public static function get_by_id($delivery_order_id){
        $query = DeliveryOrder::query()
            ->with('customer_details')
            ->where('delivery_order_id', $delivery_order_id);

        $result = $query->first();
        return $result;
    }

    public static function get_invoice_by_id($invoice_id)
    {
        $invoice_arr = [];
        $query = Invoice::find($invoice_id);

        if ($query) {
            $invoice_arr[0] = [$query->invoice_no];
        }

        return $invoice_arr;
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'invoice_id', 'invoice_id');
    }

    public static function get_do_details_by_id($do_id)
    {
        $result = DeliveryOrder::query()
                ->where('delivery_order_id', $do_id)
                ->get();

        foreach ($result as $row) {
            $row->setAttribute('items', DeliveryOrderItem::get_do_item_details_by_id($row->delivery_order_id));
        }

        return response()->json($result);
    }

    public static function get_records_expense($search)
    {
        $query = DeliveryOrder::query();
        // $company_id = auth()->user()->company_id ?? null;
        // if (@$company_id && @$company_id != 0) {
        //     $query->where('company_id', '=', $company_id);
        // }
        $query->where('delivery_order_type_id', 2);
        $query->join('tbl_delivery_order_expense', 'tbl_delivery_order_expense.delivery_order_id', 'tbl_delivery_order.delivery_order_id');
        $query->groupBy('tbl_delivery_order_expense.delivery_order_id');
        // if (@$search['delivery_no']) {
        //     $query->where('delivery_order_no', $search['delivery_no']);
        // }
        //
        // if(@$search['delivery_order_id']){
        //     $query->where('delivery_order_id', $search['delivery_order_id']);
        // }

        $query->orderBy('delivery_order_created', 'desc');

        $result = $query->paginate(15);

        return $result;
    }

    public static function get_do_for_expense_report()
    {
      $query = DeliveryOrder::query();

      $query->where('delivery_order_type_id', 2);
      $query->join('tbl_delivery_order_expense', 'tbl_delivery_order_expense.delivery_order_id', 'tbl_delivery_order.delivery_order_id');
      $query->groupBy('tbl_delivery_order_expense.delivery_order_id');
      $result = $query->get();
        return $result;
    }

    public static function check_is_revertable($sync_id){
        $is_revertable = true;
        $sync = Sync::find($sync_id);

        $query = DeliveryOrder::where('sync_id', $sync_id)
            ->where('invoice_id','>',0)
            ->first();
        if($query || $sync->is_reverted){
            $is_revertable = false;
        }

        return $is_revertable;
    }

    public static function get_sales_analysis($search){
      $arr = [];
      $sum_qty = 0;

      $query = DeliveryOrder::selectRaw('tbl_company.company_id as company_id,
                                          tbl_company.company_name as company_name,
                                          min(date(tbl_delivery_order.delivery_order_created)) as min_date,
                                          max(date(tbl_delivery_order.delivery_order_created)) as max_date,
                                          sum(tbl_delivery_order.delivery_order_total_quantity) as total_do_qty');
      $query->join('tbl_company', 'tbl_company.company_id', 'tbl_delivery_order.company_id');
      $query->where('tbl_company.is_display', 1);
      if(isset($search['date_from'])){
        $query->whereDate('tbl_delivery_order.delivery_order_created', '>=', $search['date_from']);
      }

      if(isset($search['date_to'])){
        $query->whereDate('tbl_delivery_order.delivery_order_created', '<=', $search['date_to']);
      }

      if(isset($search['company_id'])){
        $query->where('tbl_delivery_order.company_id', $search['company_id']);
      }
    //   else {
    //       if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
    //           $ids = array();
    //           foreach (auth()->user()->user_company as $key => $user_company) {
    //               $ids[$key] = $user_company->company_id;
    //           }
    //           $query->whereIn('tbl_delivery_order.company_id', $ids);
    //       } else if (auth()->user()->company_id != 0) {
    //           $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
    //       } else {
    //           $query->where('tbl_delivery_order.company_id', '<>', 1);
    //       }
    //   }

      if(isset($search['company_cb_id'])){
        $query->whereIn('tbl_delivery_order.company_id', $search['company_cb_id']);
      }

      $result = $query->groupBy('tbl_delivery_order.company_id')->get();
      // dd($result);
      if($result){
        foreach($result as $key => $value){
          // dd($value);
          $arr[$value->company_id]['company_id'] = $value->company_id;
          $arr[$value->company_id]['min_date'] = $value->min_date;
          $arr[$value->company_id]['max_date'] = $value->max_date;
          $arr[$value->company_id]['total_do_qty'] = $value->total_do_qty;
          $sum_qty += $value->total_do_qty;
        }
        // dd($arr);
        return ['result' => $arr, 'sums' => $sum_qty];
      }else{
        return ['result' => $arr, 'sums' => $sum_qty];
      }
    }

    public function getIsMediaAttribute()
    {
      $items = $this->delivery_order_items;

      foreach($items as $rows){
          if($rows->hasMedia('delivery_order_item_media')){
              return true;
          }
      }

      return false;
    }

}
