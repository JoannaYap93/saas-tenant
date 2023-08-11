@extends('layouts.master')

@section('title')
    {{ $title }} Company
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ $title }} Company</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company</a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company Details</h4>
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name<span class="text-danger">*</span></label>
                                        @if (auth()->user()->user_type_id == 1 || $title == 'Add')
                                            <input name="company_name" type="text" class="form-control" value="{{ @$post->company_name }}">
                                        @else
                                            <br> {{ @$post->company_name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="company_code">Company Code<span
                                                        class="text-danger">*</span></label>
                                                <span id="show_msg"></span>
                                                @if ($title == 'Edit' || $title == 'Edit Details - ')
                                                    @if (@$post->company_code)
                                                        @if (auth()->user()->user_type_id == 1)
                                                            <input id="randomCode" name="company_code" readonly type="text" class="form-control col-6" maxlength="5" value="{{ @$post->company_code }}">
                                                        @else
                                                            <br>{{ $post->company_code }}
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (auth()->user()->user_type_id == 1 || $title == 'Add')
                                                        <input id="company_code" name="company_code" type="text" class="form-control col-6" maxlength="5" value="{{ @$post->company_code }}">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Phone
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" name="phone" id="" class="form-control" maxlength="12" value="{{ @$post->company_phone ?? @$post->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Email</span>
                                        </label>
                                        <input type="email" name="email" id="" class="form-control" value="{{ @$post->company_email ?? @$post->email }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Company Logo</label><br>
                                @if (@$post && $company_media != false && @$company_media->hasMedia('company_logo'))
                                    <a href="{{ $company_media->getFirstMediaUrl('company_logo') }}" target="_blank">
                                        <img src="{{ $company_media->getFirstMediaUrl('company_logo') }}" width="150">
                                    </a>
                                @endif
                                <input type="file" name="company_logo" class="form-control-file" id="">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="" class="form-control" value="{{ @$post->company_address ?? @$post->address }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="">Registration No. <span class="text-danger">*</span></label>
                                        <input type="text" name="reg_no" id="" class="form-control" value="{{ @$post->company_reg_no ?? @$post->reg_no }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Bank Account Details
                                @if (auth()->user()->user_type_id == 1)
                                    <button type="button" class="btn btn-outline-primary btn-sm ml-2" id="add_bank_acc">ADD</button>
                                @endif
                            </h5>
                            <div class="table-responsive border">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bank</th>
                                            <th>Bank Account Name</th>
                                            <th>Bank Account No.</th>
                                            @if (auth()->user()->user_type_id == 1)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $bank_count = 1; @endphp
                                        @if (@$post->company_bank && count($post->company_bank) > 0)
                                            @foreach ($post->company_bank as $bank)
                                                @if($bank->is_deleted == 0)
                                                    <tr id="bank_details_{{ $bank_count }}" class="bank_details">
                                                        <td>{{ $bank_count }}
                                                            <input type="hidden" name="company_bank_id[]" value="{{ $bank->company_bank_id }}">
                                                        </td>
                                                        <td>
                                                            @if (auth()->user()->user_type_id == 1)
                                                                {!! Form::select('setting_bank_id[]', $setting_bank_sel, @$bank->setting_bank->setting_bank_id, ['class' => 'form-control']) !!}
                                                            @else
                                                                <input type="text" name="setting_bank_id[]" class="form-control" value="{{ @$bank->setting_bank->setting_bank_name }}" readonly>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input type="text" name="company_bank_acc_name[]" id="" class="form-control" value="{{ @$bank->company_bank_acc_name }}" @if (auth()->user()->user_type_id != 1) readonly @endif>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="company_bank_acc_no[]" id="" class="form-control" onkeypress="return isNumberKey(event);" value="{{ @$bank->company_bank_acc_no }}" step="1" @if (auth()->user()->user_type_id != 1) readonly @endif>
                                                        </td>
                                                        @if (auth()->user()->user_type_id == 1)
                                                            <td class="text-center">
                                                                <i class="mdi mdi-trash-can font-size-20 text-danger"id="bank_details_delete"></i>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @php $bank_count++; @endphp
                                                @endif
                                            @endforeach
                                        @elseif (@$post->company_bank_acc_name || @$post->company_bank_acc_no || @$post->setting_bank_id)
                                            @for ($rec = 0; $rec < count($post->company_bank_acc_no); $rec++)
                                                <tr id="bank_details_{{ $rec }}" class="bank_details">
                                                    <td>{{ $rec + 1 }}
                                                        <input type="hidden" name="company_bank_id[]" value="{{ $post->company_bank_id[$rec] }}">
                                                    </td>
                                                    <td>
                                                        @if (auth()->user()->user_type_id == 1)
                                                            {!! Form::select('setting_bank_id[]', $setting_bank_sel, @$post->setting_bank_id[$rec], ['class' => 'form-control']) !!}
                                                        @else
                                                            <input type="text" name="setting_bank_id[]" class="form-control" value="{{ @$post->setting_bank_id[$rec] }}"readonly>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="text" name="company_bank_acc_name[]" id="" class="form-control" value="{{ @$post->company_bank_acc_name[$rec] }}" @if (auth()->user()->user_type_id != 1) readonly @endif>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="company_bank_acc_no[]" id="" class="form-control" onkeypress="return isNumberKey(event);" value="{{ @$post->company_bank_acc_no[$rec] }}" step="1" @if (auth()->user()->user_type_id != 1) readonly @endif>
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="mdi mdi-trash-can font-size-20 text-danger" id="bank_details_delete"></i>
                                                    </td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr id="no_bank_records">
                                                <td colspan="4">No Bank Account Details Record</td>
                                            </tr>
                                        @endif
                                        <tr id="add_new_bank_details"></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $company_land_manage = false;
                if(auth()->user()->can('company_land_manage')){
                    $company_land_manage = true;
                }
            @endphp
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-8 col-sm-12">
                            <h5>Company Land
                                @if ($company_land_manage)
                                    <button type="button" class="btn btn-outline-primary btn-sm ml-2" id="add_land">ADD</button>
                                @endif
                            </h5>
                            <div class="table-responsive border">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Land Name</th>
                                            <th>Category</th>
                                            <th>Total Acre</th>
                                            @if ($company_land_manage)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @if (@$post->company_land && count($post->company_land) > 0)
                                            @foreach ($post->company_land as $land)
                                                <tr id="l{{ $i }}">
                                                    <td>{{ $i }}
                                                        <input type="hidden" name="company_land_id[]" value="{{ $land->company_land_id }}">
                                                    </td>
                                                    <td>
                                                        @if ($company_land_manage)
                                                            {!! Form::select('company_farm[]', $farm, @$land->company_land_category->company_farm_id, ['class' => 'form-control', 'id' => 'land_{{ $i }}', 'required']) !!}
                                                        @else
                                                            <input type="text" name="company_farm[]" class="form-control" value="{{ @$land->company_land_category->company_farm->company_farm_name }}" readonly>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($company_land_manage)
                                                            <select name="company_land_category_id[]" class="form-control" id="catg_{{ $i }}" required>
                                                            </select>
                                                        @else
                                                            {!! Form::select('company_land_category_id[]', $category, $land->company_land_category_id, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".01" name="company_land_total_acre[]"class="form-control" value="{{ @$land->company_land_total_acre }}" id="total_acre_{{ $i }}" @if(!$company_land_manage) readonly @endif>
                                                    </td>
                                                    @if ($company_land_manage)
                                                        <td class="text-center">
                                                            <i class="mdi mdi-trash-can font-size-20 text-danger" id="delete"></i>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @php
                                                    $land_product = [];
                                                    foreach ($land->product_company_land as $pclk => $pcl) {
                                                        $land_product[$pcl->product_id] = $pcl->product_id;
                                                    }
                                                @endphp
                                                <tr id="p{{ $i }}">
                                                    <td colspan="4">
                                                        <h5>Product: </h5>
                                                        <ul class="p-0 m-0">
                                                            @foreach ($product as $pk1 => $p1)
                                                                <li class="custom-control custom-checkbox mb-2 mr-2" style="list-style:none;display:inline-block;">
                                                                    <input id="pd{{$i}}{{ $pk1 }}" type="checkbox" name="product_id_{{ $i }}[]" class="form-check-input" value="{{ $pk1 }}" @if (@$land_product[$pk1] == $pk1) checked @endif >
                                                                    <label for="pd{{$i}}{{ $pk1 }}">{{ $p1 }}</label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @php $i++; @endphp
                                            @endforeach
                                        @elseif (@$post->company_farm)
                                            @for ($r = 0; $r < count($post->company_farm); $r++)
                                                <tr id="l{{ $r }}">
                                                    <td>{{ $i }}
                                                        <input type="hidden" name="company_land_id[]" value="{{ $post->company_land_id[$r] }}">
                                                    </td>
                                                    <td>
                                                        @if ($company_land_manage)
                                                            {!! Form::select('company_farm[]', $farm, @$post->company_farm[$r], ['class' => 'form-control', 'id' => 'land_{{ $r }}', 'required']) !!}
                                                        @else
                                                            <input type="text" name="company_farm[]" class="form-control" value="{{ @$post->company_farm[$r] }}" readonly>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($company_land_manage)
                                                            <select name="company_land_category_id[]" class="form-control" id="catg_{{ $i }}" required>
                                                            </select>
                                                        @else
                                                            {!! Form::select('company_land_category_id[]', $category, $post->company_land_category_id[$r], ['class' => 'form-control', 'disabled', 'readonly']) !!}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number" name="company_land_total_acre[]" class="form-control" value="{{$post->company_land_total_acre[$r] }}" id="total_acre_{{ $i }}" @if(!$company_land_manage) readonly @endif>
                                                    </td>
                                                    @if ($title == 'Add')
                                                        <td class="text-center">
                                                            <i class="mdi mdi-trash-can font-size-20 text-danger" id="delete"></i>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @php
                                                    $land_product = [];
                                                    if(@$post->product_id_.$r){
                                                        foreach ($post->product_id_.$r as $pclk => $pcl) {
                                                            $land_product[$pcl] = $pcl;
                                                        }
                                                    }
                                                @endphp
                                                <tr id="p{{ $r }}">
                                                    <td colspan="4">
                                                        <h5>Product: </h5>
                                                        <ul class="p-0 m-0">
                                                            @foreach ($product as $pk1 => $p1)
                                                                <li class="custom-control custom-checkbox mb-2 mr-2" style="list-style:none;display:inline-block;">
                                                                    <input id="pd{{$r}}{{ $pk1 }}" type="checkbox" name="product_id_{{ $r }}[]" class="form-check-input" value="{{$pk1}}"  @if (@$land_product[$pk1] == $pk1) checked @endif >
                                                                    <label for="pd{{$r}}{{ $pk1 }}">{{ $p1 }}</label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr id="no_records">
                                                <td colspan="4">No Land Records</td>
                                            </tr>
                                        @endif
                                        <tr id="add_here"></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-4 mt-2">
                            <div class="form-group d-flex">
                                <label for="customSwitches">Enable GST: </label>
                                <div class="custom-switch custom-control ml-2">
                                    <input type="checkbox" class="custom-control-input" id="customSwitches" name="enable_gst" value="1"  @if (@$post->company_enable_gst == 1) checked @endif>
                                    <label for="customSwitches" class="custom-control-label"></label>
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="code">Enable Collect Code: </label>
                                <div class="custom-switch custom-control ml-2">
                                    <input type="checkbox" class="custom-control-input" id="code" name="collect_code" value="1" @if (@$post->company_force_collect == 1) checked @endif>
                                    <label for="code" class="custom-control-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if ($title == 'Add')
                        <div class="row mt-3">
                            <div class="col-12">
                                <h4>New Admin Detail </h4>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Email: <span class="text-danger">*</span> <span id="uem"></span></label>
                                            <input type="email" name="user_email" id="user_email" class="form-control" required value="{{ @$post->user_email }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Name: <span class="text-danger">*</span></label>
                                            <input type="text" name="user_fullname" id="" class="form-control" required value="{{ @$post->user_fullname }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Mobile: <span class="text-danger">*</span> <span id="umc"></span></label>
                                            <input type="tel" name="user_mobile" id="user_mobile" class="form-control" required maxlength="12" value="{{ @$post->user_mobile }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Unique Code: <span class="text-danger">*</span> <span id="uucc"></span></label>
                                            <input type="text" name="user_unique_code" id="uuc" required maxlength="4" class="form-control" value="{{ @$post->user_unique_code }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Date of Birth: <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input name="user_dob" class="form-control input-mask"
                                                    id="datepicker" value="{{ @$post->user_dob }}"
                                                    data-provide="datepicker" data-date-format="yyyy-mm-dd"
                                                    data-date-autoclose="true" data-inputmask="'alias': 'datetime'"
                                                    data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                                                    autocomplete="off">
                                                <span class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Gender: <span class="text-danger">*</span></label>
                                            @php
                                                $gender = ['' => 'Please Select Gender', 'male' => 'Male', 'female' => 'Female'];
                                            @endphp
                                            {!! Form::select('user_gender', $gender, @$post->user_gender, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('company_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        function isNumberKey(evt)
        {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        console.log(charCode);
            if (charCode != 46 && charCode != 45 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
        }

        let land_arr = [];
        let count = "{{ @$post->company_land ? count(@$post->company_land) + 1 : 1 }}";
        let bank_count = 1;

        @if (@$post->company_land && count($post->company_land) > 0)
            count = {{ count($post->company_land) }} + 1;
        @endif

        @if (@$post->company_bank && count($post->company_bank) > 0)
            bank_count = 1;
            @foreach($post->company_bank as $bank)
                @if($bank->is_deleted == 0)
                    bank_count++;
                @endif
            @endforeach
        @endif

        $(document).ready(function(e) {
            @if (@$post->company_land)
                let edit_land = 1;
                @foreach ($post->company_land as $ld => $land)
                    get_category(edit_land, '{{ $land->company_land_category->company_farm_id }}',
                    '{{ $land->company_land_category_id }}');
                    edit_land++;
                @endforeach
            @endif
        });

        $('#add_bank_acc').click(function() {
            $('#no_bank_records').hide();

            let setting_bank_option = '';
            let input = '';

            @foreach ($setting_bank_sel as $setting_bank_id => $setting_bank_name)
                setting_bank_option += '<option value="{{ $setting_bank_id }}">{{ $setting_bank_name }}</option>';
            @endforeach

            input += '<tr id="bank_details_' + bank_count + '" class=bank_details>';
            input += '<td>' + bank_count + '<input type="hidden" name="company_bank_id[]"></td>';
            input += '<td><select name="setting_bank_id[]" class="form-control" id="setting_bank_id_' + bank_count + '">' +
                            setting_bank_option + '</select></td>';
            input += '<td><input type="text" name="company_bank_acc_name[]" class="form-control"></td>';
            input += '<td><input type="text" onkeypress="return isNumberKey(event);" name="company_bank_acc_no[]" class="form-control" step="1"></td>';
            input += '<td class="text-center"><i class="mdi mdi-trash-can font-size-20 text-danger" id="bank_details_delete"></i></td>';
            input += '</tr>';

            $(input).insertBefore('#add_new_bank_details');
            bank_count++;
        });

        $(document).on('click', '#bank_details_delete', function() {
            if (confirm('Are you sure you want to delete?')) {
                let id = $(this).parent().parent().attr('id');
                $(this).parent().parent().remove();
                bank_count--;
            }
        });

        $('#add_land').click(function() {
            let input = '';
            let category = '';
            let farm = '';
            let product = '';

            @foreach ($farm as $key => $f)
                farm += '<option value="{{ $key }}">{{ $f }}</option>';
            @endforeach

            @foreach ($product as $pk => $p)
                product += '<li class="custom-control custom-checkbox mb-2 mr-2" style="list-style:none;display:inline-block;">';
                product += '<input id="pd'+count+'{{ $pk }}" type="checkbox" value="{{$pk}}" name="product_id_'+count+'[]" class="form-check-input">';
                product += '<label for="pd'+count+'{{ $pk }}">{{ $p }}</label></li>'
            @endforeach

            input += '<tr id="l' + count + '"><td>' + count + '<input type="hidden" name="company_land_id[]"></td>';
            input += '<td><select name="company_farm[]" class="form-control" id="land_' + count + '" required>' +
                farm + '</select></td>';
            input += '<td><select class="form-control" name="company_land_category_id[]" id="catg_' + count +
                '" required><option value="">Please Select Farm</option></select></td>';
            input += '<td><input type="number" class="form-control" name="company_land_total_acre[]" id="total_acre_' + count +
                    '"></td>';
            input +=
                '<td class="text-center"><i class="mdi mdi-trash-can font-size-20 text-danger" id="delete"></i></td>';
            input += '</tr>';
            input += '<tr id="p' + count + '"><td colspan="4"><h5>Product: </h5><ul class="m-0 p-0">' + product + '</ul></td></tr>'

            $(input).insertBefore('#add_here');
            count++;

            if ($('.table tbody tr').length > 1) {
                $('#no_records').hide();
            } else {
                $('#no_records').show();
            }

            $('select[name="company_farm[]"]').on('change', function() {
                let farm = $(this).val();
                let id = $(this).parent().parent().attr('id').substring(1);
                get_category(id, farm);
            });
        });
        $('select[name="company_farm[]"]').on('change', function() {
            let farm = $(this).val();
            let id = $(this).parent().parent().attr('id').substring(1);
            get_category(id, farm);
        });

        function get_category(id, farm, land = null) {
            $('#catg_' + id).html('<option value="">Loading...</option>');
            let cat = '<option value="">Please Select Category</option>';
            $.ajax({
                url: "{{ route('ajax_get_farm', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: farm
                },
                success: function(e) {
                    e.forEach(c => {
                        if (land != null && land == c.company_land_category_id) {
                            cat += '<option value="' + c.company_land_category_id + '" selected>' + c.company_land_category_name + '</option>';
                        } else {
                            cat += '<option value="' + c.company_land_category_id + '">' + c.company_land_category_name + '</option>';
                        }
                    });
                    $('#catg_' + id).html(cat);
                }
            });
        }

        $(document).on('click', '#delete', function() {
            if (confirm('Are you sure you want to delete?')) {
                let id = $(this).parent().parent().attr('id');
                $(this).parent().parent().remove();
                $('#p'+id.substring(1)).remove();
                count--;
            }

            if (($('.table tbody tr').length - 1) > 1) {
                $('#no_records').hide();
            } else {
                $('#no_records').show();
            }
        });

        $('#company_code').on('keyup', function() {
            let code = $(this).val();

            $.ajax({
                url: "{{ route('check_company_code', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code
                },
                success: function(e) {
                    if (e == 'true') {
                        $('#show_msg').html('Code available.').css({
                            'color': 'green'
                        }).show();
                        $('button[type="submit"]').prop('disabled', false);
                    } else if (e == 'false') {
                        $('#show_msg').html('Enter another code.').css({
                            'color': 'red'
                        }).show();
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#show_msg').hide();
                        $('button[type="submit"]').prop('disabled', true);
                    }
                }
            });
        });

        $('#uuc').on('keyup', function() {
            var value = $(this).val();

            $.ajax({
                url: "{{ route('ajax_check_user_unique_code', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    user_unique_code: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(e) {
                    if (e.status == true) {
                        $('#uucc').html('Invalid Code').css({
                            'color': 'red'
                        }).show();
                        $('button[type="submit"]').prop('disabled', true);
                    } else if (e.status == false) {
                        $('#uucc').html('Accepted').css({
                            'color': 'green'
                        }).show();
                        $('button[type="submit"]').prop('disabled', false);
                    } else {
                        $('#uucc').hide();
                        $('button[type="submit"]').prop('disabled', true);
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

        $("input[name='user_mobile']").on('change', function() {
            let user_mobile = $(this).val();

            $.ajax({
                url: "{{ route('ajax_check_user_mobile', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    user_mobile: user_mobile,
                    _token: '{{ csrf_token() }}'
                },
                success: function(e) {
                    if (e.data.length == 0) {
                        $('#umc').hide();
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        if (e.status == true) {
                            $('#umc').html('Exist User').css({
                                'color': 'red'
                            }).show();
                            $('button[type="submit"]').prop('disabled', true);
                        } else if (e.status == false) {
                            $('#umc').html('Accepted').css({
                                'color': 'green'
                            }).show();
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    }
                }
            });
        });

        $('#user_email').on('keyup', function() {
            let email = $(this).val();

            $.ajax({
                url: "{{ route('ajax_check_email', ['tenant' => tenant('id')]) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_email: email
                },
                method: 'POST',
                success: function(e) {
                    if (e == 'false') {
                        $('#uem').html('Existed Email').css({
                            'color': 'red'
                        }).show();
                        $('button[type="submit"]').prop('disabled', true);
                    } else if (e == 'true') {
                        $('#uem').html('Accepted Email').css({
                            'color': 'green'
                        }).show();
                        $('button[type="submit"]').prop('disabled', false);
                    } else {
                        $('#uem').hide();
                        $('button[type="submit"]').prop('disabled', true);
                    }
                }
            });
        });

        $('.pic_claim').select2({
            ajax: {
                minimumResultsForSearch: -1,
                url: "{{ route('ajax_search_user_by_name', ['tenant' => tenant('id')]) }}",
                dataType: 'json',
                data: function(p) {
                    let query = {
                        api_token: '{{ csrf_token() }}',
                        term: p.term,
                        company_id: $('#company_id').val(),

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
            placeholder: 'Select Claim PIC '
        });

        $('.pic_claim').on('change', function() {
            let company = $('#company_id').val();
            let value = $(this).val();
            let count = $(this).attr("id").substr(10);
            $.ajax({
                url: "{{ route('ajax_search_user_by_name', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_name: value,
                    company_id: company,
                },
                success: function(e) {
                    if (e.data != null) {
                        $('#user_pic_id_'+count).val(value);
                    } else {
                        $('#user_pic_id_'+count).html('');

                    }
                }
            })
        });
    </script>
@endsection
