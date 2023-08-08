@extends('layouts.master')

@section('title') Edit Zone  @endsection

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
            <h4 class="mb-0 font-size-18"> Company Land Zone - Listing </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Zone </a>
                    </li>
                    <li class="breadcrumb-item active">Form</li>
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
        <form method="POST" action="{{ route ('zone_edit',$company_land_zone_id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6">
                                            @foreach ($record as $zone)

                                                     <h5>Zone Name:</h5><input type="text" name="company_land_zone_name"
                                                            class="form-control"
                                                            placeholder="Please Insert Zone Name"
                                                            required
                                                            value="{{ $zone->company_land_zone_name }}">
                                                    <input type="number" hidden name="company_land_zone_id" value="{{$zone->company_land_zone_id}}">
                                                    <input type="number" hidden name="company_id" value="{{$zone->company_id}}">
                                                    <input type="number" hidden name="company_land_id" value="{{$zone->company_land_id}}">
                                                    <br>

                                                    <h5>Total Tree: </h5>  <input type="number" name="company_land_zone_total_tree" id="" class="form-control"
                                                        placeholder="Please Insert Tree Amount"
                                                        required
                                                        value="{{ $zone->company_land_zone_total_tree }}">
                                            @endforeach

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
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
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<!-- Plugins js -->
<script src="{{ URL::asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script>

{{-- <script>
    $('#check_all').on('click', function(event) {
        if(this.checked) {
            $('.btn-selected').prop("disabled", false);
            $('.check_product_id').each(function() {
                this.checked = true;
            });
        } else {
            $('.btn-selected').prop("disabled", true);
            $('.check_product_id').each(function() {
                this.checked = false;
            });
        }
    });

    let zone_count = 1;

    @if (@$company_land->company_land_zone && count($company_land->company_land_zone) > 0)
        zone_count = 1;
        @foreach($company_land->company_land_zone as $zone)
          @if($zone->is_delete != 1)
                zone_count++;
          @endif
        @endforeach
    @endif

    $('#add_land_zone').click(function() {
        $('#no_zone_records').hide();

        let input = '';

        input += '<tr id="zone_details_' + zone_count + '" class=bank_details>';
        input += '<td>' + zone_count + '<input type="hidden" name="company_land_zone_id[]"></td>';
        input += '<td><input type="text" name="company_land_zone_name[]" class="form-control" placeholder="Please Insert Zone Name" required></td>';
        input += '<td><input type="number" name="company_land_zone_total_tree[]" class="form-control" step="1" placeholder="Please Insert Tree Amount" required></td>';
        input += '<td><i class="mdi mdi-trash-can font-size-20 text-danger" id="zone_details_delete"></i></td>';
        input += '<td><input type="file" name="import_excel[]" class="form-control-file"></td>';
        input += '</tr>';

        $(input).insertBefore('#add_new_zone_details');
        zone_count++;
    });

    $('#download_sample').click(function(){
      window.location.assign('{{URL::to('ajax_download_excel_sample_zone')}}')
    })

    $(document).on('click', '#zone_details_delete', function() {
        if (confirm('Are you sure you want to delete?')) {
            let id = $(this).parent().parent().attr('id');
            $(this).parent().parent().remove();
            zone_count--;
        }
    });
</script> --}}
@endsection
