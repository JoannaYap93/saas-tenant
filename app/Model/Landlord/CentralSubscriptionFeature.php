<?php

namespace App\Model\Landlord;

use App\Model\Subscription;
use App\Model\FeatureSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class CentralSubscriptionFeature extends Model
{
    protected $table = 'tbl_subscription_has_feature';

    protected $fillable = [
        'id',
        'subscription_id',
        'feature_id',
    ];
}
