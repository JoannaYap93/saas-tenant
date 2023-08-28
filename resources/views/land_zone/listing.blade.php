@extends('layouts.master')

@section('title')
    Company Land Zone Listing
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                <span class="mr-2">Company Land Zone Listing</span>
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Zone</a>
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
        @php
            $missing_data = 0;
        @endphp
        {{-- @if (auth()->user()->user_type_id == 1) --}}
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <form method="POST" action="{{ route('land_zone_listing', ['tenant' => tenant('id'),'company_id'=>$company_id,'company_land_id'=> $company_land_id] ) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    {{-- @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_name_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif --}}
                                    {{-- <div class="col-md-3">
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
                                    </div> --}}
                                </div>
                                <div class="row">
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
                                        <a  href="{{ route('company_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary waves-effect waves-light mr-2"
                                            name="submit">
                                            <i class="fas fa-arrow-left mr-1"></i> Company Listing
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        {{-- @endif --}}
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <p><strong>{{$company_land_detail->company->company_name}}<br/>
                        <i>{{$company_land_detail->company_land_name}}</i><br/><small>{{$company_land_detail->company_land_total_acre}} acres</small></strong></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th>Zone Name</th>
                                <th>Zone Detail</th>
                                <th>Tree Detail</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($records->isNotEmpty())
                                @foreach ( $records as $zone )
                                    <tr>
                                        <td>
                                            <b>{{$loop->iteration}}</b>
                                        </td>

                                        <td>
                                            <b>{{@$zone->company_land_zone_name}}</b><br>
                                           {{-- <i> {{@$zone->company->company_name}} </i><br>
                                            {{@$zone->company_land->company_land_name}} --}}
                                        </td>
                                        {{--<td>
                                            @foreach($zone->tree_detail as $tree_product)
                                                 <b><a href="#" class="item_btn" data-toggle='modal' data-target='#product_tree_zone' id="item_btn_{{@$zone->company_land_zone_id}}" data-zone-id="{{@$zone->company_land_zone_id}}" data-land-id="{{@$company_land_detail->company_land_id}}">{{$tree_product->product->product_name}}</a></b> - <i>{{$tree_product->total_tree}} tree(s)</i><br/>
                                            <b>{{$tree_product->product->product_name}}</b> - <i>{{$tree_product->total_tree}} tree(s)</i><br/>
                                            @endforeach
                                        </td>--}}
                                        <td>
                                          <div class="table-responsive">
                    													<table class="table small-table table-striped mb-0">
                    															<thead>
                    																	<tr>
                    																			<th style="padding-left: 10px !important">#</th>
                    																			@foreach(@$company_pnl_sub_item as $key => $cpsi)
                    																			<th>{{$cpsi->company_pnl_sub_item_code}}</th>
                    																			@endforeach
                    																	</tr>
                    															</thead>
                    															<tbody>
                                                    @foreach($zone->tree_detail as $tree_product)
                                                      <tr>
                                                        @if(!empty($tree_product->product))
                                                        <td><b>{{$tree_product->product->product_name}}</b> ({{$tree_product->total_tree}})</td>
                                                        @else
                                                        <td style="color: #f40909"><b>No Product</b> ({{$tree_product->total_tree}})</td>
                                                        @php
                                                            $missing_data++;
                                                        @endphp
                                                        @endif
                                                        @foreach(@$company_pnl_sub_item as $key => $cpsi)
                                                          <td>
                                                          {{$zone->company_land_tree->where('company_pnl_sub_item_code', $cpsi->company_pnl_sub_item_code)->where('product_id', $tree_product->product_id)->count() > 0
                                                            ? $zone->company_land_tree->where('company_pnl_sub_item_code', $cpsi->company_pnl_sub_item_code)->where('product_id', $tree_product->product_id)->count()
                                                            : '-'}}
                                                          </td>
                                                        @endforeach
                                                      </tr>
                                                    @endforeach
                    															</tbody>
                    													</table>
                    											</div>
                                        </td>
                                        <td>
                                            <b>Total: </b>{{count(@$zone->company_land_tree)}}/{{$zone->company_land_zone_total_tree}}<br>
                                            <b>Sick: </b>{{count(@$zone->company_land_tree->where('is_sick', 1))}}<br>
                                            <b>Bear Fruit: </b>{{count(@$zone->company_land_tree->where('is_bear_fruit', 1))}}
                                        </td>

                                        <td>
                                            <!-- <b>{{ date_format(@$zone->company_land_zone_created, 'Y-m-d H:i A') }}</b> -->

                                            <b>{{isset($last_updated['company_land_zone'][$zone->company_land_zone_id]) ? date('Y-m-d H:i A', strtotime($last_updated['company_land_zone'][$zone->company_land_zone_id])) : '-'}}</b>
                                        </td>

                                            <td>
                                              @if(auth()->user()->user_type_id == 2)
                                                @can('company_land_zone_manage')
                                                    <a href="{{ route ('zone_edit', $zone->company_land_zone_id)}}"
                                                        class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Edit</a>
                                                @endcan
                                              @endif
                                                <!-- <a href="#"
                                                    class="btn btn-sm btn-outline-warning waves-effect waves-light mr-2 mb-1">Export Tree</a> -->
                                                <a href="{{route('land_tree_listing', ['tenant' => tenant('id'), 'company_land_zone_id' => $zone->company_land_zone_id])}}"
                                                    class="btn btn-sm btn-outline-success waves-effect waves-light mr-1 mb-1">Manage Tree(s)</a>
                                              @if(auth()->user()->user_type_id == 2)
                                                @can('company_land_zone_manage')
                                                @if($missing_data < 1)
                                                    <button class="btn btn-sm btn-outline-info mr-1 mb-1 import"
                                                        data-toggle="modal" data-zone-id="{{@$zone->company_land_zone_id}}"
                                                        data-company-land-id={{@$company_land_id}}
                                                        data-zone-name="{{@$zone->company_land_zone_name}}"
                                                        data-tree-count="{{count(@$zone->company_land_tree)}}"
                                                        data-target="#import">Import</button>
                                                @else
                                                <a href="{{route('fix_product_data_listing', ['tenant' => tenant('id'), 'id' => $zone->company_land_zone_id])}}"
                                                    class="btn btn-sm btn-outline-danger waves-effect waves-light mr-1 mb-1">Fix Data</a>
                                                @endif
                                                <button class="btn btn-sm btn-outline-info mr-1 mb-1 import1"
                                                data-toggle="modal" data-zone-id="{{@$zone->company_land_zone_id}}"
                                                data-company-land-id={{@$company_land_id}}
                                                data-zone-name="{{@$zone->company_land_zone_name}}"
                                                data-tree-count="{{count(@$zone->company_land_tree)}}"
                                                data-target="#import1">Export</button>
                                                @php
                                                    $missing_data = 0;
                                                @endphp
                                                @endcan
                                              @endif
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
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="zone_name">Import tree data for </h4>
                <br>
            </div>
            <form action="{{ route('restructure_zone_tree', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-6" id="pick_date">
                        <div class="form-group">
                            <div class="input-daterange input-group" id="datepicker6"
                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                data-provide="datepicker" data-date-container="#datepicker6">

                                <input type="text" style="width: 75px" class="form-control" name="import_date"
                                    placeholder="Select Date" id="import_date"
                                    autocomplete="off" value="{{ date('Y-m-d')}}" required>
                            </div>
                        </div>
                     </div>
                  <h6 id="import_desc_2">Please insert excel file to import.</h6>
                  <input type="file" required name="import_excel" class="form-control-file import_excel">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary import_submit">Submit</button>
                    <input id="submit_zone_id" type="number" name="company_land_zone_id" hidden>
                    <input id="submit_land_id" type="number" name="company_land_id" hidden>
                    <a id="close_import_modal" class="btn btn-secondary" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Import Modal --}}
