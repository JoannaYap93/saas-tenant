@extends('layouts.master')

@section('title')
    {{ $title }} Company Bank
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Company Bank</h4>
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
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Company Bank Details</h4>
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company_name">Bank Name</label>
                                            {!! Form::select('setting_bank_id', $setting_bank_sel, @$company->setting_bank_id, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company_bank_account_name">Bank Account Name</label>
                                            <input type="text" name="company_bank_acc_name" class="form-control" value="{{ @$company->company_bank_acc_name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company_bank_account_no">Bank Account No.</label>
                                            <input type="text" name="company_bank_acc_no" class="form-control" value="{{ @$company->company_bank_acc_no }}">
                                        </div>
                                    </div>
                                </div>

                                @if(@$company_land)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="company_land">Company Land</label>
                                                <div class="custom-control custom-checkbox col-sm-8">
                                                    <input type="checkbox" id="check_all" class="form-check-input"/>
                                                    <label for="check_all">Select All</label>
                                                </div>
                                                <div class="row col-sm-8">
                                                    <ul>
                                                        @foreach ($company_land as $company_land_id => $company_land_name)
                                                            <li class="custom-control custom-checkbox mb-2 mr-2" style="list-style:none;display:inline-block;width:200px">
                                                                <input type="checkbox" id="company_land_{{ $company_land_id }}"
                                                                    name="company_land_id[]" value="{{ $company_land_id }}"
                                                                    class= "check_company_land_id form-check-input"
                                                                />
                                                                <label
                                                                    for="company_land_{{ $company_land_id }}">{{ $company_land_name }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                        <a href="{{ route('company_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
<script>
    $('#check_all').on('click', function(event) {
        if(this.checked) {
            $('.btn-selected').prop("disabled", false);
            $('.check_company_land_id').each(function() {
                this.checked = true;
            });
        } else {
            $('.btn-selected').prop("disabled", true);
            $('.check_company_land_id').each(function() {
                this.checked = false;
            });
        }
    });
</script>
@endsection
