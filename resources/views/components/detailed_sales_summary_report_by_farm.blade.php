<div style="margin:auto">
    @if(@$records)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="background-color: #343a40; color: white"></th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (KG)</th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Quantity (%)</th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Amount (RM)</th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Amount (%)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $land_subtotal = array();
                    $total_quantity = 0;
                    $total_amount = 0;
                    $total_quantity_ratio = 0;
                    $total_amount_ratio = 0;
                @endphp

                @foreach ($land as $company_land)
                    @if (isset($company[$company_land->company_land_name]))
                        @foreach ($company[$company_land->company_land_name] as $company_id => $company_name)
                            @if(isset($records[$company_land->company_land_name][$company_id]))
                                @php
                                    if(isset($land_subtotal[$company_land->company_land_name])){
                                        $land_subtotal[$company_land->company_land_name]['qty'] += $records[$company_land->company_land_name][$company_id]['quantity'];
                                        $land_subtotal[$company_land->company_land_name]['amt'] += $records[$company_land->company_land_name][$company_id]['amount'];
                                    }else{
                                        $land_subtotal[$company_land->company_land_name]['qty'] = $records[$company_land->company_land_name][$company_id]['quantity'];
                                        $land_subtotal[$company_land->company_land_name]['amt'] = $records[$company_land->company_land_name][$company_id]['amount'];
                                    }
                                @endphp
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @foreach ($land as $company_land)
                    @if (isset($company[$company_land->company_land_name]))
                        @php
                            $land_subtotal_qty_ratio = 0;
                            $land_subtotal_amt_ratio = 0;
                        @endphp

                        <tr><td colspan="5" style="background-color: #e4e4e4; font-weight: bold;">{{ $company_land->company_land_name }}</td></tr>

                        @foreach ($company[$company_land->company_land_name] as $company_id => $company_name)
                            <tr>
                                <td>{{ $company_name }}</td>
                            @if(isset($records[$company_land->company_land_name][$company_id]))
                                @php
                                    $company_qty_ratio = $records[$company_land->company_land_name][$company_id]['quantity'] / $land_subtotal[$company_land->company_land_name]['qty'] * 100;
                                    $company_amt_ratio = $records[$company_land->company_land_name][$company_id]['amount'] / ($land_subtotal[$company_land->company_land_name]['amt'] > 0 ? $land_subtotal[$company_land->company_land_name]['amt'] : 1) * 100;
                                @endphp
                                    <td>
                                        {{ ($records[$company_land->company_land_name][$company_id]['quantity'] > 0 ? number_format(@$records[$company_land->company_land_name][$company_id]['quantity'], 2) : '-') }}
                                    </td>
                                    <td>
                                        {{ ($company_qty_ratio > 0 ? number_format($company_qty_ratio, 2) : '-') }}
                                     </td>
                                     <td>
                                        {{ ($records[$company_land->company_land_name][$company_id]['amount'] > 0 ? number_format(@$records[$company_land->company_land_name][$company_id]['amount'], 2) : '-') }}
                                     </td>
                                     <td>
                                        {{ ($company_amt_ratio > 0 ? number_format($company_amt_ratio, 2) : '-') }}
                                     </td>
                                </tr>
                                @php
                                    $land_subtotal_qty_ratio += $company_qty_ratio;
                                    $land_subtotal_amt_ratio += $company_amt_ratio;
                                @endphp
                            @else
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            @endif
                        @endforeach
                        <tr>
                            <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white">Subtotal</td>
                            <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white">
                                {{ (@$land_subtotal[$company_land->company_land_name]['qty'] > 0 ? number_format($land_subtotal[$company_land->company_land_name]['qty'], 2) : '-') }}
                            </td>
                            <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white">
                                {{ (@$land_subtotal_qty_ratio > 0 ? number_format($land_subtotal_qty_ratio, 2) : '-') }}
                            </td>
                            <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white">
                                {{ (@$land_subtotal[$company_land->company_land_name]['amt'] > 0 ? number_format($land_subtotal[$company_land->company_land_name]['amt'], 2) : '-') }}
                            </td>
                            <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white">
                                {{ ( @$land_subtotal_amt_ratio > 0 ? number_format($land_subtotal_amt_ratio, 2) : '-') }}
                            </td>
                        </tr>

                        @php
                            $total_quantity += @$land_subtotal[$company_land->company_land_name]['qty'];
                            $total_quantity_ratio += @$land_subtotal_qty_ratio;
                            $total_amount += @$land_subtotal[$company_land->company_land_name]['amt'];
                            $total_amount_ratio += @$land_subtotal_amt_ratio;
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td style="text-align: center; background-color:#FFF2CC;"><b>Total</b></td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{ number_format($total_quantity, 2) }}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{ number_format($total_quantity_ratio, 2) }}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{ number_format($total_amount, 2) }}</td>
                    <td style="text-align: center; background-color:#FFF2CC;">{{ number_format($total_amount_ratio, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
