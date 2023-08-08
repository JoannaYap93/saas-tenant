<?php

namespace App\Model;

use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Worker extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'tbl_worker';
    protected $primaryKey = 'worker_id';
    const CREATED_AT = 'worker_created';
    const UPDATED_AT = 'worker_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'user_id', 'worker_name', 'worker_mobile', 'worker_ic', 'company_id', 'worker_type_id',
        'worker_status_id', 'is_attendance_reward', 'setting_reward_id', 'setting_race_id', 'worker_start_date',
        'worker_resigned_date', 'is_suspended', 'worker_default', 'worker_role_id', 'worker_wallet_amount'
    ];

    public static function get_records($search)
    {
        // dd($search);
        $query = Worker::with('worker_type', 'user', 'company')->whereHas('company', function($q){
                                                                    $q->where('is_display', '=', 1);
                                                                });
        $query->selectRaw('*,GROUP_CONCAT(worker_id) as worker_ids')
                ->groupBy('tbl_worker.user_id')
                ->orderBy('tbl_worker.company_id','asc')
                ->orderBy('tbl_worker.user_id', 'asc')
                ->orderBy('tbl_worker.worker_id', 'asc')
                ->orderBy('tbl_worker.worker_updated', 'desc');

        if(@$search['freetext']){
            $query->where(function($q) use($search){
                $q->where('worker_name', 'like', '%' . $search['freetext'] . '%')
                ->orWhere('worker_ic', 'like', '%' . $search['freetext'] . '%');
            });
        }

        if (@$search['worker_availability'] == 0) {
            $query->where('is_suspended', 0);
        }elseif(@$search['worker_availability'] == 1){
            $query->where('is_suspended', 1);
        }

        if (@$search['worker_status_id']) {
            $query->where('worker_status_id', $search['worker_status_id']);
        }

        if(@$search['user_id'] && @$search['user_id'] > 0){
            $query->where('user_id',$search['user_id']);
        }

        if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }else{
          if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
              $ids = array();
              foreach (auth()->user()->user_company as $key => $user_company) {
                  $ids[$key] = $user_company->company_id;
              }
              $query->whereIn('company_id', $ids);

          } else if (auth()->user()->company_id != 0) {
              $query->where('company_id', auth()->user()->company_id);
          } else {
              $query->where('company_id', '<>', 1);
          }
        }

        if(@$search['worker_type_id'] && @$search['worker_type_id'] > 0){
            $query->where('worker_type_id',$search['worker_type_id']);
        }
        if(@$search['worker_role_id'] && @$search['worker_role_id'] > 0){
            $query->where('worker_role_id',$search['worker_role_id']);
        }

        if(@$search['is_attendance_reward'] && @$search['is_attendance_reward'] > 0){
            $query->where('is_attendance_reward',$search['is_attendance_reward']);
        }

        if(@$search['setting_race_id'] && @$search['setting_race_id'] > 0){
            $query->where('setting_race_id',$search['setting_race_id']);
        }

        $result = $query->paginate(3);

        return $result;
    }

    public static function get_worker_list_sel_by_company($company_id)
    {
        $result = [];
        $query = Worker::query()
            ->where('is_suspended', '=', 0)
            ->where('worker_role_id', 2)
            ->where('company_id' ,'=', $company_id)
            ->get();

        foreach($query as $key => $workers)
        {
          $result[$key] = ['id' => $workers->worker_id, 'name' => $workers->worker_name];
        }

        return $result;
    }

    public static function get_farm_manager_by_id($search){
        $query = Worker::where('worker_id', $search['farm_manager'])
                    ->where('is_suspended', '=', 0)
                    ->where('worker_role_id', 2);

        $result = $query->first();
        return $result;
    }

    public static function get_worker_farm_manager_by_company($search){

        $company_id = auth()->user()->company_id;
        $query = Worker::query();

        if(isset($search['company_id'])){
            $query->where('tbl_worker.company_id', $search['company_id']);
        }else{
            if($company_id != 0){
                if(auth()->user()->user_type_id != 1){
                    $query->where('tbl_worker.company_id',$company_id);
                }
            }
        }
        $query->where('is_suspended', '!=', 1);
        // $query->where('worker_status_id', '!=', 3);

        $result = $query->get();

        return $result;
    }


    public static function get_farm_manager_sel_by_company($search)
    {
        $result = [];
        $query = Worker::query()
            ->where('is_suspended', '=', 0)
            ->where('worker_role_id', 2);

        if(isset($search['company_id'])){
            $query->where('company_id', $search['company_id']);
        }else{
            if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
                $ids = array();
                foreach (auth()->user()->user_company as $key => $user_company) {
                    $ids[$key] = $user_company->company_id;
                }
                $query->whereIn('company_id', $ids);
            } else if (auth()->user()->company_id != 0) {
                $query->where('company_id', auth()->user()->company_id);
            } else {
                $query->where('company_id', '<>', 1);
            }
        }

        $result = $query->get();
        return $result;
    }

    public static function get_worker_list_sel_by_company_without_user_id($company_id)
    {
        $result = [];
        $query = Worker::query()
            ->where('is_suspended', '=', 0)
            ->where('worker_role_id', 2)
            ->where('user_id',null)
            // ->orwhere('user_id', 0)
            ->where('company_id' ,'=', $company_id)
            ->get();

        foreach($query as $key => $workers)
        {
          $result[$key] = ['id' => $workers->worker_id, 'name' => $workers->worker_name];
        }

        return $result;
    }

    public static function get_farm_manager_list_sel_by_company($company_id)
    {
        $result = [];
        $query = Worker::query()
            ->where('is_suspended', '=', 0)
            ->where('worker_role_id', 2)
            ->where('company_id' ,'=', $company_id)
            ->get();

        foreach($query as $key => $workers)
        {
          $result[$key] = ['id' => $workers->worker_id, 'name' => $workers->worker_name];
        }

        return $result;
    }

    public static function get_worker_by_farm_manager($manager_id){
        $result = [];
        $query = Worker::whereHas('user',function($q) use ($manager_id){
                $q->where('user_id', $manager_id);
            })
            ->where('worker_status_id', '<>', 3)
            ->get();

        foreach($query as $key => $worker)
        {
          $result[$key] = ['id' => $worker->worker_id, 'name' => $worker->worker_name];
        }

        return $result;
    }

    public static function get_worker_list($manager_id, $expense_type, $company_id = null, $worker_role_id, $is_edit = 0, $company_expense_id = 0){

        $result = [];

        $company_expense = CompanyExpense::find($company_expense_id);
        $query = Worker::query()

                ->where('worker_role_id', $worker_role_id);
        if($manager_id != 0){
          $query->where('user_id', $manager_id);
        }

        if ($company_expense && $company_expense->worker_id == $manager_id) {
          $query->whereHas('company_expense_worker', function($q) use($company_expense_id){
            $q->where('company_expense_id', $company_expense_id);
          });
        }else{
          $query->where('worker_status_id','<>',3);
        }

        if ($company_id) {
            $query->where('company_id', $company_id);
        }else {
            $query->where('company_id', auth()->user()->company_id);
        }

        $col = $query->get();
        
            foreach($col as $key => $worker)
            {   if($worker->worker_type_id == 1){
                $expense_daily = SettingExpense::get_expense_by_daily_worker($worker_role_id);
                    if($expense_type == 'daily'){

                        array_push($result,['id' => $worker->worker_id,
                            'name' => $worker->worker_name,
                            'type_id' => $worker->worker_type->worker_type_id,
                            'type_name' => $worker->worker_type->worker_type_name,
                            'expense' => $expense_daily
                        ]);

                    }
                }else if($worker->worker_type_id == 2){
                    $expense_subcon = SettingExpense::get_expense_by_subcon_worker($worker_role_id);
                    if($expense_type == 'monthly'){

                        if($worker->is_attendance_reward == 1){
                            array_push($result,['id' => $worker->worker_id,
                                'name' => $worker->worker_name,
                                'type_id' => $worker->worker_type->worker_type_id,
                                'type_name' => $worker->worker_type->worker_type_name,
                                'expense' => $expense_subcon
                            ]);
                        }
                    }else if($expense_type == 'daily'){
                        if($worker->is_attendance_reward == 0){

                            array_push($result,['id' => $worker->worker_id,
                                'name' => $worker->worker_name,
                                'type_id' => $worker->worker_type->worker_type_id,
                                'type_name' => $worker->worker_type->worker_type_name,
                                'expense' => $expense_subcon
                            ]);
                        }
                    }
                }else if($worker->worker_type_id == 3){
                  $expense_daily = SettingExpense::get_expense_by_daily_worker($worker_role_id);
                  // if($expense_type == 'monthly'){
                      // if($worker->is_attendance_reward == 1){
                          array_push($result,['id' => $worker->worker_id,
                              'name' => $worker->worker_name,
                              'type_id' => $worker->worker_type->worker_type_id,
                              'type_name' => $worker->worker_type->worker_type_name,
                              'expense' => $expense_daily
                          ]);
                      // }
                  // }
                }
            }
        return $result;
    }

    public static function check_worker_mobile_exist($worker_mobile, $worker_id = 0)
    {
        $result = false;
        $query = Worker::where([
            'worker_mobile' => $worker_mobile,
        ]);
        if ($worker_id > 0) {
            $query->where('worker_id', '!=', $worker_id);
        }
        $check_worker = $query->first();

        if ($check_worker) {
            $result = true;
        }
        return $result;
    }

    public static function get_farm_manager_worker($search)
    {
        $company_id = auth()->user()->company_id;
        $query = Worker::query();

        if(isset($search['company_id'])){
            $query->where('tbl_worker.company_id', $search['company_id']);
        }else{
            if($company_id != 0){
                if(auth()->user()->user_type_id != 1){
                    $query->where('tbl_worker.company_id',$company_id);
                }
            }
        }

        $result = $query->get();

        return $result;
    }

    public static function get_check_worker_ic($search){
        $query = Worker::query()->with('company')
        ->where('worker_ic', $search['worker_ic'])
        ->where('worker_id','!=',$search['worker_id']);
        $result = $query->first();
        return $result;
    }

    public static function get_worker_by_company(){
        $query = Worker::query();
        if (auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0) {
            $ids = array();
            foreach (auth()->user()->user_company as $key => $user_company) {
                $ids[$key] = $user_company->company_id;
            }
            $query->whereIn('company_id', $ids);

        } else if (auth()->user()->company_id != 0) {
            $query->where('company_id', auth()->user()->company_id);
        } else {
            $query->where('company_id', '<>', 1);
        }

        $result = $query->get();
        $arr = [];
        foreach($result as $key => $worker){
          $arr[$worker->worker_id] = $worker->worker_name;
        }
        return $arr;
    }

    public static function get_farm_manager_by_company_id($company_id, $ajax = true)
    {
        $non_worker_sel = array();

        $query = Worker::query();
        $query->where('company_id', $company_id);
        $query->whereHas('worker_role', function($q){
            $q->where('worker_role_name', 'Farm Manager');
        });
        $result = $query->get();

        foreach($result as $key => $worker)
        {
            if($ajax){
                $non_worker_sel[$key] = ['id' => $worker->worker_id, 'name' => $worker->worker_name];
            }else{
                $non_worker_sel[$worker->worker_id] = $worker->worker_name;
            }
        }

        return $non_worker_sel;
    }

    public static function get_availability()
    {
        return [
            '0' => 'Active',
            '1' => 'Suspended',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('worker_media')
            ->singleFile()

            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('full')
                    ->format('jpg')
                    ->apply();
            });
    }

    public function getSubWorkerAttribute(){
        $result = null;
        if(!empty($this->worker_ids)){
            $worker_ids = explode(",",$this->worker_ids);
            $result = Worker::query()
                    ->whereIn('worker_id',$worker_ids)->get();
        }
        return $result;
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }

    public function company_land()
    {
        return $this->belongsTo('App\Model\Company', 'company_land_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function worker_type()
    {
        return $this->belongsTo('App\Model\WorkerType', 'worker_type_id');
    }

    public function worker_status()
    {
        return $this->belongsTo('App\Model\WorkerStatus', 'worker_status_id');
    }

    public function company_expense_worker()
    {
        return $this->hasMany(CompanyExpenseWorker::class, 'worker_id', 'worker_id');
    }

    public function setting_race()
    {
        return $this->belongsTo('App\Model\SettingRace', 'setting_race_id');
    }

    public function worker_role()
    {
        return $this->belongsTo(WorkerRole::class, 'worker_role_id', 'worker_role_id');
    }

    public function setting_reward()
    {
        return $this->belongsTo(SettingReward::class, 'setting_reward_id', 'setting_reward_id');
    }

    public function worker_wallet_history()
    {
        return $this->hasMany(WorkerWalletHistory::class, 'worker_id', 'worker_id');
    }
}
