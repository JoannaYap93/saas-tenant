<div class="table-responsive">
    @if(@$companyExpenseDetail)
    <table class="table table-bordered" id="company_expense_table">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="4">Expense Category</th>
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan={{ count($companyLand) * 3 }}>
                        {{ $month_name }}
                    </th>
                @endforeach
                <th rowspan="4" style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">TOTAL</th>
            </tr>
            <tr>
                @php $col_count = 0; @endphp
                @foreach ($monthSel as $month_num => $month)
                    @foreach ($companyLand as $key => $land)
                        <th style="min-width: 120px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan=3>
                            {{ $land->company_land_name }}
                        </th>

                    @endforeach
                    @php $col_count++; @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $col_count = 0;
                $total_company_expense_by_company_land = array();
                $total_sum_company_expense_by_setting_expense = 0;
            @endphp
         
            @foreach($expenseCategory as $setting_expense_category)
                <tr>
                    <th style="text-align: center; border:1px solid #eee" class="table-secondary">{{ json_decode($setting_expense_category->setting_expense_category_name)->en }}</th>
                    <td style="text-align: center; border:1px solid #eee" class="table-secondary" colspan={{ (count($monthSel)+ 1) * (count($companyLand) *3) }}>{{ json_decode($setting_expense_category->setting_expense_category_name)->en }}</td>
                </tr>
                @foreach($settingExpense as $setting_expense)
                    @php
                        $total_company_expense_by_setting_expense = 0;
                    @endphp
                    @if ($setting_expense->setting_expense_category_id == $setting_expense_category->setting_expense_category_id)
                        <tr>
                            <td style="text-align: center; background-color: #e4e4e4; border:1px solid #eee " >{{ json_decode($setting_expense->setting_expense_name)->en }}</td>
                            @foreach ($monthSel as $month_num => $month)
                                @foreach($companyLand as $key => $land)
                                    @if (isset( $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num]))
                                        <td style="min-width: 120px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan=3>
                                            {{ ($companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num] > 0 ? number_format($companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num],2, '.', '') : '-')}}
                                        </td>
                                        @php
                                            if(isset($total_company_expense_by_company_land[$land->company_land_id][$month_num])){
                                                $total_company_expense_by_company_land[$land->company_land_id][$month_num] += $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num] ;
                                            }
                                            else{
                                                $total_company_expense_by_company_land[$land->company_land_id][$month_num] = $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num] ;
                                            }

                                            $total_company_expense_by_setting_expense += $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company_land_id][$month_num];
                                        @endphp
                                    @else
                                        <td style="min-width: 120px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan=3>
                                            -
                                        </td>
                                    @endif

                                @endforeach
                                @php $col_count++; @endphp
                            @endforeach
                            @php
                                $total_sum_company_expense_by_setting_expense += $total_company_expense_by_setting_expense;
                            @endphp
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($total_company_expense_by_setting_expense > 0 ? number_format($total_company_expense_by_setting_expense, 2, '.', '') : '-') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            @php
                $total_sum_company_expense_by_company_land = 0;
            @endphp
        </tbody>
        <tfoot>
            <tr>
                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" ><b>TOTAL</b></td>
                @foreach ($monthSel as $month_num => $month)
                    @foreach($companyLand as $key => $land)
                        <th style="min-width: 120px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan=3>
                            @if (isset($total_company_expense_by_company_land[$land->company_land_id][$month_num]))
                                {{ number_format($total_company_expense_by_company_land[$land->company_land_id][$month_num],2)}}
                                @php
                                    $total_sum_company_expense_by_company_land  += $total_company_expense_by_company_land[$land->company_land_id][$month_num];
                                @endphp
                            @else
                                -
                            @endif
                        </th>
                    @endforeach
                @endforeach
                <th style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                    <b>{{ ($total_sum_company_expense_by_setting_expense == $total_sum_company_expense_by_company_land ? number_format($total_sum_company_expense_by_company_land,2, '.', '') : '-') }}</b>
                </th>
            </tr>
        </tfoot>
    </table>
    @else
        <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
