<div class="table-responsive">
    @if(@$records['expense_product'])
    <table class="table table-bordered" id="company_expense_table">
        @php
            $grand_total = 0;
        @endphp
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="2">Expense Category</th>
                @php $col_count = 0; @endphp
                    @foreach ($companyLand as $key => $land)
                        @foreach ($products as $product)
                            <th style="min-width: 120px; text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                {{ $product->product_name }}
                            </th>
                        @endforeach
                    @php $col_count++; @endphp
                @endforeach
                <th style="text-align: center; min-width: 120px; background-color:#fffbaf; color:#000000; border:0px solid #eee" rowspan="2">TOTAL (RM)</th>
            </tr>
            @foreach($expenseCategory as $setting_expense_category)
            <tr>
                <th style="text-align: center; border:1px solid #eee" class="table-secondary" colspan={{ count($products)}}>{{ json_decode($setting_expense_category->setting_expense_category_name)->en }}</th>
            </tr>
            @endforeach
        </thead>
        <tbody>
            @foreach($expenseCategory as $setting_expense_category)
                @foreach($settingExpense as $setting_expense)
                        <tr>
                            <td style="text-align: center; background-color: #e4e4e4; border:1px solid #eee " >{{ json_decode($setting_expense->setting_expense_name)->en }}</td>
                                @foreach($companyLand as $land)
                                    @foreach ($products as $product)
                                        @if (isset($records['expense_product'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$product->product_id]))
                                            <td style=text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                {{ number_format($records['expense_product'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$product->product_id], 2)}}
                                            </td>
                                        @else
                                            <td style=text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                -
                                            </td>
                                        @endif
                                    @endforeach
                                @endforeach

                                @foreach($companyLand as $land)
                                        @if (isset($records['total_expense_by_setting_expense'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id]))
                                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                                {{ number_format($records['total_expense_by_setting_expense'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id], 2) }}
                                            </td>
                                        @else
                                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                                -
                                            </td>
                                        @endif
                                @endforeach
                                @php $col_count++; @endphp
                        </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" ><b>TOTAL (RM)</b></td>
                @foreach($companyLand as $key => $land)
                     @foreach ($products as $product)
                         <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            @if (isset($records['total_expense_by_product'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$product->product_id]))
                                {{ number_format($records['total_expense_by_product'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$product->product_id],2)}}
                                @php
                                    $grand_total  += $records['total_expense_by_product'][$land->company_land_id][$setting_expense_category->setting_expense_category_id][$product->product_id];
                                @endphp
                            @else
                                -
                            @endif
                        </th>
                     @endforeach
                @endforeach
                <th style="text-align: center; font-weight: bold; background-color:#fffbaf; border:1px solid #eee; color:#000000">
                    <b>{{ ($grand_total ? number_format($grand_total,2, '.', '') : '-') }}</b>
                </th>
            </tr>
        </tfoot>
    </table>
    @else
        <tr><td colspan="3">No Records!</td></tr>
    @endif
</div>
