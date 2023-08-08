<div class="table-responsive">
    @if (@$companyExpenseDetail)
        <table class="table" id="company-expense-table">
            <thead>
                <tr>
                    <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="4">Expense Category</th>
                    @foreach ($monthSel as $month_num => $month_name)
                        <th style="text-align: center; {{ $month_num % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                            colspan={{ count($companyLand) * 3 }}>
                            {{ $month_name }}
                        </th>
                    @endforeach
                    <th colspan={{ count($companyLand) * 3 }}
                        style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">TOTAL
                    </th>
                    <th rowspan="4"
                        style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">GRAND TOTAL
                    </th>
                </tr>
                <tr>
                    @php
                        $col_count = 0;
                    @endphp
                    @foreach ($monthSel as $month_num => $month)
                        @php
                            $company_count = [];
                            $company_name = [];
                        @endphp
                        @foreach ($companyLand as $key => $land)
                            @php
                                if (isset($company_count[$land->company->company_id])) {
                                    $company_count[$land->company->company_id] += 1;
                                } else {
                                    $company_count[$land->company->company_id] = 1;
                                }

                                $company_name[$land->company->company_id] = $land->company->company_name;

                            @endphp
                        @endforeach
                        @foreach ($company_name as $key => $name)
                            <th style="text-align: center; {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                colspan={{ $company_count[$key] * 3 }}>
                                {{ $name }}
                            </th>
                        @endforeach
                        @php $col_count++; @endphp
                    @endforeach
                    @foreach ($company_name as $key => $name)
                            <th style="text-align: center; font-weight: bold; background-color:#fdfacb; color:#000000; border:1px solid #eee"
                                colspan={{ $company_count[$key] * 3 }}>
                                {{ $name }}
                            </th>
                    @endforeach

                </tr>
                <tr>
                    @php $col_count = 0; @endphp
                    @foreach ($monthSel as $month_num => $month)
                        @foreach ($companyLand as $key => $land)
                            <th style="min-width: 120px; text-align: center; {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                colspan=3>
                                {{ $land->company_land_name }}
                            </th>

                        @endforeach
                        @php $col_count++; @endphp
                    @endforeach
                    @foreach ($companyLand as $key => $land)
                            <th style="min-width: 120px; text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee"
                                colspan=3>
                                {{ $land->company_land_name }}
                            </th>
                            @php $col_count++; @endphp
                        @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $col_count = 0;
                    $total_company_expense_by_company_land = [];
                    $total_sum_company_expense_by_setting_expense = 0;
                    $grand_total_company_land =[];
                @endphp
                @foreach ($expenseCategory as $setting_expense_category)
                    <tr>
                        <th style="text-align: left; border:1px solid #eee" class="table-secondary">
                            {{ json_decode($setting_expense_category->setting_expense_category_name)->en }}</th>
                        <td style="text-align: center; border:1px solid #eee" class="table-secondary"
                            colspan={{ (count($monthSel) + 2) * (count($companyLand) * 3) }}>
                            {{ json_decode($setting_expense_category->setting_expense_category_name)->en }}</td>
                    </tr>
                    @foreach ($settingExpense as $setting_expense)
                        @php
                            $total_company_expense_by_setting_expense = 0;
                            $grand_total_company_expense_by_company_land = [];
                        @endphp
                        @if ($setting_expense->setting_expense_category_id == $setting_expense_category->setting_expense_category_id)
                            <tr>
                                <td style="text-align: right; background-color: #e4e4e4; border:1px solid #eee ">
                                    {{ json_decode($setting_expense->setting_expense_name)->en }}</td>
                                @foreach ($monthSel as $month_num => $month)
                                    @foreach ($companyLand as $key => $land)
                                        @if (isset($companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num]) && $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num] > 0)
                                            <td style="min-width: 120px; text-align: center; {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                                colspan=3>
                                                {{ $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num] > 0 ? number_format($companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num], 2) : '-' }}
                                            </td>
                                            @php
                                                if (isset($total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num])) {
                                                    $total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num] += $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num];
                                                } else {
                                                    $total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num] = $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num];
                                                }

                                                // remark; calculate grand total for each company by land
                                                if (isset($grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id])) {
                                                    $grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id] += $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num];
                                                } else {
                                                    $grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id] = $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num];
                                                }

                                                $total_company_expense_by_setting_expense += $companyExpenseDetail[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$land->company->company_id][$land->company_land_id][$month_num];
                                            @endphp
                                        @else
                                            <td style="min-width: 120px; text-align: center; {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                                                colspan=3>
                                                -
                                            </td>
                                        @endif
                                    @endforeach

                                    @php $col_count++; @endphp
                                @endforeach
                                        {{-- remark; to calculate total for each company by land --}}
                                @foreach ($companyLand as $key => $land)

                                        @if (isset($grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id]))
                                        {{-- @dump($grand_total_company_expense_by_company_land[$land->company->company_id]) --}}
                                            <td style="min-width: 120px; text-align: center; font-weight: bold; background-color:#fdfacb; color:#000000; border:1px solid #eee"
                                                colspan=3>
                                                {{ $grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id] > 0 ? number_format($grand_total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id], 2) : '-' }}
                                            </td>

                                        @else
                                            <td style="min-width: 120px; text-align: center; font-weight: bold; background-color:#fdfacb; color:#000000; border:1px solid #eee"
                                                colspan=3>
                                                -
                                            </td>
                                        @endif

                                @endforeach

                                @php
                                    $total_sum_company_expense_by_setting_expense += $total_company_expense_by_setting_expense;
                                @endphp
                                <td
                                    style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                    {{ $total_company_expense_by_setting_expense > 0 ? number_format($total_company_expense_by_setting_expense, 2) : '-' }}
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
                    <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>TOTAL</b></td>
                    @foreach ($monthSel as $month_num => $month)
                        @foreach ($companyLand as $key => $land)
                            <th style="min-width: 120px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee"
                                colspan=3>
                                @if (isset($total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num]))
                                    {{ number_format($total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num], 2) }}
                                    @php
                                        $total_sum_company_expense_by_company_land += $total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num];
                                    @endphp
                                    @if (isset($grand_total_company_land[$land->company->company_id][$land->company_land_id]))
                                        @php
                                            $grand_total_company_land[$land->company->company_id][$land->company_land_id] = $total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num];
                                        @endphp
                                    @else
                                        @php
                                             $grand_total_company_land[$land->company->company_id][$land->company_land_id] = $total_company_expense_by_company_land[$land->company->company_id][$land->company_land_id][$month_num];
                                        @endphp
                                    @endif
                                @else
                                    -
                                @endif

                            </th>
                        @endforeach
                    @endforeach
                    @foreach ($companyLand as $key => $land)
                            @if (isset($grand_total_company_land[$land->company->company_id][$land->company_land_id]))
                                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000"
                                    colspan=3>
                                    {{ $grand_total_company_land[$land->company->company_id][$land->company_land_id] > 0 ? number_format($grand_total_company_land[$land->company->company_id][$land->company_land_id], 2) : '-' }}
                                </td>
                            @else
                                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000"
                                    colspan=3>
                                    -
                                </td>
                            @endif

                            {{-- @php $col_count++; @endphp --}}
                    @endforeach
                    <th
                        style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                        {{ $total_sum_company_expense_by_setting_expense == $total_sum_company_expense_by_company_land ? number_format($total_sum_company_expense_by_company_land, 2) : '-' }}
                    </th>
                </tr>
            </tfoot>
        </table>
    @else
        No Records!
    @endif
</div>
