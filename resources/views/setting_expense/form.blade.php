@extends('layouts.master')

@section('title') {{ $title }} Expense @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Expense</h4>
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
                                    <label for="setting_expense_name">Expense Name(EN)<span class="text-danger">*</span></label>
                                    <input type="text" name="setting_expense_name_en" class="form-control"
                                        value="{{ @$post->setting_expense_name_en }}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="setting_expense_name">Expense Name(CN)<span class="text-danger">*</span></label>
                                    <input type="text" name="setting_expense_name_cn" class="form-control"
                                        value="{{ @$post->setting_expense_name_cn }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Expense Category<span class="text-danger">*</span></label>
                                    {!! Form::select('setting_expense_category_id', $expense_category_sel, (@$post->setting_expense_category_id), ['class' => 'form-control', 'id' => 'setting_expense_category_id']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="form-group">
                                  <label for="setting_expense_type_id">Expense Type<span class="text-danger">*</span></label>
                                  {!! Form::select('setting_expense_type_id', $expense_type_sel, (@$post->setting_expense_type_id), ['class' => 'form-control', 'id' => 'setting_expense_type_id']) !!}
                              </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label for="worker_role_id" class="worker_role_id">Worker Role</label>
                                {!! Form::select('worker_role_id', $worker_role_sel, (@$post->worker_role_id), ['class' => 'form-control', 'id' => 'worker_role_id', 'name' => 'worker_role_id']) !!}
                            </div>
                        </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Expense Description</label>
                                <textarea name="setting_expense_description"
                                    class="form-control">{{ @$post->setting_expense_description }}</textarea>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label class="d-none d-sm-inline-block" for="setting_expense_value">Expense Value<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="validationTooltipUsernamePrepend">RM</span>
                                    </div>
                                    <input id="expense_value_input" name="setting_expense_value" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$post->setting_expense_value }}" style="text-align: right;">
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
                                        name="is_compulsory" value="1"
                                        @if (@$post->is_compulsory == 1) checked @endif>
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
                                        <input id="is_subcon_allow" type="checkbox" name="is_subcon_allow" switch="none" value="1" {{ @$post->is_subcon_allow ? 'checked' : '' }}/>
                                        <label for="is_subcon_allow" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="is_excluded_payroll">Exclude From Payroll</label>
                                    <div>
                                        <input id="is_excluded_payroll" type="checkbox" name="is_excluded_payroll" switch="none" value="1" {{ @$post->is_excluded_payroll ? 'checked' : '' }}/>
                                        <label for="is_excluded_payroll" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group" id="setting_expense_subcon">
                                    <label class="d-none d-sm-inline-block" for="setting_expense_subcon">Expense Subcon<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="validationTooltipUsernamePrepend">RM</span>
                                        </div>
                                        <input id="expense_subcon_input" name="setting_expense_subcon" class="form-control input-mask input-currency text-left" im-insert="true" value="{{ @$post->setting_expense_subcon }}" style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
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
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js')}}"></script>
    {{-- <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script> --}}

    <!-- form mask -->
    <script src="{{ URL::asset('assets/libs/inputmask/inputmask.min.js')}}"></script>

    <!-- form mask init -->
    <script src="{{ URL::asset('assets/js/pages/form-mask.init.js')}}"></script>

    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js')}}"></script>

    <script>
        $(document).ready(function (){
            var is_subcon_allow = '{{ @$post->is_subcon_allow }}';
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

        // Remark: check unique payment method
        $(document).on('change','#setting_expense_category_id',function(){
            let setting_expense_category_id = $(this).val();
            if(setting_expense_category_id == 2){
                $('#worker_role_id').prop('selectedIndex', 0);
                $('.worker_role_id')[0].innerHTML = 'Worker Role <span class="text-danger">*</span>';
            }else{
                $('#worker_role_id').val('5');
                $('.worker_role_id')[0].innerHTML = 'Worker Role';

            }
        }).change();
    </script>

@endsection
