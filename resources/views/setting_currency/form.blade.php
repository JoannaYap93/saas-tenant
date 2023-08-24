@extends('layouts.master')

@section('title')
    {{ $title }} Setting Currency
@endsection

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
                <h4 class="mb-0 font-size-18"> {{ $title }} Setting Currency</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Currency</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Currency Details</h4>
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="setting_currency_code">Currency Code<span class="text-danger">*</span></label>
                                        <input type="text" name="setting_currency_code" id="setting_currency_code" class="form-control" value="{{ @$post->setting_currency_code}}">
                                        <br>
                                        <label for="setting_currency_name">Currency Name<span class="text-danger">*</span></label>
                                        <input type="text" name="setting_currency_name" id="setting_currency_name" class="form-control" value="{{ @$post->setting_currency_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('setting_currency', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
