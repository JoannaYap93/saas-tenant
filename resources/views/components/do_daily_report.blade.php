<div class="table-responsive">
    @if (!@$component)
        <p><b>Daily D.O. Report ({{ $search['start_date'] }} - {{ $search['end_date'] }})</b></p>
    @endif
    <table class="table table-bordered" id="do_table">
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
                    $total_row = $total_col = array();
                    $final_total = 0;
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
            @php $col_count = 0; @endphp
            @foreach ($products as $product)
            @if(isset($doDetails[$product->product_name . '-' . $product->setting_product_size_name]))
                <tr>
                    <td style="min-width: 220px; border:1px solid #eee" class="table-secondary">
                        <b>{{ $product->product_name . '-' . $product->setting_product_size_name }}</b></td>
                    @foreach ($dateRange as $d => $date)
                        @foreach ($company as $c)

                            @php
                                $format_date = $date->format('Y-m-d');
                                $col_temp = isset($doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]) ? $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id] : 0;
                            @endphp
                            @if (isset($doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id]))
                                <td
                                    style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                    {{ $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id] > 0? number_format($doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id],2, '.', ''): '-' }}
                                </td>
                                @php
                                        if(@$total_col[$format_date][$c->company_id]){
                                                $total_col[$format_date][$c->company_id] += $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                        }else{
                                                $total_col[$format_date][$c->company_id] = $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                        }

                                        if(@$total_row[$product->product_name . '-' . $product->setting_product_size_name]){
                                                $total_row[$product->product_name . '-' . $product->setting_product_size_name] += $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                        }else{
                                                $total_row[$product->product_name . '-' . $product->setting_product_size_name] = $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                        }

                                        $final_total += $doDetails[$product->product_name . '-' . $product->setting_product_size_name][$format_date][$c->company_id];
                                @endphp
                            @else
                                <td
                                    style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">
                                    -</td>
                            @endif

                        @endforeach
                    @endforeach
                    <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$total_row[$product->product_name . '-' . $product->setting_product_size_name],2, '.', '')}}</b></td>
                </tr>
                @php $col_count++; @endphp
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center; background-color:#fffbaf; color:#000000" >TOTAL</th>
                @foreach ($dateRange as $d => $date)
                    @php
                        $format_date = $date->format('Y-m-d');
                    @endphp
                    @foreach ( $company as $c )
                        <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$total_col[$format_date][$c->company_id],2, '.', '')}}</b></td>
                    @endforeach
                @endforeach
                <td style="text-align: center; background-color:#fffbaf; color:#000000"><b>{{number_format(@$final_total,2, '.', '')}}</b></td>
            </tr>
        </tfoot>
    </table>
</div>
