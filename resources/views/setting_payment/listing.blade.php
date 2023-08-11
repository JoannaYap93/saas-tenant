@extends('layouts.master')

@section('title') Payment Type Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">Payment Type Listing</h4>
                    @can('setting_payment_method')
                        <a href="{{ route('setting_payment_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Payment Type </a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if (auth()->user()->user_type_id == 1)
        {{--  --}}
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
                                                <label for="">Gateway</label>
                                                {!! Form::select('is_payment_gateway', $gateway, @$search['gateway'], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="search">
                                                <i class="fas fa-search mr-1"></i> Search
                                            </button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"name="submit" value="reset">
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
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Payment Type Name</th>
                                    <th>Payment Gateway</th>
                                    @if (auth()->user()->user_type_id == 1)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = $records->firstItem();
                                @endphp
                                @forelse ($records as $row)
                                    @php
                                        $is_payment_gateway = $row->is_payment_gateway;
                                        $gateway = '';
                                        switch ($is_payment_gateway) {
                                            case '0':
                                                $gateway = 'No';
                                                break;
                                            case '1':
                                                $gateway = 'Yes';
                                                break;
                                            default:
                                                break;
                                        }

                                        $is_offline = $row->is_offline;
                                        $server = '';
                                        switch ($is_offline) {
                                            case '0':
                                                $server = '<span class="badge badge-warning font-size-11">Offline</span>';
                                                break;
                                            case '1':
                                                $server = '<span class="badge badge-success font-size-11">Online</span>';
                                                break;
                                            default:
                                                break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row->setting_payment_name }}</td>
                                        <td>{!! $gateway ?? '' !!}</td>
                                        <td>
                                            @if (auth()->user()->user_type_id == 1)
                                                <a href="{{ route('setting_payment_edit', ['tenant' => tenant('id'), 'setting_payment_id' => $row->setting_payment_id]) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->setting_payment_id }}">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No record found.</td>
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
                <form method="POST" action="{{ route('setting_payment_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this payment type?</h4>
                        <input type="hidden" name="setting_payment_id" id="setting_payment_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes</button>
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
                $(".modal-body #setting_payment_id").val(id);
            });
        });
    </script>
@endsection
