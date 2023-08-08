@extends('layouts.master-without-nav')
@section('title')
Sales & Product Summary Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        table {
            text-align: center;
        }

        .bg-grey {
            background: #e4e4e4;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18"><span class="mr-2 ">Sales & Product Summary Report</span></h4>
                    </div>

                    <x-sales_summary_report :records="$records"/>

                    <form method="POST">
                        @csrf
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                name="submit" value="export">
                                <i class="fas fa-download mr-1"></i> Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
