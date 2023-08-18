@extends('layouts.master')
@section('title')
    Formula Usage Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .bg-grey {
            background: #e4e4e4;
        }

        .bg-red {
            background: #f46a6a;
            color: #ffffff
        }

        table {
            text-align: center;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Formula Usage Reporting</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Formula Usage Report</a>
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
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <label for="">Land: </label>
                                        <select name="company_land_id" class="form-control" id="company_land_id">

                                        </select>
                                        {{-- {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control']) !!} --}}
                                    </div>
                                </div>
                                <div>
                                    <x-advanced_filter :companyCb="$company_cb" :productCb="$product_cb"
                                     :search="$search"/>
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
                    <x-formula_usage_report_sa
                    :monthSel="$month_sel" :search="$search" :settingFormulaCategory="$setting_formula_usage_category" :company="$company"
                    :companyLandSel="$company_land_sel" :companySel="$company_sel" :userSel="$user_sel" :formulaUsage="$formula_usage"
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
     var element = document.getElementById("collapse");

        @if(@$search['company_cb_id'])
          let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
          disableProduct(c_ids);
          $('.manage-show-hide').trigger('click');
          $('#company_id').val('').attr('disabled', true);
          $('#company_land_id').val('').attr('disabled', true);
          $('#user_id').val('').attr('disabled', true);
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
          disableProduct(selected_val);
        })

        function disableProduct(selected_val){
          $.ajax({
              url: "{{ route('ajax_get_products_multi_company', ['tenant' => tenant('id')]) }}",
              method: "POST",
              data: {
                  _token: "{{ csrf_token() }}",
                  company_id: selected_val
              },
              success: function(e) {
                let checkbox = '';
                if(e.length > 0){
                  $('.check_product_cb_id').attr('disabled', true);
                  e.forEach((product) => {
                    $('#product_cb_' + product.id).attr('disabled', false);
                  });
                }else{
                  $('.check_product_cb_id').attr('disabled', false);
                }
              }
          });
        }

        $('.manage-show-hide').on('click', function(){
          let id = $(this).attr('aria-controls')

          if($('#' + id).is(':visible')){
            $('#company_id').val('').attr('disabled', false);
            $('#company_land_id').val('').attr('disabled', false);
            $('#user_id').val('').attr('disabled', false);
            $('.check_company_cb_id').prop('checked', false);
            $('.check_product_cb_id').prop('checked', false);
          }else{
            $('#company_id').val('').attr('disabled', true);
            $('#company_land_id').val('').attr('disabled', true);
            $('#user_id').val('').attr('disabled', true);
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
                      height: '100%'
                  }
              }
          });
          $(".fancybox").fancybox();

            $("#collect_do_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true
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
            let land = '<option value="">Please Select Land</option>';
            let warehouse = '<option value="">Please Select Warehouse</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_company_land_warehouse', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            land += '<option value="' + element.company_land_id + '">' + element
                                .company_land_name + '</option>';
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                    if (e.warehouse.length > 0) {
                        e.warehouse.forEach(u => {
                            warehouse += '<option value="' + u.warehouse_id + '">' + u.warehouse_name +
                                '</option>';
                        });
                        $('#user_id').html(warehouse);
                    } else {
                        $('#user_id').html('<option value="">No warehouse</option>');
                    }
                }
            });
        });

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let warehouse = '<option value="">Please Select User</option>';
            let sland = "{{ @$search['company_land_id'] ?? null }}";
            let suser = "{{ @$search['warehouse_id'] ?? null }}";
            $.ajax({
                url: "{{ route('ajax_company_land_warehouse', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            if (sland != null && element.company_land_id == sland) {
                                land += '<option value="' + element.company_land_id + '" selected>' +
                                    element
                                    .company_land_name + '</option>';
                            } else {
                                land += '<option value="' + element.company_land_id + '">' + element
                                    .company_land_name + '</option>';
                            }
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                    if (e.warehouse.length > 0) {
                        e.warehouse.forEach(u => {
                            if (suser != null && u.warehouse_id == suser) {
                                warehouse += '<option value="' + u.warehouse_id + '" selected>' + u
                                    .warehouse_name +
                                    '</option>';
                            } else {
                                warehouse += '<option value="' + u.warehouse_id + '">' + u.warehouse_name +
                                    '</option>';
                            }
                        });
                        $('#user_id').html(warehouse);
                    } else {
                        $('#user_id').html('<option value="">No Warehouse</option>');
                    }
                }
            });
        }
    </script>
@endsection
