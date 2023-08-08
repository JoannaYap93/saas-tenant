{{-- SUPERADMIN & ADMIN --}}
<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Warehouse Report</b></p>
        <br><br>
    @endif
        @php
            $sum_total_stock = 0;
            $sum_total_sales_kg = 0;
            $sum_total_sales_rm = 0;
            $remaining = 0;
            $remaining_rm  = 0;
            $row_count = 0;
            $count_warehouse = $wareHouse = array();
        @endphp
        <table class="table" id="company-expense-table">
            <thead>
                <tr>
                    <th style="min-width: 220px; background-color: #343a40; color: white; border:1px solid #eee;" rowspan="2" colspan="2"> COMPANY </th>
                    <th style="min-width: 220px; background-color: #343a40; color: white; border:1px solid #eee;" rowspan="2" colspan="2"> Warehouse </th>
                    <th style="background-color: #d8d9df; border:1px solid #eee;" colspan="1">Warehouse Stock</th>
                    <th style="background-color: #CCCCFF; border:1px solid #eee;" colspan="2">Sales</th>
                    <th style="background-color: #d8d9df; border:1px solid #eee;" colspan="2">Remaining</th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;" colspan="1"> Total WH </th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;" colspan="1"> Total Sales </th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;" colspan="1"> Total Remaining </th>
                </tr>
                <tr>
                    <th style="background-color: #d8d9df; border:1px solid #eee;">Kg</th>
                    <th style="background-color: #CCCCFF; border:1px solid #eee;">Kg</th>
                    <th style="background-color: #CCCCFF; border:1px solid #eee;">RM</th>
                    <th style="background-color: #d8d9df; border:1px solid #eee;">Kg</th>
                    <th style="background-color: #d8d9df; border:1px solid #eee;">RM</th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;">(Kg)</th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;">(Kg)</th>
                    <th style="background-color: #fffbaf; border:1px solid #eee;">(Kg)</th>
                </tr>
            </thead>
        @if(!empty($whStock['stock_kg']))
            <tbody>
                    @foreach ($warehouses as $key => $wh)
                        @php
                            if(isset($count_warehouse[$wh->company_id])){
                                $count_warehouse[$wh->company_id] += 1;
                            }
                            else {
                                $count_warehouse[$wh->company_id] = 1;
                            }
                        @endphp
                    @endforeach
                @foreach ($companies as $key => $company)
                    @php
                        $warehouse_index = 0;
                    @endphp
                        @if(count($company->setting_warehouse) > 0)
                    <tr>

                                <th style="max-width: 50px; text-align: center; background-color: #ffffff; border:1px solid #eee" colspan="2" rowspan={{$count_warehouse[$company->company_id] ?? 1 }}>
                                    {{ $company->company_name }}
                                </th>
                        @foreach($company->setting_warehouse as $key => $warehouse)
                            @if ($warehouse_index > 0)
                                <tr>
                            @endif
                                @if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                    <td style="max-width: 50px; text-align: center; background-color: #ffffff; border:1px solid #eee" colspan="2">
                                        {{$warehouse->warehouse_name}}
                                    </td>
                                @else
                                    <td style="max-width: 50px; text-align: center; background-color: #ffffff; border:1px solid #eee" colspan="2">
                                        {{$warehouse->warehouse_name}}
                                    </td>
                                @endif

                            {{-- stock --}}
                                @if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                        @php
                                        if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0){
                                            $sum_total_stock += $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id];
                                        }else{
                                            $sum_total_stock = $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id];
                                        }
                                        @endphp
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id],2)}}
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif

                            {{-- sales kg & rm --}}
                                @if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                        @php
                                        if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0){
                                            $sum_total_sales_kg += $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id];
                                        }else{
                                            $sum_total_sales_kg = $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id];
                                        }
                                        if(isset($whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id]) && $whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id] > 0){
                                            $sum_total_sales_rm += $whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id];
                                        }else{
                                            $sum_total_sales_rm = $whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id];
                                        }
                                        @endphp
                                    <td style="text-align: center; background-color: #CCCCFF; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id],2)}}
                                    </td>
                                    <td style="text-align: center; background-color: #CCCCFF; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id],2)}}
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #CCCCFF; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                    <td style="text-align: center; background-color: #CCCCFF; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif

                            {{-- remaining --}}
                                @if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                        @php
                                        $remaining = $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] - $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id];
                                        $remaining_rm = $whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id] - $whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id];
                                        @endphp
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($remaining,2) }}
                                    </td>
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($remaining_rm,2) }}
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                    <td style="text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif

                            {{-- total stock kg --}}
                                @if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        <b>{{ number_format($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id],2)}}</b>
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif

                            {{-- total sales kg --}}
                                @if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        <b>{{ number_format($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id],2)}}</b>
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif

                            {{-- total remaining --}}
                                @if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        <b>{{number_format($remaining,2)}}</b>
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #fffbaf; color:#000000; border:1px solid #eee" colspan="1">
                                        -
                                    </td>
                                @endif
                    </tr>
                                @php
                                    $warehouse_index++;
                                @endphp
                            @endforeach
                        @endif
                    @php
                        $row_count++;
                    @endphp
                @endforeach
            </tbody>
        @else
            <tr>
                <td style="text-align: center; background-color: #fffff; color:#000000; border:1px solid #eee" colspan="12">
                    No records !
                </td>
            </tr>
        @endif
    </table>
</div>

