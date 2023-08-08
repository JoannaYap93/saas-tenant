@extends('layouts.master')

@section('title')
    Product Listing
@endsection

@section('css')
    <style>

    </style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Product Listing</h4>
                    @can('product_manage')
                        <a href="{{ route('product_add') }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Product</a>
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
                                            <label for="">Product Category</label>
                                            {!! Form::select('product_category_id', $product_category_sel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Product Status</label>
                                            {!! Form::select('product_status', $status, @$search['status'], ['class' => 'form-control', 'id' => 'product_status']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_size">Product Grades</label>
                                            {!! Form::select('product_size', $product_size_sel, @$search['product_size'], ['class' => 'form-control', 'id' => 'product_size']) !!}
                                        </div>
                                    </div>
                                    {{-- @if (auth()->user()->company_id == 0) --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="company_id">Company</label>
                                            {!! Form::select('company_id', $company, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Land</label>
                                            <select name="company_land_id" class="form-control" id="land_id">
                                                <option value="">Please Select Company</option>
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
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead>
                                <th></th>
                                <th>Name</th>
                                <!-- <th>Category</th> -->
                                <th>Grades</th>
                                <th>Land</th>
                                <th>Status</th>
                                @can('product_manage')
                                    <th>Action</th>
                                @endcan
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @foreach ($records as $product)
                                        @php
                                            $product_status = @$product->product_status->product_status_id;
                                            $span_status = '';
                                            switch ($product_status) {
                                                case 1:
                                                    $span_status = "<span class='badge badge-info font-size-11'>Pending</span>";
                                                    break;
                                                case 2:
                                                    $span_status = "<span class='badge badge-success font-size-11'>Publish</span>";
                                                    break;
                                                case 3:
                                                    $span_status = "<span class='badge badge-danger font-size-11'>Private</span>";
                                                    break;
                                                case 4:
                                                    $span_status = "<span class='badge badge-warning font-size-11'>Draft</span>";
                                                    break;
                                                default:
                                                    break;
                                            }

                                            $product_size_list = '';
                                            if ($product->product_size_link) {
                                                foreach ($product->product_size_link as $product_sizes) {
                                                    $product_size_list .= "<span class='badge badge-soft-dark'>Grade {$product_sizes->setting_size->setting_product_size_name}</span> ";
                                                }
                                            }
                                            $product_land = '';
                                            if($product->product_land) {

                                                foreach ($product->product_land as $key => $land) {
                                                    if(@$land->company_land->company->company_status != 'suspended'){
                                                    $product_land .= "<span class='badge badge-soft-dark'>{$land->land}</span> ";
                                                }
                                              }
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                @if (@$product->hasMedia('product_media'))
                                                    <img src="{{ $product->getFirstMediaUrl('product_media') }}" alt=""
                                                        width="80">
                                                @endif
                                            </td>
                                            <td>
                                                <b class="align-middle mr-1">{{ $product->product_name }}</b>
                                                @if ($product->product_category)
                                                  - <span style="font-style: italic;">{{ $product->product_category->product_category_name }}</span>
                                                @endif
                                                <br>
                                                {!! $product->product_description !!} <br>
                                            </td>
                                            <!-- <td>
                                                @if ($product->product_category)
                                                    {{ $product->product_category->product_category_name }}
                                                @else
                                                    -
                                                @endif
                                            </td> -->
                                            <td style="white-space:normal; min-width:100px">
                                                {!! $product_size_list !!}
                                            </td>
                                            <td style="white-space:normal">
                                                {!! $product_land !!}
                                            </td>
                                            <td>{!! $span_status !!}</td>
                                            @can('product_manage')
                                                <td>
                                                    <a href="{{ route('product_edit', $product->product_id) }}"
                                                        class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                                    <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                        data-target="#delete" data-id="{{ $product->product_id }}">
                                                        Delete
                                                    </button>
                                                    {{-- <button type="submit" class="ml-2 btn btn-sm btn-outline-info view_land"
                                                        data-toggle="modal" data-target="#land"
                                                        data-land="{{ json_encode($product->product_land) }}">
                                                        View Land
                                                    </button> --}}
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                @else
                                    <td colspan="4">No records found</td>
                                @endif
                            </tbody>
                        </table>
                        {!! $records->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('product_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this product ?</h4>
                        <input type="hidden" name="product_id" id="product_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="land" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Company Land</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Land Name</td>
                            </tr>
                        </thead>
                        <tbody id="land_here">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.delete').on('click', function() {
                var id = $(this).data('id');
                // alert(id);
                $('.modal-body #product_id').val(id);
            });

            @if (@$search['company_id'])
                get_land('{{ $search['company_id'] }}')
            @endif
        });

        $('.view_land').click(function() {
            let insert = '';
            let c = 1;
            let land = $(this).data('land');
            land.forEach(element => {
                insert += '<tr><td>' + c + '</td><td>' + element.land + '</td></tr>';
                c++;
            });
            $('#land_here').html(insert);
        });

        $('#company_id').on('change', function() {
            $('#land_id').html('<option value="">Loading...</option>');
            let id = $(this).val();
            get_land(id);
        });

        function get_land(id) {
            let select = '{{ @$search['company_land_id'] ?? null }}';
            let land = '<option value="">Please Select Land</option>';
            // let user = '<option value="">Please Select User</option>';

            $.ajax({
                url: "{{ route('ajax_land_user') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(e) {
                    // console.log(e);
                    if (e.land.length > 0) {
                        e.land.forEach(element => {
                            if (select != null && select == element.company_land_id) {
                                land += '<option selected value="' + element.company_land_id + '">' +
                                    element
                                    .company_land_name + '</option>';
                            } else {
                                land += '<option value="' + element.company_land_id + '">' + element
                                    .company_land_name + '</option>';
                            }
                        });
                        $('#land_id').html(land);
                    } else {
                        $('#land_id').html('<option value="">No Land</option>');
                    }
                }
            });
        }
    </script>
@endsection
