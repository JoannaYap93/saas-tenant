@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <style>
        .table-responsive {
            height: 500px !important;
            overflow: hidden !important;
            overflow: scroll !important;
        }
        .editable-buttons{
            display: none !important;
        }
        .editable-click{
            border-bottom: none !important;
        }
        input[type=number]{
            width: 120px !important;
            padding-right: 0.75rem !important;
        }
        .editable-clear-x{
            display: none !important;
        }
        .editable-input{
            margin-right: 0px !important;
        }
        .editable-inline .editableform-loading{
            background-position: center !important;
        }
        td{
            padding: 1rem !important;
        }
        th {
            position: -webkit-sticky !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 2 !important;
        }

        th:nth-child(n) {
            position: -webkit-sticky !important;
            position: sticky !important;
            z-index: 1 !important;
        }
        .update{
            cursor: pointer;
        }
        table {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }}</h4>
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
                        <h4 class="card-title mb-4">Tree Age Details</h4>
                        <div class="row">
                            <div class="col">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Product: </label>
                                            {!! Form::select('product_id', $product_sel, @$search['product_id'], ['class' => 'form-control', 'id' => 'product_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Age: </label>
                                            {!! Form::select('age_id', $age_sel, @$search['age_id'], ['class' => 'form-control', 'id' => 'age_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="collapse-product" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                                        <div class="form-group col-sm-12">
                                            <label for="user_land">Products:</label><br>
                                            <div class="row col-sm-12" id="product_cb">
                                                @foreach ($productCb as $id => $product)
                                                    <div class="custom-control custom-checkbox col-sm-3">
                                                        <input type="checkbox" id="product_cb_{{ $id }}"
                                                            name="product_cb_id[]" value="{{ $id }}"
                                                            class= "form-check-input check_product_cb_id"
                                                            @if(@$search['product_cb_id'])
                                                                @foreach(@$search['product_cb_id'] as $key => $selected_product)
                                                                    {{ $selected_product == $id ? 'checked' : '' }}
                                                                @endforeach
                                                            @endif
                                                        />
                                                        <label for="product_cb_{{ $id }}">{{ $product }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-left mb-3">
                                        <a href="#collapse-product" class="text-center manage-show-hide text-dark collapsed mb-2 collapse-product" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-product" style="vertical-align: middle;">
                                            <span class="font-weight-bold ">
                                                <span class="text-show-hide">Multiple Product Selection</span>
                                                <i class="bx bxs-down-arrow rotate-icon"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="collapse-age" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                                        <div class="form-group col-sm-12">
                                            <label for="user_land">Age:</label><br>
                                            <div class="row col-sm-12" id="product_cb">
                                                @foreach ($ageCb as $id => $age)
                                                    <div class="custom-control custom-checkbox col-sm-3">
                                                        <input type="checkbox" id="age_cb_{{ $age->setting_tree_age_id }}"
                                                            name="age_cb_id[]" value="{{ $age->setting_tree_age_id }}"
                                                            class= "form-check-input check_age_cb_id"
                                                            @if(@$search['age_cb_id'])
                                                                @foreach(@$search['age_cb_id'] as $key => $selected_age)
                                                                    {{ $selected_age == $age->setting_tree_age_id ? 'checked' : '' }}
                                                                @endforeach
                                                            @endif
                                                        />
                                                        <label for="age_cb_{{ $age->setting_tree_age_id }}">{{ $age->setting_tree_age }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-left mb-3">
                                        <a href="#collapse-age" class="text-center manage-show-hide text-dark collapsed mb-2 collapse-age" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-age" style="vertical-align: middle;">
                                            <span class="font-weight-bold ">
                                                <span class="text-show-hide">Multiple Age Selection</span>
                                                <i class="bx bxs-down-arrow rotate-icon"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Setting Tree Age Pointer Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" id="setting-tree-age-pointer-table">
                                        <thead>
                                            @php
                                                $col_count=0;
                                            @endphp
                                            <tr style="background-color: #e4e4e4;">
                                                <th style="min-width: 100px; background-color: #d8d9df;" rowspan="2">Age</th>
                                                <th colspan="{{count($products)}}" style="text-align: left; background-color: #ffffff;padding-left:50px;">Product</th>
                                            </tr>
                                            <tr>
                                                @foreach ($products as $product)

                                                            <th style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #d8d9df;' }}">
                                                                {{$product->product_name}}
                                                            </th>
                                                            <input type="hidden" name="products_id[]" id="products_id" class="form_control" value="{{ $product->product_id }}">
                                                            @php
                                                                $col_count++;
                                                            @endphp
  
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($records->isNotEmpty())
                                                @foreach ($records as $record)
                                                    @php
                                                        $col_count=0;
                                                    @endphp
                                                    <tr>
                                                        @php
                                                            $age_counter = 0;
                                                        @endphp                                          
                                                        {{-- <input type="hidden" name="tree_age_id[]" id="tree_age_id" class="form_control" value="{{ $record->setting_tree_age_id }}"> --}}
                                                        @foreach ($products as $product)
                                                            @foreach ($record->setting_tree_age_pointer as $setting_tree_age_pointer)
                                                                @if (@$setting_tree_age_pointer->product_id == $product->product_id)
                                                                    @if ($age_counter == 0)
                                                                        <td style="background-color: #e4e4e4;">{{ @$record->setting_tree_age }}</td>
                                                                        @php
                                                                            $age_counter++;
                                                                        @endphp
                                                                    @endif
                                                                    <td style="{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                                                        {{-- <input type="number" step="0.01" name="setting_tree_age_pointer_value[{{$record->setting_tree_age_id}}][]" id="setting_tree_age_pointer_value" class="form-control" value="{{ @$setting_tree_age_pointer->setting_tree_age_pointer_value }}" required> --}}
                                                                        <a class="update" data-pk="{{ @$setting_tree_age_pointer->setting_tree_age_pointer_id }}">{{ @$setting_tree_age_pointer->setting_tree_age_pointer_value }}</a>
                                                                    </td>
                                                                    @php
                                                                        $col_count++;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row" style="margin-top: 20px">
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1" name="submit" value="submit">Submit</button>
                                <a href="{{ route('setting_tree_age_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        $(document).ready(function(e) {
            $.fn.editable.defaults.mode = 'inline';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('.update').editable({
                url: "{{ route('setting_tree_age_pointer', ['tenant' => tenant('id')]) }}",
                type: 'number',
                step: '.01',
                pk: 1,
                name: 'pointer_value',
            });

            @if(@$search['product_cb_id'])
                $('.collapse-product').trigger('click');
                $('#product_id').val('').attr('disabled', true);
            @endif

            @if(@$search['age_cb_id'])
                $('.collapse-age').trigger('click');
                $('#age_id').val('').attr('disabled', true);
            @endif

            $('.collapse-product').on('click', function(){
                let id = $(this).attr('aria-controls')

                if($('#' + id).is(':visible')){
                    $('#product_id').val('').attr('disabled', false);
                    $('.check_product_cb_id').prop('checked', false);
                }else{
                    $('#product_id').val('').attr('disabled', true);
                }
            })

            $('.collapse-age').on('click', function(){
                let id = $(this).attr('aria-controls')

                if($('#' + id).is(':visible')){
                    $('#age_id').val('').attr('disabled', false);
                    $('.check_age_cb_id').prop('checked', false);
                }else{
                    $('#age_id').val('').attr('disabled', true);
                }
            })

            $("#setting-tree-age-pointer-table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
        })
    </script>
@endsection
