<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Tenant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Model\Landlord\CentralTenantCompany;

class SubscriptionPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $feature_slug)
    {
        $subscriptionCheck = tenancy()->central(function ($tenant) use ($feature_slug, $next, $request) {
            $centralTenant = CentralTenantCompany::with(['subscription', 'subscription.feature'])
            ->whereHas('subscription.feature', function ($query) use ($feature_slug) {
                $query->where('feature_slug', "{$feature_slug}");
            })
            ->where('tenant_code',$tenant->id)
            ->first();

            $featureSlug = Arr::get($centralTenant, 'subscription.feature', []);
            if (count($featureSlug) == 0) {
                return false;
            } else {
                $moduleCheck = $featureSlug->where('feature_slug', $feature_slug)->first();
                if (Arr::get($moduleCheck, 'feature_status') != 'active') {
                    return false;
                }
            }

            return true;
        });

        if (!$subscriptionCheck) {
            return redirect()->route('main.index', ['tenant' => $tenant->id]);
        } else {
            return $next($request);
        }
    }
}
