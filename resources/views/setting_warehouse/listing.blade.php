@extends('layouts.master')

@section('title') Setting Warehouse Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">Setting Warehouse Listing</h4>
                    @can('warehouse_manage')
                        <a href="{{ route('setting_warehouse_add') }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Warehouse </a>
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
                                            <label for="validationCustom03">Warehouse Status</label>
                                            {!! Form::select('warehouse_status', $warehouse_status_sel, @$search['warehouse_status'], ['class' => 'form-control select2 warehouse_status']) !!}
                                        </div>
                                    </div>
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationCustom03">Company</label> {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control select2 company_id']) !!}
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
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Warehouse Name</th>
                                    <th>Status</th>
                                    <th>Ranking</th>
                                    <th>Own by Company</th>
                                    @can('warehouse_manage')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    <?php $i = 1; ?>
                                    @foreach ($records as $key => $row)
                                        @if ($row->is_deleted == 0)
                                            @php
                                                $status = $row->warehouse_status;
                                                $span_status = '';
                                                switch ($status) {
                                                    case 'active':
                                                        $span_status = "<span class='badge badge-info font-size-11'>Active</span>";
                                                        break;
                                                    case 'inactive':
                                                        $span_status = "<span class='badge badge-danger font-size-11'>Inactive</span>";
                                                        break;
                                                    default:
                                                        break;
                                                }
                                            @endphp

                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $row->warehouse_name }}</td>
                                                <td>{!! $span_status ?? '' !!}</td>
                                                <td>{{ $row->warehouse_ranking }}</td>

                                                @if ($row->company)
                                                    <td>{{ $row->company->company_name }}</td>
                                                @else
                                                    <td> - </td>
                                                @endif

                                                @can('warehouse_manage')
                                                    <td>
                                                        <a href="{{ route('setting_warehouse_edit', $row->warehouse_id) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->warehouse_id }}">Delete </button>
                                                    </td>
                                                @endcan

                                            </tr>
                                            <?php $i++; ?>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td>No record found.</td>
                                    </tr>
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
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('setting_warehouse_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Warehouse ?</h4>
                        <input type="hidden" name="warehouse_id" id="warehouse_id">
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
        $(document).ready(function(e) {
            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                $(".modal-body #warehouse_id").val(id);
            });
        });
    </script>
@endsection
