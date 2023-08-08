@extends('layouts.master')

@section('title') {{$title}} Listing @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')


<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">{{$title}} Listing</h4>
                    @can('claim_manage')
                        <a href="{{ route('claim_add') }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Claim</a>
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
                                        <label for="">Claim Date</label>
                                        <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker6">
                                            <input type="text" style="width: 75px" class="form-control" name="claim_start_date" placeholder="Start Date" value="{{ @$search['claim_start_date'] }}" id="start" autocomplete="off">
                                            <input type="text" style="width: 75px" class="form-control" name="claim_end_date" placeholder="End Date" value="{{ @$search['claim_end_date'] }}" id="end" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        {!! Form::select('claim_status_id', $claim_status_sel, @$search['claim_status_id'], ['class' => 'form-control', 'id' => 'claim_status_id']) !!}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-left mb-3">
                                <div class="form-check-label" >
                                    <label class="d-flex flex-wrap">
                                        <input type="checkbox" id="is_account_check" name="is_account_check">
                                        &nbsp; Account Checked
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"  name="submit" value="search">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <button type="submit" class="btn btn-danger waves-effect waves-light mr-2" name="submit" value="reset">
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

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-nowrap">
						<thead class="thead-light">
							<tr>
                                <th scope="col" style="width: 70px; text-align:center">#</th>
                                <th>User</th>
                                <th>Claim Detail</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Date Last Updated</th>
                            @can('claim_manage')
                                <th>Action</th>
                            @endcan
							</tr>
						</thead>
						<tbody>
                            @php
                                $no = $records->firstItem();
                            @endphp
							@forelse ($records as $rows)
                                @php
                                    $status_name = $rows->claim_status->claim_status_name;
                                    switch ($rows->claim_status_id) {
                                        case '1'://Pending
                                            $color = 'warning';
                                            break;
                                        case '2'://Awaiting for Checking
                                            $color = 'primary';
                                            break;
                                        case '3'://Awaiting for Verify
                                            $color = 'soft-pink';
                                            break;
                                        case '4'://Awaiting Approval
                                            $color = 'pink';
                                            break;
                                        case '5'://Approved
                                            $color = 'soft-success';
                                            if ($rows->is_account_check == 1) {
                                                $status_name = "Awaiting for Payment";
                                            }
                                            break;
                                        case '6'://Completed
                                            $color = 'success';
                                            break;
                                        case '9'://Cancelled
                                            $color = 'soft-warning';
                                            break;
                                        case '8'://Rejected(Resubmit)
                                            $color = 'soft-danger';
                                            break;
                                        case '7'://Rejected(Permanent)
                                            $color = 'danger';
                                            break;
                                    }
                                @endphp

                                <tr>
                                    <td>#{{ $rows->claim_id }}</td>
                                    <td>
                                        <b>
                                            {{@$rows->worker->worker_name}}
                                        </b>
                                       <br>
                                       {{$rows->company->company_name}}

                                    </td>
                                    <td>
                                        <b>{{ date('F Y' , strtotime($rows->claim_start_date)) }}</b>
                                        <br>
                                        RM {{ number_format($rows->claim_amount,2)}}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{$color}} font-size-11">{{ $status_name }}</span><br/>
                                    </td>
                                    <td>
                                        {{ date_format($rows->claim_created, 'd M y H:i A') }}
                                    </td>
                                    <td>
                                        {{ date_format($rows->claim_updated, 'd M y H:i A') }}
                                    </td>
                                    @can('claim_manage')
                                    <td>
                                        <a href="{{ route('claim_item_listing', $rows->claim_id) }}" class="btn btn-outline-warning btn-sm mr-2">Claim Details</a>
                                        <a href="{{ route('claim_view', [$rows->claim_id,md5($rows->claim_id . env('ENCRYPTION_KEY'))]) }}" class="btn btn-outline-success btn-sm mr-2">View PDF</a>
                                        @if(count(@$rows->claim_item) > 0)
                                        <a href="{{ route('claim_export', $rows->claim_id) }}" class="btn btn-outline-primary btn-sm mr-2">Export</a>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                        data-target="#delete" data-id="{{ $rows->claim_id }}">Delete
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No record found.</td>
                                </tr>
                            @endforelse
						</tbody>
					</table>
                    {!! $records->links('pagination::bootstrap-4') !!}
				</div>
			</div>
		</div>
	</div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('claim_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this claim ?</h4>
                        <input type="hidden" name="claim_id" id="claim_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>

	$(document).ready(function(e) {
		$('.delete').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #claim_id").val(id);
		});
	});
</script>
@endsection
