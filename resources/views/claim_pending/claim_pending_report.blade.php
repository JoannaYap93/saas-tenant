@extends('layouts.master')
@section('title')
    {{$page_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .table-responsive {
            height: 400px !important;
            overflow: hidden !important;
            overflow: scroll !important;
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
        ::-webkit-scrollbar {
            display: inherit;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 "> {{$page_title}}</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);"> {{$page_title}}</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ $submit }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Year</label>
                                    <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                        <input type="text"  class="form-control"name="year" placeholder="Start Date" value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->user_type_id == 1)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Company: </label>
                                        {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                    name="submit" value="search">
                                    <i class="fas fa-search mr-1"></i> Search
                                </button>
                                <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                    name="submit" value="export">
                                    <i class="fas fa-download mr-1"></i> Export
                                </button>
                                <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                    name="submit" value="reset">
                                    <i class="fas fa-times mr-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <x-claim_pending_report_component :search="$search" :records="$records" :monthSel="$month_sel" :companySel="$company" :records="$records" :farmManager="$farm_manager" :component=true/>
                </div>
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
        $("#datepicker6").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });

        $(document).ready(function(e) {
            $(".popup").fancybox({
                'type': 'iframe',
                'width': '90%',
                'height': '90%',
                'autoDimensions': false,
                'autoScale': false,
                iframe : {
                    css : {
                        width : '90%',
                        height: '90%'
                    }
                }
            });
            $(".fancybox").fancybox();

            $("#claim-pending-report").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
        });
    </script>
@endsection
