<?php

namespace App\Model;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Invoice extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_invoice';
    protected $primaryKey = 'invoice_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'invoice_created';
    const UPDATED_AT = 'invoice_updated';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_address',
        'customer_address2',
        'customer_state',
        'customer_city',
        'customer_postcode',
        'customer_country',
        'invoice_subtotal',
        'invoice_total_discount',
        'invoice_total',
        'invoice_total_gst',
        'invoice_grandtotal',
        'invoice_total_round_up',
        'company_id',
        'user_id',
        'invoice_status_id',
        'invoice_no',
        'invoice_created',
        'invoice_updated',
        'is_approved',
        'is_approved_date',
        'company_land_id',
        'company_bank_id',
        'invoice_date',
        'invoice_remark',
        'invoice_amount_paid',
        'invoice_amount_remaining'
    ];

    public static function get_records($search, $export = false)
    {
        $query = Invoice::query()->with('delivery_orders')
                                 ->whereHas('company', function($q){
                                    $q->where('is_display', '=', 1);
                                 });

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->where('customer_name', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_address', 'like', '%' . $freetext . '%');
                $q->orWhere('invoice_no', 'like', '%' . $freetext . '%');
                $q->orWhere('invoice_id', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_city', 'like', '%' . $freetext . '%');
                $q->orWhere('customer_state', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['iv_created_from']) {
            $query->where('invoice_created', '>=', $search['iv_created_from'] . ' 00:00:00');
        }

        if (@$search['iv_created_to']) {
            $query->where('invoice_created', '<=', $search['iv_created_to'] . ' 23:59:59');
        }

        if (@$search['iv_from']) {
            $query->where('invoice_date', '>=', $search['iv_from'] . ' 00:00:00');
        }

        if (@$search['iv_to']) {
            $query->where('invoice_date', '<=', $search['iv_to'] . ' 23:59:59');
        }

        if (@$search['invoice_id']) {
            $query->where('invoice_id', $search['invoice_id']);
        }

        if (@$search['is_approved']) {
            $query->where('is_approved', $search['is_approved']);
        }

        if (@$search['invoice_status_id']) {
            $query->where('invoice_status_id', $search['invoice_status_id']);
        } else {
            $query->where('invoice_status_id', '<>', 3);
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
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

        if (@$search['product_category_id']) {
            $product_category_id = $search['product_category_id'];

            $query->whereHas('invoice_item', function ($q) use ($product_category_id) {
                $q->whereHas('product', function ($q2) use ($product_category_id) {
                    $q2->where('product_category_id', $product_category_id);
                });
            });
        }

        if (@$search['product_id']) {
            $item = DB::select('select invoice_id from tbl_invoice_item where product_id = ' . $search['product_id']);
            foreach ($item as $key => $value) {
                $item[$key] = $value->invoice_id;
            }
            $query->whereIn('invoice_id', $item);
        }

        if (@$search['product_size_id']) {
            $size = DB::select('select invoice_id from tbl_invoice_item where setting_product_size_id = ' . $search['product_size_id'] . ' and product_id = ' . $search['product_id']);
            foreach ($size as $key => $value) {
                $size[$key] = $value->invoice_id;
            }
            $query->whereIn('invoice_id', $size);
        }

        if (@$search['invoice_item_price'] && @$search['company_land_name']) {
            $item = InvoiceItem::select('invoice_id')
                ->where([
                    'setting_product_size_id' => $search['product_size_id'],
                    'product_id' => $search['product_id']
                ])
                ->whereHas('delivery_order_item', function($q) use($search){
                    $q->where('delivery_order_item_price_per_kg',$search['invoice_item_price'])
                        ->whereHas('delivery_order', function($q2) use($search){
                            $q2->whereHas('company_land', function($q3) use($search){
                                $q3->where('company_land_name', $search['company_land_name']);
                            });
                        });
                })
                ->get();
            foreach ($item as $key => $value) {
                $item[$key] = $value->invoice_id;
            }
            $query->whereIn('invoice_id', $item);
        }

        // if (@auth()->user()->company_id && auth()->user()->company_id != 0) {
        //     $query->where('company_id', auth()->user()->company_id);
        // }
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        }

        $query->orderBy('invoice_created', 'desc');
        if ($export == false) {
            $result = $query->paginate(15);
        } else {
            $result = $query->get();
        }
        foreach ($result as $row) {
            $row->setAttribute('iv_log', InvoiceLog::get_payment($row->invoice_id));
        }

        return $result;
    }

    public static function count_status($search, $status)
    {
        $result = null;
        foreach ($status as $key => $value) {
            $query = Invoice::query()->where('invoice_status_id', $value->invoice_status_id);

            if (@$search['freetext']) {
                $freetext = $search['freetext'];
                $query->where(function ($q) use ($freetext) {
                    $q->where('customer_name', 'like', '%' . $freetext . '%');
                    $q->orWhere('customer_address', 'like', '%' . $freetext . '%');
                    $q->orWhere('invoice_no', 'like', '%' . $freetext . '%');
                    $q->orWhere('invoice_id', 'like', '%' . $freetext . '%');
                    $q->orWhere('customer_city', 'like', '%' . $freetext . '%');
                    $q->orWhere('customer_state', 'like', '%' . $freetext . '%');
                });
            }

            if (@$search['iv_created_from']) {
                $query->where('invoice_created', '>=', $search['iv_created_from'] . ' 00:00:00');
            }

            if (@$search['iv_created_to']) {
                $query->where('invoice_created', '<=', $search['iv_created_to'] . ' 23:59:59');
            }

            if (@$search['iv_from']) {
                $query->where('invoice_date', '>=', $search['iv_from'] . ' 00:00:00');
            }

            if (@$search['iv_to']) {
                $query->where('invoice_date', '<=', $search['iv_to'] . ' 23:59:59');
            }

            if (@$search['invoice_id']) {
                $query->where('invoice_id', $search['invoice_id']);
            }

            if (@$search['is_approved']) {
                $query->where('is_approved', $search['is_approved']);
            }

            if (@$search['company_id']) {
                $query->where('company_id', $search['company_id']);
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

            if (@$search['product_category_id']) {
                $product_category_id = $search['product_category_id'];

                $query->whereHas('invoice_item', function ($q) use ($product_category_id) {
                    $q->whereHas('product', function ($q2) use ($product_category_id) {
                        $q2->where('product_category_id', $product_category_id);
                    });
                });
            }

            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('company_id', auth()->user()->company_id);
            }

            if (@$search['product_id']) {
                $item = DB::select('select invoice_id from tbl_invoice_item where product_id = ' . $search['product_id']);
                foreach ($item as $key => $i) {
                    $item[$key] = $i->invoice_id;
                }
                $query->whereIn('invoice_id', $item);
            }

            if (@$search['product_size_id']) {
                $size = DB::select('select invoice_id from tbl_invoice_item where setting_product_size_id = ' . $search['product_size_id'] . ' and product_id = ' . $search['product_id']);
                foreach ($size as $key => $s) {
                    $size[$key] = $s->invoice_id;
                }
                $query->whereIn('invoice_id', $size);
            }

            if (@$search['invoice_item_price'] && @$search['company_land_name']) {
                $item2 = InvoiceItem::select('invoice_id')
                    ->where([
                        'setting_product_size_id' => $search['product_size_id'],
                        'product_id' => $search['product_id']
                    ])
                    ->whereHas('delivery_order_item', function($q) use($search){
                        $q->where('delivery_order_item_price_per_kg',$search['invoice_item_price'])
                            ->whereHas('delivery_order', function($q2) use($search){
                                $q2->whereHas('company_land', function($q3) use($search){
                                    $q3->where('company_land_name', $search['company_land_name']);
                                });
                            });
                    })
                    ->get();
                foreach ($item2 as $key => $val) {
                    $item2[$key] = $val->invoice_id;
                }
                $query->whereIn('invoice_id', $item2);
            }

            $result[$value->invoice_status_id] = $query->count('invoice_id');
        }

        if ($result) {
            $result['default'] = $result[1] + $result[2] + $result[4] + $result[5];
        }

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

    public static function get_total_amount_w_status($search)
    {
        $query = Invoice::query();
        $query->selectRaw('SUM(tbl_invoice.invoice_grandtotal) as grand_total, tbl_invoice.invoice_status_id as status_id');
        if (@$search['iv_from']) {
            $query->where('invoice_created', '>=', $search['iv_from'] . ' 00:00:00');
        }

        if (@$search['iv_from']) {
            $query->where('invoice_created', '<=', $search['iv_to'] . ' 23:59:59');
        }

        if (@$search['iv_created_from']) {
            $query->where('invoice_created', '>=', $search['iv_created_from'] . ' 00:00:00');
        }

        if (@$search['iv_created_to']) {
            $query->where('invoice_created', '<=', $search['iv_created_to'] . ' 23:59:59');
        }

        if (@$search['invoice_id']) {
            $query->where('invoice_id', $search['invoice_id']);
        }

        if (@$search['is_approved']) {
            $query->where('is_approved', $search['is_approved']);
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
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

        if (@$search['product_category_id']) {
            $product_category_id = $search['product_category_id'];

            $query->whereHas('invoice_item', function ($q) use ($product_category_id) {
                $q->whereHas('product', function ($q2) use ($product_category_id) {
                    $q2->where('product_category_id', $product_category_id);
                });
            });
        }

        if (@$search['product_id']) {
            $item = DB::select('select invoice_id from tbl_invoice_item where product_id = ' . $search['product_id']);
            foreach ($item as $key => $value) {
                $item[$key] = $value->invoice_id;
            }
            $query->whereIn('invoice_id', $item);
        }

        if (@$search['product_size_id']) {
            $size = DB::select('select invoice_id from tbl_invoice_item where setting_product_size_id = ' . $search['product_size_id'] . ' and product_id = ' . $search['product_id']);
            foreach ($size as $key => $value) {
                $size[$key] = $value->invoice_id;
            }
            $query->whereIn('invoice_id', $size);
        }

        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        }

        $query->groupBy('invoice_status_id');
        $query->orderBy('invoice_status_id', 'ASC');

        $result = $query->get();
        $arr = array();
        foreach($result as $value){
            $arr[$value->status_id] = $value->grand_total;
        }
        return $arr;
    }

    public static function get_records_by_customer_id($id, $ajax = false)
    {
        $query = Invoice::query()->where('customer_id', $id);

        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        }

        $result = $query->get();
        foreach ($result as $row) {
            $row->setAttribute('items', InvoiceItem::get_item_by_invoice_id($row->invoice_id));
        }

        if ($ajax == true) {
            return response()->json(['data' => $result, 'status' => true]);
        } else {
            return $result;
        }
    }

    public static function get_invoice_details_by_id($invoice_id, $ajax = false)
    {
        $query = Invoice::query()->where('invoice_id', $invoice_id);
        $result = $query->get();
        $setting_payment_slug = [];

        $query2 = SettingPayment::query()->get();

        foreach ($query2 as $key => $value) {
          $setting_payment_slug[$value->setting_payment_id]['slug'] = $value->setting_payment_slug;
          $setting_payment_slug[$value->setting_payment_id]['name'] = $value->setting_payment_name;
        }

        foreach ($result as $row) {

            $row->setAttribute('items', InvoiceItem::get_item_by_invoice_id($row->invoice_id));
            $row->setAttribute('setting_payment_slug', $setting_payment_slug);
            $img = $row->hasMedia('payment_slip') ? $row->getFirstMediaUrl('payment_slip') : null;
            $invoice = $row->invoice_payment;
        }

        if ($ajax == true) {
            return response()->json(['data' => $result, 'status' => true, 'img' => $img, 'setting_payment_slug' => $setting_payment_slug]);
        } else {
            return $result;
        }
    }

    public static function get_by_id($invoice_id)
    {
        $query = Invoice::query()
            ->with('customer', 'company')
            ->where('invoice_id', $invoice_id);
        $result = $query->first();
        return $result;
    }

    public static function get_price_information($search)
    {
        $query = "(SELECT CONCAT(p.product_name,' - ',sps.setting_product_size_name) as product,MIN(doi.delivery_order_item_price_per_kg) as min,MAX(doi.delivery_order_item_price_per_kg) as max,
                DATE(do.delivery_order_created) as date,SUBSTRING_INDEX(cl.company_land_name,' - ',1) as farm_name, 'do_item' as source, p.product_ranking, sps.setting_product_size_name
                FROM tbl_delivery_order_item doi
                INNER JOIN tbl_product p ON p.product_id=doi.product_id
                INNER JOIN tbl_setting_product_size sps ON sps.setting_product_size_id=doi.setting_product_size_id
                INNER JOIN tbl_delivery_order do ON do.delivery_order_id = doi.delivery_order_id
                JOIN tbl_company c ON c.company_id = do.company_id
                JOIN tbl_company_land cl ON cl.company_land_id = do.company_land_id
                WHERE do.delivery_order_status_id != 3"; // not deleted

        if (@$search['date']) {
            $query .= " AND DATE(do.delivery_order_created) = '{$search['date']}'";
        }
        $query .= " GROUP BY p.product_id,sps.setting_product_size_id,DATE(do.delivery_order_created),farm_name)

                    UNION

                    (SELECT CONCAT(p.product_name, ' - ', sps.setting_product_size_name) as product, product_info_price as min, product_info_price as max,
                    product_info_date as date, cf.company_farm_name as farm_name, 'product_info' as source, p.product_ranking, sps.setting_product_size_name
                    FROM tbl_product_info pi
                    INNER JOIN tbl_setting_product_size sps ON sps.setting_product_size_id = pi.setting_product_size_id
                    INNER JOIN tbl_company_farm cf on cf.company_farm_id = pi.company_farm_id
                    INNER JOIN tbl_product p on p.product_id = pi.product_id
                    WHERE pi.is_deleted = 0";

        if (@$search['date']) {
            $query .= " AND product_info_date = '{$search['date']}'";
        }

        $query .= ") ORDER BY product_ranking, product";

        $result = DB::select($query);
        $data = array();
        $data['products'] = array();
        $data['product_list'] = array();
        foreach ($result as $row) {
            if(!in_array($row->product, $data['products'], true) && $row->max){
                array_push($data['products'], $row->product);
            }
            $data[$row->product][$row->farm_name]['min'] = $row->min;
            $data[$row->product][$row->farm_name]['max'] = $row->max;
        }

        $products = Product::get_product_name_list();
        $data['product_list'] = array_merge($data['products'], array_diff($products, $data['products']));
        return $data;
    }

    public static function get_price_analysis($search)
    {
        $data = array();
        $query = "SELECT  i.company_id, i.company_land_id, cf.company_farm_id, ii.setting_product_size_id ,ii.product_id, SUM(invoice_item_total) as price, SUM(invoice_item_quantity) as qty,DATE(i.invoice_date) as invoice_date, (SUM(invoice_item_total)/SUM(invoice_item_quantity)) as average
                FROM tbl_invoice_item ii
                INNER JOIN tbl_invoice i ON i.invoice_id=ii.invoice_id
                JOIN  tbl_setting_product_size s ON ii.setting_product_size_id = s.setting_product_size_id
                JOIN tbl_company_land cl ON i.company_land_id=cl.company_land_id
                JOIN tbl_company_land_category cc ON cl.company_land_category_id = cc.company_land_category_id
                JOIN tbl_company_farm cf ON cc.company_farm_id= cf.company_farm_id
                WHERE i.invoice_status_id <> 3";
        if (@$search['product_id']) {
            $query .= " AND (ii.product_id) = '{$search['product_id']}'";
        }

        if (@$search['setting_product_size_id']) {
            $query .= "AND ii.setting_product_size_id = '{$search['product_size_id']}'";

        }

        if (@$search['date_from']) {
            $query .= " AND DATE(i.invoice_date) >= '{$search['date_from']}'";
        }

        if (@$search['date_to']) {
            $query .= " AND DATE(i.invoice_date) <= '{$search['date_to']}'";
        }

        if (@$search['company_id']) {
            $query .=" AND i.company_id = '{$search['company_id']}'";

        }

        if (@$search['company_land_id']) {
            $query .=" AND i.company_land_id = '{$search['company_land_id']}'";
        }

        if (@$search['company_farm_id']) {
            $query .=" AND cc.company_farm_id = '{$search['company_farm_id']}'";
        }

        $query .= " GROUP BY DATE(i.invoice_date)
                    ORDER BY DATE(i.invoice_date)";

        $result = DB::select($query);
        foreach ($result as $row) {
            $temp[$row->invoice_date]['price'] = $row->price;
            $temp[$row->invoice_date]['average'] = $row->average;
            $temp[$row->invoice_date]['qty'] = $row->qty;
        }

        $period = new DatePeriod(
            new DateTime($search['date_from']),
            new DateInterval('P1D'),
            new DateTime($search['date_to'])
        );

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');
            $data['period'][] = $date;
            if (@$temp[$date]) {
                $data['price'][] = $temp[$date]['price'];
                $data['average'][] = $temp[$date]['average'];
                $data['qty'][] = $temp[$date]['qty'];
            } else {
                $data['price'][] = 0;
                $data['average'][] = 0;
                $data['qty'][] = 0;
            }
        }
        return $data;
    }

    public static function get_min_max_detail($search_date, $product, $company_farm_name){
        $product = explode(' - ',$product);

        $query = DB::table('tbl_delivery_order_item as doi')
            ->selectRaw('do.invoice_id, do.company_id, c.company_name, cl.company_land_id, cl.company_land_name, min(doi.delivery_order_item_price_per_kg) as min, max(doi.delivery_order_item_price_per_kg) as max')
            ->join('tbl_delivery_order as do','do.delivery_order_id','doi.delivery_order_id')
            ->join('tbl_company as c', 'c.company_id', 'do.company_id')
            ->join('tbl_company_land as cl', 'cl.company_land_id', 'do.company_land_id')
            ->join('tbl_company_land_category as clc', 'clc.company_land_category_id', 'cl.company_land_category_id')
            ->join('tbl_company_farm as cf', 'cf.company_farm_id', 'clc.company_farm_id')
            ->join('tbl_product as p', 'p.product_id', 'doi.product_id')
            ->join('tbl_setting_product_size as sps', 'sps.setting_product_size_id', 'doi.setting_product_size_id')
            ->whereDate('do.delivery_order_created', $search_date)
            ->where('p.product_name', $product[0])
            ->where('sps.setting_product_size_name', $product[1])
            ->where('cf.company_farm_name', $company_farm_name)
            ->groupBy('do.company_id','do.company_land_id')
            ->orderBy('max', 'desc')
            ->get();

        $data = array();

        foreach($query as $row){
            $data[$row->company_id][$row->company_land_name] = $row;
            $data['company'][$row->company_id] = $row->company_name;
            $data['company_land'][$row->company_land_name] = $row->company_land_name;
        }
        return $data;
    }

    public static function get_sales_analysis($search){
        $arr = [];
        $arr2 = [];
        $arr3 = [];
        $sum_total = 0;
        $sum_qty = 0;
        $sum_warehouse = 0;

        $query = Invoice::selectRaw('tbl_company.company_id as company_id,
                                    tbl_company.company_name as company_name,
                                    min(date(tbl_invoice.invoice_date)) as min_date,
                                    max(date(tbl_invoice.invoice_date)) as max_date,
                                    sum(tbl_invoice.invoice_grandtotal) as invoice_grandtotal');
        $query->join('tbl_company', 'tbl_company.company_id', 'tbl_invoice.company_id');
        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_invoice.invoice_status_id', '<>', 3);
        
        if(isset($search['date_from'])){
            $query->whereDate('tbl_invoice.invoice_date', '>=', $search['date_from']);
        }

        if(isset($search['date_to'])){
            $query->whereDate('tbl_invoice.invoice_date', '<=', $search['date_to']);
        }

        if (@$search['company_id']) {
            $query->where('tbl_invoice.company_id', $search['company_id']);
        }
        // else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //         $ids = array();
        //         foreach (auth()->user()->user_company as $key => $user_company) {
        //             $ids[$key] = $user_company->company_id;
        //         }
        //         $query->whereIn('tbl_invoice.company_id', $ids);
        //     } else if (auth()->user()->company_id != 0) {
        //         $query->where('tbl_invoice.company_id', auth()->user()->company_id);
        //     } else {
        //         $query->where('tbl_invoice.company_id', '<>', 1);
        //     }
        // }

        if(isset($search['company_cb_id'])){
            $query->whereIn('tbl_invoice.company_id', $search['company_cb_id']);
        }
        
        $result = $query->groupBy('company_id')->get();

        foreach($result as $key => $value){
            $arr[$value->company_id]['company_id'] = $value->company_id;
            $arr[$value->company_id]['min_date'] = $value->min_date;
            $arr[$value->company_id]['max_date'] = $value->max_date;
            $arr[$value->company_id]['invoice_grandtotal'] = $value->invoice_grandtotal;
            $sum_total += $value->invoice_grandtotal;
        }

        $query2 = DB::table('tbl_invoice_item as ii')
                    ->selectRaw('sum(ii.invoice_item_quantity) as invoice_item_total,
                    c.company_id as company_id')
                    ->join('tbl_invoice as i', 'ii.invoice_id', '=', 'i.invoice_id')
                    ->join('tbl_company as c', 'i.company_id', '=', 'c.company_id')
                    ->where('c.is_display', 1)
                    ->where('i.invoice_status_id', '<>', 3);

        if(isset($search['date_from'])){
            $query2->whereDate('i.invoice_date', '>=', $search['date_from']);
        }

        if(isset($search['date_to'])){
            $query2->whereDate('i.invoice_date', '<=', $search['date_to']);
        }

        if(isset($search['company_id'])){
            $query2->where('i.company_id', $search['company_id']);
        }

        if(isset($search['company_cb_id'])){
            $query2->whereIn('i.company_id', $search['company_cb_id']);
        }

        $result2 = $query2->groupBy('i.company_id')->get();

        foreach ($result2 as $key => $value2) {
            $arr2[$value2->company_id]['company_id'] = $value2->company_id;
            $arr2[$value2->company_id]['invoice_item_total'] = $value2->invoice_item_total;
            $sum_qty += $value2->invoice_item_total;
        }

        $query3 = DB::table('tbl_delivery_order as do')
        ->selectRaw('c.company_id as company_id,
                    do.delivery_order_type_id as type_warehouse,
                    min(date(do.delivery_order_created)) as min_date,
                    max(date(do.delivery_order_created)) as max_date,
                    sum(do.delivery_order_total_quantity) as warehouse_total')
        ->join('tbl_company as c', 'c.company_id', '=', 'do.company_id')
        ->where('c.is_display', 1)
        ->where('do.delivery_order_type_id', 2)
        ->where('do.delivery_order_status_id', '<>', 3);

        if(isset($search['date_from'])){
            $query3->whereDate('do.delivery_order_created', '>=', $search['date_from']);
        }

        if(isset($search['date_to'])){
            $query3->whereDate('do.delivery_order_created', '<=', $search['date_to']);
        }

        if(isset($search['company_id'])){
            $query3->where('do.company_id', $search['company_id']);
        }
        // else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //         $ids = array();
        //         foreach (auth()->user()->user_company as $key => $user_company) {
        //             $ids[$key] = $user_company->company_id;
        //         }
        //         $query3->whereIn('do.company_id', $ids);
        //     } else if (auth()->user()->company_id != 0) {
        //         $query3->where('do.company_id', auth()->user()->company_id);
        //     } else {
        //         $query3->where('do.company_id', '<>', 1);
        //     }
        // }

        if(isset($search['company_cb_id'])){
            $query3->whereIn('do.company_id', $search['company_cb_id']);
        }

        $result3 = $query3->groupBy('company_id')->get();

        foreach ($result3 as $key => $value3) {
            if ($value3->type_warehouse == 2){         
                if(isset($arr3[$value3->company_id])){
                    $arr3[$value3->company_id]['company_id'] = $value3->company_id;
                    $arr3[$value3->company_id]['min_date'] = $value3->min_date;
                    $arr3[$value3->company_id]['max_date'] = $value3->max_date;
                    $arr3[$value3->company_id]['warehouse'] += $value3->warehouse_total;
                }else{
                    $arr3[$value3->company_id]['company_id'] = $value3->company_id;
                    $arr3[$value3->company_id]['min_date'] = $value3->min_date;
                    $arr3[$value3->company_id]['max_date'] = $value3->max_date;
                    $arr3[$value3->company_id]['warehouse'] = $value3->warehouse_total;
                }
                $sum_warehouse += $value3->warehouse_total;
            }
        }

        return ['result' => $arr, 'result2' => $arr2, 'result3' => $arr3, 'sums' => $sum_total, 'sum_qty' => $sum_qty, 'sum_warehouse' => $sum_warehouse];
    }

    public static function get_by_invoice_remark($invoice_remark){
        $query = Invoice::query()
            ->where('invoice_remark', $invoice_remark);
        $result = $query->first();
        return $result;
    }

    public function invoice_item()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id')->orderBy('product_id')->orderBy('setting_product_size_id');
    }

    public function invoice_payment()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }

    public function invoice_status()
    {
        return $this->belongsTo(InvoiceStatus::class, 'invoice_status_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function invoice_log()
    {
        return $this->hasMany(InvoiceLog::class, 'invoice_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo(CompanyLand::class, 'company_land_id');
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'invoice_id', 'invoice_id');
    }

    public function delivery_orders()
    {
        return $this->hasMany(DeliveryOrder::class,'invoice_id');
    }

    public function customer_credit_history()
    {
        return $this->hasMany('App\Model\CustomerCreditHistory', 'invoice_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_slip')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    ->apply();
                $this->addMediaConversion('thumb')
                    ->format('jpg')
                    ->crop('crop-center', 300, 300)
                    ->apply();
            });
    }
   
}
