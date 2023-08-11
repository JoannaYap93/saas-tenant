@extends('layouts.master')

@section('title') Company Expense Listing @endsection

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
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Company Expense Listing</h4>
                    {{-- @if (auth()->user()->user_type_id == 2) --}}
                        @can('company_expense_manage')
                            <a href="{{route('company_expense_add', ['tenant' => tenant('id')])}}"
                                class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                                <i class="fas fa-plus"></i> ADD NEW</a>
                            <a href="{{route('company_expense_add_labour', ['tenant' => tenant('id')])}}"
                                class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">
                                <i class="fas fa-plus"></i> ADD NEW LABOUR EXPENSE</a>
                        @endcan
                    {{-- @endif --}}
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Expense</a>
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
            {{-- @if (auth()->user()->user_type_id == 2) --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form method="POST" action="{{ route('company_expense_listing', ['tenant' => tenant('id')]) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <div class="input-daterange input-group" id="datepicker6"
                                                    data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                    data-provide="datepicker" data-date-container="#datepicker6">
                                                    <input type="text" style="width: 75px" class="form-control"
                                                        name="date_from" placeholder="Start Date"
                                                        value="{{ @$search['date_from'] }}" id="start" autocomplete="off">
                                                    <input type="text" style="width: 75px" class="form-control"
                                                        name="date_to" placeholder="End Date"
                                                        value="{{ @$search['date_to'] }}" id="end" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationCustom03">Freetext</label>
                                                <input type="text" class="form-control" name="freetext"
                                                    placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                            </div>
                                        </div>
                                        @if (auth()->user()->company_id == 0)
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Company: </label>
                                                    {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company Land: </label>
                                                {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Expense Category: </label>
                                                {!! Form::select('expense_category_id', $expense_category_sel, @$search['expense_category_id'], ['class' => 'form-control', 'id' => 'expense_category_id']) !!}
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Expense Name: </label>
                                                {!! Form::select('expense_id', $expense_sel, @$search['expense_id'], ['class' => 'form-control', 'id' => 'expense_id']) !!}
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Expense Type: </label>
                                                {!! Form::select('comp_expense_type', $expense_type_sel, @$search['comp_expense_type'], ['class' => 'form-control', 'id' => 'comp_exspense_type']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">User: </label>
                                                {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control', 'id' => 'user_id']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary  waves-effect waves-light mb-2 mr-2"
                                                    name="submit" value="search">
                                                    <i class="fas fa-search mr-1"></i> Search
                                                </button>
                                                <button type="submit"
                                                    class="btn btn-danger  waves-effect waves-light mb-2 mr-2" name="submit"
                                                    value="reset">
                                                    <i class="fas fa-times mr-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endif --}}

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px; text-align:center">#</th>
                                    <th>Company Expense Detail</th>
                                    <th>Expense Item</th>
                                    <th>Expense Land</th>
                                    <th>Expense Total</th>
                                    <th>Company Expense Created</th>
                                    @can('company_expense_manage')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                        @foreach ($records as $row)
                                        @php
                                            $status = '';
                                            $expense_log = '<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>';
                                            if (@$row->company_expense_log) {
                                                foreach ($row->company_expense_log as $key => $log) {
                                                    $expense_log .= '<tr>';
                                                    $log_user = '';
                                                    $expense_log .= "<td>{$log->user->user_fullname}</td>";
                                                    $expense_log .= "<td>{$log->company_expense_log_description}</td><td>{$log->company_expense_log_created}</td>";
                                                    $expense_log .= '</tr>';
                                                }
                                            } else {
                                                $expense_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                            }
                                            $expense_log .= '</tbody></table>';
                                            $expense_log = str_replace("'", '`', $expense_log);

                                            $log_info = "<span class='badge badge-dark font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($expense_log) . "'>Log</span>";

                                        @endphp
                                            <tr>
                                                <td align="center">
                                                    {{ $i }}
                                                </td>
                                                <td>
                                                    @php
                                                        $format_date = $row->company_expense_year.'-'.$row->company_expense_month.'-'.$row->company_expense_day;
                                                        $date = date('d/m/Y', strtotime($format_date));
                                                    @endphp
                                                    <b>{{ $row->company_expense_number}}</b> <br>
                                                    <b><i>{{ @$row->company_land->company_land_name}}</i></b><br>
                                                    <i>{{$date}}</i> <br>
                                                    <i>{{ json_decode($row->expense_category->setting_expense_category_name)->en }}</i> <br>
                                                    @php
                                                    $show_type='';
                                                        switch($row->company_expense_type){
                                                            case 'daily':
                                                                $show_type = "<span class='badge badge-warning font-size-11'>Daily</span>";
                                                                break;
                                                            case 'monthly':
                                                                $show_type = "<span class='badge badge-success font-size-11'>Monthly</span>";
                                                                break;
                                                        }
                                                    @endphp

                                                    <i>{!!$show_type!!}
                                                    @php
                                                        if(@$row->company_expense_item){
                                                            foreach ($row->company_expense_item as $item) {
                                                                $value = $item->is_claim;
                                                            }
                                                        }
                                                    @endphp
                                                        @if(@$value == 1)
                                                            <span class='badge badge-info font-size-11'>Claim</span>
                                                        @endif
                                                    </i>
                                                </td>

                                                <td>
                                                    @if(@$row->company_expense_item)
                                                        @foreach($row->company_expense_item as $item)
                                                            <b>{{json_decode(@$item->expense->setting_expense_name)->en}} - RM {{$item->company_expense_item_total}}</b> <br>
                                                            @if(@$row->setting_expense_category_id == 2)
                                                              @foreach(json_decode($item->company_expense_item_detail) as $ids => $detail)
                                                                @if(is_numeric($ids))
                                                                  @foreach($worker_by_company as $worker_id => $worker_name)
                                                                    @if($ids == $worker_id)
                                                                      <i>{{$worker_name}}</i> - RM {{$detail->detail->expense_total}}<br>
                                                                    @endif
                                                                  @endforeach
                                                                @endif
                                                              @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </td>
                                                <td>
                                                  <div class="table-responsive">
                            													<table class="table small-table table-striped mb-0">
                            															<thead>
                            																	<tr>
                            																			<th style="padding-left: 10px !important">#</th>
                                                                  <th>Total Tree</th>
                                                                  <th>Total Price</th>
                            																	</tr>
                            															</thead>
                            															<tbody>
                            																@foreach(@$row->company_expense_land as $key => $cel)
                            																<tr>
                            																	<td style="padding-left: 10px !important">
                            																		{{@$cel->company_land->company_land_name}}
                            																	</td>
                                                              <td>
                                                                {{$cel->company_expense_land_total_tree}}
                                                              </td>
                                                              <td>
                                                                RM {{$cel->company_expense_land_total_price}}
                                                              </td>
                            																</tr>
                            																@endforeach
                            															</tbody>
                            													</table>
                            											</div>
                                                </td>
                                                <td>
                                                    <b>RM {{ $row->company_expense_total }}</b>
                                                </td>
                                                <td>
                                                    <b>{{ date_format($row->company_expense_created, 'Y-m-d h:i A') }} {!! $log_info !!}</b> <br>
                                                    <i>Created By: <b>{{ @$row->user->user_fullname }}</b></i><br>
                                                    <i>Under: <b>{{ @$row->worker->worker_name }}</b></i>
                                                </td>

                                                    @can('company_expense_manage')
                                                        <td>
                                                            @if($row->setting_expense_category_id == 2)
                                                            <span data-toggle='modal' data-target='#fulfill'
                                                                data-id='{{ $row->company_expense_id }}'
                                                                class='fulfill'>
                                                                <a href="{{ route('company_expense_edit_labour', ['tenant' => tenant('id'), 'id' => $row->company_expense_id]) }}"
                                                                    class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                            </span>
                                                            @else
                                                            <span data-toggle='modal' data-target='#fulfill'
                                                                data-id='{{ $row->company_expense_id }}'
                                                                class='fulfill'>
                                                                <a href="{{ route('company_expense_edit', ['tenant' => tenant('id'), 'id' => $row->company_expense_id]) }}"
                                                                    class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                            </span>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline-danger mr-2 mb-1 delete" data-toggle="modal"
                                                                data-target="#delete"
                                                                data-id="{{ $row->company_expense_id }}">
                                                                Delete
                                                            </button>
                                                            {{-- <br/><a href="#" class="btn btn-outline-warning btn-sm mb-2 view_images ml-2"
                                                                data-id="{{ $row->company_expense_id }}"
                                                                company_expense_item_media="{{ @$row->company_expense_item_media }}"
                                                                id='view_images_{{$row->company_expense_id}}'>
                                                                <span data-toggle='modal' data-target='#view_images_modal'>View Attachment</span>
                                                            </a> --}}
                                                        </td>
                                                    @endcan
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Records!</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->
    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('company_expense_delete', ['tenant' => tenant('id')])}}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Company Expense ?</h4>
                        <input type="hidden" name="company_expense_id" id="company_expense_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    {{-- <!-- View Details Image Expense Item -->
    <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>Expense Item Attachment</b>
                    </div>
                    <div class="modal-body view_images_body">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <!-- End Modal -->

@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.delete').on('click', function() {
			var id = $(this).attr('data-id');
            $('.modal-body #company_expense_id').val(id);
        });

        @if (@$search['company_id'] != null)
            get_land_user('{{ $search['company_id'] }}');
        @else
            get_land_user('{{ auth()->user()->company_id }}');
        @endif

        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });




        $('#company_id').on('change', function() {
            let id = $(this).val();
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            land += '<option value="' + element.company_land_id + '">' + element
                                .company_land_name + '</option>';
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                '</option>';
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                }
            });
        });

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            let sland = "{{ @$search['company_land_id'] ?? null }}";
            let suser = "{{ @$search['user_id'] ?? null }}";
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
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
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            if (suser != null && u.user_id == suser) {
                                user += '<option value="' + u.user_id + '" selected>' + u
                                    .user_fullname +
                                    '</option>';
                            } else {
                                user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                    '</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                }
            });
        }
    </script>
@endsection
