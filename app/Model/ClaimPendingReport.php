<?php

namespace App\Model;

use App\Model\ClaimItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ClaimPendingReport extends Model
{
    public static function get_claim_pending_report($search)
    {
        $company_id = auth()->user()->company_id;

            $query = "(SELECT SUM(tbl_company_expense_item.company_expense_item_total) as total_claim,
                tbl_company_expense.worker_id as farm_manager,
                tbl_company_expense.company_id as company,
                tbl_company_expense_item.company_expense_item_detail as type,
                tbl_company_expense_item.company_expense_item_id as do_item_id,
                tbl_company_expense.company_expense_month as month
                FROM tbl_company_expense_item
                -- JOIN tbl_company on tbl_company.company_id = tbl_company_expense.company_id
                JOIN tbl_company_expense on tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
                WHERE tbl_company_expense_item.is_claim = 1 and tbl_company_expense.company_expense_status='completed'
                AND tbl_company_expense.worker_id <> 0
                AND tbl_company_expense.worker_id IS NOT NULL";

        if (isset($search['year'])) {
              // dd($search['year']);
            $query .= " AND tbl_company_expense.company_expense_year = {$search['year']}";
        }
        // dd($search['year']);

        if (@$search['company_id']) {
            $query .= " AND tbl_company_expense.company_id = {$search['company_id']}";
        // } elseif(@$search['company_cb_id']) {
        //     $cb_id = implode(",", $search['company_cb_id']);
        //     $query .= " AND tbl_company.company_id IN ({$cb_id})";
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

        $query .=" GROUP BY do_item_id, month, farm_manager, company)

            UNION

            (SELECT SUM(tbl_raw_material_company_usage.raw_material_company_usage_total_price) as total_claim,
            tbl_raw_material_company_usage.claim_worker_id as farm_manager,
            tbl_raw_material_company.company_id as company,
            tbl_raw_material_company_usage.raw_material_company_usage_type as type,
            tbl_raw_material.raw_material_id as do_item_id,
            MONTH (tbl_raw_material_company_usage.raw_material_company_usage_date) as month
            FROM tbl_raw_material_company_usage
            JOIN tbl_raw_material_company on tbl_raw_material_company.raw_material_company_id = tbl_raw_material_company_usage.raw_material_company_id
            JOIN tbl_raw_material on tbl_raw_material.raw_material_id = tbl_raw_material_company_usage.raw_material_id
            WHERE tbl_raw_material_company_usage.is_claim = 1
            AND tbl_raw_material_company_usage.claim_worker_id <> 0
            AND tbl_raw_material_company_usage.claim_worker_id IS NOT NULL";

            if (isset($search['year'])) {
                // dd($search['year']);
                $query .= " AND YEAR (tbl_raw_material_company_usage.raw_material_company_usage_date) = {$search['year']}";
            }
          // dd($search['year']);

            if (@$search['company_id']) {
                $query .= " AND tbl_raw_material_company.company_id = {$search['company_id']}";
          // } elseif(@$search['company_cb_id']) {
          //     $cb_id = implode(",", $search['company_cb_id']);
          //     $query .= " AND tbl_company.company_id IN ({$cb_id})";
            } else {
                if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_raw_material_company.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_raw_material_company.company_id = {$company_id}";
            } else {
                $query .= " AND tbl_raw_material_company.company_id <> 1";
            }
            }

        $query .=" GROUP BY do_item_id, month, farm_manager, company)";

        $result = DB::select($query);
        // dd($result);

        $claim_data_farm_manager = [];
        //////////////////////////////////////////////////////////////////////////////////////////////////

        // $query = "SELECT SUM(tbl_claim.claim_amount) as total_claim,
        //         tbl_claim.worker_id as farm_manager,
        //         tbl_claim.company_id as company,
        //         MONTH(tbl_claim.claim_start_date) as month
        //         FROM tbl_claim
        //         JOIN tbl_company ON tbl_company.company_id = tbl_claim.company_id
        //         WHERE tbl_claim.claim_status_id = 1";

        // if (isset($search['year'])) {
        //     $query .= " AND YEAR(tbl_claim.claim_start_date) = {$search['year']}";
        // }

        // if (@$search['company_id']) {
        //     $query .= " AND tbl_company.company_id = {$search['company_id']}";
        // } elseif(@$search['company_cb_id']) {
        //     $cb_id = implode(",", $search['company_cb_id']);
        //     $query .= " AND tbl_company.company_id IN ({$cb_id})";
        // } else {
        //     if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

        //         $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
        //         $query .= " AND tbl_company.company_id IN ({$ids})";
        //     } else if (auth()->user()->company_id != 0) {
        //         $query .= " AND tbl_company.company_id = {$company_id}";
        //     } else {
        //         $query .= " AND tbl_company.company_id <> 1";;
        //     }
        // }

        // $query .=" GROUP BY month, farm_manager, company";

        // $result = DB::select($query);

        // $claim_data_farm_manager = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($claim_data_farm_manager[$data->company][$data->month][$data->farm_manager])) {
                    $claim_data_farm_manager[$data->company][$data->month][$data->farm_manager] += $data->total_claim;
                }else{
                    $claim_data_farm_manager[$data->company][$data->month][$data->farm_manager] = $data->total_claim;
                }
            }
        }
        // dd($claim_data_farm_manager);

        $claim_data_company = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($claim_data_company[$data->company][$data->month])) {
                    $claim_data_company[$data->company][$data->month] += $data->total_claim;
                }else{
                    $claim_data_company[$data->company][$data->month] = $data->total_claim;
                }
            }
        }

        $total_by_month_claim_data_company = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_month_claim_data_company[$data->month])) {
                    $total_by_month_claim_data_company[$data->month] += $data->total_claim;
                }else{
                    $total_by_month_claim_data_company[$data->month] = $data->total_claim;
                }
            }
        }

        $total_by_company_claim_data_company = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_company_claim_data_company[$data->company])) {
                    $total_by_company_claim_data_company[$data->company] += $data->total_claim;
                }else{
                    $total_by_company_claim_data_company[$data->company] = $data->total_claim;
                }
            }
        }

        $total_by_month_claim_data_farm_manager = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_month_claim_data_farm_manager[$data->month])) {
                    $total_by_month_claim_data_farm_manager[$data->month] += $data->total_claim;
                }else{
                    $total_by_month_claim_data_farm_manager[$data->month] = $data->total_claim;
                }
            }
        }
        // dd($total_by_month_claim_data_farm_manager);

        $total_by_farm_manager_claim_data_farm_manager = [];

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_farm_manager_claim_data_farm_manager[$data->farm_manager])) {
                    $total_by_farm_manager_claim_data_farm_manager[$data->farm_manager] += $data->total_claim;
                }else{
                    $total_by_farm_manager_claim_data_farm_manager[$data->farm_manager] = $data->total_claim;
                }
            }
        }

        $total_by_all_company = 0;

        if ($result) {
            foreach($result as $data) {
                if (isset($total_by_all_company)) {
                    $total_by_all_company += $data->total_claim;
                }else{
                    $total_by_all_company = $data->total_claim;
                }
            }
        }
        // dd($claim_data_farm_manager,$claim_data_company,$total_by_month_claim_data_farm_manager,$total_by_farm_manager_claim_data_farm_manager,$total_by_month_claim_data_company,$total_by_company_claim_data_company, $total_by_all_company);
        return ['claim_data_farm_manager' => $claim_data_farm_manager, 'claim_data_company' => $claim_data_company, 'total_by_month_claim_data_farm_manager' => $total_by_month_claim_data_farm_manager, 'total_by_farm_manager_claim_data_farm_manager' => $total_by_farm_manager_claim_data_farm_manager, 'total_by_month_claim_data_company' => $total_by_month_claim_data_company, 'total_by_company_claim_data_company' => $total_by_company_claim_data_company, 'total_by_all_company' => $total_by_all_company];
    }

    public static function get_claim_pending_report_detail_expense($search)
    {
        // dd($search);

        $query_expense = "SELECT SUM(tbl_company_expense_item.company_expense_item_total) as total_claim_item,
        tbl_company_expense_item.setting_expense_id as setting_expense,
        tbl_company_expense.setting_expense_category_id as setting_expense_category,
        tbl_company_expense.worker_id as farm_manager,
        -- tbl_company_expense.company_id as company,
        tbl_company_expense.company_expense_month as company_expense_month
        -- tbl_company_expense.company_expense_year as company_expense_year
        FROM tbl_company_expense_item
        -- JOIN tbl_company on tbl_company.company_id = tbl_company_expense.company_id
        JOIN tbl_setting_expense on tbl_setting_expense.setting_expense_id = tbl_company_expense_item.setting_expense_id
        JOIN tbl_company_expense on tbl_company_expense.company_expense_id = tbl_company_expense_item.company_expense_id
        WHERE tbl_company_expense_item.is_claim = 1
        AND tbl_company_expense.company_expense_status != 'deleted'";

        if(isset($search['farm_manager'])){
            // $query_expense->where('tbl_company_expense.worker_id', $search['farm_manager']);
            $query_expense .= " AND tbl_company_expense.worker_id = {$search['farm_manager']}";
        }

        if(isset($search['year'])){
            $query_expense .= " AND tbl_company_expense.company_expense_year = {$search['year']}";
        }

        if(isset($search['month'])){
            $query_expense .= " AND tbl_company_expense.company_expense_month = {$search['month']}";
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // change to company_expense_item
        // $query_expense = DB::table('tbl_claim_item')
        // ->select(DB::raw("SUM(tbl_claim_item.claim_item_amount_claim)  as total_claim_item"),'tbl_company_expense_item.setting_expense_id as setting_expense', 'tbl_company_expense.setting_expense_category_id as setting_expense_category')
        // ->rightJoin('tbl_company_expense_item', function ($join) {
        //     $join->on('tbl_company_expense_item.company_expense_item_id', '=', 'tbl_claim_item.claim_item_type_value')
        //         ->where('tbl_claim_item.claim_item_type', '=', 'company_expense_item_id');
        // })
        // ->join('tbl_claim', 'tbl_claim.claim_id', '=', 'tbl_claim_item.claim_id')
        // ->join('tbl_company_expense', 'tbl_company_expense.company_expense_id', '=', 'tbl_company_expense_item.company_expense_id')
        // ->join('tbl_company', 'tbl_company.company_id', '=', 'tbl_claim.company_id')
        // ->where('tbl_claim.claim_status_id', '=', 1)
        // ->where('tbl_claim_item.is_deleted', '!=', 1)
        // ->where('tbl_claim_item.is_rejected', '!=', 1)
        // ->where('tbl_company.company_id', $search['company_id'])
        // ->whereYear('tbl_claim.claim_start_date', $search['year'])
        // ->whereMonth('tbl_claim.claim_start_date', $search['month']);

        // if(isset($search['farm_manager'])){
        //     $query_expense->where('tbl_claim.worker_id', $search['farm_manager']);
        // }

        $query_material = "SELECT SUM(tbl_raw_material_company_usage.raw_material_company_usage_total_price) as total_claim_item,
        tbl_raw_material_company_usage.claim_worker_id as farm_manager,
        tbl_raw_material_company.company_id as company,
        tbl_raw_material.raw_material_category_id as raw_material_category,
        tbl_raw_material.raw_material_id as raw_material,
        -- tbl_company_expense.company_id as company,
        -- tbl_company_expense.company_expense_month as month
        MONTH (tbl_raw_material_company_usage.raw_material_company_usage_date) as month
        -- YEAR (tbl_raw_material_company_usage.raw_material_company_usage_date) as year
        FROM tbl_raw_material_company_usage
        -- JOIN tbl_company on tbl_company.company_id = tbl_company_expense.company_id
        JOIN tbl_raw_material_company on tbl_raw_material_company.raw_material_company_id = tbl_raw_material_company_usage.raw_material_company_id
        JOIN tbl_raw_material on tbl_raw_material.raw_material_id = tbl_raw_material_company_usage.raw_material_id
        WHERE tbl_raw_material_company_usage.is_claim = 1";

        if(isset($search['farm_manager'])){
            // $query_material->where('tbl_claim.worker_id', $search['farm_manager']);
            $query_material .= " AND tbl_raw_material_company_usage.claim_worker_id = {$search['farm_manager']}";
        }

        if(isset($search['year'])){
            $query_material .= " AND YEAR (tbl_raw_material_company_usage.raw_material_company_usage_date) = {$search['year']}";
            // $query_material = DB::whereYear('tbl_raw_material_company_usage.raw_material_company_usage_date', date('Y'))->get();
            // $query_material->whereYear('tbl_raw_material_company_usage.raw_material_company_usage_date',$search['year']);
            // $result_expense = DB::select($query_expense);
        }

        if(isset($search['month'])){
            $query_material .= " AND MONTH (tbl_raw_material_company_usage.raw_material_company_usage_date) = {$search['month']}";
            // $query_material = DB::whereMonth('tbl_raw_material_company_usage.raw_material_company_usage_date', date('m'))->get();
            // $query_material->whereMonth('tbl_raw_material_company_usage.raw_material_company_usage_date',$search['month']);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // change to raw_material_company_usage
        // $query_material = DB::table('tbl_claim_item')
        // ->select(DB::raw("SUM(tbl_claim_item.claim_item_amount_claim)  as total_claim_item"),'tbl_raw_material_company_usage.raw_material_id as raw_material', 'tbl_raw_material.raw_material_category_id as raw_material_category')
        // ->rightJoin('tbl_raw_material_company_usage', function ($join) {
        //     $join->on('tbl_raw_material_company_usage.raw_material_company_usage_id', '=', 'tbl_claim_item.claim_item_type_value')
        //         ->where('tbl_claim_item.claim_item_type', '=', 'raw_material_company_usage_id');
        // })
        // ->join('tbl_claim', 'tbl_claim.claim_id', '=', 'tbl_claim_item.claim_id')
        // ->join('tbl_raw_material', 'tbl_raw_material.raw_material_id', '=', 'tbl_raw_material_company_usage.raw_material_id')
        // ->join('tbl_company', 'tbl_company.company_id', '=', 'tbl_claim.company_id')
        // ->where('tbl_claim.claim_status_id', '=', 1)
        // ->where('tbl_claim_item.is_deleted', '!=', 1)
        // ->where('tbl_claim_item.is_rejected', '!=', 1)
        // ->where('tbl_company.company_id', $search['company_id'])
        // ->whereYear('tbl_claim.claim_start_date', $search['year'])
        // ->whereMonth('tbl_claim.claim_start_date', $search['month']);

        // if(isset($search['farm_manager'])){
        //     $query_material->where('tbl_claim.worker_id', $search['farm_manager']);
        // }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $query_expense_manually = DB::table('tbl_claim_item')
        // ->select(DB::raw("SUM(tbl_claim_item.claim_item_amount_claim)  as total_claim_item"), 'tbl_claim_item.claim_item_type_value as setting_expense_category')
        // ->rightJoin('tbl_setting_expense_category', function ($join) {
        //     $join->on('tbl_setting_expense_category.setting_expense_category_id', '=', 'tbl_claim_item.claim_item_type_value')
        //         ->where('tbl_claim_item.claim_item_type', '=', 'manually_company_expense_item_category_id');
        // })
        // ->join('tbl_claim', 'tbl_claim.claim_id', '=', 'tbl_claim_item.claim_id')
        // ->join('tbl_company', 'tbl_company.company_id', '=', 'tbl_claim.company_id')
        // ->where('tbl_claim.claim_status_id', '=', 1)
        // ->where('tbl_claim_item.is_deleted', '!=', 1)
        // ->where('tbl_claim_item.is_rejected', '!=', 1)
        // ->where('tbl_company.company_id', $search['company_id'])
        // ->whereYear('tbl_claim.claim_start_date', $search['year'])
        // ->whereMonth('tbl_claim.claim_start_date', $search['month']);

        // if(isset($search['farm_manager'])){
        //     $query_expense_manually->where('tbl_claim.worker_id', $search['farm_manager']);
        // }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $query_material_manually = DB::table('tbl_claim_item')
        // ->select(DB::raw("SUM(tbl_claim_item.claim_item_amount_claim)  as total_claim_item"), 'tbl_claim_item.claim_item_type_value as raw_material_category')
        // ->rightJoin('tbl_raw_material_category', function ($join) {
        //     $join->on('tbl_raw_material_category.raw_material_category_id', '=', 'tbl_claim_item.claim_item_type_value')
        //         ->where('tbl_claim_item.claim_item_type', '=', 'manually_raw_material_company_usage_category_id');
        // })
        // ->join('tbl_claim', 'tbl_claim.claim_id', '=', 'tbl_claim_item.claim_id')
        // ->join('tbl_company', 'tbl_company.company_id', '=', 'tbl_claim.company_id')
        // ->where('tbl_claim.claim_status_id', '=', 1)
        // ->where('tbl_claim_item.is_deleted', '!=', 1)
        // ->where('tbl_claim_item.is_rejected', '!=', 1)
        // ->where('tbl_company.company_id', $search['company_id'])
        // ->whereYear('tbl_claim.claim_start_date', $search['year'])
        // ->whereMonth('tbl_claim.claim_start_date', $search['month']);

        // if(isset($search['farm_manager'])){
        //     $query_material_manually->where('tbl_claim.worker_id', $search['farm_manager']);
        // }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // $query_expense->groupBy('setting_expense', 'setting_expense_category');
        // $query_material->groupBy('raw_material', 'raw_material_category');
        // $query_expense_manually->groupBy('setting_expense_category');
        // $query_material_manually->groupBy('raw_material_category');

        $query_expense .= " GROUP BY tbl_company_expense_item.setting_expense_id, tbl_company_expense.setting_expense_category_id";
        $query_material .= " GROUP BY tbl_raw_material.raw_material_id, tbl_raw_material.raw_material_category_id";
        // $result_expense = $query_expense->get();
        $result_expense = DB::select($query_expense);
        // dd($result_expense);
        // $result_material = $query_material->get();
        $result_material = DB::select($query_material);
        // dd($result_material);
        // dd($result_material,$result_expense);
        // $result_expense_manually = $query_expense_manually->get();
        // $result_material_manually = $query_material_manually->get();

        $total_by_expense_category = [];
        $total_by_material_category = [];

        $claim_item_expense = [];

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (isset($claim_item_expense[$data->setting_expense_category][$data->setting_expense])) {
                    $claim_item_expense[$data->setting_expense_category][$data->setting_expense] += $data->total_claim_item;
                }else{
                    $claim_item_expense[$data->setting_expense_category][$data->setting_expense] = $data->total_claim_item;
                }
                if (@$total_by_expense_category[$data->setting_expense_category]) {
                    $total_by_expense_category[$data->setting_expense_category] += $data->total_claim_item;
                } else {
                    $total_by_expense_category[$data->setting_expense_category] = $data->total_claim_item;
                }

            }
        }

        $claim_item_material = [];

        if ($result_material) {
            foreach($result_material as $data) {
                if (isset($claim_item_material[$data->raw_material_category][$data->raw_material])) {
                    $claim_item_material[$data->raw_material_category][$data->raw_material] += $data->total_claim_item;
                }else{
                    $claim_item_material[$data->raw_material_category][$data->raw_material] = $data->total_claim_item;
                }
                if (@$total_by_material_category[$data->raw_material_category]) {
                    $total_by_material_category[$data->raw_material_category] += $data->total_claim_item;
                } else {
                    $total_by_material_category[$data->raw_material_category] = $data->total_claim_item;
                }
            }
        }

        // $claim_item_expense_manually = [];

        // if ($result_expense_manually) {
        //     foreach($result_expense_manually as $data) {
        //         if (isset($claim_item_expense_manually[$data->setting_expense_category]['others'])) {
        //             $claim_item_expense_manually[$data->setting_expense_category]['others'] += $data->total_claim_item;
        //         }else{
        //             $claim_item_expense_manually[$data->setting_expense_category]['others'] = $data->total_claim_item;
        //         }
        //         if (@$total_by_expense_category[$data->setting_expense_category]) {
        //             $total_by_expense_category[$data->setting_expense_category] += $data->total_claim_item;
        //         } else {
        //             $total_by_expense_category[$data->setting_expense_category] = $data->total_claim_item;
        //         }
        //     }
        // }

        // $claim_item_material_manually = [];

        // if ($result_material_manually) {
        //     foreach($result_material_manually as $data) {
        //         if (isset($claim_item_material_manually[$data->raw_material_category]['others'])) {
        //             $claim_item_material_manually[$data->raw_material_category]['others'] += $data->total_claim_item;
        //         }else{
        //             $claim_item_material_manually[$data->raw_material_category]['others'] = $data->total_claim_item;
        //         }
        //         if (@$total_by_material_category[$data->raw_material_category]) {
        //             $total_by_material_category[$data->raw_material_category] += $data->total_claim_item;
        //         } else {
        //             $total_by_material_category[$data->raw_material_category] = $data->total_claim_item;
        //         }
        //     }
        // }

        $total_claim_item_expense = 0;

        if ($result_expense) {
            foreach($result_expense as $data) {
                if (isset($total_claim_item_expense)) {
                    $total_claim_item_expense += $data->total_claim_item;
                }else{
                    $total_claim_item_expense = $data->total_claim_item;
                }
            }
        }

        $total_claim_item_material = 0;

        if ($result_material) {
            foreach($result_material as $data) {
                if (isset($total_claim_item_material)) {
                    $total_claim_item_material += $data->total_claim_item;
                }else{
                    $total_claim_item_material = $data->total_claim_item;
                }
            }
        }

        // $total_claim_item_expense_manually = 0;

        // if ($result_expense_manually) {
        //     foreach($result_expense_manually as $data) {
        //         if (isset($total_claim_item_expense_manually)) {
        //             $total_claim_item_expense_manually += $data->total_claim_item;
        //         }else{
        //             $total_claim_item_expense_manually = $data->total_claim_item;
        //         }
        //     }
        // }

        // $total_claim_item_material_manually = 0;

        // if ($result_material_manually) {
        //     foreach($result_material_manually as $data) {
        //         if (isset($total_claim_item_material_manually)) {
        //             $total_claim_item_material_manually += $data->total_claim_item;
        //         }else{
        //             $total_claim_item_material_manually = $data->total_claim_item;
        //         }
        //     }
        // }

        $total_claim_item = 0;
        // $total_claim_item = $total_claim_item_material + $total_claim_item_expense + $total_claim_item_material_manually + $total_claim_item_expense_manually;
        $total_claim_item = $total_claim_item_material + $total_claim_item_expense;
        // return ['claim_item_expense' => $claim_item_expense, 'claim_item_material' => $claim_item_material, 'claim_item_expense_manually' => $claim_item_expense_manually, 'claim_item_material_manually' => $claim_item_material_manually, 'total_claim_item' => $total_claim_item, 'total_by_expense_category' => $total_by_expense_category, 'total_by_material_category' => $total_by_material_category];
        return ['claim_item_expense' => $claim_item_expense, 'claim_item_material' => $claim_item_material, 'total_claim_item' => $total_claim_item, 'total_by_expense_category' => $total_by_expense_category, 'total_by_material_category' => $total_by_material_category];
    }
}
