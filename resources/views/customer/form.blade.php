@extends('layouts.master')

@section('title') {{ $title }} Customer @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">


@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }} Customer</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Customer</a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if($errors->any())
@foreach($errors->all() as $error)
<div class="alert alert-danger" role="alert">
    {{ $error }}
</div>
@endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            @if (auth()->user()->user_type_id == 1)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_name">Company Name<span class="text-danger">*</span></label>
                                {!! Form::select('company_id', $company_sel, (@$post->company_id), ['class' => 'form-control', 'id' => 'company_id']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Customer Details</h4>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Customer Name: <span class="text-danger">*</span></label>
                                <select required name="customer_name" id="customer_name" class="form-control customer_name_sel">
                                    @if ($title == 'Edit')
                                        @if (@$post->customer_id != null)
                                            <option
                                                value="{{@$post->customer_name ?? @$post->customer_details->customer_name}}"
                                                selected="selected">
                                                {{ @$post->customer_name ?? @$post->customer_details->customer_name}} -
                                                {{ @$post->customer_code ?? @$post->customer_details->customer_code }}
                                            </option>
                                        @endif
                                    @endif
                                    <option></option>
                                </select>
                                <input type="hidden" name="customer_id"
                                    value="{{ @$post->customer_id }}" id="customer_id">            
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_name">Customer Company Name<span class="text-danger">*</span></label>
                                <input name="customer_company_name" id="customer_company_name" type="text" maxlength="100" class="form-control" maxlength="100" value="{{ @$post->customer_company_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_mobile_no">Mobile No<span class="text-danger">*</span></label>
                                <input name="customer_mobile_no" id="customer_mobile_no" maxlength="45" class="form-control" maxlength="12" value="{{ @$post->customer_mobile_no }}"> <!-- class="form-control input-mask text-left" data-inputmask="'mask': '+609999999999','clearIncomplete':'false','removeMaskOnSubmit':'true','autoUnmask':'true','placeholder':''" im-insert="true" style="text-align: right;" -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_email">Email</label>
                                <input name="customer_email" type="email" id="customer_email" class="form-control" maxlength="100" value="{{ @$post->customer_email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Customer Category<span class="text-danger">*</span></label>
                                {!! Form::select('customer_category_id', $customer_category_sel, (@$post->customer_category_id?$post->customer_category_id:@$company->company_name), ['class' => 'form-control', 'id' => 'customer_category_id']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Warehouse</label>
                                {!! Form::select('warehouse_id', $warehouse_sel, (@$post->warehouse_id), ['class' => 'form-control', 'id' => 'warehouse_id']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Account PIC Name<span class="text-danger">*</span></label>
                                <input name="customer_acc_name" id="customer_acc_name" type="text" class="form-control" maxlength="100" value="{{ @$post->customer_acc_name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Account PIC Mobile Number<span class="text-danger">*</span></label>
                                <input name="customer_acc_mobile_no" id="customer_acc_mobile_no" class="form-control" maxlength="12" value="{{ @$post->customer_acc_mobile_no }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_code">Customer Code<span class="text-danger">*</span></label>
                                 <input id="customer_code" name="customer_code" readonly type="text" class="form-control" maxlength="100" value="{{@$post->customer_code }}">   
                                <input id="customer_code_input" name="customer_code" type="text" class="form-control" maxlength="100" value="{{@$post->customer_code }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Address Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_address">Address 1</label>
                                <input name="customer_address" id="customer_address" required type="text" maxlength="100" class="form-control" value="{{ @$post->customer_address }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="customer_address2">Address 2</label>
                                <input name="customer_address2" id="customer_address2" type="text" maxlength="100" class="form-control" value="{{ @$post->customer_address2 }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="customer_postcode">Postcode</label>
                                <input name="customer_postcode" class="form-control" id="customer_postcode" value="{{ @$post->customer_postcode }}" maxlength="45" required>
                                <!-- class="form-control input-mask text-left" data-inputmask="'mask': '999999999999','clearIncomplete':'false','removeMaskOnSubmit':'true','autoUnmask':'true','placeholder':''" im-insert="true" style="text-align: right;"  -->
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="customer_state">State</label>
                                {{-- <input name="user_state" type="text" class="form-control" value="{{ @$post->user_state }}"> --}}
                                <input name="customer_state" id="customer_state" maxlength="45" class="form-control"  value="{{ @$post->customer_state }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="customer_city">City</label>
                                <input name="customer_city" id="customer_city" maxlength="45" class="form-control"  value="{{ @$post->customer_city }}" required>
                            </div>
                        </div>
                         <div class="col-sm-3">
                            <div class="form-group">
                                <label for="customer_country">Country</label>
                                <input name="customer_country" id="customer_country" type="text" id="country" class="form-control" value="{{ @$post->customer_country ?? 'Malaysia' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body" id="pic_details">
                    <h4 class="card-title d-flex align-items-center justify-content-between">Person In Charge Details
                        <button type="button" class="btn btn-outline-success btn-sm add_cust_pic" onclick="add_pic()" id="add_customer_pic">+ Add PIC</button>
                    </h4>
                    @if(isset($post->customer_pic) && $title == 'Edit')
                        @foreach($post->customer_pic as $customer_pic_details)
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="pic_ic">PIC IC:</label>
                                        <input type="text" name="customer_pic_ic[]" id="customer_pic_ic" maxlength="12"
                                            class="form-control customer_pic_ic"
                                            value="{{ @$customer_pic_details->customer_pic_ic }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="pic_name">PIC Name:</label>
                                        <!-- <h5 id="customer_pic_name[]" class="m-0">
                                            {{ @$customer_pic_details->customer_pic_name }}
                                        </h5> -->
                                        <input type="text" name="customer_pic_name[]" id="customer_pic_name_input"
                                            class="form-control customer_pic_name_input" value="{{ @$customer_pic_details->customer_pic_name }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="pic_mobile_no">PIC Mobile:</label>
                                        <!-- <h5 id="customer_pic_mobile_no[]" class="m-0">
                                            {{ @$customer_pic_details->customer_pic_mobile_no }}
                                        </h5> -->
                                        <input type="text" name="customer_pic_mobile_no[]" id="customer_pic_mobile_no_input"
                                            class="form-control customer_pic_mobile_no_input" value="{{ @$customer_pic_details->customer_pic_mobile_no }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="remove">Action</label><br>
                                        <i class="mdi mdi-trash-can-outline font-size-20 remove waves-effect waves-light text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                    {{-- @dd($post->customer_pic_ic) --}}
                        @if (@$post->customer_pic_ic && count($post->customer_pic_ic) > 0)
                            @for ($pic = 0; $pic < count($post->customer_pic_ic); $pic++)
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="pic_ic">PIC IC:</label>
                                            <input type="text" name="customer_pic_ic[]" id="customer_pic_ic" maxlength="12"
                                                class="form-control customer_pic_ic"
                                                value="{{ @$post->customer_pic_ic[$pic] }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="pic_name">PIC Name:</label>
                                            <!-- <h5 id="customer_pic_name[]" class="m-0">
                                                {{ @$post->customer_pic_name_input[$pic] }}
                                            </h5> -->
                                            <input type="text" name="customer_pic_name[]" id="customer_pic_name_input"
                                                class="form-control customer_pic_name_input" value="{{ @$post->customer_pic_name[$pic] }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="pic_mobile_no">PIC Mobile:</label>
                                            <!-- <h5 id="customer_pic_mobile_no[]" class="m-0">
                                                {{ @$customer_pic_details->customer_pic_mobile_no }}
                                            </h5> -->
                                            <input type="text" name="customer_pic_mobile_no[]" id="customer_pic_mobile_no_input"
                                                class="form-control customer_pic_mobile_no_input" value="{{ @$post->customer_pic_mobile_no[$pic]}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="remove">Action</label><br>
                                            <i class="mdi mdi-trash-can-outline font-size-20 remove waves-effect waves-light text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    @endif
                    <div id="add_pic_div">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <span id="error_user"></span>
                            <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('customer_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<!-- End Modal -->
@endsection

@section('script')
<script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<!-- Plugins js -->
<script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
{{-- <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script> --}}

<!-- form mask -->
<script src="{{ global_asset('assets/libs/inputmask/inputmask.min.js')}}"></script>

<!-- form mask init -->
<script src="{{ global_asset('assets/js/pages/form-mask.init.js')}}"></script>

<script>
    let count = 1;
    @if ($title == 'Add')
        $('#customer_code').hide();
        $('#customer_code_input').show();
    @endif

    @if ($title == 'Edit' && $post->customer_id != null)
        $('#customer_code').show();
        $('#customer_code_input').hide();
    @endif

    $(document).ready(function() {

 
        
        // $('.customer_pic_mobile_no_input').hide();

        // let randomCode = (Math.random() + 1).toString(36).substring(7);
        // document.getElementById('randomCode').value = randomCode;
    //
    //     $("input[name='user_mobile']").on('change', function() {
    //         let user_mobile = $(this).val();
    //
    //         $.ajax({
    //             url: "{{ route('ajax_check_user_mobile', ['tenant' => tenant('id')]) }}",
    //             method: 'post',
    //             data: {
    //                 user_mobile: user_mobile,
    //                 _token: '{{csrf_token()}}'
    //             },
    //             success: function (e) {
    //                 if (e.status) {
    //                     console.log(e.data);
    //                     $('#user_id_exist').val(e.data.user_id);
    //                     $('#user_fullname_exist').html(e.data.user_fullname);
    //                     $('#user_email_exist').html(e.data.user_email);
    //                     $('#user_type_exist').html(e.data.user_type);
    //                     $('#user_cdate_exist').html(e.data.user_cdate);
    //                     $('#existUserModal').modal('show');
    //                 }
    //             }
    //         });
    //     });

        // $('#user_slug').blur(function(){
        //     var user_slug = $('#user_slug').val();
        //     var user_id = {{$customer_id}};
        //
        //     if(user_slug){
        //         var error_slug = '';
        //         $.ajax({
        //             type: 'POST',
        //             url: "{{route('ajax_check_user_slug', ['tenant' => tenant('id')])}}",
        //             data: {
        //                 user_slug: $(this).val(),
        //                 user_id: user_id,
        //                 _token: '{{csrf_token()}}'
        //             },
        //             success: function(e) {
        //                 if (e.status == true){
        //                     if(e.data == 'unique')
        //                     {
        //                     $('#error_user').html('');
        //                     $('#error_user_slug').html('<label class="text-success">User Slug Available</label>');
        //                     $('#user_slug').removeClass('has-error');
        //                     $('#submit').attr('disabled', false);
        //                     }
        //                     else
        //                     {
        //                     $('#error_user').html('<label class="text-danger">*User slug already exist</label><br>');
        //                     $('#error_user_slug').html('<label class="text-danger">User slug already exist</label>');
        //                     $('#user_slug').addClass('has-error');
        //                     $('#submit').attr('disabled', 'disabled');
        //                     }
        //                 }
        //                 else{
        //                         $('#error_user').html('<label class="text-danger">*Invalid User Slug</label><br>');
        //                         $('#error_user_slug').html('<label class="text-danger">Invalid User Slug</label>');
        //                         $('#user_slug').addClass('has-error');
        //                         $('#submit').attr('disabled', 'disabled');
        //                     }
        //             }
        //         });
        //     }else{
        //         $('#error_user').html('<label class="text-danger">*Invalid User Slug</label><br>');
        //         $('#error_user_slug').html('<label class="text-danger">Invalid User Slug</label>');
        //         $('#user_slug').addClass('has-error');
        //         $('#submit').attr('disabled', 'disabled');
        //     }
        // });

        // Update Date base on NRIC
        // $('#user_nric').on('change', function() {
        //     var user_nric = this.value;
        //     var dob =  user_nric.substring(0, 6);
        //     //arrange date from ic number
        //     var y = dob.substr(0, 2);
        //     var m = dob.substr(2, 2);
        //     var d = dob.substr(4,4);

        //     var dateraw = y+'-'+m+'-'+d;

        //     var year = parseInt(y) > 20 ? '19' + y : '20' + y;
        //     var month = parseInt(m) > 12 ? '0' + m.substring(0, 1) : ( m == '00' ? '01' : m );
        //     var day = d;
        //     if(month == 2 && parseInt(d) > 31){
        //         var lastday = function(y,m){
        //             return  new Date(y, m, 0).getDate();
        //         }
        //         day = lastday(parseInt(year), 2);
        //         console.log(day);
        //     } else if(parseInt(d) > 31) {
        //         day = '0' + d.substring(0, 1);
        //     }

        //     var fulldate = year+'-'+month+'-'+day;
        //     $('#datepicker').val(fulldate);
        // });
    });

    function add_pic() {
        count++;
        var pic_details_input = '<div class="row">' +
                                    '<div class="col-3">' +
                                        '<div class="form-group">' +
                                            '<label for="pic_ic">PIC IC:</label>' +
                                            '<input type="text" name="customer_pic_ic[]" id="customer_pic_ic_' + count + '" maxlength="12" class="form-control customer_pic_ic">' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="col-3">' +
                                        '<div class="form-group">' +
                                            '<label for="pic_name">PIC Name:</label>' +
                                            '<h5 id="customer_pic_name_' + count + '" class="m-0"></h5>' +
                                            '<input type="text" name="customer_pic_name[]" id="customer_pic_name_input_' + count + '" class="form-control customer_pic_name_input">' +
                                            '</div>' +
                                    '</div>' +
                                    '<div class="col-3">' +
                                        '<div class="form-group">' +
                                            '<label for="pic_mobile_no">PIC Mobile:</label>' +
                                            '<h5 id="customer_pic_mobile_no_' + count + '" class="m-0"></h5>' +
                                            '<input type="text" name="customer_pic_mobile_no[]" id="customer_pic_mobile_no_input_' + count + '" class="form-control customer_pic_mobile_no_input">' +
                                            '</div>' +
                                    '</div>' +
                                    '<div class="col-3">' +
                                        '<div class="form-group">' +
                                            '<label for="remove">Action</label><br>' +
                                            '<i class="mdi mdi-trash-can-outline font-size-20 remove waves-effect waves-light text-danger"></i>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
        $('#add_pic_div').append(pic_details_input);
        $('#customer_pic_name_input_' + count).hide();
        $('#customer_pic_mobile_no_input_' + count).hide();

        $('.customer_pic_ic' ).on('keyup', function() {
            let input_id = $(this).attr('id').substring(16,17);
            let customer_pic_ic = $(this).val();
            $('#customer_pic_name_' + input_id).html('Loading...');
            $('#customer_pic_mobile_no_' + input_id).html('Loading...');

            $.ajax({
                url: "{{ route('ajax_check_pic', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_ic: customer_pic_ic
                },
                success: function(e) {
                    if (customer_pic_ic.length > 0) {
                        if (e.data != null && e.status != false) {
                            $('#customer_pic_name_' + input_id).html(e.data.customer_pic_name).show();
                            $('#customer_pic_name_input_' + input_id).val(e.data.customer_pic_name).hide();
                            $('#customer_pic_mobile_no_' + input_id).html(e.data.customer_pic_mobile_no).show();
                            $('#customer_pic_mobile_no_input_' + input_id).val(e.data.customer_pic_mobile_no).hide();
                        } else {
                            $('#customer_pic_name_' + input_id).html('').hide();
                            $('#customer_pic_name_input_' + input_id).val('').show();
                            $('#customer_pic_mobile_no_' + input_id).html('').hide();
                            $('#customer_pic_mobile_no_input_' + input_id).val('').show();
                        }
                    } else {
                        $('#customer_pic_name_' + input_id).html('').hide();
                        $('#customer_pic_mobile_no_' + input_id).html('').hide();
                    }
                }
            });
        });
    };

    $('#pic_details').on('click', '.remove', function () {
        $(this).parent().parent().parent().remove();
    });

    $('#customer_name').select2({
            ajax: {
                minimumResultsForSearch: -1,
                url: "{{ route('ajax_search_customer_by_customer_name', ['tenant' => tenant('id')]) }}",
                dataType: 'json',
                data: function(p) {
                    let query = {
                        api_token: '{{ csrf_token() }}',
                        term: p.term,

                    }
                    return query;
                },
                processResult: function(data, p) {
                    return {
                        results: data.results,
                    }
                }
            },
            allowClear: true,
            tags: true,
            placeholder: 'Select Customer'
        });

        $('#customer_category_id').on('change', function() {
            let customer_category_id = $(this).val();
            console.log(customer_category_id);

            if(customer_category_id == 9){
                $('#warehouse_id').val('').attr('readonly', true).attr('disabled', true);
            }else{
                $('#warehouse_id').attr('readonly', false).attr('disabled', false);
            }
        });

        $('#customer_name').on('change', function() {
            $("#customer_code").empty();
            var value = $(this).val();
            $.ajax({
                url: "{{ route('ajax_search_customer_by_customer_name', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_name: value,
                },
                success: function(e) {
                    if (e.data[0] != null) {
                        $('#customer_id').val(e.data[0].id);
                        $('#customer_code').val(e.data[0].customer_code).show();
                        $('#customer_code').val(e.data[0].customer_code);
                        $('#customer_company_name').val(e.data[0].company);
                        $('#customer_email').val(e.data[0].email);
                        $('#customer_mobile_no').val(e.data[0].value);
                        $('#customer_category_id').val(e.data[0].category);
                        if(e.data[0].category == 9){
                            $('#warehouse_id').val('').attr('readonly', true).attr('disabled', true);
                        }else{
                            $('#warehouse_id').val(e.data[0].warehouse).attr('readonly', false).attr('disabled', false);
                        }
                        $('#customer_acc_name').val(e.data[0].customer_acc_name);
                        $('#customer_acc_mobile_no').val(e.data[0].customer_acc_mobile_no);
                        $('#customer_address').val(e.data[0].address);
                        $('#customer_address2').val(e.data[0].address2);
                        $('#customer_city').val(e.data[0].city);
                        $('#customer_state').val(e.data[0].state);
                        $('#customer_postcode').val(e.data[0].postcode);
                        $('#country').val(e.data[0].country);
                        $('#customer_code_input').val(e.data[0].customer_code).hide();
                        customer_details = e.data[0];
                    } else {
                        $('#customer_code_input').show();
                        $('#customer_code_input').val('');
                        $('#customer_company_name').val('');
                        $('#customer_email').val('');
                        $('#customer_mobile_no').val('');
                        $('#customer_category_id').val('');
                        $('#warehouse_id').val('');
                        $('#customer_acc_name').val('');
                        $('#customer_acc_mobile_no').val('');
                        $('#customer_address').val('');
                        $('#customer_address2').val('');
                        $('#customer_city').val('');
                        $('#customer_state').val('');
                        $('#customer_postcode').val('');
                        $('#country').val('');
                        $('#customer_code').html('').hide();
                        customer_details = [];
                    }
                }
            })
        });

        @if ($title == 'Edit' && $edit_code == true)
            $('#customer_code').hide();
            $('#customer_code_input').show();
        @endif
</script>
@endsection
