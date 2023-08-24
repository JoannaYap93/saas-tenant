@extends('layouts.master')
@section('title')
    Worker Attendance Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

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
        .table-responsive::-webkit-scrollbar {
            display: inherit;
        }
        .grid-parent {
            display: grid;
            grid-template-columns: 1fr 1fr
        }
        </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Worker Attendance Reporting</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Worker Attendance Report</a>
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
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Land</label>
                                            <select name="company_land_id" class="form-control" id="company_land_id">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Worker Role</label>
                                            {!! Form::select('worker_role_id', $worker_role_sel, @$search['worker_role_id'], ['class' => 'form-control', 'id' => 'worker_role_sel']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div id="collapse" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                                        @if(!@$companyComponent)
                                            <div class="form-group col-sm-12">
                                                <label for="user_land">Companies:</label><br>
                                                <div class="row col-sm-12" id="company_cb">
                                                    @foreach ($company_cb as $id => $companys)
                                                        <div class="custom-control custom-checkbox col-sm-3">
                                                            <input type="checkbox" id="company_cb_{{ $id }}"
                                                                name="company_cb_id[]" value="{{ $id }}"
                                                                class= "form-check-input check_company_cb_id"
                                                                @if(@$search['company_cb_id'])
                                                                @foreach(@$search['company_cb_id'] as $key => $selected_company)
                                                                    {{ $selected_company == $id ? 'checked' : '' }}
                                                                @endforeach
                                                            @endif
                                                            />
                                                            <label for="company_cb_{{ $id }}">{{ $companys }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                      </div>
                                      <div class="col-12 text-left mb-3">
                                        <a href="#collapse" class="text-center manage-show-hide text-dark collapsed mb-2" data-toggle="collapse" aria-expanded="true" aria-controls="collapse" style="vertical-align: middle;">
                                            <span class="font-weight-bold ">
                                                <span class="text-show-hide">Multiple Company Selection</span>
                                                <i class="bx bxs-down-arrow rotate-icon"></i>
                                            </span>
                                        </a>
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
                                            name="reset" value="reset">
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
                {{-- @dd($records); --}}
                <div class="card-body">
                    <x-worker_attendance_report_component :daySel="$day_array" :workerList="$worker_list" :search="$search" :records="$records" :monthSel="$month_sel" :currentday="$current_day"
                    :component=true />
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

         var element = document.getElementById("collapse");

         @if(@$search['company_cb_id'])
          let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
          $('.manage-show-hide').trigger('click');
          $('#company_id').val('').attr('disabled', true);
        @endif

        $('.check_company_cb_id').on('click', function() {
          let selected_val = [];
          let id = document.getElementById('company_cb');
          let checkbox = id.getElementsByTagName('INPUT');

          for (var i = 0; i < checkbox.length; i++){
            if (checkbox[i].checked){
              selected_val.push(checkbox[i].value);
            }
          }
        //   get_company_land_checkbox(selected_val);
        //   disableProduct(selected_val);
        })

        $('.manage-show-hide').on('click', function(){
          let id = $(this).attr('aria-controls')

          if($('#' + id).is(':visible')){
            $('#company_id').val('').attr('disabled', false);
            $('.check_company_cb_id').prop('checked', false);
          }else{
            $('#company_id').val('').attr('disabled', true);
          }
        })

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

            $(document).ready(function(e) {
                $("#tree-target-report-table").parent().freezeTable({
                    'freezeColumn': true,
                    'shadow': true,
                    'scrollable': true,
                });
            });

            $(".fancybox").fancybox();

            $("#company-expense-table").parent().freezeTable({
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

        @if (@$search['company_id'] != null)
            get_land_user('{{ $search['company_id'] }}');
        @else
            get_land_user('{{ auth()->user()->company_id }}');
        @endif

        $('#company_id').on('change', function() {
            let id = $(this).val();
            get_land_user(id);
        });

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            let sland = '{{ @$search['company_land_id'] }}' ?? null;
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    console.log(sland);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            if (sland != null && element.company_land_id == sland) {
                                land += '<option value="' + element.company_land_id + '" selected>' +
                                    element.company_land_name + '</option>';
                            } else {
                                land += '<option value="' + element.company_land_id + '">' + element
                                    .company_land_name + '</option>';
                            }
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                }
            });
        }
    </script>
@endsection
