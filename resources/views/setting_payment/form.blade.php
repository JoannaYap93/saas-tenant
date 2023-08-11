@extends('layouts.master')

@section('title') {{ $title }} Setting Payment @endsection

@section('css')
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $title }} Setting Payment</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Setting Paymnet</a>
					</li>
					<li class="breadcrumb-item active">Form</li>
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
    <div class="col-12">
        <form method="POST" action="{{ $submit }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Setting Payment Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="setting_payment_name">Setting Payment Name<span class="text-danger">*</span></label>
                                <input type="text" name="setting_payment_name" class="form-control" value="{{ @$post->setting_payment_name }}" >
                            </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Gateway<span class="text-danger">*</span></label>
                                    {!! Form::select('is_payment_gateway', $is_gateway, @$post->is_payment_gateway, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Service<span class="text-danger">*</span></label>
                                    {!! Form::select('is_offline', $is_offline, @$post->is_offline, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>


                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                        <a href="{{ route('setting_payment_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                    </div>

                </div>
            </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script>

@endsection
