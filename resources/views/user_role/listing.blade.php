@extends('layouts.master')

@section('title') Role Listing @endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><span class="mr-2 ">Admin Role Listing</span>
                    @can('admin_role_manage')
                        <a href="{{ route('admin_role_add', ['tenant' => tenant('id')]) }}"
                            class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i
                                class="fas fa-plus"></i> Add New</a>
                    @endcan
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Role</a>
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
                            <form method="POST" action="{{ route('admin_role_listing', ['tenant' => tenant('id')]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="validationCustom03">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    {{-- @if (auth()->user()->user_type_id == 1 && auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <label for="">Company: </label>
                                            {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control']) !!}
                                        </div>
                                    @endif --}}
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
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">#</th>
                                    <th>Role Title</th>
                                    <th>Total User</th>
                                    {{-- @if (auth()->user()->user_type_id == 1)
                                        <th>Company</th>
                                    @endif --}}
                                    @can('admin_role_manage')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                <?php $num = 1; ?>
                                @if (count($roles) > 0)
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $num }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $user_role_count[$role->id] }}</td>
                                            {{-- @if (auth()->user()->user_type_id == 1)
                                                <td>{{ @$company[$role->id] }}</td>
                                            @endif --}}
                                            @can('admin_role_manage')
                                                <td>
                                                    @if ($role->company_id == auth()->user()->company_id || auth()->user()->user_type_id == 1)
                                                    <a href="{{ route('admin_role_edit', ['tenant' => tenant('id'), 'id' => $role->id]) }}"
                                                        class="btn btn-sm btn-outline-primary waves-effect waves-light">
                                                        Edit & Assign Permission
                                                    </a>
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                        <?php $num++; ?>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">"No Records!"</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->
@endsection
