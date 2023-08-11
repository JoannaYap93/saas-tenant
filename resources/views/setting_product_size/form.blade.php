@extends('layouts.master')

@section('title') {{ $title }} Product Grade @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Product Grade</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product Grade</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
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
                        <h4 class="card-title mb-4">Product Grade Details</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="setting_product_size_name">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="setting_product_size_name" class="form-control" value="{{ @$post->setting_product_size_name }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('setting_product_size_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>
@endsection
@section('script')

@endsection
