@extends('layouts.master')

@section('title')
    {{ $title }} Claim Item
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Claim Item</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Claim</a>
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
            <form action="{{ $submit }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="add_manually" class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Date<code>*</code></label>
                                    <div class="input-daterange input-group" id="datepicker6"
                                        data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker6">
                                        <input type="text" class="form-control"
                                            name="claim_item_date" placeholder=""
                                            value="{{@$post->claim_item_date}}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Name<code>*</code></label>
                                    <input type="text" class="form-control" name="claim_item_name" value="{{@$post->claim_item_name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Value<code>*</code></label>
                                    <input type="number" class="form-control" name="claim_item_value" value="{{@$post->claim_item_value}}" step='0.01' value='0.00' placeholder='0.00' >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Amount<code>*</code></label>
                                    <input type="number" class="form-control" name="claim_item_amount" value="{{@$post->claim_item_amount}}" step='0.01' value='0.00' placeholder='0.00' >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Claim Amount<code>*</code></label>
                                    <input type="number" class="form-control" name="claim_item_amount_claim" value="{{@$post->claim_item_amount_claim}}" step='0.01' value='0.00' placeholder='0.00' >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="">Claim Type<code>*</code></label>
                                    {!! Form::select('claim_item_type', $claim_type_sel, @$post->claim_item_type, ['class' => 'form-control', 'id' => 'claim_item_type']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row" id="manually_raw_material_company_usage_category_id">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="">Raw Material Category<code>*</code></label>
                                    {!! Form::select('manually_raw_material_company_usage_category_id', $raw_material_category_sel, @$post->claim_item_type_value, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row" id="manually_company_expense_item_category_id">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="">Setting Expense Category<code>*</code></label>
                                    {!! Form::select('manually_company_expense_item_category_id', $setting_expense_category_sel, @$post->claim_item_type_value, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Upload</label><br>
                                    <input name="claim_item_media[]" id="upload_image" type="file" accept=".jpeg,.png,.jpg" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 my-2">
                                <button type="submit" class="btn btn-primary mr-2" name="submit" value="manual">Add</button>
                                <a href="{{route('claim_item_listing',$claim->claim_id)}}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        $(document).ready(function(e) {
            $("#manually_raw_material_company_usage_category_id").hide();
            $("#manually_company_expense_item_category_id").hide();
            $('#claim_item_type').change(function(e) {
                if ($('#claim_item_type').val() == "manually_raw_material_company_usage_category_id") {
                    $("#manually_raw_material_company_usage_category_id").show();
                    $("#manually_company_expense_item_category_id").hide();
                }
                else{
                    $("#manually_raw_material_company_usage_category_id").hide();
                    $("#manually_company_expense_item_category_id").show();
                }
            });

            // $('.currency_only').bind('input paste', function(){
            //         this.value = this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            // });
        });
    </script>
@endsection
