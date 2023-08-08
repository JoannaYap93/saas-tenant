@extends('layouts.master')

@section('title') {{$page_title}} @endsection


@section('css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">{{$page_title}}</h4>
                    @can('setting_message_template')
                    <a href="{{ route('message_template_add') }}"
                        class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                            class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Message Template</a>
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
                                            <label for="validationCustom03">Template Type</label>

                                            <select class="form-control" id="message_template_involve_id" name="message_template_involve_id" tabindex="-1" aria-hidden="true">
                                                <option value="">Please Select A Type</option>
                                                @if($involve_sel)
                                                    @foreach($involve_sel as $involve)
                                                        <option @if(@$search['message_template_involve_id']==$involve->message_template_involve_id) selected @endif value="{{$involve->message_template_involve_id}}">{{$involve->message_template_involve_type}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <a href="{{route('message_template_listing','reset=1')}}" class="btn btn-danger waves-effect waves-light mr-2"><i class="fas fa-times mr-1"></i> Reset</a>
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
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Name</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = $records->firstItem();
                                @endphp

                                @forelse($records as $rows)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td><b>{{$rows->message_template_name}}</b>
                                        @if (@$rows->message_template_involve_link)
                                            @foreach ($rows->message_template_involve_link as $involve_link)
                                                <br>-> {{$involve_link->message_template_involve->message_template_involve_type}}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{!!nl2br($rows->message_template_content)!!}</td>
                                    <td>
                                        @switch($rows->message_template_status)
                                            @case('1')
                                                <span class='badge badge-success'>Enable</span>
                                                @break
                                            @default
                                                <span class='badge badge-danger'>Disable</span>
                                            @endswitch
                                        <br>Updated On: <b>{{ date_format($rows->message_template_updated, 'Y-m-d h:i A') }}</b>
                                    </td>
                                    <td>
                                        @can('setting_message_template')
                                            <a href="{{ route('message_template_edit', $rows->message_template_id) }}" class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                        @endcan
                                        @can('setting_message_template')
                                            <button class="btn btn-sm btn-outline-danger delete mr-2" data-toggle="modal" data-target="#delete" data-id="{{ $rows->message_template_id }}">Delete</button>
                                        @endcan
                                        @can('setting_message_template')
                                            <a href="https://api.whatsapp.com/send?phone={{ $rows->message_template_mobile_no }}&text={{ $rows->message_template_content }}" target="_blank" class="btn btn-outline-success btn-sm mr-2">WhatsApps</a>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                <td colspan='5'>No Records!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{$records->links('pagination::bootstrap-4')}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('message_template_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Message Template ?</h4>
                        <input type="hidden" name="message_template_id" id="message_template_id">
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
<script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function(e) {
            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                $(".modal-body #message_template_id").val(id);
            });
        });


		$('.message_template_involve_id').select2({
            minimumResultsForSearch: -1
        });

    </script>
@endsection
