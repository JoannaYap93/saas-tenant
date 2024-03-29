@extends('layouts.master')
@section('title')
    Daily DO Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
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
        background: #f46a6a !important;
        color: #ffffff !important
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
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Delivery Order (Daily Report)</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Delivery Order Daily Report</a>
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

                                                <input type="text" style="width: 75px" class="form-control" name="start_date"
                                                    placeholder="Start Date" value="{{ $search['start_date'] }}" id="start"
                                                    autocomplete="off">

                                                <input type="text" style="width: 75px" class="form-control" name="end_date"
                                                    placeholder="End Date" value="{{ $search['end_date'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <x-option_filter_do_daily :companySel="$company_sel" :customercategorysel="$customer_category_sel" :customerSel="$customer_sel" :ordertype="$order_type" :search="$search"/>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Product Category</label>
                                            {!! Form::select('product_category_id', $product_category_sel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="show_product">
                                        <div class="form-group">
                                            <label for="">Product</label>
                                            <select name="product_id" id="product_id" class="form-control">
                                                @if (@$search['product_id'])
                                                    <option></option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="show_size">
                                        <div class="form-group">
                                            <label for="">Grade</label>
                                            <select name="product_size_id" id="product_size_id" class="form-control">
                                                @if (@$search['product_size_id'])
                                                <option></option>
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <x-advanced_filter :deliveryOrderItemDetails="$delivery_order_item_details"
                                    :companyCb="$company_cb" :productCb="$product_cb"  :search="$search"/>
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
                    <x-do_daily_report :dateRange="$date_range" :company="$company" :products="$products"
                    :doDetails="$delivery_order_item_details" :search="$search" :component=true/>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
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
          $('.manage-show-hide').trigger('click');
          $('#company_id').val('').attr('disabled', true);
          $('#company_land_id').val('').attr('disabled', true);
          $('#customer_category_id').val('').attr('disabled', true);
          $('#customer_id').val('').attr('disabled', true);
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
            $('#customer_category_id').val('').attr('disabled', false);
            $('#customer_id').val('').attr('disabled', false);
            $('.check_company_cb_id').prop('checked', false);
            $('.check_product_cb_id').prop('checked', false);
            $('.check_company_land_cb_id').prop('checked', false);
          }else{
            $('#company_id').val('').attr('disabled', true);
            $('#company_land_id').val('').attr('disabled', true);
            $('#customer_category_id').val('').attr('disabled', true);
            $('#user_id').val('').attr('disabled', true);
            $('#customer_id').val('').attr('disabled', true);
          }

        })

        $(document).ready(function(e) {
            get_land_product_size();
            get_product_by_category_land($('#product_category_id').val(),$('#company_land_id').val());
            get_size_by_product(sid);

            $("#do_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true,
                'scrollable': true,
            });
            $('#datepicker6').datepicker({
                orientation: "bottom left",
            });
        });

        @if (@$search['company_id'] != null)
            get_land_user('{{ $search['company_id'] }}');

            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}', '{{ $search['company_id'] }}')
            @endif

        @else
            get_land_user('{{ auth()->user()->company_id }}');
            @if (@$search['customer_category_id'] != null)
            console.log("kwek");
                get_customer_by_category ('{{ $search['customer_category_id'] }}','{{ auth()->user()->company_id }}')
            @endif
        @endif

        $('#company_id').on('change', function() {
            let id = $(this).val();
            get_land_user(id);
            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}', id)
            @endif
        });

        $('#customer_category_id').on('change', function() {
            let category = $(this).val();
            let company_detect = $('#company_id').val();
            let user_type = '{{auth()->user()->user_type_id}}';

            if (user_type != 1){
                let company = '{{ auth()->user()->company_id }}';
            }else{
                if (company_detect != null){
                    let company  = $('#company_id').val();
                }else{
                    let company  = 0;
                }
            }

            get_customer_by_category (category, company);
         });

        function get_customer_by_category (category, company){
            console.log(category, company);

            let customer = '<option value="">Please Select Customer</option>';
            let scust = '{{ @$search['customer_id'] }}' ?? null;
            $('#customer_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_get_customer_by_category', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    category: category,
                    company: company
                },
                success: function(e) {
                    console.log(e);
                    if (e.length > 0) {
                        e.forEach(cust => {
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

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            $('#customer_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $('#company_land_id').html('<option value="">Loading...</option>');
            let sland = '{{ @$search['company_land_id'] }}' ?? null;
            let suser = '{{ @$search['user_id'] }}' ?? null;
            let scust = '{{ @$search['customer_id'] }}' ?? null;
            let scust_cat = '{{ @$search['customer_category_id'] }}' ?? null;
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    console.log(e.customer);
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
                    // console.log(scust_cat);
                    if (!scust_cat){
                        console.log(scust_cat);
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

        // $('#customer_category_id').on('change', function() {
        //     let category = $(this).val();
        //     if(company_search == 1){
        //         let company = $('#company_id').val();
        //     }else{
        //         let company = 0;
        //     }
        //     console.log(company);

        //     let customer = '<option value="">Please Select Customer</option>';
        //     $('#customer_id').html(''<option value="">Loading...</option>');
        //     $.ajax({
        //         url: "{{ route('ajax_get_customer_by_category', ['tenant' => tenant('id')]) }}",
        //         method: "POST",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             category: category,
        //             company: company
        //         },
        //         success: function(e) {
        //             console.log(e);
        //             if (e.length > 0) {
        //                 e.forEach(cust => {
        //                     customer += '<option value="' + cust.customer_id + '">' + cust.customer_company_name+ ' - ' +cust.customer_name +
        //                         '</option>';
        //                 });
        //                 $('#customer_id').html(customer);
        //             } else {
        //                 $('#customer_id').html('<option value="">No Customer</option>');
        //             }
        //         }
        //     });
        // });


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
