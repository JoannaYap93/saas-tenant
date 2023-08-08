<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use DB;
use Illuminate\Support\Str;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
// use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\UserCreditHistory;
use Illuminate\Pagination\Paginator;

class User extends Authenticatable implements HasMedia
{
    // use Notifiable, HasRoles, LogsActivity;
    use HasRoles, Notifiable, InteractsWithMedia;

    protected $table = 'tbl_user';

    protected $primaryKey = 'user_id';

    const CREATED_AT = 'user_cdate';
    const UPDATED_AT = 'user_udate';
    protected $dateFormat = 'Y-m-d H:i:s';

    // protected static $logAttributes = ['*'];
    // protected static $logAttributesToIgnore = ['user_udate'];
    // protected static $logName = 'user';
    // protected static $logOnlyDirty = true;
    // protected static $submitEmptyLogs = false;

    protected $fillable = [
        'user_type_id', 'user_email', 'password', 'user_fullname', 'user_profile_photo', 'user_nric', 'user_nationality',
        'user_gender', 'user_address', 'user_address2', 'user_city', 'user_state', 'user_postcode', 'user_dob',
        'user_status', 'user_logindate', 'user_cdate', 'user_udate', 'user_ip', 'is_deleted', 'user_mobile',
        'user_join_date', 'user_admin_skin', 'user_unique_code', 'company_id'
    ];

    protected $hidden = [
        'password', 'user_remember_token'
    ];

    public static function get_record($search, $perpage)
    {
        // $user = User::query()->whereHas('company', function($q){
        //     $q->where('is_display', '=', 1);
        // });

        $user = User::query();
        $user->where('tbl_user.is_deleted', '!=', 1);

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $user->where(function ($q) use ($freetext) {
                $q->where('tbl_user.user_email', 'like', '%' . $freetext . '%')->orWhere('tbl_user.user_mobile', 'like', '%' . $freetext . '%')
                ->orWhere('tbl_user.user_fullname', 'like', '%' . $freetext . '%')
                ->orWhere('tbl_user.user_unique_code', 'like', '%' . $freetext . '%');
            });
        }
        if (@$search['user_status']) {
            $user = $user->where('tbl_user.user_status', $search['user_status']);
        }
        if (@$search['user_type_id']) {
            $user = $user->where('tbl_user.user_type_id', $search['user_type_id']);
        }
        if (@$search['user_language']) {
            $user = $user->where('tbl_user.user_language', $search['user_language']);
        }
        if (@$search['user_gender']) {
            $user = $user->where('tbl_user.user_gender', $search['user_gender']);
        }

        if (@$search['company_id']) {
            $user->where('tbl_user.company_id', $search['company_id']);
        }

