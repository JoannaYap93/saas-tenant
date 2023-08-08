<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <link rel="shortcut icon" href="{{ URL::asset('images/huaxin_logo_transparent.png') }}">
    <style>
        @font-face {
            font-family: 'Firefly Sung' !important;
            src: url("{{ env('GRAPHQL_API') . '/fonts/fireflysung.ttf' }}") format('truetype');
        }

        .chinese {
            font-family: 'Firefly Sung' !important;
        }

    </style>
</head>

<body>
            <table class="center" border="0" cellpadding="0" cellspacing="0" id="templateContainer"
                style=" -webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025); width:100%; -webkit-border-radius:6px;">
                <tr>
                    <td align="center" style="padding-top:5px" valign="top">
                        <!-- HEADER: SECTION 1 -->
                        <table border="0" cellpadding="0" cellspacing="2" id="templateHeader"
                            style="-webkit-border-top-left-radius:2px; -webkit-border-top-right-radius:6px; width:100%; font-family: sans-serif;">
                            <tr>
                                <!-- SECTION 1: SECTION LOGO -->
                                <td style="width: 20%;">
                                    @if ($invoice->company->hasMedia('company_logo'))
                                        <img src="{{ $invoice->company->getFirstMediaUrl('company_logo') }}" alt=""
                                            height="65">
                                    @else
                                        <img src="{{ env('GRAPHQL_API') . '/images/huaxin-logo-transparent.png' }}"
                                            height="65" />
                                    @endif
                                </td>
                                <!-- SECTION 1: BRANCH INFO -->
                                <td style="text-align: center; line-height:1; padding-right:125px">
                                      <span style="font-size: 11px"><b style="font-size:16px;">{{ $invoice->company->company_name }}</b> {{ $invoice->company->company_reg_no }}</span><br>
                                        <span
                                            style="font-size:11px; font-family: sans-serif; color: #373737;">
                                            {{ $invoice->company->company_address }}
                                            {{-- NO. 2-3, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor --}}
                                        </span><br>
                                        <span style="font-size:11px; font-family: sans-serif; color: #373737;">
                                            {{ $company_website }}
                                            {{-- www.website.com --}}
                                        </span><br>
                                        <span style="font-size:11px; font-family: sans-serif; color: #373737;">
                                          Tel: {{ $invoice->company->company_phone }}  Email: {{ $invoice->company->company_email }}
                                            {{-- emai@email.com --}}
                                        </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <hr>
            <table class="center" border="0" cellpadding="0" cellspacing="0" id="templateContainer"
                style="padding-top: 10px; -webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025); width:100%; -webkit-border-radius:6px;">
                <tr>
                    <td align="center" valign="middle">
                        <!-- HEADER: SECTION 2 -->
                        <table border="0" cellpadding="0" cellspacing="0" id="templateHeader"
                            style="-webkit-border-top-left-radius:2px; -webkit-border-top-right-radius:6px; width:100%; font-family: sans-serif;">
                            <tr>
                                <!-- SECTION 2: CUSTOMER INFO -->
                                <td align="left">
                                    <p style="padding: 0px; margin: 0px">
                                        <span style="font-size:18px; font-family: sans-serif;"><b>CUSTOMER
                                                INFO</b></span>
                                    </p>
                                </td>
                                <!-- SECTION 2: INVOICE -->
                                <td align="right">
                                    <p style="padding: 0px; margin: 0px">
                                        <span style="font-size:33px; font-family: sans-serif;"><b>INVOICE</b></span>
                                    </p>

                                </td>
                            </tr>
                            <tr>
                                <!-- SECTION 3: CUSTOMER INFO -->
                                <td align="left">
                                    <table border="0" align="left" cellpadding="0" cellspacing="0"
                                        style="padding: 0px; margin-left: 0px; width: 100%;">
                                        <tbody>
                                            <tr style="padding: 0px; margin: 0px">
                                                <!-- <td align="left" valign="top"
                                                    style="vertical-align: middle; text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">Name</td>
                                                <td align="left" valign="top"
                                                    style="vertical-align: middle; text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">:</td> -->
                                                <td align="left" valign="top"
                                                    style="vertical-align: middle; font-family: Yahei, sans-serif; text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">
                                                    <span style="vertical-align: middle;">
                                                        {{ $invoice->customer->customer_company_name }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <!-- <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">Mobile No</td>
                                                <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">:</td> -->
                                                <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">
                                                    {{ $invoice->company->customer_mobile_no }}
                                                </td>
                                            </tr>

                                            {{-- @if ($invoice->transaction_type->transaction_type_id != 3 && @$invoice->transaction_shipping)
                                                <tr>
                                                    <!-- <td align="left" valign="top"
                                                        style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                        scope="col">
                                                        Shipping Address
                                                    </td>
                                                    <td align="left" valign="top"
                                                        style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                        scope="col">:</td> -->
                                                    <td align="left" valign="top"
                                                        style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                        scope="col">
                                                        @if (@$invoice->transaction_shipping[0])
                                                            @if ($invoice->transaction_shipping[0]->shipping_address != '' || $invoice->transaction_shipping[0]->shipping_address2 != '' || $invoice->transaction_shipping[0]->shipping_postcode != '' || $invoice->transaction_shipping[0]->shipping_city != '' || $invoice->transaction_shipping[0]->shipping_state != '')
                                                                {{ $invoice->transaction_shipping[0]->shipping_address }},
                                                                <br />
                                                                {!! $invoice->transaction_shipping[0]->shipping_address2 ? $invoice->transaction_shipping[0]->shipping_address2 . ',<br/>' : '' !!}
                                                                {{ $invoice->transaction_shipping[0]->shipping_postcode }}
                                                                {{ $invoice->transaction_shipping[0]->shipping_city }},
                                                                <br />
                                                                {{ $invoice->transaction_shipping[0]->shipping_state }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif --}}
                                            @if($invoice->customer->customer_email)
                                            <tr>
                                                <!-- <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">Email</td>
                                                <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">:</td> -->
                                                <td align="left" valign="top"
                                                    style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">
                                                    {{ $invoice->customer->customer_email }}
                                                </td>
                                            </tr>
                                            @endif
                                            @if ($invoice->customer_address != '' || $invoice->customer_postcode != '' || $invoice->customer_city != '' || $invoice->customer_state != '' || $invoice->customer_country != '')
                                              <tr>
                                                  <td align="left" valign="top"
                                                      style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                      scope="col">
                                                              {{ $invoice->customer_address }},
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td align="left" valign="top"
                                                      style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                      scope="col">
                                                              {!! $invoice->customer_address2 ? $invoice->customer_address2 . ',<br>' : '' !!}
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td align="left" valign="top"
                                                      style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                      scope="col">
                                                              {{ $invoice->customer_postcode }}
                                                              {{ $invoice->customer_city }},
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td align="left" valign="top"
                                                      style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                      scope="col">
                                                              {{ $invoice->customer_state }}
                                                  </td>
                                              </tr>
                                              @endif
                                            <tr>
                                              <td align="left" valign="top"
                                                  style="text-align:left; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                  scope="col">
                                                  Attn: {{ $invoice->customer_name }}
                                              </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                                <!-- SECTION 3: INVOICE -->
                                <td align="right">
                                    <table border="0" align="right" cellpadding="0" cellspacing="0"
                                        style=" padding: 0px; margin: 0px; margin-right: -2px; width: 100%; text-transform: uppercase;">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="text-align:right; border: 0px solid; font-size: 12px; padding-bottom: 3px; color: #373737;"
                                                    scope="col">
                                                    #{{ $invoice->invoice_no }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="text-align:right; border: 0px solid; font-size: 12px; color: #373737;"
                                                    scope="col">
                                                    {{ $invoice->invoice_created }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        <table class="center" cellspacing="0" cellpadding="1"
            style="font-family: sans serif; width: 100%; border: 0px solid; padding-top:10; padding-bottom: 10px;"
            border="0">
            <tbody>
                <tr>
                    <td
                        style="font-family: sans serif; text-align:center; border-top:1px; border-bottom:1px; padding-top:3px; padding-bottom:3px; font-size: 12px; border-color: #000; width: 3%; font-weight: bold;">
                        No.</td>
                    <td
                        style="font-family: sans serif; padding-left: 5px; padding-top:3px; padding-bottom:3px; text-align:left; border-top:1px; border-bottom:1px; font-size: 12px; border-color: #000; width: 30%; font-weight: bold;">
                        Item</td>
                    <td style="font-family: sans serif; padding-left: 0px; padding-top:3px; padding-bottom:3px; text-align:center; border-top:1px; border-bottom:1px; font-size: 12px; border-color: #000; width: 7%; font-weight: bold;"
                        scope="col">Qty</td>
                    <td
                        style="font-family: sans serif; padding-right: 0px; padding-top:3px; padding-bottom:3px; text-align:center; border-top:1px; border-bottom:1px; font-size: 12px;  border-color: #000; width: 10%; font-weight: bold;">
                        Unit Price<br>(RM)</td>
                    <td
                        style="font-family: sans serif; padding-right: 0px; padding-top:3px; padding-bottom:3px; text-align:center; border-top:1px; border-bottom:1px; font-size: 12px;  border-color: #000; width: 10%; font-weight: bold;">
                        Discount<br>(RM)</td>
                    <td
                        style="font-family: sans serif; padding-right: 0px; padding-top:3px; padding-bottom:3px; text-align:center; border-top:1px; border-bottom:1px; font-size: 12px;  border-color: #000; width: 10%; font-weight: bold;">
                        Amount<br />(RM)</td>
                </tr>
                @php
                    $i = 1;
                @endphp

                @foreach ($invoice->invoice_item as $item)
                    <tr>
                        <td valign='top' scope='col'
                            style='font-family: sans serif; color: #373737; text-align:center; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px; border-bottom:1px; border-color: #000;'>
                            {{ $i++ }}</td>
                        <td valign='top' scope='col'
                            style='color: #373737; padding-left: 5px; text-align:left; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px;  border-bottom:1px; border-color: #000;'>
                            <span style="font-family: 'Firefly Sung'">{{ $item->invoice_item_name }}</span> - Grade
                            {{ $item->setting_product_size->setting_product_size_name }}
                        </td>
                        <td valign='top' scope='col'
                            style=' color: #373737; text-align:center; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px; border-bottom:1px; border-color: #000; padding-left: 5px;'>
                            {{ round($item->invoice_item_quantity, 4) }} KG</td>
                        <td valign='top' scope='col'
                            style=' color: #373737; padding-right: 5px; text-align:center; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px; border-bottom:1px; border-color: #000;'>
                            {{ $item->invoice_item_price }}</td>
                        <td valign='top' scope='col'
                            style=' color: #373737; padding-right: 5px; text-align:center; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px; border-bottom:1px; border-color: #000;'>
                            {{ $item->invoice_item_discount }}</td>
                        <td valign='top' scope='col'
                            style=' color: #373737; padding-right: 5px; text-align:center; border:0px; padding-top:10px; padding-bottom:10px; font-size: 12px; border-bottom:1px; border-color: #000;'>
                            {{ sprintf('%.2f', $item->invoice_item_total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="center" cellspacing="0" cellpadding="1"
            style="width: 100%; border: 0px solid; padding-bottom: 10px;" border="0">
            <tbody>
                @php
                    //quotation
                    $grand_total_sales = 0;
                    $grand_total_quotation = 0;
                    $grand_total_sales = $invoice->invoice_subtotal;
                @endphp

                <tr>
                    <td style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px;">Notes:</td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%; border-bottom: 1px;">
                        <b>Total (RM)</b>
                    </td>
                    <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%; border-bottom: 1px;"
                        scope="col"></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%; border-bottom: 1px;">
                        <b>{{ $grand_total_sales }}<b>
                    </td>
                </tr>
                @if (auth()->user()->company->company_enable_gst != 0)
                    <tr>
                        <td style="font-family: sans-serif; font-size: 12px; padding-right: 0px;">1. All checques should be crossed and made payable to</td>
                        <td
                            style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%;">
                            <b>GST (RM)</b>
                        </td>
                        <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%;"
                            scope="col"></td>
                        <td
                            style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%;">
                            <b>{{ $invoice->invoice_total_gst }}<b>
                        </td>
                    </tr>
                @else
                <tr>
                    <td style="font-family: sans-serif; font-size: 12px; padding-right: 0px;">1. All checques should be crossed and made payable to</td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%;">
                    </td>
                    <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%;"
                        scope="col"></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%;">
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="font-family: sans-serif; font-size: 12px; padding-right: 0px;"> &nbsp;&nbsp;&nbsp;&nbsp;{{@$invoice->company->company_bank_acc_name}} - {{@$invoice->company->setting_bank->setting_bank_name}} {{@$invoice->company->company_bank_acc_no}}</td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%;">
                        <b>Discount (RM)</b>
                    </td>
                    <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%;"
                        scope="col"></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%;">
                        <b>{{ $invoice->invoice_total_discount }}<b>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%; border-bottom: 1px;">
                    </td>
                    <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%; border-bottom: 1px;"
                        scope="col"></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%; border-bottom: 1px;">
                    </td>
                </tr>
                <tr>
                    <td style="font-family: sans-serif; font-size: 12px; padding-right: 0px;">2. Goods sold are neither returnable nor refundable.</td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:right; width: 20%; border-bottom: 1px;">
                        <b>Total (RM)</b>
                    </td>
                    <td style="font-family: sans-serif; font-size: 12px; padding-left: 0px; padding-bottom: 5px; text-align:center; width: 2%; border-bottom: 1px;"
                        scope="col"></td>
                    <td
                        style="font-family: sans-serif; font-size: 12px; padding-right: 0px; padding-bottom: 5px; text-align:center; width: 10%; border-bottom: 1px;">
                        <b>{{ $invoice->invoice_grandtotal }}<b>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>

        <div style="padding-bottom: 50px; position:absolute; bottom: 0; text-align:center; width:100%">
            <span class="text-muted" style="font-size:10px; ">
                {{-- © {{ date('Y') }} {{ $companyName }}. All Rights Reserved<br> --}}
                <i>By placing order, you’ve read and accept the Terms & Conditions, Piracy Policy, Return & Refund
                    Policy of
                    www.website.com
                    {{-- {{ $companyWebsite }}</i> --}}
            </span>
        </div>

</body>

</html>
