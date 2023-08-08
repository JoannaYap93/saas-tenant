{{-- ADMIN --}}
<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Warehouse Report</b></p>
        <br><br>
    @endif
            @php
                $company_count = 0;
                $sum_total_stock = 0;
                $sum_total_sales_kg = 0;
                $sum_total_sales_rm = 0;
                $remaining = 0;
            @endphp
    <table class="table" id="company-expense-table">
            <thead>
                @foreach ($companies as $key => $company)
                <tr>
                    <th style="min-width: 220px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary" colspan="3"><b>{{ $company->company_name }}</b></th>
                </tr>
                @endforeach
            </thead>
        <tbody>

            {{-- warehouse stock --}}
            @if(count($company->setting_warehouse) > 0)
                    <tr>
                        <th style="min-width: 220px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1"><b></b></th>
                        <th style="min-width: 220px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="2"><b>Warehouse</b></th>
                    </tr>
                @foreach($company->setting_warehouse as $key => $warehouse)
                    <tr>
                        @if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                @php
                                if(isset($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id]) && $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id] > 0){
                                    $sum_total_stock += $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id];
                                }else{
                                    $sum_total_stock = $whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id];
                                }
                                @endphp
                            <td style="max-width: 50px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">
                                {{$warehouse->warehouse_name}}
                            </td>
                            <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="2">
                                {{ number_format($whStock['stock_kg'][$company->company_id][$warehouse->warehouse_id],2)}} Kg
                            </td>
                        @else
                            <td style="max-width: 50px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">
                                {{$warehouse->warehouse_name}}
                            </td>
                            <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="2">
                                -
                            </td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <th style="width: 20px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="1"><b>Total</b></th>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "2">
                        <b>{{number_format($sum_total_stock,2)}} Kg</b>
                    </td>
                </tr>

                {{-- sales --}}
                <tr>
                    <th style="min-width: 220px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1"><b></b></th>
                    <th style="min-width: 220px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="2"><b>Sales</b></th>
                </tr>
                    @foreach($company->setting_warehouse as $key => $warehouse)
                    <tr>
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
                                <td style="width: 20px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    {{$warehouse->warehouse_name}}
                                </td>
                                <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    {{ number_format($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id],2)}} Kg
                                </td>
                                <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    RM {{ number_format($whSalesRm['sales_rm'][$company->company_id][$warehouse->warehouse_id],2)}}
                                </td>
                        @else
                            <td style="width: 20px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                {{$warehouse->warehouse_name}}
                            </td>
                            <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                -
                            </td>
                            <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                -
                            </td>
                        @endif
                    </tr>
                    @endforeach
                <tr>
                    <th style="width: 20px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total Sales</b></th>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                        <b>{{number_format($sum_total_sales_kg,2)}} Kg</b>
                    </td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                        <b>RM {{number_format($sum_total_sales_rm,2)}}</b>
                    </td>
                </tr>

                {{-- remaining --}}
                <tr>
                    <th style="width: 20px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="1"><b></b></th>
                    <th style="width: 20px; text-align: center; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="2"><b>Total Remaining</b></th>
                </tr>
                    @foreach($company->setting_warehouse as $key => $warehouse)
                        <tr>
                            @if(isset($whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id]) && $whSalesKg['sales_kg'][$company->company_id][$warehouse->warehouse_id] > 0)
                                    <td style="width: 20px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                        {{$warehouse->warehouse_name}}
                                    </td>
                                @php
                                $remaining = $sum_total_stock - $sum_total_sales_kg;
                                @endphp
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                        {{ number_format($remaining,2) }} Kg
                                    </td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                        RM {{ number_format($sum_total_sales_rm,2) }}
                                    </td>
                            @else
                                <td style="width: 20px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    {{$warehouse->warehouse_name}}
                                </td>
                                <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    -
                                </td>
                                <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                    -
                                </td>
                            @endif
                        </tr>
                    @endforeach

                {{-- total wh --}}
                    <tr>
                        <th style="width: 20px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total WH(Kg)</b></th>
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "2">
                            <b>{{number_format($sum_total_stock,2)}}</b>
                        </td>
                    </tr>
                {{-- total sales --}}
                    <tr>
                        <th style="width: 20px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total Sales(Kg)</b></th>
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "2">
                            <b>{{number_format($sum_total_sales_kg,2)}}</b>
                        </td>
                    </tr>
                {{-- total remaining --}}
                    <tr>
                        <th style="width: 20px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total Remaining(Kg)</b></th>
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "2">
                            <b>{{number_format($remaining,2)}}</b>
                        </td>
                    </tr>
            @else
                <tr>
                    <td colspan="5">No Records!</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

