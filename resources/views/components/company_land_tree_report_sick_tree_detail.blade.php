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
        <div class="page-title-box d-flex align-items-center justify-content-between">
        </div>
        <div class="table-responsive">

            <table class="table table-bordered" id="company_land_tree_report_product_detail">
                <thead>
                    @foreach ($records['land'] as $land_id => $land_name)
                        @foreach ($records['product'] as $product_id => $product_name)
                                <th colspan="2" style="background-color: #e4e4e4; border:1px solid #eee;">{{ $product_name }} ({{ $land_name }})</th>
                        @endforeach
                    @endforeach
                    <tr>
                        <th style="background-color: #e4e4e4; border:1px solid #eee;">Land Zone</th>
                        <th style="text-align: center; background-color: #e4e4e4; border:1px solid #eee;">Tree No</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($records['tree'] as $id => $tree)
                        <tr>
                            <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                {{$records['zone'][$id]}}
                            </td>
                            <td style="text-align: center; background-color: #ffffff; border:1px solid #eee;">
                                {{$tree}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if(!empty($records['total_sick_tree']))
                        <tr>
                            <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">Total Sick Tree</td>
                            @foreach ($records['total_sick_tree'] as $product_id => $total_tree)
                                @if(isset($records['total_sick_tree']))
                                    <td style="text-align: center; font-weight: bold; background-color: #fffbaf; color:#000000; border:1px solid #eee;">
                                        {{ $total_tree }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @else
                        <td style="text-align: center; background-color:#ffffff; color:#000000 border:1px solid #eee" colspan= "2">
                            No Records !
                        </td>
                    @endif
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
