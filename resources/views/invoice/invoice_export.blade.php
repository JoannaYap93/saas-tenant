<body style="width:100%">
    <div style="margin:auto">
        <table>
            @php
                $total_product = array();
                $total = 0;
                $total_price = array();
                $grand_total = 0;
            @endphp
            <thead style="border:1px solid #eee">
                <tr>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Date Created</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Invoice Number</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Company Name</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Company Land Name</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">PIC Name</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">PIC Number</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Items</th>
                    <th style="width: 220px; height: 50px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Price</th>
                </tr>
            </thead>
            <tbody>
              @php
              $round_up_down = 0;
              $invoice_grandtotal = 0;
              @endphp
                @foreach ($records as $key => $invoice)
                @php
                $count_item = count($invoice->invoice_item);
                $round_up_down += $invoice->invoice_total_round_up;
                $invoice_grandtotal += $invoice->invoice_grandtotal;
                @endphp
                    <tr>
                        <td style="text-align: center; border:1px solid #eee">{{ date_format(@$invoice->invoice_created, 'Y-m-d h:i A') }}</td>
                        <td style="text-align: center; border:1px solid #eee">{{ $invoice->invoice_no }}</td>
                        <td style="text-align: center; border:1px solid #eee">{{ @$invoice->customer->customer_company_name }}</td>
                        <td style="text-align: center; border:1px solid #eee">{{ @$invoice->company_land->company_land_name }}</td>
                        <td style="text-align: center; border:1px solid #eee">{{ @$invoice->customer_name }}</td>
                        <td style="text-align: center; border:1px solid #eee">{{ @$invoice->customer->customer_mobile_no }}</td>
                        <td style="text-align: center; border:1px solid #eee">

                            @foreach ($invoice->invoice_item as $item)
                                @php
                                    if(isset($total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id])){
                                        $total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id] += @$item->invoice_item_quantity;
                                    }else{
                                        $total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id] = @$item->invoice_item_quantity;
                                    }
                                    if(isset($total_price[$item->product->product_id][$item->setting_product_size->setting_product_size_id])){
                                        $total_price[$item->product->product_id][$item->setting_product_size->setting_product_size_id] += @$item->invoice_item_total;
                                    }else{
                                        $total_price[$item->product->product_id][$item->setting_product_size->setting_product_size_id] = @$item->invoice_item_total;
                                    }
                                    $total += @$item->invoice_item_quantity;
                                    $grand_total += @$item->invoice_item_total;
                                @endphp
                                <b>{{ $item->product->product_name }}</b>-{{ @$item->setting_product_size->setting_product_size_name }}
                                <br>[{{ @$item->invoice_item_quantity }}]<br>
                            @endforeach
                        </td>
                        <td style="text-align: center; border:1px solid #eee">{{ @$invoice->invoice_grandtotal }}</td>

                    </tr>
                @endforeach;

                <tr><td></td></tr>
                <tr style="text-align: center; border:1px solid #eee background-color: #d8d9df;">
                    <td colspan="3" style="text-align: center; border:1px solid #eee; background-color: #d8d9df;">
                    Total By Product
                    </td>
                </tr>

                <tr style="text-align: center; border:1px solid #eee background-color: #E6E7EA;">
                    <td style="text-align: center; border:1px solid #eee; background-color: #E6E7EA;">
                    Product
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #E6E7EA;">
                    Qty
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #E6E7EA;">
                    Price
                    </td>
                </tr>


                @foreach($product_list as $product_id => $product_name)
                    @foreach($setting_product_size as $size_id => $size_name)
                        @if(isset($total_product[$product_id][$size_id]))
                            <tr style="border:1px solid #eee background-color: #d8d9df;">
                                <td style="text-align: center; border:1px solid #eee">{{ $product_name }}-{{ $size_name }}</td>
                                <td style="text-align: center; border:1px solid #eee">{{ $total_product[$product_id][$size_id] }}</td>
                                <td style="text-align: center; border:1px solid #eee">{{ $total_price[$product_id][$size_id] }}</td>
                            </tr>
                        @endif
                @endforeach
                @endforeach

                <tr style="text-align: center; border:1px solid #eee background-color: #F4F4F6;">
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        Total By Product:
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        {{ $total }}
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        {{ $grand_total }}
                    </td>
                </tr>
                <tr style="text-align: center; border:1px solid #eee background-color: #F4F4F6;">
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        Total Round Up/Down:
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        -
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        {{ $round_up_down }}
                    </td>
                </tr>
                <tr style="text-align: center; border:1px solid #eee background-color: #F4F4F6;">
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        GrandTotal:
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        -
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        {{ $invoice_grandtotal }}
                    </td>
                </tr>
            </tbody>
      </table>
  </div>
</body>
</html>
