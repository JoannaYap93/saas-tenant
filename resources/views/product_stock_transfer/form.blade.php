@extends('layouts.master')

@section('title') {{ $type }} Stock Transfer @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <style>

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
                <h4 class="mb-0 font-size-18">{{ $type }} Stock Transfer</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Stock Transfer</a>
                        </li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ $submit }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Stock Transfer Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Stock Transfer Remarks</label>
                                    <input type="text" name="product_stock_transfer_remark" id="" value="{{ @$stock->product_stock_transfer_remark }}"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Stock Transfer Description</label>
                                    <textarea name="product_stock_transfer_description" id="summernote" class="summernote">
                                            {{ @$stock->product_stock_transfer_description }}
                                        </textarea>
                                    <span id="total-caracteres"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Product</label>
                                            {!! Form::select('product_id', $product_id_sel, @$stock->product->product_name, ['class' => ' select2 form-control']) !!}
                                            <!-- <input type="hidden" name="product" id="product"> -->
                                        </div>
                                        <div class="form-group">
                                            <label for="">Warehouse</label>
                                            <select id="show_product" class="select3 form-control" name="product_stock_warehouse_id">
                                              <option id="insert_after_selection">Please Select Warehouse (After Product)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Current Quantity</label>
                                            <!-- <span id="show_current_qty"></span> -->
                                            <input type="number" readonly name="product_stock_transfer_qty_before" class="form-control" id="show_current_qty" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Insert Quantity</label>
                                            <input type="number" name="product_stock_transfer_qty" id="inserted_qty" class="form-control"><br>
                                            <a id="add_qty" class="btn btn-primary mr-2" style="background-color: #00FF00; border-color: #00FF00;">ADD</a>
                                            <input name="product_stock_transfer_action" id="action_1" hidden>
                                            <a id="deduct_qty" class="btn btn-primary mr-2" style="background-color: #FF0000; border-color: #FF0000;">DEDUCT</a>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Quantity After</label>
                                            <input type="number" readonly name="product_stock_transfer_qty_after" id="show_after_qty" class="form-control"
                                                value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Product Stock Transfer Status</label>
                                            {!! Form::select('product_stk_transfer_status', $product_stk_transfer_status_sel, @$stock->product_stock_transfer_status, ['class' => ' select2 form-control']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="">Product Grade</label>
                                            {!! Form::select('setting_product_size_id', $product_size_id_sel, @$stock->setting_product_size_name, ['class' => ' select2 form-control']) !!}
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('product_stock_transfer_listing') }}"
                                                class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/form-editor.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/libs/summernote/summernote.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    {{-- <script src="{{ URL::asset('assets/js/jquery-ui.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        $('#summernote').summernote({
            height: 300,
            maximumImageFileSize: 1024 * 1024 * 20,
        });

        // $('.select2').select2({
        //     maximumInputLength: 90,
        //     tags: true,
        //     tokenSeparators: [','],
        //     createTag: function(params) {
        //         let term = $.trim(params.term);
        //         console.log(term);
        //
        //         return {
        //             id: term,
        //             text: term,
        //             newTag: true,
        //         }
        //     },
        // });
        $('#add_qty').on('click', function(){
          var sum = 0;
          var action = 'Add';
           sum = Number($('#show_current_qty').val()) + Number($('#inserted_qty').val());
        //   console.log(sum);

          $('#show_after_qty').val(sum);
          $('#action_1').val(action);
        })
        $('#deduct_qty').on('click', function(){
          var sum = 0;
          var action = 'Deduct';
           sum = Number($('#show_current_qty').val()) - Number($('#inserted_qty').val());
        //   console.log(sum);

          $('#show_after_qty').val(sum);
          $('#action_1').val(action);
        })
        $('.select3').on('change', function() {
          var value = $(this).val();
        //   console.log(value);
          $.ajax({
              url: "{{ route('ajax_get_current_warehouse_qty') }}",
              method: 'post',
              data: {
                  _token: '{{ csrf_token() }}',
                  product_stock_warehouse_id: $(this).val(),
              },
              success: function(e) {

                  var show = '';

                  $('#show_current_qty').val(e.data[0].value);
                //   console.log(e.data[0].value);
                  // e.data.forEach((element, ix) => {
                  //     show += '<input type="number" readonly name="product_tag_2" class="form-control" value='+ element.value +'>'
                  //
                  // });

                  // if (value == '') {
                  //     $('#show_current_qty').html('');
                  // } else {
                  //     $('#show_current_qty').html(show);
                  // }
              }
          })
        })
        $('.select2').on('change', function() {
            var value = $(this).val();
            // console.log(value);
            $.ajax({
                url: "{{ route('ajax_check_stock_warehouse') }}",
                method: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: $(this).val(),
                },
                success: function(e) {

                    var show = '';

                      // show += '<select>'
                    e.data.forEach((element, ix) => {
                        show += '<option  value='+element.id+'>' + element.label + '</option>'
                        // show += '</option>'
                        // show += '<tr id=products_' + element.id + '>';
                        // show += '<td>' + element.label + '</td>';
                        // // show += '<td>' + element.price + '</td>';
                        // if (product_array[element.id] == element.id) {
                        //     show += '<td class="d-flex"><span class="m-auto">Added</span></td>';
                        // } else {
                        //     show += '<td class="d-flex"><span id="btn_' + element.id +
                        //         '" class="m-auto">' +
                        //         '<a href="javascript:void(0);" class="btn btn-sm btn-outline-success waves-effect waves-light" onclick="add_product(' +
                        //         element.id + ')">' +
                        //         '<i class="fas fa-plus"></i></a></span><span id="added_' +
                        //         element.id +
                        //         '" class="m-auto" style="display:none">Added</span></td>';
                        // }
                        // show += '</tr>';
                    });
                      // show += '</select>'

                    // if (value == '') {
                    //     $('#show_product').html('');
                    // } else {
                        // $('#show_product').html(show);
                        $(show).insertAfter('#insert_after_selection');
                    // }
                }
            })
            // var data = $(".select2 option:selected");
            // // console.log(data);
            // var tag_arr = {};
            // $.each(data, function(index, value) {
            //    product_id_selected = value.value;
            //     console.log(product_id_selected);
            //     // tag_arr[index] = value.text;
            // });
            // // $('#product').val(JSON.stringify(tag_arr));
        })
    </script>
@endsection
