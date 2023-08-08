<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class UserGroup extends Model
    {
        protected $table = 'tbl_user_group';
        protected $primaryKey = 'user_group_id';
        public $timestamps = false;

        protected $fillable = [
            'user_group_name'
        ];

        public static function user_group_sel()
        {
            $query = UserGroup::query()->get();
            $result = ['' => "Please Select User Group"];

            foreach($query as $key => $value)
            {
              $result[$value->user_group_id] = $value->user_group_name;
            }

            return $result;
        }
    }
?>
