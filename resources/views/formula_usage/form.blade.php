@extends('layouts.master')

@section('title') {{ $type }} Formula Usage @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <style type="text/css">
    .preloader {
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   z-index: 9999;
   background-image: url('/../assets/images/custom_preloader.gif');
   background-repeat: no-repeat;
   background-color: #FFF;
   background-position: center;
}
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
    <div class="preloader"></div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $type }} Usage</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Formula Usage</a>
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
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Formula Usage Details</h4>
                        <div class="row">
                            <div class="col-12">
                              <div class="row">
                                @if (auth()->user()->company_id == 0)
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Company: </label>
                                            {!! Form::select('company_id_sel', $company_sel, @$records->company_id, ['class' => 'form-control', 'id' => 'company_id_sel', 'required']) !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label for="">Company Land:</label>
                                      {!! Form::select('company_land_id', $company_land_sel, @$records->company_land_id, ['class' => 'form-control', 'id' => 'company_land_id', 'required']) !!}
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label for="">Company Land Zone:</label>
                                      <select required name="company_land_zone_id" id="company_land_zone_id" class="form-control">
                                        <option value=''>No Zone</option>
                                      </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label for="">Formula Category:</label>
                                      {!! Form::select('setting_formula_category_id', $formula_category_sel, @$records->setting_formula->setting_formula_category_id, ['class' => 'form-control', 'id' => 'setting_formula_category_id', 'required']) !!}
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label for="">Formula:</label>
                                      <select required name="setting_formula_id" id="setting_formula_id" class="form-control">
                                        <option value=''>No Formula Found</option>
                                      </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6" id="show_acres_value_input">
                                  <div class="form-group">
                                      <label for=""><span id="acres_value_label"></span></label>
                                      <input type="number" name="formula_usage_value" id="acres_value_input" step='.01' min='0'
                                          value="{{ @$records->formula_usage_value }}" class="form-control" required>
                                  </div>
                                </div>
                                <div class="col-sm-6" id="show_type">
                                  <div class="form-group">
                                      <label for="">Type:</label>
                                      {!! Form::select('formula_usage_type', $formula_type_sel, @$records->formula_usage_type, ['class' => 'form-control', 'id' => 'formula_usage_type', 'required']) !!}
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label for="user_dob">Formula Usage Date: </label>
                                        <div class="input-group">
                                          @php
                                            $current_date = date('Y-m-d');
                                          @endphp
                                            <!-- <input name="delivery_order_created" onclick="return false;" class="form-control input-mask" id="datetimepicker" value="{{@$records->delivery_order_created ?? $current_date}}">
                                            <span class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-calendar"></i>
                                                </span>
                                            </span> -->
                                            <div class="input-group">
                                                <input name="formula_usage_date" class="form-control input-mask" id="datepicker" value="{{ @$records->formula_usage_date ? @$records->formula_usage_date : $current_date }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                                <span class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="table-responsive border">
                                    <table class="table" id="item_table_id">
                                        <thead>
                                            <tr>
                                                <th id="items_s">Item(s)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="found_items">
                                                  <tr id="no_items">
                                                      <td colspan="4">No Formula Item(s) Details Records</td>
                                                  </tr>
                                        </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mt-2">
                                <span id="error_user"></span>
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{route('formula_usage_listing')}}" class="btn btn-secondary" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-12">
              <div class="card">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-12">
                                <div class="table-responsive border">
                                  <table class="table">
                                      <thead>
                                          <tr>
                                              <th scope="col" style="width: 50px; text-align: center; vertical-align: middle;">
                                                    <input type="checkbox" id="check_all" disabled/>
                                              </th>
                                              <th>Tree(s) <span id="value_unit_for_tree" hidden></span></th>
                                              <th><th>
                                          </tr>
                                      </thead>
                                      <tbody id="found_trees">
                                                <tr id="no_trees">
                                                    <td></td>
                                                    <td colspan="4">No Tree(s) Details Records</td>
                                                </tr>
                                      </tbody>
                                  </table>
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
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script>

        $(document).ready(function(e) {
            $('#company_id_sel').on('change', function() {
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

        $('#show_acres_value_input').hide();
        $('#show_type').hide();

        $('#check_all').on('change', function(event) {
            // if(this.checked) {
              $('.check_boxes').trigger('click');
            // } else {
            //   $('.check_boxes').each(function() {
            //     this.checked = false;
            //   });
            // }
          });

        $(document).on('keyup clickup change', 'input[name="formula_usage_item_value[]"]', function(){
          $('input[name="formula_usage_item_qty[]"]').trigger('change');
        })

        $(document).on('keyup clickup change', 'input[name="formula_usage_item_qty[]"]', function(){
          let count = $(this).attr('id').substr(18);
          let pack_input = parseFloat($(this).val());
          let kg_per_pack = parseFloat($('#kg_per_pack_input_' + count).val());
          let available_material = parseFloat($('#raw_material_available' + count).val());
          let total = 0 ;
          let balance_available = 0;
          // console.log(pack_input);
          if(pack_input > 0 && kg_per_pack > 0){
            total = pack_input * kg_per_pack;
            balance_available = available_material - total;
            if(balance_available < total  ){
                $('#total_after_round_' + count).html('<span class="text-danger font-size-12 ">'+
                                                        total+ 'kg/Pack(s)'+
                                                        '</span><div class="font-size-10 font-weight-bold text-danger">Not Enough Stock.</div>'
                );
                $('#submit').attr('disabled', true);
            }else{
                $('#total_after_round_' + count).html(total.toFixed(2) + ' kg');
                $('#formula_usage_item_total_' + count).val(total.toFixed(2));
                $('#submit').attr('disabled', false);
            }
          }else if(pack_input > 0 && isNaN(kg_per_pack) == true){
            $('#total_after_round_' + count).html('Please Insert kg/Pack');
          }else if(isNaN(pack_input) == true && kg_per_pack > 0){
            $('#total_after_round_' + count).html('Please Insert Pack(s)');
          }
          // console.log(kg_per_pack);
        })

        $(document).on('keyup', 'input[name="formula_usage_item_rounding[]"]', function(){
          let count = $(this).attr('id').substr(12);
          let rounding_input = parseFloat($(this).val());
          let needed_val = $('#value_need_after_input_'+ count).html();
          let volume_acre_span = document.getElementsByClassName("acre_volume_value")[0];
          let unit = volume_acre_span.innerHTML.substring(volume_acre_span.innerHTML.indexOf(' '), volume_acre_span.innerHTML.indexOf('/'));
          let amount = parseFloat(needed_val);
          let total = amount ;
          // console.log(amount);
          if(amount > 0 && isNaN(rounding_input) == false){
            total = amount + rounding_input;
            if(unit == ' kg'){
              $('#total_after_round_' + count).html(total.toFixed(2));
              $('#formula_usage_item_total_'+ count).val(total.toFixed(2));
            }else{
              $('#total_after_round_' + count).html(total.toFixed(2));
              $('#formula_usage_item_total_'+ count).val(total.toFixed(2));
            }
          }else if(isNaN(amount) == true && isNaN(rounding_input) == false){
            $('#total_after_round_' + count).html('Please Insert Volume/Acre');
          }else if(amount > 0 && isNaN(rounding_input) == true){
            if(unit == ' kg'){
              $('#total_after_round_' + count).html(total.toFixed(2));
              $('#formula_usage_item_total_'+ count).val(total.toFixed(2));
            }else{
              $('#total_after_round_' + count).html(total.toFixed(2));
              $('#formula_usage_item_total_'+ count).val(total.toFixed(2));
            }
          }
          // console.log(amount);
        })

        $('#acres_value_input').on('keyup', function() {
          let input_amount = parseFloat($(this).val());
          let volume_acre_span = document.getElementsByClassName("acre_volume_value");
          let needed = 0;
          volume_acre_span.forEach((val, i) => {
            let item_count = val.id.substr(18);
            let unit = val.innerHTML.substring(val.innerHTML.indexOf(' '), val.innerHTML.indexOf('/'));
            // console.log(unit);
            let value = parseFloat(val.innerHTML.substr(0,5));
            needed = input_amount * value;
            if(input_amount > 0){
              if(unit == ' kg'){
                $('#value_need_after_input_'+ item_count).html(needed.toFixed(2));
                $('#value_need_after_input2_'+ item_count).attr('hidden', false);
                $('#formula_usage_item_value_'+ item_count).val(needed.toFixed(2));
                $('#total_after_round_' + item_count).html(needed.toFixed(2));
                $('#total_after_round2_' + item_count).attr('hidden', false);
                $('#formula_usage_item_total_'+ item_count).val(needed.toFixed(2));
              }else{
                $('#value_need_after_input_'+ item_count).html(needed.toFixed(2));
                $('#value_need_after_input2_'+ item_count).attr('hidden', false);
                $('#formula_usage_item_value_'+ item_count).val(needed.toFixed(2));
                $('#total_after_round_' + item_count).html(needed.toFixed(2));
                $('#total_after_round2_' + item_count).attr('hidden', false);
                $('#formula_usage_item_total_'+ item_count).val(needed.toFixed(2));
              }
              $('input[name="formula_usage_item_rounding[]"]').trigger('keyup');
            }else{
              $('#value_need_after_input_'+ item_count).html('Please Insert Volume/Acre');
              $('#value_need_after_input2_'+ item_count).attr('hidden', true);
              $('#total_after_round_' + item_count).html('Please Insert Volume/Acre');
              $('#total_after_round2_' + item_count).attr('hidden', true);
            }
          });

        })
        $('#company_land_id').on('change', function() {
          $('#acres_value_input').val('');
          let acres_value_input = $('#acres_value_input').val();
          let company_land_id = $(this).val();
            @if (auth()->user()->company_id == 0)
                let company_id = $('#company_id_sel').val();
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
            @endif
          let company_land_zone_id = $('#company_land_zone_id').val();
          let tree_details = '';
          let val_unit = $('#value_unit_for_tree').html();
          // console.log(company_land_id);
          $('#found_trees').html('<tr><td></td><td>Loading...</td></tr>');
          if(company_land_id != ''){
              get_trees(company_land_id, company_land_zone_id, val_unit);
            $('#setting_formula_id').trigger('change');
          }else{
            tree_details += '<tr><td></td><td>Please select company land to proceed.</td></tr>';
            $('#setting_formula_id').trigger('change');
            $('#no_trees').hide();
            $('#found_trees').html(tree_details);
            $('#check_all').attr({checked: false, disabled: true});
          }

          $.ajax({
              url: "{{ route('ajax_get_zone_by_land') }}",
              method: 'post',
              data: {
                  _token: '{{ csrf_token() }}',
                  land_id: company_land_id,
                  company_id: company_id,
              },
              success: function(e){
                // console.log(e);
                let zone_option = '<option value="0">All Zone</option>';
                if(e.data.length > 0){
                  e.data.forEach(zone => {
                    zone_option += '<option value="' + zone.company_land_zone_id + '">' + zone.company_land_zone_name +
                        '</option>';
                  })
                  $('#company_land_zone_id').html(zone_option);
                  // $('#setting_formula_id').trigger('change');
                }else if(e.data.length == 0){
                    $('#company_land_zone_id').html('<option value="">No Zone Found</option>');
                    // $('#setting_formula_id').trigger('change');
                }
              },
          })
        })

        $('#company_land_zone_id').on('change', function() {
          $('#acres_value_input').val('');
          let acres_value_input = $('#acres_value_input').val();
          let company_land_id = $('#company_land_id').val();
          let company_land_zone_id = $(this).val();
          let tree_details = '';
          let val_unit = $('#value_unit_for_tree').html();
          // console.log(company_land_zone_id);
          $('#found_trees').html('<tr><td></td><td>Loading...</td></tr>');
          if(company_land_id != ''){
              get_trees(company_land_id, company_land_zone_id, val_unit);
            $('#setting_formula_id').trigger('change');
          }else{
            tree_details += '<tr><td></td><td>Please select company land to proceed.</td></tr>';
            $('#setting_formula_id').trigger('change');
            $('#no_trees').hide();
            $('#found_trees').html(tree_details);
            $('#check_all').attr({checked: false, disabled: true});
          }
        })

        $('#setting_formula_category_id').on('change', function() {
          let cate_id = $(this).val();
          let company_land_id = $('#company_land_id').val();
            @if (auth()->user()->company_id == 0)
                let company_id = $('#company_id_sel').val();
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
            @endif
          $('#acres_value_input').val('');
          if(cate_id == 3){
            $('#acres_value_label').html('Acres Needed:');
            $('#show_acres_value_input').show();
            $('#show_type').hide();
          }else if(cate_id == 2){
            $('#acres_value_label').html('Volume Needed (L):');
            $('#show_acres_value_input').show();
            $('#show_type').show();
          }else{
            $('#show_acres_value_input').hide();
            $('#show_type').hide();
          }

          $.ajax({
              url: "{{ route('ajax_get_formula_by_category') }}",
              method: 'post',
              data: {
                  _token: '{{ csrf_token() }}',
                  setting_formula_category_id: cate_id,
                  company_land_id: company_land_id,
                  company_id: company_id
              },
              success: function(e){
                let formula_option = '<option value="">Please Select Formula</option>';
                if(e.data.length > 0){
                  e.data.forEach(formula => {
                    let name_eng = JSON.parse(formula.setting_formula_name);
                    formula_option += '<option value="' + formula.setting_formula_id + '">' + name_eng.en +
                        '</option>';
                  })
                  $('#setting_formula_id').html(formula_option);
                  $('#setting_formula_id').trigger('change');
                }else if(e.data.length == 0){
                    $('#setting_formula_id').html('<option value="">No Formula Found</option>');
                    $('#setting_formula_id').trigger('change');
                }
              },
          })
        })

        $('#setting_formula_id').on('change', function() {
          let acres_value_input = $('#acres_value_input').val();
          let formula_cate_id = $('#setting_formula_category_id').val();
            @if (auth()->user()->company_id == 0)
                let company_id = $('#company_id_sel').val();
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
            @endif
          let company_land_id = $('#company_land_id').val();
          let company_land_zone_id = $('#company_land_zone_id').val();
          let formula_id = $(this).val();
          // console.log(company_id, company_land_id, formula_id);
          $('#acres_value_input').val('');
          $('#found_items').html('<tr><td>Loading...</td></tr>');
          $('#found_trees').html('<tr><td></td><td>Loading...</td></tr>');

          let item_table = document.getElementById('item_table_id').tHead.children[0];
          let item_details = '';
          let val_unit = '';
          let item_count = 0;
          if(company_land_id != ''){
            // if( ((formula_cate_id == 2 || formula_cate_id == 3) && acres_value_input > 0) || formula_cate_id == 1){
              $.ajax({
                  url: "{{ route('ajax_get_setting_formula_item') }}",
                  method: 'post',
                  data: {
                      _token: '{{ csrf_token() }}',
                      company_id: company_id,
                      company_land_id: company_land_id,
                      setting_formula_id: formula_id,
                  },
                  success: function(e){
                    // console.log(e);
                    if(e.data.length > 0){
                      if(e.data[0].value == 'pack'){
                        $('#formula_usage_type').attr({required: false});
                        $('#acres_value_input').attr({required: false});
                        $(item_table).empty();
                        $(item_table).append('<th>Item(s)</th>');
                        $(item_table).append('<th>Pack Needed</th>');
                        $(item_table).append('<th>kg/Pack</th>');
                        $(item_table).append('<th>Total</th>');
                      }else if(e.data[0].value == 'litre' || e.data[0].value == 'acres'){
                        if( e.data[0].value == 'litre' ){
                          $('#formula_usage_type').attr({required: true});
                        }else{
                          $('#formula_usage_type').attr({required: false});
                        }
                        $('#acres_value_input').attr({required: true});
                        $(item_table).empty();
                        $(item_table).append('<th>Item(s)</th>');
                        $(item_table).append('<th>Needed</th>');
                        $(item_table).append('<th>Round</th>');
                        $(item_table).append('<th>Total</th>');
                      }

                      e.data.forEach((data, i) => {
                        item_details += '<tr>';
                        if(data.value == 'pack'){
                          item_details += '<td>';
                          item_details +='<span><b>'+ JSON.parse(data.item.setting_formula_item_name).en +'</b></span><br>';
                          item_details += '<input hidden name="raw_material_id[]" value='+ data.item.raw_material_id +'>';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<input class="form-control" required name="formula_usage_item_qty[]" id="pack_needed_input_'+ item_count +'" type="number" step=".01" min="0">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<input class="form-control" required name="formula_usage_item_value[]" id="kg_per_pack_input_'+ item_count +'" type="number" step=".01" value='+ data.item.setting_formula_item_value +'  min="0">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<span id="total_after_round_'+ item_count + '">Please Insert Pack(s)</span>';
                          item_details += '<input type="number" step=".01" min="0" name="formula_usage_item_total[]" id="formula_usage_item_total_'+ item_count +'" hidden  min="0">';
                          item_details += '<input type="number" step=".01" min="0" name="raw_material_available[]" id="raw_material_available'+ item_count +'" hidden value='+data.item.raw_material_value+'  min="0">';
                          item_details += '</td>';
                          item_details += '</tr>';
                          val_unit = data.unit + '/Tree';
                        }
                        else if(data.value == 'litre'){
                          item_details += '<td style="width: 150px;">';
                          item_details +='<b>'+ JSON.parse(data.item.setting_formula_item_name).en +'</b><br><span class="acre_volume_value" id=acre_volume_value_'+ item_count +'>'+ data.item.setting_formula_item_value  +' '+ data.item.raw_material_value_unit+'</span><br>';
                          item_details += '<input hidden name="raw_material_id[]" value='+ data.item.raw_material_id +'>';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<span id=value_need_after_input_'+ item_count +'>Please Insert Volume/Acre</span><span hidden id=value_need_after_input2_'+ item_count +'> '+ data.item.raw_material_value_unit+'</span>';
                          item_details += '<input hidden id="formula_usage_item_value_'+ item_count +'" name="formula_usage_item_value[]" type="number" step=".01"  min="0">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<input name="formula_usage_item_rounding[]" id="round_input_'+ item_count +'" class="form-control" type="number"  min="0" required value="0" step=".01">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<span id="total_after_round_'+ item_count + '">Please Insert Rounding</span><span hidden id=total_after_round2_'+ item_count + '> '+ data.item.raw_material_value_unit+'</span>';
                          item_details += '<input hidden id="formula_usage_item_total_'+ item_count +'" name="formula_usage_item_total[]" type="number" step=".01"  min="0">';
                          item_details += '<input type="number" step=".01" min="0" name="raw_material_available[]" id="raw_material_available'+ item_count +'" hidden value='+data.item.raw_material_value+'  min="0">';
                          item_details += '</td>';
                          item_details += '</tr>';

                          val_unit = data.unit + '/Tree';
                        }
                        else if(data.value == 'acres'){
                          item_details += '<td style="width: 150px;">';
                          item_details +='<b>'+ JSON.parse(data.item.setting_formula_item_name).en +'</b><br><span class="acre_volume_value" id=acre_volume_value_'+ item_count +'>'+ data.item.setting_formula_item_value  +' '+ data.item.raw_material_value_unit +'/Acre</span><br>';
                          item_details += '<input hidden name="raw_material_id[]" value='+ data.item.raw_material_id +'>';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<span id=value_need_after_input_'+ item_count +'>Please Insert Volume/Acre</span><span hidden id=value_need_after_input2_'+ item_count +'> '+ data.item.raw_material_value_unit+'</span>';
                          item_details += '<input hidden id="formula_usage_item_value_'+ item_count +'" name="formula_usage_item_value[]" type="number" step=".01"  min="0">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<input name="formula_usage_item_rounding[]" id="round_input_'+ item_count +'" class="form-control" type="number" required value="0" step=".01"  min="0">';
                          item_details += '</td>';
                          item_details += '<td>';
                          item_details += '<span id="total_after_round_'+ item_count + '">Please Insert Rounding</span><span id=total_after_round2_'+ item_count + '> '+ data.item.raw_material_value_unit+'</span>';
                          item_details += '<input hidden id="formula_usage_item_total_'+ item_count +'" name="formula_usage_item_total[]" type="number" step=".01"  min="0">';
                          item_details += '<input type="number" step=".01" min="0" name="raw_material_available[]" id="raw_material_available'+ item_count +'" hidden value='+data.item.raw_material_value+'  min="0">';
                          item_details += '</td>';
                          item_details += '</tr>';

                          val_unit = data.unit + '/Tree';
                        }
                        $('#found_items').html(item_details);
                        $('#no_items').hide();
                        item_count++
                      });

                        get_trees(company_land_id, company_land_zone_id, val_unit);
                        $('#submit').attr('disabled', false);
                    }else if(e.data.length == 0){
                      get_trees(company_land_id, company_land_zone_id, val_unit);
                      $('#found_items').html('<tr><td>No Formula Item(s) Details Records</td></tr>');
                      $('#check_all').attr({checked: false, disabled: true});
                      $('#submit').attr('disabled', true);
                    }
                  },
              })
            // }else{
            //   item_details += '<tr><td>Please Insert Volume/Acre Needed.</td></tr>';
            //   $('#no_items').hide();
            //   $('#found_items').html(item_details);
            //   $('#found_trees').html('<tr><td></td><td>Please Insert Volume/Acre Needed.</td></tr>');
            // }
          }else{
            item_details += '<tr><td>Please select company land to proceed.</td></tr>';
            $('#no_items').hide();
            $('#found_items').html(item_details);
            $('#found_trees').html('<tr><td></td><td>Please select company land to proceed.</td></tr>');
          }
        })

        function get_trees(company_land_id, company_land_zone_id, val_unit)
        {
          let tree_details = '';
          if(company_land_id != ''){
            if(val_unit != '' && val_unit != 'g/Tree'){
              $.ajax({
                  url: "{{ route('ajax_find_tree_w_product_by_land') }}",
                  method: 'post',
                  data: {
                      _token: '{{ csrf_token() }}',
                      company_land_id: company_land_id,
                      company_land_zone_id: company_land_zone_id,
                  },
                  success: function(e){
                    // console.log(e.data);
                    if(e.data){
                      // console.log(e.data);
                      // Object.keys(e.data).forEach((keys) => {
                      //   console.log(e.data[keys].product_id);
                      // });

                      Object.keys(e.data).forEach((keys) => {
                        // console.log(e.data[keys]);
                        tree_details += '<tr>';
                        tree_details += '<td colspan="4" style="text-align: left; vertical-align:middle; background-color:lightgray;"><b>'+ e.data[keys].product.product_name + '</b> (' + e.data[keys].total_tree_count +')<input disabled id="product_id_'+e.data[keys].product_id+'" hidden name="product_id[]" value="'+e.data[keys].product_id+'"/></td>';
                        // tree_details += '<td style="text-align: center; vertical-align:middle;"><input  id="check_boxes_'+ e.data[keys].product_id +'" type="checkbox" name="product_id[]" value="' +
                        //                       e.data[keys].product_id + '"></td>';
                        // tree_details += '<td style="vertical-align:middle;"><label for="check_boxes_'+ e.data[keys].product_id +'">'+ e.data[keys].product.product_name + '</label>(' + e.data[keys].tree_count +')<input hidden type="number" id="formula_usage_product_quantity_'+ e.data[keys].product_id +'" disabled step=".01" name="formula_usage_product_quantity[]" value="'+e.data[keys].tree_count +'"></td>';
                        // tree_details += '<td><input class="form-control" type="number" id="value_per_tree_input_'+ e.data[keys].product_id +'" name="formula_usage_product_value_per_tree[]" disabled step=".01" placeholder='+ val_unit +'></td>';
                        tree_details += '</tr>';

                          Object.keys(e.data[keys].pnl_codes).forEach((pnl_item) => {
                            // console.log(e.data[keys].product_id);
                            tree_details += '<tr>';
                            tree_details += '<td style="text-align: center; vertical-align:middle;"><input class="check_boxes" id="check_boxes_'+ e.data[keys].product_id +'_' + e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code + '" type="checkbox" value="' +
                                                  e.data[keys].product_id + '"></td>';
                            tree_details += '<td style="vertical-align:middle;"><label for="check_boxes_'+ e.data[keys].product_id +'_'+ e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code + '">'+ e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_name + '</label> - <span style="font-style: italic;">'+e.data[keys].pnl_codes[pnl_item].tree_count+'</span><input hidden type="number" id="formula_usage_product_quantity_'+ e.data[keys].product_id +'_'+ e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code +'" disabled step=".01" name="formula_usage_product_quantity['+e.data[keys].product_id+']['+e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code+']" value="'+e.data[keys].pnl_codes[pnl_item].tree_count +'"  min="0"></td>';
                            tree_details += '<td><input class="form-control value_per_tree_input" type="number"  min="0" id="value_per_tree_input_'+ e.data[keys].product_id +'_'+ e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code + '" name="formula_usage_product_value_per_tree['+e.data[keys].product_id+']['+e.data[keys].pnl_codes[pnl_item].company_pnl_sub_item_code+']" disabled step=".01" placeholder='+ val_unit +'></td>';
                            tree_details += '<tr>';
                          });

                        $('#found_trees').html(tree_details);
                        $('#no_trees').hide();
                      });

                      $('#check_all').attr('disabled', false);
                      $('#submit').attr('disabled', false);
                    }else if(!e.data){
                      $('#found_trees').html('<tr><td></td><td>No Tree(s) Details Records</td></tr>');
                      $('#check_all').attr({checked: false, disabled: true});
                      $('#submit').attr('disabled', true);
                    }
                  },
              })
            }else if(val_unit == 'g/Tree'){
              $('#found_trees').html('<tr><td></td><td>Value for tree(s) added automatically.</td></tr>');
            }else if(val_unit == ''){
              $('#found_trees').html('<tr><td></td><td>No Tree(s) Details Records</td></tr>');
            }
          }else{
            tree_details += '<tr><td></td><td>Please select company land to proceed.</td></tr>';
            $('#no_trees').hide();
            $('#found_trees').html(tree_details);
            $('#check_all').attr({checked: false, disabled: true});
            $('#submit').attr('disabled', true);
          }
        }

        $(document).on('click', 'input[type="checkbox"]', function(){
            let value = $(this).attr('id').substr(12);
            let id = value.substring(0, value.indexOf('_'));
            // console.log(id);
            if($(this)[0].checked == true){
              $('#value_per_tree_input_' + value).attr({required: true, disabled: false});
              $('#formula_usage_product_quantity_' + value).attr({required: true, disabled: false});
              $('#product_id_' + id).attr({required: true, disabled: false});
              // console.log(value);
            }else{
              $('#value_per_tree_input_' + value).val('').attr({required: false, disabled: true});
              $('#formula_usage_product_quantity_' + value).attr({required: false, disabled: true});
              $('#product_id_' + id).attr({required: false, disabled: true});
            }
            // console.log($(this));
        })

        $('#submit').on('click', function() {
          let check_tree_table = document.getElementById('found_trees').children;
          let checked = document.querySelectorAll('input[type="checkbox"]:checked');
          let all_checkbox = document.querySelectorAll('input[type="checkbox"]');

          if(check_tree_table.length > 1){
            if(checked.length <= 0){
              // window.alert('Please Select At Least 1 Tree...');
              $(all_checkbox[0]).attr('required', true);
            }
          }
          // console.log(check_tree_table);
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

        @if($type == 'Edit')
          let company_land_id = <?php echo json_encode(@$records->company_land_id); ?>;
          let company_land_zone_id = <?php echo json_encode(@$records->company_land_zone_id); ?>;
          let setting_formula_id = <?php echo json_encode(@$records->setting_formula_id); ?>;
          let setting_formula_category_id = <?php echo json_encode(@$records->setting_formula->setting_formula_category_id); ?>;
          let formula_usage_value = <?php echo json_encode(@$records->formula_usage_value); ?>;
          let formula_usage_item_rounding = <?php echo json_encode(@$records->formula_usage_item); ?>;
          let formula_usage_product = <?php echo json_encode(@$records->formula_usage_product); ?>;
          // console.log(formula_usage_product);
          $('#company_land_id').trigger('change');
          $('#setting_formula_category_id').trigger('change');
          let change_fc = $('#setting_formula_category_id').trigger('change');

          setTimeout(function(){
            $('#company_land_zone_id').val(company_land_zone_id).trigger('change');

          },3000);
          setTimeout(function(){
            $('#setting_formula_id').val(setting_formula_id).trigger('change');

          },3200);
          if(setting_formula_category_id == 2 || setting_formula_category_id == 3){
            setTimeout(function(){
                $('#acres_value_input').val(formula_usage_value).trigger('keyup');
            },4000);
            setTimeout(function(){
              formula_usage_item_rounding.forEach((item, i) => {
                $('#round_input_'+ i).val(item.formula_usage_item_rounding).trigger('keyup');
              });
            },4800);
          }else{
            setTimeout(function(){
              formula_usage_item_rounding.forEach((item, i) => {
                $('#pack_needed_input_'+ i).val(item.formula_usage_item_qty).trigger('keyup');
                $('#kg_per_pack_input_'+ i).val(item.formula_usage_item_value).trigger('keyup');
              });
            },4800);
          }

          // if(setting_formula_category_id == 1 || setting_formula_category_id == 2 || setting_formula_category_id == 4){
            setTimeout(function(){
              formula_usage_product.forEach((tree, i) => {
                let json = JSON.parse(tree.formula_usage_product_json);
                // console.log(json);
                json.forEach((item, i) => {
                  // console.log(item);
                  $('#check_boxes_'+ tree.product_id +'_'+ item.company_pnl_sub_item_code).trigger('click');
                  $('#value_per_tree_input_' + tree.product_id +'_'+ item.company_pnl_sub_item_code).val(item.formula_usage_product_value_per_tree);
                });
                // $('#check_boxes_'+ tree.product_id).trigger('click');
                // $('#value_per_tree_input_' + tree.product_id).val(tree.formula_usage_product_value_per_tree);
              });
              $('.preloader').fadeOut('slow');
            },5200);
          // }else{
          //   setTimeout(function(){
          //     $('.preloader').fadeOut('slow');
          //   },5400);
          // }
        @else
        $('.preloader').fadeOut('slow');

        @endif
});
    </script>
@endsection
