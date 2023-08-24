@extends('layouts.master')

@section('title') Formula Usage Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Sync Formula Usage Listing</span>

			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Sync Formula Company Usage</a>
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
								<div class="col-md-3">
									<div class="form-group">
										<label for="company_land_id">Company Land</label>
										{!! Form::select('company_land_id', $company_land_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="raw_material_id">User</label>
										{!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control', 'id' => 'user_id']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="raw_material_id">Raw Material</label>
										{!! Form::select('raw_material_id', $raw_material_sel, @$search['raw_material_id'], ['class' => 'form-control', 'id' => 'raw_material_id']) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="formula_usage_status">Status</label>
										{!! Form::select('formula_usage_status', $formula_usage_status_sel, @$search['formula_usage_status'], ['class' => 'form-control', 'id' => 'formula_usage_status']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="formula_usage_type">Type</label>
										{!! Form::select('formula_usage_type', $formula_usage_type_sel, @$search['formula_usage_type'], ['class' => 'form-control', 'id' => 'formula_usage_type']) !!}
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
				@if(auth()->user()->company_id > 0)
					<div class="mb-2">
						<span><b>Company: {{auth()->user()->company->company_name}}</b></span>
					</div>
				@endif
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th scope="col" style="width: 50px; text-align: center;">#</th>
								<th >Company Details</th>
								<th style="text-align: left;">Formula Item(s)</th>
								<th style="text-align: left;">Formula Product(s)</th>
								<th style="text-align: left;">Status</th>
								<!-- <th style="text-align: left;">Action</th> -->
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
								@php $i = $records->perPage()*($records->currentPage() - 1) + 1; @endphp
								@foreach($records as $fu)
								@php
										$status = $fu->sync_formula_usage_status;
										$span_status = '';
										switch ($status) {
												case 'pending':
														$span_status = "<span class='badge badge-warning font-size-11'>pending</span><br>";
														break;
												case 'completed':
														$span_status = "<span class='badge badge-success font-size-11'>Completed</span><br>";
														break;
												case 'deleted':
														$span_status = "<span class='badge badge-danger font-size-11'>Deleted</span><br>";
														break;
												default:
														break;
										}
								@endphp
								<tr>
									<td align="center">
										{{ $i }}
									</td>
									<td>
										@if(auth()->user()->company_id == 0)
											<b>{{@$fu->company->company_name}}</b><br>
											<span>{{@$fu->setting_formula->setting_formula_name}}</span><br>
										@else
											<b>{{@$fu->setting_formula->setting_formula_name}}</b> - {{@$fu->formula_usage_type == 'man' ? 'Man' : 'Drone'}}<br>
										@endif
											<span style="font-style: italic;">{{@$fu->company_land->company_land_name}}</span><br>
											{{@$fu->formula_usage_value}}
											<b>
												@if(@$fu->setting_formula->setting_formula_measurement == 'litre')
													Litre
												@elseif(@$fu->setting_formula->setting_formula_measurement == 'pack')
													Pack(s)
												@elseif(@$fu->setting_formula->setting_formula_measurement == 'acres')
													Acre(s)
												@endif
											</b><br>
										<!-- @if($fu->raw_material_company_usage_type == 'usage' && $fu->formula_usage_id)
											<span>{{@$fu->formula_usage->setting_formula->setting_formula_name}}</span>
										@endif -->
									</td>
									<td align="left">
										@if(count(@$fu->sync_formula_usage_item) > 0)
											@foreach(@$fu->sync_formula_usage_item as $key => $fui)
												<b><a href="#" class="item_btn" data-toggle='modal' data-target='#formula_item_modal' id="item_btn_{{@$fui->sync_formula_usage_item_id}}" data-id="{{@$fui->sync_formula_usage_id}}" data-user-id="{{@$fu->user_id}}">{{@$fui->raw_material->raw_material_name}}</a></b> - {{@$fui->sync_formula_usage_item_total}} {{@$fu->setting_formula->setting_formula_measurement == 'litre' ? "litre" : "kg"}}<br>
											@endforeach
										@else
										-
										@endif
									</td>
										<td align="left">
											@if(count(@$fu->sync_formula_usage_product) > 0)
												<div class="table-responsive">
														<table class="table small-table table-striped mb-0">
																<thead>
																		<tr>
																				<th style="padding-left: 10px !important">#</th>
																				@foreach(@$company_pnl_sub_item as $key => $cpsi)
																				<th>{{$cpsi->company_pnl_sub_item_code}}</th>
																				@endforeach
																		</tr>
																</thead>
																<tbody>
																	@foreach(@$fu->sync_formula_usage_product as $key => $fup)
																	<tr>
																		<td style="padding-left: 10px !important">
																			@if(isset($product_tree[$fu->company_land_id][$fup->product_id]['total_product_tree']))
																		  	{{@$fup->product->product_name}} ({{$product_tree[$fu->company_land_id][$fup->product_id]['total_product_tree']}})
																		  @endif
																		</td>
																		@foreach(@$company_pnl_sub_item as $key => $cpsi)
																			<td>
																				@php
																					$json_data = json_decode($fup->sync_formula_usage_product_json);
																					$status = 0;
																					$data = '-';
																				@endphp
																				@if(is_array($json_data) || is_object($json_data))
																					@foreach($json_data as $key => $product_json)
																						@if($product_json->company_pnl_sub_item_code == $cpsi->company_pnl_sub_item_code)
																							@php
																								$measurement = @$fu->setting_formula->setting_formula_measurement == 'litre' ? "L" : "Kg";
																								$data = $product_json->formula_usage_product_quantity.'('. $product_json->formula_usage_product_value_per_tree. $measurement .')';
																							@endphp
																						@endif
																					@endforeach
																				@endif
																				{{$data}}
																			</td>
																		@endforeach
																	<tr>
																	@endforeach
																</tbody>
														</table>
												</div>
											@else
											-
											@endif
									</td>
									<td align="left">
										{!! $span_status !!}
										{{ date_format($fu->sync_formula_usage_created, 'Y-m-d h:i A') }}<br>
										<span style="font-style: italic;">{{$fu->user->user_fullname}}</span>
									</td>
									<!-- <td align="left">
										@if(auth()->user()->user_type_id == 2 && $fu->formula_usage_status != 'deleted')
										<a href="{{route('formula_usage_edit', ['tenant' => tenant('id'), 'id' => $fu->formula_usage_id])}}"
											 class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Edit</a>
											<a href="#" data-toggle='modal' data-target='#delete' id="delete_btn_{{@$fui->formula_usage_item_id}}" data-id="{{@$fui->formula_usage_id}}"
												 class="btn btn-sm btn-outline-danger waves-effect waves-light mr-1 mb-1 delete_btn">Delete</a>
										@endif
									</td> -->
								</tr>
								@php $i++; @endphp
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
<div class="modal fade" id="formula_item_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
						<form method="POST" action="javascript: void(0)">
								@csrf
								<div class="modal-body">
										<h4 class="card-title mb-4">Formula Usage Item Details</h4>
										<div class="col-12">
											<div class="table-responsive">
											  <table class="table table-bordered">
											    <thead class="thead-light">
											      <tr>
											        <th scope="col" style="width: 50px; text-align: center;">#</th>
											        <th>Raw Material</th>
											        <th>Qty</th>
											        <th>Value</th>
											        <th>Rounding</th>
											        <th>Total</th>
											      </tr>
											    </thead>
											    <tbody id="item_table_body">
											    </tbody>
											  </table>
											</div>
											<div>
												<span id="total_rounding_user"></span>
											</div>
												<div class="d-flex justify-content-end">
														<!-- <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button> -->
														<a class="btn btn-secondary" data-dismiss="modal">Close</a>
												</div>
										</div>
								</div>
						</form>
				</div>
		</div>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
						<form method="POST" action="{{ route('delete_formula_usage', ['tenant' => tenant('id')]) }}">
								@csrf
								<div class="modal-body">
										<h4>Delete this Formula Usage ?</h4>
										<input type="hidden" , name="formula_usage_id" id="formula_usage_id">
										<!-- <input type="hidden" , name="action" value="delete"> -->
										<!-- <div>
												<label for="delivery_order_log_description">Remark: </label>
												<textarea name="delivery_order_log_description" id="summernote"
																class="form-control" required></textarea>
										</div> -->
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
<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script>
	$(document).ready(function(e) {
				$('.delete_btn').on('click', function(){
					let formula_usage_id = $(this).attr('data-id');
					$('#formula_usage_id').val(formula_usage_id);
				});

				$('.item_btn').on('click', function() {
					let formula_usage_id = $(this).attr('data-id');
					let user_id = $(this).attr('data-user-id');
					$('#item_table_body').html('Loading...');
					$('#total_rounding_user').html("Loading...")
					// console.log(formula_usage_id, user_id);
					$.ajax({
							url: "{{ route('ajax_find_sync_formula_usage_item_details', ['tenant' => tenant('id')]) }}",
							method: "POST",
							data: {
									_token: "{{ csrf_token() }}",
									formula_usage_id: formula_usage_id,
									user_id: user_id
							},
							success: function(e) {
									// console.log(e);
									let count = 1;
									let data = '';

									if(e){
										e.result.forEach((item) => {
											data += '<tr>';
											data += '<td align="center">'+ count +'</td>';
											data += '<td>'+ item.raw_material_name +'</td>';
											data += '<td>'+ item.sync_formula_usage_item_qty +'</td>';
											data += '<td>'+ item.sync_formula_usage_item_value +'</td>';
											data += '<td>'+ item.sync_formula_usage_item_rounding +'</td>';
											data += '<td>'+ item.sync_formula_usage_item_total +'</td>';
											data += '</tr>';
											count++
										});
										$('#item_table_body').html(data);
										$('#total_rounding_user').html(e.total_rounding[0].user_fullname + "'s Total Rounding: " + e.total_rounding[0].sum);
									}
							}
					});
				})

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
								url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
										if (e.user.length > 0) {
												e.user.forEach(u => {
														user += '<option value="' + u.user_id + '">' + u.user_fullname +
																'</option>';
												});
												$('#user_id').html(user);
										} else {
												$('#user_id').html('<option value="">No User</option>');
										}
								}
						});
				});

				function get_land_user(id) {
						let land = '<option value="">Please Select Land</option>';
						let user = '<option value="">Please Select User</option>';
						let sland = "{{ @$search['company_land_id'] ?? null }}";
						let suser = "{{ @$search['user_id'] ?? null }}";
						$.ajax({
								url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
										if (e.user.length > 0) {
												e.user.forEach(u => {
														if (suser != null && u.user_id == suser) {
																user += '<option value="' + u.user_id + '" selected>' + u
																		.user_fullname +
																		'</option>';
														} else {
																user += '<option value="' + u.user_id + '">' + u.user_fullname +
																		'</option>';
														}
												});
												$('#user_id').html(user);
										} else {
												$('#user_id').html('<option value="">No User</option>');
										}
								}
						});
				}
	});
</script>
@endsection
