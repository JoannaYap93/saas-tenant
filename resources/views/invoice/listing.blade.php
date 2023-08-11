@extends('layouts.master')

@section('title')
    Invoice Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
        .log {
            cursor: pointer;
        }

        .button_status {
            font-size: 15px;
            font-weight: 600;
            padding: 1.5em !important;
            border: none;
        }

        .button_status.active {
            color: #536ae9 !important;
            border: none !important;
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
            border-color: transparent !important;
            border-bottom: 2 px solid #536ae9 !important;
        }

        .transaction-btn {
            background-color: #fff;
        }

        .check_invoice {
            background-color: rgb(0, 211, 106);
            animation: success 3s linear infinite;
        }

        @keyframes success {
            0% {
                box-shadow: 0 0 0 0 rgb(0, 211, 106, 0.7), 0 0 0 0 rgb(0, 211, 106, 0.7);
            }

            40% {
                box-shadow: 0 0 0 10px rgb(255, 109, 74, 0),
                    0 0 0 0 rgb(0, 211, 106, 0.7);
            }

            80% {
                box-shadow: 0 0 0 10px rgb(255, 109, 74, 0),
                    0 0 0 8px rgb(255, 109, 74, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgb(255, 109, 74, 0), 0 0 0 8px rgb(255, 109, 74, 0);
            }
        }

    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Invoice Listing</h4>
                    {{-- <a href="{{ route('do_add', ['tenant' => tenant('id')]) }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> Add New</a> --}}
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Invoice</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ $submit }}">
                <div class="card">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <div class="col-12">
                            <div class="row">
                                <button type="button" value=" "
                                    class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if (!@$search['invoice_status_id']) active @endif ">
                                    <span class="transaction-font">All</span>
                                    <span
                                        class="transaction-font">({{ @$count_status['default'] ? $count_status['default'] : 0 }})</span>
                                </button>

                                @foreach ($iv_status as $key => $val)
                                    @if($val->invoice_status_id != 3)
                                        <button type="button" value="{{ $val->invoice_status_id }}"
                                            class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if ($val->invoice_status_id == @$search['invoice_status_id']) active @endif ">
                                            <span class="transaction-font">{{ $val->invoice_status_name }}</span>
                                            <span
                                                class="transaction-font">({{ @$count_status[$val->invoice_status_id] ? $count_status[$val->invoice_status_id] : 0 }})</span>
                                        </button>
                                    @endif
                                @endforeach
                                <input id="hidden_order_value" type="hidden" name="invoice_status_id"
                                    class="hidden_value_id" value="{{ @$search['invoice_status_id'] }}" />
                                <input id="hidden_previous_transaction_value" type="hidden" name="hidden_invoice_status_id"
                                    value="{{ @$search['invoice_status_id'] }}" />
                            </div>
                        </div>
                    </ul>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Invoice Created</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="iv_created_from"
                                                    placeholder="Start Date" value="{{ @$search['iv_created_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="iv_created_to"
                                                    placeholder="End Date" value="{{ @$search['iv_created_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Invoice Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="iv_from"
                                                    placeholder="Start Date" value="{{ @$search['iv_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="iv_to"
                                                    placeholder="End Date" value="{{ @$search['iv_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Is Approved</label>
                                            {!! Form::select('is_approved', $is_approved, @$search['is_approved'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <label for="">Land: </label>
                                        <select name="company_land_id" class="form-control" id="company_land_id">

                                        </select>
                                    </div>
                                    @if (count($product) > 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Product Category</label>
                                                {!! Form::select('product_category_id', $product_category_sel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_product">
                                            <div class="form-group">
                                                <label for="">Product</label>
                                                <select name="product_id" id="product" class="form-control">
                                                    @if (@$search['product_id'])
                                                        <option></option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_size">
                                            <div class="form-group">
                                                <label for="">Grade</label>
                                                <select name="product_size_id" id="size_id" class="form-control">
                                                    @if (@$search['product_size_id'])
                                                        <option></option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">User</label>
                                            {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Customer Category</label>
                                            {!! Form::select('customer_category_id', $customer_category_sel, @$search['customer_category_id'], ['class' => 'form-control', 'id' => 'customer_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Customer</label>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                @if (@$search['customer_id'])
                                                    <option></option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2" name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2" name="submit" value="reset">
                                        <i class="fas fa-times mr-1"></i> Reset
                                    </button>

                                        <button type="submit" class="btn btn-success waves-effect waves-light mr-2" name="submit" value="export" id="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button>

                                        @can('invoice_manage')
                                            <a href="{{ route('invoice_import', ['tenant' => tenant('id')]) }}" class="btn btn-success waves-effect waves-light mr-2">
                                                <i class="fas fa-file-import mr-1"></i>Import
                                            </a>
                                            <a href="{{ URL::asset('assets/sample/sample-import-invoice.xlsx') }}" target="_blank" class="btn btn-info waves-effect waves-light mr-2">
                                                <i class="fas fa-download mr-1"></i>Download Sample Import
                                            </a>
                                            <a href="{{ route('invoice_export_product_list', ['tenant' => tenant('id')]) }}" class="btn btn-info waves-effect waves-light mr-2">
                                                <i class="fas fa-file-download mr-1"></i>Export Product Lists
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <table>
                            <tbody>
                                <tr>
                                    <td class=" p-2  m-0" style="min-width:50px; border: 1px solid #e4e4e4;"><b>Total
                                            Invoice Amount</b></td>
                                    @foreach ($iv_status_2 as $status)
                                        <td class=" p-2  m-0" style="min-width:50px; border: 1px solid #e4e4e4;">
                                            <span>{{ $status->invoice_status_name }}: <span>
                                                    <span
                                                        @if ($status->invoice_status_id == 1 || $status->invoice_status_id == 5) style="color: #f1b44c;"
                                            @elseif($status->invoice_status_id == 3 || $status->invoice_status_id == 4) style="color: #f46a6a;"
                                            @else style="color: #34c38f;" @endif>
                                                        RM
                                                        {{ isset($invoice_total_amount[$status->invoice_status_id])? number_format($invoice_total_amount[$status->invoice_status_id], 2): '0.00' }}
                                                    </span>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <!-- <th>#</th> -->
                                <th>Invoice</th>
                                <th>Billing Info</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @foreach ($records as $key => $invoice)
                                        <tr>
                                            <!-- <td>{{ $key + 1 }}</td> -->
                                            <td><b>{{ $invoice->invoice_no }}</b><br>
                                                {{ $invoice->customer->customer_company_name }} - <span
                                                    style="font-style: italic;">{{ $invoice->customer->customer_category->customer_category_name }}</span><br>
                                                @if ($invoice->customer->customer_category->customer_category_slug == 'cash')
                                                    @if (@$invoice->delivery_order)
                                                        {{-- {{ @$invoice->delivery_order->customer_pic->where('customer_pic_ic', $invoice->delivery_order->customer_ic)->first()->customer_pic_mobile_no ?? '-' }}<br> --}}
                                                        {{@$invoice->delivery_order->customer_mobile_no}}<br>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    {{ $invoice->customer->customer_mobile_no }}<br>
                                                @endif
                                                <b>{{ $invoice->company_land->company_land_name }}</b><br><br>
                                                <b>Delivery Order No.:</b><br>
                                                @if (count($invoice->delivery_orders) > 0)
                                                    @foreach ($invoice->delivery_orders as $do)
                                                        {{ $do->delivery_order_no }}<br>
                                                    @endforeach
                                                @else
                                                   -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($invoice->customer->customer_acc_name && $invoice->customer->customer_acc_mobile_no && $invoice->customer->customer_category->customer_category_slug != 'cash')
                                                    <b>Account:</b><br>
                                                    {{ @$invoice->customer->customer_acc_name }}<br>
                                                    {{ @$invoice->customer->customer_acc_mobile_no }}
                                                    <br><br>
                                                @endif

                                                @if ($invoice->customer_name)
                                                    <b>PIC:</b><br>
                                                    @if (@$invoice->customer->customer_category->customer_category_slug == 'cash')
                                                        @if (@$invoice->delivery_order)
                                                          {{--  @php $customer_pic = $invoice->delivery_order->customer_pic->where('customer_pic_ic',$invoice->delivery_order->customer_ic)->first() @endphp
                                                            @if ($customer_pic)
                                                                {{ @$customer_pic->customer_pic_name }}
                                                            @endif --}}
                                                            {{@$invoice->delivery_order->customer_name}} (Cash)
                                                        @else
                                                            -
                                                        @endif
                                                    @else
                                                        {{ $invoice->customer_name }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($invoice->invoice_item)
                                                    @foreach ($invoice->invoice_item as $ik => $item)
                                                        @if ($ik > 0)
                                                            <br>
                                                        @endif
                                                        {{ $item->product->product_name }} -
                                                        {{ $item->setting_product_size->setting_product_size_name }}
                                                        - <b>{{ round($item->invoice_item_quantity, 4) }} KG</b>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {{ date_format(new DateTime(@$invoice->invoice_date), 'Y-m-d h:i A') }}
                                                <br>
                                                <b>Grand Total: RM {{ @$invoice->invoice_grandtotal }}</b>
                                                <br>
                                                <b>Amount Paid: RM {{ @$invoice->invoice_amount_paid ?? 0 }}</b>
                                                <br>
                                                <b>Remaining: RM {{ @$invoice->invoice_amount_remaining ?? 0 }}</b>
                                                <br>
                                                @php
                                                    $order_log = '<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>';
                                                    if (@$invoice->invoice_log) {
                                                        foreach ($invoice->invoice_log as $key => $dol) {
                                                            $order_log .= '<tr>';
                                                            $log_user = '';
                                                            if ($dol->user_id != null) {
                                                                $order_log .= "<td>{$dol->user->user_fullname}</td>";
                                                            } else {
                                                                $order_log .= '<td>-</td>';
                                                            }
                                                            $order_log .= "<td>{$dol->invoice_log_description}</td><td>{$dol->invoice_log_created}</td>";
                                                            $order_log .= '</tr>';
                                                        }
                                                    } else {
                                                        $order_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                                    }
                                                    $order_log .= '</tbody></table>';
                                                    $order_log = str_replace("'", '`', $order_log);

    $show_status = '';
    if (@$invoice->invoice_status) {
        $status = $invoice->invoice_status->invoice_status_name;
        switch ($status) {
            case 'Partially Paid':
                $show_status = "<span class='badge badge-warning font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Partially Paid</span>";
                break;
            case 'Pending Payment':
                $show_status = "<span class='badge badge-warning font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Pending Payment</span>";
                break;
            case 'Pending Approval':
                $show_status = "<span class='badge badge-warning font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Pending Approval</span>";
                break;
            case 'Paid':
                $show_status = "<span class='badge badge-success font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Paid</span>";
                break;
            case 'Cancelled':
                $show_status = "<span class='badge badge-danger font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Cancelled</span>";
                break;
            case 'Rejected':
                $show_status = "<span class='badge badge-danger font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Rejected</span>";
                                                                break;
                                                        }
                                                    }
                                                    echo $show_status;
                                                @endphp
                                            </td>
                                            <td>
                                                @if (auth()->user()->user_type_id == 1)
                                                    <a class="btn btn-outline-primary btn-sm" target="_blank"
                                                        {{-- href="{{ route('view_invoice_pdf', [$invoice->invoice_id, $encryption]) }}"> --}}
                                                        href="{{ env('GRAPHQL_API') .'/view_invoice/' .$invoice->invoice_id .'/' .md5($invoice->invoice_id . env('ENCRYPTION_KEY')) }}">
                                                        View Invoice
                                                    </a>
                                                    @if ($invoice->invoice_status_id == 2)
                                                        <a class="btn btn-outline-primary btn-sm" target="_blank"
                                                            {{-- href="{{ route('view_invoice_pdf', [$invoice->invoice_id, $encryption]) }}"> --}}
                                                            href="{{ env('GRAPHQL_API') .'/receipt/' .$invoice->invoice_id .'/' .md5($invoice->invoice_id . env('ENCRYPTION_KEY')) }}">
                                                            View Receipt
                                                        </a>
                                                        @can('payment_approval')
                                                        <button data-toggle="modal"
                                                            data-target="#check_invoice_approval"
                                                            data-id="{{ $invoice->invoice_id }}"
                                                            class="btn btn-outline-success btn-sm check_aprroval">View
                                                            Bank
                                                            Slip</button>
                                                        @endcan
                                                    @else
                                                        @php
                                                            $check = '';
                                                            if ($invoice->iv_log && date('Y-m-d H:i:s', strtotime($invoice->invoice_updated)) == date('Y-m-d H:i:s', strtotime($invoice->iv_log['invoice_log_created']))) {
                                                                $check = 'check_invoice';
                                                            }
                                                        @endphp
                                                        @can('payment_approval')
                                                            {{-- <tr class="p-0 m-0" style="border: none;"> --}}
                                                            {{-- <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;"> --}}
                                                            <button data-toggle="modal" data-target="#check_invoice_approval"
                                                                data-id="{{ $invoice->invoice_id }}"
                                                                class="btn btn-outline-success btn-sm check_aprroval {{ $check }}">Check
                                                                Approval
                                                            </button>
                                                            {{-- </td> --}}
                                                            {{-- </tr> --}}
                                                        @endcan
                                                    @endif
                                                @else
                                                    <table border='0'>
                                                        <tr class="p-0 m-0" style="border: none;">
                                                            @can('invoice_manage')
                                                                <td class="pt-2 pr-2 p-0  m-0"
                                                                    style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                    @if ($message_templates)
                                                                        @foreach ($message_templates as $rows)
                                                                            <button data-toggle="modal"
                                                                                data-target="#send_message" if (!$.trim(data)){
                                                                                data-id="{{ $invoice->invoice_id }}"
                                                                                data-slug="{{ $rows->message_template_involve_slug }}"
                                                                                data-message-template-id=" {{ $rows->message_template_id }}"
                                                                                data-customer-category-slug="{{ $invoice->customer->customer_category->customer_category_slug }}"
                                                                                class="btn btn-outline-info btn-sm invoice whatsapp mb-2">{{ $rows->message_template_name }}</button><br>
                                                                            <input type="hidden" id="slug"
                                                                                value="{{ $rows->message_template_involve_slug }}">
                                                                            <input type="hidden" id="message_template_id"
                                                                                value="{{ $rows->message_template_id }}">
                                                                            <input type="hidden" id="customer_category_slug"
                                                                                value="{{ $invoice->customer->customer_category->customer_category_slug }}">
                                                                        @endforeach
                                                                        <hr class="mt-0 mb-0">
                                                                    @endif
                                                                </td>
                                                            @endcan

                                                            <td class="pt-2 pr-2 p-0  m-0"
                                                                style="min-width:50px; border-top: none;">
                                                                <a class="btn btn-outline-primary btn-sm ml-2 mb-2"
                                                                    target="_blank" {{-- href="{{ route('view_invoice_pdf', [$invoice->invoice_id, $encryption]) }}"> --}}
                                                                    href="{{ env('GRAPHQL_API') .'/view_invoice/' .$invoice->invoice_id .'/' .md5($invoice->invoice_id . env('ENCRYPTION_KEY')) }}">
                                                                    View Invoice
                                                                </a><br>
                                                                @if ($invoice->invoice_status_id == 2)
                                                                    <a class="btn btn-outline-primary btn-sm ml-2 mb-2"
                                                                        target="_blank" {{-- href="{{ route('view_invoice_pdf', [$invoice->invoice_id, $encryption]) }}"> --}}
                                                                        href="{{ env('GRAPHQL_API') .'/receipt/' .$invoice->invoice_id .'/' .md5($invoice->invoice_id . env('ENCRYPTION_KEY')) }}">
                                                                        View Receipt
                                                                    </a>
                                                                @else
                                                                    @php
                                                                        $check = '';
                                                                        if ($invoice->iv_log && date('Y-m-d H:i:s', strtotime($invoice->invoice_updated)) == date('Y-m-d H:i:s', strtotime($invoice->iv_log['invoice_log_created']))) {
                                                                            $check = 'check_invoice';
                                                                        }
                                                                    @endphp
                                                                    @can('payment_approval')
                                                                        <button data-toggle="modal" data-target="#check_invoice_approval"
                                                                            data-id="{{ $invoice->invoice_id }}"
                                                                            class="btn btn-outline-success btn-sm ml-2 check_aprroval {{ $check }}">Check
                                                                            Approval
                                                                        </button>
                                                                    @endcan
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @can('invoice_manage')
                                                            <tr class="p-0 m-0" style="border: none;">
                                                                <td class="pt-2 pr-2 p-0   m-0"
                                                                    style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                    @can('paid_invoice_edit')
                                                                    @if ($invoice->invoice_status_id == 2)
                                                                        <a class="btn btn-outline-warning btn-sm"
                                                                            href="{{ route('paid_invoice_edit', ['tenant' => tenant('id'), 'id' => $invoice->invoice_id]) }}">Edit</a>
                                                                    @endif
                                                                    @endcan
                                                                    @if ($invoice->invoice_status_id != 3 && ($invoice->invoice_status_id == 1 || $invoice->invoice_status_id == 4 || $invoice->invoice_status_id == 5))
                                                                        <a class="btn btn-outline-warning btn-sm"
                                                                            href="{{ route('invoice_edit', ['tenant' => tenant('id'), 'id' => $invoice->invoice_id]) }}">Edit</a>
                                                                    @endif
                                                                    <span data-toggle='modal' data-target='#delete'
                                                                        data-id='{{ $invoice->invoice_id }}' class='delete'><a
                                                                            href='javascript:void(0);'
                                                                            class='btn btn-sm btn-outline-danger waves-effect waves-light'>Delete</a></span>
                                                                </td>

                                                            </tr>

                                                            @if ($invoice->invoice_status_id != 3 && ($invoice->invoice_status_id == 1 || $invoice->invoice_status_id == 4 || $invoice->invoice_status_id == 5))
                                                                @php
                                                                    if (@$invoice->delivery_order->customer_pic) {
                                                                        $customer_pic = $invoice->delivery_order->customer_pic->where('customer_pic_ic', $invoice->delivery_order->customer_ic)->first();
                                                                        $customer_pic_name = @$customer_pic->customer_pic_name;
                                                                        $customer_pic_mobile_no = @$customer_pic->customer_pic_mobile_no;
                                                                    } else {
                                                                        $customer_pic_name = @$invoice->customer_name;
                                                                        $customer_pic_mobile_no = @$invoice->customer->customer_mobile_no;
                                                                    }

                                                                @endphp
                                                                <tr class="p-0 m-0" style="border: none;">
                                                                    <td class="pt-2 pr-2 p-0  m-0"
                                                                        style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        <button class="btn btn-outline-success btn-sm sendInv"
                                                                            data-toggle="modal" data-target="#send_invoice"
                                                                            data-id="{{ $invoice->invoice_id }}"
                                                                            data-name="{{ $invoice->customer->customer_category->customer_category_slug != 'cash'? $invoice->customer_name: $customer_pic_name }}"
                                                                            data-mobile="{{ $invoice->customer->customer_category->customer_category_slug != 'cash'? $invoice->customer->customer_mobile_no: $customer_pic_mobile_no }}">
                                                                            Send Single Payment Url
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $check = '';
                                                                    if ($invoice->iv_log && date('Y-m-d H:i:s', strtotime($invoice->invoice_updated)) == date('Y-m-d H:i:s', strtotime($invoice->iv_log['invoice_log_created']))) {
                                                                        $check = 'check_invoice';
                                                                    }
                                                                @endphp
                                                            @elseif($invoice->invoice_status_id == 2)
                                                              @can('payment_approval')
                                                                <tr class="p-0 m-0" style="border: none;">
                                                                    <td class="pt-2 pr-2 p-0  m-0"
                                                                        style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        <button data-toggle="modal"
                                                                            data-target="#check_invoice_approval"
                                                                            data-id="{{ $invoice->invoice_id }}"
                                                                            class="btn btn-outline-success btn-sm check_aprroval">View
                                                                            Bank
                                                                            Slip</button>
                                                                    </td>
                                                                </tr>
                                                                @endcan
                                                            @endif
                                                            @if ($invoice->invoice_status_id == 1 || $invoice->invoice_status_id == 6)
                                                                <tr class="p-0 m-0" style="border: none;">
                                                                    <td class="pt-2 pr-2 p-0  m-0"
                                                                        style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        <a class="btn btn-outline-dark btn-sm" target="_blank"
                                                                            href="{{ route('payment_url_add', ['tenant' => tenant('id'), 'id' => $invoice->customer->customer_id]) }}">
                                                                            Create Payment Url
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endcan
                                                    </table>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-left">"No Records Found"</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $records->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <!-- Delete -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('invoice_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Invoice ?</h4>
                        <input type="hidden" , name="invoice_id" id="invoice_id">
                        <input type="hidden" , name="action" value="delete">
                        <div>
                            <label for="invoice_log_description">Remark: </label>
                            <textarea name="invoice_log_description" id="summernote" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Check Invoice Approval -->
    <div class="modal fade" id="check_invoice_approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="invoice_no" class="modal-header">
                    <!-- <h4>Check Invoice #123 Approval</h4> -->
                </div>
                <form action="{{ route('invoice_approve_reject', ['tenant' => tenant('id')]) }}" method="POST">

                    @csrf
                    <div id="check_body" class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table">
                                    <tbody id="details_body">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div id="image_checking" class="form-group">
                                    <!-- <label for="customer_mobile_no">Mobile No:</label> -->
                                </div>
                                <input hidden id="invoice_id_approve" name="invoice_id">
                                <input hidden id="status_invoice" name="status">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="approve_btn" class="btn btn-primary">Confirm Payment</button>
                        <button type="submit" id="reject_btn" class="btn btn-primary">Reject</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Message To Whom Invoice Listing -->
    <div class="modal fade" id="send_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>Invoice Whatsapp</b>
                    </div>
                    <div class="modal-body whatsapp_body">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    {{-- Order Log --}}
    <div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Logs</b>
                </div>
                <div class="modal-body">
                    <div id="log-description"></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
    {{-- End Order Log --}}

    {{-- Order Log --}}
    <div class="modal fade" id="send_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form>
                    @csrf
                    <div class="modal-header">
                        <b>Confirm Send Invoice?</b>
                    </div>
                    <div class="modal-body">
                        <label for="">Name: <span id="customer_name"></span></label><br>
                        <label for="">Mobile: <span id="customer_mobile"></span></label>
                        <input type="hidden" name="invoice_id" id="invoice_id">
                    </div>
                    <div class="modal-footer">
                        <a href="javascript: void(0);" class="btn btn-success sendLink">Confirm</a>
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Order Log --}}
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.5.0/js/md5.min.js"></script>
    <script>
        $(document).ready(function() {
            get_product_by_category($('#product_category_id').val());
        });

        $(document).on('change', '#product_category_id', function() {
            let product_category_id = $(this).val();
            get_product_by_category(product_category_id);
        });

        $(document).on('change', '#product', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        function get_product_by_category(product_category_id) {
            let sel_product_id = '{{ @$search['product_id'] }}' ?? null;
            let product_sel = '<option value="">Please Select Product</option>';

            $('#product').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_product_by_product_category_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    product_category_id: product_category_id
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (sel_product_id != null && element.id == sel_product_id) {
                                product_sel += '<option value="' + element.id + '" selected>' + element
                                    .value + '</option>';
                            } else {
                                product_sel += '<option value="' + element.id + '">' + element.value +
                                    '</option>';
                            }
                        });
                    }
                    $('#product').html(product_sel);
                }
            });

            if(sel_product_id){
                get_size_by_product(sel_product_id);
            }
        }

        function get_size_by_product(product_id) {

            let sel_size_id = '{{ @$search['product_size_id'] }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#size_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id
                },
                success: function(e) {
                    let option = '<option value="">Please Select Grade</option>';

                    if (e.data.length > 0) {
                        e.data.forEach(function(p) {
                            if (sel_size_id != null && p.id == sel_size_id) {
                                option += '<option value="' + p.id + '" selected>' + p.value + '</option>';
                            } else {
                                option += '<option value="' + p.id + '">' + p.value +'</option>';
                            }
                        });
                    }
                    $('#size_id').html(option);
                }
            });
        }

        $('.button_status').on('click', function() {
            var value_transaction = this.value;

            $('#hidden_order_value').val(value_transaction);
            $('#search').click();
        });

        $('.whatsapp').on('click', function() {
            let invoice_id = $(this).attr('data-id');
            // let delivery_order_id = $(this).attr('data-order-id');
            let slug = $(this).attr('data-slug');
            let message = $(this).attr('data-message-template-id');
            let customer_category_slug = $(this).attr('data-customer-category-slug');
            let details = '';

            $.ajax({
                url: "{{ route('ajax_get_mobile_no_by_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    invoice_id: invoice_id
                },
                success: function(e) {
                    details += '<table class="table table-nowrap">';
                    if (e.customer_name != null && e.customer_mobile_no != null) {
                        details += '<tr>';
                        details += '<td style="border-top: none;">Customer Name: ' + e.customer_name +
                            '</td>';
                        details +=
                            '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/' +
                            invoice_id + '/' + slug + '/' + message + '/' + e.customer_mobile_no +
                            '" target="_blank" class="btn btn-success send" id="customer-whatsapp"</a>Send</td>'
                        details += '</tr>';
                        details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                        details += '<td style="border-top: none;">Customer Mobile: ' + e
                            .customer_mobile_no + '</td>';
                        details += '</tr>';
                    } else {
                        details += '';
                    }
                    if (e.company_acc_name != null && e.company_acc_mobile_no != null) {
                        details += '<tr>';
                        details += '<td style="border-top: none;">Company Account Name: ' + e
                            .company_acc_name + '</td>';
                        details +=
                            '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/' +
                            invoice_id + '/' + slug + '/' + message + '/' + e.company_mobile_no +
                            '" target="_blank" class="btn btn-success send" id="company-whatsapp">Send</a></td>'
                        details += '</tr>';
                        details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                        details += '<td style="border-top: none;">Company Account Mobile: ' + e
                            .company_acc_mobile_no + '</td>';
                        details += '</tr>';
                    } else {
                        details += '';
                    }
                    if (customer_category_slug == "cash") {
                        if (e.customer_pic_name != null && e.customer_pic_mobile_no != null) {
                            details += '<tr>';
                            details += '<td style="border-top: none;">PIC Name: ' + e
                                .customer_pic_name + '</td>';
                            details +=
                                '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/' +
                                invoice_id + '/' + slug + '/' + message + '/' + e
                                .customer_pic_mobile_no +
                                '" target="_blank" class="btn btn-success send" id="company-whatsapp">Send</a></td>'
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td style="border-top: none;">PIC Mobile: ' + e
                                .customer_pic_mobile_no + '</td>';
                            details += '<tr>';
                        }
                    }
                    details += '</table>';

                    $("#company-whatsapp").attr("href", "../message-template/send_whatsapp_template/" +
                        invoice_id + "/" + slug + "/" + message + "/" + e.company_acc_mobile_no);
                    $("#customer-whatsapp").attr("href", "../message-template/send_whatsapp_template/" +
                        invoice_id + "/" + slug + "/" + message + "/" + e.customer_mobile_no);
                    $('.whatsapp_body').html(details);

                },
                error: function(e) {
                    alert(e);
                }
            });
        })

        $('.delete').on('click', function() {
            var id = $(this).attr('data-id');
            $(".modal-body #invoice_id").val(id);
        });

        $('.sendLink').on('click', function() {
            var invoice_id = $('#invoice_id').val();
            var domain = '{{ env('GRAPHQL_API') }}';

            var encryption = md5(invoice_id + '{{ env('ENCRYPTION_KEY') }}');
            var mobile = $('#customer_mobile').html();
            var url = 'https://api.whatsapp.com/send?phone=' + mobile + '&text=Click below to view your invoice. ' +
                domain + '/view_invoice/' + invoice_id + '/' + encryption;
            window.open(url, '_blank');
        })
        $('#approve_btn').on('click', function() {
            var approve = 'approve';
            $('#status_invoice').val(approve);
        })
        $('#reject_btn').on('click', function() {
            var reject = 'reject';
            $('#status_invoice').val(reject);
        })
        $('.check_aprroval').on('click', function() {
            $('#details_body').html('Loading Details...');
            $('#invoice_no').html('Loading Invoice...');
            $('#image_checking').html('Checking For Bank Slip...');
            let invoice_id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('ajax_find_invoice_with_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    invoice_id: invoice_id,
                },
                success: function(e) {
                    $('#approve_btn').show();
                    $('#reject_btn').show();
                    $('#approve_btn').prop('disabled', false);
                    let details = '';
                    let invoice_num = '';
                    let image = '';
                    if (e.length > 0) {
                        e.forEach(element => {
                            let items = '';
                            element.items.forEach(item => {
                                items += item[0] + ' [' + item[1] + '] <br>';
                            });
                            details += '<tr>';
                            details += '<td>Cutomer Name:</td>';
                            details += '<td>' + element.customer_name + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Cutomer Address:</td>';
                            details += '<td>' +
                                element.customer_address + ',<br>'
                            if (element.customer_address2 != null) {
                                details += element.customer_address2 + ',<br>'
                            }
                            details += element.customer_postcode + ',<br>'
                            details += element.customer_state + ',<br>'
                            details += element.customer_country + '<br>'
                            details += '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Item:</td>';
                            details += '<td>' + items + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Subtotal:</td>';
                            details += '<td>' + element.invoice_subtotal + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Discount:</td>';
                            details += '<td>' + element.invoice_total_discount + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Total:</td>';
                            details += '<td>' + element.invoice_total + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>GST:</td>';
                            details += '<td>' + element.invoice_total_gst + '</td>';
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td>Grandtotal:</td>';
                            details += '<td>' + element.invoice_grandtotal + '</td>';
                            details += '</tr>';
                            details += '<td>Amount Paid:</td>';
                            details += '<td>' + element.invoice_amount_paid + '</td>';
                            details += '</tr>';
                            details += '<td>Remaining:</td>';
                            details += '<td>' + element.invoice_amount_remaining + '</td>';
                            details += '</tr>';
                            if(element.invoice_payment.length > 0){
                              image += '<span class="mb-2"><b>Transaction History</b></span>';
                              image += '<div class="mb-2" style="overflow-y:scroll; height:125px;">';
                              element.invoice_payment.forEach((item, i) => {
                                if(item.invoice_payment_date != '0000-00-00'){
                                  // if(element.setting_payment_slug[item.setting_payment_id].slug == 'credit'){
                                  //   image += '<div class="mb-2">'+item.invoice_payment_amount+' Customer Credit was used on '+item.invoice_payment_date+'.</div>';
                                  // }else{
                                    image += '<div class="mb-2">';
                                    image += '['+ new Date(item.invoice_payment_created).toLocaleDateString("sv-SE") + ' ' + new Date(item.invoice_payment_created).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) +'] <br> RM '+item.invoice_payment_amount+' by '+element.setting_payment_slug[item.setting_payment_id].name+' on '+item.invoice_payment_date+'.';
                                    if(item.invoice_payment_remark != null){
                                      image += '<br><i>'+item.invoice_payment_remark+'</i>';
                                    }
                                    image += '</div>';
                                  // }
                                }
                              });
                              image += '</div>';
                            }

                            if (element.media.length == 0 && element.invoice_status_id != 2 &&
                                element.invoice_status_id != 3) {
                                image +=
                                    '<div class="mb-2">No Bank Slip Image/PDF Found...</div>';
                                $('#approve_btn').prop('disabled', true);
                                $('#reject_btn').prop('disabled', true);
                            } else if (element.media.length > 0 && element.invoice_status_id !=
                                2 && element.invoice_status_id != 3) {

                                image += '<div class="mb-2"><b>Bank Slip Image/PDF</b></div>';
                                image += '<input type="checkbox" name="select_all_img" id="select_all_img"><label for="select_all_img">Select all</label>'
                                image += '<div class="mb-2" style="overflow-y:scroll; height:355px;">';
                                // image += '<img src="'+element.media[0].original_url+'">';
                                element.media.forEach(e => {

                                    if (e.mime_type == 'application/pdf') {
                                        image += '<div style="position: relative; width: 100px; height: 100px; display: flex; padding: 0; margin: 0; float: left; margin-right: 10px">';
                                        image += '<a target="_blank" href="' + e
                                            .original_url +
                                            '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="{{ url('assets/images/PDF-img.png') }}" width="100" height="100"></a>';
                                        image += '<input style="position: absolute; top: 7px; left: 5px" type="checkbox" name="invoice_image[]" value='+e.id+' />';
                                        image += '</div>';
                                    } else {
                                        image += '<div style="position: relative; width: 100px; height: 100px; display: flex; padding: 0; margin: 0; float: left; margin-right: 10px">';
                                        image += '<a target="_blank" href="' + e
                                            .original_url +
                                            '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="' +
                                            e.original_url +
                                            '" width="100" height="100"></a>';
                                        image += '<input style="position: absolute; top: 7px; left: 5px" type="checkbox" name="invoice_image[]" value='+e.id+' />';
                                        image += '</div>';
                                    }
                                });
                                image += '</div>';
                                $('#approve_btn').prop('disabled', false);
                                $('#reject_btn').prop('disabled', false);
                            } else if (element.media.length > 0 && element.invoice_status_id ==
                                2) {
                                image += '<div class="mb-2"><b>Invoice Approved</b></div>';
                                image += '<div class="mb-2" style="overflow:scroll; height:355px;">';
                                element.media.forEach(e => {

                                    if (e.mime_type == 'application/pdf') {
                                        image += '<a target="_blank" href="' + e
                                            .original_url +
                                            '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="{{ url('assets/images/PDF-img.png') }}" width="100" height="100"></a>';
                                    } else {
                                        image += '<a target="_blank" href="' + e
                                            .original_url +
                                            '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="' +
                                            e.original_url +
                                            '" width="100" height="100"></a>';
                                    }
                                });
                                image += '</div>';
                                $('#approve_btn').hide();
                                $('#reject_btn').hide();
                            }

                            invoice_num += '<h4>Approval for #' + element.invoice_no + '</h4>';
                        });
                    } else {
                        details += '<tr><td class="text-center" colspan="5">No Records...</td></tr>';
                    }
                    $('#details_body').html(details);
                    $('#invoice_no').html(invoice_num);
                    $('#image_checking').html(image);
                    $('#invoice_id_approve').val(invoice_id);
                    $("#select_all_img").on('click', function(){
                        $('input:checkbox').not(this).prop('checked', this.checked);
                    });
                },
                error: function(e) {
                    alert(e);
                }
            })
        });

        $('.sendInv').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let mobile = $(this).data('mobile');
            $('.modal-body #invoice_id').val(id);
            $('.modal-body #customer_name').html(name);
            $('.modal-body #customer_mobile').html(mobile);
        });
        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });


        @if (@$search['company_id'] != null)
            get_land_user('{{ $search['company_id'] }}');

            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}', '{{ $search['company_id'] }}')
            @endif

        @else
            get_land_user('{{ auth()->user()->company_id }}');
            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}','{{ auth()->user()->company_id }}')
            @endif
        @endif

        $('#company_id').on('change', function() {
            let id = $(this).val();
            get_land_user(id);
            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}', id)
            @endif
        });

        $('#customer_category_id').on('change', function() {
            let category = $(this).val();
            let company_detect = $('#company_id').val();

            @if (auth()->user()->user_type_id != 1)
                let company = '{{ auth()->user()->company_id }}';
            @else
                if (company_detect != null){
                    let company  = $('#company_id').val();
                }else{
                    let company  = 0;
                }
            @endif

            get_customer_by_category (category, company);
        });

        function get_customer_by_category (category, company){
            let customer = '<option value="">Please Select Customer</option>';
            let scust = '{{ @$search['customer_id'] }}' ?? null;
            $('#customer_id').html('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ route('ajax_get_customer_by_category', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    category: category,
                    company: company
                },
                success: function(e) {
                    if (e.length > 0) {
                        e.forEach(cust => {
                            if (scust != null && cust.customer_id == scust) {
                                customer += '<option value="' + cust.customer_id + '" selected>' + cust.customer_company_name + ' - ' + cust.customer_name
                                    '</option>';
                            } else {
                                customer += '<option value="' + cust.customer_id + '">' + cust.customer_company_name + ' - ' +cust.customer_name
                                    '</option>';
                            }
                        });
                        $('#customer_id').html(customer);
                    } else {
                        $('#customer_id').html('<option value="">No Customer</option>');
                    }
                }
            });
        }

        function get_land_user(id) {
            let land = '<option value="">Please Select Land</option>';
            let user = '<option value="">Please Select User</option>';
            let customer = '<option value="">Please Select Customer</option>';
            $('#customer_id').html('<option value="">Loading...</option>');
            $('#user_id').html('<option value="">Loading...</option>');
            $('#company_land_id').html('<option value="">Loading...</option>');
            let sland = '{{ @$search['company_land_id'] }}' ?? null;
            let suser = '{{ @$search['user_id'] }}' ?? null;
            let scust = '{{ @$search['customer_id'] }}' ?? null;
            let scust_cat = '{{ @$search['customer_category_id'] }}' ?? null;
            $.ajax({
                url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
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

                    if (!scust_cat){

                        if (e.customer.length > 0) {
                            e.customer.forEach(cust => {
                                if (scust != null && cust.customer_id == scust) {
                                    customer += '<option value="' + cust.customer_id + '" selected>' + cust.customer_company_name + ' - ' + cust.customer_name
                                        '</option>';
                                } else {
                                    customer += '<option value="' + cust.customer_id + '">' + cust.customer_company_name + ' - ' +cust.customer_name
                                        '</option>';
                                }
                            });
                            $('#customer_id').html(customer);
                        } else {
                            $('#customer_id').html('<option value="">No Customer</option>');
                        }
                    }
                }
            });
        }
    </script>
@endsection
