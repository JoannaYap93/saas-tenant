<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Profit &#38; Loss Reporting {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    @php
        $sum_total_sales = 0;
        $company_count = 0;
        $sum_total_net = [];
        $sum_total_cost_expense = 0;
        $gross_profit = [];
        $gross_expense = [];
        $total_sum_net_sales_company = 0;
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
                        <td style="min-width: 120px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
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
                        $grandtotal_sales = $recordsales['total_roundup_company'][$value->company_id] ? $recordsales['total_sales_company'][$value->company_id] + $recordsales['total_roundup_company'][$value->company_id] : $recordsales['total_sales_company'][$value->company_id];
                    @endphp
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                            <b>{{number_format( $grandtotal_sales,2)}}</b>
                        </td>
                        @php
                            if(isset($gross_profit['data_gross_profit'][$value->company_id])){
                                $gross_profit['data_gross_profit'][$value->company_id] +=  $grandtotal_sales;
                            }else{
                                $gross_profit['data_gross_profit'][$value->company_id] =  $grandtotal_sales;
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
                        $grandtotal_sales = $recordsales['total_roundup_company'] ? $sum_total_sales + array_sum(array_values($recordsales['total_roundup_company'])) : $recordsales['total_roundup_company'];
                    @endphp
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                    <b>{{number_format($grandtotal_sales,2)}}</b>
                </td>
            </tr>
            <tr>
                <th style="min-width: 220px; text-align: left; background-color: #d8d9df; color:#000000; border:1px solid #eee" colspan= {{$company_count + 2 }}><b>Cost</b></th>
            </tr>
            @foreach ($expensecategory as $key => $value)
            @if($value->setting_expense_category_group == 'Cost')
                @if(isset($recordcost['expense_category_name'][$value->setting_expense_category_id]))
                    <tr>
                        <td style="min-width: 120px; text-align: left; background-color:#fdfacb; color:#000000; border:1px solid #eee" colspan="1">
                            <b>{{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}</b>
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
                        @if(isset($recordcost['total_cost'][$value->setting_expense_category_id]))
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                {{number_format($recordcost['total_cost'][$value->setting_expense_category_id],2)}}
                            </td>
                        @else
                            <td>-</td>
                        @endif
                    </tr>
                @endif
                @foreach ($expenseitemdata as $key => $item)
                    @if(isset($expenseitem['expense_item_name'][$value->setting_expense_category_id][$item->setting_expense_id]))
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                {{-- @dd($item->setting_expense_id) --}}
                                {{json_decode($expenseitem['expense_item_name'][$value->setting_expense_category_id][$item->setting_expense_id])->en}}
                            </td>
                            @foreach ($companies as $key => $company)
                                @if(isset($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id]) && $expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id] > 0)
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee">
                                        @if(isset($search['company_land_id']))
                                        <a class="popup" href="{{route('profit_loss_reporting_detail_by_land', ['tenant' => tenant('id'),'year' => $search['year'], 'company_id' => $company->company_id, 'company_land_id' => $search['company_land_id'], 'setting_expense_id' => $item->setting_expense_id])}}">
                                            {{number_format($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                        </a>
                                        @else
                                        <a class="popup" href="{{route('profit_loss_reporting_detail', ['tenant' => tenant('id'), 'year' => $search['year'], 'company_id' => $company->company_id, 'setting_expense_id' => $item->setting_expense_id])}}">
                                            {{number_format($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                        </a>
                                        @endif
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                            @if(isset($expenseitem['total_expense_each_item'][$value->setting_expense_category_id][$item->setting_expense_id]))
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    {{number_format($expenseitem['total_expense_each_item'][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                                -
                                </td>
                            @endif
                        </tr>
                    @endif
                @endforeach
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
                            <b>{{json_decode($recordcost['expense_category_name'][$value->setting_expense_category_id])->en}}</b>
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
                        @if(isset($recordcost['total_cost'][$value->setting_expense_category_id]))
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                {{number_format($recordcost['total_cost'][$value->setting_expense_category_id],2)}}
                            </td>
                        @else
                            <td>-</td>
                        @endif
                    </tr>
                @endif
                @foreach ($expenseitemdata as $key => $item)
                    @if(isset($expenseitem['expense_item_name'][$value->setting_expense_category_id][$item->setting_expense_id]))
                        <tr>
                            <td style="min-width: 120px; text-align: left; background-color: #ffffff; color:#000000; border:1px solid #eee" colspan="1">
                                {{json_decode($expenseitem['expense_item_name'][$value->setting_expense_category_id][$item->setting_expense_id])->en}}
                            </td>
                            @foreach ($companies as $key => $company)
                                @if(isset($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id]) && $expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id] > 0)
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee">
                                        @if(isset($search['company_land_id']))
                                        <a class="popup" href="{{route('profit_loss_reporting_detail_by_land', ['tenant' => tenant('id'), 'year' => $search['year'], 'company_id' => $company->company_id, 'company_land_id' => $search['company_land_id'], 'setting_expense_id' => $item->setting_expense_id])}}">
                                            {{number_format($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                        </a>
                                        @else
                                        <a class="popup" href="{{route('profit_loss_reporting_detail', ['tenant' => tenant('id'), 'year' => $search['year'], 'company_id' => $company->company_id, 'setting_expense_id' => $item->setting_expense_id])}}">
                                            {{number_format($expenseitem['data_expense_item'][$company->company_id][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                        </a>
                                        @endif
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #ffffff; color:#000000; border:1px solid #eee">-</td>
                                @endif
                            @endforeach
                            @if(isset($expenseitem['total_expense_each_item'][$value->setting_expense_category_id][$item->setting_expense_id]))
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">
                                    {{number_format($expenseitem['total_expense_each_item'][$value->setting_expense_category_id][$item->setting_expense_id],2)}}
                                </td>
                            @else
                                <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee" colspan= "1">
                                -
                                </td>
                            @endif
                        </tr>
                    @endif
                @endforeach
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
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan= "1">-</td>
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
</div>
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>

    $(document).ready(function(e) {
        $(".popup").fancybox({
            'type': 'iframe',
            'width': '100%',
            'height': '100%',
            'autoDimensions': false,
            'autoScale': false,
            iframe : {
                css : {
                    width : '100%',
                    height: '100%',
                }
            }
        });

        $(".fancybox").fancybox();
        $("#company-expense-table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' : true,
        });
    });

    @if (@$search['company_id'] != null)
        // console.log('kwek');
        get_land_user('{{ $search['company_id'] }}');
    @else
        get_land_user('{{ auth()->user()->company_id }}');
    @endif

    $('#company_id').on('change', function() {
        let id = $(this).val();
        console.log('kwek2');
        get_land_user(id);
    });

    function get_land_user(id) {
        let land = '<option value="">Please Select Land</option>';
        let sland = "{{ @$search['company_land_id'] ?? null }}";
        console.log(sland);
        $.ajax({
            url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(e) {
                if (e.land.length > 0) {
                    e.land.forEach(element => {
                        if (sland != null && element.company_land_id == sland) {
                            land += '<option value="' + element.company_land_id + '" selected>' +
                                element
                                .company_land_name + '</option>';
                        } else {
                            land += '<option value="' + element.company_land_id + '">' + element
                                .company_land_name + '</option>';
                        }
                    });
                    $('#company_land_id').html(land);
                } else {
                    $('#company_land_id').html('<option value="">No Land</option>');
                }
            }
        });
    }
    </script>
@endsection
