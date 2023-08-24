@extends('layouts.master')

@section('title')
    Issue Invoice
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <style>
        td:last-child {
            text-align: center;
        }

        .mdi-trash-can-outline {
            font-size: 1.25rem;
        }

        input[type="number"] {
            appearance: none;
            -webkit-appearance: none;
        }

        .form-control[readonly] {
            background-color: #ffffff !important;
        }

    </style>
@endsection

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $type }} Invoice </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Invoice</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $type }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form id="submit_form" action="{{ route('invoice_add', ['tenant' => tenant('id')]) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Details</h4>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="row" id="show_email">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Email: <span class="text-danger">*</span> <span
                                                    id="email_check"></span> </label>
                                            <input type="email" name="customer_email" id="customer_email"
                                                value="{{ @$customer->customer_email }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Customer Mobile: </label><br>
                                            @if (@$customer->customer_mobile_no)
                                                <h5>{{ @$customer->customer_mobile_no }}</h5>
                                            @else
                                                <select name="customer_mobile" id="customer_mobile"
                                                    class="form-control" required>
                                                    <option></option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Customer Name: <span
                                                    class="text-danger">*</span></label><br>
                                            <h5 id="customer_name">{{ @$customer->customer_name }}</h5>
                                            <input type="hidden" name="customer_name" class="form-control"
                                                id="customer_name_input" value="{{ @$customer->customer_name }}"
                                                required>
                                            <input type="hidden" name="customer_id"
                                                value="{{ @$customer->customer_id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Address: <span class="text-danger">*</span></label>
                                            <input type="text" name="address" id=""
                                                value="{{ @$customer->customer_address }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Address 2:</label>
                                            <input type="text" name="address2"
                                                value="{{ @$customer->customer_address2 }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">City: <span class="text-danger">*</span></label>
                                            <input type="text" name="city" id=""
                                                value="{{ @$customer->customer_city }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Postcode: <span class="text-danger">*</span></label>
                                            <input type="text" name="postcode"
                                                value="{{ @$customer->customer_postcode }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">State: <span class="text-danger">*</span></label>
                                            <input type="text" name="state" value="{{ @$customer->customer_state }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Country: <span class="text-danger">*</span></label>
                                            <input type="text" name="country"
                                                value="{{ @$customer->customer_country }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Company : <span class="text-danger">*</span></label>
                                                {!! Form::select('company_id', $company, @$customer->company_id, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <label for="">Land: <span class="text-danger">*</span></label>
                                        {!! Form::select('company_land_id', $company_land, @$orders[0]->company_land_id, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
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
                        @php
                            $do_txt = '';
                            foreach ($orders as $dok => $do) {
                                $do_txt .= '#' . $do->delivery_order_no . ', ';
                                $do_txt .= '<input type="hidden" name="do_id[]" value="' . $do->delivery_order_id . '">';
                            }
                        @endphp
                        <h4 class="card-title">Delivery Order Items</h4> <span>{!! $do_txt !!}</span>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price / KG</th>
                                            <th> Discount / KG</th>
                                            <th data-toggle="tooltip"
                                                title="Total = (Price * Quantity) - (Discount * Quantity)"
                                                class="text-right">
                                                Total
                                                <i class="mdi mdi-information"></i>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $product_arr = [];
                                            $items = '';
                                            $item_quantity = [];
                                            $input1 = 0;
                                            $product_size = [];
                                            $data = [];
                                        @endphp
                                        @foreach ($orders as $key => $order)
                                            @php
                                                foreach ($order->delivery_order_items as $k => $item) {
                                                    if (array_key_exists($item->product_id, $product_arr)) {
                                                        if (array_key_exists($item->setting_product_size_id, $product_arr[$item->product_id])) {
                                                            array_push($product_arr[$item->product_id][$item->setting_product_size_id], $item->delivery_order_item_quantity);

                                                            $item_quantity[$item->product_id][$item->setting_product_size_id] += $item->delivery_order_item_quantity;
                                                        } else {
                                                            $product_arr[$item->product_id][$item->setting_product_size_id] = [$item->delivery_order_item_quantity];
                                                            $item_quantity[$item->product_id][$item->setting_product_size_id] = $item->delivery_order_item_quantity;
                                                            if($item->delivery_order_item_price_per_kg != NULL){
                                                                array_push($product_size, [$item->product_id, $item->setting_product_size_id, $item->product->product_name, $item->setting_product_size->setting_product_size_name, $item->delivery_order_item_price_per_kg]);
                                                            }else{
                                                                array_push($product_size, [$item->product_id, $item->setting_product_size_id, $item->product->product_name, $item->setting_product_size->setting_product_size_name]);
                                                            }
                                                        }
                                                    } else {
                                                        $item_quantity[$item->product_id][$item->setting_product_size_id] = $item->delivery_order_item_quantity;
                                                        if($item->delivery_order_item_price_per_kg != NULL){
                                                            array_push($product_size, [$item->product_id, $item->setting_product_size_id, $item->product->product_name, $item->setting_product_size->setting_product_size_name, $item->delivery_order_item_price_per_kg]);
                                                        }else{
                                                            array_push($product_size, [$item->product_id, $item->setting_product_size_id, $item->product->product_name, $item->setting_product_size->setting_product_size_name]);
                                                        }
                                                        $product_arr[$item->product_id] = [$item->setting_product_size_id => [0 => $item->delivery_order_item_quantity]];
                                                    }
                                                }
                                            @endphp
                                        @endforeach
                                        @php
                                            $data = collect($product_size)->sortBy(1);
                                            $data = $data->groupBy(0);
                                                foreach($products as $prod){
                                                    if(isset($data[$prod->product_id])){
                                                        for ($i = 0; $i < count($data[$prod->product_id]); $i++) {
                                                            $items .= '<tr><td>' . $data[$prod->product_id][$i][2] . ' - ' . $data[$prod->product_id][$i][3] . ' - <b>' . round($item_quantity[$data[$prod->product_id][$i][0]][$data[$prod->product_id][$i][1]], 4) . ' KG </b>';
                                                            $items .= '<input type="hidden" id="product_quantity_' . $input1 . '_0" name="product_quantity_' . $input1 . '_0" value="' . $item_quantity[$data[$prod->product_id][$i][0]][$data[$prod->product_id][$i][1]] . '">';
                                                            $items .= '<input type="hidden" name="products[]" value="' . $data[$prod->product_id][$i][0] . '">';
                                                            $items .= '<input type="hidden" name="size[]" value="' . $data[$prod->product_id][$i][1] . '"></td>';
                                                            $items .= '<td><input type="number" id="product_price_' . $input1 . '_0" name="product_price_' . $input1 . '_0" class="form-control product_price" required value=' . (isset($data[$prod->product_id][$i][4]) ? $data[$prod->product_id][$i][4] : 0) . ' step="0.1"><span id="p_q_' . $input1 . '_0"></span></td>';
                                                            $items .= '<td><input type="number" id="product_dis_' . $input1 . '_0" name="product_dis_' . $input1 . '_0" class="form-control product_dis" required value="0" step="0.1"><span id="d_q_' . $input1 . '_0"></span>';
                                                            $items .= '<input type="hidden" id="product_tt_dis_' . $input1 . '_0" class="product_ttl_dis"></td>';
                                                            $items .= '<td id="product_stt_' . $input1 . '_0" class="text-right"></td>';
                                                            $items .= '<input type="hidden" id="product_sub_' . $input1 . '_0" class="form-control product_sub" readonly>';
                                                            $items .= '<input type="hidden" id="product_b4_' . $input1 . '_0" class="product_b4_dis">';
                                                            $items .= '</tr>';
                                                            $input1++;
                                                        }
                                                    }
                                                }
                                        @endphp
                                        {!! $items !!}
                                        <input type="hidden" name="product_arr"
                                            value="{{ json_encode($product_arr) }}">
                                        <tr>
                                            <td colspan="3" class="text-right">Subtotal: </td>
                                            <td id="subtotal" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Discount: </td>
                                            <td id="discounttotal" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Total: </td>
                                            <td id="total" class="text-right"></td>
                                        </tr>
                                        @if (auth()->user()->company->company_enable_gst == 1)
                                            <tr>
                                                <td colspan="3" class="text-right">GST ({{ $gst }}%):</td>
                                                <td id="gst" class="text-right"></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-right">Round Up/Down: </td>
                                            <td>
                                              <input type="number" name="invoice_total_round_up" id="round_up_down"
                                                class="form-control text-right" value="0" step="any">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right"><b>Grand Total: </b></td>
                                            <td id="grandtotal" class="text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                        <h4 class="card-title">Other Details</h4>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Payment Method: </label>
                                    <select name="payment_method" id="" class="form-control" required>
                                        @foreach ($payment_method as $pk => $payment)
                                            <option value="{{ $pk }}">
                                                {{ $payment }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Remark: </label>
                                    @if (count($orders) > 1)
                                        <input type="text" name="invoice_remark" id="" value="{!! implode(', ',$orders->pluck('delivery_order_remark')->toArray()) !!}" class="form-control">
                                    @else
                                        <input type="text" name="invoice_remark" id="" value="{{ @$do->delivery_order_remark }}" class="form-control">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submit_btn" class="btn btn-primary mr-3">Submit</button>
                                    <a href="{{ route('do_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        // variable
        let product_subtotal_arr = [];
        let subttl = 0;
        let product_totaldis_arr = [];
        let discount_total = 0;
        let product_price_arr = [];

        // function
        $(document).ready(function() {
            // console.log(parseFloat($('#expense_total').val()));
            $('#submit_btn').attr('disabled', false);
            subtotal();
            total_discount();
            total();

            if($('.product_price').val()){
                $('.product_price').trigger('keyup');
            }

            $('#show_email').hide();
        });

        // $('input[type="number"]').on('keydown', function() {
        //     if ($(this).val() == 0) {
        //         $(this).val('');
        //     }
        // });

        $('input[type="number"]').on('keyup', function() {
            let id = $(this).attr('id');
            let quantity = 0;
            let price = 0;
            let discount = 0;
            let product_subtotal = 0;
            let product_setting_id = null;

            if (id.substring(0, 11) == 'product_pri') {
                product_setting_id = id.substring(13);
                price = $(this).val();
                discount = $('#product_dis' + product_setting_id).val() ?? 0;
            } else {
                product_setting_id = id.substring(11);
                price = $('#product_price' + product_setting_id).val() ?? 0;
                discount = $(this).val();
            }

            quantity = $('#product_quantity' + product_setting_id).val();
            product_subtotal = (quantity * price) - (discount * quantity);
            $('#product_sub' + product_setting_id).val(product_subtotal);
            $('#product_stt' + product_setting_id).html("RM " + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(product_subtotal.toFixed(2)));
            $('#product_tt_dis' + product_setting_id).val((discount * quantity));
            $('#product_b4' + product_setting_id).val((quantity * price));
            $('#p_q' + product_setting_id).html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format((quantity * price).toFixed(2)));
            $('#d_q' + product_setting_id).html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format((quantity * discount).toFixed(2)));
            subtotal();
            total_discount();
            total();
        });

        function total() {
            product_price_arr = $('.product_b4_dis').map((_, el) => el.value).get();
            let price = product_price_arr.reduce((p, c) => {
                return p + (parseFloat(c) || 0);
            }, 0);
            $('#subtotal').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price.toFixed(2)));
        }

        function total_discount() {
            product_totaldis_arr = $('.product_ttl_dis').map((_, el) => el.value).get();
            let discount = product_totaldis_arr.reduce((p, c) => {
                return p + (parseFloat(c) || 0);
            }, 0);
            // console.log(discount);
            discount_total = discount.toFixed(2) ?? 0;
            $('#discounttotal').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(discount.toFixed(2)));
        }

        function subtotal() {
            product_subtotal_arr = $('.product_sub').map((_, el) => el.value).get();
            let sum = product_subtotal_arr.reduce((p, c) => {
                return p + (parseFloat(c) || 0);
            }, 0)
            // console.log(subttl);
            $('#total').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(sum.toFixed(2)));
            let gst_val = {{ auth()->user()->company->company_enable_gst ? $gst : 0 }};
            let gst = (sum * gst_val) / 100;
            let round_up_down = 0;
            if(isNaN(parseFloat($('#round_up_down').val())) == false){
              round_up_down = parseFloat($('#round_up_down').val());
            }
            let total_b4_round = gst + sum;
            let total_aft_round =  total_b4_round + round_up_down;
            console.log(parseFloat($('#round_up_down').val()));
            $('#gst').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(gst.toFixed(2)));
            $('#grandtotal').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_aft_round.toFixed(2)) + '</b>');
        }

        $('#customer_mobile').select2({
            ajax: {
                minimumResultsForSearch: -1,
                url: "{{ route('ajax_search_customer_by_mobile_no', ['tenant' => tenant('id')]) }}",
                dataType: 'json',
                data: function(p) {
                    let query = {
                        api_token: '{{ csrf_token() }}',
                        term: p.term,
                    }
                    return query;
                },
                processResult: function(data, p) {
                    return {
                        results: data.results,
                    }
                }
            },
            allowClear: true,
            tags: true,
            placeholder: 'Select Mobile No '
        });

        $('#customer_mobile').on('change', function() {
            var value = $(this).val();
            // console.log(value);
            $.ajax({
                url: "{{ route('ajax_search_customer_by_mobile_no', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_mobile_no: value,
                },
                success: function(e) {
                    // console.log(e.data[0]);
                    if (e.data[0] != null) {
                        $('#show_email').hide();
                        $('#customer_name').html(e.data[0].name);
                        $('#customer_name_input').hide();
                        $('#customer_name_input').val(e.data[0].name);
                        $('#company_name').html(e.data[0].company);
                        $('#customer_id').val(e.data[0].id);
                        $('#new_user').html('');
                        $('input[name="address"]').val(e.data[0].address);
                        $('input[name="address2"]').val(e.data[0].address2);
                        $('input[name="city"]').val(e.data[0].city);
                        $('input[name="postcode"]').val(e.data[0].postcode);
                        $('input[name="state"]').val(e.data[0].state);
                        $('input[name="country"]').val(e.data[0].country);
                        // $('#company_id').val(e.data[0].company_id).change();
                    } else {
                        $('#customer_id').val('');
                        $('#show_email').show();
                        $('#customer_name_input').attr('type', 'text').show().val('');
                        $('#customer_name').html('').hide();
                        $('input[name="address"]').val('');
                        $('input[name="address2"]').val('');
                        $('input[name="city"]').val('');
                        $('input[name="postcode"]').val('');
                        $('input[name="state"]').val('');
                        $('input[name="country"]').val('');
                    }
                }
            })
        });
        $('#submit_form').submit(function(e){
          $('#submit_btn').attr("disabled", true);
        });
    </script>
@endsection
