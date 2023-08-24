@extends('layouts.master-without-nav')
@section('title')
Farm Manager Worker Expense Report
@endsection
@section('css')
<style>
 .table-responsive {
        /* height: 500px !important; */
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
    </style>
@endsection
@section('content')
    <div class="card-body">
        <x-farm_manager_worker_expense_report_detail :companyExpenseWorker="$company_expense_worker"
        :workers="$workers"  :farmManager="$farm_manager" :company="$company" :search="$search"
        :settingExpense="$setting_expense" :settingExpenseCategory="$setting_expense_category" :component=true/>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
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
        $("#farm-manager-worker-expense-table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true
        });
    });
    </script>
@endsection

