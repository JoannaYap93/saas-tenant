<?php

namespace App\Model\Landlord;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CentralFeatureSetting extends Model
{
    protected $table = 'tbl_feature_setting';
    protected $primaryKey = 'feature_id';
    public $timestamps = false;

    protected $fillable = [
        'feature_slug',
        'feature_title',
        'feature_group',
        'feature_icon',
        'feature_extra_charge',
        'feature_charge_per_kg',
        'feature_charge_per_year',
        'feature_charge_subscription_price',
        'feature_status'
    ];

    public function subscription()
    {
        return $this->belongsToMany(CentralSubscription::class,'tbl_subscription_has_feature','feature_id','subscription_id');
    }
}
