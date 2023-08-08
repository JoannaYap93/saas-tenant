@extends('layouts.master')

@section('title') Setting Formula Category Listing @endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 font-size-18 mr-2">Formula Category Listing</h4>
                    @if (auth()->user()->user_type_id == 1)
                        @can('formula_category_manage')
                            <a href="{{ route('setting_formula_category_add') }}"
                                class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1">
                                <i class="fas fa-plus"></i> ADD NEW</a>
                        @endcan
                    @endif
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Formula Category</a>
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
            {{-- @if (auth()->user()->user_type_id == 2) --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <form method="POST" action="{{ route('setting_formula_category_listing') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationCustom03">Freetext</label>
                                                <input type="text" class="form-control" name="freetext"
                                                    placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary  waves-effect waves-light mb-2 mr-2"
                                                    name="submit" value="search">
                                                    <i class="fas fa-search mr-1"></i> Search
                                                </button>
                                                <button type="submit"
                                                    class="btn btn-danger  waves-effect waves-light mb-2 mr-2" name="submit"
                                                    value="reset">
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
            {{-- @endif --}}

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 50px; text-align:center">#</th>
                                    <th style="width: 300px;">Formula Category Name</th>
                                    <th>Budget</th>
                                    @if (auth()->user()->user_type_id == 1)
                                            <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $i =  $records->perPage()*($records->currentPage() - 1) + 1; @endphp
                                        @foreach ($records as $formula)
                                            <tr>

                                                <td align="center">
                                                    {{ $i }}
                                                </td>
                                                <td>
                                                    <b>{{ json_decode($formula->setting_formula_category_name)->en }}</b>
                                                </td>
                                                <td>
                                                  @if($formula->setting_formula_category_budget)
                                                    <b>RM: </b>{{$formula->setting_formula_category_budget}}
                                                  @else
                                                    -
                                                  @endif
                                                </td>
                                                @if (auth()->user()->user_type_id == 1)
                                                    @can('formula_category_manage')
                                                        <td><span data-toggle='modal' data-target='#fulfill'
                                                                data-id='{{ $formula->setting_formula_category_id }}'
                                                                class='fulfill'>
                                                                <a href="{{ route('setting_formula_category_edit', $formula->setting_formula_category_id ) }}"
                                                                    class="btn btn-sm btn-outline-primary waves-effect waves-light mr-2 mb-1">Edit</a>
                                                            </span>
                                                        <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                            data-target="#delete" data-id="{{ $formula->setting_formula_category_id }}">Delete
                                                        </button>
                                                        </td>
                                                    @endcan
                                                @endif
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
    <!-- End Page-content -->
    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('setting_formula_category_delete') }}">
                    @csrf
                    <div class="modal-body">
                        <h4>Delete this Formula ?</h4>
                        <input type="hidden" name="setting_formula_category_id" id="id">
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
        $('.delete').on('click', function() {
            let id = $(this).data('id');
            $('.modal-body #id').val(id);
        });
    </script>
@endsection
