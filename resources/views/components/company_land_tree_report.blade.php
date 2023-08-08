<div class="table-responsive">
    @if (@$records)
        <table class="table table-nowrap table-bordered" border="1">
            <thead>
                <tr>
                    <th style="text-align: center; background-color: #e4e4e4;">No</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Company</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Land</th>
                    <th style="text-align: center; background-color: #e4e4e4;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total trees for company">Total All Tree</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Product</th>
                    <th style="text-align: center; background-color: #e4e4e4;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Total for healthy, sick, died and saw off trees">Total Tree</th>
                    <th style="text-align: center; background-color: #e4e4e4;">&lt; 12 years total</th>
                    <th style="text-align: center; background-color: #e4e4e4;"> > 12 years total</th>
                    <th style="text-align: center; background-color: #e4e4e4;"> 1 year tree</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Healthy Tree</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Sick</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Died</th>
                    <th style="text-align: center; background-color: #e4e4e4;">Saw Off</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_total_tree = 0;
                    $total_all_tree = 0;
                    $total_healthy = 0;
                    $total_sick = 0;
                    $total_died = 0;
                    $total_saw_off = 0;
                    $total_k1 = 0;
                    $total_b10 = 0;
                    $total_a10 = 0;
                    $total_lt5 = 0;
                    $total_lt10 = 0;
                    $total_lt15 = 0;
                @endphp
                @foreach ($records['data'] as $company_id => $company)
                    @php
                        $count_row = 0;
                        foreach ($records['product'][$company_id] as $company_land_product) {
                            $count_row += count($company_land_product);
                        }

                        $company_count = 0;
                        $company_land_count = 0;

                    @endphp

                    <tr>
                        <td rowspan="{{ $count_row }}" style="text-align: center"><b>{{ $loop->iteration }}</b></td>
                        <td rowspan="{{ $count_row }}" style="text-align: center">
                            @if (@$component)
                            <a class="popup" href="{{ route('company_land_tree_pointer_age_report_detail', ['company_id' => $company_id])}}">
                                {{ $records['company'][$company_id] }}
                            </a>
                            @else
                                {{ $records['company'][$company_id] }}
                            @endif
                        </td>
                        @foreach ($company as $company_land_id => $company_land)
                            @php
                                $grand_total_tree += $records['total_all_tree'][$company_id][$company_land_id];
                            @endphp
                            @if ($company_land_count == 0)
                                <td rowspan="{{ count($records['product'][$company_id][$company_land_id]) }}" style="text-align: center">
                                    @if (@$component)
                                        <a class="popup" href="{{ route('company_land_tree_report_detail', ['company_land_id' => $company_land_id])}}">
                                            {{ $records['company_land'][$company_id][$company_land_id] }}
                                        </a>
                                    @else
                                        {{ $records['company_land'][$company_id][$company_land_id] }}
                                    @endif
                                </td>
                                <td rowspan="{{ count($records['product'][$company_id][$company_land_id]) }}" style="text-align: center">
                                    {{ $records['total_all_tree'][$company_id][$company_land_id] }}</td>

                                @php
                                    $products = $records['product'][$company_id][$company_land_id];
                                @endphp

                                @foreach ($products as $product)
                                        <td style="text-align: center">{{ $product['product_name'] }}</td>
                                        <td style="text-align: center">{{ $product['no_all_tree'] }}</td>
                                        {{-- <td style="text-align: center">{{ $product['k1'] > 0 ? $product['k1'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['b10'] > 0 ? $product['b10'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['a10'] > 0 ? $product['a10'] : '-' }}</td> --}}
                                        <td style="text-align: center">{{ $product['lt10'] > 0 ? $product['lt10'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['lt15'] > 0 ? $product['lt15'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['lt5'] > 0 ? $product['lt5'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['healthy'] > 0 ? $product['healthy'] : '-' }}</td>
                                        <td style="text-align: center">
                                        <a class="popup" href="{{ route('company_land_tree_report_sick_tree_detail', ['company_land_id' => $company_land_id, 'product_id' => $product['product_id']])}}">
                                            {{ $product['sick'] > 0 ? $product['sick'] : '-' }}
                                        </a>
                                        </td>
                                        <td style="text-align: center">{{ $product['died'] > 0 ? $product['died'] : '-' }}</td>
                                        <td style="text-align: center">{{ $product['saw_off'] > 0 ? $product['saw_off'] : '-' }}</td>
                                        @php
                                            $total_all_tree += $product['no_all_tree'];
                                            $total_healthy += $product['healthy'];
                                            $total_sick += $product['sick'];
                                            $total_died += $product['died'];
                                            $total_saw_off += $product['saw_off'];
                                            $total_k1 += $product['k1'];
                                            $total_b10 += $product['b10'];
                                            $total_a10 += $product['a10'];
                                            $total_lt5 += $product['lt5'];
                                            $total_lt10 += $product['lt10'];
                                            $total_lt15 += $product['lt15'];
                                        @endphp
                                    </tr>
                                    @php
                                        break;
                                    @endphp
                                @endforeach

                                @php
                                    $product_count = 0;
                                @endphp

                                @foreach ($products as $product)
                                    @if ($product_count > 0)
                                        <tr>
                                            <td style="text-align: center">{{ $product['product_name'] }}</td>
                                            <td style="text-align: center">{{ $product['no_all_tree'] }}</td>
                                            {{-- <td style="text-align: center">{{ $product['k1'] > 0 ? $product['k1'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['b10'] > 0 ? $product['b10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['a10'] > 0 ? $product['a10'] : '-' }}</td> --}}
                                            <td style="text-align: center">{{ $product['lt10'] > 0 ? $product['lt10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt15'] > 0 ? $product['lt15'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt5'] > 0 ? $product['lt5'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['healthy'] > 0 ? $product['healthy'] : '-' }}</td>
                                            <td style="text-align: center">
                                                <a class="popup" href="{{ route('company_land_tree_report_sick_tree_detail', ['company_land_id' => $company_land_id, 'product_id' => $product['product_id']])}}">
                                                {{ $product['sick'] > 0 ? $product['sick'] : '-' }}
                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ $product['died'] > 0 ? $product['died'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['saw_off'] > 0 ? $product['saw_off'] : '-' }}</td>
                                            @php
                                                $total_all_tree += $product['no_all_tree'];
                                                $total_healthy += $product['healthy'];
                                                $total_sick += $product['sick'];
                                                $total_died += $product['died'];
                                                $total_saw_off += $product['saw_off'];
                                                $total_k1 += $product['k1'];
                                                $total_b10 += $product['b10'];
                                                $total_a10 += $product['a10'];
                                                $total_lt5 += $product['lt5'];
                                                $total_lt10 += $product['lt10'];
                                                $total_lt15 += $product['lt15'];
                                            @endphp
                                        </tr>
                                    @endif
                                    @php
                                        $product_count++;
                                    @endphp
                                @endforeach
                            @else
                                @php
                                    $products = $records['product'][$company_id][$company_land_id];
                                    $product_count = 0;
                                @endphp

                                @foreach ($products as $product)
                                    @if ( $product_count == 0)
                                        <tr>
                                            <td rowspan="{{ count($records['product'][$company_id][$company_land_id]) }}" style="text-align: center">
                                                @if (@$component)
                                                    <a class="popup" href="{{ route('company_land_tree_report_detail', ['company_land_id' => $company_land_id])}}">
                                                        {{ $records['company_land'][$company_id][$company_land_id] }}
                                                    </a>
                                                @else
                                                    {{ $records['company_land'][$company_id][$company_land_id] }}
                                                @endif
                                            </td>
                                            <td rowspan="{{ count($records['product'][$company_id][$company_land_id]) }}" style="text-align: center">
                                                {{ $records['total_all_tree'][$company_id][$company_land_id] }}</td>
                                            <td style="text-align: center">{{ $product['product_name'] }}</td>
                                            <td style="text-align: center">{{ $product['no_all_tree'] }}</td>
                                            {{-- <td style="text-align: center">{{ $product['k1'] > 0 ? $product['k1'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['b10'] > 0 ? $product['b10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['a10'] > 0 ? $product['a10'] : '-' }}</td> --}}
                                            <td style="text-align: center">{{ $product['lt10'] > 0 ? $product['lt10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt15'] > 0 ? $product['lt15'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt5'] > 0 ? $product['lt5'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['healthy'] > 0 ? $product['healthy'] : '-' }}</td>
                                            <td style="text-align: center">
                                                <a class="popup" href="{{ route('company_land_tree_report_sick_tree_detail', ['company_land_id' => $company_land_id, 'product_id' => $product['product_id']])}}">
                                                    {{ $product['sick'] > 0 ? $product['sick'] : '-' }}
                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ $product['died'] > 0 ? $product['died'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['saw_off'] > 0 ? $product['saw_off'] : '-' }}</td>
                                            @php
                                                $total_all_tree += $product['no_all_tree'];
                                                $total_healthy += $product['healthy'];
                                                $total_sick += $product['sick'];
                                                $total_died += $product['died'];
                                                $total_saw_off += $product['saw_off'];
                                                $total_k1 += $product['k1'];
                                                $total_b10 += $product['b10'];
                                                $total_a10 += $product['a10'];
                                                $total_lt5 += $product['lt5'];
                                                $total_lt10 += $product['lt10'];
                                                $total_lt15 += $product['lt15'];
                                            @endphp
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="text-align: center">{{ $product['product_name'] }}</td>
                                            <td style="text-align: center">{{ $product['no_all_tree'] }}</td>
                                            {{-- <td style="text-align: center">{{ $product['k1'] > 0 ? $product['k1'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['b10'] > 0 ? $product['b10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['a10'] > 0 ? $product['a10'] : '-' }}</td> --}}
                                            <td style="text-align: center">{{ $product['lt10'] > 0 ? $product['lt10'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt15'] > 0 ? $product['lt15'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['lt5'] > 0 ? $product['lt5'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['healthy'] > 0 ? $product['healthy'] : '-' }}</td>
                                            <td style="text-align: center">
                                                <a class="popup" href="{{ route('company_land_tree_report_sick_tree_detail', ['company_land_id' => $company_land_id, 'product_id' => $product['product_id']])}}">
                                                    {{ $product['sick'] > 0 ? $product['sick'] : '-' }}
                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ $product['died'] > 0 ? $product['died'] : '-' }}</td>
                                            <td style="text-align: center">{{ $product['saw_off'] > 0 ? $product['saw_off'] : '-' }}</td>
                                            @php
                                                $total_all_tree += $product['no_all_tree'];
                                                $total_healthy += $product['healthy'];
                                                $total_sick += $product['sick'];
                                                $total_died += $product['died'];
                                                $total_saw_off += $product['saw_off'];
                                                $total_k1 += $product['k1'];
                                                $total_b10 += $product['b10'];
                                                $total_a10 += $product['a10'];
                                                $total_lt5 += $product['lt5'];
                                                $total_lt10 += $product['lt10'];
                                                $total_lt15 += $product['lt15'];
                                            @endphp
                                        </tr>
                                    @endif

                                    @php
                                        $product_count++;
                                    @endphp
                                @endforeach
                            @endif
                            @php
                                $company_land_count++;
                            @endphp
                        @endforeach
                @endforeach
            </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">TOTAL</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $grand_total_tree }}</td>
                <td style="text-align: center; background-color:#fffbaf; color:#000000"></td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_all_tree }}</td>
                {{-- <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_k1 }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_b10 }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_a10 }}</td> --}}
                {{-- <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_lt5 }}</td> --}}
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_lt10 }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_lt15 }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_lt5 }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_healthy }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_sick }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_died }}</td>
                <td style="font-weight:bold; text-align: center; background-color:#fffbaf; color:#000000">{{ $total_saw_off }}</td>
            </tr>
        </tfoot>
    </table>
@else
    <tr>
        <td colspan="3">No Records!</td>
    </tr>
@endif
</div>
