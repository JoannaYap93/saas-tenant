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
                @can('worker_manage')
                    <a href="{{ route('worker_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                    <i class="fas fa-plus"></i> ADD NEW</a>
                @endcan
            </div>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Worker</a>
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
                                        <label for="">Worker Race</label>
                                        {!! Form::select('setting_race_id', $setting_race_sel, @$search['setting_race_id'], ['class' => 'form-control', 'id' => 'setting_race_sel']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Worker Availability</label>
                                        {!! Form::select('worker_availability', $worker_availability, @$search['worker_availability'], ['class' => 'form-control', 'id' => 'worker_availability']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Worker Status</label>
                                        {!! Form::select('worker_status_id', $worker_status_sel, @$search['worker_status_id'], ['class' => 'form-control', 'id' => 'worker_status_sel']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Worker Type</label>
                                        {{-- <select name="worker_type_id" id="worker_type_id" class=" form-control">
                                            <option value="">All types</option>
                                            @foreach ($worker_type_sel as $worker_type_id => $worker_type_name)
                                                <option value="{{$worker_type_id}}" @if ($worker_type_id == @$search['worker_type_id']) selected @endif>{{$worker_type_name}}</option>
                                            @endforeach
                                        </select> --}}
                                        {!! Form::select('worker_type_id', $worker_type_sel, @$search['worker_type_id'], ['class' => 'form-control', 'id' => 'worker_type_sel']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Worker Role</label>
                                        {!! Form::select('worker_role_id', $worker_role_sel, @$search['worker_role_id'], ['class' => 'form-control', 'id' => 'worker_role_sel']) !!}
                                    </div>
                                </div>
                                @if(auth()->user()->user_type_id == 1)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="company_id">Company</label>
                                            {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Farm Manager</label>
                                        <select name="user_id" id="user_id" class=" form-control">
                                            <option value="">Please select company first</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="is_attendance_reward" class="mb-2">Attendance Reward</label>
                                        <div>
                                            <input name="is_attendance_reward" type="checkbox" id="is_attendance_reward" switch="none" value="1" {{ @$search['is_attendance_reward'] ? 'checked' : '' }}/>
                                            <label for="is_attendance_reward" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="search">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <button type="submit" class="btn btn-danger waves-effect waves-light mr-2" name="submit" value="reset">
                                        <i class="fas fa-times mr-1"></i> Reset
                                    </button>

                                    @can('worker_manage')
                                        <a href="{{ route('worker_import', ['tenant' => tenant('id')]) }}" class="btn btn-success waves-effect waves-light mr-2">
                                            <i class="fas fa-file-import mr-1"></i>Import
                                        </a>
                                        <a href="{{ URL::asset('assets/sample/sample-import-worker.xls') }}" target="_blank" class="btn btn-info waves-effect waves-light mr-2">
                                            <i class="fas fa-download mr-1"></i>Download Sample Import
                                        </a>
                                    @endcan

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
					<table class="table table-striped mb-0">
                        @php
                            $no = $records->firstItem();
                        @endphp
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Worker Photo</th>
                                <th>Worker Details</th>
                                <th>Daily Attendance Reward</th>
                                <th>Status</th>
                                @can('worker_manage')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($records) > 0)
                                @foreach($records as $row)
                                    <tr>
                                        @if(auth()->user()->user_type_id == 1)
                                            @if(!empty($row->user_id))
                                                @if ($row->worker_role_id == 1 || $row->worker_role_id == 2 )
                                                    <td colspan="6" align="center" style="color: white; background-color:darkgrey;">
                                                        {{$row['user']['user_fullname'].' - '.$row['company']['company_name']}}
                                                @else
                                                    <td colspan="6" align="center" style="color: white; background-color:darkgrey;">
                                                        {{$row['company']['company_name']}}
                                                @endif
                                            @else
                                                <td colspan="6" align="center" style="color: white; background-color:darkgrey;">
                                                {{$row->company->company_name}}
                                            @endif
                                        @else
                                            @if(!empty($row->user_id))
                                                @if ($row->worker_role_id == 1 || $row->worker_role_id == 2)
                                                    <td colspan="6" align="center" style="color: white; background-color:darkgrey;">
                                                        {{$row['user']['user_fullname'].' - '.$row['company']['company_name']}}
                                                @endif
                                            @else
                                                <td colspan="6" align="center" style="color: white; background-color:darkgrey;">
                                                {{$row['company']['company_name']}}
                                            @endif
                                        @endif
                                    </tr>

                                    @if (count($row->sub_worker) != 0)
                                        @foreach ($row->sub_worker as $key => $sub_worker )
                                            @php
                                                $attendace_reward = '';
                                                $is_suspended = '';
                                                $is_resigned = false;

                                                switch ($sub_worker->is_attendance_reward) {
                                                    case '1':
                                                        $attendace_reward = "<span class='badge badge-primary mr-2 font-size-11'>Attendance Reward</span>";
                                                        break;
                                                    case '0':
                                                        $attendace_reward = "-";
                                                        break;
                                                    default:
                                                        break;
                                                }

                                                switch ($sub_worker->is_suspended) {
                                                    case '1':
                                                        $is_suspended = "<span class='badge badge-warning mr-2 font-size-11'>Suspended</span>";
                                                        break;
                                                    case '0':
                                                        $is_suspended = "<span class='badge badge-success mr-2 font-size-11'>Active</span>";
                                                        break;
                                                    default:
                                                        break;
                                                }

                                                switch ($sub_worker->worker_status_id) {
                                                    case '3':
                                                        $worker_status = "<span class='badge badge-danger mr-2 font-size-11'>".json_decode($sub_worker->worker_status->worker_status_name)->en."</span>";
                                                        $is_resigned = TRUE;
                                                        break;
                                                    default:
                                                        $worker_status = "<span class='badge badge-primary mr-2 font-size-11'>".json_decode($sub_worker->worker_status->worker_status_name)->en."</span>";
                                                        break;
                                                }

                                                $worker_start_date = $sub_worker->worker_start_date ? date('d-m-Y', strtotime($sub_worker->worker_start_date)) : '-';
                                            @endphp
                                            <tr>
                                                <td scope="row">{{$no++}}</td>
                                                <td>
                                                    @if($sub_worker->hasMedia('worker_media'))
                                                        <a href="{{ $sub_worker->getFirstMediaUrl('worker_media') }}" target="_blank">
                                                            <img src="{{ $sub_worker->getFirstMediaUrl('worker_media') }}" width="90px">
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- @dd($sub_worker->$worker_role->worker_role_name) --}}

                                                    <b>{{ $sub_worker->worker_name.' - #'.$sub_worker->worker_id}}</b><br>
                                                    @if (!empty($sub_worker->worker_role->worker_role_name))
                                                    <span class='badge badge-info mr-2 font-size-11'>
                                                    {{$sub_worker->worker_role->worker_role_name}}</span>
                                                    @endif
                                                    @if($sub_worker->worker_role_id==2)
                                                    <a class="badge badge-success font-size-11 popup" href="{{ route('worker_wallet_details', ['tenant' => tenant('id'), 'id' => $sub_worker->worker_id]) }}">Wallet: RM{{$sub_worker->worker_wallet_amount??0}}</a>
                                                    @endif
                                                    <br>{{ $sub_worker->worker_ic}}<br>
                                                    {{ $sub_worker->worker_mobile}}<br>
                                                    {{ $worker_start_date }}
                                                </td>
                                                <td>
                                                    @php echo $attendace_reward; @endphp
                                                </td>
                                                <td>
                                                    <span class='badge badge-info mr-2 font-size-11'>
                                                        {{ $sub_worker->worker_type->worker_type_name }}</span>
                                                        {!!$is_suspended!!}
                                                        {!!$worker_status!!}
                                                    <br>
                                                    Created Date: {{ date('d-m-Y', strtotime($sub_worker->worker_created))}}<br>
                                                    @if ($is_resigned && @$sub_worker->worker_resigned_date != null)
                                                        Resigned Date: <span style="color:red">{{ date('d-m-Y', strtotime($sub_worker->worker_resigned_date)) }}</span>
                                                    @endif
                                                </td>
                                                @can('worker_manage')
                                                    <td>
                                                        <a href="{{ route('worker_edit', ['tenant' => tenant('id'), 'id' => $sub_worker->worker_id]) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                        @if($sub_worker->worker_role_id==2)
                                                        <a href="{{ route('worker_wallet_adjustment', ['tenant' => tenant('id'), 'id' => $sub_worker->worker_id]) }}" class='btn btn-sm btn-outline-success waves-effect waves-light'>Adjust Wallet</a>
                                                        @endif
                                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ $sub_worker->worker_id }}">Delete</button>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" align="center">
                                        No record found.
                                    </td>
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
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('worker_delete', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Delete this worker ?</h4>
                    <input type="hidden" name="worker_id" id="worker_id">
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
	$(document).ready(function(e) {
		$('.delete').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #worker_id").val(id);
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
    @if(auth()->user()->user_type_id == 1)
        $('#company_id').on('change', function() {
            get_farm_manager($(this).val());
        });
    @else
        let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
        get_farm_manager(company_id);
    @endif

        var exist_company = "<?php echo @$search['company_id']; ?>";

        if (exist_company > 0) {
            $('#company_id').trigger("change");
        }
	});

    function get_farm_manager(company_id) {
        var exist_farm_manager = "<?php echo @$search['user_id']; ?>";
        let user = '<option value="">Please Select Farm Manager</option>';
        $('#user_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "{{ route('ajax_get_farm_manager_sel_by_company', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
            },
            success: function(e) {
                if (e.length > 0) {
                    e.forEach(u => {
                        if(u.id == exist_farm_manager){
                            user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                        }else{
                            user += '<option value="' + u.id + '">' + u.name +'</option>';
                        }
                    });
                    $('#user_id').html(user);
                } else {
                    $('#user_id').html('<option value="">No Farm Manager</option>');
                }
            }
        });
    }
</script>
@endsection
