@extends('layouts.master')

@section('title')
    Company Listing
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                <span class="mr-2">Company Listing</span>
                {{-- @if (auth()->user()->user_type_id == 1) --}}
                    @can('company_manage')
                        <a href="{{ route('company_add', ['tenant' => tenant('id')]) }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                            <i class="fas fa-plus"></i> Add New</a>
                    @endcan
                {{-- @endif --}}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company</a>
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
        @if (auth()->user()->user_type_id == 1)
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <form method="POST" action="{{ route('company_listing', ['tenant' => tenant('id')]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_name_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Farm</label>
                                            {!! Form::select('farm', $farm, @$search['farm'], ['class' => 'form-control', 'id' => 'farm_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Category</label>
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value="">Please Select Farm</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Is Enable GST</label>
                                            @php
                                                $gst = ['' => 'Please Select GST', 'yes' => 'Yes', 'no' => 'No'];
                                            @endphp
                                            <select name="enable_gst" id="" class="form-control">
                                                @foreach ($gst as $key => $g)
                                                    <option value="{{ $key }}"
                                                        @if (@$search['enable_gst'] == $key) selected @endif>
                                                        {{ $g }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Is Enable Collect Code</label>
                                            @php
                                                $code = ['' => 'Please Select Collect Code', 'yes' => 'Yes', 'no' => 'No'];
                                            @endphp
                                            <select name="enable_collect" id="" class="form-control">
                                                @foreach ($code as $key => $g)
                                                    <option value="{{ $key }}"
                                                        @if (@$search['enable_collect'] == $key) selected @endif>
                                                        {{ $g }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Bank</label>
                                            {!! Form::select('setting_bank_id', $setting_bank_sel, @$search['setting_bank_id'], ['class' => 'form-control', 'id' => 'setting_bank_id']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-primary  waves-effect waves-light mb-2 mr-2"
                                                name="submit" value="search">
                                                <i class="fas fa-search mr-1"></i> Search
                                            </button>
                                            <button type="submit"
                                                class="btn btn-danger  waves-effect waves-light mb-2 mr-2" name="submit"
                                                value="reset">
                                                <i class="fas fa-times mr-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                {{--<th scope="col" style="width: 70px;">#</th>--}}
                                <th>Logo</th>
                                <th>Company</th>
                                <th>Company Land</th>
                                <th>Date</th>
                                {{-- @can('company_manage') --}}
                                    <th>Action</th>
                                {{-- @endcan --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($records->isNotEmpty())
                                @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                @foreach ($records as $company)
                                <?php
                								$status = '';
                								switch ($company->company_status) {
                									case 'active':
                										$status = "<span class='badge badge-primary font-size-11'>{$company->company_status}</span>";
                                    $actions = "<span data-toggle='modal' data-target='#suspend' data-id='$company->company_id' class='suspend'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light mr-2 mb-1'>Suspend</a></span>";
                										break;
                									case 'suspended':
                										$status = "<span class='badge badge-danger'>{$company->company_status}</span>";
                                    $actions = "<span data-toggle='modal' data-target='#activate' data-id='$company->company_id' class='activate'><a href='javascript:void(0);' class='btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1'>Activate</a></span>";
                										break;
                								}

                                            if ($company->company_claim_approval) {
                                                $test = array();
                                                $pic_log ='<table class=table>';
                                                foreach ($company->company_claim_approval as $pic_key => $pic) {
                                                    $test[$pic->claim_approval_step->claim_approval_step_name][] = $pic;
                                                }
                                                foreach ($test as $key => $value) {
                                                    $pic_log .='<thead>';
                                                    $pic_log .= '<tr><th style="border-top: 0px;">';
                                                    $pic_log .= "{$key}</th></tr>";
                                                    $pic_log .='</thead><tbody>';
                                                    $pic_log .= '<tr><td>';

                                                    foreach($value as $pic){
                                                        $pic_log .= "{$pic->user->user_fullname}</br>";
                                                    }
                                                    $pic_log .= '</td></tr>';
                                                    $pic_log .='</tbody>';
                                                }

                                            } else {
                                                $pic_log .= '<tr><td colspan=3>No Records!</td></tr>';
                                            }
                                            $pic_log .= '</table>';
                                            $pic_log = str_replace("'", '`', $pic_log);

                                            $pic_info = "<span style='cursor: pointer;' class='badge badge-warning font-size-11 pic_modal' data-toggle='modal' data-target='#pic_modal' data-pic='" . json_encode($pic_log) . "'>Claim PIC</span>";

                				?>
                                    <tr>
                                        {{--<td align="center">
                                            {{ $i }}
                                        </td>--}}
                                        <td align="center">
                                            @if($company->hasMedia('company_logo'))
                                            <a href="{{ $company->getFirstMediaUrl('company_logo') }}"
                                                target="_blank">
                                                <img src="{{ $company->getFirstMediaUrl('company_logo') }}"
                                                    width="90">
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            <b>{{ $company->company_name }} - <span style="font-style: italic;">{{ $company->company_code }}</span></b>
                                            <br />{{ $company->company_reg_no }}
                                            <br />{{ $company->company_phone }}
                                            @if($company->company_email)
                                            <br />{{ $company->company_email }}
                                            @endif
                                            <br />{{ $company->company_address }}
                                            <br> {!! $pic_info !!}
                                        </td>
                                        <td>
                                          <div class="table-responsive">
                                              <table class="table small-table table-striped mb-0">
                                                  <thead>
                                                      <tr>
                                                          <th style="padding-left: 10px !important">Land Details</th>
                                                          <th style="min-width: 150px;">Last Updated</th>
                                                          <th>Action</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                    @foreach ($company->company_land as $index => $company_land)
                                                      <tr>
                                                        <td  style="padding-left: 10px !important">
                                                          <a @can('company_manage') href="{{ route('product_company_land_add', ['tenant' => tenant('id'), 'id' => $company_land->company_land_id]) }}" @endcan>
                                                              <b>{{ @$company_land->company_land_category->company_farm->company_farm_name . '-' . $company_land->company_land_category->company_land_category_name }}</b></a><br>
                                                              {{ @$company_land->company_bank->setting_bank->setting_bank_name }}<br>
                                                              {{ @$company_land->company_bank->company_bank_acc_no }}
                                                        </td>
                                                        <td>
                                                          {{isset($last_updated['company_land'][$company_land->company_land_id]) ? date('Y-m-d H:i A', strtotime($last_updated['company_land'][$company_land->company_land_id])) : '-' }}<br>
                                                          @if ($company_land->company_land_total_acre > 0)
                                                          <b style="font-size:11px">{{@$company_land->company_land_total_acre}} Acre</b><br>
                                                          @endif
                                                          @if($company_land->is_overwrite_budget == 1 && $company_land->overwrite_budget_per_tree > 0)
                                                            @if(auth()->user()->user_type_id == 2)
                                                              Budget: {{$default_budget}}/<a @can('company_manage') href="{{route('budget_overwrite', ['tenant' => tenant('id'), 'company_id'=> $company->company_id, 'company_land_id' => $company_land->company_land_id])}}" @endcan>{{$company_land->overwrite_budget_per_tree}}</a>
                                                            @else
                                                              Budget: {{$default_budget}}/{{$company_land->overwrite_budget_per_tree}}
                                                            @endif
                                                          @else
                                                            @if(auth()->user()->user_type_id == 2)
                                                              Budget: <a @can('company_manage') href="{{route('budget_overwrite', ['tenant' => tenant('id'), 'company_id'=> $company->company_id, 'company_land_id' => $company_land->company_land_id])}}" @endcan>{{$default_budget}}</a>
                                                            @else
                                                              Budget: {{$default_budget}}
                                                            @endif
                                                          @endif
                                                        </td>
                                                        <td>
                                                          <a href="{{ route('land_zone_listing', ['tenant' => tenant('id'), 'company_id' => $company->company_id, 'company_land_id' => $company_land->company_land_id] ) }}"
                                                            class='btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1'>ZONE</a>
                                                            <a href="{{ route('land_tree_log_listing', ['tenant' => tenant('id'), 'land_id' => $company_land->company_land_id, 'id' => 0]) }}"
                                                              class='btn btn-sm btn-outline-dark waves-effect waves-light mr-2 mb-1'>TREE LOG</a>
                                                        </td>
                                                      </tr>
                                                    @endforeach
                                                  </tbody>
                                              </table>
                                          </div>
                                        </td>
                                        <td>
                                            <b>{{ date_format($company->company_created, 'Y-m-d H:i A') }}</b><br>
                                            {!! $status !!}
                                        </td>
                                            <td>
                                                <span data-toggle='modal' data-target='#fulfill'
                                                    data-id='{{ $company->company_id }}' class='fulfill'>
                                                    @can('company_manage')
                                                        @if (auth()->user()->user_type_id == 1)
                                                            <a href="{{ route('company_edit', ['tenant' => tenant('id'), 'company_id' => $company->company_id]) }}"
                                                                class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                        @else
                                                            <a href="{{ route('company_edit_land', ['tenant' => tenant('id'), 'company_id' => $company->company_id]) }}"
                                                                class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                        @endif
                                                        {{-- <a href="{{ route('company_edit_pic', ['tenant' => tenant('id'), 'id' => $company->company_id]) }}" class="btn btn-sm btn-outline-info waves-effect waves-light mr-2 mb-1">Edit PIC</a> --}}
                                                    @endcan
                                                </span>
                                                <button data-toggle="modal" data-target="#view_product_company_land"
                                                    data-id="{{ $company->company_id }}"
                                                    class="btn btn-outline-success btn-sm waves-effect waves-light mr-2 mb-1 view_product_company_land">View Product</button>
                                                    @can('company_manage')
                                                        @if(auth()->user()->user_type_id == 1)
                                                        {!! $actions !!}
                                                        @endif
                                                        <a href="{{ route('add_company_bank', ['tenant' => tenant('id'), 'id' => $company->company_id]) }}"
                                                            class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Add Bank</a>
                                                    @endcan
                                                    @can('claim_manage')
                                                        <a href="{{ route('claim_pic', ['tenant' => tenant('id'), 'id' => $company->company_id]) }}"
                                                            class="btn btn-sm btn-outline-warning waves-effect waves-light mr-2 mb-1">Claim PIC</a>
                                                    @endcan
                                                    @can('sync_company_expense_cost')
                                                    <button data-toggle="modal" data-target="#view_company_expense_cost"
                                                        data-id="{{ $company->company_id }}"
                                                        data-land-id="{{ $company->company_land }}"
                                                        class="btn btn-outline-success btn-sm waves-effect waves-light mr-2 mb-1 view_company_expense_cost">Update Cost</button>
                                                    @endcan
                                            </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Records!</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                {!! $records->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>
</div>
<!-- End Page-content -->

<!-- Modal -->

{{-- Update Cost modal --}}
<div class="modal fade" id="view_company_expense_cost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form method="POST"  action="{{ route('sync_cost', ['tenant' => tenant('id')]) }}">
            @csrf
            <div class="modal-header">
                <b>Company Land Zone - Tree Data Status</b>
            </div>
            <div class="modal-body">
              <input hidden name="company_id" id="sync_cost_company_id"/>
              <div class="mb-2">
                <span style="color: green">Available</span> - Tree data inserted. <br>
                <span style="color: red">Unavailable</span> - Tree data not inserted.
              </div>
              <div class="table-responsive">
                  <table class="table table-nowrap">
                      <thead class="thead-light">
                          <tr>
                              <th>Land Name</th>
                              <th>Tree Data Status</th>
                          </tr>
                      </thead>
                      <tbody id="update_cost_body">
                      </tbody>
                  </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="update_cost_submit">Update</button>
                <a class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
          </form>
        </div>
    </div>
</div>
{{-- End Update Cost modal --}}

{{-- PIC modal --}}
<div class="modal fade" id="pic_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <b>PIC Claim</b>
            </div>
            <div class="modal-body">
                <div id="pic-body"></div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>
{{-- End PIC modal --}}

<!-- View Product Company Land -->
<div class="modal fade" id="view_product_company_land" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Company Land - Products</h4>
            </div>
                <div id="view_body" class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Land Name</th>
                                            <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody id="details_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="suspend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('company_status', ['tenant' => tenant('id')]) }}">
				@csrf
				<div class="modal-body suspend">
          <h4>Company Suspension</h4>
					<h7>By suspending this company, all users associate with this company will not be able to access this admin site anymore.</h7>
					<input type="hidden" , name="company_id" id="company_id">
					<input type="hidden" , name="action" value="suspended">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger">Suspend</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="activate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content active">
			<form method="POST" action="{{ route('company_status', ['tenant' => tenant('id')]) }}">
				@csrf
				<div class="modal-body">
					<h4>Company Activation</h4>
          <h7>By activating this company, all users (previously suspended) will be allow to access this admin site.<h7>
					<input type="hidden" , name="company_id" id="company_id">
					<input type="hidden" , name="action" value="active">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Activate</button>
					<a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->
@endsection

@section('script')
    <script>
      $('.suspend').on('click', function() {
  			var id = $(this).attr('data-id');
  			console.log(id);
  			$(".modal-body #company_id").val(id);
  		});
  		$('.activate').on('click', function() {
  			var id = $(this).attr('data-id');
  			// console.log(id);
  			$(".modal-body #company_id").val(id);
  		});

        $('.view_company_expense_cost').click(function() {
          $('#update_cost_submit').attr('disabled', true);
          let company_land = JSON.parse($(this).attr('data-land-id'));
          let company_id = $(this).attr('data-id');
          let body = ''
          if(company_land.length > 0){
            let available_count = 0;
            company_land.forEach((c_land, i) => {
              body += '<tr>';
              body += '<td>'+ c_land.company_land_name+'</td>';
              body += '<td>';
              if(c_land.company_land_tree.length > 0){
                body += '<span style="color: green">Available</span>';
                available_count += 1;
              }else{
                body += '<span style="color: red">Unavailable</span>';
              }
              body += '</td>';
              body += '</tr>';

            });

            if(available_count == company_land.length){
              $('#update_cost_submit').attr('disabled', false);
            }
          }
          $('#update_cost_body').html(body);
          $('#sync_cost_company_id').val(company_id);
        });
        $('.pic_modal').click(function() {
            let pic_data = $(this).data('pic');
            $('.modal-body #pic-body').html(JSON.parse(pic_data));
        });
        $('.view_product_company_land').on('click', function(){
            $('#details_body').html('<tr><td class="text-center" colspan="2">Loading Details...</td></tr>');
            let company_id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('ajax_find_product_company_land_with_id', ['tenant' => tenant('id')]) }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_id: company_id,
                },
                success: function(e){
                    let details = '';

                    if (e.length > 0){
                        $.each(e, function(index, product_company_land) {
                            details += '<tr>';
                            details += '<td>' + product_company_land.company_land_name + '</td>';
                            $.each(product_company_land, function(key, products){
                                if(key == 'items'){
                                    details += '<td><table class="table table-nowrap" style="margin-bottom:0.25rem">';
                                    $.each(products, function(product_category_name, product_name){
                                        details += '<tr style="background-color: #f8f9fa;"><td style="padding:0.50rem;">' + product_category_name + '</td></tr>';
                                        $.each(product_name, function(pkey, pvalue){
                                            details += '<tr><td style="padding:0.50rem;"> - ' + pvalue + '</td></tr>';
                                        })

                                    })
                                    details += '</td></table>';
                                }
                            })
                            details += '</tr>';
                        });
                    }else{
                        details = '<tr><td class="text-center" colspan="2">No Records...</td></tr>';
                    }
                    $('#details_body').html(details);
                },
            })
        });

        $(document).ready(function () {
            @if (@$search['farm'])
                get_farm('{{$search["farm"]}}');
            @endif
        });

        $('#farm_id').on('change', function() {
            let farm = $(this).val();
            get_farm(farm);
        });

        function get_farm(farm) {
            $('#category_id').html('<option value="">Loading...</option>');
            let cat = "<option value=''>Please Select Category</option>";
            let cat_id = "{{ @$search['category_id'] ?? null }}";
            $.ajax({
                url: "{{route('ajax_get_farm', ['tenant' => tenant('id')])}}",
                method: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    id: farm
                },
                success: function(e) {
                    // console.log(e);
                    if (e.length > 0) {
                        e.forEach(el => {
                            if (cat_id != null && cat_id == el.company_land_category_id) {
                            cat += '<option selected value="'+el.company_land_category_id+'">'+el.company_land_category_name+'</option>';
                            } else {
                            cat += '<option value="'+el.company_land_category_id+'">'+el.company_land_category_name+'</option>';
                            }
                        });
                        $('#category_id').html(cat);
                    }
                }
            });
        }
    </script>
@endsection
