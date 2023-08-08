<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SettingExpense</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Setting Expense</b></p>
        <br><br>
        <table class="table table-nowrap">
            <thead class="thead-light">
                <tr>
                    <th scope="col" style="width: 70px;">#</th>
                    <th>Expense Details</th>
                    <th>Expense Descrption</th>
                    <th>Expense Value</th>
                    <th>Expense Subcon</th>
                    @if(auth()->user()->user_type_id == 1)
                        <th>Overwrite Company</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp

                @forelse ($records as $row)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td><b>{{ json_decode($row->setting_expense_name)->en }}</b><br>
                        <span style="font-style: italic">
                            @if(!empty($row->setting_expense_category_id))
                                {{ json_decode($row->expense_category->setting_expense_category_name)->en }}
                            @endif
                        </span><br>
                        @if(@$row->setting_expense_status == 'active')
                        <span class="badge badge-primary font-size-10">Active</span>
                        @else
                        <span class="badge badge-danger font-size-10">Inactive</span>
                        @endif
                    </td>
                    <td>
                        Role: <b>{{ @$row->worker_role->worker_role_name }}</b><br>
                        {{ Str::limit(@$row->setting_expense_description, 50) }}
                    </td>
                    <td>
                        @if($row->expense_overwrite && Auth::user()->user_type_id != 1)
                            RM {{ @$row->expense_overwrite->setting_expense_overwrite_value }}
                        @else
                            RM {{ $row->setting_expense_value }}
                        @endif
                    </td>
                    <td>
                        @if($row->expense_overwrite && Auth::user()->user_type_id != 1)
                            @if($row->expense_overwrite->is_subcon_allow == 0 )
                                <div>
                                    <span class="badge badge-warning font-size-10">No Subcon Allow</span>
                                </div>
                            @else
                                <div>
                                    <span class="badge badge-success font-size-10">Subcon Allow</span>
                                </div>
                                RM {{ @$row->expense_overwrite->setting_expense_overwrite_subcon }}
                            @endif
                        @else
                            @if($row->is_subcon_allow == 0)
                                <div>
                                    <span class="badge badge-warning font-size-10">No Subcon Allow</span>
                                </div>
                            @else
                                <div>
                                    <span class="badge badge-success font-size-10">Subcon Allow</span>
                                </div>
                                RM {{ @$row->setting_expense_subcon }}
                            @endif
                        @endif
                    </td>
                    @if(auth()->user()->user_type_id == 1)
                        <td>
                            @if($row->expense_overwrite != null && count(@$row->expense_overwrite)>0)
                                @php
                                    $count = 0;
                                @endphp
                                @foreach($row->expense_overwrite as $expenses_overwrite)
                                    @if($expenses_overwrite->overwrite_company)
                                        <div class="waves-effect waves-light" data-toggle="modal" data-target=".overwrite-detail" onClick="overwriteDetail({{$row->setting_expense_id}}, {{$expenses_overwrite->company_id}})">{{ @$expenses_overwrite->overwrite_company->company_name }}</div>
                                    @endif
                                        @php
                                            $count++
                                        @endphp
                                    @if($count != count($row->expense_overwrite))
                                            <br>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center">
                                    -
                                </div>
                            @endif
                        </td>
                    @endif
                </tr>
                @empty
                    <tr>
                        <td colspan="7">No record found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
