<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Worker Attendance Reporting</h4>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" style="@if (!@$component) margin:auto @endif">
                    <table class="table" id="tree-target-report-table">
                        <thead>
                            @php
                                $col_count=0;
                                $total_all_day = 0;
                                $grand_total_salary = 0;
                                $total_wd = 0;
                                $total_hd = 0;
                                $total_current_day = 0;
                                $total_absent = 0;
                                $total_worker_per_day = [];
                            @endphp
                            <tr style="background-color: #e4e4e4;">
                                <th style="min-width: 100px; background-color: #d8d9df; border:1px solid #eee;" rowspan="2">Worker Name</th>
                                @foreach($monthSel as $month)
                                <th colspan="{{count($daySel)}}" style="text-align: center; background-color: #d8d9df;padding-left:50px;">{{ $month }}</th>
                                @endforeach
                                <th style="min-width: 100px; background-color: #fffbaf; border:1px solid #eee;" rowspan="2">Whole Day</th>
                                <th style="min-width: 100px; background-color: #fffbaf; border:1px solid #eee;" rowspan="2">Half Day</th>
                                <th style="min-width: 100px; background-color: #fffbaf; border:1px solid #eee;" rowspan="2">Absent</th>
                                <th style="min-width: 100px; background-color: #fffbaf; border:1px solid #eee;" rowspan="2">Total Day</th>
                                <th style="min-width: 100px; background-color: #fffbaf; border:1px solid #eee;" rowspan="2">Salary(RM)</th>
                            </tr>
                            <tr>
                                @foreach ($daySel as $day)
                                            <th style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #d8d9df;' }} border:1px solid #eee;">
                                                {{$day}}
                                            </th>
                                            @php
                                                $col_count++;
                                            @endphp
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workerList as $worker)
                                @foreach ($records["worker_day"] as $key => $record)
                                    @if($key == $worker->worker_id)
                                        <tr>
                                            <td style="min-width: 180px; text-align: left; background-color: #d8d9df; border:1px solid #eee">{{ $worker->worker_name}}</td>
                                            @php
                                                $total_day = 0;
                                                $total_expense = 0;
                                                $total_expense2 = 0;
                                                $col_count = 0;
                                                $sub_total_wd = 0;
                                                $sub_total_hd = 0;
                                                $rest_day_salary = 0;
                                                $sub_total_absent = 0;
                                                $array_date = [];
                                            @endphp
                 
                                            @foreach($daySel as $keys => $day)
                                                @if(isset($record[$keys]))
                                                    @if(json_decode($record[$keys]))
                                                        @php
                                                            foreach(json_decode($record[$keys]) as $ids => $detail){
                                                                if(isset($value_attendance[$ids])){
                                                                    $value_attendance[$ids] = $detail;
                                                                }else{
                                                                    $value_attendance[$ids] = $detail;
                                                                }
                                                            }
                                                            foreach ($value_attendance["task"] as $value){
                                                                $total_expense += $value->expense_total;
                                                            }
                                                        @endphp

                                                        @if($value_attendance["status"] == 1)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">W</td>
                                                            @php
                                                                $total_day += 1;
                                                                $total_wd++;
                                                                $sub_total_wd++;
                                                                $col_count++;
                                                                $total_all_day += 1;
                                                                if($day <= $currentday){
                                                                    $total_current_day++;
                                                                }
                                                                if(isset($total_worker_per_day[$day])){
                                                                    $total_worker_per_day[$day] += 1;
                                                                }else{
                                                                    $total_worker_per_day[$day] = 1;
                                                                }
                                                            @endphp
                                                        @elseif($value_attendance["status"] == 2)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">H</td>
                                                            @php
                                                                $total_day += 0.5;
                                                                $total_all_day += 0.5;
                                                                $total_hd++;
                                                                $col_count++;
                                                                $sub_total_hd++;
                                                                if($day <= $currentday){
                                                                    $total_current_day++;
                                                                }

                                                                if(isset($total_worker_per_day[$day])){
                                                                    $total_worker_per_day[$day] += 1;
                                                                }else{
                                                                    $total_worker_per_day[$day] = 1;
                                                                }
                                                            @endphp
                                                        @elseif($value_attendance["status"] == 3)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @elseif($value_attendance["status"] == 4)
                                                        <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                // $rest_day_salary += 60;
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @else
                                                        <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                  $rest_day_salary += 60;
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @endif
                                                    @else
                                                      
                                                        @php
                                                            // echo "<pre>";
                                                            // print_r($record[$keys]);
                                                            // echo "<br>";
                                                            // echo "<br>";
                                                            // echo "<br>";
                                                            // echo "<br>";

                                                            // print_r(explode('},{',$record[$keys]));

                                                            $test = explode('},{',$record[$keys]);

                                                            foreach ($test as $key => $value) {
                                                               
                                                         

                                                                if(substr($value,0,1) != '{'){
                                                                    $value = '{' . $value;
                                                                }

                                                                
                                                                if(substr($value,-1) != '}'){
                                                                    $value = $value . '}';
                                                                }

                                                             
                                                                foreach(json_decode($value) as $ids => $detail){
                                                                    if(isset($value_attendance[$ids])){
                                                                        $value_attendance[$ids] = $detail;
                                                                    }else{
                                                                        $value_attendance[$ids] = $detail;
                                                                    }
                                                                }
                                                                foreach ($value_attendance["task"] as $value){
                                                                        $total_expense2 += $value->expense_total;
                                                                }

                                                            }
                                                        @endphp
                                                        @if($value_attendance["status"] == 1)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">W</td>
                                                            @php
                                                                $total_day += 1;
                                                                $total_all_day += 1;
                                                                $total_wd++;
                                                                $sub_total_wd++;
                                                                $col_count++;
                                                                if($day <= $currentday){
                                                                    $total_current_day++;
                                                                }
                                                                
                                                                if(isset($total_worker_per_day[$day])){
                                                                    $total_worker_per_day[$day] += 1;
                                                                }else{
                                                                    $total_worker_per_day[$day] = 1;
                                                                }
                                                            @endphp
                                                        @elseif($value_attendance["status"] == 2)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">H</td>
                                                            @php
                                                                $total_day += 0.5;
                                                                $total_all_day += 0.5;
                                                                $total_hd++;
                                                                $sub_total_hd++;
                                                                $col_count++;
                                                                if($day <= $currentday){
                                                                    $total_current_day++;
                                                                }

                                                                if(isset($total_worker_per_day[$day])){
                                                                    $total_worker_per_day[$day] += 1;
                                                                }else{
                                                                    $total_worker_per_day[$day] = 1;
                                                                }

                                                            @endphp
                                                        @elseif($value_attendance["status"] == 3)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @elseif($value_attendance["status"] == 4)
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @else
                                                            <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                            @php
                                                                if($day <= $currentday){
                                                                    $total_absent++;
                                                                    $sub_total_absent++;
                                                                    $total_current_day++;
                                                                }
                                                                $col_count++;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @else            
                                                    <td style="min-width: 150px; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                                                    @php
                                                        $col_count++;
                                                        if($day <= $currentday){
                                                            $total_absent++;
                                                            $sub_total_absent++;
                                                            $total_current_day++;
                                                        }
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                $total_salary_combine = $total_expense + $total_expense2 + $rest_day_salary;
                                            @endphp
                                            {{-- @dd($total_expense,$total_expense2) --}}
                                            <td style="text-align: center; background-color: #fffde0; border:1px solid #eee"><b>{{ $sub_total_wd }}</b></td>
                                            <td style="text-align: center; background-color: #fffde0; border:1px solid #eee"><b>{{$sub_total_hd}}</b></td>
                                            <td style="text-align: center; background-color: #fffde0; border:1px solid #eee"><b>{{$sub_total_absent}}</b></td>
                                            <td style="text-align: center; background-color: #fffde0; border:1px solid #eee"><b>{{ $total_day }}</b></td>
                                            @if(isset($records["worker_salary"][$worker->worker_id]))
                                                <td style="text-align: center; background-color: #fffde0; border:1px solid #eee"><b>{{ $total_salary_combine}}</b></td>
                                                @php
                                                    $grand_total_salary +=  $total_salary_combine;
                                                @endphp
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>TOTAL</b></td>
                                @foreach ($daySel as $day)
                                        <td style="text-align: center; background-color:#fffbaf; color:#000000">
                                            @if(isset($total_worker_per_day[$day]))
                                            {{$total_worker_per_day[$day]}}
                                            @else
                                            0
                                            @endif
                                        </td>
                                        @php
                                            $col_count++;
                                        @endphp
                                @endforeach
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{ $total_wd }}</b></td>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{$total_hd}}</b></td>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{$total_absent}}</b></td>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{$total_all_day}}</b></td>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format($grand_total_salary,2, '.', '')}}</b></td>
                             </tr>
                        </tfoot>
                    </table>
                </div>
                <div class='parent grid-parent'>
                    <div class='child' style="margin-left: 10px">
                        <strong>W : Whole Day</strong><br>
                        <strong>H : Half Day</strong><br>
                        <strong>- : Absent</strong>
                    </div>
                    <div class='child' style="margin-right: 10px">
                            <table align="right">
                                <thead>
                                    <tr style="background-color: #e4e4e4;">
                                        <th class=" p-2  m-0" style="text-align: left; min-width:300px; border: 1px solid #e4e4e4;" colspan="2"><b>Total Summary</b></th>       
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Worker<span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                                <span>
                                                    {{ count($records["worker_day"]) ? count($records["worker_day"]): '0' }} People
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Day: <span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                                <span>
                                                    {{ $total_all_day? $total_all_day: '0' }} Days
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Paid Out: <span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>RM
                                                {{ number_format($grand_total_salary,2) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Whole Day: <span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>
                                                {{ $total_wd }} Days
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Half Day: <span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>
                                                {{ $total_hd }} Days
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>Total Absent: <span>
                                        </td>
                                        <td class=" p-2  m-0" style="text-align: left; min-width:150px; border: 1px solid #e4e4e4;">
                                            <span>
                                                {{ $total_absent }} Days
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                  </div>
            </div>
            </div>
        </div>
    </div>