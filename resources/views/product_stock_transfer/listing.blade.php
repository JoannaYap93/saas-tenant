
@extends('layouts.master')

@section('title')
    Stock Transfer Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    <span class="mr-2">Stock Transfer Listing</span>
                    @can('product_stock_manage')
                        @if (count($product_id_sel) > 1)
                            <a href="javascript: void(0);" id="add_transfer" data-toggle="modal" data-target='#change_stock'
                                class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                    class="fas fa-plus"></i> Transfer</a>
                        @else
                            <span class="text-danger">Please add a product before continue. </span>
                        @endif
                    @endcan
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Stock Transfer</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
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
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="company_id">Company</label>
                                                {!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Warehouse</label>
                                            {!! Form::select('warehouse_id', $setting_warehouse_sel, @$search['warehouse_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    @if (count($product_id_sel) > 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationCustom03">Product</label>
                                                {!! Form::select('product_id', $product_id_sel, @$search['product_id'], ['class' => 'select2 form-control', 'id' => 'product']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3" id="show_size">
                                        <div class="form-group">
                                            <label for="validationCustom03">Product Grade</label>
                                            <select name="product_size_id" id="size_id" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date: </label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="stock_from" placeholder="Start Date"
                                                    value="{{ @$search['stock_from'] }}" id="start" autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="stock_to" placeholder="End Date"
                                                    value="{{ @$search['stock_to'] }}" id="end" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary  waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger  waves-effect waves-light mr-2"
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 50px; text-align: center;">#</th>
                                    <th>Transfer Remark & Description</th>
                                    <th style="text-align: center;">Warehouse</th>
                                    <th style="text-align: center;">Product</th>
                                    <th style="text-align: center;">Grade</th>
                                    <th style="text-align: center;">Quantity Before</th>
                                    <th style="text-align: center;">Quantity After</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i = $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                    @foreach ($records as $stock)
                                        <tr>
                                            <td align="center">
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <b>{{ $stock->product_stock_transfer_remark }}</b><br>
                                                @if (substr($stock->product_stock_transfer_description, 0, 1) == '#')
                                                    <a href="{{ route('find_do_no', ['tenant' => tenant('id'), 'id' => substr($stock->product_stock_transfer_description, 1)]) }}"
                                                        target="_blank">
                                                        {{ $stock->product_stock_transfer_description }} <br>
                                                    </a>
                                                @else
                                                    {{ $stock->product_stock_transfer_description }} <br>
                                                @endif
                                                Created On:
                                                <b>{{ date_format($stock->product_stock_transfer_created, 'Y-m-d h:i A') }}</b>
                                            </td>
                                            <td align="center">
                                                {{ $stock->product_stock_warehouse->setting_warehouse->warehouse_name }}
                                            </td>
                                            <td align="center">
                                                @if ($stock->product)
                                                    {{ $stock->product->product_name }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                {{ $stock->setting_size->setting_product_size_name }}
                                            </td>
                                            <td align="center">
                                                {{ $stock->product_stock_transfer_qty_before }}
                                            </td>
                                            <td align="center">
                                                {{ $stock->product_stock_transfer_qty_after }}
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Records!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $records->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->
    <!-- Modal -->
    <div class="modal fade" id="change_stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('product_stock_transfer_add', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4 class="card-title mb-4">Stock Transfer Details</h4>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Stock Transfer Remarks</label>
                                {!! Form::select('product_stock_transfer_remark', $product_stk_transfer_remark_sel, @$product_stock_transfer_remark, ['class' => ' form-control']) !!}

                            </div>
                            <div class="form-group">
                                <label for="">Stock Transfer Description</label>
                                <textarea name="product_stock_transfer_description" id="summernote"
                                    class="form-control"></textarea>
                                <span id="total-caracteres"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Company Land</label>
                                    {!! Form::select('company_land_id', $company_land_sel, null , ['class' => 'select1 form-control']) !!}
                                <input hidden id="company_land_id_input">
                            </div>
                            <div class="form-group">
                                <label for="">Product</label>
                                <select id="show_product" class="select2 form-control">
                                </select>
                                <input hidden id="product_id" name="product_id">
                            </div>
                            <div class="form-group">
                                <label for="">Product Grade</label>
                                <select id="show_product_size" class="form-control">
                                    <!-- <option id="insert_after_selection" value="">Please Select Size</option> -->
                                </select>
                                <input hidden id="size_id_input" name="setting_product_size_id">
                            </div>
                            <div class="form-group">
                                <label for="">Warehouse</label>
                                {!! Form::select('warehouse_id', $setting_warehouse_sel, @$stock->setting_product_size_name, ['class' => 'select3 form-control', 'id' => 'warehouse_sel']) !!}
                            </div>
                            <div class="form-group">
                                <label for="">Current Quantity</label>
                                <!-- <span id="show_current_qty"></span> -->
                                <input type="number" readonly name="product_stock_transfer_qty_before"
                                    class="form-control" id="show_current_qty" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Insert Quantity</label>
                                <span id="invalid_insert_alert"
                                    style="font-style: Italic; color: red; font-size: 12px; font-Weight: 200;"> The total
                                    stock should not be less than 0*</span>
                                <input type="number" name="product_stock_transfer_qty" id="inserted_qty"
                                    class="form-control"><br>
                                <input name="product_stock_transfer_qty_after" id="qty_after" hidden>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
                                <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(e) {
            $('#show_product').attr('disabled', true);
            $('#warehouse_sel').attr('disabled', true);
            $('#show_product_size').attr('disabled', true);
            $('#invalid_insert_alert').hide();
            $('.select3').on('change', function() {
                var value = $(this).val();
                var size_id = $('#show_product_size').val();
                var product_id = $('#show_product').val();
                $('#size_id_input').val(size_id);
                $('#product_id').val(product_id);
                // console.log($('#product_id').val());
                // console.log($('#show_product_size').val());
                $.ajax({
                    url: "{{ route('ajax_get_current_warehouse_qty', ['tenant' => tenant('id')]) }}",
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        warehouse_id: $(this).val(),
                        product_id: $('#product_id').val(),
                        setting_product_size_id: $('#show_product_size').val(),
                    },
                    success: function(e) {
                        if (e.status == true) {
                            var show = '';
                            $('#show_current_qty').val(e.data[0].value);
                            // console.log(e.data[0].value);
                        } else {
                            var value = 0;
                            $('#show_current_qty').val(value);
                        }
                    }
                })
            })
            $('.select2').on('change', function() {
                var value = $(this).val();
                var show = '<option value=0> Please Select Grade</option>';
                $('#warehouse_sel').prop('selectedIndex', 0);
                $('#show_current_qty').val('');
                // console.log(value);
                if (!value) {
                    $('#warehouse_sel').attr('disabled', true);
                    // $('#warehouse_sel').prop('selectedIndex', 0);
                    $('#show_product_size').attr('disabled', true);
                    // $('#show_current_qty').val('');
                } else {
                    $('#show_product_size').attr('disabled', false);
                }
                $('#product_id').val(value);
                // console.log(value);
                $.ajax({
                    url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: $(this).val(),
                    },
                    success: function(e) {

                        // var show = '<option value=> TEST </option>';

                        e.data.forEach((element, ix) => {
                            // show += '<option value=></option>'
                            show += '<option value=' + element.id + '>' + element
                                .label + '</option>'

                        });
                        // $(show).insertAfter('#insert_after_selection
                        $('#show_product_size').html(show);
                    }
                })
            })

            $('.select1').on('change', function(){
                var value = $(this).val();
                var show = '<option value=0> Please Select Product</option>';

                if(value){
                    $('#show_product').attr('disabled', false);
                }else{
                    $('#show_product').attr('disabled', true);
                }

                $('#company_land_id_input').val(value);

                $.ajax({
                    url: "{{ route('ajax_get_product_sel_by_company_land_id', ['tenant' => tenant('id')]) }}",
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        company_land_id: $(this).val(),
                    },
                    success: function(e) {
                        // console.log(e);
                        e.data.forEach((element) => {
                            show += '<option value=' + element.product_id + '>' + element.product_label + '</option>'
                        });
                        $('#show_product').html(show);
                    }
                })
            })

            $('#show_product_size').on('change', function() {
                $('#warehouse_sel').prop('selectedIndex', 0);
                $('#show_current_qty').val('');
                if ($(this).val() != 0) {
                    $('#warehouse_sel').attr('disabled', false);
                } else {
                    $('#warehouse_sel').attr('disabled', true);
                    // $('#warehouse_sel').prop('selectedIndex', 0);
                    // $('#show_current_qty').val('');
                }
                // console.log($(this).val());
            })
            $('#inserted_qty').on('keyup', function() {
                var sum = Number($('#show_current_qty').val()) + Number($('#inserted_qty').val());

                if (sum < 0) {
                    $('#submit').prop('disabled', true);
                    $('#invalid_insert_alert').show();
                } else if (sum >= 0) {
                    $('#submit').prop('disabled', false);
                    $('#invalid_insert_alert').hide();
                }
                // console.log(sum);
                $('#qty_after').val(sum);
            })

            $('#add_transfer').on('click', function() {

                $(".modal-body #delete_id").val();
            });

            $('.company_id').select2({
                minimumResultsForSearch: -1
            });

            $('.company_id').select2({
                minimumResultsForSearch: -1
            });

            $('#show_size').hide();
            let option = '<option value="">Please Select Grade</option>';

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: $('#product').val()
                },
                success: function(e) {
                    $('#show_size').show();
                    let id = "{{ @$search['product_size_id'] }}";

                    e.data.forEach(function(p) {
                        if (id == p.id) {
                            option += '<option selected value="' + p.id + '">' + p.value +
                                '</option>';
                        } else {
                            option += '<option value="' + p.id + '">' + p.value + '</option>';
                        }
                    });

                    $('#size_id').html(option);
                }
            });
        });

        $(document).on('change', '#product', function() {

            $('#size_id').html('');
            let product = $(this).val();

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product
                },
                success: function(e) {
                    $('#show_size').show();
                    let option = '<option value="">Please Select Grade</option>';
                    e.data.forEach(function(p) {
                        option += '<option value="' + p.id + '">' + p.value + '</option>';
                    });

                    $('#size_id').html(option);
                }
            });
        });
    </script>
@endsection
