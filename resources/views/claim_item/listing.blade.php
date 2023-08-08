@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
						<a href="javascript: void(0);">Claim</a>
					</li>
					<li class="breadcrumb-item active">Claim Item Listing</li>
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
<form method="POST" id="form_post" action="{{ $submit }}">
    @csrf
    @php
        $display_submission_status = false;
        $display_reject_resubmit_status = false;
        $display_reject_permanent_status = false;
        $redirect = false;
        $status_color = "";
        $message = "";

        switch ($claim_status_id) {
            case 1:
                break;
            case 2:
                $display_submission_status = true;
                break;
            case 3:
                $display_submission_status = true;
                break;
            case 4:
                $display_submission_status = true;
                break;
            case 5:
                $redirect = true;
                break;
            case 6:

                break;
            case 7:
                $display_reject_permanent_status = true;
                break;
            case 8:
                $display_reject_resubmit_status = true;
                break;
        }
    //cancel_submission
    @endphp

    @if ($claim->worker_id == Auth::id() || $claim->admin_id == Auth::id())
        @if ($display_submission_status)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">You've Submitted your Claim. Currently Status <br/><strong>{{$claim->claim_status->claim_status_name}}</strong>.</p>
                        {{-- <a href="{{route('claim_cancel_submission',$claim->claim_id)}}" class="btn btn-primary">Edit My Submission</a> --}}
                        <span data-toggle='modal' data-target='#cancel_submission' data-id='{{$claim->claim_id}}' class='modal_claim_action'><a href='javascript:void(0);' class='btn btn-primary'>Edit My Submission</a></span>
                    </div>
                </div>
            </div>
        @endif

        @if ($display_reject_resubmit_status)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><strong>Your Claim has been Rejected. Please resubmit for approval again.<strong></p>
                    </div>
                </div>
            </div>
        @endif

        @if ($display_reject_permanent_status)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><strong>Your Claim is Permanent Rejected, Please create a new claim to start again.<strong></p>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="row">
        <div class="col-md-6 mt-4">
            <div class="card h-100">
                <h5 class="card-header bg-transparent border-bottom text-uppercase">Claim Detail #{{@$claim->claim_number}}</h5>
                <div class="card-body">
                    <table class="small_table">
                        <tr>
                            <td style="width: 130px;">Farm Manager</td>
                            <td>:</td>
                            <td>{{@$claim->worker->worker_name}}</td>
                        </tr>
                        <tr>
                            <td>Company</td>
                            <td>:</td>
                            <td>{{$claim->company->company_name}}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td>{{date_format (new DateTime($claim->claim_start_date), 'd-m-Y')}}</td>
                        </tr>
                        <tr>
                            <td>Total Claim</td>
                            <td>:</td>
                            <td>RM {{$claim->claim_amount}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>{{$claim->claim_status->claim_status_name}}</td>
                        </tr>

                        @if ($claim->claim_status_id >= 5)
                            <tr>
                                <td>Account Checked</td>
                                <td>:</td>
                                <td>{!! $claim->is_account_check == 1 ? "<i class='fa fa-check-circle' style=' color: green; '></i>" : "<i class='fa fa-times-circle' style=' color: red; '></i>" !!}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>Remark</td>
                            <td>: </td>
                            <td>{{$claim->claim_remark != '' ? $claim->claim_remark : '-'}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="card h-100">
                <h5 class="card-header bg-transparent border-bottom text-uppercase">Claim Log</h5>
                <div class="card-body">
                    <div data-simplebar="init" class="mt-2" style="max-height: 280px;"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: -15px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
                        <ul class="verti-timeline list-unstyled">
                            @foreach ($claim->claim_log as $key => $claim_log)
                                <li class="event-list pb-2">
                                    <div class="event-timeline-dot">
                                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                                    </div>
                                    {{-- <div class="d-flex"> --}}
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14 mb-0">{{ $claim_log->claim_log_created->format('d M y H:i A') }}</h5>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>
                                                [{{$claim_log->claim_log_action}}] - {{$claim_log->claim_log_description}}
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                </li>

                                @if ($key == 2)
                                    @break
                                @endif
                            @endforeach
                        </ul>
                    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 472px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 166px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>
                    @if (count($claim->claim_log) > 3)
                        <div class="text-center mt-4"><a href="{{ route('claim_log', $claim->claim_id) }}" class="btn btn-primary waves-effect waves-light btn-sm popup">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
             <div class="card h-100">
                @php
                    $claim_step = (isset($claim[$step])) ? $claim[$step] : array();
                @endphp
                <h5 class="card-header bg-transparent border-bottom text-uppercase">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-auto center">
                            Claim Listing
                        </div>
                        <div class="col-12 col-md-auto" style="text-align: right">
                            @if (in_array(Auth::id(),$claim_step))
                                @if ($title)
                                    @if($is_remark)
                                        <span data-toggle='modal' data-target='#update_status' data-id='{{$claim->claim_id}}' class='modal_claim_action'><a href='javascript:void(0);' class='btn btn-primary waves-effect waves-light mr-1'>{{$title}}</a></span>
                                    @else
                                        <button type="submit" name="submit" value="submit" class="btn btn-primary waves-effect waves-light  ml-2 text-uppercase">{{$title}}</button>
                                    @endif
                                @endif
                                @if ($claim->claim_status_id == 1 || $claim->claim_status_id == 8)
                                    @if ($claim->claim_status->is_edit_claim)
                                        <a href="{{route('claim_item_add',$claim->claim_id)}}" class="btn btn-outline-primary waves-effect waves-light ml-2 text-uppercase">Add New Item</a>
                                        @if (count($company_expense_item) > 0 || count($raw_material_company_usage) > 0)
                                            <button type="submit" name="submit" value="update" class="btn btn-outline-primary waves-effect waves-light ml-2 text-uppercase">Update Claim</button>
                                        @endif
                                    @else
                                        <span data-toggle='modal' data-target='#cancel_submission' data-id='{{$claim->claim_id}}' class='modal_claim_action'><a href='javascript:void(0);' class='btn btn-danger waves-effect waves-light mr-1'>Cancel Submission</a></span>
                                        {{-- <a href="{{route('claim_cancel_submission',$claim->claim_id)}}" class="btn btn-danger waves-effect waves-light">Cancel Submission</a> --}}
                                    @endif
                                @else
                                    @if ($claim->claim_status->is_editable)
                                        <span data-toggle='modal' data-target='#reject_resubmit' data-id='{{$claim->claim_id}}' class='modal_claim_action'><a class="btn btn-warning waves-effect waves-light mr-1" href='javascript:void(0);'>Reject (Resubmit)</a></span>
                                    @endif
                                @endif
                                {{-- <button type="button" class="btn btn-outline-success waves-effect waves-light">Submit My Claim</button> --}}
                            @endif
                        </div>
                    </div>
                </h5>
                <div class="card-body">
                    <table class="table table-striped- table-hover table-checkable" id="kt_table_1">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th width="10%">Date</th>
                                <th width="20%">Name</th>
                                <th width="10%">Image</th>
                                <th  width="25%">Detail</th>
                                <th  width="15%">Claimable</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($company_expense_item) > 0)
                                <tr>
                                    <td colspan='6'><b>Expense</b></td>
                                        <td scope='col'>
                                        <div class="form-check-label" >
                                            <label class="d-flex flex-wrap">
                                                <input type="checkbox" class="checkAll_expense_item_ids" id='checkAll_expense_item_ids' name='checkAll_expense_item_ids' value=''/>
                                                &nbsp; Check All
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($company_expense_item as $key => $item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->company_expense_item_created}}</td>
                                        <td>
                                            {{json_decode($item->expense->setting_expense_name)->en}}<br>
                                            <a href="{{route('company_expense_edit',$item->company_expense_id)}}" target="_blank" class="pr-2">
                                                {{$item->company_expense->company_expense_number}}
                                            </a>
                                            {{-- @if($item->hasMedia('company_expense_item_media'))
                                                @php
                                                    $item_medias = $item->getMedia('company_expense_item_media');
                                                @endphp
                                                @foreach($item_medias as $media)
                                                    <a target="_blank" href="{{$media->getUrl()}}" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 1px;" src="{{ $media->getUrl()}}" width="20" height="20"></a>
                                                @endforeach
                                            @endif --}}
                                        </td>
                                        <td style="text-align: left">
                                            <a href="#" class="btn btn-outline-primary btn-sm mb-2 view_images ml-2 center" data-id="{{ $item->company_expense_item_id }}">
                                                <span data-toggle='modal' data-target='#view_images_modal'>View Images</span>
                                            </a>
                                        </td>
                                        <td>
                                            Unit: {{$item->company_expense_item_unit}}<br>
                                            Price: {{$item->company_expense_item_unit_price}}<br>
                                            Total: {{$item->company_expense_item_total}}
                                        </td>
                                        <td>RM {{$item->claim_remaining_amount}}</td>
                                        <td><input type="checkbox" class="expense_item_ids" id="expense_item_ids[]" name="expense_item_ids[]" value="{{$item->company_expense_item_id}}"/></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan='7'></td>
                                </tr>
                            @endif

                            @if (count($raw_material_company_usage) > 0)
                            <tr>
                                <td colspan='6'><b>Stock In</b></td>

                                <td scope='col'>
                                    <div class="form-check-label" >
                                        <label class="d-flex flex-wrap">
                                            <input type="checkbox" class="checkAll_stock_in" id='checkAll_stock_in' name='checkAll_stock_in' value=''>
                                            &nbsp; Check All
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($raw_material_company_usage as $key =>  $rows)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$rows->raw_material_company_usage_created}}</td>
                                <td>{{json_decode($rows->raw_material->raw_material_name)->en}}</td>
                                <td style="text-align: left">
                                </td>
                                <td>
                                    Unit: {{$rows->raw_material_company_usage_qty}}<br>
                                    Price: {{$rows->raw_material_company_usage_value_per_qty}}<br>
                                    Total: {{$rows->raw_material_company_usage_total_price}}
                                </td>
                                <td>RM: {{$rows->claim_remaining_amount}}</td>
                                <td><input type="checkbox" class="stock_in" id="stock_in[]" name="stock_in[]" value="{{$rows->raw_material_company_usage_id}}"/></td>                            
                            </tr>
                            @endforeach
                            @endif
                            <th colspan='5'></th>
                            <th><strong>Total Claim Item:</strong></th>
                            <th id="total_sub_claim"><strong></strong></th>
                            <tr>
                                <td colspan='7'><b>Claimed</b></td>
                            </tr>

                            @forelse ($claim->claim_item as $key => $claim_item)
                                @php
                                    $reject = "";
                                    if ($claim->is_editable && (in_array(Auth::id(), $claim_step))) {
                                        $reject = "<a title='Reject'  href='" . route('claim_item_reject',[$claim_item->claim_id, $claim_item->claim_item_id]) . "' style='color:red;'>Reject</a>";
                                    }
                                    $caption = $claim_item->claim_item_name . " at " . $claim_item->claim_item_type_name . " <br/> " . $claim_item->claim_item_date . " <br/> RM " . $claim_item->claim_item_amount . "<br/>" . $reject;
                                @endphp
                                <tr>
                                    <td>{{ $key+1}}</td>
                                    <td>  {{$claim_item->claim_item_date}}</td>
                                    <td>
                                        <strong>
                                            @if ($claim_item->claim_item_type == "company_expense_item_id" || $claim_item->claim_item_type == "raw_material_company_usage_id")
                                                {{json_decode($claim_item->claim_item_name)->en}}
                                            @else
                                                {{$claim_item->claim_item_name}}
                                            @endif
                                        </strong><br/>
                                        {{$claim_item->claim_category_name}}<br/>

                                    </td>
                                    <td style="text-align: left">
                                        <a href="#" class="btn btn-outline-primary btn-sm mb-2 view_images_claim ml-2 center" data-id="{{ $claim_item->claim_item_id }}">
                                            <span data-toggle='modal' data-target='#view_images_modal_claim'>View Images</span>
                                        </a>
                                    </td>
                                    <td>
                                        Amount : RM {{ $claim_item->claim_item_amount}}
                                    </td>
                                    <td>RM {{$claim_item->claim_item_amount_claim}}</td>
                                    <td>
                                        <span style="overflow: visible; position: relative;">
                                            @if(@$claim->claim_status->company_claim_approval)
                                                @if ($claim->claim_status->is_edit_claim == 1)
                                                    <span data-toggle='modal' data-target='#reject' data-id='{{$claim_item->claim_item_id}}' class='modal_claim_item_action'><a class="text-warning" href='javascript:void(0);'>Reject</a></span><br/>
                                                @endif
                                            @endif
                                            @if ($claim->claim_status_id == 1 && $claim->admin_id == Auth::id())
                                                <a title="Delete" class="text-danger" href="{{route('claim_item_delete',$claim_item->claim_item_id)}}">Delete</a><br/>
                                            @endif
                                            {{$reject}}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan='7'>No record found!</td>
                                </tr>
                            @endforelse
                            <th colspan='5'></th>
                            <th><strong>Total</strong></th>
                            <th><strong>RM{{$claim->claim_amount}}</strong></th>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4 ">
            <div class="card">
                <h5 class="card-header bg-transparent border-bottom text-uppercase">Rejected Listing</h5>
                <div class="card-body">
                    <table class="table table-striped- table-hover table-checkable" id="kt_table_1">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Date</th>
                                <th width="20%">Name</th>
                                <th  width="10%">Image</th>
                                <th  width="25%">Detail</th>
                                <th  width="15%">Claimable</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($claim->claim_item_rejected as $key =>  $claim_item_rejected)
                                @php
                                     $caption = $claim_item_rejected->claim_item_name . " at " . $claim_item_rejected->claim_item_description . " <br/> " . $claim_item_rejected->claim_item_date . " <br/> RM " . $claim_item_rejected->claim_item_amount;
                                @endphp

                                <tr>
                                    <td>{{ $key+1}}</td>
                                    <td> {{ $claim_item_rejected->claim_item_date}}</td>
                                    <td>
                                        <strong>{{ json_decode($claim_item_rejected->claim_item_name)->en}}</strong><br/>
                                        {{ $claim_item_rejected->claim_category_name}}<br/>
                                    </td>
                                    <td style="text-align: left">
                                        <a href="#" class="btn btn-outline-primary btn-sm mb-2 view_images_claim ml-2 center" data-id="{{ $claim_item_rejected->claim_item_id }}">
                                            <span data-toggle='modal' data-target='#view_images_modal_claim'>View Images</span>
                                        </a>
                                    </td>
                                    <td>
                                        Amount : RM {{ $claim_item_rejected->claim_item_amount}}
                                        @if ($claim_item_rejected->company_expense_url)
                                            <a href="{{$claim_item_rejected->company_expense_url}}" target="_blank"><i class="fas fa-external-link-alt ml-1"></i></a>
                                        @endif
                                        <br/>
                                        {{-- @if ($claim_item_rejected->hasMedia('claim_item_media'))
                                            @php
                                                $claim_item_medias = $claim_item_rejected->getMedia('claim_item_media');
                                            @endphp
                                            @foreach ($claim_item_medias as $claim_item_media)
                                                <a href='{{$claim_item_media->getUrl()}}' class="fancybox " data-fancybox="mygroup" data-caption="{{$caption}}"><img src="{{$claim_item_media->getUrl()}}" width="20" />
                                            @endforeach
                                        @endif --}}
                                    </td>
                                    <td>RM {{ $claim_item_rejected->claim_item_amount_claim}}</td>
                                    <td>
                                        {{ $claim_item_rejected->claim_item_rejected_date}}
                                        <br/><span style="color:red;">{{ $claim_item_rejected->claim_item_rejected_remark}}</span>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan='7'>No record found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer pt-4 px-0">
                    <a href="{{route('claim_listing')}}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-1"></i>Back To Claim Listing</a>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="update_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" , name="claim_id" id="claim_id">
                    <div>
                        <label>Remark:<span class="text-danger">*</span></label>
                        <textarea name="remark" id="summernote"
                                class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Resubmit Modal --}}
