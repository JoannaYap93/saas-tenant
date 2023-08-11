{{-- SUPER ADMIN --}}
<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Budget Report</b></p>
        <br><br>
    @endif
    <table class="table table-nowrap table-bordered" id="budget-report-table">
        <thead>
            <tr>
                <th style="background-color: #d8d9df; border:1px solid #eee;" rowspan="2" colspan="2"></th>
                <th style="background-color: #FFE5B4; border:1px solid #eee;" colspan="3">Per Tree</th>
                <th style="background-color: #fffbaf; border:1px solid #eee;" colspan="3">Total Tree</th>
            </tr>
            <tr>
                <th style="background-color: #FFE5B4; border:1px solid #eee;">Budget</th>
                <th style="background-color: #FFE5B4; border:1px solid #eee;">Actual</th>
                <th style="background-color: #FFE5B4; border:1px solid #eee;">Remaining</th>
                <th style="background-color: #fffbaf; border:1px solid #eee;">Budget</th>
                <th style="background-color: #fffbaf; border:1px solid #eee;">Actual</th>
                <th style="background-color: #fffbaf; border:1px solid #eee;">Remaining</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count_company_land = $company_lands = array();
                $overwrite_budget = false;
                $actual = array();
                $row_count = 0;
            @endphp
            @foreach ($companyLand as $key => $company_land)
                @php
                    if(isset($count_company_land[$company_land->company_id])){
                        $count_company_land[$company_land->company_id] += 1;
                    }
                    else {
                        $count_company_land[$company_land->company_id] = 1;
                    }

                    if (isset($expense[$company_land->company_id][$company_land->company_land_id]) || isset($formula[$company_land->company_id][$company_land->company_land_id])) {
                        if(isset($expense[$company_land->company_id][$company_land->company_land_id])){
                            if(isset($actual[$company_land->company_land_id] )){
                                $actual[$company_land->company_land_id] += $expense[$company_land->company_id][$company_land->company_land_id];
                            }
                            else {
                                $actual[$company_land->company_land_id] = $expense[$company_land->company_id][$company_land->company_land_id];
                            }
                        }
                        if(isset($formula[$company_land->company_id][$company_land->company_land_id])){
                            if(isset($actual[$company_land->company_land_id] )){
                                $actual[$company_land->company_land_id] += $formula[$company_land->company_id][$company_land->company_land_id];
                            }
                            else{
                                $actual[$company_land->company_land_id] = $formula[$company_land->company_id][$company_land->company_land_id];

                            }
                        }
                    }
                    $company_lands[$company_land->company_id][$key] = $company_land;
                @endphp
            @endforeach

            @foreach ($companies as $company)
            @php
                $company_land_index = 0;
            @endphp
            <tr>
                <td rowspan="{{$count_company_land[$company->company_id]}}" style="text-align: left; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                    <a class="popup" href="{{route('budget_report_detail', ['tenant' => tenant('id'), 'company_id' => $company->company_id, 'year' => @$search['year']])}}">{{$company->company_name}}</a>
                </td>
                @foreach ($company_lands[$company->company_id]  as $land)
                    @if ($company_land_index > 0)
                        <tr>
                    @endif

                    <td style="text-align: left; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$land->company_land_name }}</td>

                    {{-- budget per tree --}}
                        @php
                            if($land->company_land_total_tree != 0){
                                $owb = $land->overwrite_budget_per_tree;
                            }else{
                                $owb = 0;
                            }

                            if($land->company_land_total_tree != 0){
                                $default_tree = $default;
                            }else{
                                $default_tree = 0;
                            }
                        @endphp

                    @if ($land->is_overwrite_budget)
                        @php
                            $overwrite_budget = true;
                        @endphp
                            @if($land->company_land_total_tree != 0)
                                <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($owb,2, '.', ',')}} <small style="color:black;">(OV)</small></td>
                            @else
                                <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($owb,2, '.', ',')}}</td>
                            @endif
                    @else
                        @php
                            $overwrite_budget = false;
                        @endphp
                        <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($default_tree,2, '.', ',')}}</td>
                    @endif

                    {{-- actual per tree --}}
                    @if(isset($actual[$land->company_land_id]))
                    {{-- @dd($actual[$land->company_land_id]) --}}
                            @php

                            // check total tree
                                if($land->company_land_total_tree != 0){
                                    $total_tree = $actual[$land->company_land_id]/$land->company_land_total_tree;
                                }else{
                                    $total_tree = 0;
                                }

                            // check total tree for overwrite budget
                                if($land->company_land_total_tree != 0){
                                    $owb = $land->overwrite_budget_per_tree;
                                }else{
                                    $owb = 0;
                                }
                            @endphp
                        @if($overwrite_budget)
                            @if($actual[$land->company_land_id]/$land->company_land_total_tree <= $owb)
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($total_tree,2, '.', ',')}}</td>
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($owb - $total_tree,2, '.', ',')}}</td>
                            @elseif($total_tree > $owb)
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($total_tree,2, '.', ',')}}</td>
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($owb - $total_tree,2, '.', ',')}}</td>
                            @endif
                        @else
                            @if($total_tree <= $default_tree)
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($total_tree,2, '.', ',')}}</td>
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($default_tree - $total_tree,2, '.', ',')}}</td>
                            @elseif($total_tree > $default_tree)
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($total_tree,2, '.', ',')}}</td>
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format($default_tree - $total_tree,2, '.', ',')}}</td>
                            @endif
                        @endif
                    @else
                        <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{number_format(0,2)}}</td>
                        <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffeecb;' : 'background-color: #ffeecb;' }} border:1px solid #eee;">{{$overwrite_budget ? number_format($owb,2, '.', ',') : number_format($default_tree,2, '.', ',')}}</td>
                    @endif

                    {{-- budget total tree --}}
                    @if ($land->is_overwrite_budget)
                        @php
                            $overwrite_budget = true;

                            if($land->company_land_total_tree != 0){
                                $default_total = $default * $land->company_land_total_tree;
                             }else{
                                $default_total = 0;
                             }

                            $budget_total = $land->overwrite_budget_per_tree * $land->company_land_total_tree;
                        @endphp
                        <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($budget_total,2, '.', ',')}}</td>
                    @else
                        @php
                            $overwrite_budget = false;

                            if($land->company_land_total_tree != 0){
                                $default_total = $default * $land->company_land_total_tree;
                             }else{
                                $default_total = 0;
                             }

                            $budget_total = $land->overwrite_budget_per_tree * $land->company_land_total_tree;
                        @endphp
                        <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($default_total,2, '.', ',')}}</td>
                    @endif

                    {{-- actual total tree --}}
                    @if(isset($actual[$land->company_land_id]))
                        @if($overwrite_budget)
                            @if($actual[$land->company_land_id] <= $budget_total)
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($actual[$land->company_land_id],2, '.', ',')}}</td>
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($budget_total - $actual[$land->company_land_id],2, '.', ',')}}</td>
                            @elseif($actual[$land->company_land_id] > $budget_total)
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($actual[$land->company_land_id],2, '.', ',')}}</td>
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($budget_total - $actual[$land->company_land_id],2, '.', ',')}}</td>
                            @endif
                        @else
                            @if($actual[$land->company_land_id] <= $default_total)
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($actual[$land->company_land_id],2, '.', ',')}}</td>
                                <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($default_total - $actual[$land->company_land_id],2, '.', ',')}}</td>
                            @elseif($actual[$land->company_land_id] > $default_total)
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($actual[$land->company_land_id],2, '.', '')}}</td>
                                <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format($default_total - $actual[$land->company_land_id],2, '.', ',')}}</td>
                            @endif
                        @endif
                    @else
                        <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{number_format(0,2)}}</td>
                        <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #fffde0;' : 'background-color: #fffde0;' }} border:1px solid #eee;">{{$overwrite_budget ? number_format($budget_total,2, '.', ',') : number_format($default_total,2, '.', ',')}}</td>
                    @endif

                    </tr>
                    @php
                        $company_land_index++;
                    @endphp
                @endforeach
                @php
                  $row_count++;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>
    <td class="p-2">
<b> - Budget = Formula budget + Expense budget </b>
    <br>
    <b> - Actual = Amount Used Up to Today</b>
    <br>
   <b> - Remaining = Budget Amount - Actual Amount</b>
    <br/>
    <b> Total Tree Section = Budget/Actual * Total Tree in the land</b>
    </td>
