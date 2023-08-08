<div class="table-responsive">
    <table class="table table-bordered" id="company-expense-table">

        @php
            $grand_total = 0;
            $grand_total_2 = 0;
        @endphp
            <thead>
                @if(!empty($records['expense_name']) || !empty($recordslastyear['expense_name']))
                    <tr>
                        @foreach ($settingExpense as $setting_expense => $setting)
                            @if(isset($records['expense_name'][$setting->setting_expense_id]))
                                <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee" colspan="6">
                                    {{json_decode($records['expense_name'][$setting->setting_expense_id])->en}}
                                </th>
                            @endif
                        @endforeach
                    </tr>
                @endif
                <tr>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "3">{{$search['year']}}&nbsp;</th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "3">{{$search['last_year']}}&nbsp;</th>
                </tr>
                <tr>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Farm Manager
                    </th>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Date Created
                    </th>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        TOTAL
                    </th>

                    {{-- last year --}}
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Farm Manager
                    </th>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        Date Created
                    </th>
                    <th style="max-width: 50px; text-align: center; background-color: #343a40; color: white; border:1px solid #eee">
                        TOTAL
                    </th>
                </tr>
            </thead>

            <tbody>
                @if(!empty($records["total_expense_by_product"]) || !empty($recordslastyear["total_expense_by_product"]))
                    @foreach ($companyExpense as $company_expense => $expense)
                        <tr>
                            {{-- this year --}}
                            @if (!empty($records['farm_manager_name']))
                                @if(isset($records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]))
                                        <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                            {{$records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]}}
                                        </td>
                                @endif
                            @endif
                                @if (!empty($records['date_created']))
                                        @if(isset($records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]))
                                                <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                    {{date_format(new DateTime(@$records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]), 'd-m-Y')}}
                                                </td>
                                        @endif
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
                            @endif

                        {{-- last year no result --}}
                        @if (!empty($records['farm_manager_name']))
                            @if(isset($records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]))
                                <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                    {{isset($recordslastyear['farm_manager_name'][$expense->user_id][$expense->company_expense_id]) ? $recordslastyear['farm_manager_name'][$expense->user_id][$expense->company_expense_id] : '-'}}
                                </td>
                            @endif
                        @endif
                        @if (!empty($records['date_created']))
                                @if(isset($records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]))
                                        <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                            {{isset($recordslastyear['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]) ? $recordslastyear['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id] : '-'}}
                                        </td>
                                @endif
                        @endif
                        @if (!empty($records['total_expense_by_product']))
                            @if(isset($records['total_expense_by_product'][$expense->company_expense_id]))
                                <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #fffbaf;' }} border:1px solid #eee">
                                    {{isset($recordslastyear['total_expense_by_product'][$expense->company_expense_id]) ? $recordslastyear['total_expense_by_product'][$expense->company_expense_id] : '-'}}
                                </td>
                            @endif
                        @endif

                            {{-- this year no result --}}
                            @if (!empty($recordslastyear['farm_manager_name']))
                                @if(isset($recordslastyear['farm_manager_name'][$expense->user_id][$expense->company_expense_id]))
                                    <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                        {{isset($records['farm_manager_name'][$expense->user_id][$expense->company_expense_id]) ? $records['farm_manager_name'][$expense->user_id][$expense->company_expense_id] : '-'}}
                                    </td>
                                @endif
                            @endif
                            @if (!empty($recordslastyear['date_created']))
                                @if(isset($recordslastyear['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]))
                                        <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                            {{isset($records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]) ? $records['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id] : '-'}}
                                        </td>
                                @endif
                            @endif
                            @if (!empty($recordslastyear['total_expense_by_product']))
                                    @if(isset($recordslastyear['total_expense_by_product'][$expense->company_expense_id]))
                                        <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #fffbaf;' }} border:1px solid #eee">
                                            {{isset($records['total_expense_by_product'][$expense->company_expense_id]) ? $records['total_expense_by_product'][$expense->company_expense_id] : '-'}}
                                        </td>
                                    @endif
                            @endif

                        {{-- last year --}}
                        @if (!empty($recordslastyear['farm_manager_name']))
                            @if(isset($recordslastyear['farm_manager_name'][$expense->user_id][$expense->company_expense_id]))
                                <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                    {{$recordslastyear['farm_manager_name'][$expense->user_id][$expense->company_expense_id]}}
                                </td>
                            @endif
                        @endif
                        @if (!empty($recordslastyear['date_created']))
                            @if(isset($recordslastyear['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]))
                                    <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                        {{date_format(new DateTime(@$recordslastyear['date_created'][$expense->company_expense_day][$expense->company_expense_month][$expense->company_expense_year][$expense->company_expense_id]), 'd-m-Y')}}
                                    </td>
                            @endif
                        @endif
                        @if (!empty($recordslastyear['total_expense_by_product']))
                                @if(isset($recordslastyear['total_expense_by_product'][$expense->company_expense_id]))
                                    <td style="text-align: center; {{ $component % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #fffbaf;' }} border:1px solid #eee">
                                        {{number_format($recordslastyear['total_expense_by_product'][$expense->company_expense_id],2)}}
                                    </td>
                                    @php
                                    $total_expense_by_product = $recordslastyear['total_expense_by_product'][$expense->company_expense_id];
                                    $grand_total_2 += $total_expense_by_product;
                                    @endphp
                                @endif
                        @endif
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6">No Records!</td>
                    </tr>
                @endif
            <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="2"><b>TOTAL (RM)</b></td>
            <th style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                <b>{{ ($grand_total ? number_format($grand_total,2, '.', '') : '-') }}</b>
            </th>
                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="2"><b>TOTAL (RM)</b></td>
                    <th style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                        <b>{{ ($grand_total_2 ? number_format($grand_total_2,2, '.', '') : '-') }}</b>
                    </th>
                </tbody>
    </table>
</div>
