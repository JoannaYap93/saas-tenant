@extends('layouts.master')

@section('title')
    {{ $title }} Company Land Tree Action
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Company Land Tree Action</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Land Tree Action</a>
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
                    <h4 class="card-title mb-4">Action Details</h4>
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                            <label for="">Name</span>
                                            </label>
                                            <input type="company_land_tree_action_name" name="company_land_tree_action_name" id="" class="form-control"
                                                value="{{ @$post->company_land_tree_action_name}}">
                                        </div>
                                    </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="customSwitches">Value Required </label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches"
                                                name="is_value_required" value="1"
                                                @if (@$post->is_value_required == 1) checked @endif>
                                            <label for="customSwitches" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                        <a href="{{ route('company_landtree_listing') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
@endsection
