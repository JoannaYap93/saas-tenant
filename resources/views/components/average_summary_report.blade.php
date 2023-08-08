<div class="table-responsive" style="width:100%">
    <table class="table table-bordered" id="collect_do_table">
        @php
            $col_count = $records['col_count'];
            $customer_name_list = $records['customer_name_list'];
        @endphp
        @if($col_count>0)
            <thead>
                @if (!@$component)
                    <tr>
                        <td colspan="{{3+ ($col_count*3)}}">
                            <b>Average Summary Report ({{ $search['date_from'] }} - {{ $search['date_to'] }})</b>
                        </td>
                    </tr>
                    @if(!empty($company_name))
                    <tr>
                        <td colspan="{{3 + ($col_count*3)}}">
                            Company: <b> {{$company_name}}</b>
                        </td>
                    </tr>
                    @endif
                    @if(!empty($company_land_name))
                        <tr>
                            <td colspan="{{3 + ($col_count*3)}}">
                                Land: <b> {{$company_land_name}}</b>
                            </td>
                        </tr>
                    @endif
                @endif
                <tr></tr>
                <tr>
                    <th rowspan ="4" style="border:1px solid #000; background-color:#88d6bc">Date</th>
                    <th rowspan ="4" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Durian Species</th>
                    <th rowspan ="4" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Type</th>
                    <th colspan ="{{$col_count*3}}" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Buyer/Company Durian Pricing (RM)</th>
                </tr>
                <tr>
                    @foreach ($customer_name_list as $company_name => $customer)
                        @if($customer)
                            <th colspan="{{count($customer)*3}}" style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                                {{ $company_name }}
                            </th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    @foreach ($customer_name_list as $company_name => $customer)
                        @foreach ($customer as $customer_name)
                            <th colspan="3" style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                                {{ $customer_name }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
                <tr>
                    @for ($i = 0; $i < $col_count; $i++)
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            KG
                        </th>
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            Average
                        </th>
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            Amount(RM)
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $count_date = 0;
                @endphp
                @foreach ($date_range as $d)
                    @php
                        $format_date = $d->format('Y-m-d');
                        if($count_date % 2 == 0){
                            $style_1 = "text-align:center; border:1px solid #000; background-color:#aaaaaa;";
                            $style_2 = "text-align:center; border:1px solid #000; background-color:#e4e4e4;";
                        }else{
                            $style_1 = "text-align:center; border:1px solid #000; background-color:#cfcfcf;";
                            $style_2 = "text-align:center; border:1px solid #000; background-color:#fff;";
                        }
                        $first = true;
                    @endphp
                    @foreach ($product_list['result'] as $key => $product)
                        @if(@$product['size'])
                            @foreach($product['size'] as $size_id => $size_name)
                                <tr>
                                    @if($first)
                                        <td rowspan = "{{$product_list['count']}}" style="vertical-align: center;{{$style_1}}">{{$format_date}}</td>
                                        @php
                                            $first = false;
                                        @endphp
                                    @endif
                                    <td style="{{$style_2}}">{{$product['name']}}</td>
                                    <td style="{{$style_1}}">{{$size_name}}</td>
                                    @foreach ($customer_name_list as $company_name => $customer)
                                        @php
                                            $count_customer = 0;
                                        @endphp
                                        @foreach($customer as $customer_id => $customer_name)
                                            @if(isset($records[$format_date][$product['id']][$size_id][$company_name][$customer_id]))
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_quantity,2)}}</td>
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->average_price,2)}}</td>
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_amount,2)}}</td>
                                                @php
                                                    if(isset($records['total'][$product['id']][$size_id][$company_name][$customer_id])){
                                                        $records['total'][$product['id']][$size_id][$company_name][$customer_id] += $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->average_price;
                                                        $records['total_quantity'][$product['id']][$size_id][$company_name][$customer_id] += $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_quantity;
                                                        $records['total_amount'][$product['id']][$size_id][$company_name][$customer_id] += $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_amount;
                                                        $records['count'][$product['id']][$size_id][$company_name][$customer_id] ++;
                                                    }else{
                                                        $records['total'][$product['id']][$size_id][$company_name][$customer_id] = $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->average_price;
                                                        $records['total_quantity'][$product['id']][$size_id][$company_name][$customer_id] = $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_quantity;
                                                        $records['total_amount'][$product['id']][$size_id][$company_name][$customer_id] = $records[$format_date][$product['id']][$size_id][$company_name][$customer_id]->total_amount;
                                                        $records['count'][$product['id']][$size_id][$company_name][$customer_id] = 1;
                                                    }
                                                @endphp
                                            @else
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                                <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                            @endif
                                            @php
                                                $count_customer ++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                </tr>

                            @endforeach
                        @endif
                    @endforeach
                    @php
                        $count_date++;
                    @endphp
                @endforeach
                @php
                    $count_customer = 0;
                @endphp
                <tr>
                    <td style="border: 0;">&nbsp;</td>
                </tr>

                <tr>
                    @if ($search['type'] == 'export')
                        <td></td>
                    @endif
                    <td colspan="{{2 + ($col_count*3)}}">
                        <b>All Day Summary</b>
                    </td>

                </tr>
                <tr></tr>
                <tr>
                    @if ($search['type'] == 'export')
                        <th rowspan ="4"></th>
                    @endif
                    <th rowspan ="4" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Durian Species</th>
                    <th rowspan ="4" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Type</th>
                    <th colspan ="{{$col_count*3}}" style="text-align: center; border:1px solid #000; background-color:#88d6bc">Buyer/Company Durian Pricing (RM)</th>
                </tr>
                <tr>
                    @foreach ($customer_name_list as $company_name => $customer)
                        @if($customer)
                            <th colspan="{{count($customer)*3}}" style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                                {{ $company_name }}
                            </th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    @foreach ($customer_name_list as $company_name => $customer)
                        @foreach ($customer as $customer_name)
                            <th colspan="3" style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                                {{ $customer_name }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
                <tr>
                    @for ($i = 0; $i < $col_count; $i++)
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            KG
                        </th>
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            Average
                        </th>
                        <th style="text-align: center; border:1px solid #000; background-color:#88d6bc ">
                            Amount(RM)
                        </th>
                    @endfor
                </tr>
                @foreach ($product_list['result'] as $product)
                    @if(@$product['size'])
                        @foreach($product['size'] as $size_id => $size_name)
                        <tr>
                            @if ($search['type'] == 'export')
                                <td></td>
                            @endif
                            <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{$product['name']}}</td>
                            <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{$size_name}}</td>
                            @foreach ($customer_name_list as $company_name => $customer)
                                @foreach($customer as $customer_id => $customer_name)
                                    @if(isset($records['total'][$product['id']][$size_id][$company_name][$customer_id]))
                                        @php
                                            $average = $records['total'][$product['id']][$size_id][$company_name][$customer_id] / $records['count'][$product['id']][$size_id][$company_name][$customer_id];
                                        @endphp
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($records['total_quantity'][$product['id']][$size_id][$company_name][$customer_id],2)}}</td>
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($average,2)}}</td>
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">{{number_format($records['total_amount'][$product['id']][$size_id][$company_name][$customer_id],2)}}</td>
                                    @else
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                        <td style="{!!$count_customer%2==0 ? $style_2 : $style_1!!}">-</td>
                                    @endif

                                @endforeach
                            @endforeach
                            @php
                                $count_customer ++;
                            @endphp

                        </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        @else
            <tr>
                <td>No Record Found</td>
            </tr>
        @endif
    </table>
</div>


