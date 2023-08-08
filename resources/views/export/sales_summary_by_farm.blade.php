<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sales Summary By Farm Report</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Sales Summary By Farm Report {{ $search['sales_from'] }} to {{ $search['sales_to'] }}</b></p>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th>Farm</th>
                    <th>Quantity (KG)</th>
                    <th>Quantity (%)</th>
                    <th>Amount (RM)</th>
                    <th>Amount (%)</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($sales_summary_details_by_farm))
                    @php
                        $total_quantity = 0;
                        $total_amount = 0;
                        $total_quantity_ratio = 0;
                        $total_amount_ratio = 0;

                        foreach($sales_summary_details_by_farm as $row)
                        {
                            $total_quantity += $row->quantity;
                            $total_amount += $row->amount;
                        }

                        foreach($sales_summary_details_by_farm as $row)
                        {
                            $quantity_ratio = $row->quantity/$total_quantity * 100;
                            $amount_ratio = $row->amount/$total_amount * 100;

                            echo '<tr>';
                            echo '<td>'. $row->company_farm_name .'</td>';
                            echo '<td>'. number_format($row->quantity, 2) .'</td>';
                            echo '<td>'. number_format($quantity_ratio, 2) .'%</td>';
                            echo '<td>'. number_format($row->amount, 2) .'</td>';
                            echo '<td>'. number_format($amount_ratio, 2) .'%</td>';
                            echo '</tr>';

                            $total_quantity_ratio += $quantity_ratio;
                            $total_amount_ratio += $amount_ratio;
                        }
                    @endphp
                    <tr>
                        <td>Grand Total</td>
                        <td>{{ number_format($total_quantity, 2) }}</td>
                        <td>{{ number_format($total_quantity_ratio, 2) }}%</td>
                        <td>{{ number_format($total_amount, 2) }}</td>
                        <td>{{ number_format($total_amount_ratio, 2) }}%</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5">No records found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>