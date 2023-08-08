@extends('layouts.master')

@section('title') {{ $title }} Supplier Delivery Order @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Supplier Delivery Order</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Supplier Delivery Order</a>
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
            <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Supplier Details</h4>
                        <div class="row">
                            <div class="col-6" {{ (auth()->user()->company_id == 0 ? '' : 'hidden') }}>
                                <div class="form-group">
                                    <label for="company_id">Company</label>
                                    {!! Form::select('company_id', $company_sel, @$supplier_do->company_id, ['class' => 'form-control', 'id' => 'company_id']) !!}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_id">Supplier Name</label>
                                    <select name="supplier_id" class="form-control" id="supplier_id">
                                        <option value="">Please Select Supplier</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_delivery_order_no">Supplier Delivery Order No<span class="text-danger">*</span></label>
                                    <input type="text" name="supplier_delivery_order_no" id="supplier_delivery_order_no" value="{{ @$supplier_do->supplier_delivery_order_no }}" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="supplier_delivery_order_date">Stock In Date: </label>
                                    @php $current_date = date('Y-m-d'); @endphp
                                        <div class="input-group">
                                            <input name="supplier_delivery_order_date" class="form-control input-mask" id="datepicker" value="{{ @$supplier_do->supplier_delivery_order_date ? @$supplier_do->supplier_delivery_order_date : $current_date }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                            <span class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-calendar"></i>
                                                </span>
                                            </span>
                                        </div>
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title d-flex align-items-center justify-content-between mb-4">Supplier Delivery Order Item Details
                        <button class="btn btn-outline-primary btn-sm add_raw_material" data-target="#add_raw_material_modal"
                                data-toggle="modal" id="add_raw_material" type="button">Add
                                Raw Material</button>
                        </h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <th>Raw Material Name</th>
                                            <th>Value</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Discount (RM)</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @if($title == 'Edit' && !is_null(@$supplier_do->supplier_delivery_order_item))
                                                @foreach(@$supplier_do->supplier_delivery_order_item as $key => $supplier_delivery_order_item)
                                                    @php
                                                        $supplier_delivery_order_item_amount = ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_price_per_qty) -
                                                                ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_disc_per_qty)
                                                    @endphp
                                                    <tr class="raw_materials" id="raw_material_{{ $supplier_delivery_order_item->raw_material_id }}_{{ $key }}">
                                                        <td>
                                                            <b>{{ json_decode($supplier_delivery_order_item->raw_material->raw_material_name)->en }}</b>-
                                                            {{ json_decode($supplier_delivery_order_item->raw_material->setting_raw_category->raw_material_category_name)->en }}
                                                            <input type="hidden" name="raw_material_id[]" value="{{ $supplier_delivery_order_item->raw_material_id }}"/>
                                                            <input type='hidden' name='raw_material_name[]' value="{{ json_decode($supplier_delivery_order_item->raw_material->raw_material_name)->en }}"/>
                                                            <input type='hidden' name='raw_material_category[]' value="{{ json_decode($supplier_delivery_order_item->raw_material->setting_raw_category->raw_material_category_name)->en }}"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0.000" step="0.001" name="supplier_delivery_order_item_value_per_qty[]" value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty }}" id="raw_material_value_{{ $supplier_delivery_order_item->raw_material_id }}" class="raw_material_value form-control mr-1" @if($supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining != ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty)) readonly @endif/>
                                                            <span>{{ $supplier_delivery_order_item->raw_material->raw_material_value_unit }}</span>
                                                            <input type="hidden" name="raw_material_value_unit[]" value="{{ $supplier_delivery_order_item->raw_material->raw_material_value_unit }}"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" step="1" name="supplier_delivery_order_item_qty[]" value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_qty }}" id="raw_material_qty_{{ $supplier_delivery_order_item->raw_material_id }}" class="raw_material_qty form-control mr-1" @if($supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining != ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty)) readonly @endif/>
                                                            <span>{{ $supplier_delivery_order_item->raw_material->raw_material_quantity_unit }}</span>
                                                            <input type="hidden" name="raw_material_quantity_unit[]" value="{{ $supplier_delivery_order_item->raw_material->raw_material_quantity_unit }}"/>
                                                        </td>
                                                        <td>
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_price_per_qty[]' value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_price_per_qty }}" id='raw_material_unit_price_{{ $supplier_delivery_order_item->raw_material_id }}' class='raw_material_unit_price form-control mr-1' @if($supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining != ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty)) readonly @endif/>
                                                        </td>
                                                        <td>
                                                            {{-- <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_disc_per_qty[]' value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_disc_per_qty }}" id='raw_material_unit_disc_{{ $supplier_delivery_order_item->raw_material_id }}' class='raw_material_unit_disc form-control mr-1'/> --}}
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_disc[]' value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_disc }}" id='raw_material_disc_{{ $supplier_delivery_order_item->raw_material_id }}' class='raw_material_unit_disc form-control mr-1' @if($supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining != ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty)) readonly @endif/>
                                                        </td>
                                                        <td>
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_amount[]' value="{{ @$supplier_delivery_order_item_amount }}" id='raw_material_amount_{{ $supplier_delivery_order_item->raw_material_id }}' class='raw_material_amount form-control mr-1' readonly/>
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="mdi mdi-trash-can font-size-20 text-danger remove" @if($supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining != ($supplier_delivery_order_item->supplier_delivery_order_item_qty * $supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty)) hidden @endif></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr id="add_raw_material_before"></tr>
                                            @elseif(!is_null(@$supplier_do->raw_material_id))
                                                @foreach ($supplier_do->raw_material_id as $key => $raw_material_id)
                                                    <tr class="raw_materials" id="raw_material_{{ $raw_material_id }}_{{ $key }}">
                                                        <td>
                                                            <b>{{ $supplier_do->raw_material_name[$key] }}</b>-{{ $supplier_do->raw_material_category[$key] }}
                                                            <input type='hidden' name='raw_material_id[]' value="{{ $raw_material_id }}"/>
                                                            <input type='hidden' name='raw_material_name[]' value="{{ $supplier_do->raw_material_name[$key] }}"/>
                                                            <input type='hidden' name='raw_material_category[]' value="{{ $supplier_do->raw_material_category[$key] }}"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0.000" step="0.001" name="supplier_delivery_order_item_value_per_qty[]" value="{{ @$supplier_do->supplier_delivery_order_item_value_per_qty[$key] }}" id="raw_material_value_{{ $raw_material_id }}" class="raw_material_value form-control mr-1"/>
                                                            <span>{{ $supplier_do->raw_material_value_unit[$key] }}</span>
                                                            <input type="hidden" name="raw_material_value_unit[]" value="{{ $supplier_do->raw_material_value_unit[$key] }}"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" step="1" name="supplier_delivery_order_item_qty[]" value="{{ @$supplier_do->supplier_delivery_order_item_qty[$key] }}" id="raw_material_qty_{{ $raw_material_id }}" class="raw_material_qty form-control mr-1"/>
                                                            <span>{{ $supplier_do->raw_material_quantity_unit[$key] }}</span>
                                                            <input type="hidden" name="raw_material_quantity_unit[]" value="{{ $supplier_do->raw_material_quantity_unit[$key] }}"/>
                                                        </td>
                                                        <td>
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_price_per_qty[]' value="{{ @$supplier_do->supplier_delivery_order_item_price_per_qty[$key] }}" id='raw_material_unit_price_{{ $raw_material_id }}' class='raw_material_unit_price form-control mr-1' />
                                                        </td>
                                                        <td>
                                                            {{-- <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_disc_per_qty[]' value="{{ @$supplier_do->supplier_delivery_order_item_disc_per_qty[$key] }}" id='raw_material_unit_disc_{{ $raw_material_id }}' class='raw_material_unit_disc form-control mr-1'/> --}}
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_disc[]' value="{{ @$supplier_do->supplier_delivery_order_item_disc[$key] }}" id='raw_material_disc_{{ $raw_material_id }}' class='raw_material_unit_disc form-control mr-1'/>
                                                        </td>
                                                        <td>
                                                            <input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_amount[]' value="{{ @$supplier_do->supplier_delivery_order_item_amount[$key] }}" id='raw_material_amount_{{ $raw_material_id }}' class='raw_material_amount form-control mr-1' readonly/>
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="mdi mdi-trash-can font-size-20 text-danger remove"></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr id="add_raw_material_before">
                                                </tr>
                                            @else
                                                <tr id="no_supplier_do_item_records">
                                                    <td colspan="7">No Supplier Delivery Order Item Records</td>
                                                </tr>
                                            @endif
                                            <tr id="add_raw_material_before"></tr>

                                            <tr>
                                                <td colspan="5" class="text-right">Subtotal: </td>
                                                <td id="subtotal" class="text-right">RM {{ number_format(@$supplier_do->supplier_delivery_order_subtotal, 2) ?? number_format(0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right">Discount:</td>
                                                <td id="discount" class="text-right">RM {{ number_format(@$supplier_do->supplier_delivery_order_discount, 2) ?? number_format(0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right">Nett:</td>
                                                <td id="nett" class="text-right">RM {{ number_format(@$supplier_do->supplier_delivery_order_total, 2) ?? number_format(0, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right" style="padding-top:0.9rem">Tax:</td>
                                                <td id="tax" class="text-right"><input type='number' step="0.01" id="supplier_delivery_order_tax" name="supplier_delivery_order_tax" class="form-control form-control-sm" value="{{ number_format(@$supplier_do->supplier_delivery_order_tax, 2) ?? number_format(0, 2) }}"/></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"><b>Grand Total: </b></td>
                                                <td id="grandtotal" class="text-right">RM {{ number_format(@$supplier_do->supplier_delivery_order_grandtotal, 2) ?? number_format(0, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4 d-inline-flex">Upload Invoice</h4><span class="text-danger"> *</span>
                        <input type="file" class="form-control-file" name="supplier_do_media" accept=".png,.jpg,.jpeg"/>
                        @if ($title == 'Edit' && @$supplier_do->hasMedia('supplier_delivery_order_media'))
                            <a href="{{ @$supplier_do->getFirstMediaUrl('supplier_delivery_order_media') }}" target="_blank">
                                <img src="{{ @$supplier_do->getFirstMediaUrl('supplier_delivery_order_media') }}"
                                    width="90" class="mt-2" alt="Supplier Delivery Order Invoice">
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('supplier_do_listing') }}" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    {{-- Add Raw Material Modal --}}
    <div class="modal fade" id="add_raw_material_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <h4>Raw Material Search</h4>
                        <div class="row">
                            <div class="col-12">
                                <label for="raw_material_category">Raw Material Category</label>
                                {!! Form::select('raw_material_category_id', $raw_material_category_sel, '', ['class' => 'form-control mb-3', 'id' => 'raw_material_category_id']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <th>Raw Material Name</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="show_raw_material">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script>
        let supplier_id = '{{ @$supplier_do->supplier_id }}' ?? "";
        let company_id = '{{ @$supplier_do->company_id }}' ?? "";
        let raw_material_row_count = 0;
        let subtotal = 0;
        let discount = 0;
        let nett = 0;
        let grand_total = 0;

        @if (auth()->user()->company_id != 0)
            company_id = '{{ auth()->user()->company_id }}';
        @endif

        $(document).ready(function(e){
            get_supplier_by_company_id(company_id);
            enable_disable_supplier_company();
            $('input[type="number"]').trigger('keyup');
            @if ($title == "Edit")
                $('#add_raw_material').show();
            @else
                $('#add_raw_material').hide();
            @endif
        });

        $(document).on('change', '#raw_material_category_id', function() {
            let raw_material_category_id = $('#raw_material_category_id').val();
            var raw_material_name_entered = $('#raw_material_name').val();
            get_raw_material_by_raw_material_category(raw_material_category_id, raw_material_name_entered, company_id);
        });

        $(document).on('change', '#company_id', function() {
            let company_id = $('#company_id').val();
            get_supplier_by_company_id(company_id);
        });

        $(document).on('click', '#supplier_id', function() {
            if ($(this).val() != 0 && $(this).val() > 0) {
                enable_disable_supplier_company();
                $('#add_raw_material').show();
            }else {
                $('#add_raw_material').hide();
            }
        });

        $(document).on('click', '.remove', function() {
            let raw_material_row_id = $(this).parent().parent().attr('id');
            $('#' + raw_material_row_id).remove();
            calc_all_supplier_do_items();
            enable_disable_supplier_company();
            raw_material_row_count--;
        });

        $(document).on('keyup', 'input[type="number"]', function(){
            let input_row = $(this).attr('id').split('_');
            let raw_material_id = input_row.pop();

            let raw_material_amount = 0;
            let raw_material_value = $('#raw_material_value_' + raw_material_id).val() ?? 0;
            let raw_material_qty = $('#raw_material_qty_' + raw_material_id).val() ?? 0;
            let raw_material_unit_price = parseFloat($('#raw_material_unit_price_' + raw_material_id).val()) ?? 0;
            let raw_material_disc = isNaN(parseFloat($('#raw_material_disc_' + raw_material_id).val())) ? 0.00 : parseFloat($('#raw_material_disc_' + raw_material_id).val());

            raw_material_amount = (raw_material_qty * raw_material_unit_price) - raw_material_disc;
            $('#raw_material_amount_' + raw_material_id).val(raw_material_amount.toFixed(2));
            calc_all_supplier_do_items();
        });

        function calc_all_supplier_do_items()
        {
            let tax = isNaN(parseFloat($('#supplier_delivery_order_tax').val())) ? 0.00 : parseFloat($('#supplier_delivery_order_tax').val()) ;
            var all_raw_material_value = $('.raw_material_value').map((key,element) => element.value).get();
            var all_raw_material_qty = $('.raw_material_qty').map((key,element) => element.value).get();
            var all_raw_material_unit_price = $('.raw_material_unit_price').map((key,element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();
            var all_raw_material_disc = $('.raw_material_unit_disc').map((key,element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();
            var all_raw_material_amount = $('.raw_material_amount').map((key,element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();

            var temp_subtotal = 0;
            var temp_all_raw_material_amount = 0;
            var temp_all_raw_material_disc = 0;

            for(let i = 0; i < all_raw_material_value.length; i++)
            {
                let temp_raw_material_amount = 0;
                temp_raw_material_amount = (all_raw_material_qty[i] * all_raw_material_unit_price[i]);

                temp_all_raw_material_amount += (all_raw_material_qty[i] * all_raw_material_unit_price[i]);
                temp_all_raw_material_disc += all_raw_material_disc[i];
            }

            subtotal = temp_all_raw_material_amount;
            $('#subtotal').html('RM ' + subtotal.toFixed(2));

            discount = temp_all_raw_material_disc;
            $('#discount').html('RM ' + discount.toFixed(2));

            nett = subtotal - discount;
            $('#nett').html('RM ' + nett.toFixed(2));

            grand_total = nett + tax;
            $('#grandtotal').html('RM ' + grand_total.toFixed(2));
        }


        function get_raw_material_by_raw_material_category(raw_material_category_id, raw_material_name){
            let company_id = isNaN($('#company_id').val()) ? '{{ auth()->user()->company_id }}' : $('#company_id').val();
            $.ajax({
                url: "{{ route('ajax_get_raw_material_by_raw_material_category_id') }}",
                method: "get",
                data: {
                    _token: "{{ csrf_token() }}",
                    raw_material_category_id: raw_material_category_id,
                    supplier_id: $('#supplier_id').val(),
                    company_id: company_id,
                    raw_material_name_entered: raw_material_name
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        var raw_material_list = '';
                        e.data.forEach(element => {
                            raw_material_list += '<tr id=raw_material_' + element.id + '>';
                            raw_material_list += '<td>' + element.value + '</td>';
                            raw_material_list += '<td class="d-flex"><span id="add_btn_' + element.id + '" class="m-auto">' +
                                '<a href="javascript:void(0);" class="btn btn-sm btn-outline-success waves-effect waves-light" onclick="add_raw_material(' + element.id + ')">' +
                                '<i class="fas fa-plus"></i></a></span><span id="load_' + element.id + '" class=" load text-center m-auto">Loading...</span></td>';
                            raw_material_list += '</tr>';
                        });

                        if (raw_material_category_id == '') {
                        $('#show_raw_material').html('');
                        } else {
                            $('#show_raw_material').html(raw_material_list);
                        }

                        $('.load').hide();
                    }else{
                        var raw_material_list = "<tr><td colspan='2'>No records!</td></tr>";
                        $('#show_raw_material').html(raw_material_list);
                    }
                },
                error: function(e) {
                    alert('An Error Occurred! Please try again. ');
                }
            });
        }

        function get_supplier_by_company_id(company_id)
        {
            let sel_supplier_id = '{{ @$supplier_do->supplier_id }}' ?? null;
            let supplier_sel = '<option value="">Please Select Supplier</option>';

            $.ajax({
                url: "{{ route('ajax_get_supplier_by_company_id') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if(sel_supplier_id == element.id)
                                supplier_sel += '<option selected value="' + element.id + '">' + element.value + '</option>';
                            else
                                supplier_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                        });
                        $('#supplier_id').html(supplier_sel);
                    }else{
                        $('#supplier_id').html('<option value="">No Supplier</option>');
                    }
                }
            });
        }

        function add_raw_material(raw_material_id){
            $('#add_btn_' + raw_material_id).hide();
            $('#load_' + raw_material_id).show();
            $('#no_supplier_do_item_records').hide();

            $.ajax({
                url:"{{ route('ajax_get_raw_material_details') }}",
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    raw_material_id: raw_material_id,
                },
                success: function(e){
                    if(e.status){
                        raw_material_row_count++;

                        var raw_material_detail = "<tr class='raw_materials' id='raw_material_" + e.data.raw_material_id + "_" + raw_material_row_count + "'>";
                        raw_material_detail += "<td><b>" + JSON.parse(e.data.raw_material_name).en + "</b>-" + JSON.parse(e.data.raw_material_category_name).en + "</td>";
                        raw_material_detail += "<input type='hidden' name='raw_material_id[]' value='" + e.data.raw_material_id + "' />"
                        raw_material_detail += "<input type='hidden' name='raw_material_name[]' value='" + JSON.parse(e.data.raw_material_name).en + "' />"
                        raw_material_detail += "<input type='hidden' name='raw_material_category[]' value='" + JSON.parse(e.data.raw_material_category_name).en + "' />"
                        raw_material_detail += "<td>";
                        raw_material_detail += "<input type='number' min='0.000' step='0.001' name='supplier_delivery_order_item_value_per_qty[]' id='raw_material_value_" + e.data.raw_material_id + "' " + "class='raw_material_value form-control mr-1' />";
                        raw_material_detail += "<span>" + e.data.raw_material_value_unit + "</span></td>";
                        raw_material_detail += "<input type='hidden' name='raw_material_value_unit[]' value='" + e.data.raw_material_value_unit + "' />"
                        raw_material_detail += "<td>";
                        raw_material_detail += "<input type='number' min='0' step='1' name='supplier_delivery_order_item_qty[]' id='raw_material_qty_" + e.data.raw_material_id + "' " + "class='raw_material_qty form-control mr-1' />";
                        raw_material_detail += "<span>" + e.data.raw_material_quantity_unit + "</span></td>";
                        raw_material_detail += "<input type='hidden' name='raw_material_quantity_unit[]' value='" + e.data.raw_material_quantity_unit + "' />"
                        raw_material_detail += "<td><input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_price_per_qty[]' id='raw_material_unit_price_" + e.data.raw_material_id + "' " + "class='raw_material_unit_price form-control mr-1' /></td>";
                        raw_material_detail += "<td><input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_disc[]' id='raw_material_disc_" + e.data.raw_material_id + "' " + " value='0' class='raw_material_unit_disc form-control mr-1' /></td>";
                        raw_material_detail += "<td><input type='number' min='0.00' step='0.01' name='supplier_delivery_order_item_amount[]' id='raw_material_amount_" + e.data.raw_material_id + "' " + "class='raw_material_amount form-control mr-1' readonly/></td>";
                        raw_material_detail += '<td class="text-center">';
                        raw_material_detail += '<i class="mdi mdi-trash-can font-size-20 text-danger remove"></i>';
                        raw_material_detail += "</td></tr>";


                        $(raw_material_detail).insertBefore('#add_raw_material_before');
                        $('#load_' + raw_material_id).hide();
                        $('#add_btn_' + raw_material_id).show();
                        enable_disable_supplier_company();
                    }
                },
                error: function(e) {
                    alert('An Error Occurred! Please try again. ');
                }
            })
        }

        function enable_disable_supplier_company(){
            let sel_company_id = $('#company_id').val() ?? company_id;
            let sel_supplier_id = $('#supplier_id').val() == "" ? supplier_id : $('#supplier_id').val();
            let count = $('.raw_materials').length;

            if(count > 0){

                $('#company_id option').not('option[value="' + sel_company_id + '"]').each(
                    function(index){
                        $(this).prop('disabled', true)
                    }
                );

                $('#supplier_id option').not('option[value="' + sel_supplier_id + '"]').each(
                    function(index){
                        $(this).prop('disabled', true)
                    }
                );

                }else{
                $('#company_id option').each(
                    function(index) {
                        $(this).prop('disabled', false);
                    }
                );

                $('#supplier_id option').each(
                    function(index) {
                        $(this).prop('disabled', false);
                    }
                );
            }
        }
    </script>
@endsection
