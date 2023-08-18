@extends('layouts.master')

@section('title')
    Supplier Delivery Order Listing
@endsection

@section('css')
    <style>
        .log {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0 font-size-18 mr-2">Supplier Delivery Order Listing</h4>
                    @can('supplier_delivery_order_manage')
                        <a href="{{ route('supplier_do_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> MULTI STOCK IN</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Supplier Delivery Order Listing</a>
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
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="freetext">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    @if(auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="company_id">Company</label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="raw_material_category_id">Raw Material Category</label>
                                            {!! Form::select('raw_material_category_id', $raw_material_category_sel, @$search['raw_material_category_id'], ['class' => 'form-control', 'id' => 'raw_material_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="raw_material_id">Raw Material</label>
                                            <select name="raw_material_id" class="form-control" id="raw_material_id">
                                                <option value="">Please Select Raw Material</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <th>Supplier DO Details</th>
                                <th>Raw Materials</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if(@$records->isNotEmpty())
                                    @foreach (@$records as $supplier_do)
                                        @php
                                            $status = "";
                                            $supplier_do_log = "<table class=table><thead><tr><th>Admin</th><th>Description</th><th>Date</th></tr></thead><tbody>";
                                            if(@$supplier_do->supplier_delivery_order_log){
                                                foreach(@$supplier_do->supplier_delivery_order_log as $sdo_log){
                                                    $supplier_do_log .= "<tr>";
                                                    $supplier_do_log .= "<td>{$sdo_log->user->user_fullname}</td>";
                                                    $supplier_do_log .= "<td>{$sdo_log->supplier_delivery_order_log_description}</td>";
                                                    $supplier_do_log .= "<td>{$sdo_log->supplier_delivery_order_log_created}</td>";
                                                    $supplier_do_log .= "</tr>";
                                                }
                                            }else {
                                                $supplier_do_log .= "<tr><td colspan=3>No Records!</td></tr>";
                                            }
                                            $supplier_do_log .= '</tbody></table>';
                                            $supplier_do_log = str_replace("'", '`', $supplier_do_log);

                                            switch(@$supplier_do->supplier_delivery_order_status){
                                                case "completed":
                                                    $status = "<span class='badge badge-success log' data-toggle='modal' data-target='#log' data-log='" . json_encode($supplier_do_log) . "'>" . ucfirst($supplier_do->supplier_delivery_order_status) . "</span>";
                                                break;
                                                case "partially returned":
                                                    $status = "<span class='badge badge-info log' data-toggle='modal' data-target='#log' data-log='" . json_encode($supplier_do_log) . "'>" . ucfirst($supplier_do->supplier_delivery_order_status) . "</span>";
                                                break;
                                                case "returned":
                                                    $status = "<span class='badge badge-warning log' data-toggle='modal' data-target='#log' data-log='" . json_encode($supplier_do_log) . "'>" . ucfirst($supplier_do->supplier_delivery_order_status) . "</span>";
                                                break;
                                                case "deleted":
                                                    $status = "<span class='badge badge-danger log' data-toggle='modal' data-target='#log' data-log='" . json_encode($supplier_do_log) . "'>" . ucfirst($supplier_do->supplier_delivery_order_status) . "</span>";
                                                break;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <b>{{ @$supplier_do->supplier_delivery_order_running_no }}</b><br>
                                                <b>{{ @$supplier_do->supplier_delivery_order_no }}</b><br>
                                                {{ @$supplier_do->supplier->supplier_name }}<br>
                                                @if(auth()->user()->company_id == 0)
                                                    <small>{{ @$supplier_do->company->company_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if (@$supplier_do->supplier_delivery_order_item)
                                                    @foreach (@$supplier_do->supplier_delivery_order_item as $supplier_do_item)
                                                        <b>{{ json_decode($supplier_do_item->raw_material->raw_material_name)->en }}</b> -
                                                        {{ ($supplier_do_item->supplier_delivery_order_item_value_per_qty * $supplier_do_item->supplier_delivery_order_item_qty) }}
                                                        {{ $supplier_do_item->raw_material->raw_material_value_unit }}<br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {!! $status !!}<br>
                                                <small>
                                                    Stock In Date:<b>{{ @$supplier_do->supplier_delivery_order_date }}</b><br>
                                                    Created:<b>{{ date_format(@$supplier_do->supplier_delivery_order_created, 'Y-m-d h:i A') }}</b>
                                                </small>
                                            </td>
                                            <td>
                                                @if (@$supplier_do->hasMedia('supplier_delivery_order_media'))
                                                    <a href="{{ @$supplier_do->getFirstMediaUrl('supplier_delivery_order_media') }}" class="btn btn-sm btn-outline-primary" target="_blank">View D.O</a>
                                                @endif
                                                @if (@$supplier_do->supplier_delivery_order_item)
                                                    <a href="{{ route('get_raw_material_company_usage', ['tenant' => tenant('id'), 'id' => $supplier_do->supplier_delivery_order_id]) }}"
                                                        class="btn btn-sm btn-outline-success">View Stock History</a>
                                                @endif
                                                @can('supplier_delivery_order_manage')
                                                    @if(@$supplier_do->supplier_delivery_order_status == "completed")
                                                        <a href="{{ route('supplier_do_edit', ['tenant' => tenant('id'), 'id' => $supplier_do->supplier_delivery_order_id]) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    @endif
                                                    @if(@$supplier_do->supplier_delivery_order_status != "returned" && $supplier_do->supplier_delivery_order_status != "deleted")
                                                        <a href= "{{ route('supplier_do_return', ['tenant' => tenant('id'), 'id' => $supplier_do->supplier_delivery_order_id]) }}" class='btn btn-sm btn-outline-secondary'>Return</a>
                                                    @endif
                                                @endcan
                                                @can('supplier_delivery_order_delete')
                                                    @if($supplier_do->supplier_delivery_order_status != "deleted")
                                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                            data-target="#delete" data-id="{{ @$supplier_do->supplier_delivery_order_id }}">Delete
                                                        </button>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No Records!</td>
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

    {{-- Modal --}}
    {{-- Order Log --}}
    <div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Logs</b>
                </div>
                <div class="modal-body">
                    <div id="log-description"></div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
    {{-- End Order Log --}}
    {{-- Delete --}}
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('supplier_do_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Supplier Delivery Order?</h4>
                        <input type="hidden" name="supplier_delivery_order_id" id="supplier_delivery_order_id">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Delete --}}
    {{-- End Modal --}}
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            let company_id = $('#company_id').val();

            if('{{ auth()->user()->company_id }}' == '0')
                company_id = $('#company_id').val();
            else
                company_id = '{{ auth()->user()->company_id }}';

            get_raw_material_by_raw_material_category($('#raw_material_id').val(), company_id);
        });

        $('.delete').on('click', function() {
            var id = $(this).attr('data-id');
            $(".modal-body #supplier_delivery_order_id").val(id);
        });

        $('.log').click(function() {
            let log_data = $(this).data('log');
            console.log(log_data);
            $('.modal-body #log-description').html(JSON.parse(log_data));
        });

        $('#raw_material_category_id').on('change', function() {
            $('#raw_material_id').html('<option value="">Loading...</option>');
            let raw_material_category_id = $(this).val();
            let company_id = $('#company_id').val();

            if('{{ auth()->user()->company_id }}' == '0')
                company_id = $('#company_id').val();
            else
                company_id = '{{ auth()->user()->company_id }}'

            get_raw_material_by_raw_material_category(raw_material_category_id, company_id);
        });

        function get_raw_material_by_raw_material_category(raw_material_category_id, company_id) {
            let raw_material_id = '{{ @$search['raw_material_id'] }}' ?? null;
            let raw_material_sel = '<option value="">Please Select Raw Material</option>';

            $.ajax({
                url: "{{ route('ajax_get_raw_material_by_raw_material_category_id', ['tenant' => tenant('id')]) }}",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                    raw_material_category_id: raw_material_category_id
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (raw_material_category_id != null && raw_material_id == element.id) {
                                raw_material_sel += '<option selected value="' + element.id + '">' +
                                    element.value + '</option>';
                            } else {
                                raw_material_sel += '<option value="' + element.id + '">' +
                                    element.value + '</option>';
                            }
                        });
                        $('#raw_material_id').html(raw_material_sel);
                    } else {
                        $('#raw_material_id').html('<option value="">No Raw Material</option>');
                    }
                }
            });
        }
    </script>
@endsection
