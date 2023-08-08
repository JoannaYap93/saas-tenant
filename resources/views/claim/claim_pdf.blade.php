<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Claim {{ $claim->claim_id }}</title>
    <style>
          table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      }

      th {
        border: 2px solid black;
      }

      .main {
        width:100%;
      }

      .signature th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: left;
      }

    </style>
</head>

<body>
    <div style="margin-bottom: 25px">
        <table class="table main">
          <thead>
            <tr>
              <th colspan="5">{{$claim->company->company_name}}</th>
            </tr>
            <tr>
              <th colspan="5">Expense Claim Form for {{date('d-m-Y', strtotime($claim->claim_start_date))}} to {{date('d-m-Y', strtotime($claim->claim_end_date))}}</th>
            </tr>
          </thead>
          <tbody>
            @php
              $index = 1;
              $claim_total = 0;
              $expense_count = [];
              $material_count = []
            @endphp
            <tr>
                <td><b>Item No.</b></td>
                <td><b>Date</b></td>
                <td><b>Category Item</b></td>
                <td><b>Claim Item Name</b></td>
                <td align ="right"><b>Total (RM)</b></td>
            </tr>

            {{-- Company Expense --}}
            @foreach($claim_category_expense as $category)
              @php
                $category_counter = 0;
                
                foreach ($claim_item['result'] as $cirkey => $item) {
                  if($category->setting_expense_category_id == $item['category_id'] && ($item['claim_item_type'] == 'company_expense_item_id' || $item['claim_item_type'] == 'manually_company_expense_item_category_id')){
                    if(isset($expense_count[$category->setting_expense_category_id])){
                      $expense_count[$category->setting_expense_category_id] += 1;
                    }else{
                      $expense_count[$category->setting_expense_category_id] = 1;
                    }
                  }
                }
              @endphp
              @foreach(@$claim_item['result'] as $cirkey => $item)
                @if(($item['claim_item_type'] == 'company_expense_item_id') || $item['claim_item_type'] == 'manually_company_expense_item_category_id')
                  @if($category->setting_expense_category_id == $item['category_id'])
                    <tr>
                      <td>{{$index++}}</td>
                      <td>{{ date('d-m-Y', strtotime($item['date']))}}</td>
                      @if($category_counter == 0)
                      <td rowspan="{{$expense_count[$category->setting_expense_category_id]}}"><b>{{json_decode(@$category->setting_expense_category_name)->en}}</b></td>
                      @endif
                      @if($category_counter > 0)
                      @endif
                      @if($item['claim_item_type'] == 'company_expense_item_id')
                        <td>{{json_decode($item['claim_item_name'])->en}}</td>
                      @else
                        <td>{{$item['claim_item_name']}}</td>
                      @endif
                      <td align ="right">{{number_format($item['amount_claim'], 2)}}</td>
                      @php
                        if($item['amount_claim']){
                          $claim_total += $item['amount_claim'];
                        }  
                      @endphp
                    </tr>
                    @php
                      $category_counter++;
                    @endphp
                  @endif
                @endif
              @endforeach
            @endforeach

            {{-- Company Material --}}
            @foreach($claim_category_material as $category)
              @php
                $category_counter = 0;
                
                foreach ($claim_item['result'] as $cirkey => $item) {
                  if($category->raw_material_category_id == $item['category_id'] && ($item['claim_item_type'] == 'raw_material_company_usage_id' || $item['claim_item_type'] == 'manually_raw_material_company_usage_category_id')){
                    if(isset($material_count[$category->raw_material_category_id])){
                      $material_count[$category->raw_material_category_id] += 1;
                    }else{
                      $material_count[$category->raw_material_category_id] = 1;
                    }
                  }
                }
              @endphp
              @foreach(@$claim_item['result'] as $cirkey => $item)
                @if(($item['claim_item_type'] == 'raw_material_company_usage_id') || $item['claim_item_type'] == 'manually_raw_material_company_usage_category_id')
                  @if($category->raw_material_category_id == $item['category_id'])
                    <tr>
                      <td>{{$index++}}</td>
                      <td>{{ date('d-m-Y', strtotime($item['date']))}}</td>
                      @if($category_counter == 0)
                      <td rowspan="{{$material_count[$category->raw_material_category_id]}}"><b>{{json_decode(@$category->raw_material_category_name)->en}}</b></td>
                      @endif
                      @if($category_counter > 0)
                      @endif
                      @if($item['claim_item_type'] == 'raw_material_company_usage_id')
                        <td>{{json_decode($item['claim_item_name'])->en}}</td>
                      @else
                        <td>{{$item['claim_item_name']}}</td>
                      @endif
                      <td align ="right">{{number_format($item['amount_claim'], 2)}}</td>
                      @php
                        if($item['amount_claim']){
                          $claim_total += $item['amount_claim'];
                        } 
                      @endphp
                    </tr>
                    @php
                      $category_counter++;
                    @endphp
                  @endif
                @endif
              @endforeach
            @endforeach
            <tr>
                <th align="right" colspan="4">Total (RM)</th>
                <th align="right">{{number_format($claim_total, 2)}}</th>
            </tr>
          </tbody>
        </table>
      </div>
      @if(!@$excel)
        <div>
          <div style="width:30%; float: left; padding-right: 55px">
            <table class="table signature" style="width: 100%;">
              <thead>
                <tr>
                  <th colspan="2">CLAIM BY:</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2" style="height: 100px"></td>
                </tr>
                <tr>
                  <td>Name:</td>
                  <td align='center'><b>{{ @$claim->worker_id ? @$claim->worker->worker_name : 'N/A' }}</b></td>
                </tr>
                <tr>
                  <td>Date:</td>
                  <td align='center'><b>{{$claim->claim_created ? date_format($claim->claim_created, 'd-m-Y') : 'N/A'}}</b></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="width: 30%; float: left;">
            <table class="table signature" style="width: 100%">
              <thead>
                <tr>
                  <th colspan="2">REVIEWED & VERIFIED BY:</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2" style="height: 100px"></td>
                </tr>
                <tr>
                  <td>Name:</td>
                  <td align='center'><b>{{$claim_approval_verify ? $claim_approval_verify->user->user_full_name : 'N/A'}}</b></td>
                </tr>
                <tr>
                  <td>Date:</td>
                  <td align='center'><b>{{$claim_approval_verify ? date_format($claim_approval_verify->claim_approval_created, 'd-m-Y') : 'N/A'}}</b></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="width: 30%; float: right;">
            <table class="table signature" style="width: 100%">
              <thead>
                <tr>
                  <th colspan="2">APPROVED BY:</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2" style="height: 100px"></td>
                </tr>
                <tr>
                  <td>Name:</td>
                  <td align='center'><b>{{$claim_approval_approve ? $claim_approval_approve->user->user_full_name : 'N/A'}}</b></td>
                </tr>
                <tr>
                  <td>Date:</td>
                  <td align='center'><b>{{$claim_approval_approve ? date_format($claim_approval_approve->claim_approval_created, 'd-m-Y') : 'N/A'}}</b></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @else
      <table class="table">
        <thead>
          <tr>
            <th colspan="2">CLAIM BY:</th>
            <th></th>
            <th colspan="2">REVIEWED &amp; VERIFIED BY:</th>
            <th></th>
            <th colspan="2">APPROVED BY:</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2" style="height: 100px"></td>
            <td></td>
            <td colspan="2" style="height: 100px"></td>
            <td></td>
            <td colspan="2" style="height: 100px"></td>
          </tr>
          <tr>
            <td>Name:</td>
            <td align='center'><b>{{ @$claim->worker_id ? @$claim->worker->worker_name : 'N/A' }}</b></td>
            <td></td>
            <td>Name:</td>
            <td align='center'><b>{{$claim_approval_verify ? $claim_approval_verify->user->user_full_name : 'N/A'}}</b></td>
            <td></td>
            <td>Name:</td>
            <td align='center'><b>{{$claim_approval_approve ? $claim_approval_approve->user->user_full_name : 'N/A'}}</b></td>
          </tr>
          <tr>
            <td>Date:</td>
            <td align='center'><b>{{$claim->claim_created ? date_format($claim->claim_created, 'd-m-Y') : 'N/A'}}</b></td>
            <td></td>
            <td>Date:</td>
            <td align='center'><b>{{$claim_approval_verify ? date_format($claim_approval_verify->claim_approval_created, 'd-m-Y') : 'N/A'}}</b></td>
            <td></td>
            <td>Date:</td>
            <td align='center'><b>{{$claim_approval_approve ? date_format($claim_approval_approve->claim_approval_created, 'd-m-Y') : 'N/A'}}</b></td>
          </tr>
        </tbody>
      </table>
      @endif
      @if(!@$excel)
        @if(count($claim_item['media']) > 0)
          @foreach($claim_item['media'] as $media_key => $media_url)
                <div style="page-break-after:always;">
                </div>
                <img width="100%" height="100%" src="{{ $media_url }}">

          @endforeach
        @endif
      @endif
</body>

</html>
