<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Profit &#38; Loss Reporting {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    @php
        $sum_total_sales = 0;
        $company_count = 0;
        $sum_total_cost_expense = 0;
        $sum_total_sales_category = 0;
        $gross_profit = [];
        $gross_expense = [];
        $total_sum_gross_profit = [];
        $total_profit = 0;
    @endphp
    <table class="table" id="company-expense-table">
        <thead>
            <tr>
                <th style="min-width: 220px; text-align: left; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary"><b>COMPANY</b></th>
                    @foreach ($companies as $key => $company)
                        <th style="max-width: 50px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
                        colspan='1'>
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
                <th style="max-width: 50px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
                colspan='1'>
                    <b>Total</b>
                </th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($recordsales["total_sales"]) || !empty($recordcost['total_cost']))
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>SALES</b></th>
            </tr>
                @foreach ($productcategory as $key => $value)
                    @if(isset($recordsales['data_product_category'][$value->product_category_id]))
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">
                                {{$recordsales['data_product_category'][$value->product_category_id]}}
                            </td>
                            @foreach ($companies as $key => $company)
                                @if(isset($recordsales['data_sales'][$company->company_id][$value->product_category_id]) && $recordsales['data_sales'][$company->company_id][$value->product_category_id] > 0)
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                        {{number_format($recordsales['data_sales'][$company->company_id][$value->product_category_id],2)}}
                                            @php
                                                if(isset($recordsales['data_sales'][$company->company_id][$value->product_category_id])){
                                                    $sum_total_sales += $recordsales['data_sales'][$company->company_id][$value->product_category_id];
                                                }else{
                                                    $sum_total_sales = $recordsales['data_sales'][$company->company_id][$value->product_category_id];
                                                }
                                            @endphp
                                    </td>
                                @else
                                    <td style="min-width: 120px; text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">-</td>
                                @endif
                            @endforeach
                            @if(isset($recordsales['total_sales'][$value->product_category_id]))
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    {{number_format($recordsales['total_sales'][$value->product_category_id],2)}}
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    -
                                </td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Subtotal Sales (RM)</b></th>
                @foreach ($companies as $key => $value)
                    @if(isset($recordsales['total_sales_company'][$value->company_id]) && $recordsales['total_sales_company'][$value->company_id] > 0)
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($recordsales['total_sales_company'][$value->company_id],2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                        @php
                            $gross_profit['data_gross_profit'][$value->company_id] = 0;
                        @endphp
                    @endif
                    @endforeach
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                        <b>{{number_format($sum_total_sales,2)}}</b>
                    </td>
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
                @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                    <b>{{number_format(array_sum(array_values($recordsales['total_roundup_company'])),2)}}</b>
                </td>
            </tr>
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Net Sales (RM)</b></th>
                @foreach ($companies as $key => $value)
                    @if(isset($recordsales['total_sales_company'][$value->company_id]) && $recordsales['total_sales_company'][$value->company_id] > 0)
                    @php
                        $grandtotal_sales = $recordsales['total_roundup_company'][$value->company_id] ? $recordsales['total_sales_company'][$value->company_id] + $recordsales['total_roundup_company'][$value->company_id] :$recordsales['total_sales_company'][$value->company_id];
                    @endphp
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($grandtotal_sales,2)}}</b>
                        </td>
                        @php
                            if(isset($gross_profit['data_gross_profit'][$value->company_id])){
                                $gross_profit['data_gross_profit'][$value->company_id] += $grandtotal_sales;
                            }else{
                                $gross_profit['data_gross_profit'][$value->company_id] = $grandtotal_sales;
                            }
                        @endphp
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                        @php
                            $gross_profit['data_gross_profit'][$value->company_id] = 0;
                        @endphp
                    @endif
                    @endforeach
                    @php
                        $grandtotal_sales_sum = $recordsales['total_roundup_company'] ? $sum_total_sales + array_sum(array_values($recordsales['total_roundup_company'])) : $sum_total_sales;
                    @endphp
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                        <b>{{number_format($grandtotal_sales_sum,2)}}</b>
                    </td>
            </tr>
            {{-- <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>EXPENSES</b></th>
            </tr> --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>Cost</b></th>
            </tr>
            @foreach ($expensecategory as $key => $value)
            @if($value->setting_expense_category_group == 'Cost')
                @if(isset($recordcost['expense_category_name'][$value->setting_expense_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1">
                            {{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}
                        </td>
                            @foreach ($companies as $key => $company)
                                @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                        {{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                            @php
                                                if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id])){
                                                    $sum_total_cost_expense += $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id];
                                                }else{
                                                    $sum_total_cost_expense = $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id];
                                                }
                                            @endphp
                                    </td>
                                @else
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            @if(isset($recordcost['total_cost'][$value->setting_expense_category_id]))
                                {{number_format($recordcost['total_cost'][$value->setting_expense_category_id],2)}}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                @endif
            @endif
            @endforeach
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>Expense</b></th>
            </tr>
            @foreach ($expensecategory as $key => $value)
            @if($value->setting_expense_category_group == 'Expense')
                @if(isset($recordcost['expense_category_name'][$value->setting_expense_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1">
                            {{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}
                        </td>
                            @foreach ($companies as $key => $company)
                                @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id]) && $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id] > 0)
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                        {{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id],2)}}
                                            @php
                                                if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id])){
                                                    $sum_total_cost_expense += $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id];
                                                }else{
                                                    $sum_total_cost_expense = $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id];
                                                }
                                            @endphp
                                    </td>
                                @else
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            @if(isset($recordcost['total_cost'][$value->setting_expense_category_id]))
                                {{number_format($recordcost['total_cost'][$value->setting_expense_category_id],2)}}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                @endif
            @endif
            @endforeach
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Total Expenses (RM)</b></th>
                    @foreach ($companies as $key => $value)
                        @if(isset($recordcost['total_expense_company'][$value->company_id]) && $recordcost['total_expense_company'][$value->company_id] > 0)
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($recordcost['total_expense_company'][$value->company_id],2)}}</b>
                            </td>
                            @php
                                $gross_expense['data_cost_month'][$value->company_id] = $recordcost['total_expense_company'][$value->company_id];
                            @endphp
                        @else
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">-</td>
                            @php
                                $gross_expense['data_cost_month'][$value->company_id] = 0;
                            @endphp
                        @endif
                    @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                    <b>{{number_format($sum_total_cost_expense,2)}}</b>
                </td>
            </tr>
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>GROSS PROFIT</b></th>
            </tr>
            <tr>
                <td style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Net Gross Profit (RM)</b></td>
                @foreach ($companies as $key => $value)
                    @if(isset($gross_profit['data_gross_profit'][$value->company_id]))
                        @php
                            if(isset($total_sum_gross_profit[$value->company_id])){
                                $total_sum_gross_profit[$value->company_id] += $gross_profit['data_gross_profit'][$value->company_id] - $gross_expense['data_cost_month'][$value->company_id];

                            }else{
                                $total_sum_gross_profit[$value->company_id] = $gross_profit['data_gross_profit'][$value->company_id] - $gross_expense['data_cost_month'][$value->company_id];
                            }
                        @endphp
                        @foreach($gross_profit as $final_profit)
                            @php
                                $total_profit += $total_sum_gross_profit[$value->company_id];
                            @endphp
                        @endforeach
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($total_sum_gross_profit[$value->company_id],2)}}</b>
                        </td>
                    @else
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                    @endif
                @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                    <b>{{number_format($total_profit,2)}}</b>
                </td>
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
<tfoot>
</tfoot>
</div>
