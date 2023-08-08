<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SettingReportingTemplate extends Model
{
    protected $table = 'tbl_setting_reporting_template';
    protected $primaryKey = 'setting_reporting_template_id';

    protected $fillable = [
        'setting_reporting_template_name',
    ];
}