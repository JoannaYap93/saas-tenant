@extends('layouts.master')

@section('title')
    Setting Reward Listing
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
                  <h4 class="mb-0 font-size-18 mr-2">Setting Reward Listing</h4>
                    <a href="{{ route('setting_reward_add', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Reward</a>
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
                                            <label for="">Reward Category</label>
                                            {!! Form::select('setting_reward_category_id', $setting_reward_category_sel, @$search['setting_reward_category_id'], ['class' => 'form-control', 'id' => 'setting_reward_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Reward Status</label>
                                            {!! Form::select('setting_reward_status', $setting_reward_status_sel, @$search['setting_reward_status'], ['class' => 'form-control', 'id' => 'setting_reward_status']) !!}
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
                                <th>Reward Details</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @php
                                    $no = $records->firstItem();
                                @endphp
                                @forelse ($records as $rows)
                                    @php
                                        $status = '';
                                        switch ($rows->setting_reward_status) {
                                            case 'active':
                                                $status = "<span class='badge badge-primary'>{$rows->setting_reward_status}</span>";
                                                break;
                                            case 'pending':
                                                $status = "<span class='badge badge-danger'>{$rows->setting_reward_status}</span>";
                                                break;
                                        }
                                        $json_info = json_decode(@$rows->setting_reward_json,true) ?? [];
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <b>{{ @$rows->setting_reward_name }}</b>-{{ @$rows->setting_reward_category->setting_reward_category_name }}<br>
                                            @if (@$rows->setting_reward_description)
                                                <br>Description:<br>
                                                {{ @$rows->setting_reward_description }}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach (@$json_info as $json)
                                                Tier: <b>{{ @$json['tier'] }}</b> ,
                                                Amount: <b>{{ @$json['amount'] }}</b> ,
                                                Full Attendance: <b>{{ @$json['full_attendance'] }}</b> ,
                                                Day: <b>{{ @$json['day'] }}</b><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {!! $status !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('setting_reward_edit', ['tenant' => tenant('id'), 'id' => @$rows->setting_reward_id]) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal" data-target="#delete" data-id="{{ @$rows->setting_reward_id }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No record found.</td>
                                    </tr>
                                @endforelse
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
         <form method="POST" action="{{ route('setting_reward_delete', ['tenant' => tenant('id')]) }}">
             @csrf
             <div class="modal-body">
                 <h4>Delete this reward?</h4>
                 <input type="hidden" name="setting_reward_id" id="setting_reward_id">
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
                $(".modal-body #setting_reward_id").val(id);
            });
        });
    </script>
@endsection

