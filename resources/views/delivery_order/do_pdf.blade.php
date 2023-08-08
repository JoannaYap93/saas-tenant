<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Order {{ $do->delivery_order_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        @font-face {
            font-family: 'Yahei';
            src: url({{public_path('fonts/chinese.msyh.ttf')}}) format("truetype");
        }
        html {
            margin: 0;
            padding: 0;
            box-sizing: content-box;
        }

        body {
            /* margin: auto; */
            font-size: 14px;
            font-family: "Times New Roman", Times, serif;
        }

        h2 {
            font-weight: bold;
            font-size: 18px;
        }

        p {
            font-size: 12px;
            text-align: center;
            padding-bottom: 3px;
        }

        h2 p {
            font-size: 12px;
            font-weight: normal;
            display: inline-block;
            padding-top: 3px;
            text-align: center;
        }

        h3 {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        h4 {
            padding-bottom: 5px;
        }

        span {
            font-size: 14px;
        }

        /* reset */
        /* * {
            border: 0;
            box-sizing: content-box;
            color: inherit;
            font-family: inherit;
            /* font-size: inherit; */
        /*font-style: inherit;
            font-weight: inherit;
            line-height: inherit;
            list-style: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
            vertical-align: top;
        } */

        /* content editable */

        /* *[] {
            border-radius: 0.25em;
            outline: 0;
        }

        *[] {
            cursor: pointer;
        }

        *[]:hover,
        *[]:focus,
        td:hover *[],
        td:focus *[],
        img.hover {
            background: #DEF;
            box-shadow: 0 0 1em 0.5em #DEF;
        }

        span[] {
            display: inline-block;
        } */

        /* heading */

        h1 {
            font: bold 100% sans-serif;
            letter-spacing: 0.5em;
            text-align: center;
            text-transform: uppercase;
        }

        /* table */

        table {
            font-size: 75%;
            width: 100%;
            table-layout: auto
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.5em;
            position: relative;
            text-align: left;
            max-height: 20px;
        }

        th {
            border-color: black;
            border-top-width: 1px;
            border-bottom-width: 1px;
            border-style: solid
        }

        /* page */

        body {
            box-sizing: unset;
            height: 100%;
            margin: 0 auto;
            overflow: hidden;
            /* width: 10.5in; */
            padding: 3em;
        }

        body {
            background: #FFF;
        }

        /* header */

        header:after {
            clear: both;
            content: "";
            display: table;
        }

        header h1 {
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        }

        header address {
            float: left;
            font-size: 75%;
            font-style: normal;
            line-height: 1.25;
            margin: 0 1em 1em 0;
        }

        header address p {
            margin: 0 0 0.25em;
        }

        header span,
        header img {
            display: block;
            float: right;
        }

        header span {
            margin: 0 0 1em 1em;
            max-height: 25%;
            max-width: 60%;
            position: relative;
        }

        header img {
            max-height: 100%;
            max-width: 100%;
        }

        header input {
            cursor: pointer;
            /* -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; */
            height: 100%;
            left: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        /* article */

        article,
        article address,
        table.meta,
        table.inventory {
            margin: 0 0 3em;
        }

        article:after {
            clear: both;
            content: "";
            display: table;
        }

        article h1 {
            clip: rect(0 0 0 0);
            position: absolute;
        }

        article address {
            float: left;
            font-size: 125%;
            font-weight: bold;
        }

        /* table meta & balance */

        table.meta,
        table.balance {
            float: right;
            width: 36%;
        }

        table.meta:after,
        table.balance:after {
            clear: both;
            content: "";
            display: table;
        }

        /* table meta */

        table.meta th {
            width: 40%;
        }

        table.meta td {
            width: 60%;
        }

        /* table items */

        table.inventory {
            clear: both;
            width: 100%;
        }

        table.inventory th:nth-child(1) {
            font-weight: bold;
            text-align: center;
        }

        table.inventory th:nth-child(2) {
            font-weight: bold;
            text-align: left;
        }

        table.inventory th:nth-child(3) {
            font-weight: bold;
            text-align: left;
        }

        table.inventory th:nth-child(4) {
            font-weight: bold;
            text-align: right;
        }

        table.inventory td:nth-child(1) {
            text-align: center;
            width: 5%;
        }

        table.inventory td:nth-child(2) {
            width: 20%;
        }

        table.inventory td:nth-child(3) {
            width: 50%;
        }

        table.inventory td:nth-child(4) {
            text-align: right;
            width: 24%;
        }

        /* table balance */

        table.balance th,
        table.balance td {
            width: 50%;
        }

        table.balance td {
            text-align: right;
        }

        /* aside */

        aside h1 {
            border-width: 0 0 1px;
            margin: 0 0 1em;
        }

        aside h1 {
            border-color: black;
            border-bottom-style: solid;
        }

        thead {
            display: table-header-group;
            display: table-row-group;
        }
        /* tfoot {
            display: table-row-group;
        }
        tr {
            page-break-inside: avoid;
        } */


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
                          @if ($do->company->hasMedia('company_logo'))
                              <img src="{{ $do->company->getFirstMediaUrl('company_logo') }}" alt=""
                                  height="65">
                          @else
                              <img src="{{ env('GRAPHQL_API') . '/images/huaxin-logo-transparent.png' }}"
                                  height="65" />
                          @endif
                      </td>
                      <!-- SECTION 1: BRANCH INFO -->
                      <td style="text-align: center; line-height: 1.25; padding-right:125px">
                            <span style="font-size: 11px"><b style="font-size:16px;">{{ $do->company->company_name }}</b> {{ $do->company->company_reg_no }}</span><br>
                              <span
                                  style="font-size:11px; font-family: sans-serif; color: #373737;">
                                  {{ $do->company->company_address }}
                                  {{-- NO. 2-3, Jalan Merbah 1, Bandar Puchong Jaya, 47170 Puchong, Selangor --}}
                              </span><br>
                              <span style="font-size:11px; font-family: sans-serif; color: #373737;">
                                Tel: {{ $do->company->company_phone }}  Email: {{ $do->company->company_email }}
                                  {{-- emai@email.com --}}
                              </span>
                      </td>
                  </tr>
              </table>
          </td>
      </tr>
  </table>
    <hr>
    <br />
    <div style="float: left; padding-top: 8px; line-height: 1.25">
        <span style="font-size: 13px">{{ $do->customer_details->customer_company_name }}</span><br>
        <span style="font-size: 13px">
            {{ $do->customer_details->customer_address }}
            @if ($do->customer_details->customer_address2)
                , {{ $do->customer_details->customer_address2 }}
            @endif
            ,
            {{ $do->customer_details->customer_city }}
        </span><br>
        <span style="font-size: 13px">{{ $do->customer_details->customer_state }}</span><br>
        <span style="font-size: 13px">{{ $do->customer_details->customer_postcode }}</span><br>
        <span style="font-size: 13px">{{ $do->customer_details->customer_country }}</span><br>
        <span style="font-size: 13px">TEL: {{ $do->customer_details->customer_category->customer_category_slug != 'cash' ? $do->customer_details->customer_mobile_no: $do->customer_pic->where('customer_pic_ic',$do->customer_ic)->first()->customer_pic_mobile_no }}</span><br>
        <span style="font-size: 13px">Attn To: {{ $do->customer_details->customer_category->customer_category_slug != 'cash' ? $do->customer_details->customer_name : $do->customer_pic->where('customer_pic_ic',$do->customer_ic)->first()->customer_pic_name }}</span><br>
        <br />
    </div>
    <table class="meta" style="line-height:1; width: auto">
        <tr>
            <th style="border-color: transparent" colspan="2">
                <h2 style="padding-bottom: 10px">Delivery Order</h2>
            </th>
        </tr>
        <tr>
            <th style="border-color: transparent; "><span style="font-weight: normal; font-size: 13px">D.O. NO</span></th>
            <td><span style="font-weight: bold; font-size: 13px">: {{ $do->delivery_order_no }} </span></td>
        </tr>
        <tr>
            <th style="border-color: transparent;"><span style="font-weight: normal; font-size: 13px">Date</span></th>
            <td><span style="font-weight: normal; font-size: 13px">: {{ date_format($do->delivery_order_created, 'Y-m-d') }}</span></td>
        </tr>
        <tr>
            <th style="border-color: transparent;"><span style="font-weight: normal; font-size: 13px">Prepared By</span></th>
            <td><span style="font-weight: normal; font-size: 13px">: {{ @$do->user->user_fullname }}</span></td>
        </tr>
        <tr>
            <th style="border-color: transparent;"><span style="font-weight: normal; font-size: 13px">Address</span></th>
            <td><span style="font-weight: normal; font-size: 13px">: {{ $do->company_land->company_land_name }}</span></td>
        </tr>
        {{-- @dd($do->delivery_order_remark) --}}
        @if(@$do->delivery_order_remark)
        <tr>
            <th style="border-color: transparent;"><span style="font-weight: normal; font-size: 13px">Remark</span></th>
            <td><span style="font-family: 'Yahei'; font-weight: normal; font-size: 11px">: {{ $do->delivery_order_remark }}</span></td>
        </tr>
        @endif
    </table>
    <table class="inventory" border="0">
        <thead>
            <tr>
                <th style="width: 20px"><span style="font-size: 12px">No</span></th>
                <th><span style="font-size: 12px">Item Code</span></th>
                <th><span style="font-size: 12px">Description</span></th>
                <th><span style="font-size: 12px">Weight (KG)</span></th>
            </tr>
        </thead>
        <tbody >
            @php
                $grade_total = [];
                $counter = 0;
                $count_first = 0;
            @endphp

            @foreach ($do->delivery_order_items as $key => $item)
                @foreach ($grade as $grades)
                @if($item->setting_product_size->setting_product_size_id == $grades->setting_product_size_id)
                <tr style="max-height: 20px;">
                    <td style="width: 20px">
                        <p>{{ $key + 1 }}</p>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-family:'Yahei'">
                            {{ $item->product->product_name }}
                            ({{ $item->setting_product_size->setting_product_size_name }})
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-family:'Yahei'">DURIAN
                            {{ $item->product->product_name }} - GRADE
                            {{ $item->setting_product_size->setting_product_size_name }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px">
                            {{ number_format($item->delivery_order_item_quantity, 2) }}
                            @php
                                if (isset($grade_total[$item->product->product_id][$grades->setting_product_size_id])){
                                    $grade_total[$item->product->product_id][$grades->setting_product_size_id] += $item->delivery_order_item_quantity;
                                }else{
                                    $grade_total[$item->product->product_id][$grades->setting_product_size_id] = $item->delivery_order_item_quantity;
                                }
                            @endphp
                        </span>
                    </td>
                </tr>
                @endif
            @endforeach
            @endforeach
            {{-- <tr>
                <td colspan="4" style="height: 100%">

                </td>
            </tr> --}}
            {{-- @dd($grade_total) --}}
        </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="border-top-width: 1px; border-style: solid; border-color: black" />
                </td>
            </tr>
            <tr>
                <td align="left" colspan="2" style="text-align: left;">
                    <span style="font-size: 12px; font-family:'Yahei'"><b>Product Total Summary :</b></span>
                </td>
                <th align="right" style="border-color: transparent;" colspan="1">
                    <h3>Total</h3>
                </th>
                <th align="right" style="border-width: 1px; width:165px">
                    <h3>{{ number_format($do->delivery_order_total_quantity, 2) }} KG</h3>
                </th>
            </tr>
            </tfoot>

            <tfoot>
            @foreach ($product as $products)
                @foreach ($grade as $grades)
                    @if (isset($grade_total[$products->product_id][$grades->setting_product_size_id]))
                    @php
                        $counter++;
                    @endphp
                        <tr>
                            <td align="left" colspan="2" style="text-align: left">
                                <span style="font-size: 12px; font-family:'Yahei'">
                                    {{$counter}}. {{$products->product_name}} ({{$grades->setting_product_size_name}}) = {{number_format($grade_total[$products->product_id][$grades->setting_product_size_id],2)}} KG
                                </span>
                            </td>
                            @if($count_first == 0)
                                <td align="right" style="border-color: transparent;" colspan="1">
                                </td>
                                <td align="right" style="border-top-width: 1px; width:165px">
                                </td>
                            @php
                                $count_first++;
                            @endphp
                            @endif
                        </tr>
                    @endif
                @endforeach
            @endforeach
            </tfoot>
            {{-- <tr>
                <th style="border-color: transparent" colspan="3">
                    <h3>Total</h3>
                </th>
                <th style="border-width: 1px; width:165px">
                    <h3>{{ number_format($do->delivery_order_total_quantity, 2) }} KG</h3>
                </th>
            </tr> --}}
    </table>
    <div style="float: right; padding-top: 50px">
      <div>
        @if ($do->hasMedia('delivery_order_signature'))
            <img src="{{ $do->getFirstMediaUrl('delivery_order_signature') }}"
                style="width:150px; padding-bottom: 5px; margin-bottom: 5px;"
                alt="Signature" />
        @endif
      </div>
      <hr>
        <h3 style="text-align: left;">
            Receiver: {{ $do->customer_name }}
            <br />{{ $do->customer_ic }}
        </h3>
    </div>
</body>

</html>
