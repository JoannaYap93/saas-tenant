<div>
    @if(!@$component)
        <p><b>Formula Usage Report - {{$monthName}} {{$search['year']}} ({{$settingFormulaCategoryName}})</b></p>
    @endif
  </div>
  <div class="table-responsive" style="@if(!@$component) margin:auto @endif">

      <table class="table table-bordered" id="formula_usage_detail_table">
            <thead>
                <tr>
                    @php
                      $count = 0;
                    @endphp
                    <th style="text-align:left; background-color: #d8d9df; border:1px solid #eee" rowspan="3">Product</th>
                </tr>
                <tr>
                  @php
                    $count = 0;
                  @endphp
                  @foreach($companY as $ckey => $company)
                    @if($companyLand[$ckey])
                      @foreach($companyLand[$ckey] as $clkey => $company_land_name)
                      <th style="min-width: 220px; text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='3'>
                          {{ $company_land_name }}
                      </th>
                      @endforeach
                      <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="3">Total</th>
                      @php
                        $count++;
                      @endphp
                    @endif
                  @endforeach
                </tr>
                <tr>
                  @php
                    $count = 0;
                  @endphp
                  @foreach($companY as $ckey => $company)
                    @if($companyLand[$ckey])
                      @foreach($companyLand[$ckey] as $clkey => $company_land_name)
                      <th style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='0'>
                          Tree
                      </th>
                      <th style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='0'>
                          Value/Tree
                      </th>
                      <th style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='0'>
                          Price/Tree (RM)
                      </th>
                      @endforeach
                      @php
                        $count++;
                      @endphp
                    @endif
                  @endforeach
                  <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Tree</th>
                  <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Value/Tree</th>
                  <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Price/Tree (RM)</th>
                </tr>
            </thead>
            <tbody>
              @php
                $total_tree_by_p = [];
                $total_value_tree_by_p = [];
                $total_price_tree_by_p = [];
                $total_tree_by_c_cl = [];
                $total_value_tree_by_c_cl = [];
                $total_price_tree_by_c_cl = [];
                $grandtotal_tree = 0;
                $grandtotal_val_tree = 0;
                $grandtotal_price_tree = 0;
              @endphp
              @foreach($productS as $pkey => $product_name)
                <tr>
                  <td style="text-align: left; background-color: #e4e4e4; border:1px solid #eee">
                    {{$product_name}}
                  </td>
                  @php
                    $count = 0;
                  @endphp
                  @foreach($companY as $ckey => $company)
                    @if($companyLand[$ckey])
                      @foreach($companyLand[$ckey] as $clkey => $company_land_name)
                      <td style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}border:1px solid #eee">
                        {{ isset($formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree']) ? $formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'] : '-' }}
                        @php
                          if(isset($formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'])){
                            if(isset($total_tree_by_p[$pkey])){
                              $total_tree_by_p[$pkey] += $formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                            }else{
                              $total_tree_by_p[$pkey] = $formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                            }
                            if(isset($total_tree_by_c_cl[$ckey][$clkey])){
                              $total_tree_by_c_cl[$ckey][$clkey] += $formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                            }else{
                              $total_tree_by_c_cl[$ckey][$clkey] = $formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                            }
                        }
                        @endphp
                      </td>
                      <td style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}border:1px solid #eee">
                        {{ isset($formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree']) ? number_format($formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree']/$formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'],2) : '-' }}
                        @php
                          if(isset($formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree'])){
                            if(isset($total_value_tree_by_p[$pkey])){
                              $each_total_value_per_tree = $formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree']*$formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                              $total_value_tree_by_p[$pkey] += $formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree'];
                            }else{
                              $total_value_tree_by_p[$pkey] = $formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree'];
                            }
                            if(isset($total_value_tree_by_c_cl[$ckey][$clkey])){
                              $total_value_tree_by_c_cl[$ckey][$clkey] += $formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree'];
                            }else{
                              $total_value_tree_by_c_cl[$ckey][$clkey] = $formulaUsage['result'][$pkey][$ckey][$clkey]['val_per_tree'];
                            }
                        }
                        @endphp
                      </td>
                      <td style="text-align: center; {{ $count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}border:1px solid #eee">
                        {{ isset($formulaUsage['result2'][$pkey][$ckey][$clkey]['total_price']) ?
                        number_format($formulaUsage['result2'][$pkey][$ckey][$clkey]['total_price']/$formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'], 2)
                        : '-' }}
                        @php
                          if(isset($formulaUsage['result2'][$pkey][$ckey][$clkey]['total_price'])){
                            $price_per_tree = $formulaUsage['result2'][$pkey][$ckey][$clkey]['total_price']/$formulaUsage['result'][$pkey][$ckey][$clkey]['total_tree'];
                            if(isset($total_price_tree_by_p[$pkey])){
                              $total_price_tree_by_p[$pkey] += $price_per_tree;
                            }else{
                              $total_price_tree_by_p[$pkey] = $price_per_tree;
                            }
                            if(isset($total_price_tree_by_c_cl[$ckey][$clkey])){
                              $total_price_tree_by_c_cl[$ckey][$clkey] += $price_per_tree;
                            }else{
                              $total_price_tree_by_c_cl[$ckey][$clkey] = $price_per_tree;
                            }
                        }
                        @endphp
                      </td>
                      @endforeach
                      @php
                        $count++;
                      @endphp
                    @endif
                  @endforeach
                  <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{isset($total_tree_by_p[$pkey]) ? $total_tree_by_p[$pkey] : '-'}}</td>
                  <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{isset($total_value_tree_by_p[$pkey]) ?  number_format($total_value_tree_by_p[$pkey]/$total_tree_by_p[$pkey], 2) : '-'}}</td>
                  <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{isset($total_price_tree_by_p[$pkey]) ? number_format($total_price_tree_by_p[$pkey], 2) : '-'}}</td>
                </tr>
                @php
                  if(isset($total_tree_by_p[$pkey])){
                    $grandtotal_tree += $total_tree_by_p[$pkey];
                  }
                  if(isset($total_value_tree_by_p[$pkey])){
                    $grandtotal_val_tree += $total_value_tree_by_p[$pkey];
                  }
                  if(isset($total_price_tree_by_p[$pkey])){
                    $grandtotal_price_tree += $total_price_tree_by_p[$pkey];
                  }
                @endphp
              @endforeach
              <tr>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000"><b>Total</b></td>
                @foreach($companY as $ckey => $company)
                  @if($companyLand[$ckey])
                    @foreach($companyLand[$ckey] as $clkey => $company_land_name)
                      <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_tree_by_c_cl[$ckey][$clkey]) ? $total_tree_by_c_cl[$ckey][$clkey] : '-' }}</td>
                      <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_value_tree_by_c_cl[$ckey][$clkey]) ? number_format($total_value_tree_by_c_cl[$ckey][$clkey]/$total_tree_by_c_cl[$ckey][$clkey],2 ) : '-' }}</td>
                      <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_price_tree_by_c_cl[$ckey][$clkey]) ? number_format($total_price_tree_by_c_cl[$ckey][$clkey],2 ) : '-' }}</td>
                    @endforeach
                  @endif
                @endforeach
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_tree > 0 ? $grandtotal_tree : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_val_tree > 0 ? number_format($grandtotal_val_tree/$grandtotal_tree, 2) : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_price_tree > 0 ? number_format($grandtotal_price_tree, 2) : '-' }}</td>
              </tr>
            </tbody>
      </table>
   </div>
