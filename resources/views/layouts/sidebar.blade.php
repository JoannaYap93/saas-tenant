<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
               
                {{-- @canany(['user_dashboard', 'user_listing', 'user_manage','user_role_listing', 'user_role_manage','user_activity_log']) --}}
                    <li class="menu-title">USER</li>
                    {{-- @canany(['user_dashboard', 'user_listing', 'user_manage']) --}}
                    {{-- @can('user_dashboard') --}}
                    {{-- @endcan --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-layout"></i>
                            <span>User</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            {{-- @can('user_manage') --}}
                            <li><a href="{{ route('user_add') }}">Add New User</a></li>
                            {{-- @endcan
                            @can('user_listing') --}}
                            <li><a href="{{ route('user_listing') }}">User Listing</a></li>
                            {{-- @endcan --}}
                        </ul>
                    </li>
                    {{-- @endcanany
                    @canany(['user_role_listing', 'user_role_manage']) --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-layout"></i>
                            <span>User Role</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            {{-- @can('user_role_manage') --}}
                            <li><a href="{{ route('user_role_add') }}">Add New User Role</a></li>
                            {{-- @endcan
                            @can('user_role_listing') --}}
                            <li><a href="{{ route('user_role_listing') }}">User Role Listing</a></li>
                            {{-- @endcan --}}
                        </ul>
                    </li>
                    {{-- @endcanany
                    @can('user_activity_log') --}}
                    {{-- <li>
                    <a href="{{ route('user_activity') }}" class=" waves-effect">
                        <i class="bx bx-book-content"></i>
                            <span>User Activity</span>
                        </a>
                    </li> --}}
                    {{-- @endcan
                @endcanany --}}
                
                {{-- @canany(['master_setting', 'genre_setting','event_category_setting']) --}}
                <li class="menu-title">SETTING</li>
                {{-- @can('master_setting') --}}
                {{-- <li>
                    <a href="{{ route('setting_listing') }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Master Setting</span>
                    </a>
                </li> --}}
                {{-- @endcan --}}
                {{-- @endcanany --}}
                <li>
                    <a class="waves-effect text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i>
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->