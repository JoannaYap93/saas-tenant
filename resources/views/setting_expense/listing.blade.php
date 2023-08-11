@extends('layouts.master')

@section('title')
    Expense Listing
@endsection

@section('css')
    <style>

    </style>
@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Expense Listing</h4>
                    @can('setting_expense')
                         <a href="{{ route('expense_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Expense</a>
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
                                            <input type="text" class="form-control" name="freetext"
                                                   placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Expense Category</label>
                                            {!! Form::select('setting_expense_category_id', $expense_category_sel, @$search['setting_expense_category_id'], ['class' => 'form-control', 'id' => 'setting_expense_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Subcon</label>
                                            {!! Form::select('is_subcon_allow', $is_subcon_allow, @$search['is_subcon_allow'], ['class' => 'form-control', 'id' => 'is_subcon_allow']) !!}
                                        </div>
                                    </div>
                                    {{-- @if (auth()->user()->company_id == 0) --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="company_id">Company</label>
                                            {!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Worker Role</label>
                                            {!! Form::select('worker_role_id', $worker_role_sel, @$search['worker_role_id'], ['class' => 'form-control', 'id' => 'worker_role_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                                name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                                name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
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
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Expense Details</th>
                                    <th>Expense Descrption</th>
                                    <th>Expense Value</th>
                                    <th>Expense Subcon</th>
                                    @if(auth()->user()->user_type_id == 1)
                                        <th>Overwrite Company</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = $records->firstItem();
                                @endphp

                                @forelse ($records as $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><b>{{ json_decode($row->setting_expense_name)->en }}</b><br>
                                        <span style="font-style: italic">
                                            @if(!empty($row->setting_expense_category_id))
                                                {{ json_decode($row->expense_category->setting_expense_category_name)->en }}
                                            @endif
                                        </span><br>
                                        @if(@$row->setting_expense_status == 'active')
                                        <span class="badge badge-primary font-size-10">Active</span>
                                        @else
                                        <span class="badge badge-danger font-size-10">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        Role: <b>{{ @$row->worker_role->worker_role_name }}</b><br>
                                        {{ Str::limit(@$row->setting_expense_description, 50) }}
                                    </td>
                                    <td>
                                        @if($row->expense_overwrite && Auth::user()->user_type_id != 1)
                                            RM {{ @$row->expense_overwrite->setting_expense_overwrite_value }}
                                        @else
                                            RM {{ $row->setting_expense_value }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->expense_overwrite && Auth::user()->user_type_id != 1)
                                            @if($row->expense_overwrite->is_subcon_allow == 0 )
                                                <div>
                                                    <span class="badge badge-warning font-size-10">No Subcon Allow</span>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="badge badge-success font-size-10">Subcon Allow</span>
                                                </div>
                                                RM {{ @$row->expense_overwrite->setting_expense_overwrite_subcon }}
                                            @endif
                                        @else
                                            @if($row->is_subcon_allow == 0)
                                                <div>
                                                    <span class="badge badge-warning font-size-10">No Subcon Allow</span>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="badge badge-success font-size-10">Subcon Allow</span>
                                                </div>
                                                RM {{ @$row->setting_expense_subcon }}
                                            @endif
                                        @endif
                                    </td>
                                    @if(auth()->user()->user_type_id == 1)
                                        <td>
                                            @if($row->expense_overwrite != null && count(@$row->expense_overwrite)>0)
                                                @php
                                                    $count = 0;
                                                @endphp
                                                @foreach($row->expense_overwrite as $expenses_overwrite)
                                                    @if($expenses_overwrite->overwrite_company)
                                                        <div class="waves-effect waves-light" data-toggle="modal" data-target=".overwrite-detail" onClick="overwriteDetail({{$row->setting_expense_id}}, {{$expenses_overwrite->company_id}})">{{ @$expenses_overwrite->overwrite_company->company_name }}</div>
                                                    @endif
                                                        @php
                                                            $count++
                                                        @endphp
                                                    @if($count != count($row->expense_overwrite))
                                                            <br>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    -
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if (auth()->user()->user_type_id == 1)
                                            <a class="btn btn-outline-warning btn-sm mr-2"
                                                href="{{ route('expense_edit', ['tenant' => tenant('id'), 'id' => $row->setting_expense_id]) }}">
                                                Edit
                                            </a>
                                            @if(@$row->setting_expense_status == 'active')
                                            <a class="btn btn-outline-danger btn-sm mr-2"
                                                href="{{ route('expense_activation', ['tenant' => tenant('id'), 'id' => $row->setting_expense_id, 'status' => 'inactive']) }}">
                                                Deactivate
                                            </a>
                                            @else
                                            <a class="btn btn-outline-primary btn-sm mr-2"
                                                href="{{ route('expense_activation', ['tenant' => tenant('id'), 'id' => $row->setting_expense_id, 'status' => 'active']) }}">
                                                Activate
                                            </a>
                                            @endif
                                        @else
                                            @if (@$row->expense_overwrite->company_id === auth()->user()->company_id)
                                                <a href="{{ route('expense_overwrite', ['tenant' => tenant('id'), 'company_id' => auth()->user()->company_id, 'setting_expense_id' => $row->setting_expense_id]) }}" class="btn btn-outline-warning btn-sm mr-1">
                                                    Edit
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                        data-target="#delete" onclick="DeleteOverwrite({{$row->setting_expense_id}}, {{auth()->user()->company_id}})" data-id="{{ $row->setting_expense_id }}">Cancel Overwrite
                                                </button>
                                            @else
                                                <a class="btn btn-outline-primary btn-sm mr-2"
                                                    href="{{ route('expense_overwrite', ['tenant' => tenant('id'), 'company_id' => auth()->user()->company_id, 'setting_expense_id' => $row->setting_expense_id]) }}">
                                                    Overwrite
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No record found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('expense_overwrite_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Cancel this Expense Overwrite ?</h4>
                        <input type="hidden" name="setting_expense_id" id="setting_expense_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Proceed</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade overwrite-detail" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="overwrite_detail" aria-hidden="true" id="overwrite-detail-modal">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" id="overwrite-detail">

        </div>
    </div>

@endsection

@section('script')
    <script>

        function overwriteDetail(setting_expense_id, company_id){
            $('#overwrite-detail').html('');
            $.ajax({
                type: 'POST',
                url: '{{ route("expense_overwrite_detail_modal", ['tenant' => tenant('id')]) }}',
                data: {
                    _token: '{{csrf_token()}}',
                    setting_expense_id: setting_expense_id,
                    company_id: company_id
                },
                success: function (e) {
                    $('#overwrite-detail').html(e);
                    $('#overwrite-detail-modal').modal('show');
                }
            })
        }

        function DeleteOverwrite(setting_expense_id) {
            document.getElementById("setting_expense_id").value = setting_expense_id
        }
    </script>
@endsection
