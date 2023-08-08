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
        width: 75% !important;
        margin : auto;
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
    </style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-nowrap table-bordered" id="claim-detail-report">
                <thead>
                    <tr>
                        <th rowspan="2" style="text-align: left; background-color: #e4e4e4; border:1px solid #eee;">User</th>
                        <th rowspan="2" style="text-align: left; background-color: #ffffff; border:1px solid #eee;">Date</th>
                        <th rowspan="2" style="text-align: left; background-color: #e4e4e4; border:1px solid #eee;">Items</th>
                        <th colspan="3" style="text-align: left; background-color: #fffbaf; border:1px solid #eee;">Amount (RM)</th>
                    </tr>
                    {{-- <tr>
                        <th style="text-align: center; background-color: #ffffff; border:1px solid #eee;">Completed</th>
                        <th style="text-align: center; background-color: #e4e4e4; border:1px solid #eee;">Pending</th>
                        <th style="text-align: center; background-color: #ffffff; border:1px solid #eee;">Total</th>
                    </tr> --}}
                </thead>
                <tbody>
                    @php
                        $total_claim = 0;
                        $total_claim_pending = 0;
                        $total = 0
                    @endphp
                    @foreach ($records['user'] as $user_id => $user)
                        @php
                            $total += @$records['data'][$user_id]['total_claim_amount'];
                            // $total_claim += @$records['data'][$user_id]['claim'];
                            // $total_claim_pending += @$records['data'][$user_id]['claim_pending'];
                            // $total += @$records['data'][$user_id]['claim'] + @$records['data'][$user_id]['claim_pending'];
                            $current_total = @$records['data'][$user_id]['claim'] + @$records['data'][$user_id]['claim_pending'];
                        @endphp

                    <tr>
                        <td style="text-align: left; background-color: #e4e4e4; border:1px solid #eee;">{{@$user->user_fullname}}</td>
                        {{-- <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{!! substr(@$records['data'][$user_id]['item_date'], 8, 2) . '-' . substr(@$records['data'][$user_id]['item_date'], 5, 2) . '-' . substr(@$records['data'][$user_id]['item_date'], 2, 2) !!}</td> --}}
                        <td style="text-align: left; background-color: #ffffff; border:1px solid #eee;">{!! @$records['data'][$user_id]['item_date'] !!}</td>
                        <td style="text-align: left; background-color: #e4e4e4; border:1px solid #eee;">{!! @$records['data'][$user_id]['item'] !!}</td>
                        @if ($search['claim_status_id'] == 6)
                            <td style="text-align: left; background-color: #fffde0; border:1px solid #eee;">{!! @$records['data'][$user_id]['claim_amount'] ? $records['data'][$user_id]['claim_amount']: '- </br>' !!}</td>
                        @elseif ($search['claim_status_id'] == 5)
                            <td style="text-align: left; background-color: #fffde0; border:1px solid #eee;">{!! @$records['data'][$user_id]['claim_amount'] ? $records['data'][$user_id]['claim_amount']: '- </br>' !!}</td>
                        @endif
                        {{-- <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">{{@$current_total ? number_format($current_total,2) : '-'}}</td> --}}
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total</td>
                        {{-- @if ($search['claim_status_id'] == 6) --}}
                            <td style="text-align: left; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{$total ? number_format($total,2) : '-'}}</td>
                        {{-- @elseif ($search['claim_status_id'] == 5)
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{$total ? number_format($total,2) : '-'}}</td>
                        @endif --}}
                        {{-- <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">{{$total ? number_format($total,2) : '-'}}</td> --}}
                    </tr>
                </tfoot>
            </table>
        </div>
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
