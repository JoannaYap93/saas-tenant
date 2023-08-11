@if(@$records['expense_data_supplier'])
    <div class="table-responsive">
            <table class="table" id="supplier-expenses-report">
            <thead>
                <tr>
                    <th style="background-color:white; font-weight: bold; color:#000000; min-width: 250px; border: 1px solid #eee" >{{ auth()->user()->user_type_id == 1 ? 'Supplier' : 'Supplier Name' }}</th>
                    @foreach ($monthSel as $key => $month)
                        <th colspan="1" style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} min-width: 150px; border:1px solid #eee">{{$month}}</th>
                    @endforeach
                    <th colspan="1" style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; min-width: 150px; border: 1px solid #eee">Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                                @foreach ($supplierSel as $supplier)
                                {{-- @dd($supplier->supplier_id) --}}
                                    <tr>
                                        <td style="border: 1px solid #eee;">{{$supplier->supplier_name}}</td>
                                        @foreach ($monthSel as $key => $month)
                                            <td style="text-align: center; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                @if (@$records['expense_data_supplier'][$key][$supplier->supplier_id] > 0)
                                                    @if (!@$search['company_id'])
                                                        <a class="popup" href="{{route('supplier_expenses_report_detail', ['tenant' => tenant('id'),'year' => $search['year'], 'month' => $key, 'supplier_id' => $supplier->supplier_id, 'company_id' => 0])}}">
                                                        {{number_format($records['expense_data_supplier'][$key][$supplier->supplier_id], 2)}}
                                                    </a>
                                                    @elseif (@$search['company_id'])
                                                        <a class="popup" href="{{route('supplier_expenses_report_detail', ['tenant' => tenant('id'), 'year' => $search['year'], 'month' => $key, 'supplier_id' => $supplier->supplier_id, 'company_id' => $search['company_id']])}}">
                                                        {{number_format($records['expense_data_supplier'][$key][$supplier->supplier_id], 2)}}
                                                    </a>
                                                    @else
                                                        {{number_format($records['expense_data_supplier'][$key][$supplier->supplier_id], 2)}}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                        <td style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; border: 1px solid #eee">{{ @$records['total_by_supplier_expense_data_supplier'][$supplier->supplier_id] > 0 ? number_format($records['total_by_supplier_expense_data_supplier'][$supplier->supplier_id], 2) : '-' }}</td>
                                    </tr>
                                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: left; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">Total (RM)</td>
                    @foreach ($monthSel as $key => $month)
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_month_expense_data_supplier'][$key] > 0 ? number_format($records['total_by_month_expense_data_supplier'][$key], 2) : '-' }}</td>
                    @endforeach
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_all_supplier'] > 0 ? number_format($records['total_by_all_supplier'], 2) : '-' }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@else
    <table>
        <tr>
            <td>No records found!</td>
        </tr>
    </table>
@endif