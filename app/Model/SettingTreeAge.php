<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingTreeAge extends Model
{
    protected $table ='tbl_setting_tree_age';

    protected $primaryKey = 'setting_tree_age_id';

    const CREATED_AT = 'setting_tree_age_created';
    const UPDATED_AT = 'setting_tree_age_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'setting_tree_age',
        'setting_tree_age_lower_circumference',
        'setting_tree_age_upper_circumference',
        'company_pnl_sub_item_code'
    ];

    public static function get_records($search,$perpage)
    {
        $query = SettingTreeAge::query();
        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('setting_tree_age_lower_circumference', 'like', '%' . $freetext . '%');
                $q->orWhere('setting_tree_age_upper_circumference', 'like', '%' . $freetext . '%');
            });
        }

        $query->orderBy('setting_tree_age_updated', 'DESC');

        $result = $query->paginate($perpage);
        return $result;
    }

    public static function get_age(){
        $age = SettingTreeAge::query();

        $result = $age->get();
        $temp[''] = 'Please Select Age';

        foreach ($result as $value) {
            $temp[$value->setting_tree_age_id] = $value->setting_tree_age;
        }

        return $temp;
    }

    public static function get_age_report($search){
        $query = SettingTreeAge::query();

        if (@$search['setting_tree_age_lower']) {
            $query->where('setting_tree_age', '>=', $search['setting_tree_age_lower']);
        }

        if (@$search['setting_tree_age_upper']) {
            $query->where('setting_tree_age', '<=', $search['setting_tree_age_upper']);
        }
        $result = $query->get();

        return $result;
    }

    public static function get_tree_age(){

        $query = SettingTreeAge::query();
        $result = $query->get();

        return $result;
    }

    public static function get_age_by_circumference($circumference){
        // dd($circumference);
        $max_circumference=SettingTreeAge::max('setting_tree_age_upper_circumference');
        if($circumference < 1){
            $age = SettingTreeAge::whereRaw('setting_tree_age = 1')->first();
        }elseif($circumference == 1){
            $age = SettingTreeAge::whereRaw('setting_tree_age_lower_circumference<='.$circumference.' and setting_tree_age_upper_circumference>='.$circumference)->first();
        }else{
            $age = SettingTreeAge::whereRaw('setting_tree_age_lower_circumference<'.$circumference.' and setting_tree_age_upper_circumference>='.$circumference)->first();
        }
        if(is_null($age) && $circumference>$max_circumference){
            $age=SettingTreeAge::where('setting_tree_age',30)->first();
        }
        return $age;
    }

    public function setting_tree_age_pointer()
    {
        return $this->hasMany(SettingTreeAgePointer::class, 'setting_tree_age_id', 'setting_tree_age_id');
    }
}
