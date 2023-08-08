<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Profit &#38; Loss Reporting {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    <table class="table" id="company-expense-table">
        <thead>
            {{-- Month --}}
            <tr>
                <th style="min-width: 220px; text-align: left; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary" rowspan="2"><b>COMPANY</b></th>
                @php
                    $col_count = 0;
                    $sum_total_sales = [];
                    $company_count = 0;
                    $sum_total_cost_expense = [];
                    $sum_total_sales_category = 0;
                    $gross_profit = [];
                    $total_profit = [];
                    $sum_cost_expense_month = [];
                    $total_sum_gross_profit = [];
                    $total_roundup = [];
                @endphp



                <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "{{count($companies)}}">Year {{$search['year']}}</th>
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
                        colspan={{count($companies)}}>
                        {{ $month_name ." ". $search['year']}}
                    </th>
                @endforeach

            </tr>
            {{-- Company Name --}}
            <tr>
                @foreach ($companies as $key => $company)
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
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
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $company)
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee"
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
                @endforeach
            </tr>
        </thead>
        <tbody>
        @if(!empty($recordsales['total_sales']))
            {{-- Sales title --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:0px solid #eee" colspan= {{$company_count + 1}}><b>SALES</b></th>
            </tr>
            {{-- Sales items --}}
            @foreach ($productcategory as $key => $value)
                @if(isset($recordsales['data_product_category'][$value->product_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee" colspan=1>
                            {{$recordsales['data_product_category'][$value->product_category_id]}}
                        </td>

                        @foreach ($companies as $key => $company)
                            @if(isset($recordsales['data_sales'][$company->company_id][$value->product_category_id]))
                                <td style="min-width: 150px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    <b>{{number_format($recordsales['data_sales'][$company->company_id][$value->product_category_id],2)}}</b>
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @endif
                        @endforeach

                        @foreach ($monthSel as $month_num => $month_name)
                            @foreach ($companies as $key => $company)
                                @if(isset($recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num]) && $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num] > 0)
                                    <td style="min-width: 150px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #f6f6f6;' }} border:1px solid #eee">
                                        {{number_format($recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num],2)}}
                                        @php
                                            if(isset($sum_total_sales[$company->company_id])){
                                                $sum_total_sales[$company->company_id] += $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num];
                                            }else{
                                                $sum_total_sales[$company->company_id] = $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num];
                                            }
                                        @endphp
                                    </td>
                                @else
                                    <td style="min-width: 120px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #f6f6f6;' }} border:1px solid #eee" colspan=1>-</td>
                                @endif
                            @endforeach
                            @php $col_count++; @endphp
                        @endforeach
                    </tr>
                @endif
            @endforeach

              {{-- Subtotal Sales --}}
              <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee"><b>Subtotal Sales (RM)</b></th>
                @foreach ($companies as $key => $company)
                    @if (isset($sum_total_sales[$company->company_id]))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($sum_total_sales[$company->company_id],2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>-</b>
                        </td>
                    @endif
                @endforeach
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($recordsales['total_sales_company'][$value->company_id][$month_num]) && $recordsales['total_sales_company'][$value->company_id][$month_num] > 0)
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($recordsales['total_sales_company'][$value->company_id][$month_num],2)}}</b>
                            </td>
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @php
                                $gross_profit['data_gross_profit'][$value->company_id][$month_num] = 0
                            @endphp
                        @endif
                    @endforeach
                @endforeach
            </tr>

            {{-- Round Up/Down --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee"><b>R. Up/Down</b></th>
                @foreach ($companies as $key => $company)
                    @if (isset($recordsales['total_roundup_company'][$company->company_id]))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format(array_sum($recordsales['total_roundup_company'][$company->company_id]),2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>-</b>
                        </td>
                    @endif
                @endforeach

                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($recordsales['total_roundup_company'][$company->company_id][$month_num]) && $recordsales['total_roundup_company'][$company->company_id][$month_num] != 0)
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($recordsales['total_roundup_company'][$company->company_id][$month_num],2)}}</b>
                            </td>
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                        @endif
                    @endforeach
                @endforeach
            </tr>

            {{-- Net Sales --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee"><b>Net Sales (RM)</b></th>
                @foreach ($companies as $key => $company)
                    @if (isset($sum_total_sales[$company->company_id]))
                    @php
                        $grandtotal_sales = $recordsales['total_roundup_company'][$company->company_id] ? $sum_total_sales[$company->company_id] + array_sum($recordsales['total_roundup_company'][$company->company_id]) : $sum_total_sales[$company->company_id];
                    @endphp
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($grandtotal_sales,2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>-</b>
                        </td>
                    @endif
                @endforeach
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($recordsales['total_sales_company'][$value->company_id][$month_num]) && $recordsales['total_sales_company'][$value->company_id][$month_num] > 0)
                        @php
                            $grandtotal_month_sales = $recordsales['total_roundup_company'][$company->company_id][$month_num] ? $recordsales['total_sales_company'][$value->company_id][$month_num] + $recordsales['total_roundup_company'][$company->company_id][$month_num] : $recordsales['total_sales_company'][$value->company_id][$month_num];
                        @endphp
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($grandtotal_month_sales,2)}}</b>
                            </td>
                            @php
                            if(isset($gross_profit['data_gross_profit'][$value->company_id][$month_num])){
                                $gross_profit['data_gross_profit'][$value->company_id][$month_num] += $grandtotal_month_sales;
                            }else{
                                $gross_profit['data_gross_profit'][$value->company_id][$month_num] = $grandtotal_month_sales;
                            }
                            @endphp
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @php
                                $gross_profit['data_gross_profit'][$value->company_id][$month_num] = 0
                            @endphp
                        @endif
                    @endforeach
                @endforeach
            </tr>
            {{-- Spacing --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #ffffff; border-left:1px solid #eee"></th>
                <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:0px solid #eee" colspan= {{$company_count}}></td>
            </tr>
            @php
            $col_count = 0;
            @endphp
            {{-- Cost of Sales title --}}
            {{-- <tr>
                <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:0px solid #eee" colspan= {{$company_count + 1}}><b>EXPENSES</b></th>
            </tr> --}}
            {{-- Expense Category --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:0px solid #eee" colspan= {{$company_count + 1}}><b>Cost</b></th>
            </tr>
            @foreach ($expensecategory as $key => $value)
            @if($value->setting_expense_category_group == 'Cost')
                @if(isset($recordcost['expense_category_name'][$value->setting_expense_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan=1>
                            <b>{{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}</b>
                        </td>
                        @foreach ($companies as $key => $company)
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                @if(isset($recordcost['total_cost'][$company->company_id][$value->setting_expense_category_id]))
                                    <b>{{number_format($recordcost['total_cost'][$company->company_id][$value->setting_expense_category_id],2)}}</b>
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        @foreach ($monthSel as $month_num => $month_name)
                            @foreach ($companies as $key => $company)
                                @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num]))
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                        <b>{{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num],2)}}</b>
                                        @php
                                            if(isset($sum_total_cost_expense[$company->company_id])){
                                                $sum_total_cost_expense[$company->company_id] += $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num];
                                            }else{
                                                $sum_total_cost_expense[$company->company_id] = $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num];
                                            }
                                        @endphp
                                    </td>
                                @else
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endif
            @endif
            @endforeach
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:0px solid #eee" colspan= {{$company_count + 1}}><b>Expense</b></th>
            </tr>
            @foreach ($expensecategory as $key => $value)
            @if($value->setting_expense_category_group == 'Expense')
                @if(isset($recordcost['expense_category_name'][$value->setting_expense_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan=1>
                            <b>{{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}</b>
                        </td>
                        @foreach ($companies as $key => $company)
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                @if(isset($recordcost['total_cost'][$company->company_id][$value->setting_expense_category_id]))
                                    <b>{{number_format($recordcost['total_cost'][$company->company_id][$value->setting_expense_category_id],2)}}</b>
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        @foreach ($monthSel as $month_num => $month_name)
                            @foreach ($companies as $key => $company)
                                @if(isset($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num]))
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                        <b>{{number_format($recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num],2)}}</b>
                                        @php
                                            if(isset($sum_total_cost_expense[$company->company_id])){
                                                $sum_total_cost_expense[$company->company_id] += $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num];
                                            }else{
                                                $sum_total_cost_expense[$company->company_id] = $recordcost['data_expense_category'][$company->company_id][$value->setting_expense_category_id][$month_num];
                                            }
                                        @endphp
                                    </td>
                                @else
                                    <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endif
            @endif
            @endforeach
            {{-- Total Cost of Sales --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee"><b>Total Expenses (RM)</b></th>
                @foreach ($companies as $key => $company)
                    @if (isset($sum_total_cost_expense[$company->company_id]))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                            <b>{{number_format($sum_total_cost_expense[$company->company_id],2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                            <b>-</b>
                        </td>
                    @endif
                @endforeach
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($recordcost['total_expense_company'][$value->company_id][$month_num]))
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($recordcost['total_expense_company'][$value->company_id][$month_num],2)}}</b>
                            </td>
                            @php
                                $sum_cost_expense_month['data_cost_month'][$value->company_id][$month_num] = $recordcost['total_expense_company'][$value->company_id][$month_num];
                            @endphp
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                            @php
                                $sum_cost_expense_month['data_cost_month'][$value->company_id][$month_num] = 0;
                            @endphp
                        @endif
                    @endforeach
                @endforeach
            </tr>
            {{-- Spacing --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #ffffff; border-left:1px solid #eee"></th>
                <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:0px solid #eee" colspan= {{$company_count}}></td>
            </tr>
            {{-- Gross Profit title --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:0px solid #eee" colspan= {{$company_count + 1}}><b>GROSS PROFIT</b></th>
            </tr>
            {{-- Net Gross Profit --}}
            <tr>
                <td style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan=1><b>Net Gross Profit (RM)</b></td>
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($gross_profit['data_gross_profit'][$value->company_id][$month_num]))
                            @php
                                if(isset($total_sum_gross_profit[$value->company_id])){
                                    $total_sum_gross_profit[$value->company_id] += $gross_profit['data_gross_profit'][$value->company_id][$month_num] - $sum_cost_expense_month['data_cost_month'][$value->company_id][$month_num];

                                }else{
                                    $total_sum_gross_profit[$value->company_id] = $gross_profit['data_gross_profit'][$value->company_id][$month_num] - $sum_cost_expense_month['data_cost_month'][$value->company_id][$month_num];
                                }
                            @endphp
                            @foreach($gross_profit as $final_profit)
                                @php
                                    $total_profit['total_gross_profit'][$value->company_id][$month_num] = number_format(number_format($gross_profit['data_gross_profit'][$value->company_id][$month_num],2, '.', '') - number_format($sum_cost_expense_month['data_cost_month'][$value->company_id][$month_num],2, '.', ''),2,'.','')
                                @endphp
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
                @foreach ($companies as $key => $company)
                    @if (isset($total_sum_gross_profit[$company->company_id]) && $total_sum_gross_profit[$company->company_id] <> 0)
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format($total_sum_gross_profit[$company->company_id],2)}}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>-</b>
                        </td>
                    @endif
                @endforeach
                @foreach ($monthSel as $month_num => $month_name)
                    @foreach ($companies as $key => $value)
                        @if(isset($total_profit['total_gross_profit'][$value->company_id][$month_num]) && $total_profit['total_gross_profit'][$value->company_id][$month_num] <> 0)
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">
                                <b>{{number_format($total_profit['total_gross_profit'][$value->company_id][$month_num],2)}}</b>
                            </td>
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @else
            <tr>
                <td colspan= {{$company_count + 1}}>No Records!</td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
