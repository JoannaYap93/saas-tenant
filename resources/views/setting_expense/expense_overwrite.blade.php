@extends('layouts.master')

@section('title') {{ $title }} Expense @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Expense | {{ $company->company_name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Expense</a>
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
                            <h4 class="card-title mb-4">Expense Details</h4>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="setting_expense_name">Expense Name(EN)</label>
                                        <input type="text" name="setting_expense_name" class="form-control"
                                               value="{{ json_decode(@$expense->setting_expense_name)->en }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="setting_expense_name">Expense Name(CN)</label>
                                        <input type="text" name="setting_expense_name" class="form-control"
                                               value="{{ json_decode(@$expense->setting_expense_name)->cn }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Expense Category</label>
                                        {!! Form::select('setting_expense_category_id', $expense_category_sel, (@$expense->setting_expense_category_id), ['disabled' => true, 'class' => 'form-control', 'id' => 'setting_expense_category_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-3">
                                  <div class="form-group">
                                      <label for="setting_expense_type_id">Expense Type</label>
                                      @if(@$expense->expense_overwrite && $overwrite->company_id == auth()->user()->company_id)
                                          {!! Form::select('setting_expense_type_id', $expense_type_sel, (@$overwrite->setting_expense_type_id), ['class' => 'form-control', 'id' => 'setting_expense_type_id']) !!}
                                      @else
                                          {!! Form::select('setting_expense_type_id', $expense_type_sel, (@$expense->setting_expense_type_id), ['class' => 'form-control', 'id' => 'setting_expense_type_id']) !!}
                                      @endif
                                  </div>
                              </div>
                              <div class="col-sm-3">
                              <div class="form-group">
                                    <label for="worker_role">Worker Role</label>
                                    {!! Form::select('worker_role_id', $worker_role_sel, (@$expense->worker_role_id), ['disabled' => true, 'class' => 'form-control', 'id' => 'worker_role_id', 'name' => 'worker_role_id']) !!}
                                </div>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Expense Description</label>
                                        <textarea name="setting_expense_description"
                                                  class="form-control" disabled>{{ @$expense->setting_expense_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="d-none d-sm-inline-block" for="setting_expense_overwrite_value">Expense Value</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="validationTooltipUsernamePrepend">RM</span>
                                            </div>
                                            @if(@$expense->expense_overwrite && $overwrite->company_id == auth()->user()->company_id)
                                                <input id="setting_expense_overwrite_value_input" name="setting_expense_overwrite_value" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$overwrite->setting_expense_overwrite_value }}" style="text-align: right;">
                                            @else
                                                <input id="setting_expense_overwrite_value_input" name="setting_expense_overwrite_value" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$expense->setting_expense_value }}" style="text-align: right;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group d-flex">
                                        <label for="customSwitches">Compulsory: </label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches"
                                                   name="is_compulsory" value="1" disabled
                                                   @if (@$expense->is_compulsory == 1) checked @endif>
                                            <label for="customSwitches" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="is_subcon_allow">Allow Subcon</label>
                                        <div>
                                            @if($expense->expense_overwrite && $overwrite->company_id == auth()->user()->company_id)
                                                <input id="is_subcon_allow" type="checkbox" name="is_subcon_allow" switch="none" value="1" {{ @$overwrite->is_subcon_allow ? 'checked' : '' }}/>
                                                <label for="is_subcon_allow" data-on-label="On" data-off-label="Off"></label>
                                            @else
                                                <input id="is_subcon_allow" type="checkbox" name="is_subcon_allow" switch="none" value="1" {{ @$expense->is_subcon_allow ? 'checked' : '' }}/>
                                                <label for="is_subcon_allow" data-on-label="On" data-off-label="Off"></label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group" id="setting_expense_subcon">
                                        <label class="d-none d-sm-inline-block" for="setting_expense_overwrite_subcon">Expense Subcon</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="validationTooltipUsernamePrepend">RM</span>
                                            </div>
                                            @if($expense->expense_overwrite && $overwrite->company_id == auth()->user()->company_id)
                                                <input id="expense_subcon_input" name="setting_expense_overwrite_subcon" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$overwrite->setting_expense_overwrite_subcon }}" style="text-align: right;">
                                            @else
                                                <input id="expense_subcon_input" name="setting_expense_overwrite_subcon" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$expense->setting_expense_overwrite_subcon }}" style="text-align: right;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="is_extra_commission">Extra Commission</label>
                                        <div>
                                            <input id="is_extra_commission" type="checkbox" name="is_extra_commission" switch="none" value="1" {{ @$overwrite->is_extra_commission && $overwrite->company_id == auth()->user()->company_id ? 'checked' : '' }}/>
                                            <label for="is_extra_commission" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group" id="setting_expense_overwrite_commission">
                                        <label class="d-none d-sm-inline-block" for="setting_expense_overwrite_commission">Extra Commission</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="validationTooltipUsernamePrepend">RM</span>
                                            </div>
                                            <input id="setting_expense_overwrite_commission_input" name="setting_expense_overwrite_commission" class="form-control input-mask input-currency text-left" im-insert="true" value="@if(@$overwrite->is_extra_commission == 1 && $overwrite->company_id == auth()->user()->company_id){{ @$overwrite->setting_expense_overwrite_commission }} @else {{null}} @endif" style="text-align: right;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit"
                                            class="btn btn-primary waves-effect waves-light mr-1">Update</button>
                                    <a href="{{ route('expense_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <!-- Plugins js -->
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- form mask -->
    <script src="{{ global_asset('assets/libs/inputmask/inputmask.min.js')}}"></script>

    <!-- form mask init -->
    <script src="{{ global_asset('assets/js/pages/form-mask.init.js')}}"></script>

    <script src="{{ global_asset('assets/libs/dropzone/dropzone.min.js')}}"></script>

    <script>
        $(document).ready(function (){
            @if($expense->expense_overwrite && $overwrite->company_id == auth()->user()->company_id)
                var is_subcon_allow_overwrite = '{{ @$overwrite->is_subcon_allow }}';
                if(is_subcon_allow_overwrite != 1){
                    $("#setting_expense_subcon").hide();
                }

                $("#is_subcon_allow").change(function(){
                    if($(this).prop("checked")){
                        $("#setting_expense_subcon").show();
                    }else{
                        $("#setting_expense_subcon").hide();
                        $("#expense_subcon_input").val('');
                    }
                });
            @else
                var is_subcon_allow = '{{ @$expense->is_subcon_allow }}';
                if(is_subcon_allow != 1){
                    $("#setting_expense_subcon").hide();
                }

                $("#is_subcon_allow").change(function(){
                    if($(this).prop("checked")){
                        $("#setting_expense_subcon").show();
                    }else{
                        $("#setting_expense_subcon").hide();
                        $("#expense_subcon_input").val('');
                    }
                });
            @endif

            var is_extra_commission = '{{ @$overwrite->is_extra_commission && $overwrite->company_id == auth()->user()->company_id }}';
            if(is_extra_commission != 1){
                $("#setting_expense_overwrite_commission").hide();
            }

            $("#is_extra_commission").change(function(){
                if($(this).prop("checked")){
                    $("#setting_expense_overwrite_commission").show();
                }else{
                    $("#setting_expense_overwrite_commission").hide();
                    $("#setting_expense_overwrite_commission_input").val('');
                }
            });

            $('.input-currency').inputmask({
                'alias': 'decimal',
                // 'groupSeparator': ',',
                'autoGroup': true,
                'digits': 2,
                'digitsOptional': false,
                'placeholder': '0.00',
                // 'text-align': 'left',
                'repeat': 11
            });

            $('.input-mask-integer').inputmask({
                'alias': 'numeric',
                'mask': '9',
                'repeat': 9,
                'greedy' : false
            });

        });
    </script>

@endsection
