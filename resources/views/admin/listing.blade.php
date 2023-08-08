@extends('layouts.master')

@section('title') Admin Listing @endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">
				<span class="mr-2">Admin Listing</span>
				@can('admin_manage')
					<a href="{{ route('admin_add') }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1" ><i class="fas fa-plus"></i> Add New</a>
				@endcan
			</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Admin</a>
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
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Gender</label>
										{!! Form::select('user_gender', $user_gender_sel, @$search['user_gender'], ['class' => 'form-control select2 user_gender']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Admin Status</label>
										{!! Form::select('user_status', $user_status_sel, @$search['user_status'], ['class' => 'form-control select2 user_status']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="validationCustom03">Admin Role</label>
										{!! Form::select('user_role_id', $user_role_sel, @$search['user_role_id'], ['class' => 'form-control select2 user_role']) !!}
									</div>
								</div>
								@if(auth()->user()->user_type_id)
									<div class="col-md-3">
										<div class="form-group">
											<label for="validationCustom03">Admin Type</label>
											{!! Form::select('user_type_id', $user_type_sel, @$search['user_type_id'], ['class' => 'form-control select2 user_type']) !!}
										</div>
									</div>
								@endif
								@if (auth()->user()->company_id == 0)
									<div class="col-md-3">
										<div class="form-group">
											<label for="">Company: </label>
											{!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control']) !!}
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
								<th scope="col" style="width: 50px;">#</th>
								<th>Admin Profile</th>
								<th>Details</th>
								<th>Status</th>
								@can('admin_manage')
								<th>Action</th>
								@endcan
							</tr>
						</thead>
						<tbody>
							@if($users->isNotEmpty())
								@php $i = $users->perPage()*($users->currentPage() - 1) + 1;
                                @endphp
								@foreach($users as $user)
                                    <?php
                                    $status = '';
                                    $assign_permission = '';
                                    $change_password = '';

                                    $action = '';
                                    switch ($user->user_status) {
                                        case 'active':
                                            $status = "<span class='badge badge-primary font-size-11'>{$user->user_status}</span>";

                                            $assign_permission = "<a href='" . route('assign_permission', $user->user_id) . "' class='btn btn-sm btn-outline-primary waves-effect waves-light mb-1'>Assign Permission</a>";

                                            $change_password = "<a href='" . route('user_change_password_by_super_admin', $user->user_id) . "' class='btn btn-sm btn-outline-primary waves-effect waves-light mb-1'>Change Password</a>";

                                            $action = "<a href='" . route('admin_edit', $user->user_id) . "' class='btn btn-sm btn-outline-warning waves-effect waves-light mb-1'>Edit</a>
                                                    {$assign_permission}
                                                    <span data-toggle='modal' data-target='#suspend' data-id='$user->user_id' class='suspend'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light mb-1'>Suspend</a></span>";

                                            break;
                                        case 'suspended':
                                            $status = "<span class='badge badge-danger'>{$user->user_status}</span>";
                                            $action = "<span data-toggle='modal' data-target='#activate' data-id='$user->user_id' class='activate'><a href='javascript:void(0);' class='btn btn-sm btn-outline-success waves-effect waves-light mb-1'>Activate</a></span>";
                                            break;
                                        case 'pending':
                                            $status = "<span class='badge badge-warning'>{$user->user_status}</span>";
                                            break;
                                    }

                                    ?>
                                    <tr>
                                        <td align="center">
                                            {{-- <div class="avatar-xs">
                                                @if($user->user_profile_photo)
                                                <img src="{{ url($user->user_profile_photo) }}" class="rounded-circle avatar-xs" />
                                                @else
                                                <img class="rounded-circle avatar-xs" src="assets/images/users/avatar-3.jpg" alt="">
                                                @endif
                                            </div> --}}
                                            {{ $i }}
                                        </td>
                                        <td>
                                            <b>{{ $user->user_fullname }}</b><br />{{ $user->user_mobile }}<br>{{ $user->user_email}}
                                            <br>
                                            @if($user->company)
                                                {{$user->company->company_name}}
                                            @else
                                                -
                                            @endif
                                            <br>
                                            Code: {{$user->user_unique_code}} <br>
                                        </td>
                                        <td>
                                            Type: <b>{{ @$user->user_type->user_type_name }}</b><br>
                                            Role: <b>{{ @$user->roles[0]->name }}</b>
                                        </td>
                                        <td>
                                            <b>{{ $user->user_cdate }}</b><br>
                                            {!! $status !!}
                                        </td>
                                        @can('admin_manage')
                                            <td>
                                                {!! $action !!} <br>
                                                @can('admin_delete')
                                                <span data-toggle='modal' data-target='#delete' data-id='{{$user->user_id}}' class='delete'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light mb-1'>Delete</a></span>
                                                @endcan
                                                @if(auth()->user()->user_type_id == 1)
                                                {!! $change_password !!}
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
				{!! $users->links('pagination::bootstrap-4') !!}
			</div>
		</div>
	</div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('admin_status') }}">
				@csrf
				<div class="modal-body">
					<h4>Delete this user ?</h4>
					<input type="hidden" , name="user_id" id="user_id">
					<input type="hidden" , name="action" value="delete">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Delete</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="suspend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('admin_status') }}">
				@csrf
				<div class="modal-body">
					<h4>Suspend this user ?</h4>
					<input type="hidden" , name="user_id" id="user_id">
					<input type="hidden" , name="action" value="suspended">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger">Suspend</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="activate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('admin_status') }}">
				@csrf
				<div class="modal-body">
					<h4>Activate this user ?</h4>
					<input type="hidden" , name="user_id" id="user_id">
					<input type="hidden" , name="action" value="active">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Activate</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
	$(document).ready(function(e) {
		$('.suspend').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #user_id").val(id);
		});
		$('.delete').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #user_id").val(id);
		});
		$('.activate').on('click', function() {
			var id = $(this).attr('data-id');
			// console.log(id);
			$(".modal-body #user_id").val(id);
		});

		$('.user_status').select2({
            minimumResultsForSearch: -1
        });

		$('.user_gender').select2({
            minimumResultsForSearch: -1
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

        $(".fancybox").fancybox();
    });
</script>
@endsection
