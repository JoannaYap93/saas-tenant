@extends('layouts.master')

@section('title') {{$title}} Company Expense @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <style>
        /* Style image holder */
        .img-wrap {
            position: relative;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19);
            border-radius: 10px;
        }

        .img-wrap .del_image {
            position: absolute;
            top: -3px;
            right: -1px;
            z-index: 100;
            padding: 5px 2px 2px;
            background-color: #FFF;
            cursor: pointer;
            opacity: .5;
            text-align: center;
            font-size: 20px;
            color: #ff4a4a;
            line-height: 10px;
            border-radius: 50%;
        }
        .img-wrap:hover .del_image {
            opacity: 1;
        }



        /* Style the Image Used to Trigger the Modal */
        #expense_item_media {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #expense_item_media:hover {opacity: 0.7;}

        /* Modal Content (Image) */
        .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: rgb(255, 255, 255);
        padding: 10px 0;
        height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
        }

        @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
        }

        /* The Close Button */
        .closeModal {
        position: fixed;
        top: 15px !important;
        right: 35px;
        color: #f1f1f1;
        font-size: 30px;
        font-weight: bold;
        transition: 0.3s;
        }

        .closeModal:hover,
        .closeModal:focus {
        color: rgb(255, 255, 255);
        text-decoration: none;
        cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
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
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{$title}} Company Expense</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Expense</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{$submit}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Company Expense Details</h4>
                        <div class="row mt-3">
                            <div class="col-12">
                                @php
                                    if($title == "Edit"){
                                        $format_date = @$records->company_expense_year.'-'.@$records->company_expense_month.'-'.@$records->company_expense_day;
                                        $date = date('Y-m-d', strtotime($format_date));
                                    }
                                @endphp
                                <div class="row">
                                    <input type="hidden" id="user_id" name="user_id" value="{{auth()->user()->user_id}}">
                                    @if ($title == "Edit")
                                        <input type="hidden" name="date_created" value="{{@$records->company_expense_created}}">
                                    @endif
                                        @if (auth()->user()->company_id != 0)
                                            <input type="hidden" id="company_id" name="company_id" value="{{auth()->user()->company_id}}">
                                        @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">

                                                <input type="text" style="width: 75px" class="form-control" name="expense_date"
                                                    placeholder="Select Date" id="expense_date"
                                                    autocomplete="off" value="{{@$date}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id_sel', $company_sel, @$records->company_id, ['class' => 'form-control', 'id' => 'company_id_sel', 'required']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                          <label for="user_land">Company Land:</label><br>
                                          <div class="row col-sm-12" id="company_land_id">

                                            <span id="after_land_cb"></span>
                                          </div>
                                            {{-- {!! Form::select('company_land_id', $company_land_sel, @$records->company_land_id, ['class' => 'form-control', 'id' => 'company_land_id', 'required']) !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Expense Type:</label>
                                            {!! Form::select('expense_type', $expense_type_sel, @$records->company_expense_type, ['class' => 'form-control', 'id' => 'expense_type', 'required']) !!}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Role: </label>
                                            <div class="row col-sm-12">
                                                @foreach ($worker_role_cb as $key => $worker_role)
                                                    <div class="custom-control custom-checkbox col-sm-6">
                                                        <input type="radio" id="worker_role_{{ $worker_role->worker_role_id }}" name="worker_role_id" value="{{ $worker_role->worker_role_id }}" class= "form-check-input worker_role_cb"
                                                          @if(@$records->worker_role_id && @$records->worker_role_id == $worker_role->worker_role_id)
                                                          checked
                                                          @endif
                                                        />
                                                        <label for="worker_role_{{ $worker_role->worker_role_id }}">{{ $worker_role->worker_role_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 farm_manager_div">
                                        <div class="form-group">
                                            <label for="manager_id">Farm Manager: </label>
                                            <select name="manager_id" class="form-control" id="manager_id"  required>
                                                @foreach ( $manager_sel as $manager_id => $manager )
                                                    <option value="{{$manager_id}}" @if($manager_id != null && $manager['worker_id'] == @$records->worker_id) selected @endif>
                                                        {{$manager['name'] ?? $manager}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Expense Category:</label>
                                            <h5>Labour<h5>
                                            <input hidden id="labour_id" name="expense_category_id" value="2"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- items --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title d-flex align-items-center justify-content-between">Worker Expenses
                            </h4>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th style="width: 6%">Worker</th>
                                                <th style="width: 5%">Type</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 30%">Task</th>
                                                <th style="width: 11%">Total (RM)</th>
                                            </thead>
                                            <tbody>
                                              <tr id="grand_total_td">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align: right;">Grand Total: </td>
                                                <td id="grand_total"></td>
                                                <input hidden id="company_expense_total" name="company_expense_total" type="number" step=".01" value="0"/>
                                              </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                          <div class="row">
                              <div class="col-sm-6">
                                  <span id="error_user"></span>
                                  <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                  <a href="{{route('company_expense_listing')}}" class="btn btn-secondary" >Cancel</a>
                              </div>
                          </div>
                        </div>
                      </div>
                </div>

            </div>
        </div>
    </form>

    <!-- modal popup images -->
    <div id="popupModal" class="modal" tabindex="-1" role="dialog" >
        <span class="closeModal" data-dismiss="modal">&times;</span>
        <div class="modal-content image_wrap"></div>
        <div id="caption"></div>
    </div>

     <!-- Delete Modal -->
     <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form>
                    @csrf
                    <div class="modal-body">
                        <h5>Are you sure want to delete this image ?</h5>
                        <div class="image_div_delete" align="center">

                        </div>
                        <input type="hidden" name="media_id" id="media_id">
                        <input type="hidden" name="ce_item_id" id="ce_item_id">
                    </div>
                    <div class="modal-footer">
                        <span id="del_button" class="btn btn-danger" data-dismiss="modal">Delete</span>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Images Expense Item -->
    <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>Expense Item Images</b>
                    </div>
                    <div class="modal-body view_images_body">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    {{-- <script src="{{ URL::asset('assets/libs/summernote/summernote.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    {{-- <script src="{{ URL::asset('assets/js/jquery-ui.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        var row_table = 1;
        var first_time_load = true;
        $(document).ready(function(e) {
            var row=1;

            var exist_company = "<?php echo @$records->company_id; ?>";
            if (exist_company > 0) {
                $('#company_id_sel').trigger("change");
            }

            function get_farm_manager(company_id) {

                var exist_farm_manager = "<?php echo @$records->company_id; ?>";
                var manager_farm_id = "<?php echo @$records->worker->user_id; ?>";

                let user = '<option value="">Please Select Farm Manager</option>';
                $('#manager_id').html('<option value="">Loading...</option>');
                $.ajax({
                    url: "{{ route('ajax_get_farm_manager_sel_by_company') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        company_id: company_id,
                    },
                    success: function(e) {
                        if (e.length > 0) {
                            e.forEach(u => {
                                if(u.id == exist_farm_manager || u.id == manager_farm_id){
                                    user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                                }else{
                                    user += '<option value="' + u.id + '">' + u.name +'</option>';
                                }
                            });
                            $('#manager_id').html(user);
                        } else {
                            $('#manager_id').html('<option value="">No Farm Manager</option>');
                        }
                    }
                });
            }

            $('#expense_category_id').on('change', function(e) {
                if ($(this).val() != 2) {
                    $('.expense_card').show();
                    $('.worker_field *').prop('disabled', true).hide();
                } else {
                    $('.expense_card').hide();
                }

                if ($(this).val() == 2) {
                    $('.worker_field *').prop('disabled', false);
                    $('.worker_field').show();
                    get_worker($('#manager_id').val(), $('#expense_type').val(), $('#company_id_sel').val());
                }
            });

            $('#expense_type').on('change', function(e) {
              Array.prototype.forEach.call($('.worker_role_cb'), function(el) {
                  if(el.checked == true){
                    let title = <?php echo json_encode($title); ?>;
                    if (first_time_load && title == 'Edit') {
                      var expense_manager_id = <?php echo json_encode(@$records->worker_id); ?>;
                      $.ajax({
                          url: "{{ route('ajax_get_user_id_by_worker') }}",
                          method: "POST",
                          data: {
                              _token: "{{ csrf_token() }}",
                              id: expense_manager_id
                          },
                          success: function(e) {
                            get_worker(e.user_id, $('#expense_type').val(), $('#company_id_sel').val(), el.value);
                          }
                      });
                    } else {
                      get_worker($('#manager_id').val(), $('#expense_type').val(), $('#company_id_sel').val(), el.value);
                    }
                  }
              });
            });

            $('.worker_role_cb').on('click', function(e) {
                let worker_role_id = $(this).val();
                let manager_id = 0;
                $('#manager_id').val('');
                $('#expense_category_id').val('').attr('disabled', false);
                $('.expense_card').hide();
                $('.worker_field').hide();
                if(worker_role_id != 1){
                  $('#manager_id').attr({diabled: true, hidden: true, required: false});
                  $('.farm_manager_div').attr('hidden', true);
                  get_worker(manager_id, $('#expense_type').val(), $('#company_id_sel').val(), worker_role_id);
                }else{
                  manager_id = $('#manager_id').val();
                  $('#manager_id').attr({diabled: false, hidden: false, required: true});
                  $('.farm_manager_div').attr('hidden', false);
                  if (manager_id) {
                    get_worker(manager_id, $('#expense_type').val(), $('#company_id_sel').val(), worker_role_id);
                  } else {
                    $('#grand_total').html('RM 0.00');
                    $('table tr.worker_table_row').remove();
                    $('table tr.no_workers').remove();
                    $('table tr.sel_to_proceed').remove();
                  }
                }
            });

            $('#manager_id').on('change', function(e) {
                let worker_role_id = $('.worker_role_cb').val();
                $('#expense_category_id').val('').attr('disabled', false);
                $('.expense_card').hide();
                $('.worker_field').hide();
                if ($(this).val()) {
                  get_worker($(this).val(), $('#expense_type').val(), $('#company_id_sel').val(), worker_role_id);
                } else {
                  $('#grand_total').html('RM 0.00');
                  $('table tr.worker_table_row').remove();
                  $('table tr.no_workers').remove();
                  $('table tr.sel_to_proceed').remove();
                }
            });


            @if(auth()->user()->user_type_id == 1)
                $('#company_id_sel').on('change', function() {
                    let id = $(this).val();
                    let land = '';
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
                                    land += '<div class="custom-control custom-checkbox col-sm-6">';
                                    land += '<input type="checkbox" id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class="form-check-input" >';
                                    land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                    land += '</div>';
                                });
                                $('#company_land_id').html(land);
                            } else {
                                $('#company_land_id').html('<option value="">No Land</option>');
                            }
                        }
                    });
                    get_farm_manager($(this).val());
                });
            @endif

            @if (@$title == 'Add')
              $('#manager_id').attr({diabled: true, hidden: true, required: false});
              $('.farm_manager_div').attr('hidden', true);
              get_land_user('{{ auth()->user()->company_id }}');
            @else
              var company_id;
              @if (@auth()->user()->user_type_id == 1)
                company_id = "{{ $records->company_id }}";
              @else
                company_id = "{{ auth()->user()->company_id }}";
              @endif

              let expense_type = <?php echo json_encode(@$records->company_expense_type); ?>;
              let worker_role_id = <?php echo json_encode(@$records->worker_role_id); ?>;
              let company_expense_worker = <?php echo json_encode(@$records_worker); ?>;
              let worker_table_row = document.getElementsByClassName("worker_table_row");
              if(worker_role_id != 1){
                $('.farm_manager_div').attr('hidden', true);
                $('#manager_id').attr('disabled', true)
              }
              $('#expense_type').trigger('change');

              get_farm_manager(company_id);
              get_land_user(company_id);
              check_loaded_data();
            @endif

            function get_land_user(id) {
                let land = '';
                let sland = <?php echo json_encode(@$records->company_expense_land); ?>;
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
                              let check = '';
                                if (sland != null) {
                                  land += '<div class="custom-control custom-checkbox col-sm-6">';
                                  land += '<input type="checkbox" ';
                                  sland.forEach(company_expense_land => {
                                    if(company_expense_land.company_land_id == element.company_land_id){
                                      land += 'checked';
                                    }
                                  });
                                  land += ' id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class= "form-check-input" />';
                                  land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                  land += '</div>';
                                } else {
                                  land += '<div class="custom-control custom-checkbox col-sm-6">';
                                  land += '<input type="checkbox" id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class= "form-check-input" />';
                                  land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                  land += '</div>';
                                }
                            });
                            $('#company_land_id').html(land);
                        } else {
                            $('#company_land_id').html('<option value="">No Land</option>');
                        }
                    }
                });
            }

            function get_worker(manager_id, expense_type, company_id, worker_role_id){
              first_time_load = false;
              @if (@$title == 'Edit')
                let is_edit = 1;
                let company_expense_id = <?php echo json_encode(@$records->company_expense_id); ?>;
              @else
                let is_edit = 0;
                let company_expense_id = 0;
              @endif

              let worker_details = '';
              $('#grand_total').html('RM 0.00');
              $('table tr.worker_table_row').remove();
              $('table tr.no_workers').remove();
              $('table tr.sel_to_proceed').remove();
              var user_type = <?php echo auth()->user()->user_type_id ?>;
              if(expense_type != ''){
                  if((worker_role_id != '' && worker_role_id != 1 )|| (worker_role_id == 1 && manager_id != '') || (user_type == 1)){
                      $.ajax({
                          url: "{{ route('ajax_get_worker_list') }}",
                          method: 'post',
                          data: {
                              _token: '{{ csrf_token() }}',
                              manager_id: manager_id,
                              expense_type: expense_type,
                              company_id: company_id,
                              worker_role_id: worker_role_id,
                              is_edit: is_edit,
                              company_expense_id: company_expense_id
                          },
                          success: function(e){
                            if(e.data.length > 0){
                              let worker_row = '';
                              e.data.forEach((worker, w) => {
                                worker_row += "<tr class='worker_table_row'>";
                                worker_row += "<td>"+ worker.name +"<input hidden type='number' name='worker_id_[]' value=" + worker.id +" /></td>";
                                worker_row += "<td>"+ worker.type_name +"<input hidden type='text' name='worker_type_name_["+ worker.id +"]' value=" + worker.type_name +" /></td>";
                                worker_row += '<td><select name="worker_status_['+ worker.id +']" class="form-control worker_status_sel_'+ worker.id +'" id="worker_status_'+ worker.id+'" >';
                                  @foreach($worker_status_sel as $worker_status_id => $worker_status_name)
                                    worker_row += '<option value="{{$worker_status_id}}">{{$worker_status_name}}</option>';
                                  @endforeach
                                worker_row += '</select>';
                                worker_row += '<select name="worker_timing_['+ worker.id+']" hidden disabled class="form-control mt-2 worker_time_slot_'+ worker.id +'" id="worker_time_slot_'+ worker.id+'" >';
                                  @foreach($time_slot as $time)
                                    worker_row += '<option value="{{$time}}">{{$time}}</option>';
                                  @endforeach
                                worker_row += '</select></td>';
                                worker_row += "<td><div class='row row_for_select_task' style='padding-left: 12px;' id='row_for_select_task_"+ worker.id +"'><select id='select_task_add_"+ worker.id +"' class='form-control col-6'>";
                                  worker.expense.forEach((task, t) => {
                                    worker_row += '<option value='+ task.id + '_'+ t +'>'+ task.name +'</option>';
                                  });
                                worker_row += '</select><input id="add_task_'+ worker.id +'-'+ w +'" type="button" class="btn btn-primary mt-3 mt-lg-0 add_task_'+ worker.id +'" value="Add" /></div></td>';
                                worker_row += "<td><span class='get_all_sub_total sub_total_"+ worker.id +"'></span><input hidden type='number' step='.01' id='input_sub_total_"+ worker.id +"' name='worker_total_["+ worker.id +"]' /></td>";
                                worker_row += "</tr>";
                              });
                              $('#sel_to_proceed').hide();
                              $('#no_workers').hide();
                              $(worker_row).insertBefore('#grand_total_td');
                              // check_loaded_data();
                              get_loaded_worker(e.data);
                            }else if(e.data.length == 0){
                              $('table tr.worker_table_row').remove();
                              $('table tr.sel_to_proceed').remove();
                                $('<tr id="no_workers" class="no_workers"><td></td><td>No Worker(s) Details Records</td></tr>').insertBefore('#grand_total_td');
                                $('#sel_to_proceed').hide();
                            }
                          }
                      });
                  } else{
                    $('table tr.worker_table_row').remove();
                    $('table tr.no_workers').remove();
                    worker_details += '<tr id="sel_to_proceed" class="sel_to_proceed"><td></td><td>Please select role to proceed.</td></tr>';
                    $('#no_workers').hide();
                    $(worker_details).insertBefore('#grand_total_td');
                  }
              }else if(expense_type != '' && expense_type == 'monthly'){
                $('table tr.worker_table_row').remove();
                $('table tr.no_workers').remove();
              }
            }

            function get_loaded_worker(data){
              let time_cal = 0;

              $('input[type="button"]').attr('disabled', true);
              data.forEach((worker, i) => {
                $('.worker_status_sel_' + worker.id).on('change', function(e) {
                  let worker_id = $(this).attr('id').substring(14);
                  let value = $(this).val();
                  $('.delete_task_' + worker_id).parent().parent().remove();
                  if(value > 0){
                    if(value == 1){
                      time_cal = 8;
                      $('.add_task_' + worker_id).attr('disabled', false);
                      $('.worker_time_slot_' + worker_id).attr({hidden: true, disabled: true});
                    }else if(value == 2){
                      $('.add_task_' + worker_id).attr('disabled', true);
                      $('.worker_time_slot_' + worker_id).attr({hidden: false, disabled: false});
                      $('.worker_time_slot_' + worker_id).on('change', function(e) {
                        let half_day_sel_val = $(this).val();
                        if(half_day_sel_val == 'Please Select Time Slot'){
                          $('.add_task_' + worker_id).attr('disabled', true);
                        }else{
                          $('.add_task_' + worker_id).attr('disabled', false);
                          let first_time_str = half_day_sel_val.substring(0, half_day_sel_val.indexOf('-'));
                          let second_time_str = half_day_sel_val.substring(half_day_sel_val.indexOf('-')).substring(1);
                          let first_time_num = Number(first_time_str.match(/\d+/)[0]);
                          let second_time_num = Number(second_time_str.match(/\d+/)[0]);
                          time_cal = second_time_num - first_time_num;
                        }
                      })
                    }else{
                      $('.worker_time_slot_' + worker_id).attr({hidden: true, disabled: true});
                      $('.add_task_' + worker_id).attr('disabled', true);
                    }

                  }else{
                    $('.add_task_' + worker_id).attr('disabled', true);
                    $('.worker_time_slot_' + worker_id).attr({hidden: true, disabled: true});
                  }
                  $('.sub_total_' + worker_id).html('<span></span>');
                  grand_total();
                });


                $(document).on('click', '.add_task_' + worker.id ,function(e) {
                  let worker_row = "";
                  let worker_id = $(this).attr('id').substr(0, $(this).attr('id').indexOf('-')).substring(9);
                  let worker_index = $(this).attr('id').substring($(this).attr('id').indexOf('-')).substring(1);
                  let selected_task_index = $('#select_task_add_' + worker_id).val().substring($('#select_task_add_' + worker_id).val().indexOf('_')).substring(1);
                  let expense_total = 0;
                  let sub_total = 0;
                  let value_based_on_type = 0;

                  if($('#added_task_'+ worker_index + '_' + selected_task_index).length <= 0){
                    if(data[worker_index].type_id == 1 || data[worker_index].type_id == 3){
                      value_based_on_type = parseFloat(data[worker_index].expense[selected_task_index].value);
                      if(data[worker_index].expense[selected_task_index].expense_type_id == 3){
                          expense_total += parseFloat(value_based_on_type) / 8 * time_cal;
                    }else if(data[worker_index].expense[selected_task_index].expense_type_id == 6){
                          expense_total += parseFloat(value_based_on_type) * time_cal;
                    }else if(data[worker_index].expense[selected_task_index].expense_type_id == 5){
                          expense_total += parseFloat(value_based_on_type);
                    }
                      worker_row += "<div class='added_task_"+ worker_id +"' id='added_task_"+ worker_index +"_"+ selected_task_index +"'>";
                      worker_row += "<div style='display: block; align-items: center;'>"
                      worker_row += "<span><b>"+data[worker_index].expense[selected_task_index].name+"</b></span>";
                      worker_row += "<input hidden type='number' name='expense_id_["+ worker_id +"][]' value="+ data[worker_index].expense[selected_task_index].id +" />";
                      worker_row += '<i class="delete_task_'+ worker_id +' mdi mdi-trash-can font-size-20 text-danger" id="delete_task" style="float: right"></i>';
                      worker_row += "</div>";
                      worker_row += "<div class='row col-12 pl-0'>"
                      worker_row += '<div class="col-4"><div class="form-group"><label for="expense_value">Amount'+ data[worker_index].expense[selected_task_index].expense_type_name +':</label>';
                      worker_row += "<input type='number' name='expense_value_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' id='expense_value_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +"' class='expense_value_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2' step='.01' value='"+ value_based_on_type +"'/></div></div>";
                      if(data[worker_index].expense[selected_task_index].overwrite_commission > 0){
                        expense_total += parseFloat(data[worker_index].expense[selected_task_index].overwrite_commission);

                        worker_row += '<div class="col-4"><div class="form-group"><label for="expense_commission">Commision:</label>';
                        worker_row += "<input type='number' name='setting_expense_commission_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' class='expense_commission_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2' step='.01' value='"+ data[worker_index].expense[selected_task_index].overwrite_commission +"'/></div></div>";
                      }
                      worker_row += '<div class="col-4"><div class="form-group"><label for="expense_total">Expense Total:</label>';
                      worker_row += "<input type='number' id='expense_total_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +"' name='expense_total_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' class='expense_total_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2 expense_total_"+ worker_id +"' step='.01' value='"+ expense_total.toFixed(2) +"'/></div></div>";
                      worker_row += "</div>";
                      worker_row += "<hr></div>";

                      let all_added_expense_total = $('.expense_total_'+ worker_id);
                      for(var i = 0; i < all_added_expense_total.length; i++){
                        let each_added_expense_total = $(all_added_expense_total[i]).val();
                        sub_total += parseFloat(each_added_expense_total);
                      }

                      sub_total += parseFloat(expense_total);

                    }else if(data[worker_index].type_id == 2){
                      worker_row += "<div class='added_task_"+ worker_id +"' id='added_task_"+ worker_index +"_"+ selected_task_index +"'>";
                      worker_row += "<div style='display: block; align-items: center;'>"
                      worker_row += "<span><b>"+data[worker_index].expense[selected_task_index].name+"</b></span>";
                      worker_row += "<input hidden type='number' name='expense_id_["+ worker_id +"][]' value="+ data[worker_index].expense[selected_task_index].id +" />";
                      worker_row += '<i class="delete_task_'+ worker_id +' mdi mdi-trash-can font-size-20 text-danger" id="delete_task" style="float: right"></i>';
                      worker_row += "</div>";
                      worker_row += "<div class='row col-12 pl-0'>"
                      worker_row += '<div class="col-4"><div class="form-group"><label for="expense_value">Amount'+ data[worker_index].expense[selected_task_index].expense_type_name +':</label>';
                      worker_row += "<input type='number' id='expense_value_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +"' name='expense_value_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' class='expense_value_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2' step='.01' value='"+ data[worker_index].expense[selected_task_index].value +"'/></div></div>";
                      worker_row += '<div class="col-4"><div class="form-group"><label for="expense_quantity">Quantity:</label>';
                      worker_row += "<input type='number' id='expense_quantity_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +"' name='qty_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' class='expense_quantity_"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2' step='.01' value='0'/></div></div>";
                      worker_row += '<div class="col-4"><div class="form-group"><label for="expense_total">Expense Total:</label>';
                      worker_row += "<input type='number' id='expense_total_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +"' name='expense_total_["+ worker_id +"]["+ data[worker_index].expense[selected_task_index].id +"]' class='expense_total_"+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id +" form-control mr-2 mb-2 expense_total_"+ worker_id +"' step='.01' value='0'/></div></div>";
                      worker_row += "</div>";
                      worker_row += "<hr></div>";
                    }
                  }else{
                    alert('Task selected already added to '+ data[worker_index].name +'!');
                  }

                  $(worker_row).insertBefore('#row_for_select_task_' + worker_id);
                  $(document).on('keyup', '.expense_quantity_' + data[worker_index].expense[selected_task_index].id , function(e) {
                    let worker_id = $(this).attr('id').substring(17, $(this).attr('id').indexOf('-'));
                    let task_id = $(this).attr('id').substring($(this).attr('id').indexOf('-')).substring(1);
                    let task_value = $('#expense_value_' + worker_id + '-' + task_id).val();
                    let expense_total = parseFloat(task_value) * parseFloat($(this).val());
                    let sub_total_2 = 0;
                    let each_added_expense_total = 0;

                    if(isNaN(expense_total)){
                      $('#expense_total_'+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id).val(0);
                    }else{
                      $('#expense_total_'+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id).val(expense_total);
                    }

                    let all_added_expense_total = $('.expense_total_'+ worker_id);
                    for(var i = 0; i < all_added_expense_total.length; i++){
                      if($(all_added_expense_total[i]).val() != ' '){
                        each_added_expense_total = $(all_added_expense_total[i]).val();
                        sub_total_2 += parseFloat(each_added_expense_total);
                      }else if($(all_added_expense_total[i]).val() === ' '){
                        sub_total_2 += parseFloat(0);
                      }
                    }

                    $('.sub_total_' + worker_id).html('RM ' + sub_total_2.toFixed(2));
                    $('#input_sub_total_' + worker_id).val(sub_total_2.toFixed(2));
                    grand_total();
                  });

                  $(document).on('keyup', '.expense_value_' + worker_id +'-'+ data[worker_index].expense[selected_task_index].id , function(e) {
                    let sub_total_3 = 0;
                    let expense_total_2 = 0;
                    if(data[worker_index].type_id == 1){
                      if(data[worker_index].expense[selected_task_index].expense_type_id == 3){
                          expense_total_2 += parseFloat($(this).val()) / 8 * time_cal;
                      }else{
                          expense_total_2 += parseFloat($(this).val()) * time_cal;
                      }
                        if(data[worker_index].expense[selected_task_index].overwrite_commission > 0){
                          expense_total_2 += parseFloat($('.expense_commission_'+ worker_id +'-'+ data[worker_index].expense[selected_task_index].id).val());
                        }

                        if(isNaN(expense_total_2)){
                          $('#expense_total_'+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id).val(0);
                        }else{
                          $('#expense_total_'+ worker_id +"-"+ data[worker_index].expense[selected_task_index].id).val(expense_total_2);
                        }

                      let all_added_expense_total = $('.expense_total_'+ worker_id);
                      for(var i = 0; i < all_added_expense_total.length; i++){
                        if($(all_added_expense_total[i]).val() != ' '){
                          each_added_expense_total = $(all_added_expense_total[i]).val();
                          sub_total_3 += parseFloat(each_added_expense_total);
                        }else if($(all_added_expense_total[i]).val() === ' '){
                          sub_total_3 += parseFloat(0);
                        }
                      }

                      $('.sub_total_' + worker_id).html('RM ' + sub_total_3.toFixed(2));
                      $('#input_sub_total_' + worker_id).val(sub_total_3.toFixed(2));
                      grand_total();

                    }else if(data[worker_index].type_id == 2){
                      $('.expense_quantity_' + data[worker_index].expense[selected_task_index].id).trigger('keyup');
                    }
                  });

                  $(document).on('keyup', '.expense_total_'+ worker_id +'-'+ data[worker_index].expense[selected_task_index].id , function(e) {
                    let sub_total_4 = 0;
                    let all_added_expense_total = $('.expense_total_'+ worker_id);
                    for(var i = 0; i < all_added_expense_total.length; i++){
                        each_added_expense_total = $(all_added_expense_total[i]).val();
                        sub_total_4 += parseFloat(each_added_expense_total);
                    }

                    if(isNaN(sub_total_4)){
                      $('.sub_total_' + worker_id).html('RM 0');
                      $('#input_sub_total_' + worker_id).val(0);
                    }else{
                      $('.sub_total_' + worker_id).html('RM ' + sub_total_4.toFixed(2));
                      $('#input_sub_total_' + worker_id).val(sub_total_4.toFixed(2));
                    }
                    grand_total();
                  });

                  $(document).on('keyup', '.expense_commission_'+ worker_id +'-'+ data[worker_index].expense[selected_task_index].id , function(e) {
                    $('.expense_value_' + worker_id +'-'+ data[worker_index].expense[selected_task_index].id).trigger('keyup');
                  });

                  $('.sub_total_' + worker_id).html('RM ' + sub_total.toFixed(2));
                  $('#input_sub_total_' + worker_id).val(sub_total.toFixed(2));
                  grand_total();
                });
              });
            }

            function grand_total(){
              let sub_total = document.getElementsByClassName('get_all_sub_total');
              let grand_total = 0;
              sub_total.forEach((sub, i) => {
                  let num = parseFloat(sub.innerHTML.substring(3));
                  if(isNaN(num) == false){
                    grand_total += num;

                  }
              });
              $('#grand_total').html('RM ' + grand_total.toFixed(2));
              $('#company_expense_total').val(grand_total.toFixed(2));
            }


            function check_loaded_data(){
              if(worker_table_row.length <= 0){
                setTimeout(check_loaded_data, 1000);
              }else{
                let setting_time_slot = <?php echo json_encode(@$time_slot); ?>;

                company_expense_worker.forEach((worker, i) => {
                  let company_expense_worker_detail = JSON.parse(worker.company_expense_worker_detail);
                  if(company_expense_worker_detail.status > 0){
                    $('.worker_status_sel_' + worker.worker_id).val(Number(company_expense_worker_detail.status)).trigger('change');
                    if(company_expense_worker_detail.status == 2){
                      if(setting_time_slot[company_expense_worker_detail.timing]){
                        $('.worker_time_slot_'+ worker.worker_id).val(setting_time_slot[company_expense_worker_detail.timing]).trigger('change');
                      }else{
                        $('.worker_time_slot_'+ worker.worker_id).val(company_expense_worker_detail.timing).trigger('change');
                      }
                      company_expense_worker_detail.task.forEach((task, t) => {
                        let x = document.getElementById("select_task_add_"+ worker.worker_id);
                        for (i = 0; i < x.length; i++) {
                          let txt = x.options[i].value;
                          if(task.expense_id == txt.substring(0, txt.indexOf('_'))){
                            $('#select_task_add_'+ worker.worker_id).val(task.expense_id + '_' + txt.substring(txt.indexOf('_')).substring(1)).trigger('change');
                            $('.add_task_' + worker.worker_id).trigger('click');
                            $('#expense_value_' + worker.worker_id + '-' + task.expense_id).val(task.expense_value);
                            $('#expense_commission_' + worker.worker_id + '-' + task.expense_id).val(task.setting_expense_overwrite_commission);
                            $('#expense_quantity_' + worker.worker_id + '-' + task.expense_id).val(task.qty);
                            $('#expense_total_' + worker.worker_id + '-' + task.expense_id).val(task.expense_total);
                            $('.expense_total_' + worker.worker_id + '-' + task.expense_id).trigger('keyup');
                          }
                        }
                      });
                    }else if(company_expense_worker_detail.status == 1){
                      $('.worker_time_slot_'+ worker.worker_id).val(company_expense_worker_detail.timing).trigger('change');
                      company_expense_worker_detail.task.forEach((task, t) => {
                        let x = document.getElementById("select_task_add_"+ worker.worker_id);
                        for (i = 0; i < x.length; i++) {
                          let txt = x.options[i].value;
                          if(task.expense_id == txt.substring(0, txt.indexOf('_'))){
                            $('#select_task_add_'+ worker.worker_id).val(task.expense_id + '_' + txt.substring(txt.indexOf('_')).substring(1)).trigger('change');
                            $('.add_task_' + worker.worker_id).trigger('click');
                            $('#expense_value_' + worker.worker_id + '-' + task.expense_id).val(task.expense_value);
                            $('#expense_commission_' + worker.worker_id + '-' + task.expense_id).val(task.setting_expense_overwrite_commission);
                            $('#expense_quantity_' + worker.worker_id + '-' + task.expense_id).val(task.qty);
                            $('#expense_total_' + worker.worker_id + '-' + task.expense_id).val(task.expense_total);
                            $('.expense_total_' + worker.worker_id + '-' + task.expense_id).trigger('keyup');
                          }
                        }
                      });
                    }
                  }
                });

              }
            }

            $(document).on('click', '#delete_task', function() {
                if (confirm('Are you sure you want to delete?')) {
                    let worker_id = $(this).attr('class').substring(12, $(this).attr('class').indexOf(' '));
                    $(this).parent().parent().remove();
                    let sub_total = 0;
                    let all_added_expense_total = $('.expense_total_'+ worker_id);
                    for(var i = 0; i < all_added_expense_total.length; i++){
                      let each_added_expense_total = $(all_added_expense_total[i]).val();
                      sub_total += parseFloat(each_added_expense_total);
                    }
                    $('.sub_total_' + worker_id).html('RM ' + sub_total.toFixed(2));
                    $('#input_sub_total_' + worker_id).val(sub_total.toFixed(2));
                    grand_total();
                }
            });
        });

    </script>
@endsection
