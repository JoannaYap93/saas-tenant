@extends('layouts.master')

@section('title')
    Company Profit & Loss Item Listing
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                <span class="mr-2">Profit & Loss Item Listing</span>
                {{-- @if (auth()->user()->user_type_id == 1) --}}
                    @can('pnl_item_manage')
                        <a href="{{ route('company_pnl_item_add') }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                            <i class="fas fa-plus"></i> Add New</a>
                    @endcan
                {{-- @endif --}}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Profit & Loss Item</a>
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
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                @can('pnl_item_manage')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = $records->firstItem();
                            @endphp
                            @forelse ($records as $rows)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $rows->company_pnl_item_name ?? '-' }}</td>
                                    <td>{{ $rows->company_pnl_item_code ?? '-' }}</td>
                                    <td>{{ $rows->company_pnl_item_desc ?? '-' }}</td>
                                    @can('pnl_item_manage')
                                        <td>
                                            <span>
                                                <a href="{{ route('company_pnl_item_edit', $rows->company_pnl_item_id) }}" class="btn btn-sm btn-outline-warning mr-2 mb-1">Edit</a>
                                            </span>
                                        </td>
                                    @endcan
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
<!-- End Page-content -->
@endsection


