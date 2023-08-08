<div class="table-responsive">
    @if (!@$component)
        <p><b>Daily Collect Report ({{ $search['start_date'] }} - {{ $search['end_date'] }})</b></p>
    @endif
    <table class="table table-bordered" id="collect_do_table">
        <thead>
            <tr>
                <th style="min-width: 220px; border:1px solid #eee" class="table-secondary" rowspan="2">Product</th>
                @foreach ($dateRange as $d => $date)
                    @php $format_date = $date->format('d/m'); @endphp
                    <th style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee"
                        colspan={{ count($company) }}>
                        {{ $format_date }}
                    </th>
                @endforeach
                <th style="text-align: center; background-color:#fffbaf; color:#000000" rowspan="2">TOTAL</th>
            </tr>
            <tr>
                @php
                    $col_count = 0;
                @endphp
                @foreach ($dateRange as $d => $date)
                    @foreach ($company as $c_key => $c)
                        <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                            {{ $c->company_name }}
                        </th>
                    @endforeach
                    @php $col_count++; @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $col_count = 0;
                $total_row = $total_col = array();
                $final_total = 0;
            @endphp
            @foreach ($products as $product)
                @if(isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name]))
                    <tr>
                        <td style="min-width: 220px; border:1px solid #eee" class="table-secondary">
                            <b>{{ $product->product_name . '-' . $product->setting_product_size_name }}</b></td>
                        @foreach ($dateRange as $d => $date)
                            @foreach ($company as $c)

                                @php
                                    $format_date = $date->format('Y-m-d');
                                    $col_temp = isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']) ? $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'] : 0;
                                @endphp
                                @if (isset($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col']))
                                    <td
                                        style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                        {{ $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'] > 0? number_format($collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'],2, '.', ''): '-' }}
                                    </td>

                                    @php
                                        if(@$total_col[$format_date][$c->company_id]){
                                                $total_col[$format_date][$c->company_id]['col'] += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                        }else{
                                                $total_col[$format_date][$c->company_id]['col'] = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                        }

                                        if(@$total_row[$product->product_name . '-' . $product->setting_product_size_name]){
                                                $total_row[$product->product_name . '-' . $product->setting_product_size_name]['col'] += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                        }else{
                                                $total_row[$product->product_name . '-' . $product->setting_product_size_name]['col'] = $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                        }

                                        $final_total += $collectDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]['col'];
                                    @endphp
                                @else
                                    <td
                                        style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                        -</td>
                                @endif
                            @endforeach
                        @endforeach
                        <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$total_row[$product->product_name . '-' . $product->setting_product_size_name]['col'],2, '.', '')}}</b></td>
                    </tr>
                    @php $col_count++; @endphp
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>TOTAL</b></td>
                @foreach ($dateRange as $d => $date)
                    @php
                        $format_date = $date->format('Y-m-d');
                    @endphp
                    @foreach ( $company as $c )
                        <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$total_col[$format_date][$c->company_id]['col'],2, '.', '')}}</b></td>
                    @endforeach
                @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$final_total,2, '.', '')}}</b></td>
             </tr>
        </tfoot>
    </table>
</div>
