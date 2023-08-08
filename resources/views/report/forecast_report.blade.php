@extends('layouts.master')
@section('title')
    Forecast Report
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
    .table-responsive {
        height: 500px !important;
        overflow: hidden !important;
        overflow: scroll !important;
    }
    table {
        text-align: center !important;
    }
    .bg-grey {
        background: #e4e4e4 !important;
    }

    .bg-red {
            background: #f46a6a !important;
            color: #ffffff !important
    }
    th {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 2 !important;
    }

    th:nth-child(n) {
        position: -webkit-sticky !important;
        position: sticky !important;
        z-index: 1 !important;
    }

    tfoot {
        position: -webkit-sticky !important;
        position: sticky !important;
        bottom: 0 !important;
    }

    .table-responsive::-webkit-scrollbar {
        display: inherit;
    }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"><span class="mr-2 ">Forecast Reporting</span></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Forecast Report</a>
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
                    <div class="row">
                        @if (auth()->user()->company_id == 0)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="company_id">Company</label>
                                    {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="company_land_id">Company Land</label>
                                {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'company_land_id']) !!}
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->company_id == 0)
                    <div>
                        <div id="collapse" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
                            @if(!@$companyComponent)
                                <div class="form-group col-sm-12">
                                    <label for="user_land">Companies:</label><br>
                                    <div class="row col-sm-12" id="company_cb">
                                        @foreach ($company_cb as $id => $companys)
                                            <div class="custom-control custom-checkbox col-sm-3">
                                                <input type="checkbox" id="company_cb_{{ $id }}"
                                                    name="company_cb_id[]" value="{{ $id }}"
                                                    class= "form-check-input check_company_cb_id"
                                                    @if(@$search['company_cb_id'])
                                                    @foreach(@$search['company_cb_id'] as $key => $selected_company)
                                                        {{ $selected_company == $id ? 'checked' : '' }}
                                                    @endforeach
                                                @endif
                                                />
                                                <label for="company_cb_{{ $id }}">{{ $companys }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(!@$dashboard)
                                @if(!@$companyLandComponent)
                                    <div class="form-group col-sm-12">
                                        <label for="company_land">Company Land:</label>
                                        <div class="row col-sm-12" id="land_cb">
                                            <div class="land_id_cb">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="col-12 text-left mb-3">
                            <a href="#collapse" class="text-center manage-show-hide text-dark collapsed mb-2" data-toggle="collapse" aria-expanded="true" aria-controls="collapse" style="vertical-align: middle;">
                                <span class="font-weight-bold ">
                                    <span class="text-show-hide">Multiple Company Selection</span>
                                    <i class="bx bxs-down-arrow rotate-icon"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                name="submit" value="search">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                            <button type="submit" class="btn btn-success waves-effect waves-light mr-2"
                                name="submit" value="export">
                                <i class="fas fa-download mr-1"></i> Export
                            </button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                name="submit" value="reset">
                                <i class="fas fa-times mr-1"></i> Reset
                            </button>
                        </div>
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
                <x-forecast_report :companyPnlItem=$company_pnl_item :setting=$setting :forecastReportResult=$forecast_report_result :avgprice=$price :component=true />
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/freeze-table/freeze-table.js') }}"></script>


<script>
    $(document).ready(function(e) {
        $("#tree-target-report-table").parent().freezeTable({
            'freezeColumn': true,
            'shadow': true,
            'scrollable': true,
        });
    });

    $('#datepicker7').datepicker({
        format: "mm/yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true //to close picker once year is selected
    });

    @if (@$search['company_id'] != null)
        get_land_user('{{ $search['company_id'] }}');
    @else
        get_land_user('{{ auth()->user()->company_id }}');
    @endif

    $('#company_id').on('change', function() {
        let id = $(this).val();
        let land = '<option value="">Please Select Land</option>';

        $('#company_land_id').html('<option value="">Loading...</option>');

        $.ajax({
            url: "{{ route('ajax_land_user') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(e) {
                if (e.land.length > 0) {
                    e.land.forEach(element => {
                        land += '<option value="' + element.company_land_id + '">' + element
                            .company_land_name + '</option>';
                    });
                    $('#company_land_id').html(land);
                } else {
                    $('#company_land_id').html('<option value="">No Land</option>');
                }
            }
        });
    });

    function get_land_user(id) {
        let land = '<option value="">Please Select Land</option>';
        let sland = "{{ @$search['company_land_id'] ?? null }}";
        $.ajax({
            url: "{{ route('ajax_land_user') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(e) {
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
            }
        });
    }

    @if(@$search['company_cb_id'])
        let c_ids = <?php echo json_encode($search['company_cb_id']);?>;
        get_company_land_checkbox(c_ids);
        disableProduct(c_ids);
        $('.manage-show-hide').trigger('click');
        $('#company_id').val('').attr('disabled', true);
        $('#company_land_id').val('').attr('disabled', true);
        $('#user_id').val('').attr('disabled', true);
        $('#product_id').val('').attr('disabled', true);
        $('#product_category_id').val('').attr('disabled', true);
        $('#size_id').val('').attr('disabled', true);
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
            url: "{{ route('ajax_get_land_by_company_id') }}",
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
            url: "{{ route('ajax_get_products_multi_company') }}",
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
        $('#company_id').val('').attr('disabled', false);
        $('#company_land_id').val('').attr('disabled', false);
        $('#product_id').val('').attr('disabled', false);
        $('#product_category_id').val('').attr('disabled', false);
        $('#product_size_id').val('').attr('disabled', false);
        $('#customer_id').val('').attr('disabled', false);
        $('.check_company_cb_id').prop('checked', false);
        $('.check_product_cb_id').prop('checked', false);
        $('.check_company_land_cb_id').prop('checked', false);
        }else{
        $('#company_id').val('').attr('disabled', true);
        $('#company_land_id').val('').attr('disabled', true);
        $('#product_id').val('').attr('disabled', true);
        $('#product_category_id').val('').attr('disabled', true);
        $('#product_size_id').val('').attr('disabled', true);
        $('#customer_id').val('').attr('disabled', true);
        }

    })
</script>
@endsection
