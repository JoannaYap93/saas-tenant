@extends('layouts.master')

@section('title')
    Collect Listing
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <style>

        /* Style the Image Used to Trigger the Modal */
        #collect_item_media {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #collect_item_media:hover {opacity: 0.7;}

        /* Modal Content (Image) */
        .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: rgb(255, 255, 255);
        padding: 10px 0;
        height: 150px;
        }

        /* The Close Button */
        .closeModal {
        position: fixed;
        top: 15px !important;
        right: 35px;
        color: #f1f1f1;
        font-size: 30px;
        font-weight: bold;
        transition: 0.3s;
        }

        .closeModal:hover,
        .closeModal:focus {
        color: rgb(255, 255, 255);
        text-decoration: none;
        cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
        }

    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Collect Listing</h4>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Collect</a>
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
                                            <label for="">Collect Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input type="text" style="width: 75px" class="form-control" name="collect_from"
                                                    placeholder="Start Date" value="{{ @$search['collect_from'] }}" id="start"
                                                    autocomplete="off">
                                                <input type="text" style="width: 75px" class="form-control" name="collect_to"
                                                    placeholder="End Date" value="{{ @$search['collect_to'] }}" id="end"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    @if ($product_sel)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Product Category</label>
                                                {!! Form::select('product_category_id', $product_category_sel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_product">
                                            <div class="form-group">
                                                <label for="">Product</label>
                                                <select name="product_id" id="product" class="form-control">
                                                    @if (@$search['product_id'])
                                                        <option></option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="show_size">
                                            <div class="form-group">
                                                <label for="">Grade</label>
                                                <select name="product_size_id" id="size_id" class="form-control">
                                                    @if (@$search['product_size_id'])
                                                        <option></option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            {!! Form::select('collect_status', $collect_status_sel, @$search['collect_status'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Land</label>
                                            {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company</label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">User</label>
                                            {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control']) !!}
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
                                <th>Image</th>
                                <th>Company Details</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @foreach ($records as $row)
                                        @php
                                            $status = '';

                                            switch ($row->collect_status) {
                                                case 'delivered':
                                                    $status = "<span class='badge badge-success font-size-11'>Delivered</span>";
                                                    break;
                                                case 'pending':
                                                    $status = "<span class='badge badge-warning font-size-11'>Pending</span>";
                                                    break;
                                                case 'completed':
                                                    $status = "<span class='badge badge-success font-size-11'>Completed</span>";
                                                    break;
                                                case 'deleted':
                                                    $status = "<span class='badge badge-danger font-size-11'>Deleted</span>";
                                                    break;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                #{{ $row->collect_id }}<br>
                                            </td>
                                            <td>
                                                <div class="row">
                                                @if($row->hasMedia('collect_media'))
                                                    @foreach ( $row->getMedia('collect_media') as $key_media => $media )
                                                        <div id="content_{{ $media->id }}" class="p-1">
                                                            <div class="img-wrap">
                                                                <a>
                                                                    <img width="60" height="60"
                                                                    data-toggle="popupModal" data-id="{{ $media->id }}" alt="{{ $media->file_name }}" class="collect_item_media" id="collect_item_media"
                                                                    src="{{ $media->getUrl()}}">
                                                                </a>
                                                            </div>
                                                        </div>

                                                        {{-- <a target="_blank" href="{{$media->getUrl()}}" class="mr-2"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 1px;" src="{{ $media->getUrl()}}" width="50" height="50"></a> --}}
                                                    @endforeach
                                                @else
                                                -
                                                @endif
                                                </div>
                                            </td>
                                            <td>
                                              <b>#{{ $row->collect_code }}</b><br>
                                                {{ @$row->company_land->company_land_category->company_farm->company_farm_name }} -
                                                {{ @$row->company_land->company_land_category->company_land_category_name }}
                                            </td>
                                            <td>
                                                @php
                                                    $items = '';
                                                    if($row->product){
                                                        $items .= $row->product->product_name . '-';
                                                    }

                                                    if($row->setting_product_size){
                                                        $items .= $row->setting_product_size->setting_product_size_name . '-';
                                                    }

                                                    if(fmod($row->collect_quantity, 1) === 0.0000){
                                                        $collect_quantity = number_format($row->collect_quantity, 0);
                                                    } else {
                                                        $collect_quantity = $row->collect_quantity;
                                                    }

                                                    $items .= '<b>' . $collect_quantity . ' KG</b>';
                                                @endphp

                                                {!! $items != null ? $items : '"No Item"' !!}
                                            </td>
                                            <td>
                                                <b>Collect Date: </b><br>
                                                {{ $row->collect_date }}<br>
                                                {!! $status !!}
                                            </td>
                                            <td>
                                                @if ($row->sync_id)
                                                    <a href="{{ route('get_sync_listing', ['tenant' => tenant('id'), 'id' => $row->sync_id]) }}"
                                                        class="btn btn-sm btn-outline-primary">View Sync Listing</a>
                                                @endif
                                                @can('delivery_order_company_land_edit')
                                                    <span data-toggle='modal' data-target='#edit' data-id='{{$row->collect_id}}' data-code='{{$row->collect_code}}' data-land='{{$row->company_land_id}}' class='edit'><a href='javascript:void(0);' class='btn btn-sm btn-outline-warning waves-effect waves-light'>Edit</a></span>
                                                @endcan
                                                @can('collect_manage')
                                                    <span data-toggle='modal' data-target='#delete' data-id='{{$row->collect_id}}' class='delete'><a href='javascript:void(0);' class='btn btn-sm btn-outline-danger waves-effect waves-light'>Delete</a></span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No Record Found !</td>
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
    {{-- Delete --}}
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('collect_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Collect ? </h4>
                        <input type="hidden" name="collect_id" id="collect_id">
                        <input type="hidden" name="action" value="delete">
                        <div>
                            <label for="collect_log_description">Remark: </label>
                            <textarea name="collect_log_description" id="summernote"
                                    class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('collect_edit', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Edit Collect ? </h4>
                        <input type="hidden" name="collect_id" id="collect_id_edit">
                        <div class="pb-2">
                          <span id="collect_no_edit_modal" ></span>
                        </div>
                        <div class="form-group">
                            <label for="">Company Land:</label>
                            {!! Form::select('company_land_id', $company_land_sel, null, ['class' => 'form-control', 'id' => 'edit_land_sel']) !!}
                        </div>
                        <!-- <div>
                            <label for="collect_log_description">Remark: </label>
                            <textarea name="collect_log_description" id="summernote"
                                    class="form-control" required></textarea>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- modal popup images -->
        <div id="popupModal" class="modal" tabindex="-1" role="dialog" >
            <span class="closeModal" data-dismiss="modal">&times;</span>
            <div class="modal-content image_wrap"></div>
            <div id="caption"></div>
        </div>

                <!-- View Details Images Collect Item -->
        <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b> Collect Item Images</b>
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
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            get_product_by_category($('#product_category_id').val());
            get_size_by_product($('#product').val());

                    // to trigger modal view image in full size
                    var captionText = document.getElementById("caption");
                    $('.collect_item_media').on('click',function(){

                        let media_id = $(this).attr('data-id');
                        let source = $(this).attr('src');
                        let caption = +$(this).attr('alt');
                        let img = '<img src="'+source+'" id="'+media_id+'">';
                        $('.image_wrap').html(img);
                        captionText.innerHTML = this.alt;
                        $('#popupModal').modal('show');

                    });

                    // modal for view all images for collect selected
                    $('.view_images').on('click',function(){
                        let collect_media_id = $(this).attr('data-id');
                        let collect_media = $(this).attr('collect_media');
                        let collect_name = $(this).attr('data-collect-name');
                        $('.view_images_body').html('<div> Loading... </div>');
                        let details = '';

                        $.ajax({
                            url: "{{ route('ajax_get_image_by_ce_item_id', ['tenant' => tenant('id')]) }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                ce_item_id: collect_media_id
                            },
                            dataType: "json",
                            success: function(e) {
                                details +='<div class="row">';
                                details += '<div class="col-md-12 col-sm-12 style=" border-style: groove;">';

                                e.items.forEach(element => {
                                    if(element.media != null){
                                        element.media.forEach(images => {
                                            details +='<a href="'+images.original_url+'" target="_blank" rel="noopener noreferrer"><img style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19); border-radius: 10px;" src="'+images.original_url+'" alt="" srcset="" width="100" height="100">';
                                            details += '&nbsp; &nbsp; &nbsp;';
                                        });
                                    } else{
                                        details += '';
                                    }
                                });

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
        });

        $(document).on('change', '#product_category_id', function() {
            let product_category_id = $(this).val();
            get_product_by_category(product_category_id);
        });

        $(document).on('change', '#product', function() {
            let product_id = $(this).val();
            get_size_by_product(product_id);
        });

        $('.delete').on('click', function() {
            let id = $(this).attr('data-id');
            $(".modal-body #collect_id").val(id);
        })

        $('.edit').on('click', function() {
            let id = $(this).attr('data-id');
            let collect_code = $(this).attr('data-code');
            let company_land_id = $(this).attr('data-land');
            $(".modal-body #collect_id_edit").val(id);
            $("#collect_no_edit_modal").html('<b>#' + collect_code + '</b>');
            $('#edit_land_sel').val(company_land_id);
        })

        function get_product_by_category(product_category_id){
            let sel_product_id = '{{ @$search['product_id'] }}' ?? null;
            let product_sel = '<option value="">Please Select Product</option>';

            $('#product').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_product_by_product_category_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    product_category_id: product_category_id
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (sel_product_id != null && element.id == sel_product_id) {
                                product_sel += '<option value="' + element.id + '" selected>' + element.value + '</option>';
                            } else {
                                product_sel += '<option value="' + element.id + '">' + element.value + '</option>';
                            }
                        });
                    }
                    $('#product').html(product_sel);
                }
            });
        }

        function get_size_by_product(product_id){
            let sel_size_id = '{{ @$search['product_size_id'] }}' ?? null;
            let size_sel = '<option value="">Please Select Grade</option>';

            $('#size_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_setting_size_by_product_id', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id
                },
                success: function(e) {
                    let option = '<option value="">Please Select Grade</option>';

                    if(e.data.length > 0){
                        e.data.forEach(function(p) {
                            option += '<option value="' + p.id + '">' + p.value + '</option>';
                        });
                    }
                    $('#size_id').html(option);
                }
            });
        }
    </script>
@endsection
