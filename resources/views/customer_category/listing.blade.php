@extends('layouts.master')

@section('title') Customer Category Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">Customer Category Listing</h4>
                    @can('customer_category_manage')
                    <a href="{{ route('customer_category_add', ['tenant' => tenant('id')]) }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> Add New</a>
                    @endcan
                </div>



			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Customer Category</a>
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
								<th>Customer Category Name</th>
								<th>Customer Category Slug</th>
                                <th>Status</th>
								<th>Priority</th>
                                @can('customer_category_manage')
								    <th>Action</th>
                                @endcan
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())

							@foreach($records as $key => $row)
                                @php
                                    $customer_category_status = $row->customer_category_status;
                                    $span_status = '';
                                    switch ($customer_category_status) {
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
							<tr>
								<td>{{ $records->firstItem() + $key }}</td>
								<td>{{ $row->customer_category_name }}</td>
								<td>{{ $row->customer_category_slug }}</td>
								<td>{!! $span_status ?? '' !!}</td>
								<td>{{ $row->customer_category_priority }}</td>
                                @can('customer_category_manage')
                                    <td>
                                        @if ($row->company_id == auth()->user()->company_id || auth()->user()->user_type_id == 1 )
                                            <a href="{{ route('customer_category_edit', ['tenant' => tenant('id'), 'id' => $row->customer_category_id]) }}"class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->customer_category_id }}">Delete</button>
                                        @endif
                                    </td>
                                @endcan
							</tr>
							{{-- <?php $i++; ?> --}}
							@endforeach
							@else
							<tr>
								<td colspan="7" class="text-center">No record found.</td>
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

 <!-- Modal -->
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('customer_category_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this customer category ?</h4>
                        <input type="hidden" name="customer_category_id" id="customer_category_id">
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
			$(".modal-body #customer_category_id").val(id);
		});
	});
</script>
@endsection
