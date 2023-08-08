@extends('layouts.master')
@section('title')
    Budget Estimate Reporting
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .log {
                cursor: pointer;
            }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    <span class="mr-2 ">Budget Estimate Reporting</span>
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Budget Estimate Report</a>
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
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Month Year</label>
                                            <div class="input-daterange input-group" id="datepicker7"
                                                data-date-format="yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker7">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="year" placeholder="2022"
                                                    value="2022" id="month" autocomplete="off">
                                                    {{-- placeholder="Start Month"
                                                    value="{{$search['month_year']}}" id="month" autocomplete="off"> --}}
                                                    {{-- @dd( $search['month_year']); --}}
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
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <th style="width: 70px;">#</th>
                                <th>Report Detail</th>
                                <th>Budget Estimate Created</th> 
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php
                                        $no = $records->firstItem();
                                    @endphp
                                    @foreach ($records as $budget)
                                        <tr style="text-align: left">
                                            <td style="width: 70px">
                                                {{ $no++ }}
                                            </td>
                                            <td style="min-width: 300px;">
                                                <b class="align-middle mr-1">{{$budget->budget_estimated_title}}</b><br>
                                                <span>{{$budget->budget_estimated_company->company_name}}</span><br>
                                                <span>{{$budget->budget_estimated_year}}</span>
                                            </td>
                                            <td style="white-space:normal; min-width:100px">
                                                <b>{{ date_format(new DateTime($budget->budget_estimated_created), 'Y-m-d h:i A') }}</b><br>
                                                <i>{{ @$budget->budget_estimated_user->user_fullname }}</i>
                                            </td>
                                                <td>
                                                    <a href="{{ route('view_monthly_budget_estimate_report', $budget->budget_estimated_id) }}" class="btn btn-sm btn-outline-success">View</a>
                                                </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <td colspan="4">No records found</td>
                                @endif
                            </tbody>
                        </table>
                        {!! $records->links('pagination::bootstrap-4') !!}
                    </div>
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
        });

        $("#datepicker7").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });
    </script>
@endsection
