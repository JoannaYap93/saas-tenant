@extends('layouts.master')

@section('title')
    {{ $title }} Setting Raw Material
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Setting Raw Material</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Raw Material</a>
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
                        <h4 class="card-title mb-4">Setting Raw Material Details</h4>
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Name(EN)</span></label>
                                            <input type="text" name="raw_material_name_en" id=""
                                                class="form-control" value="{{json_decode(@$post->raw_material_name)->en ?? ''}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="raw_material_status">Status</label>
                                            {!! Form::select('raw_material_status', $raw_material_status_sel, @$post->raw_material_status, [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="raw_material_category_id">Category</label>
                                            {!! Form::select('raw_material_category_id', $raw_material_category_sel, @$post->raw_material_category_id, [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="raw_material_quantity_unit">Quantity</label>
                                            {!! Form::select(
                                                'raw_material_quantity_unit',
                                                $raw_material_quantity_unit_sel,
                                                @$post->raw_material_quantity_unit,
                                                ['class' => 'form-control'],
                                            ) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="raw_material_value_unit">Value</label>
                                            {!! Form::select('raw_material_value_unit', $raw_material_value_unit_sel, @$post->raw_material_value_unit, [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Name(CN)</span></label>
                                                <input type="text" name="raw_material_name_cn" id="" class="form-control"
                                                value="{{ json_decode(@$post->raw_material_name)->cn ?? ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group" id="company_cb">
                                        <label for="company">Company</label><br>
                                        <div class="custom-control custom-checkbox col-sm-6">
                                            <input type="checkbox" id="check_all" class="form-check-input" />
                                            <label for="check_all">Select All</label>
                                        </div>
                                        <div class="row col-sm-12">
                                            @foreach ($company_cb as $id => $company)
                                                <div class="custom-control custom-checkbox col-sm-6">
                                                    <input type="checkbox" id="company_cb_{{ $id }}"
                                                        name="company_cb_id[]" value="{{ $id }}"
                                                        class="form-check-input check_company_cb_id"
                                                        @if (@$post->raw_material_company) @foreach (@$post->raw_material_company as $key => $raw_material_company)
                                                            {{ $raw_material_company->company_id == $id ? 'checked' : '' }}
                                                            @endforeach @endif />
                                                    <label for="company_cb_{{ $id }}">{{ $company }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                        <a href="{{ route('setting_raw_material_listing', ['tenant' => tenant('id')]) }}"
                                            class="btn btn-secondary">Cancel</a>
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
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        $('#check_all').on('click', function(event) {
            if (this.checked) {
                $('.check_company_cb_id').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check_company_cb_id').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
@endsection
