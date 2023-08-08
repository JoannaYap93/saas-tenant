<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Differential Report</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Differential Report {{ $search['start_date'] }} to {{ $search['end_date'] }}</b></p>
        <br><br>
        <table>
            <thead>
              <tr>
                  <th style="min-width: 250px;"></th>
                  @php $col_count = 0; @endphp
                  @foreach ($date_range as $date_key => $date)
                      <th colspan="3" style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">{{ $date->format('d-m-Y') }}</th>
                      @php $col_count++; @endphp
                  @endforeach
                  <th colspan="3" style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Total</th>
              </tr>
              <tr>
                  <th style="text-align: left;">Products</th>
                  @for($i = 0; $i < count($date_range) + 1; $i++)
                      <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Collect(KG)</th>
                      <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">D.O.(KG)</th>
                      <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Differential(%)</th>
                  @endfor
              </tr>
            </thead>
            <tbody>
              @foreach($company_land as $land)
                  @php
                      $land_total_arr = array();
                      $land_collect_total = 0;
                      $land_collect_sum_total = 0;
                      $land_do_total = 0;
                      $land_do_sum_total = 0;
                      $land_sync_total = 0;
                  @endphp
                  @if($land->product_company_land->isNotEmpty())
                  <tr>
                      <td style="text-align: left;  background-color: #eeee;">
                          <b>{{ $land->company->company_name }}<br/>
                              {{ $land->company_land_name }}</b>
                      </td>
                      <td style="background-color: #eeee;" colspan="{{ (count($date_range)+ 1) * 5 }}">
                          <b>{{ $land->company->company_name }}<br/>
                              {{ $land->company_land_name }}</b>
                      </td>
                  </tr>
                  @endif
                  @foreach($product_company_land as $pkey => $product)
                      @if($land->company_land_id == $product->company_land_id)
                          @php

                              $product_collect_total = 0;
                              $product_do_total = 0;
                              $product_do_sum_total = 0;
                              $product_sync_total = 0;
                          @endphp
                          @for($p = 0; $p < count($product->product->product_size_link); $p++)
                          @php
                          $col_count = 0;
                          $product_collect_sum_total = 0;
                          $product_do_sum_total = 0;
                          @endphp
                          <tr>
                              <td style="text-align: left;">
                                  {{ $product->product->product_name }}
                                  -
                                  <span style="font-style: italic">{{ $product->product->product_size_link[$p]->setting_size->setting_product_size_name }}</span>
                              </td>

                              @foreach($date_range as $date_key => $date)
                                  @php $format_date = $date->format('Y-m-d'); @endphp
                                  @if(isset($collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum']))
                                      <td style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum'] > 0 ? number_format($collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum'], 2) : '-') }} </td>
                                      @php $product_collect_sum_total
                                       +=
                                       $collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum']; @endphp

                                      @if(isset($land_total_arr[$format_date]['collect_sum']))
                                          @php $land_total_arr[$format_date]['collect_sum'] += $collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum']; @endphp
                                      @else
                                          @php $land_total_arr[$format_date]['collect_sum'] = $collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum']; @endphp
                                      @endif
                                  @else
                                      <td style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                  @endif
                                  @if(isset($do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum']))
                                      <td style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum'] > 0 ? number_format($do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum'], 2) : '-') }} </td>
                                      @php $product_do_sum_total += $do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum']; @endphp
                                      @if(isset($land_total_arr[$format_date]['do_sum']))
                                          @php $land_total_arr[$format_date]['do_sum'] += $do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum']; @endphp
                                      @else
                                          @php $land_total_arr[$format_date]['do_sum'] = $do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum']; @endphp
                                      @endif
                                  @else
                                      <td style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                  @endif
                                  @php
                                    $co = isset($collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum']);
                                    $do = isset($do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum']);

                                    $td='';
                                    if($co && $do){
                                      $single_co = $collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum'];
                                      $single_do = $do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum'];
                                      $dif = (($single_co - $single_do) / $single_co * 100);
                                      $formated_dif = number_format($dif, 2, '.', '');
                                      $td .= '<td style=';
                                      $td .=  $date_key  % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                      $td .=  $formated_dif.'%</td>';
                                    }elseif($co && !$do){
                                      $single_co = $collect_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['collect_sum'];
                                      $empty = 0;
                                      $dif = (($single_co - $empty) / $single_co * 100);
                                      $formated_dif = number_format($dif, 2, '.', '');
                                      $td .= '<td style=';
                                      $td .=  $date_key % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                      $td .=  $formated_dif.'%</td>';
                                    }elseif(!$co && $do){
                                      $single_do = $do_details[$land->company_land_id][$product->product_id][$product->product->product_size_link[$p]->setting_size->setting_product_size_id][$format_date]['do_sum'];
                                      $empty = 0;
                                      $dif = (($empty - $single_do) / $single_do * 100);
                                      $formated_dif = number_format($dif, 2, '.', '');
                                      $td .= '<td style=';
                                      $td .=  $date_key % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                      $td .=  $formated_dif.'%</td>';
                                    }elseif(!$co && !$do){
                                      $td .= '<td style=';
                                      $td .=  $date_key % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                      $td .=  '-</td>';
                                    }
                                    echo $td;
                                  @endphp
                                  @php $col_count++; @endphp
                              @endforeach
                              <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                  {{ ($product_collect_sum_total > 0 ? number_format($product_collect_sum_total, 2) : '-') }}
                              </td>

                              <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                  {{ ($product_do_sum_total > 0 ? number_format($product_do_sum_total, 2) : '-') }}
                              </td>
                                @if($product_collect_sum_total > 0 && $product_do_sum_total > 0)
                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total * 100), 2) }}%
                                </td>
                                @elseif($product_collect_sum_total > 0 && $product_do_sum_total <= 0)
                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_collect_sum_total * 100), 2) }}%
                                </td>
                                @elseif($product_collect_sum_total <= 0 && $product_do_sum_total > 0)
                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ number_format((($product_collect_sum_total - $product_do_sum_total) / $product_do_sum_total * 100), 2) }}%
                                </td>
                                @else
                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    -
                                </td>
                                @endif

                          </tr>
                          @php
                              $land_collect_total += $product_collect_total;
                              $land_collect_sum_total += $product_collect_sum_total;
                              $land_do_total += $product_do_total;
                              $land_do_sum_total += $product_do_sum_total;
                          @endphp

                          @endfor
                      @endif
                      <!-- <tr>
                          <td style="text-align: left;">
                            This land has no products currently..
                          </td>
                      </tr> -->
                  @endforeach
                  @if($land->product_company_land->isNotEmpty())
                  <tr>
                      <td style="text-align: left; background-color: #343a40; color: white"><b>TOTAL</b></td>

                      @foreach($date_range as $date)
                          @php $format_date = $date->format('Y-m-d'); @endphp

                          @if(isset($land_total_arr[$format_date]['collect_sum']))
                              <td style="text-align: center; background-color: #343a40; color: white">
                                  {{ ($land_total_arr[$format_date]['collect_sum'] > 0 ? number_format($land_total_arr[$format_date]['collect_sum'], 2) : '-') }}
                              </td>
                          @else
                              <td style="text-align: center; background-color: #343a40; color: white">-</td>
                          @endif

                          @if(isset($land_total_arr[$format_date]['do_sum']))
                              <td style="text-align: center; background-color: #343a40; color: white">
                                  {{ ($land_total_arr[$format_date]['do_sum'] > 0 ? number_format($land_total_arr[$format_date]['do_sum'], 2) : '-') }}
                              </td>
                          @else
                              <td style="text-align: center; background-color: #343a40; color: white">-</td>
                          @endif

                          @php
                            if(isset($land_total_arr[$format_date]['collect_sum']) && isset($land_total_arr[$format_date]['do_sum'])){
                              $land_co_arr = $land_total_arr[$format_date]['collect_sum'];
                              $land_do_arr = $land_total_arr[$format_date]['do_sum'];
                              $diff = ($land_co_arr - $land_do_arr) / $land_co_arr * 100;
                              $formated_diff = number_format($diff, 2, '.', '');
                              echo '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_diff.'%</td>';
                            }elseif(isset($land_total_arr[$format_date]['collect_sum']) && !isset($land_total_arr[$format_date]['do_sum'])){
                              $land_co_arr = $land_total_arr[$format_date]['collect_sum'];
                              $empty = 0;
                              $diff = ($land_co_arr - $empty) / $land_co_arr * 100;
                              $formated_diff = number_format($diff, 2, '.', '');
                              echo '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_diff.'%</td>';
                            }elseif(!isset($land_total_arr[$format_date]['collect_sum']) && isset($land_total_arr[$format_date]['do_sum'])){
                              $land_do_arr = $land_total_arr[$format_date]['do_sum'];
                              $empty = 0;
                              $diff = ($empty - $land_do_arr) / $land_do_arr * 100;
                              $formated_diff = number_format($diff, 2, '.', '');
                              echo '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_diff.'%</td>';
                            }elseif(!isset($land_total_arr[$format_date]['collect_sum']) && !isset($land_total_arr[$format_date]['do_sum'])){
                              echo '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                            }
                          @endphp
                      @endforeach

                      <td style="text-align: center; background-color: #343a40; color: white">
                          {{ ($land_collect_sum_total > 0 ? number_format($land_collect_sum_total, 2) : '-') }}
                      </td>

                      <td style="text-align: center; background-color: #343a40; color: white">
                          {{ ($land_do_sum_total > 0 ? number_format($land_do_sum_total, 2) : '-') }}
                      </td>
                      @if($land_collect_sum_total > 0 && $land_do_sum_total > 0)
                      <td style="text-align: center; background-color: #343a40; color: white">
                          {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2) }}%
                      </td>
                      @elseif($product_collect_sum_total > 0 && $product_do_sum_total <= 0)
                      <td style="text-align: center; background-color: #343a40; color: white">
                          {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2) }}%
                      </td>
                      @elseif($product_collect_sum_total <= 0 && $product_do_sum_total > 0)
                      <td style="text-align: center; background-color: #343a40; color: white">
                          {{ number_format((($land_collect_sum_total - $land_do_sum_total) / $land_collect_sum_total * 100), 2) }}%
                      </td>
                      @else
                      <td style="text-align: center; background-color: #343a40; color: white">
                          -
                      </td>
                      @endif
                  </tr>
                  @endif
              @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
