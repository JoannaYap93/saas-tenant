@extends('layouts.master')

@section('title') Product Grade Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">Product Grade Listing</h4>
                    {{-- @if (auth()->user()->user_type_id == 1) --}}
                    @can('setting_product_size')
                    <a href="{{ route('setting_product_size_add') }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                    {{-- @endif --}}
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product Grade </a>
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
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Product Grade</th>
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
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td><b>{{ $row->setting_product_size_name }}</b></td>
                                        @if (auth()->user()->user_type_id == 1)
                                            <td>
                                                <a href="{{ route('setting_product_size_edit', $row->setting_product_size_id) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                {{-- <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->setting_product_size_id }}">Delete</button> --}}
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No record found.</td>
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
                <form method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Grade ?</h4>
                        <input type="hidden" name="setting_product_size_id" id="size_id">
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
                $(".modal-body #size_id").val(id);
            });
        });
    </script>
@endsection
