<?php
    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;

    class WorkerRole extends Model
    {
        protected $table = 'tbl_worker_role';
        protected $primaryKey = 'worker_role_id';
        public $timestamps = false;

        protected $fillable = [
            'worker_role_name'
        ];

        public static function worker_role_sel()
        {
            $worker_role_sel[''] = 'Please Select Worker Role';

            $query = WorkerRole::query()->get();

            foreach($query as $result){
                $worker_role_sel[$result->worker_role_id] = $result->worker_role_name;
            }

            return $worker_role_sel;
        }

        public static function get_worker_role_sel()
        {
            $result[''] = 'Please Select Worker Role';
            $query = WorkerRole::query()->get();
            foreach ($query as $key => $value) {
                $result[$value->worker_role_id] = $value->worker_role_name;
            }
            return $result;
        }

        public static function get_worker_role()
        {
            $result = [];

            $query = WorkerRole::query()->where('worker_role_id', '!=', 5)->get();

            return $query;
        }

        public function worker_role()
        {
            return $this->hasMany('App\Model\Worker', 'worker_id');
        }

    }
?>
