@extends('layouts.master')

@section('title') {{ $title }} Product Category @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ $title }} Product Category</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Product Category</a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if(\Session::has('failed_msg'))
<div class="alert alert-danger" role="alert">
    {{\Session::get('failed_msg')}}
</div>
@endif
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
                    <h4 class="card-title mb-4">Product Category Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="product_category_name">Product Category Name: <span class="text-danger">*</span></label>
                                <input name="product_category_name" type="text" class="form-control" value="{{ @$post->product_category_name }}">
                            </div>
                            <div class="form-group">
                                <label for="product_category_status">Product Category Status: <span class="text-danger">*</span></label>
                                {!! Form::select('product_category_status', $status, @$post->product_category_status, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label for="product_category_status">Product Category Ranking: <span class="text-danger">*</span></label>
                                <input type="number" name="product_category_ranking" id="" class="form-control" value="{{ @$post->product_category_ranking }}">
                            </div>
                            @if (count($parent_id) > 1)
                                <div class="form-group">
                                    <label for="product_category_status">Product Category Parent</label>
                                    {!! Form::select('product_category_parent', $parent_id, @$post->product_category_parent, ['class' => 'form-control']) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('product_category_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
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
<!-- Plugins js -->
<script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>

@endsection
