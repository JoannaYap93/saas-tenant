@extends('layouts.master')

@section('title') {{$title}} Company Expense @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <style>
        /* Style image holder */
        .img-wrap {
            position: relative;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1.5px 5px 0 rgba(0, 0, 0, 0.19);
            border-radius: 10px;
        }

        .img-wrap .del_image {
            position: absolute;
            top: -3px;
            right: -1px;
            z-index: 100;
            padding: 5px 2px 2px;
            background-color: #FFF;
            cursor: pointer;
            opacity: .5;
            text-align: center;
            font-size: 20px;
            color: #ff4a4a;
            line-height: 10px;
            border-radius: 50%;
        }
        .img-wrap:hover .del_image {
            opacity: 1;
        }



        /* Style the Image Used to Trigger the Modal */
        #expense_item_media {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        }

        #expense_item_media:hover {opacity: 0.7;}

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

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
        }

        @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
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
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{$title}} Company Expense</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Expense</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{$submit}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Company Expense Details</h4>
                        <div class="row mt-3">
                            <div class="col-12">
                                @php
                                    if($title == "Edit"){
                                        $format_date = $records->company_expense_year.'-'.$records->company_expense_month.'-'.$records->company_expense_day;
                                        $date = date('Y-m-d', strtotime($format_date));
                                    }
                                @endphp
                                <div class="row">
                                    <input type="hidden" id="user_id" name="user_id" value="{{auth()->user()->user_id}}">
                                    @if ($title == "Edit")
                                        <input type="hidden" name="date_created" value="{{@$records->company_expense_created}}">
                                    @endif
                                        @if (auth()->user()->company_id != 0)
                                            <input type="hidden" id="company_id" name="company_id" value="{{auth()->user()->company_id}}">
                                        @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="yyyy-mm-dd" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">

                                                <input type="text" style="width: 75px" class="form-control" name="expense_date"
                                                    placeholder="Select Date" id="expense_date"
                                                    autocomplete="off" value="{{@$date}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    @if (auth()->user()->company_id == 0)
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id_sel', $company_sel, @$records->company_id, ['class' => 'form-control', 'id' => 'company_id_sel', 'required']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                          <label for="user_land">Company Land:</label><br>
                                          <div class="row col-sm-12" id="company_land_id">

                                            <span id="after_land_cb"></span>
                                          </div>
                                            <!-- <label for="">Company Land: </label>
                                            {!! Form::select('company_land_id', $company_land_sel, @$records->company_land_id, ['class' => 'form-control', 'id' => 'company_land_id', 'required']) !!} -->
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Farm Manager: </label>
                                            <select name="manager_id" class="form-control" id="manager_id">
                                                @foreach ( $manager_sel as $manager_key => $manager )
                                                    <option value="{{$manager_key}}" @if($manager_key == @$records->worker_id) selected @endif>
                                                        {{$manager}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Expense Type:</label>
                                            {!! Form::select('expense_type', $expense_type_sel, @$records->company_expense_type, ['class' => 'form-control', 'id' => 'expense_type', 'required']) !!}
                                        </div>
                                    </div>
                                    {{--<div class="col-6">
                                        <div class="form-group">
                                            <label for="">Role: </label>
                                            <div class="row col-sm-12">
                                                @foreach ($worker_role_cb as $key => $worker_role)
                                                    <div class="custom-control custom-checkbox col-sm-6">
                                                        <input type="radio" id="worker_role_{{ $worker_role->worker_role_id }}" name="worker_role_id" value="{{ $worker_role->worker_role_id }}" class= "form-check-input worker_role_cb"
                                                          @if(@$records->worker_role_id && @$records->worker_role_id == $worker_role->worker_role_id)
                                                          checked
                                                          @endif
                                                        />
                                                        <label for="worker_role_{{ $worker_role->worker_role_id }}">{{ $worker_role->worker_role_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>--}}

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Expense Category:</label>
                                            {!! Form::select('expense_category_id', $expense_category_sel, @$records->setting_expense_category_id, ['class' => 'form-control', 'id' => 'expense_category_id', 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- items --}}
                    <div class="card expense_card">
                        <div class="card-body">
                            <h4 class="card-title d-flex align-items-center justify-content-between">Expense Items Details
                                    <button type="button" name="add_expense" id="add_expense" class="btn btn-success waves-effect waves-light mr-1">Add Expense</button>
                            </h4>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" >
                                            <thead>
                                                <th style="width: 20%">Expense Name</th>
                                                <th style="width: 10%">Expense Item Unit</th>
                                                <th style="width: 12%">Price (RM)</th>
                                                <th style="width: 15%">Supplier</th>
                                                <th style="width: 13%; text-align:center;"><label class="form-check-label"><label for="is_claim_all"><input type="checkbox" name="is_claim_all" id="is_claim_all"><b> Claimed </b></label></label></th>
                                                <th style="width: 11%">Total (RM)</th>
                                                <th style="width: 20%; text-align:left;">Images</th>
                                                <th style="width: 10%; text-align:center">Action</th>
                                            </thead>
                                            <tbody id="expense_field">
                                                @if (@$records->company_expense_item)
                                                    @foreach (@$records->company_expense_item as $key => $item_id)
                                                        <input type="hidden" name="company_expense_item_id" id="company_expense_item_id" value={{@$item_id->company_expense_item_id}}>
                                                    @endforeach
                                                    @php
                                                        $row = 1;
                                                    @endphp
                                                    @foreach ($records->company_expense_item as $key => $rows)
                                                        @if(@$records->setting_expense_category_id !=2)
                                                            <tr id="row_{{$row}}">
                                                                <td><select id="expense_id{{$key}}" class="form-control expense_id{{$row}}" name="expense_id[]">
                                                                <option value="{{@$rows->expense->setting_expense_id}}">{{json_decode(@$rows->expense->setting_expense_name)->en}}</option>
                                                            </select></td>
                                                            <input type="hidden" id="expense_id_hidden{{$key}}" name="expense_id_hidden[]" value= {{@$rows->expense->setting_expense_id}} >
                                                                <td><input type="number"class="item_quantity form-control" required id="item_quantity_{{$key}}" min="0" name="item_quantity[]" step="any" class="form-control" value="{{$rows->company_expense_item_unit}}"></td>
                                                                <td><input type="number" class="item_price form-control" required id="item_price_{{$key}}" name="item_price[]" step="any" value="{{$rows->company_expense_item_unit_price}}" class="form-control"></td>
                                                                <td><select id="supplier_id{{$key}}" class="form-control supplier_id{{$row}}" name="supplier_id[]">
                                                                    <option value="">Please select Supplier</option>
                                                                    @foreach ($supplier_sel as $supplier_key => $supplier )
                                                                        <option value="{{$supplier_key}}" @if($supplier_key == @$rows->supplier_id)  selected @endif>
                                                                            {{$supplier}}
                                                                        </option>
                                                                    @endforeach
                                                                </select></td>
                                                                <input type="hidden" id="supplier_id_hidden{{$key}}" name="supplier_id_hidden[]" value= {{@$rows->supplier_id}} class="supplier_id_hidden{{ $row }}">
                                                                <td style="text-align:center;"><input type="checkbox" class="claim_item" id="is_claim_{{$key}}" name="is_claim[claim][{{$key}}]" value="1" @if($rows->is_claim == 1) checked @endif/></td>
                                                                <td id="total_price_{{$key}}" name="total_price[]">{{$rows->company_expense_item_total}}</td>
                                                                <td align="left">
                                                                    @if(@$rows->hasMedia('company_expense_item_media'))
                                                                        <div class="row">
                                                                                @foreach ( $rows->getMedia('company_expense_item_media') as $key_media => $media )
                                                                                    <div id="content_{{ $media->id }}" class="p-1">
                                                                                        <div class="img-wrap">
                                                                                            <span class="del_image" data-target="#delete" data-toggle="modal" data-id="{{ $media->id }}"
                                                                                                company_expense_item_id="{{ @$rows->company_expense_item_id }}"
                                                                                                data-img-src="{{ $media->getUrl()}}">&times;</span>
                                                                                            <a>
                                                                                                <img width="60" height="60"
                                                                                                data-toggle="popupModal" data-id="{{ $media->id }}" alt="{{ $media->file_name }}" class="expense_item_media" id="expense_item_media"
                                                                                                src="{{ $media->getUrl()}}">
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            {{-- <a href="#" class="btn btn-outline-primary btn-sm view_images mb-2"
                                                                                data-id="{{ $rows->company_expense_item_id }}"
                                                                                company_expense_item_media="{{ @$rows->company_expense_item_media }}"
                                                                                id='view_images_{{$rows->company_expense_id}}'>
                                                                                <span data-toggle='modal' data-target='#view_images_modal'>View Images</span>
                                                                            </a> --}}
                                                                        </div>
                                                                    @endif
                                                                    <div class="row pt-1">
                                                                        <input type="file" id="expense_item_media_{{$key}}" name="expense_item_media[{{$row}}][]" class="form-control-file" accept="image/*" multiple>
                                                                    </div>
                                                                </td>
                                                                <td style="text-align:center;"><button type="button" name="remove" id={{$row}} class="btn btn-danger btn_remove waves-effect waves-light mr-1">X</button></td>
                                                                <tr id="row_{{$row}}">
                                                                    <td colspan="4"><textarea class="form-control" rows="5" id="remark_{{$row}}" name="remark[]" placeholder="Remarks for...">{{$rows->remark}}</textarea></td>
                                                                </tr>
                                                                </tr>
                                                        @endif
                                                        @php
                                                            $row++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                <tr id="grand_total_td">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 12px">GRAND TOTAL (RM):</td>
                                                    @if(@$records->setting_expense_category_id !=2)
                                                        <td id="grand_total" name="grand_total"> <input type="number" disabled id="grand_total_expense" id="grand_total_expense" class="form-control" value="{{@$records->company_expense_total}}"></td>
                                                    @endif
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- worker --}}
                    <div class="worker_field">

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <span id="error_user"></span>
                        <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                        <a href="{{route('company_expense_listing', ['tenant' => tenant('id')])}}" class="btn btn-secondary" >Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- modal popup images -->
    <div id="popupModal" class="modal" tabindex="-1" role="dialog" >
        <span class="closeModal" data-dismiss="modal">&times;</span>
        <div class="modal-content image_wrap"></div>
        <div id="caption"></div>
    </div>

     <!-- Delete Modal -->
     <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form>
                    @csrf
                    <div class="modal-body">
                        <h5>Are you sure want to delete this image ?</h5>
                        <div class="image_div_delete" align="center">

                        </div>
                        <input type="hidden" name="media_id" id="media_id">
                        <input type="hidden" name="ce_item_id" id="ce_item_id">
                    </div>
                    <div class="modal-footer">
                        <span id="del_button" class="btn btn-danger" data-dismiss="modal">Delete</span>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Images Expense Item -->
    <div class="modal fade" id="view_images_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <b>Expense Item Images</b>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ global_asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    {{-- <script src="{{ global_asset('assets/libs/summernote/summernote.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    {{-- <script src="{{ global_asset('assets/js/jquery-ui.js') }}"></script> --}}
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        var row_table = 1;
        var company_id_supplier = 0;
        $(document).ready(function(e) {
            var row=1;
            @if ($title == 'Edit')
                var row_counter = '{{ $row }}';
                @if(auth()->user()->user_type_id == 1)
                    company_id_supplier = $('#company_id_sel').val();
                @else
                    company_id_supplier = <?php echo json_encode(auth()->user()->company_id); ?>;
                @endif

                for(let k = 0; k < row_counter; k++){
                    get_supplier_by_upkeep(company_id_supplier, k);
                }


                @if ($worker == true)
                    manager = $('#manager_id').val();
                    exp_type = $('#expense_type').val();
                    company_id_sel = $('#company_id_sel').val();

                get_worker(manager, exp_type, company_id_sel);

                    $('.worker_field').show();
                    $('.expense_card').prop("disabled", true);
                    $('.expense_card').hide();

                @elseif ($worker == false)

                    // to trigger the delete modal
                    $('.del_image').on('click',function(){

                        var id = $(this).attr('data-id');
                        var ce_item_id = $(this).attr('company_expense_item_id');
                        var img_src = $(this).attr('data-img-src');
                        let img = '<img class="img-wrap" width="150" height="150" src="'+img_src+'" id="'+id+'">';
                        $('#delete').modal('show');
                        $('.image_div_delete').html(img);
                        $('.modal-body #media_id').val(id);
                        $('.modal-body #ce_item_id').val(ce_item_id);

                    });

                    // when click delete button, it will delete image
                    $('#del_button').on('click',function(){

                        let media_id = $('.modal-body #media_id').val();
                        let company_expense_item_id =$('.modal-body #ce_item_id').val();

                        $.ajax({
                            url: "{{ route('ajax_delete_image_by_media_item_id', ['tenant' => tenant('id')]) }}",
                            method: "POST",
                            data: {
                                    _token: "{{ csrf_token() }}",
                                    media_id: media_id,
                                    company_expense_item_id: company_expense_item_id,
                            },
                            success: function(e){
                                $('#content_'+media_id).remove();
                            },
                            error: function(e) {
                                alert(e);
                            }
                        });
                    });

                    // to trigger modal view image in full size
                    var captionText = document.getElementById("caption");
                    $('.expense_item_media').on('click',function(){

                        let media_id = $(this).attr('data-id');
                        let source = $(this).attr('src');
                        let caption = +$(this).attr('alt');
                        let img = '<img src="'+source+'" id="'+media_id+'">';
                        $('.image_wrap').html(img);
                        captionText.innerHTML = this.alt;
                        $('#popupModal').modal('show');

                    });

                    // modal for view all images for expense selected
                    $('.view_images').on('click',function(){
                        let company_expense__item_id = $(this).attr('data-id');
                        let company_expense_item_media = $(this).attr('company_expense_item_media');
                        let expense_name = $(this).attr('data-expense-name');
                        $('.view_images_body').html('<div> Loading... </div>');
                        let details = '';

                        $.ajax({
                            url: "{{ route('ajax_get_image_by_ce_item_id', ['tenant' => tenant('id')]) }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                ce_item_id: company_expense__item_id
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

                    row = '{{$row}}';
                    row_table = '{{$row}}';
                    exp_cat = $('#expense_category_id').val();
                    $('.expense_card').prop("disabled", false);
                    $('.expense_card').show();
                    $('input[name="item_quantity[]"]').on('keyup clickup change', function () {
                        let count = $(this).attr("id").substr(14);
                        let unit_price = $('#item_price_' + count).val();
                        let line_total = 0;
                        if(unit_price >0){
                            line_total = unit_price * $(this).val();

                            $("#total_price_" + count).html(line_total.toFixed(2));

                            // if(line_total >0){
                                cal_grand_total();
                            // }
                        }
                    })

                    $('.expense_id').change(function(){
                        let test = $(this).val();
                        var AnotherSelected = $(".expense_id").not(this).filter(function(){
                            return same_value = this.value ;
                        }).length; // get the number of select which has 'select' value
                        $('#submit').prop("disabled", AnotherSelected > 0 && this.value == same_value ? true : false);  // toggle the disabled when all select value = 'select'
                        $('.expense_id').not(this).val(this.value); // return all other select to 'select' value
                    }).change();

                    $('input[name="item_price[]"]').on('keyup clickup clickdown change', function () {
                        let count = $(this).attr("id").substr(11);
                        let unit_quantity = $('#item_quantity_' + count).val();
                        let line_total = 0;
                        if(unit_quantity >0){
                            line_total = unit_quantity * $(this).val();

                            $("#total_price_" + count).html(line_total.toFixed(2));

                            // if(line_total > 0){
                                cal_grand_total();
                            // }
                        }
                    })
                @endif
                $('#manager_id').attr('disabled', false);
                $('#expense_type').attr('disabled', false);
                $('#expense_category_id').attr('disabled', false);

            @elseif ($title == "Add")
                $('.expense_card').hide();
                $('.worker_field').hide();
                $('#expense_type').val('').attr('disabled', true);
                $('#expense_category_id').val('').attr('disabled', true);

                @if(auth()->user()->user_type_id == 1)
                $('#company_id_sel').on('change', function() {
                    let id = $(this).val();
                    company_id_supplier = id;
                    // let land = '<option value="">Please Select Land</option>';
                    let land = '';
                    $('#company_land_id').html('<option value="">Loading...</option>');
                    $.ajax({
                        url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(e) {

                          if (e.land.length > 0) {
                              e.land.forEach(element => {
                                  land += '<div class="custom-control custom-checkbox col-sm-6">';
                                  land += '<input type="checkbox" id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class="form-check-input" >';
                                  land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                  land += '</div>';
                                  // land += '<option value="' + element.company_land_id + '">' + element.company_land_name + '</option>';
                              });
                              $('#company_land_id').html(land);
                          } else {
                              $('#company_land_id').html('<option value="">No Land</option>');
                          }
                        }
                    });
                    get_farm_manager($(this).val());
                });
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                company_id_supplier = company_id;
                get_farm_manager(company_id);
            @endif

            var exist_company = "<?php echo @$records->company_id; ?>";
                if (exist_company > 0) {
                    $('#company_id').trigger("change");
                }

                function get_farm_manager(company_id) {

                    var exist_farm_manager = "<?php echo @$records->company_id; ?>";

                    let user = '<option value="">Please Select Farm Manager</option>';
                    $('#manager_id').html('<option value="">Loading...</option>');
                    $.ajax({
                        url: "{{ route('ajax_get_farm_manager_by_company_id, ['tenant' => tenant('id')]') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            company_id: company_id,
                        },
                        success: function(e) {
                            if (e.data.length > 0) {
                                e.data.forEach(u => {
                                    if(u.id == exist_farm_manager){
                                        user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                                    }else{
                                        user += '<option value="' + u.id + '">' + u.name +'</option>';
                                    }
                                });
                                $('#manager_id').html(user);
                            } else {
                                $('#manager_id').html('<option value="">No Farm Manager</option>');
                            }
                        }
                    });
                }

                @endif

            var total = 0;
            let grand_total = 0;

            $('#add_expense').click(function(){
                row_table++;
                $('<tr id="row'+row+'"><td><select id="expense_id'+row+'" class="form-control  expense_id'+row+'" name="expense_id[]" required></select>'
                    +'<input type="hidden" id="expense_id_hidden'+row+'" name="expense_id_hidden[]"/>'
                    +'<td><input type="number"class="item_quantity form-control" required id="item_quantity_'+ row +'" min="0" name="item_quantity[]" step="any" class="form-control"></td>'
                    +'<td><input type="number" class="item_price form-control" required id="item_price_'+ row +'" name="item_price[]" step="any" value="{{@$records->expense->expense_value}}" class="form-control"></td>'
                    +'<td><select id="supplier_id'+row+'" class="form-control supplier_id'+row+'" name="supplier_id[]">'
                    +'</select></td>'
                    +'<input type="hidden" id="supplier_id_hidden'+row+'" name="supplier_id_hidden[]"/>'
                    +'<td style="text-align:center;"><input type="checkbox" class="claim_item" id="is_claim_'+ row +'" name="is_claimed[claim]['+row+']" value="1"/></td>'
                    +'<td id="total_price_'+ row +'" name="total_price[]">0.00</td>'
                    +'<td><input type="file" id="expense_item_media_'+row+'" name="expense_item_media['+row+'][]" class="form-control-file" multiple accept="image/*"></td>'
                    +'<td style="text-align:center;"><button type="button" name="remove" id="'+row+'" class="btn btn-danger btn_remove waves-effect waves-light mr-1">X</button></td>'
                    +'<tr id="row_'+row+'"><td colspan="4"><textarea class="form-control" id="remark_'+row+'" name="remark[]" placeholder="Remarks for..." value="{{@$records->remark}}"></textarea></td></tr>'
                    +'</tr>').insertBefore('#grand_total_td');

                    $("#submit").attr("disabled", true);
                console.log(company_id_supplier);
                get_update_table();
                get_expense_by_upkeep($('#expense_category_id').val(),row);
                get_supplier_by_upkeep(company_id_supplier, row);
                row++;

                $('input[name="item_quantity[]"]').on('keyup clickup change', function () {
                    let count = $(this).attr("id").substr(14);
                    let unit_price = $('#item_price_' + count).val();
                    let line_total = 0;
                    if(unit_price >0){
                        line_total = unit_price * $(this).val();

                        $("#total_price_" + count).html(line_total.toFixed(2));

                        // if(line_total >0){
                            cal_grand_total();
                        // }
                    }
                })

                $('input[name="item_price[]"]').on('keyup clickup clickdown change', function () {
                    let count = $(this).attr("id").substr(11);
                    let unit_quantity = $('#item_quantity_' + count).val();
                    let line_total = 0;
                    if(unit_quantity >0){
                        line_total = unit_quantity * $(this).val();
                        $("#total_price_" + count).html(line_total.toFixed(2));
                        // if(line_total > 0){
                            cal_grand_total();
                        // }
                    }
                })
            });

            $(document).on('click', '.btn_remove', function(){
                $(this).parent().parent().remove();
                let value = $(this).attr("id");
                $('#row_'+value).remove();
                $('#remark_'+value).remove();
                row_table = row_table - 1;
                get_update_table();
                cal_grand_total();
                DisableOptions();
            });

            $('#manager_id').on('change', function(e) {
                $('#expense_type').val('').attr('disabled', false);
                $('#expense_category_id').val('').attr('disabled', true);
                $('.expense_card').hide();
                $('.worker_field').hide();
                $("#submit").attr("disabled", true);
            });


            $('body').on('change','select[name="expense_id[]"]',function(){
                let count = $(this).attr("id").substr(10);
                id_selected = $(this).val();

                $.ajax({
                    url: "{{ route('ajax_get_price_expense', ['tenant' => tenant('id')]) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        expense_id: $(this).val(),
                    },
                    success: function(e) {
                        $('#item_price_' + count).val(e);
                        $('#expense_id_hidden'+count).val(id_selected);
                        DisableOptions(id_selected, count);
                    }
                });
            });

            $('body').on('change','select[name="supplier_id[]"]',function(){
                let count = $(this).attr("id").substr(11);
                id_selected = $(this).val();
                console.log(count);
                $('#supplier_id_hidden'+count).val(id_selected);
            });



            $('#expense_category_id').on('change', function(e) {
                if ($(this).val() != 2) {
                    $('.expense_card').show();
                    $('.worker_field *').prop('disabled', true).hide();
                } else {
                    $('.expense_card').hide();
                }

                if ($(this).val() == 2) {
                    $('.worker_field *').prop('disabled', false);
                    $('.worker_field').show();
                    get_worker($('#manager_id').val(), $('#expense_type').val(), $('#company_id_sel').val());
                }
            });

            $('#expense_type').on('change', function(e) {
                $('#expense_category_id').val('').attr('disabled', false);
                $('.expense_card').hide();
                $('.worker_field').hide();
                $("#submit").attr("disabled", true);
            });

            @if (@$records->company_id != null)
                get_land_user('{{ $records->company_id }}');
            @else
                get_land_user('{{ auth()->user()->company_id }}');
            @endif

            function get_land_user(id) {
                // let land = '<option value="">Please Select Land</option>';
                let land = '';
                // let sland = "{{ @$records->company_expense_land ?? null }}";
                let sland = <?php echo json_encode(@$records->company_expense_land); ?>;
                $.ajax({
                    url: "{{ route('ajax_land_user', ['tenant' => tenant('id')]) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(e) {
                        if (e.land.length > 0) {
                            e.land.forEach(element => {
                              let check = '';
                                if (sland != null) {

                                  land += '<div class="custom-control custom-checkbox col-sm-6">';
                                  land += '<input type="checkbox" ';
                                  sland.forEach(company_expense_land => {
                                    if(company_expense_land.company_land_id == element.company_land_id){
                                      land += 'checked';
                                    }
                                  });
                                  land += ' id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class= "form-check-input" />';
                                  land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                  land += '</div>';
                                    // land += '<option value="' + element.company_land_id + '" selected>' +
                                    //     element
                                    //     .company_land_name + '</option>';
                                } else {
                                  land += '<div class="custom-control custom-checkbox col-sm-6">';
                                  land += '<input type="checkbox" id="company_land_id_'+element.company_land_id+'" name="company_land_id[]" value="'+element.company_land_id+'" class= "form-check-input" />';
                                  land += '<label for="company_land_id_'+element.company_land_id+'">'+element.company_land_name+'</label>';
                                  land += '</div>';
                                    // land += '<option value="' + element.company_land_id + '">' + element
                                    //     .company_land_name + '</option>';
                                }
                            });
                            $('#company_land_id').html(land);
                        } else {
                            $('#company_land_id').html('<option value="">No Land</option>');
                        }
                    }
                });
            }



            $("#is_claim_all").click(function(){
                $('.claim_item').not(this).prop('checked', this.checked);
            });

        });

        function get_worker(manager_id, expense_type, company_id){
            let worker_details = '';
            if(manager_id != ''){
                if(expense_type != ''){
                    $.ajax({
                        url: "{{ route('ajax_get_worker_list', ['tenant' => tenant('id')]) }}",
                        method: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            manager_id: manager_id,
                            expense_type: expense_type,
                            company_id: company_id,
                        },
                        success: function(e){
                            if(e.data.length > 0){

                                worker_details += '<div class="row">';
                                e.data.forEach((worker, i) => {
                                    @if ($title == "Edit")
                                        row_table ++;
                                        var tick = null;
                                        let existing_worker = {!!json_encode($worker_item)!!};

                                        if(existing_worker.hasOwnProperty(worker.id)){
                                            tick = 'checked';
                                        }

                                        var status = null;
                                        var time = null;

                                        if(existing_worker[worker.id]){
                                            status =  existing_worker[worker.id].status;
                                            time = existing_worker[worker.id].time_slot;
                                        }
                                    @endif
                                    worker_details += '<div class="col-xl-6">'+
                                            '<div class="card">'+
                                                '<div class="card-body">'+
                                                    '<div class="d-flex justify-content-between">'+
                                                        '<div>'+
                                                            '<h5 class="card-title">Worker : '+ worker.name+'</h5>'+
                                                            '<input type="hidden" class="hidden_worker_name'+worker.id+'" name="worker['+ worker.id+'][worker_name]" value="'+ worker.name+'">'+
                                                            '<p class="card-title-desc">Type: '+worker.type_name+'</p>'+
                                                        '</div>'+
                                                        '<div class="mb-3">'+
                                                            '<div class="form-check">'+
                                                                '<label class="form-check-label" >'+
                                                                    '<label>'+
                                                                        '<input name="worker['+ worker.id+'][selected]" id="select_'+ worker.id+'" type="checkbox" value="1" @if ($title == "Edit")'+ tick + '@endif/>'+
                                                                    ' Check</label>'+
                                                                '</label>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<input type="hidden" class="hidden_worker_id'+worker.id+'" name="worker['+ worker.id+'][worker_id]" value="'+ worker.id+'">'+
                                                    '<input type="hidden" class="hidden_worker_type'+worker.id+'" name="worker['+ worker.id+'][type]" value="'+ worker.type_name+'">'+
                                                    '<div class="form-group worker_input'+worker.id+'">'+
                                                        '<label for="example-text-input" class="col-md-2 col-form-label">Status</label>'+
                                                        '<div class="col-md-12">'+
                                                            '<select name="worker['+ worker.id+'][status]" class="form-control worker_status_'+ worker.id+'" id="worker_status_'+ worker.id+'" >';
                                                                @foreach($worker_status_sel as $worker_status)
                                                                    if (status == '{{$worker_status}}'){

                                                                        worker_details += '<option value="{{$worker_status}}" selected>{{$worker_status}}</option>';
                                                                    }else{
                                                                        worker_details += '<option value="{{$worker_status}}" >{{$worker_status}}</option>';
                                                                    }

                                                                @endforeach


                                                 worker_details +=  '</select>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="form-group time_slot_'+ worker.id+'">'+
                                                        '<label for="example-text-input" class="col-md-6 col-form-label">Time Slot</label>'+
                                                        '<div class="col-md-12">'+
                                                            '<select name="worker['+ worker.id+'][time_slot]" class="form-control worker_time_slot_'+ worker.id+'" id="worker_time_slot_'+ worker.id+'" >';

                                                                @if ($title == "Edit")
                                                                    @foreach($time_slot as $time)
                                                                        if (time == '{{$time}}'){
                                                                            worker_details += '<option value="{{$time}}" selected>{{$time}}</option>';
                                                                        }else{
                                                                            worker_details += '<option value="{{$time}}">{{$time}}</option>';
                                                                        }
                                                                        @endforeach
                                                                @elseif ($title == "Add")
                                                                        worker_details += '@foreach ( $time_slot as $time )'+
                                                                            '<option value="{{$time}}" @if($time == @$rows->time_slot) selected @endif>'+
                                                                                '{{$time}}'+
                                                                            '</option>'+
                                                                            '@endforeach';

                                                                @endif
                                                            worker_details +=  '</select>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="form-group">'+
                                                        '<label for="example-text-input" class="col-md-2 col-form-label">Task: </label>'+
                                                    '</div>';

                                                    worker.expense.forEach((expense, i) => {
                                                        @if ($title == "Edit")
                                                            expense_value = expense.value;
                                                            total_expense = null;
                                                            setting_expense_overwrite_commission = expense.overwrite_commission;
                                                            qty = null;
                                                            check_exist_expense = null;
                                                            existing_expense = null;
                                                            var key = null;
                                                            if(tick == 'checked'){
                                                                id_exp = expense.id;
                                                                if(existing_worker[worker.id].task){

                                                                    $.each(existing_worker[worker.id].task,function(index,v){
                                                                        if(id_exp == v.expense_id){
                                                                            key = index;
                                                                        }
                                                                    });

                                                                    if(key != null){
                                                                        existing_expense = existing_worker[worker.id].task[key].expense_id;

                                                                            check_exist_expense = 'checked';

                                                                            if(existing_worker[worker.id].task[key].expense_value){
                                                                                expense_value = existing_worker[worker.id].task[key].expense_value;
                                                                            }

                                                                            if(existing_worker[worker.id].task[key].expense_total){
                                                                                total_expense = existing_worker[worker.id].task[key].expense_total;
                                                                            }
                                                                            if(existing_worker[worker.id].task[key].qty){
                                                                                qty = existing_worker[worker.id].task[key].qty;
                                                                            }
                                                                            if(existing_worker[worker.id].task[key].setting_expense_overwrite_commission){
                                                                                setting_expense_overwrite_commission = existing_worker[worker.id].task[key].setting_expense_overwrite_commission;
                                                                            }

                                                                    }else{
                                                                        check_exist_expense = null;
                                                                        expense_value = expense.value;
                                                                        setting_expense_overwrite_commission = expense.overwrite_commission;
                                                                        if(isNaN(setting_expense_overwrite_commission) || parseFloat(setting_expense_overwrite_commission) == null || parseFloat(setting_expense_overwrite_commission) == undefined || parseFloat(setting_expense_overwrite_commission) == ''){
                                                                            setting_expense_overwrite_commission = 0;

                                                                        }
                                                                        qty = 0;
                                                                        total_expense =0;
                                                                    }
                                                                }
                                                            }
                                                        @endif

                                                        worker_details += '<div class="row expense_div_'+worker.id+'">'+
                                                            '<div class="col-md-12">'+
                                                                '<div class="form-check">'+
                                                                    '<label class="form-check-label" >'+
                                                                        '<input name="worker['+ worker.id+'][task]['+expense.id+'][selected]" type="checkbox" id="check_expense'+worker.id+'_'+expense.id+'" @if($title == "Edit") '+check_exist_expense+' @endif value="1"/>'
                                                                        +expense.name+expense.expense_type_name+
                                                                        '<input name="worker['+ worker.id+'][task]['+expense.id+'][expense_name]" type="hidden" class="hidden_expense_name'+worker.id+'_'+expense.id+'" value="'+expense.name+'">'+
                                                                        '<input name="worker['+ worker.id+'][task]['+expense.id+'][expense_id]" type="hidden" class="hidden_expense_name'+worker.id+'_'+expense.id+'" value="'+expense.id+'">'+
                                                                    '</label>'+
                                                                '</div>'+
                                                            '</div>'+
                                                            '<div class="col-1">'+
                                                            '</div>'+
                                                            '<div class="col-3">'+
                                                                '<label class="form-check-label">Value</label>'+
                                                                '<input class="form-control value_test_'+worker.id+'_'+expense.id+'" type="number" min="0.00" step="any"  name="worker['+ worker.id+'][task]['+expense.id+'][expense_value]" @if($title == "Add")value="'+expense.value+'"@elseif($title == "Edit") value="'+expense_value+'" @endif id=" value_test_'+expense.id+'"/>'+
                                                            '</div>';
                                                            if (worker.type_name == 'Subcon'){
                                                                worker_details +='<div class="col-3">'+
                                                                '<label class="form-check-label">Quantity</label>'+
                                                                '<input class="form-control qty_test_'+worker.id+'_'+expense.id+'" type="number" min="0" step="any"  name="worker['+ worker.id+'][task]['+expense.id+'][qty]" @if($title == "Add")value=""@elseif($title == "Edit") value="'+qty+'" @endif id=" qty_test_'+expense.id+'"/>'+
                                                            '</div>';
                                                            }else{
                                                                if(expense.overwrite_commission > 0){
                                                                    worker_details +='<div class="col-3">'+
                                                                        '<label class="form-check-label">Commission</label>'+
                                                                        '<input class="form-control commission_test_'+worker.id+'_'+expense.id+'" type="number" min="0" step="any"  name="worker['+ worker.id+'][task]['+expense.id+'][setting_expense_overwrite_commission]" @if($title == "Add")value="'+expense.overwrite_commission+'"@elseif($title == "Edit") value="'+setting_expense_overwrite_commission+'"@endif id="commission_test_'+expense.id+'"/>'+
                                                                    '</div>';
                                                                }

                                                            }

                                                            worker_details +=  '<div class="col-3">'+
                                                                '<label class="form-check-label">Expense Total</label>'+
                                                                '<input class="form-control expense_test_'+worker.id+'_'+expense.id+'" type="number" min="0" step="any"  name="worker['+ worker.id+'][task]['+expense.id+'][expense_total]" @if($title == "Add") value="" @elseif ($title == "Edit") value="'+total_expense+'" @else @endif id="example-text-input expense_test_'+expense.id+'" />'+
                                                            '</div>'+
                                                            '<div class="col-2">'+
                                                                '<input class="form-control" type="hidden" class="hidden_expense_type'+worker.id+'_'+expense.id+'"  name="worker['+ worker.id+'][task]['+expense.id+'][setting_expense_type]" value="'+expense.expense_type_name+'" id="'+ expense.expense_type_id +'">'+
                                                            '</div>'+
                                                        '</div>';
                                                    })
                                                worker_details +='</div>'+
                                            '</div>'+
                                        '</div>';
                                });
                                worker_details +='</div>';
                                $('.worker_field').html(worker_details);
                            }else if(e.data.length == 0){
                                $('.worker_field').html('<tr><td></td><td>No Worker(s) Details Records</td></tr>');
                            }
                            e.data.forEach((worker, i) => {
                                $('#select_'+ worker.id).click(function(){
                                        if($(this).prop("checked") == true){
                                            $(".worker_input"+worker.id+' *').prop('disabled', false);
                                            $(".time_slot_"+worker.id+' *').prop('disabled', false);
                                            $(".expense_div_"+worker.id+' *').prop('disabled', false);
                                            $(".hidden_worker_name"+worker.id+' *').prop('disabled', false);
                                            $(".hidden_worker_id"+worker.id+' *').prop('disabled', false);
                                            $(".hidden_worker_type"+worker.id+' *').prop('disabled', false);
                                            row_table++;
                                            get_update_table();

                                        }
                                        else if($(this).prop("checked") == false){
                                            $(".worker_input"+worker.id+' *').prop('disabled', true);
                                            $(".time_slot_"+worker.id+' *').prop('disabled', true);
                                            $(".expense_div_"+worker.id+' *').prop('disabled', true);
                                            $(".hidden_worker_name"+worker.id+' *').prop('disabled', true);
                                            $(".hidden_worker_id"+worker.id+' *').prop('disabled', true);
                                            $(".hidden_worker_type"+worker.id+' *').prop('disabled', true);
                                            row_table--;
                                            get_update_table();
                                        }
                                    });
                                worker.expense.forEach((expense, i) => {
                                    $('#select_'+ worker.id).click(function(){
                                        if($(this).prop("checked") == true){
                                            $('.hidden_expense_name'+worker.id+'_'+expense.id+' *').prop('disabled', false);
                                            $('.hidden_expense_type'+worker.id+'_'+expense.id+' *').prop('disabled', false);

                                        }
                                        else if($(this).prop("checked") == false){
                                            $('.hidden_expense_name'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                            $('.hidden_expense_type'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                        }
                                    });

                                    $('#check_expense'+worker.id+'_'+expense.id).click(function(){
                                        if($(this).prop("checked") == true){
                                            $('#select_'+ worker.id).prop('checked', true);
                                            $("#submit").attr("disabled", false);

                                        }
                                    });
                                    var status = $('#worker_status_'+worker.id).val();
                                    @if ($title == 'Add')
                                        $('.time_slot_'+ worker.id).hide();
                                        $("#submit").attr("disabled", true);
                                    @elseif ($title == 'Edit')
                                            if( status == 'Resigned' || status == 'Rest'){
                                                $('.expense_div_'+worker.id).hide();
                                                $('.time_slot_'+ worker.id).hide();
                                                $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                            }else if( status == 'Whole Day' ){
                                                $('#worker_time_slot_'+worker.id+' option').each(
                                                    function(index) {
                                                        $(this).prop('disabled', true);
                                                    }
                                                );
                                                $('.time_slot_'+ worker.id).val('').attr('disabled', true).hide();
                                                $('.expense_div_'+worker.id).show();
                                                $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', false);

                                            }else if( status == 'Half Day'){
                                                $('#worker_time_slot_'+worker.id+' option').each(
                                                    function(index) {
                                                        $(this).prop('disabled', false);
                                                    }
                                                );
                                                $('.time_slot_'+ worker.id).attr('disabled', false).show();
                                                $('.expense_div_'+worker.id).show();
                                            }else{
                                                $('#worker_time_slot_'+worker.id+' option').each(
                                                    function(index) {
                                                        $(this).prop('disabled', true);
                                                    }
                                                );
                                                $('.time_slot_'+ worker.id).hide();
                                                $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                            }
                                    @endif
                                    $('body').on('change','#worker_status_'+worker.id,function(){
                                        status = $('#worker_status_'+worker.id).val();
                                        $('#select_'+ worker.id).prop('checked', true);

                                        if (worker.type_name == 'Subcon'){
                                            qty = $('.qty_test_'+worker.id+'_'+expense.id).val();

                                                if( status == 'Resigned' || status == 'Rest'){
                                                    $('.expense_div_'+worker.id).hide();
                                                    $('.time_slot_'+ worker.id).hide();
                                                    $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                                }else if( status == 'Whole Day' ){
                                                    $('#worker_time_slot_'+worker.id+' option').each(
                                                        function(index) {
                                                            $(this).prop('disabled', true);
                                                        }
                                                    );
                                                    $('.time_slot_'+ worker.id).val('').attr('disabled', true).hide();
                                                    $('.expense_div_'+worker.id).show();
                                                    $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', false);

                                                }else if( status == 'Half Day'){
                                                    $('.expense_test_'+worker.id+'_'+expense.id).val('');
                                                    $('#worker_time_slot_'+worker.id+' option').each(
                                                        function(index) {
                                                            $(this).prop('disabled', false);
                                                        }
                                                    );
                                                    $('.time_slot_'+ worker.id).attr('disabled', false).show();
                                                    $('.expense_div_'+worker.id).show();
                                                }

                                                if(isNaN(qty) || parseFloat(qty) == null || parseFloat(qty) == undefined || parseFloat(qty) == ''){
                                                    qty = 0;

                                                }
                                                if(qty > 0){
                                                    total_sub = parseFloat(value) * qty;
                                                    $('.expense_test_'+worker.id+'_'+expense.id).val(total_sub.toFixed(2));
                                                }
                                        }else if(worker.type_name == 'Daily'){
                                                if( status == 'Resigned' || status == 'Rest'){
                                                    $('.time_slot_'+ worker.id).hide();
                                                    $('.expense_div_'+worker.id).hide();
                                                    $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', true);
                                                }else if( status == 'Whole Day' ){

                                                    value = $('.value_test_'+worker.id+'_'+expense.id).val();
                                                    comission = $('.commission_test_'+worker.id+'_'+expense.id).val();
                                                    if(isNaN(comission) || parseFloat(comission) == null || parseFloat(comission) == undefined || parseFloat(comission) == ''){
                                                        comission = 0;
                                                    }
                                                    if(comission > 0){
                                                        first = parseFloat(value) * 8;
                                                        total = parseFloat(first) + parseFloat(comission);
                                                        $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                                    }else{
                                                        total = parseFloat(value) * 8;
                                                        $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                                    }

                                                    $('#worker_time_slot_'+worker.id+' option').each(
                                                        function(index) {
                                                            $(this).prop('disabled', true);
                                                        }
                                                    );
                                                    $('.time_slot_'+ worker.id).hide();
                                                    $('.expense_div_'+worker.id).show();
                                                    $('#expense_'+worker.id+'_'+expense.id+' *').prop('disabled', false);

                                                }else if( status == 'Half Day'){
                                                    $('.expense_test_'+worker.id+'_'+expense.id).val('');
                                                    $('#worker_time_slot_'+worker.id+' option').each(
                                                        function(index) {
                                                            $(this).prop('disabled', false);
                                                        }
                                                    );
                                                    $('.time_slot_'+ worker.id).show();
                                                    $('.expense_div_'+worker.id).show();
                                                }
                                        }

                                    })
                                    $('body').on('keyup clickup change','.qty_test_'+worker.id+'_'+expense.id,function(){
                                        qty = $('.qty_test_'+worker.id+'_'+expense.id).val();
                                        value = $('.value_test_'+worker.id+'_'+expense.id).val();
                                        time = $('#worker_time_slot_'+worker.id).val();
                                        $('#check_expense'+worker.id+'_'+expense.id).prop('checked', true);
                                        $('#select_'+ worker.id).prop('checked', true);
                                        var status = $('#worker_status_'+worker.id).val();

                                        if(isNaN(qty) || parseFloat(qty) == null || parseFloat(qty) == undefined || parseFloat(qty) == ''){
                                            qty = 0;
                                        }

                                        if(isNaN(value) || parseFloat(value) == null || parseFloat(value) == undefined || parseFloat(value) == ''){
                                            value = 0;
                                        }
                                        if(qty > 0){
                                            total_sub = parseFloat(value) * qty;
                                            $('.expense_test_'+worker.id+'_'+expense.id).val(total_sub.toFixed(2));
                                        }
                                    })
                                    var first = 0;
                                    $('body').on('keyup clickup change','.commission_test_'+worker.id+'_'+expense.id,function(){
                                        value = $('.value_test_'+worker.id+'_'+expense.id).val();
                                        time = $('#worker_time_slot_'+worker.id).val();
                                        comission = $('.commission_test_'+worker.id+'_'+expense.id).val();
                                        $('#check_expense'+worker.id+'_'+expense.id).prop('checked', true);
                                        $('#select_'+ worker.id).prop('checked', true);
                                        var status = $('#worker_status_'+worker.id).val();

                                        if(isNaN(comission) || parseFloat(comission) == null || parseFloat(comission) == undefined || parseFloat(comission) == ''){
                                            comission = 0;
                                        }

                                        if(isNaN(value) || parseFloat(value) == null || parseFloat(value) == undefined || parseFloat(value) == ''){
                                            value = 0;
                                        }
                                        if (worker.type_name == 'Subcon'){
                                            $('.expense_test_'+worker.id+'_'+expense.id).val('');
                                        }else if(worker.type_name == 'Daily'){
                                            if(isNaN(comission) || parseFloat(comission) == null || parseFloat(comission) == undefined || parseFloat(comission) == ''){
                                                comission = 0;
                                            }
                                            if(comission > 0){
                                                //remark; check nan null number
                                                if (status == 'Whole day'){
                                                    first = parseFloat(value) * 8;
                                                }else if (time == '7AM-12PM'){
                                                    first = parseFloat(value) * 5;
                                                }else if(time == '2PM-5PM'){
                                                    first = parseFloat(value) * 3;
                                                }
                                                total = first + parseFloat(comission);
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }else{
                                                if (status == 'Whole day'){
                                                    total = parseFloat(value) * 8;
                                                }else if (time == '7AM-12PM'){
                                                    total = parseFloat(value) * 5;
                                                }else if(time == '2PM-5PM'){
                                                    total = parseFloat(value) * 3;
                                                }
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }
                                        }

                                    })

                                    $('body').on('keyup clickup change','#worker_time_slot_'+worker.id,function(){

                                        value = $('.value_test_'+worker.id+'_'+expense.id).val();
                                        time = $('#worker_time_slot_'+worker.id).val();
                                        comission = $('.commission_test_'+worker.id+'_'+expense.id).val();
                                        var status = $('#worker_status_'+worker.id).val();
                                        $('#select_'+ worker.id).prop('checked', true);

                                        if(isNaN(value) || parseFloat(value) == null || parseFloat(value) == undefined || parseFloat(value) == ''){
                                            value = 0;
                                        }
                                        if (worker.type_name == 'Subcon'){
                                            $('.expense_test_'+worker.id+'_'+expense.id).val('');

                                        }else if(worker.type_name == 'Daily'){
                                            if(isNaN(comission) || parseFloat(comission) == null || parseFloat(comission) == undefined || parseFloat(comission) == ''){
                                                comission = 0;
                                            }
                                            if(comission > 0){
                                                //remark; check nan null number
                                                if (status == 'Whole day'){
                                                    first = parseFloat(value) * 8;
                                                }else if (time == '7AM-12PM'){
                                                    first = parseFloat(value) * 5;
                                                }else if(time == '2PM-5PM'){
                                                    first = parseFloat(value) * 3;
                                                }
                                                total = first + parseFloat(comission);
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }else{
                                                if (status == 'Whole day'){
                                                    total = parseFloat(value) * 8;
                                                }else if (time == '7AM-12PM'){
                                                    total = parseFloat(value) * 5;
                                                }else if(time == '2PM-5PM'){
                                                    total = parseFloat(value) * 3;
                                                }
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }
                                        }
                                    })

                                    $('body').on('keyup clickup change','.value_test_'+worker.id+'_'+expense.id,function(){
                                        value = $('.value_test_'+worker.id+'_'+expense.id).val();
                                        qty = $('.qty_test_'+worker.id+'_'+expense.id).val();
                                        time = $('#worker_time_slot_'+worker.id).val();
                                        comission = $('.commission_test_'+worker.id+'_'+expense.id).val();
                                        var status = $('#worker_status_'+worker.id).val();
                                        $('#check_expense'+worker.id+'_'+expense.id).prop('checked', true);
                                        $('#select_'+ worker.id).prop('checked', true);

                                        if(isNaN(value) || parseFloat(value) == null || parseFloat(value) == undefined || parseFloat(value) == ''){
                                            value = 0;
                                        }

                                        if (worker.type_name == 'Subcon'){
                                            if(isNaN(qty) || parseFloat(qty) == null || parseFloat(qty) == undefined || parseFloat(qty) == ''){
                                                qty = 0;
                                            }
                                            if(qty > 0){
                                                total_sub = parseFloat(value) * qty;
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total_sub.toFixed(2));
                                            }
                                        }else if(worker.type_name == 'Daily'){
                                            if(isNaN(comission) || parseFloat(comission) == null || parseFloat(comission) == undefined || parseFloat(comission) == ''){
                                                comission = 0;
                                            }

                                            if(comission > 0){
                                                //remark; check nan null number
                                                if(status == 'Whole Day'){
                                                    first = value * 8;
                                                }else{
                                                    if (time == '7AM-12PM'){
                                                        first = parseFloat(value) * 5;
                                                    }else if(time == '2PM-5PM'){
                                                        first = parseFloat(value) * 3;
                                                    }
                                                }

                                                total = first + parseFloat(comission);
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }else{

                                                if(status == 'Whole Day'){
                                                    total = value * 8;
                                                }else{
                                                    if (time == '7AM-12PM'){
                                                        total = parseFloat(value) * 5;
                                                    }else if(time == '2PM-5PM'){
                                                        total = parseFloat(value) * 3;
                                                    }
                                                }
                                                $('.expense_test_'+worker.id+'_'+expense.id).val(total.toFixed(2));
                                            }


                                        }

                                    })
                                });
                            });
                        }
                    })
                }
            }else{
                worker_details += '<tr><td></td><td>Please select farm manager to proceed.</td></tr>';
                $('#no_workers').hide();
                $('.worker_field').html(worker_details);
            }
        }

        $(document).on('click', 'input[name="worker_id[]"]', function(){
            let value = $(this).val();

            if($(this)[0].checked == true){
              $('#worker_expense_' + value).attr({required: true, disabled: false});
              $('#expense_price_' + value).attr({required: true, disabled: false});
            }else{
              $('#worker_expense_' + value).val('').attr({required: false, disabled: true});
              $('#expense_price_' + value).val('').attr({required: false, disabled: true});
            }
        })

        function cal_grand_total(){
                var total_price = 0;
                $(".item_price").each(function(){
                    var item_price = 0;

                    console.log($(this).val());
                    if(!isNaN(parseFloat($(this).val())) && parseFloat($(this).val()) != null && parseFloat($(this).val()) != undefined && parseFloat($(this).val()) != ''){
                        item_price =  parseFloat($(this).val());
                    }

                    var item_quantity = $(this).parent().parent().find(".item_quantity").val();

                    if(isNaN(item_quantity) || parseFloat(item_quantity) == null || parseFloat(item_quantity) == undefined || parseFloat(item_quantity) == ''){
                        item_quantity = 0;

                    }
                    line_total = item_price*item_quantity;
                    total_price += parseFloat(line_total);

                    $(this).parent().parent().find("#total_price").html(line_total.toFixed(2));


                });
                $("#grand_total_expense").val(total_price.toFixed(2));
        }

        function get_update_table() {
            if (row_table <= 1) {
              $('#expense_category_id option').each(
                    function(index) {
                        $(this).prop('disabled', false);
                    }
                );
                  $("#submit").attr("disabled", true);

            } else {
                $('#expense_category_id option').not('option[value="' + $('#expense_category_id').val() + '"]').each(
                    function(index) {
                        $(this).prop('disabled', true);
                    }
                );
                $("#submit").attr("disabled", false);
            }
        }

        function get_expense_by_upkeep(category_id, row) {
            let selection = '<option value="">Please Select Expense</option>';
            let selected = '{{@$rows->expense->setting_expense_id}}';
            $.ajax({
                url: "{{ route('ajax_get_expense_by_upkeep', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    expense_category_id: category_id,
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(option => {
                            selection += '<option value="' + option.id + '">' + option.name +'</option>';
                            $('.expense_id'+row).html(selection);
                            DisableOptions();
                        });
                    } else {
                        $('.expense_id'+row).html('<option value="">No Expense</option>');
                    }
                }
            });
        }

        function get_supplier_by_upkeep(company_id, row) {
            let selection = '<option value="">Please Select Supplier</option>';
            let selected = $('.supplier_id_hidden'+row).val();

            $.ajax({
                url: "{{ route('ajax_get_supplier_by_upkeep', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                },
                success: function(e) {
                    console.log(e);
                    if (e.data.length > 0) {
                        e.data.forEach(option => {
                            if(selected != null && option.id == selected){
                                selection += '<option value="' + option.id + '" selected>' + option.value +'</option>';
                            } else {
                                selection += '<option value="' + option.id + '">' + option.value +'</option>';
                            }
                            $('.supplier_id'+row).html(selection);
                        });
                    } else {
                        $('.supplier_id'+row).html('<option value="">No Supplier</option>');
                    }
                }
            });
        }

        function get_expense_by_upkeep_on_ready(category_id, row) {
            let selection = '<option value="">Please Select Expense</option>';
            let selected = '{{@$rows->expense->setting_expense_id}}';
            $.ajax({
                url: "{{ route('ajax_get_expense_by_upkeep', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    expense_category_id: category_id,
                },
                success: function(e) {

                    if (e.data.length > 0) {
                        e.data.forEach(option => {
                            if (selected != null && option.id == selected) {
                                selection += '<option value="' + option.id + '" selected>' + option.name +'</option>';
                            } else {
                                selection += '<option value="' + option.id + '">' + option.name +'</option>';
                            }
                            $('.expense_id'+row).html(selection);
                        });

                    } else {
                        $('.expense_id'+row).html('<option value="">No Expense</option>');
                    }
                }
            });
        }

        function DisableOptions(){
            var arr = [];
            var all = [];

            var all = $('select[name="expense_id[]"] option').filter(function () {
                return $(this).val();
            }).removeAttr("disabled");

            var arr = $('select[name="expense_id[]"] option:selected').map(function () {
                return this.value;
            }).get();

            $('select[name="expense_id[]"] option').filter(function(){
                return $.inArray($(this).val(),arr)>-1;
            }).attr("disabled","disabled");
        }
    </script>
@endsection
