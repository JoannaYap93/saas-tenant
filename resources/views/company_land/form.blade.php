@extends('layouts.master')

@section('title') {{ $title }} Land Area  @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
{{-- @php
    dd($post->Land Area _land);
@endphp --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ $title }} Land Area </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Land Area </a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if($errors->any())
@foreach($errors->all() as $error)
<div class="alert alert-danger" role="alert">
    {{ $error }}
</div>
@endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Land Area Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="company_land_name">Land Name<span class="text-danger">*</span></label>
                                <input name="company_land_name" type="text" class="form-control" value="{{ @$post->company_land_name }}">
                            </div>
                            <div class="form-group">
                                <label for="company_land_category">Company Land Category<span class="text-danger">*</span></label>
                                {!! Form::select('company_land_category_id', $company_land_category_sel, (@$post->company_land_category_id ? $post->company_land_category_id : @$company->company_land_category_id), ['class' => 'form-control', 'id' => 'company_land_category_id']) !!}
                            </div>
                            @if (auth()->user()->company_id == 0)
                                <div class="form-group">
                                    <label for="company_land_category">Company<span class="text-danger">*</span></label>
                                    {!! Form::select('company_id', $company_name_sel, (@$post->company_id ? $post->company_id : @$company->company_id), ['class' => 'form-control', 'id' => 'company_id']) !!}
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            {{-- <div class="row"> --}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="company_land_code">Land Code<span class="text-danger">*</span></label>
                                        @if(@$post->company_land_code)
                                        <input name="company_land_code" readonly type="text" class="form-control" maxlength="100" value="{{@$post->company_land_code }}">
                                        @else
                                        <input id="randomCode" name="company_land_code" readonly type="text" class="form-control" maxlength="100" value="{{@$post->company_land_code }}">
                                        @endif
                                    </div>
                                </div>

                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('land_area_listing') }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<!-- Plugins js -->
<script src="{{ URL::asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script>

<script>
    $(document).ready(function(e) {
        //$("#user_role").hide();
        $('#user_type').on('change', function() {
            if (this.value == 1) {
                $("#user_role").show();
            } else {
                $("#user_role").hide();
            }
        });

        let randomCode = (Math.random() + 1).toString(36).substring(7);
        document.getElementById('randomCode').value = randomCode;

        // let randomCode2 = (Math.random() + 1).toString(36).substring(7);
        // document.getElementById('randomCode2').value = randomCode2;


    });
</script>
@endsection
