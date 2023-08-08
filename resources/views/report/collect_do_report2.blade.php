<div class="table-responsive">
    <table class="table table-bordered" id="collect_do_table">
        <thead>
            <tr>
                <th style="min-width: 220px;" class="table-secondary" rowspan="4">Product</th>
                @foreach ($month_sel as $month_num => $month_name)
                    <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}"
                        colspan={{ count($company_land) * 3 }}>
                        {{ $month_name }}
                    </th>
                @endforeach
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @php
                  $col_count = 0;
                @endphp
                @foreach ($month_sel as $month_num => $month)
                  @php
                    $company_count = array();
                    $campany_name = array();
                  @endphp
                  @foreach($company_land as $key => $land)
                        <?php
                          if(isset($company_count[$land->company->company_id])){
                              $company_count[$land->company->company_id] += 1;
                          }else{
                            $company_count[$land->company->company_id] = 1;
                          }

                          $campany_name[$land->company->company_id] = $land->company->company_name;

                        ?>
                  @endforeach

                  @foreach($campany_name as $key => $name)
                        <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}" colspan={{$company_count[$key]*3}}>
                            {{$name}}
                        </th>

                  @endforeach

                @endforeach
                @php $col_count++; @endphp
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @foreach ($month_sel as $month_num => $month)
                    @foreach ($company_land as $key => $land)
                        <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}" colspan=3>
                            {{ $land->company_land_name }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
            <tr>
                {{-- <th class="table-secondary"></th> --}}
                @php $col_count = 0; @endphp
                @foreach ($month_sel as $month_num => $month)
                    @foreach ($company_land as $key => $land)
                        <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}"
                            id='collect_qty'>
                            Collect
                        </th>
                        <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}"
                            id='do_qty'>
                            D.O
                        </th>
                        <th class="{{ $month_num % 2 == 0 ? 'bg-white' : 'bg-grey' }}"
                            id='do_qty'>
                            Diffrential(%)
                        </th>
                        @php $col_count++; @endphp
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php

                $col_count = 0;
                foreach ($products as $product) {
                    echo '<tr><td class = bg-grey >' . $product->product_name . '-' . $product->setting_product_size_name . '</td>';

                    foreach ($month_sel as $month_num => $month_name) {
                        foreach ($company_land as $key => $land) {
                            $col_temp = isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) ? $collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] : 0;
                            $do_temp = isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) ? $delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] : 0;

                            if (isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>' . ($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] > 0 ? number_format($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name], 2) : '-') . '</td>';
                            } else {
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>-</td>';
                            }

                            if (isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])) {
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>' . ($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name] > 0 ? number_format($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name], 2) : '-') . '</td>';
                            } else {
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>-</td>';
                            }

                            if(isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                $collect_det = $collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                $do_det = $delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                $dif = ($collect_det - $do_det) / $collect_det * 100;
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                            }elseif(isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && !isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                $collect_det = $collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                $empty = 0;
                                $dif = ($collect_det - $empty) / $collect_det * 100;
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                            }elseif(!isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                $empty = 0;
                                $do_det = $delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name];
                                $dif = ($empty - $do_det) / $do_det * 100;
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>' . number_format($dif, 2) . '%</td>';
                            }elseif(!isset($collect_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name]) && !isset($delivery_order_item_details[$product->product_name . '-' . $product->setting_product_size_name][$month_num][$land->company_land_name])){
                                echo '<td class = ' . ($month_num % 2 == 0 ? '"' : '"bg-grey') . ($col_temp != $do_temp ? ' bg-red"' : '"') . '>-</td>';
                            }

                            $col_count++;
                        }
                    }
                    echo '</tr>';
                }
            @endphp
        </tbody>
    </table>
</div>
