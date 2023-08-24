@extends('layouts.master')

@section('title') {{ $title }} Company Land - Products  @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ $title }} Company Land - Products </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Product </a>
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
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company Land Product Details</h4>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input name="company_name" type="text" class="form-control" value="{{ @$company_land->company->company_name }}" readonly>
                                    <input name="company_id" hidden value="{{@$company_land->company->company_id}}"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_land_name">Land Name</label>
                                    <input name="company_land_name" type="text" class="form-control" value="{{ @$company_land->company_land_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_bank">Company Bank</label>
                                    {!! Form::select('company_bank_id', $company_bank_sel, @$company_land->company_bank_id, ['class' => 'form-control', 'id' => 'company_bank_id', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_land_total_acre">Total Acre</label>
                                    <input name="company_land_total_acre" type="number" class="form-control" value="{{ @$company_land->company_land_total_acre }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="products">Products</label>
                            <div class="custom-control custom-checkbox col-sm-8">
                                <input type="checkbox" id="check_all" class="form-check-input"/>
                                <label for="check_all">Select All</label>
                            </div>
                            <div class="row col-sm-8">
                                <ul>
                                    @foreach ($products as $product_id => $product_name)
                                        <li class="custom-control custom-checkbox mb-2 mr-2" style="list-style:none;display:inline-block;width:200px">
                                            <input type="checkbox" id="product_{{ $product_id }}"
                                                name="product_id[]" value="{{ $product_id }}"
                                                class= "check_product_id form-check-input"
                                                @if(isset($company_land->product_company_land))
                                                    @if(in_array($product_id, collect($company_land->product_company_land)->pluck('product_id')->toArray()))
                                                        checked
                                                    @endif
                                                @endif
                                            />
                                            <label
                                                for="product_{{ $product_id }}">{{ $product_name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Zone Details
                              @if (auth()->user()->user_type_id == 2)
                                    <button type="button" class="btn btn-outline-primary btn-sm ml-2"
                                        id="add_land_zone">ADD</button>
                              @endif
                              <button type="button" class="btn btn-outline-success btn-sm ml-2"
                                  id="download_sample">Download Sample Excel</button>
                            </h5>
                            <div class="table-responsive border">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Zone Name</th>
                                            <th>Total Tree</th>
                                            <th style="min-width: 150px">Date</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $zone_count = 1;@endphp
                                          @if (@$company_land->company_land_zone && count($company_land->company_land_zone) > 0)
                                              @foreach (@$company_land->company_land_zone as $key => $zone)
                                                @if($zone->is_delete != 1)
                                                      <tr id="zone_details_{{ $zone_count }}" class="bank_details">
                                                          <td>{{ $zone_count }}
                                                          </td>
                                                          <td>
                                                                  <input type="text" name="company_land_zone_name[]"
                                                                      class="form-control"
                                                                      placeholder="Please Insert Zone Name"
                                                                      required
                                                                      value="{{ @$zone->company_land_zone_name }}"
                                                                      @if (auth()->user()->user_type_id != 2) readonly @endif>
                                                                  <input type="number" hidden name="company_land_zone_id[]" value="{{@$zone->company_land_zone_id}}">
                                                          </td>
                                                          <td>
                                                              <input type="number" name="company_land_zone_total_tree[]" id="" class="form-control"
                                                                      required
                                                                      value="{{ @$zone->company_land_zone_total_tree > 0 ? $zone->company_land_zone_total_tree : '-' }}"
                                                                       readonly>
                                                          </td>
                                                            <td>
                                                                <div class="input-daterange input-group" id="datepicker6"
                                                                    data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                                    data-provide="datepicker" data-date-container="#datepicker6">
                                                                    @if(count($zone->company_land_tree) == 0)
                                                                        <input type="text" style="width: 75px" class="form-control" name="import_date[{{$zone_count - 1}}]"
                                                                            placeholder="Select Date" id="import_date_{{$zone_count}}"
                                                                            autocomplete="off" value="{{ date("Y-m-d")}}" required>
                                                                    @endif
                                                                </div>

                                                            </td>
                                                              <td>
                                                                    <span @if(count($zone->company_land_tree) == 0) hidden @endif>This Zone has already imported.</span>
                                                                    @if(auth()->user()->user_type_id != 1)
                                                                        <input type="file" name="import_excel[]" class="form-control-file" @if(count($zone->company_land_tree) > 0) hidden @endif>
                                                                    @else
                                                                        <span @if(count($zone->company_land_tree) > 0) hidden @endif>This Zone have not import.</span>
                                                                    @endif
                                                              </td>
                                                              @if (auth()->user()->user_type_id == 2)
                                                              <td>
                                                                  <i class="mdi mdi-trash-can font-size-20 text-danger"
                                                                      id="zone_details_delete"></i>
                                                              </td>
                                                          @endif
                                                      </tr>
                                                      @php $zone_count++;@endphp
                                                @endif
                                              @endforeach
                                            @else
                                              <tr id="no_zone_records">
                                                  <td colspan="4">No Zone Details Records</td>
                                              </tr>
                                            @endif
                                        <tr id="add_new_zone_details"></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('company_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<!-- Plugins js -->
<script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>

<script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="{{ global_asset('assets/js/pages/form-editor.init.js') }}"></script>
<script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script>
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
        $('#th_date').show();
        $('#extra_td').show();

        let input = '';

        input += '<tr id="zone_details_' + zone_count + '" class=bank_details>';
        input += '<td>' + zone_count + '<input type="hidden" name="company_land_zone_id[]"></td>';
        input += '<td><input type="text" name="company_land_zone_name[]" class="form-control" placeholder="Please Insert Zone Name" required></td>';
        input += '<td><input type="number" name="company_land_zone_total_tree[]" class="form-control" step="1" readonly></td>';
        input += '<td><div class="input-daterange input-group" id="datepicker6"'
                                +'data-date-format="yyyy-mm-dd" data-date-autoclose="true"'
                                +'data-provide="datepicker" data-date-container="#datepicker6">'
                                +'<input type="text" style="width: 75px" class="form-control" name="import_date['+ (zone_count - 1) +']"'
                                    +'placeholder="Select Date" id="import_date_' + zone_count + '"'
                                   +'autocomplete="off" value="{{ date("Y-m-d")}}" required>'
                            +'</div></td>';
        input += '<td><input type="file" name="import_excel[]" class="form-control-file"></td>';
        input += '<td><i class="mdi mdi-trash-can font-size-20 text-danger" id="zone_details_delete"></i></td>';
        input += '</tr>';

        $(input).insertBefore('#add_new_zone_details');
        zone_count++;
    });

    $('#download_sample').click(function(){
      let company_land_id = <?php echo json_encode($post->company_land_id);?>;
      window.location.assign('{{URL::to('ajax_download_excel_sample_zone')}}/' + company_land_id)
    })
    $(document).on('click', '#zone_details_delete', function() {
        if (confirm('Are you sure you want to delete?')) {
            let id = $(this).parent().parent().attr('id');
            $(this).parent().parent().remove();
            zone_count--;
            index_count--;
        }
    });
</script>
@endsection
