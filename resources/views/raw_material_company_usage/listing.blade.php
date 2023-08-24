@extends('layouts.master')

@section('title') Raw Material Company Usage Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Usage Listing</span>
                @can('raw_material_company_usage_manage')
                {{-- @if(auth()->user()->user_type_id == 2) --}}
                <a href="javascript: void(0);" id="add_transfer" data-toggle="modal" data-target='#usage_modal'
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                class="fas fa-plus"></i> Stock In</a>
                {{-- @endif --}}
                @endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Raw Material Company Usage</a>
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
										<label for="validationCustom03">Free Text</label>
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
								{{--<div class="col-md-3">
									<div class="form-group">
										<label for="company_land_id">Company Land</label>
										{!! Form::select('company_land_id', $company_land_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
									</div>
								</div>--}}
								<div class="col-md-3">
									<div class="form-group">
										<label for="raw_material_id">User</label>
										{!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control', 'id' => 'user_id']) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="raw_material_id">Raw Material</label>
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
								<th style="text-align: center;">Quantity</th>
								<th style="text-align: left;">Total Price</th>
								<th style="text-align: left;">Status</th>
                                <th style="text-align: left;">Date</th>
								<th style="text-align: center;">Action</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
								@php $i = $records->perPage()*($records->currentPage() - 1) + 1; @endphp
								@foreach($records as $rmcu)
								@php
										$status = $rmcu->raw_material_company_usage_type;
										$span_status = '';
										switch ($status) {
												case 'stock in':
														$span_status = "<span class='badge badge-info font-size-11'>Stock In</span><br>";
														break;
												case 'usage':
														$span_status = "<span class='badge badge-success font-size-11'>Usage</span><br>";
														break;
												case 'formula usage restock':
														$span_status = "<span class='badge badge-warning font-size-11'>Restock</span><br>";
														break;
                                                case 'return':
                                                    $span_status = "<span class='badge badge-danger font-size-11'>Return</span><br>";
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
											<b>{{@$rmcu->raw_material_company->company->company_name}}</b><br>
											<span>{{json_decode(@$rmcu->raw_material->raw_material_name)->en}}</span><br>
										@else
											<b>{{json_decode(@$rmcu->raw_material->raw_material_name)->en}}</b><br>
										@endif
											<span style="font-style: italic;">{{@$rmcu->raw_material_company->company_land->company_land_name}}</span><br><br>
										@if($rmcu->raw_material_company_usage_type == 'usage' && $rmcu->formula_usage_id)
											<span>{{@$rmcu->formula_usage->setting_formula->setting_formula_name}}</span>
										@endif
                                        @if(@$rmcu->is_claim && isset($claim_user[$rmcu->raw_material_company_usage_id]))
                                            <span><b>Claim Details</b></span><br>
                                            <span>Farm Manager: <i>{{ @$claim_user[$rmcu->raw_material_company_usage_id]->worker_name }}</i></span><br>
                                            <span>Amount: {{@$rmcu->claim_remaining_amount ? 'RM '.number_format(@$rmcu->claim_remaining_amount,2) : '-'}}</span>
                                        @endif
									</td>
                                    {{-- @dump(@$rmcu->raw_material_company_usage_qty, @$rmcu->raw_material_company_usage_total_value, @$rmcu->raw_material_company_usage_qty == @$rmcu->raw_material_company_usage_total_value ) --}}
                                        <td align="center">
                                            <b>{{@$rmcu->raw_material_company_usage_qty}}</b>
                                            {{@$rmcu->raw_material->raw_material_quantity_unit == 'bottle' ? 'Bottle(s)' : 'Pack(s)'}}
                                            <br>
                                            <b>{{@$rmcu->raw_material_company_usage_total_value}}</b>
                                            {{@$rmcu->raw_material->raw_material_value_unit == 'litre' ? 'litre' : 'kg'}}
                                            <br>
                                            {{-- <b>{{@rmcu->raw_material_company}}</b><br> --}}
                                        </td>
									<td align="left">
                                        Unit Price: <b>{{@$rmcu->unit_price_per_value ? 'RM '.number_format(@$rmcu->unit_price_per_value,2) : '-'}}</b><br>
									    Total: <b>{{@$rmcu->raw_material_company_usage_total_price ? 'RM '.number_format($rmcu->raw_material_company_usage_total_price,2) : '-'}}</b><br>
                                        Remain: <b>{{@$rmcu->raw_material_company_usage_total_value_remaining ? 'RM '.number_format($rmcu->raw_material_company_usage_total_value_remaining,2) : '-'}}</b><br>
									</td>
									<td align="left">
										{!! $span_status !!}
										<span><i>{{$rmcu->user->user_fullname}}</i></span>
										{{-- {{ date_format($rmcu->raw_material_company_usage_created, 'Y-m-d h:i A') }}<br>
										<span style="font-style: italic;">{{$rmcu->user->user_fullname}}</span> --}}
									</td>
                                    <td align="left">
                                        <small>
                                            Stock In: <b>{{ @$rmcu->raw_material_company_usage_date }}</b><br>
                                            Created: <b>{{ date_format(@$rmcu->raw_material_company_usage_created, 'Y-m-d h:i A') }}</b>
                                        </small>
									</td>
                                    @can('raw_material_company_usage_manage')
                                        <td align="center">
                                            {{-- <!-- @if(auth()->user()->user_type_id ==2).l;
                                            <a href="{{ route('raw_material_company_edit', ['tenant' => tenant('id'), 'id' => $rmcu->raw_material_company_id])}}"
                                                class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Edit</a>
                                            @endif --> --}}
                                            @if(@$rmcu->formula_usage_id != 0)
                                            <a href="{{ route('formula_usage_listing_by_id', ['tenant' => tenant('id'), 'id' => @$rmcu->formula_usage_id] )}}"
                                                class="btn btn-sm btn-outline-info waves-effect waves-light mr-1 mb-1">Formula Usage</a>
                                            @endif
                                        </td>
                                    @endcan
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
<div class="modal fade" id="usage_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('raw_material_company_usage_stock_in', ['tenant' => tenant('id')]) }}">
				@csrf
				<div class="modal-body">
                    <h4 class="card-title mb-4">Raw Material Usage Details</h4>
                    <div class="col-12">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="">Usage Type:</label><br>
                                <span style="font-size: 20px;">Stock In</span>
                                <input name="raw_material_company_usage_type" class="modal_usage_type_sel" required hidden type="text" value="stock in">
                                <input name="raw_material_company_id" hidden type="number" class="raw_material_company_id">
                                <!-- {!! Form::select('raw_material_company_usage_type', $usage_type_sel, null, ['class' => 'form-control modal_usage_type_sel', 'required']) !!} -->
                            </div>
                            <div class="col-6" {{ (auth()->user()->company_id == 0 ? '' : 'hidden') }}>
                                <div class="form-group">
                                    <label for="stock_in_company_id">Company</label>
                                    {!! Form::select('stock_in_company_id', $company_sel, null , ['class' => 'form-control', 'id' => 'stock_in_company_id']) !!}
                                </div>
                            </div>
                            <div class="form-group col-6" {{ (auth()->user()->company_id == 0 ? '' : 'hidden') }}>
                                &nbsp;
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier Name</label>
                                    <select name="supplier_id" class="form-control" id="supplier_id">
                                        <option value="">Please Select Supplier</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-6" id="raw_material_category_div">
                                <label for="raw_material_category">Raw Material Category</label>
                                {!! Form::select('raw_material_category_id', $raw_material_category_sel, null , ['class' => 'form-control mb-3', 'id' => 'raw_material_category_id']) !!}
                            </div>
                            <div class="form-group col-6" id="raw_material_div">
                                <label for="">Raw Material:</label>
                                <select name="stock_in_raw_material_id" class="form-control modal_raw_material_sel" id="stock_in_raw_material_id">
                                    <option value="">Please Select Raw Material</option>
                                </select>
                                <span id="warning_text" style="font-style: italic; color: red; font-size: 12px;">Invalid / Inactive</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="supplier_delivery_order_no">Supplier Delivery Order No: </label>
                            <input type="text" name="supplier_delivery_order_no" id="supplier_delivery_order_no" class="form-control"/>
                            <span id="do_err" style="font-style: italic; color: red; font-size: 12px;"></span>
                        </div>
                        <div class="form-group">
                            <label for="raw_material_company_usage_date">Date Created: </label>
                            @php
                            $current_date = date('Y-m-d');
                            @endphp
                            <input type="date" name="raw_material_company_usage_date" id="raw_material_company_usage_date" value="{{ @$current_date }}" class="form-control"  autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="insert_qty">Quantity<span id="modal_qty_unit"></span>:</label>
                            <input id="insert_qty" name="supplier_delivery_order_item_qty" type="number" required class="form-control">
                            <div class="row">
                                <span class="col-6" style="font-size: 12px" id="qty_current">Current: 0</span>
                                <span class="col-6" style="font-size: 12px" id="qty_after">After: 0</span>
                                <input name="raw_material_company_usage_qty" type="number" class="raw_material_company_usage_qty" hidden>
                                <input name="raw_material_qty" type="number" class="raw_material_qty" hidden>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="insert_value">Value Per Quantity<span id="modal_value_unit"></span>:</label>
                            <input id="insert_value" name="supplier_delivery_order_item_value_per_qty" type="number" required step="0.01" class="form-control">
                            <div class="row">
                                <span class="col-6" style="font-size: 12px" id="value_current">Current: 0</span>
                                <span class="col-6" style="font-size: 12px" id="value_after">After: 0</span>
                                <input name="raw_material_company_usage_value_per_qty" required type="number" step="0.01" class="raw_material_company_usage_value_per_qty" hidden>
                                <input name="raw_material_value" type="number" step="0.01" required class="raw_material_value" hidden>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="insert_price">Price Per Quantity(RM):</label>
                            <input id="insert_price" name="supplier_delivery_order_item_price_per_qty" type="number" required step="0.01" class="form-control">
                            <div class="row">
                                <span class="col-6" style="font-size: 12px" id="total_price">Total: RM 0</span>
                                <input name="raw_material_company_usage_price_per_qty" type="number" step="0.01" required class="raw_material_company_usage_price_per_qty" hidden>
                                <input name="raw_material_company_usage_total_price" type="number" step="0.01" required class="raw_material_company_usage_total_price" hidden>
                                <input name="raw_material_company_usage_total_value" type="number" step="0.01" required class="raw_material_company_usage_total_value" hidden>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6 d-flex" >
                                <label for="is_claim_cb">Claim: </label>
                                <div class="custom-checkbox custom-control ml-2">
                                    <input type="checkbox" class="custom-control-input" id="is_claim_cb"
                                    name="is_claim" value="1">
                                    <label for="is_claim_cb" class="custom-control-label"></label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="claim_worker_id">Farm Manager:</label>
                                <select name="claim_worker_id" class="form-control" id="claim_worker_id">
                                    <option value="">Please Select Farm Manager</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
                            <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready(function () {
    $('#raw_material_company_usage_date').datepicker({
        format: "yyyy-mm-dd"
    });
    });

    $('#raw_material_company_usage_date').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd"
    });

    $(document).on('change', '#stock_in_company_id', function() {
        @if (auth()->user()->company_id == 0)
            let company_id = $('#stock_in_company_id').val();
        @else
            let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
        @endif
        $('#supplier_id').val('');
        $('#raw_material_category_id').val('');
        $('#stock_in_raw_material_id').val('');
        get_supplier_by_company_id(company_id);
        get_farm_manager_by_company_id(company_id);
    });

    $(document).on('change', '#supplier_id', function() {
        let supplier_id = $('#supplier_id').val();
        $('#raw_material_category_id').val('');
        $('#stock_in_raw_material_id').val('');
        if ($(this).val() != 0 && $(this).val() > 0) {
            $('#raw_material_category_div').show();
        }else {
            $('#raw_material_category_div').hide();
        }
    });

    $(document).on('change', '#raw_material_category_id', function() {
        let raw_material_category_id = $('#raw_material_category_id').val();
        @if (auth()->user()->company_id == 0)
            let company_id = $('#stock_in_company_id').val();
        @else
            let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
        @endif
        $('#stock_in_raw_material_id').val('');
        get_raw_material_by_raw_material_category(raw_material_category_id, company_id);
        if ($(this).val() != 0 && $(this).val() > 0) {
            $('#raw_material_div').show();
        }else{
            $('#raw_material_div').hide();
        }
    });

    $('.modal_raw_material_sel').on('change', function() {
        $('#supplier_delivery_order_no').trigger('keyup');
    });

    $('#supplier_delivery_order_no').on('keyup', function(){
        @if (auth()->user()->company_id == 0)
            let company_id = $('#stock_in_company_id').val();
        @else
            let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
        @endif
        let supplier_id = $('#supplier_id').val();
        let supplier_delivery_order_no = $('#supplier_delivery_order_no').val();
        let raw_material_id = $('#stock_in_raw_material_id').val();
        check_existing_supplier_delivery_order_items(supplier_id, company_id, supplier_delivery_order_no, raw_material_id);
    })

	$(document).ready(function(e) {
		// $('.modal_raw_material_sel').attr('disabled', true);
		$('#raw_material_category_div').hide();
		$('#raw_material_div').hide();
		$('.modal_usage_type_sel').attr('disabled', true);
		$('#insert_qty').attr('disabled', true);
		$('#insert_value').attr('disabled', true);
		$('#insert_price').attr('disabled', true);
        $('#raw_material_company_usage_date').attr('disabled', true);
		$('#warning_text').hide();
        $('#claim_worker_id').attr('disabled', true);
        $('#stock_in_company_id').trigger('change');


		// $('.modal_raw_material_sel').on('change', function() {
		// 		let raw_material_id = $(this).val();
		// 		if(raw_material_id == ''){
		// 			// $('.modal_usage_type_sel').val('').attr('disabled', true);
		// 			// $('.modal_raw_material_sel').val('').attr('disabled', true);
		// 			$('#insert_qty').val('').attr('disabled', true);
		// 			$('#insert_value').val('').attr('disabled', true);
		// 			$('#insert_price').val('').attr('disabled', true);
        //             $('#raw_material_company_usage_date').val('').attr('disabled', true);
		// 		}else{
		// 			// $('.modal_usage_type_sel').val('').attr('disabled', false);
		// 			// $('.modal_raw_material_sel').val('').attr('disabled', false);

		// 		}
		// });

		// $('.modal_usage_type_sel').on('change', function() {
		// 		let type = $(this).val();
		// 		if(type == ''){
		// 			$('.modal_raw_material_sel').val('').attr('disabled', true);
		// 			$('#insert_qty').val('').attr('disabled', true);
		// 			$('#insert_value').val('').attr('disabled', true);
		// 			$('#insert_price').val('').attr('disabled', true);
		// 		}else{
		// 			$('.modal_raw_material_sel').val('').attr('disabled', false);
		// 		}
		// });

		$('#insert_qty').on('keyup clickup change', function() {
			let value = $(this).val();
			let usage_type = $('.modal_usage_type_sel').val();
			let qty_current = parseInt($('#qty_current').html().substr(9));
			// let qty_after = 0;
			// console.log(qty_current);
			if(usage_type == 'stock in'){
				if(value > 0){
					let qty_after = qty_current + parseInt(value);
					$('#qty_after').html('After: ' + qty_after);
					$('.raw_material_company_usage_qty').val(value);
					$('.raw_material_qty').val(qty_after);
				}else{
					// $('#insert_qty').val('');
					$('#qty_after').html('After: 0');
					// $('.raw_material_company_usage_qty').val('');
					// $('.raw_material_qty').val('');
				}
			}
			$('#insert_value').trigger('keyup');
			$('#insert_price').trigger('keyup');
		})

		$('#insert_value').on('keyup clickup change', function() {
			let input_qty = $('#insert_qty').val();
			let value = $(this).val();
			let usage_type = $('.modal_usage_type_sel').val();
			let value_current = parseFloat($('#value_current').html().substr(9));
			// let qty_after = 0;
			// console.log(value*input_qty);
			if(usage_type == 'stock in'){
				if(value > 0.00){
					let value_after = value_current + parseFloat(value*input_qty);
					$('#value_after').html('After: ' + value_after.toFixed(2));
					$('.raw_material_company_usage_value_per_qty').val(value);
					$('.raw_material_company_usage_total_value').val(parseFloat(value*input_qty));
					$('.raw_material_value').val(value_after.toFixed(2));
				}else{
					// $('#insert_value').val('');
					$('#value_after').html('After: 0');
					// $('.raw_material_company_usage_value_per_qty').val('');
					// $('.raw_material_company_usage_total_value').val('');
					// $('.raw_material_value').val('');
				}
			}
		})

		$('#insert_price').on('keyup clickup change', function() {
			let input_qty = $('#insert_qty').val();
			let value = $(this).val();
			let usage_type = $('.modal_usage_type_sel').val();
			// let value_current = parseFloat($('#value_current').html().substr(9));
			// let qty_after = 0;
			// console.log(value*input_qty);
			if(usage_type == 'stock in'){
				if(value > 0.00){
					let value_after =parseFloat(value*input_qty);
					$('#total_price').html('Total: RM' + value_after.toFixed(2));
					$('.raw_material_company_usage_price_per_qty').val(value);
					$('.raw_material_company_usage_total_price').val(value_after);
				}else{
					// $('#insert_price').val('');
					$('#total_price').html('Total: RM 0');
					// $('.raw_material_company_usage_price_per_qty').val('');
					// $('.raw_material_company_usage_total_price').val('');
				}
			}
		})

        $('#is_claim_cb').on('click', function(e){
            if(this.checked){
                $('#claim_worker_id').attr('disabled', false);
                $('#claim_worker_id').attr('required', true);
            }
            else{
                $('#claim_worker_id').attr('disabled', true);
                $('#claim_worker_id').attr('required', false);
            }
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

    function get_farm_manager_by_company_id(company_id)
    {
        let farm_manager_sel = '<option value="">Please Select Farm Manager</option>';

        $('#claim_worker_id').html('<option value="">Loading...</option>');

        $.ajax({
            url:"{{ route('ajax_get_farm_manager_by_company_id', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
            },
            success: function(e){
                if(e.data.length > 0){
                    e.data.forEach((element) => {
                        farm_manager_sel += '<option value=' + element.id + '>' + element.name + '</option>'
                    });
                } else {
                    farm_manager_sel = '<option value="">No Farm Manager</option>';
                }

                $('#claim_worker_id').html(farm_manager_sel);
            }
        })

    }

    function get_supplier_by_company_id(company_id)
    {
        let supplier_sel = '<option value="">Please Select Supplier</option>';

        $.ajax({
            url: "{{ route('ajax_get_supplier_by_company_id', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
            },
            success: function(e) {
                if (e.data.length > 0) {
                    e.data.forEach(element => {
                        supplier_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                    });
                    $('#supplier_id').html(supplier_sel);
                }else{
                    $('#supplier_id').html('<option value="">No Supplier</option>');
                }
            }
        });
    }

    function get_raw_material_by_raw_material_category(raw_material_category_id){
        let company_id = isNaN($('#company_id').val()) ? '{{ auth()->user()->company_id }}' : $('#company_id').val();

        $.ajax({
            url: "{{ route('ajax_get_raw_material_by_raw_material_category_id', ['tenant' => tenant('id')]) }}",
            method: "get",
            data: {
                _token: "{{ csrf_token() }}",
                raw_material_category_id: raw_material_category_id,
                supplier_id: $('#supplier_id').val(),
                company_id: company_id,
            },
            success: function(e) {
                if (e.data.length > 0) {
                    raw_material_list += '<option value="">Please Select Raw Material</option>';
                    e.data.forEach(element => {
                        raw_material_list += '<option value="' + element.id + '">' + element.value + '</option>';
                    });
                    $('#stock_in_raw_material_id').html(raw_material_list);
                }else{
                    var raw_material_list = '<option value="">No Raw Material</option>';
                    $('#stock_in_raw_material_id').html(raw_material_list);
                }
            },
            error: function(e) {
                alert('An Error Occurred! Please try again. ');
            }
        });
    }

    function get_existing_raw_material_company (company_id, raw_material_id){
        // $('#company_land_id').html('<option value="">Loading...</option>');
        // $('#user_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "{{ route('ajax_get_existing_raw_material_company', ['tenant' => tenant('id')]) }}",
            method: "GET",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
                raw_material_id: raw_material_id
            },
            success: function(e) {
                if(e.result == true){
                    $('#insert_qty').attr('disabled', false);
                    $('#insert_value').attr('disabled', false);
                    $('#insert_price').attr('disabled', false);
                    $('#qty_current').html('Current: ' + e.data['raw_material_quantity']);
                    $('#value_current').html('Current: ' + e.data['raw_material_value']);
                    $('.raw_material_company_id').val(e.data['raw_material_company_id']);
                    $('#warning_text').hide();
                    $('#is_claim_cb').attr('disabled', false);

                    if(e.raw_material['raw_material_quantity_unit'] == 'bottle'){
                        $('#modal_qty_unit').html(' (Bottles)');
                    }else{
                        $('#modal_qty_unit').html(' (Packs)');
                    }
                    if(e.raw_material['raw_material_value_unit'] == 'litre'){
                        $('#modal_value_unit').html(' (Litre)');
                    }else{
                        $('#modal_value_unit').html(' (KG)');
                    }
                }else if(e.result == false){
                    $('#insert_qty').val('').attr('disabled', true);
                    $('#insert_value').val('').attr('disabled', true);
                    $('#insert_value').val('').attr('disabled', true);
                    $('#qty_current').html('Current: 0');
                    $('#value_current').html('Current: 0');
                    $('#qty_after').html('After: 0');
                    $('#value_after').html('After: 0');
                    $('#insert_price').val('').attr('disabled', true);
                    $('#total_price').html('Total: RM 0');
                    $('#warning_text').show();
                    $('#modal_qty_unit').html('');
                    $('#modal_value_unit').html('');
                    $('#is_claim_cb').attr('disabled', true);
                }
            }
        });
    }

    function check_existing_supplier_delivery_order_items(supplier_id, company_id, supplier_delivery_order_no, raw_material_id){
        $.ajax({
            url: "{{ route('ajax_check_existing_supplier_delivery_order_items', ['tenant' => tenant('id')]) }}",
            method: "GET",
            data: {
                _token: "{{ csrf_token() }}",
                supplier_id: supplier_id,
                company_id: company_id,
                supplier_delivery_order_no: supplier_delivery_order_no,
                raw_material_id: raw_material_id
            },
            success: function(e){
                if(e.do_check){
                    $('#raw_material_company_usage_date').val('').attr('disabled',true);
                    $('#insert_qty').val('').attr('disabled', true);
                    $('#insert_value').val('').attr('disabled', true);
                    $('#insert_value').val('').attr('disabled', true);
                    $('#qty_current').html('Current: 0');
                    $('#value_current').html('Current: 0');
                    $('#qty_after').html('After: 0');
                    $('#value_after').html('After: 0');
                    $('#insert_price').val('').attr('disabled', true);
                    $('#total_price').html('Total: RM 0');
                    $('#warning_text').hide();
                    $('#modal_qty_unit').html('');
                    $('#modal_value_unit').html('');
                    $('#is_claim_cb').attr('disabled', true);
                    $('#do_err').html('Raw material exists in this delivery order number');
                }else{
                    $('#insert_qty').val('').attr('disabled', false);
					$('#insert_value').val('').attr('disabled', false);
					$('#insert_price').val('').attr('disabled', false);
                    $('#raw_material_company_usage_date').val('').attr('disabled', false);
                    $('#do_err').html('');
                    if(e.rmc_check){
                        $('#insert_qty').attr('disabled', false);
                        $('#insert_value').attr('disabled', false);
                        $('#insert_price').attr('disabled', false);
                        $('#qty_current').html('Current: ' + e.data['raw_material_quantity']);
                        $('#value_current').html('Current: ' + e.data['raw_material_value']);
                        $('.raw_material_company_id').val(e.data['raw_material_company_id']);
                        $('#warning_text').hide();
                        $('#is_claim_cb').attr('disabled', false);

                        if(e.raw_material['raw_material_quantity_unit'] == 'bottle'){
                            $('#modal_qty_unit').html(' (Bottles)');
                        }else{
                            $('#modal_qty_unit').html(' (Packs)');
                        }
                        if(e.raw_material['raw_material_value_unit'] == 'litre'){
                            $('#modal_value_unit').html(' (Litre)');
                        }else{
                            $('#modal_value_unit').html(' (KG)');
                        }
                    }else {
                        $('#insert_qty').val('').attr('disabled', true);
                        $('#insert_value').val('').attr('disabled', true);
                        $('#insert_value').val('').attr('disabled', true);
                        $('#qty_current').html('Current: 0');
                        $('#value_current').html('Current: 0');
                        $('#qty_after').html('After: 0');
                        $('#value_after').html('After: 0');
                        $('#insert_price').val('').attr('disabled', true);
                        $('#total_price').html('Total: RM 0');
                        $('#warning_text').show();
                        $('#modal_qty_unit').html('');
                        $('#modal_value_unit').html('');
                        $('#is_claim_cb').attr('disabled', true);
                    }
                }
            }
        })
    }
</script>
@endsection
