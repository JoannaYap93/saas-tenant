@extends('layouts.master-without-nav')
@section('title')
    Company Expense Report
@endsection
@section('css')
    <style>
    .table-responsive {
        height: 500px !important;
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

    ::-webkit-scrollbar {
        display: inherit;
    }

    </style>
@endsection
@section('content')
    <div class="card-body">
        <div class="table-responsive">
            @if(@$records)
                <table class="table" id="tree_pointer_details">
                    <thead>
                        <tr>
                            <th style="min-width: 200px; border:1px solid #eee; background-color: #d8d9df" class="table-secondary" rowspan="4">Product</th>
                                @if ($title == 'land')
                                    @foreach ($company_land as $company)
                                        <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary" colspan="{{ count($ages) * 3 }}">{{$company->company_land_name}}</th>
                                    @endforeach
                                @else
                                    <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary" colspan="{{ count($ages) * 3 }}">{{$company}}</th>
                                @endif
                            <th style="min-width: 200px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" rowspan="3" colspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary" colspan="{{ count($ages) * 3 }}">Age</th>
                        </tr>
                        <tr>
                            @foreach ($ages as $key => $age)
                                <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary" colspan="3">{{$age->setting_tree_age}}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($ages as $val => $age)
                                <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary">Pointer</th>
                                <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary">Quantity</th>
                                <th style="border:1px solid #eee; background-color: #d8d9df" class="table-secondary">Total</th>
                            @endforeach
                            <th style="min-width: 200px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Quantity</th>
                            <th style="min-width: 200px; text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">Pointer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $col_count = 0;
                        @endphp
                        @foreach ($products as $key => $product)
                            <tr>
                                <td style="border:1px solid #eee; background-color: #e4e4e4" class="table-secondary">{{$product}}</td>
                                @foreach ($ages as $val => $age)
                                    <td style="min-width: 100px; {{ $age->setting_tree_age % 2 == 0 ? 'background-color: #e4e4e4;' : 'background-color: #ffffff;' }}">
                                        @if(isset($records['all_age'][$key][$age->setting_tree_age]))
                                            {{$records['all_age'][$key][$age->setting_tree_age]}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="min-width: 100px; {{ $age->setting_tree_age % 2 == 0 ? 'background-color: #e4e4e4;' : 'background-color: #ffffff;' }}">
                                        @if (isset($records['records'][$key][$age->setting_tree_age]['quantity']))
                                            {{$records['records'][$key][$age->setting_tree_age]['quantity']}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="min-width: 100px; {{ $age->setting_tree_age % 2 == 0 ? 'background-color: #e4e4e4;' : 'background-color: #ffffff;' }}">
                                        @if (isset($records['records'][$key][$age->setting_tree_age]['total']))
                                            {{$records['records'][$key][$age->setting_tree_age]['total']}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                    @if (isset($records['total_quantity_by_product'][$key]))
                                        {{$records['total_quantity_by_product'][$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                    @if (isset($records['total_pointer_by_product'][$key]))
                                        {{$records['total_pointer_by_product'][$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @php
                                $col_count++;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" >TOTAL</td>
                            @foreach ($ages as $val => $age)
                                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" >-</td>
                                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" >
                                    @if (isset($records['total_quantity'][$age->setting_tree_age]))
                                        {{$records['total_quantity'][$age->setting_tree_age]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="background-color:#fffbaf; color:#000000; border:1px solid #eee" >
                                    @if (isset($records['total'][$age->setting_tree_age]))
                                        {{$records['total'][$age->setting_tree_age]}}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                @if (isset($records['grand_quantity']))
                                    {{$records['grand_quantity']}}
                                @endif
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                                @if (isset($records['grand_pointer']))
                                    {{$records['grand_pointer']}}
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <tr><td colspan="3">No Records!</td></tr>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
    $(document).ready(function(e) {        
        $("#tree_pointer_details").parent().freezeTable({
            'freezeColumn': true,
            'columnNum': 1,
            'shadow': true,
            'scrollable' : true,
        });
    });
    </script>
@endsection
