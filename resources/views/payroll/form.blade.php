@extends('layouts.master')

@section('title') {{ $title }} Monthly Worker Expense @endsection

@section('css')
    <style>
        hr{
            width: 100%;
            margin-left: 0px;
        }
    </style>
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
                <h4 class="mb-0 font-size-18">{{ $title }} Monthly Worker Expense</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Monthly Worker Expense</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="{{ $submit }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ @$payroll->company->company_name }} - {{ date("F Y", mktime(0, 0, 0, @$payroll->payroll_month, 1, @$payroll->payroll_year)) }} </h4>
                    </div>
                </div>
                @foreach ($worker_role_list as $worker_role)
                    @if(in_array($worker_role->worker_role_id, collect($company_expense_worker_list)->pluck('worker_role_id')->toArray()))
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 id="worker_role_{{ $worker_role->worker_role_id }}" class="card-title mb-3 worker_role">{{ $worker_role->worker_role_name }} List</h6>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 10%;">Name</th>
                                                <th style="width: 10%;">Salary</th>
                                                <th style="width: 35%;">Reward</th>
                                                <th style="width: 35%;">Items</th>
                                                <th style="width: 5%;">Paid Out</th>
                                                <th style="width: 5%;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($company_expense_worker_list->isNotEmpty())
                                                @foreach ($company_expense_worker_list as $company_expense_worker)
                                                    @php
                                                        $payroll_user_reward = array();
                                                        $worker_reward = 0;
                                                        $add_list = [];
                                                        $deduct_list = [];
                                                        if(isset($company_expense_worker->worker->setting_reward)){
                                                            foreach(json_decode($company_expense_worker->worker->setting_reward->setting_reward_json) as $setting_reward){
                                                                if($company_expense_worker->worker->is_attendance_reward){
                                                                    if($setting_reward->full_attendance == 0 && $setting_reward->day <= $company_expense_worker->workdays){
                                                                        $worker_reward += $setting_reward->amount;
                                                                        $payroll_user_reward[$company_expense_worker->worker_id][$setting_reward->tier] = $setting_reward->amount;
                                                                    }
                                                                }else{
                                                                    // $worker_reward += $setting_reward->amount;
                                                                    // $payroll_user_reward[$company_expense_worker->worker_id][$setting_reward->tier] = $setting_reward->amount;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if(@$company_expense_worker->worker->worker_role_id == $worker_role->worker_role_id)
                                                        <tr>
                                                            <td>
                                                                <span id="payroll_user_name_{{ $company_expense_worker->worker_id }}" class="payroll_user_name">{{ @$company_expense_worker->worker->worker_name }}</span>
                                                                <input type="number" name="worker_id[]" id="payroll_user_{{ $company_expense_worker->worker_id }}" value="{{ @$company_expense_worker->worker_id }}" hidden/>
                                                            </td>
                                                            <td>
                                                              @php
                                                                $salary = 0;
                                                                $excluded_salary = [];
                                                                if($company_expense_worker->json){
                                                                  $removed_item_spacing = str_replace('},{', ',', $company_expense_worker->json);
                                                                  preg_match_all('/task/i', $removed_item_spacing, $matches, PREG_SET_ORDER);
                                                                  foreach ($matches as $i => $m) {
                                                                    $removed_item_spacing = implode($i, explode($m[0], $removed_item_spacing, 2));
                                                                  }
                                                                  foreach(json_decode($removed_item_spacing, 1) as $key => $each_task){
                                                                    if(is_array($each_task)){
                                                                      foreach($each_task as $key_2 => $each_task_2){
                                                                        foreach($setting_expense as $key => $each_setting_expense){
                                                                          if($each_task_2['expense_id'] == $each_setting_expense->setting_expense_id && $each_setting_expense->is_excluded_payroll == 0){
                                                                            $salary += $each_task_2['expense_total'];
                                                                          }elseif($each_task_2['expense_id'] == $each_setting_expense->setting_expense_id && $each_setting_expense->is_excluded_payroll == 1){
                                                                            if(isset($excluded_salary[$each_setting_expense->setting_expense_id]['expense_total'])){
                                                                              $excluded_salary[$each_setting_expense->setting_expense_id]['expense_total'] += $each_task_2['expense_total'];
                                                                            }else{
                                                                              $excluded_salary[$each_setting_expense->setting_expense_id]['expense_total'] = $each_task_2['expense_total'];
                                                                            }
                                                                          }
                                                                        }
                                                                      }
                                                                    }
                                                                  }
                                                                }
                                                              @endphp
                                                                <span id="payroll_user_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}" class="payroll_user_amount_{{ $worker_role->worker_role_id }} payroll_user_amount">RM {{ number_format($salary, 2) }}</span>
                                                                <input type="number" name="payroll_user_amount[]"  id="payroll_user_amount_{{ $company_expense_worker->worker_id }}" value="{{ $salary }}" hidden/>
                                                            </td>
                                                            <td>
                                                                <div class="form-group row">
                                                                    <label for="payroll_user_reward_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}" class="col-sm-5 col-form-label">Attendance</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="number" id="payroll_user_reward_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}"
                                                                                class="form-control payroll_user_reward_{{ $worker_role->worker_role_id }} payroll_user_reward"
                                                                                name="payroll_user_reward[]" min="0" step="0.01" value="{{ @$worker_reward }}" readonly/>
                                                                    </div>
                                                                </div>
                                                                {{-- @if($title == "Add")
                                                                    @if(is_array($each_task))
                                                                        @foreach($each_task as $key_2 => $each_task_2)
                                                                            @foreach($add_payroll_items_sel as $payroll_item)
                                                                                @if($payroll_item->payroll_item_type == "Add")
                                                                                    @if($payroll_item->setting_expense)
                                                                                    @if(@$excluded_salary[$payroll_item->setting_expense_id]['expense_total'])
                                                                                        <div class="form-group row">
                                                                                            <label for="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_item->payroll_item_name }}

                                                                                            </label>
                                                                                            <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                    value="{{ @$payroll_item->payroll_item_type }}"/>
                                                                                            <div class="col-sm-5">
                                                                                                <input type="number" id="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                        class="form-control payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }} payroll_user_item_amount_employer"
                                                                                                        name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_item->payroll_item_id }}][employer]"
                                                                                                        min="0" step="0.01" value="{{ $excluded_salary[$payroll_item->setting_expense_id]['expense_total'] }}" required/>
                                                                                            </div>
                                                                                        </div>
                                                                                     @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    @endif
                                                                @endif --}}
                                                                {{-- <span id="payroll_user_reward_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}" class="payroll_user_reward_{{ $worker_role->worker_role_id }} payroll_user_reward">RM {{ number_format($worker_reward, 2) }}</span> --}}
                                                                @if(isset($payroll_user_reward[$company_expense_worker->worker_id]))
                                                                    <input type="number" id="setting_reward_id_{{ $company_expense_worker->worker_id }}" name="setting_reward_id[{{ $company_expense_worker->worker_id }}]" value="{{ $company_expense_worker->worker->setting_reward_id }}" hidden/>
                                                                    @foreach($payroll_user_reward[$company_expense_worker->worker_id] as $reward_tier => $reward_amount)
                                                                        <input type="number" id="setting_reward_tier_{{ $company_expense_worker->worker_id }}" name="setting_reward_tier[{{ $company_expense_worker->worker_id }}][]" value="{{ $reward_tier }}" hidden/>
                                                                        <input type="number" id="payroll_user_reward_amount_{{ $company_expense_worker->worker_id }}" name="payroll_user_reward_amount[{{ $company_expense_worker->worker_id }}][{{ $reward_tier }}]" value="{{ $reward_amount }}" hidden/>
                                                                    @endforeach
                                                                @endif
                                                                {{-- <input type="number" id="payroll_user_reward_{{ $company_expense_worker->worker_id }}" name="payroll_user_reward[]" value="{{ $worker_reward }}" hidden/> --}}
                                                                @if($title == "Edit" && count($payroll->payroll_user)>0)
                                                                    @foreach($payroll->payroll_user as $payroll_user)
                                                                        @if($payroll_user->worker_id == $company_expense_worker->worker_id)
                                                                            <div>
                                                                                @foreach ($payroll_user->payroll_user_item as $payroll_user_item)
                                                                                    @if($payroll_user_item->payroll_item->payroll_item_type == "Add")
                                                                                        <hr/>
                                                                                        {{-- <div class="form-group row">
                                                                                            <label for="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_user_item->payroll_item->payroll_item_name }}
                                                                                                @if($payroll_user_item->payroll_item->is_compulsory == 1)
                                                                                                    <span class="text-danger">*</span>
                                                                                                @endif
                                                                                            </label>
                                                                                            <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                    value="{{ @$payroll_user_item->payroll_item->payroll_item_type }}"/>
                                                                                            <div class="col-sm-5">
                                                                                                <input type="number" id="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                        class="form-control payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_{{ $worker_role->worker_role_id }} payroll_user_item_amount"
                                                                                                        name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_user_item->payroll_item_id }}][employee]"
                                                                                                        min="0" step="0.01" value="{{ @$payroll_user_item->payroll_user_item_amount }}" required/>

                                                                                            </div>
                                                                                            @if($payroll_user_item->payroll_item->is_compulsory != 1)
                                                                                                <div class="col-sm-2 align-self-center">
                                                                                                    <i class="bx bx-x text-danger remove" style="font-size:25px;"></i>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div> --}}
                                                                                        <div class="form-group row">
                                                                                            @php
                                                                                                $add_list[$payroll_user_item->payroll_item_id] = 1;
                                                                                            @endphp
                                                                                            <label for="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_user_item->payroll_item->payroll_item_name }}
                                                                                                @if($payroll_user_item->payroll_item->is_compulsory == 1)
                                                                                                    <span class="text-danger"> *</span>
                                                                                                @endif
                                                                                            </label>
                                                                                            <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                    value="{{ @$payroll_user_item->payroll_item->payroll_item_type }}"/>
                                                                                            <div class="col-sm-5">
                                                                                                <input type="number" id="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                        class="form-control payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }} payroll_user_item_amount_employer"
                                                                                                        name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_user_item->payroll_item_id }}][employer]"
                                                                                                        min="0" step="0.01" value="{{ @$payroll_user_item->payroll_user_item_amount }}" required/>
                                                                                            </div>
                                                                                            @if($payroll_user_item->payroll_item->is_compulsory != 1)
                                                                                                <div class="col-sm-2 align-self-center">
                                                                                                    <i class="bx bx-x text-danger remove" style="font-size:25px;"></i>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @elseif(($title == "Add" && !is_null(@$add_payroll_items_sel)) || ($title == "Edit" && count($payroll->payroll_user)==0))
                                                                    @foreach($add_payroll_items_sel as $payroll_item)
                                                                        @if(in_array($worker_role->worker_role_id, collect($payroll_item->payroll_item_worker_role)->pluck('worker_role_id')->toArray()))
                                                                            @if($payroll_item->payroll_item_type == "Add")
                                                                                @if ($payroll_item->is_compulsory == 1 || @$excluded_salary[$payroll_item->setting_expense_id]['expense_total'])
                                                                                    {{-- <div class="form-group row">
                                                                                        <label for="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_item->payroll_item_name }}<span class="text-danger">*</span></label>
                                                                                        <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                        class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                        value="{{ @$payroll_item->payroll_item_type }}"/>
                                                                                        <div class="col-sm-5">
                                                                                            <input type="number" id="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_{{ $worker_role->worker_role_id }} payroll_user_item_amount"
                                                                                                    name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_item->payroll_item_id }}][employee]"
                                                                                                    min="0" step="0.01" value="{{ @$payroll_item->payroll_user_item_amount }}" required/>
                                                                                        </div>
                                                                                    </div> --}}
                                                                                    <div class="form-group row">
                                                                                        @php
                                                                                            $add_list[$payroll_item->payroll_item_id] = 1;
                                                                                        @endphp
                                                                                        <label for="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_item->payroll_item_name }}
                                                                                            <span class="text-danger"> *</span>
                                                                                        </label>
                                                                                        <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                value="{{ @$payroll_item->payroll_item_type }}"/>
                                                                                        <div class="col-sm-5">
                                                                                            <input type="number" id="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }} payroll_user_item_amount_employer"
                                                                                                    name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_item->payroll_item_id }}][employer]"
                                                                                                    min="0" step="0.01" @if(@$payroll_item->payroll_user_item_amount) value="{{@$payroll_item->payroll_user_item_amount}}"
                                                                                                    @elseif(isset($excluded_salary[$payroll_item->setting_expense_id]['expense_total'])) value="{{$excluded_salary[$payroll_item->setting_expense_id]['expense_total']}}"
                                                                                                    @else value="0"
                                                                                                    @endif required/>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                                <div id="add_payroll_item_type_add_{{ $company_expense_worker->worker_id }}"></div>
                                                                <hr/>
                                                                <div class="row mb-3">
                                                                    <div class="col-7">
                                                                        @php
                                                                            $payroll_item_sel = '<select class="form-control sel_payroll_item" id="sel_payroll_item_type_add_' . $company_expense_worker->worker_id . '"><option value="">Select Payroll Items</option>';
                                                                            foreach($add_payroll_items_sel as $payroll_item){
                                                                                if(in_array($worker_role->worker_role_id, collect($payroll_item->payroll_item_worker_role)->pluck('worker_role_id')->toArray())){
                                                                                    if (!$payroll_item->is_compulsory){
                                                                                        if(isset($add_list[$payroll_item->payroll_item_id]) > 0){

                                                                                        }else{
                                                                                            $payroll_item_sel .= '<option value="'. $payroll_item->payroll_item_id . '">' . $payroll_item->payroll_item_name . '</option>';
                                                                                        }
                                                                                        
                                                                                    }
                                                                                }
                                                                            }
                                                                            $payroll_item_sel .= '</select>';
                                                                        @endphp
                                                                        {!! $payroll_item_sel !!}
                                                                    </div>
                                                                    <button type="button" value="{{ $worker_role->worker_role_id }}" class="btn btn-primary btn_add_payroll_item ml-1" id="btn_add_payroll_item_type_add_{{ $company_expense_worker->worker_id }}" disabled><i class="fas fa-plus mr-2"></i>Add</button>
                                                                </div>
                                                                <span id="add_payroll_item_type_add_err_msg_{{ $company_expense_worker->worker_id }}" class="text-danger font-italic"></span>
                                                            </td>
                                                            <td>
                                                                @if($title == "Edit" && count($payroll->payroll_user)>0)
                                                                    @foreach($payroll->payroll_user as $payroll_user)
                                                                        @if($payroll_user->worker_id == $company_expense_worker->worker_id)
                                                                            @php $prev_payroll_item_id = null; @endphp
                                                                            <div>
                                                                                @foreach ($payroll_user->payroll_user_item as $payroll_user_item)
                                                                                    @if($payroll_user_item->payroll_item->payroll_item_type == "Deduct")
                                                                                        @if($payroll_user_item->payroll_item_id != $prev_payroll_item_id && $prev_payroll_item_id != null)
                                                                                            </div><div>
                                                                                            <hr/>
                                                                                        @endif
                                                                                        @if($payroll_user_item->payroll_item_type == "employee")
                                                                                            <div class="form-group row">
                                                                                                @php
                                                                                                    $deduct_list[$payroll_user_item->payroll_item_id] = 1;
                                                                                                @endphp
                                                                                                <label for="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_user_item->payroll_item->payroll_item_name . ($payroll_user_item->payroll_item->is_employer == 1 ? ' (Employee) ' : ' ')}}
                                                                                                    @if($payroll_user_item->payroll_item->is_compulsory == 1)
                                                                                                        <span class="text-danger">*</span>
                                                                                                    @endif
                                                                                                </label>
                                                                                                {{-- <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                        class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                        value="{{ @$payroll_user_item->payroll_item->payroll_item_type }}"/> --}}
                                                                                                <div class="col-sm-5">
                                                                                                    <input type="number" id="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                            class="form-control payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_{{ $worker_role->worker_role_id }} payroll_user_item_amount"
                                                                                                            name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_user_item->payroll_item_id }}][employee]"
                                                                                                            min="0" step="0.01" value="{{ @$payroll_user_item->payroll_user_item_amount }}" required/>

                                                                                                </div>
                                                                                                @if($payroll_user_item->payroll_item->is_compulsory != 1)
                                                                                                    <div class="col-sm-2 align-self-center">
                                                                                                        <i class="bx bx-x text-danger remove" style="font-size:25px;"></i>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        @elseif($payroll_user_item->payroll_item_type == "employer")
                                                                                            <div class="form-group row">
                                                                                                @php
                                                                                                    $deduct_list[$payroll_user_item->payroll_item_id] = 1;
                                                                                                @endphp
                                                                                                <label for="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_user_item->payroll_item->payroll_item_name }} (Employer)
                                                                                                    @if($payroll_user_item->payroll_item->is_compulsory == 1)
                                                                                                        <span class="text-danger"> *</span>
                                                                                                    @endif
                                                                                                </label>
                                                                                                <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                    value="{{ @$payroll_user_item->payroll_item->payroll_item_type }}"/>
                                                                                                <div class="col-sm-5">
                                                                                                    <input type="number" id="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_user_item->payroll_item_id }}"
                                                                                                            class="form-control payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }} payroll_user_item_amount_employer"
                                                                                                            name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_user_item->payroll_item_id }}][employer]"
                                                                                                            min="0" step="0.01" value="{{ @$payroll_user_item->payroll_user_item_amount }}" required/>
                                                                                                </div>
                                                                                                @if($payroll_user_item->payroll_item->is_compulsory != 1)
                                                                                                    <div class="col-sm-2 align-self-center">
                                                                                                        <i class="bx bx-x text-danger remove" style="font-size:25px;"></i>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endif
                                                                                        @php $prev_payroll_item_id = $payroll_user_item->payroll_item_id; @endphp
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @elseif(($title == "Add" && !is_null(@$deduct_payroll_items_sel)) || ($title == "Edit" && count($payroll->payroll_user)==0))
                                                                    @php $prev_payroll_item_id = null; @endphp
                                                                    @foreach($deduct_payroll_items_sel as $payroll_item)
                                                                        @if(in_array($worker_role->worker_role_id, collect($payroll_item->payroll_item_worker_role)->pluck('worker_role_id')->toArray()))
                                                                            @if ($payroll_item->is_compulsory == 1 || @$excluded_salary[$payroll_item->setting_expense_id]['expense_total'])
                                                                                @if($payroll_item->payroll_item_id != $prev_payroll_item_id && $prev_payroll_item_id != null)
                                                                                    <hr/>
                                                                                @endif
                                                                                <div class="form-group row">
                                                                                    @php
                                                                                        $deduct_list[$payroll_item->payroll_item_id] = 1;
                                                                                    @endphp
                                                                                    <label for="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_item->payroll_item_name . (@$payroll_item->is_employer == 1 ? ' (Employee) ' : ' ')}}<span class="text-danger">*</span></label>
                                                                                    {{-- <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                    value="{{ @$payroll_item->payroll_item_type }}"/> --}}
                                                                                    <div class="col-sm-5">
                                                                                        <input type="number" id="payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                class="form-control payroll_user_item_amount_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_{{ $worker_role->worker_role_id }} payroll_user_item_amount"
                                                                                                name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_item->payroll_item_id }}][employee]"
                                                                                                min="0" step="0.01" value="{{ @$payroll_item->payroll_user_item_amount }}" required/>
                                                                                    </div>
                                                                                </div>
                                                                                @if($payroll_item->is_employer == 1)
                                                                                    <div class="form-group row">
                                                                                        @php
                                                                                            $deduct_list[$payroll_item->payroll_item_id] = 1;
                                                                                        @endphp
                                                                                        <label for="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}" class="col-sm-5 col-form-label">{{ $payroll_item->payroll_item_name }} (Employer)<span class="text-danger"> *</span></label>
                                                                                        <input type="hidden" id="payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                class="form-control payroll_item_type_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_item_type_{{ $worker_role->worker_role_id }} payroll_item_type"
                                                                                                value="{{ @$payroll_item->payroll_item_type }}"/>
                                                                                        <div class="col-sm-5">
                                                                                            <input type="number" id="payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}_{{ $payroll_item->payroll_item_id }}"
                                                                                                    class="form-control payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }} payroll_user_item_amount_employer_{{ $worker_role->worker_role_id }} payroll_user_item_amount_employer"
                                                                                                    name="payroll_user_item_amount[{{ $company_expense_worker->worker_id }}][{{ $payroll_item->payroll_item_id }}][employer]"
                                                                                                    min="0" step="0.01"
                                                                                                    @if(@$payroll_item->payroll_user_item_amount) value="{{@$payroll_item->payroll_user_item_amount}}"
                                                                                                    @elseif(isset($excluded_salary[$payroll_item->setting_expense_id]['expense_total'])) value="{{$excluded_salary[$payroll_item->setting_expense_id]['expense_total']}}"
                                                                                                    @else value="0"
                                                                                                    @endif required/>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                @php $prev_payroll_item_id = $payroll_item->payroll_item_id; @endphp
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                                <div id="add_payroll_item_type_deduct_{{ $company_expense_worker->worker_id }}"></div>
                                                                <hr/>
                                                                <div class="row mb-3">
                                                                    <div class="col-7">
                                                                        @php
                                                                            $payroll_item_sel = '<select class="form-control sel_payroll_item" id="sel_payroll_item_type_deduct_' . $company_expense_worker->worker_id . '"><option value="">Select Payroll Items</option>';
                                                                            foreach($deduct_payroll_items_sel as $payroll_item){
                                                                                if(in_array($worker_role->worker_role_id, collect($payroll_item->payroll_item_worker_role)->pluck('worker_role_id')->toArray())){
                                                                                    if (!$payroll_item->is_compulsory){
                                                                                        if(isset($deduct_list[$payroll_item->payroll_item_id]) > 0){

                                                                                        }else{
                                                                                             $payroll_item_sel .= '<option value="'. $payroll_item->payroll_item_id . '">' . $payroll_item->payroll_item_name . '</option>';
                                                                                        }
                                                                                       
                                                                                    }
                                                                                }
                                                                            }
                                                                            $payroll_item_sel .= '</select>';
                                                                        @endphp
                                                                        {!! $payroll_item_sel !!}
                                                                    </div>
                                                                    <button type="button" value="{{ $worker_role->worker_role_id }}" class="btn btn-primary btn_add_payroll_item ml-2" id="btn_add_payroll_item_type_deduct_{{ $company_expense_worker->worker_id }}" disabled><i class="fas fa-plus mr-2"></i>Add</button>
                                                                </div>
                                                                <span id="add_payroll_item_type_deduct_err_msg_{{ $company_expense_worker->worker_id }}" class="text-danger font-italic"></span>
                                                            </td>
                                                            <td id="payroll_total_paid_out_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}">
                                                                <span id="payroll_total_paid_out_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}">RM 0.00</span>
                                                            </td>
                                                            <td id="payroll_total_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}">
                                                                <span id="payroll_total_{{ $worker_role->worker_role_id }}_{{ $company_expense_worker->worker_id }}">RM 0.00</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" class="text-right">Total Paid Out: </td>
                                                    <td id="payroll_total_paid_out_{{ $worker_role->worker_role_id }}" class="text-right">RM 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Grand Total: </td>
                                                    <td id="payroll_grand_total_{{ $worker_role->worker_role_id }}" class="text-right">RM 0.00</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th scope="row" class="text-right">Total (Amount) :</th>
                                    <td id="payroll_total_amount" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-right">Total (Reward) :</th>
                                    <td id="payroll_total_reward" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-right">Total (Employee) :</th>
                                    <td id="payroll_total_user_item_employee" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-right">Total (Employer) :</th>
                                    <td id="payroll_total_user_item_employer" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-right">Total Paid Out :</th>
                                    <td id="payroll_total_paid_out" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-right">Grand Total :</th>
                                    <td id="payroll_grandtotal" style="width:11%" class="text-right">RM {{ number_format(@$payroll->payroll_total_amount,2) ?? '0.00' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('payroll_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        var payroll_total_amount = 0
        var payroll_total_reward = 0;
        var payroll_total_worker_item_employee = 0;
        var payroll_total_worker_item_employer = 0;
        var payroll_grandtotal = 0;
        var payroll_total_paid_out =0;

        $(document).ready(function(e){
            $('input[type="number"]').trigger('keyup');
        });

        $(document).on('keyup', 'input[type="number"]', function() {
            let input_row = $(this).attr('id').split('_').slice(-3);
            let worker_role_id = input_row[0];
            let worker_id = input_row[1];
            let payroll_item_id = input_row[2];

            let user_payroll_total = 0;
            let user_payroll_total_paid_out = 0;
            let user_payroll_item_total = 0;
            let payroll_item_type_add_total = 0;
            let payroll_item_type_deduct_total = 0;
            let payroll_item_employer_type_add_total = 0;
            let payroll_item_employer_type_deduct_total = 0;
            let user_payroll_item_employer_total = 0;

            let salary = parseFloat($('#payroll_user_amount_' + worker_role_id + '_' + worker_id).text().replace("RM ", "").replace(/,/g,""));
            let reward = parseFloat($('#payroll_user_reward_' + worker_role_id + '_' + worker_id).val());
            // let reward = parseFloat($('#payroll_user_reward_' + worker_role_id + '_' + worker_id).text().replace("RM ", "").replace(/,/g,""));
            let payroll_user_item_types = $('.payroll_item_type_' + worker_role_id + '_' + worker_id).map((key,element) => element.value).get();
            let payroll_user_items = $('.payroll_user_item_amount_' + worker_role_id + '_' + worker_id).map((key,element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();
            let payroll_user_items_employer = $('.payroll_user_item_amount_employer_' + worker_role_id + '_' + worker_id).map((key,element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();

            // $.each(payroll_user_items_employer, function(key, payroll_user_item_employer) {
            //     user_payroll_item_employer_total += payroll_user_item_employer;
            // });

            $.each(payroll_user_items, function(key, payroll_user_item) {
                user_payroll_item_total += payroll_user_item;
            });

            $.each(payroll_user_items_employer, function(key, payroll_user_item_employer) {
                if(payroll_user_item_types[key] == "Add"){
                    payroll_item_employer_type_add_total += payroll_user_item_employer;
                }
                // else if(payroll_user_item_types[key] == "Deduct"){
                //     payroll_item_employer_type_deduct_total += payroll_user_item_employer;
                // }
                user_payroll_item_employer_total += payroll_user_item_employer;
            });

            // $.each(payroll_user_items, function(key, payroll_user_item) {
            //     if(payroll_user_item_types[key] == "Add"){
            //         payroll_item_type_add_total += payroll_user_item;
            //     }else if(payroll_user_item_types[key] == "Deduct"){
            //         payroll_item_type_deduct_total += payroll_user_item;
            //     }
            //     // user_payroll_item_total += payroll_user_item;
            // });

            // user_payroll_total = salary + reward + user_payroll_item_employer_total + payroll_item_type_add_total;
            // user_payroll_total_paid_out = salary + reward + payroll_item_type_add_total - payroll_item_type_deduct_total;

            user_payroll_total = salary + reward + user_payroll_item_employer_total;
            user_payroll_total_paid_out = salary + reward + payroll_item_employer_type_add_total - user_payroll_item_total;

            $('#payroll_total_' + worker_role_id + '_' + worker_id).html('RM ' + user_payroll_total.toFixed(2));
            $('#payroll_total_paid_out_' + worker_role_id + '_' + worker_id).html('RM ' + user_payroll_total_paid_out.toFixed(2));

            calc_all_payroll_total();
        });

        $('.sel_payroll_item').on('change', function(){
            let sel_user_row = $(this).attr('id').split('_').slice(-2);
            let payroll_item_type = sel_user_row[0];
            let worker_id = sel_user_row[1];
            let sel_payroll_item_id = $(this).val();
            $('#btn_add_payroll_item_type_' + payroll_item_type + '_' + worker_id).prop('disabled', true)

            if(sel_payroll_item_id != 0 && sel_payroll_item_id > 0){
                $('#btn_add_payroll_item_type_' + payroll_item_type + '_' + worker_id).prop('disabled', false);
            }
        });

        $('.btn_add_payroll_item').on('click', function(){
            let sel_user_row = $(this).attr('id').split('_').slice(-2);
            let worker_role_id = $(this).val();
            let payroll_item_type = sel_user_row[0];
            let worker_id = sel_user_row[1];
            let payroll_user_name = $('#payroll_user_name_' + worker_id).text();
            let sel_payroll_item_id = $('#sel_payroll_item_type_' + payroll_item_type + '_' + worker_id).val();

            if(sel_payroll_item_id != 0 && sel_payroll_item_id > 0){
                $.ajax({
                    url: "{{ route('ajax_get_payroll_item', ['tenant' => tenant('id')]) }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payroll_item_id: sel_payroll_item_id,
                    },
                    success: function(e){
                        if(e.data){
                            if($('#payroll_user_item_amount_' + worker_role_id + '_' + worker_id + '_' + e.data.payroll_item_id).length){
                                $('#add_payroll_item_type_' + payroll_item_type + '_err_msg_' + worker_id).html(e.data.payroll_item_name + ' already added for ' + payroll_user_name);
                                if(!$('#add_payroll_item_type_' + payroll_item_type + '_err_msg_' + worker_id).is(':visible')){
                                    $('#add_payroll_item_type_' + payroll_item_type + '_err_msg_' + worker_id).show();
                                }
                                setTimeout(function() {
                                    $('#add_payroll_item_type_' + payroll_item_type + '_err_msg_' + worker_id).hide();
                                }, 5000);
                            }else{
                                $('#add_payroll_item_type_' + payroll_item_type + '_err_msg_' + worker_id).html('');
                                add_payroll_item(worker_role_id, worker_id, e.data);
                            }
                        }
                    }
                });
            }
        });

        function calc_all_payroll_total(){
            let all_user_group_salary = [];
            let all_user_group_reward = [];
            let all_user_group_items_type = [];
            let all_user_group_items = [];
            let all_user_group_employer_items = [];
            let test_arr = [];

            let all_user_group_salary_total = 0;
            let all_user_group_reward_total = 0;
            let all_user_group_items_total = 0;
            let all_user_group_items_type_add_total = 0;
            let all_user_group_items_type_deduct_total = 0;
            let all_user_group_employer_items_total = 0;
            let all_user_group_employer_items_type_add_total = 0;
            let all_user_group_employer_items_type_deduct_total = 0;

            let all_user_group_salary_class = $.uniqueSort($('.payroll_user_amount').map((key,element) => element.classList[element.classList.length - 2]).get());
            let all_user_group_reward_class = $.uniqueSort($('.payroll_user_reward').map((key,element) => element.classList[element.classList.length - 2]).get());
            let all_user_group_items_type_class = $.uniqueSort($('.payroll_item_type').map((key,element) => element.classList[element.classList.length - 2]).get());
            let all_user_group_items_class = $.uniqueSort($('.payroll_user_item_amount').map((key,element) => element.classList[element.classList.length - 2]).get());
            let all_user_group_items_employer_class = $.uniqueSort($('.payroll_user_item_amount_employer').map((key,element) => element.classList[element.classList.length - 2]).get());

            $.each(all_user_group_salary_class, function(key, each_user_group_salary_class){
                let each_user_group_salary = $('.' + each_user_group_salary_class).map((key, element) => parseFloat(element.textContent.replace("RM ", "").replace(/,/g,""))).get();
                all_user_group_salary.push(each_user_group_salary);
            });

            // $.each(all_user_group_reward_class, function(key, each_user_group_reward_class){
            //     let each_user_group_reward = $('.' + each_user_group_reward_class).map((key, element) => parseFloat(element.textContent.replace("RM ", "").replace(/,/g,""))).get();
            //     all_user_group_reward.push(each_user_group_reward);
            // });

            $.each(all_user_group_reward_class, function(key, each_user_group_reward_class){
                let each_user_group_reward = $('.' + each_user_group_reward_class).map((key, element) => parseFloat(element.value)).get();
                all_user_group_reward.push(each_user_group_reward);
            });

            $.each(all_user_group_items_type_class, function(key, each_user_group_items_type_class){
                let each_user_group_items_type = $('.' + each_user_group_items_type_class).map((key, element) => element.value).get();
                all_user_group_items_type.push(each_user_group_items_type);
            });

            $.each(all_user_group_items_class, function(key, each_user_group_items_class){
                let each_user_group_items = $('.' + each_user_group_items_class).map((key, element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();
                all_user_group_items.push(each_user_group_items);
            });

            $.each(all_user_group_items_employer_class, function(key, each_user_group_items_employer_class){
                let each_user_group_items_employer = $('.' + each_user_group_items_employer_class).map((key, element) => isNaN(parseFloat(element.value)) ? 0.00 : parseFloat(element.value)).get();
                all_user_group_employer_items.push(each_user_group_items_employer);
            });

            $('.worker_role').each(function(key, element){
                let worker_role_id = $(this).attr('id').split('_').pop();
                let user_group_salary_total = 0;
                let user_group_reward_total = 0;
                let user_group_items_type_add_total = 0;
                let user_group_items_type_deduct_total = 0;
                let user_group_items_total = 0;
                let user_group_employer_items_type_add_total = 0;
                let user_group_employer_items_type_deduct_total = 0;
                let user_group_employer_items_total = 0;

                $.each(all_user_group_salary[key], function(key, payroll_user_group_salary){
                    user_group_salary_total += payroll_user_group_salary;
                    all_user_group_salary_total += payroll_user_group_salary;
                });

                $.each(all_user_group_reward[key], function(key, payroll_user_group_reward){
                    user_group_reward_total += payroll_user_group_reward;
                    all_user_group_reward_total += payroll_user_group_reward;
                });

                $.each(all_user_group_items[key], function(key2, payroll_user_group_items){
                    // if(all_user_group_items_type[key][key2] == "Add"){
                    //     user_group_items_type_add_total += payroll_user_group_items;
                    //     all_user_group_items_type_add_total += payroll_user_group_items;
                    // }else if(all_user_group_items_type[key][key2] == "Deduct"){
                    //     user_group_items_type_deduct_total += payroll_user_group_items;
                    //     all_user_group_items_type_deduct_total += payroll_user_group_items
                    // }
                    user_group_items_total += payroll_user_group_items;
                    all_user_group_items_total += payroll_user_group_items;
                });
                $.each(all_user_group_employer_items[key], function(key2, payroll_user_group_employer_items){
                    if(all_user_group_items_type[key][key2] == "Add"){
                        user_group_employer_items_type_add_total += payroll_user_group_employer_items;
                        all_user_group_employer_items_type_add_total += payroll_user_group_employer_items;
                    }
                    // else if(all_user_group_items_type[key][key2] == "Deduct"){
                    //     user_group_employer_items_type_deduct_total += payroll_user_group_employer_items;
                    //     all_user_group_employer_items_type_deduct_total += payroll_user_group_employer_items
                    // }
                    user_group_employer_items_total += payroll_user_group_employer_items;
                    all_user_group_employer_items_total += payroll_user_group_employer_items;
                });

                // $('#payroll_grand_total_' + worker_role_id).html("RM: " + (user_group_salary_total + user_group_reward_total + user_group_employer_items_total + user_group_items_type_add_total).toFixed(2));
                // $('#payroll_total_paid_out_' + worker_role_id).html("RM: " + (user_group_salary_total + user_group_reward_total - user_group_items_type_deduct_total + user_group_items_type_add_total).toFixed(2));

                $('#payroll_grand_total_' + worker_role_id).html("RM: " + (user_group_salary_total + user_group_reward_total + user_group_employer_items_total).toFixed(2));
                $('#payroll_total_paid_out_' + worker_role_id).html("RM: " + (user_group_salary_total + user_group_reward_total - user_group_items_total + user_group_employer_items_type_add_total).toFixed(2));
            });

            // payroll_total_amount = all_user_group_salary_total;
            // payroll_total_reward = all_user_group_reward_total;
            // payroll_total_user_item_employee = all_user_group_items_type_deduct_total;
            // payroll_total_user_item_employer = all_user_group_employer_items_total + all_user_group_items_type_add_total;
            // payroll_grandtotal = all_user_group_salary_total + all_user_group_reward_total + all_user_group_employer_items_total + all_user_group_items_type_add_total;
            // payroll_total_paid_out = all_user_group_salary_total + all_user_group_reward_total - all_user_group_items_type_deduct_total + all_user_group_items_type_add_total;

            payroll_total_amount = all_user_group_salary_total;
            payroll_total_reward = all_user_group_reward_total;
            payroll_total_user_item_employee = all_user_group_items_total;
            payroll_total_user_item_employer = all_user_group_employer_items_total;
            payroll_grandtotal = all_user_group_salary_total + all_user_group_reward_total + all_user_group_employer_items_total;
            payroll_total_paid_out = all_user_group_salary_total + all_user_group_reward_total - all_user_group_items_total + all_user_group_employer_items_type_add_total;

            $('#payroll_total_amount').html("RM: " + payroll_total_amount.toFixed(2));
            $('#payroll_total_reward').html("RM: " + payroll_total_reward.toFixed(2));
            $('#payroll_total_user_item_employee').html("RM: " + payroll_total_user_item_employee.toFixed(2));
            $('#payroll_total_user_item_employer').html("RM: " + payroll_total_user_item_employer.toFixed(2));
            $('#payroll_grandtotal').html("RM: " + payroll_grandtotal.toFixed(2));
            $('#payroll_total_paid_out').html("RM: " + payroll_total_paid_out.toFixed(2));
        }

        function add_payroll_item(worker_role_id, worker_id, data){
            let add_payroll_item_input = "";
            add_payroll_item_input += "<div>";
            add_payroll_item_input += "<hr/>";
            if(data.payroll_item_type == "Deduct"){
                add_payroll_item_input += '<div class="form-group row">';
                add_payroll_item_input += '<label for="payroll_user_item_amount_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id
                                        + '" class="col-sm-5 col-form-label">' + data.payroll_item_name + (data.is_employer == 1 ? ' (Employee) ' : ' ')
                                        + '</label>';
                // add_payroll_item_input += '<input type="hidden" id="payroll_item_type_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id
                //                         + '" class="form-control payroll_item_type_' + worker_role_id + '_' + worker_id
                //                         + ' payroll_item_type_' + worker_role_id + ' payroll_item_type" value="' + data.payroll_item_type + '"/>';
                add_payroll_item_input += '<div class="col-sm-5">';
                add_payroll_item_input += '<input type="number" id="payroll_user_item_amount_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id + '" class="form-control payroll_user_item_amount_' + worker_role_id + '_' + worker_id + ' payroll_user_item_amount_' + worker_role_id + ' payroll_user_item_amount" name="payroll_user_item_amount[' + worker_id + '][' + data.payroll_item_id + '][employee]" min="0" step="0.01" value="0" required/>';
                add_payroll_item_input += '</div>';
                add_payroll_item_input += '<div class="col-sm-2 align-self-center">';
                add_payroll_item_input += '<i class="bx bx-x text-danger remove" style="font-size:25px;"></i>';
                add_payroll_item_input += '</div>';
                add_payroll_item_input += '</div>';
            }
            if(data.payroll_item_type == "Add" || data.is_employer == 1){
                add_payroll_item_input += '<div class="form-group row">';
                add_payroll_item_input += '<label for="payroll_user_item_amount_employer_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id + '" class="col-sm-5 col-form-label">' + data.payroll_item_name + (data.payroll_item_type == "Deduct" ? ' (Employer)' : '') + '</label>';
                add_payroll_item_input += '<input type="hidden" id="payroll_item_type_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id
                                        + '" class="form-control payroll_item_type_' + worker_role_id + '_' + worker_id
                                        + ' payroll_item_type_' + worker_role_id + ' payroll_item_type" value="' + data.payroll_item_type + '"/>';
                add_payroll_item_input += '<div class="col-sm-5">';
                add_payroll_item_input += '<input type="number" id="payroll_user_item_amount_employer_' + worker_role_id + '_' + worker_id + '_' + data.payroll_item_id + '" class="form-control payroll_user_item_amount_employer_' + worker_role_id + '_' + worker_id + ' payroll_user_item_amount_employer_' + worker_role_id + ' payroll_user_item_amount_employer" name="payroll_user_item_amount[' + worker_id + '][' + data.payroll_item_id + '][employer]" min="0" step="0.01" value="0" required/>';
                add_payroll_item_input += '</div>';
                add_payroll_item_input += '<div class="col-sm-2 align-self-center">';
                add_payroll_item_input += '<i class="bx bx-x text-danger remove" style="font-size:25px;"></i>';
                add_payroll_item_input += '</div>';
                add_payroll_item_input += '</div>';
            }
            add_payroll_item_input += "</div>";

            $(add_payroll_item_input).insertBefore('#add_payroll_item_type_' + data.payroll_item_type.toLowerCase() + '_' + worker_id);
        }

        $(document).on('click', '.remove', function() {
            $(this).parent().parent().parent().remove();
            $('input[type="number"]').trigger('keyup');
        });
    </script>
@endsection
