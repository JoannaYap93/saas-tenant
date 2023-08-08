@extends('layouts.master-without-nav')

@section('title') Invoice Uploading Done @endsection

@section('css')
    <link rel="stylesheet"
        href="{{ URL::asset('assets/libs/twitter-bootstrap-wizard/twitter-bootstrap-wizard.min.css') }}">

    <!-- Plugins css -->
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    <div class=" d-block m-5">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <h4 class="mt-4 font-weight-semibold">Payment Slip Verification</h4>
                                    <p class="text-muted mt-3">Thanks for purchasing from us.</p>
                                    <p class="text-muted mt-3">Our Team will be verifying the uploaded payment slip. </p>
                                </div>
                            </div>
    
                            <div class="row justify-content-center mt-5 mb-2">
                                <div class="col-sm-6 col-8">
                                    <div>
                                        <img src="{{ asset('assets/images/verification-img.png') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <!-- wizard js -->
    <script src="{{ URL::asset('assets/libs/twitter-bootstrap-wizard/twitter-bootstrap-wizard.min.js') }}"></script>

    <!-- dropzone js -->
    <script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>

    <!-- init js -->
    <script src="{{ URL::asset('assets/js/pages/crypto-kyc-app.init.js') }}"></script>

@endsection
