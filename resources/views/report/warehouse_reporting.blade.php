@extends('layouts.master')
@section('title')
    Warehouse Report
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
    .table-responsive {
        /* height: 200px !important; */
        /* overflow: hidden !important; */
        overflow: scroll !important;
    }
    table {
        text-align: center !important;
    }
    .bg-grey {
        background: #e4e4e4 !important;
    }

    .bg-red {
            background: #f46a6a !important;
            color: #ffffff !important
    }
    th {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 2 !important;
    }

    th:nth-child(n) {
        position: -webkit-sticky !important;
        position: sticky !important;
        z-index: 1 !important;
    }

    tfoot {
        position: -webkit-sticky !important;
        position: sticky !important;
        bottom: 0 !important;
    }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Warehouse Reporting</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Warehouse Report</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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
                    <form method="POST" action="{{ $submit }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Year</label>
                                    <div class="input-daterange input-group" id="datepicker6"
                                        data-date-format="yyyy" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker6">
                                        <input type="text" style="width: 75px" class="form-control"
                                            name="year" placeholder="Start Date"
                                            value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Month</label>
                                    <div class="input-daterange input-group" id="datepicker7"
                                        data-date-format="mm" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker7">
                                        <input type="text" style="width: 75px" class="form-control"
                                            name="month" placeholder="Select Month"
                                            value="{{ @$search['month'] }}" id="month" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            {{-- @if (auth()->user()->company_id == 0) --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="company_id">Company</label>
                                        {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                    </div>
                                </div>
                            {{-- @endif --}}
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
   <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- @if (auth()->user()->company_id == 0) --}}
                        <x-warehouse_sa_report :companies=$company :monthSel="$month_sel" :whSalesKg=$wh_sales_kg :whSalesRm=$wh_sales_rm :whStock=$wh_stock :search="$search" :companySel="$company_sel" :warehouses="$warehouse"
                        :component=true />
                    {{-- @else
                        <x-warehouse_report :companies=$company :monthSel=$month_sel :whSalesKg=$wh_sales_kg :whSalesRm=$wh_sales_rm :whStock=$wh_stock :search=$search :companySel=$company_sel :warehouses="$warehouse"
                        :component=true />
                    @endif --}}
                </div>
            </div>
        </div>
   </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
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
                    width : '100%',
                    height: '100%',
                }
            }
        });
        $(".fancybox").fancybox();

        $("#datepicker6").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });

        $("#datepicker7").datepicker({
            format: "mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once month is selected
        });

        $("#warehouse-report-table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' :true,
        });
    });

    </script>
@endsection
