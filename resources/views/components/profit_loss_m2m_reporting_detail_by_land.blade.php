<div class="table-responsive">
    <table class="table table-bordered" id="company-expense-table">
        @php
            $grand_total = 0;
        @endphp
            <thead>
                @if(!empty($records['expense_name']))
                    <tr>
                        @foreach ($settingExpense as $setting_expense => $setting)
                            @if(isset($records['expense_name'][$setting->setting_expense_id]))
                                <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee" colspan="2">
                                    {{json_decode($records['expense_name'][$setting->setting_expense_id])->en}}
                                </th>
                            @endif
                        @endforeach
                            <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee" rowspan="2">
                                TOTAL
                            </th>
                    </tr>
                @endif
                <tr>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Farm Manager
                    </th>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Date Created
                    </th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($companyExpense as $company_expense => $expense)
                        <tr>
                            @if (!empty($records['farm_manager_name']))
                                @if(isset($records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]))
                                    <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                        {{$records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]}}
                                    </td>
                                @endif
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                                    -
                                </td>
                            @endif
                            @if (!empty($records['date_created']))
                                    @if(isset($records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]))
                                            <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                {{date_format(new DateTime(@$records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]), 'd-m-Y')}}
                                            </td>
                                    @endif
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                                    -
                                </td>
                            @endif
                            @if (!empty($records['total_expense_by_product']))
                                @if(isset($records['total_expense_by_product'][$expense->company_expense_id]))
                                    <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #fffbaf;' }} border:1px solid #eee">
                                        {{number_format($records['total_expense_by_product'][$expense->company_expense_id],2)}}
                                    </td>
                                    @php
                                    $total_expense_by_product = $records['total_expense_by_product'][$expense->company_expense_id];
                                    $grand_total += $total_expense_by_product;
                                    @endphp
                                @endif
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                                    -
                                </td>
                            @endif
                        </tr>
                    @endforeach
            </tbody>
                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="2"><b>TOTAL (RM)</b></td>
                    <th style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                        <b>{{ ($grand_total ? number_format($grand_total,2, '.', '') : '-') }}</b>
                    </th>
    </table>
</div>
