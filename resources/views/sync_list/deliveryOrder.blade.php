@extends('layouts.master')

@section('title') Sync Delivery Order Listing @endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>

    </style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Sync Deliver Order Listing</h4>

                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sync Deliver Order</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<!-- end page title -->

{{--  --}}
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
                                            <label for="validationCustom03">Order Type Name</label>
                                            {!! Form::select('delivery_order_type_id', $order_type_sel, @$search['delivery_order_type_id'], ['class' => 'form-control', 'id' => 'delivery_order_type_id']) !!}
                                        </div>
								    </div>
                                    @if (auth()->user()->company_land_id == 0)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Company Land Name</label>
                                            {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'cl.company_land_id']) !!}
                                        </div>
								    </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="cc_from"
                                                    placeholder="Start Date" value="{{ @$search['cc_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="cc_to"
                                                    placeholder="End Date" value="{{ @$search['cc_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
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
{{--  --}}


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th scope="col" style="width: 70px;">#</th>
                                <th>Customer</th>
                                <th>Delivery Order Details</th>
                                <!-- <th>Sync Delivery Order Total Quantity</th> -->
                                <th>Company Land</th>
                                <th>Items</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
							<?php $i = 1; ?>
							@foreach($records as $key => $row)
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>

                                <td><b>{{ $row->customer_name }}</b><br>
                                    {{ $row->customer_ic }}</td>
                                <td>
                                    @php
                                        if (@$row->delivery_order) {
                                            $deliveryOrder = $row->delivery_order;
                                            $delivery_order_no = $deliveryOrder->delivery_order_no ;
                                        } else {
                                            $delivery_order_no = '';
                                            $delivery_order_no = "<b>No Delivery Order</b>";
                                        }
                                    @endphp
                                    <b>{!!'#'. $delivery_order_no !!}</b><br>
                                    {{ $row->sync_delivery_order_date }}<br>
                                    @php
                                        if (@$row->delivery_order_type) {
                                            $deliveryOrderType = $row->delivery_order_type;
                                            $delivery_order_type_name = $deliveryOrderType->delivery_order_type_name . '<br>';
                                        } else {
                                            $delivery_order_type_name = '';
                                            $delivery_order_type_name = "<b>No Delivery Order</b>";
                                        }
                                    @endphp
                                    {!! $delivery_order_type_name !!}<br>
                                </td>

                                <td>
                                @php
                                    if (@$row->company_land) {
                                        $companyLand = $row->company_land;
                                        $company_land_name = $companyLand->company_land_name . '<br>';
                                    } else {
                                        $company_land_name = "<b>No Company Land</b>";
                                    }
                                @endphp
                                {!! $company_land_name !!}
                                </td>

                                <td>
                                    <a href="{{ route('get_items_from_do', @$row->sync_delivery_order_id ?? 0) }}"
                                                        class="btn btn-outline-success btn-sm">View Items</a>
                                    @if (auth()->user()->user_type_id == 2)
                                        <a href="{{ route('view_sync_do_pdf', @$row->delivery_order_id ?? 0) }}" target="_blank"
                                            class="btn btn-outline-info btn-sm">View PDF</a>
                                    @endif
                                </td>
                                    <!-- syncDeliveryOrderItems_listing -->

                            </tr>
							<?php $i++; ?>
							@endforeach
							@<?php else: ?>
							<tr>
								<td colspan="4">No record found.</td>
							</tr>
							@endif
						</tbody>
					</table>
                    {!! $records->links('pagination::bootstrap-4') !!}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endsection
