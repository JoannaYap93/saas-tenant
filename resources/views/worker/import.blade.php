@extends('layouts.master')

@section('title') {{ $title }} Worker @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $title }}</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="{{route('worker_listing', ['tenant' => tenant('id')])}}">Worker</a>
					</li>
					<li class="breadcrumb-item active">Import</li>
				</ol>
			</div>
		</div>
	</div>
</div>
@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ $title}}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                @if(auth()->user()->user_type_id == 1)
                                    {!! Form::select('company_id', $company_sel, @$post->company_id, ['class' => 'form-control','id'=> 'company_id']) !!}
                                @else
                                    <br><span><b>{{ auth()->user()->company->company_name }}</b></span>
                                    <input hidden name="company_id" id="company_id" value="{{auth()->user()->company_id}}"/>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Farm Manager</label>
                                <select name="user_id" id="user_id" class=" form-control" required>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>File:</label>
                                <input type="file" class="form-control" name="worker_file" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('worker_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <script>
        $(document).ready(function(e) {
            @if(auth()->user()->user_type_id == 1)
                $('#company_id').on('change', function() {
                    get_farm_manager($(this).val());
                });
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                get_farm_manager(company_id);
            @endif

            var exist_company = "<?php echo @$post->company_id; ?>";

            if (exist_company > 0) {
                $('#company_id').trigger("change");
            }
        });

        function get_farm_manager(company_id) {
            var exist_farm_manager = "<?php echo @$post->user_id; ?>";
            let user = '<option value="">Please Select Farm Manager</option>';

            $('#user_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_farm_manager_sel_by_company', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                },
                success: function(e) {
                    if (e.length > 0) {
                        e.forEach(u => {
                            if(u.id == exist_farm_manager){
                                user += '<option value="' + u.id + '" selected>' + u.name +'</option>';
                            }else{
                                user += '<option value="' + u.id + '">' + u.name +'</option>';
                            }
                        });
                        $('#user_id').html(user);
                    } else {
                        $('#user_id').html('<option value="">No Farm Manager</option>');
                    }
                }
            });
        }
    </script>
@endsection
