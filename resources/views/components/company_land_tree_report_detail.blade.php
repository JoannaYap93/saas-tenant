@extends('layouts.master-without-nav')

@section('title'){{$page_title}}@endsection

@section('css')
<style>
    .table-responsive {
        max-height: 500px !important;
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
    .table-responsive::-webkit-scrollbar {
        display: inherit;
    }
    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        {{-- @dd($records['land']) --}}
        <div class="page-title-box d-flex align-items-center justify-content-between">
            @foreach ($records['land'] as $land_id => $land_name)
            <h4 class="mb-0 font-size-18; font-weight: bold;"><span class="mr-2 ">Zone Report by Product ({{ $land_name }})</span></h4>
            @endforeach
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="company_land_tree_report_product_detail">
                <thead>
                    <tr>
                        <th style="background-color: #e4e4e4; border:1px solid #eee; min-width: 150px">Zone</th>
                        @foreach ($product as $prod)
                            <th style="text-align: center; background-color: #ffffff; border:1px solid #eee; min-width: 200px">{{ $prod["product_value"] }}</th>
                        @endforeach
                        <th style="text-align: center; background-color: #fffbaf; border:1px solid #eee; min-width: 200px">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records['zone'] as $zone_id => $zone_name)
                        <tr>
                            <td  style="background-color: #e4e4e4; border:1px solid #eee;">{{ $zone_name }}</td>
                            @foreach ($product as $prod)
                                <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                        {{ @$records['total_tree_by_land'][$zone_id][$prod['product_id']] ? $records['total_tree_by_land'][$zone_id][$prod['product_id']] : '-' }}
                                </td>
                            @endforeach
                            <td style="text-align: center; background-color: #fffbaf; border:1px solid #eee;">{{ @$records['total_by_zone'][$zone_id] ? $records['total_by_zone'][$zone_id] : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total</td>
                        @foreach ($product as $prod)
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ @$records['total_by_product'][$prod['product_id']] ? $records['total_by_product'][$prod['product_id']] : '-' }}</td>
                        @endforeach
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ @$records['total_tree'] ? $records['total_tree'] : '-' }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            @foreach ($records['land'] as $land_id => $land_name)
            <h4 class="mb-0 font-size-18; font-weight: bold;"><span class="mr-2 ">Zone Report by PnL Sub Item ({{ $land_name }})</span></h4>
            @endforeach
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="company_land_tree_report_sub_item_detail">
                <thead>
                    <tr>
                        <th style="background-color: #e4e4e4; border:1px solid #eee; min-width: 150px">Zone</th>
                        @foreach ($pnl_sub_item as $item)
                            <th style="text-align: center; background-color: #ffffff; border:1px solid #eee; min-width: 200px">{{ $item->company_pnl_sub_item_name }}</th>
                        @endforeach
                        <th style="text-align: center; background-color: #fffbaf; border:1px solid #eee; min-width: 200px">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records['zone'] as $zone_id => $zone_name)
                        <tr>
                            <td  style="background-color: #e4e4e4; border:1px solid #eee;">{{ $zone_name }}</td>
                            @foreach ($pnl_sub_item as $item)
                                <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                        {{ @$records['total_tree_by_sub_item'][$zone_id][$item->company_pnl_sub_item_id] ? $records['total_tree_by_sub_item'][$zone_id][$item->company_pnl_sub_item_id] : '-' }}
                                </td>
                            @endforeach
                            <td style="text-align: center; background-color: #fffbaf; border:1px solid #eee;">{{ @$records['total_by_zone'][$zone_id] ? $records['total_by_zone'][$zone_id] : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total</td>
                        @foreach ($pnl_sub_item as $item)
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ @$records['total_by_sub_item'][$item->company_pnl_sub_item_id] ? $records['total_by_sub_item'][$item->company_pnl_sub_item_id] : '-' }}</td>
                        @endforeach
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ @$records['total_tree'] ? $records['total_tree'] : '-' }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            @foreach ($records['land'] as $land_id => $land_name)
            <h4 class="mb-0 font-size-18; font-weight: bold;"><span class="mr-2 ">Company Land Tree Age ({{ $land_name }})</span></h4>
            @endforeach
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="company_land_tree_report_product_detail">
                <thead>
                    <tr>
                        <th style="background-color: #d8d9df; border:1px solid #eee; min-width: 150px">Product</th>
                        @foreach ($tree_age as $age)
                            <th style="text-align: center; background-color: #d8d9df; border:1px solid #eee; min-width: 200px">{{ $age->setting_tree_age }}</th>
                        @endforeach
                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total</td>
                    </tr>
                </thead>
                <tbody>
                    {{-- @dd($records_age) --}}
                    @php
                        $total_by_age = [];
                    @endphp
                    @foreach ($product as $prod)
                        @foreach ($records_age as $product_id => $record_age)
                            @if ($prod['product_id'] == $product_id)
                            <tr>
                                @php
                                    $total_pointer_by_product = 0;
                                @endphp
                                <td  style="background-color: #e4e4e4; border:1px solid #eee;">{{ $prod["product_value"] }}</td>
                                @foreach ($tree_age as $tree)
                                    @if(isset($record_age[$tree->setting_tree_age]))
                                        <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                            {{ $record_age[$tree->setting_tree_age]}}
                                        </td>
                                        @php
                                            $total_pointer_by_product += $record_age[$tree->setting_tree_age];
                                            if(isset($total_by_age[$tree->setting_tree_age])){
                                                $total_by_age[$tree->setting_tree_age] += $record_age[$tree->setting_tree_age];
                                            }else{
                                                $total_by_age[$tree->setting_tree_age] = $record_age[$tree->setting_tree_age];
                                            }
                                        @endphp
                                    @else
                                        <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                            -
                                        </td>
                                    @endif
                                @endforeach
                                <td  style="text-align: center; background-color: #fffbaf; border:1px solid #eee; min-width: 200px">{{ $total_pointer_by_product > 0 ? $total_pointer_by_product : '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total</td>
                        @php
                            $grand_total_pointer = 0;
                        @endphp
                        @foreach ($tree_age as $tree)
                            @if(isset($total_by_age[$tree->setting_tree_age]))
                                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ $total_by_age[$tree->setting_tree_age] }}</td>
                                @php
                                    $grand_total_pointer += $total_by_age[$tree->setting_tree_age];
                                @endphp
                            @else
                                <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">-</td>
                            @endif
                        @endforeach
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ $grand_total_pointer > 0 ? $grand_total_pointer : '-' }}</td>
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

        $("#company_land_tree_report_product_detail").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' :true,
        });

        $("#company_land_tree_report_sub_item_detail").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' :true,
        });
    });
    </script>
@endsection
