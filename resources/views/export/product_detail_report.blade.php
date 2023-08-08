<style>
    .bg-dark-report{
        background-color: #343a40!important;
        color: white !important
    }
    .bg-grey-report{
        background-color: lightgrey !important
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18"><span class="mr-2 ">Sales Summary Report</span></h4>
                </div>
                @if(@$records)
                    <table class="table table-bordered">
                        <thead>
                            {{-- <tr>
                                <th colspan="3">{{$template->message_template_name}}</th>
                            </tr> --}}
                            <tr>
                                <th></th>
                                <th>Quantity (KG)</th>
                                <th>Total (RM)</th>
                                <th>Average Price (RM)</th>
                                <th>Sales Percentage (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records->data as $key => $data)
                                @php
                                    $total_quantity = $total_prices = $total_average_price = $count = $previous = $total_sales_percentage = 0;
                                @endphp
                                <tr>
                                    <th class = "bg-grey-report" colspan="5" >{{@$records->company_land_name[$key]}}</th>
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
                                        <td>{{$row->product_name}} - {{$row->setting_product_size_name}}</td>
                                        <td>{{number_format($row->quantity,2)}}</td>
                                        <td>{{number_format($row->total_price,2)}}</td>
                                        <td>{{number_format($row->average_price,2)}}</td>
                                        <td>{{number_format($sales_percentage,2)}}</td>
                                    </tr>

                                @endforeach
                                <tr class = "bg-dark-report">
                                    <td><b>Subtotal</b></td>
                                    <td>{{number_format($total_quantity,2)}}</td>
                                    <td>{{number_format($records->total[$key],2)}}</td>
                                    <td>{{number_format($total_average_price/$count,2)}}</td>
                                    <td>{{number_format($total_sales_percentage,0)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <tr><td colspan="3">No Records!</td></tr>
                @endif
            </div>
        </div>
    </div>
</div>
