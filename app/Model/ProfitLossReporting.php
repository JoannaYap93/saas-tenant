<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use App\Model\SettingReportingTemplate;
use Illuminate\Database\Eloquent\Model;

class ProfitLossReporting extends Model
{
    protected $dateFormat = 'd-m-Y H:i:s';

    public static function get_sales_profit_loss_reporting($search, $search2 = false)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_sales_profit_loss_reporting_by_land($search, $search2);
        }else{

        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_total) as total_sales_category,
                        GROUP_CONCAT( DISTINCT tbl_invoice.invoice_id ) as "invoice_ids",
                        tbl_product.product_id as product_id,
                        tbl_product_category.product_category_id as product_category_id,
                        tbl_company.company_id as company_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                    WHERE tbl_company.is_display = 1
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


        if ($search2) {
            if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_invoice.invoice_date) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_invoice.invoice_date) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_product_category.product_category_name, tbl_company.company_id
                    ORDER BY tbl_product.product_ranking ASC";


        $result = DB::select($query);

        $res_arr = array();
        $total_sales = array();
        $grouped_product_category = array();
        $total_sales_company =  array();
        $total_roundup_company = array();
        $invoice_ids = array();

        if($result){
            foreach ($result as $data) {

                if (isset($res_arr[$data->company_id][$data->product_category_id])) {
                    $res_arr[$data->company_id][$data->product_category_id] += $data->total_sales_category;
                } else {
                    $res_arr[$data->company_id][$data->product_category_id] = $data->total_sales_category;

                }

                if (isset($total_sales[$data->product_category_id])) {
                    $total_sales[$data->product_category_id] += $data->total_sales_category;
                } else {
                    $total_sales[$data->product_category_id] = $data->total_sales_category;
                }

                if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }else{
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }

                if (isset( $total_sales_company[$data->company_id])) {
                    $total_sales_company[$data->company_id] += $data->total_sales_category;
                } else {
                    $total_sales_company[$data->company_id] = $data->total_sales_category;
                }

                $invoice_ids[$data->company_id][] = $data->invoice_ids;
            }

            foreach($invoice_ids as $company_id => $rows){
                $total_roundup_company[$company_id] = Invoice::query()
                                                            ->whereIn('invoice_id',explode(',',implode(',',$rows)))
                                                            ->sum('invoice_total_round_up');
            }
        }


        return ['data_sales' => $res_arr,'data_product_category' => $grouped_product_category, 'total_sales' => $total_sales,  'total_sales_company' => $total_sales_company, 'total_roundup_company' => $total_roundup_company];
        }
    }

    public static function get_sales_profit_loss_reporting_by_land($search, $search2 = false)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_total) as total_sales_category,
                        GROUP_CONCAT( DISTINCT tbl_invoice.invoice_id ) as "invoice_ids",
                        tbl_product.product_id as product_id,
                        tbl_product_category.product_category_id as product_category_id,
                        tbl_company_land.company_land_id as company_land_id,
                        tbl_company.company_id as company_id
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    WHERE tbl_company.is_display = 1
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

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land.company_land_id = {$search['company_land_id']}";
        }

        if ($search2) {
            if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_invoice.invoice_date) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_invoice.invoice_date) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_product_category.product_category_name, tbl_company.company_id
                    ORDER BY tbl_product.product_ranking ASC";


        $result = DB::select($query);

        $res_arr = array();
        $total_sales = array();
        $grouped_product_category = array();
        $total_sales_company =  array();
        $total_roundup_company = array();
        $invoice_ids = array();

        if($result){
            foreach ($result as $data) {
                if (isset($res_arr[$data->company_id][$data->product_category_id])) {
                    $res_arr[$data->company_id][$data->product_category_id] += $data->total_sales_category;
                } else {
                    $res_arr[$data->company_id][$data->product_category_id] = $data->total_sales_category;
                }

                if (isset($total_sales[$data->product_category_id])) {
                    $total_sales[$data->product_category_id] += $data->total_sales_category;
                } else {
                    $total_sales[$data->product_category_id] = $data->total_sales_category;
                }

                if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }else{
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }

                if (isset( $total_sales_company[$data->company_id])) {
                    $total_sales_company[$data->company_id] += $data->total_sales_category;
                } else {
                    $total_sales_company[$data->company_id] = $data->total_sales_category;
                }

                 $invoice_ids[$data->company_id][] = $data->invoice_ids;
            }

            foreach($invoice_ids as $company_id => $rows){
                $total_roundup_company[$company_id] = Invoice::query()
                                                            ->whereIn('invoice_id',explode(',',implode(',',$rows)))
                                                            ->sum('invoice_total_round_up');
            }
        }

        return ['data_sales' => $res_arr,'data_product_category' => $grouped_product_category, 'total_sales' => $total_sales, 'total_sales_company' => $total_sales_company, 'total_roundup_company' => $total_roundup_company];
    }

    public static function get_cost_sales_profit_loss_reporting($search, $search2 = false)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_cost_sales_profit_loss_reporting_by_land($search, $search2);
        }else{

        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_name,
                        SUM(tbl_company_expense.company_expense_total) as total_expense_category,
                        tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                        tbl_company.company_id as company_id
                    FROM tbl_company_expense
                    LEFT JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_company_expense.company_expense_status <> "deleted"';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if ($search2) {
            if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select($query);

        $grouped_expense_category_name2 = [];

        foreach($result as $data){
            if(isset($grouped_expense_category_name2[$data->setting_expense_category_id])){
                $grouped_expense_category_name2[$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }else{
                $grouped_expense_category_name2[$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }
        }

        $value_expense_category = [];

        foreach($result as $data){
            if(isset($value_expense_category[$data->company_id][$data->setting_expense_category_id])){
                $value_expense_category[$data->company_id][$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $value_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }

        $total_cost = array();

        foreach($result as $data){
            if(isset($total_cost[$data->setting_expense_category_id])){
                $total_cost[$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $total_cost[$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }


        $total_expense_company = [];

        foreach($result as $data){
            if(isset($total_expense_company[$data->company_id])){
                $total_expense_company[$data->company_id] += $data->total_expense_category;

            }else{
                $total_expense_company[$data->company_id] = $data->total_expense_category;

            }
        }

        return ['data_expense_category' => $value_expense_category, 'data_expense' => $result, 'total_cost' => $total_cost, 'total_expense_company' => $total_expense_company,  'data_last_year_expense' => $result,'expense_category_name' => $grouped_expense_category_name2];
        }
    }

    public static function get_cost_sales_profit_loss_reporting_by_land($search, $search2 = false)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_name,
                        SUM(tbl_company_expense.company_expense_total) as total_expense_category,
                        tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                        tbl_company_expense_land.company_land_id as company_land_id,
                        tbl_company.company_id as company_id
                    FROM tbl_company_expense
                    LEFT JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
                    LEFT JOIN tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_company_expense.company_expense_status <> "deleted"';

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        if ($search2) {
            if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select($query);

        $grouped_expense_category_name2 = [];

        foreach($result as $data){
            if(isset($grouped_expense_category_name2[$data->setting_expense_category_id])){
                $grouped_expense_category_name2[$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }else{
                $grouped_expense_category_name2[$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }
        }

        $value_expense_category = [];

        foreach($result as $data){
            if(isset($value_expense_category[$data->company_id][$data->setting_expense_category_id])){
                $value_expense_category[$data->company_id][$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $value_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }

        $total_cost = array();

        foreach($result as $data){
            if(isset($total_cost[$data->setting_expense_category_id])){
                $total_cost[$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $total_cost[$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }


        $total_expense_company = [];

        foreach($result as $data){
            if(isset($total_expense_company[$data->company_id])){
                $total_expense_company[$data->company_id] += $data->total_expense_category;

            }else{
                $total_expense_company[$data->company_id] = $data->total_expense_category;

            }
        }

        return ['data_expense_category' => $value_expense_category, 'data_expense' => $result, 'total_cost' => $total_cost, 'total_expense_company' => $total_expense_company,  'data_last_year_expense' => $result,'expense_category_name' => $grouped_expense_category_name2];

    }

    public static function get_cost_sales_item_profit_loss_reporting($search, $search2 = false)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_cost_sales_item_profit_loss_reporting_by_land($search, $search2);
        }else{

        $company_id = auth()->user()->company_id;
        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            sum(tbl_company_expense_item.company_expense_item_total) as total_cost_sales_item
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if ($search2) {
            if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select($query);

        $grouped_expense_item_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }else{
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }
        }

        $value_expense_item = [];

        foreach($result as $data){
            if(isset($value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id])){
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;

            }else{
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;

            }
        }

        $total_cost_each_item = array();

        foreach($result as $data){
            if(isset($total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id])){
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;

            }else{
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;

            }
        }

        return ['data_expense_item' => $value_expense_item, 'expense_item_name' => $grouped_expense_item_name, 'total_expense_each_item' => $total_cost_each_item];
        }
    }

    public static function get_cost_sales_item_profit_loss_reporting_by_land($search, $search2 = false)
    {
        $company_id = auth()->user()->company_id;
        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            tbl_company_expense_land.company_land_id as company_land_id,
            sum(tbl_company_expense_item.company_expense_item_total) as total_cost_sales_item
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        if ($search2) {
        if (isset($search['last_year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
            }
        } else {
            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select($query);

        $grouped_expense_item_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;
            }
        }

        $value_expense_item = [];

        foreach($result as $data){
            if(isset($value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id])){
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;
            }else{
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;
            }
        }

        $total_cost_each_item = array();

        foreach($result as $data){
            if(isset($total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id])){
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;
            }else{
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;
            }
        }
        return ['data_expense_item' => $value_expense_item, 'expense_item_name' => $grouped_expense_item_name, 'total_expense_each_item' => $total_cost_each_item];
    }

    public static function get_level_sel_for_pnl_reporting()
    {
        return [
            '' => 'Please Select Level View',
            '1' => 'Level 1',
            '2' => 'Level 2',
            '3' => 'Level 3',
        ];
    }

    public static function get_temp_sel_for_pnl_reporting()
    {
        $company = SettingReportingTemplate::query();
        if (auth()->user()->user_type_id == 2) {
            $company->where('setting_reporting_template_id', '!=', 3);
        }
        $result = $company->orderBy('setting_reporting_template_id')->get();
        $temp[''] = 'Please Select Template';

        foreach ($result as $value) {
            $temp[$value->setting_reporting_template_id] = $value->setting_reporting_template_name;
        }
        return $temp;
    }

    //Remark for m2m temp---------------------------
    public static function get_sales_profit_loss_reporting_month($search)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_sales_profit_loss_reporting_month_by_land($search);
        }else{

            $company_id = auth()->user()->company_id;

            $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                            SUM(tbl_invoice_item.invoice_item_total) as total_sales_category,
                            GROUP_CONCAT( DISTINCT tbl_invoice.invoice_id) as "invoice_ids",
                            tbl_product.product_id as product_id,
                            tbl_product_category.product_category_id as product_category_id,
                            tbl_company.company_id as company_id,
                            month(tbl_invoice.invoice_date) as month_num
                        FROM tbl_invoice_item
                        LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                        LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                        LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                        LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                        LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                        LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                        WHERE tbl_company.is_display = 1
                        AND tbl_invoice.invoice_status_id <> 3';

            if (isset($search['year'])) {
                $query .= " AND YEAR(tbl_invoice.invoice_date) = ".$search['year']."";
            }else{
                $query .= " AND YEAR(tbl_invoice.invoice_date) = YEAR(date('Y'))";
            }

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

            $query .= " GROUP BY tbl_product_category.product_category_name, tbl_company.company_id, tbl_invoice.invoice_date
                        ORDER BY tbl_product.product_ranking ASC";

            $result = DB::select(DB::raw($query));

            $res_arr = array();
            $sale_by_month_arr = array();
            $total_sales = array();
            $grouped_product_category = array();
            $total_sales_company =  array();
            $total_roundup_company = array();
            $invoice_ids = array();

            if($result){
                foreach ($result as $data) {
                    if (isset($res_arr[$data->company_id][$data->product_category_id])) {
                        $res_arr[$data->company_id][$data->product_category_id] += $data->total_sales_category;
                    } else {
                        $res_arr[$data->company_id][$data->product_category_id] = $data->total_sales_category;
                    }

                    if (isset($sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num])) {
                        $sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num] += $data->total_sales_category;
                    } else {
                        $sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num] = $data->total_sales_category;
                    }

                    if (isset($total_sales[$data->product_category_id])) {
                        $total_sales[$data->product_category_id] += $data->total_sales_category;
                    } else {
                        $total_sales[$data->product_category_id] = $data->total_sales_category;
                    }

                    if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                        $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                    }else{
                        $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                    }

                    if (isset( $total_sales_company[$data->company_id][$data->month_num])) {
                        $total_sales_company[$data->company_id][$data->month_num] += $data->total_sales_category;
                    } else {
                        $total_sales_company[$data->company_id][$data->month_num] = $data->total_sales_category;
                    }

                    $invoice_ids[$data->company_id][$data->month_num][] = $data->invoice_ids;
                }

                foreach($invoice_ids as $company_id => $invoice){
                    foreach($invoice as $month => $ids){
                        $total_roundup_company[$company_id][$month] = Invoice::query()
                        ->whereIn('invoice_id',explode(',',implode(',',$ids)))
                        ->sum('invoice_total_round_up');
                    }
                }
            }

            return ['data_sales' => $res_arr, 'data_product_category' => $grouped_product_category, 'data_sales_by_month' => $sale_by_month_arr, 'total_sales' => $total_sales, 'total_sales_company' => $total_sales_company, 'total_roundup_company' => $total_roundup_company];
        }
    }

    public static function get_sales_profit_loss_reporting_month_by_land($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_total) as total_sales_category,
                        GROUP_CONCAT( DISTINCT tbl_invoice.invoice_id) as "invoice_ids",
                        tbl_product.product_id as product_id,
                        tbl_product_category.product_category_id as product_category_id,
                        tbl_company_land.company_land_id as company_land_id,
                        tbl_company.company_id as company_id,
                        month(tbl_invoice.invoice_date) as month_num
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    LEFT JOIN tbl_company_land ON tbl_company_land.company_land_id = tbl_invoice.company_land_id
                    LEFT JOIN tbl_company_land_category ON tbl_company_land_category.company_land_category_id = tbl_company_land.company_land_category_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_invoice.invoice_status_id <> 3';

        if (isset($search['year'])) {
            $query .= " AND YEAR(tbl_invoice.invoice_date) = ".$search['year']."";
        }else{
            $query .= " AND YEAR(tbl_invoice.invoice_date) = YEAR(date('Y'))";
        }

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

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land.company_land_id = {$search['company_land_id']}";
        }

        $query .= " GROUP BY tbl_product_category.product_category_name, tbl_company.company_id, tbl_invoice.invoice_date
                    ORDER BY tbl_product.product_ranking ASC";


        $result = DB::select(DB::raw($query));

        $res_arr = array();
        $sale_by_month_arr = array();
        $total_sales = array();
        $grouped_product_category = array();
        $total_sales_company =  array();
        $total_roundup_company = array();
        $invoice_ids = array();


        if($result){
            foreach ($result as $data) {
                if (isset($res_arr[$data->company_id][$data->product_category_id])) {
                    $res_arr[$data->company_id][$data->product_category_id] += $data->total_sales_category;
                } else {
                    $res_arr[$data->company_id][$data->product_category_id] = $data->total_sales_category;
                }

                if (isset($sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num])) {
                    $sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num] += $data->total_sales_category;
                } else {
                    $sale_by_month_arr[$data->company_id][$data->product_category_id][$data->month_num] = $data->total_sales_category;
                }

                if (isset($total_sales[$data->product_category_id])) {
                    $total_sales[$data->product_category_id] += $data->total_sales_category;
                } else {
                    $total_sales[$data->product_category_id] = $data->total_sales_category;
                }

                if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }else{
                    $grouped_product_category[$data->product_category_id] = $data->product_category_name;
                }

                if (isset( $total_sales_company[$data->company_id][$data->month_num])) {
                    $total_sales_company[$data->company_id][$data->month_num] += $data->total_sales_category;
                } else {
                    $total_sales_company[$data->company_id][$data->month_num] = $data->total_sales_category;
                }

                $invoice_ids[$data->company_id][$data->month_num][] = $data->invoice_ids;
            }

            foreach($invoice_ids as $company_id => $invoice){
                foreach($invoice as $month => $ids){
                    $total_roundup_company[$company_id][$month] = Invoice::query()
                    ->whereIn('invoice_id',explode(',',implode(',',$ids)))
                    ->sum('invoice_total_round_up');
                }
            }
        }


        return ['data_sales' => $res_arr, 'data_product_category' => $grouped_product_category, 'data_sales_by_month' => $sale_by_month_arr, 'total_sales' => $total_sales, 'total_sales_company' => $total_sales_company, 'total_roundup_company' => $total_roundup_company];
    }

    public static function get_cost_sales_profit_loss_reporting_month($search)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_cost_sales_profit_loss_reporting_month_by_land($search);
        }else{

        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_name,
                        SUM(tbl_company_expense.company_expense_total) as total_expense_category,
                        tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                        tbl_company.company_id as company_id,
                        tbl_company_expense.company_expense_month as month_num
                    FROM tbl_company_expense
                    LEFT JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_company_expense.company_expense_status <> "deleted"';

        if (isset($search['year'])) {
            $query .= " AND tbl_company_expense.company_expense_year = ".$search['year']."";
        }else{
            $query .= " AND tbl_company_expense.company_expense_year = YEAR(date('Y'))";
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id, tbl_company_expense.company_expense_month
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select(DB::raw($query));

        $value_expense_category = [];

        foreach($result as $data){
            if(isset($value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num])){
                $value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num] += $data->total_expense_category;
            }else{
                $value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num] = $data->total_expense_category;
            }
        }

        $total_cost = array();

        foreach($result as $data){
            if(isset($total_cost[$data->company_id][$data->setting_expense_category_id])){
                $total_cost[$data->company_id][$data->setting_expense_category_id] += $data->total_expense_category;
            }else{
                $total_cost[$data->company_id][$data->setting_expense_category_id] = $data->total_expense_category;
            }
        }

        $grouped_expense_category_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_category_name[$data->setting_expense_category_id])){
                $grouped_expense_category_name[$data->setting_expense_category_id] = $data->setting_expense_category_name;
            }else{
                $grouped_expense_category_name[$data->setting_expense_category_id] = $data->setting_expense_category_name;
            }
        }

        $total_expense_company = [];

        foreach($result as $data){
            if(isset($total_expense_company[$data->company_id][$data->month_num])){
                $total_expense_company[$data->company_id][$data->month_num] += $data->total_expense_category;
            }else{
                $total_expense_company[$data->company_id][$data->month_num] = $data->total_expense_category;
            }
        }
        return ['data_expense_category' => $value_expense_category, 'data_expense' => $result, 'expense_category_name' => $grouped_expense_category_name, 'total_cost' => $total_cost, 'total_expense_company' => $total_expense_company];
        }
    }

    public static function get_cost_sales_profit_loss_reporting_month_by_land($search)
    {
        $company_id = auth()->user()->company_id;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_name,
                        SUM(tbl_company_expense.company_expense_total) as total_expense_category,
                        tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                        tbl_company_expense_land.company_land_id as company_land_id,
                        tbl_company.company_id as company_id,
                        tbl_company_expense.company_expense_month as month_num
                    FROM tbl_company_expense
                    LEFT JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
                    LEFT JOIN tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_company_expense.company_expense_status <> "deleted"';

        if (isset($search['year'])) {
            $query .= " AND tbl_company_expense.company_expense_year = ".$search['year']."";
        }else{
            $query .= " AND tbl_company_expense.company_expense_year = YEAR(date('Y'))";
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id, tbl_company_expense.company_expense_month
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";


        $result = DB::select(DB::raw($query));

        $value_expense_category = [];

        foreach($result as $data){
            if(isset($value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num])){
                $value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num] += $data->total_expense_category;

            }else{
                $value_expense_category[$data->company_id][$data->setting_expense_category_id][$data->month_num] = $data->total_expense_category;

            }
        }

        $total_cost = array();

        foreach($result as $data){
            if(isset($total_cost[$data->company_id][$data->setting_expense_category_id])){
                $total_cost[$data->company_id][$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $total_cost[$data->company_id][$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }

        $grouped_expense_category_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_category_name[$data->setting_expense_category_id])){
                $grouped_expense_category_name[$data->setting_expense_category_id] = $data->setting_expense_category_name;
            }else{
                $grouped_expense_category_name[$data->setting_expense_category_id] = $data->setting_expense_category_name;
            }
        }

        $total_expense_company = [];

        foreach($result as $data){
            if(isset($total_expense_company[$data->company_id][$data->month_num])){
                $total_expense_company[$data->company_id][$data->month_num] += $data->total_expense_category;
            }else{
                $total_expense_company[$data->company_id][$data->month_num] = $data->total_expense_category;
            }
        }
        return ['data_expense_category' => $value_expense_category, 'data_expense' => $result, 'expense_category_name' => $grouped_expense_category_name, 'total_cost' => $total_cost, 'total_expense_company' => $total_expense_company];
    }

    public static function get_cost_sales_item_profit_loss_reporting_month($search)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::get_cost_sales_item_profit_loss_reporting_month_by_land($search);
        }else{

        $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            ifnull(sum(tbl_company_expense_item.company_expense_item_total), 0) as total_cost_sales_item,
            tbl_company_expense.company_expense_month as month_num
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

        if (isset($search['year'])) {
            $query .= " AND tbl_company_expense.company_expense_year = ".$search['year']."";
        }else{
            $query .= " AND tbl_company_expense.company_expense_year = YEAR(date('Y'))";
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_month
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";

        $result = DB::select(DB::raw($query));

        $value_expense_item = [];

        foreach($result as $data){
            if(isset($value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num])){
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] += $data->total_cost_sales_item;
            }else{
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] = $data->total_cost_sales_item;
            }
        }

        $total_cost_each_item = array();

        foreach($result as $data){
            if(isset($total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num])){
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] += $data->total_cost_sales_item;
            }else{
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] = $data->total_cost_sales_item;
            }
        }

        $grouped_expense_item_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;
            }
        }

        $total_expense_cost = [];

        foreach($result as $data){
            if(isset($total_expense_cost[$data->company_id][$data->setting_expense_id])){
                $total_expense_cost[$data->company_id][$data->setting_expense_id] += $data->total_cost_sales_item;
            }else{
                $total_expense_cost[$data->company_id][$data->setting_expense_id] = $data->total_cost_sales_item;
            }
        }

        return ['data_expense_item' => $value_expense_item, 'expense_item_name' => $grouped_expense_item_name, 'total_expense_each_item' => $total_cost_each_item, 'total_expense_cost' => $total_expense_cost];
        }
    }

    public static function get_cost_sales_item_profit_loss_reporting_month_by_land($search)
    {
        $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            tbl_company_expense_land.company_land_id as company_land_id,
            ifnull(sum(tbl_company_expense_item.company_expense_item_total), 0) as total_cost_sales_item,
            tbl_company_expense.company_expense_month as month_num
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

        if (isset($search['year'])) {
            $query .= " AND tbl_company_expense.company_expense_year = ".$search['year']."";
        }else{
            $query .= " AND tbl_company_expense.company_expense_year = YEAR(date('Y'))";
        }

        if (isset($search['company_id'])) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

        } elseif(isset($search['company_cb_id'])) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_expense.company_id IN ({$ids})";

            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_expense.company_id = {$company_id}";

            } else {
                $query .= " AND tbl_company_expense.company_id <> 1";
            }
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_month
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";

        $result = DB::select(DB::raw($query));

        $value_expense_item = [];

        foreach($result as $data){
            if(isset($value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num])){
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] += $data->total_cost_sales_item;

            }else{
                $value_expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] = $data->total_cost_sales_item;

            }
        }

        $total_cost_each_item = array();

        foreach($result as $data){
            if(isset($total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num])){
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] += $data->total_cost_sales_item;

            }else{
                $total_cost_each_item[$data->setting_expense_category_id][$data->setting_expense_id][$data->month_num] = $data->total_cost_sales_item;

            }
        }

        $grouped_expense_item_name = [];

        foreach($result as $data){
            if(isset($grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }else{
                $grouped_expense_item_name[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }
        }

        $total_expense_cost = [];

        foreach($result as $data){
            if(isset($total_expense_cost[$data->company_id][$data->setting_expense_id])){
                $total_expense_cost[$data->company_id][$data->setting_expense_id] += $data->total_cost_sales_item;
            }else{
                $total_expense_cost[$data->company_id][$data->setting_expense_id] = $data->total_cost_sales_item;
            }
        }
        return ['data_expense_item' => $value_expense_item, 'expense_item_name' => $grouped_expense_item_name, 'total_expense_each_item' => $total_cost_each_item, 'total_expense_cost' => $total_expense_cost];

    }

    public static function profit_loss_reporting_detail($company_id, $setting_expense_id, $year)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::profit_loss_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year);
        }else{

        // $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense.company_id = $company_id ";
        $query .= " AND tbl_company_expense_item.setting_expense_id = $setting_expense_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";
        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id";


        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();
        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
        }
    }

    public static function profit_loss_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year)
    {
        // $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company_expense_land.company_land_id as company_land_id,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense.company_id = $company_id ";
        $query .= " AND tbl_company_expense_item.setting_expense_id = $setting_expense_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id";

        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();
        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
    }

    public static function profit_loss_y2y_reporting_detail($search, $search2 = false)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::profit_loss_y2y_reporting_detail_by_land($search, $search2 = false);
        }else{

        $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

            if (isset($search['company_id'])) {
                $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

            } elseif(isset($search['company_cb_id'])) {
                $cb_id = implode(",", $search['company_cb_id']);
                $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                    $query .= " AND tbl_company_expense.company_id IN ({$ids})";

                } else if (auth()->user()->company_id != 0) {
                    $query .= " AND tbl_company_expense.company_id = {$company_id}";

                } else {
                    $query .= " AND tbl_company_expense.company_id <> 1";
                }
            }

            if (isset($search['setting_expense_id'])) {
                $query .= " AND tbl_setting_expense.setting_expense_id = {$search['setting_expense_id']}";
            }

            if ($search2) {
                if (isset($search['last_year'])) {
                    $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
                }
            } else {
                if (isset($search['year'])) {
                    $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
                }
            }
        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id
                    ORDER BY tbl_company_expense.company_expense_id";

        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();

        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
        }
    }

    public static function profit_loss_y2y_reporting_detail_by_land($search, $search2 = false)
    {
        $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company_expense_land.company_land_id as company_land_id,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

            if (isset($search['company_id'])) {
                $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";

            } elseif(isset($search['company_cb_id'])) {
                $cb_id = implode(",", $search['company_cb_id']);
                $query .= " AND tbl_company_expense.company_id IN ({$cb_id})";

            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                    $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                    $query .= " AND tbl_company_expense.company_id IN ({$ids})";

                } else if (auth()->user()->company_id != 0) {
                    $query .= " AND tbl_company_expense.company_id = {$company_id}";

                } else {
                    $query .= " AND tbl_company_expense.company_id <> 1";
                }
            }

            if (isset($search['company_land_id'])) {
                $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
            }

            if (isset($search['setting_expense_id'])) {
                $query .= " AND tbl_setting_expense.setting_expense_id = {$search['setting_expense_id']}";
            }

            if ($search2) {
                if (isset($search['last_year'])) {
                    $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['last_year']}";
                }
            } else {
                if (isset($search['year'])) {
                    $query .= " AND YEAR(tbl_company_expense.company_expense_created) = {$search['year']}";
                }
            }
        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id
                    ORDER BY tbl_company_expense.company_expense_id";

        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();

        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
    }

    public static function profit_loss_m2m_reporting_detail($company_id, $setting_expense_id, $year, $month)
    {
        if(isset($search['company_land_id'])){
            return ProfitLossReporting::profit_loss_m2m_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year, $month);
        }else{

        // $company_id = auth()->user()->company_id;

        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense.company_id = $company_id ";
        $query .= " AND tbl_company_expense_item.setting_expense_id = $setting_expense_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";
        $query .= " AND tbl_company_expense.company_expense_month = $month";
        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id";

        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();
        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_id])) {
                $total_expense_by_setting_expense[$data->company_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
        }
    }

    public static function profit_loss_m2m_reporting_detail_by_land($search, $company_id, $setting_expense_id, $year, $month)
    {
        $query = "SELECT tbl_setting_expense.setting_expense_id,
            tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company_expense.company_expense_id,
            tbl_company_expense.company_expense_day,
            tbl_company_expense.company_expense_month,
            tbl_company_expense.company_expense_year,
            tbl_company_expense_land.company_land_id as company_land_id,
            tbl_company.company_id,
            tbl_company_expense.user_id,
            tbl_user.user_fullname,
            DATE (tbl_company_expense.company_expense_created) AS date_created,
            tbl_company_expense_item.company_expense_item_total AS total_cost
            FROM tbl_company_expense_item
            JOIN tbl_company_expense ON tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
            JOIN tbl_company_expense_land ON tbl_company_expense_land.company_expense_id = tbl_company_expense.company_expense_id
            JOIN tbl_setting_expense ON tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
            JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            JOIN tbl_user ON tbl_user.company_id = tbl_company_expense.company_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            WHERE tbl_company_expense.company_expense_status != 'deleted'";

        $query .= " AND tbl_company_expense.company_id = $company_id ";
        $query .= " AND tbl_company_expense_item.setting_expense_id = $setting_expense_id ";
        $query .= " AND tbl_company_expense.company_expense_year = $year ";
        $query .= " AND tbl_company_expense.company_expense_month = $month";

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_expense_land.company_land_id = {$search['company_land_id']}";
        }

        $query .= " GROUP BY tbl_setting_expense.setting_expense_id, tbl_company.company_id, tbl_company_expense.company_expense_id";

        $result = DB::select($query);

        // item name
        $expense_name = array();
        // farm manager
        $farm_manager_name = array();
        // date
        $date_created = array();
        // per items
        $total_expense_by_product = array();
        foreach ($result as $data)
        {
            if(isset($expense_name[$data->setting_expense_id])){
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }else{
                $expense_name[$data->setting_expense_id] = $data->setting_expense_name;
            }

            if (isset($farm_manager_name[$data->user_id][$data->company_expense_id])) {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            } else {
                $farm_manager_name[$data->user_id][$data->company_expense_id] = $data->user_fullname;
            }

            if (isset($date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id])) {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            } else {
                $date_created[$data->company_expense_day][$data->company_expense_month][$data->company_expense_year][$data->company_expense_id] = $data->date_created;
            }

            if (isset($total_expense_by_product[$data->company_expense_id])) {
                $total_expense_by_product[$data->company_expense_id] += $data->total_cost;
            } else {
                $total_expense_by_product[$data->company_expense_id] = $data->total_cost;
            }

            if (isset($total_expense_by_setting_expense[$data->company_id])) {
                $total_expense_by_setting_expense[$data->company_id] += $data->total_cost;
            } else {
                $total_expense_by_setting_expense[$data->company_id] = $data->total_cost;
            }
        }
        return ['expense_name' => $expense_name, 'farm_manager_name' => $farm_manager_name, 'date_created' => $date_created, 'total_expense_by_product' => $total_expense_by_product];
    }
}
