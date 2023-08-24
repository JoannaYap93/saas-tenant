@extends('layouts.master')

@section('title')
    Monthly Worker Expense Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
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
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Monthly Worker Expense Listing</h4>
                    @can('payroll_manage')
                        <a href="javascript: void(0);" id="add_payroll" data-toggle="modal" data-target='#add_payroll_modal'
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> Add New</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Monthly Worker Expense</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext" placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Year</label>
                                            <div class="input-daterange input-group" id="payroll_year"
                                                data-date-format="yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#payroll_year">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="payroll_year" placeholder="Select Year"
                                                    value="{{ @$search['payroll_year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Month</label>
                                            <div class="input-daterange input-group" id="payroll_month"
                                                data-date-format="mm" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#payroll_month">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="payroll_month" placeholder="Select Month"
                                                    value="{{ @$search['payroll_month'] }}" id="month" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company</label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <th>#</th>
                                <th>Monthly Worker Expense Details</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $no = $records->firstItem() @endphp
                                    @foreach ($records as $payroll)
                                        <?php
                                            $status = '';
                                            $log = "<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>";
                                            if(@$payroll->payroll_log){
                                                foreach(@$payroll->payroll_log as $payroll_log){
                                                    $log .= "<tr>";
                                                    $log .= "<td>{$payroll_log->user->user_fullname}</td>";
                                                    $log .= "<td>{$payroll_log->payroll_log_description}</td>";
                                                    $log .= "<td>{$payroll_log->payroll_log_created}</td>";
                                                    $log .= "</tr>";
                                                }
                                            }else {
                                                $log .= "<tr><td colspan=3>No Records!</td></tr>";
                                            }
                                            $log .= '</tbody></table>';
                                            $log = str_replace("'", '`', $log);

                                            switch (@$payroll->payroll_status) {
                                                case 'Pending':
                                                    $status = "<span class='badge badge-primary font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($log) . "'>{$payroll->payroll_status}</span>";
                                                break;
                                                case 'In Progress':
                                                    $status = "<span class='badge badge-warning font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($log) . "'>{$payroll->payroll_status}</span>";
                                                break;
                                                case 'Completed':
                                                    $status = "<span class='badge badge-success font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($log) . "'>{$payroll->payroll_status}</span>";
                                                break;
                                                case 'Deleted':
                                                    $status = "<span class='badge badge-danger font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($log) . "'>{$payroll->payroll_status}</span>";
                                                break;
                                            }
                                        ?>

                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <strong>{{ @$payroll->company->company_name }}</strong><br/>
                                                {{ date("F Y", mktime(0, 0, 0, @$payroll->payroll_month, 1, @$payroll->payroll_year)) }}
                                            </td>
                                            <td>
                                                Grand Total: <strong>RM {{ number_format(@$payroll->payroll_grandtotal, 2) }}</strong><br/>
                                                Total Paid Out: <strong>RM {{ number_format(@$payroll->payroll_total_paid_out, 2) }}</strong>
                                            </td>
                                            <td>
                                                {!! $status !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('payroll_view', ['tenant' => tenant('id'), 'id' => $payroll->payroll_id]) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                                @can('payroll_manage')
                                                    @if($payroll->payroll_status != "Completed")
                                                        <a href="{{ route('payroll_edit', ['tenant' => tenant('id'), 'id' => $payroll->payroll_id]) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    @endif
                                                    @if($payroll->payroll_status != "Deleted" && $payroll->payroll_status != "Completed")
                                                        <span data-toggle='modal' data-target='#complete' data-id='{{ $payroll->payroll_id }}' class='complete'>
                                                            <a href='javascript:void(0);' class='btn btn-sm btn-outline-info waves-effect waves-light'>Complete</a>
                                                        </span>
                                                        <span data-toggle='modal' data-target='#delete' data-id='{{ $payroll->payroll_id }}' class='delete'>
                                                            <a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light'>Delete</a>
                                                        </span>
                                                    @endif
                                                    <a href="{{ route('payroll_export', ['tenant' => tenant('id'), 'id' => $payroll->payroll_id]) }}" class="btn btn-sm btn-outline-success">Export</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <td colspan="4">No Records!</td>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Generate Payroll --}}
    <div class="modal fade" id="add_payroll_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('payroll_generate', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Monthly Worker Expense</h5>
                        <a class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <div class="modal-body">
                        <h4 class="card-title mb-4">Monthly Worker Expense Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_id">Company Name</label>
                                    {!! Form::select('company_id', $company_sel, null, ['class' => 'form-control', 'id' => 'add_payroll_company_id', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Month</label>
                                    <div class="input-daterange input-group" id="datepicker_payroll_date"
                                        data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker_payroll_date">
                                        <input type="text" class="form-control" name="payroll_date"
                                            placeholder="Payroll Month" id="payroll_date"
                                            autocomplete="off" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="exists_err_msg" class="text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <button type="submit" id="btn_generate_payroll" class="btn btn-primary mr-2">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete --}}
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('payroll_status', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Monthly Worker Expense ? </h4>
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <input type="hidden" name="action" value="delete">
                        <div>
                            <label for="payroll_log_remark">Remark: </label>
                            <textarea name="payroll_log_remark" id="summernote"
                                    class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Complete --}}
    <div class="modal fade" id="complete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('payroll_status', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Complete this Monthly Worker Expense ? </h4>
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <input type="hidden" name="action" value="complete">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-success">Complete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    {{-- Log --}}
    <div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Logs</b>
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
    {{-- End Log --}}
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });

        $("#datepicker_payroll_date").datepicker({
            format: "yyyy-mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once year is selected
        });

        $("#payroll_year").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true //to close picker once year is selected
        });

        $("#payroll_month").datepicker({
            format: "mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once year is selected
        });

        $(document).ready(function(e){
            $('#btn_generate_payroll').hide();
        });

        $('.delete').on('click', function() {
            let id = $(this).attr('data-id');
            $(".modal-body #payroll_id").val(id);
        })

        $('.complete').on('click', function() {
            let id = $(this).attr('data-id');
            $(".modal-body #payroll_id").val(id);
        })

        $('#add_payroll_company_id').on('change', function(){
            let company_id = $(this).val();
            let payroll_date = $('#payroll_date').val();
            check_payroll_exists(company_id, payroll_date);
        });

        $('#payroll_date').on('change', function(){
            let company_id = $('#add_payroll_company_id').val();
            let payroll_date = $(this).val();
            check_payroll_exists(company_id, payroll_date);
        });

        function check_payroll_exists(company_id, payroll_date){

            $.ajax({
                url: "{{ route('ajax_check_payroll_exists', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                    payroll_date: payroll_date,
                },
                success: function(e) {
                    if(e.data){
                        console.log(payroll_date)
                        if(company_id != "" && payroll_date != ""){
                            $('#exists_err_msg').html('Payroll exists for this company and month selected.');
                        }
                        $('#btn_generate_payroll').hide();
                    }else{
                        if(company_id != "" && payroll_date != ""){
                            $('#btn_generate_payroll').show();
                        }
                        $('#exists_err_msg').html('');
                    }
                }
            });
        }
    </script>
@endsection
