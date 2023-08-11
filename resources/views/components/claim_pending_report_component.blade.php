@if(@$records['claim_data_farm_manager'] || @$records['claim_data_company'])
    <div class="table-responsive">
        <table class="table" id="claim-pending-report">
            <thead>
                <tr>
                    <th style="background-color:white; font-weight: bold; color:#000000; min-width: 250px; border: 1px solid #eee" >{{ auth()->user()->user_type_id == 1 ? 'Company' : 'Farm Manager' }}</th>
                    @foreach ($monthSel as $key => $month)
                        <th colspan="1" style="text-align: center; font-weight: bold; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} min-width: 150px; border:1px solid #eee">{{$month}}</th>
                    @endforeach
                    <th colspan="1" style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; min-width: 150px; border: 1px solid #eee">Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($records) --}}
                @if (auth()->user()->user_type_id == 1)
                    @foreach ($companySel as $company_id => $companies)
                        <tr>
                            <td style="border: 1px solid #eee;">{{$companies->company_name}}</td>
                            @foreach ($monthSel as $key => $month)
                                <td style="text-align: center; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                    @if (@$records['claim_data_company'][$companies->company_id][$key] > 0)
                                        @if (@$component)
                                            {{-- <a class="popup" href="{{ route('claim_pending_detail_report_superadmin', ['tenant' => tenant('id'), 'year' => $search['year'], 'month' => $key, 'company_id' => $companies->company_id])}}"> --}}
                                            {{number_format($records['claim_data_company'][$companies->company_id][$key], 2)}}
                                        {{-- </a> --}}
                                        @else
                                            {{number_format($records['claim_data_company'][$companies->company_id][$key], 2)}}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; border: 1px solid #eee">{{ @$records['total_by_company_claim_data_company'][$companies->company_id] > 0 ? number_format($records['total_by_company_claim_data_company'][$companies->company_id], 2) : '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($companySel as $company_id => $companies)
                        @if (auth()->user()->company_id == $companies->company_id)
                            @foreach ($farmManager as $manager)
                                @if ($manager->company_id == $companies->company_id)
                                    <tr>
                                        <td style="border: 1px solid #eee;">{{$manager->worker_name}}</td>
                                        @foreach ($monthSel as $key => $month)
                                            <td style="text-align: center; {{ $key % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                                @if (@$records['claim_data_farm_manager'][$companies->company_id][$key][$manager->worker_id] > 0)
                                                    @if (@$component)
                                                        <a class="popup" href="{{ route('claim_pending_detail_report_admin', ['tenant' => tenant('id'), 'year' => $search['year'], 'month' => $key, 'company_id' => $companies->company_id, 'farm_manager' => $manager->worker_id])}}">
                                                            {{number_format($records['claim_data_farm_manager'][$companies->company_id][$key][$manager->worker_id], 2)}}
                                                            {{-- @dump($records['claim_data_farm_manager'][$companies->company_id]) --}}
                                                        </a>
                                                    @else
                                                        {{number_format($records['claim_data_farm_manager'][$companies->company_id][$key][$manager->worker_id], 2)}}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                        <td style="text-align: center; background-color:#fffbaf; font-weight: bold; color:#000000; border: 1px solid #eee">{{ @$records['total_by_farm_manager_claim_data_farm_manager'][$manager->worker_id] > 0 ? number_format($records['total_by_farm_manager_claim_data_farm_manager'][$manager->worker_id], 2) : '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: left; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">Total (RM)</td>
                    @foreach ($monthSel as $key => $month)
                        @if (auth()->user()->user_type_id == 1)
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_month_claim_data_company'][$key] > 0 ? number_format($records['total_by_month_claim_data_company'][$key], 2) : '-' }}</td>
                        @else
                            <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_month_claim_data_farm_manager'][$key] > 0 ? number_format($records['total_by_month_claim_data_farm_manager'][$key], 2) : '-' }}</td>
                        @endif
                    @endforeach
                    @if (auth()->user()->user_type_id == 1)
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_all_company'] > 0 ? number_format($records['total_by_all_company'], 2) : '-' }}</td>
                    @else
                        <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{ @$records['total_by_company_claim_data_company'][auth()->user()->company_id] > 0 ? number_format($records['total_by_company_claim_data_company'][auth()->user()->company_id], 2) : '-' }}</td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
@else
    <table>
        <tr>
            <td>No records found!</td>
        </tr>
    </table>
@endif