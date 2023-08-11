@extends('layouts.master')

@section('title')
    Edit Budget Estimate
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <style>
        .editable-buttons{
            display: none !important;
        }
        .editable-click{
            border-bottom: none !important;
        }
        input[type=number]{
            width: 150px !important;
            padding-right: 0.75rem !important;
        }
        .editable-clear-x{
            display: none !important;
        }
        .editable-input{
            margin-right: 0px !important;
        }
        .editable-inline .editableform-loading{
            background-position: center !important;
        }
        .update{
            cursor: pointer;
        }
        .table-responsive {
            height: 500px !important;
            overflow: hidden !important;
            overflow: scroll !important;
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
    </style>
@endsection

@section('content')
<!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    <span class="mr-2">Edit Budget Estimate</span>
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Budget Estimate</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror

    <!-- end page title -->
    @if (@$data)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ $data->budget_estimated_title }}</h4>
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <a  href="{{ route('budget_estimate_report_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary waves-effect waves-light mr-2"
                                    name="submit">
                                    <i class="fas fa-arrow-left mr-1"></i> Budget Estimate Listing
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <x-budget_estimate :company="$company" :productCategory="$product_category" :productSel="$product_sel" :monthSel="$month_sel" :expenseCategory="$expense_category" :expenseItem="$expense_item" :data="$data" :items="$budget_estimated_item" :component=true/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
<!-- End Page-content -->
@endsection

@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        $("#budgetEstimateTable").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'scrollable': true,
        });

        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(window).on('load', function(){
            sum();
        })

        $(".update").on('hidden', function(e, param){
            sum();

            $.ajax({
                url: "{{ route('budget_estimate_report_edit', ['tenant' => tenant('id'), 'id' => $data->budget_estimated_id]) }}",
                type: "POST",
                dataType: 'json',
                data: {
                    budget_id : $(this).attr("data-pk"),
                    company_id : {!!json_encode(@$data->company_id)!!},
                    month : $(this).attr("month"),
                    year : {!!json_encode($data->budget_estimated_year)!!},
                    type : $(this).attr("type"),
                    type_value : $(this).attr("type_value"),
                    amount : $(this).html(),
                    total : Number($("tfoot:eq(0) .total").html()),
                },
                success: function(result){
                }
            });
        });

        $('.update').editable({
            url: "{{ route('budget_estimate_report_edit', ['tenant' => tenant('id'), 'id' => $data->budget_estimated_id]) }}",
            type: 'number',
            step: '0.01',
            pk: 1,
            name: 'value',
            title: 'Enter value',
        });

        function sum(){
            tableProduct=document.getElementsByTagName("tbody")[0];
            allrow = tableProduct.getElementsByClassName('product_item');
            tableProduct2=document.getElementsByTagName("tbody")[1];
            allrow2 = tableProduct2.getElementsByClassName('expense_item');

            for(i = 0; i < allrow.length; i++)
            {
                rowtotal = null;
                for(j=0; j < allrow[i].getElementsByClassName("row-input").length; j++){
                    if($("tbody:eq(0) .product_item:eq("+(i)+") .row-input:eq("+(j)+")").editable("getValue", true) != "-"){
                        rowtotal += Number($("tbody:eq(0) .product_item:eq("+(i)+") .row-input:eq("+(j)+")").editable("getValue", true));
                    }
                }
                if(rowtotal != null){
                    $("tbody:eq(0) .product_item:eq("+(i)+") .row-total").html(rowtotal);
                }
            }

            for(i = 0; i < allrow2.length; i++)
            {
                rowtotal = null;
                for(j=0; j < allrow2[i].getElementsByClassName("ro-input").length; j++){
                    if($("tbody:eq(1) .expense_item:eq("+(i)+") .ro-input:eq("+(j)+")").editable("getValue", true) != "-"){
                        rowtotal += Number($("tbody:eq(1) .expense_item:eq("+(i)+") .ro-input:eq("+(j)+")").editable("getValue", true));
                    }
                }
                if(rowtotal != null){
                    $("tbody:eq(1) .expense_item:eq("+(i)+") .ro-total").html(rowtotal);
                }
            }
            
            netsales = null;
            netexpenses = null;
            for(j = 0; j < {!! json_encode(count($month_sel)) !!}; j++){
                salestotal = null;
                expensetotal = null;
                coltotal = null;
                for(i = 0; i < allrow.length; i++){
                    if($("tbody:eq(0) .product_item:eq("+(i)+") .row-input:eq("+(j)+")").editable("getValue", true) != "-"){
                        salestotal += Number($("tbody:eq(0) .product_item:eq("+(i)+") .row-input:eq("+(j)+")").editable("getValue", true));
                    }
                }
                for(k = 0; k < allrow2.length; k++){
                    if($("tbody:eq(1) .expense_item:eq("+(k)+") .ro-input:eq("+(j)+")").editable("getValue", true) != "-"){
                        expensetotal += Number($("tbody:eq(1) .expense_item:eq("+(k)+") .ro-input:eq("+(j)+")").editable("getValue", true));
                    }
                }

                if(salestotal != null){
                    netsales += salestotal;
                    $(".salestotal:eq("+(j)+")").html(salestotal);
                }

                if(expensetotal != null){
                    netexpenses += expensetotal;
                    $(".expensetotal:eq("+(j)+")").html(expensetotal);
                }

                coltotal = salestotal - expensetotal;

                if(coltotal != null && coltotal != 0){
                    $(".col-total:eq("+(j)+")").html(coltotal);
                }else if(coltotal == 0){
                    $(".col-total:eq("+(j)+")").html("-");
                }
            }
            if(netsales != null){
                $(".netsales").html(netsales);
            }
            if(netsales != null){
                $(".netexpenses").html(netexpenses);
            }

            total = netsales - netexpenses;
            if(total != null){
                $("tfoot:eq(0) .total").html(total);
            }
        }
    </script>
@endsection
