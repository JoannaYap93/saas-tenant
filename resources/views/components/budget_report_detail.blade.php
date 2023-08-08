{{-- IFRAME SUPER ADMIN & MAIN REPORT ADMIN --}}
<div class="card">
    <div class="card-body">
<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Budget Report </b></p>
        <br><br>
    @endif
    <table class="table table-nowrap table-bordered" id="budget-report-table">
        <thead>
            <tr>
                <th rowspan="2" style="background-color: #e4e4e4; border:1px solid #eee;">Budget Items</th>
                @php
                    $col_count = 0;
                @endphp
                @foreach ($companyLand as $company_land )
                    <th colspan="3" style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                        {{$company_land->company_land_name}}</th>
                @php
                    $col_count++;
                @endphp
                @endforeach
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @foreach ($companyLand as $company_land )
                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">Budget</th>
                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">Actual</th>
                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">Remaining</th>
                @php
                    $col_count++;
                @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $budget = 0;
                $actual = 0;
                $remaining = 0;
                $total_budget = array();
                $total_actual = array();
                $total_remaining = array();
                $overwrite_budget = false;
            @endphp

            @if (isset($category['formula']) && !empty($category['formula']))
                @foreach ($category['formula'] as $f)
                    <tr>
                        <td style="text-align: left; background-color: #ffff; border:1px solid #eee;">{{json_decode($f->setting_formula_category_name)->en}}</td>
                        @php
                            $col_count = 0;
                        @endphp
                        @foreach ($companyLand as $company_land )
                        @if (isset($overwriteBudget['formula'][$company_land->company_land_id][$f->setting_formula_category_id]))
                            @php
                                $budget = $overwriteBudget['formula'][$company_land->company_land_id][$f->setting_formula_category_id];
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{number_format($overwriteBudget['formula'][$company_land->company_land_id][$f->setting_formula_category_id],2, '.', ',')}} <small style="color:black;">(OV)</small></td>
                        @elseif($company_land->company_land_total_tree != 0)

                            @php
                                // $budget = $f->setting_formula_category_budget;
                                if($company_land->company_land_total_tree != 0){
                                    $budget = $f->setting_formula_category_budget;
                                }else{
                                    $budget = 0;
                                }
                                // $budget = $overwrite_budget;
                                // $overwrite_budget;
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{number_format($f->setting_formula_category_budget,2, '.', ',')}}
                                {{-- {{number_format($overwrite_budget,2, '.', ',')}} --}}
                            </td>
                        @else
                        @php
                        // $budget = $f->setting_formula_category_budget;
                        if($company_land->company_land_total_tree != 0){
                            $budget = $f->setting_formula_category_budget;
                        }else{
                            $budget = 0;
                        }
                        // $budget = $overwrite_budget;
                        // $overwrite_budget;
                    @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{ '0.00' }}
                                {{-- {{number_format($overwrite_budget,2, '.', ',')}} --}}
                            </td>
                        @endif


                        @if (isset($overwriteBudget['actual_formula'][$company_land->company_land_id][$f->setting_formula_category_id]))
                            @php
                                $actual = $overwriteBudget['actual_formula'][$company_land->company_land_id][$f->setting_formula_category_id]/$company_land->company_land_total_tree;
                             if($company_land->company_land_total_tree != 0){
                                $actual_total = $overwriteBudget['actual_formula'][$company_land->company_land_id][$f->setting_formula_category_id]/$company_land->company_land_total_tree;
                             }else{
                                $actual_total = 0;
                             }
                            @endphp
                            @if ($actual_total <= $budget)
                                <td style="color:green; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                    {{number_format($actual,2, '.', '')}}</td>
                            @else
                                <td style="color:red; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                    {{number_format($actual,2, '.', '')}}</td>
                            @endif
                        @else
                            @php $actual_total = 0; @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{number_format($actual,2, '.', ',')}}</td>
                        @endif

                            {{-- remaining --}}
                        @if ($budget <= $actual_total)
                            <td style="color:red; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                            {{number_format($budget - $actual_total,2, '.', ',')}} </td>
                            @php $remaining = $budget - $actual_total; @endphp
                        @elseif ($budget > $actual_total)
                            <td style="color:green; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                            {{number_format($budget - $actual_total,2, '.', ',')}} </td>
                            @php $remaining = $budget - $actual_total; @endphp
                        @else
                            @php $remaining = 0; @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{number_format(0,2, '.', ',')}}</td>
                        @endif
                            {{-- end of remaining --}}

                        @php
                            $col_count++;
                            if (isset($total_budget[$company_land->company_land_id]))
                                $total_budget[$company_land->company_land_id] += $budget;
                            else
                                $total_budget[$company_land->company_land_id] = $budget;

                            if (isset($total_actual[$company_land->company_land_id]))
                                $total_actual[$company_land->company_land_id] += $actual_total;
                            else
                                $total_actual[$company_land->company_land_id] = $actual_total;

                            if (isset($total_remaining[$company_land->company_land_id]))
                                $total_remaining[$company_land->company_land_id] += $remaining;
                            else
                                $total_remaining[$company_land->company_land_id] = $remaining;
                        @endphp
                        @endforeach
                    </tr>
                @endforeach
            @endif

            @if (isset($category['expense']) && !empty($category['expense']))
                @foreach ($category['expense'] as $e)
                    <tr>
                        <td style="text-align: left; background-color: #ffff; border:1px solid #eee;">{{json_decode($e->setting_expense_category_name)->en}}</td>
                        @php
                            $col_count = 0;
                        @endphp
                        @foreach ($companyLand as $company_land )
                        @if (isset($overwriteBudget['expense'][$company_land->company_land_id][$e->setting_expense_category_id]))
                            @php
                                $budget = $overwriteBudget['expense'][$company_land->company_land_id][$e->setting_expense_category_id];
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{number_format($overwriteBudget['expense'][$company_land->company_land_id][$e->setting_expense_category_id],2, '.', ',')}} <small style="color:black;">(OV)</small></td>
                        @elseif($company_land->company_land_total_tree != 0)

                            @php
                                // $budget = $e->setting_expense_category_budget;
                                // if($company_land->company_land_total_tree != 0){
                                    $budget = $e->setting_expense_category_budget;
                                // }else{
                                //     $budget = 0;
                                // }
                                // $budget = $overwrite_budget;
                                // $overwrite_budget;
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{number_format($e->setting_expense_category_budget,2, '.', ',')}}
                                {{-- {{number_format($overwrite_budget,2, '.', ',')}} --}}
                            </td>
                        @else
                            @php
                                // $budget = $f->setting_formula_category_budget;
                                if($company_land->company_land_total_tree != 0){
                                    $budget = $f->setting_formula_category_budget;
                                }else{
                                    $budget = 0;
                                }
                                // $budget = $overwrite_budget;
                                // $overwrite_budget;
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                {{ '0.00'}}
                                {{-- {{number_format($overwrite_budget,2, '.', ',')}} --}}
                            </td>
                        @endif
                        @if (isset($overwriteBudget['actual_expense'][$company_land->company_land_id][$e->setting_expense_category_id]))
                            @php
                                // $actual_total = $overwriteBudget['actual_expense'][$company_land->company_land_id][$e->setting_expense_category_id];
                            $actual_total = $overwriteBudget['actual_expense'][$company_land->company_land_id][$e->setting_expense_category_id]/$company_land->company_land_total_tree;
                            if($company_land->company_land_total_tree != 0){
                            $actual_total = $overwriteBudget['actual_expense'][$company_land->company_land_id][$e->setting_expense_category_id]/$company_land->company_land_total_tree;
                            }else{
                            $actual_total = 0;
                            }
                            @endphp
                            @if ($actual_total <= $budget)
                                <td style="color:green; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                    {{number_format($actual_total,2, '.', ',')}}</td>
                            @else
                                <td style="color:red; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                                    {{number_format($actual_total,2, '.', ',')}}</td>
                            @endif
                        @else
                            @php
                                $actual_total = 0;
                            @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{number_format($actual,2, '.', ',')}}</td>
                        @endif

                        {{-- remaining --}}
                        @if ($budget <= $actual_total)
                            <td style="color:red; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                            {{number_format($budget - $actual_total,2, '.', ',')}} </td>
                            @php $remaining = $budget - $actual_total; @endphp
                        @elseif ($budget > $actual_total)
                            <td style="color:green; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                            {{number_format($budget - $actual_total,2, '.', ',')}} </td>
                            @php $remaining = $budget - $actual_total; @endphp
                        @else
                            @php $remaining = 0; @endphp
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{number_format(0,2, '.', ',')}}</td>
                        @endif
                        {{-- end of remaining --}}

                        @php
                            $col_count++;
                            if (isset($total_budget[$company_land->company_land_id]))
                                $total_budget[$company_land->company_land_id] += $budget;
                            else
                                $total_budget[$company_land->company_land_id] = $budget;

                            if (isset($total_actual[$company_land->company_land_id]))
                                $total_actual[$company_land->company_land_id] += $actual_total;
                            else
                                $total_actual[$company_land->company_land_id] = $actual_total;

                            if (isset($total_remaining[$company_land->company_land_id]))
                                $total_remaining[$company_land->company_land_id] += $remaining;
                            else
                                $total_remaining[$company_land->company_land_id] = $remaining;
                        @endphp
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">TOTAL</td>
                @foreach ($companyLand as $company_land )
                    @if (isset($total_actual[$company_land->company_land_id]) && isset($total_budget[$company_land->company_land_id]))
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{number_format($total_budget[$company_land->company_land_id],2, '.', ',')}}</td>
                        @if( $total_actual[$company_land->company_land_id] <= $total_budget[$company_land->company_land_id])
                            <td style="color:green; text-align: center; font-weight: bold; background-color: #fffbaf; border:1px solid #eee;">{{number_format($total_actual[$company_land->company_land_id],2, '.', ',')}}</td>
                            <td style="color:green; text-align: center; font-weight: bold; background-color: #fffbaf; border:1px solid #eee;">{{number_format($total_remaining[$company_land->company_land_id],2, '.', ',')}}</td>
                        @else
                            <td style="color:red; text-align: center; font-weight: bold; background-color: #fffbaf; border:1px solid #eee;">{{number_format($total_actual[$company_land->company_land_id],2, '.', ',')}}</td>
                            <td style="color:red; text-align: center; font-weight: bold; background-color: #fffbaf; border:1px solid #eee;">{{number_format($total_remaining[$company_land->company_land_id],2, '.', ',')}}</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        </tfoot>
    </table>
</div>
    <td class="p-2">
    <b>
    REMARK </b><br>
    - Budget = Formula budget + Expense budget
    <br>
    - Budget with OV = Overwrite Formula / Expense
    <br>
    - Actual = Amount Used Up to Today
    <br>
    - Remaining = Budget Amount - Actual Amount
    </td>
    </div>
</div>
