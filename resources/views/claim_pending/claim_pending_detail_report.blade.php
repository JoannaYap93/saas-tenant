@extends('layouts.master-without-nav')

@section('title'){{$page_title}}@endsection

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
            <table class="table table-nowrap table-bordered" id="claim-detail-report">
                <thead>
                    <tr>
                        @if (auth()->user()->user_type_id == 1)
                            <th style="background-color: #343a40; border:1px solid #eee; width: 35%; color: #ffffff"><b>Company</b></th>
                            <th style="text-align: center; background-color: #343a40; border:1px solid #eee; color: #ffffff"><b>{{@$company->company_name}}</b></th>
                        @else
                            <th style="background-color: #343a40; border:1px solid #eee; width: 35%; color: #ffffff"><b>Farm Manager</b></th>
                            <th style="text-align: center; background-color: #343a40; border:1px solid #eee; color: #ffffff"><b>{{@$farm_manager->worker_name}}</b></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (@$records['total_by_expense_category'])
                        <tr>
                            <th style="background-color: #d8d9df" colspan="2">Company Expenses</th>
                        </tr>
                        @foreach ($expense_category as $expense)
                            @if (@$records['total_by_expense_category'][$expense->setting_expense_category_id])
                                <tr>
                                    <td style="font-weight: bold; background-color: #ebebeb; border:1px solid #eee;">{{json_decode($expense->setting_expense_category_name)->en}}</td>
                                    <td style="font-weight: bold; text-align: center; background-color: #ebebeb; border:1px solid #eee;">{{ number_format($records['total_by_expense_category'][$expense->setting_expense_category_id], 2) }}</td>
                                </tr>
                            @endif
                            @foreach ($expense_item as $item)
                                @if ($item->setting_expense_category_id == $expense->setting_expense_category_id)
                                    @if (@$records['claim_item_expense'][$item->setting_expense_category_id][$item->setting_expense_id])
                                        <tr>
                                            <td style="background-color: #f5f5f5; border:1px solid #eee;">{{json_decode($item->setting_expense_name)->en}}</td>
                                            <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{{ number_format($records['claim_item_expense'][$item->setting_expense_category_id][$item->setting_expense_id], 2) }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            {{-- @if (@$records['claim_item_expense_manually'][$expense->setting_expense_category_id]['others'])
                                <tr>
                                    <td style="background-color: #f5f5f5; border:1px solid #eee;">Others</td>
                                    <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{{ number_format($records['claim_item_expense_manually'][$expense->setting_expense_category_id]['others'], 2) }}</td>
                                </tr>
                            @endif --}}
                        @endforeach
                    @endif
                    @if (@$records['total_by_material_category'])
                        <tr>
                            <th style="background-color: #d8d9df" colspan="2">Raw Materials</th>
                        </tr>
                        @foreach ($material_category as $material)
                            @if (@$records['total_by_material_category'][$material->raw_material_category_id])
                                <tr>
                                    <td style="font-weight: bold; background-color: #ebebeb; border:1px solid #eee;">{{json_decode($material->raw_material_category_name)->en}}</td>
                                    <td style="font-weight: bold; text-align: center; background-color: #ebebeb; border:1px solid #eee;">{{ number_format($records['total_by_material_category'][$material->raw_material_category_id], 2) }}</td>
                                </tr>
                            @endif
                            @foreach ($material_item as $item)
                                @if ($item->raw_material_category_id == $material->raw_material_category_id)
                                    @if (@$records['claim_item_material'][$item->raw_material_category_id][$item->raw_material_id])
                                        <tr>
                                            <td style="background-color: #f5f5f5; border:1px solid #eee;">{{json_decode($item->raw_material_name)->en}}</td>
                                            <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{{ number_format($records['claim_item_material'][$item->raw_material_category_id][$item->raw_material_id], 2) }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            @if (@$records['claim_item_material_manually'][$material->raw_material_category_id]['others'])
                                <tr>
                                    <td style="background-color: #f5f5f5; border:1px solid #eee;">Others</td>
                                    <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{{ number_format($records['claim_item_material_manually'][$material->raw_material_category_id]['others'], 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total (RM)</td>
                        <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{ $records['total_claim_item'] > 0 ? number_format($records['total_claim_item'], 2) : '-' }}</td>
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
        $("#claim-detail-report").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true,
            'scrollable' :true,
        });
    });
    </script>
@endsection
