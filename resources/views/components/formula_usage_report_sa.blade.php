<div class="table-responsive" style="@if(!@$component) margin:auto @endif">
    @if(!@$component)
        <p><b>Formula Usage Report {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    <table class="table table-nowrap table-bordered" id="collect_do_table">
          <thead>
              <tr>
                  <th style="text-align:left; background-color: #d8d9df; border:1px solid #eee" rowspan="2">Category</th>
                  @foreach ($monthSel as $month_num => $month_name)
                      <th style="min-width: 220px; text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='3'>
                          {{ $month_name }}
                      </th>
                  @endforeach
                  <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" rowspan="1 "colspan="3">Total</th>
              </tr>
              <tr>
                @foreach($monthSel as $month_num => $month_name)
                    <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan='1'>
                        Value
                    </th>
                    <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan='1'>
                        Tree
                    </th>
                    <th style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan='1'>
                        Value/Tree
                    </th>
                @endforeach
                <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Value</th>
                <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Tree</th>
                <th class="h-auto" style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000" colspan="1">Value/Tree</th>
              </tr>

          </thead>
          <tbody>
            @php
              $total_val_by_c = [];
              $total_tree_by_c = [];
              $total_val_by_sfc = [];
              $total_tree_by_sfc = [];
              $grandtotal_val = 0;
              $grandtotal_tree = 0;
            @endphp
            @foreach($settingFormulaCategory as $sfckey => $sfc)
              <tr>
                <td style="text-align: left; background-color: #e4e4e4; border:1px solid #eee">
                  {{ json_decode($sfc)->en }}
                </td>
                @foreach($monthSel as $month_num => $month_name)
                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                      @if(@$component)
                      <a class="popup" href="{{route('formula_usage_report_sa_detail', ['tenant' => tenant('id'), 'year' => $search['year'], 'month' => $month_num, 'setting_formula_category_id' => $sfckey])}}">{{ isset($formulaUsage[$month_num][$sfckey]['value']) ? number_format($formulaUsage[$month_num][$sfckey]['value'], 2, '.', '') : '-' }}</a>
                      @else
                      {{ isset($formulaUsage[$month_num][$sfckey]['value']) ? number_format($formulaUsage[$month_num][$sfckey]['value'], 2, '.', '') : '-' }}
                      @endif
                      @php
                        if(isset($formulaUsage[$month_num][$sfckey]['value'])){
                          if(isset($total_val_by_c[$month_num])){
                            $total_val_by_c[$month_num] += $formulaUsage[$month_num][$sfckey]['value'];
                          }else{
                            $total_val_by_c[$month_num] = $formulaUsage[$month_num][$sfckey]['value'];
                          }
                        if(isset($total_val_by_sfc[$sfckey])){
                          $total_val_by_sfc[$sfckey] += $formulaUsage[$month_num][$sfckey]['value'];
                        }else{
                          $total_val_by_sfc[$sfckey] = $formulaUsage[$month_num][$sfckey]['value'];
                        }
                      }
                      @endphp
                    </td>
                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}border:1px solid #eee">
                      {{ isset($formulaUsage[$month_num][$sfckey]['total_tree']) ? $formulaUsage[$month_num][$sfckey]['total_tree'] : '-' }}
                      @php
                        if(isset($formulaUsage[$month_num][$sfckey]['total_tree'])){
                          if(isset($total_tree_by_c[$month_num])){
                            $total_tree_by_c[$month_num] += $formulaUsage[$month_num][$sfckey]['total_tree'];
                          }else{
                            $total_tree_by_c[$month_num] = $formulaUsage[$month_num][$sfckey]['total_tree'];
                          }
                          if(isset($total_tree_by_sfc[$sfckey])){
                            $total_tree_by_sfc[$sfckey] += $formulaUsage[$month_num][$sfckey]['total_tree'];
                          }else{
                            $total_tree_by_sfc[$sfckey] = $formulaUsage[$month_num][$sfckey]['total_tree'];
                          }
                      }
                      @endphp
                    </td>
                    <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                      {{ isset($formulaUsage[$month_num][$sfckey]['total_tree']) ?
                      number_format($formulaUsage[$month_num][$sfckey]['value']/$formulaUsage[$month_num][$sfckey]['total_tree'], 2, '.', '') :
                      '-' }}
                    </td>
                @endforeach
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_val_by_sfc[$sfckey]) ? number_format($total_val_by_sfc[$sfckey],2, '.', '' ) : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_tree_by_sfc[$sfckey]) ? $total_tree_by_sfc[$sfckey] : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_tree_by_sfc[$sfckey]) ? number_format($total_val_by_sfc[$sfckey]/$total_tree_by_sfc[$sfckey],2, '.', '' ) : '-' }}</td>
              </tr>
            @php
              if(isset($total_val_by_sfc[$sfckey])){
                $grandtotal_val += $total_val_by_sfc[$sfckey];
              }
              if(isset($total_tree_by_sfc[$sfckey])){
                $grandtotal_tree += $total_tree_by_sfc[$sfckey];
              }
            @endphp
            @endforeach
            <tr>
              <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000"><b>Total</b></td>
              @foreach($monthSel as $month_num => $month_name)
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_val_by_c[$month_num]) ? number_format($total_val_by_c[$month_num],2, '.', '' ) : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_tree_by_c[$month_num]) ? number_format($total_tree_by_c[$month_num],2, '.', '' ) : '-' }}</td>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ isset($total_tree_by_c[$month_num]) ?number_format($total_val_by_c[$month_num]/$total_tree_by_c[$month_num],2, '.', '' ) : '-' }}</td>
              @endforeach
              <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_val > 0 ? number_format($grandtotal_val,2, '.', '' ) : '-' }}</td>
              <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_tree > 0 ? $grandtotal_tree : '-' }}</td>
              <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000">{{ $grandtotal_tree > 0 ? number_format($grandtotal_val/$grandtotal_tree,2, '.', '' ) : '-' }}</td>
            </tr>
          </tbody>
    </table>
 </div>
