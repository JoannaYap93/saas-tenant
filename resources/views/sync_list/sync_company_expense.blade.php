@extends('layouts.master')

@section('title') Sync Company Expense Listing @endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Sync Company Expense Listing</h4>
                    <!-- @if (auth()->user()->user_type_id == 2)
                        @can('company_land_category_manage')
                            <a href="{{route('company_expense_add')}}"
                                class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                                <i class="fas fa-plus"></i> ADD NEW</a>
                        @endcan
                    @endif -->
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
                                <form method="POST" action="{{ route('company_expense_listing') }}">
                                    @csrf
                                    <div class="row">
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
                                    <th>Company Expense Item</th>
                                    <th>Expense Total</th>
                                    <th>Company Expense Created</th>
                                    @if (auth()->user()->user_type_id == 2)
                                        @can('company_land_category_manage')
                                            <th>Action</th>
                                        @endcan
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                        @foreach ($records as $row)
                                            <tr>
                                                <td align="center">
                                                    {{ $i }}
                                                </td>
                                                <td>
                                                    <b>{{ $row->sync_company_expense_number}}</b>-<i>{{ $row->expense_category->setting_expense_category_name }}</i> <br>

                                                    <i>{{ @$row->company_land->company_land_name }}</i>
                                                    @php
                                                    $show_type='';
                                                        switch($row->sync_company_expense_type){
                                                            case 'daily':
                                                                $show_type = "<span class='badge badge-warning font-size-11'>Daily</span>";
                                                                break;
                                                            case 'monthly':
                                                                $show_type = "<span class='badge badge-success font-size-11'>Monthly</span>";
                                                                break;
                                                        }

                                                    @endphp
                                                    @php
                                                    $status = '';
                                                    switch($row->sync_company_expense_status){
                                                        case 'pending':
                                                            $status = "<span class='badge badge-warning font-size-11'>Pending</span>";
                                                            break;
                                                        case 'completed':
                                                            $status = "<span class='badge badge-success font-size-11'>Completed</span>";
                                                            break;
                                                        case 'deleted':
                                                            $status = "<span class='badge badge-danger font-size-11'>Deleted</span>";
                                                            break;
                                                    }
                                                    @endphp
                                                    <i>{!!$show_type!!}</i>
                                                </td>
                                                <td>
                                                  @if(@$row->sync_company_expense_item)
                                                      @foreach($row->sync_company_expense_item as $item)
                                                          <i>{{@$item->expense->setting_expense_name}}</i> - RM {{$item->sync_company_expense_item_total}} <br>
                                                      @endforeach
                                                  @else
                                                  -
                                                  @endif
                                                </td>
                                                <td>
                                                    <b>RM {{ $row->sync_company_expense_total }}</b>
                                                </td>
                                                <td>
                                                    {!!$status!!}<br>
                                                    <b>{{ date_format($row->sync_company_expense_created, 'Y-m-d h:i A') }}</b> <br>
                                                    <i>{{ $row->user->user_fullname }}</i>
                                                </td>

                                                @if (auth()->user()->user_type_id == 2)
                                                    @can('company_land_category_manage')
                                                        <td><span data-toggle='modal' data-target='#fulfill'
                                                                data-id='{{ $row->company_expense_id }}'
                                                                class='fulfill'>
                                                                <a href="{{ route('company_expense_edit', $row->company_expense_id ) }}"
                                                                    class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                            </span>
                                                            <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                                data-target="#delete"
                                                                data-id="{{ $row->company_expense_id }}">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    @endcan
                                                @endif
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
                <form method="POST" action="{{ route('company_expense_delete')}}">
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

@endsection

@section('script')

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


        $('#company_id').on('change', function() {
            let id = $(this).val();
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_land_user') }}",
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
                url: "{{ route('ajax_land_user') }}",
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
