
<div class="table-responsive">
    <table class="table table-bordered" id="sales_summary_company_table">
        <thead>
            <tr>
                <th style="text-align: center; font-weight: bold; background-color: #e4e4e4;" rowspan="2">Company\Product</th>
                @foreach($product as $pkey => $products)
                    <th style="text-align: center; font-weight: bold; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}" colspan="2">{{ $products['name'] }}</th>
                @endforeach
                <th style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan="2">TOTAL</th>
            </tr>
            <tr>
              @php
                $final_total_qty = 0;
                $final_total_amount = 0;
                $final_total_avg = 0;
                $final_total_qty_per = 0;
              @endphp
              @foreach($product as $pkey => $products)
                <th style="text-align: center; font-weight: bold; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Quantity (KG) </th>
                <th style="text-align: center; font-weight: bold; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Average Price (RM) </th>
              @endforeach
              <th style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">Quantity (KG) </th>
              <th style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">Average Price (RM) </th>
            </tr>
        </thead>
        <tbody>
          @foreach($company as $cid => $company_name)
          @php
            $qty_sum_com = 0;
            $amount_sum_com = 0;
            $avg_sum_com = 0;
          @endphp
            <tr>
              <td style="text-align: left; background-color: #ffffff;">{{$company_name}}</td>
              @foreach($product as $pkey => $products)
                  @if(isset($records['result2'][$cid][$products['id']]['total_company_qty']) && isset($records['result2'][$cid][$products['id']]['total_company_total']))
                    @php

                        $quantity_percentage = $records['sum_qty'] == 0 ? 0 : $records['result2'][$cid][$products['id']]['total_company_qty']/$records['sum_qty'] * 100;
                        $avg_pri = number_format($records['result2'][$cid][$products['id']]['total_company_total']/$records['result2'][$cid][$products['id']]['total_company_qty'], 2);
                        $qty_sum_com += $records['result2'][$cid][$products['id']]['total_company_qty'];
                        $amount_sum_com += $records['result2'][$cid][$products['id']]['total_company_total'];
                        $avg_sum_com += $avg_pri;
                        $qty_sum_com_per = $records['sum_qty'] == 0 ? 0 : $qty_sum_com/$records['sum_qty'] * 100;
                        if(isset($qty_sum_ps[$products['id']])){
                            $qty_sum_ps[$products['id']] += $records['result2'][$cid][$products['id']]['total_company_qty'];
                        }else{
                            $qty_sum_ps[$products['id']] = $records['result2'][$cid][$products['id']]['total_company_qty'];
                        }
                        if(isset($amount_sum_ps[$products['id']])){
                            $amount_sum_ps[$products['id']] += $records['result2'][$cid][$products['id']]['total_company_total'];
                        }else{
                            $amount_sum_ps[$products['id']] = $records['result2'][$cid][$products['id']]['total_company_total'];
                        }
                        if(isset($avg_sum_ps[$products['id']])){
                            $avg_sum_ps[$products['id']] += $records['result2'][$cid][$products['id']]['total_company_total']/$records['result2'][$cid][$products['id']]['total_company_qty'];
                        }else{
                            $avg_sum_ps[$products['id']] = $records['result2'][$cid][$products['id']]['total_company_total']/$records['result2'][$cid][$products['id']]['total_company_qty'];
                        }
                        $final_total_qty += $records['result2'][$cid][$products['id']]['total_company_qty'];
                        $final_total_amount += $records['result2'][$cid][$products['id']]['total_company_total'];
                        $final_total_avg += $avg_pri;
                        $final_total_qty_per = $records['sum_qty'] == 0 ? 0 : $final_total_qty/$records['sum_qty'] * 100;
                    @endphp
                      <td style="text-align: center; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                        {{ number_format($records['result2'][$cid][$products['id']]['total_company_qty'], 2) }}({{number_format($quantity_percentage, 2)}}%)
                      </td>
                      <td style="text-align: center; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">{{ $avg_pri }}</td>
                  @else
                  <td style="text-align: center; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> - </td>
                  <td style="text-align: center; {{ $pkey % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> - </td>
                  @endif
              @endforeach
              <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{$qty_sum_com > 0 ? $qty_sum_com."(".number_format($qty_sum_com_per, 2)."%)" : '-'}}</td>
              <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{$qty_sum_com > 0 ? number_format($amount_sum_com/$qty_sum_com, 2) : '-'}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: left; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">TOTAL</td>
                @foreach($product as $pkey => $products)
                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{ isset($qty_sum_ps[$products['id']]) ? number_format($qty_sum_ps[$products['id']], 2)."(".number_format( $records['sum_qty'] == 0 ? 0 :$qty_sum_ps[$products['id']]/$records['sum_qty']*100, 2)."%)" :'-'}}</td>
                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{isset($amount_sum_ps[$products['id']]) ? number_format($amount_sum_ps[$products['id']]/$qty_sum_ps[$products['id']], 2) :'-'}}</td>
                @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{$final_total_qty > 0 ? number_format($final_total_qty, 2).'('.number_format($records['sum_qty'] == 0 ? 0 : $final_total_qty/$records['sum_qty']*100, 2).'%)' : '-'}}</td>
                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{$final_total_amount > 0 ? number_format($final_total_amount/$final_total_qty, 2) : '-'}}</td>
            </tr>
        </tfoot>
    </table>
</div>
