<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CustomerCreditHistory extends Model
{
    protected $table = 'tbl_customer_credit_history';
    protected $primaryKey = 'customer_credit_history_id';
    const CREATED_AT = 'customer_credit_history_created';
    const UPDATED_AT = 'customer_credit_history_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'customer_credit_history_value_before',
        'customer_credit_history_value_after',
        'customer_credit_history_action',
        'customer_credit_history_status',
        'customer_credit_history_remark',
        'customer_credit_history_description',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer', 'customer_id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Model\Invoice', 'invoice_id');
    }

    public static function get_credit_history_by_customer_id($customer_id)
    {

        $query = CustomerCreditHistory::query()
            ->where('customer_id', '=', $customer_id)
            ->orderBy('customer_credit_history_created', 'desc')->get();

        return $query;
    }

    // public static function update_user_wallet_history($user_id,$user_wallet_history_type,$user_wallet_history_type_value, $admin_id = 0){
    //     $query = <<<GQL
    //         mutation {
    //             updateUserWalletHistory(input:{
    //                 user_id: $user_id
    //                 user_wallet_history_type: $user_wallet_history_type
    //                 user_wallet_history_type_value: $user_wallet_history_type_value
    //                 admin_id: $admin_id
    //             }){
    //                 user_wallet_history_id
    //             }
    //         }
    //     GQL;

    //     Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //     ])->post(env('GRAPHQL_API').'/graphql', [
    //         'query' => $query
    //     ]);
    // }

    // public static function revert_user_wallet_history($user_id,$user_wallet_history_type,$user_wallet_history_type_value, $admin_id = 0){
    //     $query = <<<GQL
    //         mutation {
    //             revertUserWalletHistory(input:{
    //                 user_id: $user_id
    //                 user_wallet_history_type: $user_wallet_history_type
    //                 user_wallet_history_type_value: $user_wallet_history_type_value
    //                 admin_id: $admin_id
    //             }){
    //                 user_wallet_history_id
    //             }
    //         }
    //     GQL;

    //     Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //     ])->post(env('GRAPHQL_API').'/graphql', [
    //         'query' => $query
    //     ]);
    // }
}
