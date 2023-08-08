<div class="table-responsive">
@if (@$record)
    <table class="table table-bordered" id="tree_pointer_reporting_table">
        <thead>
          <tr>
            <th style="text-align: center; background-color: #d8d9df; border:1px solid #eee">No</th>
            <th style="min-width: 200px; text-align: center; background-color: #d8d9df; border:1px solid #eee">Company</th>
            <th style="min-width: 150px; text-align: center; background-color: #d8d9df; border:1px solid #eee">Land</th>
            <th style="min-width: 100px; text-align: center; background-color: #d8d9df; border:1px solid #eee">Tree Pointer</th>
            <th style="text-align: center; background-color: #d8d9df; border:1px solid #eee">Total Tree Pointer</th>
        </tr>
        </thead>
        <tbody>
            @php
                $counter_row = 0;
                $total_pointer = 0;
                $count_total = 0;
            @endphp
            @foreach ($record['data'] as $company_id => $company)
                @php
                    $count_land = 0;
                    foreach ($record['company_land'][$company_id] as $land) {
                        $count_land += count(array($land));
                    }
                    $company_count = 0;
                    $company_land_count = 0;
                    $company_land_index = 0;
                @endphp
                <tr>
                    <td rowspan="{{ $count_land }}" style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">{{ $loop->iteration }}</td>
                    <td rowspan="{{ $count_land }}" style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">{{ $record['company'][$company_id] }}</td>   
                @foreach ($company as $company_land_id => $company_land)
                    @if ($company_land_index > 0)
                        <tr>
                    @endif
                    <td style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">
                        {{ $record['company_land'][$company_id][$company_land_id] }}</td>

                    @if(isset($recordpointer['pointer'][$company_id][$company_land_id]))
                        <td style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">
                            @if (isset($component))
                                <a class="popup" href="{{ route('tree_pointer_reporting_details', ['company_land_id' => @$company_land_id, 'setting_tree_age_upper' => @$search['setting_tree_age_upper'] ? $search['setting_tree_age_upper'] : count($age) , 'setting_tree_age_lower' => @$search['setting_tree_age_lower'] ? $search['setting_tree_age_lower'] : '1'])}}">{{number_format($recordpointer['pointer'][$company_id][$company_land_id],2)}}
                                </a>
                            @else
                                {{number_format($recordpointer['pointer'][$company_id][$company_land_id],2)}}
                            @endif
                        </td>
                    @else
                        <td style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">
                            -
                        </td>
                    @endif
                    @if($company_count == 0)
                        @if((isset($recordpointer['total_pointer'][$company_id])))
                            <td rowspan="{{ $count_land }}" style="text-align: center; {{ $counter_row % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}; border:1px solid #eee">
                                <a class="popup" href="{{ route('tree_pointer_reporting_details_total', ['company_id' => @$company_id, 'setting_tree_age_upper' => @$search['setting_tree_age_upper'] ? $search['setting_tree_age_upper'] : count($age) , 'setting_tree_age_lower' => @$search['setting_tree_age_lower'] ? $search['setting_tree_age_lower'] : '1'])}}">{{number_format($recordpointer['total_pointer'][$company_id],2)}}
                                </a>
                            </td>
                            @php
                                $total_pointer += $recordpointer['total_pointer'][$company_id];
                            @endphp
                        @endif 
                    @endif
                    </tr>
                    @php
                        $company_land_index++;
                        $company_count ++;
                    @endphp            
                @endforeach
                @php
                    $counter_row ++;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: right; background-color:#fffbaf; color:#000000; border:1px solid #eee" colspan="4"><b>Total</b></th>
                <th style="text-align: center; background-color:#fffbaf; color:#000000; border:1px solid #eee">{{number_format($total_pointer,2)}}</th>
            </tr>
        </tfoot>
    </table>
@else
    <tr>
        <td colspan="3">No Records!</td>
    </tr>
@endif
</div>
