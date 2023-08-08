@extends('layouts.master')
@section('title')
    {{$page_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .table-responsive {
            height: 400px !important;
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
                                <a href="{{route('claim_report','reset=1')}}" class="btn btn-danger waves-effect waves-light mr-2"><i class="fas fa-times mr-1"></i> Reset</a>
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
                    @if($records)
                        <div class="table-responsive">
                            <table class="table" id="claim-report">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="background-color:white; color:#000000" >Company</th>
                                        @foreach ($month_sel as $key => $month)
                                            <th colspan="2" style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{$month}}</th>
                                        @endforeach
                                        <th colspan="2" style="text-align: center; background-color:#fffbaf; color:#000000">Total</th>
                                    </tr>
                                    <tr>
                                        @foreach ($month_sel as $key => $month)
                                            <th style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">C</th>
                                            <th style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">P</th>
                                        @endforeach
                                        <th style="text-align: center; background-color:#fffbaf; color:#000000">S</th>
                                        <th style="text-align: center; background-color:#fffbaf; color:#000000">P</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_month_claim = array();
                                        $total_month_pending = array();
                                        unset($company_sel['']);
                                    @endphp
                                    @foreach ($company_sel as $company_id => $company_name)
                                        @php
                                            $total_company_claim = 0;
                                            $total_company_pending = 0;
                                            // $total_link = route('claim_detail_report',['year'=>$search['year'],'month'=>$key, 'company_id' => $company_id]);
                                        @endphp
                                        @php
                                            if (@$search['company_id']) {
                                                if ($company_id != $search['company_id']) {
                                                    continue;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$company_name}}</td>
                                            @foreach ($month_sel as $key => $month)
                                                @php
                                                    $total_company_claim += @$records[$company_id][$key]['claim'];
                                                    $total_company_pending += @$records[$company_id][$key]['claim_pending'];

                                                    if (isset($total_month_claim[$key]) || isset($total_month_pending[$key]) ) {
                                                        $total_month_claim[$key] += @$records[$company_id][$key]['claim'];
                                                        $total_month_pending[$key] += @$records[$company_id][$key]['claim_pending'];
                                                    }else{
                                                        $total_month_claim[$key] = @$records[$company_id][$key]['claim'];
                                                        $total_month_pending[$key] = @$records[$company_id][$key]['claim_pending'];
                                                    }

                                                    // $link = route('claim_detail_report',['year'=>$search['year'],'month'=>$key, 'company_id' => $company_id]);
                                                    $link1 = route('claim_detail_report',['year' => $search['year'], 'month' => $key, 'company_id' => $company_id, 'claim_status_id' => 6]);
                                                    $link2 = route('claim_detail_report',['year' => $search['year'], 'month' => $key, 'company_id' => $company_id, 'claim_status_id' => 5]);
                                                @endphp

                                                <td style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{!!@$records[$company_id][$key]['claim'] ? '<a  class="popup" href="'.$link1.'">'.number_format($records[$company_id][$key]['claim'],2).'</a>' : '-'!!}</td>
                                                <td style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{!!@$records[$company_id][$key]['claim_pending'] ? '<a  class="popup" href="'.$link2.'">'.number_format($records[$company_id][$key]['claim_pending'],2).'</a>' : '-'!!}</td>
                                            @endforeach
                                                <td style="text-align: center; background-color:#fffbaf; color:#000000">{!!@$total_company_claim ? '<a  class="popup" href="'.$link1.'">'.number_format($total_company_claim,2).'</a>'  : '-'!!}</td>
                                                <td style="text-align: center; background-color:#fffbaf; color:#000000">{!!@$total_company_pending ? '<a  class="popup" href="'.$link2.'">'.number_format($total_company_pending,2).'</a>'  : '-'!!}</td>
                                        </tr>

                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">Total</td>
                                        @php
                                            $total_claim = 0;
                                            $total_pending = 0;
                                        @endphp
                                        @foreach ($month_sel as $key => $month)
                                            @php
                                                $total_claim += @$total_month_claim[$key];
                                                $total_pending += @$total_month_pending[$key];
                                            @endphp
                                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{@$total_month_claim[$key] ? number_format($total_month_claim[$key],2) : '-'}}</td>
                                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{@$total_month_pending[$key] ? number_format($total_month_pending[$key],2) : '-'}}</td>
                                        @endforeach
                                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{@$total_claim ? number_format($total_claim,2) : '-'}}</td>
                                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">{{@$total_pending ? number_format($total_pending,2) :  '-'}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <br><strong>C - Completed</strong><br>
                        <strong>P - Pending</strong>
                    @else
                        No records found!
                    @endif
                </div>
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

            $("#claim-report").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
        });
    </script>
@endsection
