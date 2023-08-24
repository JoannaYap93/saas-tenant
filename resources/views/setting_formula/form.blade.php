@extends('layouts.master')

@section('title') {{ $title }} Setting Formula @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }} Formula</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
						<a href="javascript: void(0);"> Formula</a>
					</li>
					<li class="breadcrumb-item active">Form</li>
                </ol>
            </div>

        </div>
    </div>
</div>

@if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
    @endforeach
@enderror

<div id="add_error">

</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form method="POST" id="form_post" action="{{ $submit }}">
                <div class="card-body">
                  <div class="col-6">
                    <h4 class="card-title mb-4">{{$title}} Formula</h4>
                        @csrf
                        <div class="form-group">
                            <label>Name(EN)<span class="text-danger">*</span></label>
                            <input type="text" name="setting_formula_name_en" class="form-control" value="{{ json_decode(@$post->setting_formula_name)->en ?? '' }}" >
                        </div>
                        <div class="form-group">
                            <label>Name(CN)<span class="text-danger">*</span></label>
                            <input type="text" name="setting_formula_name_cn" class="form-control" value="{{ json_decode(@$post->setting_formula_name)->cn ?? ''}}" >
                        </div>
                        <div class="form-group">
                            <label>Category<span class="text-danger">*</span></label>
                            {!! Form::select('setting_formula_category_id', $setting_formula_category_sel, @$post->setting_formula_category_id, ['class' => 'form-control', 'id' => 'setting_formula_category_id']) !!}
                        </div>

                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            {!! Form::select('setting_formula_status', $setting_formula_status_sel, @$post->setting_formula_status, ['class' => 'form-control', 'id' => 'setting_formula_status']) !!}
                        </div>

                        <div class="form-group">
                            <label>Measurement<span class="text-danger">*</span></label>
                            {!! Form::select('setting_formula_measurement', $setting_formula_measurement_sel, @$post->setting_formula_measurement, ['class' => 'form-control', 'id' => 'setting_formula_measurement']) !!}
                        </div>

                        <div class="form-group">
                            <label>Unit<span class="text-danger">*</span></label>
                            {!! Form::select('setting_formula_unit', $setting_formula_unit_sel, @$post->setting_formula_unit, ['class' => 'form-control', 'id' => 'setting_formula_unit']) !!}
                        </div>

                        <div class="repeater">
                            <div data-repeater-list="setting_formula_item">
                              <div class="row">
                                <div  class="col-lg-4">
                                    <label for="">Raw Material</span></label>
                                </div>
                                <div  class="col-lg-4">
                                    <label for="">Value</span></label>
                                </div>
                              </div>
                                @if (@$post->setting_formula_item)
                                    @foreach($post->setting_formula_item as $item)
                                        <div data-repeater-item class="row">
                                            <input type="hidden" name="item[setting_formula_item_id][]" id="setting_formula_item_id" value="{{ @$item['setting_formula_item_id'] }}">

                                            <div  class="form-group col-lg-4">
                                                {!! Form::select('item[raw_material_id][]', $setting_raw_material_sel, @$item['raw_material_id'], ['class' => 'form-control rm_id_{{$count}}', 'id'=>'raw_material_id']) !!}
                                            </div>

                                            <div  class="form-group col-lg-4">
                                                <input type="number" name="item[setting_formula_item_value][]" id="setting_formula_item_value" class="form-control" value="{{ @$item['setting_formula_item_value'] }}">
                                            </div>

                                            <div class="form-group col-lg-4 align-self-center">
                                              <i data-repeater-delete class="mdi mdi-trash-can font-size-20 text-danger"
                                                      id="zone_details_delete"></i>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                  @php
                                    $count = 0;
                                  @endphp
                                    <div data-repeater-item class="row">

                                        <div  class="form-group col-lg-4">
                                            {!! Form::select('item[raw_material_id][]', $setting_raw_material_sel,'', ['class' => 'form-control raw_material_id', 'id'=>'raw_material_id']) !!}
                                        </div>

                                        <div  class="form-group col-lg-4">
                                            <input type="number" name="item[setting_formula_item_value][]" id="setting_formula_item_value" class="form-control" value="">
                                        </div>

                                        <div class="form-group col-lg-4 align-self-center">
                                          <i data-repeater-delete class="mdi mdi-trash-can font-size-20 text-danger"
                                                  id="zone_details_delete"></i>
                                        </div>
                                        @php
                                          $count++;
                                        @endphp
                                    </div>
                                @endif
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success mt-3 mt-lg-0" value="Add"/>
                        </div>

                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" id="form_post_button" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('setting_formula_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                  </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- end row -->

@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/jquery-repeater/jquery-repeater.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-repeater.int.js')}}"></script>
    <script>
        $('body').on('change','select[id="raw_material_id"]',function(){
            let rm_id = $(this).val();
            let placeholder = $(this).parent().parent().children().find('input[id="setting_formula_item_value"]');

            $.ajax({
                url: "{{ route('ajax_get_rm_name_for_placeholder', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rm_id: rm_id,
                },
                success: function(e) {
                placeholder.attr('placeholder', e)
                }
            });

        });


        $("#form_post_button").on("click",function(e){
            e.preventDefault();

            $("#add_error").empty();

            var validation_flag = false;
            var setting_formula_item_name = false;
            var raw_material_id = false;
            var setting_formula_item_value = false;

            var count = 1;
            $("input[id='setting_formula_item_name']").each(function(){
                var val = $(this).val();
                if(val === undefined || val === ''){
                    setting_formula_item_name = true;
                    $(this).focus();
                    $("#add_error").append('<div class="alert alert-danger" role="alert">The setting formula item name '+ count +' field is required.</div>');
                }

                count++;
            });

            var count = 1;
            $("input[id='raw_material_id']").each(function(){
                var val = $(this).val();
                console.log(val);
                if(val === undefined || val === ''){
                    raw_material_id = true;
                    $(this).focus();
                    $("#add_error").append('<div class="alert alert-danger" role="alert">The raw material '+ count +' field is required.</div>');
                }
                count++;
            });

            var count = 1;
            $("input[id='setting_formula_item_value']").each(function(){
                var val = $(this).val();
                console.log(val);
                if(val === undefined || val === ''){
                    setting_formula_item_value = true;
                    $(this).focus();
                    $("#add_error").append('<div class="alert alert-danger" role="alert">The setting formula item value '+ count +' field is required.</div>');
                }
                count++;
            });

            if(setting_formula_item_name || raw_material_id || setting_formula_item_value){
                validation_flag = true;
            }

            if(!validation_flag){
                $("#form_post").submit();
            }else{
                return;
            }
        });
    </script>
@endsection
