@extends('layouts.master')

@section('title') User Application Listing @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    {{-- <link href="{{ URL::asset('assets/fancybox-2.1.7/source/jquery.fancybox.css')}}" rel="stylesheet" /> --}}
    <link href="{{URL::asset('assets/lightbox2/src/css/lightbox.css')}}" rel="stylesheet"/>

@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">User Type Listing</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">User Type</a>
                        </li>
                        <li class="breadcrumb-item active"> Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Total User</th>
                                @can('admin_type_manage')
                                {{-- <th>Action</th> --}}
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 0;
                            @endphp
                            @if($records->isNotEmpty())
                                @foreach($records as $row)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $row->user_type_name }}</td>
                                        <td>{{ $row->count }}</td>
                                        {{-- <td>
                                            @if($row->user_type_referral)
                                                <table class="table-borderless">
                                                    @foreach($row->user_type_referral as $user_type_referral)
                                                        @if($user_type_referral->product)
                                                            <tr>
                                                                <td>
                                                                    {{ $user_type_referral->product->product_name }} :
                                                                </td>
                                                                <td class="text-right">
                                                                    @if($user_type_referral->user_type_referral_discount_type == 'amount')
                                                                        RM {{$user_type_referral->user_type_referral_discount_value}} @else {{$user_type_referral->user_type_referral_discount_value}}
                                                                    % @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td> --}}

                                        @can('admin_type_manage')
                                            @if($row->is_trigger_referral)
                                                <td>
                                                    <a href="{{ route('manage_referral',['id'=>$row->user_type_id]) }}">Manage
                                                        Referral</a>
                                                </td>
                                            @endif
                                        @endcan
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">No record found.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <!-- pagination -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


