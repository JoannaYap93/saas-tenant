@extends('layouts.master')
@section('title')
    Differentiate Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        .bg-grey {
            background: #e4e4e4;
        }

        .bg-red {
            background: #f46a6a;
            color: #ffffff
        }

        table {
            text-align: center;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Differentiate Report</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Differentiate Report</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                <div class="row">
                                  <div class="col-md-2">
                                      <div class="form-group">
                                          <label for="">Month-Year</label>
                                          <div class="input-daterange input-group" id="datepicker7"
                                              data-date-format="mm-yyyy" data-date-autoclose="true"
                                              data-provide="datepicker" data-date-container="#datepicker7">
                                              <input type="text" style="width: 75px" class="form-control"
                                                  name="month_year" placeholder="Start Month"
                                                  value="{{ @$search['month_year'] }}" id="month" autocomplete="off">
                                          </div>
                                      </div>
                                  </div>
                                    <!-- <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Year</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control"
                                                    name="year" placeholder="Start Year"
                                                    value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div> -->
                                    <x-option_filter :companySel="$company_sel" :customerSel="$customer_sel" :search="$search" />
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="diff_report_table">
                            <thead style="border:1px solid #eee">
                                <tr>
                                    <td style="border-right: 1px solid #eee"></td>
                                    @php
                                    $current_date = now();
                                    $month = substr(@$search['month_year'], 0, 2) ?? $current_date->month;
                                    $year = substr(@$search['month_year'], 3, 6) ?? $current_date->year;
                                    $dt = cal_days_in_month(0,$month, $year);
                                    @endphp
                                    @for ($d = 1; $d <= $dt; $d++)
                                        <!-- echo '<td colspan="3" style="border-right: 1px solid #eee">' . $d . '/' . $month . '/' . $year . '</td>'; -->
                                        <th colspan='3' style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">{{$d . '/' . $month . '/' . $year}}</th>
                                        @if($d >= $dt)
                                        <th colspan='3' style="text-align: center; {{ $d % 1 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Total</th>
                                        @endif
                                    @endfor
                                </tr>
                                <tr>
                                    <td style="border-right: 1px solid #eee"></td>
                                    @php
                                    $current_date = now();
                                    $month = substr(@$search['month_year'], 0, 2) ?? $current_date->month;
                                    $year = substr(@$search['month_year'], 3, 6) ?? $current_date->year;
                                    $dt = cal_days_in_month(0,$month, $year);
                                    @endphp
                                    @for ($d = 1; $d <= $dt; $d++)
                                      <th style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Collect</th>
                                      <th style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Delivery Order(KG)</th>
                                      <th style="text-align: center; {{ $d % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Differential(%)</th>
                                        @if($d >= $dt)
                                        <th style="text-align: center; {{ $d % 1 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Collect</th>
                                        <th style="text-align: center; {{ $d % 1 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Delivery Order(KG)</th>
                                        <th style="text-align: center; {{ $d % 1 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">Differential(%)</th>
                                        @endif
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 0;
                                    $set_land['land'] = [];
                                    // dd($land);
                                @endphp
                                @for ($f = 0; $f < count($farm); $f++)
                                    @for ($c = 0; $c < count($category); $c++)
                                        @php
                                            $count_product = 0;
                                            $last_count = 0;
                                        @endphp
                                        @for ($l = 0; $l < count($land); $l++)
                                            @for ($a = 0; $a < count($product); $a++)
                                                {{-- @if ($product[$a]['id'] == 10)
                                                    {{ var_dump($product[$a]) }}
                                                @endif --}}
                                                @foreach ($size as $sk => $s)
                                                    @if (isset($product[$a]['size'][$sk]))
                                                        @php
                                                        $status = false;
                                                        if(@$search['company_id'] && @$search['company_land_id'] == null){
                                                          if($land[$l]['farm'] == $farm[$f]['id'] && $land[$l]['category'] == $category[$c]['id'] && $land[$l]['product'] == $product[$a]['id'] && $land[$l]['company'] == @$search['company_id']){
                                                            $status = true;
                                                          }
                                                        }
                                                        else if(@$search['company_id'] && @$search['company_land_id']){
                                                          if($land[$l]['farm'] == $farm[$f]['id'] && $land[$l]['category'] == $category[$c]['id'] && $land[$l]['product'] == $product[$a]['id'] && $land[$l]['company'] == @$search['company_id'] && $land[$l]['land'] == @$search['company_land_id']){
                                                            $status = true;
                                                          }
                                                        }else{
                                                          if($land[$l]['farm'] == $farm[$f]['id'] && $land[$l]['category'] == $category[$c]['id'] && $land[$l]['product'] == $product[$a]['id']){
                                                            $status = true;
                                                          }
                                                        }
                                                        @endphp
                                                        @if ($status)
                                                            @php
                                                                if (isset($set_land['land'][$land[$l]['land']]) == false) {
                                                                    $set_land['land'][$land[$l]['land']] = $counter;
                                                                }
                                                                $current_date = now();
                                                                $month = substr(@$search['month_year'], 0, 2) ?? $current_date->month;
                                                                $year = substr(@$search['month_year'], 3, 6) ?? $current_date->year;
                                                                $day = cal_days_in_month(0, $month, $year);
                                                            @endphp
                                                            @if ($set_land['land'][$land[$l]['land']] == $counter)
                                                                <tr>
                                                                    <td style="background-color: #eee; text-align: left;"
                                                                        colspan="{{ $day * 3 + 4 }}">
                                                                        {{-- {{ $farm[$f]['name'] }} -
                                                                        {{ $category[$c]['name'] }} --}}
                                                                        {{ $land[$l]['land_name'] }}({{$land[$l]['company']}})
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                @php
                                                                echo '<td style="border-left: 1px solid #eee">' . $product[$a]['name'] . '-' . $s . '</td>';
                                                                $td = '';
                                                                for ($i = 1; $i <= $day; $i++) {
                                                                    $id = $i . '-' . $farm[$f]['id'] . '-' . $category[$c]['id'] . '-' . $product[$a]['id'] . '-' . $sk;
                                                                    // $td .= '<td>'.$id.'</td>';

                                                                    if (isset($records['co'][$id])) {
                                                                      $formated_co = number_format($records['co'][$id]->total_collect, 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_co . '</td>';
                                                                        $total['co'][$land[$l]['land']][$i] = isset($total['co'][$land[$l]['land']][$i]) ? $total['co'][$land[$l]['land']][$i] + $records['co'][$id]->total_collect :  $records['co'][$id]->total_collect;
                                                                        $total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk] = isset($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk]) ? $total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk] + $records['co'][$id]->total_collect :  $records['co'][$id]->total_collect;
                                                                        $total_3['co'][$land[$l]['land']] = isset($total_3['co'][$land[$l]['land']]) ? $total_3['co'][$land[$l]['land']] + $records['co'][$id]->total_collect : $records['co'][$id]->total_collect;
                                                                    } else {
                                                                      $td .= '<td style=';
                                                                      $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                      $td .=  '-</td>';
                                                                    }
                                                                    if (isset($records['do'][$id])) {
                                                                      $formated_do = number_format($records['do'][$id]->total_order, 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_do . '</td>';
                                                                        $total['do'][$land[$l]['land']][$i] = isset($total['do'][$land[$l]['land']][$i]) ? $total['do'][$land[$l]['land']][$i] + $records['do'][$id]->total_order :  $records['do'][$id]->total_order;
                                                                        $total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk] = isset($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk]) ? $total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk] + $records['do'][$id]->total_order :  $records['do'][$id]->total_order;
                                                                        $total_3['do'][$land[$l]['land']] = isset($total_3['do'][$land[$l]['land']]) ? $total_3['do'][$land[$l]['land']] + $records['do'][$id]->total_order : $records['do'][$id]->total_order;
                                                                        // $td .= '<td>' . $records['do'][$i . '-' . $farm[$f]['id'] . '-' . $category[$c]['id'] . '-' . $product[$a]['id'] . '-' . $s]->total_order . '</td>';
                                                                    } else {
                                                                      $td .= '<td style=';
                                                                      $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                      $td .=  '-</td>';
                                                                    }
                                                                    if (isset($records['co'][$id]) && isset($records['do'][$id])) {
                                                                        $dif = (($records['co'][$id]->total_collect - $records['do'][$id]->total_order) / $records['co'][$id]->total_collect) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_dif . '%</td>';
                                                                    } elseif (isset($records['co'][$id]) && !isset($records['do'][$id])) {
                                                                        $empty = 0;
                                                                        $dif = (($records['co'][$id]->total_collect - $empty) / $records['co'][$id]->total_collect) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_dif . '%</td>';
                                                                    } elseif (!isset($records['co'][$id]) && isset($records['do'][$id])) {
                                                                        $empty = 0;
                                                                        $dif = (($empty - $records['do'][$id]->total_order) / $records['do'][$id]->total_order) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_dif . '%</td>';
                                                                    } else {
                                                                      $td .= '<td style=';
                                                                      $td .=  $i % 2 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                      $td .=  '-</td>';
                                                                    }

                                                                    if($i >= $day){
                                                                      if (isset($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk])) {
                                                                        $formated_total_co_2 = number_format($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk], 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_total_co_2 . '</td>';
                                                                      } else {
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  '-</td>';
                                                                      }
                                                                      if (isset($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk])) {
                                                                        $formated_total_do_2 = number_format($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk], 2, '.', '');
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  $formated_total_do_2 . '</td>';
                                                                      } else {
                                                                        $td .= '<td style=';
                                                                        $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                        $td .=  '-</td>';
                                                                      }
                                                                      if (isset($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk]) && isset($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk])) {
                                                                          $dif = (($formated_total_co_2 - $formated_total_do_2) / $formated_total_co_2) * 100;
                                                                          $formated_dif = number_format($dif, 2, '.', '');
                                                                          $td .= '<td style=';
                                                                          $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                          $td .=  $formated_dif . '%</td>';
                                                                      } elseif (isset($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk]) && !isset($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk])) {
                                                                          $empty = 0;
                                                                          $dif = (($formated_total_co_2 - $empty) / $formated_total_co_2) * 100;
                                                                          $formated_dif = number_format($dif, 2, '.', '');
                                                                          $td .= '<td style=';
                                                                          $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                          $td .=  $formated_dif . '%</td>';
                                                                      } elseif (!isset($total_2['co'][$land[$l]['land']][$product[$a]['id'].'-'.$sk]) && isset($total_2['do'][$land[$l]['land']][$product[$a]['id'].'-'.$sk])) {
                                                                          $empty = 0;
                                                                          $dif = (($empty - $formated_total_do_2) / $formated_total_do_2) * 100;
                                                                          $formated_dif = number_format($dif, 2, '.', '');
                                                                          $td .= '<td style=';
                                                                          $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                          $td .=  $formated_dif . '%</td>';
                                                                      } else {
                                                                          $td .= '<td style=';
                                                                          $td .=  $i % 1 == 0 ? '"text-align: center; background-color: #ffffff;" >' : '"text-align: center; background-color: #e4e4e4;">';
                                                                          $td .=  '-</td>';
                                                                      }
                                                                    }
                                                                }

                                                                echo $td;

                                                                $count_product++;
                                                            @endphp
                                                            </tr>
                                                        @endif
                                                        @if ($count_product == ($count_product >= $land[$l]['count'] ? $last_count + $land[$l]['count'] : $land[$l]['count']))
                                                            <tr>
                                                                <td style="background-color: #343a40; color: white">Total</td>
                                                                @php
                                                                $td ='';
                                                                for ($d = 1; $d <= $dt; $d++) {
                                                                  if(isset($total['co'][$land[$l]['land']][$d])){
                                                                    $formated_total_co = number_format($total['co'][$land[$l]['land']][$d], 2, '.', '');
                                                                    $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_total_co . '</td>';
                                                                  }else{
                                                                    $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                  }
                                                                    if(isset($total['do'][$land[$l]['land']][$d])){
                                                                      $formated_total_do = number_format($total['do'][$land[$l]['land']][$d], 2, '.', '');
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_total_do . '</td>';
                                                                  }else{
                                                                    $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                  }
                                                                  if (isset($total['co'][$land[$l]['land']][$d]) && isset($total['do'][$land[$l]['land']][$d])) {
                                                                      $dif = (($total['co'][$land[$l]['land']][$d] - $total['do'][$land[$l]['land']][$d]) / $total['co'][$land[$l]['land']][$d]) * 100;
                                                                      $formated_dif = number_format($dif, 2, '.', '');
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                  } elseif (isset($total['co'][$land[$l]['land']][$d]) && !isset($total['do'][$land[$l]['land']][$d])) {
                                                                      $empty = 0;
                                                                      $dif = (($total['co'][$land[$l]['land']][$d] - $empty) / $total['co'][$land[$l]['land']][$d]) * 100;
                                                                      $formated_dif = number_format($dif, 2, '.', '');
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                  } elseif (!isset($total['co'][$land[$l]['land']][$d]) && isset($total['do'][$land[$l]['land']][$d])) {
                                                                      $empty = 0;
                                                                      $dif = (($empty - $total['do'][$land[$l]['land']][$d]) / $total['do'][$land[$l]['land']][$d]) * 100;
                                                                      $formated_dif = number_format($dif, 2, '.', '');
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                  } else {
                                                                    $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                  }
                                                                  if($d >= $dt){
                                                                    if(isset($total_3['co'][$land[$l]['land']])){
                                                                      $formated_total_co = number_format($total_3['co'][$land[$l]['land']], 2, '.', '');
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_total_co . '</td>';
                                                                    }else{
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                    }
                                                                      if(isset($total_3['do'][$land[$l]['land']])){
                                                                        $formated_total_do = number_format($total_3['do'][$land[$l]['land']], 2, '.', '');
                                                                        $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_total_do . '</td>';
                                                                    }else{
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                    }
                                                                    if (isset($total_3['co'][$land[$l]['land']]) && isset($total_3['do'][$land[$l]['land']])) {
                                                                        $dif = (($total_3['co'][$land[$l]['land']] - $total_3['do'][$land[$l]['land']]) / $total_3['co'][$land[$l]['land']]) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                    } elseif (isset($total_3['co'][$land[$l]['land']]) && !isset($total_3['do'][$land[$l]['land']])) {
                                                                        $empty = 0;
                                                                        $dif = (($total_3['co'][$land[$l]['land']] - $empty) / $total_3['co'][$land[$l]['land']]) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                    } elseif (!isset($total_3['co'][$land[$l]['land']]) && isset($total_3['do'][$land[$l]['land']])) {
                                                                        $empty = 0;
                                                                        $dif = (($empty - $total_3['do'][$land[$l]['land']]) / $total_3['do'][$land[$l]['land']]) * 100;
                                                                        $formated_dif = number_format($dif, 2, '.', '');
                                                                        $td .=  '<td style="text-align: center; background-color: #343a40; color: white">'.$formated_dif . '%</td>';
                                                                    } else {
                                                                      $td .=  '<td style="text-align: center; background-color: #343a40; color: white">-</td>';
                                                                    }
                                                                  }
                                                                }
                                                                echo $td;
                                                                @endphp
                                                            </tr>
                                                            @php
                                                                $last_count = $count_product;
                                                            @endphp
                                                        @endif
                                                        @php

                                                        @endphp
                                                    @endif
                                                    @php
                                                        $counter++;
                                                    @endphp
                                                @endforeach
                                            @endfor

                                        @endfor

                                    @endfor
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ global_asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <script>
        $(document).ready(function(e) {

            $("#diff_report_table").parent().freezeTable({
                'freezeColumn': true,
                'shadow': true
            });
        });

        // $("#datepicker6").datepicker({
        //     format: "yyyy",
        //     viewMode: "years",
        //     minViewMode: "years",
        //     autoclose: true //to close picker once year is selected
        // });
        $("#datepicker7").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once year is selected
        });

        @if (@$search['company_id'] != null)
            get_land_user('{{ $search['company_id'] }}');
        @else
            get_land_user('{{ auth()->user()->company_id }}');
        @endif

        $('#company_id').on('change', function() {
            let id = $(this).val();
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            $('#company_land_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            land += '<option value="' + element.company_land_id + '">' + element
                                .company_land_name + '</option>';
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                '</option>';
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                }
            });
        });

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let sland = "{{ @$search['company_land_id'] ?? null }}";
            let suser = "{{ @$search['user_id'] ?? null }}";
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            if (sland != null && element.company_land_id == sland) {
                                land += '<option value="' + element.company_land_id + '" selected>' +
                                    element
                                    .company_land_name + '</option>';
                            } else {
                                land += '<option value="' + element.company_land_id + '">' + element
                                    .company_land_name + '</option>';
                            }
                        });
                        $('#company_land_id').html(land);
                    } else {
                        $('#company_land_id').html('<option value="">No Land</option>');
                    }
                    if (e.user.length > 0) {
                        e.user.forEach(u => {
                            if (suser != null && u.user_id == suser) {
                                user += '<option value="' + u.user_id + '" selected>' + u
                                    .user_fullname +
                                    '</option>';
                            } else {
                                user += '<option value="' + u.user_id + '">' + u.user_fullname +
                                    '</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No User</option>');
                    }
                }
            });
        }
    </script>
@endsection
