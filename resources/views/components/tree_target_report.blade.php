<div class="table-responsive" style="@if (!@$component) margin:auto @endif">
    @if (!@$component)
        <p><b>Tree Target Report</b></p>
        <br><br>
    @endif
    <table class="table table-nowrap table-bordered" id="tree-target-report-table">
        <thead>
            <tr>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>No</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Company</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Land</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Acres</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Total Tree</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Awaiting Plant</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Tree to Plant<br>(each month)</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Small Tree Planted<br>(by current month)</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Remaining Tree<br>to Plant</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Baby Tree Planted<br>(by current month)</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Production<br>New Tree</b></th>
                <th style="color:black; background-color: #d8d9df; border:1px solid #eee"><b>Remaining<br>Available Space</b></th>
            </tr>
        </thead>
        <tbody>
            @php
                $row_count = 0;
                $current_date = now();
                $month = $current_date->month; 
                $count_company_land = $company_lands = array();
                $total_tree = $small_tree_planted = $baby_tree_planted = array();
                $sum_col_acres = $sum_col_total_tree = $sum_col_awaiting_plant = $sum_col_tree_to_plant = 0;
                $sum_col_small_tree = $sum_col_remaining_tree = $sum_col_baby_tree = 0;
                $sum_col_production_new_tree = $sum_col_remaining_available_space = 0;
            @endphp
            @foreach ($companyLand as $key => $company_land)
                @php
                    if(isset($count_company_land[$company_land->company_id])){
                        $count_company_land[$company_land->company_id] += 1;
                    }
                    else {
                        $count_company_land[$company_land->company_id] = 1;
                    }

                    $company_lands[$company_land->company_id][$key] = $company_land;

                    if(isset($totalTreePlanted[$company_land->company_land_id])){
                        $total_tree[$company_land->company_land_id] = $totalTreePlanted[$company_land->company_land_id];
                    }

                    if(isset($smallTreePlanted[$company_land->company_land_id])){
                        $small_tree_planted[$company_land->company_land_id] = $smallTreePlanted[$company_land->company_land_id];
                    }

                    if(isset($babyTreePlanted[$company_land->company_land_id])){
                        $baby_tree_planted[$company_land->company_land_id] = $babyTreePlanted[$company_land->company_land_id];
                    }
                @endphp
            @endforeach
            @foreach ($companies as $key => $company)
                @php
                    $company_land_index = 0;
                @endphp
                <tr>
                    <td rowspan="{{$count_company_land[$company->company_id]}}" style="text-align: center; width:10px; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$key+1}}</td>
                    <td rowspan="{{$count_company_land[$company->company_id]}}" style="text-align: left; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">
                        {{$company->company_name}}
                    </td>
                    @foreach ($company_lands[$company->company_id]  as $land)
                        <?php
                            $no_tree_planted = 0;
                            $total_according_acres = 0;

                            $awaiting_plant = 0;
                            $tree_to_plant = 0;

                            $small_tree = 0;
                            $remaining_tree = 0;
                            $baby_tree = 0;

                            $production_new_tree = 0;
                            $remaining_available_space = 0;


                            if (isset($total_tree[$land->company_land_id])){
                                $no_tree_planted = $total_tree[$land->company_land_id];

                                $sum_col_acres += $land->company_land_total_acre;
                                $sum_col_total_tree += $total_tree[$land->company_land_id];
                            }

                            if($land->company_land_total_acre > 0 && $numberTreePerAcre > 0){
                                $total_according_acres = $land->company_land_total_acre * $numberTreePerAcre;
                            }

                            $awaiting_plant = $total_according_acres - $no_tree_planted;
                            $sum_col_awaiting_plant += $awaiting_plant;

                            $tree_to_plant = round($awaiting_plant / (13 - $month));
                            $sum_col_tree_to_plant += $tree_to_plant;

                            if (isset($small_tree_planted[$land->company_land_id])){
                                $small_tree = $small_tree_planted[$land->company_land_id];
                                $sum_col_small_tree += $small_tree;

                                $production_new_tree = round($small_tree/1.5);
                                $sum_col_production_new_tree +=  $production_new_tree;
                            }

                            $remaining_tree = $tree_to_plant - $small_tree;
                            $sum_col_remaining_tree += $remaining_tree;

                            if (isset($baby_tree_planted[$land->company_land_id])){
                                $baby_tree = $baby_tree_planted[$land->company_land_id];
                                $sum_col_baby_tree += $baby_tree;
                            }

                            $remaining_available_space = $awaiting_plant - $production_new_tree;
                            $sum_col_remaining_available_space += $remaining_available_space;
                        ?>
                        @if ($company_land_index > 0)
                            <tr>
                        @endif
                            <td style="text-align: left; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$land->company_land_name }}</td>
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$land->company_land_total_acre ? $land->company_land_total_acre : '-'}}</td>

                        @if ($no_tree_planted == 0 && $total_according_acres == 0)
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                        @else
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$no_tree_planted}} / {{$total_according_acres}}</td>
                        @endif

                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$awaiting_plant != 0 ? $awaiting_plant : '-'}}</td>
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$tree_to_plant != 0 ? $tree_to_plant : '-' }}</td>

                        @if ($small_tree < $tree_to_plant)
                            <td style="color:red; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$small_tree != 0 ? $small_tree : '-'}}</td>
                        @elseif ($small_tree != 0 && $tree_to_plant != 0 && $small_tree >= $tree_to_plant)
                            <td style="color:green; text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$small_tree != 0 ? $small_tree : '-'}}</td>
                        @else
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">-</td>
                        @endif

                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$remaining_tree != 0 ? $remaining_tree : '-'}}</td>
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$baby_tree != 0 ? $baby_tree : '-'}}</td>
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$production_new_tree != 0 ? $production_new_tree : '-'}}</td>
                            <td style="text-align: center; {{ $row_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee;">{{$remaining_available_space != 0 ? $remaining_available_space : '-'}}</td>
                            </tr>
                        @php
                            $company_land_index++;
                        @endphp
                    @endforeach
                    @php
                        $row_count++;
                    @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=3 style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">TOTAL</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_acres != 0 ? $sum_col_acres : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_total_tree != 0 ? $sum_col_total_tree : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_awaiting_plant != 0 ? $sum_col_awaiting_plant : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_tree_to_plant != 0 ? $sum_col_tree_to_plant : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_small_tree != 0 ? $sum_col_small_tree : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_remaining_tree != 0 ? $sum_col_remaining_tree : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_baby_tree != 0 ? $sum_col_baby_tree : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_production_new_tree != 0 ? $sum_col_production_new_tree : '-'}}</td>
                <td style="font-weight:bold; color:black; text-align: center; background-color:#fffbaf; border:1px solid #eee;">{{$sum_col_remaining_available_space != 0 ? $sum_col_remaining_available_space : '-'}}</td>
            </tr>
        </tfoot>
    </table>
</div>
