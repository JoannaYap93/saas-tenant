@extends('layouts.master')

@section('title')
    {{ $type }} Delivery Order
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/jquery-datetimepicker/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        .img-wrap {
            position: relative;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19);
            border-radius: 10px;
        }

        .img-wrap .del_image {
            position: absolute;
            top: -3px;
            right: -1px;
            z-index: 100;
            padding: 5px 2px 2px;
            background-color: #FFF;
            cursor: pointer;
            opacity: .5;
            text-align: center;
            font-size: 20px;
            color: #ff4a4a;
            line-height: 10px;
            border-radius: 50%;
        }
        .img-wrap:hover .del_image {
            opacity: 1;
        }
        /* Style the Image Used to Trigger the Modal */
        #expense_item_media {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #expense_item_media:hover {opacity: 0.7;}

        /* Modal Content (Image) */
        .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: rgb(255, 255, 255);
        padding: 10px 0;
        height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
        }

        @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
        }

        /* The Close Button */
        .closeModal {
        position: fixed;
        top: 15px !important;
        right: 35px;
        color: #f1f1f1;
        font-size: 30px;
        font-weight: bold;
        transition: 0.3s;
        }

        .closeModal:hover,
        .closeModal:focus {
        color: rgb(255, 255, 255);
        text-decoration: none;
        cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
        }
        td:last-child {
            text-align: center;
        }

        .mdi-trash-can-outline {
            font-size: 1.25rem;
        }

    </style>
