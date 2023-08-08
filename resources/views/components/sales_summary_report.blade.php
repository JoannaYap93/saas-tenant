<div style="margin:auto">

    @if(@$records)
        <table class="table table-bordered">
            <thead>
                <tr style="background-color: #343a40; color: white">
                    <th></th>
                    <th>Quantity (KG)</th>
                    <th>Total (RM)</th>
                    <th>Average Price (RM)</th>
                    <th>Sales Percentage (%)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandtotal_quantity = $grandtotal_price = $grandtotal_count = $grandtotal_average_price = 0;
                @endphp
                @foreach ($records->data as $key => $data)
                    @php
                        $total_quantity = $total_prices = $total_average_price
                        = $count = $previous = $total_sales_percentage
                        = 0;
                    @endphp
                    <tr>
                        <th colspan="5" style="text-align: center; background-color: #e4e4e4">{{@$records->company_land_name[$key]}}</th>
                    </tr>
                    @foreach ($data as $row)
                        @php
                            $total_quantity += $row->quantity;
                            $total_average_price += $row->average_price;
                            $count ++;
                            $sales_percentage = @$records->total[$key] != 0 ? ($row->total_price/$records->total[$key]*100): 0;
                            $total_sales_percentage += $sales_percentage;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{$row->product_name}} - {{$row->setting_product_size_name}}</td>
                            <td style="text-align: center;">{{number_format($row->quantity,2)}}</td>
                            <td style="text-align: center;">{{number_format($row->total_price,2)}}</td>
                            <td style="text-align: center;">{{number_format($row->average_price,2)}}</td>
                            <td style="text-align: center;">{{number_format($sales_percentage,2)}}</td>
                        </tr>

                    @endforeach
                    <tr>
                        <td style="text-align: center; background-color:#343a40; color:white"><b>Subtotal</b></td>
                        <td style="text-align: center; background-color:#343a40; color:white">{{number_format($total_quantity,2)}}</td>
                        <td style="text-align: center; background-color:#343a40; color:white">{{number_format($records->total[$key],2)}}</td>
                        <td style="text-align: center; background-color:#343a40; color:white">{{number_format($total_average_price/$count,2)}}</td>
                        <td style="text-align: center; background-color:#343a40; color:white">{{number_format($total_sales_percentage,0)}}</td>
                    </tr>
                    @php
                        $grandtotal_quantity += $total_quantity;
                        $grandtotal_price += $records->total[$key];
                        $grandtotal_count += $count;
                        $grandtotal_average_price += $total_average_price;
                    @endphp
                @endforeach
                <tr>
                    <td style="text-align: center; background-color:#FFF2CC;"><b>Total</b></td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{number_format($grandtotal_quantity,2)}}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{number_format($grandtotal_price,2)}}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{number_format($grandtotal_average_price/$grandtotal_count,2)}}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">100</td>
                </tr>

            </tbody>
        </table>
    @else
        <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
