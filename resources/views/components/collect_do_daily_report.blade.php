<div class="table-responsive">
    @if (!@$component)
        <p><b>Daily Collect D.O. Report ({{ $search['start_date'] }} - {{ $search['end_date'] }})</b></p>
    @endif
    <table class="table table-bordered" id="collect_do_table">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="3">Product</th>
                @foreach ($dateRange as $d => $date)
                    @php $format_date = $date->format('d/m'); @endphp
                    <th style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan={{ count($company) * 3 }}>
                        {{ $format_date }}
                    </th>
                @endforeach
                <th colspan="3" rowspan="2" style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Total</th>
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @foreach ($dateRange as $d => $date)
                    @foreach ($company as $c_key => $c)
                        <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                            colspan="3">
                            {{ $c->company_name }}
                        </th>
                    @endforeach
                @endforeach
                @php $col_count++; @endphp
            </tr>
            <tr>
                @php $col_count = 0; @endphp
                @foreach ($dateRange as $d => $date)
                    @foreach ($company as $c)
                        <th
                            style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            Collect
                        </th>
                        <th
                            style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            D.O
                        </th>
                        <th
                            style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            Diffrential(%)
                        </th>
                        @php $col_count++; @endphp
                    @endforeach
                @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Collect(KG)</th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">D.O.(KG)</th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Differential(%)</th>
            </tr>
        </thead>
        @php
            $land_total_arr = [];
            $land_collect_sum_total = 0;
            $land_do_sum_total = 0;
        @endphp
        <tbody>
            @foreach ($products as $product)
                @php
                    $col_count = 0;
                    $product_collect_sum_total = 0;
                    $product_do_sum_total = 0;
                @endphp
            @if(isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name]) || isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name]))

                <tr>
                    <td style="background-color: #e4e4e4; border:1px solid #eee">
                        {{ $product->product_name . '-' . $product->setting_product_size_name }}</td>
                    @foreach ($dateRange as $d => $date)
                        @foreach ($company as $c)

                            @php
                                $format_date = $date->format('Y-m-d');
                                $col_temp = isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) ? $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'] : 0;
                                $do_temp = isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]) ? $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id] : 0;
                            @endphp

                            @if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']))
                                <td
                                    style="text-align: center;  {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                    {{ $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'] > 0? number_format($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'],2, '.', ''): '-' }}
                                </td>
                            @php $product_collect_sum_total += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']; @endphp
                                @if(isset($land_total_arr[$format_date][$c->company_id]['collect_sum']))
                                    @php $land_total_arr[$format_date][$c->company_id]['collect_sum'] += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']; @endphp
                                        @else
                                    @php $land_total_arr[$format_date][$c->company_id]['collect_sum'] = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']; @endphp
                                @endif
                            @else
                                <td
                                    style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                    -</td>
                            @endif

                            @if (isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]))
                                <td
                                    style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee ">
                                    {{ $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id] > 0? number_format($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id],2, '.', ''): '-' }}
                                </td>
                            @php $product_do_sum_total += $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]; @endphp
                            @if(isset($land_total_arr[$format_date][$c->company_id]['do_sum']))
                                @php $land_total_arr[$format_date][$c->company_id]['do_sum'] += $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]; @endphp
                                    @else
                                @php $land_total_arr[$format_date][$c->company_id]['do_sum'] = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]; @endphp
                            @endif
                            @else
                                <td
                                    style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee ">
                                    -</td>
                            @endif
                            @php
                                if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) && isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id])) {
                                    $collect_det = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                    $do_det = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                    $dif = $collect_det == 0 ? 0 : (($collect_det - $do_det) / $collect_det) * 100;
                                    echo '<td style = ' . ($col_count % 2 == 0 ? '"text-align: center; background-color: #ffffff; border:1px solid #eee' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' text-align: center; background-color: #f46a6a; color:#ffffff; border:1px solid #eee"' : ' text-align: center; border:1px solid #eee"') . '>' . ($dif <> 0 ? number_format($dif, 2, '.', '') : '-') . '</td>';
                                } elseif (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) && !isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id])) {
                                    $collect_det = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                    $empty = 0;
                                    $dif = (($collect_det - $empty) / $collect_det) * 100;
                                    echo '<td style = ' . ($col_count % 2 == 0 ? '"text-align: center; background-color: #ffffff; border:1px solid #eee' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' text-align: center; background-color: #f46a6a; color:#ffffff; border:1px solid #eee"' : ' text-align: center; border:1px solid #eee"') . '>' . number_format($dif, 2, '.', '') . '</td>';
                                } elseif (!isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) && isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id])) {
                                    $empty = 0;
                                    $do_det = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                    $dif = (($empty - $do_det) / $do_det) * 100;
                                    echo '<td style = ' . ($col_count % 2 == 0 ? '"text-align: center; background-color: #ffffff; border:1px solid #eee' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' text-align: center; background-color: #f46a6a; color:#ffffff; border:1px solid #eee"' : ' text-align: center; border:1px solid #eee"') . '>' . number_format($dif, 2, '.', '') . '</td>';
                                } elseif (!isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) && !isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id])) {
                                    echo '<td style = ' . ($col_count % 2 == 0 ? '"text-align: center; background-color: #ffffff; border:1px solid #eee' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' text-align: center; background-color: #f46a6a; color:#ffffff; border:1px solid #eee"' : ' text-align: center;border:1px solid #eee"') . '>-</td>';
                                }
                            @endphp
                                @php $col_count++; @endphp
                        @endforeach
                    @endforeach

                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">
                        {{ ($product_collect_sum_total > 0 ? $product_collect_sum_total : '-') }}
                    </td>
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">
                        {{ ($product_do_sum_total > 0 ? $product_do_sum_total : '-') }}
                    </td>
                        @if($product_collect_sum_total > 0 && $product_do_sum_total > 0)
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{  number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total * 100), 2, '.', '') }}
                            </td>
                         @elseif($product_collect_sum_total > 0 && $product_do_sum_total < 0)
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{  number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total * 100), 2, '.', '')  }}
                            </td>
                         @elseif($product_collect_sum_total < 0 && $product_do_sum_total > 0)
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_do_sum_total * 100), 2, '.', '') }}
                            </td>
                         @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                -
                            </td>
                        @endif

                </tr>
                    @php
                        $land_collect_sum_total += $product_collect_sum_total;
                        $land_do_sum_total += $product_do_sum_total;
                    @endphp
                @endif
            @endforeach
        </tbody>
        <tfoot>
              <tr>
              <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000"><b>Total</b></td>
                @foreach ($dateRange as $d => $date)
                @php $format_date = $date->format('Y-m-d'); @endphp
                    @foreach ($company as $c)
                        @if(isset($land_total_arr[$format_date][$c->company_id]['collect_sum']))
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            {{ ($land_total_arr[$format_date][$c->company_id]['collect_sum'] > 0 ? number_format($land_total_arr[$format_date][$c->company_id]['collect_sum'], 2, '.', '') : '-') }}
                        </td>
                         @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>
                        @endif

                        @if(isset($land_total_arr[$format_date][$c->company_id]['do_sum']))
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            {{ ($land_total_arr[$format_date][$c->company_id]['do_sum'] > 0 ? number_format($land_total_arr[$format_date][$c->company_id]['do_sum'], 2, '.', '') : '-') }}
                        </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>
                        @endif
                        @php
                            if(isset($land_total_arr[$format_date][$c->company_id]['collect_sum']) && isset($land_total_arr[$format_date][$c->company_id]['do_sum'])){
                            $land_co_arr = $land_total_arr[$format_date][$c->company_id]['collect_sum'];
                            $land_do_arr = $land_total_arr[$format_date][$c->company_id]['do_sum'];
                            $diff = ($land_co_arr - $land_do_arr) / $land_co_arr * 100;
                            $formated_diff = number_format($diff, 2, '.', '');
                            echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">'.$formated_diff.'</td>';
                            }elseif(isset($land_total_arr[$format_date][$c->company_id]['collect_sum']) && !isset($land_total_arr[$format_date][$c->company_id]['do_sum'])){
                            $land_co_arr = $land_total_arr[$format_date][$c->company_id]['collect_sum'];
                            $empty = 0;
                            $diff = ($land_co_arr - $empty) / $land_co_arr * 100;
                            $formated_diff = number_format($diff, 2, '.', '');
                            echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">'.$formated_diff.'</td>';
                            }elseif(!isset($land_total_arr[$format_date][$c->company_id]['collect_sum']) && isset($land_total_arr[$format_date][$c->company_id]['do_sum'])){
                            $land_do_arr = $land_total_arr[$format_date][$c->company_id]['do_sum'];
                            $empty = 0;
                            $diff = ($empty - $land_do_arr) / $land_do_arr * 100;
                            $formated_diff = number_format($diff, 2, '.', '');
                            echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">'.$formated_diff.'</td>';
                            }elseif(!isset($land_total_arr[$format_date][$c->company_id]['collect_sum']) && !isset($land_total_arr[$format_date][$c->company_id]['do_sum'])){
                            echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>';
                            }
                        @endphp
                    @endforeach
                @endforeach

                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">
                    {{ ($land_collect_sum_total > 0 ? number_format($land_collect_sum_total, 2, '.', '') : '-') }}
                </td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">
                    {{ ($land_do_sum_total > 0 ? number_format($land_do_sum_total, 2, '.', '') : '-') }}
                </td>
                @if($land_collect_sum_total > 0 && $land_do_sum_total > 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2, '.', '') }}
                    </td>
                        @elseif($product_collect_sum_total > 0 && $product_do_sum_total < 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2, '.', '') }}
                    </td>
                        @elseif($product_collect_sum_total < 0 && $product_do_sum_total > 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2, '.', '') }}
                    </td>
                        @else
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        -
                    </td>
                @endif
            </tr>
        </tfoot>
    </table>
</div>
