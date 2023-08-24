@extends('layouts.master-without-nav')
@section('title')
    Company Expense Report
@endsection
@section('css')
    <style>
        .table-responsive {
            height: 600px !important;
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
        body{
            overflow-x: hidden;
        }

        .clone-head-table-wrap{
            top: 0px !important;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-0">
                <div class="card-body">
                    @if (auth()->user()->user_type_id == 1)
                        <x-detailed_company_expense_report :settingExpense="$setting_expense" :expenseCategory="$expense_category" :monthSel="$month_sel" :search="$search"
                        :company="$company" :companyLand="$company_land" :companyExpenseDetail="$company_expense_detail"/>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        $(document).ready(function(e) {
            $("#company-expense-table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
                'namespace': 'company-expense-table'
            });
        });
    </script>
@endsection
