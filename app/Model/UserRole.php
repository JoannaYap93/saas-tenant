<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserRole extends Model
{
    protected $table = 'tbl_user_role';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = ['name','guard_name', 'company_id'];

    public static function get_user_role_selection_by_id($id)
    {
        $query = UserRole::query()
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.role_id', 'tbl_user_role.id')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_user_model_has_role.model_id')
            ->where('tbl_user_role.id', $id)
            ->get()->pluck('user_fullname','user_id');;

        return $query;
    }

    public static function get_sel()
    {
        $query = UserRole::query()->get();
        $result = ['' => 'Please Select Role'];
        foreach ($query as $rows) {
            $result[$rows->id] = $rows->name;
        }
        return $result;
    }
}
