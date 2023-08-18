@extends('layouts.master')

@section('title')
    Budget Estimate Reporting
@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"><span class="mr-2 ">Budget Estimate Reporting</span></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Budget Estimate Report</a>
                    </li>
                    <li class="breadcrumb-item active">Reporting</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
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
                                    <a  href="{{ route('budget_estimate_report_listing_reporting', ['tenant' => tenant('id')]) }}" class="btn btn-secondary waves-effect waves-light mr-2"
                                    name="submit">
                                    <i class="fas fa-arrow-left mr-1"></i> Budget Estimate Report Listing
                                </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <x-budget_estimate_report_component :month="$monthSel" :recordsales="$record_sales" :recordsalesbudget="$record_sales_budget" :recordexpense="$record_expense" :recordexpensebudget="$record_expense_budget" :recordexpenseitem="$record_expense_item" :recordexpenseitembudget="$record_expense_budget" :search="$search" :companies="$company" :productcategory="$product_category" :expensecategory="$expense_category" :expenseitem="$expense_item" :products="$products" :year="$year" :component=true/>
            </div>
        </div>
    </div> 
</div>

<!-- End Modal -->
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
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
                    width : '100%',
                    height: '100%',
                }
            }
        });

        $(".fancybox").fancybox();

        $("#company-expense-table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'scrollable': true,
        });
    });

    $("#datepicker7").datepicker({
        format: "m",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true, //to close picker once year is selected
        orientation: "left"
    });
</script>
@endsection