<div class="modal fade" id="reject_resubmit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('claim_resubmit_reject') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" , name="claim_id" id="claim_id">
                    <div>
                        <label>Remark:<span class="text-danger">*</span></label>
                        <textarea name="remark" id="summernote"
                                class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Reject</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- View Details Image Claim -->
 <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="modal-header">
                    <b>Expense Images Details</b>
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
<!-- End Modal -->

 <!-- View Details Image Claim Rejected -->
 <div class="modal fade" id="view_images_modal_claim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="modal-header">
                    <b>Rejected Item Images Details</b>
                </div>
                <div class="modal-body view_images_body_claim">
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->

{{-- Reject Resubmit Modal --}}
<div class="modal fade" id="cancel_submission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('claim_cancel_submission') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" , name="claim_id" id="claim_id">
                    <div>
                        <label>Remark:<span class="text-danger">*</span></label>
                        <textarea name="remark" id="summernote"
                                class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Item Modal --}}
<div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('claim_item_reject')}}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" , name="claim_item_id" id="claim_item_id">
                    <div>
                        <label>Remark:<span class="text-danger">*</span></label>
                        <textarea name="remark" id="summernote"
                                class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Reject</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end row -->

@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/jquery-repeater/jquery-repeater.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-repeater.int.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script>

        let expense_sub_total = 0;
        let raw_sub_total = 0;
        let total_sub_claim = 0;
        
        $(".popup").fancybox({
            'type': 'iframe',
            'width': 1000,
            'height': 600,
            'autoDimensions': false,
            'autoScale': false,
            iframe : {
                css : {
                    width : '1000px',
                    height: '600px'
                }
            }
        });


        $(".fancybox").fancybox();

        $('.modal_claim_item_action').on('click', function() {
            var id = $(this).attr('data-id');
            $(".modal-body #claim_item_id").val(id);
        });
        $('.modal_claim_action').on('click', function() {
            var id = $(this).attr('data-id');
            $(".modal-body #claim_id").val(id);
        });

        
        $('.view_images').on('click',function(){
            let company_expense_item_id = $(this).attr('data-id');
            let details = '';
            let row = '';

            details +='<div class="row">';
            details += '<div class="col-md-12 col-sm-12 style=" border-style: groove;">';
            $('.view_images_body').html('');
            $.ajax({
                url: "{{ route('ajax_get_image_by_company_expense_item_id') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    expense_item_id : company_expense_item_id
                },
                dataType: "json",
                success: function(e) {
                    console.log(e)

                    details += '<table class="table small-table">';
                    e.items.forEach(element => {
                        details += '<tr>';
                        if(element.media_url != null){
                            details += '<td><div class="row">';
                            element.media_url.forEach(media => {
                            details +='<div class="p-1">'
                                    +'<div class="img-wrap">'
                                    +'<a href="'+media+'" target="_blank" rel="noopener noreferrer"><img src="'+media+'" alt="" srcset="" width="50" height="50"></a>'
                                    +'</div>'
                                    +'</div>'
                            });
                            details+='</div></td>';
                        } else{
                            details += '<td></td>';
                        }
                        details+='</tr>';
                    });

                    details += '</table>';
                    details +='</div>';
                    details += '</div>';

                    $('.view_images_body').html(details);
                    $('#view_images_modal').modal('show');
                },
                error: function(e) {
                    // console.log(e);
                    alert(e);
                }
            });
        })

           
        $('.view_images_claim').on('click',function(){
            let claim_item_id = $(this).attr('data-id');
            let details = '';
            let row = '';

            details +='<div class="row">';
            details += '<div class="col-md-12 col-sm-12 style=" border-style: groove;">';
            $('.view_images_body_claim').html('');
            $.ajax({
                url: "{{ route('ajax_get_image_by_claim_item_id') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    claim_item_id : claim_item_id
                },
                dataType: "json",
                success: function(e) {
                    console.log(e)

                    details += '<table class="table small-table">';
                    e.items.forEach(element => {
                        details += '<tr>';
                        if(element.media_url != null){
                            details += '<td><div class="row">';
                            element.media_url.forEach(media => {
                            details +='<div class="p-1">'
                                    +'<div class="img-wrap">'
                                    +'<a href="'+media+'" target="_blank" rel="noopener noreferrer"><img src="'+media+'" alt="" srcset="" width="50" height="50"></a>'
                                    +'</div>'
                                    +'</div>'
                            });
                            details+='</div></td>';
                        } else{
                            details += '<td></td>';
                        }
                        details+='</tr>';
                    });

                    details += '</table>';
                    details +='</div>';
                    details += '</div>';

                    $('.view_images_body_claim').html(details);
                    $('#view_images_modal_claim').modal('show');
                },
                error: function(e) {
                    // console.log(e);
                    alert(e);
                }
            });
        })

        

        $(document).ready(function() {
            $('#total_sub_claim').html('<b>RM ' + 0.00 + '</b>');
            @if(count($company_expense_item) >0)
                var checkAll_expense_item_ids = document.getElementById("checkAll_expense_item_ids"); //select all checkbox
                var expense_item_ids = document.getElementsByName("expense_item_ids[]"); //checkbox items

                checkAll_expense_item_ids.addEventListener("change", function(e) {
                    if (this.checked == true) {
                        expense_sub_total = 0;
                        total_sub_claim = raw_sub_total;
                        for (i = 0; i < expense_item_ids.length; i++) {
                            let expense_item_id = $(expense_item_ids[i]).val();
                            add_get_price_expense_item(expense_item_id);
                            expense_item_ids[i].checked = checkAll_expense_item_ids.checked;
                        }
                    }else{
                        expense_sub_total = 0;
                        total_sub_claim = raw_sub_total;
                        $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                        for (i = 0; i < expense_item_ids.length; i++) {
                            expense_item_ids[i].checked = checkAll_expense_item_ids.checked;
                        }
                    }
                });

                for (var i = 0; i < expense_item_ids.length; i++) {
                    expense_item_ids[i].addEventListener('change', function(e) { //".checkbox" change

                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if (this.checked == true) {
                            let expense_item_id = $(this).val();
                            console.info($(this).val())
                            add_get_price_expense_item(expense_item_id);
                            // add_price_expense_item();
                        }

                        if (this.checked == false) {
                            // let expense_item_id2 = $(this).val();
                            // console.info(expense_item_id2);
                            checkAll_expense_item_ids.checked = false;
                            minus_get_price_expense_item($(this).val());
                            // minus_price_expense_item();
                        }
                        //check "select all" if all checkbox items are checked
                        if (document.querySelectorAll('.expense_item_ids:checked').length == expense_item_ids.length) {
                            checkAll_expense_item_ids.checked = true;
                        }                        
                    });
                }
            @endif

            @if(count($raw_material_company_usage) > 0)
                var checkAll_stock_in = document.getElementById("checkAll_stock_in"); //select all checkbox
                var stock_in = document.getElementsByName("stock_in[]"); //checkbox items

                checkAll_stock_in.addEventListener("change", function(e) {
                    if (this.checked == true){
                        raw_sub_total = 0;
                        total_sub_claim = expense_sub_total;
                        for (i = 0; i < stock_in.length; i++) {
                            let raw_material_company_usage_id = $(stock_in[i]).val();
                            add_get_price_raw_material_item(raw_material_company_usage_id);
                            stock_in[i].checked = checkAll_stock_in.checked;
                        }
                    }else{
                        raw_sub_total = 0;
                        total_sub_claim = expense_sub_total;
                        
                        $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                        for (i = 0; i < stock_in.length; i++) {
                            stock_in[i].checked = checkAll_stock_in.checked;
                        }
                    }
                });

                for (var i = 0; i < stock_in.length; i++) {
                    stock_in[i].addEventListener('change', function(e) { //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if (this.checked == true) {
                            let raw_material_company_usage_id = $(this).val();
                            console.info($(this).val())
                            add_get_price_raw_material_item(raw_material_company_usage_id);
                            // add_price_expense_item();
                        }

                        if (this.checked == false) {
                            minus_get_price_raw_material_item($(this).val());
                            checkAll_stock_in.checked = false;
                        }

                        //check "select all" if all checkbox items are checked
                        if (document.querySelectorAll('.stock_in:checked').length == stock_in.length) {
                            checkAll_stock_in.checked = true;
                        }
                    });
                }
            @endif
            });

        function add_get_price_expense_item(expense_item_id) {
            $.ajax({
                url: "{{ route('ajax_get_price_expense_item') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    expense_item_id: expense_item_id
                },
                success: function(e) {
                    console.log(e.claim_remaining_amount);
                    expense_sub_total += parseFloat(e.claim_remaining_amount);
                    total_sub_claim += parseFloat(e.claim_remaining_amount);
                    console.log(expense_sub_total);
                    console.log( total_sub_claim);
                    $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                }
            });
        }

        function minus_get_price_expense_item(expense_item_id2) {
            $.ajax({
                url: "{{ route('ajax_get_price_expense_item') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    expense_item_id: expense_item_id2
                },
                success: function(e) {
                    console.log(e.claim_remaining_amount);
                    expense_sub_total -= parseFloat(e.claim_remaining_amount);
                    total_sub_claim -= parseFloat(e.claim_remaining_amount);
                    console.log(expense_sub_total);
                    console.log( total_sub_claim);
                    $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                }
           });
        }

        function add_get_price_raw_material_item(raw_material_company_usage_id) {
            $.ajax({
                url: "{{ route('ajax_get_price_raw_material_item') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    raw_material_company_usage_id: raw_material_company_usage_id
                },
                success: function(e) {
                    console.log(e.claim_remaining_amount);
                    raw_sub_total += parseFloat(e.claim_remaining_amount);
                    total_sub_claim += parseFloat(e.claim_remaining_amount);
                    console.log(raw_sub_total);
                    console.log( total_sub_claim);
                    $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                }
            });
        }

        function minus_get_price_raw_material_item(raw_material_company_usage_id) {
            $.ajax({
                url: "{{ route('ajax_get_price_raw_material_item') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    raw_material_company_usage_id: raw_material_company_usage_id
                },
                success: function(e) {
                    console.log(e.claim_remaining_amount);
                    raw_sub_total -= parseFloat(e.claim_remaining_amount);
                    total_sub_claim -= parseFloat(e.claim_remaining_amount);
                    console.log(raw_sub_total);
                    console.log( total_sub_claim);
                    $('#total_sub_claim').html('<b>RM ' + new Intl.NumberFormat('ja-JP', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total_sub_claim.toFixed(2)) + '</b>');
                }
            });
        }

    </script>
@endsection
