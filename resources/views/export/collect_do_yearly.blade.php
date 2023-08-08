<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Yearly Collect D.O. Report {{ $search['year'] }}</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Yearly Collect D.O. Report {{ $search['year'] }}</b></p>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th style="background-color: #d8d9df;"></th>
                    @foreach ($month_sel as $month_num => $month_name)
                        <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}" colspan= {{ count($company_land)*3}}>
                            {{ $month_name }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th style="background-color: #d8d9df;"></th>
                    @php
                      $col_count = 0;
                    @endphp
                    @foreach ($month_sel as $month_num => $month)
                      @php
                        $company_count = array();
                        $campany_name = array();
                      @endphp
                      @foreach($company_land as $key => $land)
                            <?php
                              if(isset($company_count[$land->company->company_id])){
                                  $company_count[$land->company->company_id] += 1;
                              }else{
                                $company_count[$land->company->company_id] = 1;
                              }

                              $campany_name[$land->company->company_id] = $land->company->company_name;

                            ?>
                      @endforeach

                      @foreach($campany_name as $key => $name)
                            <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}" colspan={{$company_count[$key]*3}}>
                                {{$name}}
                            </th>

                      @endforeach

                    @endforeach
                    @php $col_count++; @endphp
                </tr>
                <tr>
                    <th style="background-color: #d8d9df;"></th>
                    @php $col_count = 0; @endphp
                    @foreach ($month_sel as $month_num => $month)
                        @foreach ($company_land as $key => $land)
                            <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}" colspan = 3>
                                {{ $land->company_land_name }}
                            </th>
                            @php $col_count++; @endphp
                        @endforeach
                    @endforeach
                </tr>
                <tr>
                    <th style="text-align: center; background-color: #d8d9df;">Product</th>
                    @php $col_count = 0; @endphp
                    @foreach ($month_sel as $month_num => $month)
                        @foreach ($company_land as $key => $land)
                            <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Collect</th>
                            <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">D.O</th>
                            <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Diffrential(%)</th>
                            @php $col_count++; @endphp
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $col_count = 0; @endphp
                @foreach($products as $product)
                    <tr>
                        <td style="background-color: #e4e4e4;">{{ $product->product_name . '-' . $product->setting_product_size_name }}</td>
                        @foreach($month_sel as $month_num => $month_name)
                            @foreach($company_land as $key => $land)
                                @php
                                    $col_temp = isset($collect_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name]) ? $collect_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name] : 0;
                                    $do_temp = isset($delivery_order_item_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name]) ? $delivery_order_item_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name] : 0;
                                @endphp

                                @if(isset($collect_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name]))
                                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }}"> {{ ($collect_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name] > 0 ? number_format($collect_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name], 2) : '-') }}</td>
                                @else
                                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }}">-</td>
                                @endif

                                @if(isset($delivery_order_item_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name]))
                                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }}">{{ ($delivery_order_item_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name] > 0 ? number_format($delivery_order_item_details[$product->product_name.'-'.$product->setting_product_size_name][$month_num][$land->company_land_name], 2) : '-') }}</td>
                                @else
                                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} {{ $col_temp != $do_temp ? 'background-color: #f46a6a; color:#ffffff;' : '' }}">-</td>
                                @endif
                              @php
                                if(isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                    $collect_det = $collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                    $do_det = $delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                    $dif = ($collect_det - $do_det) / $collect_det * 100;
                                    echo '<td style = ' . ($month_num % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                                }elseif(isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && !isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                    $collect_det = $collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                    $empty = 0;
                                    $dif = ($collect_det - $empty) / $collect_det * 100;
                                    echo '<td style = ' . ($month_num % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                                }elseif(!isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                    $empty = 0;
                                    $do_det = $delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                    $dif = ($empty - $do_det) / $do_det * 100;
                                    echo '<td style = ' . ($month_num % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                                }elseif(!isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && !isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                    echo '<td style = ' . ($month_num % 2 == 0 ? '"background-color: #ffffff;' : '"background-color: #e4e4e4;') . ($col_temp != $do_temp ? ' background-color: #f46a6a; color:#ffffff;"' : '"') . '>-</td>';
                                }
                                @endphp
                                @php $col_count++; @endphp
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
