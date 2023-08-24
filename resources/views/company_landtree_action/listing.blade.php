@extends('layouts.master')

@section('title')
    Company Land Tree Action Listing
@endsection

@section('css')
    <style></style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0 font-size-18 mr-2">Tree Action Listing</h4>
                  @can('company_land_tree_manage')
                      <a href="{{ route('add_default', ['tenant' => tenant('id')]) }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                  @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Land Tree Action</a>
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
                                    @if (auth()->user()->user_type_id == 1)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Company: </label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
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
                    @if(auth()->user()->user_type_id === 2)
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <p><strong>{{ auth()->user()->company->company_name }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <th>#</th>
                                <th>Tree Action Details</th>
                                <th>Date Created</th>
                            @can('company_land_tree_manage')
                                <th>Action</th>
                            @endcan
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    <?php $i = $records->firstItem(); ?>
                                    @foreach ($records as $action)
                                        <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                          <b>{{ @$action->company_land_tree_action_name }}</b><br>
                                          <span style="font-style: italic;">{{ @$action->company_id != 0 ? @$action->company->company_name : 'Default' }}</span>
                                        </td>
                                        <td>{{ date_format(new DateTime(@$action->company_land_tree_action_created), 'Y-m-d h:i A') }}</td>
                                        @can('company_land_tree_manage')
                                        <td>
                                            {{-- @if (auth()->user()->company_id == $action->company_id ) --}}
                                                <a href="{{ route('edit_default', ['tenant' => tenant('id'), 'id' => $action->company_land_tree_action_id) }}"
                                                class="btn btn-outline-warning btn-sm mr-2">Edit</a>
                                            {{-- @endif --}}
                                        </td>
                                        @endcan
                                        </tr>
                                    <?php $i++; ?>
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
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ global_asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
@endsection
