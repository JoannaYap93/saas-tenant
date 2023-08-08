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
                    @foreach ($monthSel as $month_num => $month_name)
                        @foreach ($companies as $key => $company)
                            @if(isset($recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num]) && $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num] > 0)
                                @php
                                    if(isset($sum_total_sales[$company->company_id])){
                                        $sum_total_sales[$company->company_id] += $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num];
                                    }else{
                                        $sum_total_sales[$company->company_id] = $recordsales['data_sales_by_month'][$company->company_id][$value->product_category_id][$month_num];
                                    }
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                @endif
            @endforeach

            {{-- Subtotal Sales --}}
            <tr>
                <th style="min-width: 220px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee"><b>Net Sales (RM)</b></th>
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
                        @else
                            <td style="text-align: center; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan= "1">-</td>
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
