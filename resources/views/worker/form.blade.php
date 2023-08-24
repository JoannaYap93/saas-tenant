@extends('layouts.master')

@section('title') {{ $title }} Worker @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $title }} Worker</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Worker</a>
					</li>
					<li class="breadcrumb-item active">Form</li>
				</ol>
			</div>
		</div>
	</div>
</div>

@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ $title}} Worker</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="worker_photo">Worker Photo</label>
                                    @if ($edit == true && @$post->media)
                                        @if (@$post->worker_id ? @$post->hasMedia('worker_media') : null)
                                            <br><img src="{{ @$post->getFirstMediaUrl('worker_media') }}" alt="" width="150" style="margin-bottom:5px;" class="img-fluid img-thumbnail avatar-xl">
                                        @endif
                                    @endif
                                <input type="file" name="worker_photo" class="form-control-file" accept=".jpeg,.png,.jpg">
                            </div>

                            <div class="form-group">
                                <label>Worker Name<span class="text-danger">*</span></label>
                                <input type="text" name="worker_name" class="form-control" value="{{ @$post->worker_name }}" required>
                            </div>

                            <div class="form-group">
                                <label>Worker IC<span class="text-danger">*</span></label>
                                <input type="text" id="worker_ic" name="worker_ic" class="form-control number_only" value="{{ @$post->worker_ic }}" required >
                                <span id="error_worker_ic"></span>
                            </div>

                            <div class="form-group">
                                <label for="worker_mobile">Worker Mobile<span class="text-danger">*</span></label>
                                <input name="worker_mobile" class="form-control" maxlength="12" value="{{ @$post->worker_mobile }}" required>
                            </div>

                            <div class="form-group">
                                <label>Worker Race<span class="text-danger">*</span></label>
                                {!! Form::select('setting_race_id', $setting_race_sel, @$post->setting_race_id, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label>Worker Status<span class="text-danger">*</span></label>
                                {!! Form::select('worker_status_id', $worker_status_sel, @$post->worker_status_id, ['class' => 'form-control','required']) !!}
                            </div>

                            <div class="form-group">
                                <label>Worker Type<span class="text-danger">*</span></label>
                                {!! Form::select('worker_type_id', $worker_type_sel, @$post->worker_type_id, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="form-group">
                                <label>Worker Role<span class="text-danger">*</span></label>
                                {!! Form::select('worker_role_id', $worker_role_sel, @$post->worker_role_id, ['class' => 'form-control', 'required', 'id' => 'worker_role_id']) !!}
                            </div>

                            @php
                                $current_date = date('Y-m-d');
                            @endphp

                            <div class="form-group">
                                <label>Start Date<span class="text-danger">*</span></label>
                                <div class="input-group" >
                                    <input name="worker_start_date" class="form-control input-mask" id="datepicker" value="{{ @$post->worker_start_date ? substr(@$post->worker_start_date, 0, 10) : $current_date }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                    <span class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">

                            <div class="form-group">
                                <label>Company<span class="text-danger">*</span></label>
                                @if(auth()->user()->user_type_id == 1)
                                    {!! Form::select('company_id', $company_sel, @$post->company_id, ['class' => 'form-control','id'=> 'company_id']) !!}
                                @else
                                    <br><span><b>{{ auth()->user()->company->company_name }}</b></span>
                                    <input hidden name="company_id" id="company_id" value="{{auth()->user()->company_id}}"/>
                                @endif
                            </div>

                            @if ($edit == true)
                                @if($post->worker_role_id == 1)
                                    <div class="form-group" id="farm_manager_div">
                                        <label>Farm Manager<span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class=" form-control">
                                        </select>
                                    </div>
                                @else
                                    <div class="form-group" id="farm_manager_div">
                                        <label>Farm Manager<span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class=" form-control">
                                        </select>
                                    </div>
                                @endif
                            @else
                            <div class="form-group" id="farm_manager_div">
                                <label>Farm Manager<span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class=" form-control">
                                </select>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="is_attendance_reward" class="mb-2">Attendance Reward</label>
                                <div>
                                    <input name="is_attendance_reward" type="checkbox" id="is_attendance_reward" switch="none" value="1" {{ @$post->is_attendance_reward ? 'checked' : '' }}/>
                                    <label for="is_attendance_reward" data-on-label="On" data-off-label="Off"></label>
                                </div>
                            </div>
                            <div class="form-group" id="reward_div">
                                <label>Reward</label>
                                <select name="setting_reward_id" id="setting_reward_id" class=" form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('worker_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(e) {
            var worker_role_id = '{{ @$post->worker_role_id }}';
            if(worker_role_id != 1){
                $("#farm_manager_div").hide();
            }else{
                $("#farm_manager_div").show();
            }

            var is_attendance_reward = '{{ @$post->is_attendance_reward }}';
            if(is_attendance_reward != 1){
                $("#setting_reward_id").hide();
                $("#reward_div").hide();
            }

            $("#is_attendance_reward").change(function(){
                if($(this).prop("checked")){
                    $("#setting_reward_id").show();
                    $("#reward_div").show();
                }else{
                    $("#setting_reward_id").hide();
                    $("#reward_div").hide();
                }
            });

            $('.number_only').bind('input paste', function(){
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            @if(auth()->user()->user_type_id == 1)
                $('#company_id').on('change', function() {
                    get_farm_manager($(this).val());
                    get_reward($(this).val());
                    get_user_list($(this).val());

                });
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                get_farm_manager(company_id);
                get_reward(company_id);
                get_user_list(company_id);
            @endif

            var exist_company = "<?php echo @$post->company_id; ?>";

            if (exist_company > 0) {
                $('#company_id').trigger("change");
            }

            $('#worker_ic').blur(function(){
            var worker_ic = $('#worker_ic').val();
            var worker_id = {{$worker_id}};

            if(worker_ic){
                var error_worker_id = '';
                $.ajax({
                    type: 'POST',
                    url: "{{route('ajax_check_worker_ic', ['tenant' => tenant('id')])}}",
                    data: {
                        worker_ic: $(this).val(),
                        worker_id: worker_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(e) {
                        console.log(e);
                        if (e.status == true){
                            if(e.data == 'unique'){
                                $('#error_worker_ic').html('<label class="text-success">Worker IC Available</label>');
                                $('#worker_ic').removeClass('has-error');
                            }else{
                                $('#error_worker_ic').html('<label class="text-danger">Worker IC already exist - ' + e.data + '</label>');
                                $('#worker_ic').addClass('has-error');
                            }
                        }
                        else{
                            $('#error_worker_ic').html('<label class="text-danger">Invalid Worker IC </label>');
                            $('#worker_ic').addClass('has-error');
                        }
                    }
                });
            }else{
                $('#error_worker_ic').html('<label class="text-danger">Invalid Worker IC</label>');
                $('#worker_ic').addClass('has-error');
            }
        });
        });

        $('#worker_role_id').on('change', function() {
            let role = $(this).val();
            if (role == 1) {
                $('#farm_manager_div').show();
            } else {
                $('#farm_manager_div').hide();
            }
        });

        function get_farm_manager(company_id) {
            var exist_farm_manager = "<?php echo @$post->user_id; ?>";
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
                                user += '<option value="' + u.id + '" selected>' +u.id +' - ' + u.name +'</option>';
                            }else{
                                user += '<option value="' + u.id + '">' + u.id +' - ' + u.name +'</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No Farm Manager</option>');
                    }
                }
            });
        }
        function get_reward(company_id) {
            var exist_reward = "<?php echo @$post->setting_reward_id; ?>";
            let user = '<option value="">Please Select Reward</option>';

            $('#setting_reward_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_reward_sel_by_company', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                },
                // dd(company_id)
                success: function(e) {
                    if (e.length > 0) {
                        e.forEach(u => {
                            if(u.id == exist_reward){
                                user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                            }else{
                                user += '<option value="' + u.id + '">' + u.name +'</option>';
                            }
                        });
                        $('#setting_reward_id').html(user);
                    } else {
                        $('#setting_reward_id').html('<option value="">No Reward</option>');
                    }
                }
            });
        }

    </script>
@endsection
