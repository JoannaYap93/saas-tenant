@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

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

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"><span class="mr-2 ">Sync Attendance Reporting</span></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Sync Attendance Report</a>
                    </li>
                    <li class="breadcrumb-item active">Report</li>
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Date</label>
                                        <div class="input-daterange input-group" id="datepicker6"
                                            data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                            data-provide="datepicker" data-date-container="#datepicker6">

                                            <input type="text" style="width: 75px" class="form-control" name="start_date"
                                                placeholder="Start Date" value="{{ $search['start_date'] }}" id="start"
                                                autocomplete="off">

                                            <input type="text" style="width: 75px" class="form-control" name="end_date"
                                                placeholder="End Date" value="{{ $search['end_date'] }}" id="end"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <x-option_filter :companySel="$company_sel" :customerSel="$customer_sel" :search="$search" />
                            </div>
                            <div>
                                <x-advanced_filter :companyCb="$company_cb" :productCb="$product_cb" :syncCollectDetails="$sync_collect_details"
                                :syncDoDetails="$sync_do_details" :collectDetails="$collect_details" :doDetails="$do_details" :search="$search"/>
                            </div>
                            <div class="row">
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
                <x-sync_attendance_report
                    :dateRange="$date_range" :syncCollectDetails="$sync_collect_details" :syncDoDetails="$sync_do_details"
                    :collectDetails="$collect_details" :doDetails="$do_details" :companyLand="$company_land" :users="$users"
                    :component=true />
            </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        var element = document.getElementById("collapse");

        @if(@$search['company_cb_id'])
          let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
          get_company_land_checkbox(c_ids);
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
          get_company_land_checkbox(selected_val);
          disableProduct(selected_val);
        })

        function get_company_land_checkbox(selected_val)
        {   let div_filter = '';

            @if (@$search['company_land_cb_id'])
                let land_ids = {!!json_encode($search['company_land_cb_id'])!!};
                let land_cb_ids = land_ids.map(function(item){
                    return parseInt(item, 10);
                });
            @endif

            $.ajax({
              url: "{{ route('ajax_get_land_by_company_id', ['tenant' => tenant('id')]) }}",
              method: "POST",
              data: {
                  _token: "{{ csrf_token() }}",
                  company_id: selected_val
              },
              success: function(e) {

                e.forEach((land) => {
                    div_filter += '<div class="custom-control custom-checkbox">';
                    div_filter +='<input type="checkbox" id="company_land_cb_'+land.id+'"';
                    div_filter +='name="company_land_cb_id[]" value="'+land.id+'"';
                    div_filter +='class= "form-check-input check_company_land_cb_id"';
                    div_filter +='@if(@$search["company_land_cb_id"])';
                    if(land_cb_ids.includes(land.id)){
                        div_filter += 'checked';
                    }
                    div_filter +='@endif';
                    div_filter +='/>';
                    div_filter +='<label for="company_land_cb_'+land.id+'">'+land.land_name+'</label>';
                    div_filter +='</div>';
                });
                $('.land_id_cb').html(div_filter);
              }
          });
        }

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
            $('.check_company_land_cb_id').prop('checked', false);
          }else{
            $('#company_id').val('').attr('disabled', true);
            $('#company_land_id').val('').attr('disabled', true);
            $('#user_id').val('').attr('disabled', true);
          }

        })

        $(document).ready(function(e) {
            $('#customer').hide();
            $("#do_collect_sync_by_farm_user_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true
            });

            $('#datepicker6').datepicker({
                orientation: "bottom left",
            });


            @if (@$search['company_id'] != null)
                get_company_land($('#company_id').val()).then(function(){
                    get_land_user($('#company_land_id').val());
                });
            @else
                get_company_land('{{ auth()->user()->company_id }}').then(function(){
                    get_land_user($('#company_land_id').val());
                });
            @endif

        });

        $('#company_id').on('change', function() {
            get_company_land($('#company_id').val());
        });

        $('#company_land_id').on('change', function() {
            get_land_user($('#company_land_id').val());
        });



        function get_company_land(id){
            let sel_land_id = '{{ @$search['company_land_id'] }}' ?? null;
            let land_sel = '<option value="">Please Select Land</option>';
            let user_sel = '<option value="">Please Select User</option>';

            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');

            var request = $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            if (sel_land_id != null && element.company_land_id == sel_land_id) {
                                land_sel += '<option value="' + element.company_land_id + '" selected>' + element.company_land_name + '</option>';
                            } else {
                                land_sel += '<option value="' + element.company_land_id + '">' + element.company_land_name + '</option>';
                            }
                        });
                    }
                    $('#company_land_id').html(land_sel);
                    $('#user_id').html(user_sel);
                }
            });
            return request;
        }

        function get_land_user(company_land_id){
            let sel_land_id = '{{ @$search['company_land_id'] }}' ?? null;
            let sel_user_id = '{{ @$search['user_id'] }}' ?? null;

            let user_sel = '<option value="">Please Select User</option>';
            $('#user_id').html('<option value="">Loading...</option>');

            var request = $.ajax({
                url: "{{ route('ajax_get_users_by_land', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_land_id: company_land_id
                },
                success: function(e) {
                    if (e.users.length > 0) {
                        e.users.forEach(user => {
                            if (sel_user_id != null && user.user_id == sel_user_id) {
                                user_sel += '<option value="' + user.user_id + '" selected>' + user.user_fullname + '</option>';
                            } else {
                                user_sel += '<option value="' + user.user_id + '">' + user.user_fullname + '</option>';
                            }
                        });
                        $('#user_id').html(user_sel);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                }
            });
        }
    </script>
@endsection
