<div class="table-responsive">
    @if(@$records)
        <table class="table table-nowrap table-bordered" id="invoice_daily_table">
            <thead>
                <tr>
                    <th style="text-align: center; background-color: #e4e4e4; font-weight: bold; border:1px solid #eee" rowspan="2">Date</th>
                    @php $col_count = 0; @endphp
                    @foreach ($product as $product_name)
                        <th class="h-auto" style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan="3">{{ $product_name }}</th>
                        @php $col_count++ @endphp
                    @endforeach
                    <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="2">Total</th>
                    <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="2">Grand Total</th>
                </tr>
                <tr>
                    @php $col_count = 0; @endphp
                    @for ($i=0; $i<count($product); $i++)
                        <th style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">KG</th>
                        <th style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">Average Price (RM)</th>
                        <th style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">RM</th>
                        @php $col_count++ @endphp
                    @endfor
                    <th style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">KG</th>
                    <th style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">RM</th>
                    <th style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">R. Up/Down</th>
                    <th style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">RM</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_rm = $total_kg = array();
                    $average_price = $total_average = array();
                    $total_kg['all'] = $total_rm['all'] = 0;
                    $total_round_up = $invoice_grandtotal = array();
                    $cal_round = 0;
                    // $cal_invoice_gt = 0;
                    $total_round_up['all'] = $invoice_grandtotal['all'] = 0;
                @endphp
                @foreach ($dateRange as $d => $date)
                    @php

                        $format_date = $date->format('Y-m-d');
                        $formated_date = $date->format('d/m');
                        $col_count = $total_kg[$format_date] = $total_rm[$format_date] = 0;

                    @endphp
                    <tr>
                        <td class="h-auto" style="text-align: center; background-color: #e4e4e4; border:1px solid #eee"><b>{{@$formated_date}}</b></td>
                        @foreach ($product as $product_name)
                            @if(@$records['result'][$format_date][$product_name] && @$records['result2'])
                                @php
                                    $average_price = 0;
                                    if($records['result'][$format_date][$product_name][0]->quantity > 0)
                                        $average_price = number_format($records['result'][$format_date][$product_name][0]->total_price/$records['result'][$format_date][$product_name][0]->quantity, 2, '.', '');
                                @endphp
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{number_format($records['result'][$format_date][$product_name][0]->quantity,2, '.', '')}}</td>
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{$average_price}}</td>
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{number_format($records['result'][$format_date][$product_name][0]->total_price,2, '.', '')}}</td>
                                @php
                                    if(isset($total_rm[$product_name])){
                                        $total_rm[$product_name] += $records['result'][$format_date][$product_name][0]->total_price;
                                        $total_kg[$product_name] += $records['result'][$format_date][$product_name][0]->quantity;
                                    }else{
                                        $total_rm[$product_name] = $records['result'][$format_date][$product_name][0]->total_price;
                                        $total_kg[$product_name] = $records['result'][$format_date][$product_name][0]->quantity;
                                    }
                                    // $total_average[$format_date] += $average_price[$format_date];
                                    $total_rm[$format_date] += $records['result'][$format_date][$product_name][0]->total_price;
                                    $total_round_up[$format_date] = $records['result2'][$format_date][0]->total_round_up;
                                    $invoice_grandtotal[$format_date] = $records['result2'][$format_date][0]->invoice_grandtotal;
                                    $total_kg[$format_date] += $records['result'][$format_date][$product_name][0]->quantity;
                                    $total_rm['all'] += $records['result'][$format_date][$product_name][0]->total_price;
                                    $total_kg['all'] += $records['result'][$format_date][$product_name][0]->quantity;
                                    $total_round_up['all'] += $total_round_up[$format_date];
                                    $invoice_grandtotal['all'] += $records['result2'][$format_date][0]->invoice_grandtotal;
                                @endphp
                            @else
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                <td style="text-align:center;{{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @php
                                    if(!isset($total_rm[$product_name])){
                                        $total_rm[$product_name] = 0;
                                        $total_kg[$product_name] = 0;
                                    }
                                @endphp
                            @endif
                            @php
                                $col_count++
                            @endphp
                        @endforeach
                        @php
                          if(isset($total_round_up[$format_date])){
                          $cal_round += $total_round_up[$format_date];
                        //   $cal_invoice_gt += $records['result2'][$format_date][0]->invoice_grandtotal;
                        }

                        @endphp
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_kg[$format_date],2, '.', '')}}</td>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_rm[$format_date],2, '.', '')}}</td>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{isset($total_round_up[$format_date]) ? number_format($total_round_up[$format_date],2, '.', '') : '0.00'}}</td>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{isset($invoice_grandtotal[$format_date]) ? number_format($invoice_grandtotal[$format_date],2, '.', '') : '0.00'}}</td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                  @php
                    $total_round_up['all'] = $cal_round;
                    $invoice_grandtotal['all'] = $total_rm['all'] + $total_round_up['all'];
                    // $invoice_grandtotal['all'] = $cal_invoice_gt;
                  @endphp
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000"><b>Total</b></td>
                    @foreach($product as $product_name)
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_kg[$product_name],2, '.', '')}}</td>
                        @if ($total_kg[$product_name] > 0)
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_rm[$product_name]/$total_kg[$product_name],2, '.', '')}}</td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">-</td>
                        @endif
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_rm[$product_name],2)}}</td>
                    @endforeach
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_kg['all'],2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_rm['all'],2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($total_round_up['all'],2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{number_format($invoice_grandtotal['all'],2, '.', '')}}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
