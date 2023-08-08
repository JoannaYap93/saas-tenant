<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TreePointerReporting extends Model 
{

    public static function record_tree_age_pointer_report($search)
    {
        $company_id = auth()->user()->company_id;
        $result = [];

        $query ='SELECT tbl_company.company_name, 
                tbl_company.company_id, 
                tbl_company_land.company_land_name, 
                tbl_company_land.company_land_id,
                tbl_product.product_name,
                tbl_product.product_id,
                tbl_setting_tree_age.setting_tree_age,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_value,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_id,
                count(tbl_company_land_tree.product_id) as no_of_tree
                FROM tbl_company_land_tree
                join tbl_company_land on tbl_company_land.company_land_id = tbl_company_land_tree.company_land_id
                join tbl_setting_tree_age_pointer ON tbl_setting_tree_age_pointer.product_id = tbl_company_land_tree.product_id
                join tbl_setting_tree_age ON tbl_setting_tree_age.setting_tree_age_id = tbl_setting_tree_age_pointer.setting_tree_age_id
                join tbl_company on tbl_company.company_id = tbl_company_land.company_id
                join tbl_product on tbl_product.product_id = tbl_company_land_tree.product_id
                join tbl_company_land_zone on tbl_company_land_zone.company_land_zone_id = tbl_company_land_tree.company_land_zone_id
                WHERE tbl_company_land_tree.company_land_tree_status = "alive" && tbl_product.is_deleted = 0
                && tbl_company.is_display = 1 && tbl_company_land_zone.is_delete = 0';

        if (@$search['company_id']) {
            $query .= " AND tbl_company_land.company_id = {$search['company_id']}";
        } elseif(@$search['company_cb_id']) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_land.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_land.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_land.company_id = {$company_id}";
            } else {
                $query .= " AND tbl_company_land.company_id <> 1";;
            }
        }

        if (isset($search['setting_tree_age_upper'])) {
            $query .= " AND tbl_setting_tree_age.setting_tree_age <= {$search['setting_tree_age_upper']}";
        }

        if (isset($search['setting_tree_age_lower'])) {
            $query .= " AND tbl_setting_tree_age.setting_tree_age >= {$search['setting_tree_age_lower']}";
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land.company_land_id = {$search['company_land_id']}";
        }

        if(isset($search['product_category_id'])) {
            $query .= " AND tbl_product.product_category_id I= {$search['product_cateogry_id']}";
        }

        if(isset($search['product_id'])) {
            $query .= " AND tbl_company_land_tree.product_id = {$search['product_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_company_land_tree.product_id IN ({$p_id})";
        }

        if(isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_company_land.company_land_id IN ({$cl_id})";
        }

        $query .=" GROUP BY tbl_company.company_name, tbl_company_land_tree.company_land_id, tbl_setting_tree_age.setting_tree_age_id
                    ORDER BY tbl_product.product_ranking ASC";

        $data = DB::select($query);

        foreach ($data as $key => $rows) {

            $result['data'][$rows->company_id][$rows->company_land_id][$rows->product_id] = (array)$rows;

            $result['company'][$rows->company_id] = $rows->company_name;
            $result['company_land'][$rows->company_id][$rows->company_land_id] = $rows->company_land_name;
        }
        return $result;
    }  

    public static function record_pointer_tree_age_pointer_report($search)
    {
        $company_id = auth()->user()->company_id;
        $result = [];

        $query ='SELECT tbl_company.company_name, 
                tbl_company.company_id, 
                tbl_company_land.company_land_name, 
                tbl_company_land.company_land_id,
                tbl_product.product_name,
                tbl_product.product_id,
                tbl_company_land_tree.company_land_tree_year,
                tbl_setting_tree_age.setting_tree_age,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_value,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_id,
                (YEAR(NOW()) - tbl_company_land_tree.company_land_tree_year) as age,
                count(tbl_company_land_tree.product_id) as no_of_tree
                FROM tbl_company_land_tree
                join tbl_company_land on tbl_company_land.company_land_id = tbl_company_land_tree.company_land_id
                join tbl_setting_tree_age_pointer ON tbl_setting_tree_age_pointer.product_id = tbl_company_land_tree.product_id
                join tbl_setting_tree_age ON tbl_setting_tree_age.setting_tree_age_id = tbl_setting_tree_age_pointer.setting_tree_age_id
                join tbl_company on tbl_company.company_id = tbl_company_land.company_id
                join tbl_product on tbl_product.product_id = tbl_company_land_tree.product_id
                join tbl_company_land_zone on tbl_company_land_zone.company_land_zone_id = tbl_company_land_tree.company_land_zone_id
                WHERE tbl_company_land_tree.company_land_tree_status = "alive" && tbl_product.is_deleted = 0
                && tbl_company.is_display = 1 && tbl_company_land_zone.is_delete = 0';

        if (@$search['company_id']) {
            $query .= " AND tbl_company_land.company_id = {$search['company_id']}";
        } elseif(@$search['company_cb_id']) {
            $cb_id = implode(",", $search['company_cb_id']);
            $query .= " AND tbl_company_land.company_id IN ({$cb_id})";
        } else {
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {

                $ids = implode(",", auth()->user()->user_company->pluck('company_id')->toArray());
                $query .= " AND tbl_company_land.company_id IN ({$ids})";
            } else if (auth()->user()->company_id != 0) {
                $query .= " AND tbl_company_land.company_id = {$company_id}";
            } else {
                $query .= " AND tbl_company_land.company_id <> 1";;
            }
        }

        if (isset($search['setting_tree_age_upper'])) {
            $query .= " AND tbl_setting_tree_age.setting_tree_age <= {$search['setting_tree_age_upper']}";
        }

        if (isset($search['setting_tree_age_lower'])) {
            $query .= " AND tbl_setting_tree_age.setting_tree_age >= {$search['setting_tree_age_lower']}";
        }

        if (isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land.company_land_id = {$search['company_land_id']}";
        }

        if(isset($search['product_category_id'])) {
            $query .= " AND tbl_product.product_category_id I= {$search['product_cateogry_id']}";
        }

        if(isset($search['product_id'])) {
            $query .= " AND tbl_company_land_tree.product_id = {$search['product_id']}";
        }

        if(isset($search['product_cb_id'])) {
            $p_id = implode(",", $search['product_cb_id']);
            $query .= " AND tbl_company_land_tree.product_id IN ({$p_id})";
        }

        if(isset($search['company_land_cb_id'])) {
            $cl_id = implode(",", $search['company_land_cb_id']);
            $query .= " AND tbl_company_land.company_land_id IN ({$cl_id})";
        }

        $query .=" GROUP BY tbl_company_land_tree.company_land_id, tbl_company_land_tree.product_id,age, tbl_setting_tree_age_pointer.setting_tree_age_id
                    ORDER BY tbl_product.product_ranking ASC";

        $data = DB::select($query);

        $value = [];

        foreach ($data as $key => $rows) {

            if($rows->age > 30){
                if($rows->setting_tree_age == 30){
                    $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age] = $rows->no_of_tree * $rows->setting_tree_age_pointer_value;
                }
            }else if($rows->age < 1){
                if($rows->setting_tree_age == 1){
                    $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age] = $rows->no_of_tree * $rows->setting_tree_age_pointer_value;
                }
            }else{
                if($rows->setting_tree_age == $rows->age){
                $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age] = $rows->no_of_tree * $rows->setting_tree_age_pointer_value;
                }
            }
        }

        foreach ($data as $key => $rows) {

            if($rows->age > 30){
                if($rows->setting_tree_age == 30){
                    if (isset($result['pointer'][$rows->company_id][$rows->company_land_id])) {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }

                    if (isset($result['total_pointer'][$rows->company_id])) {
                        $result['total_pointer'][$rows->company_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['total_pointer'][$rows->company_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }
                }
            }else if($rows->age < 1){
                if($rows->setting_tree_age == 1){
                    if (isset($result['pointer'][$rows->company_id][$rows->company_land_id])) {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }

                    if (isset($result['total_pointer'][$rows->company_id])) {
                        $result['total_pointer'][$rows->company_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['total_pointer'][$rows->company_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }
                }
            }else{
                if($rows->setting_tree_age == $rows->age){
                    if (isset($result['pointer'][$rows->company_id][$rows->company_land_id])) {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['pointer'][$rows->company_id][$rows->company_land_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }

                    if (isset($result['total_pointer'][$rows->company_id])) {
                        $result['total_pointer'][$rows->company_id] += $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    } else {
                        $result['total_pointer'][$rows->company_id] = $value['fix_pointer'][$rows->company_id][$rows->company_land_id][$rows->product_id][$rows->age];
                    }
                }
            }
        }
        return $result;
    }

    public static function record_pointer_tree_age_pointer_report_details($search){
        $query ='SELECT tbl_company_land.company_land_id,
                tbl_company_land.company_land_name,
                tbl_product.product_id,
                tbl_product.product_name,
                tbl_setting_tree_age_pointer.setting_tree_age_id,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_value,
                (YEAR(NOW()) - tbl_company_land_tree.company_land_tree_year) as age,
                count(tbl_company_land_tree.product_id) as no_of_tree
                FROM tbl_company_land_tree
                join tbl_company_land on tbl_company_land.company_land_id = tbl_company_land_tree.company_land_id
                join tbl_setting_tree_age_pointer on tbl_setting_tree_age_pointer.product_id = tbl_company_land_tree.product_id
                join tbl_product on tbl_product.product_id = tbl_company_land_tree.product_id
                join tbl_company_land_zone on tbl_company_land_zone.company_land_zone_id = tbl_company_land_tree.company_land_zone_id
                join tbl_company on tbl_company.company_id = tbl_company_land.company_id
                WHERE tbl_company_land_tree.company_land_tree_status = "alive" && tbl_product.is_deleted = 0
                && tbl_company.is_display = 1 && tbl_company_land_zone.is_delete = 0';

        if(isset($search['company_id'])){
            $query .= " AND tbl_company_land.company_id = {$search['company_id']}";
        }

        if(isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land_tree.company_land_id = {$search['company_land_id']}";
        }

        if (isset($search['setting_tree_age_upper'])) {
            $query .= " AND tbl_setting_tree_age_pointer.setting_tree_age_id <= {$search['setting_tree_age_upper']}";
        }

        if (isset($search['setting_tree_age_lower'])) {
            $query .= " AND tbl_setting_tree_age_pointer.setting_tree_age_id >= {$search['setting_tree_age_lower']}";
        }

        $query .=" GROUP BY tbl_company_land_tree.company_land_id, tbl_company_land_tree.product_id, age, tbl_setting_tree_age_pointer.setting_tree_age_id
        ORDER BY tbl_product.product_ranking ASC";

        $data = DB::select($query);
        // dd($query);

        $result = [];
        $all_age = [];
        $total_quantity = [];
        $total = [];
        $total_quantity_by_product = [];
        $total_pointer_by_product = [];
        $grand_quantity = 0.00;
        $grand_pointer = 0.00;
        $sum = [];
        $sum2 = [];

        foreach ($data as $key => $rows) {
            if($rows->age >= 30){
                if($rows->setting_tree_age_id == 30){
                    if(isset($sum[$rows->product_id])){
                        $sum[$rows->product_id] += $rows->no_of_tree;
                    }else{
                        $sum[$rows->product_id] = $rows->no_of_tree;
                    }
                    $result[$rows->product_id][30] = [
                        "pointer" => $rows->setting_tree_age_pointer_value,
                        "quantity" => $sum[$rows->product_id],
                        "total" => $rows->setting_tree_age_pointer_value * $sum[$rows->product_id],
                    ];

                    if(isset($total_quantity[30])){
                        $total_quantity[30] += $rows->no_of_tree;
                    }else{
                        $total_quantity[30] = $rows->no_of_tree;
                    }

                    if(isset($total[30])){
                        $total[30] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total[30] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($total_quantity_by_product[$rows->product_id])){
                        $total_quantity_by_product[$rows->product_id] += $rows->no_of_tree;
                    }else{
                        $total_quantity_by_product[$rows->product_id] = $rows->no_of_tree;
                    }

                    if(isset($total_pointer_by_product[$rows->product_id])){
                        $total_pointer_by_product[$rows->product_id] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total_pointer_by_product[$rows->product_id] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($grand_quantity)){
                        $grand_quantity += $rows->no_of_tree;
                    }else{
                        $grand_quantity = $rows->no_of_tree;
                    }

                    if(isset($grand_pointer)){
                        $grand_pointer += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $grand_pointer = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }
                }
            }else if($rows->age <= 1){
                if($rows->setting_tree_age_id == 1){
                    if(isset($sum2[$rows->product_id])){
                        $sum2[$rows->product_id] += $rows->no_of_tree;
                    }else{
                        $sum2[$rows->product_id] = $rows->no_of_tree;
                    }
                    $result[$rows->product_id][$rows->age] = [
                        "pointer" => $rows->setting_tree_age_pointer_value,
                        "quantity" => $sum2[$rows->product_id],
                        "total" => $rows->setting_tree_age_pointer_value * $sum2[$rows->product_id],
                    ];

                    if(isset($total_quantity[1])){
                        $total_quantity[1] += $rows->no_of_tree;
                    }else{
                        $total_quantity[1] = $rows->no_of_tree;
                    }

                    if(isset($total[1])){
                        $total[1] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total[1] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($total_quantity_by_product[$rows->product_id])){
                        $total_quantity_by_product[$rows->product_id] += $rows->no_of_tree;
                    }else{
                        $total_quantity_by_product[$rows->product_id] = $rows->no_of_tree;
                    }

                    if(isset($total_pointer_by_product[$rows->product_id])){
                        $total_pointer_by_product[$rows->product_id] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total_pointer_by_product[$rows->product_id] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($grand_quantity)){
                        $grand_quantity += $rows->no_of_tree;
                    }else{
                        $grand_quantity = $rows->no_of_tree;
                    }

                    if(isset($grand_pointer)){
                        $grand_pointer += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $grand_pointer = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }
                }
            }else{
                if($rows->age == $rows->setting_tree_age_id){
                    $result[$rows->product_id][$rows->age] = [
                        "pointer" => $rows->setting_tree_age_pointer_value,
                        "quantity" => $rows->no_of_tree,
                        "total" => $rows->setting_tree_age_pointer_value * $rows->no_of_tree,
                    ];

                    if(isset($total_quantity[$rows->age])){
                        $total_quantity[$rows->age] += $rows->no_of_tree;
                    }else{
                        $total_quantity[$rows->age] = $rows->no_of_tree;
                    }

                    if(isset($total[$rows->age])){
                        $total[$rows->age] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total[$rows->age] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($total_quantity_by_product[$rows->product_id])){
                        $total_quantity_by_product[$rows->product_id] += $rows->no_of_tree;
                    }else{
                        $total_quantity_by_product[$rows->product_id] = $rows->no_of_tree;
                    }

                    if(isset($total_pointer_by_product[$rows->product_id])){
                        $total_pointer_by_product[$rows->product_id] += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $total_pointer_by_product[$rows->product_id] = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }

                    if(isset($grand_quantity)){
                        $grand_quantity += $rows->no_of_tree;
                    }else{
                        $grand_quantity = $rows->no_of_tree;
                    }

                    if(isset($grand_pointer)){
                        $grand_pointer += $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }else{
                        $grand_pointer = $rows->setting_tree_age_pointer_value * $rows->no_of_tree;
                    }
                }
            }

            if(isset($all_age[$rows->product_id][$rows->setting_tree_age_id])){
                $all_age[$rows->product_id][$rows->setting_tree_age_id] = $rows->setting_tree_age_pointer_value;
            }else{
                $all_age[$rows->product_id][$rows->setting_tree_age_id] = $rows->setting_tree_age_pointer_value;
            }
        }

        return ['records' => $result, 'all_age' => $all_age, 'total_quantity' => $total_quantity, 'total' => $total, 'total_quantity_by_product' =>  $total_quantity_by_product, 'total_pointer_by_product' => $total_pointer_by_product, 'grand_quantity' => $grand_quantity, 'grand_pointer' => $grand_pointer];
    }

    public static function record_pointer_tree_age_pointer_report_details_get_product($search){
        $query ='SELECT tbl_company_land.company_land_id,
                tbl_company_land.company_land_name,
                tbl_product.product_id,
                tbl_product.product_name,
                tbl_setting_tree_age_pointer.setting_tree_age_id,
                tbl_setting_tree_age_pointer.setting_tree_age_pointer_value
                FROM tbl_company_land_tree
                join tbl_company_land on tbl_company_land.company_land_id = tbl_company_land_tree.company_land_id
                join tbl_setting_tree_age_pointer on tbl_setting_tree_age_pointer.product_id = tbl_company_land_tree.product_id
                join tbl_product on tbl_product.product_id = tbl_company_land_tree.product_id
                join tbl_company_land_zone on tbl_company_land_zone.company_land_zone_id = tbl_company_land_tree.company_land_zone_id
                join tbl_company on tbl_company.company_id = tbl_company_land.company_id
                WHERE tbl_company_land_tree.company_land_tree_status = "alive" && tbl_product.is_deleted = 0
                && tbl_company.is_display = 1 && tbl_company_land_zone.is_delete = 0';

        if(isset($search['company_land_id'])) {
            $query .= " AND tbl_company_land_tree.company_land_id = {$search['company_land_id']}";
        }

        $query .=" GROUP BY tbl_company_land_tree.company_land_id, tbl_company_land_tree.product_id, tbl_setting_tree_age_pointer.setting_tree_age_id
        ORDER BY tbl_product.product_ranking ASC";

        $data = DB::select($query);
        $products = [];

        foreach ($data as $key => $rows) {
            if (isset($products[$rows->product_id])) {
                $products[$rows->product_id] = $rows->product_name;
            } else {
                $products[$rows->product_id] = $rows->product_name;
            }
        }

        return $products;
    }
}
