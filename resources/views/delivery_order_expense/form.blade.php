@extends('layouts.master')

@section('title') {{ $title }} Customer Term @endsection

@section('css')
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">

        <!-- Summernote css -->
        <link href="{{ global_asset('assets/libs/summernote/summernote.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ $title }} Term</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Customer</a></li>
                    <li class="breadcrumb-item active">{{ $title }} Term</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="outer-repeater"  method="POST" action="{{ $submit }}">
                    @csrf
                    <h4 class="card-title mb-4">Term Details</h4>
                    <div data-repeater-list="outer-group" class="outer">
                        <div data-repeater-item class="outer">
                            <div class="form-group row mb-4">
                                <label for="customer_term_name" class="col-form-label col-lg-2">Term Name</label>
                                <div class="col-lg-4">
                                    <input name="customer_term_name" class="form-control" type="text" value="{{ @$post->customer_term_name }}" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-lg-10">
                            <button type="submit" class="btn btn-primary">{{ $title }} Term</button>
                            <a href="{{ route('customer_term_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary" >Cancel</a>
                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    // $(document).ready(function(e) {
    //     $("#check_all").click(function () {
    //         $(".check:not(:disabled)").prop('checked', $(this).prop('checked'));
    //     });
    // });
</script>
@endsection
