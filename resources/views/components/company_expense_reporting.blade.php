<div class="table-responsive">
    @if(!@$component)
        <p><b>Company Expense Reporting {{ $search['month_year'] }}</b></p>
    @endif
    <table class="table table-bordered" id="company_expense_table">
        <thead>
            <tr>
            @php $col_count = 0; @endphp
            <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="4"> Expense Category</th>
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="min-width: 220px; text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan={{ count($companyLand) * 3 }}>
                        {{ $month_name }}
                    </th>
                @endforeach
                <td colspan="4" style="background-color:#fffbaf; color:#000000 border:1px solid #eee" rowspan="4"><b>TOTAL</b></td>
                @php $col_count++; @endphp
            </tr>
            <tr>
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
                        <th style="min-width: 150px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan=3>
                            {{ $land->company_land_name }}
                        </th>
                    @endforeach
                    @php $col_count++; @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
        @php
            $total_expense_arr = [];
            $col_count = 1;
            $land_expense_sum_total = 0;
        @endphp
        @foreach ($category as $category)
        @php
            $expense_sum_total = 0;
         @endphp
            <tr>
            <td style="background-color: #e4e4e4; border:1px solid #eee"><b>{{ json_decode($category->setting_expense_category_name)->en }}</b></td>
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
                        @if(isset($companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum']))
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}  border:1px solid #eee" colspan=3><a class="popup" href="{{ route('company_expense_reporting_details', ['month' => $month_num, 'setting_expense_category_id' => $category->setting_expense_category_id])}}">
                                {{ ($companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum'] > 0 ? number_format($companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum'],2, '.', '') : '-') }}
                            </td>
                            @php
                            $expense_sum_total += $companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum'];
                            if(isset($total_expense_arr[$land->company_land_id][$month_num])){
                                $total_expense_arr[$land->company_land_id][$month_num]+= $companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum'];
                            } else{
                                $total_expense_arr[$land->company_land_id][$month_num] = $companyExpense[$category->setting_expense_category_id][$land->company_land_id][$month_num]['company_expense_sum'];
                            }
                            @endphp
                            @else
                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}  border:1px solid #eee" colspan=3>-</td>
                        @endif

                    @endforeach
                    @php $col_count++; @endphp
                @endforeach
            @if ($expense_sum_total > 0)
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{number_format($expense_sum_total ,2, '.', '')}}</b></td>
            @else
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
             @endif
            </tr>
            @php
            $land_expense_sum_total += $expense_sum_total;
        @endphp
       @endforeach
       @php
       $final_total = 0;
       @endphp

        </tbody>
        <tfoot>
            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee"><b>TOTAL</b></td>
            @foreach ($monthSel as $month_num => $month)
                @foreach ($companyLand as $key => $land)
                    @if (isset($total_expense_arr[$land->company_land_id][$month_num]))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="3"><b>{{ number_format($total_expense_arr[$land->company_land_id][$month_num],2, '.', '') }}</b></td>
                        @php
                            $final_total += $total_expense_arr[$land->company_land_id][$month_num];
                        @endphp
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="3">-</td>
                    @endif
                @endforeach
            @endforeach
            @if ($land_expense_sum_total == $final_total)
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{ number_format($final_total,2, '.', '') }}</b></td>
            @else
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
             @endif
        </tfoot>
    </table>
</div>
