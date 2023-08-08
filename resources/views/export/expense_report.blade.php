<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Expense Report {{ $search['year'] }}</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Expense Report {{ $search['year'] }}</b></p>
        <br><br>
        <table>
          <thead>
              <tr>
                  <th style="text-align:left; background-color: #d8d9df;" >Expense Type</th>
                  @foreach ($month_sel as $month_num => $month_name)
                      <th style="min-width: 220px; text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"
                          colspan='1'>
                          {{ $month_name }}
                      </th>
                  @endforeach
              </tr>

          </thead>
          <tbody>
            @foreach($expense_w_type as $ewtkey => $ewt)
              <tr>
                <td style="text-align: left; background-color: #e4e4e4;">
                {{ $ewt->setting_expense_name}}({{$ewt->setting_expense_type->setting_expense_type_name}})
                </td>
                @foreach($month_sel as $month_num => $month_name)
                  @if(isset($do_expense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum']) && $do_expense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum'] > 0)
                  <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">RM {{ $do_expense[$ewt->setting_expense_name][$ewt->setting_expense_id][$ewt->setting_expense_type_id][$month_num]['expense_sum'] }}</td>
                  @else
                  <td style="text-align: center; {{ $month_num % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                  @endif
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</body>
</html>
