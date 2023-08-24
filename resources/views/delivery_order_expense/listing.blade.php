@extends('layouts.master')

@section('title') D.O. Expense Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">D.O. Expense Listing</span>
				@can('customer_term_manage')
					<!-- <a href="{{ route('customer_term_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a> -->
				@endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Expense</a>
					</li>
					<li class="breadcrumb-item active">Listing</li>
				</ol>
			</div>
		</div>
	</div>
</div>
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
								<th>Order No.</th>
								<th>Expense Details</th>
								<th>Date Created<th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if($do_exp->isNotEmpty())
								@php $i = $do_exp->perPage()*($do_exp->currentPage() - 1) + 1; @endphp
								@foreach($do_exp as $exp)
								<tr>
									<td align="center">
										{{ $i }}
									</td>
									<td>
										<b>{{$exp->delivery_order_no}}</b>
									</td>
									<td>
										@foreach($exp->delivery_order_expense as $key => $doex)
											<b>{{ $doex->expenses->setting_expense_name }}</b> - RM {{$doex->delivery_order_expense_value}}<br>
											@if($doex->expenses->setting_expense_type->setting_expense_type_id == 4 | $doex->expenses->setting_expense_type->setting_expense_type_id == 1)
											<b>Per</b> - {{$doex->delivery_order_expense_kg}} Kg<br>
											<b>Per</b> - {{$doex->delivery_order_expense_day}} Day(s)<br>
											@elseif($doex->expenses->setting_expense_type->setting_expense_type_id == 2)
											<b>Per</b> - {{$doex->delivery_order_expense_kg}} Kg<br>
											@elseif($doex->expenses->setting_expense_type->setting_expense_type_id == 3)
											<b>Per</b> - {{$doex->delivery_order_expense_day}} Day(s)<br>
											@endif
											<b>Total</b> - RM {{$doex->delivery_order_expense_total}}<br>
											<hr>

										@endforeach
									</td>
									<td></td>
									<td>
										@can('customer_term_manage')
                                            <!-- <a href="" class='btn btn-sm btn-outline-warning waves-effect waves-light'>Edit</a> -->
											<!-- <span data-toggle='modal' data-target='#delete' data-id='' class='delete'><a href='javascript:void(0);' data-toggle='modal' data-target='#delete' data-id=''class="btn btn-sm btn-outline-danger waves-effect waves-light">Delete</a></span> -->
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
				{{$do_exp->links('pagination::bootstrap-4')}}
			</div>
		</div>
	</div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
			<form method="POST" action="{{ route('customer_term_delete', ['tenant' => tenant('id')]) }}">
				@csrf
				<div class="modal-body">
					<h4>Delete this Term ?</h4>
					<input type="hidden" name="customer_term_id" id="customer_term_id">
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
			$(".modal-body #customer_term_id").val(id);
		});

		$('.suspend').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #customer_term_id").val(id);
		});
		$('.activate').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #customer_term_id").val(id);
		});

		$('.company_id').select2({
            minimumResultsForSearch: -1
        });
	});
</script>
@endsection
