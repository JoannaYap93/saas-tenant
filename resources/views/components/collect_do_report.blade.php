<div class="table-responsive">
    @if (!@$component)
        <p><b>Yearly Collect D.O. Report {{ $search['year'] }}</b></p>
    @endif
    <table class="table table-bordered" id="collect_do_table">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="4">Product</th>
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan={{ count($companyLand) * 3 }}>
                        {{ $month_name }}
                    </th>
                @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="3" rowspan="3">Total
                </th>
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @php
                    $col_count = 0;
                @endphp
                @foreach ($monthSel as $month_num => $month)
                    @php
                        $company_count = [];
                        $campany_name = [];
                    @endphp
                    @foreach ($companyLand as $key => $land)
                        <?php
                        if (isset($company_count[$land->company->company_id])) {
                            $company_count[$land->company->company_id] += 1;
                        } else {
                            $company_count[$land->company->company_id] = 1;
                        }

                        $campany_name[$land->company->company_id] = $land->company->company_name;

                        ?>
                    @endforeach

                    @foreach ($campany_name as $key => $name)
                        <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                            colspan={{ $company_count[$key] * 3 }}>
                            {{ $name }}
                        </th>
                    @endforeach
                @endforeach
                @php $col_count++; @endphp
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
                        <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                            colspan=3>
                            {{ $land->company_land_name }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @php $col_count = 0; @endphp
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
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
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Collect
                </th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">D.O.</th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                    Differential(%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $col_count = 0;

                $land_total_arr = [];
                $land_collect_sum_total = 0;
                $land_do_sum_total = 0;
            @endphp
            @foreach ($products as $product)
                @php
                    $product_collect_sum_total = 0;
                    $product_do_sum_total = 0;
                @endphp
                @if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name]) || isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name]))
                    <tr>
                        <td style="background-color: #e4e4e4; border:1px solid #eee">
                            {{ $product->product_name . '-' . $product->setting_product_size_name }}</td>
                        @foreach ($monthSel as $month_num => $month_name)
                            @foreach ($companyLand as $key => $land)
                                @php
                                    $col_temp = isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']) ? $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'] : 0;
                                    $do_temp = isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) ? $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] : 0;
                                @endphp

                                @if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']))
                                    <td
                                        style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                        {{ $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'] > 0 ? number_format($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'], 2, '.', '') : '-' }}
                                    </td>
                                    @php
                                        $product_collect_sum_total += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'];

                                        if (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'])) {
                                            $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'] += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'];
                                        } else {
                                            $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'] = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'];
                                        }
                                    @endphp
                                @else
                                    <td
                                        style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                        -</td>
                                @endif

                                @if (isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]))
                                    <td
                                        style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                        {{ $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] > 0 ? number_format($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name], 2, '.', '') : '-' }}
                                    </td>
                                    @php
                                        $product_do_sum_total += $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];

                                        if (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'])) {
                                            $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'] += $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                        } else {
                                            $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'] = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                        }
                                    @endphp
                                @else
                                    <td
                                        style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }} border:1px solid #eee">
                                        -</td>
                                @endif

                                @php
                                    if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']) && isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                        $collect_det = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'];
                                        $do_det = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                        $dif = (($collect_det - $do_det) / $collect_det) * 100;
                                        echo '<td style = ' . ($col_count % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : 'border:1px solid #eee"') . '>' . number_format($dif, 2, '.', '') . '</td>';
                                    } elseif (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']) && !isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                        $collect_det = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col'];
                                        $empty = 0;
                                        $dif = (($collect_det - $empty) / $collect_det) * 100;
                                        echo '<td style = ' . ($col_count % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : 'border:1px solid #eee"') . '>' . number_format($dif, 2, '.', '') . '</td>';
                                    } elseif (!isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']) && isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                        $empty = 0;
                                        $do_det = $deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                        $dif = (($empty - $do_det) / $do_det) * 100;
                                        echo '<td style = ' . ($col_count % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : 'border:1px solid #eee"') . '>' . number_format($dif, 2, '.', '') . '</td>';
                                    } elseif (!isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]['col']) && !isset($deliveryOrderItemDetails[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                        echo '<td style = ' . ($col_count % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : 'border:1px solid #eee"') . '>-</td>';
                                    }
                                @endphp
                                @php $col_count++; @endphp
                            @endforeach
                        @endforeach

                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            <b>{{ $product_collect_sum_total > 0 ? $product_collect_sum_total : '-' }}</b></td>
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            <b>{{ $product_do_sum_total > 0 ? $product_do_sum_total : '-' }}</b></td>
                        @if ($product_collect_sum_total > 0 && $product_do_sum_total > 0)
                            <td
                                style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total) * 100, 2, '.', '') }}
                            </td>
                        @elseif($product_collect_sum_total > 0 && $product_do_sum_total < 0)
                            <td
                                style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total) * 100, 2, '.', '') }}
                            </td>
                        @elseif($product_collect_sum_total < 0 && $product_do_sum_total > 0)
                            <td
                                style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_do_sum_total) * 100, 2, '.', '') }}
                            </td>
                        @else
                            <td
                                style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
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
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>TOTAL</b></td>
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
                        @if (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum']))
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                <b>{{ $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'] > 0 ? number_format($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'], 2, '.', '') : '-' }}</b>
                            </td>
                        @else
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>-</b></td>
                        @endif

                        @if (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum']))
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                <b>{{ $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'] > 0 ? number_format($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'], 2, '.', '') : '-' }}</b>
                            </td>
                        @else
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>-</b></td>
                        @endif

                        @php
                            if (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum']) && isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'])) {
                                $land_co_arr = $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'];
                                $land_do_arr = $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'];
                                $diff = (($land_co_arr - $land_do_arr) / $land_co_arr) * 100;
                                $formated_diff = number_format($diff, 2, '.', '');
                                echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">' . $formated_diff . '</td>';
                            } elseif (isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum']) && !isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'])) {
                                $land_co_arr = $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum'];
                                $empty = 0;
                                $diff = (($land_co_arr - $empty) / $land_co_arr) * 100;
                                $formated_diff = number_format($diff, 2, '.', '');
                                echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">' . $formated_diff . '</td>';
                            } elseif (!isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum']) && isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'])) {
                                $land_do_arr = $land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'];
                                $empty = 0;
                                $diff = (($empty - $land_do_arr) / $land_do_arr) * 100;
                                $formated_diff = number_format($diff, 2, '.', '');
                                echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">' . $formated_diff . '</td>';
                            } elseif (!isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['collect_sum']) && !isset($land_total_arr[$month_num][$land->company_land_name][$land->company->company_id]['do_sum'])) {
                                echo '<td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>';
                            }
                        @endphp
                    @endforeach
                @endforeach
                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                    {{ $land_collect_sum_total > 0 ? number_format($land_collect_sum_total, 2, '.', '') : '-' }}
                </td>
                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                    {{ $land_do_sum_total > 0 ? number_format($land_do_sum_total, 2, '.', '') : '-' }}
                </td>
                @if ($land_collect_sum_total > 0 && $land_do_sum_total > 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total) * 100, 2, '.', '') }}
                    </td>
                @elseif($product_collect_sum_total > 0 && $product_do_sum_total < 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total) * 100, 2, '.', '') }}
                    </td>
                @elseif($product_collect_sum_total < 0 && $product_do_sum_total > 0)
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                        {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total) * 100, 2, '.', '') }}
                    </td>
                @else
                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                        -
                    </td>
                @endif
            </tr>
        </tfoot>
    </table>
</div>
