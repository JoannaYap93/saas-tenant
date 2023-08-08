@if (@$component)
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <h4 class="mb-0 font-size-18">
            <span span class="mr-2">
                {{ $farmManager->user_fullname }} Workers Expense Detail Report - {{ date('F Y', mktime(0, 0, 0, $search['month'], 1, $search['year'])) }}
            </span>
        </h4>
    </div>
@endif
<div style="margin:auto">
    @if (!@$component)
        <p><b>Farm Manager Expense Report </b></p>
        <br><br>
    @endif
    <table class="table table-bordered" id="farm-manager-worker-expense-table">
        <thead>
            <tr>
                <th style="text-align: left; min-width: 230px; background-color: #343a40; color: white;">Expense</th>
                @foreach($workers as $worker)
                    <th style="background-color: #343a40; color: white; min-width:250px;">{{ ucwords(strtolower($worker['name'])) }}</th>
                @endforeach
                <th style="text-align: center; min-width: 130px; background-color:#FFF2CC; color:#000000;"><b>TOTAL</b></th>
        </thead>
        <tbody>
            @php $setting_expense_grandtotal = 0; @endphp
            @foreach($settingExpenseCategory as $setting_expense_category)
                @php $company_expense_worker_total = array(); @endphp
                <tr style="background-color: #e4e4e4;">
                    <th style="text-align: left;">
                        {{ json_decode($setting_expense_category->setting_expense_category_name)->en }}
                    </th>
                    <th style="text-align: center; background-color: #e4e4e4;" colspan="{{ sizeof($workers) + 1 }}">
                        {{ json_decode($setting_expense_category->setting_expense_category_name)->en }}
                    </th>
                </tr>
                @foreach ($settingExpense as $setting_expense)
                    @if ($setting_expense->setting_expense_category_id == $setting_expense_category->setting_expense_category_id)
                        @php $setting_expense_total = 0; @endphp
                        <tr>
                            <td style="text-align: left;">
                                {{ json_decode($setting_expense->setting_expense_name)->en }}
                            </td>
                            @foreach($workers as $key => $worker)
                                @if(isset($companyExpenseWorker[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$worker['id']]))
                                    <td style="{{ ($key % 2 == 0 ? 'background-color: #f5f5f5;' : '') }}">
                                        {{ number_format($companyExpenseWorker[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$worker['id']], 2) }}
                                    </td>
                                    @php
                                        $setting_expense_total += $companyExpenseWorker[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$worker['id']];
                                        if(isset($company_expense_worker_total[$worker['id']])){
                                            $company_expense_worker_total[$worker['id']] += $companyExpenseWorker[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$worker['id']];
                                        }else{
                                            $company_expense_worker_total[$worker['id']] = $companyExpenseWorker[$setting_expense_category->setting_expense_category_id][$setting_expense->setting_expense_id][$worker['id']];
                                        }
                                    @endphp
                                @else
                                    <td style="{{ ($key % 2 == 0 ? 'background-color: #f5f5f5;' : '') }}">-</td>
                                @endif
                            @endforeach
                            <td style="text-align: center; background-color:#FFF2CC; color:#000000;">
                                @php $setting_expense_grandtotal += $setting_expense_total; @endphp
                                <b>{{ ($setting_expense_total > 0 ? number_format($setting_expense_total, 2) : '-') }}</b>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            <tr>
                <th style="text-align: left; background-color:#FFF2CC; color:#000000;"><b>TOTAL</b></th>
                @php $company_expense_worker_grandtotal = 0; @endphp
                @foreach($workers as $worker)
                    @if(isset($company_expense_worker_total[$worker['id']]))
                        @php $company_expense_worker_grandtotal += $company_expense_worker_total[$worker['id']]; @endphp
                        <td style="text-align: center; background-color:#FFF2CC; color:#000000;;">
                            <b>{{ number_format($company_expense_worker_total[$worker['id']], 2) }}</b>
                        </td>
                    @else
                        <td style="text-align: center; background-color:#FFF2CC; color:#000000;;"><b>-</b></td>
                    @endif
                @endforeach
                @if($setting_expense_grandtotal == $company_expense_worker_grandtotal)
                    <td style="text-align: center; background-color:#FFF2CC; color:#000000;"><b>{{ number_format($company_expense_worker_grandtotal, 2) }}</b></td>
                @else
                    <td style="text-align: center; background-color:#FFF2CC; color:#000000;"><b>-</b></td>
                @endif
            </tr>
        </tbody>
    </table>
</div>
