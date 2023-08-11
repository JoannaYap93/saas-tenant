@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .hover-zoom {
            transition: transform .2s;
        }

        .hover-zoom:hover {
            transform: scale(1.1);
        }

        .float-end {
            float: right !important;
        }

        .button_status {
            z-index: 1;
            border-bottom: 2px socompany_land_id #536AE9 !important;
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
            border-bottom: 2px socompany_land_id #536AE9 !important;
        }

        .btn-nav-list.active {
            z-index: 1;
            color: #536AE9;
            border-bottom: 2px socompany_land_id #536AE9 !important;
        }

        .dashboard-nav {
            background-color: transparent;
        }

        .transaction-card-col {
            padding: 1.5em !important;
        }

        .position-bottom {
            padding-top: 5px!important;
            padding-bottom: 2px!important;
        }

    </style>
@endsection

@section('content')
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
        <div class="col-12">
            <div class="row">
                @can('dashboard_sales_analysis')
                    <a style="color: black" href="{{ route('dashboard_sales_analysis', ['tenant' => tenant('id')]) }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav "><span class="transaction-font">Sales Analysis</span></a>
                @endcan
                <a style="color: black" href="{{ route('dashboard', ['tenant' => tenant('id')]) }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav">
                <span class="transaction-font">Price Information</span></a>
                <a style="color: black" href="{{ route('dashboard_price_analysis', ['tenant' => tenant('id')]) }}"
                    class="button_status transaction-card-col btn-nav-list float-left dashboard-nav active"><span
                        class="transaction-font">Price Analysis</span></a>
                <a style="color: black" href="{{ route('dashboard_profit_loss_analysis', ['tenant' => tenant('id')]) }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav"><span
                    class="transaction-font">Profit & Loss</span></a>
            </div>
        </div>
    </ul>
    <br />
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                    <h4 class="card-title mb-4" id="type_title">Price Analysis</h4>
                                    <div class="row">
                                    <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                                    <input type="text" style="width: 75px" class="form-control" name="date_from" placeholder="Start Date" value="{{ @$search['date_from'] }}" id="start" autocomplete="off">
                                                    <input type="text" style="width: 75px" class="form-control" name="date_to" placeholder="End Date" value="{{ @$search['date_to'] }}" id="end" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        @can('dashboard_sales_analysis')
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Company: </label>
                                                    {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                                </div>
                                            </div>
                                        @endcan
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Farm</label>
                                                {!! Form::select('company_farm_id', $company_farm_sel, @$search['company_farm_id'], ['class' => 'form-control', 'id' => 'company_farm_id']) !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Land</label>
                                                {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_product">
                                            <div class="form-group">
                                                <label for="">Product</label>
                                                    {!! Form::select('product_id', $product_sel, @$search['product_id'], ['class' => 'form-control', 'id' => 'product_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_product">
                                            <div class="form-group">
                                                <label for="">Grade</label>
                                                {!! Form::select('product_size_id', $product_size_sel, @$search['product_size_id'], ['class' => 'form-control', 'id' => 'product_size_id']) !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="search">
                                                    <i class="fas fa-search mr-1"></i> Search
                                                </button>
                                                <button type="submit" class="btn btn-danger waves-effect waves-light mr-2" name="submit" value="reset">
                                                    <i class="fas fa-times mr-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <div class="col-12">
                                <div id="loading">Loading...</div>
                                <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <script>
        var date_from = '{{$search["date_from"]}}';
        var date_to = '{{$search["date_to"]}}';
        let land_arr = [];
        let product_arr = [];
        let farm_arr = [];
        let company_land_id_arr = [];
        let company_farm_id_arr = [];
        let company_id_arr = [];
        let company_id = '{{ @$search['company_id'] }}' ?? null;
        let company_farm_id = '{{ @$search['company_farm_id'] }}' ?? null;
        let company_land_id = '{{ @$search['company_land_id'] }}' ?? null;
        let product_id = '{{ @$search['product_id'] }}' ?? null;
        let product_size_id = '{{ @$search['product_size_id'] }}' ?? null;

        $(document).ready(function(e) {
            if(company_id > 0){
                get_farm_by_company_id(company_id);
            }
            if(company_farm_id > 0){
                get_land_by_company_farm(company_farm_id);
            }
            if(company_land_id > 0){
                get_product_by_company_land_id(company_land_id);
            }
            if(product_id > 0){
                get_size_by_product(product_id);
            }
            chart(date_from,date_to,company_id,company_farm_id,company_land_id,product_id,product_size_id);
        });

        $(document).on('change', '#company_id', function() {
            let company_id = $(this).val();
            get_farm_by_company_id(company_id);
        });

        $(document).on('change', '#company_farm_id', function() {
            let company_farm_id = $(this).val();
            get_land_by_company_farm(company_farm_id);
        });

        $(document).on('change', '#company_land_id', function() {
            let company_land_id = $(this).val();
            get_product_by_company_land_id(company_land_id);
        });

        $(document).on('change', '#product_id', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        $('#loading').show();

        function chart(date_from,date_to,company_id,company_farm_id,company_land_id,product_id,product_size_id){
            $.ajax({
                    url: "{{ route('ajax_price_analysis', ['tenant' => tenant('id')]) }}",
                    method: 'POST',
                    data: {
                        date_from: date_from,
                        date_to: date_to,
                        company_id: company_id,
                        company_farm_id: company_farm_id,
                        company_land_id: company_land_id,
                        product_id: product_id,
                        product_size_id: product_size_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(e) {
                        $('#loading').hide();
                        var options = {
                            chart: {
                                height: 350,
                                type: 'line',
                                stacked: false,
                                toolbar: {
                                    show: false
                                }
                            },
                            stroke: {
                                width: [2, 2],
                                curve: 'smooth'
                            },
                            colors: ['#556ee6', '#34c38f', '#FFFF00'],
                            series: [{
                                name: 'Total Price',
                                type: 'area',
                                data: e.data.price
                            }, {
                                name: 'Average Price(RM)',
                                type: 'area',
                                data: e.data.average
                            },
                            {
                                name: 'Quantity(KG)',
                                type: 'area',
                                data: e.data.qty
                            }],
                            fill: {
                                opacity: [0.1, 0.1, 0.1],
                                gradient: {
                                    inverseColors: false,
                                    shade: 'light',
                                    type: "vertical",
                                    opacityFrom: 0.85,
                                    opacityTo: 0.55,
                                    stops: [0, 100, 100, 100]
                                }
                            },
                            labels: e.data.period,
                            markers: {
                                size: 0
                            },
                            legend: {
                                offsetY: -10
                            },
                            xaxis: {
                                type: 'datetime'
                            },
                            yaxis: {
                                decimalsInFloat: 0
                            },
                            tooltip: {
                                shared: true,
                                intersect: false,
                                y: {
                                    formatter: function formatter(y) {
                                        if (typeof y !== "undefined") {
                                            return y.toFixed(2);
                                        }

                                        return y;
                                    }
                                }
                            },
                            grid: {
                                borderColor: '#f1f1f1'
                            }
                        };
                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();
                    }

                 })
             }

            function get_farm_by_company_id($company_id){
                let company_id = $('#company_id').val();

                let farm_sel = '<option value="">Please Select Farm</option>';

                $('#company_farm_id').html('<option value="">Loading...</option>');


                var request = $.ajax({
                    url: "{{ route('ajax_get_farm_by_company_id', ['tenant' => tenant('id')]) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        company_id: company_id
                    },
                    success: function(e) {
                        e.data.forEach((element) => {
                            if(company_farm_id == element.id){
                                farm_sel += '<option selected value="' + element.id + '">' + element.value + '</option>';
                            }else{
                                farm_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });
                        $('#company_farm_id').html(farm_sel);
                    }
                })
             }

            function get_land_by_company_farm(company_farm_id){

            let land_sel = '<option value="">Please Select Land</option>';

            $('#company_land_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_land_by_company_farm_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_farm_id:company_farm_id
                },
                success: function(e) {
                        e.data.forEach((element) => {
                            if(company_land_id == element.id) {
                                    land_sel += '<option selected value="' + element.id + '">' + element.value + '</option>';
                                }else{
                                    land_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });

                        $('#company_land_id').html(land_sel);

                    }
                })
        }

        function  get_product_by_company_land_id(company_land_id) {

            let product_sel = '<option value="">Please Select Product</option>';

            $('#product_id').html('<option value="">Loading...</option>');
                if($('#company_land_id_input') == 'value'){
                    $('#show_product').attr('disabled', false);

                }else{
                    $('#show_product').attr('disabled', true);
                }
                $.ajax({
                    url: "{{ route('ajax_get_product_sel_by_company_land_id', ['tenant' => tenant('id')]) }}",
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        company_land_id: company_land_id,
                    },
                    success: function(e) {
                        e.data.forEach((element) => {
                            if(product_id == element.product_id){
                                product_sel += '<option selected value=' + element.product_id + '>' + element.product_label + '</option>'
                            }else{
                                product_sel += '<option value=' + element.product_id + '>' + element.product_label + '</option>'
                            }
                        });

                        $('#product_id').html(product_sel);
                    }
                })
        }

        function get_size_by_product(product_id){

            let product_size_id = '{{ @$search['product_size_id'] }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#product_size_id').html('<option value="">Loading...</option>');

            if($('#product_id_input') == 'value'){
                $('#show_product').attr('disabled', false);
            }else{
                $('#show_product').attr('disabled', true);
            }

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id
                },
                success: function(e) {
                    if(e.data.length > 0){
                        e.data.forEach(function(p) {
                            if (product_size_id != null && p.id == product_size_id) {
                                size_sel += '<option selected value=' + p.id + '>' + p.value + '</option>'
                            }else{
                                size_sel += '<option value=' + p.id + '>' + p.value + '</option>'
                            }
                        });
                    }
                    $('#product_size_id').html(size_sel);
                }
            });
        }

        $("#datepicker6").datepicker({
            orientation: "bottom left"
        });
    </script>
@endsection
