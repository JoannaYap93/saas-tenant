<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sync Attendance Report</title>
</head>

<body style="width:100%">
    <div style="margin:auto">
        <p><b>Sync Attendance Report {{ $search['start_date'] }} to {{ $search['end_date'] }}</b></p>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th style="min-width: 250px;"></th>
                    @php $col_count = 0; @endphp
                    @foreach ($date_range as $date_key => $date)
                        <th colspan="5" style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">{{ $date->format('d-m-Y') }}</th>
                        @php $col_count++; @endphp
                    @endforeach
                    <th colspan="5" style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Total</th>
                </tr>
                <tr>
                    <th>Users</th>
                    @for($i = 0; $i < count($date_range) + 1; $i++)
                        <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Collect</th>
                        <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">KG</th>
                        <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">D.O.</th>
                        <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">KG</th>
                        <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Sync</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($company_land as $land)
                    @php
                        $land_total_arr = array();
                        $land_collect_total = 0;
                        $land_collect_sum_total = 0;
                        $land_do_total = 0;
                        $land_do_sum_total = 0;
                        $land_sync_total = 0;
                    @endphp

                    <tr>
                        <td style="text-align: left; background-color: #eee;">
                            <b>{{ $land->company->company_name }}<br/>
                                {{ $land->company_land_name }}</b>
                        </td>
                        <td style="background-color: #eee;" colspan="{{ (count($date_range)+ 1) * 5 }}">
                            <b>{{ $land->company->company_name }}<br/>
                                {{ $land->company_land_name }}</b>
                        </td>
                    </tr>

                    @foreach($users as $user)
                        @if($land->company_land_id == $user->company_land_id)
                            @php
                                $col_count = 0;
                                $user_collect_total = 0;
                                $user_collect_sum_total = 0;
                                $user_do_total = 0;
                                $user_do_sum_total = 0;
                                $user_sync_total = 0;
                            @endphp

                            <tr>
                                <td style="text-align: left;">
                                    {{ $user->user_fullname }}
                                </td>

                                @foreach($date_range as $date)
                                    @php $format_date = $date->format('Y-m-d'); @endphp

                                    @if(isset($collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count']))
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count'] > 0 ? $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count'] : '-') }} </td>
                                        @php $user_collect_total += $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp

                                        @if(isset($land_total_arr[$format_date]['collect_count']))
                                            @php $land_total_arr[$format_date]['collect_count'] += $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp
                                        @else
                                            @php $land_total_arr[$format_date]['collect_count'] = $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp
                                        @endif
                                    @else
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                    @endif

                                    @if(isset($collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum']))
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum'] > 0 ? number_format($collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum'], 2) : '-') }} </td>
                                        @php $user_collect_sum_total += $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp

                                        @if(isset($land_total_arr[$format_date]['collect_sum']))
                                            @php $land_total_arr[$format_date]['collect_sum'] += $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp
                                        @else
                                            @php $land_total_arr[$format_date]['collect_sum'] = $collect_details[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp
                                        @endif
                                    @else
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                    @endif

                                    @if(isset($do_details[$land->company_land_id][$user->user_id][$format_date]['do_count']))
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($do_details[$land->company_land_id][$user->user_id][$format_date]['do_count'] > 0 ? $do_details[$land->company_land_id][$user->user_id][$format_date]['do_count'] : '-') }} </td>
                                        @php $user_do_total += $do_details[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp

                                        @if(isset($land_total_arr[$format_date]['do_count']))
                                            @php $land_total_arr[$format_date]['do_count'] += $do_details[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp
                                        @else
                                            @php $land_total_arr[$format_date]['do_count'] = $do_details[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp
                                        @endif
                                    @else
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                    @endif

                                    @if(isset($do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum']))
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum'] > 0 ? number_format($do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum'], 2) : '-') }} </td>
                                        @php $user_do_sum_total += $do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp

                                        @if(isset($land_total_arr[$format_date]['do_sum']))
                                            @php $land_total_arr[$format_date]['do_sum'] += $do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp
                                        @else
                                            @php $land_total_arr[$format_date]['do_sum'] = $do_details[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp
                                        @endif
                                    @else
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                    @endif

                                    @if(isset($sync_collect_details[$land->company_land_id][$user->user_id][$format_date]) || isset($sync_do_details[$land->company_land_id][$user->user_id][$format_date]))
                                        @php
                                            $sync_details = array();

                                            if(isset($sync_details[$land->company_land_id][$user->user_id][$format_date])){
                                                $sync_details[$land->company_land_id][$user->user_id][$format_date] += @$sync_collect_details[$land->company_land_id][$user->user_id][$format_date] + @$sync_do_details[$land->company_land_id][$user->user_id][$format_date];
                                            }else {
                                                $sync_details[$land->company_land_id][$user->user_id][$format_date] = @$sync_collect_details[$land->company_land_id][$user->user_id][$format_date] + @$sync_do_details[$land->company_land_id][$user->user_id][$format_date];
                                            }
                                        @endphp

                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}"> {{ ($sync_details[$land->company_land_id][$user->user_id][$format_date] > 0 ? $sync_details[$land->company_land_id][$user->user_id][$format_date] : '-') }} </td>
                                        @php $user_sync_total += $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp

                                        @if(isset($land_total_arr[$format_date]['sync_count']))
                                            @php $land_total_arr[$format_date]['sync_count'] += $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp
                                        @else
                                            @php $land_total_arr[$format_date]['sync_count'] = $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp
                                        @endif
                                    @else
                                        <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                    @endif

                                    @php $col_count++; @endphp

                                @endforeach

                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ ($user_collect_total > 0 ? $user_collect_total : '-') }}
                                </td>

                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ ($user_collect_sum_total > 0 ? number_format($user_collect_sum_total, 2) : '-') }}
                                </td>

                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ ($user_do_total > 0 ? $user_do_total : '-') }}
                                </td>

                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ ($user_do_sum_total > 0 ? number_format($user_do_sum_total, 2) : '-') }}
                                </td>

                                <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                    {{ ($user_sync_total > 0 ? $user_sync_total : '-') }}
                                </td>
                            </tr>

                            @php
                                $land_collect_total += $user_collect_total;
                                $land_collect_sum_total += $user_collect_sum_total;
                                $land_do_total += $user_do_total;
                                $land_do_sum_total += $user_do_sum_total;
                                $land_sync_total += $user_sync_total;
                            @endphp
                        @endif
                    @endforeach
                    <tr>
                        <td style="text-align: center; background-color: #343a40; color:#ffffff;"><b>TOTAL</b></td>

                        @foreach($date_range as $date)
                            @php $format_date = $date->format('Y-m-d'); @endphp

                            @if(isset($land_total_arr[$format_date]['collect_count']))
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                                    {{ ($land_total_arr[$format_date]['collect_count'] > 0 ? $land_total_arr[$format_date]['collect_count'] : '-') }}
                                </td>
                            @else
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">-</td>
                            @endif

                            @if(isset($land_total_arr[$format_date]['collect_sum']))
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                                    {{ ($land_total_arr[$format_date]['collect_sum'] > 0 ? number_format($land_total_arr[$format_date]['collect_sum'], 2) : '-') }}
                                </td>
                            @else
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">-</td>
                            @endif

                            @if(isset($land_total_arr[$format_date]['do_count']))
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                                    {{ ($land_total_arr[$format_date]['do_count'] > 0 ? $land_total_arr[$format_date]['do_count'] : '-') }}
                                </td>
                            @else
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">-</td>
                            @endif

                            @if(isset($land_total_arr[$format_date]['do_sum']))
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                                    {{ ($land_total_arr[$format_date]['do_sum'] > 0 ? number_format($land_total_arr[$format_date]['do_sum'], 2) : '-') }}
                                </td>
                            @else
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">-</td>
                            @endif

                            @if(isset($land_total_arr[$format_date]['sync_count']))
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                                    {{ ($land_total_arr[$format_date]['sync_count'] > 0 ? $land_total_arr[$format_date]['sync_count'] : '-') }}
                                </td>
                            @else
                                <td style="text-align: center; background-color: #343a40; color:#ffffff;">-</td>
                            @endif

                        @endforeach


                        <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                            {{ ($land_collect_total > 0 ? $land_collect_total : '-') }}
                        </td>

                        <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                            {{ ($land_collect_sum_total > 0 ? number_format($land_collect_sum_total, 2) : '-') }}
                        </td>

                        <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                            {{ ($land_do_total > 0 ? $land_do_total : '-') }}
                        </td>

                        <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                            {{ ($land_do_sum_total > 0 ? number_format($land_do_sum_total, 2) : '-') }}
                        </td>

                        <td style="text-align: center; background-color: #343a40; color:#ffffff;">
                            {{ ($land_sync_total > 0 ? $land_sync_total : '-') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
