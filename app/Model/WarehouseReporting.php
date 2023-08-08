<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WarehouseReporting extends Model
{
    public static function get_sales_rm_report($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_company.company_id,
                tbl_invoice.invoice_grandtotal as sales_rm,
                tbl_setting_warehouse.warehouse_name as wname,
                tbl_setting_warehouse.warehouse_id,
                tbl_invoice.invoice_id,
                tbl_company.company_id as company_id
                FROM tbl_invoice
                LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                LEFT JOIN tbl_delivery_order ON tbl_delivery_order.invoice_id = tbl_invoice.invoice_id
                LEFT JOIN tbl_setting_warehouse ON tbl_setting_warehouse.warehouse_id = tbl_delivery_order.warehouse_id
                WHERE tbl_company.is_display = 1
                AND tbl_delivery_order.delivery_order_type_id = 2
                AND tbl_delivery_order.warehouse_id Is Not NULL
                AND tbl_delivery_order.delivery_order_status_id <> 3
                AND tbl_invoice.invoice_status_id <> 3';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";

            } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_invoice.company_id IN ({$cb_id})";

            } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
            $query .= " AND tbl_invoice.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
            $query .= " AND tbl_invoice.company_id = {$company_id}";

            } else {
            $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_delivery_order.delivery_order_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query .= " AND MONTH(tbl_delivery_order.delivery_order_created) = {$search['month']} ";
        }

        $result = DB::select($query);

        $wh_name = [];
        $sales_rm = [];
        foreach($result as $data){

            $wh_name[$data->company_id][$data->warehouse_id] = $data->wname;

            if(isset($sales_rm[$data->company_id][$data->warehouse_id])){
                $sales_rm[$data->company_id][$data->warehouse_id] += $data->sales_rm;
            }else{
                $sales_rm[$data->company_id][$data->warehouse_id] = $data->sales_rm;

            }
        }

        return ['sales_rm' => $sales_rm, 'wh_name' => $wh_name];
    }

    public static function get_sales_kg_report($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_company.company_id,
                tbl_delivery_order.delivery_order_total_quantity as sales_kg,
                tbl_setting_warehouse.warehouse_name as wname,
                tbl_setting_warehouse.warehouse_id,
                tbl_invoice.invoice_id,
                tbl_company.company_id as company_id
                FROM tbl_invoice
                LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                LEFT JOIN tbl_delivery_order ON tbl_delivery_order.invoice_id = tbl_invoice.invoice_id
                LEFT JOIN tbl_setting_warehouse ON tbl_setting_warehouse.warehouse_id = tbl_delivery_order.warehouse_id
                WHERE tbl_company.is_display = 1
                AND tbl_delivery_order.delivery_order_type_id = 2
                AND tbl_delivery_order.warehouse_id Is Not NULL
                AND tbl_delivery_order.delivery_order_status_id <> 3
                AND tbl_invoice.invoice_status_id <> 3';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_invoice.company_id = {$search['company_id']}";

            } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_invoice.company_id IN ({$cb_id})";

            } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
            $query .= " AND tbl_invoice.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
            $query .= " AND tbl_invoice.company_id = {$company_id}";

            } else {
            $query .= " AND tbl_invoice.company_id <> 1";
            }
        }

        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_delivery_order.delivery_order_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query .= " AND MONTH(tbl_delivery_order.delivery_order_created) = {$search['month']} ";
        }

        $result = DB::select($query);

        $wh_name = [];
        $sales_kg = [];
        foreach($result as $data){

            $wh_name[$data->company_id][$data->warehouse_id] = $data->wname;

            if(isset($sales_kg[$data->company_id][$data->warehouse_id])){
                $sales_kg[$data->company_id][$data->warehouse_id] += $data->sales_kg;
            }else{
                $sales_kg[$data->company_id][$data->warehouse_id] = $data->sales_kg;

            }
        }

        return ['sales_kg' => $sales_kg, 'wh_name' => $wh_name];
    }

    public static function get_stock_report($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_company.company_id as company_id,
                tbl_delivery_order.delivery_order_total_quantity as stock_kg,
                tbl_setting_warehouse.warehouse_name as wname,
                tbl_delivery_order.warehouse_id as warehouse_id
                FROM tbl_delivery_order
                JOIN tbl_company ON tbl_company.company_id = tbl_delivery_order.company_id
                JOIN tbl_setting_warehouse ON tbl_setting_warehouse.warehouse_id = tbl_delivery_order.warehouse_id
                WHERE tbl_delivery_order.delivery_order_type_id = 2
                AND tbl_delivery_order.delivery_order_status_id <> 3
                AND tbl_delivery_order.warehouse_id Is Not NULL
                AND tbl_company.is_display = 1';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_delivery_order.company_id = {$search['company_id']}";
        }

        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_delivery_order.delivery_order_created) = {$search['year']} ";
        }

        if (isset($search['month'])) {
            $query .= " AND MONTH(tbl_delivery_order.delivery_order_created) = {$search['month']} ";
        }

        $result = DB::select($query);

        $wh_name = [];
        $stock_kg = [];
        foreach($result as $data){

            $wh_name[$data->company_id][$data->warehouse_id] = $data->wname;

            if(isset($stock_kg[$data->company_id][$data->warehouse_id])){
                $stock_kg[$data->company_id][$data->warehouse_id] += $data->stock_kg;
            }else{
                $stock_kg[$data->company_id][$data->warehouse_id] = $data->stock_kg;

            }
        }

        return ['stock_kg' => $stock_kg, 'wh_name' => $wh_name];
    }
}
