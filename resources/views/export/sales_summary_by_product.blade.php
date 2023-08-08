<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sales Summary By Product Report</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Sales Summary By Product Report {{ $search['sales_from'] }} to {{ $search['sales_to'] }}</b></p>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th>Products</th>
                    <th>Quantity (KG)</th>
                    <th>Total (RM)</th>
                    <th>Average Price (RM)</th>
                    <th>Sales Percentage (%)</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($sales_summary_details_by_product))
                    @php
                        $total_qty = 0;
                        $total_sales = 0;
                        $total_sales_percentage = 0;

                        foreach ($sales_summary_details_by_product as $row)
                        {
                            $total_sales += $row->total;
                        }
                            
                        foreach ($sales_summary_details_by_product as $row)
                        {
                            $avg_price = $row->total/$row->quantity;
                            $sales_percentage = $row->total/$total_sales * 100;

                            echo '<tr>';
                            echo '<td>'. $row->product_name . ' - ' . $row->setting_product_size_name .'</td>';
                            echo '<td>'. number_format($row->quantity, 2) .'</td>';
                            echo '<td>'. number_format($row->total, 2) .'</td>';
                            echo '<td>'. number_format($avg_price, 2) .'</td>';
                            echo '<td>'. number_format($sales_percentage, 2) .'%</td>';
                            echo '</tr>';

                            $total_qty += $row->quantity;
                            $total_sales_percentage += $sales_percentage;
                        }
                    @endphp
                    <tr>
                        <td>Total</td>
                        <td>{{ number_format($total_qty, 2) }}</td>
                        <td>{{ number_format($total_sales, 2) }}</td>
                        <td>-</td>
                        <td>{{ number_format($total_sales_percentage, 2) }}%</td>
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