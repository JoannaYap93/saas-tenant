@extends('layouts.master')

@section('title') Sync Customer Listing @endsection

@section('css')
    <style>
        
    </style>
@endsection

@section('content') 

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Sync Customer Listing</h4>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sync Customer Listing</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<!-- end page title -->


{{--  --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form method="POST" action="{{ $submit }}">
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
                                        @if (auth()->user()->company_land_id == 0)
                                        <div class="form-group">
                                            <label for="validationCustom03">Company Name</label>
                                            {!! Form::select('company_id', $company_name_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                        </div>
                                        @endif
								    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--  --}}


	
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
								<th scope="col" style="width: 70px;">#</th>
                                <th>Customer Detail</th>
                                <th>Customer Address</th>
                                <th>Company Name</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
							<?php $i = 1; ?>
							@foreach($records as $key => $row)
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td><b>{{ $row->sync_customer_name }}</b><br><br>
                                    {{ $row->sync_customer_mobile }}<br>
                                    {{ $row->sync_customer_email }}
                                </td>
                                <td>{{ $row->sync_customer_address }}<br>
                                    {{ $row->sync_customer_address2 }}<br>
                                    {{ $row->sync_customer_state }}<br>
                                    {{ $row->sync_customer_city }}<br>
                                    {{ $row->sync_customer_postcode }}
                                </td>
                                <td><b>{{ $row->sync_customer_company_name }}</b></td>
                                
                            </tr>
							<?php $i++; ?>
							@endforeach
							@else
							<tr>
								<td colspan="4">No record found.</td>
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

@endsection

