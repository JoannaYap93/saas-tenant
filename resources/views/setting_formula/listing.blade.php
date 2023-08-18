@extends('layouts.master')

@section('title') {{$title}} Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">{{$title}} Listing</h4>
                    {{-- @can('product_tag_manage') --}}
                    @can('formula_manage')
                        {{-- @if(auth()->user()->user_type_id == 2) --}}
                        <a href="{{ route('setting_formula_add', ['tenant' => tenant('id')]) }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                class="fas fa-plus"></i> ADD NEW</a>
                        {{-- @endif --}}
                    @endcan

                    {{-- @endcan --}}
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Formula</a>
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
                                        <label>Category</label>
                                        {!! Form::select('setting_formula_category_id', $setting_formula_category_sel, @$search['setting_formula_category_id'], ['class' => 'form-control', 'id' => 'setting_formula_category_id']) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        {!! Form::select('setting_formula_status', $setting_formula_status_sel, @$search['setting_formula_status'], ['class' => 'form-control', 'id' => 'setting_formula_status']) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Measurement</label>
                                        {!! Form::select('setting_formula_measurement', $setting_formula_measurement_sel, @$search['setting_formula_measurement'], ['class' => 'form-control', 'id' => 'setting_formula_measurement']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Raw Material</label>
                                        {!! Form::select('raw_material_id', $raw_material_sel, @$search['raw_material_id'], ['class' => 'form-control', 'id' => 'raw_material_id']) !!}
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
                                <th scope="col" style="width: 70px; text-align:center">#</th>
                                <th>Formula Detail</th>
                                <th>Formula Item</th>
                                <th>Formula Created</th>
                                @can('formula_manage')
                                    <th>Action</th>
                                @endcan

							</tr>
						</thead>
						<tbody>
                            @php
                                $no = $records->firstItem();
                            @endphp
							@forelse ($records as $rows)
                                @php
                                    $setting_formula_status = '';
                                    switch ($rows->setting_formula_status) {
                                        case 'pending':
                                            $setting_formula_status = "<span class='badge badge-warning mr-2 font-size-11'>Pending</span>";
                                            break;
                                        case 'active':
                                            $setting_formula_status = "<span class='badge badge-success mr-2 font-size-11'>Active</span>";
                                            break;
                                        case 'deleted':
                                            $setting_formula_status = "<span class='badge badge-danger mr-2 font-size-11'>Deleted</span>";
                                            break;
                                        default:
                                            break;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <b>{{ json_decode(@$rows->setting_formula_name)->en}}</b>-<i>{{ json_decode($rows->setting_formula_category->setting_formula_category_name)->en }}</i> <br>
                                        <i>{{ $rows->company->company_name }}</i><br>
                                        <b>{{ $rows->setting_formula_measurement != '' ? $rows->setting_formula_measurement : '-' }}</b>

                                    </td>
                                    <td>
                                        @if (count($rows->setting_formula_item) != 0)
                                          @if($rows->setting_formula_category_id == 2 || $rows->setting_formula_category_id == 3)
                                            <div class="table-responsive">
                                                <table class="table small-table table-striped mb-0">

                                                    <thead>
                                                        <tr>
                                                            <th style="padding-left: 10px !important">#</th>
                                                            <th>Item Details</th>
                                                            <th>Raw Material Detail</th>
                                                            <th>Item Values</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rows->setting_formula_item as $key => $item)
                                                            <tr>
                                                                <th style="padding-left: 10px !important" scope="row">{{$key+1}}</th>
                                                                <td>
                                                                    <b>{{ json_decode($item->setting_formula_item_name)->en }}</b>
                                                                </td>
                                                                <td>
                                                                    {{ json_decode(@$item->raw_material->raw_material_name)->en }} <br>
                                                                </td>
                                                                <td>
                                                                    <b>{{ $item->setting_formula_item_value }}</b>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                          @else
                                          -
                                          @endif
                                        @else
                                            No setting formula item records
                                        @endif

                                    </td>
                                    <td>
                                        {!! $setting_formula_status ?? '' !!}<br>
                                        <b>{{ date_format($rows->setting_formula_created, 'Y-m-d h:i A') }}</b>
                                    </td>
                                    <td>
                                      @if (auth()->user()->user_type_id == 2)
                                          <br>
                                          @can('formula_manage')

                                              <a href="{{ route('setting_formula_edit', ['tenant' => tenant('id'), 'id' => $rows->setting_formula_id]) }}"class="btn btn-outline-warning btn-sm mt-2 mr-2">Edit</a>
                                              <button class="btn btn-sm btn-outline-danger mt-2 delete" data-toggle="modal"
                                                  data-target="#delete" data-id="{{ $rows->setting_formula_id }}">Delete
                                              </button>

                                          @endcan
                                      @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No record found.</td>
                                </tr>
                            @endforelse
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
                <form method="POST" action="{{ route('setting_formula_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Formula ?</h4>
                        <input type="hidden" name="setting_formula_id" id="setting_formula_id">
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
			$(".modal-body #setting_formula_id").val(id);
		});
	});
</script>
@endsection
