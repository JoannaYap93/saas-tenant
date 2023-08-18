@extends('layouts.master')
@section('title')
    Sales Analysis (Deliver Order)
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

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
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Sales Analysis (Deliver Order)</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sales Analysis (Deliver Order)</a>
                        </li>
                        <li class="breadcrumb-item active">Reporting</li>
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
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ $submit }}">
                        @csrf
                        <div class="clearfix">
                                <div id="graph_chart_month" class="row mb-2">
                                    <div class="col-md-3">
                                        <label>Date:</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-daterange input-group" id="datepicker6"
                                            data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                            data-provide="datepicker" data-date-container="#datepicker6">
                                            <input type="text" style="width: 75px" class="form-control"
                                                name="date_from" placeholder="Start Date"
                                                value="{{ @$search['date_from'] }}" id="datepicker" autocomplete="off">
                                            <input type="text" style="width: 75px" class="form-control"
                                                name="date_to" placeholder="End Date"
                                                value="{{ @$search['date_to'] }}" id="datepicker" autocomplete="off">
                                        </div>
                                    </div>
                                  </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <label for="">Company: </label>
                                              {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                          </div>
                                      </div>
                                </div>
                                  <x-advanced_filter :dashboard=true :companyCb="$company_cb" :productCb="$product_cb" :search="$search"/>
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1"
                                    name="submit" value="search">
                                    <i class="fas fa-search mr-1"></i> Search
                                </button>
                                <button type="submit" class="btn btn-success waves-effect waves-light mr-1"
                                    name="submit" value="today">
                                    <i class="fas fa-search mr-1"></i> Today
                                </button>
                                <button type="submit" class="btn btn-info waves-effect waves-light mr-2"
                                    name="submit" value="yesterday">
                                    <i class="fas fa-search mr-1"></i> Yesterday
                                </button> 
                                <button type="submit" class="btn btn-danger waves-effect waves-light mr-1"
                                    name="submit" value="reset">
                                    <i class="fas fa-times mr-1"></i> Reset
                                </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">

                  <table class="table table-bordered" id="sales_analysis">
                      <thead>
                          <tr>
                                <th style="text-align: left; min-width: 200px" rowspan="2">Company</th>
                                <th
                                    style="text-align: center; background-color: #e4e4e4; min-width: 200px" rowspan="2">
                                    Invoice Duration
                                </th>
                                <th
                                    style="text-align: center; min-width: 200px" rowspan="2">
                                    Warehouse (DO) KG
                                </th>
                                <th
                                    style="text-align: center; background-color: #e4e4e4; min-width: 200px" colspan="2">
                                    Weight
                                </th>
                                <th
                                    style="text-align: center; min-width: 200px" colspan="2">
                                    Total
                                </th>
                          </tr>
                          <tr>
                            <th
                                style="text-align: center; background-color: #e4e4e4;">
                                KG
                            </th>
                            <th
                                style="text-align: center; background-color: #e4e4e4;">
                                %
                            </th>
                            <th
                                style="text-align: center;">
                                RM
                            </th>
                            <th
                                style="text-align: center;">
                                %
                            </th>
                          </tr>
                      </thead>
                      <tbody>
                        @php
                            $total_invoice_item_qty = 0;
                            $invoice_grandtotal_sum = 0;
                        @endphp
                        @foreach($company as $cid => $company_name)
                            <tr>
                            <td style="text-align: left;">{{$company_name}}</td>
                            @if((isset($sales_analysis_invoice['result'][$cid]) || isset($sales_analysis_do['result'][$cid])) || isset($sales_analysis_invoice['result3'][$cid]))

                                @php
                                if(isset($sales_analysis_invoice['result'][$cid]) || isset($sales_analysis_do['result'][$cid])){
                                    $invoice_grandtotal_sum += @$sales_analysis_invoice['result'][$cid]['invoice_grandtotal'];
                                $total_invoice_item_qty += @$sales_analysis_invoice['result2'][$cid]['invoice_item_total'];
                                }
                                @endphp
                            <td style="text-align: center; background-color: #e4e4e4;">
                                @if (@$sales_analysis_invoice['result'][$cid]['min_date'] || @$sales_analysis_invoice['result'][$cid]['max_date'])
                                    {{ date_format(date_create($sales_analysis_invoice['result'][$cid]['min_date']), "d M Y").'-'.date_format(date_create($sales_analysis_invoice['result'][$cid]['max_date']), "d M Y")}}
                                @elseif(@$sales_analysis_invoice['result3'][$cid]['min_date'] && @$sales_analysis_invoice['result3'][$cid]['max_date'])
                                    {{ date_format(date_create($sales_analysis_invoice['result3'][$cid]['min_date']), "d M Y").'-'.date_format(date_create($sales_analysis_invoice['result3'][$cid]['max_date']), "d M Y")}}
                                @else
                                -
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{@$sales_analysis_invoice['result3'][$cid]['warehouse'] ? number_format(@$sales_analysis_invoice['result3'][$cid]['warehouse'], 2) : '-'}}
                            </td>
                                @if((isset($sales_analysis_invoice['result'][$cid]) && isset($sales_analysis_do['result'][$cid])))
                                    <td style="text-align: center; background-color: #e4e4e4;">
                                        {{ @$sales_analysis_invoice['result2'][$cid]['invoice_item_total'] ? number_format(@$sales_analysis_invoice['result2'][$cid]['invoice_item_total'], 2) : '-' }}
                                    </td>
                                    <td style="text-align: center; background-color: #e4e4e4;">
                                        {{ @$sales_analysis_invoice['sum_qty'] > 0 ?number_format(@$sales_analysis_invoice['result2'][$cid]['invoice_item_total']/$sales_analysis_invoice['sum_qty']*100, 2) : '-'}}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ @$sales_analysis_invoice['result'][$cid]['invoice_grandtotal'] ? number_format(@$sales_analysis_invoice['result'][$cid]['invoice_grandtotal'], 2) : '-' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ @$sales_analysis_invoice['sums'] > 0 ? number_format(@$sales_analysis_invoice['result'][$cid]['invoice_grandtotal']/$sales_analysis_invoice['sums']*100, 2) : '-'}}
                                    </td>
                                @else
                                    <td style="text-align: center; background-color: #e4e4e4;">
                                        -
                                    </td>
                                    <td style="text-align: center; background-color: #e4e4e4;">
                                    -
                                    </td>
                                    <td style="text-align: center;">
                                        -
                                    </td>
                                    <td style="text-align: center;">
                                    -
                                    </td>
                                @endif
                            @else
                            <td style="text-align: center; background-color: #e4e4e4;">
                                -
                            </td>
                            <td style="text-align: center;">
                                -
                            </td>
                            <td style="text-align: center; background-color: #e4e4e4;">
                                -
                            </td>
                            <td style="text-align: center; background-color: #e4e4e4;">
                                -
                                </td>
                            <td style="text-align: center;">
                                -
                            </td>
                            <td style="text-align: center;">
                                -
                            </td>
                            @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align: left; font-weight: bold; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            TOTAL
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            -
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                                {{$sales_analysis_invoice['sum_warehouse'] > 0 ? number_format($sales_analysis_invoice['sum_warehouse'], 2) : '0.00'}}
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            {{number_format($total_invoice_item_qty, 2)}}
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            {{$sales_analysis_invoice['sum_qty'] > 0 ? number_format($total_invoice_item_qty/$sales_analysis_invoice['sum_qty']*100, 2) : '-'}}
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            {{number_format($invoice_grandtotal_sum, 2)}}
                            </td>
                            <td style="text-align: center; background-color:#fffbaf; color:#000000 border:1px solid #eee">
                            {{$sales_analysis_invoice['sums'] > 0 ? number_format($invoice_grandtotal_sum/$sales_analysis_invoice['sums']*100, 2) : '0.00'}}
                            </td>
                        </tr>
                      </tbody>
                  </table>
              </div>
              </div>
            </div>
          </div>
      </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>
        $(document).ready(function(e) {
          var element = document.getElementById("collapse");

          @if(@$search['company_cb_id'])
            let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
            // disableProduct(c_ids);
            // console.log(c_ids);
            $('.manage-show-hide').trigger('click');
            $('#company_id').val('').attr('disabled', true);
            // $('#company_land_id').val('').attr('disabled', true);
            // $('#product_id').val('').attr('disabled', true);
            // $('#product_category_id').val('').attr('disabled', true);
            // $('#product_size_id').val('').attr('disabled', true);
          @endif

          // $('.check_company_cb_id').on('click', function() {
          //   let selected_val = [];
          //   let id = document.getElementById('company_cb');
          //   let checkbox = id.getElementsByTagName('INPUT');
          //
          //   for (var i = 0; i < checkbox.length; i++){
          //     if (checkbox[i].checked){
          //       selected_val.push(checkbox[i].value);
          //     }
          //   }
          //   disableProduct(selected_val);
          //   // console.log(selected_val);
          // })
          //
          // function disableProduct(selected_val){
          //   $.ajax({
          //       url: "{{ route('ajax_get_products_multi_company', ['tenant' => tenant('id')]) }}",
          //       method: "POST",
          //       data: {
          //           _token: "{{ csrf_token() }}",
          //           company_id: selected_val
          //       },
          //       success: function(e) {
          //         let checkbox = '';
          //         // console.log(e);
          //         if(e.length > 0){
          //           $('.check_product_cb_id').attr('disabled', true);
          //           e.forEach((product) => {
          //             $('#product_cb_' + product.id).attr('disabled', false);
          //             // product_c_arr.push(product.id);
          //             // checkbox += '<div class="custom-control custom-checkbox col-sm-12">';
          //             // checkbox += '<input type="checkbox" id=product_cb_'+ product.id +' name="product_cb_id[]" value='+ product.id +' class= "form-check-input check_product_cb_id"/>';
          //             // checkbox += '<label for=product_cb_'+ product.id +'>'+ product.name +'</label>'
          //             // checkbox += '</div>'
          //           });
          //           // $('#product_cb').html(checkbox);
          //         }else{
          //           $('.check_product_cb_id').attr('disabled', false);
          //         // $('#product_cb').html('');
          //         }
          //       }
          //   });
          // }

          $('.manage-show-hide').on('click', function(){
            let id = $(this).attr('aria-controls')

            if($('#' + id).is(':visible')){
              $('#company_id').val('').attr('disabled', false);
              // $('#company_land_id').val('').attr('disabled', false);
              // $('#product_id').val('').attr('disabled', false);
              // $('#product_category_id').val('').attr('disabled', false);
              // $('#product_size_id').val('').attr('disabled', false);
              $('.check_company_cb_id').prop('checked', false);
              $('.check_product_cb_id').prop('checked', false);
            }else{
              $('#company_id').val('').attr('disabled', true);
              // $('#company_land_id').val('').attr('disabled', true);
              // $('#product_id').val('').attr('disabled', true);
              // $('#product_category_id').val('').attr('disabled', true);
              // $('#product_size_id').val('').attr('disabled', true);
            }

          })

          $("#sales_analysis").parent().freezeTable({
              'freezeColumn': true,
              'shadow': true
          });
            $(".popup").fancybox({
                'type': 'iframe',
                'width': '100%',
                'height': '100%',
                'autoDimensions': false,
                'autoScale': false,
                iframe : {
                    css : {
                        width : '80%',
                        height: '80%'
                    }
                }
            });
            $(".fancybox").fancybox();
        });

        $("#datepicker6").datepicker({
            orientation: "bottom left"
        });
    </script>
@endsection
