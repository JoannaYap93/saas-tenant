@extends('layouts.master')

@section('title')
    Fix Company Land Tree Listing
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                <span class="mr-2">Fix Company Land Tree Listing</span>
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Fix Company Land Tree</a>
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
                <div class="row">
                    <div class="col-sm-12">
                        <form method="POST" action="{{ route('fix_product_data_listing', $company_land_zone_id)}}">
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
                                    <label for="">Status: </label>
                                    {!! Form::select('company_land_tree_status', $status_sel, @$search['company_land_tree_status'], ['class' => 'form-control']) !!}
                                  </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validationCustom03">Tree Circumference (lower)</label>
                                        <input type="text" class="form-control" name="tree_circumference_lower"
                                            placeholder="Inch" value="{{ @$search['tree_circumference_lower'] }}">
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validationCustom03">Tree Circumference (upper)</label>
                                        <input type="text" class="form-control" name="tree_circumference_upper"
                                            placeholder="Inch" value="{{ @$search['tree_circumference_upper'] }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <button type="submit"
                                        class="btn btn-primary  waves-effect waves-light mr-2"
                                        name="submit" value="search">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                    <button type="submit"
                                        class="btn btn-danger  waves-effect waves-light mr-2" name="submit"
                                        value="reset">
                                        <i class="fas fa-times mr-1"></i> Reset
                                    </button>
                                    <a  href="{{ route('land_zone_listing', [ 'company_id' => $company_land->company->company_id, 'company_land_id' => $company_land->company_land_id]) }}" class="btn btn-secondary waves-effect waves-light mr-2"
                                        name="submit">
                                        <i class="fas fa-arrow-left mr-1"></i> Zone Listing
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col sm-12">
                        <p><strong>{{$company_land->company->company_name}}<br/>
                        <i>{{$company_land->company_land_name}}</i><br/>
                        {{$company_zone_detail->company_land_zone_name}}</strong></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th scope="col" style="width: 70px;">Code</th>
                                <th style="text-align:center">Product</th>
                                <th>Year of Tree</th>
                                <th>Sick</th>
                                <th>Bear Fruit</th>
                                <th>Tree Status</th>
                                    <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($records->isNotEmpty())
                            @php
                                $no = $records->firstItem();
                            @endphp
                                @foreach ( $records as $row )
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <b>{{@$row->company_land_tree_no}}</b><br><span style="font-style: italic; font-size: 12px">{{@$row->company_pnl_sub_item_code}}</span>
                                        </td>
                                        <td style="background-color:rgb(246, 242, 242); text-align:center">
                                            @if(!empty(@$row->product->product_name))
                                            <b>{{@$row->product->product_name}}</b>
                                            @else
                                            <b style="color: red">No Data</b>
                                            @endif
                                        </td>
                                        <td>
                                            {{@$row->company_land_tree_age}} Years ({{@$row->company_land_tree_circumference}} Inch)
                                        </td>
                                        <td>
                                            {!!$row->is_sick?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>'!!}
                                        </td>
                                        <td>
                                            {!!$row->is_bear_fruit?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>'!!}
                                        </td>
                                        @php
                                            switch(@$row->company_land_tree_status){
                                                case 'alive':
                                                    $status = "<span class='badge badge-success font-size-11'>Alive</span>";
                                                    break;
                                                case 'dead':
                                                    $status = "<span class='badge badge-danger'>Dead</span>";
                                                    break;
                                                case 'saw off':
                                                    $status = "<span class='badge badge-warning'>Saw Off</span>";
                                                    break;
                                            }
                                        @endphp
                                        <td>
                                            {!!$status!!}
                                            <br>
                                            <td>
                                            @can('company_land_tree_manage')
                                                {{-- @if(auth()->user()->user_type_id == 2) --}}
                                                <a href="{{ route('fix_product_data', $row->company_land_tree_id)}}" class="btn btn-sm btn-outline-warning waves-effect waves-light mr-1 mb-1">Fix Data</a>
                                                <button class="btn btn-sm btn-outline-danger delete" data-toggle="modal"
                                                data-target="#delete" data-id="{{ $row->company_land_tree_id }}">Delete
                                                </button>
                                                {{-- @endif --}}
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Records!</td>
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
<!-- End Page-content -->

 <!-- Modal -->
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
         <form method="POST" action="{{ route('tree_delete_fix') }}">
             @csrf
             <div class="modal-body">
                 <h4>Delete this Tree ?</h4>
                 <input type="hidden" name="company_land_tree_id" id="company_land_tree_id">
                 <input type="hidden" name="company_land_zone_id" id="company_land_zone_id" value="{{$company_land_zone_id}}">
             </div>
             <div class="modal-footer">
                 <button type="submit" class="btn btn-danger">Delete</button>
                 <a class="btn btn-secondary" data-dismiss="modal">Cancel</a>
             </div>
         </form>
     </div>
 </div>
</div>
 <!-- End Modal -->
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('.delete').on('click', function() {
            var id = $(this).data('id');
            // alert(id);
            $('.modal-body #company_land_tree_id').val(id);
        });
    });
</script>
@endsection