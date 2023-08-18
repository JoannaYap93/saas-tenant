@extends('layouts.master')

@section('title') {{ $title }} Monthly Worker Expense Item @endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror

     <!-- start page title -->
     <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Monthly Worker Expense Item</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Monthly Worker Expense Item</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <form action="{{ $submit }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Monthly Worker Expense Item Details</h4>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">Monthly Worker Expense Item Name</label>
                                    <input type="text" name="payroll_item_name" id="payroll_item_name" value="{{ @$payroll_item->payroll_item_name }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">Monthly Worker Expense Item Type</label>
                                    {!! Form::select('payroll_item_type', $payroll_item_type_sel, @$payroll_item->payroll_item_type, ['class' => 'form-control', 'id' => 'payroll_item_type', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">Setting Expense Link</label>
                                    {!! Form::select('setting_expense_id', $setting_expense_sel, @$payroll_item->setting_expense_id, ['class' => 'form-control', 'id' => 'setting_expense_id']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="is_compulsory">Compulsory: </label>
                                    <div>
                                        <input id="is_compulsory" name="is_compulsory" type="checkbox" switch="none" value="1" @if (@$payroll_item->is_compulsory == 1) checked @endif/>
                                        <label for="is_compulsory" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="payroll_item_status">Status: </label>
                                    <div>
                                        <input id="payroll_item_status" type="checkbox" name="payroll_item_status" switch="none" value="1" {{ (@$payroll_item->payroll_item_status == "Available" ||  @$payroll_item->payroll_item_status == 1 ? 'checked' : '') }}/>
                                        <label for="payroll_item_status" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group" id="is_employer_div" hidden>
                                    <label for="is_employer">Employer: </label>
                                    <div>
                                        <input id="is_employer" name="is_employer" type="checkbox" switch="none" value="1" @if (@$payroll_item->is_employer == 1) checked @endif/>
                                        <label for="is_employer" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payroll Item For: </label>
                            <div class="row">
                                <div class="col-4 mb-3">
                                    @foreach ($worker_roles as $worker_role)
                                        <div class="custom-control custom-checkbox mb-2 ml-2">
                                            <input type="checkbox" id="worker_role_{{ @$worker_role->worker_role_id }}"
                                                name="worker_role_id[]" value="{{ @$worker_role->worker_role_id }}"
                                                class= "custom-control-input"
                                                @if(isset($payroll_item->payroll_item_worker_role))
                                                    @if(in_array($worker_role->worker_role_id, collect($payroll_item->payroll_item_worker_role)->pluck('worker_role_id')->toArray()))
                                                        checked
                                                    @endif
                                                @elseif (@$payroll_item->worker_role_id && in_array($worker_role->worker_role_id, $payroll_item->worker_role_id))
                                                        checked
                                                @endif
                                            />
                                            <label
                                                for="worker_role_{{ @$worker_role->worker_role_id }}" class="custom-control-label">{{ $worker_role->worker_role_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('payroll_item_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('#payroll_item_type').trigger('change');
        });

        $('#payroll_item_type').on('change', function(){
            let sel_payroll_item_type = $('#payroll_item_type').val();
            if(sel_payroll_item_type != ""){
                if(sel_payroll_item_type == "Add"){
                    $('#is_employer_div').attr('hidden', true);
                    $('#is_employer').prop('checked', false)
                }
                else
                    $('#is_employer_div').attr('hidden', false);
            }
        });
    </script>
@endsection
