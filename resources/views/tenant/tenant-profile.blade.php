@extends('layouts.master')

@section('title') Tenant Profile @endsection

@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">Tenant Profile</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Tenant</a>
					</li>
					<li class="breadcrumb-item active">Profile</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<!-- end page title -->
@if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('save.tenant.profile', ['tenant' => tenant('id')])}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-sm-center">
                                <div class="col-sm-12">
                                    <div class="form-group text-center">
                                        @if( @$settingMaster->where('setting_slug', 'admin_site_logo')->first()->hasMedia('setting') )
                                            <img style="object-fit: cover" class="m-auto p-auto rounded-circle avatar-xl m-auto" src="{{ @$settingMaster->where('setting_slug', 'admin_site_logo')[0]->getFirstMediaUrl('setting') }}"/>
                                        @else
                                            <img class="m-auto p-auto rounded-circle avatar-xl" src="{{ "https://ui-avatars.com/api/?name=".Auth::user()->user_fullname }}" height="50"/>
                                        @endif
                                        <input name="admin_site_logo" type="file" class="form-control mt-2" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="user_email">Company Name</label> - {{ @$settingMaster->where('setting_slug', 'company_name')->first()->setting_value }}
                                    </div>                   
                                    <div class="form-group">
                                        <label for="user_email">Registration Number</label> - {{ @$settingMaster->where('setting_slug', 'company_reg_no')->first()->setting_value }}
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Tenant Details</h4>
                            <div class="row">
                                <div class="col-sm-12">            
                                    <div class="form-group">
                                        <label for="company_address">Company Address</label>
                                        <input name="company_address" type="text" class="form-control" value="{{ @$settingMaster->where('setting_slug', 'company_address')->first()->setting_value }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="company_email">Company Email</label>
                                        <input name="company_email" type="email" class="form-control" value="{{ @$settingMaster->where('setting_slug', 'company_email')->first()->setting_value }}"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="company_phone">Company Phone</label>
                                        <input name="company_phone" type="text" class="form-control" value="{{ @$settingMaster->where('setting_slug', 'company_phone')->first()->setting_value }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="company_website">Company Website</label>
                                        <input name="company_website" type="text" class="form-control" value="{{ @$settingMaster->where('setting_slug', 'company_website')->first()->setting_value }}">
                                    </div>       
                                    <div class="form-group">
                                        <label for="website_favicon">Website Favicon</label>
                                        <input name="website_favicon" type="file" class="form-control" value="">
                                        @if (@$settingMaster->where('setting_slug', 'website_favicon')->first())
                                            <img src="{{ $settingMaster->where('setting_slug', 'website_favicon')->first()->getFirstMediaUrl('setting') }}" alt="" srcset="" width="100">
                                        @endif
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Update</button>
                                        <a href="{{ route('user_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $tenant->subscription->subscription_name }} <span class="small text-muted">- Subscription Plan</span></h4>
                            <div class="row">
                                <div class="col-sm-12">   
                                    <h4 class="card-title mt-4 text-center">Active Feature</h4>
                                    @if (@$tenant->subscription->feature)
                                        <div class="row">
                                            @foreach ($tenant->subscription->feature as $feature)
                                                <div class='card col-sm-4 text-center' style='box-shadow: unset !important'>
                                                    <div class='card-body'>
                                                        <i class='{{ $feature->feature_icon }} mb-4' style='font-size:50px'></i>
                                                        <h5 class='card-title'>{{ $feature->feature_title }}</h5>
                                                        <p class='card-text'>{{ $feature->feature_group }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if (@count($additionalFeature) > 0)
                                        <h4 class="card-title mt-4 text-center">Additional Feature</h4>
                                        <div class="row">
                                            @foreach ($additionalFeature as $feature)
                                                <div class='card col-sm-4 text-center' style='box-shadow: unset !important'>
                                                    <div class='card-body'>
                                                        <i class='{{ $feature->feature_icon }} mb-4' style='font-size:50px'></i>
                                                        <h5 class='card-title'>{{ $feature->feature_title }}</h5>
                                                        <p class='card-text'>{{ $feature->feature_group }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script> 
    <!-- Plugins js -->
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script> 

    <script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script> 

    <script>
        $(document).ready(function(e) {
            //$("#user_role").hide();
            $('#user_type').on('change', function() {
                if(this.value == 1){
                    $("#user_role").show();
                } else {
                    $("#user_role").hide();
                }
            });
        });
    </script>
@endsection