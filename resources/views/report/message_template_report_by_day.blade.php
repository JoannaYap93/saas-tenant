@extends('layouts.master-without-nav')
@section('title')
    {{$page_title}}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0 font-size-18"><span class="mr-2 "> {{$page_title}}</span></h4>
                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item">
                                                    <a href="javascript: void(0);"> {{$page_title}}</a>
                                                </li>
                                                <li class="breadcrumb-item active">Reporting</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @foreach ($users as $user)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="3">{{$user->user_fullname}} ({{$user->user_email}})</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @foreach ($message_templates as $template)
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3">{{$template->message_template_name}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Details</th>
                                                        <th>Customer Details</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($records->where('message_template_id', $template->message_template_id) as $rows)

                                                        <tr>
                                                            @if($rows->message_log_slug == 'invoice-listing')

                                                                @if ($rows->invoice)
                                                                    @php
                                                                        $encryption = md5($rows->invoice->invoice_id . env('ENCRYPTION_KEY'));
                                                                    @endphp
                                                                    <td>
                                                                        <a class="btn btn-outline-success btn-sm" target="_blank"
                                                                            {{-- href="{{ route('view_invoice_pdf', [$rows->invoice->invoice_id, $encryption]) }}"> --}}
                                                                            href="{{ env('GRAPHQL_API').'/view_invoice/'.$rows->invoice->invoice_id.'/'.$encryption }}">
                                                                            {{$rows->invoice->invoice_no}} ({{$rows->result}})
                                                                        </a>
                                                                    </td>
                                                                @else
                                                                    <td>-</td>
                                                                @endif
                                                            @elseif ($rows->message_log_slug == 'do-listing')
                                                                @if($rows->delivery_order)
                                                                    <td>
                                                                        <a class="btn btn-outline-success btn-sm" target="_blank"
                                                                            href="{{ route('get_invoice_from_do', ['tenant' => tenant('id'), 'id' => $rows->delivery_order->delivery_order_id]) }}">
                                                                            {{$rows->delivery_order->delivery_order_no}} ({{$rows->result}})
                                                                        </a>
                                                                    </td>
                                                                @else
                                                                    <td>-</td>
                                                                @endif
                                                            @else
                                                                <td>-</td>
                                                            @endif
                                                            @if ($rows->customer)
                                                                <td>{{$rows->customer->customer_name}}<br>
                                                                    {{$rows->customer->customer_mobile_no}}<br>
                                                                    {{$rows->customer->customer_email}}
                                                                </td>
                                                            @else
                                                                <td>-</td>
                                                            @endif
                                                            @if ($rows->dates)
                                                                @php
                                                                    $dates = explode(",",$rows->dates)
                                                                @endphp
                                                                <td>
                                                                @foreach ($dates as $val)
                                                                    {{$val}}<br>
                                                                @endforeach
                                                                </td>
                                                            @else
                                                                <td>-</td>
                                                            @endif
                                                            </tr>

                                                    @endforeach


                                                </tbody>
                                            </table>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function(e) {
        });
    </script>
@endsection
