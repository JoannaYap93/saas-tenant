@extends('layouts.master')
@section('title')
    Farm Manager Worker Expense Report
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

    .bg-red {
        background: #f46a6a;
        color: #ffffff
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
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Farm Manager Expense Reporting</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Worker Expense Report</a>
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
                                        <label for="">Year</label>
                                        <div class="input-daterange input-group" id="datepicker6"
                                            data-date-format="yyyy" data-date-autoclose="true"
                                            data-provide="datepicker" data-date-container="#datepicker6">
                                            <input type="text" style="width: 75px" class="form-control"
                                                name="year" placeholder="Start Date"
                                                value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Month</label>
                                            <div class="input-daterange input-group" id="datepicker7"
                                                data-date-format="mm" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker7">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="month" placeholder="Select Month"
                                                    value="{{ @$search['month'] }}" id="month" autocomplete="off">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Farm Manager</label>
                                            <select name="user_id" id="user_id" class=" form-control">
                                                <option value="">Please select company first</option>
                                            </select>
                                        </div>
                                    </div>
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
        </div>
    </div>
   <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <x-farm_manager_worker_expense_report :month="$month" :companies="$company"  :managers="$manager" :companyExpenseWorker="$company_expense_worker" :companyWorker="$worker" :search="$search"
                    :component=true />
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

    @if(auth()->user()->user_type_id == 1)
    $('#company_id').on('change', function() {
        get_farm_manager($(this).val());
    });
    @else
    let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
    get_farm_manager(company_id);
    @endif

    var exist_company = "<?php echo @$search['company_id']; ?>";

    if (exist_company > 0) {
        $('#company_id').trigger("change");
    }

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

            $("#farm-manager-worker-expense-table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
        });

    $("#datepicker6").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });

    $("#datepicker7").datepicker({
            format: "mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once year is selected
        });

    function get_farm_manager(company_id) {

        var exist_farm_manager = "<?php echo @$search['user_id']; ?>";

        let user = '<option value="">Please Select Farm Manager</option>';
        $('#user_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "{{ route('ajax_get_farm_manager_sel_by_company', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
            },
            success: function(e) {
                if (e.length > 0) {
                    e.forEach(u => {
                        if(u.id == exist_farm_manager){
                            user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                        }else{
                            user += '<option value="' + u.id + '">' + u.name +'</option>';
                        }
                    });
                    $('#user_id').html(user);
                } else {
                    $('#user_id').html('<option value="">No Farm Manager</option>');
                }
            }
        });
    }
    </script>
@endsection
