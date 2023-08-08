<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Budget Estimate Reporting </b></p>
        <br><br>
    @endif
        @php
            $total_month_date_sales = 0;
            $total_month_budget_sales = 0;
            $total_category_expense_month_to_date = 0;
            $total_category_expense_budget = 0;
            $total_net_expense = 0;
            $total_net_sales = 0;
            $sum_gross_profit_month_to_date = 0; 
            $sum_gross_profit_budget = 0;
            $sum_gross_profit_variance = 0; 
        @endphp
        <table class="table" id="company-expense-table">
            <thead>
                <tr>
                    <th style="min-width: 220px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary"><b>COMPANY</b></th>    
                        @if(isset($search['month']))
                            <th style="max-width: 50px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan='3'>
                                @foreach ($month as $month_num => $month_name)
                                    {{@$month_name}} - {{$year}}&nbsp;
                                @endforeach                         
                            </th>
                        @else
                            <th style="max-width: 50px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan='3'>                          
                                {{$year}}&nbsp;
                            </th> 
                        @endif
                </tr>
                <tr>
                    <th style="min-width: 220px; text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" class="table-secondary"><b>{{ $companies->company_name }}</b></th>
                    @if(isset($search['month']))
                    <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Month To Date</th>
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Month Budget</th>
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Variance</th>
                    @else
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Total Month To Date</th>
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Total Month Budget</th>
                        <th style="text-align: center; font-weight: bold; background-color: #343a40; color: white; border:1px solid #eee" colspan= "1">Total Variance</th>    
                    @endif
                </tr>      
            </thead>
            <tbody>
                
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="4"><b>SALES</b></th>
                    </tr>
                    @foreach ($productcategory as $key => $value)     
                        <tr>             
                            <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="4"> 
                                {{$value->product_category_name}}
                            </td>
                        </tr>
                            @if(isset($recordsales['grouped_product_name'][$companies->company_id][$value->product_category_id]) && count($recordsales['grouped_product_name'][$companies->company_id][$value->product_category_id]) > 0)
                                @foreach ($recordsales['grouped_product_name'][$companies->company_id][$value->product_category_id] as $product_id => $product_name)
                                <tr>
                                    @php
                                        $varience_sales = 0;
                                        $month_to_date_sales = 0;
                                        $month_to_budget_sales = 0;

                                        if(isset($recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id]))
                                        {
                                            $month_to_date_sales = $recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                            $total_month_date_sales += $recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                        }else {
                                            $month_to_date_sales = 0;
                                        }
                                        if(isset($recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id]))
                                        {
                                            $month_to_budget_sales = $recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                            $total_month_budget_sales += $recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                        }else {
                                            $month_to_budget_sales = 0;
                                        }
                                    @endphp                    
                                    <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">{{$product_name}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($month_to_date_sales,2)}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($month_to_budget_sales,2)}}</td>
                                    @php
                                        $variance_sales = $month_to_date_sales - $month_to_budget_sales;
                                    @endphp
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($variance_sales,2)}}</td>
                                </tr>
                                @endforeach

                            @elseif(isset($recordsalesbudget['grouped_product_name'][$companies->company_id][$value->product_category_id]) && count($recordsalesbudget['grouped_product_name'][$companies->company_id][$value->product_category_id]) > 0)

                                @foreach ($recordsalesbudget['grouped_product_name'][$companies->company_id][$value->product_category_id] as $product_id => $product_name)
                                    <tr>
                                        @php
                                            $varience_sales = 0;
                                            $month_to_date_sales = 0;
                                            $month_to_budget_sales = 0;

                                            if(isset($recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id]))
                                            {
                                                $month_to_date_sales = $recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                                $total_month_date_sales += $recordsales['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                            }else {
                                                $month_to_date_sales = 0;
                                            }
                                            if(isset($recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id]))
                                            {
                                                $month_to_budget_sales = $recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                                $total_month_budget_sales += $recordsalesbudget['sales'][$companies->company_id][$value->product_category_id][$product_id];
                                            }else {
                                                $month_to_budget_sales = 0;
                                            }
                                        @endphp                         
                                        <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">{{$product_name}}</td>
                                        <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($month_to_date_sales,2)}}</td>
                                        <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($month_to_budget_sales,2)}}</td>
                                        @php
                                            $variance_sales = $month_to_date_sales - $month_to_budget_sales;
                                        @endphp
                                        <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{number_format($variance_sales,2)}}</td>
                                    </tr>
                                @endforeach
                            @endif
                    @endforeach
                    <tr>
                        @php
                            $total_net_sales = $total_month_date_sales - $total_month_budget_sales
                        @endphp
                    <th style="text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1"><b>Net Sales (RM)</b></th>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_month_date_sales,2)}}</td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_month_budget_sales,2)}}</td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_net_sales,2)}}</td>
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="4"><b>EXPENSES</b></th>
                    </tr>
                    @foreach ($expensecategory as $key => $value)     
                        <tr>             
                            <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1"> 
                                {{json_decode($value->setting_expense_category_name)->en}}
                            </td>
                            @php
                                $variance_category_expense = 0;
                            @endphp
                            @if(isset($recordexpense['expense_category'][$companies->company_id][$value->setting_expense_category_id]) && $recordexpense['expense_category'][$companies->company_id][$value->setting_expense_category_id] > 0)
                                <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">     
                                    {{number_format($recordexpense['expense_category'][$companies->company_id][$value->setting_expense_category_id],2)}}
                                    @php
                                        $expense_category_month_to_date = $recordexpense['expense_category'][$companies->company_id][$value->setting_expense_category_id];
                                        $total_category_expense_month_to_date += $recordexpense['expense_category'][$companies->company_id][$value->setting_expense_category_id];
                                    @endphp
                                </td> 
                            @else
                                <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @php
                                    $expense_category_month_to_date = 0;
                                @endphp
                            @endif
                                    
                            @if(isset($recordexpensebudget['expense_category'][$companies->company_id][$value->setting_expense_category_id]) && $recordexpensebudget['expense_category'][$companies->company_id][$value->setting_expense_category_id] > 0)
                                <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">    
                                    {{number_format($recordexpensebudget['expense_category'][$companies->company_id][$value->setting_expense_category_id],2)}}
                                    @php
                                        $expense_category_budget = $recordexpensebudget['expense_category'][$companies->company_id][$value->setting_expense_category_id];
                                        $total_category_expense_budget += $recordexpensebudget['expense_category'][$companies->company_id][$value->setting_expense_category_id];
                                    @endphp
                                </td> 
                            @else
                                <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">-</td>
                                @php
                                    $expense_category_budget = 0;
                                @endphp
                            @endif
                            @php
                                $variance_category_expense = $expense_category_month_to_date - $expense_category_budget
                            @endphp
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee">     
                                {{number_format($variance_category_expense,2)}}
                            </td> 
                        </tr>

                        @if( isset($recordexpenseitem['expense_item'][$value->setting_expense_category_id]) && count($recordexpenseitem['expense_item'][$value->setting_expense_category_id]) > 0)
                            @foreach ($recordexpenseitem['expense_item'][$value->setting_expense_category_id] as $expense_item_id => $item_name)
                                <tr>
                                    <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">{{json_decode($item_name)->en}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{isset($recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id]) ? $recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id] : '0.00'}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{isset($recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id]) ? $recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id] : '0.00'}}</td>
                                    @php
                                        $varience_expense_item = 0;
                                        $month_to_date_expense_item = 0;
                                        $month_to_budget_expense_item = 0;

                                        if(isset($recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id]))
                                        {
                                            $month_to_date_expense_item = $recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id];
                                        }else {
                                            $month_to_date_expense_item = 0;
                                        }
                                        if(isset($recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id]))
                                        {
                                            $month_to_budget_expense_item = $recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id];
                                        }else {
                                            $month_to_budget_expense_item = 0;
                                        }
                                    @endphp
                                    @php
                                        $varience_expense_item = $month_to_date_expense_item - $month_to_budget_expense_item
                                    @endphp
                                    <td style="text-align: center;background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">     
                                        {{number_format($varience_expense_item,2)}}
                                    </td> 
                                </tr>
                            @endforeach
                        @elseif(isset($recordexpenseitembudget['grouped_expense_item'][$value->setting_expense_category_id]) && count($recordexpenseitembudget['grouped_expense_item'][$value->setting_expense_category_id]) > 0)
                            @foreach ($recordexpenseitembudget['grouped_expense_item'][$value->setting_expense_category_id] as $expense_item_id => $item_name)
                                <tr>
                                    <td style="min-width: 120px; text-align: left; background-color: #ffffff; border:1px solid #eee" colspan="1">{{json_decode($item_name)->en}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{isset($recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id]) ? $recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id] : '0.00'}}</td>
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">{{isset($recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id]) ? $recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id] : '0.00'}}</td>
                                    @php
                                        $varience_expense_item = 0;
                                        $month_to_date_expense_item = 0;
                                        $month_to_budget_expense_item = 0;

                                        if(isset($recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id]))
                                        {
                                            $month_to_date_expense_item = $recordexpenseitem['total_expense_item'][$value->setting_expense_category_id][$expense_item_id];
                                        }else {
                                            $month_to_date_expense_item = 0;
                                        }
                                        if(isset($recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id]))
                                        {
                                            $month_to_budget_expense_item = $recordexpenseitembudget['expense_item'][$companies->company_id][$value->setting_expense_category_id][$expense_item_id];
                                        }else {
                                            $month_to_budget_expense_item = 0;
                                        }
                                    @endphp
                                    @php
                                        $varience_expense_item = $month_to_date_expense_item - $month_to_budget_expense_item
                                    @endphp
                                    <td style="text-align: center;background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">     
                                        {{number_format($varience_expense_item,2)}}
                                    </td> 
                                </tr>
                            @endforeach
                        @endif
                        
                    @endforeach
                    <tr>
                        @php
                            $total_net_expense = $total_category_expense_month_to_date - $total_category_expense_budget
                        @endphp
                    <th style="text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1"><b>Total Expense (RM)</b></th>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_category_expense_month_to_date,2)}}</td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_category_expense_budget,2)}}</td>
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">{{number_format($total_net_expense,2)}}</td>
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan="4"><b>GROSS PROFIT</b></th>
                    </tr>
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Net Gross Profit (RM)</b></th>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                @php
                                    $sum_gross_profit_month_to_date = $total_month_date_sales - $total_category_expense_month_to_date; 
                                    $sum_gross_profit_budget = $total_month_budget_sales - $total_category_expense_budget;
                                    $sum_gross_profit_variance = $total_net_sales - $total_net_expense; 
                                @endphp
                                <b>{{number_format($sum_gross_profit_month_to_date,2)}}</b>
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($sum_gross_profit_budget,2)}}</b>
                            </td>
        
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                <b>{{number_format($sum_gross_profit_variance,2)}}</b>
                            </td>                          
                    </tr> 
            </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
