<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class WorkerStatus extends Model
{
    protected $table = 'tbl_worker_status';
    protected $primaryKey = 'worker_status_id';
    public $timestamps = false;

    protected $fillable = [
        'worker_status_name'
    ];
    //REMARK: if you add or remove status please do update the import worker function and file also later on

    public static function get_sel_worker_status(){
        $result = [];
        $query = WorkerStatus::query()->get();
        foreach($query as $key => $value)
        {
            $result[$value->worker_status_id] = json_decode($value->worker_status_name)->en;
        }

        return $result;
    }

    public function worker()
    {
        return $this->hasMany('App\Model\Worker', 'worker_id');
    }
}
