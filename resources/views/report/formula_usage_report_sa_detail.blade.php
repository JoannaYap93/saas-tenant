@extends('layouts.master-without-nav')
@section('title')
    Formula Usage Report
@endsection
@section('css')
    <style>
        table {
            text-align: center;
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
    <div class="card-body">
            <x-formula_usage_report_sa_detail :productS="$products" :companY="$company" :formulaUsage="$formula_usage" :monthName="$month_name" :search="$search"
            :companyLand="$company_land" :settingFormulaCategoryName="$setting_formula_category_name"/>
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
        $("#formula_usage_detail_table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'freezeHead' : true
        });
    });
    </script>
@endsection
