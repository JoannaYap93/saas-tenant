@extends('layouts.master')

@section('title')
    Setting Tree Age Listing
@endsection

@section('css')
    <style></style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0 font-size-18 mr-2">Setting Tree Age Listing</h4>
                    @can('setting_tree_age')
                        <a href="{{ route('setting_tree_age_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Tree Age</a>
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
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
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
                                <th>#</th>
                                <th>Tree Age</th>
                                <th>Circumference</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @php
                                    $no = $records->firstItem();
                                @endphp
                                @forelse ($records as $rows)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ @$rows->setting_tree_age }}</td>
                                        <td>
                                            @if (@$rows->setting_tree_age_lower_circumference == 0)
                                                Below {{ @$rows->setting_tree_age_upper_circumference }}
                                            @elseif (@$rows->setting_tree_age_lower_circumference == $rows->setting_tree_age_upper_circumference)
                                                {{ @$rows->setting_tree_age_lower_circumference }}
                                            @elseif (@$rows->setting_tree_age_upper_circumference == 0)
                                                Above {{ @$rows->setting_tree_age_lower_circumference }}
                                            @else
                                                {{ @$rows->setting_tree_age_lower_circumference }}-{{ @$rows->setting_tree_age_upper_circumference }}
                                            @endif
                                            Inch
                                        </td>
                                        <td>
                                            @can('setting_tree_age')
                                                <a href="{{ route('setting_tree_age_edit', ['tenant' => tenant('id'), 'id' => @$rows->setting_tree_age_id]) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            @endcan
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
@endsection
