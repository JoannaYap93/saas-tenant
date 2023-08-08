@extends('layouts.master')

@section('title') Sync Customer Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-1">Sync Delivery Order Items Listing</h4>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sync Delivery Order Items Listing</a>
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
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th scope="col" style="width: 70px;">#</th>
                                <th>Product Name</th>
                                <th>Product Grade</th>
                                <th>Product Items Order Quantity</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
							<?php $i = 1; ?>
							@foreach($records as $key => $row)
                               <b> {{'#'. @$row->sync_delivery_order->sync_delivery_order_no }}</b> <br> <br>
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td> 
                                    {{ @$row->product->product_name }} 
                                </td>
                                <td>
                                    {{ @$row->setting_product_size->setting_product_size_name }}
                                   
                                </td>
                                <td>{{round( $row->sync_delivery_order_item_quantity ,4) }} <b>KG</td>
                                
                            </tr>
							<?php $i++; ?>
							@endforeach
							@else
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

