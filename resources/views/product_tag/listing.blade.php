@extends('layouts.master')

@section('title') Product Tag Listing @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">Product Tag Listing</h4>
                    @can('product_tag_manage')
                    <a href="{{ route('product_tag_add') }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product Tag</a>
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
								<th>Product Tag Name</th>
								<th>Product Tag Slug</th>
                                <th>Status</th>
                                @can('product_tag_manage')
								    <th>Action</th>
                                @endcan
							</tr>
						</thead>
						<tbody>
                            @php
                                $no = $records->firstItem();
                            @endphp

                            @forelse ($records as $row)
                                @php
                                    $product_tag_status = $row->product_tag_status;
                                    $span_status = '';
                                    switch ($product_tag_status) {
                                        case 'draft':
                                            $span_status = "<span class='badge badge-info font-size-11'>Draft</span>";
                                            break;
                                        case 'published':
                                            $span_status = "<span class='badge badge-success font-size-11'>Published</span>";
                                            break;
                                        case 'deleted':
                                            $span_status = "<span class='badge badge-danger font-size-11'>Deleted</span>";
                                            break;
                                        default:
                                            break;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row->product_tag_name }}</td>
                                    <td>{{ $row->product_tag_slug }}</td>
                                    <td>{!! $span_status ?? '' !!}</td>
                                    @can('product_tag_manage')
                                        <td>
                                            <a href="{{ route('product_tag_edit', $row->product_tag_id) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $row->product_tag_id }}">Delete </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No record found.</td>
                                </tr>
                            @endforelse
						</tbody>
					</table>
				</div>
                {!! $records->links('pagination::bootstrap-4') !!}
			</div>
		</div>
	</div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('product_tag_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this product tag ?</h4>
                        <input type="hidden" name="product_tag_id" id="product_tag_id">
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
			$(".modal-body #product_tag_id").val(id);
		});
	});
</script>
@endsection
