@extends('layouts.master')

@section('title') {{ $title }} Expense Category @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Expense Category</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Expense Category</a>
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
            <div class="col-7">
                <form method="POST" action="{{ $submit }}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Expense Category Details</h4>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="setting_expense_category_name">Expense Category Name(EN)<span class="text-danger">*</span></label>
                                        <input type="text" name="setting_expense_category_name_en" class="form-control" value="{{ @$post->setting_expense_category_name_en }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="setting_expense_category_name">Expense Category Name(CN)<span class="text-danger">*</span></label>
                                        <input type="text" name="setting_expense_category_name_cn" class="form-control" value="{{ @$post->setting_expense_category_name_cn }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="control-label">Expense Category Group<span class="text-danger">*</span></label>
                                        {!! Form::select('setting_expense_category_group', $setting_expense_category_group_sel, @$post->setting_expense_category_group, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="customSwitches">Budget Limited: </label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches" name="is_budget_limited" value="1" @if (@$post->is_budget_limited == 1) checked @endif>
                                            <label for="customSwitches" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-3">
                                    <div class="form-group">
                                        <label for="customSwitches2">Backend Only: </label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches2" name="is_backend_only" value="1" @if (@$post->is_backend_only == 1) checked @endif>
                                            <label for="customSwitches2" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="setting_expense_category_name">Default Budget:</label>
                                        <input type="number" class="form-control" name="setting_expense_category_budget" value="{{ @$post->setting_expense_category_budget }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                    <a href="{{ route('expense_category_listing') }}" class="btn btn-secondary">Cancel</a>
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
