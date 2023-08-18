@extends('layouts.master')

@section('title')
    Setting Raw Material Listing
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
                  <h4 class="mb-0 font-size-18 mr-2">Raw Material Listing</h4>
                    @can('raw_material_manage')
                    {{-- @if(auth()->user()->user_type_id == 1) --}}
                    <a href="{{ route('setting_raw_material_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    {{-- @endif --}}
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Raw Material Listing</a>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Category</label>
                                            {!! Form::select('raw_material_category_id', $category_sel, @$search['raw_material_category_id'], ['class' => 'form-control', 'id' => 'raw_material_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Quantity</label>
                                            {!! Form::select('raw_material_quantity_unit', $quantity_sel, @$search['raw_material_quantity_unit'], ['class' => 'form-control', 'id' => 'raw_material_quantity_unit']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Value</label>
                                            {!! Form::select('raw_material_value_unit', $value_sel, @$search['raw_material_value_unit'], ['class' => 'form-control', 'id' => 'raw_material_value_unit']) !!}
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
                                <th>Raw Material Details</th>
                                <th>Company</th>
                                <th>Date</th>
                                <th>Status</th>
                                @can('raw_material_manage')
                                <th>Action</th>
                                @endcan
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i = $records->firstItem() @endphp
                                    @foreach ($records as $record)
                                    @php
                                    $raw_material_name_en = null;
                                    $company_list = '';
                                        if ($record->raw_material_company) {
                                            foreach ($record->raw_material_company->where('company_id', '!=', 1)->groupBy('company_id') as $key => $raw_company) {
                                                $company_list .= "<span class='badge badge-soft-dark'> {$raw_company[0]->company->company_name}</span> ";
                                            }
                                        }
                                    @endphp
                                        <tr>
                                        <td>{{ $i++ }}</td>

                                        <td>
                                          <b>{{ json_decode(@$record->raw_material_name)->en}}</b>-<span style="font-style: italic;">{{ json_decode(@$record->setting_raw_category->raw_material_category_name)->en }}</span><br>
                                          <span style="font-style: italic;">{{ @$record->raw_material_quantity_unit }}-{{ @$record->raw_material_value_unit }}</span><br>
                                        </td>
                                        <td style="white-space:normal">
                                            {!! $company_list !!}
                                        </td>
                                        <td>{{ date_format(new DateTime(@$record->raw_material_created), 'Y-m-d h:i A') }}</td>
                                        <td>
                                            @php
                                                $show_status = '';
                                                if (@$record->raw_material_status) {
                                                $status = @$record->raw_material_status;
                                                    switch ($status) {
                                                        case 'active':
                                                            $show_status = "<span class='badge badge-success font-size-11 log'>Active</span>";
                                                            break;
                                                        case 'pending':
                                                            $show_status = "<span class='badge badge-warning font-size-11 log'>Pending</span>";
                                                            break;
                                                        case 'deleted':
                                                            $show_status = "<span class='badge badge-danger font-size-11 log'>Deleted</span>";
                                                            break;
                                                    }
                                                }
                                                echo $show_status;
                                            @endphp
                                        </td>
                                        <td>
                                            @can('raw_material_manage')
                                            <a href="{{ route('setting_raw_material_edit', ['tenant' => tenant('id'), 'id' => @$record->raw_material_id, $record->raw_material_company_id]) }}"
                                                class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            @endcan
                                        </td>
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
                        {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
