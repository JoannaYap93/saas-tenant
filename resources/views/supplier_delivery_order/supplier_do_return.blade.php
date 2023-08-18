@extends('layouts.master')

@section('title') {{ $title }} Supplier Delivery Order @endsection

@section('css')
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
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3">#{{ @$supplier_do->supplier_delivery_order_running_no }}</h4>
                        <h4 class="card-title mb-3">Stock In Date: {{ date('d M Y', strtotime(@$supplier_do->supplier_delivery_order_date)) }}</h4>
                    </div>
                    <div>
                        <h6><b>{{ @$supplier_do->supplier->supplier_name }}</b></h6>
                    </div>
                    <div class="mb-4">
                        {{ (substr(@$supplier_do->supplier->supplier_address, -1) == "," ? @$supplier_do->supplier->supplier_address : @$supplier_do->supplier->supplier_address . ",") }}<br/>
                        {{ (substr(@$supplier_do->supplier->supplier_address2, -1) == "," ? @$supplier_do->supplier->supplier_address2 : @$supplier_do->supplier->supplier_address2 . ",") }}<br/>
                        {{ @$supplier_do->supplier->supplier_postcode . ", " . @$supplier_do->supplier->supplier_city . ", " . @$supplier_do->supplier->supplier_state . ", " . @$supplier_do->supplier->supplier_country }}<br/>
                    </div>
                    <form method="POST" action="{{ $submit }}">
                        @csrf
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead class="thead-light">
                                    <th>Supplier Delivery Order Items</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Discount (RM)</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Return Quantity</th>
                                </thead>
                                <tbody id="supplier_do_item_return">
                                    @foreach (@$supplier_do->supplier_delivery_order_item as $supplier_delivery_order_item)
                                        <tr>
                                            <td>
                                                <small>{{ json_decode(@$supplier_delivery_order_item->raw_material->setting_raw_category->raw_material_category_name)->en }}</small><br>
                                                <b>{{ json_decode(@$supplier_delivery_order_item->raw_material->raw_material_name)->en }}</b> -
                                                {{ @$supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining  }}
                                                {{ @$supplier_delivery_order_item->raw_material->raw_material_value_unit }}
                                                <input type="hidden" name="supplier_delivery_order_item_id[]" id="supplier_delivery_order_item_{{ @$supplier_delivery_order_item->supplier_delivery_order_item_id }}" value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_id }}"/>
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ floor(@$supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining / @$supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty) }}
                                                {{ @$supplier_delivery_order_item->raw_material->raw_material_quantity_unit }}
                                                <input type="hidden" class="supplier_delivery_order_item_qty" name="supplier_delivery_order_item_qty[]" id="supplier_delivery_order_item_qty_{{ @$supplier_delivery_order_item->supplier_delivery_order_item_id }}" value="{{ @$supplier_delivery_order_item->supplier_delivery_order_item_qty }}"/>
                                            </td>
                                            <td class="align-middle text-center raw_material_unit_price" id="price_per_qty_{{ @$supplier_delivery_order_item->supplier_delivery_order_item_id }}">
                                                RM {{ @$supplier_delivery_order_item->supplier_delivery_order_item_price_per_qty }}
                                            </td>
                                            <td class="align-middle text-center raw_material_unit_disc" id="disc_{{ $supplier_delivery_order_item->supplier_delivery_order_item_id }}">
                                                RM {{ @$supplier_delivery_order_item->supplier_delivery_order_item_disc }}
                                            </td>
                                            <td class="align-middle text-center raw_material_amount" id="raw_material_return_amount_{{ @$supplier_delivery_order_item->supplier_delivery_order_item_id }}">
                                                RM 0.00
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="number" value="0" min="0" max="{{ floor(@$supplier_delivery_order_item->raw_material_company_usage->raw_material_company_usage_total_value_remaining / @$supplier_delivery_order_item->supplier_delivery_order_item_value_per_qty) }}" step="1" name="return_qty[]" id="return_qty_{{ $supplier_delivery_order_item->supplier_delivery_order_item_id }}" class="raw_material_return_qty form-control form-control-sm"/>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="text-right">Subtotal: </td>
                                        <td id="subtotal" class="text-right">RM 0.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">Discount:</td>
                                        <td id="discount" class="text-right">RM 0.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Grand Total: </b></td>
                                        <td id="grandtotal" class="text-right">RM 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('supplier_do_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let subtotal = 0;
        let discount = 0;
        let grandtotal = 0;

        $(document).on('keyup', 'input[type="number"]', function(){
            let input_row = $(this).attr('id').split('_');
            let supplier_do_item_id = input_row.pop();

            let raw_material_return_amount = 0;
            let raw_material_return_qty = $('#return_qty_' + supplier_do_item_id).val() ?? 0;
            let raw_material_unit_price = parseFloat($('#price_per_qty_' + supplier_do_item_id).text().replace("RM ", ""));
            let raw_material_unit_disc = parseFloat($('#disc_' + supplier_do_item_id).text().replace("RM ", ""));
            let supplier_do_item_qty = $('#supplier_delivery_order_item_qty_' + supplier_do_item_id).val() ?? 0;

            raw_material_return_amount = (raw_material_unit_price - (raw_material_unit_disc / supplier_do_item_qty)) * raw_material_return_qty;
            $('#raw_material_return_amount_' + supplier_do_item_id).html("RM " + raw_material_return_amount.toFixed(2));
            calc_all_return_supplier_do_items();
        });

        function calc_all_return_supplier_do_items()
        {
            let all_raw_material_unit_price = [];
            let all_raw_material_unit_disc = [];
            let all_raw_material_amount = [];

            let all_raw_material_return_qty = $('.raw_material_return_qty').map((key,element) => isNaN(element.value) ? 0 : parseInt(element.value)).get();
            let all_supplier_delivery_order_item_qty = $('.supplier_delivery_order_item_qty').map((key,element) => isNaN(element.value) ? 0 : parseInt(element.value)).get();

            $("td.raw_material_unit_price").each(function(){
                all_raw_material_unit_price.push(parseFloat($(this).text().replace("RM ", "")));
            })

            $("td.raw_material_unit_disc").each(function(){
                all_raw_material_unit_disc.push(parseFloat($(this).text().replace("RM ", "")));
            })

            $("td.raw_material_amount").each(function(){
                all_raw_material_amount.push(parseFloat($(this).text().replace("RM ", "")));
            })

            let temp_all_raw_material_amount = 0;
            let temp_all_raw_material_disc = 0;

            for(let i = 0; i < all_raw_material_return_qty.length; i++)
            {
                let temp_raw_material_amount = 0;
                temp_raw_material_amount = (all_raw_material_unit_price[i] - (all_raw_material_unit_disc[i] / all_supplier_delivery_order_item_qty[i])) * all_raw_material_return_qty[i];

                if(temp_raw_material_amount.toFixed(2) == all_raw_material_amount[i])
                {
                    temp_all_raw_material_amount += (all_raw_material_return_qty[i] * all_raw_material_unit_price[i]);
                    temp_all_raw_material_disc += (all_raw_material_unit_disc[i] / all_supplier_delivery_order_item_qty[i]) * all_raw_material_return_qty[i];
                }
            }

            subtotal = temp_all_raw_material_amount;
            $('#subtotal').html('RM ' + subtotal.toFixed(2));

            discount = temp_all_raw_material_disc;
            $('#discount').html('RM ' + discount.toFixed(2));

            grandtotal = subtotal - discount;
            $('#grandtotal').html('RM ' + grandtotal.toFixed(2));
        }
    </script>
@endsection
