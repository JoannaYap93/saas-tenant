<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Worker Expense Report {{ $search['month'] }} {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    <table class="table" id="farm-manager-worker-expense-table">
        <thead>
            <tr>
                <th style="background-color: #d8d9df; border:1px solid #eee" rowspan="2"></th>
                @php
                    $col_count = 0;
                    $company_count = array();
                    $company_name = array();
                    $farm_manager = array();
                    $count = array();
                @endphp
                @foreach($managers as $manager)
                <?php
                    if(isset($company_count[$manager->company->company_id])){
                        $company_count[$manager->company->company_id] += 1;
                    }else{
                        $company_count[$manager->company->company_id] = 1;
                    }
                    $company_name[$manager->company->company_id] = $manager->company->company_name;

                    if(isset($farm_manager[$manager->company->company_id])){
                        array_push($farm_manager[$manager->company->company_id], $manager->toArray());
                    }else{
                        $farm_manager[$manager->company->company_id] = array();
                        array_push($farm_manager[$manager->company->company_id], $manager->toArray());
                    }
                ?>
                @endforeach
                @foreach($company_name as $k => $name)
                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan={{ $company_count[$k] }}
                        >
                        {{$name}}
                    </th>
                    @php $col_count++; @endphp
                @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" rowspan="2">TOTAL</th>
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @foreach($company_name as $company_id => $name)
                    @foreach ($farm_manager[$company_id] as $key => $user)
                        <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            {{$user['user_fullname']}}
                        </th>
                    @endforeach
                    @php $col_count++; @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
               $worker_expense_sum_total = [];
               $worker_sum_total = 0;
               $final_total = 0;
            @endphp
            @foreach ($month as $month_num => $month_name )
                <tr>
                    <td style="border:1px solid #eee; background-color: #e4e4e4;" class="table-secondary">
                        {{ $month_name }}
                    </td>
                    @php
                        $expense_worker_sum = 0;
                        $col_count = 0;
                    @endphp
                    @foreach($company_name as $company_id => $name)
                       @foreach ($farm_manager[$company_id] as $key => $user)
                        @if (isset($companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum']) && $companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum'] > 0)
                        <td style="text-align: center; {{  $col_count % 2 == 0 ?  'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            <a class="popup" href="{{route('farm_manager_worker_expense_detail', ['tenant' => tenant('id'), 'year' => $search['year'], 'month' => $month_num, 'company_id' => $company_id, 'user_id' => $user['user_id']])}}">
                            {{number_format($companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum'],2, '.', '')}}</a>
                        </td>
                            @php
                            $expense_worker_sum += $companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum'];
                            if (isset($worker_expense_sum_total[$user['user_id']][$company_id])) {
                                $worker_expense_sum_total[$user['user_id']][$company_id] += $companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum'];
                            } else {
                                $worker_expense_sum_total[$user['user_id']][$company_id]= $companyExpenseWorker[$user['user_id']][$company_id][$month_num]['company_expense_worker_sum'];
                            }
                            @endphp
                        @else
                            <td style="text-align: center; {{  $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                -
                            </td>
                        @endif
                        @endforeach
                        @php $col_count++; @endphp
                    @endforeach
                    @if ($expense_worker_sum  > 0)
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{number_format($expense_worker_sum,2, '.', '')}}</b></td>
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
                    @endif
                </tr>
                @php
                $worker_sum_total += $expense_worker_sum;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee" >
                <b>TOTAL</b>
                </td>
                @foreach($company_name as $company_id => $name)
                    @foreach ($farm_manager[$company_id] as $key => $user)
                    @if (isset($worker_expense_sum_total[$user['user_id']][$company_id]))
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            <b>{{ number_format($worker_expense_sum_total[$user['user_id']][$company_id],2, '.', '') }}</b>
                        </td>
                        @php
                        $final_total += $worker_expense_sum_total[$user['user_id']][$company_id];
                        @endphp
                    @else
                        <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">
                            -
                        </td>
                    @endif
                    @endforeach
                @endforeach
                @if ($worker_sum_total == $final_total)
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>{{ number_format($final_total,2, '.', '') }}</b></td>
                @else
                    <td style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee;"><b>-</b></td>
                @endif
            </tr>
        </tfoot>
    </table>
</div>
