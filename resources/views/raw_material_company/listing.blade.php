@extends('layouts.master')

@section('title') Raw Material Company Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Raw Material Company Listing</span>
                @can('raw_material_company_manage')
                    {{-- @if(auth()->user()->user_type_id == 2) --}}
					<a href="{{route('raw_material_company_add')}}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a>
					{{-- @endif --}}
                @endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Raw Material Company</a>
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
										<input type="text" class="form-control" name="freetext" placeholder="Search for..." value="{{ @$search['freetext'] }}">
									</div>
								</div>
								@if (auth()->user()->company_id == 0)
									<div class="col-md-3">
										<div class="form-group">
											<label for="company_id">Company</label>
											{!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
										</div>
									</div>
								@endif
								{{-- <div class="col-md-3">
									<div class="form-group">
										<label for="company_id">Company Land</label>
										{!! Form::select('company_land_id', $company_land_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
									</div>
								</div> --}}
                                <div class="col-md-3">
									<div class="form-group">
										<label for="company_id">Category</label>
                                        {!! Form::select('raw_material_category_id', $category_sel, @$search['raw_material_category_id'], ['class' => 'form-control', 'id' => 'raw_material_category_id']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="company_id">Raw Material</label>
										{!! Form::select('raw_material_id', $raw_material_sel, @$search['raw_material_id'], ['class' => 'form-control', 'id' => 'raw_material_id']) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<button type="submit" class="btn btn-primary  waves-effect waves-light mr-2" name="submit" value="search">
										<i class="fas fa-search mr-1"></i> Search
									</button>
									<button type="submit" class="btn btn-danger  waves-effect waves-light mr-2" name="submit" value="reset">
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
								{{-- <th scope="col" style="width: 50px; text-align: center;">#</th> --}}
								{{-- <th colspan="2" style="text-align: center;">Raw Material Company Details</th> --}}
                                <th>#</th>
                                <th>Raw Material Company Details</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Status</th>
                            @can('raw_material_company_manage')
                                <th>Action</th>
                            @endcan
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
                                @php
                                     $no = $records->firstItem();
                                @endphp
								@foreach($records as $rmc)
								<tr>
									<td>
										{{ $no++ }}
									</td>
									<td>
                                        @if (@$rmc->raw_material->raw_material_name != '')
                                            <a href="{{ route('rm_usage_listing_by_id', @$rmc->raw_material_id) }}">{{json_decode(@$rmc->raw_material->raw_material_name)->en }}</a><br>
                                        @endif
										{{-- <b></b><br> --}}
                                        <b>{{@$rmc->company->company_name}}</b><br>
                                        {{-- @dd(@$rmc); --}}
                                        <span style="font-style: italic;">{{json_decode(@$rmc->raw_material->setting_raw_category->raw_material_category_name)->en}}</span>
									</td>
                                    <td>
                                        <b>{{@$rmc->raw_material_quantity}}</b> {{@$rmc->raw_material->raw_material_quantity_unit == 'bottle' ? 'Bottle(s)' : 'Pack(s)'}}<br>
                                    </td>
                                    <td>
                                        <b>{{@$rmc->raw_material_value}}</b> {{@$rmc->raw_material->raw_material_value_unit == 'litre' ? 'litre' : 'kg'}}
                                    </td>
                                    <td>
                                        @php
                                        $status = @$rmc->raw_material_company_status;
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
                                    {!! $span_status !!}
                                    </td>
                                    @can('raw_material_company_manage')
                                    <td>
                                        <a href="{{ route('raw_material_company_edit', @$rmc->company_id) }}"
                                            class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                    </td>
                                    @endcan
                                    {{-- <td> --}}
                                        {{-- @if (count($rmc->sub_raw_material_company) != 0) --}}
                                            {{-- <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th> --}}
                                                            {{-- <th>Land</th> --}}
                                                            {{-- <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Status</th> --}}
                                                            {{-- <th>Action</th> --}}
                                                        {{-- </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($rmc->sub_raw_material_company as $key => $sub_raw_material_company)
                                                        <tr>
                                                            <td scope="row">{{$key+1}}</td> --}}
                                                            {{-- <td>
                                                                {{@$sub_raw_material_company->company_land->company_land_name}}
                                                                <a @can('raw_material_company_manage')
                                                                    href="{{ route('raw_material_company_edit', $sub_raw_material_company->raw_material_company_id) }}"
                                                                @endcan >
                                                                    <span>{{@$sub_raw_material_company->company_land->company_land_name}}</span></a>
                                                            </td> --}}
                                                            {{-- <td>
                                                                <b>{{@$sub_raw_material_company->raw_material_quantity}}</b> {{@$sub_raw_material_company->raw_material->raw_material_quantity_unit == 'bottle' ? 'Bottle(s)' : 'Pack(s)'}}<br>
                                                            </td>
                                                            <td>
                                                                <b>{{@$sub_raw_material_company->raw_material_value}}</b> {{@$sub_raw_material_company->raw_material->raw_material_value_unit == 'litre' ? 'litre' : 'kg'}}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $status = @$sub_raw_material_company->raw_material_company_status;
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
                                                                {!! $span_status !!}
                                                            </td> --}}
                                                            {{-- <td>
                                                                <a href="{{ route('raw_material_company_edit', @$sub_raw_material_company->raw_material_company_id) }}"
                                                                        class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                            </td> --}}
                                                        {{-- </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div> --}}
                                        {{-- @else --}}
                                            {{-- No land --}}
                                        {{-- @endif --}}
                                    {{-- </td> --}}
								</tr>
								@endforeach
							@else
								<tr><td colspan="5">No Records!</td></tr>
							@endif
						</tbody>
					</table>
				</div>
				{!! $records->links('pagination::bootstrap-4') !!}
			</div>
		</div>
	</div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
			<form method="POST" action="">
				@csrf
				<div class="modal-body">
					<h4>Delete this Term ?</h4>
					<input type="hidden" name="product_stock_warehouse_id" id="product_stock_warehouse_id">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger">Delete</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
    	</div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
<script>
	$(document).ready(function(e) {
		$('.delete').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #customer_term_id").val(id);
		});

		$('.suspend').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #customer_term_id").val(id);
		});
		$('.activate').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #customer_term_id").val(id);
		});

		$('.company_id').select2({
            minimumResultsForSearch: -1
        });

		$('.company_id').select2({
            minimumResultsForSearch: -1
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
										// if (e.user.length > 0) {
										// 		e.user.forEach(u => {
										// 				user += '<option value="' + u.user_id + '">' + u.user_fullname +
										// 						'</option>';
										// 		});
										// 		$('#user_id').html(user);
										// } else {
										// 		$('#user_id').html('<option value="">No User</option>');
										// }
								}
						});
				});

				function get_land_user(id) {
						let land = '<option value="">Please Select Land</option>';
						let user = '<option value="">Please Select User</option>';
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
																		element
																		.company_land_name + '</option>';
														} else {
																land += '<option value="' + element.company_land_id + '">' + element
																		.company_land_name + '</option>';
														}
												});
												$('#company_land_id').html(land);
										} else {
												$('#company_land_id').html('<option value="">No Land</option>');
										}
										// if (e.user.length > 0) {
										// 		e.user.forEach(u => {
										// 				if (suser != null && u.user_id == suser) {
										// 						user += '<option value="' + u.user_id + '" selected>' + u
										// 								.user_fullname +
										// 								'</option>';
										// 				} else {
										// 						user += '<option value="' + u.user_id + '">' + u.user_fullname +
										// 								'</option>';
										// 				}
										// 		});
										// 		$('#user_id').html(user);
										// } else {
										// 		$('#user_id').html('<option value="">No User</option>');
										// }
								}
						});
				}
	});
</script>
@endsection
