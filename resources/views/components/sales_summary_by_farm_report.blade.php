<div class="table-responsive">
    <table class="table table-bordered" id="sales_summary_table">
        <thead>
            <tr>
                <th style="font-weight: bold; background-color: #343a40; color: white">Farm </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (KG) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (%) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Amount (RM) </th>
                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Amount (%) </th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($records))
                @php
                    $total_quantity = 0;
                    $total_amount = 0;
                    $total_quantity_ratio = 0;
                    $total_amount_ratio = 0;

                    foreach($records as $row)
                    {
                        $total_quantity += $row->quantity;
                        $total_amount += $row->amount;
                    }

                    foreach($records as $row)
                    {
                        $quantity_ratio = $row->quantity/$total_quantity * 100;
                        $amount_ratio = $row->amount/$total_amount * 100;

                        echo '<tr>';
                        if(isset($component)){
                            if (auth()->user()->user_type_id == 1) {
                                echo '<td><a class="popup" href="' . route('farm_detail_report', $row->company_farm_id) . '">'. $row->company_farm_name .'</a></td>';
                            } else {
                                echo '<td>'. $row->company_farm_name .'</td>';
                            }
                        }else{
                            $link = "#";
                            echo '<td style="text-align: center; border:1px solid #eee">'. $row->company_farm_name .'</td>';
                        }
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($row->quantity, 2, '.', '') .'</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($quantity_ratio, 2, '.', '') .'</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($row->amount, 2, '.', '') .'</td>';
                        echo '<td style="text-align: center; border:1px solid #eee">'. number_format($amount_ratio, 2, '.', '') .'</td>';
                        echo '</tr>';

                        $total_quantity_ratio += $quantity_ratio;
                        $total_amount_ratio += $amount_ratio;
                    }
                @endphp
                <tr>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>Total</b></td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{ number_format($total_quantity, 2, '.', '') }}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{ number_format($total_quantity_ratio, 2, '.', '') }}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{ number_format($total_amount, 2, '.', '') }}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{ number_format($total_amount_ratio, 2, '.', '') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="5">No records found!</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
