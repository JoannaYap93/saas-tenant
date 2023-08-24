@extends('layouts.master')

@section('title') {{ $title }} Supplier @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
    {{-- @if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
    @enderror --}}
    {{-- @dd($errors); --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Supplier</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Supplier</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror

    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Supplier Details</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_name">Name<span style="color: red">*</span></label>
                                    <input type="text" name="supplier_name" class="form-control" value="{{ @$supplier->supplier_name }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_email">Email</label>
                                    <input type="email" name="supplier_email" class="form-control" value="{{ @$supplier->supplier_email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_mobile_no">Mobile Number</label>
                                    <input type="text" name="supplier_mobile_no" class="form-control" value="{{ @$supplier->supplier_mobile_no }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_phone_no">Phone Number</label>
                                    <input type="text" name="supplier_phone_no" class="form-control" value="{{ @$supplier->supplier_phone_no }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_currency">Currency</label>
                                    {!! Form::select('supplier_currency', $setting_currency_sel, @$supplier->supplier_currency, ['class' => 'form-control', 'id' => 'supplier_currency']) !!}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_credit_term">Credit Term (Days)</label>
                                    <input type="number" name="supplier_credit_term" class="form-control" value="{{ @$supplier->supplier_credit_term }}" step="1">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="supplier_credit_limit">Credit Limit</label>
                                    <input type="number" name="supplier_credit_limit" class="form-control" value="{{ @$supplier->supplier_credit_limit }}" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_pic">PIC Name</label>
                                    <input type="text" name="supplier_pic" class="form-control" value="{{ @$supplier->supplier_pic }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="supplier_status">Status</label>
                                <div class="custom-switch custom-control">
                                    <input type="checkbox" class="custom-control-input" id="supplier_status" name="supplier_status" @if (@$supplier->supplier_status == "Active") checked @endif>
                                    <label for="supplier_status" class="custom-control-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Supplier Address Details</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_address">Address Line 1</label>
                                    <input type="text" name="supplier_address" class="form-control" value="{{ @$supplier->supplier_address }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="supplier_address2">Address Line 2</label>
                                    <input type="text" name="supplier_address2" class="form-control" value="{{ @$supplier->supplier_address2 }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="supplier_postcode">Postcode</label>
                                    <input type="number" name="supplier_postcode" class="form-control" value="{{ @$supplier->supplier_postcode }}" step="1">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="supplier_city">City</label>
                                    <input type="text" name="supplier_city" class="form-control" value="{{ @$supplier->supplier_city }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="supplier_state">State</label>
                                    <input type="text" name="supplier_state" class="form-control" value="{{ @$supplier->supplier_state }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="supplier_country">Country</label>
                                    <input type="text" name="supplier_country" class="form-control" value="{{ @$supplier->supplier_country ?? 'Malaysia' }}">
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
                                    <button type="button" class="btn btn-outline-primary btn-sm ml-2" id="add_bank_acc">ADD</button>
                                </h5>
                                <div class="table-responsive border">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Bank</th>
                                                <th>Bank Account Name</th>
                                                <th>Bank Account No.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $bank_count = 1; @endphp
                                            @if (@$supplier->supplier_bank && count($supplier->supplier_bank) > 0)
                                                @foreach ($supplier->supplier_bank as $bank)
                                                    @if($bank->is_deleted == 0)
                                                        <tr id="bank_details_{{ $bank_count }}" class="bank_details">
                                                            <td style="text-align:center;padding-top:20px;">{{ $bank_count }}
                                                                <input type="hidden" name="supplier_bank_id[]" value="{{ $bank->supplier_bank_id }}">
                                                            </td>
                                                            <td>
                                                                {!! Form::select('setting_bank_id[]', $setting_bank_sel, @$bank->setting_bank->setting_bank_id, ['class' => 'form-control']) !!}
                                                            </td>
                                                            <td>
                                                                <input type="text" name="supplier_bank_acc_name[]" id="" class="form-control" value="{{ @$bank->supplier_bank_acc_name }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="supplier_bank_acc_no[]" id="" class="form-control" onkeypress="return isNumberKey(event);" value="{{ @$bank->supplier_bank_acc_no }}" step="1">
                                                            </td>
                                                            <td class="text-center">
                                                                <i class="mdi mdi-trash-can font-size-20 text-danger"id="bank_details_delete"></i>
                                                            </td>
                                                        </tr>
                                                        @php $bank_count++; @endphp
                                                    @endif
                                                @endforeach
                                            @elseif (@$supplier->supplier_bank_acc_name || @$supplier->supplier_bank_acc_no || @$supplier->setting_bank_id)
                                                @for ($rec = 0; $rec < count($supplier->supplier_bank_acc_no); $rec++)
                                                    <tr id="bank_details_{{ $rec }}" class="bank_details">
                                                        <td style="text-align:center;padding-top:20px;">{{ $rec + 1 }}
                                                            <input type="hidden" name="supplier_bank_id[]" value="{{ $supplier->supplier_bank_id[$rec] }}">
                                                        </td>
                                                        <td>
                                                            {!! Form::select('setting_bank_id[]', $setting_bank_sel, @$supplier->setting_bank_id[$rec], ['class' => 'form-control']) !!}
                                                        </td>
                                                        <td>
                                                            <input type="text" name="supplier_bank_acc_name[]" id="" class="form-control" value="{{ @$supplier->supplier_bank_acc_name[$rec] }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="supplier_bank_acc_no[]" id="" class="form-control" onkeypress="return isNumberKey(event);" value="{{ @$supplier->supplier_bank_acc_no[$rec] }}" step="1">
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-2">Raw Material</h4>
                        <div class="form-group">
                            @foreach($raw_material_category_checkbox_sel as $raw_material_category_id => $raw_material_category_name)
                                <div class="row ml-1 mt-3 mb-2">
                                    <div class="custom-control custom-checkbox col-12">
                                        <input type="checkbox" id="raw_material_category_{{ $raw_material_category_id }}" class="custom-control-input raw_material_category_check_all"/>
                                        <label for="raw_material_category_{{ $raw_material_category_id }}" class="custom-control-label mb-0 font-weight-bolder">
                                            {{ json_decode($raw_material_category_name)->en }}
                                        </label>
                                    </div>
                                </div>
                                <div class="row ml-4 mb-2">
                                    @foreach ($raw_material_checkbox_sel as $raw_material)
                                        @if($raw_material->raw_material_category_id == $raw_material_category_id)
                                            <div class="custom-control custom-checkbox col-3 mb-2">
                                                <input type="checkbox" id="raw_material_{{ $raw_material->raw_material_id }}"
                                                    name="raw_material_id[]" value="{{ $raw_material->raw_material_id }}"
                                                    class= "check_raw_material_id_{{ $raw_material->raw_material_category_id }} custom-control-input"
                                                    @if(isset($supplier->raw_material))
                                                        @if(in_array($raw_material->raw_material_id, collect($supplier->raw_material)->pluck('raw_material_id')->toArray()))
                                                            checked
                                                        @endif
                                                    @elseif (@$supplier->raw_material_id && in_array($raw_material->raw_material_id, $supplier->raw_material_id))
                                                            checked
                                                    @endif
                                                />
                                                <label
                                                    for="raw_material_{{ @$raw_material->raw_material_id }}" class="custom-control-label">{{ @json_decode($raw_material->raw_material_name)->en }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if(auth()->user()->company_id == 0)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Company<span style="color: red">*</span></h4>
                            <div class="form-group">
                                <div class="row ml-1 mb-2">
                                        <div class="custom-control custom-checkbox col-12">
                                            <input type="checkbox" id="company_check_all" class="custom-control-input"/>
                                            <label for="company_check_all" class="custom-control-label mb-0">Select All</label>
                                        </div>
                                </div>
                                <div class="row ml-4">
                                    @foreach ($company_sel as $company_id => $company_name)
                                        <div class="custom-control custom-checkbox col-3 mb-2">
                                            <input type="checkbox" id="company_{{ $company_id }}"
                                                name="company_id[]" value="{{ $company_id }}"
                                                class= "check_company_id custom-control-input"
                                                @if(isset($supplier->supplier_company))
                                                    @if(in_array($company_id, collect($supplier->supplier_company)->pluck('company_id')->toArray()))
                                                        checked
                                                    @endif
                                                @elseif (@$supplier->company_id && in_array($company_id, $supplier->company_id))
                                                        checked
                                                @endif
                                            />
                                            <label for="company_{{ $company_id }}" class="custom-control-label">{{ $company_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('supplier_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        $('#company_check_all').on('click', function(event) {
            if(this.checked) {
                $('.check_company_id').each(function() {
                    this.checked = true;
                });
            }
            else {
                $('.check_company_id').each(function() {
                    this.checked = false;
                });
            }
        });

        $('.raw_material_category_check_all').on('click', function(event) {
            let raw_material_category_id = $(this).attr('id').replace('raw_material_category_','');
            let selected_checkbox_id = '#raw_material_category_' + raw_material_category_id;
            let check_raw_material_id = '.check_raw_material_id_' + raw_material_category_id;

            if($(selected_checkbox_id).prop('checked')) {
                $(check_raw_material_id).each(function() {
                    $(check_raw_material_id).prop('checked', true );
                });
            }
            else {
                $(check_raw_material_id).each(function() {
                    $(check_raw_material_id).prop('checked', false );
                });
            }
        });

        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        var bank_count = $('.bank_details').length;

        $('#add_bank_acc').click(function() {
            $('#no_bank_records').hide();
            bank_count++;

            let setting_bank_option = '';
            let input = '';

            @foreach ($setting_bank_sel as $setting_bank_id => $setting_bank_name)
                setting_bank_option += '<option value="{{ $setting_bank_id }}">{{ $setting_bank_name }}</option>';
            @endforeach

            input += '<tr id="bank_details_' + bank_count + '" class=bank_details>';
            input += '<td style="text-align:center;padding-top:20px;">' + bank_count + '<input type="hidden" name="supplier_bank_id[]"></td>';
            input += '<td><select name="setting_bank_id[]" class="form-control" id="setting_bank_id_' + bank_count + '">' +
                            setting_bank_option + '</select></td>';
            input += '<td><input type="text" name="supplier_bank_acc_name[]" class="form-control"></td>';
            input += '<td><input type="text" onkeypress="return isNumberKey(event);" name="supplier_bank_acc_no[]" class="form-control" step="1"></td>';
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

    </script>
@endsection
