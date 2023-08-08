@extends('layouts.master')

@section('title')
    Expense Category Listing
@endsection

@section('css')

@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Expense Category Listing</h4>
                    @can('setting_expense')
                        <a href="{{ route('expense_category_add') }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Expense Category</a>
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
            <form method="POST" action="{{ $submit }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext" placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2" name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                                <th style="width: 300px;">Expense Category Name</th>
                                <th>Budget</th>
                                <th>Group</th>
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
                                        <td>
                                            <b>{{ json_decode($row->setting_expense_category_name)->en }}</b><br>
                                        </td>
                                        <td>
                                            @if($row->setting_expense_category_budget)
                                              <b>RM: </b>{{$row->setting_expense_category_budget}}
                                            @else
                                              -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->setting_expense_category_group }}
                                        </td>
                                        <td>
                                            @if (auth()->user()->user_type_id == 1)
                                                <a href="{{ route('expense_category_edit', $row->setting_expense_category_id) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                {{-- <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->setting_expense_id }}">Delete</button> --}}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No record found.</td>
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
{{--    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-dialog-centered" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <form method="POST" action="{{ route('expense_delete') }}">--}}
{{--                    @csrf--}}
{{--                    <div class="modal-body">--}}
{{--                        <h4>Delete this Expense ?</h4>--}}
{{--                        <input type="hidden" name="setting_expense_id" id="setting_expense_id">--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection

@section('script')
    <script>
        $(document).ready(function(e) {
            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                $(".modal-body #setting_expense_id").val(id);
            });
        });
    </script>
@endsection