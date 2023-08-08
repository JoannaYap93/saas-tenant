@extends('layouts.master')

@section('title')
    Monthly Worker Expense Details
@endsection

@section('css')
    <style>
        .log {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }} Monthly Worker Expense</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Monthly Worker Expense</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ @$payroll->company->company_name }} - {{ date("F Y", mktime(0, 0, 0, @$payroll->payroll_month, 1, @$payroll->payroll_year)) }} </h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- @dd($worker) --}}
                <h4 class="card-title mb-3">Monthly Worker Expense Details</h4>
                <x-payroll :payroll="$payroll" :payrollDetails="$payroll_details" :payrollItemsAdd="$payroll_items_type_add" :payrollItemsDeduct="$payroll_items_type_deduct" :workerRoleList="$worker_role_list" :worker="$worker" :settingExpense="$setting_expense" :otvalue="$ot_value" />
            </div>
        </div>
    </div>
</div>
{{-- Order Log --}}
<div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <b>Worker Expense Details</b>
            </div>
            <div class="modal-body">
                <div id="log-description"></div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>
{{-- End Order Log --}}
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        $(document).ready(function(e) {
            $("#payroll_details_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
        });

        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });
    </script>
@endsection
