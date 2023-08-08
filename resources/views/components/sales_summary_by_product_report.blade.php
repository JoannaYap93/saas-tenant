<div class="table-responsive">
    <table class="table table-bordered" id="sales_summary_table">
        <thead>
            <tr>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Products </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (KG) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity By Product (%) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (%) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Total (RM) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Total By Product (RM %) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Total (RM %) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Average Price (RM) </th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($records))
                @php
                    $total_qty = 0;
                    $total_sales = 0;
                    $total_sales_percentage = 0;
                    $total_sales_percentage_by_product = [];
                    $total_sales_percentage_by_product_of_all = [];
                    $total_quality_percentage = 0;
                    $total_quantity_percentage_by_product = [];
                    $total_quantity_percentage_by_product_of_all = [];
                    $total_rounding = 0;
                    $grand_total = 0;
                    $grouped_product = [];
                    $counter = 0;

                    foreach ($records['result'] as $row)
                    {
                        $total_sales += $row->total;
                        $total_qty += $row->quantity;
                        if(isset($grouped_product[$row->product_name])){
                          $grouped_product[$row->product_name]['grouped_product_name'] = $row->product_name;
                          $grouped_product[$row->product_name]['grouped_quantity'] += $row->quantity;
                          $grouped_product[$row->product_name]['grouped_total_price'] += $row->total;
                          $grouped_product[$row->product_name]['counter'] += 1;

                        }else{
                          $grouped_product[$row->product_name]['grouped_product_name'] = $row->product_name;
                          $grouped_product[$row->product_name]['grouped_quantity'] = $row->quantity;
                          $grouped_product[$row->product_name]['grouped_total_price'] = $row->total;
                          $grouped_product[$row->product_name]['counter'] = 1;
                        }
                    }

                    foreach ($records['result'] as $key => $row)
                    {
                        $avg_price = $row->total/$row->quantity;
                        if ($total_sales == 0) {
                          $sales_percentage = 0;
                        } else {
                          $sales_percentage = $row->total/$total_sales * 100;
                        }
                        $quality_percentage = $row->quantity/$total_qty * 100;
                        $quantity_by_product = $grouped_product[$row->product_name]['grouped_quantity'] == 0 ? 0 : ($row->quantity/$grouped_product[$row->product_name]['grouped_quantity'])*100;
                        $sales_by_product = $grouped_product[$row->product_name]['grouped_total_price'] == 0 ? 0 : ($row->total/$grouped_product[$row->product_name]['grouped_total_price'])*100;

                        if(isset($total_quantity_percentage_by_product[$row->product_name])){
                          $total_quantity_percentage_by_product[$row->product_name] += $quantity_by_product;
                        }else{
                          $total_quantity_percentage_by_product[$row->product_name] = $quantity_by_product;
                        }

                        if(isset($total_quantity_percentage_by_product_of_all[$row->product_name])){
                          $total_quantity_percentage_by_product_of_all[$row->product_name] += $quality_percentage;
                        }else{
                          $total_quantity_percentage_by_product_of_all[$row->product_name] = $quality_percentage;
                        }

                        if(isset($total_sales_percentage_by_product[$row->product_name])){
                          $total_sales_percentage_by_product[$row->product_name] += $sales_by_product;
                        }else{
                          $total_sales_percentage_by_product[$row->product_name] = $sales_by_product;
                        }

                        if(isset($total_sales_percentage_by_product_of_all[$row->product_name])){
                          $total_sales_percentage_by_product_of_all[$row->product_name] += $sales_percentage;
                        }else{
                          $total_sales_percentage_by_product_of_all[$row->product_name] = $sales_percentage;
                        }

                        echo '<tr>';
                        if(isset($component)){
                            $link = route('product_detail_report');
                            $link2 = route('listing_invoice_daily', $search);
                            echo '<td style="text-align: center; border:1px solid #eee"><a class="popup" href="'.$link.'">'. $row->product_name . ' - ' . $row->setting_product_size_name .'</a></td>';
                            echo '<td style="text-align: center; border:1px solid #eee"><a href="'.$link2.'" target="_blank">'. number_format($row->quantity, 2, '.', '') .'</a></td>';

                        }else{
                            echo '<td style="text-align: center; border:1px solid #eee">'. $row->product_name . ' - ' . $row->setting_product_size_name .'</td>';
                            echo '<td style="text-align: center; border:1px solid #eee">'. number_format($row->quantity, 2, '.', '') .'</td>';
                        }
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($quantity_by_product, 2) .'%</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($quality_percentage, 2) .'%</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($row->total, 2) .'</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($sales_by_product, 2) .'%</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($sales_percentage, 2) .'%</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($avg_price, 2) .'</td>';
                        echo '</tr>';
                        if($grouped_product[$row->product_name]){
                          $counter++;
                            if($counter == $grouped_product[$row->product_name]['counter']){
                            echo '<tr>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. $grouped_product[$row->product_name]['grouped_product_name'] .'</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($grouped_product[$row->product_name]['grouped_quantity'], 2) .'</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($total_quantity_percentage_by_product[$row->product_name], 2) .'%</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($total_quantity_percentage_by_product_of_all[$row->product_name], 2) .'%</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($grouped_product[$row->product_name]['grouped_total_price'], 2) .'</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($total_sales_percentage_by_product[$row->product_name], 2) .'%</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">'. number_format($total_sales_percentage_by_product_of_all[$row->product_name], 2) .'%</td>';
                            echo '<td style="text-align: center; background-color:#fdfacb; color:#000000 border:1px solid #eee">-</td>';
                            echo '</tr>';
                            $counter -= $counter;

                          }
                        }
                        // $total_qty += $row->quantity;
                        $total_quality_percentage += $quality_percentage;
                        $total_sales_percentage += $sales_percentage;
                    }
                @endphp

        </tbody>
        <tfoot>
                <tr>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>Total</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($total_qty, 2) }}</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($total_quality_percentage, 2) }}%</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($total_sales, 2) }}</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($total_sales_percentage, 2) }}%</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                </tr>
                @php

                    $total_rounding = $records['result2']->round_up;
                    $grand_total = $total_rounding + $total_sales


                @endphp
                <tr>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>Total R.Up/Down</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($total_rounding, 2) }}</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                </tr>
                <tr>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>Grand Total</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>{{ number_format($grand_total, 2) }}</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>-</b></td>
                </tr>
            @else
                <tr>
                    <td colspan="5">No records found!</td>
                </tr>
            @endif
        </tfoot>
    </table>
</div>
