<body style="width:100%">
  <div style="margin:auto">
      <table>
          <tbody>
             @php
                $total_product = array();
                $total = 0;
            @endphp
                    <tr>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Date Created</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee" >DO Number</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Company Name</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">Company Land Name</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">PIC Name</th>
                        <th style="width: 220px;background-color: #d8d9df; text-align: center; border:1px solid #eee">PIC IC</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">DO Items</th> 
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">DO Expenses</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">DO Status</th>
                        <th style="width: 220px; background-color: #d8d9df; text-align: center; border:1px solid #eee">DO Type</th>
                     </tr>
                    @foreach ($records as $do)
                        @php
                            $count_item = count($do->delivery_order_items);
                        @endphp
                        <tr>
                            <td style="text-align: center; border:1px solid #eee ">{{ date_format(@$do->delivery_order_created, 'Y-m-d h:i A') }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ $do->delivery_order_no }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ @$do->customer_details->customer_company_name }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ @$do->company_land->company_land_name }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ @$do->customer_name }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ @$do->customer_ic }}</td>
                            <td style="text-align: center; border:1px solid #eee">
                                @foreach ($do->delivery_order_items as $item)
                                            <b>{{ $item->product->product_name }}-{{ @$item->setting_product_size->setting_product_size_name }}</b>
                                            <br>[{{ @$item->delivery_order_item_quantity }}][{{ @$item->no_collect_code }}]<br>
                                    @php
                                        if(isset($total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id])){
                                            $total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id] += @$item->delivery_order_item_quantity;
                                        }else{
                                            $total_product[$item->product->product_id][$item->setting_product_size->setting_product_size_id] = @$item->delivery_order_item_quantity;
                                        }
                                        $total += @$item->delivery_order_item_quantity;
                                    @endphp
                                @endforeach
                            </td>
                            <td style="text-align: center; border:1px solid #eee">{{ $do->delivery_order_id->delivery_order_expense ?? '-' }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ $do->delivery_order_status->delivery_order_status_name }}</td>
                            <td style="text-align: center; border:1px solid #eee">{{ @$do->delivery_order_type->delivery_order_type_name }}</td>
                        </tr>
                    @endforeach
          
                <tr><td></td></tr>
                <tr style="text-align: center; border:1px solid #eee; background-color: #d8d9df;">
                    <td colspan="2" style="text-align: center; border:1px solid #eee; background-color: #d8d9df;">
                    Total By Product
                    </td>
                    
                </tr>
                @foreach($product_list as $product_id => $product_name)
                    @foreach($setting_product_size as $size_id => $size_name)
                        @if(isset($total_product[$product_id][$size_id]))
                            <tr style="border:1px solid #eee background-color: #d8d9df;">
                                <td style="text-align: center; border:1px solid #eee">{{ $product_name }}-{{ $size_name }}</td>
                                <td style="text-align: center; border:1px solid #eee">{{ $total_product[$product_id][$size_id] }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                <tr style="text-align: center; border:1px solid #eee background-color: #F4F4F6;">
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        Total:
                    </td>
                    <td style="text-align: center; border:1px solid #eee; background-color: #F4F4F6;">
                        {{ $total }}
                    </td>
                </tr>
          </tbody>
      </table>
  </div>    
</body>
</html>