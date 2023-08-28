@extends('layouts.master')

@section('title')
    Company Land Tree Listing
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                <span class="mr-2">Company Land Tree Listing</span>
                @can('company_land_tree_manage')
                {{-- @if(auth()->user()->user_type_id == 2) --}}
                    <a href="{{route('land_tree_add', ['tenant' => tenant('id'), 'id' => $company_land_zone_id])}}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                    class="fas fa-plus"></i> ADD NEW</a>
                    {{-- @endif --}}
                @endcan
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Tree</a>
                    </li>
                    <li class="breadcrumb-item active">Listing</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="POST" action="{{ route('land_tree_listing', ['tenant' => tenant('id'), 'id' => $company_land_zone_id])}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validationCustom03">Freetext</label>
                                        <input type="text" class="form-control" name="freetext"
                                            placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Product: </label>
                                    {!! Form::select('product_id', $product_sel, @$search['product_id'], ['class' => 'form-control', 'id' => 'product_id']) !!}
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Status: </label>
                                    {!! Form::select('company_land_tree_status', @$status_sel, @$search['company_land_tree_status'], ['class' => 'form-control']) !!}
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Category: </label>
                                    {!! Form::select('company_land_tree_category', $company_land_tree_cate_sel, @$search['company_land_tree_category'], ['class' => 'form-control']) !!}
                                  </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Zone: </label>
                                    {!! Form::select('company_land_zone_id', $zone_sel, @$search['company_land_zone_id'], ['class' => 'form-control', 'id' => 'company_land_zone_id']) !!}
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validationCustom03">Tree Circumference (lower)</label>
                                        <input type="text" class="form-control" name="tree_circumference_lower"
                                            placeholder="Inch" value="{{ @$search['tree_circumference_lower'] }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validationCustom03">Tree Circumference (upper)</label>
                                        <input type="text" class="form-control" name="tree_circumference_upper"
                                            placeholder="Inch" value="{{ @$search['tree_circumference_upper'] }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <button type="submit"
                                        class="btn btn-primary  waves-effect waves-light mr-2"
                                        name="submit" value="search">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <button type="submit"
                                        class="btn btn-danger  waves-effect waves-light mr-2" name="submit"
                                        value="reset">
                                        <i class="fas fa-times mr-1"></i> Reset
                                    </button>
                                    <a  href="{{ route('land_zone_listing', ['tenant' => tenant('id'), 'company_id' => $company_land->company->company_id, 'company_land_id' => $company_land->company_land_id]) }}" class="btn btn-secondary waves-effect waves-light mr-2"
                                        name="submit">
                                        <i class="fas fa-arrow-left mr-1"></i> Zone Listing
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col sm-12">
                        <p><strong>{{$company_land->company->company_name}}<br/>
                        <i>{{$company_land->company_land_name}}</i><br/>
                        {{$company_zone_detail->company_land_zone_name}}</strong></p>
                    </div>
                    {{-- <div class="column sm-12">
                        <button
                        data-toggle="modal"
                        data-target="#manage_tree"
                        data-id="#selected_tree_id"
                        data-company-id="{{auth()->user()->company_id}}"
                        style="float: right;"
                        class="btn btn-success mb-0 manage_tree">Manage</button>
                    </div> --}}
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                          @can('company_land_tree_manage')

                          <tr>
                              <th scope='col'>
                                  <div class="form-check-label" >
                                      <label class="d-flex flex-wrap">
                                          <input type="checkbox" class="check_all_tree_ids" id='check_all_tree_ids' name='check_all_tree_ids' value=''>
                                          {{-- &nbsp; Check All --}}
                                      </label>
                                  </div>
                              </th>
                              <th><b>Check All</b></th> <th></th> <th></th> <th></th> <th></th> <th></th> <th></th>
                              <th>
                                  <button
                                  data-toggle="modal"
                                  data-target="#manage_tree"
                                  data-id="#selected_tree_id"
                                  data-company-id="{{auth()->user()->company_id}}"
                                  style="width: 80px"
                                  class="btn btn-sm btn-outline-success waves-effect waves-light manage_tree">Manage Selected</button>
                              </th>
                          </tr>
                          @endcan
                            <tr>
                            @can('company_land_tree_manage')
                                <th></th>
                            @endcan
                                <th scope="col" style="width: 70px;">Code</th>
                                <th style="text-align:center">Product</th>
                                <th>Year of Tree</th>
                                <th>Sick</th>
                                <th>Bear Fruit</th>
                                <th>Last Treatment</th>
                                <th>Tree Status</th>
                                    <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($records->isNotEmpty())
                                @foreach ( $records as $row )
                                    <tr>
                                        @can('company_land_tree_manage')
                                            <td>
                                                <input type="checkbox"
                                                id="selected_land_tree_id[]"
                                                name="selected_land_tree_id[]"
                                                value="{{ @$row->company_land_tree_id }}"
                                                >
                                            </td>
                                        @endcan
                                        <td>
                                            <b>{{@$row->company_land_tree_no}}</b><br><span style="font-style: italic; font-size: 12px">{{@$row->company_pnl_sub_item_code}}</span>
                                        </td>
                                        <td style="background-color:rgb(246, 242, 242); text-align:center">
                                            @if(!empty($row->product->product_name))
                                                <b>{{@$row->product->product_name}}</b>
                                            @else
                                                @if(auth()->user()->user_type_id == 2)
                                                    <a href="{{ route('fix_product_data', ['tenant' => tenant('id'), 'id' => $row->company_land_tree_id])}}"><b style= "color: red">No Data</b></a>
                                                @else
                                                <b style= "color: red">No Data</b></a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{@$row->company_land_tree_age}} Years ({{@$row->company_land_tree_circumference}} Inch)
                                        </td>
                                        <td>
                                            {!!$row->is_sick?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>'!!}
                                        </td>
                                        <td>
                                            {!!$row->is_bear_fruit?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>'!!}
                                        </td>
                                        <td>
                                            @if(@$row->last_treatment)
                                                {{$row->last_treatment->company_land_tree_action->company_land_tree_action_name}} - {{$row->last_treatment->company_land_tree_log_value}}ml<br/>
                                                <i>{{$row->last_treatment->company_land_tree_log_date}}</i>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @php
                                            switch(@$row->company_land_tree_status){
                                                case 'alive':
                                                    $status = "<span class='badge badge-success font-size-11'>Alive</span>";
                                                    break;
                                                case 'dead':
                                                    $status = "<span class='badge badge-danger'>Dead</span>";
                                                    break;
                                                case 'saw off':
                                                    $status = "<span class='badge badge-warning'>Saw Off</span>";
                                                    break;
                                            }
                                        @endphp
                                        <td>
                                            {!!$status!!}
                                            <br>
                                            <td>
                                            @can('company_land_tree_manage')
                                                {{-- @if(auth()->user()->user_type_id == 2) --}}
                                                <a href="{{ route('land_tree_edit', ['tenant' => tenant('id'), 'id' => $row->company_land_tree_id])}}" class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Edit</a>
                                                <button

                                                    data-id="{{$row->company_land_tree_id}}"
                                                    data-company-id="{{auth()->user()->company_id}}"
                                                    class="btn btn-sm btn-outline-success waves-effect waves-light mr-1 mb-1 manage_indi_tree">Manage</button>
                                                {{-- @endif --}}
                                            @endcan
                                            <a href="{{ route('land_tree_log_listing', ['tenant' => tenant('id'), 'land_id' => 0, 'id' => $row->company_land_tree_id])}}" class="btn btn-sm btn-outline-dark waves-effect waves-light mr-1 mb-1">Log</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Records!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page-content -->
<!-- Modal -->
<div class="modal fade" id="manage_tree" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('manage_tree', ['tenant' => tenant('id'), 'zone_id' => $company_land_zone_id]) }}">
                @csrf
                <div class="modal-body">
                    <h4 class="card-title mb-4">Manage Tree</h4>
                    <div class="col-12">
                        @php
                          $current_date = date('Y-m-d');
                        @endphp
                        <div class="form-group">
                            <label for="">Date<span class="text-danger">*</span></label>
                            <input hidden name="selected_land_tree_id_2" id="selected_tree_id">
                            <div class="input-group">
                                <input name="company_land_tree_log_date" class="form-control input-mask" id="datepicker" value="{{ $current_date }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                <span class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Action<span class="text-danger">*</span></label>
                            <select required name="company_land_tree_action_id" id="select_action" class="form-control">
                            </select>
                        </div>
                        <div class="form-group" id="description">
                            <label for="">Description<span class="text-danger">*</span></label>
                            <textarea required name="company_land_tree_log_description" id="summernote"
                                class="form-control"></textarea>
                            <span id="total-caracteres"></span>
                        </div>
                        <div class="form-group" id="insert_value_div">
                            <!-- <label for="">Value (ml)</label>
                            <input name="company_land_tree_log_value" type="number" step="any"
                                class="form-control"> -->
                        </div>
                        <div class="d-flex justify-content-end">
                            <span class="mr-2 warning" style="color: red;">Please select at least 1 tree.</span>
                            <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
                            <a class="btn btn-secondary" id="cancel" data-dismiss="modal">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
        integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        var check_all_tree_ids = document.getElementById("check_all_tree_ids");
        var selected_tree_id = document.getElementsByName("selected_land_tree_id[]");

        check_all_tree_ids.addEventListener("change", function(e) {
            for (i = 0; i < selected_tree_id.length; i++) {
                selected_tree_id[i].checked = check_all_tree_ids.checked;
            }
        });

        for (var i = 0; i < selected_tree_id.length; i++) {
            selected_tree_id[i].addEventListener('change', function(e) {
                if (this.checked == false) {
                    check_all_tree_ids.checked = false;
                }
                if (document.querySelectorAll('.selected_tree_id:checked').length == selected_tree_id.length) {
                    check_all_tree_ids.checked = true;
                }
            });
        }
        $('.manage_indi_tree').click(function() {
          $(this).parent().parent()[0].children[0].children[0].checked = true;
          $('.manage_tree').trigger('click');
        });

        $('.manage_tree').click(function() {
            let company_id = $(this).attr('data-company-id');
            let tree_id = $(this).attr('data-id');
            $('#insert_value_div').html('');
            var selected_tree_id = [];

            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
            if(checkboxes.length > 0){
              for (var i = 0; i < checkboxes.length; i++) {
                  selected_tree_id[i] = parseInt(checkboxes[i].value);
              }

              $.ajax({
                  url: "{{ route('ajax_get_tree_action', ['tenant' => tenant('id')]) }}",
                  method: 'post',
                  data: {
                      _token: '{{ csrf_token() }}',
                      company_id: company_id,
                  },
                  success: function(e){
                      let option = '';
                      option += "<option value='0'>Please Select Action</option>"
                      e.forEach((action) => {
                      let company = '';
                      if(action.company_id == 0){
                          company += ' - <span style="font-style: italic; font-size: 10px;">Default</span>'
                      }
                      option += "<option value="+action.company_land_tree_action_id + " id=is_value_required_"+ action.is_value_required+">" + action.company_land_tree_action_name + company + "</option>"
                      })
                      $('#select_action').html(option);
                      $('#tree_id').val(tree_id);
                      $('#selected_tree_id').val(selected_tree_id);
                  }
              })
              $('.warning').hide();
              $('#submit').attr('disabled', false);
            }else{
              $('.warning').show();
              $('#submit').attr('disabled', true);
            }
        })

        $('#select_action').on('change', function(){
          let is_value_required = $(this).children(":selected").attr("id").substr(18);
          let div = '';
          if(is_value_required == 1){
            // div += "<div class='form-group'>"
            div +=  "<label>Value (ml)<span class='text-danger'>*</span></label>"
            div +=  "<input required name='company_land_tree_log_value' type='number' step='any' class='form-control'>"
            // div += '</div>';
            $('#insert_value_div').html(div);
          }else{
            $('#insert_value_div').html(div);
          }
        })

        $('#cancel').on('click', function() {
          var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
          for (var i = 0; i < checkboxes.length; i++) {
              checkboxes[i].checked = false;
          }
        });

    </script>
@endsection
