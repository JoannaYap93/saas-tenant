@extends('layouts.master')

@section('title') Product Category Listing @endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Category Listing</h4>
                    @can('product_category_manage')
                        <a href="{{ route('product_category_add', ['tenant' => tenant('id')]) }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product Category</a>
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
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <form method="POST" action="{{ route('product_category_listing', ['tenant' => tenant('id')]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="validationCustom03">Product Category Status</label>
                                            {!! Form::select('product_category_status', $product_category_sel, @$search['product_category_status'], ['class' => 'form-control', 'id' => 'product_category_status']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-primary  waves-effect waves-light mb-2 mr-2" name="submit"
                                                value="search">
                                                <i class="fas fa-search mr-1"></i> Search
                                            </button>
                                            <button type="submit" class="btn btn-danger  waves-effect waves-light mb-2 mr-2"
                                                name="submit" value="reset">
                                                <i class="fas fa-times mr-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Product Category Name</th>
                                    <th>Status</th>
                                    @can('product_category_manage')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                    @foreach ($records as $product_category)
                                        @php
                                            // $product_category_status = $product_category->product_category_status;
                                            $span_status = '';
                                            switch ($product_category->product_category_status) {
                                                case 'published':
                                                    $span_status = "<span class='badge badge-success font-size-11'>Published</span>";
                                                    break;
                                                case 'draft':
                                                    $span_status = "<span class='badge badge-warning font-size-11'>Draft</span>";
                                                    break;
                                                // case 3:
                                                //     $span_status = "<span class='badge badge-danger font-size-11'>Private</span>";
                                                //     break;
                                                // case 4:
                                                //     $span_status = "<span class='badge badge-warning font-size-11'>Draft</span>";
                                                //     break;
                                                default:
                                                    break;
                                            }
                                        @endphp
                                        <tr>

                                            <td align="center">
                                                {{ $i }}
                                            </td>
                                            <td>
                                                <b>{{ $product_category->product_category_name }}</b><br>
                                                {{ $product_category->product_category_slug }}
                                            </td>
                                            <td>
                                                {!! $span_status !!}
                                            </td>
                                            @can('product_category_manage')
                                                <td>
                                                    <span data-toggle='modal' data-target='#fulfill'
                                                        data-id='{{ $product_category->product_category_id }}'
                                                        class='fulfill'>
                                                        <a href="{{ route('product_category_edit', ['tenant' => tenant('id'), 'product_category_id' => $product_category->product_category_id]) }}"
                                                            class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2">Edit</a>
                                                    </span>
                                                    <span data-toggle='modal' data-target='#delete'
                                                        data-id='{{ $product_category->product_category_id }}'
                                                        class='delete'><a href='javascript:void(0);'
                                                            class="btn btn-sm btn-outline-danger waves-effect waves-light">Delete</a></span>

                                                </td>
                                            @endcan
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No Records!</td>
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
    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('product_category_delete', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this product category?</h4>
                        <input type="hidden" name="product_category_id" id="product_category_id">
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
            //$("#user_role").hide();
            $('.delete').on('click', function() {
                var id = $(this).attr('data-id');
                // console.log(id);
                $(".modal-body #product_category_id").val(id);
            });
        });
    </script>
@endsection
