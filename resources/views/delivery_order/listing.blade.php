@extends('layouts.master')

@section('title')
    Delivery Order Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
         .img-wrap {
            position: relative;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19);
            border-radius: 10px;
        }
        table.b td{
          padding: 0.75rem;
          vertical-align: top;
          border-top: 1px solid #eff2f7;
        }
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

    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">D.O. Listing</h4>
                    @can('delivery_order_manage')
                        <a href="{{ route('do_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                            <i class="fas fa-plus"></i> ADD NEW
                        </a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">D.O.</a>
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
                                <button type="button" value="" class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if (!@$search['order_status_id']) active @endif ">
                                    <span class="transaction-font">All</span>
                                    <span class="transaction-font">({{ @$count_status['default'] ? $count_status['default'] : 0 }})</span>
                                </button>

                                @foreach ($do_status as $key => $val)
                                    <button type="button" value="{{ $val->delivery_order_status_id }}" class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if ($val->delivery_order_status_id == @$search['order_status_id']) active @endif ">
                                        <span class="transaction-font">{{ $val->delivery_order_status_name }}</span>
                                        <span class="transaction-font">({{ @$count_status[$val->delivery_order_status_id] ? $count_status[$val->delivery_order_status_id] : 0 }})</span>
                                    </button>
                                @endforeach
                                <input id="hidden_order_value" type="hidden" name="order_status_id" class="hidden_value_id" value="{{ @$search['order_status_id'] }}" />
                                <input id="hidden_previous_transaction_value" type="hidden" name="hidden_order_status_id" value="{{ @$search['order_status_id'] }}" />
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
                                            <input type="text" class="form-control" name="freetext" placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Delivery Order Date</label>
                                            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="do_from" placeholder="Start Date" value="{{ @$search['do_from'] }}" id="start" autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="do_to" placeholder="End Date" value="{{ @$search['do_to'] }}" id="end" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Order Type</label>
                                            {!! Form::select('order_type_id', $order_type, @$search['order_type_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Warehouse</label>
                                            {!! Form::select('warehouse_id', $warehouse, @$search['warehouse_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company</label>
                                                {!! Form::select('company', $company, @$search['company'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Land</label>
                                            <select name="company_land_id" class="form-control" id="company_land_id">

                                            </select>
                                        </div>
                                    </div>

                                    @if ($product)
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
                                            <select name="user_id" id="user_id" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="customer_category">
                                        <div class="form-group">
                                            <label for="">Customer Category</label>
                                            {!! Form::select('customer_category_id', $customer_category_sel, @$search['customer_category_id'], ['class' => 'form-control', 'id' => 'customer_category_id']) !!}
                                            {{-- {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="customer">
                                        <div class="form-group">
                                            <label for="">Customer</label>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                @if (@$search['customer_id'])
                                                    <option></option>
                                                @endif
                                            </select>
                                            {{-- {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <th>DO</th>
                                <th>Items</th>
                                <th>Expenses</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @foreach ($records as $do)
                                        @php
                                            $status = '';
                                            $order_log = '<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>';
                                            if (@$do->delivery_order_log) {
                                                foreach ($do->delivery_order_log as $key => $dol) {
                                                    $order_log .= '<tr>';
                                                    $log_user = '';
                                                    if(@$dol->user->user_fullname != null){
                                                      $order_log .= "<td>{$dol->user->user_fullname}</td>";
                                                    }else{
                                                      $order_log .= "<td>-</td>";
                                                    }
                                                    $order_log .= "<td>{$dol->delivery_order_log_description}</td><td>{$dol->delivery_order_log_created}</td>";
                                                    $order_log .= '</tr>';
                                                }
                                            } else {
                                                $order_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                            }
                                            $order_log .= '</tbody></table>';
                                            $order_log = str_replace("'", '`', $order_log);
                                            switch ($do->delivery_order_status_id) {
                                                case 1:
                                                    $status = "<span class='badge badge-warning log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>" . $do->delivery_order_status->delivery_order_status_name . '</span>';
                                                    break;
                                                case 2:
                                                    $status = "<span class='badge badge-success log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>" . $do->delivery_order_status->delivery_order_status_name . '</span>';
                                                    break;
                                                case 3:
                                                    $status = "<span class='badge badge-danger log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>" . $do->delivery_order_status->delivery_order_status_name . '</span>';
                                                    break;
                                                case 4:
                                                    $status = "<span class='badge badge-primary log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>" . $do->delivery_order_status->delivery_order_status_name . '</span>';
                                                    break;
                                                case 5:
                                                    $status = "<span class='badge badge-success log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>" . $do->delivery_order_status->delivery_order_status_name . '</span>';
                                                    break;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <small><i>{{ date_format(@$do->delivery_order_created, 'Y-m-d h:i A') }}</i></small><br/>
                                                <b>{{ $do->delivery_order_no }}</b><br>
                                                {{ @$do->customer_details->customer_company_name }} - <span style="font-style: italic;">{{ @$do->customer_details->customer_category->customer_category_name }}</span><br>
                                                {{ @$do->company_land->company_land_name }}<br><br>
                                                <b>PIC: </b><br>
                                                {!! $do->customer_name != null ? $do->customer_name : '-' !!}<br>
                                                {{ @$do->customer_ic }}
                                            </td>
                                            <td>
                                                @php
                                                    $items = '';
                                                    if ($do->delivery_order_items) {
                                                        foreach ($do->delivery_order_items as $key => $order_item) {
                                                            if ($key >= 1) {
                                                                $items .= '</br>';
                                                            }
                                                            $items .= $order_item->product->product_name . ' - ';
                                                            $items .= $order_item?->setting_product_size?->setting_product_size_name;

                                                            //check if decimal is all 0
                                                            if (fmod($order_item->delivery_order_item_quantity, 1) === 0.0) {
                                                                $delivery_order_item_quantity = $order_item->delivery_order_item_quantity;
                                                            } else {
                                                                $delivery_order_item_quantity = $order_item->delivery_order_item_quantity;
                                                            }
                                                            $items .= '<br><b>' . number_format($delivery_order_item_quantity,2) . ' KG</b>';

                                                            if($order_item->delivery_order_item_collect_no){
                                                                $items .= ' [' . strtoupper($order_item->delivery_order_item_collect_no) . ']';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                {!! $items != null ? $items : '"No Item"' !!}
                                            </td>
                                            <td>
                                                @php
                                                    $expense_value = '';
                                                    if ($do->delivery_order_expense->isNotEmpty()) {
                                                        foreach ($do->delivery_order_expense as $ek => $exp) {
                                                            if (@$exp->expenses) {
                                                                $expense_value .= json_decode($exp->expenses->setting_expense_name)->en . ' - ';
                                                                $expense_value .= '<b>RM ' . $exp->delivery_order_expense_total . '</b><br><br>';
                                                            } else {
                                                                $expense_value = '';
                                                            }
                                                        }
                                                    } else {
                                                        $expense_value = '"No Expenses"';
                                                    }
                                                @endphp
                                                {!! $expense_value !!}
                                            </td>
                                            <td>
                                                <b>{{ @$do->delivery_order_type->delivery_order_type_name }}</b><br>
                                                {!! $status !!}
                                            </td>
                                            <td>
                                                <table border='0'>
                                                    @if(auth()->user()->user_type_id == 1)
                                                        <tr  class="p-0 m-0" style="border: none;">
                                                            <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none;">
                                                                <a href="{{ route('do_pdf', ['tenant' => tenant('id'), 'id' => $do->delivery_order_id, 'encryption' => md5($do->delivery_order_id.env('ENCRYPTION_KEY'))]) }}" target="_blank" class="btn btn-outline-success btn-sm mb-2 ml-2">
                                                                    View PDF
                                                                </a>
                                                                @if($do->delivery_order_status_id == 5 && $do->invoice_id != 0)
                                                                    <br><a href="{{ route('get_invoice_from_do', ['tenant' => tenant('id'), 'id' => $do->invoice_id]) }}" class="btn btn-outline-primary btn-sm mb-2 ml-2">View Invoice</a>
                                                                @endif
                                                                <br/>
                                                            @if ($do->is_media)
                                                                <div class="btn btn-outline-dark btn-sm mb-2 view_images ml-2" data-id="{{ $do->delivery_order_id }}" delivery_order_item_media="{{ $do->delivery_order_item_media }}" data-product-name="{{ $do->product_name }}" data-qty="{{ $do->delivery_order_item_quantity }}" id='view_images_{{$do->delivery_order_id}}'>
                                                                    <span data-toggle='modal' data-target='#view_images_modal'>View Images</span>
                                                                </div>
                                                            @endif
                                                        </tr>
                                                    @else
                                                        @if($do->delivery_order_status_id == 3)
                                                            <tr  class="p-0 m-0" style="border: none;">
                                                                <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none;">
                                                                    <a href="{{ route('do_pdf', ['tenant' => tenant('id'), 'id' => $do->delivery_order_id, 'encryption' => md5($do->delivery_order_id.env('ENCRYPTION_KEY'))]) }}" target="_blank" class="btn btn-outline-success btn-sm mb-2 ml-2">View PDF</a>
                                                                    <br>
                                                                @if ($do->is_media)
                                                                    <div class="btn btn-outline-dark btn-sm mb-2 view_images ml-2" data-id="{{ $do->delivery_order_id }}" delivery_order_item_media="{{ $do->delivery_order_item_media }}" data-product-name="{{ $do->product_name }}" data-qty="{{ $do->delivery_order_item_quantity }}" id='view_images_{{$do->delivery_order_id}}'>
                                                                        <span data-toggle='modal' data-target='#view_images_modal'>View Images</span>
                                                                    </div>
                                                                @endif
                                                                    @if($do->delivery_order_status_id == 5 && $do->invoice_id != 0)
                                                                        <br/>
                                                                        <a href="{{ route('get_invoice_from_do', ['tenant' => tenant('id'), 'id' => $do->invoice_id]) }}" class="btn btn-outline-primary btn-sm ml-2 mb-2">View Invoice</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr  class="p-0 m-0" style="border: none;">
                                                                    <td class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        @if($do->delivery_order_status_id == 2)
                                                                            @if ($message_templates)
                                                                                @foreach ($message_templates as $rows)
                                                                                    <button class="btn btn-sm btn-outline-success mb-2 do_whatsapp" data-toggle="modal" data-target="#send_message" data-id="{{ $do->invoice_id }}" data-order-id="{{ $do->delivery_order_id }}"data-slug="{{ $rows->message_template_involve_slug }}" data-message-template-id= "{{ $rows->message_template_id }}" data-customer-category-slug= "{{ @$do->customer_details->customer_category->customer_category_slug }}" href="">{{$rows->message_template_name}}</button>
                                                                                    <input type="hidden" id="slug" value="{{ $rows->message_template_involve_slug }}">
                                                                                    <input type="hidden" id="message_template_id" value="{{ $rows->message_template_id }}">
                                                                                @endforeach
                                                                            <hr class="mt-0 mb-0">
                                                                            @endif
                                                                    @elseif($do->delivery_order_status_id == 5 && $do->invoice_id == 0)
                                                                        @can('delivery_order_manage')
                                                                            <button class="btn btn-sm btn-outline-info issue_invoice" data-toggle="modal" data-customer="{{ $do->customer_id }}" data-do_date="{{ date_format($do->delivery_order_created, 'd-m-Y') }}" data-land="{{ $do->company_land_id }}" data-type="{{ $do->delivery_order_type_id }}"data-target="#invoice">Issue Invoice</button>
                                                                        @endcan
                                                                        @can('delivery_order_revert')
                                                                            <span data-toggle='modal' data-target='#revert' data-id='{{$do->delivery_order_id}}' class='revert'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light'>Revert</a></span>
                                                                        @endcan
                                                                    @elseif($do->delivery_order_status_id == 4)
                                                                        @can('delivery_order_manage')
                                                                            <button data-toggle="modal" data-target="#price_verification_approval" data-id="{{ $do->delivery_order_id }}" class="btn btn-outline-success btn-sm check_aprroval">Price Verification</button>
                                                                        @endcan
                                                                    @elseif ($do->delivery_order_status_id == 4 && $do->delivery_order_type_id == 2 && count($do->delivery_order_expense) <= 0)
                                                                        @can('delivery_order_manage')
                                                                            <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                                <button class="btn btn-outline-dark btn-sm add_exp" data-target="#expense" data-toggle="modal" data-id="{{ $do->delivery_order_id }}">
                                                                                    Add Expense
                                                                                </button>
                                                                                <button data-toggle="modal" data-target="#price_verification_approval" data-id="{{ $do->delivery_order_id }}" class="btn btn-outline-success btn-sm check_aprroval">Price Verification</button>
                                                                            </td>
                                                                        @endcan
                                                                    @elseif ($do->delivery_order_status_id == 5 && $do->invoice_id != 0)
                                                                        @can('delivery_order_revert')
                                                                            <span data-toggle='modal' data-target='#revert' data-id='{{$do->delivery_order_id}}' class='revert'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light'>Revert</a></span>
                                                                        @endcan
                                                                    @endif
                                                                </td>
                                                                <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none;">
                                                                    <a href="{{ route('do_pdf', ['tenant' => tenant('id'), 'id' => $do->delivery_order_id, 'encryption' => md5($do->delivery_order_id.env('ENCRYPTION_KEY'))]) }}" target="_blank" class="btn btn-outline-success btn-sm mb-2 ml-2">View PDF</a>
                                                                    @if($do->delivery_order_status_id == 5 && $do->invoice_id != 0)
                                                                        <br>
                                                                        <a href="{{ route('get_invoice_from_do', ['tenant' => tenant('id'), 'id' => $do->invoice_id]) }}"class="btn btn-outline-primary btn-sm ml-2 mb-2">View Invoice</a>
                                                                    @endif
                                                                    <br/>
                                                                    @if ($do->is_media)
                                                                    <div class="btn btn-outline-dark btn-sm mb-2 view_images ml-2" data-id="{{ $do->delivery_order_id }}"delivery_order_item_media="{{ $do->delivery_order_item_media }}"data-product-name="{{ $do->product_name }}"data-qty="{{ $do->delivery_order_item_quantity }}"id='view_images_{{$do->delivery_order_id}}'><span data-toggle='modal' data-target='#view_images_modal'>View Images</span>
                                                                    @endif
                                                                </div>
                                                                </td>
                                                            </tr>
                                                            @if ($do->invoice_id == 0)
                                                                @can('delivery_order_manage')
                                                                    <tr  class="p-0 m-0" style="border: none;">
                                                                        <td  class="pt-2 pr-2 p-0   m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                            @if($do->delivery_order_status_id == 2)
                                                                                <a href="{{ route('do_edit', ['tenant' => tenant('id'), 'id' => $do->delivery_order_id]) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                                            @endif
                                                                            <span data-toggle='modal' data-target='#delete' data-id='{{$do->delivery_order_id}}' class='delete'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light'>Delete</a></span>
                                                                        </td>
                                                                    </tr>
                                                                @endcan
                                                            @endif
                                                            @can('delivery_order_manage')
                                                                @if ($do->delivery_order_type_id == 2 && count($do->delivery_order_expense) <= 0)
                                                                    <tr class="p-0 m-0" style="border: none;">
                                                                    <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        <button class="btn btn-outline-dark btn-sm add_exp"
                                                                            data-target="#expense" data-toggle="modal"
                                                                            data-id="{{ $do->delivery_order_id }}">
                                                                            Add Expense
                                                                        </button>
                                                                    </td>
                                                                    </tr>
                                                                @endif
                                                                <tr class="p-0 m-0" style="border: none;">
                                                                    <td  class="pt-2 pr-2 p-0  m-0" style="min-width:50px; border-top: none; border-right: 1px solid #e4e4e4;">
                                                                        @if($do->delivery_order_status_id == 2 && $do->invoice_id == 0)
                                                                            <button class="btn btn-sm btn-outline-info issue_invoice "
                                                                                data-toggle="modal" data-customer="{{ $do->customer_id }}"
                                                                                data-do_date="{{ date_format($do->delivery_order_created, 'd-m-Y') }}"
                                                                                data-land="{{ $do->company_land_id }}"
                                                                                data-type="{{ $do->delivery_order_type_id }}"
                                                                                data-target="#invoice">
                                                                                Issue Invoice
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endcan
                                                        @endif
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">"No Record Found"</td>
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
    <!-- Revert -->
    <div class="modal fade" id="revert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('do_revert', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Are you sure you want to revert this Delivery Order?</h4>
                        <input type="hidden" , name="delivery_order_id" id="delivery_order_id_revert">
                        <div>
                            <label for="delivery_order_log_description">Remark: </label>
                            <textarea name="delivery_order_log_description" id="summernote" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Revert</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('do_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this delivery order ?</h4>
                        <input type="hidden" , name="delivery_order_id" id="delivery_order_id">
                        <input type="hidden" , name="action" value="delete">
                        <div>
                            <label for="delivery_order_log_description">Remark: </label>
                            <textarea name="delivery_order_log_description" id="summernote" class="form-control" required></textarea>
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

    <!-- Check Invoice Approval -->
    <div class="modal fade" id="price_verification_approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('price_verification_approve_reject', ['tenant' => tenant('id')]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 id="do_no"></h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <th>Product</th>
                                <th>Grade</th>
                                <th>Quantity (KG)</th>
                                <th>Price / KG (RM)</th>
                                <th>Total (RM)</th>
                            </thead>
                            <tbody id="do_item_details">
                            </tbody>
                        </table>

                        <div class="row">
                            <input hidden id="do_id" name="delivery_order_id">
                            <input hidden id="do_status" name="delivery_order_status_id">
                        </div>
                        <div class="row">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="approve_btn" class="btn btn-primary">Approve</button>
                        <button type="submit" id="reject_btn" class="btn btn-primary">Reject</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Send Message To Whom DO Listing -->
    <div class="modal fade" id="send_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>DO Whatsapp</b>
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

    <!-- View Details Image DO -->
    <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>DO Images Details</b>
                    </div>
                    <div class="modal-body view_images_body">
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
    <div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    {{-- Issue Invoice --}}
    <div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Select Delivery Order to Issue Invoice</h4><br>
                </div>
                <div class="modal-header">
                    <h5 id="date"></h5>
                </div>
                <form action="{{ route('do_issue_invoice', ['tenant' => tenant('id')]) }}" method="POST">
                    @csrf
                    <div class="modal-body table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <th>#</th>
                                <th id="type"></th>
                                <th>Items</th>
                                <th>Expenses</th>
                                <th><span id="selectall" class="bold">Tick</span></th>
                            </thead>
                            <tbody id="insertOrder">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Issue Invoice --}}
    {{-- Add Expenses --}}
    <div class="modal fade" id="expense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div style="padding: 15px; border-bottom: 1px solid #eff2f7;">
                    <h4>Add Expenses: </h4><br>
                    <b>Total Expense: </b>
                    <span>RM </span>
                    <span class="total_result"></span>
                </div>
                <form action="{{ route('add_expense', ['tenant' => tenant('id')]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($expense as $key => $exp)
                                <div class="col-6" style="padding-bottom: 10px">
                                    <div class="form-group">
                                        <input type="checkbox" name="setting_expense_id[]" id="e_{{ $key }}" value="{{ $key }}">
                                        <input hidden type="text" name="do_id" id="do_id">
                                        <input hidden id="type_id{{ $key }}" value="{{$exp['type_id']}}">
                                        <label for="e_{{ $key }}">{{ json_decode($exp['name'])->en }}({{ $exp['type_name'] }})</label>
                                    </div>
                                    <div class="row">
                                      <div class="col-4">
                                        <div class="form-group" id="v_{{ $key }}">
                                            <span class="rm" id="rm_{{ $key }}">RM: </span>
                                            <input type="number" placeholder="{{ $exp['value'] }}" name="setting_expense_value[]" id="val_{{ $key }}"
                                                class="form-control">
                                        </div>
                                      </div>
                                      @if($exp['type_id'] == 4 || $exp['type_id'] == 1 )
                                      <div class="col-4">
                                        <div class="form-group" id="k_{{ $key }}">
                                          <span class="kg2" id="kg2_{{ $key }}">Kg: </span>
                                            <input readonly type="number" placeholder="Per Kg" name="expense_kg[]" id="kg_{{ $key }}"
                                                class="form-control">
                                        </div>
                                      </div>
                                      <div class="col-4">
                                        <div class="form-group" id="d_{{ $key }}">
                                          <span class="day2" id="day2_{{ $key }}">Day(s): </span>
                                            <input type="number" placeholder="Per Day" name="expense_day[]" id="day_{{ $key }}"
                                                class="form-control">
                                        </div>
                                      </div>
                                      @elseif($exp['type_id'] == 2)
                                      <div class="col-4">
                                        <div class="form-group" id="k_{{ $key }}">
                                          <span class="kg2" id="kg2_{{ $key }}">Kg: </span>
                                            <input readonly type="number" placeholder="Per Kg" name="expense_kg[]" id="kg_{{ $key }}"
                                                class="form-control">
                                        </div>
                                      </div>
                                      @elseif($exp['type_id'] == 3)
                                      <div class="col-4">
                                        <div class="form-group" id="d_{{ $key }}">
                                          <span class="day2" id="day2_{{ $key }}">Day(s): </span>
                                            <input type="number" placeholder="Per Day" name="expense_day[]" id="day_{{ $key }}"
                                                class="form-control">
                                        </div>
                                      </div>
                                      @endif
                                    </div>
                                    <div class="row">
                                      <div class="col-6 sub_total" id="sub_total_{{ $key }}">
                                        <span><b>Sub Total: </b></span>
                                        <span>RM </span>
                                        <span class="sub_total_result{{ $key }}" ></span>
                                        <input hidden name="sub_total[]" class="sub_total_result_inp{{ $key }}" id="sub_total">
                                      </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="id" id="do_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Expense --}}
@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.do_whatsapp').on('click',function(){
            let invoice_id = $(this).attr('data-id');
            let delivery_order_id = $(this).attr('data-order-id');
            let slug = $(this).attr('data-slug');
            let message = $(this).attr('data-message-template-id');
            let customer_category_slug = $(this).attr('data-customer-category-slug');
            let details = '';

            $.ajax({
                url: "{{ route('ajax_get_mobile_no_by_do_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    do_id: delivery_order_id
                },
                success: function(e) {
                    details += '<table class="table table-nowrap">';
                    if(e.customer_name != null && e.customer_mobile_no != null){
                    details += '<tr>';
                    details += '<td style="border-top: none;">Customer Name: ' + e.customer_name +'</td>';
                    details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+delivery_order_id+'/'+slug+'/'+message+'/'+e.customer_mobile_no+'" target="_blank" class="btn btn-success send" id="customerdo-whatsapp">Send</a></td>'
                    details += '</tr>';
                    details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                    details += '<td style="border-top: none;">Customer Mobile: '+ e.customer_mobile_no + '</td>';
                    details += '</tr>';
                    } else{
                        details += '';
                    }
                    if(e.company_acc_name != null && e.company_acc_mobile_no != null){
                    details += '<td style="border-top: none;">Company Account Name: '+ e.company_name +'</td>';
                    details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+delivery_order_id+'/'+slug+'/'+message+'/'+e.company_mobile_no+'" target="_blank" class="btn btn-success send" id="companydo-whatsapp">Send</a></td>'
                    details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                    details += '<td style="border-top: none;">Company Account Mobile: '+ e.company_acc_mobile_no +'</td>';
                    details += '</tr>';
                    }else{
                        details += '';
                    }
                    if(customer_category_slug == 'cash'){
                        if(e.customer_pic_name != null && e.customer_pic_mobile_no != null){
                            details += '<tr>';
                            details += '<td style="border-top: none;">PIC Name: ' + e.customer_pic_name +'</td>';
                            details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+delivery_order_id+'/'+slug+'/'+message+'/'+e.customer_pic_mobile_no+'" target="_blank" class="btn btn-success send" id="customerdo-whatsapp">Send</a></td>'
                            details += '</tr>';
                            details += '<tr>';
                            details += '<td style="border-top: none;">PIC Mobile: '+ e.customer_pic_mobile_no + '</td>';
                            details += '</tr>';
                        }
                    }
                    details += '</table>';

                    $("#companydo-whatsapp").attr("href","../message-template/send_whatsapp_template/"+delivery_order_id+"/"+slug+"/"+message+"/"+e.company_acc_mobile_no);
                    $("#customerdo-whatsapp").attr("href","../message-template/send_whatsapp_template/"+delivery_order_id+"/"+slug+"/"+message+"/"+e.customer_mobile_no);
                    $('.whatsapp_body').html(details);
                },
                error: function(e) {
                    console.log(e);
                    alert(e);
                }
            });
        })

        $('.view_images').on('click',function(){
            let delivery_order_id = $(this).attr('data-id');
            let delivery_order_item_media = $(this).attr('data-order-item-media');
            let product_name = $(this).attr('data-product-name');
            let delivery_order_item_quantity = $(this).attr('data-qty');
            let details = '';
            let row = '';

            details +='<div class="row">';
            details += '<div class="col-md-12 col-sm-12 style=" border-style: groove;">';
            $('.view_images_body').html('');
            $.ajax({
                url: "{{ route('ajax_get_image_by_do_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    do_id: delivery_order_id
                },
                dataType: "json",
                success: function(e) {

                    details += '<table class="table small-table">';
                    e.items.forEach(element => {
                        details += '<tr>';
                        if(element.media_url != null){
                            details += '<td><div class="row">';
                            element.media_url.forEach(media => {
                            details +='<div class="p-1">'
                                    +'<div class="img-wrap">'
                                    +'<a href="'+media+'" target="_blank" rel="noopener noreferrer"><img src="'+media+'" alt="" srcset="" width="50" height="50"></a>'
                                    +'</div>'
                                    +'</div>'
                            });
                            details+='</div></td>';
                        } else{
                            details += '<td></td>';
                        }
                        details += '<td>' + element.product.product_name + '-' + element.setting_product_size.setting_product_size_name + '<br/>'+
                                            element.delivery_order_item_quantity +'kg'
                                    +'</td>';
                        if(element.no_collect_code == 1){
                            details += '<td>No Collect Code</td>';
                         }else{
                            details += '<td>Has Collect Code '+(element.delivery_order_item_collect_no?'<br/>['+ element.delivery_order_item_collect_no+ ']':'')+'</td>';
                         }
                        details+='</tr>';
                    });

                    details += '</table>';
                    details +='</div>';
                    details += '</div>';

                    $('.view_images_body').html(details);
                    $('#view_images_modal').modal('show');
                },
                error: function(e) {
                    // console.log(e);
                    alert(e);
                }
            });
        })

        $(document).ready(function() {

            $('.sub_total').attr('disabled', 'disabled').prop('required', false).hide();
            $('.rm').attr('disabled', 'disabled').prop('required', false).hide();
            $('.kg2').attr('disabled', 'disabled').prop('required', false).hide();
            $('.day2').attr('disabled', 'disabled').prop('required', false).hide();
            $('input[name="setting_expense_value[]"]').attr('disabled', 'disabled').prop('required', false).hide();
            $('input[name="expense_kg[]"]').attr('disabled', 'disabled').prop('required', false).hide();
            $('input[name="expense_day[]"]').attr('disabled', 'disabled').prop('required', false).hide();

            get_product_by_category($('#product_category_id').val());
            get_size_by_product($('#product').val());
        });

        $('.delete').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #delivery_order_id").val(id);
		});

          $('.revert').on('click', function() {
            var id = $(this).attr('data-id');
            $(".modal-body #delivery_order_id_revert").val(id);
          });

        $('.button_status').on('click', function() {
            var value_transaction = this.value;

            $('#hidden_order_value').val(value_transaction);
            $('#search').click();
        });

        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });

        $('.issue_invoice').click(function() {
            $('#insertOrder').html('<tr><td class="text-center" colspan="5">Loading...</td></tr>');
            let customer_id = $(this).data('customer');
            let date = $(this).data('do_date');
            let land = $(this).data('land');
            let type = $(this).data('type');
            // show_date = new Date(date)
            // $('#date').html('Date: <b>' + show_date.getDate() + '-' + (show_date.getMonth() + 1) + '-' + show_date
            //     .getFullYear() + '</b>');
            $('#date').html('Date : <b>' + date + '</b>');

            if (type == 1) {
                $('#type').html('Customer');
            } else {
                $('#type').html('Warehouse');
            }



            $.ajax({
                url: "{{ route('ajax_find_delivery_with_customer_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: customer_id,
                    date: date,
                    land: land,
                    type: type,
                    invoice: null
                },
                success: function(e) {
                    let tb = '';
                    e.forEach(element => {
                        let do_item = '';
                        let total = 0;
                        element.items.forEach(item => {
                          // console.log(item);
                            if(item[3] != null){
                              do_item += item[0] + ' - ' + item[2] + ' - <b>' + item[1] +
                                  ' KG</b> - RM ' + item[3] + '<br>';
                              total += item[1] * item[3];
                            }else{
                              do_item += item[0] + ' - ' + item[2] + ' - <b>' + item[1] +
                                  ' KG</b><br>';
                            }
                        });
                        // console.log(new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total.toFixed(2)));
                        tb += '<tr>';
                        tb += '<td>#' + element.delivery_order_no;
                        if(total != 0){
                          tb += '</br><b>Total:</b> RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total.toFixed(2));
                        }
                        tb += '</td>';
                        if (type == 1) {
                            tb += '<td>' + element.customer[0][0] + '<br>' + element.customer[0]
                                [1] + '</td>';
                        } else {
                            if (element.warehouse != null) {
                                tb += '<td>' + element.warehouse.warehouse_name + '</td>';
                            } else {
                                tb += '<td> - </td>';
                            }
                        }
                        tb += '<td>' + do_item + '</td>';
                        if (element.expense.length > 0) {
                            if (element.expense.length > 1) {
                                let total_expense = 0;
                                let setting_expense_name = '';
                                element.expense.forEach(ex => {
                                    setting_expense_name += '' + ex[0] + '<br> RM ' +
                                        parseFloat(ex[1]) + ' <br>';
                                });
                                tb += '<td> ' + setting_expense_name + '</td>';
                            } else {
                                tb += '<td>' + element.expense[0][0] + ' - RM ' + element
                                    .expense[0][1] + '</td>';
                            }
                        } else {
                            tb += '<td>"No Expenses"</td>';
                        }
                        tb +=
                            '<td><input type="checkbox" name="delivery_order[]" value="' +
                            element.delivery_order_id + '"></td>'
                        tb += '</tr>';
                    });
                    $('#insertOrder').html(tb);
                },
                error: function(e) {
                    console.log(e);
                    alert(e);
                }
            })
        });



        $(document).on('change', '#product_category_id', function() {
            let product_category_id = $(this).val();
            get_product_by_category(product_category_id);
        });

        $(document).on('change', '#product', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        $('.add_exp').click(function() {
            let id = $(this).attr('data-id');
            // console.log(id);
            $('.modal-body #do_id').val(id);
            $.ajax({
                url: "{{ route('ajax_find_do_quantity_with_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    do_id: id,
                },
                success: function(e) {
                  e.forEach(element => {
                    // console.log(e);
                    $('input[name="expense_kg[]"]').val(element.delivery_order_total_quantity)
                  })
                },
                error: function(e) {
                    // console.log(e);
                    alert(e);
                }
            })
        });
        // $('#sub_total').hide();
        $('.total_result').html(0);

        $(document).on('click', 'input[name="setting_expense_id[]"]', function() {
            let val = $(this).val();
            // console.log(val);
            let id = $(this).attr('id');
            let type_id = $('#type_id' + val ).val();
            // console.log(type_id);
            $(document).on('keyup', 'input[name="setting_expense_value[]"]', function(){
            let value = $("#val_" + val).val();
            let kg = $("#kg_" + val).val();
            let day = $('#day_' + val).val();
            // let total = 0;
            if(type_id == 4 || type_id == 1){
              let sub_total = (value * kg) * day
              $('.sub_total_result' + val).html(sub_total);
              $('.sub_total_result_inp' + val).val(sub_total);
            }else if(type_id == 2){
              let sub_total = value * kg
              $('.sub_total_result' + val).html(sub_total);
              $('.sub_total_result_inp' + val).val(sub_total);
            }else if(type_id == 3){
              let sub_total = value * day
              $('.sub_total_result' + val).html(sub_total);
              $('.sub_total_result_inp' + val).val(sub_total);
            }
            // console.log($('.sub_total_result' + val).html());
            let sum = 0;
              $('input[name="sub_total[]"]').each(function() {
                let subs = $(this).val();
                if ($.isNumeric(subs)) {
                 sum += +subs;
               }
               $('.total_result').html(sum);
              })
              // console.log(sum);
             // sum += $('.sub_total_result' + val).html();
            // $('.sub_total_result' + val).html("&pound;" + parseInt(value) * parseInt($('#k_' + val).val()));
              })

            if ($(this)[0].checked == false) {
                $('#sub_total_' + val).hide();
                $('#v_' + val).hide();
                $('#rm_' + val).hide();
                $('#v_' + val).children().attr('disabled', 'disabled').prop('required', false).hide();
                $('#k_' + val).hide();
                $('#kg2_' + val).hide();
                $('#k_' + val).children().attr('disabled', 'disabled').prop('required', false).hide();
                $('#d_' + val).hide();
                $('#day2_' + val).hide();
                $('#d_' + val).children().attr('disabled', 'disabled').prop('required', false).hide();
            } else {
                $('#sub_total_' + val).show();
                $('#v_' + val).show();
                $('#rm_' + val).show();
                $('#v_' + val).children().removeAttr('disabled').prop('required', true).show();
                $('#k_' + val).show();
                $('#kg2_' + val).show();
                $('#k_' + val).children().removeAttr('disabled').prop('required', true).show();
                $('#d_' + val).show();
                $('#day2_' + val).show();
                $('#d_' + val).children().removeAttr('disabled').prop('required', true).show();
            }
        });

        $('#approve_btn').on('click', function() {
            var approve = 'approve';
            $('#do_status').val(approve);
        })
        $('#reject_btn').on('click', function() {
            var reject = 'reject';
            $('#do_status').val(reject);
        })

        $('.check_aprroval').on('click', function() {
            $('#details_body').html('Loading Details...');
            let do_id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('ajax_find_do_with_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: do_id,
                },
                success: function(e) {
                    $('#approve_btn').show();
                    $('#reject_btn').show();

                    let details = '';
                    let do_num = 'Delivery Order No';
                    let payment_option = '';

                    if (e.length > 0) {
                        e.forEach(element => {
                            do_num += element.delivery_order_no;
                            element.items.forEach(item => {
                                details += '<tr>';
                                details += '<td>' + item[0] + '</td>';
                                details += '<td>' + item[1] + '</td>';
                                details += '<td>' + item[2] + '</td>';
                                details += '<td>' + item[3] + '</td>';
                                details += '<td>' + item[4] + '</td></tr>';
                            });
                        });
                    }else {
                        details += '<tr><td colspan="4">No Records...</td></tr>';
                    }

                    $('#do_item_details').html(details);
                    $('#do_no').html(do_num);
                    $('#do_id').val(do_id);
                }
            });
        });

        function get_product_by_category(product_category_id){
            let sel_product_id = '{{ @$search['product_id'] }}' ?? null;
            let product_sel = '<option value="">Please Select Product</option>';
            $('#product').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_product_by_product_category_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_category_id: product_category_id
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (sel_product_id != null && element.id == sel_product_id) {
                                product_sel += '<option value="' + element.id + '" selected>' + element.value + '</option>';
                            } else {
                                product_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });
                    }
                    $('#product').html(product_sel);
                }
            });
        }

        function get_size_by_product(product_id){
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

                    if(e.data.length > 0){
                        e.data.forEach(function(p) {
                            if (sel_size_id != null && p.id == sel_size_id) {
                                option += '<option value="' + p.id + '" selected>' + p.value + '</option>';

                            }else{
                                option += '<option value="' + p.id + '">' + p.value + '</option>';
                            }
                        });
                    }
                    $('#size_id').html(option);
                }
            });
        }

        @if (@$search['company'] != null)
            get_land_user('{{ $search['company'] }}');

            @if (@$search['customer_category_id'] != null)
                get_customer_by_category ('{{ $search['customer_category_id'] }}', '{{ $search['company'] }}')
            @endif

        @else
            get_land_user('{{ auth()->user()->company_id }}');
            @if (@$search['customer_category_id'] != null)
            console.log("kwek");
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
            console.log(category, company);

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
                    console.log(e);
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
                    console.log(e.customer);
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
                    // console.log(scust_cat);
                    if (!scust_cat){
                        console.log(scust_cat);
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
