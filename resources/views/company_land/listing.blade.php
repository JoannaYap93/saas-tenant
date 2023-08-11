@extends('layouts.master')

@section('title') Company Land Listing @endsection

@section('content')

@php
    // dd($records);
@endphp

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
                      <h4 class="mb-0 font-size-18 mr-2">Company Land Listing</h4>
					  {{-- @can('company_land_manage')
                      <a href="{{ route('land_area_add', ['tenant' => tenant('id')]) }}"
                          class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                              class="fas fa-plus"></i> ADD NEW</a>
					  @endcan --}}
                  </div>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Company Land</a>
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
                <div class="row mb-2">
					<div class="col-sm-8">
						<form method="POST" action="{{ route('company_land_listing', ['tenant' => tenant('id')]) }}">
							@csrf
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="validationCustom03">Freetext</label>
										<input type="text" class="form-control" name="freetext" placeholder="Search for..." value="{{ @$search['freetext'] }}">
									</div>
								</div>
								@if (auth()->user()->company_id == 0) 
									<div class="col-md-4">
										<div class="form-group">
											<label for="validationCustom03">Company</label>
											{!! Form::select('company_id', $company_name_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
										</div>
									</div>
								@endif
                                
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary  waves-effect waves-light mb-2 mr-2" name="submit" value="search">
											<i class="fas fa-search mr-1"></i> Search
										</button>
										<button type="submit" class="btn btn-danger  waves-effect waves-light mb-2 mr-2" name="submit" value="reset">
											<i class="fas fa-times mr-1"></i> Reset
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					{{-- @can('user_manage')
					<div class="col-sm-4">
						<div class="text-sm-right">
							<a href="{{ route('user_add', ['tenant' => tenant('id')]) }}">
								<button type="button" class="btn btn-success  waves-effect waves-light mb-2 mr-2">
									<i class="mdi mdi-plus mr-1"></i> Add New User
								</button>
							</a>
						</div>
					</div>
					@endcan --}}
				</div>
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th scope="col" style="width: 70px;">#</th>
								<th>Land Name</th>
								<!-- <th>Land Category Details</th> -->
								@can('company_land_manage')
								<th>Action</th>
								@endcan
							</tr>
						</thead>
						<tbody>
                            @if($records->isNotEmpty())
                                @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                @foreach ( $records as $company_land)
								{{-- @dd($company_land->company_land_zone) --}}
                                <tr>

                                    <td align="center">
                                        {{ $i }}
                                    </td>
                                    <td>
                                        <b>{{ $company_land->company_land_name }}</b><br/>
										<i>{{ $company_land->company->company_name }}</i>
                                    </td>
									@can('company_land_manage')
                                    <td><a href="{{ route('land_zone_listing', ['tenant' => tenant('id'), 'company_id' => $company_land->company_id, 'company_land_id' => $company_land->company_land_id] ) }}"
											class='btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1'>ZONE</a>
                                    </td>
									@endcan
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                            @else
                                <tr><td colspan="5">No Records!</td></tr>
							@endif

						</tbody>
					</table>
				</div>
				<ul class="pagination pagination-rounded justify-content-end mb-2">
					<li class="page-item">
						<a class="page-link" href="{{ $records->previousPageUrl() }}" aria-label="Previous">
							<i class="mdi mdi-chevron-left"></i>
						</a>
					</li>
					@for ($i = 1; $i <= $records->lastPage(); $i++)
						<li class="page-item @if($records->currentPage() == $i) active  @endif">
							<a class="page-link" href="{{ $records->url($i) }}">{{ $i }}</a>
						</li>
						@endfor
						<li class="page-item">
							<a class="page-link" href="{{ $records->nextPageUrl() }}" aria-label="Next">
								<i class="mdi mdi-chevron-right"></i>
							</a>
						</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- End Page-content -->

@endsection

@section('script')
{{-- <script>
	$(document).ready(function(e) {
		//$("#user_role").hide();
		$('.suspend').on('click', function() {
			var id = $(this).attr('data-id');
			console.log(id);
			$(".modal-body #user_id").val(id);
		});
		$('.activate').on('click', function() {
			var id = $(this).attr('data-id');
			console.log(id);
			$(".modal-body #user_id").val(id);
		});

		$('#user_type').on('change', function() {
		    if(this.value == 1){
				alert(this.value);
		        $("#user_role").show();
		    } else {
		        $("#user_role").hide();
		    }
		});
	});
</script> --}}
@endsection
