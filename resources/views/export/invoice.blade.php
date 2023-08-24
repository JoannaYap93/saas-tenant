@extends('layouts.master-without-nav')

@section('title')
    Invoice #{{ $invoice->invoice_no }}
@endsection

@section('css')
    <style>
        .round-big {
            border-radius: 1rem;
        }

        .download_invoice {
            cursor: pointer;
        }

        @media screen and (max-width: 450px) {
            .download_invoice {
                position: relative !important;
                left: 0 !important;
                text-align: left !important;
            }

            .download_invoice>p {
                text-align: left !important;
            }
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

    <div class="m-lg-5 m-sm-1 mt-3">
        <div class="row m-0">
            <div class="col-12">
                <div class="card round-big">
                    <div class="card-body">
                        <h4 class="card-title">Invoice No #{{ $invoice->invoice_no }}</h4>
                        @php
                            $dos = '';
                            foreach ($do as $dk => $d) {
                                if ($dk > 0) {
                                    $dos .= ', ';
                                }
                                $dos .= $d . '';
                            }
                        @endphp
                        <p class=" m-0 mt-2">D.O. No #{{ $dos }} </p>
                    </div>
                </div>
                <div class="card mt-2 round-big">
                    <div class="card-body">
                        <h1 class="card-title" style="font-size: 3rem">RM
                            {{ number_format($invoice->invoice_grandtotal, 2) }}</h1>
                        <h5 class="card-title">Date: {{ date_format($invoice->invoice_created, 'M d, Y h:i A') }}
                        </h5>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-12">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 10%">To</td>
                                            <td style="font-weight: bold">{{ $invoice->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>From</td>
                                            <td style="font-weight: bold">{{ $company->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td style="font-weight: bold">
                                                @php
                                                    $add = '';
                                                    if (@$invoice->customer_address2) {
                                                        $add .= $invoice->customer_address . ', ' . $invoice->customer_address2 . ', <br>';
                                                        $add .= $invoice->customer_city . ' ' . $invoice->customer_postcode . ', ';
                                                        $add .= $invoice->customer_state . ' ' . $invoice->customer_country . ' ';
                                                    } else {
                                                    }
                                                    
                                                    echo $add;
                                                @endphp
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Term</td>
                                            <td style="font-weight: bold">
                                                {{ $invoice->invoice_payment->setting_payment->setting_payment_name }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <span id="download_invoice" class="download_invoice position-absolute text-center"
                                    style="left: 50%">
                                    <img src="{{ global_asset('images/invoice.svg') }}" alt="" width="50px">
                                    <p class="text-center">Click here for download</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 round-big">
                    <div class="card-body">
                        <h4 class="card-title">Invoice Items</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Grade</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th style="text-align: right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (@$invoice->invoice_item)
                                        @foreach ($invoice->invoice_item as $k => $item)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $item->product->product_name }}</td>
                                                <td>{{ round($item->invoice_item_quantity, 4) }} KG</td>
                                                <td>{{ $item->setting_product_size->setting_product_size_name }}</td>
                                                <td>RM {{ number_format($item->invoice_item_subtotal, 2) }}</td>
                                                <td>RM {{ number_format($item->invoice_item_discount, 2) }}</td>
                                                <td style="text-align: right">RM {{ number_format($item->invoice_item_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr style="background-color: #fff; border-top: 1.5px solid #d9d9d9">
                                            <td colspan="6" class="text-right">Total: </td>
                                            <td style="text-align: right">RM {{ number_format($invoice->invoice_subtotal, 2) }}</td>
                                        </tr>
                                        <tr style="background-color: #fff">
                                            <td colspan="6" class="text-right">Discount: </td>
                                            <td style="text-align: right">RM {{ number_format($invoice->invoice_total_discount, 2) }}</td>
                                        </tr>
                                        <tr style="background-color: #fff">
                                            <td colspan="6" class="text-right">Subtotal: </td>
                                            <td style="text-align: right">RM {{ number_format($invoice->invoice_total, 2) }}</td>
                                        </tr>
                                        <tr style="background-color: #fff">
                                            <td colspan="6" class="text-right">GST: </td>
                                            <td style="text-align: right">RM {{ number_format($invoice->invoice_total_gst, 2) }}</td>
                                        </tr>
                                        <tr style="background-color: #fff">
                                            <td colspan="6" class="text-right">Grandtotal: </td>
                                            <td style="text-align: right">RM {{ number_format($invoice->invoice_grandtotal, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>"No Item"</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ $submit }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-between align-center">
                                    <div class="form-group">
                                        <label for="">Upload Payment Slip <span class="text-danger">*</span></label>
                                        <input type="file" name="payment_slip" id="" class="form-control-file mb-2"
                                            required>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $('#download_invoice').click(function() {
            let link = window.location.pathname;
            // console.log();
            window.open('{{ env('APP_URL') }}/invoice/' + link.substring(14), 'Invoice')
        });
    </script>
@endsection
