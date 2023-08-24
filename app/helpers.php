<?php

use App\Model\Invoice;
use App\Model\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Model\Landlord\CentralTenantCompany;
use App\Model\Landlord\CentralFeatureSetting;

if (!function_exists('sidebar')) {
    function sidebar()
    {
        $query = Invoice::query()
            ->join('tbl_invoice_log as il', 'il.invoice_log_created', '=', 'tbl_invoice.invoice_updated')
            ->where('company_id', auth()->user()->company_id)
            ->where('is_approved', 0)
            ->where('il.invoice_log_action', 'Payment')
            ->get();

        return $query;
    }
}

if (!function_exists('get_logo')) {
    function get_logo()
    {
        $logo = Setting::get_by_slug('admin_site_logo');
        if ($logo) {
            return $logo;
        } else {
            return global_asset('images/huaxin_logo.png');
        }
    }
}

if (!function_exists('get_icon')) {
    function get_icon()
    {
        $logo = Setting::get_by_slug('website_icon');
        if ($logo) {
            return $logo;
        } else {
            return global_asset('images/huaxin_logo_transparent.png');
        }
    }
}

if (!function_exists('checkModuleActive')) {
    function checkModuleActive($module_slug)
    {
        echo $module_slug;
    }
}

if (!function_exists('checkSubscriptionFeature')) {
    function checkSubscriptionFeature($feature_slug)
    {
        $check = tenancy()->central(function ($tenant) use ($feature_slug) {
            $tenant = CentralTenantCompany::with(['subscription', 'subscription.feature'])
            ->where('tenant_code',$tenant->id)
            ->first();

            // Overwrite feature * admin can add feature per tenant
            $overwriteFeature = Arr::get($tenant, 'overwrite_feature', '[]');
            $jsonOverwriteFeature = json_decode($overwriteFeature, true);

            if (in_array($feature_slug, $jsonOverwriteFeature)) {
                $settingFeature = CentralFeatureSetting::where('feature_slug', $feature_slug)->first();
                if (Arr::get($settingFeature, 'feature_status') != 'active') {
                    return false;
                }
                return true;
            }


            // Check subscription feature 
            $featureSlug = Arr::get($tenant, 'subscription.feature', []);
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
        
        return $check;
    }
}
