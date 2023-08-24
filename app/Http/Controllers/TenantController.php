<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Setting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Model\Landlord\CentralTenantCompany;
use App\Model\Landlord\CentralFeatureSetting;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function profile(Request $request)
    {
        $tenantCode = tenant('id');

        $centralResponse = tenancy()->central(function ($tenant) use ($tenantCode) {
            $tenantCompany = CentralTenantCompany::with(['subscription', 'subscription.feature'])
            ->where('tenant_code',$tenant->id)
            ->first();

            $overWriteFeature = json_decode(Arr::get($tenantCompany, 'overwrite_feature', '[]'), true);
            $additionalFeature = CentralFeatureSetting::whereIn('feature_slug', $overWriteFeature)->get();
            return [
                'tenant_company' => $tenantCompany,
                'additional_feature' => $additionalFeature
            ];
        });

        $tenant = $centralResponse['tenant_company'];
        $additionalFeature = $centralResponse['additional_feature'];

        $settingMaster = Setting::whereIn('setting_slug', ['company_name', 'company_address', 'company_website', 'website_favicon', 'admin_site_logo', 'company_email', 'company_reg_no', 'company_phone'])->get();
        return view('tenant.tenant-profile', compact('settingMaster', 'tenant', 'additionalFeature'));
        // echo json_encode($settingMaster);
    }

    public function saveProfile(Request $request) 
    {
        $validation = Validator::make($request->all(), [
            'company_address' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required',
            'company_website' => 'required',
        ])->setAttributeNames([
            'company_address' => 'Company Address',
            'company_email' => 'Company Email',
            'company_phone' => 'Company Phone Number',
            'company_website' => 'Company Address',
        ]);
        if (!$validation->fails()) {

            // Update Master Setting
            $masterSetting = Setting::updateOrCreate(
                [
                    'setting_slug' => 'company_address'
                ],
                [
                    'setting_value' => $request->input('company_address')
                ]
            );
            $masterSetting = Setting::updateOrCreate(
                [
                    'setting_slug' => 'company_email'
                ],
                [
                    'setting_value' => $request->input('company_email')
                ]
            );
            $masterSetting = Setting::updateOrCreate(
                [
                    'setting_slug' => 'company_phone'
                ],
                [
                    'setting_value' => $request->input('company_phone')
                ]
            );
            $masterSetting = Setting::updateOrCreate(
                [
                    'setting_slug' => 'company_website'
                ],
                [
                    'setting_value' => $request->input('company_website')
                ]
            );

            // Upload Image Setting
            if ($request->hasFile('admin_site_logo')) {
                $setting = Setting::where('setting_slug', 'admin_site_logo')->first();
                $setting->addMediaFromRequest('admin_site_logo')->toMediaCollection('setting');
                if ($setting->hasMedia('setting')) {
                    $value = $setting->getFirstMediaUrl('setting');
                } else {
                    $value = 'image';
                }
                $setting->setting_value = $value;
                $setting->save();
            }

            if ($request->hasFile('website_favicon')) {
                $setting = Setting::where('setting_slug', 'website_favicon')->first();
                $setting->addMediaFromRequest('website_favicon')->toMediaCollection('setting');
                if ($setting->hasMedia('setting')) {
                    $value = $setting->getFirstMediaUrl('setting');
                } else {
                    $value = 'image';
                }
                $setting->setting_value = $value;
                $setting->save();
            }

            return redirect()->route('tenant.profile', ['tenant' => tenant('id')])->with('success_msg', 'Company Profile Updated');
        }

        return redirect()->route('tenant.profile', ['tenant' => tenant('id')])->withErrors($validation);
    }
}