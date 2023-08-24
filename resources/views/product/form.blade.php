@extends('layouts.master')

@section('title') {{ $type }} Product @endsection

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
                <h4 class="mb-0 font-size-18">{{ $type }} Product {{ @$product->product_name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ $submit }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Product Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="product_name" value="{{ @$product->product_name }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Product SKU: <span class="text-danger">*</span></label>
                                    <small id="text-success" class="text-success">Available</small>
                                    <small id="text-danger" class="text-danger">Not Available</small>
                                    <input type="text" name="product_sku" id="product_sku" value="{{ @$product->product_sku ?? @$product->product_sku }}" class="form-control" @if ($edit) readonly @endif>
                                </div>
                                <div class="form-group">
                                    <label for="">Product Description <span class="text-danger">*</span></label>
                                    <textarea name="product_description" id="summernote" class="summernote">
                                            {{ @$product->product_description }}
                                        </textarea>
                                    <span id="total-caracteres"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Product Remarks</label>
                                    <input type="text" name="product_remarks" id=""
                                        value="{{ @$product->product_remarks }}" class="form-control">
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
                                            <label for="">Product Image</label><br>
                                            @if ($edit == true && $product && @$product->media)
                                                <img src="{{ $product->getFirstMediaUrl('product_media') }}" alt="" width="250">
                                            @endif
                                            <input type="file" name="product_image" id="" class="form-control-file mt-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        @if (count($category) > 1)
                                            <div class="form-group">
                                                <label for="">Product Category  <span class="text-danger">*</span></label>
                                                {!! Form::select('product_category', $category, @$product->product_category_id ?? @$product->product_category, ['class' => 'form-control']) !!}
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="">Product Category <span class="text-danger">*</span></label> <br>
                                                <span class="text-danger">Please add a category first!</span>
                                            </div>
                                        @endif
                                        @if (@$tags)
                                            <div class="form-group">
                                                <label for="">Product Tag</label>
                                                {!! Form::select('product_tag[]', @$tags, @$product->product_tag_link ? $product->product_tag_link->pluck('product_tag_id')->toArray() : '', ['class' => 'select2 form-control select2-multiple', 'multiple' => 'multiple', 'id' => 'product_tag']) !!}
                                                <input type="hidden" name="product_tag_2" id="product_tag_2">
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="">Product Ranking <span class="text-danger">*</span></label>
                                            <input type="number" name="product_ranking" id="" class="form-control" value="{{ @$product->product_ranking }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Product Status <span class="text-danger">*</span></label>
                                            {!! Form::select('product_status', $status, @$product->product_status_id ?? @$product->product_status, ['class' => 'form-control', 'id' => 'product_status']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="setting_product_size">Product Grades</label><br>
                                            <div class="custom-control custom-checkbox col-sm-6">
                                                <input type="checkbox" id="check_all" class="form-check-input"/>
                                                <label for="check_all">Select All</label>
                                            </div>
                                            <div class="row col-sm-12">
                                                @foreach ($product_size_sel as $setting_product_size_id => $setting_product_size_name)
                                                    <div class="custom-control custom-checkbox col-sm-6">
                                                        <input type="checkbox" id="setting_product_size_{{ $setting_product_size_id }}"
                                                            name="setting_product_size_id[]" value="{{ $setting_product_size_id }}"
                                                             class= "check_setting_product_size_id form-check-input"
                                                            @if(isset($product->product_size_link))
                                                                @if(in_array($setting_product_size_id, collect($product->product_size_link)->pluck('setting_product_size_id')->toArray()))
                                                                    checked
                                                                @endif
                                                            @elseif (@$product->setting_product_size_id && in_array($setting_product_size_id, $product->setting_product_size_id))
                                                                    checked
                                                            @endif
                                                        />
                                                        <label
                                                            for="setting_product_size_{{ $setting_product_size_id }}">{{ $setting_product_size_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('product_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
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
    <script src="{{ global_asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var product_id = '{{ @$product->product_id }}'
            var product_sku = '{{ @$product->product_sku ?? ''}}';

            $('#text-success').hide();
            $('#text-danger').hide();

            $('#product_name').on('change', function (e) {
                var product_name = $(this).val();
                var product_sku = slugify(product_name);
                $('#product_sku').val(product_sku).change();
                check_product_sku(product_sku);
            });

            function slugify(text) {
                //https://gist.github.com/codeguy/6684588
                return text
                    .toString()                     // Cast to string
                    .toLowerCase()                  // Convert the string to lowercase letters
                    .normalize('NFD')       // The normalize() method returns the Unicode Normalization Form of a given string.
                    .trim()                         // Remove whitespace from both sides of a string
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-');        // Replace multiple - with single -
            }

            function check_product_sku(product_sku) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('ajax_check_product_sku', ['tenant' => tenant('id')]) }}",
                    data: {
                        product_sku: product_sku,
                        product_id: product_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (e) {
                        if (e.status) {
                            $('#text-success').show();
                            $('#text-danger').hide();
                        } else {
                            $('#text-success').hide();
                            $('#text-danger').show();
                        }
                    }
                });
            }
        });

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

        $('#check_all').on('click', function(event) {
			if(this.checked) {
				$('.btn-selected').prop("disabled", false);
				$('.check_setting_product_size_id').each(function() {
					this.checked = true;
				});
			} else {
				$('.btn-selected').prop("disabled", true);
				$('.check_setting_product_size_id').each(function() {
					this.checked = false;
				});
			}
		});
    </script>
@endsection
