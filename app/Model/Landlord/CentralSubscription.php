<?php

namespace App\Model\Landlord;

use Spatie\Permission\Models\Role;
use App\Model\Landlord\CentralFeatureSetting;
use Illuminate\Database\Eloquent\Model;

class CentralSubscription extends Model
{
    protected $table = 'tbl_subscription';
    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'subscription_name',
        'subscription_description',
        'subscription_price',
        'subscription_maximum_charge_per_year',
        'subscription_charge_per_kg',
        'subscription_status',
    ];

    public function feature()
    {
        return $this->belongsToMany(CentralFeatureSetting::class,'tbl_subscription_has_feature','subscription_id','feature_id');
    }
}
