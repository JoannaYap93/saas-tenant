<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if(!empty($companyExpense['company_expense_array']))
    @if (!@$component)
        <p><b>Company Expense Report {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    <table class="table" id="company-expense-table">
        <thead>
            <tr>
                <th style="background-color: #d8d9df; border:1px solid #eee" rowspan="3">Expense
                    Category</th>
                @if (isset($search['month']))
                    @foreach ($monthSel as $month_num => $month_name)
                        @php
                            $company_land_count = 0;
                        @endphp
                        @foreach ($companies as $key => $company)
                            @foreach($companyExpense['company_land']['land'][$month_num] as $result => $land)
                                @if ($result == $company->company_id)
                                    @if($land != 0)
                                        @foreach ($land as $land_name)
                                            @php
                                                $company_land_count++;
                                            @endphp
                                        @endforeach
                                    @else
                                        @php
                                            $company_land_count++;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        <th style="min-width: 220px; text-align: center; background-color: #ffffff; border:1px solid #eee"
                            colspan={{ $company_land_count }}>
                            {{ $month_name }}
                        </th>
                    @endforeach
                @endif
                @if (!isset($search['month']))
                    <th style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee;" colspan={{ count($companyLand) }}>{{$search['year']}}</th>
                @endif
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;" rowspan="3">GRAND TOTAL (RM)</th>
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @if (isset($search['month']))
                    @foreach ($monthSel as $month_num => $month_name)
                        @foreach ($companies as $key => $company)
                            @php
                                $land_count = 0;
                            @endphp
                            @foreach($companyExpense['company_land']['land'][$month_num] as $result => $land)
                                @if ($result == $company->company_id)
                                    @if($land != 0)
                                        @foreach ($land as $land_name)
                                            @php
                                                $land_count++;
                                            @endphp
                                        @endforeach
                                    @else
                                        @php
                                            $land_count++;
                                        @endphp
                                    @endif
                                @endif
                            @endforeach
                            <th style="text-align: center; background-color:#d8d9df; border:1px solid #eee"
                                colspan='{{$land_count}}'>
                                {{ $company->company_name }}
                            </th>
                        @endforeach
                        @php $col_count++; @endphp
                    @endforeach
                @endif
                @if (!isset($search['month']))
                    @foreach ($companies as $key => $company)
                        @php
                            $land_count = 0;
                        @endphp
                        @foreach ($companyLand as $val => $land)
                            @if ($company->company_id == $land->company_id)
                                @php
                                    $land_count++;
                                @endphp
                            @endif
                        @endforeach
                        <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"
                            colspan='{{$land_count}}'>
                            {{ $company->company_name }}
                        </th>
                        @php $col_count++; @endphp
                    @endforeach
                @endif
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @if (isset($search['month']))
                    @foreach ($monthSel as $month_num => $month_name)
                        @foreach ($companies as $key => $company)
                            @foreach($companyExpense['company_land']['land'][$month_num] as $result => $land)
                                @if ($result == $company->company_id)
                                    @if($land != 0)
                                        @foreach ($land as $land_name)
                                            <th style="min-width: 200px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                            colspan='1'>
                                                {{$land_name}}
                                            </th>
                                        @endforeach
                                    @else
                                        <th style="min-width: 200px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                        colspan='1'>
                                                -
                                        </th>
                                    @endif
                                @endif
                            @endforeach
                            @php
                                $col_count++;
                            @endphp
                        @endforeach
                    @endforeach
                @endif
                @if (!isset($search['month']))
                    @foreach ($companies as $key => $company)
                        @foreach ($companyLand as $val => $land)
                            @if ($company->company_id == $land->company_id)
                                <th style="min-width: 200px; text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee"
                                colspan='1'>
                                    {{ $land->company_land_name }}
                                </th>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($expenseCategory as $setting_expense_category)
                @php
                    $col_count = 0;
                @endphp
                <tr>
                    <td style="min-width: 220px; border:1px solid #eee; background-color: #e4e4e4;" class="table-secondary">
                        <b>{{ json_decode($setting_expense_category->setting_expense_category_name)->en }}{{$setting_expense_category->setting_expense_category_group == "General" ? " (Excluded)" : ""}}</b>
                    </td>
                    @if (isset($search['month']))
                        @foreach ($monthSel as $month_num => $month_name)
                            @foreach ($companies as $key => $company)
                                @foreach($companyExpense['company_land']['land'][$month_num] as $result => $land)
                                    @if ($result == $company->company_id)
                                        @if($land != 0)
                                            @foreach ($land as $land_id => $land_name)
                                                @if (isset($companyExpense['company_expense_array'][$setting_expense_category->setting_expense_category_id][$company->company_id][$month_num][$land_id]['company_expense_sum']) )
                                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                        @if (@$component)
                                                            <a class="popup" href="{{ route('company_expense_report_land_product', ['tenant' => tenant('id'), 'company_land_id' => $land_id, 'setting_expense_category_id' => $setting_expense_category->setting_expense_category_id, 'year' => @$search['year'], 'month_num' => $month_num])}}">
                                                                {{number_format($companyExpense['company_expense_array'][$setting_expense_category->setting_expense_category_id][$company->company_id][$month_num][$land_id]['company_expense_sum'],2, '.', ',')}}
                                                            </a>
                                                        @else
                                                            {{number_format($companyExpense['company_expense_array'][$setting_expense_category->setting_expense_category_id][$company->company_id][$month_num][$land_id]['company_expense_sum'],2, '.', ',')}}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                        @endif
                                    @endif
                                @endforeach
                                @php $col_count++; @endphp
                            @endforeach
                        @endforeach
                    @endif
                    @if (!isset($search['month']))
                        @foreach ($companies as $key => $company)
                            @foreach ($companyLand as $val => $land)
                                @if ($company->company_id == $land->company_id)
                                    @if (isset($companyExpense['company_total_by_category'][$company->company_id][$land->company_land_id][$setting_expense_category->setting_expense_category_id]['total_by_category']) )
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                            @if (@$component)
                                                @if(empty(@$search['month']))
                                                <a class="popup" href="{{ route('company_expense_report_land_product_total', ['tenant' => tenant('id'), 'company_land_id' => $land->company_land_id, 'setting_expense_category_id' => $setting_expense_category->setting_expense_category_id, 'year' => @$search['year']])}}">
                                                    {{number_format($companyExpense['company_total_by_category'][$company->company_id][$land->company_land_id][$setting_expense_category->setting_expense_category_id]['total_by_category'],2, '.', ',')}}
                                                </a>
                                                @else
                                                    {{number_format($companyExpense['company_total_by_category'][$company->company_id][$land->company_land_id][$setting_expense_category->setting_expense_category_id]['total_by_category'],2, '.', ',')}}
                                                @endif
                                            @else
                                                {{number_format($companyExpense['company_total_by_category'][$company->company_id][$land->company_land_id][$setting_expense_category->setting_expense_category_id]['total_by_category'],2, '.', ',')}}
                                            @endif
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endif

                    @if (isset($companyExpense['category_total'][$setting_expense_category->setting_expense_category_id]['total']))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{number_format($companyExpense['category_total'][$setting_expense_category->setting_expense_category_id]['total'] ,2, '.', ',')}}</b></td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" >TOTAL (RM)</th>
                @if (isset($search['month']))
                    @foreach ($monthSel as $month_num => $month_name)
                        @foreach ($companies as $key => $company)
                            @foreach($companyExpense['company_land']['land'][$month_num] as $result => $land)
                                @if ($result == $company->company_id)
                                    @if($land != 0)
                                        @foreach ($land as $land_id => $land_name)
                                            @if (isset($companyExpense['company_land_array'][$month_num][$company->company_id][$land_id]['company_expense_sum']) )
                                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                                    <b>{{ number_format($companyExpense['company_land_array'][$month_num][$company->company_id][$land_id]['company_expense_sum'],2, '.', ',') }}</b></td>
                                            @else
                                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>
                                            @endif
                                        @endforeach
                                    @else
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
                @if (!isset($search['month']))
                    @foreach ($companies as $key => $company)
                        @foreach ($companyLand as $val => $land)
                            @if ($company->company_id == $land->company_id)
                                @if (isset($companyExpense['company_land_total'][$company->company_id][$land->company_land_id]['land_total']))
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>{{ number_format($companyExpense['company_land_total'][$company->company_id][$land->company_land_id]['land_total'],2, '.', ',') }}</b></td>

                                @else
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                @endif
                @if (isset($companyExpense['company_total']))
                    <td style="min-width: 200px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{ number_format($companyExpense['company_total'],2, '.', ',')}}</b></td>
                @else
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
                @endif
            </tr>
        </tfoot>
    @else
        No Records!
    @endif
    </table>
</div>
