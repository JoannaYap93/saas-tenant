@extends('layouts.master')

@section('title')
    Monthly Worker Expense Item Listing
@endsection

@section('css')
    <style></style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0 font-size-18 mr-2">Monthly Worker Expense Item Listing</h4>
                  @can('payroll_item_manage')
                    <a href="{{ route('payroll_item_add') }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                  @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Monthly Worker Expense Item</a>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
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
                                            <label for="">Worker Role</label>
                                            {!! Form::select('worker_role_id', $worker_role_sel, @$search['worker_role_id'], ['class' => 'form-control', 'id' => 'worker_role_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Type</label>
                                            {!! Form::select('payroll_item_type', $payroll_item_type_sel, @$search['payroll_item_type'], ['class' => 'form-control', 'id' => 'payroll_item_type']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Available</label>
                                            {!! Form::select('payroll_item_status', $payroll_item_status_sel, @$search['payroll_item_status'], ['class' => 'form-control', 'id' => 'payroll_item_status']) !!}
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
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Worker Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                    @foreach ($records as $record)
                                        <?php
                                            $status = '';
                                            $payroll_item_type = "";
                                            switch ($record->payroll_item_status) {
                                                case 'Available':
                                                    $status = "<span class='badge badge-success'>{$record->payroll_item_status}</span>";
                                                    break;
                                                case 'Unavailable':
                                                    $status = "<span class='badge badge-danger'>{$record->payroll_item_status}</span>";
                                                    break;
                                            }
                                            switch (@$record->payroll_item_type) {
                                                case 'Add':
                                                    $payroll_item_type = "<span class='badge badge-success font-size-11'>{$record->payroll_item_type}</span>";
                                                    break;
                                                case 'Deduct':
                                                    $payroll_item_type = "<span class='badge badge-danger font-size-11'>{$record->payroll_item_type}</span>";
                                                    break;
                                            }
                                            $compulsory = '';
                                            switch ($record->is_compulsory) {
                                                case '1':
                                                    $compulsory = "<span class='badge badge-primary'>Compulsory</span>";
                                                    break;
                                            }
                                        ?>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                {{ @$record->payroll_item_name }}<br>
                                            </td>
                                            <td>
                                                {!! $payroll_item_type !!}
                                            </td>
                                            <td>
                                                @foreach ($record->payroll_item_worker_role as $worker_role)
                                                    {{ @$worker_role->worker_role_name }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @php echo $status; @endphp
                                                @if (@$record->is_compulsory == 1)
                                                    @php echo $compulsory; @endphp
                                                @endif
                                                <br>
                                                {{ date_format(@$record->payroll_item_created, 'Y-m-d H:i A') }}
                                            </td>
                                            <td>
                                                @can('payroll_item_manage')
                                                    <a href="{{ route('payroll_item_edit', @$record->payroll_item_id ) }}"
                                                        class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                            data-target="#delete" data-id="{{ @$record->payroll_item_id }}">Delete
                                                    </button>
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

<!-- Modal -->
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
         <form method="POST" action="{{ route('payroll_item_delete') }}">
             @csrf
             <div class="modal-body">
                 <h4>Delete this payroll item ?</h4>
                 <input type="hidden" name="payroll_item_id" id="payroll_item_id">
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
    <script>
        $(document).ready(function(e) {
            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                $(".modal-body #payroll_item_id").val(id);
            });
        });
    </script>
@endsection
