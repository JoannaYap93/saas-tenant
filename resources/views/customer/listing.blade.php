@extends('layouts.master')

@section('title') Customer Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Customer Listing</span>
				@can('customer_manage')
					<a href="{{ route('customer_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a>
				@endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Customer</a>
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
									@if (count($company_sel) > 1)
									<div class="col-md-3">
										<div class="form-group">
											<label for="validationCustom03">Company</label>
											{!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control select2 company_id']) !!}
										</div>
									</div>
									@endif
								@endif
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Category</label>
										{!! Form::select('customer_category_id', $customer_category_sel, @$search['customer_category_id'], ['class' => 'form-control select2 customer_category_id']) !!}
									</div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        {!! Form::select('customer_status', $customer_status_sel, @$search['customer_status'], ['class' => 'form-control', 'id' => 'customer_status_sel']) !!}
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
								<th scope="col" style="width: 50px;">#</th>
								<th>Customer</th>
								<th>Customer Details</th>
								<!-- <th>PIC Details </th> -->
								<th>Category</th>
								@can('customer_manage')
								<th>Action</th>
								@endcan
							</tr>
						</thead>
						<tbody>
							@if($customers->isNotEmpty())
								@php $i = $customers->perPage()*($customers->currentPage() - 1) + 1; @endphp
								@foreach($customers as $customer)
								<tr>
									<td align="center">
										{{ $i }}
									</td>
									<td>
										<b>{{ $customer->customer_company_name }}</b><br>
										{{ $customer->customer_name }}<br>
										{{ $customer->customer_mobile_no }}<br>
										@if(isset($customer->customer_email))
											{{ $customer->customer_email }}<br>
										@endif

										@php
                                            $customer_status = '';
                                                switch ($customer->customer_status) {
                                                    case 'inactivate':
														$customer_status = "<span class='badge badge-danger mr-2 font-size-11'>Inactivate</span>";
                                                        break;
                                                    case 'activate':
														$customer_status = "<span class='badge badge-success mr-2 font-size-11'>Activate</span>";
                                                        break;
                                                    default:
                                                        break;
                                                }
                                            @endphp

										{!!$customer_status!!}<br>

                                        <a class="badge badge-success font-size-11 popup" href="{{ route('customer_credit_detail', ['tenant' => tenant('id'), 'id' => $customer->customer_id]) }}">Credit: RM{{number_format($customer->customer_credit,2) ?? 0}}</a>
										@if($customer->warehouse_id != NULL)
											<br><br><b>WH: </b>
											<a href="{{ route('get_warehouse_listing_by_id', ['tenant' => tenant('id'), 'id' => $customer->warehouse_id]) }}">{{ $customer->warehouse->warehouse_name }}</a>
										@endif
									</td>
									<td>
										@if($customer->customer_address)
											{{ ucfirst($customer->customer_address) }}
										@endif
										@if($customer->customer_address2)
											,{{ ucfirst($customer->customer_address2) }}
										@endif
										@if($customer->customer_postcode)
											,{{ $customer->customer_postcode }}
										@endif
										@if($customer->customer_city)
											,<br>{{ ucfirst($customer->customer_city) }}
										@endif
										@if($customer->customer_state)
											,{{ ucfirst($customer->customer_state) }}<br>
										@endif
										@if($customer->customer_country)
											{{ ucfirst($customer->customer_country) }}
										@endif

										<br><br>
										<b>Account Info:</b><br>
										@if($customer->customer_acc_name)
											{{ ucfirst($customer->customer_acc_name) }}<br>
											{{ $customer->customer_acc_mobile_no }}<br>
										@else
											-
										@endif
									</td>
									<!-- <td>
										@if ($customer->customer_pic->isNotEmpty())
											@if(isset($customer->customer_pic))
												@foreach($customer->customer_pic as $pic)
													<b>{{ $pic->customer_pic_name }}</b> - {{ $pic->customer_pic_ic }}<br>
												@endforeach
											@endif
										@else
											-
										@endif
									</td> -->
									<td>
										@if($customer->customer_category)
										<b>{{ $customer->customer_category->customer_category_name }}</b><br>
										@else
										<b> - </b><br>
										@endif
									</td>
									<td>
										@can('customer_manage')
                                            <a href="{{route('customer_edit', ['tenant' => tenant('id'), 'id' => $customer->customer_id])}}" class='btn btn-sm btn-outline-warning waves-effect waves-light'>Edit</a>
											<button class="btn btn-sm btn-outline-success do_history"
													data-toggle="modal" data-id="{{ $customer->customer_id }}"
													data-target="#deliver_order">D.O. History</button>
											<button class="btn btn-sm btn-outline-success invoice_history"
													data-toggle="modal" data-id="{{ $customer->customer_id }}"
													data-target="#invoice_history">Invoice History</button>
											<button class="btn btn-sm btn-outline-success pic_details"
													data-toggle="modal" data-id="{{ $customer->customer_id }}"
													data-target="#pic_details">PIC Details</button><br>
                                            <a href="{{ route('credit_adjustment', ['tenant' => tenant('id'), 'id' => $customer->customer_id]) }}" class='btn btn-sm btn-outline-primary waves-effect waves-light pt-2'>Adjust Credit</a>

											@if ($customer->customer_status == "activate")
												<button class="btn btn-sm btn-outline-danger inactivate" data-toggle="modal" data-target="#inactivate" data-id="{{ $customer->customer_id }}">Inactivate</button>
											@else
												<button class="btn btn-sm btn-outline-success activate" data-toggle="modal" data-target="#activate" data-id="{{ $customer->customer_id }}">Activate</button>
											@endif
										@endcan
									</td>
								</tr>
								@php $i++; @endphp
								@endforeach
							@else
								<tr><td colspan="5">No Records!</td></tr>
							@endif
						</tbody>
					</table>
				</div>
				{!! $customers->links('pagination::bootstrap-4') !!}
			</div>
		</div>
	</div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="activate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('customer_activate', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Activate this customer ?</h4>
                    <input type="hidden" name="customer_id" id="customer_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Activate</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="inactivate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('customer_inactivate', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Inactivate this customer ?</h4>
                    <input type="hidden" name="customer_id" id="customer_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Inactivate</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Deliver Order History -->
<div class="modal fade" id="deliver_order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h4>Deliver Order History</h4>
						</div>
						<form action="{{ route('do_issue_invoice', ['tenant' => tenant('id')]) }}" method="POST">
								@csrf
								<div class="modal-body">
										<table class="table table-bordered">
												<thead class="thead-light">
														<th>Order No.</th>
														<th>Items</th>
														<th>Expenses</th>
														<th>Status</th>
														<th>Invoice No.</th>
														<!-- <th><span id="selectall" class="bold">Tick</span></th> -->
												</thead>
												<tbody id="show_order_history">
												</tbody>
										</table>
								</div>
								<div class="modal-footer">
										<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
										<a class="btn btn-secondary" data-dismiss="modal">Close</a>
								</div>
						</form>
				</div>
		</div>
</div>
<!-- Invoice History -->
<div class="modal fade" id="invoice_history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h4>Invoice History</h4>
						</div>
						<form action="{{ route('do_issue_invoice', ['tenant' => tenant('id')]) }}" method="POST">
								@csrf
								<div class="modal-body">
										<table class="table table-bordered">
												<thead class="thead-light">
													<th>Invoice No.</th>
													<th>Items</th>
													<th>Subtotal</th>
													<th>Total Disount</th>
													<th>Total</th>
													<th>GST</th>
													<th>Grandtotal</th>
														<!-- <th><span id="selectall" class="bold">Tick</span></th> -->
												</thead>
												<tbody id="show_invoice_history">
												</tbody>
										</table>
								</div>
								<div class="modal-footer">
										<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
										<a class="btn btn-secondary" data-dismiss="modal">Close</a>
								</div>
						</form>
				</div>
		</div>
</div>
<!-- Pic Details -->
<div class="modal fade" id="pic_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h4>Person In Charge Details</h4>
						</div>
						<form action="{{ route('do_issue_invoice', ['tenant' => tenant('id')]) }}" method="POST">
								@csrf
								<div class="modal-body">
										<table class="table table-bordered">
												<thead class="thead-light">
														<th>#</th>
														<th>PIC Name</th>
														<th>PIC IC</th>
														<th>PIC Mobile</th>
														<!-- <th><span id="selectall" class="bold">Tick</span></th> -->
												</thead>
												<tbody id="show_pic_details">
												</tbody>
										</table>
								</div>
								<div class="modal-footer">
										<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
										<a class="btn btn-secondary" data-dismiss="modal">Close</a>
								</div>
						</form>
				</div>
		</div>
</div>
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
	$(document).ready(function(e) {
		$('.inactivate').on('click', function() {
			var id = $(this).attr('data-id');
			console.log(id);
			$(".modal-body #customer_id").val(id);
		});
		$('.activate').on('click', function() {
			var id = $(this).attr('data-id');
			console.log(id);
			$(".modal-body #customer_id").val(id);
		});

        $(".popup").fancybox({
        'type': 'iframe',
        'width': 1000,
        'height': 600,
        'autoDimensions': false,
        'autoScale': false,
        iframe : {
            css : {
                width : '1000px',
                height: '600px'
            }
        }
        });

		// $('.inactive').on('click', function() {
		// 	var id = $(this).attr('data-id');
		// 	$(".modal-body #customer_id").val(id);
		// });
        // $(".popup").fancybox({
        //     'type': 'iframe',
        //     'width': 1000,
        //     'height': 600,
        //     'autoDimensions': false,
        //     'autoScale': false,
        //     iframe : {
        //         css : {
        //             width : '1000px',
        //             height: '600px'
        //         }
        //     }
        // });

		// $('.active').on('click', function() {
		// 	var id = $(this).attr('data-id');
		// 	$(".modal-body #customer_id").val(id);
		// });
        // $(".popup").fancybox({
        //     'type': 'iframe',
        //     'width': 1000,
        //     'height': 600,
        //     'autoDimensions': false,
        //     'autoScale': false,
        //     iframe : {
        //         css : {
        //             width : '1000px',
        //             height: '600px'
        //         }
        //     }
        // });

        $(".fancybox").fancybox();

		$('.pic_details').on('click', function() {
				$('#show_pic_details').html('<tr><td class="text-center" colspan="5">Loading...</td></tr>');
				let customer_id = $(this).attr('data-id');
				// console.log(customer_id);
				$.ajax({
						url: "{{ route('ajax_find_pic_with_customer_id', ['tenant' => tenant('id')]) }}",
						method: 'post',
						data: {
								_token: '{{ csrf_token() }}',
								customer_id: customer_id,
						},
						success: function(e) {
								// console.log(e);
								let tb = '';

								if (e.length > 0){
								e.forEach((element, index) => {
										let do_item = '';
										var no = index+1;
										tb += '<tr>';
										tb += '<td>' + no + '</td>';
										tb += '<td>' + element.customer_pic_name + '</td>';
										tb += '<td>' + element.customer_pic_ic+ '</td>';
										tb += '<td>' + element.customer_pic_mobile_no + '</td>';
										tb += '</tr>';
								});
							}else {
								tb += '<tr><td class="text-center" colspan="5">No Records...</td></tr>';
							}
								$('#show_pic_details').html(tb);
						},
						error: function(e) {
								// console.log(e);
								alert(e);
						}
				})
		});
		$('.invoice_history').on('click', function() {
				$('#show_invoice_history').html('<tr><td class="text-center" colspan="5">Loading...</td></tr>');
				let customer_id = $(this).attr('data-id');
				// console.log(customer_id);
				$.ajax({
						url: "{{ route('ajax_find_invoice_with_customer_id', ['tenant' => tenant('id')]) }}",
						method: 'post',
						data: {
								_token: '{{ csrf_token() }}',
								customer_id: customer_id,
						},
						success: function(e) {
								// console.log(e);
								let tb = '';
								if (e.length > 0){
								e.forEach(element => {
										let do_item = '';
										element.items.forEach(item => {
												do_item += item[0] + ' [' + item[1] + '] <br>';
										});
										tb += '<tr>';
										tb += '<td>#' + element.invoice_no + '</td>';
										// tb += '<td>' + element.customer[0][0] + '<br>' + element.customer[0][
										// 		1
										// ] + '</td>';
										tb += '<td>' + do_item + '</td>';
										tb += '<td>' + element.invoice_subtotal + '</td>';
										tb += '<td>' + element.invoice_total_discount + '</td>';
										tb += '<td>' + element.invoice_total + '</td>';
										tb += '<td>' + element.invoice_total_gst + '</td>';
										tb += '<td>' + element.invoice_grandtotal + '</td>';
										// if (element.expense.length > 0) {
										// 		tb += '<td>' + element.expense[0][0] + '<br>' + element.expense[0][
										// 				1
										// 		] + '</td>';
										// } else {
										// 		tb += '<td>"No Expenses"</td>';
										// }
										// if (element.delivery_order_status_id == 1){
										// 		tb += '<td>Pending</td>';
										// }else if (element.delivery_order_status_id == 2){
										// 		tb += '<td>Approved</td>';
										// }else if (element.delivery_order_status_id == 3){
										// 		tb += '<td>Issued</td>';
										// }else if (element.delivery_order_status_id == 4){
										// 		tb += '<td>Cancelled</td>';
										// }

										// if (element.invoice.length > 0){
										// 		tb += '<td>'+element.invoice[0] +'</td>';
										// }else{
										// 		tb += '<td> - </td>';
										// }
										// tb +=
										// 		'<td><input type="checkbox" name="delivery_order[]" value="' +
										// 		element.delivery_order_id + '"></td>'
										tb += '</tr>';
								});
							}else {
								tb += '<tr><td class="text-center" colspan="5">No Records...</td></tr>';
							}
								$('#show_invoice_history').html(tb);
						},
						error: function(e) {
								// console.log(e);
								alert(e);
						}
				})
		});
		$('.do_history').on('click', function() {
				$('#show_order_history').html('<tr><td class="text-center" colspan="5">Loading...</td></tr>');
				let customer_id = $(this).attr('data-id');
				// console.log(customer_id);
				$.ajax({
						url: "{{ route('ajax_find_delivery_with_customer_id', ['tenant' => tenant('id')]) }}",
						method: 'post',
						data: {
								_token: '{{ csrf_token() }}',
								customer_id: customer_id,
						},
						success: function(e) {
								// console.log(e);
								let tb = '';
								if(e.length > 0) {
								e.forEach(element => {
										let do_item = '';
										element.items.forEach(item => {
												do_item += item[0] + ' - ' + item[1] + ' KG - ' + item[2] +
														'<br>';
										});
										tb += '<tr>';
										tb += '<td>#' + element.delivery_order_no + '</td>';
										// tb += '<td>' + element.customer[0][0] + '<br>' + element.customer[0][
										// 		1
										// ] + '</td>';
										tb += '<td>' + do_item + '</td>';
										if (element.expense.length > 0) {
												tb += '<td>' + element.expense[0][0] + '<br>' + element.expense[0][
														1
												] + '</td>';
										} else {
												tb += '<td>"No Expenses"</td>';
										}
										if (element.delivery_order_status_id == 1){
												tb += '<td>Pending</td>';
										}else if (element.delivery_order_status_id == 2){
												tb += '<td>Approved</td>';
										}else if (element.delivery_order_status_id == 3){
												tb += '<td>Issued</td>';
										}else if (element.delivery_order_status_id == 4){
												tb += '<td>Cancelled</td>';
										}

										if (element.invoice.length > 0){
												tb += '<td>'+element.invoice[0] +'</td>';
										}else{
												tb += '<td> - </td>';
										}
										// tb +=
										// 		'<td><input type="checkbox" name="delivery_order[]" value="' +
										// 		element.delivery_order_id + '"></td>'
										tb += '</tr>';
								});
							}else {
								tb += '<tr><td class="text-center" colspan="5">No Records...</td></tr>';
							}
								$('#show_order_history').html(tb);
						},
						error: function(e) {
								console.log(e);
								alert(e);
						}
				})
		});

		$('.company_id').select2({
            minimumResultsForSearch: -1
        });

		$('.company_id').select2({
            minimumResultsForSearch: -1
        });
	});
</script>
@endsection
