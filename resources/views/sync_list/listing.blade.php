@extends('layouts.master')

@section('title') Sync Listing @endsection

@section('css')
    <style>
        .btn {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
<!-- start page title -->

<div class="page-title-box d-flex align-items-center justify-content-between">
    <h4 class="mb-0 font-size-18">
        <span class="mr-2">Sync Listing</span>
        @can('sync_zip_file')
            <a href="{{ route('sync_zip_file', ['tenant' => tenant('id')]) }}"
                class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                <i class="fas fa-plus"></i> Add New</a>
        @endcan
    </h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <a href="javascript: void(0);">Sync</a>
            </li>
            <li class="breadcrumb-item active">Listing</li>
        </ol>
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
                                        <label for="">Land</label>
                                        {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">User</label>
                                        {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Date</label>
                                        <div class="input-daterange input-group" id="datepicker6"
                                            data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                            data-provide="datepicker" data-date-container="#datepicker6">
                                            <input type="text" style="width: 75px" class="form-control" name="sync_from"
                                                placeholder="Start Date" value="{{ @$search['sync_from'] }}" id="start"
                                                autocomplete="off">
                                            <input type="text" style="width: 75px" class="form-control" name="sync_to"
                                                placeholder="End Date" value="{{ @$search['sync_to'] }}" id="end"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->user_type_id == 1)
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Company</label>
                                            {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary  waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <button type="submit" class="btn btn-danger  waves-effect waves-light mr-2"
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 100px;">Sync ID</th>
                            <th style="width: 150px">Sync User</th>
                            <th style="width: 200px">Sync Details</th>
                            <th style="width: 150px">Sync Date</th>
                            @can('sync_zip_file')
                                <th style="width: 50px">Action</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                            @if($records->isNotEmpty())
                                @foreach($records as $row)
                                {{-- @dd($row) --}}
                                    <tr>
                                        <td>
                                            #{{ $row->sync_id }} <br>
                                            <a href="{{@$row->zip_files_media_url}}" target="_blank" class="fas fa-file"></i>
                                        </td>
                                        <td> {{ @$row->user->user_fullname }}<br>
                                            @if(isset($row->company))
                                                {{ $row->company->company_name }}
                                            @endif
                                            <br><br>
                                            <b>Sync D.O. Land:</b><br>
                                            @if($row->sync_delivery_order->isNotEmpty())
                                                @foreach($row->sync_delivery_order as $sync_delivery_order)
                                                    @php
                                                        $company_land_name = '';
                                                        if(!str_contains($company_land_name, $sync_delivery_order->company_land->company_land_name)){
                                                            $company_land_name .= $sync_delivery_order->company_land->company_land_name . '<br>';
                                                        }
                                                    @endphp
                                                @endforeach
                                                @php echo $company_land_name; @endphp
                                            @else
                                                - <br>
                                            @endif
                                            <br>
                                            <b>Sync Collect Land:</b><br>
                                            @if($row->sync_collect->isNotEmpty())
                                                @foreach($row->sync_collect as $sync_collect)
                                                    @php
                                                        $company_land_name = '';
                                                        if(!str_contains($company_land_name, $sync_collect->company_land->company_land_name)){
                                                            $company_land_name .= $sync_collect->company_land->company_land_name . '<br>';
                                                        }
                                                    @endphp
                                                @endforeach
                                                @php echo $company_land_name; @endphp
                                            @else
                                                - <br>
                                            @endif
                                            <br>
                                            <b>Sync Formula Usage Land:</b><br>
                                            @if($row->sync_formula_usage->isNotEmpty())
                                                @foreach($row->sync_formula_usage as $sync_formula_usage)
                                                    @php
                                                        $company_land_name = '';
                                                        if(!str_contains($company_land_name, $sync_formula_usage->company_land->company_land_name)){
                                                            $company_land_name .= $sync_formula_usage->company_land->company_land_name . '<br>';
                                                        }
                                                    @endphp
                                                @endforeach
                                                @php echo $company_land_name; @endphp
                                            @else
                                                - <br>
                                            @endif
                                            <br>
                                            <b>Sync Company Expense Land:</b><br>
                                            @if($row->sync_company_expense->isNotEmpty())
                                                @foreach($row->sync_company_expense as $sync_company_expense)
                                                    @php
                                                      foreach($sync_company_expense->company_expense_land as $company_expense_land){
                                                        echo $company_expense_land->company_land->company_land_name . '<br>';
                                                      }
                                                    @endphp
                                                @endforeach
                                            @else
                                                - <br>
                                            @endif
                                        </td>
                                        <td>
                                            <table id="sync_count">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width: 130px;">Type</th>
                                                        <th style="width: 130px;">Sync</th>
                                                        <th style="width: 130px;">Synced</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="transform: rotate(0);">
                                                        <td><a href="{{ route('get_sync_do', ['tenant' => tenant('id'), 'id' => $row->sync_id]) }}" class="stretched-link">Number of DO</a></td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['do_count'] }}
                                                        </td>
                                                        <td class="link-primary" style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['sync_do_count'] }}
                                                        </td>
                                                    </tr>
                                                    <tr style="transform: rotate(0);">
                                                        <td><a href="{{ route('get_sync_daily', ['tenant' => tenant('id'), 'id' => $row->sync_id]) }}" class="stretched-link">Number of Collect</a></td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['collect_count'] }}
                                                        </td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['sync_collect_count'] }}
                                                        </td>
                                                    </tr>
                                                    <tr style="transform: rotate(0);">
                                                        <td><a href="{{ route('get_sync_formula_usage', ['tenant' => tenant('id'), 'id' => $row->sync_id]) }}" class="stretched-link">Number of Formula Usage</a></td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['formula_usage_count'] }}
                                                        </td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['sync_formula_usage_count'] }}
                                                        </td>
                                                    </tr>
                                                    <tr style="transform: rotate(0);">
                                                        <td><a href="{{ route('get_sync_company_expense', ['tenant' => tenant('id'), 'id' => $row->sync_id]) }}" class="stretched-link">Number of Company Expense</a></td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['company_expense_count'] }}
                                                        </td>
                                                        <td style="color: #556ee6">
                                                            {{ $sync_details[$row->sync_id]['sync_company_expense_count'] }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date DO</td>
                                                        <?php
                                                            if(isset($sync_details[$row->sync_id]['sync_do_date'][$row->sync_id]['do_created'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_do_date'][$row->sync_id]['do_created'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }

                                                            if(isset($sync_details[$row->sync_id]['sync_do_date'][$row->sync_id]['do_date'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_do_date'][$row->sync_id]['do_date'] . "</td>";
                                                            } else{
                                                                echo "<td>-</td>";
                                                            }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Collect</td>
                                                        <?php
                                                            if(isset($sync_details[$row->sync_id]['sync_collect_date'][$row->sync_id]['collect_created'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_collect_date'][$row->sync_id]['collect_created'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }

                                                            if(isset($sync_details[$row->sync_id]['sync_collect_date'][$row->sync_id]['collect_date'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_collect_date'][$row->sync_id]['collect_date'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Formula Usage</td>
                                                        <?php
                                                            if(isset($sync_details[$row->sync_id]['sync_formula_usage_date'][$row->sync_id]['formula_usage_created'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_formula_usage_date'][$row->sync_id]['formula_usage_created'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }

                                                            if(isset($sync_details[$row->sync_id]['sync_formula_usage_date'][$row->sync_id]['formula_usage_created'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_formula_usage_date'][$row->sync_id]['formula_usage_created'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Date Company Expense</td>
                                                        <?php
                                                            if(isset($sync_details[$row->sync_id]['sync_company_expense_date'][$row->sync_id]['company_expense_created'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_company_expense_date'][$row->sync_id]['company_expense_created'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }

                                                            if(isset($sync_details[$row->sync_id]['sync_company_expense_date'][$row->sync_id]['company_expense_date'])){
                                                                echo "<td>" . $sync_details[$row->sync_id]['sync_company_expense_date'][$row->sync_id]['company_expense_date'] . "</td>";
                                                            }else{
                                                                echo "<td>-</td>";
                                                            }
                                                        ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            Created:
                                            <small>{!! date_format($row->sync_created, 'Y-m-d h:i A') !!}</small><br>
                                        </td>
                                        @can('sync_zip_file')
                                            @if($sync_details[$row->sync_id]['is_revertable'])
                                                <td>
                                                    <button data-toggle="modal" data-target="#revert_sync"
                                                        data-id="{{ $row->sync_id }}"
                                                        class="btn btn-outline-danger btn-sm revert_sync">
                                                        Revert
                                                    </button>
                                                </td>
                                            @elseif(@$row->is_reverted)
                                                <td>
                                                    <button data-toggle="modal" data-target="#resync"
                                                        data-id="{{ $row->sync_id }}"
                                                        class="btn btn-outline-warning btn-sm resync">
                                                        Resync
                                                    </button>
                                                </td>
                                            @endif
                                        @endcan
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="4">No records found</td>
                            @endif
                        </tbody>
                    </table>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
{{----- revert sync modal -----}}
<div class="modal fade" id="revert_sync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('revert_sync', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Revert this Sync ?</h4>
                    <input type="hidden" , name="sync_id" id="sync_id">
                    {{-- <div>
                        <label for="invoice_log_description">Remark: </label>
                        <textarea name="invoice_log_description" id="summernote"
                                class="form-control" required></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Revert</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="resync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('resync', ['tenant' => tenant('id')]) }}">
                @csrf
                <div class="modal-body">
                    <h4>Resync?</h4>
                    <input type="hidden" , name="sync_id" id="sync_id">
                    {{-- <div>
                        <label for="invoice_log_description">Remark: </label>
                        <textarea name="invoice_log_description" id="summernote"
                                class="form-control" required></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Resync</button>
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.revert_sync').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #sync_id").val(id);
		});

        $('.resync').on('click', function() {
			var id = $(this).attr('data-id');
			$(".modal-body #sync_id").val(id);
		});
    </script>
@endsection
