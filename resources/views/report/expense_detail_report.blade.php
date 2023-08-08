@extends('layouts.master-without-nav')
@section('title')
    Expense Report
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
        table {
            text-align: center;
        }

        .bg-grey {
            background: #e4e4e4;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18"><span class="mr-2 ">Expense Report - {{ $month }}/{{@$search['year']}} </span></h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="collect_do_table">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;" class="table-secondary" ></th>
                                    @php $col_count = 0; @endphp
                                    @foreach ($month_sel as $month_num => $month_name)
                                      @if($month_num == $month)
                                        <th style="min-width: 220px; text-align: center;background-color: #ffffff;"
                                            colspan="{{ (count($expense_w_type)+ 1) * 5 }}">
                                            {{ $month_name }}
                                        </th>
                                        @endif
                                        @php $col_count++; @endphp
                                    @endforeach
                                </tr>
                                <tr>
                                    <th style="min-width: 220px;" class="table-secondary">Warehouse / Expense Type</th>
                                    @php $col_count = 0; @endphp
                                    @foreach($expense_w_type as $ewtkey => $ewt)
                                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                      {{ $ewt->setting_expense_name }}
                                    </th>
                                    @php $col_count++; @endphp
                                    @endforeach
                                    <th style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                      Total
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                              @foreach($company_sel as $ckey => $company)
                                <tr>
                                  <td style="text-align: left; background-color: #eeee;" colspan="{{ (count($month_sel)+ 1) * 5 }}">
                                    {{$company->company_name}}
                                  </td>
                                </tr>
                                @foreach($warehouse_sel as $wkey => $warehouse)
                                  @php
                                    $do_exp_warehouse_total = 0;
                                   $col_count = 0;
                                  @endphp
                                  @if($company->company_id == $warehouse->company_id)
                                  <tr>
                                    <td style="text-align: left; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                      {{$warehouse->warehouse_name}}
                                    </td>

                                    @foreach($expense_w_type as $ewtkey => $ewt)
                                      @if(isset($records[$warehouse->company_id][$warehouse->warehouse_id][$ewt->setting_expense_id][$ewt->setting_expense_type->setting_expense_type_id][$month]['expense_sum'])
                                          && $records[$warehouse->company_id][$warehouse->warehouse_id][$ewt->setting_expense_id][$ewt->setting_expense_type->setting_expense_type_id][$month]['expense_sum'] > 0)
                                          <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                              RM {{ $records[$warehouse->company_id][$warehouse->warehouse_id][$ewt->setting_expense_id][$ewt->setting_expense_type->setting_expense_type_id][$month]['expense_sum'] }}
                                          </td>
                                          @php $do_exp_warehouse_total
                                           +=
                                           $records[$warehouse->company_id][$warehouse->warehouse_id][$ewt->setting_expense_id][$ewt->setting_expense_type->setting_expense_type_id][$month]['expense_sum'];
                                           @endphp
                                          @else
                                          <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>

                                          @endif
                                          @php $col_count++; @endphp
                                    @endforeach
                                      @if($do_exp_warehouse_total > 0)
                                      <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">RM {{  number_format($do_exp_warehouse_total, 2) }}</td>
                                      @else
                                      <td style="text-align: center; {{ $col_count % 2 == 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                      @endif
                                  </tr>
                                  @endif
                                @endforeach
                              @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form method="POST">
                        @csrf
                        <div class="col-md-12 text-right">
                            <!-- <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                name="submit" value="export">
                                <i class="fas fa-download mr-1"></i> Export
                            </button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
