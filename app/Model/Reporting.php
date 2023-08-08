<?php

namespace App\Model;

use Aws\Result;
use App\Model\Product;
use Illuminate\Support\Arr;
use App\Model\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class Reporting extends Model
{
    public static function get_product_details()
    {
        $data = array();
        $query = DB::table('tbl_product')
            ->select('tbl_product.product_id', 'tbl_product.product_name', 'tbl_setting_product_size.setting_product_size_id', 'tbl_setting_product_size.setting_product_size_name')
            ->leftJoin('tbl_product_size_link', function ($join) {
                $join->on('tbl_product_size_link.product_id', '=', 'tbl_product.product_id')
                    ->leftJoin('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', '=', 'tbl_product_size_link.setting_product_size_id');
            })
            // ->where('tbl_product.company_id', $company_id)
            ->orderBy('tbl_product.product_ranking', 'asc')
            ->orderBy('tbl_setting_product_size.setting_product_size_name')
            ->get();

        return $query;
    }

    public static function get_invoice_monthly_report($search)
    {
        $company_id = auth()->user()->company_id;
        $array = array();
        $query = "SELECT SUM(ii.invoice_item_total) as price, SUM(ii.invoice_item_quantity) as qty,
                CONCAT(p.product_name, ' - ' , s.setting_product_size_name ) as product_name,
                i.company_id as company,
                MONTH(i.invoice_date) as invoice_month
                FROM tbl_invoice_item ii
                JOIN tbl_invoice i ON i.invoice_id=ii.invoice_id
                JOIN tbl_company_land as cl ON cl.company_land_id = i.company_land_id
                JOIN tbl_company as c ON c.company_id = i.company_id
                JOIN tbl_setting_product_size s ON  s.setting_product_size_id = ii.setting_product_size_id
                JOIN tbl_product p ON p.product_id=ii.product_id
                WHERE c.is_display =1
                AND i.invoice_status_id  <> 3";

        if (isset($search['year'])) {
            $query .= " AND YEAR(i.invoice_date) = {$search['year']}";
        }

        if (@$search['company_id']) {
            $query .= " AND i.company_id = {$search['company_id']}";
        } elseif(@$search['company_cb_id']) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND i.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND i.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND i.company_id = {$company_id}";
            } else {
                $query .= " AND i.company_id <> 1";;
            }
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND ii.product_id IN ({$p_id})";
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND i.company_land_id = {$search['company_land_id']}";
        }

        if(isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND i.company_land_id IN ({$cl_id})";
        }

        if (isset($search['user_id'])) {
            $query .= " AND i.user_id = {$search['user_id']}";
        }

        if (isset($search['customer_id'])) {
            $query .= " AND i.customer_id = {$search['customer_id']}";
        }

        $query .=" GROUP BY invoice_month, product_name, i.company_id, s.setting_product_size_id
                    ORDER BY p.product_ranking ASC";

        $result = DB::select($query);
        if ($result) {
           foreach($result as $data) {
                if (isset($array[$data->product_name][$data->invoice_month]['qty'])|| isset($array[$data->product_name][$data->invoice_month]['price'])) {
                    $array[$data->product_name][$data->invoice_month]['qty'] += $data->qty;
                    $array[$data->product_name][$data->invoice_month]['price'] += $data->price;
                }else{
                    $array[$data->product_name][$data->invoice_month]['qty'] = $data->qty;
                    $array[$data->product_name][$data->invoice_month]['price'] = $data->price;
                }
            }
        }

        $query2 = "SELECT SUM(i.invoice_total_round_up) as total_round_up, SUM(i.invoice_grandtotal) as invoice_grandtotal,
                i.company_id as company,
                MONTH(i.invoice_date) as invoice_month
                FROM tbl_invoice as i
                JOIN tbl_company as c ON c.company_id = i.company_id
                WHERE c.is_display = 1
                AND i.invoice_status_id <> 3";

        if (isset($search['year'])) {
            $query2 .= " AND YEAR(i.invoice_date) = {$search['year']}";
        }

        if (@$search['company_id']) {
            $query2 .= " AND i.company_id = {$search['company_id']}";
        } elseif(@$search['company_cb_id']) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query2 .= " AND i.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query2 .= " AND i.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query2 .= " AND i.company_id = {$company_id}";
            } else {
                $query2 .= " AND i.company_id <> 1";;
            }
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query2 .= " AND ii.product_id IN ({$p_id})";
        }

        if (isset($search['company_land_id'])) {
            $query2 .= " AND i.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['user_id'])) {
            $query2 .= " AND i.user_id = {$search['user_id']}";
        }

        if (@$search['customer_id']) {
            $query2 .= " AND i.customer_id = {$search['customer_id']}";
        }

        $query2 .=" GROUP BY invoice_month";


        $array2 = array();

        $result2 = DB::select($query2);
        if ($result2) {
           foreach($result2 as $data) {
                if (isset($array2[$data->invoice_month]['total_round_up'])|| isset($array2[$data->invoice_month]['invoice_grandtotal'])) {
                    $array2[$data->invoice_month]['total_round_up'] += $data->total_round_up;
                    $array2[$data->invoice_month]['invoice_grandtotal'] += $data->invoice_grandtotal;
                }else{
                    $array2[$data->invoice_month]['total_round_up'] = $data->total_round_up;
                    $array2[$data->invoice_month]['invoice_grandtotal'] = $data->invoice_grandtotal;
                }
            }
        }

        return ['result' => $array, 'result2' => $array2];
    }

    public static function get_delivery_order_item_details($search, $daily = false)
    {
        $company_id = auth()->user()->company_id;
        $array = array();

        $query = "SELECT DISTINCT tbl_product.product_name, tbl_setting_product_size.setting_product_size_name as size,
                    MONTH(tbl_delivery_order.delivery_order_created) as do_month,
                    DATE(tbl_delivery_order.delivery_order_created) as do_date,
                    IFNULL (SUM(tbl_delivery_order_item.delivery_order_item_quantity), 0) as do_sum,
                    tbl_delivery_order.company_id, tbl_company_land.company_land_name
                FROM tbl_delivery_order
                JOIN tbl_delivery_order_item ON tbl_delivery_order_item.delivery_order_id = tbl_delivery_order.delivery_order_id
                LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_delivery_order.company_land_id
                JOIN tbl_customer ON tbl_customer.customer_id = tbl_delivery_order.customer_id
                JOIN tbl_company ON tbl_company.company_id = tbl_delivery_order.company_id
                 JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id=tbl_delivery_order_item.setting_product_size_id
                 JOIN tbl_product ON tbl_product.product_id=tbl_delivery_order_item.product_id
                 WHERE tbl_company.is_display = 1
                 AND tbl_delivery_order.delivery_order_status_id <> 3";

        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_delivery_order.delivery_order_created) = {$search['year']}";
        }

        if (isset($search['date_from'])) {
            $query .= " AND DATE(tbl_delivery_order.delivery_order_created) >= {$search['date_from']}". " 00:00:00";
        }

        if (isset($search['date_to'])) {
            $query .= " AND DATE(tbl_delivery_order.delivery_order_created) <= {$search['date_to']}". " 23:59:59";
        }

        if (isset($search['order_type'])) {
            $query .= " AND (tbl_delivery_order.delivery_order_type_id) = {$search['order_type']}";
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_delivery_order.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_delivery_order.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('tbl_delivery_order.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND tbl_delivery_order.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                $query .= " AND tbl_delivery_order.company_id =". auth()->user()->company_id;
            } else {
                // $query->where('tbl_delivery_order.company_id', '<>', 1);
                $query .= " AND tbl_delivery_order.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_delivery_order.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['company_land_cb_id'])) {
            $cb_land_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_delivery_order.company_land_id IN ({$cb_land_id})";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_delivery_order_item.product_id = {$search['product_id']}";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_delivery_order_item.setting_product_size_id = {$search['product_size_id']}";
        }

        if (isset($search['user_id'])) {
            $query .= " AND tbl_delivery_order.user_id = {$search['user_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_delivery_order_item.product_id IN ({$p_id})";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_delivery_order_item.product_id = {$search['product_id']}";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_delivery_order_item.setting_product_size_id = {$search['product_size_id']}";
        }

        if (isset($search['customer_category_id'])) {
            $query .= " AND tbl_customer.customer_category_id = {$search['customer_category_id']}";
        }

        if (isset($search['customer_id'])) {
                    $query .= " AND tbl_delivery_order.customer_id = {$search['customer_id']}";
        }

        $query .= " GROUP BY tbl_product.product_name, tbl_setting_product_size.setting_product_size_name";


        if ($daily) {
            $query .= ", DATE(tbl_delivery_order.delivery_order_created)";
        } else {
            $query .= ", MONTH(tbl_delivery_order.delivery_order_created)";
        }

        $query .= "ORDER BY tbl_delivery_order_item.product_id ASC";

        $result = DB::select($query);


        if ($result) {
            if ($daily) {
                foreach ($result as $data) {
                    //sum delivery order item quantity
                    if (isset($array[$data->product_name . '-' . $data->size][$data->do_date][$data->company_id])) {
                        $array[$data->product_name . '-' . $data->size][$data->do_date][$data->company_id] += $data->do_sum;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->do_date][$data->company_id] = $data->do_sum;
                    }
                }
            } else {
                foreach ($result as $data) {
                    //sum delivery order item quantity
                    if (isset($array[$data->product_name . '-' . $data->size][$data->do_month][$data->company_land_name])) {
                        $array[$data->product_name . '-' . $data->size][$data->do_month][$data->company_land_name] += $data->do_sum;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->do_month][$data->company_land_name] = $data->do_sum;
                    }
                }
            }
        }
        return $array;
    }

    public static function get_collect_details($search, $daily = false)
    {
        $company_id = auth()->user()->company_id;
        $array = array();

        $query = "SELECT DISTINCT tbl_product.product_name, tbl_setting_product_size.setting_product_size_name as size,
                    MONTH(tbl_collect.collect_date) as collect_month,
                    DATE(tbl_collect.collect_date) as collect_date,
                    IFNULL (SUM(tbl_collect.collect_quantity), 0 ) as collected_sum,
                    tbl_collect.company_id,
                    tbl_company_land.company_land_name,
                    tbl_collect.company_id
                FROM tbl_collect
                LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_collect.company_land_id
                JOIN tbl_company ON tbl_company.company_id = tbl_collect.company_id
                JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id = tbl_collect.setting_product_size_id
                JOIN tbl_product ON tbl_product.product_id = tbl_collect.product_id
                WHERE tbl_company.is_display = 1 AND tbl_collect.collect_status <> 'deleted' ";

        $delivery_order_item = DB::table('tbl_delivery_order_item as doi')
            ->selectRaw(
                'p.product_name, sps.setting_product_size_name as size,
                MONTH(do.delivery_order_created) as doi_month,
                DATE(do.delivery_order_created) as doi_date,
                ifnull(SUM(doi.delivery_order_item_quantity), 0) as sum_doi_quantity,
                cl.company_land_name,
                do.company_id'
            )
            ->join('tbl_delivery_order as do', 'do.delivery_order_id', '=', 'doi.delivery_order_id')
            ->join('tbl_company_land as cl', 'cl.company_land_id', '=', 'do.company_land_id')
            ->join('tbl_company as c', 'c.company_id', '=', 'do.company_id')
            ->join('tbl_setting_product_size as sps', 'sps.setting_product_size_id', '=', 'doi.setting_product_size_id')
            ->join('tbl_product as p', 'p.product_id', '=', 'doi.product_id')
            ->where('c.is_display', 1)
            ->where('doi.no_collect_code', 1)
            ->where('do.delivery_order_status_id', '<>', 3);


        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_collect.collect_date) = {$search['year']}";
            $delivery_order_item->whereYear('do.delivery_order_created', $search['year']);
        }

        if (isset($search['date_from'])) {
            $query .= " AND DATE(tbl_collect.collect_date) >= {$search['date_from']}". " 00:00:00";
            $delivery_order_item->whereDate('do.delivery_order_created', '>=', $search['date_from']. " 00:00:00");
        }

        if (isset($search['date_to'])) {
            $query .= " AND DATE(tbl_collect.collect_date) <= {$search['date_to']}". " 23:59:59";
            $delivery_order_item->whereDate('do.delivery_order_created', '<=', $search['date_from']. " 23:59:59");
        }


        if (isset($search['customer_id'])) {
            $delivery_order_item->where('do.customer_id', $search['customer_id']);
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_collect.company_id = {$search['company_id']}";
            $delivery_order_item->where('do.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_collect.company_id IN ({$cb_id})";
            $delivery_order_item->where('do.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('tbl_collect.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);
                $query .= " AND tbl_collect.company_id IN ({$ids})";
                $delivery_order_item->whereIn('do.company_id', auth()->user()->user_company->pluck('company_id')->toArray());
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_collect.company_id', auth()->user()->company_id);
                $query .= " AND tbl_collect.company_id =" .auth()->user()->company_id;
                $delivery_order_item->where('do.company_id', auth()->user()->company_id);
            } else {
                // $query->where('tbl_collect.company_id', '<>', 1);
                $query .= " AND tbl_collect.company_id <> 1";
                $delivery_order_item->where('do.company_id', '<>', 1);
            }
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_collect.product_id IN ({$p_id})";
            $delivery_order_item->where('doi.product_id', $search['product_cb_id']);
        }

        if(isset($search['product_id'])) {
            $query .= " AND tbl_collect.product_id = {$search['product_id']}";
            $delivery_order_item->where('doi.product_id', $search['product_id']);
        }

        if(isset($search['product_size_id'])) {
            $query .= " AND tbl_collect.setting_product_size_id = {$search['product_size_id']}";
            $delivery_order_item->where('doi.setting_product_size_id', $search['product_size_id']);
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_collect.company_land_id = {$search['company_land_id']}";
            $delivery_order_item->where('do.company_land_id', $search['company_land_id']);
        }

        if (isset($search["company_land_cb_id"])) {
            $cb_land_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_collect.company_land_id IN ({$cb_land_id})";
            $delivery_order_item->where('do.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['user_id'])) {
            $query .= " AND tbl_collect.user_id = {$search['user_id']}";
            $delivery_order_item->where('do.user_id', $search['user_id']);
        }


        $query .= " GROUP BY tbl_product.product_name, tbl_setting_product_size.setting_product_size_name";

        if ($daily) {
            $query .= ", DATE(tbl_collect.collect_date) ";
            $delivery_order_item->groupByRaw('p.product_id, sps.setting_product_size_id, DATE(do.delivery_order_created), do.company_id');
        } else {
            $query .= ", MONTH(tbl_collect.collect_date), tbl_company_land.company_land_name";
            $delivery_order_item->groupByRaw('p.product_id, sps.setting_product_size_id, MONTH(do.delivery_order_created), cl.company_land_id, do.company_id');
        }

        $query .= ", tbl_collect.company_id";

        $result = DB::select($query);
        // dd($result);
        $doi = $delivery_order_item->get();
        if ($result) {
            if ($daily == true) {
                foreach ($result as $data) {
                    //sum collect quantity
                    // dump($data);
                    if (isset($array[$data->product_name . '-' . $data->size][$data->collect_date][$data->company_id]['col'])) {
                        $array[$data->product_name . '-' . $data->size][$data->collect_date][$data->company_id]['col'] += $data->collected_sum;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->collect_date][$data->company_id]['col'] = $data->collected_sum;
                    }
                }
                foreach ($doi as $data) {
                    if (isset($array[$data->product_name . '-' . $data->size][$data->doi_date][$data->company_id]['col'])) {
                        $array[$data->product_name . '-' . $data->size][$data->doi_date][$data->company_id]['col'] += $data->sum_doi_quantity;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->doi_date][$data->company_id]['col'] = $data->sum_doi_quantity;
                    }
                }
            } else {
                foreach ($result as $data) {
                    //sum collect quantity
                    if (isset($array[$data->product_name . '-' . $data->size][$data->collect_month][$data->company_land_name]['col'])) {
                        $array[$data->product_name . '-' . $data->size][$data->collect_month][$data->company_land_name]['col'] += $data->collected_sum;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->collect_month][$data->company_land_name]['col'] = $data->collected_sum;
                    }
                }

                foreach ($doi as $data) {
                    //sum collect quantity
                    if (isset($array[$data->product_name . '-' . $data->size][$data->doi_month][$data->company_land_name]['col'])) {
                        $array[$data->product_name . '-' . $data->size][$data->doi_month][$data->company_land_name]['col'] += $data->sum_doi_quantity;
                    } else {
                        $array[$data->product_name . '-' . $data->size][$data->doi_month][$data->company_land_name]['col'] = $data->sum_doi_quantity;
                    }
                }
            }
        }
        // dd($array);
        return $array;
    }

    public static function get_sales_summary_details_by_product($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product.product_name, tbl_setting_product_size.setting_product_size_name,
                        SUM(tbl_invoice_item.invoice_item_quantity) as quantity,
                        SUM(tbl_invoice_item.invoice_item_total) as total,
                        tbl_product.product_id,
                        tbl_setting_product_size.setting_product_size_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id = tbl_invoice_item.setting_product_size_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                    WHERE DATE(tbl_invoice.invoice_date) >= DATE(?) AND tbl_company.is_display = 1
                    AND DATE(tbl_invoice.invoice_date) <= DATE(?) AND tbl_invoice.invoice_status_id <> 3';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_invoice.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if(isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_invoice.company_land_id IN ({$cl_id})";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_invoice_item.product_id IN ({$p_id})";
        }

        if(isset($search['product_category_id'])) {
            $query .= " AND tbl_product.product_category_id = {$search['product_category_id']}";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";
        }

        if (isset($search['customer_id'])) {
            $query .= " AND tbl_invoice.customer_id = {$search['customer_id']}";
        }

        if (isset($search['company_farm_id'])) {
            $query .= " AND tbl_company_land_category.company_farm_id = {$search['company_farm_id']}";
        }
        // if($company_id != 0){
        //     if(auth()->user()->user_type_id != 1){
        //         $query .= " AND tbl_invoice.company_id = {$company_id}";
        //     }
        // }

        $query .= " GROUP BY tbl_product.product_name, tbl_setting_product_size.setting_product_size_name
                    ORDER BY tbl_product.product_ranking ASC, tbl_product.product_name ASC,  tbl_setting_product_size.setting_product_size_name ASC";
        // dd($query);

        $result = DB::select(DB::raw($query), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);

        $invoice_query = Invoice::selectRaw('SUM(tbl_invoice.invoice_total_round_up) as round_up, SUM(tbl_invoice.invoice_grandtotal) as grandtotal')
            ->with('invoice_item', 'company_land.company_land_category')
            ->whereDate('invoice_date','>=',$search['sales_from'])
            ->whereDate('invoice_date','<=',$search['sales_to'])
            ->where('invoice_status_id','<>',3)
            ->whereHas('company',function ($q) use ($search) {
                $q->where('is_display', 1);
            });

        if (isset($search['company_farm_id'])) {
            $invoice_query->whereHas('company_land', function ($q) use ($search){
              $q->whereHas('company_land_category', function ($q2) use ($search){
                $q2->where('company_farm_id', $search['company_farm_id']);
              });
            });
        }

        if (isset($search['product_id'])) {
            $invoice_query->whereHas('invoice_item', function ($q) use ($search) {
                $q->where('product_id', $search['product_id']);
            });
        }

        if(isset($search['product_cb_id'])) {
            $invoice_query->whereHas('invoice_item', function ($q) use ($search) {
                $q->whereIn('product_id', $search['product_cb_id']);
            });
        }

        if (isset($search['product_size_id'])) {
            $invoice_query->whereHas('invoice_item', function ($q) use ($search) {
                $q->where('setting_product_size_id', $search['product_size_id']);
            });
        }

        if (isset($search['company_id'])) {
            $invoice_query->where('company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $invoice_query->whereIn('company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                // $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $invoice_query .= " AND tbl_invoice.company_id IN ({$ids})";
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $invoice_query->whereIn('company_id', $ids);

            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $invoice_query->where('company_id', auth()->user()->company_id);
            } else {
                $invoice_query->where('tbl_invoice.company_id', '<>', 1);
                // $invoice_query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (@$search['company_land_id']) {
            $invoice_query->where('company_land_id', $search['company_land_id']);
        }

        $result2 = $invoice_query->first();

        //-----------------------------------

        // $query2 = "SELECT
        //             SUM(tbl_invoice.invoice_total_round_up) as round_up,
        //             SUM(tbl_invoice.invoice_grandtotal) as grandtotal
        //             FROM tbl_invoice
        //             JOIN tbl_invoice_item ON tbl_invoice_item.invoice_id = tbl_invoice.invoice_id
        //             -- JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
        //             where date(invoice_date) >= DATE(?)
        //             And date(invoice_date) <= DATE(?)
        //             AND invoice_status_id <> 3";

        // if (isset($search['company_id'])) {
        //     $query2 .= " AND tbl_invoice.company_id = {$search['company_id']}";
        // } else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //         // $ids = array();
        //         // foreach(auth()->user()->user_company as $key => $user_company){
        //         //   // $company->where('company_id', $user_company->company_id);
        //         //   $ids[$key] = $user_company->company_id;
        //         //   // dd($ids[$key]);
        //         // }
        //         // // $query->whereIn('tbl_invoice.company_id', $ids);
        //         // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
        //         $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
        //         // $query->whereIn('tbl_invoice.company_id', $ids);

        //         $query2 .= " AND tbl_invoice.company_id IN ({$ids})";
        //     } else if (auth()->user()->company_id != 0) {
        //         // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
        //         $query2 .= " AND tbl_invoice.company_id = {$company_id}";
        //     } else {
        //         // $query->where('tbl_invoice.company_id', '<>', 1);
        //         $query2 .= " AND tbl_invoice.company_id <> 1";
        //     }
        // }

        // if (isset($search['company_land_id'])) {
        //     $query2 .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        // }

        // if (isset($search['product_id'])) {
        //     $query2 .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        // }

        // if (isset($search['product_size_id'])) {
        //     $query2 .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";

        // }

        // $result2 = DB::select(DB::raw($query2), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);
        // dd($result);
        return ['result' => $result, 'result2' => $result2 ];
    }

    public static function get_sales_summary_details_by_product_company($search)
    {
        // dd($search);
        $res_arr = array();
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product.product_name, tbl_setting_product_size.setting_product_size_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_quantity) as quantity,
                        SUM(tbl_invoice_item.invoice_item_total) as total,
                        tbl_product.product_id as product_id,
                        tbl_setting_product_size.setting_product_size_id as size_id,
                        tbl_company.company_id as company_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id = tbl_invoice_item.setting_product_size_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                    WHERE DATE(tbl_invoice.invoice_date) >= DATE(?) AND tbl_company.is_display = 1
                    AND DATE(tbl_invoice.invoice_date) <= DATE(?) AND tbl_invoice.invoice_status_id <> 3';



        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            // dd($cb_id);
            $query .= " AND tbl_invoice.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_invoice.company_land_id IN ({$cl_id})";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if(isset($search['product_category_id'])) {
            $query .= " AND tbl_product.product_category_id = {$search['product_category_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            // dd($cb_id);
            $query .= " AND tbl_invoice_item.product_id IN ({$p_id})";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";
        }

        if (isset($search['customer_id'])) {
            $query .= " AND tbl_invoice.customer_id = {$search['customer_id']}";
        }

        if (isset($search['company_farm_id'])) {
            $query .= " AND tbl_company_land_category.company_farm_id = {$search['company_farm_id']}";
        }
        // if($company_id != 0){
        //     if(auth()->user()->user_type_id != 1){
        //         $query .= " AND tbl_invoice.company_id = {$company_id}";
        //     }
        // }

        $query .= " GROUP BY tbl_product.product_name, tbl_setting_product_size.setting_product_size_name, tbl_company.company_id
                    ORDER BY tbl_product.product_ranking ASC, tbl_setting_product_size.setting_product_size_name ASC";
        // dd($query);

        $result = DB::select(DB::raw($query), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);
        // dd($result);
        $sum_of_total_company_qty = 0;
        foreach ($result as $data) {
            $sum_of_total_company_qty += $data->quantity;
            if (isset($res_arr[$data->company_id][$data->product_id][$data->size_id])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $res_arr[$data->company_id][$data->product_id][$data->size_id]['total_company_qty'] += $data->quantity;
                $res_arr[$data->company_id][$data->product_id][$data->size_id]['total_company_total'] += $data->total;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $res_arr[$data->company_id][$data->product_id][$data->size_id]['total_company_qty'] = $data->quantity;
                $res_arr[$data->company_id][$data->product_id][$data->size_id]['total_company_total'] = $data->total;
            }
        }
        // dd($res_arr);
            //-----------------------------------------------
        $res_arr2 = array();

        $query2 = 'SELECT tbl_product.product_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_quantity) as quantity,
                        SUM(tbl_invoice_item.invoice_item_total) as total,
                        tbl_product.product_id as product_id,
                        -- tbl_setting_product_size.setting_product_size_id as size_id,
                        tbl_company.company_id as company_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    -- LEFT JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id = tbl_invoice_item.setting_product_size_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                    WHERE DATE(tbl_invoice.invoice_date) >= DATE(?) AND tbl_company.is_display = 1
                    AND DATE(tbl_invoice.invoice_date) <= DATE(?) AND tbl_invoice.invoice_status_id <> 3';

            if (isset($search['company_land_id'])) {
                $query2 .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
            }

            if (isset($search['product_id'])) {
                $query2 .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
            }

        if (isset($search['company_id'])) {
            $query2 .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            // dd($cb_id);
            $query2 .= " AND tbl_invoice.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query2 .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query2 .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query2 .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query2 .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['product_id'])) {
            $query2 .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            // dd($cb_id);
            $query2 .= " AND tbl_invoice_item.product_id IN ({$p_id})";
        }

        if (isset($search['company_farm_id'])) {
            $query2 .= " AND tbl_company_land_category.company_farm_id = {$search['company_farm_id']}";
        }

        $query2 .= " GROUP BY tbl_product.product_name, tbl_company.company_id
                    ORDER BY tbl_product.product_ranking ASC";
        // dd($search);

        $result2 = DB::select(DB::raw($query2), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);
        // dd($result);
        $sum_of_total_company_qty_no_grade = 0;
        foreach ($result2 as $data2) {
            $sum_of_total_company_qty_no_grade += $data2->quantity;
            if (isset($res_arr2[$data2->company_id][$data2->product_id])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $res_arr2[$data2->company_id][$data2->product_id]['total_company_qty'] += $data2->quantity;
                $res_arr2[$data2->company_id][$data2->product_id]['total_company_total'] += $data2->total;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $res_arr2[$data2->company_id][$data2->product_id]['total_company_qty'] = $data2->quantity;
                $res_arr2[$data2->company_id][$data2->product_id]['total_company_total'] = $data2->total;
            }
        }
        // dd($result);
        $whatsapp_arr = [];
        // dd($result2);
          foreach ($result2 as $key => $value) {
            if(isset($whatsapp_arr[$value->product_id])){
              $whatsapp_arr[$value->product_id]['product_name'] = $value->product_name;
              $whatsapp_arr[$value->product_id]['quantity'] += $value->quantity;
              $whatsapp_arr[$value->product_id]['total'] += $value->total;
            }else{
              $whatsapp_arr[$value->product_id]['product_name'] = $value->product_name;
              $whatsapp_arr[$value->product_id]['quantity'] = $value->quantity;
              $whatsapp_arr[$value->product_id]['total'] = $value->total;
            }
          }
          // dd($whatsapp_arr);
        return ['whatsapp_use' => $whatsapp_arr, 'result' => $res_arr, 'result2' => $res_arr2, 'sum_qty' => $sum_of_total_company_qty, 'sum_qty_2' => $sum_of_total_company_qty_no_grade ];
    }


    public static function get_sales_summary_details_by_product_1($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product.product_name, tbl_setting_product_size.setting_product_size_name,
                        SUM(tbl_invoice_item.invoice_item_quantity) as quantity,
                        SUM(tbl_invoice_item.invoice_item_total) as total,
                        tbl_product.product_id,
                        tbl_setting_product_size.setting_product_size_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_setting_product_size ON tbl_setting_product_size.setting_product_size_id = tbl_invoice_item.setting_product_size_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    WHERE DATE(tbl_invoice.invoice_date) >= DATE(?) AND tbl_company.is_display = 1
                    AND DATE(tbl_invoice.invoice_date) <= DATE(?) AND tbl_invoice.invoice_status_id <> 3';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";
        }

        // if($company_id != 0){
        //     if(auth()->user()->user_type_id != 1){
        //         $query .= " AND tbl_invoice.company_id = {$company_id}";
        //     }
        // }

        $query .= " GROUP BY tbl_product.product_name, tbl_setting_product_size.setting_product_size_name
                    ORDER BY tbl_product.product_ranking ASC, tbl_setting_product_size.setting_product_size_name ASC";
        // dd($query);

        $result = DB::select(DB::raw($query), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);

        $query2 = "SELECT
                    SUM(tbl_invoice.invoice_total_round_up) as round_up,
                    SUM(tbl_invoice.invoice_grandtotal) as grandtotal
                    FROM tbl_invoice
                    -- JOIN tbl_invoice_item ON tbl_invoice_item.invoice_id = tbl_invoice.invoice_id
                    -- JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    where date(invoice_date) >= DATE(?)
                    And date(invoice_date) <= DATE(?)
                    AND invoice_status_id <> 3";

        if (isset($search['company_id'])) {
            $query2 .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // // $query->whereIn('tbl_invoice.company_id', $ids);
                // $query .= " WHERE IN tbl_invoice.company_id = {$ids}";
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query2 .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query2 .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query2 .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query2 .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['product_id'])) {
            $query2 .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if (isset($search['product_size_id'])) {
            $query2 .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";

        }

        $result2 = DB::select(DB::raw($query2), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);

        return ['result' => $result, 'result2' => $result2 ];
    }

    public static function get_product_detail_report($search)
    {
        $query = DB::table('tbl_invoice_item as ii')
            ->selectRaw('SUM(ii.invoice_item_quantity) as quantity,
                        SUM(ii.invoice_item_total) as total_price,
                        cl.company_land_name,
                        cl.company_land_id,
                        p.product_name,
                        sts.setting_product_size_name
                        ')
            ->join('tbl_invoice as i', 'i.invoice_id', '=', 'ii.invoice_id')
            ->join('tbl_company_land as cl', 'cl.company_land_id', '=', 'i.company_land_id')
            ->join('tbl_company as c', 'c.company_id', '=', 'i.company_id')
            ->join('tbl_product as p', 'p.product_id', '=', 'ii.product_id')
            ->join('tbl_setting_product_size as sts', 'sts.setting_product_size_id', '=', 'ii.setting_product_size_id')
            ->where('c.is_display', 1);
            // ->where('ii.setting_product_size_id', $setting_product_size_id)

        if (@$search['sales_from']) {
            $query->whereDate('ii.invoice_item_created', '>=', $search['sales_from']);
        }

        if (@$search['sales_to']) {
            $query->whereDate('ii.invoice_item_created', '<=', $search['sales_to']);
        }

        if (@$search['company_id']) {
            $query->where('i.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('i.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('i.company_id', auth()->user()->company_id);
            } else {
                $query->where('i.company_id', '<>', 1);
            }
        }

        if (@$search['company_land_id']) {
            $query->where('i.company_land_id', $search['company_land_id']);
        }

        if (@$search['product_id']) {
            $query->where('p.product_id', $search['product_id']);
        }

        if (@$search['product_size_id']) {
            $query->where('sts.setting_product_size_id', $search['sts.setting_product_size_id']);
        }

        $result = $query->groupBy('cl.company_land_name', 'p.product_id', 'sts.setting_product_size_id')
                        ->orderBy('cl.company_land_name', 'asc')
                        ->orderBy('p.product_ranking', 'asc')
                        ->orderBy('sts.setting_product_size_id', 'asc')
                        ->get();

        $result->map(function ($q) {
            if (@$q->quantity) {
                $average_price = $q->total_price / $q->quantity;
            } else {
                $average_price = 0;
            }

            $q->average_price = $average_price;
            return $q;
        });

        $array = array();
        foreach ($result as $row) {
            if (isset($array['data'][$row->company_land_name])) {
                array_push($array['data'][$row->company_land_name], $row);
                $array['total'][$row->company_land_name] += $row->total_price;
            } else {
                $array['data'][$row->company_land_name] = [$row];
                $array['company_land_name'][$row->company_land_name] = $row->company_land_name;
                $array['total'][$row->company_land_name] = $row->total_price;
            }
        }
        return (object)$array;
    }

    public static function get_sales_summary_details_by_farm($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_company_farm.company_farm_id,
                    tbl_company_farm.company_farm_name,
                    SUM(tbl_invoice_item.invoice_item_total) as amount,
                    SUM(tbl_invoice_item.invoice_item_quantity) as quantity
                FROM tbl_invoice
                LEFT JOIN tbl_invoice_item ON tbl_invoice_item.invoice_id = tbl_invoice.invoice_id
                LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                LEFT JOIN tbl_company_farm ON tbl_company_farm.company_farm_id = tbl_company_land_category.company_farm_id
                LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                WHERE DATE(tbl_invoice_item.invoice_item_created) >= DATE(?) AND tbl_company.is_display = 1
                AND DATE(tbl_invoice_item.invoice_item_created) <= DATE(?)';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_invoice.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // dd(auth()->user()->user_company->pluck('company_id'));
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND tbl_invoice.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_invoice.company_id', auth()->user()->company_id);
                $query .= " AND tbl_invoice.company_id = {$company_id}";
            } else {
                // $query->where('tbl_invoice.company_id', '<>', 1);
                $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_invoice.company_land_id IN ({$cl_id})";
        }

        if (isset($search['product_id'])) {
            $query .= " AND tbl_invoice_item.product_id = {$search['product_id']}";
        }

        if (isset($search['product_category_id'])) {
            $query .= " AND tbl_product.product_category_id = {$search['product_category_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_invoice_item.product_id IN ({$p_id})";
        }

        if (isset($search['product_size_id'])) {
            $query .= " AND tbl_invoice_item.setting_product_size_id = {$search['product_size_id']}";
        }

        if (isset($search['customer_id'])) {
            $query .= " AND tbl_invoice.customer_id = {$search['customer_id']}";
        }

        if ($company_id != 0) {
            if (auth()->user()->user_type_id != 1) {
                $query .= " AND tbl_invoice.company_id = {$company_id}";
            }
        }

        $query .= " GROUP BY tbl_company_farm.company_farm_name
                    ORDER BY tbl_company_farm.company_farm_name ASC";

        $result = DB::select(DB::raw($query), [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);

        return $result;
    }


    public static function get_sync_collect_details_by_land_user($search)
    {
        $sync_collect_array = array();
        $company_id = auth()->user()->company_id;

        $query = SyncCollect::query();
        $query->selectRaw('tbl_user.user_id,
                            tbl_user_land.company_land_id,
                            DATE(tbl_sync_collect.sync_collect_date) AS sync_collect_date,
                            COUNT(tbl_sync_collect.sync_collect_id) AS sync_collect_count')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_sync_collect.user_id')
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.model_id', 'tbl_user.user_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_user.company_id')
            ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_user_land', function ($join) {
                $join->on('tbl_user_land.user_id', '=', 'tbl_sync_collect.user_id');
                $join->on('tbl_user_land.company_land_id', '=', 'tbl_sync_collect.company_land_id');
            });

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_sync_collect.sync_collect_date', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_user.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_user.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_user.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_user.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_user.company_id', '<>', 1);
            }
        }

        // if (isset($search['product_cb_id'])){
        //     $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
        //  }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_user_land.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->whereIn('tbl_user_land.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['user_id'])) {
            $query->where('tbl_user.user_id', $search['user_id']);
        }

        $query->groupByRaw('tbl_user_land.company_land_id, tbl_user.user_id,  DATE(tbl_sync_collect.sync_collect_date)');

        $result = $query->get();

        foreach ($result as $data) {
            if (isset($sync_collect_array[$data->company_land_id][$data->user_id][date_format($data->sync_collect_date,'Y-m-d')])) {
                $sync_collect_array[$data->company_land_id][$data->user_id][date_format($data->sync_collect_date,'Y-m-d')] += $data->sync_collect_count;
            } else {
                $sync_collect_array[$data->company_land_id][$data->user_id][date_format($data->sync_collect_date,'Y-m-d')] = $data->sync_collect_count;
            }
        }
        return $sync_collect_array;
    }

    public static function get_sync_do_details_by_land_user($search)
    {
        $sync_do_array = array();
        $company_id = auth()->user()->company_id;

        $query = SyncDeliveryOrder::query();
        $query->selectRaw('tbl_user.user_id,
                            tbl_user_land.company_land_id,
                            DATE(tbl_sync_delivery_order.sync_delivery_order_date) AS sync_do_date,
                            COUNT(tbl_sync_delivery_order.sync_delivery_order_id) AS sync_do_count')
            ->join('tbl_sync', 'tbl_sync.sync_id', 'tbl_sync_delivery_order.sync_id')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_sync.user_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_user.company_id')
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.model_id', 'tbl_user.user_id')
            ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_user_land', function ($join) {
                $join->on('tbl_user_land.user_id', '=', 'tbl_sync.user_id');
                $join->on('tbl_user_land.company_land_id', '=', 'tbl_sync_delivery_order.company_land_id');
            });

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_sync_delivery_order.sync_delivery_order_date', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_user.company_id', $search['company_id']);
        } elseif (isset($search['company_cb_id'])) {
            $query->whereIn('tbl_user.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_user.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_user.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_user.company_id', '<>', 1);
            }
        }

        // if (isset($search['product_cb_id'])){
        //      $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
        //  }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_user_land.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->whereIn('tbl_user_land.company_land_id', $search['company_land_cb_id']);
        }


        if (isset($search['user_id'])) {
            $query->where('tbl_user.user_id', $search['user_id']);
        }

        if (@$search['customer_id']) {
            $query->where('tbl_sync_delivery_order.customer_id', $search['customer_id']);
        }

        $query->groupByRaw('tbl_user_land.company_land_id, tbl_user.user_id,  DATE(tbl_sync_delivery_order.sync_delivery_order_date)');

        $result = $query->get();

        foreach ($result as $data) {
            if (isset($sync_do_array[$data->company_land_id][$data->user_id][$data->sync_do_date])) {
                $sync_do_array[$data->company_land_id][$data->user_id][$data->sync_do_date] += $data->sync_do_count;
            } else {
                $sync_do_array[$data->company_land_id][$data->user_id][$data->sync_do_date] = $data->sync_do_count;
            }
        }

        return $sync_do_array;
    }

    public static function get_collect_details_by_land_user($search)
    {
        $collect_array = array();
        $company_id = auth()->user()->company_id;


        $query = Collect::query();
        $query->selectRaw('tbl_user_land.company_land_id,
                            tbl_user.user_id,
                            COUNT( tbl_collect.collect_id ) AS collect_count,
                            ifnull( SUM( tbl_collect.collect_quantity ), 0 ) AS collect_sum,
                            DATE( tbl_collect.collect_created ) AS collect_date')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_collect.user_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_user.company_id')
            ->join('tbl_customer', 'tbl_customer.company_id', 'tbl_company.company_id')
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.model_id', 'tbl_user.user_id')
            ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_user_land', function ($join) {
                $join->on('tbl_user_land.user_id', '=', 'tbl_collect.user_id');
                $join->on('tbl_user_land.company_land_id', '=', 'tbl_collect.company_land_id');
            });

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_collect.collect_status', '<>', 'deleted');
        $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_collect.collect_created', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_user.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_user.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_user.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_user.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_user.company_id', '<>', 1);
            }
        }

        // if (isset($search['product_cb_id'])){
        //      $query->whereIn('tbl_product.product_id', $search['product_cb_id']);
        //  }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_user_land.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->whereIn('tbl_user_land.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['user_id'])) {
            $query->where('tbl_user.user_id', $search['user_id']);
        }

        // if (isset($search['customer_id'])) {
        //     $query->where('tbl_customer.customer_id', $search['customer_id']);
        // }

        $query->groupByRaw('tbl_user_land.company_land_id, tbl_user.user_id, DATE(tbl_collect.collect_created)');

        $result = $query->get();

        foreach ($result as $data) {
            if (isset($collect_array[$data->company_land_id][$data->user_id][$data->collect_date])) {
                $collect_array[$data->company_land_id][$data->user_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $collect_array[$data->company_land_id][$data->user_id][$data->collect_date]['collect_sum'] += $data->collect_sum;
            } else {
                $collect_array[$data->company_land_id][$data->user_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $collect_array[$data->company_land_id][$data->user_id][$data->collect_date]['collect_sum'] = $data->collect_sum;
            }
        }

        return $collect_array;
    }

    public static function get_collect_details_by_land_product($search)
    {
        $collect_array = array();
        $company_id = auth()->user()->company_id;


        $query = Collect::query();
        $query->selectRaw('tbl_product.product_id,
                            tbl_setting_product_size.setting_product_size_id,
                            tbl_company_land.company_land_id,
                            COUNT( tbl_collect.collect_id ) AS collect_count,
                            ifnull( SUM( tbl_collect.collect_quantity ), 0 ) AS collect_sum,
                            DATE( tbl_collect.collect_date ) AS collect_date')
            ->join('tbl_product', 'tbl_product.product_id', 'tbl_collect.product_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_collect.company_id')
            ->join('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', 'tbl_collect.setting_product_size_id')
            // ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_company_land', function ($join) {
                $join->on('tbl_company_land.company_land_id', '=', 'tbl_collect.company_land_id');
                // $join->on('tbl_user_land.company_land_id', '=', 'tbl_collect.company_land_id');
            });

        $delivery_order_item = DB::table('tbl_delivery_order_item as doi')
            ->selectRaw(
                'p.product_id,
                sps.setting_product_size_id,
                cl.company_land_id,
                COUNT( doi.delivery_order_item_id) AS doi_count,
                ifnull( SUM( doi.delivery_order_item_quantity), 0 ) AS sum_doi_quantity,
                DATE( do.delivery_order_created) AS doi_date'
            )
            ->join('tbl_delivery_order as do', 'do.delivery_order_id', 'doi.delivery_order_id')
            ->join('tbl_company_land as cl', 'cl.company_land_id', 'do.company_land_id')
            ->join('tbl_product as p', 'p.product_id', 'doi.product_id')
            ->join('tbl_company as c', 'c.company_id', 'do.company_id')
            ->join('tbl_setting_product_size as sps', 'sps.setting_product_size_id', 'doi.setting_product_size_id')
            ->where('c.is_display', 1)
            ->where('doi.no_collect_code', 1)
            ->whereBetween('do.delivery_order_created', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_collect.collect_status', '<>', 'deleted');
        // $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_collect.collect_date', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_collect.company_id', $search['company_id']);
            $delivery_order_item->where('c.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_collect.company_id', $search['company_cb_id']);
            $delivery_order_item->where('c.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_collect.company_id', $ids);
                $delivery_order_item->whereIn('c.company_id', $ids);
            } elseif (auth()->user()->company_id != 0) {
                $query->where('tbl_collect.company_id', auth()->user()->company_id);
                $delivery_order_item->where('c.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_collect.company_id', '<>', 1);
                $delivery_order_item->where('c.company_id', '<>', 1);
            }
        }

        if (isset($search['product_cb_id'])) {
            $query->whereIn('tbl_collect.product_id', $search['product_cb_id']);
            $delivery_order_item->where('doi.product_id', $search['product_cb_id']);
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_collect.company_land_id', $search['company_land_id']);
            $delivery_order_item->where('do.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->whereIn('tbl_collect.company_land_id', $search['company_land_cb_id']);
            $delivery_order_item->where('do.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['user_id'])) {
            $query->where('tbl_collect.user_id', $search['user_id']);
            $delivery_order_item->where('do.user_id', $search['user_id']);
        }

        if (isset($search['customer_id'])) {
            $delivery_order_item->where('do.customer_id', $search['customer_id']);
        }

        $query->groupByRaw('tbl_collect.product_id, tbl_collect.setting_product_size_id, DATE(tbl_collect.collect_date)');
        $delivery_order_item->groupByRaw('doi.product_id, doi.setting_product_size_id, DATE(do.delivery_order_created)');
        $query->orderBy('tbl_collect.product_id', 'ASC');
        $result = $query->get();
        $doi = $delivery_order_item->get();

        foreach ($result as $data) {
            if (isset($collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->collect_date])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->collect_date]['collect_sum'] += $data->collect_sum;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->collect_date]['collect_sum'] = $data->collect_sum;
            }
        }

        foreach ($doi as $data) {
            if (isset($collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->doi_date])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->doi_date]['collect_count'] += $data->doi_count;
                $collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->doi_date]['collect_sum'] += $data->sum_doi_quantity;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->doi_date]['collect_count'] = $data->doi_count;
                $collect_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->doi_date]['collect_sum'] = $data->sum_doi_quantity;
            }
        }
        return $collect_array;
    }

    public static function get_do_details_by_land_user($search)
    {
        $do_array = array();
        $company_id = auth()->user()->company_id;


        $query = DeliveryOrder::query();
        $query->selectRaw('tbl_user.user_id,
                            tbl_user_land.company_land_id,
                            DATE(tbl_delivery_order.delivery_order_created) AS do_date,
                            COUNT(tbl_delivery_order.delivery_order_id) AS do_count,
                            ifnull( SUM( tbl_delivery_order.delivery_order_total_quantity ), 0 ) AS do_sum')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_delivery_order.user_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_delivery_order.company_id')
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.model_id', 'tbl_user.user_id')
            ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_user_land', function ($join) {
                $join->on('tbl_user_land.user_id', '=', 'tbl_delivery_order.user_id');
                $join->on('tbl_user_land.company_land_id', '=', 'tbl_delivery_order.company_land_id');
            });

        $delivery_order_item = DB::table('tbl_delivery_order_item as i')
        ->selectRaw('p.product_id')
        ->join('tbl_product as p', 'p.product_id', 'i.product_id');

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_delivery_order.delivery_order_status_id', '<>', 3);
        $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_delivery_order.delivery_order_created', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_delivery_order.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_delivery_order.company_id', $search['company_cb_id']);
        } else {
            if ($company_id != 0) {
                if (auth()->user()->user_type_id != 1) {
                    $query->where('tbl_delivery_order.company_id', $company_id);
                }
            }
        }

        // if (isset($search['product_cb_id'])) {
        //    $query->whereIn('i.product_id', $search['product_cb_id']);
        //    $delivery_order_item->where('i.product_id', $search['product_cb_id']);
        // }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_delivery_order.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->where('tbl_delivery_order.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['user_id'])) {
            $query->where('tbl_delivery_order.user_id', $search['user_id']);
        }

        // if (isset($search['customer_id'])) {
        //     $query->where('tbl_delivery_order.customer_id', $search['customer_id']);
        // }

        $query->groupByRaw('tbl_user_land.company_land_id, tbl_user.user_id, DATE(tbl_delivery_order.delivery_order_created)');

        $result = $query->get();

        foreach ($result as $data) {
            if (isset($do_array[$data->company_land_id][$data->user_id][$data->do_date])) {
                $do_array[$data->company_land_id][$data->user_id][$data->do_date]['do_count'] += $data->do_count;
                $do_array[$data->company_land_id][$data->user_id][$data->do_date]['do_sum'] += $data->do_sum;
            } else {
                $do_array[$data->company_land_id][$data->user_id][$data->do_date]['do_count'] = $data->do_count;
                $do_array[$data->company_land_id][$data->user_id][$data->do_date]['do_sum'] = $data->do_sum;
            }
        }

        return $do_array;
    }

    public static function get_do_details_by_land_product($search)
    {
        $do_array = array();
        $collect_array = array();
        $company_id = auth()->user()->company_id;


        $query = DeliveryOrder::query();
        $query->selectRaw('tbl_product.product_id,
                            tbl_setting_product_size.setting_product_size_id,
                            tbl_company_land.company_land_id,
                            COUNT( tbl_delivery_order.delivery_order_id ) AS do_count,
                            IFNULL (SUM(tbl_delivery_order_item.delivery_order_item_quantity), 0) AS do_item_quan,
                            DATE( tbl_delivery_order.delivery_order_created ) AS do_date')
            ->join('tbl_delivery_order_item', 'tbl_delivery_order_item.delivery_order_id', 'tbl_delivery_order.delivery_order_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_delivery_order.company_id')
            ->join('tbl_product', 'tbl_product.product_id', 'tbl_delivery_order_item.product_id')
            ->join('tbl_setting_product_size', 'tbl_setting_product_size.setting_product_size_id', 'tbl_delivery_order_item.setting_product_size_id')
            // ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
            ->leftJoin('tbl_company_land', function ($join) {
                $join->on('tbl_company_land.company_land_id', '=', 'tbl_delivery_order.company_land_id');
                // $join->on('tbl_user_land.company_land_id', '=', 'tbl_collect.company_land_id');
            });



        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_delivery_order.delivery_order_status_id', '<>', 3);
        // $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereBetween('tbl_delivery_order.delivery_order_created', [$search['start_date'] . " 00:00:00", $search['end_date'] . " 23:59:59"]);

        if (isset($search['company_id'])) {
            $query->where('tbl_delivery_order.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_delivery_order.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_delivery_order.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_delivery_order.company_id', '<>', 1);
            }
        }

        if (isset($search['product_cb_id'])) {
            $query->whereIn('tbl_delivery_order_item.product_id', $search['product_cb_id']);
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_delivery_order.company_land_id', $search['company_land_id']);
        }

        if (isset($search['user_id'])) {
            $query->where('tbl_delivery_order.user_id', $search['user_id']);
        }

        if (isset($search['customer_id'])) {
            $query->where('tbl_delivery_order.customer_id', $search['customer_id']);
        }

        $query->groupByRaw('tbl_delivery_order_item.product_id, tbl_delivery_order_item.setting_product_size_id, DATE(tbl_delivery_order.delivery_order_created)');
        $query->orderBy('tbl_delivery_order_item.product_id', 'ASC');
        $result = $query->get();
        // dd($result);
        foreach ($result as $data) {
            if (isset($do_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->do_date])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $do_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->do_date]['do_item_quan'] += $data->do_item_quan;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $do_array[$data->company_land_id][$data->product_id][$data->setting_product_size_id][$data->do_date]['do_item_quan'] = $data->do_item_quan;
            }
        }
        // dd($do_array);
        return $do_array;
    }

    public static function get_land_product_size($company_id)
    {
        $result = CompanyLand::query()->where('company_id', $company_id)->get();

        foreach ($result as $row) {
            $row->setAttribute('products', ProductCompanyLand::get_products_by_company_land_id($row->company_land_id));
        }

        return response()->json(['data' => $result]);
    }

    public static function differentiate_detail($search)
    {
        $company_id = auth()->user()->company_id;
        $result = [];
        $doq = "SELECT sum(do.delivery_order_total_quantity) as total_order,
        cf.company_farm_name as farm_name, cf.company_farm_id,
        clc.company_land_category_name as category_name, clc.company_land_category_id,
        p.product_name, doi.product_id,
        ps.setting_product_size_name, doi.setting_product_size_id, day(do.delivery_order_created) as dates
        from tbl_delivery_order as do
        left join (select delivery_order_id, max(product_id) as product_id, max(setting_product_size_id) as setting_product_size_id
        from tbl_delivery_order_item group by delivery_order_id)
        as doi on doi.delivery_order_id = do.delivery_order_id
        left join tbl_company_land as cl on cl.company_land_id = do.company_land_id
        left join tbl_company_land_category as clc on clc.company_land_category_id = cl.company_land_category_id
        left join (select company_farm_id, company_farm_name from tbl_company_farm) as cf on cf.company_farm_id = clc.company_farm_id
        left join tbl_product as p on p.product_id = doi.product_id
        left join tbl_setting_product_size as ps on ps.setting_product_size_id = doi.setting_product_size_id
        WHERE do.delivery_order_status_id <> 3";
        if (@$search['month_year']) {
            $month = substr($search['month_year'], 0, 2);
            $year = substr($search['month_year'], 3, 6);
            $doq .= " where month(do.delivery_order_created) = " . $month . " and year(do.delivery_order_created) = " . $year . " and do.delivery_order_status_id != 3 ";
        }
        // if (@$search['start_date']){
        //   $doq .= " where date(do.delivery_order_created) >= " . @$search['start_date'];
        // }
        // if (@$search['end_date']){
        //   $doq .= " and date(do.delivery_order_created) <= " . @$search['end_date'];
        // }
        if (isset($search['company_id'])) {
            $doq .= " AND do.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('do.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND do.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('do.company_id', auth()->user()->company_id);
                $query .= " AND do.company_id = {$company_id}";
            } else {
                // $query->where('do.company_id', '<>', 1);
                $query .= " AND do.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $doq .= " AND do.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['user_id'])) {
            $doq .= " AND do.user_id = {$search['user_id']}";
        }

        $doq .= " group by doi.setting_product_size_id, doi.product_id, date(do.delivery_order_created)
        order by p.product_id asc";

        $do = DB::select($doq);
        $result_do = [];
        foreach ($do as $dk => $d) {
            if ($d->product_id != null && $d->setting_product_size_id != null) {
                // if(isset($result_do[$d->dates][$d->company_farm_id][$d->company_land_category_id][$d->product_id][$d->setting_product_size_id])){
                //       $result_do[$d->dates][$d->company_farm_id][$d->company_land_category_id][$d->product_id][$d->setting_product_size_id]+=$d;
                //   }else{
                //       $result_do[$d->dates][$d->company_farm_id][$d->company_land_category_id][$d->product_id][$d->setting_product_size_id]=$d;
                //   }
                $result_do[$d->dates . '-' . $d->company_farm_id . '-' . $d->company_land_category_id . '-' . $d->product_id . '-' . $d->setting_product_size_id] = $d;
            }
        }

        $colq = "SELECT sum(co.collect_quantity) as total_collect,
        cf.company_farm_name as farm_name, cf.company_farm_id,
        clc.company_land_category_name as category_name, clc.company_land_category_id,
        p.product_name, co.product_id,
        ps.setting_product_size_name, co.setting_product_size_id, day(co.collect_created) as dates
        from tbl_collect as co
        left join tbl_company_land as cl on cl.company_land_id = co.company_land_id
        left join tbl_company_land_category as clc on clc.company_land_category_id = cl.company_land_category_id
        left join tbl_company_farm as cf on cf.company_farm_id = clc.company_farm_id
        left join tbl_product as p on p.product_id = co.product_id
        left join tbl_setting_product_size as ps on ps.setting_product_size_id = co.setting_product_size_id
        WHERE co.collect_status <> 'deleted' ";

        if (@$search['month_year']) {
            $month = substr($search['month_year'], 0, 2);
            $year = substr($search['month_year'], 3, 6);
            $colq .= " where month(co.collect_created) = " . $month . " and year(co.collect_created) = " . $year . " and co.collect_status != 'deleted' ";
        }
        // if (@$search['start_date']){
        //   $colq .= " where date(co.collect_created) >= " . @$search['start_date'];
        // }
        // if (@$search['end_date']){
        //   $colq .= " and date(co.collect_created) <= " . @$search['end_date'];
        // }
        if (isset($search['company_id'])) {
            $colq .= " AND co.company_id = {$search['company_id']}";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('co.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND co.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('co.company_id', auth()->user()->company_id);
                $query .= " AND co.company_id = {$company_id}";
            } else {
                // $query->where('co.company_id', '<>', 1);
                $query .= " AND co.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $colq .= " AND co.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['user_id'])) {
            $colq .= " AND co.user_id = {$search['user_id']}";
        }
        $colq .= " group by co.setting_product_size_id, co.product_id, date(co.collect_created)
        order by co.product_id asc";

        $col = DB::select($colq);
        $result_co = [];
        foreach ($col as $ck => $c) {
            if ($c->product_id != null && $c->setting_product_size_id != null) {
                // if(isset($result_co[$c->dates][$d->company_farm_id][$d->company_land_category_id][$c->product_id][$c->setting_product_size_id])){
                //       $result_co[$c->dates][$d->company_farm_id][$d->company_land_category_id][$c->product_id][$c->setting_product_size_id]+=$c;
                //   }else{
                //       $result_co[$c->dates][$d->company_farm_id][$d->company_land_category_id][$c->product_id][$c->setting_product_size_id]=$c;
                //   }
                $result_co[$c->dates . '-' . $d->company_farm_id . '-' . $d->company_land_category_id . '-' . $c->product_id . '-' . $c->setting_product_size_id] = $c;
            }
        }

        return ['do' => $result_do, 'co' => $result_co];
    }

    public static function get_detailed_farm_sales_summary_details($search, $company_farm_id)
    {
        $array = array();

        $query = InvoiceItem::query();
        $query->selectRaw('tbl_company_land.company_land_name,
                            tbl_invoice.company_id,
                            ifnull(SUM(tbl_invoice_item.invoice_item_quantity), 0) as quantity,
                            ifnull(SUM(tbl_invoice_item.invoice_item_total), 0) as amount')
            ->join('tbl_invoice', 'tbl_invoice.invoice_id', 'tbl_invoice_item.invoice_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_invoice.company_id')
            ->join('tbl_company_land', 'tbl_company_land.company_land_id', 'tbl_invoice.company_land_id')
            ->join('tbl_company_land_category', 'tbl_company_land_category.company_land_category_id', 'tbl_company_land.company_land_category_id')
            ->join('tbl_company_farm', 'tbl_company_farm.company_farm_id', 'tbl_company_land_category.company_farm_id');

        if (isset($search['company_id'])) {
            $query->where('tbl_invoice.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_invoice.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_invoice.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_invoice.company_id', '<>', 1);
            }
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_invoice.company_land_id', $search['company_land_id']);
        }

        if (isset($search['product_id'])) {
            $query->where('tbl_invoice_item.product_id', $search['product_id']);
        }

        if (isset($search['product_size_id'])) {
            $query->where('tbl_invoice_item.setting_product_size_id', $search['product_size_id']);
        }

        $query->where('tbl_company.is_display', 1);
        $query->where('tbl_company_farm.company_farm_id', $company_farm_id);
        $query->whereBetween('tbl_invoice_item.invoice_item_created', [$search['sales_from'] . " 00:00:00", $search['sales_to'] . " 23:59:59"]);
        $query->groupByRaw('tbl_company_land.company_land_name, tbl_invoice.company_id');

        $result = $query->get();

        if ($result) {
            foreach ($result as $data) {
                if (isset($array[$data->company_land_name][$data->company_id])) {
                    $array[$data->company_land_name][$data->company_id] += ['quantity' => $data->quantity, 'amount' => $data->amount];
                } else {
                    $array[$data->company_land_name][$data->company_id] = ['quantity' => $data->quantity, 'amount' => $data->amount];
                }
            }
        }

        return $array;
    }

    public static function get_month()
    {
        return [
            '1' => 'January',
            '2' => 'Febuary',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }

    public static function get_month_w_filter($search)
    {
        $month_arr = [
            '1' => 'January',
            '2' => 'Febuary',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        if(isset($search['month'])){
          $search_month = [];
          foreach ($month_arr as $month_num => $month_name) {
            if($month_num == $search['month']){
              $search_month[$month_num] = $month_name;
              return $search_month;
            }
          }
        }
        return $month_arr;
    }

    public static function get_do_expense_details($search)
    {
        $expense_array = array();
        // $company_id = auth()->user()->company_id;
        $year = $search['year'];

        $query = DeliveryOrderExpense::query();
        $query->selectRaw('tbl_setting_expense.setting_expense_name,
                            tbl_setting_expense.setting_expense_id,
                            tbl_setting_expense_type.setting_expense_type_id,
                            ifnull( SUM( tbl_delivery_order_expense.delivery_order_expense_total ), 0 ) AS expense_sum,
                            MONTH( tbl_delivery_order_expense.delivery_order_expense_created ) AS expense_month')
            ->join('tbl_setting_expense', 'tbl_setting_expense.setting_expense_id', 'tbl_delivery_order_expense.setting_expense_id')
            ->join('tbl_setting_expense_type', 'tbl_setting_expense_type.setting_expense_type_id', 'tbl_setting_expense.setting_expense_type_id')
            ->join('tbl_delivery_order', 'tbl_delivery_order.delivery_order_id', 'tbl_delivery_order_expense.delivery_order_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_delivery_order.company_id');
        // ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
        // ->leftJoin('tbl_company_land', function($join){
        //     $join->on('tbl_company_land.company_land_id', '=', 'tbl_collect.company_land_id');
        // $join->on('tbl_user_land.company_land_id', '=', 'tbl_collect.company_land_id');
        // });

        // $query->where('tbl_user_role.name', 'Farm Manager');
        $query->where('tbl_company.is_display', 1);
        $query->whereYear('tbl_delivery_order_expense.delivery_order_expense_created', '=',  $year);

        if (isset($search['company_id'])) {
            $query->where('tbl_delivery_order.company_id', $search['company_id']);
        } elseif(isset($search['company_cb_id'])) {
            $query->whereIn('tbl_delivery_order.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    // $company->where('company_id', $user_company->company_id);
                    $ids[$key] = $user_company->company_id;
                    // dd($ids[$key]);
                }
                $query->whereIn('tbl_delivery_order.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_delivery_order.company_id', '<>', 1);
            }
            // if($company_id != 0){
            //     if(auth()->user()->user_type_id != 1){
            //         $query->where('tbl_delivery_order.company_id',$company_id);
            //     }
            // }else{
            //     $query->where('tbl_delivery_order.company_id','<>',1);
            // }
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_delivery_order.company_land_id', $search['company_land_id']);
        }

        if (isset($search['company_land_cb_id'])) {
            $query->whereIn('tbl_delivery_order.company_land_id', $search['company_land_cb_id']);
        }

        if (isset($search['warehouse_id'])) {
            $query->where('tbl_delivery_order.company_land_id', $search['warehouse_id']);
        }

        $query->groupByRaw('tbl_delivery_order_expense.setting_expense_id, MONTH(tbl_delivery_order_expense.delivery_order_expense_created)');
        // $query->orderBy('tbl_setting_expense.expense_group', 'ASC');
        // dd($query->toSql());
        $result = $query->get();


        foreach ($result as $data) {
            if (isset($expense_array[$data->setting_expense_name][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $expense_array[$data->setting_expense_name][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month]['expense_sum'] += $data->expense_sum;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $expense_array[$data->setting_expense_name][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month]['expense_sum'] = $data->expense_sum;
            }
        }
        return $expense_array;
    }

    public static function get_do_expense_details_2($search, $month)
    {
        $expense_array = array();
        // $company_id = auth()->user()->company_id;
        $year = $search['year'];

        $query = DeliveryOrderExpense::query();
        $query->selectRaw('tbl_delivery_order.company_id,
                          tbl_delivery_order.warehouse_id,
                            tbl_delivery_order_expense.setting_expense_id,
                            tbl_setting_expense_type.setting_expense_type_id,
                            ifnull( SUM( tbl_delivery_order_expense.delivery_order_expense_total ), 0 ) AS expense_sum,
                            MONTH( tbl_delivery_order_expense.delivery_order_expense_created ) AS expense_month')
            ->join('tbl_setting_expense', 'tbl_setting_expense.setting_expense_id', 'tbl_delivery_order_expense.setting_expense_id')
            ->join('tbl_setting_expense_type', 'tbl_setting_expense_type.setting_expense_type_id', 'tbl_setting_expense.setting_expense_type_id')
            ->join('tbl_delivery_order', 'tbl_delivery_order.delivery_order_id', 'tbl_delivery_order_expense.delivery_order_id')
            ->join('tbl_company', 'tbl_company.company_id', 'tbl_delivery_order.company_id');
        // ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
        // ->leftJoin('tbl_company_land', function($join){
        //     $join->on('tbl_company_land.company_land_id', '=', 'tbl_collect.company_land_id');
        // $join->on('tbl_user_land.company_land_id', '=', 'tbl_collect.company_land_id');
        // });

        // $query->where('tbl_user_role.name', 'Farm Manager');
        $query->whereYear('tbl_delivery_order_expense.delivery_order_expense_created', '=',  $year);

        // if (isset($search['company_id'])) {
        //     $query->where('tbl_delivery_order.company_id', $search['company_id']);
        // }else{
        //     if($company_id != 0){
        //         if(auth()->user()->user_type_id != 1){
        //             $query->where('tbl_delivery_order.company_id',$company_id);
        //         }
        //     }
        // }
        //
        // if (isset($search['company_land_id'])) {
        //     $query->where('tbl_delivery_order.company_land_id', $search['company_land_id']);
        // }
        //
        // if (isset($search['warehouse_id'])) {
        //     $query->where('tbl_delivery_order.company_land_id', $search['warehouse_id']);
        // }
        $query->whereMonth('tbl_delivery_order_expense.delivery_order_expense_created', '=',  $month);
        // $query->groupByRaw('tbl_setting_expense.setting_expense_group, tbl_delivery_order_expense.setting_expense_id, DATE(tbl_delivery_order_expense.delivery_order_expense_created)');
        // $query->orderBy('tbl_setting_expense.setting_expense_group', 'ASC');

        $query->groupByRaw('tbl_setting_expense.setting_expense_category_id, tbl_delivery_order_expense.setting_expense_id, DATE(tbl_delivery_order_expense.delivery_order_expense_created)');
        $query->orderBy('tbl_setting_expense.setting_expense_category_id', 'ASC');
        $result = $query->get();
        foreach ($result as $data) {
            if (isset($expense_array[$data->company_id][$data->warehouse_id][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month])) {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] += $data->collect_count;
                $expense_array[$data->company_id][$data->warehouse_id][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month]['expense_sum'] += $data->expense_sum;
            } else {
                // $collect_array[$data->company_land_id][$data->product_id][$data->collect_date]['collect_count'] = $data->collect_count;
                $expense_array[$data->company_id][$data->warehouse_id][$data->setting_expense_id][$data->setting_expense_type_id][$data->expense_month]['expense_sum'] = $data->expense_sum;
            }
        }
        // dd($expense_array);
        return $expense_array;
    }

    public static function get_invoice_daily_report($search)
    {
      // dd($search);
        $query = DB::table('tbl_invoice_item as ii')
            ->selectRaw('SUM(ii.invoice_item_quantity) as quantity,
                        SUM(ii.invoice_item_total) as total_price,
                        CONCAT(p.product_name, " - ", sps.setting_product_size_name) as product,
                        p.product_id,
                        sps.setting_product_size_id,
                        i.user_id,
                        DATE(i.invoice_date) as date')
            ->join('tbl_invoice as i', 'i.invoice_id', '=', 'ii.invoice_id')
            ->join('tbl_company_land as cl', 'cl.company_land_id', '=', 'i.company_land_id')
            ->join('tbl_company as c', 'c.company_id', '=', 'i.company_id')
            ->join('tbl_setting_product_size as sps', 'sps.setting_product_size_id', '=', 'ii.setting_product_size_id')
            ->join('tbl_product as p', 'p.product_id', '=', 'ii.product_id')
            ->join('tbl_user as u', 'u.user_id', '=', 'i.user_id')
            ->where('c.is_display', 1)
            ->where('i.invoice_status_id', '<>', 3)
            ->groupBy('date', 'product');

        if (@$search['sales_from']) {
            $query->whereDate('i.invoice_date', '>=', $search['sales_from']);
        }

        if (@$search['sales_to']) {
            $query->whereDate('i.invoice_date', '<=', $search['sales_to']);
        }

        if (@$search['company_id']) {
            $query->where('i.company_id', $search['company_id']);
        } elseif(@$search['company_cb_id']) {
            $query->whereIn('i.company_id', $search['company_cb_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('i.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('i.company_id', auth()->user()->company_id);
            } else {
                $query->where('i.company_id', '<>', 1);
            }
        }


        if (@$search['company_land_id']) {
            $query->where('i.company_land_id', $search['company_land_id']);
        }

        if (@$search['company_land_cb_id']) {
            $query->whereIn('i.company_land_id', $search['company_land_cb_id']);
        }

        if(@$search['product_cb_id']){
            $query->whereIn('ii.product_id', $search['product_cb_id']);
        }
        if (isset($search['customer_id'])) {
            $query->where('i.customer_id', $search['customer_id']);
        }

        if(@$search['user_id']){
            $query->where('i.user_id', $search['user_id']);
        }
        // if(@$search['product_id']){
        //     $query->where('p.product_id', $search['p.product_id']);
        // }

        // if(@$search['product_size_id']){
        //     $query->where('sts.setting_product_size_id', $search['sts.setting_product_size_id']);
        // }

        $result = $query->orderBy('date', 'asc')
            ->orderBy('p.product_ranking', 'asc')
            ->get()->groupBy(['date', 'product'])->toArray();

        $query2 = DB::table('tbl_invoice as i')
            ->selectRaw('SUM(i.invoice_total_round_up) as total_round_up,
                        SUM(i.invoice_grandtotal) as invoice_grandtotal,
                        DATE(i.invoice_date) as date')
            ->join('tbl_company_land as cl', 'cl.company_land_id', '=', 'i.company_land_id')
            ->join('tbl_company as c', 'c.company_id', '=', 'i.company_id')
            // ->join('tbl_product as p', 'p.product_id', '=', 'iii.product_id')
            ->join('tbl_user as u', 'u.user_id', '=', 'i.user_id')
            ->where('c.is_display', 1)
            ->where('i.invoice_status_id', '<>', 3)
            ->groupBy('date');

        if (@$search['sales_from']) {
            $query2->whereDate('i.invoice_date', '>=', $search['sales_from']);
        }

        if (@$search['sales_to']) {
            $query2->whereDate('i.invoice_date', '<=', $search['sales_to']);
        }

        if (@$search['company_id']) {
            $query2->where('i.company_id', $search['company_id']);
        }elseif(auth()->user()->user_type_id != 1){
            if(@$search['company_cb_id']) {
                $query2->whereIn('i.company_id', $search['company_cb_id']);
            }else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = array();
                    foreach (auth()->user()->user_company as $key => $user_company) {
                        $ids[$key] = $user_company->company_id;
                    }
                    $query2->whereIn('i.company_id', $ids);
                } else if (auth()->user()->company_id != 0) {
                    $query2->where('i.company_id', auth()->user()->company_id);
                } else {
                    $query2->where('i.company_id', '<>', 1);
                }
            }
        }

        //  if (@$search['product_cb_id']) {
        //        $query2->whereIn('iii.product_id', $search['product_cb_id']);
        //   }

        if (@$search['company_land_id']) {
            $query2->where('i.company_land_id', $search['company_land_id']);
        }

        if (@$search['customer_id']) {
            $query2->where('i.customer_id', $search['customer_id']);
        }

        if(@$search['user_id']){
            $query->where('i.user_id', $search['user_id']);
        }

        $result2 = $query2->orderBy('date', 'asc')
            ->get()->groupBy(['date'])->toArray();

        return ['result' => $result, 'result2' => $result2 ];
    }

    public static function get_average_summary($search){
        $query = 'SELECT AVG(ii.invoice_item_price) as average_price,
                    SUM(ii.invoice_item_quantity) as total_quantity,
                    SUM(ii.invoice_item_total) as total_amount,
                    CONCAT(p.product_name, " - ", sps.setting_product_size_name) as product,
                    p.product_id, p.product_name,
                    sps.setting_product_size_id, sps.setting_product_size_name,
                    DATE(i.invoice_date) as date,
                    cus.customer_id, cus.customer_name, c.company_id, c.company_name
                    FROM tbl_invoice_item as ii
                    JOIN tbl_invoice i ON i.invoice_id=ii.invoice_id
                    JOIN tbl_company_land as cl ON cl.company_land_id = i.company_land_id
                    JOIN tbl_customer as cus ON cus.customer_id = i.customer_id
                    JOIN tbl_company as c ON c.company_id = i.company_id
                    JOIN tbl_setting_product_size sps ON  sps.setting_product_size_id = ii.setting_product_size_id
                    JOIN tbl_product p ON p.product_id=ii.product_id
                    WHERE c.is_display = 1
                    AND i.invoice_status_id != 3';
        if (@$search['company_id']) {
            $query .= " and i.company_id = " . $search['company_id'];
        }

        if (@$search['company_land_id']) {
            $query .= " and i.company_land_id = " . $search['company_land_id'];
        }

        if (@$search['date_from']) {
            $query .= " and DATE(i.invoice_date) >= '" . $search['date_from']."'";
        }

        if (@$search['date_to']) {
            $query .= " and date(i.invoice_date) <= '" . $search['date_to']."'";
        }

        if (@$search['product_id']) {
            $query .= " and p.product_id = '" . $search['product_id']."'";
        }

        if (@$search['product_category_id']) {
            $query .= " and p.product_category_id = '" . $search['product_category_id']."'";
        }

        if (@$search['product_size_id']) {
            $query .= " and sps.setting_product_size_id = '" . $search['product_size_id']."'";
        }

        $query.= " group by date, product, company_name, customer_id";

        $data = DB::select($query);

        $result = [];
        $result['customer_name_list'] = [];
        $result['col_count'] = 0;

        if($data){
            foreach($data as $row){
                $result[$row->date][$row->product_id][$row->setting_product_size_id][$row->company_name][$row->customer_id] = $row;
                $result['customer_name_list'][$row->company_name][$row->customer_id] = $row->customer_name;
            }

            foreach($result['customer_name_list'] as $customer){
                $result['col_count'] += count($customer);
            }
        }

        return $result;
    }

    public static function get_customer_list_by_company_id($company_id){
        $query = DB::table('tbl_invoice_item as ii')
                ->select('cus.customer_id', 'cus.customer_name', 'cus.customer_company_name','c.company_id')
                ->join('tbl_invoice as i', 'i.invoice_id','ii.invoice_id')
                ->join('tbl_customer as cus', 'cus.customer_id','i.customer_id')
                ->join('tbl_company as c', 'c.company_id','i.company_id')
                ->where('c.is_display',1)
                ->where('c.company_id', $company_id)
                ->where('i.invoice_status_id','<>',3);

        $result = $query->groupBy('customer_id','company_id')->orderBy('cus.customer_company_name')->get();
        return $result;
    }

    public static function get_company_expense_report($search)
    {
        $query = CompanyExpense::query()
                ->selectRaw('tbl_setting_expense_category.setting_expense_category_id as setting_category_expense_id,
                            tbl_company.company_id,
                            tbl_company_expense_land.company_land_id,
                            tbl_company_land.company_land_name,
                            ifnull( SUM( tbl_company_expense_land.company_expense_land_total_price ), 0 ) AS sum,
                            company_expense_month,
                            company_expense_year')
                ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_company_expense.setting_expense_category_id')
                ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
                ->join('tbl_company_expense_land', 'tbl_company_expense_land.company_expense_id', 'tbl_company_expense.company_expense_id')
                ->join('tbl_company_land', 'tbl_company_expense_land.company_land_id', 'tbl_company_land.company_land_id')
                ->where('tbl_company.is_display', 1)
                ->where('tbl_company_expense.company_expense_status', '<>', 'deleted');
                // ->where('tbl_company_expense.company_land_id', '<>', 0);

        if (isset($search['year'])) {
            $query->where('tbl_company_expense.company_expense_year', $search['year']);
        }

        if (isset($search['month'])) {
            $query->where('tbl_company_expense.company_expense_month', $search['month']);
        }

        if(isset($search['company_cb_id'])){
            $query->whereIn('tbl_company_expense.company_id', $search['company_cb_id']);
        }elseif (isset($search['company_id'])) {
            $query->where('tbl_company_expense.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_company_expense.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_company_expense.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_company_expense.company_id', '<>', 1);
            }
        }

        if (isset($search['setting_expense_category_id'])) {
            $query->where('tbl_setting_expense_category.setting_expense_category_id', $search['setting_expense_category_id']);
        }

        $query->groupByRaw('tbl_company_expense.setting_expense_category_id, tbl_company_expense.company_id, tbl_company_expense.company_expense_month, tbl_company_expense_land.company_land_id');

        $query->orderBy('tbl_company_expense.setting_expense_category_id', 'ASC');

        // $sql = str_replace(array('?'), array('\'%s\''), $query->toSql());
        // $sql= vsprintf($sql, $query->getBindings());
        // dd($sql);

        $result = $query->get();

        $query2 = CompanyExpense::query()
                ->selectRaw('tbl_setting_expense_category.setting_expense_category_id as setting_category_expense_id,
                            tbl_company.company_id,
                            tbl_company_expense_land.company_land_id,
                            tbl_company_land.company_land_name,
                            ifnull( SUM( tbl_company_expense_item.company_expense_item_total ), 0 ) AS sum,
                            company_expense_month,
                            company_expense_year')
                ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
                ->join('tbl_company_expense_item', 'tbl_company_expense_item.company_expense_id', 'tbl_company_expense.company_expense_id')
                ->join('tbl_company_expense_land', 'tbl_company_expense_land.company_expense_id', 'tbl_company_expense.company_expense_id')
                ->join('tbl_company_land', 'tbl_company_expense_land.company_land_id', 'tbl_company_land.company_land_id')
                ->join('tbl_setting_expense', 'tbl_setting_expense.setting_expense_id', 'tbl_company_expense_item.setting_expense_id')
                ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_setting_expense.setting_expense_category_id')
                ->where('tbl_company.is_display', 1)
                ->where('tbl_company_expense.company_expense_status', '<>', 'deleted')
                ->where('tbl_setting_expense_category.setting_expense_category_group', 'General');

        if (isset($search['year'])) {
            $query2->where('tbl_company_expense.company_expense_year', $search['year']);
        }

        if (isset($search['month'])) {
            $query2->where('tbl_company_expense.company_expense_month', $search['month']);
        }

        if(isset($search['company_cb_id'])){
            $query2->whereIn('tbl_company_expense.company_id', $search['company_cb_id']);
        }elseif (isset($search['company_id'])) {
            $query2->where('tbl_company_expense.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query2->whereIn('tbl_company_expense.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query2->where('tbl_company_expense.company_id', auth()->user()->company_id);
            } else {
                $query2->where('tbl_company_expense.company_id', '<>', 1);
            }
        }

        if (isset($search['setting_expense_category_id'])) {
            $query2->where('tbl_setting_expense_category.setting_expense_category_id', $search['setting_expense_category_id']);
        }

        $query2->groupByRaw('tbl_company_expense.setting_expense_category_id, tbl_company_expense.company_id, tbl_company_expense.company_expense_month, tbl_company_expense_land.company_land_id');

        $result2 = $query2->get();

        $company_expense_array = array();
        $company_land_array = array();
        $company_land = array();
        $company_total_by_category = array();
        $company_land_total = array();
        $category_total = array();
        $company_total = 0;

        $month = Reporting::get_month();
        $companies = Company::get_company();

        foreach ($month as $month_num => $month_name) {
            foreach ($companies as $key => $company){
                foreach ($result as $data){
                    if ($data->company_expense_month == $month_num && $data->company_id == $company->company_id){
                        if (isset($company_land['land'][$month_num][$company->company_id])) {
                            if (is_array($company_land['land'][$month_num][$company->company_id])) {
                                if(!in_array($data->company_land_name, $company_land['land'][$month_num][$company->company_id])){
                                    $company_land['land'][$month_num][$company->company_id][$data->company_land_id] = $data->company_land_name;
                                }
                            } else {
                                $company_land['land'][$month_num][$company->company_id] = [];
                                $company_land['land'][$month_num][$company->company_id][$data->company_land_id] = $data->company_land_name;
                            }
                        } else {
                            $company_land['land'][$month_num][$company->company_id] = [];
                            $company_land['land'][$month_num][$company->company_id][$data->company_land_id] = $data->company_land_name;
                        }
                    } else {
                        if(!isset($company_land['land'][$month_num][$company->company_id])){
                            $company_land['land'][$month_num][$company->company_id] = 0;
                        }
                    }
                }
            }
        }

        foreach ($result as $data) {
            if (isset($company_expense_array[$data->setting_category_expense_id][$data->company_id][$data->company_expense_month][$data->company_land_id]['company_expense_sum'])) {
                $company_expense_array[$data->setting_category_expense_id][$data->company_id][$data->company_expense_month][$data->company_land_id]['company_expense_sum'] += $data->sum;
            } else {
                $company_expense_array[$data->setting_category_expense_id][$data->company_id][$data->company_expense_month][$data->company_land_id]['company_expense_sum'] = $data->sum;
            }
        }

        foreach ($result as $data) {
            if (isset($company_land_array[$data->company_expense_month][$data->company_id][$data->company_land_id]['company_expense_sum'])) {
                $company_land_array[$data->company_expense_month][$data->company_id][$data->company_land_id]['company_expense_sum'] += $data->sum;
            } else {
                $company_land_array[$data->company_expense_month][$data->company_id][$data->company_land_id]['company_expense_sum'] = $data->sum;
            }
        }

        foreach ($result as $data) {
            if (isset($company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'])) {
                $company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'] += $data->sum;
            } else {
                $company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'] = $data->sum;
            }
        }

        foreach ($result as $data) {
            if (isset($company_land_total[$data->company_id][$data->company_land_id]['land_total'])) {
                $company_land_total[$data->company_id][$data->company_land_id]['land_total'] += $data->sum;
            } else {
                $company_land_total[$data->company_id][$data->company_land_id]['land_total'] = $data->sum;
            }
        }

        foreach ($result as $data) {
            if (isset($category_total[$data->setting_category_expense_id]['total'])) {
                $category_total[$data->setting_category_expense_id]['total'] += $data->sum;
            } else {
                $category_total[$data->setting_category_expense_id]['total'] = $data->sum;
            }
        }

        foreach ($result as $data) {
            if (isset($company_total)) {
                $company_total += $data->sum;
            } else {
                $company_total = $data->sum;
            }
        }

        foreach ($result2 as $data) {
          if (isset($company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'])) {
              $company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'] += $data->sum;
          } else {
              $company_total_by_category[$data->company_id][$data->company_land_id][$data->setting_category_expense_id]['total_by_category'] = $data->sum;
          }

          if (isset($category_total[$data->setting_category_expense_id]['total'])) {
              $category_total[$data->setting_category_expense_id]['total'] += $data->sum;
          } else {
              $category_total[$data->setting_category_expense_id]['total'] = $data->sum;
          }
        }

        return ['result' => $result, 'company_expense_array' => $company_expense_array, 'company_land_array' => $company_land_array, 'company_land' => $company_land, 'company_total_by_category' => $company_total_by_category, 'category_total' => $category_total, 'company_land_total' => $company_land_total, 'company_total' => $company_total];
    }

    public static function get_company_expense_report_land_product($company_land_id, $setting_expense_category_id, $year, $month_num)
    {

        $query = "SELECT tbl_product.product_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_company_land.company_land_id,
            tbl_company.company_id,
            ifnull( SUM( tbl_company_expense_land_product.company_expense_land_product_total_cost ), 0 ) AS total_cost
            FROM tbl_company_expense_land_product
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_land_product.company_expense_id
            JOIN tbl_product ON tbl_product.product_id = tbl_company_expense_land_product.product_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_land_product.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense_land_product.company_land_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_land.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense_land_product.company_land_id = $company_land_id ";
        $query .= " AND tbl_company_expense.setting_expense_category_id = $setting_expense_category_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";
        $query .= " AND tbl_company_expense.company_expense_month = $month_num ";

        $query .= " GROUP BY tbl_product.product_id, tbl_setting_expense.setting_expense_id, tbl_setting_expense_category.setting_expense_category_id, tbl_company_land.company_land_id, tbl_company.company_id";

        $result = DB::select($query);

        $query2 = "SELECT tbl_product.product_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_company_land.company_land_id,
            tbl_company.company_id,
            ifnull( SUM( tbl_company_expense_land_product.company_expense_land_product_total_cost ), 0 ) AS total_cost
            FROM tbl_company_expense_land_product
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_land_product.company_expense_id
            JOIN tbl_product ON tbl_product.product_id = tbl_company_expense_land_product.product_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_land_product.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_setting_expense.setting_expense_category_id
            JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense_land_product.company_land_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_land.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'
            AND tbl_setting_expense_category.setting_expense_category_group = 'General'";

        $query2 .= " AND tbl_company_expense_land_product.company_land_id = $company_land_id ";
        $query2 .= " AND tbl_setting_expense_category.setting_expense_category_id = $setting_expense_category_id ";
        $query2 .= " AND tbl_company_expense.company_expense_year = $year ";
        $query2 .= " AND tbl_company_expense.company_expense_month = $month_num ";

        $query2 .= " GROUP BY tbl_product.product_id, tbl_setting_expense.setting_expense_id, tbl_setting_expense_category.setting_expense_category_id, tbl_company_land.company_land_id, tbl_company.company_id";

        $result2 = DB::select($query2);

        $expense_product = [];
        $total_expense_by_product = [];
        $total_expense_by_setting_expense = [];
        foreach ($result as $data)
        {
            if (isset($expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id])) {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] += $data->total_cost;
            } else {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id])) {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id])) {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost;
            }
        }

        foreach ($result2 as $data)
        {
            if (isset($expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id])) {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] += $data->total_cost;
            } else {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id])) {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id])) {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost;
            }
        }
        return ['expense_product' => $expense_product, 'total_expense_by_product' => $total_expense_by_product, 'total_expense_by_setting_expense' => $total_expense_by_setting_expense];
    }

    public static function get_company_expense_report_land_product_total($company_land_id, $setting_expense_category_id, $year)
    {


        $query = "SELECT tbl_product.product_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_company_land.company_land_id,
            tbl_company.company_id,
            ifnull( SUM( tbl_company_expense_land_product.company_expense_land_product_total_cost ), 0 ) AS total_cost
            FROM tbl_company_expense_land_product
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_land_product.company_expense_id
            JOIN tbl_product ON tbl_product.product_id = tbl_company_expense_land_product.product_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_land_product.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense_land_product.company_land_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_land.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense_land_product.company_land_id = $company_land_id ";
        $query .= " AND tbl_company_expense.setting_expense_category_id = $setting_expense_category_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";

        $query .= " GROUP BY tbl_product.product_id, tbl_setting_expense.setting_expense_id, tbl_setting_expense_category.setting_expense_category_id, tbl_company_land.company_land_id, tbl_company.company_id";

        $result = DB::select($query);

        $query2 = "SELECT tbl_product.product_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_company_land.company_land_id,
            tbl_company.company_id,
            ifnull( SUM( tbl_company_expense_land_product.company_expense_land_product_total_cost ), 0 ) AS total_cost
            FROM tbl_company_expense_land_product
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_land_product.company_expense_id
            JOIN tbl_product ON tbl_product.product_id = tbl_company_expense_land_product.product_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_land_product.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_setting_expense.setting_expense_category_id
            JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense_land_product.company_land_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_land.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'
            AND tbl_setting_expense_category.setting_expense_category_group = 'General'";

        $query2 .= " AND tbl_company_expense_land_product.company_land_id = $company_land_id ";
        $query2 .= " AND tbl_setting_expense_category.setting_expense_category_id = $setting_expense_category_id ";
        $query2 .= " AND tbl_company_expense.company_expense_year = $year ";

        $query2 .= " GROUP BY tbl_product.product_id, tbl_setting_expense.setting_expense_id, tbl_setting_expense_category.setting_expense_category_id, tbl_company_land.company_land_id, tbl_company.company_id";

        $result2 = DB::select($query2);

        $expense_product = [];
        $total_expense_by_product = [];
        $total_expense_by_setting_expense = [];
        foreach ($result as $data)
        {
            if (isset($expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id])) {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] += $data->total_cost;
            } else {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id])) {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id])) {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost;
            }
        }
        foreach ($result2 as $data)
        {
            if (isset($expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id])) {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] += $data->total_cost;
            } else {
                $expense_product[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id])) {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_land_id][$data->setting_expense_category_id][$data->product_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id])) {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_land_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost;
            }
        }
        return ['expense_product' => $expense_product, 'total_expense_by_product' => $total_expense_by_product, 'total_expense_by_setting_expense' => $total_expense_by_setting_expense];
    }

    public static function get_company_expense_detail_report($search, $month, $setting_expense_category_id)
    {

        $company_expense_detail_array = array();
        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_company.company_id,
            tbl_company_land.company_land_id,
            ifnull(sum(tbl_company_expense_item.company_expense_item_total), 0) as total,
            company_expense_month
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            join tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense.company_land_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company.is_display = 1
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

        // dd(($search['company_id']));
        // if (isset($search['company_id'])) {
        //     $query .= " AND tbl_company.company_id = {$search['company_id']}";
        // } else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
        //         $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
        //         $query .= " AND tbl_company.company_id IN ({$ids})";
        //     } else if (auth()->user()->company_id != 0) {
        //         $query .= " AND tbl_company.company_id = {$company_id}";
        //     } else {
        //         $query .= " AND tbl_company.company_id <> 1";
        //     }
        // }

        // dd(@$search['setting_expense_category_id']);
        // if (@$search['setting_expense_category_id']) {
        //     $query .= " and tbl_setting_expense_category.setting_expense_category_id = " . $search['setting_expense_category_id'];
        // }

        $query .= " AND tbl_company_expense.setting_expense_category_id = $setting_expense_category_id ";
        $query .= " AND company_expense_month = $month ";

        $query .= " GROUP BY tbl_company_expense.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company_expense.company_id, tbl_company_expense.company_land_id,company_expense_month
                   ORDER BY tbl_company_expense.company_id";


        $result = DB::select($query);

        foreach ($result as $data) {
            if (isset($company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_id][$data->company_land_id][$data->company_expense_month])) {
                $company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_id][$data->company_land_id][$data->company_expense_month] += $data->total;
            } else {
                $company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_id][$data->company_land_id][$data->company_expense_month] = $data->total;
            }
        }

        return($company_expense_detail_array);

    }

    public static function company_expense_reporting($search)
    {
        $company_expense = array();

        $query = CompanyExpense::query()
        ->selectRaw('tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                    tbl_company_land.company_land_id,
                    ifnull( SUM( tbl_company_expense.company_expense_total ), 0 ) AS company_expense_sum,
                    company_expense_month')
        ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_company_expense.setting_expense_category_id')
        ->join('tbl_company_land', 'tbl_company_land.company_land_id', 'tbl_company_expense.company_land_id')
        ->where('tbl_company_expense.company_expense_status', '<>', 'deleted');

        if (isset($search['company_id'])) {
            $query->where('tbl_company_expense.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_company_expense.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_company_expense.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_company_expense.company_id', '<>', 1);
            }
        }

        if (isset($search['month_year'])) {
            $query->where('tbl_company_expense.company_expense_month', date('m', strtotime('01-'.$search['month_year'])));
            $query->where('tbl_company_expense.company_expense_year', date('Y', strtotime('01-'.$search['month_year'])));
        }else{
            $query->where('tbl_company_expense.company_expense_year', date('Y'));
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_company_land.company_land_id', $search['company_land_id']);
        }

        if (isset($search['setting_expense_category_id'])) {
            $query->where('tbl_setting_expense_category.setting_expense_category_id', $search['setting_expense_category_id']);
        }

        $query->groupByRaw('tbl_company_expense.setting_expense_category_id, tbl_company_expense.company_land_id, tbl_company_expense.company_expense_month');

        $query->orderBy('tbl_company_expense.setting_expense_category_id', 'ASC');

        // $sql = str_replace(array('?'), array('\'%s\''), $query->toSql());
        // $sql= vsprintf($sql, $query->getBindings());
        // dd($sql);

        $result = $query->get();

        foreach ($result as $data) {
            if (isset($company_expense[$data->setting_expense_category_id][$data->company_land_id][$data->company_expense_month])) {
                $company_expense[$data->setting_expense_category_id][$data->company_land_id][$data->company_expense_month]['company_expense_sum'] += $data->company_expense_sum;
            } else {
                $company_expense[$data->setting_expense_category_id][$data->company_land_id][$data->company_expense_month]['company_expense_sum'] = $data->company_expense_sum;
            }
        }

        return $company_expense;
    }

    public static function get_company_expense_detail_report_component($search, $month_num){

        $company_expense_detail_array = array();
        $company_id = auth()->user()->company_id;


        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_company_land.company_land_id,
            ifnull(sum(tbl_company_expense_item.company_expense_item_total), 0) as total,
            company_expense_month
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            join tbl_company_land ON tbl_company_land.company_land_id = tbl_company_expense.company_land_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id
            and tbl_company_expense.company_expense_month = $month_num
            group by tbl_company_expense.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_company_expense.company_id,
            tbl_company_expense.company_land_id
            order by tbl_company_expense.company_id";

            if (isset($search['company_id'])) {
                $query .= " AND tbl_company.company_id = {$search['company_id']}";
            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                    $query .= " AND tbl_company.company_id IN ({$ids})";
                } else if (auth()->user()->company_id != 0) {
                    $query .= " AND tbl_company_expense.company_id = {$company_id}";
                } else {
                    $query .= " AND tbl_company_expense.company_id <> 1";
                }
            }

        $result = DB::select($query);

        foreach ($result as $data) {
            if (isset($company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_land_id][$data->company_expense_month])) {
                $company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_land_id][$data->company_expense_month] += $data->total;
            } else {
                $company_expense_detail_array[$data->setting_expense_category_id][$data->setting_expense_id][$data->company_land_id][$data->company_expense_month] = $data->total;
            }
        }


        return($company_expense_detail_array);

    }


    public static function get_company_land_tree($search){
        $company_id = auth()->user()->company_id;
        $result = [];
        $query ='SELECT c.company_name, c.company_id, cl.company_land_name, cl.company_land_id, p.product_name, p.product_id,
                    count(clt.company_land_tree_id) as no_all_tree,
                    count(case when clt.company_land_tree_status="alive" then 1 end) as no_of_tree,
                    count(case when clt.is_sick = 0 && clt.company_land_tree_status="alive" then 1 end) as healthy,
                    count(case when clt.is_sick = 1 && clt.company_land_tree_status="alive" then 1 end) as sick,
                    count(case when clt.company_land_tree_status = "dead" then 1 end) as died,
                    count(case when clt.company_land_tree_status = "saw off" then 1 end) as saw_off,
                    count(case when company_pnl_sub_item_code = "k1" && clt.company_land_tree_status="alive" then 1 end) as kone,
                    count(case when company_pnl_sub_item_code = "B10" && clt.company_land_tree_status="alive" then 1 end) as bten,
                    count(case when company_pnl_sub_item_code = "A10" && clt.company_land_tree_status="alive" then 1 end) as aten,

                    count(case when (YEAR(current_timestamp()) - clt.company_land_tree_year) = 1 && clt.company_land_tree_status="alive" then 1 end) as ltfive,
                    count(case when (YEAR(current_timestamp()) - clt.company_land_tree_year) > 1 && (YEAR(current_timestamp()) - clt.company_land_tree_year) < 12 && clt.company_land_tree_status="alive" then 1 end) as ltten,
                    count(case when (YEAR(current_timestamp()) - clt.company_land_tree_year) >= 12 && clt.company_land_tree_status="alive" then 1 end) as ltfifteen
                    FROM tbl_company_land_tree as clt
                    join tbl_company_land as cl on cl.company_land_id = clt.company_land_id
                    join tbl_company_land_zone as clz on clz.company_land_zone_id = clt.company_land_zone_id
                    join tbl_company as c on c.company_id = cl.company_id
                    join tbl_product as p on p.product_id = clt.product_id WHERE clz.is_delete=0';

        if (isset($search['company_id'])) {
            $query .= " AND c.company_id = {$search['company_id']}";
        } else { //comment due to admin can now see all company
            // if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            //     // $ids = array();
            //     // foreach(auth()->user()->user_company as $key => $user_company){
            //     //   // $company->where('company_id', $user_company->company_id);
            //     //   $ids[$key] = $user_company->company_id;
            //     //   // dd($ids[$key]);
            //     // }
            //     // $query->whereIn('co.company_id', $ids);
            //     $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
            //     // $query->whereIn('tbl_invoice.company_id', $ids);
            //
            //     $query .= " AND c.company_id IN ({$ids})";
            // } else if (auth()->user()->company_id != 0) {
            //     // $query->where('co.company_id', auth()->user()->company_id);
            //     $query .= " AND c.company_id = {$company_id}";
            // } else {
            //     // $query->where('co.company_id', '<>', 1);
            //     $query .= " AND c.company_id <> 1";
            // }
        }

        if (@$search['company_land_id']) {
            $query .= " and cl.company_land_id = " . $search['company_land_id'];
        }

        if (@$search['product_id']) {
            $query .= " and p.product_id = " . $search['product_id'];
        }

        $query.= " group by c.company_name, clt.company_land_id, clt.product_id";

        $data = DB::select($query);
        if($data)
        {
            foreach($data as $key => $rows)
            {

                $result['data'][$rows->company_id][$rows->company_land_id][$rows->product_id] = (array)$rows;

                $result['company'][$rows->company_id] = $rows->company_name;
                $result['company_land'][$rows->company_id][$rows->company_land_id] = $rows->company_land_name;

                if(!isset($result['product'][$rows->company_id][$rows->company_land_id])){
                    $result['product'][$rows->company_id][$rows->company_land_id] = array();
                    $tree_arr = array(
                        "product_id" => $rows->product_id,
                        "product_name" => $rows->product_name,
                        "no_all_tree" => $rows->no_all_tree,
                        "no_of_tree" => $rows->no_of_tree,
                        "healthy" => $rows->healthy,
                        "sick" => $rows->sick,
                        "died" => $rows->died,
                        "saw_off" => $rows->saw_off,
                        "k1" => $rows->kone,
                        "b10" => $rows->bten,
                        "a10" => $rows->aten,
                        "lt5" => $rows->ltfive,
                        "lt10" => $rows->ltten,
                        "lt15" => $rows->ltfifteen,
                    );
                    array_push( $result['product'][$rows->company_id][$rows->company_land_id], $tree_arr);
                }else{
                    $tree_arr = array(
                        "product_id" => $rows->product_id,
                        "product_name" => $rows->product_name,
                        "no_all_tree" => $rows->no_all_tree,
                        "no_of_tree" => $rows->no_of_tree,
                        "healthy" => $rows->healthy,
                        "sick" => $rows->sick,
                        "died" => $rows->died,
                        "saw_off" => $rows->saw_off,
                        "k1" => $rows->kone,
                        "b10" => $rows->bten,
                        "a10" => $rows->aten,
                        "lt5" => $rows->ltfive,
                        "lt10" => $rows->ltten,
                        "lt15" => $rows->ltfifteen,
                    );
                    array_push( $result['product'][$rows->company_id][$rows->company_land_id], $tree_arr);
                }

                $total_all_tree= 0;
                foreach($result['data'][$rows->company_id][$rows->company_land_id] as $lkey => $val){
                    // dd($result['data'][$rows->company_id][$rows->company_land_id][$lkey]['no_of_tree']);
                    $tree = $result['data'][$rows->company_id][$rows->company_land_id][$lkey]['no_all_tree'];
                    $total_all_tree += $tree;
                    $result['total_all_tree'][$rows->company_id][$rows->company_land_id] = $total_all_tree;
                }
            }

        }
        // dd($result);

        return $result;
    }

    public static function get_formula_usage_sa($search){
      // dd($search);
      $query = "SELECT month(fu.formula_usage_created) as month_num,
              sfc.setting_formula_category_id as sfc,
              fu.formula_usage_value as val,
              fup.formula_usage_product_json as product_json
              FROM tbl_formula_usage as fu
              JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
              JOIN tbl_company as c ON fu.company_id = c.company_id
              JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
              JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
              WHERE fu.formula_usage_status = 'completed'";

      if (isset($search['company_id'])) {
          $query .= " AND fu.company_id = {$search['company_id']}";
      } elseif(isset($search['company_cb_id'])) {
          $cb_id = implode(",", $search['company_cb_id']);
          $query .= " AND fu.company_id IN ({$cb_id})";
      } else {
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              // $ids = array();
              // foreach(auth()->user()->user_company as $key => $user_company){
              //   // $company->where('company_id', $user_company->company_id);
              //   $ids[$key] = $user_company->company_id;
              //   // dd($ids[$key]);
              // }
              // $query->whereIn('tbl_delivery_order.company_id', $ids);
              $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
              // $query->whereIn('tbl_invoice.company_id', $ids);

              $query .= " AND fu.company_id IN ({$ids})";
          } else if (auth()->user()->company_id != 0) {
              // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
              $query .= " AND fu.company_id =". auth()->user()->company_id;
          } else {
              // $query->where('tbl_delivery_order.company_id', '<>', 1);
              $query .= " AND fu.company_id <> 1";
          }
      }

      if (isset($search['year'])) {
          $query .= " AND YEAR(fu.formula_usage_created) = {$search['year']} ";
      }

      if (isset($search['month'])) {
          $query .= " AND MONTH(fu.formula_usage_created) = {$search['month']} ";
      }

      if(isset($search['product_cb_id'])) {
          $p_id = implode(",", $search['product_cb_id']);
          $query .= " AND fup.product_id IN ({$p_id})";
      }

        $query .= "group by fu.formula_usage_id, MONTH(fu.formula_usage_created)";
        $result = DB::select($query);

        $query2 = "SELECT month(fu.formula_usage_created) as month_num,
                sfc.setting_formula_category_id as sfc,
                fu.formula_usage_value as val,
                fup.formula_usage_product_json as product_json
                FROM tbl_formula_usage as fu
                JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
                JOIN tbl_company as c ON fu.company_id = c.company_id
                JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
                JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
                WHERE fu.formula_usage_status = 'completed'";

        if (isset($search['company_id'])) {
            $query2 .= " AND fu.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query2 .= " AND fu.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('tbl_delivery_order.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query2 .= " AND fu.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                $query2 .= " AND fu.company_id =". auth()->user()->company_id;
            } else {
                // $query->where('tbl_delivery_order.company_id', '<>', 1);
                $query2 .= " AND fu.company_id <> 1";
            }
        }

        if (isset($search['year'])) {
            $query2 .= " AND YEAR(fu.formula_usage_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query2 .= " AND MONTH(fu.formula_usage_created) = {$search['month']} ";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query2 .= " AND fup.product_id IN ({$p_id})";
        }

         $result2 = DB::select($query2);
        $arr = [];
        // dd($result);
        foreach($result as $key => $value){
            if(isset($arr[$value->month_num][$value->sfc]['value'])){
                $arr[$value->month_num][$value->sfc]['value'] += $value->val;
            }else{
            $arr[$value->month_num][$value->sfc]['value'] = $value->val;
            }
        }

        foreach($result2 as $key => $value){
            if(isset($arr[$value->month_num][$value->sfc]['total_tree'])){
                $product_json = json_decode($value->product_json);
                // dd($product_json);
                if (is_array($product_json) || is_object($product_json)){
                foreach ($product_json as $key => $json) {
                    $arr[$value->month_num][$value->sfc]['total_tree'] += $json->formula_usage_product_quantity;
                }
                }
            }else{
            $product_json = json_decode($value->product_json);
            // dd($product_json);
            if (is_array($product_json) || is_object($product_json)){
                foreach ($product_json as $key => $json) {
                    if(isset($arr[$value->month_num][$value->sfc]['total_tree'])){
                        $arr[$value->month_num][$value->sfc]['total_tree'] += (int) $json->formula_usage_product_quantity;
                    }else{
                        $arr[$value->month_num][$value->sfc]['total_tree'] = (int) $json->formula_usage_product_quantity;
                    }
                }
            }
            }
        }

        // dd($arr);
        return $arr;
    }

    public static function get_formula_usage_sa_detail($search, $year, $month, $setting_formula_category_id){

      $query = "SELECT fup.product_id as product_id,
              fu.company_id as company_id,
              fu.company_land_id as company_land_id,
              fup.formula_usage_product_json as product_json
              FROM tbl_formula_usage as fu
              JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
              JOIN tbl_company as c ON fu.company_id = c.company_id
              JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
              JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
              WHERE fu.formula_usage_status = 'completed'";

      if (isset($search['company_id'])) {
          $query .= " AND fu.company_id = {$search['company_id']}";
      } elseif(isset($search['company_cb_id'])) {
          $cb_id = implode(",", $search['company_cb_id']);
          $query .= " AND fu.company_id IN ({$cb_id})";
      } else {
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              // $ids = array();
              // foreach(auth()->user()->user_company as $key => $user_company){
              //   // $company->where('company_id', $user_company->company_id);
              //   $ids[$key] = $user_company->company_id;
              //   // dd($ids[$key]);
              // }
              // $query->whereIn('tbl_delivery_order.company_id', $ids);
              $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
              // $query->whereIn('tbl_invoice.company_id', $ids);

              $query .= " AND fu.company_id IN ({$ids})";
          } else if (auth()->user()->company_id != 0) {
              // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
              $query .= " AND fu.company_id =". auth()->user()->company_id;
          } else {
              // $query->where('tbl_delivery_order.company_id', '<>', 1);
              $query .= " AND fu.company_id <> 1";
          }
      }

      if(isset($search['product_cb_id'])) {
          $p_id = implode(",", $search['product_cb_id']);
          $query .= " AND fup.product_id IN ({$p_id})";
      }

        $query .= " AND sfc.setting_formula_category_id = $setting_formula_category_id ";
        $query .= " AND MONTH(fu.formula_usage_created) = $month ";
        $query .= " AND YEAR(fu.formula_usage_created) = $year ";
        // $query .= "group by fup.product_id, fu.company_id, fu.company_land_id";

        $result = DB::select($query);
        $arr = [];

        $query2 = "SELECT fup.product_id as product_id,
                fu.company_id as company_id,
                fu.company_land_id as company_land_id,
                rmcu.raw_material_company_usage_total_price as total_price
                FROM tbl_formula_usage as fu
                JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
                JOIN tbl_formula_usage_item as fui on fu.formula_usage_id = fui.formula_usage_id
                JOIN tbl_company as c ON fu.company_id = c.company_id
                JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
                JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
                JOIN tbl_raw_material_company as rmc ON (fu.company_id = rmc.company_id and fui.raw_material_id = rmc.raw_material_id)
                JOIN tbl_raw_material_company_usage as rmcu on rmc.raw_material_company_id = rmcu.raw_material_company_id
                WHERE fu.formula_usage_status = 'completed' AND rmcu.raw_material_company_usage_type != 'usage'
                AND rmcu.raw_material_company_usage_created = ( select max(raw_material_company_usage_created) from tbl_raw_material_company_usage where tbl_raw_material_company_usage.raw_material_company_id = rmc.raw_material_company_id)";

        if (isset($search['company_id'])) {
            $query2 .= " AND fu.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query2 .= " AND fu.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                // }
                // $query->whereIn('tbl_delivery_order.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query2 .= " AND fu.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                $query2 .= " AND fu.company_id =". auth()->user()->company_id;
            } else {
                // $query->where('tbl_delivery_order.company_id', '<>', 1);
                $query2 .= " AND fu.company_id <> 1";
            }
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query2 .= " AND fup.product_id IN ({$p_id})";
        }

        $query2 .= " AND sfc.setting_formula_category_id = $setting_formula_category_id ";
        $query2 .= " AND MONTH(fu.formula_usage_created) = $month ";
        $query2 .= " AND YEAR(fu.formula_usage_created) = $year ";

        $result2 = DB::select($query2);

        $arr2 = [];

        foreach($result as $key => $value){

            if(isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree']) && isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'])){
            $product_json = json_decode($value->product_json);
            foreach ($product_json as $key => $json) {
                $each_total_value_per_tree = $json->formula_usage_product_value_per_tree*$json->formula_usage_product_quantity;
                $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] += (int) $each_total_value_per_tree;
                $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] += (int) $json->formula_usage_product_quantity;
            }
            }else{
            $product_json = json_decode($value->product_json);
            foreach ($product_json as $key => $json) {
                $each_total_value_per_tree = $json->formula_usage_product_value_per_tree*$json->formula_usage_product_quantity;
                if(isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree']) && isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'])){
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] += (int) $each_total_value_per_tree;
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] += (int) $json->formula_usage_product_quantity;
                }else{
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] = (int) $each_total_value_per_tree;
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] = (int) $json->formula_usage_product_quantity;
                }
            }
            // dd($arr);
            }
        }

        foreach($result2 as $key2 => $value2){
            if(isset($arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'])){
            $arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'] += $value2->total_price;
            }else{
            $arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'] = $value2->total_price;
            }
        }
        // exit;
        // dump($arr);
        // dd('stop');
        return ['result' => $arr, 'result2' => $arr2];
    }

    public static function get_formula_usage_admin_detail($search, $year, $month, $setting_formula_category_id){
        // dd($search);
        $query = "SELECT fup.product_id as product_id,
                fu.company_id as company_id,
                fu.company_land_id as company_land_id,
                fup.formula_usage_product_json as product_json
                FROM tbl_formula_usage as fu
                JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
                JOIN tbl_company as c ON fu.company_id = c.company_id
                JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
                JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
                WHERE fu.formula_usage_status = 'completed'";

        if (isset($search['company_id'])) {
            $query .= " AND fu.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND fu.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('tbl_delivery_order.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND fu.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                $query .= " AND fu.company_id =". auth()->user()->company_id;
            } else {
                // $query->where('tbl_delivery_order.company_id', '<>', 1);
                $query .= " AND fu.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND fu.company_land_id = {$search['company_land_id']} ";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND fup.product_id IN ({$p_id})";
        }

            $query .= " AND sfc.setting_formula_category_id = $setting_formula_category_id ";
            $query .= " AND MONTH(fu.formula_usage_created) = $month ";
            $query .= " AND YEAR(fu.formula_usage_created) = $year ";
            // $query .= "group by fup.product_id, fu.company_id, fu.company_land_id";
            // dd($query);
            $result = DB::select($query);
            $arr = [];

            $query2 = "SELECT fup.product_id as product_id,
                        fu.company_id as company_id,
                        fu.company_land_id as company_land_id,
                        rmcu.raw_material_company_usage_total_price as total_price
                        FROM tbl_formula_usage as fu
                        JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
                        JOIN tbl_formula_usage_item as fui on fu.formula_usage_id = fui.formula_usage_id
                        JOIN tbl_company as c ON fu.company_id = c.company_id
                        JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
                        JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
                        JOIN tbl_raw_material_company as rmc ON (fu.company_id = rmc.company_id and fu.company_land_id = rmc.company_land_id and fui.raw_material_id = rmc.raw_material_id)
                        JOIN tbl_raw_material_company_usage as rmcu on rmc.raw_material_company_id = rmcu.raw_material_company_id
                        WHERE fu.formula_usage_status = 'completed' AND rmcu.raw_material_company_usage_type != 'usage'
                        AND rmcu.raw_material_company_usage_created = ( select max(raw_material_company_usage_created) from tbl_raw_material_company_usage where tbl_raw_material_company_usage.raw_material_company_id = rmc.raw_material_company_id)";

            if (isset($search['company_id'])) {
                $query2 .= " AND fu.company_id = {$search['company_id']}";
            } elseif(isset($search['company_cb_id'])) {
                $cb_id = implode(",", $search['company_cb_id']);
                $query2 .= " AND fu.company_id IN ({$cb_id})";
            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    // $ids = array();
                    // foreach(auth()->user()->user_company as $key => $user_company){
                    //   // $company->where('company_id', $user_company->company_id);
                    //   $ids[$key] = $user_company->company_id;
                    //   // dd($ids[$key]);
                    // }
                    // $query->whereIn('tbl_delivery_order.company_id', $ids);
                    $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                    // $query->whereIn('tbl_invoice.company_id', $ids);

                    $query2 .= " AND fu.company_id IN ({$ids})";
                } else if (auth()->user()->company_id != 0) {
                    // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                    $query2 .= " AND fu.company_id =". auth()->user()->company_id;
                } else {
                    // $query->where('tbl_delivery_order.company_id', '<>', 1);
                    $query2 .= " AND fu.company_id <> 1";
                }
            }

            if (isset($search['company_land_id'])) {
                $query2 .= " AND fu.company_land_id = {$search['company_land_id']} ";
            }

            if(isset($search['product_cb_id'])) {
                $p_id = implode(",", $search['product_cb_id']);
                $query2 .= " AND fup.product_id IN ({$p_id})";
            }

                $query2 .= " AND sfc.setting_formula_category_id = $setting_formula_category_id ";
                $query2 .= " AND MONTH(fu.formula_usage_created) = $month ";
                $query2 .= " AND YEAR(fu.formula_usage_created) = $year ";

                $result2 = DB::select($query2);
                // dd($result2);
                $arr2 = [];
            // dd($result);
                foreach($result as $key => $value){

                if(isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree']) && isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'])){
                    $product_json = json_decode($value->product_json);
                    foreach ($product_json as $key => $json) {
                    $each_total_value_per_tree = $json->formula_usage_product_value_per_tree*$json->formula_usage_product_quantity;
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] += (int) $each_total_value_per_tree;
                    $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] += (int) $json->formula_usage_product_quantity;
                    }
                }else{
                    $product_json = json_decode($value->product_json);
                    foreach ($product_json as $key => $json) {
                        $each_total_value_per_tree = $json->formula_usage_product_value_per_tree*$json->formula_usage_product_quantity;
                        if(isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree']) && isset($arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'])){
                        $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] += (int) $each_total_value_per_tree;
                            $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] += (int) $json->formula_usage_product_quantity;
                        }else{
                        $arr[$value->product_id][$value->company_id][$value->company_land_id]['val_per_tree'] = (int) $each_total_value_per_tree;
                            $arr[$value->product_id][$value->company_id][$value->company_land_id]['total_tree'] = (int) $json->formula_usage_product_quantity;
                        }
                    }
                    // dd($arr);
                }
                }

                foreach($result2 as $key2 => $value2){
                if(isset($arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'])){
                    $arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'] += $value2->total_price;
                }else{
                    $arr2[$value2->product_id][$value2->company_id][$value2->company_land_id]['total_price'] = $value2->total_price;
                }
                }
                // exit;
            // dd($arr);
            return ['result' => $arr, 'result2' => $arr2];
    }

    public static function get_formula_usage_admin($search){
        // dd($search);
        $query = "SELECT month(fu.formula_usage_created) as month_num,
                sfc.setting_formula_category_id as sfc,
                fu.formula_usage_value as val,
                fup.formula_usage_product_json as product_json
                FROM tbl_formula_usage as fu
                JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
                JOIN tbl_company as c ON fu.company_id = c.company_id
                JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
                JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
                WHERE fu.formula_usage_status = 'completed'";

        if (isset($search['company_id'])) {
            $query .= " AND fu.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND fu.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                // $ids = array();
                // foreach(auth()->user()->user_company as $key => $user_company){
                //   // $company->where('company_id', $user_company->company_id);
                //   $ids[$key] = $user_company->company_id;
                //   // dd($ids[$key]);
                // }
                // $query->whereIn('tbl_delivery_order.company_id', $ids);
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                // $query->whereIn('tbl_invoice.company_id', $ids);

                $query .= " AND fu.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
                $query .= " AND fu.company_id =". auth()->user()->company_id;
            } else {
                // $query->where('tbl_delivery_order.company_id', '<>', 1);
                $query .= " AND fu.company_id <> 1";
            }
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND fup.product_id IN ({$p_id})";
        }

        if (isset($search['year'])) {
            $query .= " AND YEAR(fu.formula_usage_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query .= " AND MONTH(fu.formula_usage_created) = {$search['month']} ";
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND fu.company_land_id = {$search['company_land_id']} ";
        }

      $query .= "group by fu.formula_usage_id, MONTH(fu.formula_usage_created)";
      $result = DB::select($query);

      $query2 = "SELECT month(fu.formula_usage_created) as month_num,
              sfc.setting_formula_category_id as sfc,
              fu.formula_usage_value as val,
              fup.formula_usage_product_json as product_json
              FROM tbl_formula_usage as fu
              JOIN tbl_formula_usage_product as fup ON fu.formula_usage_id = fup.formula_usage_id
              JOIN tbl_company as c ON fu.company_id = c.company_id
              JOIN tbl_setting_formula as sf ON fu.setting_formula_id = sf.setting_formula_id
              JOIN tbl_setting_formula_category as sfc ON  sf.setting_formula_category_id = sfc.setting_formula_category_id
              WHERE fu.formula_usage_status = 'completed'";

      if (isset($search['company_id'])) {
          $query2 .= " AND fu.company_id = {$search['company_id']}";
      } elseif(isset($search['company_cb_id'])) {
          $cb_id = implode(",", $search['company_cb_id']);
          $query2 .= " AND fu.company_id IN ({$cb_id})";
      } else {
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              // $ids = array();
              // foreach(auth()->user()->user_company as $key => $user_company){
              //   // $company->where('company_id', $user_company->company_id);
              //   $ids[$key] = $user_company->company_id;
              //   // dd($ids[$key]);
              // }
              // $query->whereIn('tbl_delivery_order.company_id', $ids);
              $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
              // $query->whereIn('tbl_invoice.company_id', $ids);

              $query2 .= " AND fu.company_id IN ({$ids})";
          } else if (auth()->user()->company_id != 0) {
              // $query->where('tbl_delivery_order.company_id', auth()->user()->company_id);
              $query2 .= " AND fu.company_id =". auth()->user()->company_id;
          } else {
              // $query->where('tbl_delivery_order.company_id', '<>', 1);
              $query2 .= " AND fu.company_id <> 1";
          }
      }

      if(isset($search['product_cb_id'])) {
        $p_id = implode(",", $search['product_cb_id']);
            $query2 .= " AND fup.product_id IN ({$p_id})";
        }

        if (isset($search['year'])) {
            $query2 .= " AND YEAR(fu.formula_usage_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query2 .= " AND MONTH(fu.formula_usage_created) = {$search['month']} ";
        }

        if (isset($search['company_land_id'])) {
            $query2 .= " AND fu.company_land_id = {$search['company_land_id']} ";
        }

        $result2 = DB::select($query2);
      $arr = [];
      // dd($result);
        foreach($result as $key => $value){
          if(isset($arr[$value->month_num][$value->sfc]['value'])){
              $arr[$value->month_num][$value->sfc]['value'] += $value->val;
          }else{
            $arr[$value->month_num][$value->sfc]['value'] = $value->val;
          }
        }

        foreach($result2 as $key => $value){
          if(isset($arr[$value->month_num][$value->sfc]['total_tree'])){
              $product_json = json_decode($value->product_json);
              // dd($product_json);
              if (is_array($product_json) || is_object($product_json)){
                foreach ($product_json as $key => $json) {
                  $arr[$value->month_num][$value->sfc]['total_tree'] += $json->formula_usage_product_quantity;
                }
              }
          }else{
            $product_json = json_decode($value->product_json);
            // dd($product_json);
            if (is_array($product_json) || is_object($product_json)){
              foreach ($product_json as $key => $json) {
                  if(isset($arr[$value->month_num][$value->sfc]['total_tree'])){
                      $arr[$value->month_num][$value->sfc]['total_tree'] += (int) $json->formula_usage_product_quantity;
                  }else{
                      $arr[$value->month_num][$value->sfc]['total_tree'] = (int) $json->formula_usage_product_quantity;
                  }
              }
            }
          }
        }

      // dd($arr);
      return $arr;
    }
    public static function company_farm_worker_expense_report($search)
    {
        $company_worker_expense_array = array();

        $query = CompanyExpenseWorker::query()
                ->selectRaw('tbl_company_expense.company_expense_id AS company_expense_id,
                             tbl_company_expense.user_id AS user_id,
                            tbl_company.company_id, company_expense_worker_id, tbl_company_expense_worker.worker_id,
                            ifnull( SUM( tbl_company_expense_worker.company_expense_worker_total ), 0 ) AS company_expense_worker_sum,
                            tbl_company_expense.company_expense_month')
                ->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_worker.company_expense_id')
                ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
                ->join('tbl_user', 'tbl_user.user_id', 'tbl_company_expense.user_id')
                ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.model_id', 'tbl_user.user_id')
                ->join('tbl_user_role', 'tbl_user_role.id', 'tbl_user_model_has_role.role_id')
                ->where('tbl_user_role.name', 'Farm Manager')
                ->where('tbl_company_expense.company_expense_status', '<>', 'deleted');

        if (isset($search['year'])) {
            $query->where('tbl_company_expense.company_expense_year', $search['year']);
        }

        if (isset($search['month'])) {
            $query->where('tbl_company_expense.company_expense_month', $search['month']);
        }

        if (isset($search['company_id'])) {
            $query->where('tbl_company.company_id', $search['company_id']);
        } else {
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

        if (isset($search['user_id'])) {
            $query->where('tbl_company_expense.user_id', $search['user_id']);
        }

        $query->groupByRaw('tbl_company_expense.user_id,tbl_company_expense.company_expense_month');
        $query->orderBy('user_id', 'ASC');

        // $sql = str_replace(array('?'), array('\'%s\''), $query->toSql());
        // $sql= vsprintf($sql, $query->getBindings());
        // dd($sql);

        $result = $query->get();
        // dd($result);

        foreach ($result as $data) {
            if (isset($company_worker_expense_array[$data->user_id][$data->company_id][$data->company_expense_month])) {
                $company_worker_expense_array[$data->user_id][$data->company_id][$data->company_expense_month]['company_expense_worker_sum'] += $data->company_expense_worker_sum;
            } else {
                $company_worker_expense_array[$data->user_id][$data->company_id][$data->company_expense_month]['company_expense_worker_sum'] = $data->company_expense_worker_sum;
            }
        }

        // dd($company_worker_expense_array);
        return $company_worker_expense_array;
    }

    public static function company_farm_worker_expense_report_detail($year, $month_num, $company_id, $user_id)
    {
        $worker_expense_arr = array();
        $query = CompanyExpenseWorker::query();
        $query->selectRaw('tbl_company_expense_worker.worker_id,
                            tbl_company_expense.setting_expense_category_id,
                            tbl_company_expense_worker.company_expense_worker_detail');
        $query->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', 'tbl_company_expense_worker.company_expense_id');
        $query->join('tbl_company_expense_item', 'tbl_company_expense_item.company_expense_id', 'tbl_company_expense.company_expense_id');
        $query->where('tbl_company_expense.company_expense_status', '<>', 'deleted');
        $query->where('tbl_company_expense.user_id', $user_id);
        $query->where('tbl_company_expense.company_id', $company_id);
        $query->where('tbl_company_expense.company_expense_year', $year);
        $query->where('tbl_company_expense.company_expense_month', $month_num);
        $query->groupBy('tbl_company_expense_worker.worker_id', 'tbl_company_expense_worker.company_expense_id');
        $result = $query->get();

        foreach($result as $data){
            $company_expense_worker_detail = json_decode($data->company_expense_worker_detail);
            foreach($company_expense_worker_detail as $details){
                if(is_array($details) && !empty($details)){
                    foreach($details as $task){
                        if(isset($worker_expense_arr[$data->setting_expense_category_id][$task->expense_id][$data->worker_id])){
                            $worker_expense_arr[$data->setting_expense_category_id][$task->expense_id][$data->worker_id] += $task->expense_total;
                        }else{
                            $worker_expense_arr[$data->setting_expense_category_id][$task->expense_id][$data->worker_id] = $task->expense_total;
                        }
                    }
                }
            }
        }

        return $worker_expense_arr;
    }

    public static function get_company_expense($search){
        $query = CompanyExpense::query()
        ->selectRaw('*,company_expense_total as total_actual_expense, company_expense_year as year')
        ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_company_expense.setting_expense_category_id')
        ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
        ->join('tbl_company_expense_land', 'tbl_company_expense_land.company_expense_id', 'tbl_company_expense.company_expense_id')
        ->join('tbl_company_land', 'tbl_company_expense_land.company_land_id', 'tbl_company_land.company_land_id')
        ->where('tbl_setting_expense_category.is_budget_limited', 1)
        ->where('tbl_company_land.company_land_total_tree', '!=', 0)
        ->whereNotNull('tbl_company_land.company_land_total_tree');

        if (isset($search['company_id'])) {
            $query->where('tbl_company_expense.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_company_expense.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_company_expense.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_company_expense.company_id', '<>', 1);
            }
        }

        if(@$search['company_land_id']){
            $company_land_id = $search['company_land_id'];
            $query->whereHas('company_expense_land', function ($query) use ($search){
                $query->where('company_land_id', $search['company_land_id']);
            });
        }

        if (isset($search['year'])) {
            $query->where('tbl_company_expense.company_expense_year', $search['year']);
        }

        $result = $query->get();

        $arr = array();
        foreach($result as $data){
            if(isset($arr[$data->company_id][$data->company_land_id])){
                $arr[$data->company_id][$data->company_land_id] += $data->total_actual_expense;
            }
            else{
                $arr[$data->company_id][$data->company_land_id] = $data->total_actual_expense;
            }
        }

        return $arr;
    }

    public static function get_formula_usage_item($search){
        $query = FormulaUsage::query();
        $query =  $query->selectRaw('*,tbl_formula_usage_item.formula_usage_item_total_price as total_actual_formula, YEAR(tbl_formula_usage_item.formula_usage_item_created) as year')
                        ->join('tbl_setting_formula','tbl_setting_formula.setting_formula_id','=', 'tbl_formula_usage.setting_formula_id')
                        ->join('tbl_setting_formula_category', 'tbl_setting_formula_category.setting_formula_category_id','=', 'tbl_setting_formula.setting_formula_category_id')
                        ->join('tbl_formula_usage_item','tbl_formula_usage_item.formula_usage_id','=','tbl_formula_usage.formula_usage_id')
                        ->join('tbl_company_land', 'tbl_company_land.company_land_id','=', 'tbl_formula_usage.company_land_id')
                        ->where('tbl_setting_formula_category.is_budget_limited', 1);

        if (isset($search['company_id'])) {
            $query->where('tbl_formula_usage.company_id', $search['company_id']);
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_formula_usage.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_formula_usage.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_formula_usage.company_id', '<>', 1);
            }
        }

        if (isset($search['company_land_id'])) {
            $query->where('tbl_formula_usage.company_land_id', $search['company_land_id']);
        }

        if (isset($search['year'])) {
            $query->whereYear('tbl_formula_usage.formula_usage_date', '=', $search['year']);
        }

        $result = $query->get();

        $arr = array();

        foreach($result as $data){
            if(isset($arr[$data->company_id][$data->company_land_id])){
                $arr[$data->company_id][$data->company_land_id] += $data->total_actual_formula;
            }
            else{
                $arr[$data->company_id][$data->company_land_id] = $data->total_actual_formula;
            }
        }

        return $arr;
    }

    public static function get_budget_category_name($search){

        if(isset($search['budget_category_id']) && preg_replace('/[^A-Za-z]/','',@$search['budget_category_id']) === 'formula'){
            $get_id = preg_replace('/[^0-9]/','',@$search['budget_category_id']);
            $query1 = "SELECT * FROM tbl_setting_formula_category WHERE is_budget_limited = 1";
            $query1 .= " AND tbl_setting_formula_category.setting_formula_category_id = $get_id";

            $result1 = DB::select($query1);
            return ['formula'=> $result1];
        }


        else if(isset($search['budget_category_id']) && preg_replace('/[^A-Za-z]/','',@$search['budget_category_id']) === 'expense'){
            $get_id = preg_replace('/[^0-9]/','',@$search['budget_category_id']);
            $query2 = "SELECT * FROM tbl_setting_expense_category WHERE is_budget_limited = 1";
            $query2 .= " AND tbl_setting_expense_category.setting_expense_category_id = $get_id";

            $result2 = DB::select($query2);
            return ['expense'=> $result2];
        }

        else{
            $query1 = "SELECT * FROM tbl_setting_formula_category WHERE is_budget_limited = 1";
            $query2 = "SELECT * FROM tbl_setting_expense_category WHERE is_budget_limited = 1";

            $result1 = DB::select($query1);
            $result2 = DB::select($query2);

            return ['formula'=> $result1, 'expense'=> $result2];
        }
    }

    public static function get_budget_company_formula_expense($search){

        $arr_formula = array();
        $arr_expense = array();
        $arr_actual_formula = array();
        $arr_actual_expense = array();

        $query1 = CompanyLandBudgetOverwrite::query()
                                            ->join('tbl_setting_formula_category', 'tbl_setting_formula_category.setting_formula_category_id','=','tbl_company_land_budget_overwrite.company_land_budget_overwrite_type_id')
                                            ->join('tbl_company_land', 'tbl_company_land.company_land_id','=', 'tbl_company_land_budget_overwrite.company_land_id')
                                            ->where('tbl_company_land.company_land_total_tree', '!=', 0)
                                            ->where('tbl_company_land_budget_overwrite.company_land_budget_overwrite_type', 'formula')
                                            ->where('tbl_setting_formula_category.is_budget_limited',1);

        if (isset($search['year'])) {
            $query1->whereYear('tbl_company_land_budget_overwrite.company_land_budget_overwrite_created', '=', $search['year']);
        }

        $result1 = $query1->get();

        foreach($result1 as $result){
            $arr_formula[$result->company_land_id][$result->setting_formula_category_id] = $result->company_land_budget_overwrite_value;
        }

        $query2 = CompanyLandBudgetOverwrite::query()
                                            ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id','=','tbl_company_land_budget_overwrite.company_land_budget_overwrite_type_id')
                                            ->join('tbl_company_land', 'tbl_company_land.company_land_id','=', 'tbl_company_land_budget_overwrite.company_land_id')
                                            ->where('tbl_company_land.company_land_total_tree', '!=', 0)
                                            ->where('tbl_company_land_budget_overwrite.company_land_budget_overwrite_type', 'expense')
                                            ->where('tbl_setting_expense_category.is_budget_limited',1);

        if (isset($search['year'])) {
            $query2->whereYear('tbl_company_land_budget_overwrite.company_land_budget_overwrite_created', '=', $search['year']);
        }

        $result2 = $query2->get();

        foreach($result2 as $result){
            $arr_expense[$result->company_land_id][$result->setting_expense_category_id] = $result->company_land_budget_overwrite_value;
        }

        $query3 = CompanyExpense::query()
        ->selectRaw('*,company_expense_total as total_actual_expense, company_expense_year as year, tbl_company_land.company_land_total_tree as total_tree')
        ->join('tbl_setting_expense_category', 'tbl_setting_expense_category.setting_expense_category_id', 'tbl_company_expense.setting_expense_category_id')
        ->join('tbl_company', 'tbl_company.company_id', 'tbl_company_expense.company_id')
        ->join('tbl_company_expense_land', 'tbl_company_expense_land.company_expense_id', 'tbl_company_expense.company_expense_id')
        ->join('tbl_company_land', 'tbl_company_expense_land.company_land_id', 'tbl_company_land.company_land_id')
        ->where('tbl_setting_expense_category.is_budget_limited', 1)
        ->where('tbl_company_land.company_land_total_tree', '!=', 0)
        ->whereNotNull('tbl_company_land.company_land_total_tree');

        if (isset($search['year'])) {
            $query3->where('tbl_company_expense.company_expense_year', '=', $search['year']);
        }

        $result3 = $query3->get();

        foreach($result3 as $data){
            if(isset($arr_actual_expense[$data->company_land_id][$data->setting_expense_category_id])){
                $arr_actual_expense[$data->company_land_id][$data->setting_expense_category_id] += $data->total_actual_expense;
            }
            else{
                $arr_actual_expense[$data->company_land_id][$data->setting_expense_category_id] = $data->total_actual_expense;
            }
        }

        $query4 = FormulaUsage::query();
        $query4 =  $query4->selectRaw('*,tbl_formula_usage_item.formula_usage_item_total_price as total_actual_formula, YEAR(tbl_formula_usage_item.formula_usage_item_created) as year')
                        ->join('tbl_setting_formula','tbl_setting_formula.setting_formula_id','=', 'tbl_formula_usage.setting_formula_id')
                        ->join('tbl_setting_formula_category', 'tbl_setting_formula_category.setting_formula_category_id','=', 'tbl_setting_formula.setting_formula_category_id')
                        ->join('tbl_formula_usage_item','tbl_formula_usage_item.formula_usage_id','=','tbl_formula_usage.formula_usage_id')
                        ->join('tbl_company_land', 'tbl_company_land.company_land_id','=', 'tbl_formula_usage.company_land_id')
                        ->where('tbl_setting_formula_category.is_budget_limited', 1)
                        ->where('tbl_company_land.company_land_total_tree', '!=', 0)
                        ->whereNotNull('tbl_company_land.company_land_total_tree');

        if (isset($search['year'])) {
            $query4->whereYear('tbl_formula_usage.formula_usage_created', '=', $search['year']);
        }

        $result4 = $query4->get();

        foreach($result4 as $data){
            if(isset($arr_actual_formula[$data->company_land_id][$data->setting_formula_category_id])){
                $arr_actual_formula[$data->company_land_id][$data->setting_formula_category_id] += $data->total_actual_formula;
            }
            else{
                $arr_actual_formula[$data->company_land_id][$data->setting_formula_category_id] = $data->total_actual_formula;
            }
        }

        return ['formula'=> $arr_formula, 'expense'=> $arr_expense, 'actual_formula' => $arr_actual_formula, 'actual_expense' =>$arr_actual_expense];
    }

    public static function get_budget_sel()
    {
        $arr_sel = collect();

        $query_formula = SettingFormulaCategory::query()->where('is_budget_limited',1);
        $result = $query_formula->get();
        $arr_sel = $arr_sel->merge($result);

        $query_expense = SettingExpenseCategory::query()->where('is_budget_limited',1);
        $result = $query_expense->get();
        $arr_sel = $arr_sel->merge($result);

        $r = $arr_sel->all();

        $arr = array();
        foreach($r as $data){
            if($data->setting_expense_category_id > 0){
                $arr['expense'.$data->setting_expense_category_id] = json_decode($data->setting_expense_category_name)->en;
            }else if($data->setting_formula_category_id > 0){
                $arr['formula'.$data->setting_formula_category_id] = json_decode($data->setting_formula_category_name)->en;
            }
        }

        return $arr;
    }

    public static function get_claim_by_year($search){

        $result = array();
        $query = Claim::query()
        ->selectRaw('MONTH(claim_start_date) AS month, YEAR(claim_start_date) AS year, SUM(claim_amount) AS total_claim, company_id, claim_status_id')
        // ->whereNotIn('claim_status_id',[2,3,4,5,7,8,9])
        ->where('is_deleted',0);

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }else{
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_claim.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_claim.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_claim.company_id', '<>', 1);
            }
        }

        if(@$search['year']){
            $query->whereYear('claim_start_date',$search['year']);
        }

        $query->groupByRaw('MONTH(claim_start_date ), YEAR(claim_start_date ), company_id, claim_status_id');

        foreach ($query->get() as $rows) {
            if ($rows->claim_status_id == 6) {
                if (isset($result[$rows->company_id][$rows->month]['claim'])) {
                    $result[$rows->company_id][$rows->month]['claim'] += $rows->total_claim;
                } else {
                    $result[$rows->company_id][$rows->month]['claim'] = $rows->total_claim;
                }
            } else if ($rows->claim_status_id == 5) {
                if (isset($result[$rows->company_id][$rows->month]['claim_pending'])) {
                    $result[$rows->company_id][$rows->month]['claim_pending'] += $rows->total_claim;
                } else {
                    $result[$rows->company_id][$rows->month]['claim_pending'] = $rows->total_claim;
                }
            }
        }

        return $result;
    }

    public static function get_claim_by_company($search){

        $result = array();
        $user = array();
        $query = Claim::query()
        // ->selectRaw('MONTH(claim_start_date) AS month, YEAR(claim_start_date) AS year, SUM(claim_amount) AS total_claim, company_id, claim_status_id,user_id')
        // ->whereNotIn('claim_status_id',[7,8,9]);
            ->selectRaw('*')
            ->with(['claim_item']);

        if (@$search['company_id']) {
            $query->where('tbl_claim.company_id', $search['company_id']);
        }else{
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('tbl_claim.company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('tbl_claim.company_id', auth()->user()->company_id);
            } else {
                $query->where('tbl_claim.company_id', '<>', 1);
            }
        }

        if (@$search['claim_status_id']) {
            $query->where('tbl_claim.claim_status_id', $search['claim_status_id']);
        } else {
            $query->whereNotIn('tbl_claim.claim_status_id',[7,8,9]);
        }

        if (@$search['year']) {
            $query->whereYear('tbl_claim.claim_start_date',$search['year']);
        }

        if (@$search['month']) {
            $query->whereMonth('tbl_claim.claim_start_date',$search['month']);
        }

        $total_claim = 0;

        foreach ($query->get() as $rows) {
            $result[$rows->user_id]['item'] = '';
            $result[$rows->user_id]['item_date'] = '';
            $result[$rows->user_id]['claim_amount'] = '';
            $result[$rows->user_id]['total_claim_amount'] = 0;
             if ($rows->claim_status_id == 6) {
                foreach ($rows->claim_item as $item) {
                    $result[$rows->user_id]['item'] .= json_decode($item->claim_item_name)->en . '</br>';
                    $result[$rows->user_id]['item_date'] .= $item->claim_item_date . '</br>';
                    $result[$rows->user_id]['claim_amount'] .= $item->claim_item_amount_claim . '</br>';
                    $result[$rows->user_id]['total_claim_amount'] += $item->claim_item_amount_claim;
                }
            } else if ($rows->claim_status_id == 5) {
                    foreach ($rows->claim_item as $item) {
                        $result[$rows->user_id]['item'] .= json_decode($item->claim_item_name)->en . '</br>';
                        $result[$rows->user_id]['item_date'] .= $item->claim_item_date . '</br>';
                        $result[$rows->user_id]['claim_amount'] .= $item->claim_item_amount_claim . '</br>';
                        $result[$rows->user_id]['total_claim_amount'] += $item->claim_item_amount_claim;
                    }
            }

            $user[$rows->user_id] = @$rows->user ? : array();
        }

        return [
            'data' => $result,
            'user' => $user
        ];
    }


    public static function get_number_tree_planted(){
        $query = CompanyLandTree::query()
                                ->selectRaw('tbl_company_land_tree.company_land_id, COUNT(*) as count')
                                ->join('tbl_company_land','tbl_company_land.company_land_id','=','tbl_company_land_tree.company_land_id')
                                ->join('tbl_company_pnl_sub_item', 'tbl_company_pnl_sub_item.company_pnl_sub_item_code','=','tbl_company_land_tree.company_pnl_sub_item_code')
                                ->where('tbl_company_pnl_sub_item.company_pnl_item_id','=',2) // A1
                                ->where('tbl_company_land_tree.company_land_tree_status','=','alive');

        $result = $query->groupBy('tbl_company_land_tree.company_land_id')->get();
        $arr = [];

        foreach($result as $data){
            $arr[$data->company_land_id] = $data->count;
        }
        return $arr;
    }

    public static function get_small_tree_planted()
    {
        $query = CompanyLandTree::query()
                                ->selectRaw('tbl_company_land_tree.company_land_id, COUNT(*) as count')
                                ->join('tbl_company_land','tbl_company_land.company_land_id','=','tbl_company_land_tree.company_land_id')
                                ->join('tbl_company_pnl_sub_item', 'tbl_company_pnl_sub_item.company_pnl_sub_item_code','=','tbl_company_land_tree.company_pnl_sub_item_code')
                                ->where('tbl_company_pnl_sub_item.company_pnl_item_id','=',5) // K1
                                ->where('tbl_company_land_tree.product_id','!=',10) // <> Kampung
                                ->where('tbl_company_land_tree.company_land_tree_status','=','alive');

        $result = $query->groupBy('tbl_company_land_tree.company_land_id')->get();
        $arr = [];

        foreach($result as $data){
            $arr[$data->company_land_id] = $data->count;
        }
        return $arr;
    }

    public static function get_baby_tree_planted()
    {
        $query = CompanyLandTree::query()
                                ->selectRaw('tbl_company_land_tree.company_land_id, COUNT(*) as count')
                                ->join('tbl_company_land','tbl_company_land.company_land_id','=','tbl_company_land_tree.company_land_id')
                                ->join('tbl_company_pnl_sub_item', 'tbl_company_pnl_sub_item.company_pnl_sub_item_code','=','tbl_company_land_tree.company_pnl_sub_item_code')
                                ->where('tbl_company_pnl_sub_item.company_pnl_item_id','=',5) // K1
                                ->where('tbl_company_land_tree.product_id','=',10) // Kampung
                                ->where('tbl_company_land_tree.company_land_tree_status','=','alive');

        $result = $query->groupBy('tbl_company_land_tree.company_land_id')->get();
        $arr = [];

        foreach($result as $data){
            $arr[$data->company_land_id] = $data->count;
        }
        return $arr;
    }

    public static function get_pnl_item()
    {
        $query = "SELECT cpnli.company_pnl_item_id as pnl_id,
                cpnli.company_pnl_item_name as pnl_name,
                cpnli.company_pnl_item_code as pnl_code,
                cpnli.company_pnl_item_start_year as start_year,
                cpnli.company_pnl_item_yearly_increase_value as yearly_increase,
                cpnli.company_pnl_item_max_value as max_value,
                cpnli.company_pnl_item_initial_value as initial_value
                FROM tbl_company_pnl_item as cpnli
                WHERE cpnli.company_pnl_item_code != 'A0'
                AND cpnli.company_pnl_item_code != 'A6'";

        $query .= " ORDER BY cpnli.company_pnl_item_code ASC";

        $result = DB::select($query);

        return $result;
    }

    public static function get_setting_forecast()
    {
        $query = "SELECT * FROM tbl_setting as s
                WHERE s.setting_slug = 'each_durian_weight'
                OR s.setting_slug = 'forecast_report_default_year'
                OR s.setting_slug = 'effective_capacity'
                OR s.setting_slug = 'percentage_price_increase_per_year_kg'
                OR s.setting_slug = 'forecast_report_year_count'
                OR s.setting_slug = 'forecast_report_pe_ratio_year'
                OR s.setting_slug = 'pe_ratio'";

        $result = DB::select($query);

        $arr = [];

        foreach ($result as $key => $value) {
            $arr[$value->setting_slug] = $value->setting_value;
        }

        return $arr;
    }

    public static function get_forecast_report_result($search)
    {
        $query = "SELECT cpi.company_pnl_item_code as pnl_code,
                pc.product_category_id as product_category_id,
                count(clt.company_land_tree_id) as tree_count,
                year(cltl.company_land_tree_log_date) as year
                FROM tbl_company_land_tree as clt
                join tbl_company_land_tree_log as cltl on clt.company_land_tree_id = cltl.company_land_tree_id
                join tbl_company_land as cl on clt.company_land_id = cl.company_land_id
                join tbl_company_land_zone as clz on clz.company_land_zone_id = clt.company_land_zone_id
                join tbl_company as c on cl.company_id = c.company_id
                join tbl_product as p on p.product_id = clt.product_id
                join tbl_product_category as pc on p.product_category_id = pc.product_category_id
                join tbl_company_pnl_sub_item as cpsi on clt.company_pnl_sub_item_code = cpsi.company_pnl_sub_item_code
                join tbl_company_pnl_item as cpi on cpsi.company_pnl_item_id = cpi.company_pnl_item_id
                where cltl.company_land_tree_action_id = 5 && clz.is_delete <> 1";

        if (isset($search['company_id'])) {
            $query .= " AND c.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND c.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND c.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND c.company_id =". auth()->user()->company_id;
            } else {
                $query .= " AND c.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND cl.company_land_id = {$search['company_land_id']}";
        }

        if(isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND cl.company_land_id IN ({$cl_id})";
        }


        $query .= " GROUP BY cpi.company_pnl_item_code, year(cltl.company_land_tree_log_date), pc.product_category_id
                    ORDER BY year(cltl.company_land_tree_log_date) ASC";

        $result = DB::select($query);
        // dd($result);
        $arr = [];

        foreach ($result as $key => $value) {
            if($value->product_category_id == 1 && $value->pnl_code =='A1'){
                if(isset($arr['A2'][$value->year])){
                    $arr['A2'][$value->year]['tree_count'] += $value->tree_count;
                    $arr['A2'][$value->year]['year'] = $value->year;
                }else{
                    $arr['A2'][$value->year]['tree_count'] = $value->tree_count;
                    $arr['A2'][$value->year]['year'] = $value->year;
                }
            }else{
                if(isset($arr[$value->pnl_code][$value->year])){
                    $arr[$value->pnl_code][$value->year]['tree_count'] += $value->tree_count;
                    $arr[$value->pnl_code][$value->year]['year'] = $value->year;
                }else{
                    $arr[$value->pnl_code][$value->year]['tree_count'] = $value->tree_count;
                    $arr[$value->pnl_code][$value->year]['year'] = $value->year;
                }
            }
        }
        return $arr;
    }
    public static function get_average_price_for_forecast_report($search)
    {
        $year = implode("", Setting::where('setting_id', 32)->pluck('setting_value')->all());
        $query = array();
        $query = "SELECT SUM(tbl_invoice_item.invoice_item_total) as price,
                SUM(tbl_invoice_item.invoice_item_quantity) as quantity,
                tbl_product.product_name as product,
                tbl_product.product_id as product_id,
                tbl_product_category.product_category_id as category,
                tbl_company_land.company_land_id as land,
                tbl_company.company_name as company
                FROM tbl_invoice_item
                JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                WHERE tbl_product_category.product_category_id in (2, 3)
                AND tbl_company.is_display = 1
                AND tbl_invoice.invoice_status_id <> 3";

        $query .= " AND YEAR(tbl_invoice_item.invoice_item_created) = $year";

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company.company_id = {$search['company_id']}";
        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company.company_id =". auth()->user()->company_id;
            } else {
                $query .= " AND tbl_company.company_id <> 1";
            }
        }
        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_invoice.company_land_id = {$search['company_land_id']}";
        }

        $query .=" GROUP BY product_id, category, land, company
                    ORDER BY tbl_product.product_ranking ASC";

        $result = DB::select($query);
        // dd($result);


        $total_price = [];
        $total_qty = [];
        $price_product_avg = [];
        $total_avg_price = [];
        $category_name = [];

        $product = Product::get_product_for_forecast_report();

        foreach ($result as $data) {
            if (isset($total_price[$data->category][$data->product_id])) {
                $total_price[$data->category][$data->product_id] += $data->price;
            }else{
                $total_price[$data->category][$data->product_id] = $data->price;
            }

            if (isset($total_qty[$data->category][$data->product_id])) {
                $total_qty[$data->category][$data->product_id] += $data->quantity;
            }else{
                $total_qty[$data->category][$data->product_id] = $data->quantity;
            }
        }

        foreach ($product as $data) {
            if (isset($price_product_avg[$data->product_category->product_category_id])){
                if (isset($total_price[$data->product_category->product_category_id][$data->product_id]) && isset($total_qty[$data->product_category->product_category_id][$data->product_id])) {
                    $price_product_avg[$data->product_category->product_category_id] +=  $total_price[$data->product_category->product_category_id][$data->product_id]  / $total_qty[$data->product_category->product_category_id][$data->product_id];
                    $category_name[$data->product_category->product_category_id] = $data->product_category->product_category_name;
                }
            }else{
                if (isset($total_price[$data->product_category->product_category_id][$data->product_id]) && isset($total_qty[$data->product_category->product_category_id][$data->product_id])) {
                    $price_product_avg[$data->product_category->product_category_id] =  $total_price[$data->product_category->product_category_id][$data->product_id]  / $total_qty[$data->product_category->product_category_id][$data->product_id];
                    $category_name[$data->product_category->product_category_id] = $data->product_category->product_category_name;
                }
            }
        }

        foreach ($product as $data) {
            if (isset($price_product_avg[$data->product_category->product_category_id]) && isset($total_price[$data->product_category->product_category_id])){
                $total_avg_price[$data->product_category->product_category_id] = $price_product_avg[$data->product_category->product_category_id] / count($total_price[$data->product_category->product_category_id]);
            }else{
                if(isset($total_price[$data->product_category->product_category_id])){
                    $total_avg_price[$data->product_category->product_category_id] = $price_product_avg[$data->product_category->product_category_id] / count($total_price[$data->product_category->product_category_id]);
                }
            }
        }

        // $category_name[2] = "Durian - 名种";
        // $category_name[3] = "Durian - 杂名";

        // $total_avg_price[2] = 30;
        // $total_avg_price[3] = 10;

        return ['category_name' => $category_name, 'average_price' => $total_avg_price];
    }
}
