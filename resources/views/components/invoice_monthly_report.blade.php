<div class="table-responsive">
    @if(!@$component)
        <p><b>Monthly Invoice Report {{ $search['year'] }}</b></p>
    @endif
    <table class="table table-bordered" id="collect_do_table">
        <thead>
            <tr>
                <th style="min-width: 220px;" class="table-secondary" rowspan="2" colspan="2">Product</th>
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"
                        colspan="2">
                        {{ $month_name }}
                    </th>
                @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000" colspan="2">Total</th>
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @foreach ($monthSel as $month_num => $month_name)
                <th style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                    KG
                </th>
                <th style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                    RM
                </th>
            @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000">
                    Total KG
                </th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000">
                    Total RM
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_product_rm = $total_product_kg = array();
                $total_month_rm = $total_month_kg = array();
                $final_total_kg = $final_total_rm = 0;
                $cal_round = $cal_invoice_gt = 0;
                $final_round_up = $final_invoice_grandtotal = 0;
                $total_round_up = $invoice_grandtotal = array();
            @endphp
            @foreach($products as $product)
                    <tr>

                        <td class="h-auto" style="text-align: center; background-color: #e4e4e4;" colspan="2"><b>{{$product}}</b></td>
                            @foreach ($monthSel as $month_num => $month_name)
                                @if(isset($invoice['result'][$product][$month_num]['qty']) || isset($invoice['result'][$product][$month_num]['price']))
                                    <td style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} "><a href="{{ route('invoice_daily_report',['year' => $search['year'],'month' => $month_num, 'company_id' =>  $search['company_id'], 'land_id' =>  $search['company_land_id'],'user_id' =>  $search['user_id' ]]) }}" target="_blank"> {{ ($invoice['result'][$product][$month_num]['qty'] > 0 ? number_format($invoice['result'][$product][$month_num]['qty'], 2, '.', '') : '-') }}</a></td>
                                    <td style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} "><a href="{{ route('invoice_daily_report',['year' => $search['year'],'month' => $month_num, 'company_id' =>  $search['company_id'], 'land_id' =>  $search['company_land_id'],'user_id' =>  $search['user_id' ]]) }}" target="_blank"> {{ ($invoice['result'][$product][$month_num]['price'] ? number_format($invoice['result'][$product][$month_num]['price'], 2, '.', '') : '-') }}</a></td>

                                    @php
                                        if (isset($invoice['result'][$product][$month_num])) {
                                            if (isset($total_product_rm[$product])) {
                                                $total_product_rm[$product] += $invoice['result'][$product][$month_num]['price'];
                                                $total_product_kg[$product] += $invoice['result'][$product][$month_num]['qty'];
                                            } else {
                                                $total_product_rm[$product] = $invoice['result'][$product][$month_num]['price'];
                                                $total_product_kg[$product] = $invoice['result'][$product][$month_num]['qty'];
                                            }

                                            if (isset($total_month_rm[$month_num])) {
                                                $total_month_rm[$month_num] += $invoice['result'][$product][$month_num]['price'];
                                                $total_month_kg[$month_num] += $invoice['result'][$product][$month_num]['qty'];
                                            } else {
                                                $total_month_rm[$month_num] = $invoice['result'][$product][$month_num]['price'];
                                                $total_month_kg[$month_num] = $invoice['result'][$product][$month_num]['qty'];
                                            }

                                            $final_total_rm += $invoice['result'][$product][$month_num]['price'];
                                            $final_total_kg += $invoice['result'][$product][$month_num]['qty'];

                                            if (isset($invoice['result2'][$month_num]['total_round_up'])) {
                                                $total_round_up[$month_num] = $invoice['result2'][$month_num]['total_round_up'];
                                            }
                                            if (isset($invoice['result2'][$month_num]['invoice_grandtotal'])) {
                                                $invoice_grandtotal[$month_num] = $invoice['result2'][$month_num]['invoice_grandtotal'];
                                            }
                                        }
                                    @endphp

                                @else
                                    <td style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} ">-</td>
                                    <td style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} ">-</td>
                                    @php
                                        if(!isset($total_product_rm[$product])){
                                            $total_product_rm[$product] = 0;
                                            $total_product_kg[$product] = 0;
                                        }

                                        if(!isset($total_month_rm[$month_num])){
                                            $total_month_rm[$month_num] = 0;
                                            $total_month_kg[$month_num] = 0;
                                        }

                                        if(!isset($total_round_up[$month_num])){
                                            $total_round_up[$month_num] = 0;
                                        }

                                        if(!isset($invoice_grandtotal[$month_num])){
                                            $invoice_grandtotal[$month_num] = 0;
                                        }
                                    @endphp

                                @endif
                            @endforeach
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_product_kg[$product],2, '.', '')}}</td>
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_product_rm[$product],2, '.', '')}}</td>
                    </tr>
            @endforeach
        </tbody>
        <tfoot>
              <tr>
                <td style="text-align: center; background-color:#fffbaf; color:#000000" colspan="2"><b>Total</b></td>
                @foreach($monthSel as $month_num => $month_name)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_month_kg[$month_num],2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_month_rm[$month_num],2, '.', '')}}</td>
                @endforeach

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($final_total_kg,2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($final_total_rm,2, '.', '')}}</td>

            </tr>
            <tr>
                <td style="text-align: center; background-color:#fffbaf; color:#000000" colspan="2"><b>R. Up/Down</b></td>
                @foreach($monthSel as $month_num => $month_name)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_round_up[$month_num],2, '.', '')}}</td>
                  @php
                    if(isset($total_round_up[$month_num])){
                      $final_round_up += $total_round_up[$month_num];
                    }
                  @endphp
                @endforeach

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($final_round_up,2, '.', '')}}</td>
            </tr>
            <tr>
                <td style="text-align: center; background-color:#fffbaf; color:#000000" colspan="2"><b>Grand Total</b></td>
                @foreach($monthSel as $month_num => $month_name)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($total_month_kg[$month_num],2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($invoice_grandtotal[$month_num],2, '.', '')}}</td>
                  @php
                    if(isset($invoice_grandtotal[$month_num])){
                      $final_invoice_grandtotal += $invoice_grandtotal[$month_num];
                    }
                  @endphp
                @endforeach

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($final_total_kg,2, '.', '')}}</td>
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{number_format($final_invoice_grandtotal,2, '.', '')}}</td>
            </tr>
        </tfoot>
    </table>
</div>
