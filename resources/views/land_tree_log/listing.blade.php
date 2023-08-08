@extends('layouts.master')
@section('title')
    Tree Log Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">

    <style>
        .bg-grey {
            background: #e4e4e4;
        }

        .bg-red {
            background: #f46a6a;
            color: #ffffff
        }

        table {
            text-align: left;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Tree Log Listing -
                        {{ $zone != null ? $zone->company_land_zone_name : $land->company_land_name }}</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Tree Log Listing</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="POST"
                                action="{{ route('land_tree_log_listing', ['land_id' => $land->company_land_id ?? 0, 'id' => $company_land_tree_id]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date Logged:</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">

                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="start_date" placeholder="Start Date"
                                                    value="{{ @$search['start_date'] }}" id="start_date" autocomplete="off">

                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="end_date" placeholder="End Date"
                                                    value="{{ @$search['end_date'] }}" id="end_date" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date Created:</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">

                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="start_date_created" placeholder="Start Date"
                                                    value="{{ @$search['start_date_created'] }}" id="start_date_created"
                                                    autocomplete="off">

                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="end_date_created" placeholder="End Date"
                                                    value="{{ @$search['end_date_created'] }}" id="end_date_created"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- @if (auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif --}}
                                    {{-- <div class="col-md-3">
                                        <label for="">Land: </label>
                                        <select name="company_land_id" class="form-control" id="company_land_id">

                                        </select>
                                        {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control']) !!}
                                    </div> --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">User: </label>
                                            {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control select2']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Action: </label>
                                            {!! Form::select('action_id', $action_sel, @$search['action_id'], ['class' => 'form-control select2', 'id' => 'action_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Zone: </label>
                                            {!! Form::select('company_land_zone_id', $zone_sel, @$search['company_land_zone_id'], ['class' => 'form-control select2', 'id' => 'company_land_zone_id']) !!}
                                        </div>
                                    </div>
                                    @if($tree_sel)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Tree No.: </label>
                                            {!! Form::select('company_land_tree_id', $tree_sel, @$search['company_land_tree_id'], ['class' => 'form-control select2', 'id' => 'company_land_tree_id']) !!}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Product: </label>
                                            {!! Form::select('product_id', $product_sel, @$search['product_id'], ['class' => 'form-control select2', 'id' => 'product_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        {{-- <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button> --}}
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                        @if (@$zone)
                                            <a href="{{ route('land_tree_listing', $zone->company_land_zone_id) }}"
                                                class="btn btn-secondary waves-effect waves-light mr-2" name="submit">
                                                <i class="fas fa-arrow-left mr-1"></i> Tree Listing
                                            </a>
                                        @else
                                            <a href="{{ route('company_listing') }}"
                                                class="btn btn-secondary waves-effect waves-light mr-2" name="submit">
                                                <i class="fas fa-arrow-left mr-1"></i> Company Listing
                                            </a>
                                        @endif
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if($tree_sel)
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <p><strong>{{ @$company_land_detail->company->company_name }}<br />
                                        <i>{{ @$company_land_detail->company_land_name }}</i><br /><small>{{ @$company_land_detail->company_land_total_acre > 0 ? @$company_land_detail->company_land_total_acre . ' acres' : '' }}</small></strong>
                                </p>
                            </div>
                        </div>
                    @else
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <p><strong>{{ $company }}<br />
                                    <i>{{ @$records[0]->company_land_tree->company_land->company_land_name }}</i><br /><small>{{ @$records[0]->company_land_tree->company_land->company_land_total_acre > 0 ? @$records[0]->company_land_tree->company_land->company_land_total_acre . ' acres' : '' }}</small></strong>
                            </p>
                        </div>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 120px;">Zone</th>
                                    <th style="width: 120px;">Tree Number</th>
                                    <th>Product</th>
                                    <th>Log Details</th>
                                    <!-- <th>Date Logged</th> -->
                                    <!-- <th>Action Taken</th> -->
                                    <!-- <th>Description</th> -->
                                    <th>Log Created</th>
                                    @can('company_manage')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>

                                @if ($records->isNotEmpty())
                                    @foreach ($records as $row)
                                        <tr>
                                            <td>
                                                <b>{{ @$row->company_land_tree->company_land_zone->company_land_zone_name }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ @$row->company_land_tree->company_land_tree_no }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ @$row->company_land_tree->product->product_name }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ @$row->company_land_tree_action->company_land_tree_action_name }}</b>{{ @$row->company_land_tree_log_value > 0 ? ' - ' . @$row->company_land_tree_log_value . 'ml' : '' }}<br>
                                                {{ @$row->company_land_tree_log_description }}<br>
                                                <span
                                                    style="font-style: italic">{{ @$row->company_land_tree_log_date }}</span>
                                            </td>

                                            <td>
                                                <b>{{ date_format(@$row->company_land_tree_log_created, 'Y-m-d h:i A') }}</b><br>
                                                BY: <span
                                                    style="font-style: italic">{{ @$row->user->user_fullname }}</span>
                                            </td>

                                            @can('company_manage')
                                                <td>
                                                    <span data-toggle='modal' data-target='#fulfill'
                                                        data-id='{{ @$row->company_land_zone_id }}' class='fulfill'>
                                                        @if (auth()->user()->user_type_id == 2)
                                                            <a href="{{ route('land_tree_log_edit', @$row->company_land_tree_log_id) }}"
                                                                class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Edit</a>
                                                            <!-- <a href="#"
                                                                        class="btn btn-sm btn-outline-warning waves-effect waves-light mr-2 mb-1">Export Tree</a> -->
                                                            {{-- <button class="btn btn-sm btn-outline-info mr-1 mb-1 import"
                                                                    data-toggle="modal" data-zone-id="{{$zone->company_land_zone_id}}"
                                                                    data-company-land-id={{$company_land_id}}
                                                                    data-zone-name="{{$zone->company_land_zone_name}}"
                                                                    data-tree-count="{{count($zone->company_land_tree)}}"
                                                                    data-target="#import">Import</button> --}}
                                                        @endif
                                                    </span>
                                                </td>
                                            @endcan


                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Records!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>

    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        let zid = '{{ @$search['company_land_zone_id'] }}' ?? null;

        $(document).ready(function(e) {

            $("#invoice_daily_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true
            });

            if (zid > 0) {
                get_tree_by_zone(zid);
            }

            $('.select2').select2();
        });

        $('#datepicker6').datepicker({
            orientation: "bottom left",
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
            let action = '<option value="">Please Select Action</option>';
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
                                    element.company_land_name + '</option>';
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
                                    .user_fullname + '</option>';
                            } else {
                                user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                    '</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }

                    $('#tree_action').html('<option value="">No Action</option>');
                }
            });
        }

        $(document).on('change', '#company_land_zone_id', function() {
            let zone_id = $(this).val();
            get_tree_by_zone(zone_id);
        });

        function get_tree_by_zone(zone_id) {
            let sel_tree_no = '{{ @$search['company_land_tree_id'] }}' ?? null;
            let tree = '<option value="">Please Select Tree No.</option>';

            $('#company_land_tree_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_tree_log_by_zone') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_land_zone_id: zone_id
                },
                success: function(e) {
                    if (e.length > 0) {
                        e.forEach(element => {
                            if (sel_tree_no != null && sel_tree_no == element.id) {
                                tree += '<option value="' + element.id + '" selected>' + element.label +
                                    '</option>';
                            } else {
                                tree += '<option value="' + element.id + '">' + element.label +
                                    '</option>';
                            }

                        });
                        $('#company_land_tree_id').html(tree);
                    } else {
                        $('#company_land_tree_id').html('<option value="">No Tree</option>');
                    };
                }
            });
        }
    </script>
@endsection
