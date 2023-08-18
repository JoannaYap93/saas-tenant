@extends('layouts.master')

@section('title')
    Payment Url Listing
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
{{-- @dd($records) --}}
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Payment Url Listing</h4>
                    @can('payment_url_manage')
                        <a href="{{ route('payment_url_add', ['tenant' => tenant('id')]) }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                        class="fas fa-plus"></i> Add New</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Payment Url</a>
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
                                    class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if (!@$search['payment_url_status']) active @endif ">
                                    <span class="transaction-font">All</span>
                                    <span
                                        class="transaction-font">({{ @$count_status['default'] ? $count_status['default'] : 0 }})</span>
                                </button>

                                @for ($i = 0; $i < count($pu_status); $i++)
                                    <button type="button" value="{{ $pu_status[$i] }}"
                                        class="nav-link button_status transaction-card-col transaction-btn btn-nav-list float-left @if ($pu_status[$i] == @$search['payment_url_status']) active @endif ">
                                        <span class="transaction-font">{{ $pu_status[$i] }}</span>
                                        <span
                                            class="transaction-font">({{ @$count_status[$i] ? $count_status[$i] : 0 }})</span>
                                    </button>
                                @endfor
                                <input id="hidden_order_value" type="hidden" name="payment_url_status"
                                    class="hidden_value_id" value="{{ @$search['payment_url_status'] }}" />
                                <input id="hidden_previous_transaction_value" type="hidden" name="hidden_invoice_status_id"
                                    value="{{ @$search['payment_url_status'] }}" />
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
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="pu_from"
                                                    placeholder="Start Date" value="{{ @$search['pu_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="pu_to"
                                                    placeholder="End Date" value="{{ @$search['pu_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3" id="customer_category">
                                        <div class="form-group">
                                            <label for="">Customer Category</label>
                                            {!! Form::select('customer_category_id', $customer_category_sel, @$search['customer_category_id'], ['class' => 'form-control', 'id' => 'customer_category_id']) !!}
                                            {{-- {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Customer</label>
                                            {!! Form::select('customer_id', $customer_sel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
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
                        <table class="table">
                            <thead class="thead-light">
                                <th>#</th>
                                <th>Customer</th>
                                <th>Invoice</th>
                                <th>Status</th>
                                @can('payment_url_manage')
                                    <th>Action</th>
                                @endcan
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php
                                    $no=1;
                                    @endphp
                                    @foreach ($records as $key => $payment_url)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                @if($payment_url->customer)
                                                    {{ $payment_url->customer->customer_name }}<br>
                                                    {{ $payment_url->customer->customer_acc_name }}<br>
                                                    {{ $payment_url->customer->customer_acc_mobile_no }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (@$payment_url->payment_url_item)
                                                    @foreach ($payment_url->payment_url_item as $puikey => $pui)
                                                        <b>{{ $pui->invoice->invoice_no }}</b> -{{ $pui->invoice->invoice_grandtotal }}<br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            @php
                                                $expired = date('Y-m-d h:i A', strtotime($payment_url->payment_url_created. ' + '.$expiry_period.' days'));
                                            @endphp
                                            <td>
                                                Created: {!! date_format($payment_url->payment_url_created, 'Y-m-d h:i A') !!} <br>
                                                Expired: {{ $expired }}<br>
                                                <b>RM {{ $payment_url->payment_url_total }}</b>
                                                <br>
                                                @php
                                                    $order_log = '<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>';
                                                    if (@$payment_url->payment_url_log) {
                                                        foreach ($payment_url->payment_url_log as $key => $pul) {
                                                            $order_log .= '<tr>';
                                                            $log_user = '';
                                                            if ($pul->user_id != null) {
                                                                $order_log .= "<td>{$pul->user->user_fullname}</td>";
                                                            } else {
                                                                $order_log .= '<td>-</td>';
                                                            }
                                                            $order_log .= "<td>{$pul->payment_url_log_description}</td><td>{$pul->payment_url_log_created}</td>";
                                                            $order_log .= '</tr>';
                                                        }
                                                    } else {
                                                        $order_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                                    }
                                                    $order_log .= '</tbody></table>';
                                                    $order_log = str_replace("'", '`', $order_log);

                                                    $show_status = '';
                                                    if (@$payment_url->payment_url_status) {
                                                        $status = $payment_url->payment_url_status;
                                                        switch ($status) {
                                                            case 'Pending':
                                                                $show_status = "<span class='badge badge-warning font-size-11 log' data-toggle='modal' data-target='#log' data-log='" . json_encode($order_log) . "'>Pending</span>";
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
                                                        }
                                                    }
                                                    echo $show_status;
                                                @endphp
                                            </td>
                                            @can('payment_url_manage')
                                                <td>
                                                    <a class="btn btn-outline-primary btn-sm" target="_blank"
                                                        href="{{ env('GRAPHQL_API').'/view-payment-link/'.$payment_url->payment_url_id.'/'. md5($payment_url->payment_url_id . env('ENCRYPTION_KEY')) }}">
                                                        View Payment Url
                                                    </a>

                                                    @if ($payment_url->payment_url_status != 'Paid')
                                                        <button class="btn btn-outline-success btn-sm sendInv"
                                                            data-toggle="modal" data-target="#send_invoice"
                                                            data-id="{{ $payment_url->payment_url_id }}"
                                                            data-name="{{ $payment_url->customer->customer_name }}"
                                                            data-mobile="{{ $payment_url->customer->customer_mobile_no }}">
                                                            Send Payment Url
                                                        </button>
                                                        @php
                                                            $check = '';

                                                        @endphp
                                                        @can('payment_approval')
                                                            <button data-toggle="modal" data-target="#check_invoice_approval"
                                                                data-id="{{ $payment_url->payment_url_id }}"
                                                                class="btn btn-outline-success btn-sm check_aprroval {{ $check }}">Check
                                                                Approval
                                                            </button>
                                                        @endcan
                                                        @if($payment_url->payment_url_status == 'Pending')
                                                            <button data-toggle="modal" data-target="#cancel"
                                                                data-id="{{ $payment_url->payment_url_id }}"
                                                                class="btn btn-outline-danger btn-sm cancel">Cancel
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button data-toggle="modal" data-target="#check_invoice_approval"
                                                            data-id="{{ $payment_url->payment_url_id }}"
                                                            class="btn btn-outline-success btn-sm check_aprroval">View Bank
                                                            Slip
                                                        </button>
                                                        <a class="btn btn-outline-primary btn-sm" target="_blank"

                                                            href="{{ env('GRAPHQL_API').'/payment-url-receipt/'.$payment_url->payment_url_id.'/'. md5($payment_url->payment_url_id . env('ENCRYPTION_KEY')) }}">
                                                            View Receipt
                                                        </a>
                                                    @endif

                                                    @if ($message_templates)
                                                        @foreach ($message_templates as $rows)
                                                            <button data-toggle="modal" data-target="#send_message"
                                                            if (!$.trim(data)){
                                                                data-id="{{ $payment_url->payment_url_id }}"
                                                                data-slug="{{ $rows->message_template_involve_slug }}"
                                                                data-message-template-id=" {{ $rows->message_template_id }}"

                                                                class="btn btn-outline-info btn-sm invoice whatsapp">{{$rows->message_template_name}}</button><br>
                                                            <input type="hidden" id="slug" value="{{ $rows->message_template_involve_slug }}">
                                                            <input type="hidden" id="message_template_id" value="{{ $rows->message_template_id }}">

                                                        @endforeach
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                        @php
                                        $no++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No Records Found"</td>
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

    <!-- Cancel Payment Url -->

    <div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('payment_url_cancel', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Cancel Payment Url?</h4>
                        <input type="number" hidden id="payment_url_id" name="payment_url_id">
                        <label for="payment_url_log_description">Remark: </label>
                        <textarea name="payment_url_log_description" id="summernote"
                                class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Back</a>
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
                        <b>Payment Url Whatsapp</b>
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

    <!-- Modal -->
    <!-- Check Invoice Approval -->
    <div class="modal fade" id="check_invoice_approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="invoice_no" class="modal-header">

                </div>
                <form action="{{ route('payment_url_approve_reject', ['tenant' => tenant('id')]) }}" method="POST">
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
                                <input hidden id="payment_url_id_approve_reject" name="payment_url_id">
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
                        <input type="hidden" name="payment_url_id" id="payment_url_id">
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
        $('.cancel').on('click',function(){
            let payment_url_id = $(this).attr('data-id');
            $('#payment_url_id').val(payment_url_id);
        });
        $('.whatsapp').on('click',function(){
            let payment_url_id = $(this).attr('data-id');
            // let delivery_order_id = $(this).attr('data-order-id');
            let slug = $(this).attr('data-slug');
            let message = $(this).attr('data-message-template-id');
            let details = '';

            $.ajax({
                url: "{{ route('ajax_get_mobile_no_by_payment_url_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    payment_url_id: payment_url_id
                },
                success: function(e) {
                    details += '<table class="table table-nowrap">';
                    if(e.customer_name != null && e.customer_mobile_no != null){
                    details += '<tr>';
                    details += '<td style="border-top: none;">Customer Name: ' + e.customer_name +'</td>';
                    details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+payment_url_id+'/'+slug+'/'+message+'/'+e.customer_mobile_no+'" target="_blank" class="btn btn-success send" id="customer-whatsapp"</a>Send</td>'
                    details += '</tr>';
                    details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                    details += '<td style="border-top: none;">Customer Mobile: '+ e.customer_mobile_no + '</td>';
                    details += '</tr>';
                    } else{
                        details += '';
                    }
                    if(e.company_acc_name != null && e.company_acc_mobile_no != null){
                    details += '<tr>';
                    details += '<td style="border-top: none;">Company Account Name: '+ e.company_acc_name +'</td>';
                    details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+payment_url_id+'/'+slug+'/'+message+'/'+e.company_mobile_no+'" target="_blank" class="btn btn-success send" id="company-whatsapp">Send</a></td>'
                    details += '</tr>';
                    details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                    details += '<td style="border-top: none;">Company Account Mobile: '+ e.company_acc_mobile_no +'</td>';
                    details += '</tr>';
                    }else{
                        details += '';
                    }

                    if(e.customer_acc_name != null && e.customer_acc_mobile_no != null){
                    details += '<tr>';
                    details += '<td style="border-top: none;">Customer Account Name: '+ e.customer_acc_name +'</td>';
                    details += '<td style="border-top: none; text-align: center; align-items: center; vertical-align: middle !important;" rowspan="2"><a href="../message-template/send_whatsapp_template/'+payment_url_id+'/'+slug+'/'+message+'/'+e.customer_acc_mobile_no+'" target="_blank" class="btn btn-success send" id="customer-acc-whatsapp">Send</a></td>'
                    details += '</tr>';
                    details += '<tr style="border-bottom: 1px solid #e4e4e4">';
                    details += '<td style="border-top: none;">Customer Account Mobile: '+ e.customer_acc_mobile_no +'</td>';
                    details += '</tr>';
                    }else{
                        details += '';
                    }
                    details += '</table>';

                    $("#company-whatsapp").attr("href","../message-template/send_whatsapp_template/"+payment_url_id+"/"+slug+"/"+message+"/"+e.company_acc_mobile_no);
                    $("#customer-whatsapp").attr("href","../message-template/send_whatsapp_template/"+payment_url_id+"/"+slug+"/"+message+"/"+e.customer_mobile_no);
                    $("#customer-acc-whatsapp").attr("href","../message-template/send_whatsapp_template/"+payment_url_id+"/"+slug+"/"+message+"/"+e.customer_acc_mobile_no);
                    $('.whatsapp_body').html(details);

                },
                error: function(e) {
                    alert(e);
                }
            });
        })

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
                var company = '{{ auth()->user()->company_id }}';
                console.log('here');
            @else
                if (company_detect != null){
                    var company  = $('#company_id').val();
                }else{
                    var company  = 0;
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
            let customer = '<option value="">Please Select Customer</option>';
            $('#customer_id').html('<option value="">Loading...</option>');
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

        $('.button_status').on('click', function() {
            var value_transaction = this.value;

            $('#hidden_order_value').val(value_transaction);
            $('#search').click();
        });

        $('.sendLink').on('click', function() {
            var payment_url_id = $('#payment_url_id').val();
            var domain = '{{ env('GRAPHQL_API') }}';

            var encryption = md5(payment_url_id + '{{ env('ENCRYPTION_KEY') }}');
            var mobile = $('#customer_mobile').html();
            var url = 'https://api.whatsapp.com/send?phone=' + mobile + '&text=Click below to view your invoice. ' +
                domain + '/view-payment-link/' + payment_url_id + '/' + encryption;

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
            $('#invoice_no').html('Loading Payment Link...');
            $('#image_checking').html('Checking For Bank Slip...');
            let payment_url_id = $(this).attr('data-id');
            $.ajax({
                url: "{{ route('ajax_find_payment_url_with_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_url_id: payment_url_id,
                },
                success: function(e) {
                    $('#approve_btn').show();
                    $('#reject_btn').show();
                    $('#approve_btn').prop('disabled', false);
                    let details = '';
                    let invoice_num = '';
                    let image = '';
                    let items = '';
                        e.data.payment_url_item.forEach(item => {
                            items += item.invoice.invoice_no + '<br>';
                        });
                        details += '<tr>';
                        details += '<td>Cutomer Name:</td>';
                        details += '<td>' + e.data.customer.customer_name + '</td>';
                        details += '</tr>';
                        details += '<tr>';
                        details += '<td>Cutomer Address:</td>';
                        details += '<td>' +
                            e.data.customer.customer_address + ',<br>'
                        if (e.data.customer.customer_address2 != '') {
                            details += e.data.customer.customer_address2 + ',<br>'
                        }
                        details += e.data.customer.customer_postcode + ',<br>'
                        details += e.data.customer.customer_state + ',<br>'
                        details += e.data.customer.customer_country + '<br>'
                        details += '</td>';
                        details += '</tr>';
                        details += '<tr>';
                        details += '<td>Invoice No:</td>';
                        details += '<td>' + items + '</td>';
                        details += '</tr>';
                        details += '<tr>';
                        details += '<td>Total:</td>';
                        details += '<td>' + e.data.payment_url_total + '</td>';
                        details += '</tr>';

                        if (e.data.media.length == 0 && e.data.payment_url_status != 'Paid') {
                            image +=
                                '<div class="mb-2">No Bank Slip Image/PDF Found...</div>';
                            $('#approve_btn').prop('disabled', true);
                            $('#reject_btn').prop('disabled', true);
                        } else if (e.data.media.length > 0 && e.data.payment_url_status != 'Paid') {

                            image += '<div class="mb-2">Bank Slip Image/PDF</div>';
                            // image += '<img src="'+element.media[0].original_url+'">';
                            e.data.media.forEach(media=> {
                                if (media.mime_type == 'application/pdf') {
                                    image += '<a target="_blank" href="' + media
                                        .original_url +
                                        '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="{{ url('assets/images/PDF-img.png') }}" width="100" height="100"></a>';
                                } else {
                                    image += '<a target="_blank" href="' + media
                                        .original_url +
                                        '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="' +
                                        media.original_url +
                                        '" width="100" height="100"></a>';
                                }
                            });
                            if(e.data.payment_url_status == 'Pending'){
                                $('#approve_btn').prop('disabled', true);
                                $('#reject_btn').prop('disabled', true);
                            }else{
                                $('#approve_btn').prop('disabled', false);
                                $('#reject_btn').prop('disabled', false);
                            }
                        } else if (e.data.media.length > 0 && e.data.payment_url_status == 'Paid') {
                            image += '<div class="mb-2">Invoice Approved</div>';
                            e.data.media.forEach(media => {

                                if (media.mime_type == 'application/pdf') {
                                    image += '<a target="_blank" href="' + media
                                        .original_url +
                                        '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="{{ url('assets/images/PDF-img.png') }}" width="100" height="100"></a>';
                                } else {
                                    image += '<a target="_blank" href="' + media
                                        .original_url +
                                        '" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="' +
                                        media.original_url +
                                        '" width="100" height="100"></a>';
                                }
                            });
                            $('#approve_btn').hide();
                            $('#reject_btn').hide();
                        }

                        invoice_num += '<h4>Approval for ' + e.data.customer.customer_name + '</h4>';
                    $('#details_body').html(details);
                    $('#invoice_no').html(invoice_num);
                    $('#image_checking').html(image);
                    $('#payment_url_id_approve_reject').val(payment_url_id);
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
            $('.modal-body #payment_url_id').val(id);
            $('.modal-body #customer_name').html(name);
            $('.modal-body #customer_mobile').html(mobile);
        });
        $('.log').click(function() {
            let log_data = $(this).data('log');
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });

    </script>
@endsection
