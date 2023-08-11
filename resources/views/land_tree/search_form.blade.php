@extends('layouts.master')

@section('title') Search Tree  @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Tree - Search </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Tree </a>
                    </li>
                    <li class="breadcrumb-item active">Search</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror

<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('search_listing', ['tenant' => tenant('id')])}}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-4">

                            @if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) >= 0)
                                <h5>Company: </h5>
                                {!! Form::select('company_id', $company_sel, @$records->company_id, ['class' => 'form-control', 'id' => 'company_id']) !!}
                                <br>
                            @else
                                <input type="hidden" id="company_id" value="{{auth()->user()->company_id}}">
                            @endif
                            <h5>Company Land: </h5>
                            {!! Form::select('company_land_id', $company_land_sel, @$records->company_land_id, ['class' => 'form-control', 'id' => 'company_land']) !!}
                            <br>
                            <h5>Zone: </h5>
                            <select name="company_land_zone_id" class="form-control" id="company_land_zone">
                                <option value="">Please Select Zone</option>
                            </select>
                            {{-- <h5>Tree Status: </h5> --}}
                            {{-- {!! Form::select('company_land_tree_status', $status_sel, @$records->company_land_tree_status, ['class' => 'form-control']) !!} --}}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Go To</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')

<script>
    let company_id = $('#company_id').val();
    let company_land_id = $('#company_land').val();

    $(document).ready(function(e) {
        get_company_land(company_id)
        get_land_zone(company_id, company_land_id)
    });

    $('#company_id').on('change', function() {
        $('#company_land_zone').html('<option value="">Loading...</option>');
        $('#company_land').html('<option value="">Loading...</option>');
        let company_id = $(this).val();
        let land = '<option value="">Please Select Land</option>';
        get_company_land(company_id)
        get_land_zone(company_id, company_land_id);
    });

    $('#company_land').on('change', function() {
        $('#company_land_zone').html('<option value="">Loading...</option>');
        let company_id = $('#company_id').val();
        let company_land_id = $(this).val();
        get_land_zone(company_id, company_land_id);
    });

    function get_company_land(company_id){
        let land = '';
        $.ajax({
            url: "{{ route('ajax_get_company_land', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id
            },
            success: function(e) {
                land += '<option value=""> Please Select Land</option>';
                e.forEach(element => {
                land += '<option value="' + element.company_land_id + '">' + element
                    .company_land_name + '</option>';
                });
                $('#company_land').html(land);
            }
        });
    }

    function get_land_zone(company_id, company_land_id){
        console.log(company_id, company_land_id)
        let land = '';
        $.ajax({
            url: "{{ route('ajax_get_land_zone', ['tenant' => tenant('id')]) }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                company_id: company_id,
                company_land_id: company_land_id
            },
            success: function(e) {
                land += '<option value=""> Please Select Zone</option>';
                e.forEach(element => {
                land += '<option value="' + element.company_land_zone_id + '">' + element
                    .company_land_zone_name + '</option>';
                });
                $('#company_land_zone').html(land);
            }
        });
    }
</script>

@endsection
