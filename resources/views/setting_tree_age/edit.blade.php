@extends('layouts.master')

@section('title') {{ $title }} Setting Tree Age @endsection



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
                <h4 class="mb-0 font-size-18">{{ $title }} Setting Tree Age</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Tree Age</a>
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
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Tree Details</h4>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="age_lower"><span style="font-size: 50x;"><b>Age</b></span> <span style="font-size: 10x;">(lower)</span></label>
                                    <input type="text" name="setting_tree_age_lower" id="setting_tree_age_lower" class="form-control" value="{{@$post->setting_tree_age_lower}}" required>
                                </div>
                            </div>
                            <div style="float:left;text-align: center; padding:30px">
                            _
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="age_upper"><span style="font-size: 50x;"><b>Age</b></span> <span style="font-size: 10x;">(upper)</span></label>
                                    <input type="text" name="setting_tree_age_upper" id="setting_tree_age_upper" class="form-control" value="{{@$post->setting_tree_age_upper}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_mobile_no"><span style="font-size: 50x;"><b>Circumference</b></span> <span style="font-size: 10x;">(lower)</span></label>
                                    <input type="text" name="setting_tree_age_lower_circumference" id="setting_tree_age_lower_circumference" class="form-control" value="{{@$post->setting_tree_age_lower_circumference}}" required>
                                </div>
                            </div>
                            <div style="float:left;text-align: center; padding:30px">
                                _
                                </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_phone_no"><span style="font-size: 50x;"><b>Circumference</b></span> <span style="font-size: 10x;">(upper)</span></label>
                                    <input type="text" name="setting_tree_age_upper_circumference" id="setting_tree_age_upper_circumference" class="form-control" value="{{@$post->setting_tree_age_upper_circumference}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                {{-- <a href="{{route('setting_tree_age_listing', ['tenant' => tenant('id')])}}" class="btn btn-secondary" >Cancel</a> --}}
                                <a href="" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
