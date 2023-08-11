@extends('layouts.master')

@section('title')
    {{ $title }} Claim
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Claim</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{route('claim_listing', ['tenant' => tenant('id')])}}">Claim</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
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
                        <div class="row">
                            <div class="col-6">
                                @if(auth()->user()->user_type_id == 1)
                                    <div class="form-group">
                                        <label for="example-text-input" class="col-form-label">Company<code>*</code></label>
                                        {!! Form::select('company_id', $company_sel, @$post->company_id, ['class' => 'form-control select2', 'id' => 'company_id']) !!}
                                        <span id="error_company_pic"></span>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="example-text-input" class="col-form-label">Company<code>*</code></label><br>
                                        <span><b>{{auth()->user()->company->company_name}}</b></span>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Farm Manager<code>*</code></label>
                                    {!! Form::select('worker_id', $farm_manager_sel, @$post->worker_id, ['class' => 'form-control select2', 'id' => 'worker_id']) !!}

                                </div>
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Claim Month<code>*</code></label>
                                    <div class="input-daterange input-group" id="datepicker7" data-date-format="yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container="#datepicker7">
                                        <input type="text" class="form-control" name="month_year" placeholder="Please Select Claim Month" value="{{@$post->month_year}}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Remark:</label>
                                    <textarea name="remark" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 my-2">
                                <button type="submit" id="submit" class="btn btn-success">Create Claim</button>
                                <a href="{{route('claim_listing', ['tenant' => tenant('id')])}}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
            @if(auth()->user()->user_type_id != 1)
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                get_farm_manager(company_id);
            @endif

        $("#datepicker7").datepicker({
            orientation: "top left",
            format: "yyyy-mm",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true //to close picker once year is selected
        });

        // check company pic first
        $('#company_id').change(function(){
            if($(this).val() != ""){
                var company_id = $(this).val();
                get_farm_manager(company_id)
            }else{
                $('#error_company_pic').html('');
                $('#company_id').removeClass('has-error');
                $('#submit').attr('disabled', false);
            }
        });

        function get_farm_manager(company_id)
        {
            let farm_manager_sel = '<option value="">Please Select Farm Manager</option>';
            $.ajax({
                type: 'POST',
                url: "{{route('ajax_check_company_pic', ['tenant' => tenant('id')])}}",
                data: {
                    company_id: company_id,
                    claim_status_id: 1,
                    _token: '{{csrf_token()}}'
                },
                success: function(e) {
                    if(e.status){
                        $('#error_company_pic').html('');
                        $('#company_id').removeClass('has-error');
                        $('#submit').attr('disabled', false);
                        // console.log(e);
                        // User selection ajax
                        $('#worker_id').html('<option value="">Loading...</option>');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('ajax_get_farm_manager_by_company_id') }}",
                            data: {
                                company_id: company_id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(e) {

                                if (e.data.length > 0) {
                                    e.data.forEach((element) => {
                                            farm_manager_sel += '<option value=' + element.id + '>' + element.name + '</option>'
                                        });
                                        $('#worker_id').html(farm_manager_sel);
                                } else {
                                    $('#worker_id').html('<option value="">No Farm Manager</option>');
                                }
                            }
                        });
                    }
                    else{

                        $('#error_company_pic').html('<label class="text-danger">Please assign company PIC first.</label>');
                        $('#company_id').addClass('has-error');
                        $('#submit').attr('disabled', 'disabled');
                        $('#worker_id').html(farm_manager_sel);
                    }
                }
            });
        }
    </script>
@endsection
