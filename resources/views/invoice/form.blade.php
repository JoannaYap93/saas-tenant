@extends('layouts.master')

@section('title') Edit Invoice @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <style>
        td:last-child {
            text-align: center;
        }

        .mdi-trash-can-outline {
            font-size: 1.25rem;
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
    <form action="{{ $submit }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Details</h4>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{-- @dd($customer->customer_name) --}}
                                            <label for="">Customer Name: <span
                                                    class="text-danger">*</span></label><br>
                                            <h5>{{ @$customer->customer_name }}</h5>
                                            <input type="hidden" name="customer_name" class="form-control" id=""
                                                value="{{ @$customer->customer_name }}" required>
                                            <input type="hidden" name="customer_id"
                                                value="{{ @$customer->customer_id }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Customer Mobile: </label><br>
                                            <h5>{{ @$customer->customer_mobile_no }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Address: <span class="text-danger">*</span></label>
                                            <input type="text" name="address" id=""
                                                value="{{ @$invoice->customer_address }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Address 2: </label>
                                            <input type="text" name="address2"
                                                value="{{ @$invoice->customer_address2 }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">City: <span class="text-danger">*</span></label>
                                            <input type="text" name="city" id=""
                                                value="{{ @$invoice->customer_city }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Postcode: <span class="text-danger">*</span></label>
                                            <input type="text" name="postcode"
                                                value="{{ @$invoice->customer_postcode }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">State: <span class="text-danger">*</span></label>
                                            <input type="text" name="state" value="{{ @$invoice->customer_state }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Country: <span class="text-danger">*</span></label>
                                            <input type="text" name="country"
                                                value="{{ @$invoice->customer_country }}" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Company : <span class="text-danger">*</span></label>
                                                {!! Form::select('company_id', $company, @$invoice->company_id, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <label for="">Land: <span class="text-danger">*</span></label>
                                        {!! Form::select('company_land_id', $company_land, @$invoice->company_land_id, ['class' => 'form-control']) !!}
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
                            $dos = '';
                            // dd($do_txt);
                            foreach ($do_txt as $key => $do_ids) {
                                if ($key > 0) {
                                    $dos .= ', ';
                                }
                                $dos .= '#' . $do_ids->delivery_order_no;
                            }
                        @endphp
                        <h4 class="card-title">Delivery Order Items</h4> <span>{!! $dos !!}</span>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price / KG (RM)</th>
                                            <th>Discount / KG</th>
                                            <th data-toggle="tooltip" class=" text-center"
                                                title="Total = (Price * Quantity) - (Discount * Quantity)">
                                                Total
                                                <i class="mdi mdi-information"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (@$invoice->invoice_item)
                                            @foreach ($invoice->invoice_item as $k => $item)
                                                <tr>
                                                    <input type="hidden" name="invoice_item_id[]"
                                                                value="{{ $item->invoice_item_id }}">
                                                    <td>
                                                        {{ $item->product->product_name }} -
                                                        {{ $item->setting_product_size->setting_product_size_name }}
                                                        -
                                                        <b id="quantity_{{$k}}">{{ round($item->invoice_item_quantity, 4) }} KG</b>
                                                    </td>
                                                    <td>
                                                       <input type="number" name="product_price_{{$item->invoice_item_id}}" id="product_price_{{$k}}" value="{{ number_format($item->invoice_item_price, 2)}}" class="form-control product_price" step="0.01">
                                                       <input type="number" name="invoice_item_subtotal_{{$item->invoice_item_id}}" hidden id="product_sub_b4_disc_{{$k}}" class="form-control product_sub_b4_disc" step="0.01">
                                                    </td>
                                                    <td style="width: 20%">
                                                        <input type="number" name="discount_{{$item->invoice_item_id}}" id='discountprice_{{$k}}' value="{{ $item->invoice_item_discount }}" class="form-control discount_input_each" >
                                                        <input type="number" hidden name="discount_input_{{$item->invoice_item_id}}" id='discountprice_val_{{$k}}' class="form-control discount_each" >
                                                    </td>
                                                    <td>
                                                      <span id="product_subtotal_{{$k}}" ></span>
                                                      <input type="number" name="invoice_item_total_{{$item->invoice_item_id}}" hidden id="product_sub_{{$k}}" class="form-control product_sub" step="0.01">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>"No Item"</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-right">Subtotal: </td>
                                            <td id="subtotal">RM {{ @$invoice->invoice_subtotal }}</td>
                                            <input hidden type="number" id="subtotal_input" step="0.01" name="invoice_subtotal">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Discount: </td>
                                            <td id="discounttotal">RM {{ @$invoice->invoice_total_discount ?? 0.0 }}
                                            </td>
                                            <input hidden type="number" id="discounttotal_input" step="0.01" name="invoice_total_discount">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Total: </td>
                                            <td id="total">RM {{ @$invoice->invoice_total }}</td>
                                            <input hidden type="number" id="total_input" step="0.01" name="invoice_total">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">GST (6%):</td>
                                            <td id="gst">RM {{ @$invoice->invoice_total_gst }}</td>
                                            <input hidden type="number" id="gst_input" step="0.01" name="invoice_total_gst">
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Round Up/Down:</td>
                                            <td>
                                              <input type="number" name="invoice_total_round_up" id="round_up_down"
                                                class="form-control text-right" value="{{ @$invoice->invoice_total_round_up }}" step="any">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">Grand Total: </td>
                                            <td id="grandtotal"><b>RM {{ @$invoice->invoice_grandtotal }}</b></td>
                                            <input hidden type="number" id="grandtotal_input" step="0.01" name="invoice_grandtotal">
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
                                    <select name="payment_method" id="" class="form-control">
                                        @foreach ($payment_method as $pk => $payment)
                                            <option value="{{ $pk }}" @if (@$invoice->invoice_payment->sortBy('invoice_payment_created')->first()->setting_payment_id == $pk) selected @endif>
                                                {{ $payment }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="">Remark: </label>
                                        <input type="text" name="invoice_remark" id=""
                                        value="{{ @$invoice->invoice_remark }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mr-3">Submit</button>
                                    <a href="{{ route('invoice_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
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
        grandtotal();
        // total_discount();
        // total();

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
      let input_id = $(this).attr('id');
      if(input_id != 'round_up_down'){
        let input_count = input_id.substring(14);
        let quantity = parseFloat($('#quantity_' + input_count).html().slice(0, -3));
        console.log(quantity);
        let price = $('#product_price_' + input_count).val();
        let discount = $('#discountprice_' + input_count).val() * quantity;
        $('#discountprice_val_' + input_count).val(discount);
        let discount_total = 0;
        let product_subtotal_b4_disc = (price * quantity);
        let product_subtotal = (price * quantity) - discount;
        let product_sub = 0;

        $('#product_sub_b4_disc_' + input_count).val(product_subtotal_b4_disc.toFixed(2));
        $('#product_subtotal_' + input_count).html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(product_subtotal.toFixed(2)));
        $('#product_sub_' + input_count).val(product_subtotal.toFixed(2));
          var product_sub_add = document.querySelectorAll(".product_sub_b4_disc");
          for(var i=0;i<product_sub_add.length;i++)
          {
          product_sub+=Number(product_sub_add[i].value);
          }
          $('#subtotal').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(product_sub.toFixed(2)));
          $('#subtotal_input').val(product_sub.toFixed(2));

        var discount_add = document.querySelectorAll(".discount_each");
        for(var i=0;i<discount_add.length;i++)
        {
        discount_total+=Number(discount_add[i].value);
        }

        $('#discounttotal').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(discount_total.toFixed(2)));
        $('#discounttotal_input').val(discount_total.toFixed(2));
        let aft_disc_total = product_sub - discount_total;
        $('#total').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(aft_disc_total.toFixed(2)));
        $('#total_input').val(aft_disc_total.toFixed(2));
        grandtotal();
      }else if(input_id == 'round_up_down'){
        grandtotal();
      }
        // console.log(discount_total);
        //
        // if (id.substring(0, 11) == 'product_pri') {
        //     product_setting_id = id.substring(13);
        //     price = $(this).val();
        //     discount = $('#product_dis' + product_setting_id).val() ?? 0;
        // } else {
        //     product_setting_id = id.substring(11);
        //     price = $('#product_price' + product_setting_id).val() ?? 0;
        //     discount = $(this).val();
        // }
        //
        // quantity = $('#product_quantity' + product_setting_id).val();
        // product_subtotal = (quantity * price) - (discount * quantity);
        // $('#product_sub' + product_setting_id).val(product_subtotal);
        // $('#product_stt' + product_setting_id).html("RM " + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(product_subtotal.toFixed(2)));
        // $('#product_tt_dis' + product_setting_id).val((discount * quantity));
        // $('#product_b4' + product_setting_id).val((quantity * price));
        // $('#p_q' + product_setting_id).html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format((quantity * price).toFixed(2)));
        // $('#d_q' + product_setting_id).html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format((quantity * discount).toFixed(2)));
        // subtotal();
        // total_discount();
        // total();
    });

    function total() {
      // let price = 0;
        // product_price_arr = $('.product_b4_dis').map((_, el) => el.value).get();
        // let price = product_price_arr.reduce((p, c) => {
        //     return p + (parseFloat(c) || 0);
        // }, 0);
      // let price = $('.product_sub').val();
      //  console.log(price);
        // $('#subtotal').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price.toFixed(2)));
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

    function grandtotal() {
        // product_subtotal_arr = $('.product_sub').map((_, el) => el.value).get();
        // let sum = product_subtotal_arr.reduce((p, c) => {
        //     return p + (parseFloat(c) || 0);
        // }, 0)
        // // console.log(subttl);
        // $('#total').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(sum.toFixed(2)));
        let sum = parseFloat($('#total_input').val());
        let gst_val = {{ auth()->user()->user_type_id != 1 ? (auth()->user()->company->company_enable_gst ? $gst : 0) : 0 }};
        let gst = (sum * gst_val) / 100;
        let round_up_down = 0;
        if(isNaN(parseFloat($('#round_up_down').val())) == false){
          round_up_down = parseFloat($('#round_up_down').val());
        }
        let total_b4_round = gst + sum;
        let total_aft_round =  total_b4_round + round_up_down;
        // console.log(parseFloat($('#round_up_down').val()));
        $('#gst').html('RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(gst.toFixed(2)));
        $('#gst_input').val(gst.toFixed(2));
        $('#grandtotal').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_aft_round.toFixed(2)) + '</b>');
        $('#grandtotal_input').val(total_aft_round.toFixed(2));
    }

    </script>
@endsection
