<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WorkerWalletHistory extends Model
{
    protected $table = 'tbl_worker_wallet_history';
    protected $primaryKey = 'worker_wallet_history_id';
    const CREATED_AT = 'worker_wallet_history_created';
    const UPDATED_AT = 'worker_wallet_history_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'worker_id',
        'worker_wallet_history_before',
        'worker_wallet_history_after',
        'worker_wallet_history_value',
        'worker_wallet_history_status',
        'worker_wallet_history_action',
        'user_id',
        'worker_wallet_history_type',
        'worker_wallet_history_type_value',
        'worker_wallet_history_remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
    }

    public static function get_credit_history_by_worker_id($worker_id)
    {
        $query = WorkerWalletHistory::query()
            ->where('worker_id', $worker_id)
            ->orderBy('worker_wallet_history_created','desc')
            ->get();

        return $query;
    }

    public static function update_worker_wallet_history($worker_id,$worker_wallet_history_type,$worker_wallet_history_type_value, $user_id = 0)
    {
        $query = <<<GQL
            mutation {
                updateWorkerWalletHistory(input:{
                    worker_id: $worker_id
                    worker_wallet_history_type: $worker_wallet_history_type
                    worker_wallet_history_type_value: $worker_wallet_history_type_value
                    user_id: $user_id
                }){
                    worker_wallet_history_id
                }
            }
        GQL;

        Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('GRAPHQL_API').'/graphql', [
            'query' => $query
        ]);
    }

    public static function revert_worker_wallet_history($worker_id,$worker_wallet_history_type,$worker_wallet_history_type_value, $user_id = 0)
    {
        $query = <<<GQL
            mutation {
                revertWorkerWalletHistory(input:{
                    worker_id: $worker_id
                    worker_wallet_history_type: $worker_wallet_history_type
                    worker_wallet_history_type_value: $worker_wallet_history_type_value
                    user_id: $user_id
                }){
                    worker_wallet_history_id
                }
            }
        GQL;

        Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('GRAPHQL_API').'/graphql', [
            'query' => $query
        ]);
    }
}
