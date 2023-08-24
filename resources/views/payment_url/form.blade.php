@extends('layouts.master')

@section('title')
    {{ $title }} Payment Url
@endsection

@section('css')
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Payment Url</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Payment Url</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Invoice Created</label>
                                    <div class="input-daterange input-group" id="datepicker6"
                                        data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker6">
                                        <input type="text" style="width: 75px" class="form-control" name="date_created_from"
                                            placeholder="Start Date" value="{{ @$search['date_created_from'] }}" id="date_created_from"
                                            autocomplete="off" onchange="get_invoice()">
                                        <input type="text" style="width: 75px" class="form-control" name="date_created_to"
                                            placeholder="End Date" value="{{ @$search['date_created_to'] }}" id="date_created_to"
                                            autocomplete="off" onchange="get_invoice()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Invoice Date</label>
                                    <div class="input-daterange input-group" id="datepicker6"
                                        data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                        data-provide="datepicker" data-date-container="#datepicker6">
                                        <input type="text" style="width: 75px" class="form-control" name="date_from"
                                            placeholder="Start Date" value="{{ @$search['date_from'] }}" id="date_from"
                                            autocomplete="off" onchange="get_invoice()">
                                        <input type="text" style="width: 75px" class="form-control" name="date_to"
                                            placeholder="End Date" value="{{ @$search['date_to'] }}" id="date_to"
                                            autocomplete="off" onchange="get_invoice()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="setting_expense_name">Customer</label>
                                    @php
                                        $disabled = '';
                                        if (@$customer->customer_id) {
                                            $disabled = 'disabled';
                                        }
                                    @endphp
                                    {!! Form::select('customer_id', $customer_sel, @$customer->customer_id, ['class' => 'form-control', 'id' => 'customer_id', $disabled, 'onchange'=>'get_invoice()']) !!}
                                    @if ($disabled)
                                        <input type="hidden" name="customer_id" value={{ $customer->customer_id }}>
                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Land: <span class="text-danger">*</span></label>
                                {!! Form::select('company_land_id', $company_land_sel, @$invoice->company_land_id, ['class' => 'form-control', 'id' => 'company_land_id', 'onchange'=>'get_invoice()']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('payment_url_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Invoice</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="">
                                    <tr>
                                        <th scope='col'><input type='checkbox' id='checkAll_item' name='checkAll_item'
                                                value=''></th>
                                        <th>Invoice No</th>
                                        <th>Items</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>GST</th>
                                        <th>Total</th>
                                        <th>Amount Paid</th>
                                        <th style="text-align: right">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody id="details_body">
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="{{ @$invoice_id }}">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.5.0/js/md5.min.js"></script>
    <script>
        $(document).ready(function() {
            // var customer_id = $('#customer_id').val();
            // var company_land_id = $('#company_land_id').val();
            // get_invoice_by_customer_id(search)
            get_invoice();

            var select_all_item = document.getElementById("checkAll_item"); //select all checkbox
            var checkboxes_item = document.getElementsByName("invoice_ids[]"); //checkbox items
            //select all checkboxes_product

            select_all_item.addEventListener("change", function(e) {
                for (i = 0; i < checkboxes_item.length; i++) {
                    checkboxes_item[i].checked = select_all_item.checked;
                }
            });

            for (var i = 0; i < checkboxes_item.length; i++) {
                checkboxes_item[i].addEventListener('change', function(e) { //".checkbox" change
                    //uncheck "select all", if one of the listed checkbox item is unchecked
                    if (this.checked == false) {
                        select_all_item.checked = false;
                    }
                    //check "select all" if all checkbox items are checked
                    if (document.querySelectorAll('.checkbox:checked').length == checkboxes_item.length) {
                        select_all_item.checked = true;
                    }
                });
            }
        });

        // $('#customer_id').on('change', function() {
        //     $('#details_body').html('Loading Invoice...');
        //     let customer_id = $('#customer_id').val();
        //     let company_land_id = $('#company_land_id').val();
        //     let date_from = $('#date_from').val();
        //     let date_to = $('#date_to').val();

        //     get_invoice_by_customer_id(customer_id, company_land_id,date_from,date_to)
        // });

        // $('#company_land_id').on('change', function() {
        //     $('#details_body').html('Loading Invoice...');
        //     let customer_id = $('#customer_id').val();
        //     let company_land_id = $('#company_land_id').val();
        //     let date_from = $('#date_from').val();
        //     let date_to = $('#date_to').val();
        //     get_invoice_by_customer_id(customer_id, company_land_id,date_from,date_to)

        // });

        // $('#input').on('change', function() {
        //     $('#details_body').html('Loading Invoice...');
        //     let customer_id = $('#customer_id').val();
        //     let company_land_id = $('#company_land_id').val();
        //     let date_from = $('#date_from').val();
        //     let date_to = $('#date_to').val();
        //     get_invoice_by_customer_id(customer_id, company_land_id,date_from,date_to)

        // });

        function get_invoice(){
            $('#details_body').html('Loading Invoice...');
            let search = {
                customer_id: $('#customer_id').val(),
                company_land_id: $('#company_land_id').val(),
                date_from: $('#date_from').val(),
                date_to: $('#date_to').val(),
                date_created_from: $('#date_created_from').val(),
                date_created_to: $('#date_created_to').val()
            };
            get_invoice_by_customer_id(search);
        }

        function get_invoice_by_customer_id(search) {
            $.ajax({
                url: "{{ route('ajax_get_invoice_by_payment_url', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: search,
                },
                success: function(e) {
                    let details = '';
                    e.forEach(item => {
                        details += '<tr>';
                        details +=
                            '<td><input type="checkbox" id="invoice_ids[]" name="invoice_ids[]" value="' +
                            item.invoice_id;
                        details += '" /></td>';
                        route = '{{route("invoice_listing_id", ["tenant" => tenant("id")])}}';
                        route2 = route + '/' + item.invoice_id;
                        details +='<td><a href="'+route2+'" target="_blank">' + item.invoice_no + '</a></td>';
                        details += '<td>';
                        item.invoice_item.forEach(invoice_item => {
                            details += invoice_item.product.product_name + '-' +
                                invoice_item.setting_product_size
                                .setting_product_size_name
                            details += '<br>';
                        });
                        details += '</td>';
                        details += '<td>' + item.invoice_subtotal + '</td>';
                        details += '<td>' + item.invoice_total_discount + '</td>';
                        details += '<td>' + item.invoice_total_gst + '</td>';
                        details += '<td>RM ' + item.invoice_grandtotal + "</td>";
                        if(item.invoice_amount_paid > 0){
                          details += '<td>RM '+ item.invoice_amount_paid +'</td>';
                        }else{
                          details += '<td>RM 0.00</td>';
                        }
                        if(item.invoice_amount_remaining > 0){
                          details += '<td style="text-align: right">RM ' + item.invoice_amount_remaining + '</td>';
                        }else{
                          details += '<td style="text-align: right">RM 0.00</td>';
                        }
                    });
                    $('#details_body').html(details);
                }
            })
        }
    </script>
@endsection
