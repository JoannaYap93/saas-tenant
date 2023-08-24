@extends('layouts.master')
@section('title')
    Sales & Product Summary Report
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
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Sales & Product Summary Report</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sales & Product Summary Report</a>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="sales_from"
                                                    placeholder="Start Date" value="{{ @$search['sales_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="sales_to"
                                                    placeholder="End Date" value="{{ @$search['sales_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <x-option_advance_filter :companySel="$company_sel" :productSel="$product_sel" :productCategorySel="$product_category_sel" :customerSel="$customer_sel" :search="$search" />
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Farm</label>
                                            {!! Form::select('company_farm_id', $farm_sel, @$search['company_farm_id'], ['class' => 'form-control', 'id' => 'company_farm_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div id="collapse-whatsapp" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                                        <div class="form-group col-sm-12">
                                            <label for="user_land">Grade Option:</label><br>
                                            <div class="row col-sm-12" id="whatsapp_option">
                                                <div class="custom-control custom-checkbox col-sm-3">
                                                    <input type="checkbox" id="no_grade"
                                                        name="no_grade" value="1" @if(@$search['no_grade']) checked @endif
                                                        class= "form-check-input"
                                                    />
                                                    <label
                                                        for="no_grade">No Grade
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="user_land">Report Option:</label><br>
                                            <div class="row col-sm-12" id="product_cb">
                                                @for ($i = 1; $i <= 3; $i++)
                                                    <div class="custom-control custom-checkbox col-sm-3">
                                                        <input type="radio" id="report_{{ $i }}"
                                                            name="report_id" value="{{ $i }}"
                                                            class= "form-check-input check_report_id"
                                                        />
                                                        <label for="report_{{ $i }}">Report {{ $i }}</label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-left mb-3">
                                        <a href="#collapse-whatsapp" class="text-center manage-show-hide text-dark collapsed mb-2 collapse-whatsapp" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-whatsapp" style="vertical-align: middle;">
                                            <span class="font-weight-bold ">
                                                <span class="text-show-hide">WhatsApp Selection</span>
                                                <i class="bx bxs-down-arrow rotate-icon"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <x-advanced_filter :records="$sales_summary_details_by_product" :search="$search"
                                    :companyCb="$company_cb" :productCb="$product_cb"/>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-info waves-effect waves-light mr-2"
                                            name="submit" value="today">
                                        <i class="fas fa-search mr-1"></i> Today
                                        </button>
                                        <button type="submit" class="btn btn-info waves-effect waves-light mr-2"
                                            name="submit" value="yesterday">
                                        <i class="fas fa-search mr-1"></i> Yesterday
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="whatsapp">
                                            <i class="fab fa-whatsapp mr-1"></i> Whatsapp
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
                    <x-sales_summary_by_product_report :records="$sales_summary_details_by_product" :search="$search" :component=true/>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        let land_arr = [];
        let product_arr = [];
        let lid = '{{ @$search['company_land_id'] }}' ?? null;
        let pid = '{{ @$search['product_id'] }}' ?? null;
        let sid = '{{ @$search['product_size_id'] }}' ?? null;
        var element = document.getElementById("collapse");

        @if(@$search['company_cb_id'])
          let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
          get_company_land_checkbox(c_ids);
          disableProduct(c_ids);
          $('.collapse-selection').trigger('click');
          $('#company_id').val('').attr('disabled', true);
          $('#company_land_id').val('').attr('disabled', true);
          $('#user_id').val('').attr('disabled', true);
          $('#product_id').val('').attr('disabled', true);
          $('#product_category_id').val('').attr('disabled', true);
          $('#product_size_id').val('').attr('disabled', true);
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

        $('.collapse-selection').on('click', function(){
          let id = $(this).attr('aria-controls')

          if($('#' + id).is(':visible')){
            $('#company_id').val('').attr('disabled', false);
            $('#company_land_id').val('').attr('disabled', false);
            $('#product_id').val('').attr('disabled', false);
            $('#product_category_id').val('').attr('disabled', false);
            $('#product_size_id').val('').attr('disabled', false);
            $('.check_company_cb_id').prop('checked', false);
            $('.check_product_cb_id').prop('checked', false);
            $('#customer_id').val('').attr('disabled', false);
            $('.check_company_land_cb_id').prop('checked', false);
          }else{
            $('#company_id').val('').attr('disabled', true);
            $('#company_land_id').val('').attr('disabled', true);
            $('#product_id').val('').attr('disabled', true);
            $('#product_category_id').val('').attr('disabled', true);
            $('#product_size_id').val('').attr('disabled', true);
            $('#customer_id').val('').attr('disabled', true);
          }

        })

        $(document).ready(function(e) {
            get_land_product_size();
            get_product_by_category_land($('#product_category_id').val(),$('#company_land_id').val());
            get_size_by_product(sid);

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

            $('#datepicker6').datepicker({
                orientation: "bottom left",
            });

            $('#report_1').prop("checked", true);

        });

        $('#company_id').on('change', function() {
            let id = $(this).val();
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $('#customer_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                '</option>';
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                    if (e.customer.length > 0) {
                        e.customer.forEach(cust => {
                            customer += '<option value="' + cust.customer_id + '">' + cust.customer_company_name+ ' - ' +cust.customer_name +
                                '</option>';
                        });
                        $('#customer_id').html(customer);
                    } else {
                        $('#customer_id').html('<option value="">No Customer</option>');
                    }
                }
            });
        });

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            let sland = "{{ @$search['company_land_id'] ?? null }}";
            let suser = "{{ @$search['user_id'] ?? null }}";
            let scust = "{{ @$search['customer_id'] ?? null }}";
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            if (suser != null && u.user_id == suser) {
                                user += '<option value="' + u.user_id + '" selected>' + u
                                    .user_fullname +
                                    '</option>';
                            } else {
                                user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                    '</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                    if (e.customer.length > 0) {
                        e.customer.forEach(cust => {
                            if (scust != null && cust.customer_id == scust) {
                                customer += '<option value="' + cust.customer_id + '" selected>' + cust.customer_company_name + ' - ' + cust.customer_name
                                    '</option>';
                            } else {
                                customer += '<option value="' + cust.customer_id + '">' + cust.customer_company_name + ' - ' +cust.customer_name
                                    '</option>';
                            }
                        });
                        $('#customer_id').html(customer);
                    } else {
                        $('#customer_id').html('<option value="">No Customer</option>');
                    }
                }
            });
        }

        $(document).on('change', '#company_land_id', function() {
            let land_id = $(this).val();
            let product_category_id = $('#product_category_id').val();
            get_product_by_category_land(product_category_id,land_id);
        });

        $(document).on('change', '#product_category_id', function() {
            let product_category_id = $(this).val();
            let land_id = $('#company_land_id').val();
            get_product_by_category_land(product_category_id, land_id);
        });

        $(document).on('change', '#product_id', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        function get_land_product_size(){
            let id = $('#company_id').val();

            @if (auth()->user()->user_type_id != 1)
                id = '{{ auth()->user()->company_id }}';
            @endif

            let land_sel = '<option value="">Please Select Land</option>';

            $('#company_land_id').html('<option value="">Loading...</option>');


            var request = $.ajax({
                url: "{{ route('ajax_get_land_product_size', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(function(land){
                            land_arr.push(land);
                            if(lid == land.company_land_id){
                                land_sel += '<option selected value="' + land.company_land_id + '">' + land.company_land_name + '</option>';
                            }else{
                                land_sel += '<option value="' + land.company_land_id + '">' + land.company_land_name + '</option>';
                            }
                        });
                    }
                    $('#company_land_id').html(land_sel);
                }
            });
        }


        function get_product_by_category_land(product_category_id, land_id){
            let sel_product_id = '{{ @$search['product_id'] }}' ?? null;
            let product_sel = '<option value="">Please Select Product</option>';

            $('#product_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_get_product_by_product_category_id_land_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_category_id: product_category_id,
                    land_id : land_id
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (sel_product_id != null && element.id == sel_product_id) {
                                product_sel += '<option value="' + element.id + '" selected>' + element.value + '</option>';
                            } else {
                                product_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });
                    }
                    $('#product_id').html(product_sel);
                }
            });
        }

        function get_size_by_product(product_id){
            let sel_size_id = '{{ @$search['product_size_id'] }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#product_size_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id
                },
                success: function(e) {
                    let option = '<option value="">Please Select Grade</option>';

                    if(e.data.length > 0){
                        e.data.forEach(function(p) {
                            if (sel_size_id != null && p.id == sel_size_id) {
                                option += '<option value="' + p.id + '" selected>' + p.value + '</option>';

                            }else{
                                option += '<option value="' + p.id + '">' + p.value + '</option>';
                            }
                        });
                    }
                    $('#product_size_id').html(option);
                }
            });
        }
    </script>
@endsection
