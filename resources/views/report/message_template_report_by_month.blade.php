@extends('layouts.master-without-nav')
@section('title')
    {{$page_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

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
    </style>
@endsection

@section('content')
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
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
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
                                <div class="row">
                                    <div class="col-lg-3">
                                        <select class="form-control" id="year" name="year" tabindex="-1" aria-hidden="true">
                                            @foreach($year_sel as $year)
                                                @if(@$search['year'])
                                                    <option @if(@$search['year'] == $year) selected @endif value="{{$year}}">{{$year}}</option>
                                                @else
                                                    <option @if($current_year == $year) selected @endif value="{{$year}}">{{$year}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3">
                                        <select class="form-control" id="month" name="month" tabindex="-1" aria-hidden="true">
                                            @foreach($month_sel as $key => $month)
                                                @if(@$search['month'])
                                                    <option @if(@$search['month'] == $key) selected @endif value="{{$key}}">{{$month}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    @if ($records)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center; font-weight: bold; background-color: #e4e4e4;">Day</th>
                                    @foreach ($message_templates as $template)
                                        <th style="text-align: center; font-weight: bold; background-color: #e4e4e4;">{{$template->message_template_name}}</th>
                                    @endforeach
                                    <th style="text-align: center; font-weight: bold; background-color: #e4e4e4;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_days = cal_days_in_month(CAL_GREGORIAN, $search['month'], $search['year']);
                                    $total_cols = array();
                                @endphp
                                @for($day=1;$day<=$total_days;$day++)
                                    @php
                                        $total_rows = 0;
                                    @endphp
                                    <tr>
                                        <td>{{$day}}</td>
                                        @foreach ($message_templates as $template)
                                            @php
                                                $total_rows += @$records[$template->message_template_id][$day] ?? 0;
                                                if(!array_key_exists($template->message_template_id,$total_cols)){
                                                    $total_cols[$template->message_template_id] = @$records[$template->message_template_id][$day] ?? 0;
                                                }else{
                                                    $total_cols[$template->message_template_id] += @$records[$template->message_template_id][$day] ?? 0;
                                                }

                                                $link = route('message_template_report_by_day', ['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$search['month'],'day'=> $day, 'id'=> $template->message_template_id]);

                                            @endphp
                                                <td>{!!@$records[$template->message_template_id][$day] ? '<a class="popup" href="'.$link.'">'.number_format($records[$template->message_template_id][$day]).'</a>' : '-'!!}</td>
                                        @endforeach
                                        @php
                                             $link = route('message_template_report_by_day', ['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$search['month'],'day'=> $day, 'id'=> 0]);
                                        @endphp
                                         <td>{!!@$total_rows > 0 ? '<a class="popup" href="'.$link.'">'.number_format($total_rows).'</a>' : '-'!!}</td>
                                    </tr>
                                @endfor

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">Total</td>
                                    @php
                                        $total_sum_rows = 0;
                                    @endphp
                                    @foreach ($message_templates as $template)
                                        @php
                                            $link = route('message_template_report_by_day',['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$search['month'],'day'=> 0, 'id'=> $template->message_template_id]);
                                        @endphp
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{!!@$total_cols[$template->message_template_id] > 0 ? '<a class="popup" href="'.$link.'">'.number_format($total_cols[$template->message_template_id]).'</a>' : '-'!!}</td>
                                        @php
                                            $total_sum_rows += @$total_cols[$template->message_template_id] ?? 0;
                                        @endphp
                                    @endforeach
                                    @php
                                        $link = route('message_template_report_by_day',['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$search['month'],'day'=> 0, 'id'=> 0]);
                                    @endphp
                                     <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">{!!@$total_sum_rows > 0 ? '<a class="popup" href="'.$link.'">'.number_format($total_sum_rows).'</a>' : '-'!!}</td>
                                </tr>
                            </tfoot>
                        </table>

                    @else
                        No records found!
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
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
                        height: '100%'
                    }
                }
            });
            $(".fancybox").fancybox();
        });
    </script>
@endsection
