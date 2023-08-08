<?php

namespace App\Model;

use App\Model\Company;
use App\Model\BudgetEstimatedLog;
use App\Model\BudgetEstimatedItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BudgetEstimated extends Model
{
    protected $table = 'tbl_budget_estimated';
    protected $primaryKey = 'budget_estimated_id';
    const CREATED_AT = 'budget_estimated_created';
    const UPDATED_AT = 'budget_estimated_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'budget_estimated_title',
        'budget_estimated_year',
        'company_id',
        'budget_estimated_amount',
        'is_deleted'
    ];

    public static function get_budget_estimated($search)
    {
        $query = BudgetEstimated::query()->where('is_deleted', '<>', 1);

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }
        
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);

        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        } else {
            $query->where('company_id', '<>', 1);
        }

        if (@$search['year']) {
            $query->where('budget_estimated_year', $search['year']);
        }

        $result = $query->paginate(10);

        return $result;
    }

    public static function get_sales_for_report($search, $budget_estimated_id)
    {

        $data = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $company_id = $data->company_id;
        $year = $data->budget_estimated_year;

        $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                        SUM(tbl_invoice_item.invoice_item_total) as total_sales_item,
                        tbl_product.product_id as product_id,
                        tbl_product.product_name as product_name,
                        tbl_product.product_id as product_id,
                        tbl_product_category.product_category_id as product_category_id,
                        tbl_product_category.product_category_name as product_category_name,
                        tbl_invoice.company_id as company_id,
                        tbl_company.company_name as company_name,
                        month(tbl_invoice.invoice_date) as month_num
                    FROM tbl_invoice_item
                    LEFT JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_item.invoice_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_invoice_item.product_id
                    LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_invoice.company_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_invoice.invoice_status_id <> 3';

        if (isset($company_id)) {
            $query .= " AND tbl_invoice.company_id = $company_id";
        }

        if (isset($year)) {
            $query .= " AND YEAR(tbl_invoice.invoice_date) = $year";
        }

        if (isset($search['month'])) {
            $query .= " AND MONTH(tbl_invoice.invoice_date) = {$search['month']}";
        }

        $query .= " GROUP BY product_name, tbl_company.company_id
                    ORDER BY tbl_product.product_ranking ASC";

        $result = DB::select($query);

        $sales = array();
        foreach ($result as $data) {
            if (isset($sales[$data->company_id][$data->product_category_id][$data->product_id])) {
                $sales[$data->company_id][$data->product_category_id][$data->product_id] += $data->total_sales_item;

            } else {
                $sales[$data->company_id][$data->product_category_id][$data->product_id] = $data->total_sales_item;

            }
        }

        $total_sales_category = array();
        foreach ($result as $data) {
            if (isset($total_sales_category[$data->company_id][$data->product_category_id])) {
                $total_sales_category[$data->company_id][$data->product_category_id] += $data->total_sales_item;

            } else {
                $total_sales_category[$data->company_id][$data->product_category_id] = $data->total_sales_item;

            }
        }

        $grouped_product_category = array();

        foreach($result as $data){
            if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                $grouped_product_category[$data->company_id][$data->product_category_id] = $data->product_category_name;

            }else{
                $grouped_product_category[$data->company_id][$data->product_category_id] = $data->product_category_name;

            }
        }

        $grouped_product_name = array();

        foreach($result as $data){
            if(isset($grouped_product_name[$data->company_id][$data->product_category_id])){
                $grouped_product_name[$data->company_id][$data->product_category_id][$data->product_id] = $data->product_name;

            }else{
                $grouped_product_name[$data->company_id][$data->product_category_id][$data->product_id] = $data->product_name;

            }
        }

        return ['sales' => $sales, 'total_sales_category' => $total_sales_category, 'grouped_product_category' => $grouped_product_category, 'grouped_product_name' => $grouped_product_name];
    }

    public static function get_expense_for_report($search, $budget_estimated_id)
    {

        $data = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $company_id = $data->company_id;
        $year = $data->budget_estimated_year;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_name,
                        SUM(tbl_company_expense.company_expense_total) as total_expense_category,
                        tbl_setting_expense_category.setting_expense_category_id as setting_expense_category_id,
                        tbl_company.company_id as company_id
                    FROM tbl_company_expense
                    LEFT JOIN tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
                    WHERE tbl_company.is_display = 1
                    AND tbl_company_expense.company_expense_status <> "deleted"';

        if (isset($company_id)) {
            $query .= " AND tbl_company_expense.company_id = $company_id";
        }

        if (isset($year)) {
            $query .= " AND tbl_company_expense.company_expense_year = $year";
        }

        if (isset($search['month'])) {
            $query .= " AND tbl_company_expense.company_expense_month = {$search['month']}";
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";

        $result = DB::select($query);

        $expense_category = [];

        foreach($result as $data){
            if(isset($expense_category[$data->company_id][$data->setting_expense_category_id])){
                $expense_category[$data->company_id][$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $expense_category[$data->company_id][$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }

        $grouped_expense_category = [];

        foreach($result as $data){
            if(isset($grouped_expense_category[$data->setting_expense_category_id])){
                $grouped_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }else{
                $grouped_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }
        }

        $total_expense_category = [];

        foreach($result as $data){
            if(isset($total_expense_category[$data->setting_expense_category_id])){
                $total_expense_category[$data->setting_expense_category_id] += $data->total_expense_category;

            }else{
                $total_expense_category[$data->setting_expense_category_id] = $data->total_expense_category;

            }
        }

        return ['expense_category' => $expense_category, 'grouped_expense_category' => $grouped_expense_category, 'total_expense_category' => $total_expense_category];

    }

    public static function get_expense_item_for_report($search, $budget_estimated_id)
    {

        $data = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $company_id = $data->company_id;
        $year = $data->budget_estimated_year;

        $query = "SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            ifnull(sum(tbl_company_expense_item.company_expense_item_total), 0) as total_cost_sales_item
            from tbl_company_expense
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_company_expense.setting_expense_category_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_category_id= tbl_company_expense.setting_expense_category_id
            JOIN tbl_company ON tbl_company.company_id = tbl_company_expense.company_id
            join tbl_company_expense_item ON tbl_company_expense_item.setting_expense_id = tbl_setting_expense.setting_expense_id
            where tbl_company_expense.company_expense_status != 'deleted'
            and tbl_company_expense_item.company_expense_id = tbl_company_expense.company_expense_id";

       if (isset($company_id)) {
            $query .= " AND tbl_company_expense.company_id = $company_id";
        }

        if (isset($year)) {
            $query .= " AND YEAR(tbl_company_expense.company_expense_created) = $year";
        }

        if (isset($search['month'])) {
            $query .= " AND tbl_company_expense.company_expense_month = {$search['month']}";
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_id, tbl_setting_expense.setting_expense_id, tbl_company.company_id
                    ORDER BY tbl_company_expense.setting_expense_category_id ASC";
   
        $result = DB::select($query);

        $expense_item = [];

        foreach($result as $data){
            if(isset($expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id])){
                $expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;

            }else{
                $expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;

            }
        }

        $grouped_expense_item = [];

        foreach($result as $data){
            if(isset($grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }else{
                $grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }
        }

        $total_expense_item = array();

        foreach($result as $data){
            if(isset($total_expense_item[$data->setting_expense_category_id][$data->setting_expense_id])){
                $total_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;

            }else{
                $total_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;

            }
        }

        return ['expense_item' => $expense_item, 'expense_item' => $grouped_expense_item, 'total_expense_item' => $total_expense_item];
    }

    public static function get_sales_budget_estimate_report($search, $budget_estimated_id)
    {

        $data = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $company_id = $data->company_id;
        $year = $data->budget_estimated_year;

        $query = 'SELECT tbl_product_category.product_category_name, tbl_company.company_id,
                        SUM(tbl_budget_estimated_item.budget_estimated_item_amount) as total_sales_item,
                        tbl_budget_estimated_item.budget_estimated_item_type_value as product_id,
                        tbl_product.product_name as product_name,
                        tbl_product_category.product_category_id as product_category_id,
                        tbl_product_category.product_category_name as product_category_name,
                        tbl_company.company_id as company_id,
                        tbl_budget_estimated_item.budget_estimated_item_month as num_month
                    FROM tbl_budget_estimated_item
                    LEFT JOIN tbl_budget_estimated ON tbl_budget_estimated.budget_estimated_id = tbl_budget_estimated_item.budget_estimated_id
                    LEFT JOIN tbl_product ON tbl_product.product_id = tbl_budget_estimated_item.budget_estimated_item_type_value
                    LEFT JOIN tbl_product_category ON tbl_product_category.product_category_id = tbl_product.product_category_id
                    LEFT JOIN tbl_company ON tbl_company.company_id = tbl_budget_estimated_item.company_id
                    WHERE tbl_budget_estimated_item.budget_estimated_item_type = "product_id"
                    AND tbl_company.is_display = 1
                    AND tbl_budget_estimated.is_deleted = 0';


        if (isset($company_id)) {
            $query .= " AND tbl_budget_estimated_item.company_id = $company_id";
        }

        if (isset($year)) {
            $query .= " AND tbl_budget_estimated_item.budget_estimated_item_year = $year";
        }

        if (isset($search['month'])) {
            $query .= " AND tbl_budget_estimated_item.budget_estimated_item_month = {$search['month']}";
        }

        $query .= " GROUP BY product_id, tbl_company.company_id, tbl_budget_estimated_item.budget_estimated_item_month
                    ORDER BY tbl_product.product_ranking ASC";


        $result = DB::select(DB::raw($query));
        
        $sales = array();
        foreach ($result as $data) {
            if (isset($sales[$data->company_id][$data->product_category_id][$data->product_id])) {
                $sales[$data->company_id][$data->product_category_id][$data->product_id] += $data->total_sales_item;

            } else {
                $sales[$data->company_id][$data->product_category_id][$data->product_id] = $data->total_sales_item;

            }
        }

        $total_sales_category = array();
        foreach ($result as $data) {
            if (isset($total_sales_category[$data->company_id][$data->product_category_id])) {
                $total_sales_category[$data->company_id][$data->product_category_id] += $data->total_sales_item;

            } else {
                $total_sales_category[$data->company_id][$data->product_category_id] = $data->total_sales_item;

            }
        }

        $grouped_product_category = array();

        foreach($result as $data){
            if(isset($grouped_product_category[$data->company_id][$data->product_category_id])){
                $grouped_product_category[$data->product_category_id] = $data->product_category_name;

            }else{
                $grouped_product_category[$data->product_category_id] = $data->product_category_name;

            }
        }

        $grouped_product_name = array();

        foreach($result as $data){
            if(isset($grouped_product_name[$data->company_id][$data->product_category_id][$data->product_id])){
                $grouped_product_name[$data->company_id][$data->product_category_id][$data->product_id] = $data->product_name;

            }else{
                $grouped_product_name[$data->company_id][$data->product_category_id][$data->product_id] = $data->product_name;

            }
        }

        return ['sales' => $sales, 'total_sales_category' => $total_sales_category, 'grouped_product_category' => $grouped_product_category, 'grouped_product_name' => $grouped_product_name];
    }

    public static function get_expense_budget_estimate_report($search, $budget_estimated_id)
    {

        $data = BudgetEstimated::where('budget_estimated_id', $budget_estimated_id)->first();
        $company_id = $data->company_id;
        $year = $data->budget_estimated_year;

        $query = 'SELECT tbl_setting_expense_category.setting_expense_category_id,
            tbl_setting_expense_category.setting_expense_category_name,
            tbl_setting_expense.setting_expense_id,
            tbl_setting_expense.setting_expense_name,
            tbl_company.company_id,
            ifnull(sum(tbl_budget_estimated_item.budget_estimated_item_amount), 0) as total_cost_sales_item
            from tbl_budget_estimated
            JOIN tbl_budget_estimated_item ON tbl_budget_estimated_item.budget_estimated_id = tbl_budget_estimated.budget_estimated_id
            JOIN tbl_company ON tbl_company.company_id = tbl_budget_estimated.company_id
            join tbl_setting_expense ON tbl_setting_expense.setting_expense_id= tbl_budget_estimated_item.budget_estimated_item_type_value
            join tbl_setting_expense_category ON tbl_setting_expense_category.setting_expense_category_id = tbl_setting_expense.setting_expense_category_id
            WHERE tbl_budget_estimated_item.budget_estimated_item_type = "setting_expense_id"
        
            AND tbl_budget_estimated.is_deleted = 0';

        if (isset($company_id)) {
            $query .= " AND tbl_budget_estimated_item.company_id = $company_id";
        }

        if (isset($year)) {
            $query .= " AND tbl_budget_estimated_item.budget_estimated_item_year = $year";
        }

        if (isset($search['month'])) {
            $query .= " AND tbl_budget_estimated_item.budget_estimated_item_month = {$search['month']}";
        }

        $query .= " GROUP BY tbl_setting_expense_category.setting_expense_category_name, tbl_company.company_id
                    ORDER BY tbl_setting_expense_category.setting_expense_category_id ASC";

        $result = DB::select($query);

        $expense_item = [];

        foreach($result as $data){
            if(isset($expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id])){
                $expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] += $data->total_cost_sales_item;

            }else{
                $expense_item[$data->company_id][$data->setting_expense_category_id][$data->setting_expense_id] = $data->total_cost_sales_item;
            }
        }

        $expense_category = [];

        foreach($result as $data){
            if(isset($expense_category[$data->company_id][$data->setting_expense_category_id])){
                $expense_category[$data->company_id][$data->setting_expense_category_id] += $data->total_cost_sales_item;

            }else{
                $expense_category[$data->company_id][$data->setting_expense_category_id] = $data->total_cost_sales_item;

            }
        }

        $grouped_expense_item = [];

        foreach($result as $data){
            if(isset($grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id])){
                $grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }else{
                $grouped_expense_item[$data->setting_expense_category_id][$data->setting_expense_id] = $data->setting_expense_name;

            }
        }

        $grouped_expense_category = [];

        foreach($result as $data){
            if(isset($grouped_expense_category[$data->company_id][$data->setting_expense_category_id])){
                $grouped_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }else{
                $grouped_expense_category[$data->company_id][$data->setting_expense_category_id] = $data->setting_expense_category_name;

            }
        }

        $total_expense_category = [];

        foreach($result as $data){
            if(isset($total_expense_category[$data->setting_expense_category_id])){
                $total_expense_category[$data->setting_expense_category_id] += $data->total_cost_sales_item;

            }else{
                $total_expense_category[$data->setting_expense_category_id] = $data->total_cost_sales_item;

            }
        }

        return ['expense_category' => $expense_category, 'grouped_expense_category' => $grouped_expense_category, 'total_expense_category' => $total_expense_category, 'expense_item' => $expense_item, 'grouped_expense_item' => $grouped_expense_item ];

    }

    public static function get_expense_item_budget_estimate_report($budget_estimated_id)
    {
        
    }

    public function budget_estimated_company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function budget_estimated_item()
    {
        return $this->hasMany(BudgetEstimatedItem::class, 'budget_estimated_id', 'budget_estimated_id');
    }

    public function budget_estimated_log()
    {
        return $this->hasMany(BudgetEstimatedLog::class, 'budget_estimated_id', 'budget_estimated_id');
    }
}
