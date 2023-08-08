<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Profit &#38; Loss Reporting {{ $search['year'] }}</b></p>
        <br><br>
    @endif
        @php
            $company_count = 0;
            $company_count2 = 0;

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
                @if(!empty($recordsales["total_sales"]) || !empty($recordsales["total_last_year_sales_company"]))
                    <tr>
                        <th style="min-width: 220px; text-align: left; background-color: #e4e4e4; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>SALES</b></th>
                    </tr>
                        <tr>
                            <th style="min-width: 220px; text-align: left; background-color:#fffbaf; color:#000000; border:1px solid #eee"><b>Subtotal Sales (RM)</b></th>
                                @foreach ($companies as $key => $value)
                                    @if(isset($recordsales['total_sales_company'][$value->company_id]) && $recordsales['total_sales_company'][$value->company_id] > 0)
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                            <b>{{number_format($recordsales['total_sales_company'][$value->company_id],2)}}</b>
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                                    @endif
                                    @if(isset($recordlastyearsales['total_sales_company'][$value->company_id]) && $recordlastyearsales['total_sales_company'][$value->company_id] > 0)
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                            <b>{{number_format($recordlastyearsales['total_sales_company'][$value->company_id],2)}}</b>
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                                    @endif
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
                                    @if(isset($recordsales['total_sales_company'][$value->company_id]) && $recordsales['total_sales_company'][$value->company_id] > 0)
                                    @php
                                        $grandtotal_sales = $recordsales['total_roundup_company'][$value->company_id] ? $recordsales['total_sales_company'][$value->company_id] + $recordsales['total_roundup_company'][$value->company_id] : $recordsales['total_sales_company'][$value->company_id];
                                    @endphp
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                            <b>{{number_format($grandtotal_sales,2)}}</b>
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                                    @endif
                                    @if(isset($recordlastyearsales['total_sales_company'][$value->company_id]) && $recordlastyearsales['total_sales_company'][$value->company_id] > 0)
                                        @php
                                            $year_grandtotal_sales = $recordlastyearsales['total_roundup_company'][$value->company_id] ? $recordlastyearsales['total_sales_company'][$value->company_id] + $recordlastyearsales['total_roundup_company'][$value->company_id] : $recordlastyearsales['total_sales_company'][$value->company_id];
                                        @endphp
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                            <b>{{number_format($year_grandtotal_sales,2)}}</b>
                                        </td>
                                    @else
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
                                    @endif
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
