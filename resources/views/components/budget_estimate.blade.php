<div class="table-responsive" style="@if(!@$component) margin:auto @endif">
    @if(!@$component)
        <p><b>Budget Estimate</b></p>
        <br><br>
    @endif
    <table class="table" id="budgetEstimateTable">
        <thead>
            <tr>
                <th style="text-align:center; background-color: #343a40; color: white; border:1px solid #eee; min-width: 200px" rowspan="2">{{$company->company_name}}</th>
                <th style="text-align:center; background-color: #343a40; color: white; border:1px solid #eee;" colspan="{{ count($monthSel) + 1 }}">
                    Year {{$data->budget_estimated_year}}
                </th>
            </tr>
            <tr style="text-align:center; background-color: #343a40; color: white; border:1px solid #eee">
                @foreach ($monthSel as $month_num => $month_name)
                    <th style="min-width: 200px; border:1px solid #eee">
                        {{ $month_name }}
                    </th>
                @endforeach
                <th style="min-width: 200px; border:1px solid #eee;">
                    Total
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="text-align:left; background-color: #4b545c; color: white; border:1px solid #eee"  colspan="{{count($monthSel)+2}}">SALES</th>
            </tr>
            @foreach ($productCategory as $key => $category )
                <tr>
                    <td style="text-align:left; background-color: #d8d9df; border:1px solid #eee"  colspan="{{count($monthSel)+2}}">{{$category->product_category_name}}</td>
                </tr>
                @foreach($productSel as $product_id => $product)
                    @if($product->product_category_id == $category->product_category_id)
                        <tr class="product_item">
                            <td>
                                {{$product->product_name}}
                            </td>
                            @foreach ($monthSel as $month_num => $month_name)
                                @php
                                    $item_value = null
                                @endphp
                                @foreach ($items as $value)
                                    @if ($value->budget_estimated_item_type == "product_id" && $value->budget_estimated_item_type_value == $product->product_id && $value->budget_estimated_item_month == $month_num)
                                        @php
                                            $item_value = $value->budget_estimated_item_amount
                                        @endphp
                                    @endif
                                @endforeach
                                <td style="text-align:center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{-- <input type="number" class="form-control row-input" name="text" value=""> --}}
                                    <a class="update row-input" data-pk="{{ @$data->budget_estimated_id }}" month="{{$month_num}}" type="1" type_value="{{ $product->product_id }}">
                                        @if ($item_value != null)
                                            {{$item_value}}
                                        @else
                                            -
                                        @endif
                                    </a>
                                </td>
                            @endforeach
                            <td style="text-align:center;  background-color:#fffbaf;">
                                {{-- <input type="number" class="form-control row-total" name="text" value=""> --}}
                                <a class="row-total" data-pk="{{ @$data->budget_estimated_id }}">-</a>
                            </td>
                        </tr>
                    @endif               
                @endforeach
            @endforeach
            <tr style="background-color:#fffbaf;">
                <th style="min-width: 100px">
                    Net Sales(RM)
                </th>
                @foreach ($monthSel as $month_num => $month_name)
                    <td style="text-align: center;">
                        {{-- <input type="number" class="form-control col-total" name="text" value=""> --}}
                        <a class="salestotal">-</a>
                    </td>
                @endforeach
                <td style="text-align:center;">
                    <a class="netsales">-</a>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th style="text-align:left; background-color: #4b545c; color: white; border:1px solid #eee"  colspan="{{count($monthSel)+2}}">EXPENSES</th>
            </tr>
            @foreach($expenseCategory as $key => $category)
                <tr>
                    <td style="text-align:left; background-color: #d8d9df; border:1px solid #eee"  colspan="{{count($monthSel)+2}}">{{json_decode($category->setting_expense_category_name)->en}}</td>
                </tr>
                @foreach($expenseItem as $key => $item)
                    @if($item->setting_expense_category_id == $category->setting_expense_category_id)
                        <tr class="expense_item">
                            <td>
                                {{json_decode($item->setting_expense_name)->en}}
                            </td>
                            @foreach ($monthSel as $month_num => $month_name)
                                @php
                                    $item_value = null
                                @endphp
                                @foreach ($items as $value)
                                    @if ($value->budget_estimated_item_type == "setting_expense_id" && $value->budget_estimated_item_type_value == $item->setting_expense_id && $value->budget_estimated_item_month == $month_num)
                                        @php
                                            $item_value = $value->budget_estimated_item_amount
                                        @endphp
                                    @endif
                                @endforeach
                                <td style="text-align:center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{-- <input type="number" class="form-control ro-input" name="text" value=""> --}}
                                    <a class="update ro-input" data-pk="{{ @$data->budget_estimated_id }}" month="{{$month_num}}" type="2" type_value="{{ $item->setting_expense_id }}">
                                        @if ($item_value != null)
                                            {{$item_value}}
                                        @else
                                            -
                                        @endif
                                    </a>
                                </td>
                            @endforeach
                            <td style="text-align:center; background-color:#fffbaf;">
                                {{-- <input type="number" class="form-control ro-total" name="text" value=""> --}}
                                <a class="ro-total">-</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            <tr style="background-color:#fffbaf;">
                <th style="min-width: 100px">
                    Total Expenses(RM)
                </th>
                @foreach ($monthSel as $month_num => $month_name)
                    <td style="text-align: center;">
                        {{-- <input type="number" class="form-control col-total" name="text" value=""> --}}
                        <a class="expensetotal">-</a>
                    </td>
                @endforeach
                <td style="text-align:center;">
                    <a class="netexpenses">-</a>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align:left; background-color: #4b545c; color: white; border:1px solid #eee"  colspan="{{count($monthSel)+2}}">GROSS PROFIT</th>
            </tr>
            <tr style="background-color:#fffbaf;">
                <th style="min-width: 100px">
                    Net Gross Profit (RM)
                </th>
                @foreach ($monthSel as $month_num => $month_name)
                    <td style="text-align: center;">
                        {{-- <input type="number" class="form-control col-total" name="text" value=""> --}}
                        <a class="col-total">-</a>
                    </td>
                @endforeach
                <td style="text-align:center;">
                    <a class="total">-</a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
