@extends('layouts.master')

@section('title')
    {{ $title }} Price Information
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}">
        <style>
            .select2-container--open {
                z-index: 0;
            }
        </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Price Information</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Price Information</a>
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
                        <h4 class="card-title mb-4">Price Information Details</h4>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Date<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input name="search_date" class="form-control input-mask"
                                            id="datepicker" value="{{ @$post->search_date ?? @$search_date }}"
                                            data-provide="datepicker" data-date-format="yyyy-mm-dd"
                                            data-date-autoclose="true" data-inputmask="'alias': 'datetime'"
                                            data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                                            autocomplete="off">
                                        <span class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-calendar"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Farm<span class="text-danger">*</span></label>
                                    {!! Form::select('company_farm_id', $company_farm_sel, @$company_farm_id, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>
                        </div>
                        @php
                            $select_product_id = @$post->product_id ?? @$product_id;
                            $select_size_id = @$post->setting_product_size_id ?? @$size_id;
                        @endphp
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Product</label>
                                    <select name="product_id" class="form-control select2 product_id">
                                        @foreach($product_sel as $id => $product_name)
                                            <option value="{{$id}}" @if($id == @$select_product_id) selected @endif>
                                                {{$product_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Grade</label>
                                    <select name="setting_product_size_id" id="size_id" class="form-control select2">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Price<span class="text-danger">*</span></label>
                                    <input name="product_info_price" class="form-control" type="number" required
                                        placeholder="RM 0.00" step="0.01" value="{{@$post->product_info_price}}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('dashboard', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <script>

        let pid = '{{ @$product_id }}' ?? null;
        let sid = '{{ @$select_size_id }}' ?? null;

        $(document).ready(function(e) {
            get_size_by_product($('.product_id').val());
        });
        $(document).on('change', '.product_id', function() {
            console.log('test');
            let product_id = $(this).val();
            console.log(product_id);
            get_size_by_product(product_id);
        });

        function get_size_by_product(product_id){
            let sel_size_id = '{{ @$select_size_id }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#size_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id
                },
                success: function(e) {
                    let option = '<option value="">Please Select Grade</option>';

                    if(e.data.length > 0){
                        e.data.forEach(function(p) {
                            if (sel_size_id != null && p.id == sel_size_id) {
                                option += '<option value="' + p.id + '" selected>' + p.value + '</option>';

                            }else{
                                option += '<option value="' + p.id + '">' + p.value + '</option>';
                            }
                        });
                    }
                    $('#size_id').html(option);
                }
            });
        }
    </script>
@endsection
