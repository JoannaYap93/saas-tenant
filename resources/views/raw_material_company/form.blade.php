@extends('layouts.master')

@section('title') {{ $type }} Raw Material Company @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <style>

    </style>
@endsection

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $type }} Raw Material Company</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Raw Material Company</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ $submit }}" method="POST">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Raw Material Company Details</h4>
                        <div class="row">
                            <div class="col-12">
                              <!-- <div class="row"> -->
                              {{-- <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Company Land:</label>
                                    {!! Form::select('company_land_id', $company_land_sel, @$records->company_land_id, ['class' => 'form-control', 'id' => 'company_land_id', 'required']) !!}
                                </div>
                              </div> --}}
                              <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Raw Material:</label>
                                    {!! Form::select('raw_material_id', $raw_material_sel, @$records->raw_material_id, ['class' => 'form-control', 'id' => 'raw_material_id', 'required']) !!}
                                    <span id="existing_warning" style="font-style: italic; font-size: 12px; color: red;">Existing Raw Material</span>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Quantity <span id="quantity_unit">@if($type == "Edit"){{@$records->raw_material->raw_material_quantity_unit == 'bottle' ? '( Bottles )' : '( Packs )'}}@endif</span>:</label>
                                    <input type="number" name="raw_material_quantity" id=""
                                        value="{{ @$records->raw_material_quantity }}" class="form-control">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Value <span id="value_unit">@if($type == "Edit"){{ @$records->raw_material->raw_material_value_unit == 'litre' ? '( Litre )' : '( KG )'}}@endif</span>:</label>
                                    <input type="number" name="raw_material_value" id="" step='.01'
                                        value="{{ @$records->raw_material_value }}" class="form-control">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Status:</label>
                                    {!! Form::select('raw_material_company_status', $status, @$records->raw_material_company_status, ['class' => 'form-control', 'required']) !!}
                                </div>
                              </div>
                              <!-- </div> -->
                              <div class="row">
                                  <div class="col-sm-6">
                                      <span id="error_user"></span>
                                      <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                      <a href="{{route('raw_material_company_listing')}}" class="btn btn-secondary" >Cancel</a>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/form-editor.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/libs/summernote/summernote.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    {{-- <script src="{{ URL::asset('assets/js/jquery-ui.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function(e) {
          if(  $('#company_land_id').val() == ''){
            $('#raw_material_id').attr('disabled', true);
          }
          $('#existing_warning').hide();

          $('#company_land_id').on('change', function() {
            if($(this).val() != ''){
              $('#raw_material_id').attr('disabled', false);
              $('#raw_material_id').trigger('change');
            }else{
              $('#raw_material_id').attr('disabled', true);
              $('#raw_material_id').trigger('change');
            }
          })

        $('#raw_material_id').on('change', function(){
          let rm_id = $(this).val();
          let rmc_id = <?php echo json_encode(@$records->raw_material_company_id); ?>;
          let company_id = <?php echo json_encode(@$records->company_id ?? auth()->user()->company_id); ?>;
        //   let company_land_id = $('#company_land_id').val();
          console.log(company_id);
          $.ajax({
                url: "{{ route('ajax_check_existing_raw_material_company') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rm_id: rm_id,
                    rmc_id: rmc_id,
                    company_id: company_id,
                    // company_land_id: company_land_id
                },
                success: function(e) {
                  console.log(e);
                  if(e.result == true){
                    $('#existing_warning').show();
                    $('#submit').attr('disabled', true);
                  }else{
                    $('#existing_warning').hide();
                    $('#submit').attr('disabled', false);
                  }

                  if(e.data['raw_material_quantity_unit'] == 'bottle'){
                    $('#quantity_unit').html('( Bottles )');
                  }else{
                    $('#quantity_unit').html('( Packs )');
                  }

                  if(e.data['raw_material_value_unit'] == 'ml'){
                    $('#value_unit').html('( ML )');
                  }else{
                    $('#value_unit').html('( KG )');
                  }
                }
            });
        });

        $('#summernote').summernote({
            height: 300,
            maximumImageFileSize: 1024 * 1024 * 20,
        });

        $('.select2').select2({
            maximumInputLength: 90,
            tags: true,
            tokenSeparators: [','],
            createTag: function(params) {
                let term = $.trim(params.term);
                // console.log(term);

                return {
                    id: term,
                    text: term,
                    newTag: true,
                }
            },
        });
        $('.select2').on('change', function() {
            var data = $(".select2 option:selected");
            var tag_arr = {};
            $.each(data, function(index, value) {
                tag_arr[index] = value.text;
            });
            $('#product_tag_2').val(JSON.stringify(tag_arr));
        })

});
    </script>
@endsection
