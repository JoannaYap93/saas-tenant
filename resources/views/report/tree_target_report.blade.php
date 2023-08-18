@extends('layouts.master')
@section('title')
    Tree Target Report
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
    .table-responsive {
        max-height: 500px !important;
        overflow: hidden !important;
        overflow: scroll !important;
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
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"><span class="mr-2 ">Tree Target Reporting</span></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Tree Target Report</a>
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
                <x-tree_target_report :companies=$company :companyLand=$company_land :totalTreePlanted=$total_tree_planted :smallTreePlanted=$small_tree_planted
                :babyTreePlanted=$baby_tree_planted :numberTreePerAcre=$number_of_tree_per_acre :component=true />
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
            'columnNum': 4

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
            url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
            url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
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
</script>
@endsection

