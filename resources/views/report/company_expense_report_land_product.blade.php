@extends('layouts.master-without-nav')
@section('title')
    Company Expense Report Land Product
@endsection
@section('css')
    <style>
    .table-responsive {
        height: 500px !important;
        overflow: hidden !important;
        overflow: scroll !important;
    }
    table {
        text-align: center !important;
    }
    .bg-grey {
        background: #e4e4e4 !important;
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

    body {
        overflow-x: hidden;
    }

    .clone-head-table-wrap{
        top: 0px !important;
    }
    ::-webkit-scrollbar {
    display: none;
    }
    </style>
@endsection
@section('content')
    <div class="card-body">
            <x-company_expense_report_land_product_detail :records="$company_expense_land_product" :settingExpense="$setting_expense" :expenseCategory="$expense_category" :monthSel="$month_sel"
            :companyLand="$company_land" :products="$land_product"/>
    </div>
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
        $("#company_expense_table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' : true,
        });
    });
    </script>
@endsection
