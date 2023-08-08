<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderExpense extends Model
{
    protected $table = 'tbl_delivery_order_expense';
    protected $primaryKey = 'delivery_order_expense_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'delivery_order_expense_created';
    const UPDATED_AT = 'delivery_order_expense_updated';

    protected $fillable = [
        'delivery_order_expense_value',
        'delivery_order_expense_kg',
        'delivery_order_expense_day',
        'delivery_order_expense_total',
        'setting_expense_id',
        'delivery_order_id',
        'delivery_order_expense_created',
        'delivery_order_expense_updated'
    ];

    public static function get_records($search)
    {
        $query = DeliveryOrder::query();

        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);
        }else if(auth()->user()->company_id != 0){
            $query->where('company_id', auth()->user()->company_id);
        }

        $query->join('tbl_delivery_order_expense', 'tbl_delivery_order_expense.delivery_order_id', 'tbl_delivery_order.delivery_order_id');
        $query->groupBy('delivery_order_id');
        $query->orderBy('delivery_order_created', 'desc');

        $result = $query->paginate(15);

        return $result;
    }

    public static function get_expenses_by_order_id($id)
    {
        $expense_arr = [];
        $query = DeliveryOrderExpense::query()->where('delivery_order_id', $id)->get();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $q) {
                if ($q->expenses) {
                    $expense_arr[$key] = [$q->expenses->setting_expense_name, $q->delivery_order_expense_total];
                }
            }
        }

        return $expense_arr;
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function expenses()
    {
        return $this->belongsTo(SettingExpense::class, 'setting_expense_id');
    }
}
