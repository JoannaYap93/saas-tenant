<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class WorkerType extends Model
{
    protected $table = 'tbl_worker_type';
    protected $primaryKey = 'worker_type_id';
    public $timestamps = false;

    protected $fillable = [
        'worker_type_name'
    ];

    public static function get_records($search, $perpage)
    {
        $query = WorkerType::query();

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('worker_type_name', 'like', '%' . $search['freetext'] . '%');
            });
        }

        $result = $query->orderBy('worker_type_id', 'desc')->paginate($perpage);
        return $result;
    }

    public static function get_selection(){
        return WorkerType::get()->pluck('worker_type_name','worker_type_id');
    }

    public static function get_sel_worker_type(){
        $result = [];
        $query = WorkerType::query()->get();
        foreach($query as $key => $value)
        {
            $result[$value->worker_type_id] = $value->worker_type_name;
        }

        return $result;
    }

    public function worker_type()
    {
        return $this->belongsTo('App\Model\Worker', 'worker_id');
    }
}
