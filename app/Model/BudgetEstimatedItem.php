<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BudgetEstimatedItem extends Model
{
    protected $table = 'tbl_budget_estimated_item';
    protected $primaryKey = 'budget_estimated_item_id';
    const CREATED_AT = 'budget_estimated_item_created';
    const UPDATED_AT = 'budget_estimated_item_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'budget_estimated_id',
        'company_id',
        'budget_estimated_item_month',
        'budget_estimated_item_year',
        'budget_estimated_item_type',
        'budget_estimated_item_type_value',
        'budget_estimated_item_amount'
    ];

    public static function get_items_by_budget_id($data){
        $budgetItem = BudgetEstimatedItem::query();
        $result = $budgetItem->where("budget_estimated_id", $data->budget_estimated_id)->get();

        return $result;
    }
}
