@if(@$component)
    <p>{{ $payroll->company->company_name }} - {{ date("F Y", mktime(0, 0, 0, @$payroll->payroll_month, 1, @$payroll->payroll_year)) }} Payroll</p>
    <br><br>
@endif
@php
    $payroll_total = array();
    $employer_item_count = 0;
    $item_count = 0;
    foreach($payrollItemsDeduct as $payroll_item){
        if($payroll_item->is_employer){
            $employer_item_count++;
        }else{
            $item_count++;
        }
    }
@endphp
<div class="table-responsive">
<table class="table" id="payroll_details_table">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: initial; background-color:#ffffff; text-align:center; min-width:130px;">
                Name
            </th>
            <th rowspan="2" style="vertical-align: initial; background-color:#f4f4f4; text-align:center;">
                Salary
            </th>
            <th rowspan="2" style="vertical-align: initial; background-color:#ffffff; text-align:center;">
                Day
            </th>
            <th colspan="{{ count($payrollItemsAdd) + 1 }}" style="vertical-align: initial; background-color:#f4f4f4; text-align:center;">
                Reward
            </th>
            <th colspan="{{ $employer_item_count }}" style="background-color:#ffffff; text-align:center;">
                Employee
            </th>
            <th colspan="{{ $employer_item_count }}" style="background-color:#ffffff; text-align:center;">
                Employer
            </th>
            @php $col_count = 0; @endphp
            @foreach($payrollItemsDeduct as $payroll_item)
                @if(!$payroll_item->is_employer)
                    <th rowspan="2" style="vertical-align: initial; background-color:{{ $col_count % 2 == 0 ? '#f4f4f4' : '#ffffff' }}; text-align:center;">
                        {{ $payroll_item->payroll_item_name }}
                    </th>
                    @php $col_count++; @endphp
                @endif
            @endforeach
            <th rowspan="2" style="vertical-align: initial; background-color:{{ ($col_count + 1) % 2 == 0 ? '#ffffff' : '#f4f4f4' }}; text-align:center;">Total Paid Out</th>
            <th rowspan="2" style="vertical-align: initial; background-color:{{ ($col_count + 2) % 2 == 0 ? '#ffffff' : '#f4f4f4' }}; text-align:center;">Grand Total</th>
        </tr>
        <tr>
            <th style="background-color:#f4f4f4; text-align:center;">Attendance</th>
            @foreach($payrollItemsAdd as $payroll_item)
                <th style="background-color:#f4f4f4; text-align:center;">{{ $payroll_item->payroll_item_name }}</th>
            @endforeach
            @for ($i = 0; $i < 2; $i++)
                @foreach($payrollItemsDeduct as $payroll_item)
                    @if($payroll_item->is_employer)
                        <th style="background-color:#ffffff; text-align:center;">{{ $payroll_item->payroll_item_name }}</th>
                    @endif
                @endforeach
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($workerRoleList as $worker_role)
            @if(in_array($worker_role->worker_role_id, collect($payrollDetails)->pluck('worker_role_id')->toArray()))
                <tr>
                    <td colspan="{{ count($payrollItemsAdd) + $item_count + $employer_item_count * 2 + 6 }}" style="background-color: black; color:white; text-align:center;"><strong>{{ $worker_role->worker_role_name }}</strong></td>
                </tr>
                @foreach($payrollDetails as $worker_id => $payroll_user_detail)
                    @if($worker_role->worker_role_id == $payroll_user_detail['worker_role_id'])
                    {{-- @dd($payroll_user_detail['worker_role_id']) --}}
                        @php
                            $expense_log = '<div><h5><b>'. $payroll_user_detail['worker_name'] .'</b></h5></div>';
                            $expense_log .= '<table class=table><thead><tr><th>Date</th><th>Amount</th></tr></thead><tbody>';
                            $counter = [];
                            if (@$worker) {
                                $sum_salary = 0;
                                foreach ($worker as $key => $log) {
                                    if($log->worker_id == $payroll_user_detail['worker_id']){
                                        if (json_decode($log->company_expense_worker_detail)) {
                                            foreach(json_decode($log->company_expense_worker_detail) as $ids => $detail){
                                                if(is_array($detail)){
                                                    foreach ($detail as $key => $value) {
                                                        foreach ($settingExpense as $val => $expense_id) {
                                                            if($expense_id->setting_expense_id == $value->expense_id && $expense_id->is_excluded_payroll == 0){
                                                                if ($value->expense_total != 0) {
                                                                    $expense_log .= '<tr>';
                                                                    $log_user = '';
                                                                    $expense_log .= "<td>{$log->company_expense_day}/{$log->company_expense_month}/{$log->company_expense_year}</td>";
                                                                    $expense_log .= "<td>RM ".number_format($value->expense_total, 2)."</td>";
                                                                    $expense_log .= '</tr>';
                                                                    $sum_salary += $value->expense_total;
                                                                    if(isset($counter[$log->company_expense_day])){
                                                                    }else{
                                                                        $counter[$log->company_expense_day] = 1;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $expense_log .= '<tr style="font-weight: bold;"><td>Total:</td>';
                                $expense_log .= "<td>RM ". number_format($sum_salary, 2) ."</td></tr>";
                            } else {
                                $expense_log .= '<tr><td colspan=3>No Records!</td></tr>';
                            }
                            $expense_log .= '</tbody></table>';
                            $expense_log = str_replace("'", '`', $expense_log);
                        @endphp
                        <tr>
                            <th scope="row" style="background-color:#ffffff;">{{ $payroll_user_detail['worker_name'] }}</th>
                            <td style="text-align:center; background-color:#f4f4f4;"><a href="" class='log' data-toggle='modal' data-target='#log' data-log="{{json_encode($expense_log)}}">{{ number_format($payroll_user_detail['payroll_user_amount'], 2) }}</a></td>
                            <td style="text-align:center; background-color:#ffffff;">{{count($counter)}}</td>

                            <td style="text-align:center; background-color:#f4f4f4;">{{ number_format($payroll_user_detail['payroll_user_reward'], 2) }}</td>
                            @foreach ($payrollItemsAdd as $payroll_item)
                                @if(isset($payroll_user_detail[$payroll_item->payroll_item_id]['employer']))
                                    <td style="text-align:center; background-color:#f4f4f4;">
                                        @if(@$payroll_item->setting_expense_id == 29)
                                             {{ $payroll_user_detail[$payroll_item->payroll_item_id]['employer']}} <br>({{$payroll_user_detail[$payroll_item->payroll_item_id]['employer']/ $otvalue->setting_expense_value}} hrs)
                                        @else
                                            {{ $payroll_user_detail[$payroll_item->payroll_item_id]['employer'] }}
                                       @endif
                                    </td>
                                    @php
                                        if(isset($payroll_total[$payroll_item->payroll_item_id]['employer'])){
                                            $payroll_total[$payroll_item->payroll_item_id]['employer'] += $payroll_user_detail[$payroll_item->payroll_item_id]['employer'];
                                        }else{
                                            $payroll_total[$payroll_item->payroll_item_id]['employer'] = $payroll_user_detail[$payroll_item->payroll_item_id]['employer'];
                                        }
                                    @endphp
                                @else
                                    <td style="text-align:center; background-color:#f4f4f4;">-</td>
                                @endif
                            @endforeach
                            @foreach($payrollItemsDeduct as $payroll_item)
                                @if($payroll_item->is_employer)
                                    @if(isset($payroll_user_detail[$payroll_item->payroll_item_id]['employee']))
                                        <td style="text-align:center; background-color:#ffffff;">{{ $payroll_user_detail[$payroll_item->payroll_item_id]['employee'] }}</td>
                                        @php
                                            if(isset($payroll_total[$payroll_item->payroll_item_id]['employee'])){
                                                $payroll_total[$payroll_item->payroll_item_id]['employee'] += $payroll_user_detail[$payroll_item->payroll_item_id]['employee'];
                                            }else{
                                                $payroll_total[$payroll_item->payroll_item_id]['employee'] = $payroll_user_detail[$payroll_item->payroll_item_id]['employee'];
                                            }
                                        @endphp
                                    @else
                                        <td style="text-align:center; background-color:#f4f4f4;">-</td>
                                    @endif
                                @endif
                            @endforeach
                            @foreach($payrollItemsDeduct as $payroll_item)
                                @if($payroll_item->is_employer)
                                    @if(isset($payroll_user_detail[$payroll_item->payroll_item_id]['employer']))
                                        <td style="text-align:center; background-color:#ffffff;">{{ $payroll_user_detail[$payroll_item->payroll_item_id]['employer'] }}</td>
                                        @php
                                            if(isset($payroll_total[$payroll_item->payroll_item_id]['employer'])){
                                                $payroll_total[$payroll_item->payroll_item_id]['employer'] += $payroll_user_detail[$payroll_item->payroll_item_id]['employer'];
                                            }else{
                                                $payroll_total[$payroll_item->payroll_item_id]['employer'] = $payroll_user_detail[$payroll_item->payroll_item_id]['employer'];
                                            }
                                        @endphp
                                    @else
                                        <td style="text-align:center; background-color:#ffffff;">-</td>
                                    @endif
                                @endif
                            @endforeach
                            @php $col_count = 0; @endphp
                            @foreach($payrollItemsDeduct as $payroll_item)
                                @if(!$payroll_item->is_employer)
                                    @if(isset($payroll_user_detail[$payroll_item->payroll_item_id]['employee']))
                                        <td style="text-align:center; background-color:{{ $col_count % 2 == 0 ? '#f4f4f4;' : '#ffffff;' }}">{{ $payroll_user_detail[$payroll_item->payroll_item_id]['employee'] }}</td>
                                        @php
                                            if(isset($payroll_total[$payroll_item->payroll_item_id]['employee'])){
                                                $payroll_total[$payroll_item->payroll_item_id]['employee'] += $payroll_user_detail[$payroll_item->payroll_item_id]['employee'];
                                            }else{
                                                $payroll_total[$payroll_item->payroll_item_id]['employee'] = $payroll_user_detail[$payroll_item->payroll_item_id]['employee'];
                                            }
                                        @endphp
                                    @else
                                        <td style="text-align:center; background-color:{{ $col_count % 2 == 0 ? '#f4f4f4;' : '#ffffff;' }}">-</td>
                                    @endif
                                    @php $col_count++; @endphp
                                @endif
                            @endforeach
                            <td style="text-align:center; background-color:{{ ($col_count + 1) % 2 == 0 ? '#ffffff' : '#f4f4f4' }};">{{ number_format($payroll_user_detail['payroll_user_paid_out'], 2) }}</td>
                            <td style="text-align:center; background-color:#f4f4f4;">{{ number_format($payroll_user_detail['payroll_user_total'], 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
        <tr>
            <td style="background-color:#fffbaf;"><strong>Total</strong></td>
            <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll->payroll_total_amount, 2) }}</strong></td>
            <td style="text-align:center; background-color:#fffbaf;"><strong></strong></td>
            <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll->payroll_total_reward, 2) }}</strong></td>
            @foreach ($payrollItemsAdd as $payroll_item)
                @if(isset($payroll_total[$payroll_item->payroll_item_id]['employer']))
                    <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll_total[$payroll_item->payroll_item_id]['employer'], 2) }}</strong></td>
                @else
                    <td style="text-align:center; background-color:#fffbaf;"><strong>-</strong></td>
                @endif
            @endforeach
            @foreach ($payrollItemsDeduct as $payroll_item)
                @if($payroll_item->is_employer)
                    @if(isset($payroll_total[$payroll_item->payroll_item_id]['employee']))
                        <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll_total[$payroll_item->payroll_item_id]['employee'], 2) }}</strong></td>
                    @else
                        <td style="text-align:center; background-color:#fffbaf;"><strong>-</strong></td>
                    @endif
                @endif
            @endforeach
            @foreach ($payrollItemsDeduct as $payroll_item)
                @if($payroll_item->is_employer)
                    @if(isset($payroll_total[$payroll_item->payroll_item_id]['employer']))
                        <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll_total[$payroll_item->payroll_item_id]['employer'], 2) }}</strong></td>
                    @else
                        <td style="text-align:center; background-color:#fffbaf;"><strong>-</strong></td>
                    @endif
                @endif
            @endforeach
            @php $col_count = 0; @endphp
            @foreach ($payrollItemsDeduct as $payroll_item)
                @if(!$payroll_item->is_employer)
                    @php $payroll_item_type = ($payroll_item->payroll_item_type == "Add" ? 'employer' : 'employee'); @endphp
                    @if(isset($payroll_total[$payroll_item->payroll_item_id][$payroll_item_type]))
                        <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll_total[$payroll_item->payroll_item_id][$payroll_item_type], 2) }}</strong></td>
                    @else
                        <td style="text-align:center; background-color:#fffbaf;"><strong>-</strong></td>
                    @endif
                    @php $col_count++; @endphp
                @endif
            @endforeach
            <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll->payroll_total_paid_out, 2) }}</strong></td>
            <td style="text-align:center; background-color:#fffbaf;"><strong>{{ number_format($payroll->payroll_grandtotal, 2) }}</strong></td>
        </tr>
    </tbody>
</table>
