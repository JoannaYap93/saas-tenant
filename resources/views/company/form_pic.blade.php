@extends('layouts.master')

@section('title')
    Claim PIC
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Claim PIC</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Claim PIC</a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if (\Session::has('failed_msg'))
    <div class="alert alert-danger" role="alert">
        {{ \Session::get('failed_msg') }}
    </div>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('claim_pic', $post->company_id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company Details</h4>
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name<span
                                                class="text-danger">*</span></label>
                                        @if (auth()->user()->user_type_id == 1)
                                            <input name="company_name" type="text" class="form-control"
                                                value="{{ @$post->company_name }}" disabled>
                                        @else
                                            <br> {{ $post->company_name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="company_code">Company Code<span
                                                        class="text-danger">*</span></label>
                                                <span id="show_msg"></span>
                                                @if ($title == 'Edit' || $title == 'Edit Details - ')
                                                    @if (@$post->company_code)
                                                        @if (auth()->user()->user_type_id == 1)
                                                            <input id="randomCode" name="company_code" readonly
                                                                type="text" class="form-control col-6"
                                                                maxlength="5" value="{{ @$post->company_code }}"
                                                                disabled>
                                                        @else
                                                            <br>{{ $post->company_code }}
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (auth()->user()->user_type_id == 1)
                                                        <input id="company_code" name="company_code" type="text"
                                                            class="form-control col-6" maxlength="5"
                                                            value="{{ @$post->company_code }}" disabled>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Phone
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" name="phone" id="" class="form-control"
                                            maxlength="12" value="{{ @$post->company_phone ?? @$post->phone }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Email</span>
                                        </label>
                                        <input type="email" name="email" id="" class="form-control"
                                            value="{{ @$post->company_email ?? @$post->email }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="" class="form-control"
                                            value="{{ @$post->company_address ?? @$post->address }}" disabled>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="">Registration No. <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="reg_no" id="" class="form-control"
                                            value="{{ @$post->company_reg_no ?? @$post->reg_no }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Claim PIC Details</h4>
                    <div class="row mt-3">
                        <div class="col-12 border-bottom">
                            <div class="row">

                                @foreach ($claim_approval_step as $step_key => $step)
                                    <div class="col-10">
                                        <label>{{ $step }}:</label>
                                        <div class="form-group pic_{{ $step_key }}">
                                            @if (isset($post['claim_pic_user'][$step_key]))
                                                @foreach ($post['claim_pic_user'][$step_key] as $claim_step_pic)
                                                    <div class='col-6 d-flex pb-1'>
                                                        {{ $user_fullname_pic }}
                                                        <input type='hidden' name='claim_user_{{ $step_key }}[]' value='{{ $user_fullname_pic }}' class='form-control' />
                                                        <input type='hidden' name='claim_user_id_{{ $step_key }}[]' value='{{ $user_id }}' />
                                                        <span id='remove' class='d-flex align-items-center pl-2' style='cursor: pointer; color: red; font-size: 15px'><i class='fa fa-times'></i></span><br>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if (@$post->company_claim_approval)
                                                @foreach ($post->company_claim_approval as $pic_key => $pic )
                                                    @if($pic->claim_approval_step_id == $step_key)
                                                        <div class='col-6 d-flex pb-1'>
                                                            {{$pic->user->user_fullname}}
                                                            <input type='hidden' name='claim_user_{{$step_key}}[]' value='{{$pic->user->user_fullname}}' class='form-control'/>
                                                            <input type='hidden' name='claim_user_id_{{$step_key}}[]' value='{{$pic->user->user_id}}'/>
                                                            <span id='remove' class='d-flex align-items-center pl-2' style='cursor: pointer; color: red; font-size: 15px'><i class='fa fa-times'></i></span><br>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <input type="hidden" value="{{ $step_key }}" name="step_claim[]" id="step_claim">
                                        </div>
                                    </div>
                                    @if ($step_key == 5)
                                        @break
                                    @endif
                            @endforeach
                            @if ($title == 'Edit')
                                <input type="hidden" id="company_id" value={{ $post->company_id }}>
                            @endif
                        </div>
                    </div>
                    <div class="form-group pt-3" style="display: flex;">
                        <div class="col-8">
                            {!! Form::select('claim_approval_step', $claim_approval_step, @$search['claim'], ['class' => 'form-control select2', 'id' => 'claim_approval_step', 'placeholder' => 'Select Step']) !!}
                        </div>
                        <div class="col-8">
                            <select name="pic_user" id="pic_user" class="form-control pic_user">
                            </select>
                        </div>
                        <div class="col-2"><a href="javascript:void(0)" class="btn btn-success"
                                id='pic_add'>Add</a></div>
                    </div>
                </div>
            </div>
            </div>
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-6">
                            <button type="submit"
                                class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="#" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
<!-- Plugins js -->
<script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>

<script>
    $('.pic_user').select2({
        ajax: {
            minimumResultsForSearch: -1,
            url: "{{ route('ajax_search_user_by_name') }}",
            dataType: 'json',
            data: function(p) {
                let query = {
                    api_token: '{{ csrf_token() }}',
                    term: p.term,
                    company_id: $('#company_id').val(),

                }
                return query;
            },
            processResult: function(data, p) {
                return {
                    results: data.results,
                }
            }
        },
        allowClear: true,
        tags: false,
        placeholder: 'Select Claim PIC '
    });

    $('.pic_user').on('change', function() {
        let company = $('#company_id').val();
        let value = $(this).val();
        $.ajax({
            url: "{{ route('ajax_search_user_by_name') }}",
            method: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                user_name: value,
                company_id: company,
            },
            success: function(e) {
                if (e.data != null) {
                    $(this).val(value);
                } else {
                    $(this).html('');

                }
            }
        })
    });

    $('#pic_add').on('click', function() {
        var claim_step_text = $('#claim_approval_step option:selected').html();
        var claim_step_id = $('#claim_approval_step').val();
        var claim_user = $('#pic_user').val();
        var claim_user_text = $("#pic_user option:selected").html();




        if (claim_step_id && claim_user) {

            var duplicates = false;
            var input = document.querySelectorAll("input[name^='claim_user_id_"+ claim_step_id+"[']")

            for (var i = 0; i < input.length; i++) {
                if(claim_user == input[i].value){
                    duplicates = true;
                }
            }

            if(duplicates == false){
                $('.pic_' + claim_step_id).append(
                "<div class='col-6 d-flex pb-1'>" + claim_user_text +
                "<input type='hidden' name='claim_user_id_" + claim_step_id +
                "[]' value='" + claim_user +
                "'/><span id='remove' class='d-flex align-items-center pl-2' style='cursor: pointer; color: red; font-size: 15px'><i class='fa fa-times'></i></span><br></div>"
                );
            }else{
                alert('PIC already been seleceted.');
            }


        } else {
            alert('Please select Step and PIC first.');
        }
    });

    $(document).on("click", "#remove", function() {
        $(this).parent().remove();
    });
</script>
@endsection
