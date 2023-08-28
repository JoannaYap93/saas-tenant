@extends('layouts.master')

@section('title') {{ $title }} Admin @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">


@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }} Admin</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Admin</a>
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
                    <h4 class="card-title mb-4">Admin Access</h4>
                    <div class="row justify-content-sm-center">
                        <div class="col-sm-6">
                            <div class="form-group text-center">
                                @if($title == 'Edit' && @$post && $admin)
                                    @if($post->media)
                                        <img id="profile_img" style="object-fit: cover" class="m-auto p-auto rounded-circle avatar-xl" src="{{ @$admin->getFirstMediaUrl('user_profile_photo')}}"  height="50"/>
                                    @else
                                        <img id="profile_img" class="m-auto p-auto rounded-circle avatar-xl" src="{{ "https://ui-avatars.com/api/?name=".Auth::user()->user_fullname }}"  height="50"/>
                                    @endif
                                @else
                                    <img class="m-auto p-auto rounded-circle avatar-xl" src="{{ "https://ui-avatars.com/api/?name=".Auth::user()->user_fullname }}"  height="50"/>
                                @endif
                                <label class="avatar-xl" style="position:absolute; cursor: pointer; display:contents" id="camera">
                                    <i style="position: absolute; background-color: #d9d9d9; border-radius: 50%; padding: 10px" class="fa fa-camera"></i>
                                </label>
                                <div class="user-profile-inner file-wrapper mt-4">
                                    <input name="user_profile_photo" id="upload_image" class="user-profile d-none" type="file" accept=".jpeg,.png,.jpg">
                                    <span id="file_name" class="mt-1 h-25 border"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_email">Email<span class="text-danger">*</span></label>
                                <input name="user_email" type="email" class="form-control" maxlength="100" value="{{ @$post->user_email }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_mobile">Mobile No<span class="text-danger">*</span></label>
                                <input name="user_mobile" maxlength="45" class="form-control" maxlength="12" value="{{ @$post->user_mobile }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Password<span class="text-danger">*</span><span class="bx bxs-info-circle info-tooltip" data-toggle="tooltip" data-placement="top" title="Minimum 8 character"></span></label>
                                <input name="password" type="password" class="form-control" maxlength="100" value="" @if ($title != 'Edit') required @endif>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password<span class="text-danger">*</span><span class="bx bxs-info-circle info-tooltip" data-toggle="tooltip" data-placement="top" title="Minimum 8 character"></span></label>
                                <input name="confirm_password" type="password" class="form-control" maxlength="100" value="" @if ($title != 'Edit') required @endif>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Admin Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_fullname">Full Name<span class="text-danger">*</span></label>
                                <input name="user_fullname" type="text" maxlength="100" class="form-control" maxlength="100" value="{{ @$post->user_fullname }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="user_role">
                                <label class="control-label">Admin Role<span class="text-danger">*</span></label>
                                {!! Form::select('user_role_id', $user_role_sel, (@$post->user_role_id?$post->user_role_id:@$user_role->id), ['class' => 'form-control', 'id' => 'user_role_id', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Gender<span class="text-danger">*</span></label>
                                {!! Form::select('user_gender', $user_gender_sel, @$post->user_gender, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    @if ($edit == true)
                            <div class="col-sm-6" id="worker_id_div">
                                <div class="form-group">
                                    <label class="control-label">Worker ID<span class="text-danger">*</span></label>
                                    <select name="worker_id" id="worker_id" class="form-control">
                                    </select>
                                </div>
                                <input hidden name="worker_id_existing"  id="worker_id_existing" type="text" class="form-control">
                            </div>
                    @elseif ($edit == false)
                        <div class="col-sm-6" id="worker_id_div">
                        <div class="form-group">
                            <label class="control-label">Worker ID<span class="text-danger">*</span></label>
                            <select name="worker_id" id="worker_id" class="form-control">
                            </select>
                        </div>
                        </div>
                    @endif

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_dob">Date of Birth</label>
                                <div class="input-group">
                                    <input name="user_dob" class="form-control input-mask" id="datepicker" value="{{ @$post->user_dob }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
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
                                <label for="user_nationality">Nationality<span class="text-danger">*</span></label>
                                <input name="user_nationality" type="text" class="form-control" maxlength="100" value="{{ @$post->user_nationality ?? 'Malaysian' }}" required>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->user_type_id == 1)
                        <div class="row">
                            <div class="col-sm-6" style="display: flex; justify-content: space-around">
                                @foreach($user_type_radio as $id => $name)
                                    <div class="form-check" id="">
                                        <input class="form-check-input" type="radio" name="user_type" id="user_type_{{$id}}" value="{{$id}}" {{@$post->user_type->user_type_id == $id ? 'checked' : ''}} >
                                        <label for="user_type_{{$id}}">{{$name}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
					    <input type="hidden" , name="user_type" value="2">
                    @endif
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" id="company_id_section">
                                <label class="control-label">Company</label>
                                @if($user->company_id)
                                    {!! Form::select('company_id', $company_sel, (@$user->company_id), ['class' => 'form-control', 'id' => 'company_id','readonly']) !!}
                                @else
                                    {!! Form::select('company_id', $company_sel, (@$post->company_id), ['class' => 'form-control', 'id' => 'company_id']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_unique_code">Unique Code<span class="text-danger">*</span></label>
                                <span id="checking_loading" style="font-style: Italic; color: grey; font-size: 12px; font-Weight: 200;"> Checking Unique Code...</span>
                                <span id="accepted_insert_alert" style="font-style: Italic; color: green; font-size: 12px; font-Weight: 200;"> Unique Code Accepted</span>
                                <span id="invalid_insert_alert" style="font-style: Italic; color: red; font-size: 12px; font-Weight: 200;"> Invalid unique code, try again...</span>
                                @if ($title == "Add")
                                    <input id="user_unique_code" name="user_unique_code" type="text" class="form-control" maxlength="4" min="2" style="text-transform:uppercase" value="{{ @$post->user_unique_code }}">
                                @else
                                    <input readonly class="form-control" style="text-transform:uppercase" value="{{ @$post->user_unique_code }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" id="company_cb">
                                <label for="user_land">Company</label><br>
                                <div class="custom-control custom-checkbox col-sm-6">
                                    <input type="checkbox" id="check_all_2" class="form-check-input"/>
                                    <label for="check_all_2">Select All</label>
                                </div>
                                <div class="row col-sm-12">
                                    @foreach ($company_cb as $id => $company)
                                        <div class="custom-control custom-checkbox col-sm-6">
                                            <input type="checkbox" id="company_cb_{{ $id }}" name="company_cb_id[]" value="{{ $id }}" class= "form-check-input check_company_cb_id"
                                                @if(@$post->user_company)
                                                    @foreach(@$post->user_company as $key => $user_company)
                                                        {{ $user_company->company_id == $id ? 'checked' : '' }}
                                                    @endforeach
                                                @endif
                                            />
                                            <label for="company_cb_{{ $id }}">{{ $company }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="company_land">

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Address Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_address">Address 1</label>
                                <input name="user_address" type="text" maxlength="100" class="form-control" value="{{ @$post->user_address }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_address2">Address 2</label>
                                <input name="user_address2" type="text" maxlength="100" class="form-control" value="{{ @$post->user_address2 }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="user_postcode">Postcode</label>
                                <input name="user_postcode" class="form-control" value="{{ @$post->user_postcode }}" maxlength="45">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="user_state">State</label>
                                <input name="user_state" maxlength="45" class="form-control"  value="{{ @$post->user_state }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="user_city">City</label>
                                <input name="user_city" maxlength="45" class="form-control"  value="{{ @$post->user_city }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <span id="error_user"></span>
                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('admin_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="existUserModal" tabindex="-1" aria-labelledby="existUserModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form method="POST" action="{{ route('update_user_to_admin', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">User Exists</h5>
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to add this user to admin?</p>
                    <div>
                        <div class="row">
                            <div class="col-4">Full Name : </div>
                            <div class="col-8">
                                <b><span id="user_fullname_exist"></span></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Email : </div>
                            <div class="col-8">
                                <b><span id="user_email_exist"></span></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Type : </div>
                            <div class="col-8">
                                <b><span id="user_type_exist"></span></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Join Date : </div>
                            <div class="col-8">
                                <b><span id="user_cdate_exist"></span></b>
                            </div>
                        </div>
                        <input type="hidden" id="user_id_exist" name="user_id" value="">
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary" name="submit" value="1">Yes</button>
                    <button class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<!-- Plugins js -->
<script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
{{-- <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script> --}}

<!-- form mask -->
<script src="{{ global_asset('assets/libs/inputmask/inputmask.min.js')}}"></script>

<!-- form mask init -->
<script src="{{ global_asset('assets/js/pages/form-mask.init.js')}}"></script>

<script>
$(document).ready(function(e) {
    $('#company_id_section').hide();
    $('#company_cb').hide();
    if($('input[name="user_type"]')[0].checked == true && $('input[name="user_type"]').val() == 1){
        $('#company_id_section').hide();
        $('#company_cb').show();
    }else{
        $('#company_id_section').show();
        $('#company_cb').hide();
    }

    var user_role_id = '{{ @$user_role->id }}';
    if (user_role_id  != 13) {
        $('#worker_id_div').hide();
    } else {
        $('#worker_id_div').show();
    }

    $(document).on('click', 'input[name="user_type"]', function(){
        let id = $(this).val();

        if(id == 1 || ($(this)[0].checked == true && id == 1)){
            $('#company_id_section').hide();
            $('#company_cb').show();
        }else{
            $('#company_id_section').show();
            $('#company_cb').hide();
        }
    })


    $('#file_name').hide();
    $('#camera').click(function() {
        $('#upload_image').click();
        $('#file_name').show();
    });
    $('#upload_image').on('change', function() {
        $('#file_name').html($(this).val());
    });
    $('#invalid_insert_alert').hide();
    $('#checking_loading').hide();
    $('#accepted_insert_alert').hide();
        $('#user_unique_code').bind('input', function() {
        var c = this.selectionStart,
            r = /[^a-z0-9 .]/gi,
            v = $(this).val();
        if(r.test(v)) {
            $(this).val(v.replace(r, ''));
            c--;
        }
        this.setSelectionRange(c, c);
    });
    $('#user_unique_code').on('keyup', function(){
        var value = $(this).val();
        $.ajax({
            url: "{{ route('ajax_check_user_unique_code', ['tenant' => tenant('id')]) }}",
            method: 'post',
            data: {
                user_unique_code: value,
                _token: '{{csrf_token()}}'
            },
            beforeSend: function () {
                $('#checking_loading').show();
                $('#invalid_insert_alert').hide();
                $('#accepted_insert_alert').hide();
            },
            complete: function () {
                $('#checking_loading').hide();
            },
            success: function (e) {
                if (e.status == true) {
                    $('#invalid_insert_alert').show();
                    $('#accepted_insert_alert').hide();
                    $('#submit').prop('disabled', true);
                }else if (e.status == false && value.length >= 2){
                    $('#accepted_insert_alert').show();
                    $('#invalid_insert_alert').hide();
                    $('#submit').prop('disabled', false);
                }else if (e.status == false && value.length < 2){
                    $('#invalid_insert_alert').show();
                    $('#accepted_insert_alert').hide();
                    $('#submit').prop('disabled', true);
                }
            }
        });
    })

    @if(auth()->user()->user_type_id == 1)
                $('#company_id').on('change', function() {
                    get_worker_list($(this).val());

                });
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                get_worker_list(company_id);
            @endif

            var exist_company = "<?php echo @$post->company_id; ?>";

            if (exist_company > 0) {
                $('#company_id').trigger("change");
            }

    // $("input[name='user_mobile']").on('change', function() {
    //     let user_mobile = $(this).val();
    //
    //     $.ajax({
    //         url: "{{ route('ajax_check_user_mobile', ['tenant' => tenant('id')]) }}",
    //         method: 'post',
    //         data: {
    //             user_mobile: user_mobile,
    //             _token: '{{csrf_token()}}'
    //         },
    //         success: function (e) {
    //             if (e.status) {
    //                 $('#user_id_exist').val(e.data.user_id);
    //                 $('#user_fullname_exist').html(e.data.user_fullname);
    //                 $('#user_email_exist').html(e.data.user_email);
    //                 $('#user_type_exist').html(e.data.user_type);
    //                 $('#user_cdate_exist').html(e.data.user_cdate);
    //                 $('#existUserModal').modal('show');
    //             }
    //         }
    //     });
    // });

    $('#user_slug').blur(function(){
        var user_slug = $('#user_slug').val();
        var user_id = {{$user->user_id}};

        if(user_slug){
            var error_slug = '';
            $.ajax({
                type: 'POST',
                url: "{{route('ajax_check_user_slug', ['tenant' => tenant('id')])}}",
                data: {
                    user_slug: $(this).val(),
                    user_id: user_id,
                    _token: '{{csrf_token()}}'
                },
                success: function(e) {
                    if (e.status == true){
                        if(e.data == 'unique'){
                            $('#error_user').html('');
                            $('#error_user_slug').html('<label class="text-success">User Slug Available</label>');
                            $('#user_slug').removeClass('has-error');
                            $('#submit').attr('disabled', false);
                        }else{
                            $('#error_user').html('<label class="text-danger">*User slug already exist</label><br>');
                            $('#error_user_slug').html('<label class="text-danger">User slug already exist</label>');
                            $('#user_slug').addClass('has-error');
                            $('#submit').attr('disabled', 'disabled');
                        }
                    }else{
                        $('#error_user').html('<label class="text-danger">*Invalid User Slug</label><br>');
                        $('#error_user_slug').html('<label class="text-danger">Invalid User Slug</label>');
                        $('#user_slug').addClass('has-error');
                        $('#submit').attr('disabled', 'disabled');
                    }
                }
            });
        }else{
            $('#error_user').html('<label class="text-danger">*Invalid User Slug</label><br>');
            $('#error_user_slug').html('<label class="text-danger">Invalid User Slug</label>');
            $('#user_slug').addClass('has-error');
            $('#submit').attr('disabled', 'disabled');
        }
    });

    $('#check_all').on('click', function(event) {
        if(this.checked) {
            $('.check_company_land_id').each(function() {
                this.checked = true;
            });
        } else {
            $('.check_company_land_id').each(function() {
                this.checked = false;
            });
        }
    });

    $('#check_all_2').on('click', function(event) {
        if(this.checked) {
            $('.check_company_cb_id').each(function() {
                this.checked = true;
            });
        } else {
            $('.check_company_cb_id').each(function() {
                this.checked = false;
            });
        }
    });

    $('#company_id').on('change', function() {
        let company_id = $(this).val();

        if(company_id != ''){
            get_company_land(company_id)
        }else{
            $('#company_land').html('');
        }
    });

    function get_company_land(company_id){
        var selected_company_land_id = [];
        var selected_land = {!!json_encode(@$post->user_land) !!};
        var success = false;

        if(selected_land != null){
            var selected_company_land_id = selected_land.map( (item) => item.company_land_id);
        }

        let land = '';
        $.ajax({
            url: "{{ route('ajax_get_company_land', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id
            },
            success: function(e) {
                land += '<div class="row">';
                    land += '<div class="col-sm-6">';
                        land += '<div class="form-group">';
                            land += '<label for="user_land">Land</label><br>';
                            land += '<div class="custom-control custom-checkbox col-sm-6">';
                                land += '<input type="checkbox" id="check_all" class="form-check-input"/>';
                                land += '<label for="check_all">Select All</label>';
                            land += '</div>';
                            land += '<div class="row col-sm-12">';

                                e.forEach(element => {

                                    const isInArray = selected_company_land_id.includes(element.company_land_id);
                                    land += '<div class="custom-control custom-checkbox col-sm-6">';
                                        land += ' <input type="checkbox" id="company_land_id_'+ element.company_land_id +'" name="company_land_id[]" value="'+ element.company_land_id +'" class= "form-check-input check_company_land_id"';
                                            if(isInArray){
                                                land += ' checked ';
                                            }
                                            land += '/>';
                                        land += '<label for="company_land_id_'+ element.company_land_id +'">'+ element.company_land_name +'</label>';
                                    land += '</div>';
                                });
                            land += '</div>';
                        land += '</div>';
                    land += '</div>';
                land += '</div>';

                $('#company_land').html(land);
                var select_all_company_land = document.getElementById("check_all"); //select all checkbox
                var checkboxes_company_land = document.getElementsByName("company_land_id[]"); //checkbox items


                //select all checkboxes_company_land
                select_all_company_land.addEventListener("change", function(e) {

                    for (i = 0; i < checkboxes_company_land.length; i++) {
                        checkboxes_company_land[i].checked = select_all_company_land.checked;
                    }
                });

                for (var i = 0; i < checkboxes_company_land.length; i++) {
                    checkboxes_company_land[i].addEventListener('change', function(e) { //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if (this.checked == false) {
                            select_all_company_land.checked = false;
                        }
                        //check "select all" if all checkbox items are checked
                        if (document.querySelectorAll('.check_company_land_id:checked').length == checkboxes_company_land.length) {
                            select_all_company_land.checked = true;
                        }
                    });
                }
            }
        });
    }
    $('#company_id').trigger('change');

    $('#user_role_id').on('change', function() {
            let user_role_id = $(this).val();
            if (user_role_id  == 13) {
                $('#worker_id_div').show();
            } else {
                $('#worker_id_div').hide();
            }
        });
    });

    function get_worker_list(company_id) {
        var exist_worker_list = "<?php echo @$worker; ?>";
        // console.log(exist_worker_list);
        let user = '<option value="">Please Select Worker ID</option>';

        $('#worker_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "{{ route('ajax_get_worker_list_sel_by_company_without_user_id', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
            },
            success: function(e) {
                if (e.length > 0) {
                    e.forEach(u => {
                        if(u.id == exist_worker_list){
                            user += '<option value="' + u.id + '" selected>' + u.id +' - ' + u.name +'</option>';
                            $('#worker_id_existing').val(u.id );
                        }else{
                            user += '<option value="' + u.id + '">' + u.id +' - ' + u.name +'</option>';
                        }
                    });
                    $('#worker_id').html(user);
                } else {
                    $('#worker_id').html('<option value="">Worker ID Is Not Available</option>');
                }
            }
        });
    }
</script>
@endsection
