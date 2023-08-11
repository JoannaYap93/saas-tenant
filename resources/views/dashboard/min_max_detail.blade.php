@extends('layouts.master-without-nav')
@section('title')
    Min Max Detail
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
                    <h4>{{$product}}</h4>
                    @if ($data)
                        @php
                            $col = 0;
                        @endphp
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach ($data['company'] as $c => $company_name)
                                        <th style="{{ $col % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">{{ $company_name }}</th>
                                        @php
                                            $col ++;
                                        @endphp
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['company_land'] as $cl => $company_land_name)
                                    @php
                                        $col = 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $company_land_name }}
                                        </td>
                                        @foreach ($data['company'] as $c => $company)
                                            @if(@$data[$c][$cl])
                                                @if(@$data[$c][$cl]->min)
                                                    @if($data[$c][$cl]->min == $data[$c][$cl]->max)
                                                        @php
                                                            $link = route('invoice_listing_id', ['tenant' => tenant('id'), 'id' => $data[$c][$cl]->invoice_id]);
                                                        @endphp
                                                        <td style="{{ $col % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                                            <a href="{{$link}}" target="_blank"> {{ $data[$c][$cl]->min }} </a>
                                                        </td>
                                                    @else
                                                        @php
                                                            $link_min = route('invoice_by_price', ['tenant' => tenant('id'), 'price' => $data[$c][$cl]->min, 'product' => $product, 'company_land_name' => $company_land_name, 'date' => $search_date]);
                                                            $link_max = route('invoice_by_price', ['tenant' => tenant('id'), 'price' => $data[$c][$cl]->max, 'product' => $product, 'company_land_name' => $company_land_name, 'date' => $search_date]);
                                                        @endphp
                                                        <td style="{{ $col % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">
                                                            <a href="{{$link_min}}" target="_blank"> {{ $data[$c][$cl]->min }}</a> -  <a href="{{$link_max}}" target="_blank">{{ $data[$c][$cl]->max }} </a>
                                                        </td>
                                                    @endif
                                                @else
                                                    <td style="{{ $col % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                                @endif
                                            @else
                                                <td style="{{ $col % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }}">-</td>
                                            @endif
                                            @php
                                                $col++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    @else
                        No records found!
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
