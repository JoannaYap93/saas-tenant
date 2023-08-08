<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Profit &#38; Loss Reporting {{ $search['year'] }}</b></p>
        <br><br>
    @endif
        @php
            $company_count = 0;
            $company_count2 = 0;
            $total_sum_sales = 0;
            $subtotal_sum_sales = 0;
            $total_sum_sales_last_year = 0;
            $subtotal_sum_sales_last_year = 0;
            $total_sum_expense = 0;
            $total_sum_expense_last_year = 0;
            $sum_gross_profit = 0;
            $sum_gross_profit_last_year = 0;
        @endphp
        <table class="table" id="company-expense-table">
            <thead>
                <tr>
                    <th style="min-width: 220px; text-align: left; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary"><b>COMPANY</b></th>
                    @foreach ($companies as $key => $company)
                        <th style="max-width: 50px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
                        colspan='2'>
                            @php
                                if(isset($company))
                                {
                                    $company_count += 1;
                                }else{
                                    $company_count = 1;
                                }
                            @endphp
                                {{ $company->company_name }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th style="min-width: 220px; text-align: left; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary"><b>Year</b></th>
                @foreach ($companies as $key => $company)
                        @php
                            if(isset($company))
                            {
                                $company_count2 += 1;
                            }else{
                                $company_count2 = 1;
                            }
                        @endphp
                @endforeach
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">{{$search['year']}}&nbsp;</th>
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">{{$search['last_year']}}&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($recordsales["total_sales"]) || !empty($recordcost['total_cost']))
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>SALES</b></th>
                    </tr>
                    @foreach($productcategory as $key => $value)
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">
                                {{$value->product_category_name}}
                            </td>
                                @foreach ($companies as $key => $company)
                                    @if(isset($recordsales['data_sales'][$company->company_id][$value->product_category_id]) && $recordsales['data_sales'][$company->company_id][$value->product_category_id] > 0)
                                        <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                            {{number_format($recordsales['data_sales'][$company->company_id][$value->product_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="min-width: 120px; text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">-</td>
                                    @endif

                                    @if(isset($recordlastyearsales['data_sales'][$company->company_id][$value->product_category_id]) && $recordlastyearsales['data_sales'][$company->company_id][$value->product_category_id] > 0)
                                        <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                            {{number_format($recordlastyearsales['data_sales'][$company->company_id][$value->product_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="min-width: 120px; text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">-</td>
                                    @endif
                                @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Subtotal Sales (RM)</b></th>
                        @foreach ($companies as $key => $value)
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                        if(isset($recordsales['total_sales_company'][$value->company_id]) ){
                                            $subtotal_sum_sales += $recordsales['total_sales_company'][$value->company_id];
                                        }else{
                                            $subtotal_sum_sales = 0;
                                        }
                                    @endphp
                                    <b>{{number_format($subtotal_sum_sales,2)}}</b>
                                </td>

                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                        if(isset($recordlastyearsales['total_sales_company'][$value->company_id])){
                                            $subtotal_sum_sales_last_year += $recordlastyearsales['total_sales_company'][$value->company_id];
                                        }else{
                                            $subtotal_sum_sales_last_year = 0;
                                        }

                                    @endphp
                                    <b>{{number_format($subtotal_sum_sales_last_year,2)}}</b>
                                </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>R. Up/Down</b></th>
                        @foreach ($companies as $key => $value)
                            @if(isset($recordsales['total_roundup_company'][$value->company_id]) && $recordsales['total_roundup_company'][$value->company_id] != 0)
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    <b>{{number_format($recordsales['total_roundup_company'][$value->company_id],2)}}</b>
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @endif
                            @if(isset($recordlastyearsales['total_roundup_company'][$value->company_id]) && $recordlastyearsales['total_roundup_company'][$value->company_id] != 0)
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    <b>{{number_format($recordlastyearsales['total_roundup_company'][$value->company_id],2)}}</b>
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Net Sales (RM)</b></th>
                        @foreach ($companies as $key => $value)
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                        if(isset($recordsales['total_sales_company'][$value->company_id]) ){
                                            $total_sum_sales += $recordsales['total_roundup_company'][$value->company_id] ? $recordsales['total_sales_company'][$value->company_id] + $recordsales['total_roundup_company'][$value->company_id] : $recordsales['total_sales_company'][$value->company_id];
                                        }else{
                                            $total_sum_sales = 0;
                                        }
                                    @endphp
                                    <b>{{number_format($total_sum_sales,2)}}</b>
                                </td>

                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                        if(isset($recordlastyearsales['total_sales_company'][$value->company_id])){
                                            $rounding_last_year = $recordlastyearsales['total_roundup_company'][$value->company_id] ?? 0;
                                            $total_sum_sales_last_year += $recordlastyearsales['total_roundup_company'][$value->company_id] ? $recordlastyearsales['total_sales_company'][$value->company_id] + $recordlastyearsales['total_roundup_company'][$value->company_id] : $recordlastyearsales['total_sales_company'][$value->company_id];
                                        }else{
                                            $total_sum_sales_last_year = 0;
                                        }

                                    @endphp
                                    <b>{{number_format($total_sum_sales_last_year,2)}}</b>
                                </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>Cost</b></th>
                    @foreach ($expensecategory as $key => $value)
                    @if($value->setting_expense_category_group == 'Cost')
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1">
                                {{json_decode($value->setting_expense_category_name)->en}}
                            </td>
                                @foreach ($companies as $key => $company)
                                    @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                            {{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                    @endif

                                    @if(isset($recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                            {{number_format($recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                    @endif
                                @endforeach
                        </tr>
                    @endif
                    @endforeach
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>Expense</b></th>
                    @foreach ($expensecategory as $key => $value)
                    @if($value->setting_expense_category_group == 'Expense')
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1">
                                {{json_decode($value->setting_expense_category_name)->en}}
                            </td>
                                @foreach ($companies as $key => $company)
                                    @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                            {{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                    @endif

                                    @if(isset($recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                            {{number_format($recordcostlastyear['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                    @endif
                                @endforeach
                        </tr>
                    @endif
                    @endforeach
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total Expenses (RM)</b></th>
                        @foreach ($companies as $key => $value)
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                @php
                                    if(isset($recordcost['total_expense_company'][$value->company_id])){
                                        $total_sum_expense += $recordcost['total_expense_company'][$value->company_id];
                                    }else{
                                        $total_sum_expense = 0;
                                    }
                                @endphp
                                <b>{{number_format($total_sum_expense,2)}}</b>
                            </td>

                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                @php
                                    if(isset($recordcostlastyear['total_expense_company'][$value->company_id])){
                                        $total_sum_expense_last_year += $recordcostlastyear['total_expense_company'][$value->company_id];
                                    }else{
                                        $total_sum_expense_last_year = 0;
                                    }
                                @endphp
                                <b>{{number_format($total_sum_expense_last_year,2)}}</b>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>GROSS PROFIT</b></th>
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Net Gross Profit (RM)</b></th>
                        @foreach ($companies as $key => $value)
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                        $sum_gross_profit = $total_sum_sales - $total_sum_expense;
                                    @endphp
                                    <b>{{number_format($sum_gross_profit,2)}}</b>
                                </td>

                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    @php
                                            $sum_gross_profit_last_year = $total_sum_sales_last_year - $total_sum_expense_last_year;
                                    @endphp
                                    <b>{{number_format($sum_gross_profit_last_year,2)}}</b>
                                </td>
                        @endforeach
                    </tr>
                @else
                    <tr>
                        <td colspan="5">No Records!</td>
                    </tr>
                @endif
            </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
