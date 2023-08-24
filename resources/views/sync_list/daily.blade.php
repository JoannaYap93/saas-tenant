@extends('layouts.master')

@section('title') Sync Daily In & Out Listing @endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <style>
        
    </style>
@endsection

@section('content') 

<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-1">Sync Daily In & Out Listing</h4>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Sync Daily In & Out Listing</a>
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
                                        <div class="form-group">
                                            <label for="validationCustom03">Product Name</label>
                                            {!! Form::select('product_id', $product_name_sel, @$search['product_id'], ['class' => 'form-control', 'id' => 'product_id']) !!}
                                        </div>
								    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Product Grade</label>
                                            {!! Form::select('setting_product_size_id', $product_size_sel, @$search['setting_product_size_id'], ['class' => 'form-control', 'id' => 'setting_product_size_id']) !!}
                                        </div>
								    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="cc_from"
                                                    placeholder="Start Date" value="{{ @$search['cc_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="cc_to"
                                                    placeholder="End Date" value="{{ @$search['cc_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->company_land_id == 0)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Company Land Name</label>
                                            {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control', 'id' => 'cl.company_land_id']) !!}
                                        </div>
                                    </div>
                                   @endif

                                   {{-- @dd($search); --}}
                                   
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
                                <th>User Name</th>
                                <th>Product</th>
                                <th>Collect Code</th>
                                <th>Sync Product Date Detail</th>
                                <th>Company Land</th>
							</tr>
						</thead>
						<tbody>
							@if($records->isNotEmpty())
							<?php $i = 1; ?>
							@foreach($records as $key => $row)
                            
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td>
                                @php
                                    if (@$row->user) {
                                        $User = $row->user;
                                        $user_fullname = $User->user_fullname . '<br>';
                                    } else {
                                        $user_fullname = '';
                                        $user_fullname = '<b>"No User Name"</b>';
                                    }
                                @endphp
                                <b>{!! $user_fullname !!}</b>
                                </td>

                                <td>
                                @php
                                    if (@$row->product) {
                                        $SettingSize = $row->product;
                                        $product_name = $SettingSize->product_name;
                                    } else {
                                        $product_name = '<b>"No Product Grade"</b>';
                                    }
                                @endphp

                                @php
                                    if (@$row->setting_product_size) {
                                        $Product = $row->setting_product_size;
                                        $setting_product_size_name = $Product->setting_product_size_name;
                                    } else {
                                        $setting_product_size_name = '<b>"No Product Grade"</b>';
                                    }
                                @endphp
                                {{ $product_name }} - <b>{{ $setting_product_size_name }}</b> - <b>{{round( $row->sync_collect_quantity ,4) }} KG</b>
                                </td>

                                <td>{{ $row->sync_collect_code }}</td>

                                <td>{{ date_format($row->sync_collect_created, 'Y-m-d h:i A') }}<br>
                                    <b>Collect Date: <br>{{ $row->sync_collect_date }}</b>
                                </td>

                               
                                <td>    
                                @php
                                    if (@$row->company_land) {
                                        $companyLand = $row->company_land;
                                        $company_land_name = $companyLand->company_land_name . '<br>';
                                    } else {
                                        $company_land_name = '';
                                        $company_land_name = '<b>"No Company Land"</b>';
                                    }
                                @endphp
                                {!! $company_land_name !!}
                                </td>
                            <tr>
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

@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endsection