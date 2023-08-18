@extends('layouts.master')
@section('title')
    {{$page_title}}
@endsection

@section('css')
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">


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
                <h4 class="mb-0 font-size-18"><span class="mr-2 "> {{$page_title}}</span></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);"> {{$page_title}}</a>
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
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Year</label>
                                            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text"  class="form-control" name="year" placeholder="Start Date" value="{{ @$search['year'] }}" id="year" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Template</label>
                                        <select class="form-control" id="message_template_id" name="message_template_id" tabindex="-1" aria-hidden="true">
                                            <option value="">Please Select a Template</option>
                                            @foreach($template_sel as $template)
                                                    <option @if(@$search['message_template_id'] == $template->message_template_id) selected @endif value="{{$template->message_template_id}}">{{$template->message_template_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Customer</label>
                                            {!! Form::select('customer_id', $customer_sel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!}
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <x-advanced_filter :companyCb="$company_cb" :productCb="$product_cb"
                                    :messageTemplates="$message_templates" :search="$search"/>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <a href="{{route('message_template_report_by_year',['tenant' => tenant('id')])}}" class="btn btn-danger waves-effect waves-light mr-2"><i class="fas fa-times mr-1"></i> Reset</a>
                                        {{-- <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                            name="submit" value="export">
                                            <i class="fas fa-download mr-1"></i> Export
                                        </button> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($records)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>WhatsApp</th>
                                @php
                                    $col_count =0;
                                @endphp
                                @foreach ($month_sel as $month)
                                    <th style="text-align: center; font-weight: bold; {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{{$month}}</th>
                                @php
                                    $col_count++;
                                @endphp
                                @endforeach
                                <th style="text-align: center; background-color:#fffbaf; color:#000000">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_cols = array();
                            @endphp
                            @foreach ($message_templates as $template)
                                @php
                                    $total_rows = 0;
                                @endphp
                                <tr>
                                    <td>{{$template->message_template_name}}</td>
                                    @foreach ($month_sel as $key => $month)

                                        @php
                                            $total_rows += @$records[$template->message_template_id][$key] ?? 0;
                                            if(!array_key_exists($key,$total_cols)){
                                                $total_cols[$key] = @$records[$template->message_template_id][$key] ?? 0;
                                            }else{
                                                $total_cols[$key] += @$records[$template->message_template_id][$key] ?? 0;
                                            }

                                            $link = route('message_template_report_by_month',['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$key]);
                                        @endphp
                                        <td style="text-align: center;  {{ $col_count % 2 != 0 ? 'background-color: #ffffff;' : 'background-color: #e4e4e4;' }} border:1px solid #eee">{!!@$records[$template->message_template_id][$key] ? '<a  class="popup" href="'.$link.'">'.number_format($records[$template->message_template_id][$key]).'</a>' : '-'!!}</td>
                                        @php
                                            $col_count++;
                                        @endphp
                                    @endforeach
                                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{$total_rows > 0 ? number_format($total_rows) : "-"}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="text-align: center; background-color:#fffbaf; color:#000000">Total</td>
                                @php
                                    $total_sum_rows = 0;
                                @endphp
                                @foreach ($month_sel as $key => $month)
                                    @php
                                        $total_sum_rows += @$total_cols[$key] ?? 0;
                                        $link = route('message_template_report_by_month',['tenant' => tenant('id'),'year'=>$search['year'],'month'=>$key]);
                                    @endphp
                                    <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{!!@$total_cols[$key] ? '<a class="popup" href="'.$link.'">'.number_format($total_cols[$key]).'</a>' : '-'!!}</td>
                                @endforeach
                                <td style="text-align: center; font-weight: bold; background-color:#fffbaf; color:#000000">{{$total_sum_rows > 0 ? number_format($total_sum_rows) : "-"}}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                        No records found!
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
        $("#datepicker6").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true, //to close picker once year is selected
            orientation: "left"
        });

        var element = document.getElementById("collapse");

        @if(@$search['company_cb_id'])
            let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
            get_company_land_checkbox(c_ids);
            disableProduct(c_ids);
            $('.manage-show-hide').trigger('click');
            $('#company_id').val('').attr('disabled', true);
            $('#company_land_id').val('').attr('disabled', true);
            $('#user_id').val('').attr('disabled', true);
            $('#message_template_id').val('').attr('disabled', true);
        @endif

        $('.check_company_cb_id').on('click', function() {
            let selected_val = [];
            let id = document.getElementById('company_cb');
            let checkbox = id.getElementsByTagName('INPUT');

        for (var i = 0; i < checkbox.length; i++){
            if (checkbox[i].checked){
            selected_val.push(checkbox[i].value);
            }
            }
            get_company_land_checkbox(selected_val);
            disableProduct(selected_val);
        })

        function get_company_land_checkbox(selected_val)
        {   let div_filter = '';

            @if (@$search['company_land_cb_id'])
                let land_ids = {!!json_encode($search['company_land_cb_id'])!!};
                let land_cb_ids = land_ids.map(function(item){
                    return parseInt(item, 10);
                });
            @endif

            $.ajax({
            url: "{{ route('ajax_get_land_by_company_id', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: selected_val
            },
            success: function(e) {

                e.forEach((land) => {
                    div_filter += '<div class="custom-control custom-checkbox">';
                    div_filter +='<input type="checkbox" id="company_land_cb_'+land.id+'"';
                    div_filter +='name="company_land_cb_id[]" value="'+land.id+'"';
                    div_filter +='class= "form-check-input check_company_land_cb_id"';
                    div_filter +='@if(@$search["company_land_cb_id"])';
                    if(land_cb_ids.includes(land.id)){
                        div_filter += 'checked';
                    }
                    div_filter +='@endif';
                    div_filter +='/>';
                    div_filter +='<label for="company_land_cb_'+land.id+'">'+land.land_name+'</label>';
                    div_filter +='</div>';
                });
                $('.land_id_cb').html(div_filter);
                }
            });
        }

        function disableProduct(selected_val){
            $.ajax({
            url: "{{ route('ajax_get_products_multi_company', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: selected_val
            },
            success: function(e) {
                let checkbox = '';
                if(e.length > 0){
                $('.check_product_cb_id').attr('disabled', true);
                e.forEach((product) => {
                    $('#product_cb_' + product.id).attr('disabled', false);
                });
                }else{
                $('.check_product_cb_id').attr('disabled', false);
                }
                }
            });
        }

        $('.manage-show-hide').on('click', function(){
        let id = $(this).attr('aria-controls')

        if($('#' + id).is(':visible')){
            $('#message_template_id').val('').attr('disabled', false);
            $('#customer_id').val('').attr('disabled', false);
            $('.check_company_cb_id').prop('checked', false);
            $('.check_product_cb_id').prop('checked', false);
            $('.check_company_land_cb_id').prop('checked', false);
        }else{
            $('#message_template_id').val('').attr('disabled', true);
            $('#customer_id').val('').attr('disabled', true);
        }

        })
        $(document).ready(function(e) {
            $(".popup").fancybox({
                'type': 'iframe',
                'width': '90%',
                'height': '90%',
                'autoDimensions': false,
                'autoScale': false,
                iframe : {
                    css : {
                        width : '90%',
                        height: '90%'
                    }
                }
            });
            $(".fancybox").fancybox();
        });
    </script>
@endsection
