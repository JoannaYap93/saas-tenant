<div class="table-responsive" style="@if(!@$component) margin:auto @endif">
    @if(!@$component)
        <p><b>Expense Report {{ $search['year'] }}</b></p>
        <br><br>
    @endif
    <table class="table" id="collect_do_table">
          <thead>
              <tr>
                  <th style="text-align:left; background-color: #d8d9df; border:1px solid #eee" >Expense Type</th>
                  @foreach ($monthSel as $month_num => $month_name)
                      <th style="min-width: 220px; text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                          colspan='1'>
                          {{ $month_name }}
                      </th>
                  @endforeach
              </tr>

          </thead>
          <tbody>
            @foreach($expenseWtype as $ewtkey => $ewt)
              <tr>
                <td style="text-align: left; min-width:250px; background-color: #e4e4e4; border:1px solid #eee">
                    {{ json_decode($ewt->setting_expense_name)->en }} ({{$ewt->setting_expense_type->setting_expense_type_name}})
                </td>
                @foreach($monthSel as $month_num => $month_name)
                    @if(isset($doExpense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum']) && $doExpense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum'] > 0)
                        <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">RM {{ $doExpense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum'] }}</td>
                    @else
                        <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                    @endif
                @endforeach
              </tr>
            @endforeach
          </tbody>
    </table>
 </div>