<div class="modal fade" id="product_tree_zone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
						<form method="POST" action="javascript: void(0)">
								@csrf
								<div class="modal-body">
										<h4 class="card-title mb-4">Total Tree (Code)</h4>
										<div class="col-12">
											<div class="table-responsive">
											  <table class="table table-bordered" >
											    <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    @foreach(@$company_pnl_sub_item as $key => $cpsi)
                                    <th>{{$cpsi->company_pnl_sub_item_code}}</th>
                                    @endforeach
                                </tr>
											    </thead>
											    <tbody id="item_table">
											    </tbody>
											  </table>
											</div>
											<div>
												<span id="total_rounding_user"></span>
											</div>
												<div class="d-flex justify-content-end">
														<!-- <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button> -->
														<a class="btn btn-secondary" data-dismiss="modal">Close</a>
												</div>
										</div>
								</div>
						</form>
				</div>
		</div>
</div>
<!-- End Page-content -->
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ global_asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.import_submit').hide();
        $('#import_desc_2').hide();
        $('.import_excel').hide();
        $('#pick_date').hide();

        $('.item_btn').on('click', function () {
          let land_id = $(this).attr('data-land-id');
          let zone_id = $(this).attr('data-zone-id');
          let company_pnl_sub_item = <?php echo json_encode($company_pnl_sub_item); ?>;
          $('#item_table').html('Loading...');
          // console.log(company_pnl_sub_item);
          $.ajax({
							url: "{{ route('ajax_tree_total_code_zone', ['tenant' => tenant('id')]) }}",
							method: "POST",
							data: {
									_token: "{{ csrf_token() }}",
									company_land_zone_id: zone_id,
                  company_land_id: land_id,
							},
							success: function(e) {
                // console.log(e);
                let item_table = '';
                Object.keys(e.data).forEach((keys) => {
                  // console.log(e.data[keys]);
                  item_table += '<tr>';
                  item_table += '<td>'+ e.data[keys].product.product_name +'</td>';

                  company_pnl_sub_item.forEach((sub_item, i) => {
                    let item_table_2 = '-';
                    // console.log(sub_item);
                    Object.keys(e.data[keys].pnl_codes).forEach((key2) => {
                      // console.log(e.data[keys].pnl_codes);
                      if(e.data[keys].pnl_codes[key2].company_pnl_sub_item_code == sub_item.company_pnl_sub_item_code){
                        item_table_2 = e.data[keys].pnl_codes[key2].tree_count;
                      }
                    });
                    item_table += '<td>'+ item_table_2 +'</td>'
                  });
                  item_table += '</tr>';
                })

                $('#item_table').html(item_table);
							}
					});
        });

        $('input:file').change(
          function(e) {
              var files = e.originalEvent.target.files;
              // console.log(files);
              for (var i=0, len=files.length; i<len; i++){
                  var n = files[i].name,
                      s = files[i].size,
                      t = files[i].type;
                  console.log(t);
                  if(t != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                    window.alert('Wrong file type! Please ensure to insert excel file.')
                    $(this).val('');
                  }
              }
          });

          $('.import1').on('click', function() {
          let zone_id = $(this).attr('data-zone-id')
          let land_id = $(this).attr('data-company-land-id')
        //   $('#submit_zone_id').val(zone_id);
        //   $('#submit_land_id').val(land_id);
          window.location.assign('{{URL::to('ajax_download_zone_tree_data')}}/'+ zone_id +'/'+ land_id)
        });

        $('.import').on('click', function() {
          let zone_id = $(this).attr('data-zone-id')
          let land_id = $(this).attr('data-company-land-id')
          $('#submit_zone_id').val(zone_id);
          $('#submit_land_id').val(land_id);
          let zone_name = $(this).attr('data-zone-name')
          let tree_count = $(this).attr('data-tree-count')
          $('#import_desc').hide();
          $('#import_desc_3').hide();
          $('.import_submit').show();
          $('#import_desc_2').show();
          $('.import_excel').show();
          $('#pick_date').show();
        });

        $('#close_import_modal').on('click', function() {
        //   $('#import_desc').hide();
        //   $('#import_desc_3').hide();
        //   $('.import_submit').hide();
          $('#import_desc_2').hide();
          $('.import_excel').hide();
          $('#pick_date').hide();
        //   $('#import_continue').show();
        })
    </script>
@endsection
