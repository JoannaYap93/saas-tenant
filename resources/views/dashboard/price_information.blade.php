@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">

    <style>
        .hover-zoom {
            transition: transform .2s;
        }

        .hover-zoom:hover {
            transform: scale(1.1);
        }

        .float-end{
            float: right !important;
        }

        .button_status {
            z-index: 1;
            border-bottom: 2px solid #536AE9 !important;
        }

        .transaction-font {
            font-size: 15px;
            font-weight: 600 !important;
        }

        .btn-nav-list {
            border-radius: 0 !important;
            border-left: 0 !important;
            border-right: 0 !important;
            border-top: 0 !important;
            border-bottom: 0 !important;
        }

        .btn-nav-list:hover {
            z-index: 1;
            color: #536AE9;
            /* text-decoration: none; */
            /* background-color: white; */
            border-bottom: 2px solid #536AE9 !important;
        }

        .btn-nav-list.active {
            z-index: 1;
            color: #536AE9;
            border-bottom: 2px solid #536AE9 !important;
        }

        .dashboard-nav{
            background-color: transparent;
        }

        .transaction-card-col {
            padding: 1.5em !important;
        }

    </style>
@endsection

@section('content')
    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Dashboard</h4>
            </div>
        </div>
    </div> --}}
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
        <div class="col-12">
            <div class="row">
                @can('dashboard_sales_analysis')
                {{-- @if(auth()->user()->user_type_id == 1) --}}
                    <a style="color: black" href="{{ route('dashboard_sales_analysis') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav "><span
                    class="transaction-font">Sales Analysis</span></a>
                {{-- @endif --}}
                @endcan
                <a style="color: black" href="{{ route('dashboard') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav active"><span
                    class="transaction-font">Price Information</span></a>
                <a style="color: black" href="{{ route('dashboard_price_analysis') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav"><span
                    class="transaction-font">Price Analysis</span></a>
                <a style="color: black" href="{{ route('dashboard_profit_loss_analysis') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav"><span
                    class="transaction-font">Profit & Loss</span></a>
            </div>
        </div>
    </ul>
    <br />
    <!-- end page title -->
    {{-- <div class="row">
        <div class="col-xl-4">
            <div class="card overflow-hidden">
                <div class="bg-soft-primary">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Welcome !</h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="avatar-md profile-user-wid mb-4">
                                @if (Auth::user()->hasMedia('user_profile_photo'))
                                    <img src="{{ Auth::user()->getFirstMediaUrl('user_profile_photo') }}" alt=""
                                        class="img-thumbnail rounded-circle" style="object-fit: cover">
                                @else
                                    <img src="{{ 'https://ui-avatars.com/api/?name=' . Auth::user()->user_fullname }}"
                                        alt="" class="img-thumbnail rounded-circle">
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-9">

                            <div class="pt-4">

                                <h5 class="font-size-15 text-truncate">
                                    {{ ucfirst(Auth::user()->user_fullname) }}
                                </h5>
                                <p class="text-muted mb-0 text-truncate">
                                    @if (auth()->user()->user_type_id == 2)
                                        <b>{{ auth()->user()->company->company_name }}</b> -
                                    @endif {{ Auth::user()->roles[0]->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ $submit }}">
                        @csrf
                        <div class="clearfix">
                            <div class="float-end">
                                <div id="graph_chart_month">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" class="form-control mr-1" name="search_date" value="{{ @$search['date'] }}" data-date-autoclose="true"
                                                data-date-format="yyyy-mm-dd" data-date-container='#datepicker1' data-provide="datepicker" style="text-align:center" id="datepicker">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                                    name="submit" value="search">
                                                    <i class="fas fa-search mr-1"></i> Search
                                                </button>
                                            {{-- <label class="input-group-text">Date</label> --}}
                                            {{-- <input type="hidden" id="on_focus"> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="card-title mb-4" id="type_title">Price Information</h4>
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="table-responsive">

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @php
                                                        $count = 0;
                                                    @endphp
                                                    @foreach ($farm as $name)
                                                        <th
                                                            style="text-align: center;{{$count%2 == 0 ? 'background-color: #e4e4e4;':''}}">
                                                            {{ $name }}
                                                        </th>
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($price_information['product_list'] as $p)
                                                    @if(@$p)
                                                        <tr>
                                                            <td>{{$p}}</td>
                                                            @php
                                                                $count = 0;
                                                            @endphp
                                                            @foreach($farm as $name)
                                                                <td style="text-align: center;{{$count%2 == 0 ? 'background-color: #e4e4e4;':''}}">
                                                                    @if (isset($price_information[$p][$name]['min']))
                                                                        @if ($price_information[$p][$name]['min'] == $price_information[$p][$name]['max'])
                                                                            <a class="popup" href="{{route('min_max_detail',['search_date' => @$search['date'], 'product'=> $p,'company_farm_name'=>$name])}}">
                                                                                {{ $price_information[$p][$name]['min'] }}
                                                                            </a>
                                                                        @else
                                                                            {{-- <a class="popup" href="{{route('min_max_detail',['search_date' => @$search['date'],'min'=>$price_information[$p][$name]['min'], 'max'=>$price_information[$p][$name]['max'], 'product'=> $p,'company_farm_name'=>$name])}}"> --}}
                                                                            <a class="popup" href="{{route('min_max_detail',['search_date' => @$search['date'], 'product'=> $p,'company_farm_name'=>$name])}}">

                                                                                {{ $price_information[$p][$name]['min'] }} - {{ $price_information[$p][$name]['max'] }}
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        @can('dashboard_sales_analysis')
                                                                        {{-- @if (auth()->user()->user_type_id == 1) --}}
                                                                            @php
                                                                                $detail = [
                                                                                    'search_date' => @$search['date'],
                                                                                    'company_farm_name' => $name,
                                                                                    'product' => $p,
                                                                                ]
                                                                            @endphp
                                                                            <a href="{{route('price_information_add',$detail)}}">-</a>
                                                                        {{-- @else
                                                                            - --}}
                                                                        {{-- @endif --}}
                                                                        @endcan
                                                                    @endif
                                                                </td>
                                                                @php
                                                                    $count++;
                                                            @endphp
                                                            @endforeach
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->user_type_id == 2)

        {{-- to do list --}}
        @if (count($customer_category) <= 1 || count($company_land) <= 1 || count($warehouse) <= 1 || $product == false)
            <div class="row mt-2">
                <div class="col-xl-8 col-lg-12 col-sm-12">
                    <h4>Todo List</h4>
                    <div class="row mt-3">
                        @if (count($customer_category) <= 1)
                            <div class="col-md-3">
                                <a href="{{ route('customer_category_add') }}">
                                    <div class="p-3 w-100 shadow d-flex align-center m-auto hover-zoom"
                                        style="background-color: rgb(213, 221, 255); border-radius: 1rem">
                                        <div style="width: 0.25rem; background-color: #89a8c8" class="mr-2">
                                        </div>
                                        <p class="m-0 py-1" style="color: #3f21c5">Customer Category</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (count($warehouse) <= 1)
                            <div class="col-md-3">
                                <a href="{{ route('setting_warehouse_add') }}">
                                    <div class="p-3 w-100 shadow d-flex align-center m-auto hover-zoom"
                                        style="background-color: rgb(213, 221, 255); border-radius: 1rem">
                                        <div style="width: 0.25rem; background-color:#89a8c8" class="mr-2">
                                        </div>
                                        <p class="m-0 py-1" style="color: #3f21c5">Warehouse</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if ($product == false)
                            <div class="col-md-3">
                                <a href="{{ route('company_listing') }}">
                                    <div class="p-3 w-100 shadow d-flex align-center m-auto hover-zoom"
                                        style="background-color: rgb(213, 221, 255); border-radius: 1rem">
                                        <div style="width: 0.25rem; background-color:#89a8c8" class="mr-2">
                                        </div>
                                        <p class="m-0 py-1" style="color: #3f21c5">Product</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- end --}}
    @endif
    @if (auth()->user()->user_type_id == 1)
        @if (count($product_category) <= 1 || $product == false)
            @if (count($product_category) <= 1)
                <div class="col-md-3">
                    <a href="{{ route('product_category_add') }}">
                        <div class="p-3 w-100 shadow d-flex align-center m-auto hover-zoom"
                            style="background-color: rgb(213, 221, 255); border-radius: 1rem">
                            <div style="width: 0.25rem; background-color:#89a8c8" class="mr-2">
                            </div>
                            <p class="m-0 py-1" style="color: #3f21c5">Product Category</p>
                        </div>
                    </a>
                </div>
            @endif
            @if ($product == false)
                <div class="col-md-3">
                    <a href="{{ route('product_add') }}">
                        <div class="p-3 w-100 shadow d-flex align-center m-auto hover-zoom"
                            style="background-color: rgb(213, 221, 255); border-radius: 1rem">
                            <div style="width: 0.25rem; background-color:#89a8c8" class="mr-2">
                            </div>
                            <p class="m-0 py-1" style="color: #3f21c5">Product</p>
                        </div>
                    </a>
                </div>
            @endif
        @endif
    @endif
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        $(document).ready(function(e) {
            $(".popup").fancybox({
                'type': 'iframe',
                'width': '100%',
                'height': '100%',
                'autoDimensions': false,
                'autoScale': false,
                iframe : {
                    css : {
                        'max-height': '100%'
                    }
                }
            });
            $(".fancybox").fancybox();
        });
    </script>
@endsection
