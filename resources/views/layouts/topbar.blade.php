<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box d-flex">
                <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ global_asset('images/huaxin_logo.png') }}" alt="" height="22">
                        @if(auth()->user()->company_id != 0)
                          @if(auth()->user()->company->hasMedia('company_logo'))
                          X
                          <img src="{{ auth()->user()->company->getFirstMediaUrl('company_logo') }}" alt="" height="22">
                          @endif
                        @endif
                    </span>
                    <span class="logo-lg">
                        <img src="{{ global_asset('images/huaxin_logo.png') }}" alt="" height="55">
                        @if(auth()->user()->company_id != 0)
                          @if(auth()->user()->company->hasMedia('company_logo'))
                          X
                          <img src="{{ auth()->user()->company->getFirstMediaUrl('company_logo') }}" alt="" height="55">
                          @endif
                        @endif
                    </span>
                </a>

                <a href="/" class="logo logo-light m-auto" style="color: #f6f6f6;">
                    <span class="logo-sm">
                        <img src="{{ get_logo() }}" alt="" height="22">
                        @if(auth()->user()->company_id != 0)
                          @if(auth()->user()->company->hasMedia('company_logo'))
                          X
                          <img src="{{ auth()->user()->company->getFirstMediaUrl('company_logo') }}" alt="" height="22">
                          @endif
                        @endif
                    </span>
                    <span class="logo-lg">
                        {{-- <img src="{{ global_asset('images/huaxin-logo.svg') }}" alt="" height="40"> --}}
                        <img src="{{ get_logo() }}" alt="" height="55">
                        @if(auth()->user()->company_id != 0)
                          @if(auth()->user()->company->hasMedia('company_logo'))
                          X
                          <img src="{{ auth()->user()->company->getFirstMediaUrl('company_logo') }}" alt="" width="60">
                          @endif
                        @endif
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="dropdown d-inline-block">
                @php
                    $img = "https://ui-avatars.com/api/?name=" . Auth::user()->user_fullname;
                @endphp
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img style="object-fit: cover" class="rounded-circle header-profile-user" src="{{ Auth::user()->hasMedia('user_profile_photo') ? Auth::user()->getFirstMediaUrl('user_profile_photo') : $img }}" />
                    <span class="d-none d-xl-inline-block ml-1 va_top">
                        <span>{{ ucfirst(Auth::user()->user_fullname)  }}</span><br/>
                        <span><small>{{ isset(AUTH::user()->user_type) ? AUTH::user()->user_type->user_type_name: "" }}</small></span>
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('user_profile', ['tenant' => tenant('id')]) }}"><i class="bx bx-user font-size-16 align-middle mr-1"></i> Profile</a>
                    <a class="dropdown-item d-block" href="{{ route('user_change_password', ['tenant' => tenant('id')]) }}"><i class="bx bx-wrench font-size-16 align-middle mr-1"></i> Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> {{ __('Logout') }} </a>
                    <form id="logout-form" action="{{ route('logout', ['tenant' => tenant('id')]) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
