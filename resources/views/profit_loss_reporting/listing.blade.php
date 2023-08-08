@extends('layouts.master')
@section('title')
    Profit & Loss Reporting
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
        table {
            text-align: center;
        }

        .table-responsive {
            height: 500px !important;
            overflow: hidden !important;
            overflow: scroll !important;
        }

        .bg-grey {
            background: #e4e4e4;
        }

        .table-responsive::-webkit-scrollbar {
            display: inherit;
        }

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
    @if($title == 'Profit & Loss Reporting')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Profit & Loss Reporting</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Profit & Loss</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @elseif($title == 'Dashboard Profit & Loss Analysis')
    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
        <div class="col-12">
            <div class="row">
            @can('dashboard_sales_analysis')
            <a style="color: black" href="{{ route('dashboard_sales_analysis') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav "><span
                class="transaction-font">Sales Analysis</span></a>
            @endcan
                <a style="color: black" href="{{ route('dashboard') }}"
                    class="button_status transaction-card-col btn-nav-list float-left dashboard-nav"><span
                        class="transaction-font">Price Information</span></a>
                <a style="color: black" href="{{ route('dashboard_price_analysis') }}"
                    class="button_status transaction-card-col btn-nav-list float-left dashboard-nav"><span
                        class="transaction-font">Price Analysis</span></a>
                <a style="color: black" href="{{ route('profit_loss_reporting') }}" class="button_status transaction-card-col btn-nav-list float-left dashboard-nav active"><span
                    class="transaction-font">Profit & Loss</span></a>
            </div>
        </div>
    </ul>
    <br />
    @endif
    <!-- end page title -->
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
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Year</label>
                                            <div class="input-daterange input-group" id="datepicker7"
                                                data-date-format="yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker7">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="year" placeholder="Start Date"
                                                    value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for=" ">Type Template</label>
                                            {!! Form::select('setting_reporting_template_id', $temp_sel, @$search['setting_reporting_template_id'], ['class' => 'form-control', 'id' => 'setting_reporting_template_id']) !!}
                                        </div>
                                    </div>
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Company Land</label>
                                            {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
                                            {{-- <select class="form-control" name="company_land_id" id="company_land_id"></select> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for=" ">Level View</label>
                                            {!! Form::select('level_view_id', $level_sel, @$search['level_view_id'], ['class' => 'form-control', 'id' => 'level_view_id']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div id="search_cb">
                                    @if(auth()->user()->user_type_id == 1)
                                <x-advanced_filter :dashboard=true :companyCb="$company_cb" :productCb="$product_cb" :search="$search"/>
                                @endif
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($sales) || !empty($sales_month))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($search['setting_reporting_template_id']))
                            @switch ($search['setting_reporting_template_id'])
                            {{-- Template 1year --}}
                                @case('1')
                                    @switch($search['level_view_id'])
                                        @case('1')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                            {{-- Level 2 View --}}
                                        @case('2')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl2_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                            {{-- Level 3 View --}}
                                        @case('3')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl3_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                        @default
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                    @endswitch
                                @break
                                {{-- Template y2y --}}
                                @case('2')
                                    @switch($search['level_view_id'])
                                        @case('1')
                                        <x-pnlreporting.profit_loss_reporting_temp_y2y_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :recordlastyearsales="$last_year_sales" :recordcostlastyear="$cost_last_year_sales"  :component=true/>
                                            @break
                                        {{-- Level 2 View --}}
                                        @case('2')
                                        <x-pnlreporting.profit_loss_reporting_temp_y2y_lvl2_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :recordlastyearsales="$last_year_sales" :recordcostlastyear="$cost_last_year_sales" :component=true/>
                                            @break
                                        {{-- Level 3 View --}}
                                        @case('3')
                                        <x-pnlreporting.profit_loss_reporting_temp_y2y_lvl3_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemlastyear="$cost_last_year_sales_item" :expenseitemdata="$cost_sales_item_data" :recordlastyearsales="$last_year_sales" :recordcostlastyear="$cost_last_year_sales" :component=true/>
                                            @break
                                        @default
                                        <x-pnlreporting.profit_loss_reporting_temp_y2y_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :recordlastyearsales="$last_year_sales" :recordcostlastyear="$cost_last_year_sales"  :component=true/>
                                            @break
                                    @endswitch
                                @break
                                {{-- Template c2c --}}
                                @case('3')
                                    @switch($search['level_view_id'])
                                        @case('1')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                        {{-- Level 2 View --}}
                                        @case('2')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl2_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                        {{-- Level 3 View --}}
                                        @case('3')
                                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl3_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                        @default
                                        <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                            @break
                                    @endswitch
                                @break
                                {{-- Template m2m --}}
                                @case('4')
                                    @switch($search['level_view_id'])
                                    @case('1')
                                        <x-pnlreporting.profit_loss_reporting_temp_m2m_lvl1_component :monthSel="$month_sel" :recordsales="$sales_month" :recordcost="$cost_sales_month" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :component=true/>
                                        @break
                                    {{-- Level 2 View --}}
                                    @case('2')
                                        <x-pnlreporting.profit_loss_reporting_temp_m2m_lvl2_component :monthSel="$month_sel" :recordsales="$sales_month" :recordcost="$cost_sales_month" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :component=true/>
                                        @break
                                    {{-- Level 3 View --}}
                                    @case('3')
                                        <x-pnlreporting.profit_loss_reporting_temp_m2m_lvl3_component :monthSel="$month_sel" :recordsales="$sales_month" :recordcost="$cost_sales_month" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item_month" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                        @break
                                    @default
                                        <x-pnlreporting.profit_loss_reporting_temp_m2m_lvl1_component :monthSel="$month_sel" :recordsales="$sales_month" :recordcost="$cost_sales_month" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :component=true/>
                                        @break
                                    @endswitch
                                @break
                                @default
                                    <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                                    @break
                            @endswitch
                        @else
                            <x-pnlreporting.profit_loss_reporting_temp_1year_lvl1_component :recordsales="$sales" :recordcost="$cost_sales" :search="$search" :companies="$company_data" :productcategory="$sales_product_category_data" :expensecategory="$cost_sale_category_data" :expenseitem="$cost_sales_item" :expenseitemdata="$cost_sales_item_data" :component=true/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <tr>
            <td colspan="5">No Records!</td>
        </tr>
    @endif

@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        $(document).ready(function(e) {
            var element = document.getElementById("collapse");

            @if(@$search['company_cb_id'])
                let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
                $('.manage-show-hide').trigger('click');
                $('#company_id').val('').attr('disabled', true);
                $('#company_land_id').val('').attr('disabled', true);
            @endif

            @if(@$search['setting_reporting_template_id'] == 1)
                document.getElementById('year').setAttribute('disabled', true);
            @else
                document.getElementById('year').removeAttribute('disabled');
            @endif


            $('.manage-show-hide').on('click', function(){
                let id = $(this).attr('aria-controls')

                if($('#' + id).is(':visible')){
                    $('#company_id').val('').attr('disabled', false);
                    $('#company_land_id').val('').attr('disabled', false);
                    $('.check_company_cb_id').prop('checked', false);
                    $('.check_product_cb_id').prop('checked', false);
                }else{
                    $('#company_id').val('').attr('disabled', true);
                    $('#company_land_id').val('').attr('disabled', true);
                }
            })

            $("#company-expense-table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            })
        });

        $("#datepicker7").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left",
        });

        $('#setting_reporting_template_id').on('change', function() {
            let setting_reporting_template_id = $(this).val();
            if (setting_reporting_template_id == 1) {
                let year = new Date().getFullYear();
                $('#year').val(year);
                document.getElementById('year').setAttribute('disabled', true);
                $('#search_cb').show();
            }else if(setting_reporting_template_id == 2){
                document.getElementById('year').removeAttribute('disabled');
                $('#search_cb').hide();
            }else{
                document.getElementById('year').removeAttribute('disabled');
                $('#search_cb').show();
            }
        });


    @if (@$search['company_id'] != null)
        get_land_user('{{ $search['company_id'] }}');
    @else
        get_land_user('{{ auth()->user()->company_id }}');
    @endif

    $('#company_id').on('change', function() {
        let id = $(this).val();
        console.log('kwek2');
        get_land_user(id);
    });

    function get_land_user(id) {
        let land = '<option value="">Please Select Land</option>';
        let sland = "{{ @$search['company_land_id'] ?? null }}";
        console.log(sland);
        $.ajax({
            url: "{{ route('ajax_land_user') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(e) {
                if (e.land.length > 0) {
                    e.land.forEach(element => {
                        if (sland != null && element.company_land_id == sland) {
                            land += '<option value="' + element.company_land_id + '" selected>' +
                                element
                                .company_land_name + '</option>';
                        } else {
                            land += '<option value="' + element.company_land_id + '">' + element
                                .company_land_name + '</option>';
                        }
                    });
                    $('#company_land_id').html(land);
                } else {
                    $('#company_land_id').html('<option value="">No Land</option>');
                }
            }
        });
    }
    </script>
@endsection
