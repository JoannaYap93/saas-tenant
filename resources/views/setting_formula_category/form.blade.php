@extends('layouts.master')

@section('title')
    {{ $title }} Setting Formula Category
@endsection

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Setting Formula Category</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Formula Category</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if (Session::has('failed_msg'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('failed_msg') }}
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
        <div class="col-6">
            <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Setting Formula Category Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Name (EN)<span class="text-danger">*</span></label>
                                            <input type="text" name="setting_formula_category_name_en" id="setting_formula_category_name" class="form-control" value="{{ @$post->setting_formula_category_name_en }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Name (CN)<span class="text-danger">*</span></label>
                                            <input type="text" name="setting_formula_category_name_cn" id="setting_formula_category_name" class="form-control" value="{{ @$post->setting_formula_category_name_cn }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="setting_expense_category_name">Default Budget:</label>
                                            <input type="number" class="form-control" name="setting_formula_category_budget" value="{{ @$post->setting_formula_category_budget }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customSwitches">Budget Limited: </label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches" name="is_budget_limited" value="1" @if (@$post->is_budget_limited == 1) checked @endif>
                                            <label for="customSwitches" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                        <a href="{{ route('setting_formula_category_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection
