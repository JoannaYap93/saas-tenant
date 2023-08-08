<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
    protected $table = 'tbl_company_bank';

    protected $primaryKey = 'company_bank_id';

    const CREATED_AT = 'company_bank_created';
    const UPDATED_AT = 'company_bank_updated';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'company_bank_acc_name',
        'company_bank_acc_no',
        'setting_bank_id',
        'company_id',
        'is_deleted'
    ];

    public static function get_company_bank_details($company_id)
    {
        $temp[''] = 'Please Select Bank Account';
        $query = CompanyBank::query()->where('company_id', $company_id)->where('is_deleted', 0)->get();

        foreach ($query as $data) {
            $str = $data->company_bank_acc_name . ' - ' . $data->company_bank_acc_no;
            $temp[$data->company_bank_id] = $str;
        }

        return $temp;

    }

    public function setting_bank()
    {
        return $this->belongsTo('App\Model\SettingBank', 'setting_bank_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