        // if (@auth()->user()->company_id && auth()->user()->company_id != 0) {
        //     $user->where('tbl_user.company_id', auth()->user()->company_id);
        // }
        if(auth()->user()->user_id ==1){
          $user->where('user_id', '!=', 1);
        }else{
          if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();
            foreach(auth()->user()->user_company as $key => $user_company){
              // $company->where('company_id', $user_company->company_id);
              if($user_company->company->is_display != 0){
                $ids[$key] = $user_company->company_id;
              }
              // dd($ids[$key]);
            }
            $user->where('user_id', '!=', 1);
            $user->whereIn('company_id', $ids);
            $user->orWhereHas('user_company', function($q) use($ids, $search){
              $q->whereHas('company', function($q2) use($ids, $search){

                $q2->whereIn('company_id', $ids);

                if (@$search['freetext']) {
                    $freetext = $search['freetext'];
                    $q2->where(function ($q3) use ($freetext) {
                        $q3->where('tbl_user.user_email', 'like', '%' . $freetext . '%')->orWhere('tbl_user.user_mobile', 'like', '%' . $freetext . '%')
                        ->orWhere('tbl_user.user_fullname', 'like', '%' . $freetext . '%')
                        ->orWhere('tbl_user.user_unique_code', 'like', '%' . $freetext . '%');
                    });
                }
                if (@$search['user_status']) {
                    $q2 = $q2->where('tbl_user.user_status', $search['user_status']);
                }
                if (@$search['user_type_id']) {
                    $q2 = $q2->where('tbl_user.user_type_id', $search['user_type_id']);
                }
                if (@$search['user_language']) {
                    $q2 = $q2->where('tbl_user.user_language', $search['user_language']);
                }
                if (@$search['user_gender']) {
                    $q2 = $q2->where('tbl_user.user_gender', $search['user_gender']);
                }
              });
            });

            // $user->orWhereHas('company', function($q) use($ids){
            //   $q->whereIn('company_id', $ids);
            // });
            // $user->leftJoin('tbl_user_company', 'tbl_user_company.user_id', '=', 'tbl_user.user_id');
            // $user->whereRaw("
            //                 CASE
            //                WHEN tbl_user.company_id = 0 THEN
            //                tbl_user_company.company_id IN (".implode(',',$ids).") ELSE tbl_user.company_id IN (".implode(',',$ids).")
            //                 END GROUP BY tbl_user.user_id");
                                        //
                                        //
                                        // $sql = str_replace(array('?'), array('\'%s\''), $user->toSql());
                                        // $sql= vsprintf($sql, $user->getBindings());
                                        // dd($sql);
            // $user->whereIn('company_id', $ids);
          }else if(auth()->user()->company_id != 0){
             $user->where('company_id', auth()->user()->company_id);
          }else {
              $user->where('company_id', '<>', 1);
              // $user->whereHas('company', function($q) {
              //   $q->where('is_display', 1);
              // });
          }
        }

        $user->with('user_type');
        $user->with('company');
        $user->with('user_company');
        $user->with('roles');

        if (@$search['user_role_id']) {
            // $user = $user->where('umhs.role_id', $search['user_role_id']);
            $user = $user->whereHas('roles', function ($q) use ($search) {
                $q->where('id', '=', $search['user_role_id']); // or whatever constraint you need here
            });
        }
        //
        // if(auth()->user()->user_type_id == 1){
        //     $user->whereIn('tbl_user.user_type_id', [auth()->user()->user_type_id, 2]);
        // }else{
        //     $user->where('tbl_user.user_type_id', [auth()->user()->user_type_id]);
        // }

        // $sql = str_replace(array('?'), array('\'%s\''), $user->toSql());
        // $sql= vsprintf($sql, $user->getBindings());
        // dd($sql);

        $user->orderBy('user_fullname', 'ASC');
        // dd($user->get());
        return $user->paginate(15);
    }

    public static function get_user_list_sel_by_company($company_id)
    {
        $result = [];
        $query = User::query()
            ->where('user_status', '=', 'active')
            ->where('company_id' ,'=', $company_id)
            ->get();

        foreach($query as $key => $users)
        {
          $result[$key] = ['id' => $users->user_id, 'name' => $users->user_fullname, 'unique_code' => $users->user_unique_code];
        }

        return $result;
    }

    public static function get_all_customer_by_email_sel()
    {
        $users = User::where('is_deleted', 0)->where('user_status', 'active')->get();
        $temp[''] = 'Please select user email';
        foreach ($users as $user) {
            $temp[$user->user_id] = $user->user_email;
        }
        return $temp;
    }

    public static function get_user_role_name($user_id)
    {
        $query = DB::table('tbl_user_role')
            ->selectRaw('tbl_user_role.name')
            ->join('tbl_user_model_has_role', 'tbl_user_model_has_role.role_id', 'tbl_user_role.id')
            ->join('tbl_user', 'tbl_user.user_id', 'tbl_user_model_has_role.model_id')
            ->where('tbl_user.user_id', $user_id)
            ->first();

        return $query;
    }

    public static function get_user_by_id($user_id){
        $user = User::where('is_deleted', 0)
                    ->where('user_status', 'active')
                    ->where('user_id', $user_id)
                    ->first();

        return $user;
    }

    public static function get_farm_manager_list($company_id)
    {
      $user = User::query()
                  ->role('Farm Manager')
                  ->where('is_deleted', 0)
                  ->where('user_status', 'active')
                  ->where('company_id', $company_id)
                  ->get();

      $result = [];

      foreach ($user as $key => $value) {
        $expense = SettingExpense::get_expense_by_daily_worker();
        array_push($result, [
          'id' => $value->user_id,
          'name' => $value->user_fullname,
          'type_id' => 1,
          'expense' => $expense,
        ]);
      }
      return $result;
    }

    public function user_type()
    {
        return $this->belongsTo('App\Model\UserType', 'user_type_id');
    }

    public function user_company(){
        return $this->hasMany('App\Model\UserCompany', 'user_id');
    }
    public function user_role()
    {
        return $this->belongsTo('App\Model\UserRole', 'id');
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function worker()
    {
        return $this->hasMany('App\Model\Worker', 'user_id');
    }

    public function user_land()
    {
        return $this->hasMany('App\Model\UserLand', 'user_id');
    }

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->user_email;
            case 'nexmo':
                return $this->user_mobile;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function getRememberTokenName()
    {
        return 'user_remember_token';
    }

    public function getFullNameAttribute()
    {
        return ucwords($this->user_fullname);
    }

    public function getFullAddressAttribute()
    {
        $address_1 = ucwords($this->user_address);
        $address_2 = ucwords($this->user_address2);
        $city = ucwords($this->user_city);
        $state = ucwords($this->user_state);
        $postcode = ucwords($this->user_postcode);
        $full_address = sprintf('%s,<br>%s,<br>%s,<br>%s,<br>%s', $address_1, $address_2, $postcode, $city, $state);
        return $full_address;
    }

    public static function check_user_mobile_exist($user_mobile, $user_id = 0)
    {
        $result = false;
        $query = User::where([
            'user_mobile' => $user_mobile,
            'is_deleted' => 0
        ]);
        if ($user_id > 0) {
            $query->where('user_id', '!=', $user_id);
        }
        $check_user = $query->first();

        if ($check_user) {
            $result = true;
        }
        return $result;
    }

    public static function get_user_type($type)
    {
        $query = User::query()->where('user_type_id', $type)->groupBy('user_type_id');
        $result = $query->count();
        return $result;
    }

    public static function get_user_by_company_id($company_id)
    {
        $result = [];
        $query = User::where('company_id', $company_id)
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->orderBy('user_id', 'desc')
            ->get();

        foreach($query as $key => $user)
        {
          $result[$key] = ['id' => $user->user_id, 'name' => $user->user_fullname];
        }

        return $result;
    }


    public static function get_user_land_sel()
    {
        $company_id = auth()->user()->company_id;

        $result = [];
        $query = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Farm Manager');
            })
            // ->whereHas('user_land',function($q) use ($land_id){
            //     $q->where('company_land_id', $land_id);
            // })
            ->where('company_id', $company_id)
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->orderBy('user_id', 'desc')
            ->get();

            $query2 = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'Farm Manager');
                })
                ->join('tbl_worker', 'tbl_worker.user_id', 'tbl_user.user_id')
                ->where('tbl_worker.worker_role_id', 2)
                // ->whereHas('user_land',function($q) use ($land_id){
                //     $q->where('company_land_id', $land_id);
                // })
                ->where('tbl_user.company_id', $company_id)
                ->where('tbl_user.is_deleted', '=', 0)
                ->where('tbl_user.user_status', '=', 'active')
                ->orderBy('tbl_user.user_id', 'desc')
                ->get();

        foreach($query as $key => $manager)
        {
          foreach ($query2 as $key2 => $manager2) {
            if($manager->user_id == $manager2->user_id){
              $result[$manager->user_id]['name'] = $manager->user_fullname;
              $result[$manager->user_id]['worker_id'] = $manager2->worker_id;
            }else{
              $result[$manager->user_id]['name'] = $manager->user_fullname;
              $result[$manager->user_id]['worker_id'] = 0;
            }
          }

        }
        return $result;
    }

    public static function get_user_land_sel_2($land_id)
    {
        $result = [];
        $query = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Farm Manager');
            })
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->orderBy('user_id', 'desc')
            ->get();

        foreach($query as $key => $manager)
        {
          $result[$key] = ['id' => $manager->user_id, 'name' => $manager->user_fullname];
        }

        return $result;
    }

    public static function get_farm_manager_sel_by_company($company_id)
    {
        $result = [];
        $query = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Farm Manager');
            })
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->where('company_id' ,'=', $company_id)
            ->orderBy('user_id', 'desc')
            ->get();

        foreach($query as $key => $manager)
        {
          $result[$key] = ['id' => $manager->user_id, 'name' => $manager->user_fullname];
        }

        return $result;
    }

    public static function get_manager()
    {
        $farm_manager_array = array();

        $query = User::with('company')->whereHas(
            'roles', function($q){
                $q->where('name', 'Farm Manager');
            })
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            // ->orderBy('user_id', 'desc')
            ->orderBy('company_id', 'asc')
            ->get();


        foreach ($query as $data) {
            if (isset($farm_manager_array[$data->company_id][$data->user_id]) ) {
                $farm_manager_array[$data->company_id][$data->user_id] += $data->user_fullname;
            } else {
                $farm_manager_array[$data->company_id][$data->user_id] = $data->user_fullname;
            }
        }

        return $farm_manager_array;

    }

    public static function get_all_users(){
        $array = [];
        $result = User::select('company_id','user_fullname','user_id')
            ->whereHas(
                'roles', function($q){
                    $q->where('name', 'Farm Manager');
                })
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->orderBy('company_id', 'asc')
            ->get();

            // foreach ($result as $data) {
            //     if (isset($array[$data->company_id]) ) {
            //         $array[$data->company_id] += $data->user_fullname;
            //     } else {
            //         $array[$data->company_id] = $data->user_fullname;
            //     }
            // }


    }

    public static function get_farm_manager(){
        $query = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Farm Manager');
            })
            ->where('is_deleted', '=', 0)
            ->where('user_status', '=', 'active')
            ->orderBy('company_id', 'asc')
            ->get();
        return $query;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('user_profile_photo')
            ->singleFile();
    }

    public function user_profile_photo_media()
    {
        return $this->hasOne('App\Model\Media', 'model_id', 'user_id')->where(['collection_name' => 'user_profile_photo']);
    }

    public static function get_user_sel()
    {
        $company_id = auth()->user()->company_id;

        $result = array();

        $query = User::query()
                ->where('is_deleted', '=', 0)
                ->where('user_status', '=', 'active')
                ->groupBy('user_fullname');

        if($company_id != 0){
            $query->where('company_id', $company_id);
        }

        if(!empty($query)){
            $result = $query->pluck('user_fullname', 'user_id')->toArray();
        }

        return $result;
    }

    public static function get_user_email($email)
    {
        if ($email) {
            $query = User::query()->where('user_email', $email)->first();
            if ($query) {
                return 'false';
            } else {
                return 'true';
            }
        } else {
            return false;
        }
    }

    public static function get_user_by_role($role_name){
        $query=User::query()->whereHas('roles',function($query) use ($role_name){
            $query->where('name',$role_name);
        })->get();
        return $query;
    }

    public static function get_fullname_by_company_id($search)
    {
        $company_id = auth()->user()->company_id;

        $query = User::query()->with('roles');

        $query->leftJoin('tbl_user_land', 'tbl_user_land.user_id', 'tbl_user.user_id');

        if(isset($search['company_cb_id'])){
            $query->where('tbl_user.company_id', $search['company_cb_id']);
        } elseif(isset($search['company_id'])){
            $query->where('tbl_user.company_id', $search['company_id']);
        }else{
            if($company_id != 0){
                if(auth()->user()->user_type_id != 1){
                    $query->where('tbl_user.company_id',$company_id);
                }
            }
        }

        if(isset($search['user_id'])){
            $query->where('tbl_user.user_id',$search['user_id']);
        }

        $query->whereHas('roles', function($q){
            $q->where('role_id', 13);
        });

        $query->whereHas('company', function($q){
            $q->where('is_display', 1);
        });

        $user = $query->get();

        return $user;
    }

    public static function get_farm_manager_name_by_company($search)
    {
        $company_id = auth()->user()->company_id;
        $query = User::query()->with('roles');

        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('tbl_user.company_id', $ids);
        } else if (auth()->user()->company_id != 0) {
            $query->where('tbl_user.company_id', auth()->user()->company_id);
        } else {
            $query->where('tbl_user.company_id', '<>', 1);
        }

        if(isset($search['company_id'])){
            $query->where('tbl_user.company_id', $search['company_id']);
        }

        if(isset($search['user_id'])){
            $query->where('tbl_user.user_id',$search['user_id']);
        }

        $query->whereHas('roles', function($q){
            $q->where('role_id', 13);
        });

        $query->whereHas('company', function($q){
            $q->where('is_display', 1);
        });

        $query->where('user_status', '=', 'active');

        $query->where('is_deleted', 0);

        $user = $query->get();

        return $user;
    }

    public static function get_existing_farm_manager_name_for_worker($search)
    {
        $company_id = auth()->user()->company_id;
        $query = User::query()->with('roles');
        $query->join('tbl_worker', 'tbl_worker.user_id', 'tbl_user.user_id');

        if(isset($search['company_id'])){
            $query->where('tbl_user.company_id', $search['company_id']);
        }else{
            if($company_id != 0){
                if(auth()->user()->user_type_id != 1){
                    $query->where('tbl_user.company_id',$company_id);
                }
            }
        }

        $query->whereHas('roles', function($q){
            $q->where('role_id', 13);
        });

        $user = $query->get();

        return $user;
    }

    public static function get_by_ids($user_ids){
        $query = User::query()->whereIn('user_id', $user_ids)->get();
        return $query;
    }

    public static function get_search_user_sel($user_name, $company_id, $select = false)
    {
        $list = [];

        $query = User::query()
                ->where('is_deleted', '=', 0)
                ->where('user_status', '=', 'active')
                ->groupBy('user_fullname');

            $query->where('company_id', $company_id);

        if ($select == true) {
            if ($user_name) {
                $query->where('user_fullname', 'like', '%' . $user_name . '%');
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->user_id,
                        'text' => $value->user_fullname
                    ));
                }
            }
        } else {
            if ($user_name) {
                $query->where('user_fullname', $user_name);
            }
            $query = $query->get();
            if ($query) {
                foreach ($query as $key => $value) {
                    array_push($list, array(
                        'id' => $value->user_id,
                        'text' => $value->user_fullname,
                    ));
                }
            }
        }

        return $list;
    }
}
