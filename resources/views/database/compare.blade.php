
@extends('layouts.master')

@section('title') {{$title}} @endsection

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
                    <h4 class="mb-0 font-size-18 mr-2">{{$title}}</h4>
                    {{-- @can('worker_type_manage')
                    <a href="{{ route('worker_type_add', ['tenant' => tenant('id')]) }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> ADD NEW</a>
                    @endcan --}}
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Worker Type</a>
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
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="sync">
                                        Sync DB
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
								<th>Database (LIVE)</th>
							</tr>
						</thead>
						<tbody>
                            <tr>
                                <td>
                                    @foreach ($result_live as $rows)
                                        {{$rows}}<br>
                                    @endforeach
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
                <div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th>Database (STAGING)</th>
							</tr>
						</thead>
						<tbody>
                            <tr>
                                <td>
                                    @foreach ($result_staging as $rows)
                                        {{$rows}}<br>
                                    @endforeach
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

 <!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('worker_type_delete', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Delete this worker ?</h4>
                    <input type="hidden" name="worker_type_id" id="worker_type_id">
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
			$(".modal-body #worker_type_id").val(id);
		});
	});
</script>
@endsection
