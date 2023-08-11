@extends('layouts.master')
@section('title')
    Budget Estimate Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .log {
                cursor: pointer;
            }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    <span class="mr-2 ">Budget Estimate Listing</span>
                    @can('budget_estimate_manage')
                        <a href="{{route('budget_estimate_report_add', ['tenant' => tenant('id')])}}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a>
                    @endcan
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Budget Estimate</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
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
                                                <input type="text" style="width: 75px" class="form-control" name="year" placeholder="{{date('Y')}}" value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @if(auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mt-3">
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
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <th style="width: 70px;">#</th>
                                <th>Budget Estimate Detail</th>
                                <th>Total Budget Estimate</th>
                                <th>Budget Estimate Created</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php
                                        $no = $records->firstItem();
                                    @endphp
                                    @foreach ($records as $budget)
                                        @php
                                            $expense_log = '<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>';
                                            if (@$budget->budget_estimated_log) {
                                                foreach ($budget->budget_estimated_log as $key => $log) {
                                                    $expense_log .= '<tr>';
                                                    $log_user = '';
                                                    $expense_log .= "<td>{$log->user->user_fullname}</td>";
                                                    $expense_log .= "<td>{$log->budget_estimated_log_action}</td><td>{$log->budget_estimate_log_created}</td>";
                                                    $expense_log .= '</tr>';
                                                }
                                            } else {
                                                $expense_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                            }
                                            $expense_log .= '</tbody></table>';
                                            $expense_log = str_replace("'", '`', $expense_log);

                                            $log_info = "<span class='badge badge-dark font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($expense_log) . "'>Log</span>";
                                        @endphp
                                        <tr style="text-align: left">
                                            <td>
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                <b class="align-middle mr-1">{{$budget->budget_estimated_title}}</b><br>
                                                <span>{{$budget->budget_estimated_company->company_name}}</span><br>
                                                <span>{{$budget->budget_estimated_year}}</span>
                                            </td>
                                            <td style="white-space:normal; min-width:100px">
                                                <b>RM {{ $budget->budget_estimated_amount}}</b>
                                            </td>
                                            <td style="white-space:normal; min-width:100px">
                                                <b>{{ date_format(new DateTime($budget->budget_estimated_created), 'Y-m-d h:i A') }} {!! $log_info !!}</b><br>
                                                <i>{{ @$budget->budget_estimated_user->user_fullname }}</i>
                                            </td>
                                            @can('budget_estimate_manage')
                                                <td>
                                                    <a href="{{ route('budget_estimate_report_edit', ['tenant' => tenant('id'), $budget->budget_estimated_id]) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                        data-target="#delete" data-id="{{ $budget->budget_estimated_id }}">Delete
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                @else
                                    <td colspan="5">No records found</td>
                                @endif
                            </tbody>
                        </table>
                        {!! $records->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('budget_estimate_report_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Budget Estimate Report ?</h4>
                        <input type="hidden" name="budget_estimate_report_id" id="budget_estimate_report_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    {{-- Order Log --}}
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
    {{-- End Order Log --}}
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

            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                $(".modal-body #budget_estimate_report_id").val(id);
            });
        });

        $("#datepicker7").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });

        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });
    </script>
@endsection
