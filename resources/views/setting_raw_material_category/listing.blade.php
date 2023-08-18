@extends('layouts.master')

@section('title')
    Setting Raw Material Category Listing
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
                  <h4 class="mb-0 font-size-18 mr-2">Raw Material Category Listing</h4>
                  @can('raw_material_category_manage')
                      <a href="{{ route('setting_raw_material_category_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                  @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Raw Material Category</a>
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
                                <th>Name</th>
                                @can('raw_material_category_manage')
                                <th>Action</th>
                                @endcan
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    <?php $i = $records->firstItem(); ?>
                                    @foreach ($records as $category)
                                        <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                          <b>{{ json_decode(@$category->raw_material_category_name)->en  }}</b><br>
                                        </td>
                                        @can('raw_material_category_manage')
                                            <td>
                                            <a href="{{ route('setting_raw_material_category_edit', ['tenant' => tenant('id'), 'id' => @$category->raw_material_category_id]) }}"
                                                class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            </td>
                                        @endcan
                                        </tr>
                                    <?php $i++; ?>
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
@endsection

