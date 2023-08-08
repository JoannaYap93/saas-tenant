<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserType extends Model
{
    protected $table = 'tbl_user_type';
    protected $primaryKey = 'user_type_id';

    const CREATED_AT = 'user_type_cdate';
    const UPDATED_AT = 'user_type_udate';

    protected $fillable = ['user_type_name'];

    public static function get_user_type(){
        $user_type = UserType::get();

        foreach ($user_type as $key => $value) {
            $value->setAttribute('count', User::get_user_type($value->user_type_id));
        }

        return $user_type;
    }

    public static function get_user_type_sel(){
        $user_type = UserType::get();
        $temp[''] = 'Please select type';
        foreach($user_type as $type){
            $temp[$type->user_type_id] = $type->user_type_name;
        }
        return $temp;
    }

    public static function get_user_role_sel($auth_user_type = null){
        if ($auth_user_type == 1) {
            $user_role = Role::query()->get();
        } else {
            $user_role = Role::query()->whereIn('company_id', [0, auth()->user()->company_id])->get();
        }
        $temp[''] = 'Please select role';
        foreach($user_role as $role){
            $temp[$role->id] = $role->name;
        }
        return $temp;
    }

    public static function get_user_type_radio()
    {
      $user_type = UserType::get();
      $result = array();
      foreach($user_type as $key => $value){
          $result[$value->user_type_id] = $value->user_type_name;
      }
      return $result;
    }

}
