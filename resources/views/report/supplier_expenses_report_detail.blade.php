 @extends('layouts.master-without-nav')

@section('title'){{$page_title}}@endsection

@section('css')
<style>
 .table-responsive {
        /* height: 500px !important; */
        overflow: hidden !important;
        overflow: scroll !important;
    }
    table {
        text-align: center !important;
    }
    .bg-grey {
        background: #e4e4e4 !important;
    }
    th {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 2 !important;
    }

    th:nth-child(n) {
        position: -webkit-sticky !important;
        position: sticky !important;
        z-index: 1 !important;
    }

    tfoot {
        position: -webkit-sticky !important;
        position: sticky !important;
        bottom: 0 !important;
    }

    body {
        overflow-x: hidden;
    }

    .clone-head-table-wrap{
        top: 0px !important;
    }

    ::-webkit-scrollbar {
        display: inherit;
    }
    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-nowrap table-bordered" id="supplier-expenses-report-detail">
                <thead>
                    @php
                            $col_count=0;
                    @endphp
                    <tr>
                            {{-- <th style="background-color: #343a40; border:1px solid #eee; width: 35%; color: #ffffff"><b>Supplier</b></th> --}}
                            <th style="min-width: 100px; background-color: #d8d9df; border:1px solid #eee;" rowspan="2">Expense Category</th>
                            {{-- <th style="text-align: center; background-color: #343a40; border:1px solid #eee; color: #ffffff"><b>{{ @$supplier->supplier_name }}</b></th> --}}
                            {{-- @foreach($monthSel as $month) --}}
                            <th colspan="{{count($daySel)}}" style="text-align: center; background-color: #d8d9df;padding-left:50px;">{{ @$supplier->supplier_name }}</th>
                            {{-- @endforeach --}}
                            <th colspan="1" style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; min-width: 150px; border: 1px solid #eee" rowspan="2">Total (RM)</th>
                    </tr>
                    <tr>
                        {{-- <th style="background-color: #343a40; border:1px solid #eee; width: 35%; color: #ffffff"><b>Day</b></th> --}}
                        @foreach ($daySel as $day)
                            <th style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #f5f5f5;' : 'background-color: #d8d9df;' }} border:1px solid #eee;">
                                {{$day}}
                            </th>
                            @php
                                $col_count++;
                            @endphp
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @if (@$records['daily_expenses'])
                    @foreach ($expense_category as $expense)
                    @php
                    $col_count = 0;
                @endphp
                        <tr>
                            <td style="min-width: 220px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #d8d9df;' }} border:1px solid #eee;" class="table-secondary">
                            {{-- <td style="font-weight: bold; background-color: #ebebeb; border:1px solid #eee;"> --}}
                                {{json_decode($expense->setting_expense_category_name)->en}}</td>
                                @foreach ($daySel as $key => $day)
                                    @if (@$records['daily_expenses'][$key][$expense->setting_expense_category_id])
                                        {{-- <td style="font-weight: bold; text-align: center; background-color: #ebebeb; border:1px solid #eee;"> --}}
                                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #f5f5f5;' }} border:1px solid #eee;">
                                            {{ number_format($records['daily_expenses'][$key][$expense->setting_expense_category_id], 2) }}</td>
                                    @else
                                        {{-- <td style="font-weight: bold; text-align: center; background-color: #ebebeb; border:1px solid #eee;"> --}}
                                            <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #f5f5f5;' }} border:1px solid #eee">
                                            -</td>
                                    @endif
                                    @php
                                    $col_count++;
                                @endphp
                                @endforeach
                            <td style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; border: 1px solid #eee">{{ @$records['total_by_expense_category'][$expense->setting_expense_category_id] > 0 ? number_format($records['total_by_expense_category'][$expense->setting_expense_category_id], 2) : '-' }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total (RM)</td>
                            @foreach ($daySel as $key => $day)
                                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_day_supplier_item_expense'][$day] > 0 ? number_format($records['total_by_day_supplier_item_expense'][$day], 2) : '-' }}</td>
                            @endforeach
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ $records['total_supplier_item_expense'] > 0 ? number_format($records['total_supplier_item_expense'], 2) : '-' }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script>
    $(document).ready(function(e) {
        $(".fancybox").fancybox();
        $("#supplier-expenses-report-detail").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' :true,
        });
    });
    </script>
@endsection