@endsection

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $type }} Delivery Order </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">D.O.</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $type }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ $submit }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Delivery Order Details</h4>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Customer Mobile: <span class="text-danger">*</span></label>
                                            <select required name="customer_mobile" id="customer_mobile" class="form-control customer_name_sel">
                                                @if ($type == 'Edit')
                                                    @if (@$records->customer_id != null)
                                                        <option
                                                            value="{{ @$records->customer_details->customer_mobile_no ?? @$records->customer_mobile }}"
                                                            selected="selected">
                                                            {{ @$records->customer_details->customer_id ?? @$records->customer_id }}. {{ @$records->customer_details->customer_company_name ?? @$records->company_name }} -
                                                            {{ @$records->customer_details->customer_mobile_no ?? @$records->customer_mobile}}
                                                        </option>
                                                    @else
                                                        <option value="{{ @$records->customer_mobile }}"
                                                            selected="selected">
                                                            {{ @$records->cname }} -
                                                            {{ @$records->customer_mobile }}
                                                        </option>
                                                    @endif
                                                @elseif (@$records->customer_mobile)
                                                    <option value="{{ @$records->customer_mobile }}"
                                                        selected="selected">
                                                        {{ @$records->cname }} -
                                                        {{ @$records->customer_mobile }}
                                                    </option>
                                                @endif
                                                <option></option>
                                            </select>
                                            <input type="hidden" name="customer_id"
                                                value="{{ @$records->customer_id }}" id="customer_id">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Company Land: <span class="text-danger">*</span></label>
                                            <select class="form-control" name="company_land_id" id="company_land_id"
                                                required>
                                                @foreach ($land as $ldk => $ld)
                                                    <option value="{{ $ldk }}"
                                                        @if ($ldk == @$records->company_land_id) selected @endif>
                                                        {{ $ld }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">PIC IC: <span class="text-danger">*</span></label>
                                            <select required name="customer_ic" id="customer_ic" class="form-control customer_ic_sel">
                                              @if($type == 'Edit')
                                                  @if(@$records->customer_ic != null)
                                                      <option
                                                          value="{{ @$records->customer_ic }}"
                                                          selected="selected">
                                                          {{ @$records->customer_name }} -
                                                          {{ @$records->customer_ic }}
                                                      </option>
                                                  @endif
                                              @endif
                                              <option></option>
                                            </select>
                                            <!-- <input type="text" name="customer_ic" id="" maxlength="12"
                                                class="form-control customer_ic" value="{{ @$records->customer_ic }}"
                                                placeholder="IC without '-'"> -->
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">PIC Name: <span class="text-danger"
                                                    id="pic_name">*</span></label>
                                            <h5 id="customer_name" class="m-0">
                                                {{ @$records->customer_name }}
                                            </h5>
                                            <input type="text" name="customer_name" id="customer_name_input"
                                                class="form-control" value="{{ @$records->customer_name }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">PIC Mobile: <span class="text-danger"
                                                    id="pic_mobile">*</span></label>
                                            <h5 id="customer_mobile_no" class="m-0">
                                                {{ @$records->customer_mobile_no }}
                                            </h5>
                                            <input type="text" name="customer_mobile_no" id="customer_mobile_no_input"
                                                class="form-control" value="{{ @$records->customer_mobile_no }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                      <div class="form-group">
                                          <label for="user_dob">Delivery Order Created: </label>
                                          <div class="input-group">
                                            @php
                                              $current_date = date('Y-m-d');
                                            @endphp
                                              <!-- <input name="delivery_order_created" onclick="return false;" class="form-control input-mask" id="datetimepicker" value="{{@$records->delivery_order_created ?? $current_date}}">
                                              <span class="input-group-append">
                                                  <span class="input-group-text">
                                                      <i class="mdi mdi-calendar"></i>
                                                  </span>
                                              </span> -->
                                              <div class="input-group">
                                                  <input name="delivery_order_created"
                                                  class="form-control input-mask"
                                                  id="datepicker" value="{{ @$records->delivery_order_created ? substr(@$records->delivery_order_created, 0, 10) : $current_date }}"
                                                  @if(@$type == 'Edit' && (@$records->sync_id != 0 || @$records->sync_id != null))
                                                    readonly @else
                                                    data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                                                    @endif>
                                                  <span class="input-group-append">
                                                      <span class="input-group-text">
                                                          <i class="mdi mdi-calendar"></i>
                                                      </span>
                                                  </span>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Delivery Order Remark: </label>
                                            <textarea name="delivery_order_remark" id="summernote"
                                                class="form-control" maxlength="500">{{@$records->delivery_order_remark}}</textarea>
                                            <span id="total-caracteres"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="new_user">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- items --}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title d-flex align-items-center justify-content-between">Delivery Items Details
                            <button class="btn btn-outline-primary btn-sm add_product" data-target="#add_product_modal"
                                data-toggle="modal" id="add_product">Add
                                Product</button>
                        </h4>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <th style="width: 20%">Item Name</th>
                                            <th style="width: 15%">Grade</th>
                                            <th style="width: 15%">Weight (KG)</th>
                                            <th style="width: 15%">Collect Code</th>
                                            <th style="width: 15%; text-align:center;">
                                              <input type="checkbox" class="sel_all" title="Select All"><br>
                                                No Collect Code
                                            </th>
                                            <th style="width: 20%">Image</th>
                                            <th style="width: 15%; text-align:center">Action</th>
                                        </thead>
                                        <tbody>
                                            @if ($type == 'Edit' && @$records->delivery_order_items)
                                                @foreach ($records->delivery_order_items as $k => $item)
                                                    <tr id="product_{{ $item->product_id }}_{{ $k }}">
                                                        <td>
                                                          <select name="product_id_{{$item->delivery_order_item_id}}" id="product_id{{$k}}" class="form-control edit_item_prod_sel">
                                                              @foreach ($products as $prod)
                                                                  <option
                                                                      @if ($item->product_id == $prod->product_id) selected @endif
                                                                      value="{{ $prod->product_id }}">
                                                                      {{ $prod->product_name }}
                                                                  </option>
                                                              @endforeach
                                                          </select>
                                                            <input type="hidden" name="delivery_order_item_id[]"
                                                                value="{{ $item->delivery_order_item_id }}">
                                                            <!-- <input type="hidden" name="product_id_{{$item->delivery_order_item_id}}"
                                                                value="{{ $item->product_id }}"> -->
                                                                <!-- <input type="hidden" name="product_name_{{$item->delivery_order_item_id}}" value="{{$item->product->product_name}}"> -->
                                                        </td>
                                                        <td>
                                                            <select name="product_size_{{$item->delivery_order_item_id}}" class="form-control product_size_sel{{$k}}">
                                                                @foreach ($item->product->product_size_link as $size)
                                                                    <option
                                                                        @if ($item->setting_product_size_id == $size->setting_size->setting_product_size_id) selected @endif
                                                                        value="{{ $size->setting_size->setting_product_size_id }}">
                                                                        {{ $size->setting_size->setting_product_size_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="product_weight_{{$item->delivery_order_item_id}}" id=""
                                                                value="{{ number_format($item->delivery_order_item_quantity,2) }}"
                                                                class="form-control product_weight" step="0.01">
                                                        </td>
                                                        <td style="text-align: left">
                                                            #{{ $item->delivery_order_item_collect_no ?? '-' }}
                                                            <input type="hidden" name="collect_no_{{$item->delivery_order_item_id}}"
                                                                value="{{ $item->delivery_order_item_collect_no }}">
                                                        </td>
                                                        <td style="text-align: center;">
                                                          <input class="check_boxes" type="checkbox" name="no_collect_code_{{ $item->delivery_order_item_id }}" value="1" @if (@$item->no_collect_code == 1) checked onclick="return true;" @endif>
                                                        </td>

                                                        @if(@$item->hasMedia('delivery_order_item_media'))
                                                            <td><div class="row">
                                                                    @foreach ( $item->getMedia('delivery_order_item_media') as $key_media => $media )
                                                                        <div id="content_{{ $media->id }}" class="p-1">
                                                                            <div class="img-wrap">
                                                                                <span class="del_image" data-target="#delete" data-toggle="modal" data-id="{{ $media->id }}"
                                                                                    delivery_order_item_id="{{ @$item->delivery_order_item_id }}"
                                                                                    data-img-src="{{ $media->getUrl()}}">&times;</span>
                                                                                <a>
                                                                                    <img width="60" height="60"
                                                                                    data-toggle="popupModal" data-id="{{ $media->id }}" alt="{{ $media->file_name }}" class="expense_item_media" id="expense_item_media"
                                                                                    src="{{ $media->getUrl()}}">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                            </div>
                                                            <br>
                                                                <input type="file" name="delivery_order_item_media_{{ $item->delivery_order_item_id }}[]" class="form-control-file" accept="image/*" multiple>
                                                            </td>
                                                        @else
                                                        <td>
                                                            <input type="file" name="delivery_order_item_media_{{ $item->delivery_order_item_id }}[]" class="form-control-file" accept="image/*" multiple>
                                                        </td>
                                                        @endif

                                                        <td>
                                                            <i
                                                                class="mdi mdi-trash-can-outline remove waves-effect waves-light text-danger"></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr id="add_item_before"></tr>
                                            @else
                                                @if (@$records->product && count($records->product) > 0)
                                                    @for ($p = 0; $p < count($records->product); $p++)
                                                        <tr
                                                            id="product_{{ $records->product[$p]->product_id }}_{{ $p }}">
                                                            <td>
                                                                {{ @$records->product[$p]->product_name}}
                                                                <input type="hidden" name="product_id[]"
                                                                    value="{{ @$records->product[$p]->product_id }}">
                                                                <input type="hidden" name="product_name[]"
                                                                    value="{{ @$records->product[$p]->product_name }}">
                                                            </td>
                                                            <td>
                                                                <select name="product_size[]" class="form-control"
                                                                    required>
                                                                    @if (@$records->product[$p]->product_size_link)
                                                                        @foreach ($records->product[$p]->product_size_link as $size)
                                                                            <option
                                                                                value="{{ $size->setting_product_size_id }}"  @if ($size->setting_product_size_id == @$records->product_size[$p]) selected @endif>
                                                                                {{ $size->setting_size->setting_product_size_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input name="product_weight[]"
                                                                    id="product_weight{{ $p }}"
                                                                    class="form-control product_weight" type="number" required
                                                                    value="{{ @$records->product_weight[$p] }}"
                                                                    step="0.01">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="collect_no[]"
                                                                    class="form-control"
                                                                    id="collect_no{{ $p }}"
                                                                    value="{{ @$records->collect_no[$p] }}">
                                                            </td>
                                                            <td style="text-align: center;">
                                                              <input class="custom-control-input" type="checkbox" name="no_collect_code[]" value="1" @if (@$records->no_collect_code == 1) checked @endif>;

                                                            </td>
                                                            <td>
                                                                <input type="file" name="delivery_order_item_media_{{ $records->product[$p]->product_id }}_{{ $p }}" class="form-control-file mt-2">
                                                            </td>
                                                            <td>
                                                                <i
                                                                    class="mdi mdi-trash-can-outline remove waves-effect waves-light text-danger"></i>
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                @endif
                                                <tr id="add_item_before">
                                                    <td colspan="6">
                                                        @if ($product != true)
                                                            <span class="text-danger">Please add a product to
                                                                continue.</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                              <div class="col-12 d-flex justify-content-end">
                                  @if (@$records->invoice_id == null)
                                      <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                      @if ($type == 'Edit' && @$records->delivery_order_status_id != 2)
                                          <button type="button" class="btn btn-success mr-2 approve"
                                              data-target="#approve_do" data-toggle="modal">Approve</button>
                                      @endif
                                  @endif
                                  <a href="{{ route('do_listing') }}" class="btn btn-secondary">Cancel</a>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}

            </div>
            {{--  --}}
            {{--  --}}
            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">D.O. Type <span class="text-danger">*</span></label>
                                    {!! Form::select('delivery_order_type_id', $order_type, @$records->delivery_order_type_id, ['class' => 'form-control', 'id' => 'do_type', 'required']) !!}
                                </div>
                                {{-- @if (@$warehouse && count($warehouse) > 1) --}}
                                    <div class="form-group" id="warehouse">
                                        <label for="">Warehouse: <span class="text-danger">*</span></label>
                                        {!! Form::select('warehouse_id', $warehouse, @$records->warehouse_id, ['class' => 'form-control', 'id' => 'warehouse_id']) !!}
                                        <input type="text" name="warehouse_name" placeholder="Enter new warehouse"
                                            autocomplete="off" id="warehouse_name" class="form-control"
                                            value="{{ @$records->warehouse_name }}">
                                    </div>
                                {{-- @else
                                    <div class="form-group" id="warehouse">
                                        <label for="">Warehouse: <span class="text-danger">*</span></label>
                                        <input type="text" name="warehouse_name" placeholder="Enter new warehouse"
                                            autocomplete="off" id="warehouse_name" class="form-control"
                                            value="{{ @$records->warehouse_name }}">
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                        <div class="row" id="expenses">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Expenses: </label>
                                    <div class="form-group row">
                                        @php
                                            $doe_exist = [];
                                            if ($type == 'Edit' && @$records->delivery_order_expense) {
                                                foreach ($records->delivery_order_expense as $key => $dxp) {
                                                    $doe_exist[$dxp->setting_expense_id] = $dxp->setting_expense_id;
                                                }
                                            }
                                        @endphp
                                        @foreach ($expense as $e => $exp)
                                            @if ($type == 'Edit' && @$records->delivery_order_expense)
                                                <div class="col-6">
                                                    <input type="checkbox" name="expense[]" id="{{ $e }}"
                                                        value="{{ $e }}"
                                                        @if (@$doe_exist[$e] == $e) checked @endif>
                                                    <label for="{{ $e }}">{{ json_decode($exp['name'])->en }}</label>
                                                </div>
                                            @else
                                                @php
                                                    $get_ket = [];
                                                    if (@$records->expense && count($records->expense) > 0) {
                                                        foreach ($records->expense as $key => $ids) {
                                                            $get_ket[$ids] = $ids;
                                                        }
                                                    }
                                                @endphp
                                                <div class="col-6">
                                                    <input type="checkbox" name="expense[]" id="{{ $e }}"
                                                        class="col-3"
                                                        @if ($e == @$get_ket[$e]) checked @endif
                                                        value="{{ $e }}">
                                                    <label for="{{ $e }}">{{ json_decode($exp['name'])->en }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    @foreach ($expense as $ek => $exps)
                                        <div class="form-group" id="ev{{ $ek }}">
                                            <label for="">{{ json_decode($exps['name'])->en }}({{ $exps['type_name']}})</label>
                                            <div class="row">
                                              <div class="col-4">
                                                <span>RM:</span>
                                                <input type="decimal" name="setting_expense_value[{{ $ek }}]"
                                                    id="expense_value_{{ $ek }}" class="form-control" disabled
                                                    value="{{ @$records->setting_expense_value[$ek] ?? $exps['value'] }}">
                                              </div>
                                              @if($exps['type_id'] == 4 || $exps['type_id'] == 1 )
                                              <div class="col-4">
                                                <span>Kg:</span>
                                                <input readonly type="decimal" name="expense_kg[{{ $ek }}]"
                                                    id="expense_kg_{{ $ek }}" class="form-control total_kg" value="{{ @$records->expense_kg[$ek] }}"
                                                  >
                                              </div>
                                              <div class="col-4">
                                                <span>Day:</span>
                                                <input type="number" name="expense_day[{{ $ek }}]"
                                                    id="expense_day_{{ $ek }}" class="form-control" value="{{ @$records->expense_day[$ek] }}"
                                                  >
                                              </div>
                                              @elseif ($exps['type_id'] == 2)
                                              <div class="col-4">
                                                <span>Kg:</span>
                                                <input readonly type="decimal" name="expense_kg[{{ $ek }}]"
                                                    id="expense_kg_{{ $ek }}" class="form-control total_kg" value="{{ @$records->expense_kg[$ek] }}"
                                                  >
                                              </div>
                                              @elseif ($exps['type_id'] == 3)
                                              <div class="col-4">
                                                <span>Day:</span>
                                                <input type="number" name="expense_day[{{ $ek }}]"
                                                    id="expense_day_{{ $ek }}" class="form-control" value="{{ @$records->expense_day[$ek] }}"
                                                  >
                                              </div>
                                              @endif
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                @if (@$records->invoice_id == null)
                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    @if ($type == 'Edit' && @$records->delivery_order_status_id != 2)
                                        <button type="button" class="btn btn-success mr-2 approve"
                                            data-target="#approve_do" data-toggle="modal">Approve</button>
                                    @endif
                                @endif
                                <a href="{{ route('do_listing') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            {{--  --}}
        </div>
    </form>

    <!-- Modal -->
    {{-- Add Product Modal --}}
    <div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <h4>Find a product</h4>
                        <input type="text" name="product_name" id="product_name"
                            class="form-control product_autocomplete" placeholder="Enter Product Name..."
                            autocomplete="off">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <th>Name</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="show_product">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Approve Do --}}
    <div class="modal fade" id="approve_do" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('do_approve') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Confirm Approval Of This Delivery Order?</h4>
                        <input type="hidden" name="do_id" value="{{ @$records->delivery_order_id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Approve</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- modal popup images -->
        <div id="popupModal" class="modal" tabindex="-1" role="dialog" >
            <span class="closeModal" data-dismiss="modal">&times;</span>
            <div class="modal-content image_wrap"></div>
            <div id="caption"></div>
        </div>

         <!-- Delete Modal -->
         <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form>
                        @csrf
                        <div class="modal-body">
                            <h5>Are you sure want to delete this image ?</h5>
                            <div class="image_div_delete" align="center">

                            </div>
                            <input type="hidden" name="media_id" id="media_id">
                            <input type="hidden" name="do_item_id" id="do_item_id">
                        </div>
                        <div class="modal-footer">
                            <span id="del_button" class="btn btn-danger" data-dismiss="modal">Delete</span>
                            <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Details Images DO Item -->
        <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="" method="POST">
                        @csrf
                        <div class="modal-header">
                            <b>Delivery Order Item Images</b>
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
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/jquery-datetimepicker/jquery.datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <script>

        let product_array = [];
        let count = 0;
        let k = 0;
        let customer_details = @php echo json_encode(@$records->customer_details) @endphp ?? [];
        let table_row = 1;

        $(document).ready(function() {
            $.datetimepicker.setLocale('en');
            $.datetimepicker.setDateFormatter('moment');
            $(function () {
                var dateNow = new Date();
                $('#datetimepicker').datetimepicker({
                    format: 'Y-MM-DD HH:mm:ss',
                });
            });

            $('.sel_all').mouseover(function() {
            $(this).attr("title");
        });

        $('.sel_all').on('change', function(event) {
            $('.check_boxes').trigger('click');
        });

        $('.edit_item_prod_sel').on('change', function(){
            let sel_id = $(this).attr('id').substring(10);
            let product_id = $(this).val();
            $.ajax({
                url: "{{ route('ajax_get_product_size_edit_do') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: product_id,
                },
                success: function(e) {
                    var option = '';
                    for (let a = 0; a < e.data.length; a++) {
                        if (e.data[a]['size_id'] != null) {
                            option += '<option value="' + e.data[a]['size_id'] + '">' + e.data[a]['size_name'] +
                                '</option>';
                        } else {
                            option = '<option value="">No Grade</option>';
                        }
                    }
                    $('.product_size_sel'+ sel_id).empty().append(option)
                },
                error: function(e) {
                    alert('An Error Occurred! Please try again. ');
                }
            })
        })

        @if ($type == 'Edit')
            // to trigger the delete modal
            $('.del_image').on('click',function(){
                var id = $(this).attr('data-id');
                var do_item_id = $(this).attr('delivery_order_item_id');
                var img_src = $(this).attr('data-img-src');
                let img = '<img class="img-wrap" width="150" height="150" src="'+img_src+'" id="'+id+'">';
                $('#delete').modal('show');
                $('.image_div_delete').html(img);
                $('.modal-body #media_id').val(id);
                $('.modal-body #do_item_id').val(do_item_id);

            });

            // when click delete button, it will delete image
            $('#del_button').on('click',function(){
                let media_id = $('.modal-body #media_id').val();
                let delivery_order_item_id =$('.modal-body #do_item_id').val();

                $.ajax({
                    url: "{{ route('ajax_delete_image_by_media_do_item_id') }}",
                    method: "POST",
                    data: {
                            _token: "{{ csrf_token() }}",
                            media_id: media_id,
                            delivery_order_item_id: delivery_order_item_id,
                    },
                    success: function(e){
                        $('#content_'+media_id).remove();
                    },
                    error: function(e) {
                        alert(e);
                    }
                });
            });


            // to trigger modal view image in full size
            var captionText = document.getElementById("caption");
            $('.expense_item_media').on('click',function(){

                let media_id = $(this).attr('data-id');
                let source = $(this).attr('src');
                let caption = +$(this).attr('alt');
                let img = '<img src="'+source+'" id="'+media_id+'">';
                $('.image_wrap').html(img);
                captionText.innerHTML = this.alt;
                $('#popupModal').modal('show');

            });
        @endif

        $(document).on('keyup', 'input[name="product_weight[]"]', function(){
            let input_id = $(this).attr('id').substring(14);
            let quantity = $(this).val();
            let date = $('#datepicker').val();
            let product_id = $('#product_id' + input_id).val();
            let size_id = $('#product_size' + input_id).val();
            let company_land_id = $('#company_land_id').val();
            let company_id = <?php echo json_encode(auth()->user()->company_id)?>

            $.ajax({
                url: "{{ route('ajax_get_collect_code_from_do') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_id: company_id,
                    company_land_id: company_land_id,
                    product_id: product_id,
                    size_id: size_id,
                    date: date,
                },
                success: function(e) {
                    if(e == 1){
                        $('#no_collect_code' + input_id).attr('disabled', true);
                        $('#collect_no_word' + input_id).attr('hidden', false);
                    }else{
                        let force_collect = <?php echo json_encode(auth()->user()->company?->company_force_collect)?>;
                        $('#collect_no_word' + input_id).attr('hidden', true);
                        $('#no_collect_code' + input_id).val(1).attr({disabled: false});
                        $('#no_collect_code' + input_id).val(1).attr({disabled: false});
                    }
                },
                error: function(e) {
                    alert('An Error Occurred! Please try again. ');
                }
            })
        })
        $('#warehouse_name').hide();

        @foreach ($expense as $ke => $kexp)
            $('#ev{{ $ke }}').hide();
        @endforeach

        $('#customer_name_input').hide();
        $('#customer_mobile_no_input').hide();

        @if ($type == 'Edit' && @$records->delivery_order_type_id == 1)
            $('#expenses').hide();
            $('#warehouse').hide();
            $('#warehouse_name').hide().removeAttr('required');
        @elseif (@$records->delivery_order_type_id && $records->delivery_order_type_id == 2)
            $('#expenses').show();
            $('#warehouse').show();
            $('#warehouse_id').show();
            $('#warehouse_id').val(customer_details.warehouse_id).change();
            if (customer_details.warehouse_id != 0 && customer_details.warehouse_id != null) {
            $('#warehouse_id option').not('option[value="' + customer_details.warehouse_id + '"]').each(
            function(index) {
                $(this).prop('disabled', true);
            });
            $('#warehouse_name').hide().removeAttr('required');
            } else {
            $('#warehouse_name').show();
            $('#warehouse_name').prop('required', true);
            $('#warehouse_id').hide();
            }
        @else
            $('#expenses').hide();
            $('#warehouse').hide();
            $('#warehouse_name').hide().removeAttr('required');
        @endif

        @if ($type == 'Edit' && @$records->delivery_order_expense)
            @foreach ($records->delivery_order_expense as $exp)
                $('#ev{{ $exp->expense_id }}').show();
                $('#ev{{ $exp->expense_id }}').find('#expense_value_{{ $exp->expense_id }}').val({{ $exp->delivery_order_expense_value }}).attr('required',
                true).prop('disabled', false);
                $('#ev{{ $exp->expense_id }}').find('#expense_kg_{{ $exp->expense_id }}').val({{ $exp->delivery_order_expense_kg }}).attr('required',
                true)
                $('#ev{{ $exp->expense_id }}').find('#expense_day_{{ $exp->expense_id }}').val({{ $exp->delivery_order_expense_day }}).attr('required',
                true).prop('disabled', false);
            @endforeach
        @elseif (@$records->expense && count($records->expense) > 0)
            @foreach ($records->expense as $key => $ids)
                $('#ev{{ $ids }}').show();
                $('#expense_value_{{ $ids }}').attr('required', true).prop('disabled', false);
            @endforeach
        @else
            @foreach ($expense as $ke => $kexp)
                $('#ev{{ $ke }}').hide();
            @endforeach
        @endif

        $('#customer_mobile').select2({
            ajax: {
                minimumResultsForSearch: -1,
                url: "{{ route('ajax_search_customer_by_mobile_no') }}",
                dataType: 'json',
                data: function(p) {
                    let query = {
                        api_token: '{{ csrf_token() }}',
                        term: p.term,

                    }
                    return query;
                },
                processResult: function(data, p) {
                    return {
                        results: data.results,
                    }
                }
            },
            allowClear: true,
            tags: true,
            placeholder: 'Select Mobile No '
        });

        $('#customer_ic').select2({
            ajax: {
                minimumResultsForSearch: 0,
                url: "{{ route('ajax_search_customer_pic_by_id') }}",
                dataType: 'json',
                data: function(p) {
                    let query = {
                        api_token: '{{ csrf_token() }}',
                        term: p.term,
                        c_id: $('#customer_id').val(),
                    }
                    return query;
                },
                processResult: function(data, p) {
                    return {
                        results: data.results,
                    }
                }
            },
            allowClear: true,
            tags: true,
            placeholder: 'Select IC Number'
        });

        @if (@$records->product_id && count($records->product_id) > 0)
            @foreach ($records->product_id as $pid)
                product_array[{{ $pid }}] = '{{ $pid }}';
            @endforeach
        @endif

        @if ($type == 'Edit' && @$records->delivery_order_items && count($records->delivery_order_items) > 0)
            @foreach ($records->delivery_order_items as $item)
                product_array[{{ $item->product_id }}] = [{{ $item->product_id }}];
                table_row = table_row + 1;
            @endforeach
        @endif

        @if ($type == 'Edit')
            $('#add_product').show();
            $('#company_land_id option').not('option[value="' + $('#company_land_id').val() + '"]').each(
                function(index) {
                    let delivery_order = <?php echo json_encode(@$records); ?>;
                    let edit_land = <?php echo json_encode(auth()->user()->can('delivery_order_company_land_edit')); ?>;
                    console.log(edit_land);
                    if(delivery_order.delivery_order_status_id == 2){
                    if(edit_land == true){
                        $(this).prop('disabled', false);
                    }else{
                        $(this).prop('disabled', true);
                    }
                    }else{
                    $(this).prop('disabled', true);
                    }
                }
            );
        @else
            $('#add_product').hide();
        @endif
        });
        @if ($type == 'Edit' && $records->customer_id == null)
            customer_null();
            $('#customer_name_input').show();
            $('#customer_mobile_no_input').show()''
            $('#customer_name').html('');
            $('#customer_mobile_no').html('');
        @endif

        $('.customer_name_sel').select2({
            maximumInputLength: 10,
            tags: true,
            tokenSeparators: [','],
            createTag: function(params) {
                let term = $.trim(params.term);
                return {
                    id: term,
                    text: term,
                    newTag: true,
                }
            },
        });

        $('#customer_ic').select2({
            maximumInputLength: 10,
            tags: true,
            tokenSeparators: [','],
            createTag: function(params) {
                let term = $.trim(params.term);
                console.log(term);
                return {
                    ic: term,
                    newTag: true,
                }
            },
        });

        $('#company_land_id').on('change', function() {
            if ($(this).val() != 0 && $(this).val() > 0) {
                $('#add_product').show();
            } else {
                $('#add_product').hide();
            }
        });

        $('.add_product').click(function(e) {
            e.preventDefault();
            $('.product_autocomplete').val('');
        });

        $('.product_autocomplete').on('keyup', function() {
            var value = $(this).val();

            $.ajax({
                url: "{{ route('ajax_product_by_name') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_name: $(this).val(),
                    land: $('#company_land_id').val()
                },
                success: function(e) {
                    var show = '';
                    e.data.forEach((element, ix) => {

                        show += '<tr id=products_' + element.id + '>';
                        show += '<td>' + element.label + '</td>';
                        show += '<td class="d-flex"><span id="btn_' + element.id +
                            '" class="m-auto">' +
                            '<a href="javascript:void(0);" class="btn btn-sm btn-outline-success waves-effect waves-light" onclick="add_product(' +
                            element.id + ')">' +
                            '<i class="fas fa-plus"></i></a></span><span id="load_' + element
                            .id + '" class=" load text-center m-auto">Loading...</span></td>';

                        show += '</tr>';
                    });

                    if (value == '') {
                        $('#show_product').html('');
                    } else {
                        $('#show_product').html(show);
                    }

                    $('.load').hide();
                },
                error: function(e) {
                    alert('An Error Occurred! Please try again. ');
                }
            })
        });

        function add_product(ui_item_id) {
            $('#btn_' + ui_item_id).hide();
            $('#load_' + ui_item_id).show();

            let unique_code = '{{ auth()->user()->user_unique_code }}';
            $.ajax({
                url: "{{ route('ajax_get_product_detail') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: ui_item_id,
                },
                success: function(e) {
                    if (e.status) {
                        count++;

                        var option = '';
                        for (let a = 0; a < e.size.length; a++) {
                            if (e.size[a]['id'] != null) {
                                option += '<option value="' + e.size[a]['id'] + '">' + e.size[a]['name'] +
                                    '</option>';
                            } else {
                                option = '<option value="">No Grade</option>';
                            }
                        }
                        let require =
                            "{{ auth()->user()->company?->company_force_collect ? 'required' : 'placeholder=\"optional\"' }}";
                        var append = '<tr id="product_' + e.data.product_id +
                            '_' + count + '"><td>' + e.data.product_name +
                            '<input type="hidden" name="product_id[]" id="product_id' + count + '" value="' + e.data.product_id + '">' +
                            '<input type="hidden" name="product_name[]" value="' + e.data.product_name +
                            '"></td>' +
                            '<td><select name="product_size[]" id="product_size' + count + '" class="form-control">' + option +
                            '</select></td>' +
                            '<td><input name="product_weight[]" id="product_weight' + count +
                            '" class="form-control" type="number" required step=".01"></td>' +
                            '<td><input type="text" name ="collect_no[]" id="collect_no' + count + '" class="form-control" ' + require +
                            '><span class="collect_no_word" hidden id="collect_no_word' + count + '" style="font-style: italic; font-size: 10px;">Collect exist.</span></td>' +
                            '<td style="text-align:center;" {{ auth()->user()->company?->company_force_collect ? 'hidden' : ''}}><input class="check_boxes" type="checkbox" value="1" id="no_collect_code' + count + '" name="no_collect_code[]"></td>' +
                            '<td><input type="file" name="delivery_order_item_media_' + e.data.product_id + '_' + k + '[]" class="form-control-file" accept="image/*" multiple></td>' +
                            '<td><i class="mdi mdi-trash-can-outline remove waves-effect waves-light text-danger"></i></td></tr>';
                        product_array[e.data.product_id] = [e.data.product_id];

                        $(append).insertBefore('#add_item_before');
                        $('#load_' + ui_item_id).hide();
                        $('#btn_' + ui_item_id).show();
                        table_row = table_row + 1;
                        k++;
                        get_update_table();
                    }
                }
            })
        }

        $(document).on('click', '.remove', function() {
            let item_id = $(this).parent().parent().attr('id');
            $('#' + item_id).remove();
            table_row = table_row - 1;
            get_update_table();
        });

        $('#customer_mobile').on('change', function() {
            $("#customer_ic").empty();
            var value = $(this).val();
            $.ajax({
                url: "{{ route('ajax_search_customer_by_mobile_no') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_mobile_no: value,
                },
                success: function(e) {
                    if (e.data[0] != null) {
                        $('#customer_name_input').hide();
                        $('#customer_mobile_no_input').hide();
                        $('#customer_id').val(e.data[0].id);
                        $('#new_user').html('');
                        customer_details = e.data[0];
                        if(e.data[0].type != 10){
                            $('#do_type').val(1);
                            $('#expenses').hide();
                            $('#warehouse').hide();
                            $('#warehouse_name').removeAttr('required');
                        }else{
                            $('#do_type').val(2);
                            $('#expenses').show();
                            $('#warehouse').show();
                        }
                        get_warehouse();
                    } else {
                        $('#customer_name_input').show();
                        $('#customer_mobile_no_input').show();
                        $('#customer_name').html('');
                        $('#customer_mobile_no').html('');
                        $('#customer_id').val('');
                        let user = '';
                        user += '<div class="col-6">';
                        user += '<div class="form-group"><label>Email</label>';
                        user +=
                            '<input type="email" name="email" class="form-control" required value="{{ @$records->email }}"></div></div>';
                        user += '<div class="col-6">';
                        user += '<div class="form-group"><label>Name</label>';
                        user +=
                            '<input type="text" name="cname" class="form-control" required value="{{ @$records->cname }}" ></div></div>';
                        user += '<div class="col-4">';
                        user += '<div class="form-group"><label>Address 1</label>';
                        user +=
                            '<input type="text" name="address1" class="form-control" required value="{{ @$records->address1 }}"></div>';
                        user += '<div class="form-group"><label>State</label>';
                        user +=
                            '<input type="text" name="state" class="form-control" required value="{{ @$records->state }}"></div>';
                        user += '</div>';
                        user += '<div class="col-4">';
                        user += '<div class="form-group"><label>Address 2</label>';
                        user +=
                            '<input type="text" name="address2" class="form-control" value="{{ @$records->address2 }}"></div>';
                        user += '<div class="form-group"><label>Postcode</label>';
                        user +=
                            '<input type="text" name="postcode" class="form-control" required value="{{ @$records->postcode }}"></div>';
                        user += '</div>';
                        user += '<div class="col-4">';
                        user += '<div class="form-group"><label>City</label>';
                        user +=
                            '<input type="text" name="city" class="form-control" required value="{{ @$records->city }}"></div>';
                        user += '<div class="form-group"><label>Country</label>';
                        user +=
                            '<input type="text" name="country" class="form-control" required value="{{ @$records->country }}"></div>';
                        user += '</div>';
                        user += '</div>';
                        $('#new_user').html(user);
                        customer_details = [];
                        get_warehouse();
                    }
                }
            })
        });

        $('#do_type').on('change', function() {
            let type = $(this).val();
            if (type == 2) {
                $('#expenses').show();
                $('#warehouse').show();
                get_warehouse();
            } else if (type == 1) {
                $('#expenses').hide();
                $('#warehouse').hide();
                $('#warehouse_name').removeAttr('required');
            } else {
                $('#expenses').hide();
                $('#warehouse').hide();
            }
        });


        $('input[type=checkbox]').on('change', function() {
            let expense_id = $(this).val();

            if (expense_id > 0) {
                if ($(this)[0].checked == false) {
                    $('#ev' + expense_id).hide();
                    $('#expense_value_' + expense_id).prop('required', false).prop('disabled', true);
                } else {
                    $('#ev' + expense_id).show();
                    $('#expense_value_' + expense_id).prop('required', true).prop('disabled', false);
                }
            }
        });

        $('#customer_ic').on('change', function() {
            let value = $(this).val();

            $.ajax({
                url: "{{ route('ajax_search_customer_pic_by_id') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_ic: value,
                    c_id: $('#customer_id').val(),
                },
                success: function(e) {
                    if (e.data[0] != null) {
                        console.log(e.data[0]);
                        $('#customer_name').html(e.data[0].pic_name).show();
                        $('#customer_mobile_no').html(e.data[0].pic_mobile).show();
                        $('#customer_name_input').val(e.data[0].pic_name).hide();
                        $('#customer_mobile_no_input').val(e.data[0].pic_mobile).hide();

                    } else {
                        $('#customer_name').html('').hide();
                        $('#customer_mobile_no').html('').hide();
                        $('#customer_name_input').val('').show();
                        $('#customer_mobile_no_input').val('').show();
                    }
                }
            })
        });

        function get_update_table() {
            if (table_row <= 1) {
                $('#company_land_id option').each(
                    function(index) {
                        $(this).prop('disabled', false);
                    }
                );
            } else {
                $('#company_land_id option').not('option[value="' + $('#company_land_id').val() + '"]').each(
                    function(index) {
                        $(this).prop('disabled', true);
                    }
                );
            }
        }

        function get_warehouse() {
            $('#warehouse_id').val(customer_details.warehouse).change();
            if (customer_details.warehouse != 0 && customer_details.warehouse != null) {
                $('#warehouse_id').show();
                $('#warehouse_id option').not('option[value="' + customer_details.warehouse + '"]').each(
                    function(index) {
                        $(this).prop('disabled', true);
                    });
                $('#warehouse_id option[value="' + customer_details.warehouse + '"]').prop('disabled', false);
                $('#warehouse_name').hide().removeAttr('required');
            } else {
                $('#warehouse_name').show().val('No Warehouse').prop('readonly', true);
                $('#warehouse_id').hide();
            }
        }

        function customer_null() {
            let user = '';
            user += '<div class="col-6">';
            user += '<div class="form-group"><label>Email</label>';
            user +=
                '<input type="email" name="email" class="form-control" required value="{{ @$records->email }}"></div></div>';
            user += '<div class="col-6">';
            user += '<div class="form-group"><label>Name</label>';
            user +=
                '<input type="text" name="cname" class="form-control" required value="{{ @$records->cname }}" ></div></div>';
            user += '<div class="col-4">';
            user += '<div class="form-group"><label>Address 1</label>';
            user +=
                '<input type="text" name="address1" class="form-control" required value="{{ @$records->address1 }}"></div>';
            user += '<div class="form-group"><label>State</label>';
            user +=
                '<input type="text" name="state" class="form-control" required value="{{ @$records->state }}"></div>';
            user += '</div>';
            user += '<div class="col-4">';
            user += '<div class="form-group"><label>Address 2</label>';
            user += '<input type="text" name="address2" class="form-control" value="{{ @$records->address2 }}"></div>';
            user += '<div class="form-group"><label>Postcode</label>';
            user +=
                '<input type="text" name="postcode" class="form-control" required value="{{ @$records->postcode }}"></div>';
            user += '</div>';
            user += '<div class="col-4">';
            user += '<div class="form-group"><label>City</label>';
            user += '<input type="text" name="city" class="form-control" required value="{{ @$records->city }}"></div>';
            user += '<div class="form-group"><label>Country</label>';
            user +=
                '<input type="text" name="country" class="form-control" required value="{{ @$records->country }}"></div>';
            user += '</div>';
            user += '</div>';
            $('#new_user').html(user);
        }
    </script>
@endsection
