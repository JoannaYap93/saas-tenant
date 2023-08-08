<div class="table-responsive" style="@if(!@$component) margin:auto @endif">
    @if(!@$component)
        <p><b>Sync Attendance Report {{ @$search['start_date'] }} to {{ @$search['end_date'] }}</b></p>
        <br><br>
    @endif
    <table class="table" id="do_collect_sync_by_farm_user_table">
        <thead style="border:1px solid #eee border:1px solid #eee">
            <tr>
                <th style="min-width: 250px; border:1px solid #eee"></th>
                @php $col_count = 0; @endphp
                @foreach ($dateRange as $date_key => $date)
                    <th colspan="5" style="text-align: center; {{ $date_key % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{ $date->format('d/m') }}</th>
                    @php $col_count++; @endphp
                @endforeach
                <th colspan="5" style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">Total</th>
            </tr>
            <tr>
                <th style="text-align: left;">Users</th>
                @for($i = 0; $i < count($dateRange) + 1; $i++)
                    <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">Collect</th>
                    <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">KG</th>
                    <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">D.O.</th>
                    <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">KG</th>
                    <th style="text-align: center; {{ $i % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">Sync</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($companyLand as $land)
                @php
                    $land_total_arr = array();
                    $land_collect_total = 0;
                    $land_collect_sum_total = 0;
                    $land_do_total = 0;
                    $land_do_sum_total = 0;
                    $land_sync_total = 0;
                @endphp

                <tr>
                    <td style="text-align: left;  background-color: #eeeeee; border:1px solid #eee">
                        <b>{{ $land->company->company_name }}<br/>
                            {{ $land->company_land_name }}</b>
                    </td>
                    <td style="text-align: center; background-color: #eeeeee; border:1px solid #eee" colspan="{{ (count($dateRange)+ 1) * 5 }}">
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
                            <td style="text-align: left; border:1px solid #eee">
                                {{ $user->user_fullname }}
                            </td>

                            @foreach($dateRange as $date)
                                @php $format_date = $date->format('Y-m-d'); @endphp

                                @if(isset($collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count']))
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"> {{ ($collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count'] > 0 ? $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count'] : '-') }} </td>
                                    @php $user_collect_total += $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp

                                    @if(isset($land_total_arr[$format_date]['collect_count']))
                                        @php $land_total_arr[$format_date]['collect_count'] += $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp
                                    @else
                                        @php $land_total_arr[$format_date]['collect_count'] = $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_count']; @endphp
                                    @endif
                                @else
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @endif

                                @if(isset($collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum']))
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"> {{ ($collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum'] > 0 ? number_format($collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum'], 2, '.', '') : '-') }} </td>
                                    @php $user_collect_sum_total += $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp

                                    @if(isset($land_total_arr[$format_date]['collect_sum']))
                                        @php $land_total_arr[$format_date]['collect_sum'] += $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp
                                    @else
                                        @php $land_total_arr[$format_date]['collect_sum'] = $collectDetails[$land->company_land_id][$user->user_id][$format_date]['collect_sum']; @endphp
                                    @endif
                                @else
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @endif

                                @if(isset($doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count']))
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"> {{ ($doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count'] > 0 ? $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count'] : '-') }} </td>
                                    @php $user_do_total += $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp

                                    @if(isset($land_total_arr[$format_date]['do_count']))
                                        @php $land_total_arr[$format_date]['do_count'] += $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp
                                    @else
                                        @php $land_total_arr[$format_date]['do_count'] = $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_count']; @endphp
                                    @endif
                                @else
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @endif

                                @if(isset($doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum']))
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"> {{ ($doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum'] > 0 ? number_format($doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum'], 2, '.', '') : '-') }} </td>
                                    @php $user_do_sum_total += $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp

                                    @if(isset($land_total_arr[$format_date]['do_sum']))
                                        @php $land_total_arr[$format_date]['do_sum'] += $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp
                                    @else
                                        @php $land_total_arr[$format_date]['do_sum'] = $doDetails[$land->company_land_id][$user->user_id][$format_date]['do_sum']; @endphp
                                    @endif
                                @else
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @endif

                                @if(isset($syncCollectDetails[$land->company_land_id][$user->user_id][$format_date]) || isset($syncDoDetails[$land->company_land_id][$user->user_id][$format_date]))
                                    @php
                                        $sync_details = array();

                                        if(isset($sync_details[$land->company_land_id][$user->user_id][$format_date])){
                                            $sync_details[$land->company_land_id][$user->user_id][$format_date] += @$syncCollectDetails[$land->company_land_id][$user->user_id][$format_date] + @$syncDoDetails[$land->company_land_id][$user->user_id][$format_date];
                                        }else {
                                            $sync_details[$land->company_land_id][$user->user_id][$format_date] = @$syncCollectDetails[$land->company_land_id][$user->user_id][$format_date] + @$syncDoDetails[$land->company_land_id][$user->user_id][$format_date];
                                        }
                                    @endphp

                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"> {{ ($sync_details[$land->company_land_id][$user->user_id][$format_date] > 0 ? $sync_details[$land->company_land_id][$user->user_id][$format_date] : '-') }} </td>
                                    @php $user_sync_total += $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp

                                    @if(isset($land_total_arr[$format_date]['sync_count']))
                                        @php $land_total_arr[$format_date]['sync_count'] += $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp
                                    @else
                                        @php $land_total_arr[$format_date]['sync_count'] = $sync_details[$land->company_land_id][$user->user_id][$format_date]; @endphp
                                    @endif
                                @else
                                    <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">-</td>
                                @endif

                                @php $col_count++; @endphp

                            @endforeach

                            <td style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                {{ ($user_collect_total > 0 ? $user_collect_total : '-') }}
                            </td>

                            <td style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                {{ ($user_collect_sum_total > 0 ? number_format($user_collect_sum_total, 2, '.', '') : '-') }}
                            </td>

                            <td style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                {{ ($user_do_total > 0 ? $user_do_total : '-') }}
                            </td>

                            <td style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                {{ ($user_do_sum_total > 0 ? number_format($user_do_sum_total, 2, '.', '') : '-') }}
                            </td>

                            <td style="text-align: center; font-weight: bold; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
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
                    <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>TOTAL</b></td>

                    @foreach($dateRange as $date)
                        @php $format_date = $date->format('Y-m-d'); @endphp

                        @if(isset($land_total_arr[$format_date]['collect_count']))
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($land_total_arr[$format_date]['collect_count'] > 0 ? $land_total_arr[$format_date]['collect_count'] : '-') }}
                            </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                        @endif

                        @if(isset($land_total_arr[$format_date]['collect_sum']))
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($land_total_arr[$format_date]['collect_sum'] > 0 ? number_format($land_total_arr[$format_date]['collect_sum'], 2, '.', '') : '-') }}
                            </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                        @endif

                        @if(isset($land_total_arr[$format_date]['do_count']))
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($land_total_arr[$format_date]['do_count'] > 0 ? $land_total_arr[$format_date]['do_count'] : '-') }}
                            </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                        @endif

                        @if(isset($land_total_arr[$format_date]['do_sum']))
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($land_total_arr[$format_date]['do_sum'] > 0 ? number_format($land_total_arr[$format_date]['do_sum'], 2, '.', '') : '-') }}
                            </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                        @endif

                        @if(isset($land_total_arr[$format_date]['sync_count']))
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                                {{ ($land_total_arr[$format_date]['sync_count'] > 0 ? $land_total_arr[$format_date]['sync_count'] : '-') }}
                            </td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">-</td>
                        @endif

                    @endforeach


                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ ($land_collect_total > 0 ? $land_collect_total : '-') }}
                    </td>

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ ($land_collect_sum_total > 0 ? number_format($land_collect_sum_total, 2, '.', '') : '-') }}
                    </td>

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ ($land_do_total > 0 ? $land_do_total : '-') }}
                    </td>

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ ($land_do_sum_total > 0 ? number_format($land_do_sum_total, 2, '.', '') : '-') }}
                    </td>

                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">
                        {{ ($land_sync_total > 0 ? $land_sync_total : '-') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
