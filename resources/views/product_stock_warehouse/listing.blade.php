@extends('layouts.master')

@section('title') Product Stock Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Stock Warehouse Listing</span>
				@can('admin_manage')
					<!-- <a href="{{ route('product_stock_warehouse_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a> -->
				@endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Product Stock Warehouse</a>
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
											{!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
										</div>
									</div>
								@endif
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Warehouse</label>
										{!! Form::select('warehouse_id', $setting_warehouse_sel, @$search['warehouse_id'], ['class' => 'form-control warehouse_id']) !!}
									</div>
								</div>
								@if (count($product_id_sel) > 1)
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Product</label>
										{!! Form::select('product_id', $product_id_sel, @$search['product_id'], ['class' => 'select2 form-control product_id']) !!}
									</div>
								</div>
								@endif
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
								<th scope="col" style="width: 50px; text-align: center;">#</th>
								<th >Warehouse Name</th>
								<th style="text-align: center;">Product</th>
								<th style="text-align: center;">Grade</th>
								<th style="text-align: center;">Current Quantity</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
								@php $i = $records->perPage()*($records->currentPage() - 1) + 1; @endphp
								@foreach($records as $stock)
								<tr>
									<td align="center">
										{{ $i }}
									</td>
									<td>
										{{$stock->setting_warehouse->warehouse_name}}
									</td>
									<td align="center">
										@if($stock->product)
										{{$stock->product->product_name}}
										@endif
									</td>
									<td align="center">
										@if($stock->setting_size)
										{{$stock->setting_size->setting_product_size_name}}
										@endif
									</td>
									<td align="center">
										{{$stock->product_stock_warehouse_qty_current}}
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
<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
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
	});
</script>
@endsection
