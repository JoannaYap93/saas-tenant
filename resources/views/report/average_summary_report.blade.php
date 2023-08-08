@extends('layouts.master')
@section('title')
    Average Summary Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        table {
            text-align: center;
        }

        .bg-grey {
            background: #e4e4e4;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Average Summary Report</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Average Summary Report</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="date_from" placeholder="Start Date"
                                                    value="{{ @$search['date_from'] }}" id="start" autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="date_to" placeholder="End Date"
                                                    value="{{ @$search['date_to'] }}" id="end" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Land</label>
                                            <select name="company_land_id" id="company_land_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Product Category</label>
                                            {!! Form::select('product_category_id', $product_category_sel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="show_product">
                                        <div class="form-group">
                                            <label for="">Product</label>
                                            <select name="product_id" id="product_id" class="form-control">
                                                @if (@$search['product_id'])
                                                    <option></option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="show_size">
                                        <div class="form-group">
                                            <label for="">Grade</label>
                                            <select name="product_size_id" id="product_size_id" class="form-control">
                                                @if (@$search['product_size_id'])
                                                <option></option>
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                                    <div class="form-group col-sm-12">
                                        <label for="user_land">Customer:</label><br>
                                        <div class="row col-sm-12" id="customer_ids">
                                            @foreach ($customer_list as $id => $customer)
                                                <div class="custom-control custom-checkbox col-sm-3">
                                                    <input type="checkbox" id="customer_{{ $id }}"
                                                        name="customer_ids[]" value="{{ $id }}"
                                                        class= "form-check-input customer_ids"
                                                        @if(@$search['customer_ids'])
                                                            @foreach(@$search['customer_ids'] as $key => $selected_customer)
                                                                {{ $selected_customer == $id ? 'checked' : '' }}
                                                            @endforeach
                                                        @endif
                                                    />
                                                    <label>{{ $customer }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-left mb-3">
                                    <a href="#collapse" class="text-center manage-show-hide text-dark collapsed mb-2" data-toggle="collapse" aria-expanded="true" aria-controls="collapse" style="vertical-align: middle;">
                                        <span class="font-weight-bold ">
                                            <span class="text-show-hide">Multiple Customer Selection</span>
                                            <i class="bx bxs-down-arrow rotate-icon"></i>
                                        </span>
                                    </a>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($records))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-AverageSummaryReport :records="$records" :search="$search" :component=true/>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        let land_arr = [];
        let lid = '{{ @$search['company_land_id'] }}' ?? null;
        let pid = '{{ @$search['product_id'] }}' ?? null;
        let sid = '{{ @$search['product_size_id'] }}' ?? null;
        var element = document.getElementById("collapse");

        @if(@$search['customer_ids'])
            let c_ids = <?php echo json_encode($search['customer_ids']);?>;
            $('.manage-show-hide').trigger('click');
        @endif

        $(document).ready(function(e) {
            get_land_product_size();
            get_product_by_category_land($('#product_category_id').val(),$('#company_land_id').val());
            get_size_by_product(sid);

            $('#datepicker6').datepicker({
                orientation: "bottom left",
            });

        });

        $('#company_id').on('change', function() {
            get_land_product_size();
            get_customer_list_by_company_id($(this).val());
        });

        $(document).on('change', '#company_land_id', function() {
            let land_id = $(this).val();
            let product_category_id = $('#product_category_id').val();
            get_product_by_category_land(product_category_id,land_id);
        });

        $(document).on('change', '#product_category_id', function() {
            let product_category_id = $(this).val();
            let land_id = $('#company_land_id').val();
            get_product_by_category_land(product_category_id, land_id);
        });

        $(document).on('change', '#product_id', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        $('.manage-show-hide').on('click', function(){
            let id = $(this).attr('aria-controls')

            console.log(id)

            if($('#' + id).is(':visible')){
                console.log('test')
                $('.customer_ids').prop('checked', false);
            }
        });

        function get_land_product_size() {
            let id = $('#company_id').val();

            @if (auth()->user()->user_type_id != 1)
                id = '{{ auth()->user()->company_id }}';
            @endif

            let land_sel = '<option value="">Please Select Land</option>';

            $('#company_land_id').html('<option value="">Loading...</option>');


            var request = $.ajax({
                url: "{{ route('ajax_get_land_product_size') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(function(land) {
                            land_arr.push(land);
                            if (lid == land.company_land_id) {
                                land_sel += '<option selected value="' + land.company_land_id + '">' +
                                    land.company_land_name + '</option>';
                            } else {
                                land_sel += '<option value="' + land.company_land_id + '">' + land
                                    .company_land_name + '</option>';
                            }
                        });
                    }
                    $('#company_land_id').html(land_sel);
                }
            });
        }

        function get_product_by_category_land(product_category_id, land_id){
            let sel_product_id = '{{ @$search['product_id'] }}' ?? null;
            let product_sel = '<option value="">Please Select Product</option>';

            $('#product_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_get_product_by_product_category_id_land_id') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_category_id: product_category_id,
                    land_id : land_id
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (sel_product_id != null && element.id == sel_product_id) {
                                product_sel += '<option value="' + element.id + '" selected>' + element.value + '</option>';
                            } else {
                                product_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });
                    }
                    $('#product_id').html(product_sel);
                }
            });
        }

        function get_size_by_product(product_id){
            let sel_size_id = '{{ @$search['product_size_id'] }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#product_size_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id') }}",
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
                    $('#product_size_id').html(option);
                }
            });
        }

        function get_customer_list_by_company_id(company_id){
            $('#customer_ids').html('Loading...');
            var customer_sel = '';

            $.ajax({
                url: "{{ route('ajax_get_customer_list_by_company_id') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id
                },
                success: function(e) {
                    e.data.forEach((element) => {
                        customer_sel += '<div class="custom-control custom-checkbox  col-sm-3">'+
                            '<input type="checkbox" id="customer_{{ $id }}"' +
                                'name="customer_ids[]" value="'+element.customer_id+'"' +
                                'class= "form-check-input customer_ids"' +
                            '/>' +
                            '<label>'+element.customer_company_name+'-'+element.customer_name+'</label>' +
                        '</div>';
                    });
                    $('#customer_ids').html(customer_sel);
                }
            });
        }

    </script>
@endsection
