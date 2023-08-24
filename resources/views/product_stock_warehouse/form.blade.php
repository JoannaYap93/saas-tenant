@extends('layouts.master')

@section('title') {{ $type }} Product Stock Warehouse @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
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
                <h4 class="mb-0 font-size-18">{{ $type }} Product Stock Warehouse</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product Stock Warehouse</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ $submit }}" method="POST">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Product Stock Warehouse Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label for="warehouse_id">Warehouse</label>
                                      {!! Form::select('warehouse_id', $warehouse_id_sel, @$product_stk_warehse->warehouse_id, ['class' => 'form-control']) !!}
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Product</label>
                                      {!! Form::select('product_id', $product_id_sel, @$product_stk_warehse->product_id, ['class' => 'form-control']) !!}
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Product Description</label>
                                    {!! Form::select('setting_product_size_id', $product_size_id_sel, @$product_stk_warehse->setting_product_size_id, ['class' => 'form-control']) !!}
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Stock Quantity</label>
                                    <input type="number" min="1" max="1000" name="product_stock_warehouse_qty_current" id=""
                                        value="{{ @$product_stk_warehse->product_stock_warehouse_qty_current }}" class="form-control">
                                </div>
                              </div>
                              </div>
                              <div class="row">
                                  <div class="col-sm-6">
                                      <span id="error_user"></span>
                                      <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                      <a href="" class="btn btn-secondary" >Cancel</a>
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
    <script src="{{ global_asset('assets/js/pages/form-editor.init.js') }}"></script>
    {{-- <script src="{{ global_asset('assets/libs/summernote/summernote.min.js') }}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    {{-- <script src="{{ global_asset('assets/js/jquery-ui.js') }}"></script> --}}
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        $('#summernote').summernote({
            height: 300,
            maximumImageFileSize: 1024 * 1024 * 20,
        });

        $('.select2').select2({
            maximumInputLength: 90,
            tags: true,
            tokenSeparators: [','],
            createTag: function(params) {
                let term = $.trim(params.term);
                // console.log(term);

                return {
                    id: term,
                    text: term,
                    newTag: true,
                }
            },
        });
        $('.select2').on('change', function() {
            var data = $(".select2 option:selected");
            var tag_arr = {};
            $.each(data, function(index, value) {
                tag_arr[index] = value.text;
            });
            $('#product_tag_2').val(JSON.stringify(tag_arr));
        })
    </script>
@endsection
