<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Forecast Report</b></p>
        <br><br>
    @endif

    @if(!empty($companyPnlItem) && !empty($setting) && !empty($forecastReportResult) && !empty($avgprice))
      <table class="table" id="tree-target-report-table">
        <thead>
            <tr>
                <th style="min-width: 250px; text-align:left; background-color: #d8d9df; border:1px solid #eee; height: 65px; vertical-align: middle;" ><b>Pnl Item</b></th>
                @php
                  $loop_year = $setting['forecast_report_default_year'];
                @endphp
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  @php
                    if($i > 1){
                      $loop_year += 1;
                    }
                  @endphp
                  <th style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee; vertical-align: middle;">
                      {{ $loop_year }}
                  </th>
                @endfor
            </tr>
        </thead>
        <tbody>
          @php
            $first_counter = 0;
            $total_tree_a1_to_a4 = [];
            $total_tree_a1_to_a5 = [];
            $total_tree_a1_to_a4_2 = [];
            $total_tree_a1_to_a5_2 = [];
            $total_tree_for_a4 = [];
            $total_tree_for_a5 = [];
            $total_income_kg = [];
            $total_income_rm = [];
            $each_pnl_value = [];
          @endphp
          <tr>
            <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee; height: 60px; vertical-align: middle;" colspan="{{ $setting['forecast_report_year_count'] + 1 }}"><b>Trees</b>
            </td>
          </tr>
          @foreach($companyPnlItem as $pnlkey => $company_pnl_item)
            @php
              $first_counter++;
              $loop_year = $setting['forecast_report_default_year'];
              $counter_null = 0;
            @endphp
            <tr>
              <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
                {{$company_pnl_item->pnl_name}}({{ $company_pnl_item->pnl_code }})
              </td>
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                  $tree_count = 0;
                @endphp
                <td style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    @php
                      if(isset($forecastReportResult[$company_pnl_item->pnl_code])){
                        foreach($forecastReportResult[$company_pnl_item->pnl_code] as $year => $value){
                          if($year <= $loop_year){
                            $tree_count += $value['tree_count'];
                            if($first_counter == 4){
                              if(isset($total_tree_for_a4[$loop_year])){
                                $total_tree_for_a4[$loop_year] = $tree_count * $setting['effective_capacity'];
                              }else{  
                                $total_tree_for_a4[$loop_year] = $tree_count * $setting['effective_capacity'];
                              }
                            }elseif($first_counter == 5){
                              if(isset($total_tree_for_a5[$loop_year])){
                                $total_tree_for_a5[$loop_year] = $tree_count * $setting['effective_capacity'];
                              }else{
                                $total_tree_for_a5[$loop_year] = $tree_count * $setting['effective_capacity'];
                              }
                            }
                            if($first_counter < 5){
                              if(isset($total_tree_a1_to_a4[$loop_year])){
                                $total_tree_a1_to_a4[$loop_year] += $value['tree_count'];
                              }else{
                                $total_tree_a1_to_a4[$loop_year] = $value['tree_count'];
                              }
                            }elseif($first_counter == 5){
                              if(isset($total_tree_a1_to_a5[$loop_year])){
                                $total_tree_a1_to_a5[$loop_year] += $total_tree_a1_to_a4[$loop_year] + $value['tree_count'];
                              }else{
                                $total_tree_a1_to_a5[$loop_year] = $total_tree_a1_to_a4[$loop_year] + $value['tree_count'];
                              }
                            }
                            if($first_counter <= 3){
                              if(isset($total_tree_a1_to_a4_2[$loop_year])){
                                $total_tree_a1_to_a4_2[$loop_year] += $value['tree_count'];
                              }else{
                                $total_tree_a1_to_a4_2[$loop_year] = $value['tree_count'];
                              }
                            }elseif($first_counter == 4){
                              if(isset($total_tree_a1_to_a4_2[$loop_year])){
                                $total_tree_a1_to_a4_2[$loop_year] += $total_tree_for_a4[$loop_year];
                              }else{
                                $total_tree_a1_to_a4_2[$loop_year] = $total_tree_for_a4[$loop_year];
                              }
                            }elseif($first_counter == 5){
                              if(isset($total_tree_a1_to_a5_2[$loop_year])){
                                $total_tree_a1_to_a5_2[$loop_year] += $total_tree_a1_to_a4_2[$loop_year] + round($total_tree_for_a5[$loop_year], 0);
                              }else{
                                $total_tree_a1_to_a5_2[$loop_year] = $total_tree_a1_to_a4_2[$loop_year] + round($total_tree_for_a5[$loop_year], 0);
                              }
                            }
                          }
                        }
                      }
                    @endphp
                    @if ($first_counter == 5 && $counter_null < 1)
                      N/A
                      @php
                          $counter_null++;
                      @endphp
                    @else
                      {{ $tree_count > 0 ? number_format($tree_count, 2) : '-' }}
                    @endif
                </td>
              @endfor
            </tr>
            @if($first_counter == 4)
              <tr>
                <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee">Effective Capacity</td>
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  <td style="text-align:center; background-color: #ebebeb; border:1px solid #eee">
                    {{ number_format($setting['effective_capacity'], 2) }}
                  </td>
                @endfor
              </tr>
              <tr>
                <td style="text-align: left; background-color:#fffde0; color:#000000; border:1px solid #eee"><b>Total For A4 After</b></td>
                @php
                  $loop_year = $setting['forecast_report_default_year'];
                @endphp
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  @php
                    if($i > 1){
                      $loop_year += 1;
                    }
                  @endphp
                  <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">
                    {{ isset($total_tree_for_a4[$loop_year]) ? number_format(round($total_tree_for_a4[$loop_year], 0), 2) : '-' }}
                  </td>
                @endfor
              </tr>
              <tr>
                <td style="text-align: left; background-color:#d2ffef; color:#000000; border:1px solid #eee"><b>A1 - A4 Total Tree</b></td>
                @php
                  $loop_year = $setting['forecast_report_default_year'];
                @endphp
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  @php
                    if($i > 1){
                      $loop_year += 1;
                    }
                  @endphp
                  <td style="text-align: center; background-color:#d2ffef; color:#000000; border:1px solid #eee">
                    {{ isset($total_tree_a1_to_a4[$loop_year]) ? number_format($total_tree_a1_to_a4[$loop_year], 2) : '-' }}
                  </td>
                @endfor
              </tr>
              <tr>
                <td style="text-align: left; background-color:#ffeecb; color:#000000; border:1px solid #eee"><b>A1 - A4 Total Capacity</b></td>
                @php
                  $loop_year = $setting['forecast_report_default_year'];
                @endphp
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  @php
                    if($i > 1){
                      $loop_year += 1;
                    }
                  @endphp
                  <td style="text-align: center; background-color:#ffeecb; color:#000000; border:1px solid #eee">
                    {{ isset($total_tree_a1_to_a4_2[$loop_year]) ? number_format($total_tree_a1_to_a4_2[$loop_year], 2) : '-' }}
                  </td>
                @endfor
              </tr>
            @elseif($first_counter == 5)
            <tr>
              @php
                $counter_null = 0;
              @endphp
              <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee">Effective Capacity</td>
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @if ($counter_null < 1)
                  <td style="text-align:center; background-color: #ebebeb; border:1px solid #eee">
                    N/A
                  </td>
                  @php
                    $counter_null++;
                  @endphp
                @else
                  <td style="text-align:center; background-color: #ebebeb; border:1px solid #eee">
                    {{ $setting['effective_capacity'] }}
                  </td>
                @endif
              @endfor
            </tr>
            <tr>
              <td style="text-align: left; background-color:#fffde0; color:#000000; border:1px solid #eee"><b>Total For A5 After</b></td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
                $counter_null = 0;
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                @if ($counter_null < 1)
                  <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">
                    N/A
                  </td>
                  @php
                      $counter_null++;
                  @endphp
                @else
                  <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">
                    {{ isset($total_tree_for_a5[$loop_year]) ? number_format(round($total_tree_for_a5[$loop_year], 0), 2) : '-' }}
                  </td>
                @endif
              @endfor
            </tr>
            <tr>
              <td style="text-align: left; background-color:#d2ffef; color:#000000; border:1px solid #eee"><b>A1 - A5 Total Tree</b></td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="text-align: center; background-color:#d2ffef; color:#000000; border:1px solid #eee">
                  {{ isset($total_tree_a1_to_a5[$loop_year]) ? number_format($total_tree_a1_to_a5[$loop_year], 2) : (isset($total_tree_a1_to_a4[$loop_year]) ? number_format($total_tree_a1_to_a4[$loop_year], 2) : '-' ) }}
                </td>
              @endfor
            </tr>
            <tr>
              <td style="text-align: left; background-color:#ffeecb; color:#000000; border:1px solid #eee"><b>A1 - A5 Total Capacity</b></td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="text-align: center; background-color:#ffeecb; color:#000000; border:1px solid #eee">
                  {{ isset($total_tree_a1_to_a5_2[$loop_year]) ? number_format($total_tree_a1_to_a5_2[$loop_year], 2) : (isset($total_tree_a1_to_a4_2[$loop_year]) ? number_format($total_tree_a1_to_a4_2[$loop_year], 2) : '-') }}
                </td>
              @endfor
            </tr>
            @endif
          @endforeach
          <tr>
            <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee; height: 60px; vertical-align: middle;" colspan="{{ $setting['forecast_report_year_count'] + 1 }}"><b>Capacity Per Year</b>
            </td>
          </tr>

          @foreach($companyPnlItem as $pnlkey => $company_pnl_item)
          <tr>
            <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
              {{$company_pnl_item->pnl_name}}({{ $company_pnl_item->pnl_code }})<br/>
              <i style="font-size: 11px">
                {{$company_pnl_item->start_year > 1 ? 'Start from Year '.$company_pnl_item->start_year.',' : ''}}
                Each Year Increase {{ $company_pnl_item->yearly_increase }} until {{ $company_pnl_item->max_value }}
              </i>
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
              $counter_initial = 1;
              $counter_first = 0;
              $counter_null = 0;
            @endphp
            @if($company_pnl_item->pnl_id <> 6)
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                @if($company_pnl_item->start_year > $counter_initial)
                  <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    -
                  </td>
                  @php
                    $counter_initial++;
                  @endphp
                @elseif($company_pnl_item->start_year <= $counter_initial || $company_pnl_item->start_year == 0)
                  @if($counter_first < 1)
                    @php
                      $pnl_value = $company_pnl_item->initial_value;
                      if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                        $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                      }else{
                        $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                      }
                    @endphp
                    <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                      {{number_format($company_pnl_item->initial_value, 2)}}
                    </td>
                    @php
                      $counter_first++;
                    @endphp
                  @elseif($counter_first >= 1)
                    @if($company_pnl_item->max_value > $pnl_value)
                      @php
                        $pnl_value += $company_pnl_item->yearly_increase;
                        if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }else{
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }
                      @endphp
                      <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                        {{number_format($pnl_value, 2)}}
                      </td>
                    @elseif($company_pnl_item->max_value <= $pnl_value)
                      <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                        {{number_format($pnl_value, 2)}}
                      </td>
                      @php
                        if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }else{
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }
                      @endphp
                    @endif
                  @endif
                @endif
              @endfor 
            @else
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                @if($counter_null < 1)
                  <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    N/A
                  </td>
                  @php
                      $counter_null++;
                  @endphp
                @elseif($company_pnl_item->start_year > $counter_initial)
                  <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    -
                  </td>
                  @php
                    $counter_initial++;
                  @endphp
                @elseif($company_pnl_item->start_year <= $counter_initial || $company_pnl_item->start_year == 0)
                  @if($counter_first < 1)
                    @php
                      $pnl_value = $company_pnl_item->initial_value;
                      if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                        $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                      }else{
                        $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                      }
                    @endphp
                    <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                      {{number_format($company_pnl_item->initial_value, 2)}}
                    </td>
                    @php
                      $counter_first++;
                    @endphp
                  @elseif($counter_first >= 1)
                    @if($company_pnl_item->max_value > $pnl_value)
                      @php
                        $pnl_value += $company_pnl_item->yearly_increase;
                        if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }else{
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }
                      @endphp
                      <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                        {{number_format($pnl_value, 2)}}
                      </td>
                    @elseif($company_pnl_item->max_value <= $pnl_value)
                      <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                        {{number_format($pnl_value, 2)}}
                      </td>
                      @php
                        if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }else{
                          $each_pnl_value[$company_pnl_item->pnl_id][$loop_year] = $pnl_value * $setting['each_durian_weight'];
                        }
                      @endphp
                    @endif
                  @endif
                @endif
              @endfor 
            @endif
          </tr>
          <tr>
            <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
              Durian Weight (KG)
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
              $counter_null = 0;
            @endphp
            @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
              @php
                if($i > 1){
                  $loop_year += 1;
                }
              @endphp
              @if ($company_pnl_item->pnl_id <> 6)
                <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                  {{number_format($setting['each_durian_weight'], 2)}}
                </td>
              @else
                @if ($counter_null < 1)
                  <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    N/A
                    @php
                        $counter_null++;
                    @endphp
                  </td>
                @else
                  <td style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    {{number_format($setting['each_durian_weight'], 2)}}
                  </td>
                @endif
              @endif
            @endfor
          </tr>
          <tr>
            <td style="text-align: left; background-color:#fffde0; color:#000000 border:1px solid #eee">
              Total Per Year Capacity
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
              $counter_null = 0;
            @endphp
            @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
              @php
                if($i > 1){
                  $loop_year += 1;
                }
              @endphp
              @if ($company_pnl_item->pnl_id <> 6)
                <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">      
                  @if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year]))
                    {{number_format($each_pnl_value[$company_pnl_item->pnl_id][$loop_year], 2)}}
                  @else
                    -
                  @endif
                </td>
              @else
                @if ($counter_null < 1)
                  <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">      
                    N/A
                  </td>
                  @php
                    $counter_null++;
                  @endphp
                @else
                  <td style="text-align: center; background-color:#fffde0; color:#000000; border:1px solid #eee">      
                    @if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year]))
                      {{number_format($each_pnl_value[$company_pnl_item->pnl_id][$loop_year], 2)}}
                    @else
                      -
                    @endif
                  </td>
                @endif
              @endif
            @endfor

          </tr>
          @endforeach

          <tr>
            <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee; height: 60px; vertical-align: middle;" colspan="{{ $setting['forecast_report_year_count'] + 1 }}"><b>Income (KG): <i>(Trees x Total Per Year Capacity)</i></b>
            </td>
          </tr>

          @php
            $income_kg = [];
          @endphp

          @foreach($companyPnlItem as $pnlkey => $company_pnl_item)
            @php
              $loop_year = $setting['forecast_report_default_year'];
            @endphp
            <tr>
              <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
                {{$company_pnl_item->pnl_name}}({{ $company_pnl_item->pnl_code }})
              </td>
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                  $tree_count = 0;
                @endphp
                <td style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                  @php
                  if(isset($forecastReportResult[$company_pnl_item->pnl_code])){
                    foreach($forecastReportResult[$company_pnl_item->pnl_code] as $year => $value){
                      if($year <= $loop_year){
                        $tree_count += $value['tree_count'];

                        if(isset($income_kg[$company_pnl_item->pnl_id][$loop_year])){
                          if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                            $income_kg[$company_pnl_item->pnl_id][$loop_year] += $value['tree_count'] * $each_pnl_value[$company_pnl_item->pnl_id][$loop_year];
                          }
                        }else{
                          if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                            $income_kg[$company_pnl_item->pnl_id][$loop_year] = $value['tree_count'] * $each_pnl_value[$company_pnl_item->pnl_id][$loop_year];
                          }
                        }
                        
                        if(isset($total_income_kg[$loop_year])){
                          if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                            $total_income_kg[$loop_year] += $value['tree_count'] * $each_pnl_value[$company_pnl_item->pnl_id][$loop_year];
                          }
                        }else{
                          if(isset($each_pnl_value[$company_pnl_item->pnl_id][$loop_year])){
                            $total_income_kg[$loop_year] = $value['tree_count'] * $each_pnl_value[$company_pnl_item->pnl_id][$loop_year];
                          }
                        }
                      }
                    }
                  }
                  @endphp
                  @if (isset($income_kg[$company_pnl_item->pnl_id][$loop_year]))
                    {{ $income_kg[$company_pnl_item->pnl_id][$loop_year] > 0 ? number_format($income_kg[$company_pnl_item->pnl_id][$loop_year], 2) : '-' }}
                  @else
                    -
                  @endif
                </td>
              @endfor
            </tr>
          @endforeach
          <tr>
            <td style="text-align: left; background-color: #fffde0; border:1px solid #eee">
              Total Income (KG)
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
            @endphp
            @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
              @php
                if($i > 1){
                  $loop_year += 1;
                }
              @endphp
              <td style="min-width: 220px; text-align: center; background-color:#fffde0; border:1px solid #eee">
                {{ isset($total_income_kg[$loop_year]) ? number_format($total_income_kg[$loop_year], 2) : '-' }}
              </td>
            @endfor
          </tr>

          <tr>
            <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee; height: 60px; vertical-align: middle;" colspan="{{ $setting['forecast_report_year_count'] + 1 }}"><b>Product Price per KG</b>
            </td>
          </tr>

          @php
            $each_product_price = [];
          @endphp

          @if (!empty($avgprice['category_name']))
            @foreach($avgprice['category_name'] as $key => $category_name)
              @php
                $loop_year = $setting['forecast_report_default_year'];
                $counter = 0;
              @endphp
              <tr>
                <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
                  {{ $category_name }} (RM)<br/>
                  <i style="font-size: 11px">(Each Year increase 9%)</i>
                </td>
                @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                  @php
                    if($i > 1){
                      $loop_year += 1;
                    }
                  @endphp
                  <td style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                    @if ($counter < 1)
                      @php
                        if (isset($each_product_price[$key][$loop_year])) {
                          $each_product_price[$key][$loop_year] = $avgprice['average_price'][$key];
                        }else{
                          $each_product_price[$key][$loop_year] = $avgprice['average_price'][$key];
                        }

                        $counter++;
                      @endphp
                      @if (isset($each_product_price[$key][$loop_year]))
                        {{number_format($each_product_price[$key][$loop_year], 2)}}
                      @else
                        -
                      @endif
                    @else
                      @php
                        if (isset($each_product_price[$key][$loop_year])) {
                          $each_product_price[$key][$loop_year] = $each_product_price[$key][($loop_year - 1)] * 1.09;
                        }else{
                          $each_product_price[$key][$loop_year] = $each_product_price[$key][($loop_year - 1)] * 1.09;
                        }
                      @endphp
                      @if (isset($each_product_price[$key][$loop_year]))
                        {{number_format($each_product_price[$key][$loop_year], 2)}}
                      @else
                        -
                      @endif
                    @endif
                  </td>
                @endfor
              </tr>
            @endforeach
          @else
            <tr>
              <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
                No Record
              </td>
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                  -
                </td>
              @endfor
            </tr>
          @endif
          

          <tr>
            <td style="text-align:left; background-color: #ebebeb; border:1px solid #eee; height: 60px; vertical-align: middle;" colspan="{{ $setting['forecast_report_year_count'] + 1 }}"><b>Income (RM): <i>(Income(KG) x Product Price per KG)</i></b>
            </td>
          </tr>

          @foreach($companyPnlItem as $pnlkey => $company_pnl_item)
            @php
              $loop_year = $setting['forecast_report_default_year'];
            @endphp
            <tr>
              <td style="text-align: left; background-color: #f5f5f5; border:1px solid #eee">
                {{$company_pnl_item->pnl_name}}({{ $company_pnl_item->pnl_code }})
              </td>
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; {{ $i % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #ffffff;' }} border:1px solid #eee">
                  @if ($company_pnl_item->pnl_id == 3)
                    @php
                      $key = 3;
                      if(isset($total_income_rm[$loop_year])){
                        if(isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year])){
                          $total_income_rm[$loop_year] += $income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year];
                        }
                      }else{
                        if(isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year])){
                          $total_income_rm[$loop_year] = $income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year];
                        }
                      }
                    @endphp
                    @if (isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year]))
                      {{ number_format(($income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year]), 2) }}
                    @else
                      -
                    @endif
                  @else
                    @php
                      $key = 2;
                      if(isset($total_income_rm[$loop_year])){
                        if(isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year])){
                          $total_income_rm[$loop_year] += $income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year];
                        }
                      }else{
                        if(isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year])){
                          $total_income_rm[$loop_year] = $income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year];
                        }
                      }
                    @endphp
                    @if (isset($income_kg[$company_pnl_item->pnl_id][$loop_year]) && isset($each_product_price[$key][$loop_year]))
                      {{ number_format(($income_kg[$company_pnl_item->pnl_id][$loop_year] * $each_product_price[$key][$loop_year]), 2) }}
                    @else
                      -
                    @endif
                  @endif
                </td>
              @endfor
            </tr>
          @endforeach
          <tr>
            <td style="text-align: left; background-color: #fffde0; border:1px solid #eee">
              Total Income (RM)
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
            @endphp
            @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
              @php
                if($i > 1){
                  $loop_year += 1;
                }
              @endphp
              <td style="min-width: 220px; text-align: center; background-color: #fffde0; border:1px solid #eee">
                {{ isset($total_income_rm[$loop_year]) ? number_format($total_income_rm[$loop_year], 2) : '-' }}
              </td>
            @endfor
          </tr>

          @php
            $expense = [];
            $pbt = [];
            $income_tax = [];
            $pat = [];
            $pe_ratio = [];
          @endphp

          <tr>
            <td style="text-align: left; background-color: #fffde0; border:1px solid #eee">
              Operating Expense (25%)
            </td>
            @php
              $loop_year = $setting['forecast_report_default_year'];
            @endphp
            @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
              @php
                if($i > 1){
                  $loop_year += 1;
                }
              @endphp
              <td style="min-width: 220px; text-align: center; background-color: #fffde0; border:1px solid #eee">
                @php
                  if (isset($total_income_rm[$loop_year])) {
                    $expense[$loop_year] = $total_income_rm[$loop_year] * 0.25;
                  }
                @endphp
                {{ isset($expense[$loop_year]) ? number_format($expense[$loop_year], 2) : '-' }}
              </td>
            @endfor
            </tr>

            <tr>
              <td style="text-align: left; background-color: #fffde0; border:1px solid #eee">
                Profit Before Tax (PBT)
              </td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; background-color: #fffde0; border:1px solid #eee">
                  @php
                    if(isset($total_income_rm[$loop_year]) && isset($expense[$loop_year])){
                      $pbt[$loop_year] = $total_income_rm[$loop_year] - $expense[$loop_year];
                    }
                  @endphp
                  {{ isset($pbt[$loop_year]) ? number_format($pbt[$loop_year], 2) : '-' }}
                </td>
              @endfor
            </tr>
          <tr>
              <td style="text-align: left; background-color: #d2ffef; border:1px solid #eee">
                Income Tax (24%)
              </td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; background-color: #d2ffef; border:1px solid #eee">
                  @php
                    if(isset($pbt[$loop_year])){
                      $income_tax[$loop_year] = $pbt[$loop_year] * 0.24;
                    }
                  @endphp
                  {{ isset($income_tax[$loop_year]) ? number_format($income_tax[$loop_year], 2) : '-' }}
                </td>
              @endfor
            </tr>

            <tr>
              <td style="text-align: left; background-color: #d2ffef; border:1px solid #eee">
                <b>Profit After Tax (PAT)</b>
              </td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; background-color: #d2ffef; border:1px solid #eee">
                  @php
                    if(isset($pbt[$loop_year]) && isset($income_tax[$loop_year])){
                      $pat[$loop_year] = $pbt[$loop_year] - $income_tax[$loop_year];
                    }
                  @endphp
                  {{ isset($pat[$loop_year]) ? number_format($pat[$loop_year], 2) : '-' }}
                </td>
              @endfor
            </tr>

            <tr>
              <td style="text-align: left; background-color: #ffeecb; border:1px solid #eee">
                <b>Price-Earning Ratio (PE)</b>
              </td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                  if ($loop_year < $setting['forecast_report_pe_ratio_year']) {
                    $pe_ratio[$loop_year] = 0;
                  }elseif($loop_year == $setting['forecast_report_pe_ratio_year']){
                    $pe_ratio[$loop_year] = $setting['pe_ratio'];
                  }else{
                    if ($pe_ratio[$loop_year - 1] < 30) {
                      $pe_ratio[$loop_year] = $pe_ratio[$loop_year - 1] + $setting['pe_ratio'];
                    }else{
                      $pe_ratio[$loop_year] = $pe_ratio[$loop_year - 1];
                    }
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; background-color: #ffeecb; border:1px solid #eee">
                  {{ $pe_ratio[$loop_year] > 0 ? number_format($pe_ratio[$loop_year], 2) : '-' }}
                </td>
              @endfor
            </tr>

            <tr>
              <td style="text-align: left; background-color: #ffeecb; border:1px solid #eee">
                <b>Company Market Value (RM)</b>
              </td>
              @php
                $loop_year = $setting['forecast_report_default_year'];
              @endphp
              @for($i = 1; $i <= $setting['forecast_report_year_count']; $i++)
                @php
                  if($i > 1){
                    $loop_year += 1;
                  }
                @endphp
                <td style="min-width: 220px; text-align: center; background-color: #ffeecb; border:1px solid #eee">
                  @php
                    if(isset($pat[$loop_year]) && isset($pe_ratio[$loop_year])){
                      $market_value = $pat[$loop_year] * $pe_ratio[$loop_year];
                    }
                  @endphp
                  {{ isset($market_value) && $market_value > 0 ? number_format(round($market_value, -6), 2) : '-' }}
                </td>
              @endfor
            </tr>
        </tbody>
      </table>
    @else
      <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
