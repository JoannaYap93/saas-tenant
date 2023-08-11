@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .table-rep-plugin .btn-toolbar {
            display: none !important;
        }
    </style>
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $title }}</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="{{route('invoice_listing', ['tenant' => tenant('id')])}}">Invoice Listing</a>
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
                                <label>Company Land</label>
                                <select name="company_land_id" id="company_land_id" class=" form-control" required>
                                    <option value="">Please Select Company First</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>File:</label>
                                <input type="file" class="form-control" name="invoice_file" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ route('invoice_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if (@$result)
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Result</h4>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rows</th>
                                        <th data-priority="1">Date</th>
                                        <th data-priority="2">Customer Company Name</th>
                                        <th data-priority="3">Invoice Remark</th>
                                        <th data-priority="4">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (@$result as $key => $rows)
                                        @php
                                            $bg_color = null;
                                            $remark ='';
                                            switch ($rows['status']) {
                                                case "yellow":
                                                    $bg_color = 'style=background-color:'.$rows['status'] .'!important;';
                                                    $remark = @$rows['remark'];
                                                    break;
                                                case "red":
                                                    $bg_color = 'style=color:white;background-color:'.$rows['status'] .'!important;';
                                                    $remark = @$rows['remark'];
                                                    break;
                                                default:
                                                    $bg_color = '';
                                                    $remark = '<i class="fas fa-check-circle" style="color: green"></i>' .@$rows['remark'];
                                            }
                                        @endphp

                                        <tr {{$bg_color}}>
                                            <td>{{$key}}</td>
                                            <td>{{@$rows['invoice_date']}}</td>
                                            <td>{{@$rows['customer_company_name']}}</td>
                                            <td>{{@$rows['invoice_remark']}}</td>
                                            <td>{!!@$remark!!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>




@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-validation.init.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- Responsive Table js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>

    <!-- Init js -->
    <script src="{{ URL::asset('/assets/js/pages/table-responsive.init.js') }}"></script>

    <script>
        $(document).ready(function(e) {
            @if(auth()->user()->user_type_id == 1)
                $('#company_id').on('change', function() {
                    if($(this).val() != ''){
                        get_company_land($(this).val());
                    }else{
                        $('#company_land_id').html('<option value="">Please Select Company First</option>');
                    }

                });

                var exist_company = "<?php echo @$post->company_id; ?>";

                if (exist_company > 0) {
                    $('#company_id').trigger("change");
                }
            @else
                let company_id = <?php echo json_encode(auth()->user()->company_id); ?>;
                get_company_land(company_id);
            @endif
        });

        function get_company_land(company_id) {
            var exist_company_land = "<?php echo @$post->company_land_id; ?>";
            let company_land = '<option value="">Please Select Company Land</option>';

            $('#company_land_id').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('ajax_get_company_land', ['tenant' => tenant('id')]) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                },
                success: function(e) {
                    if (e.length > 0) {
                        e.forEach(rows => {
                            if(rows.id == exist_company_land){
                                company_land += '<option value="' + rows.company_land_id + '" selected>' + rows.company_land_name +'</option>';
                            }else{
                                company_land += '<option value="' + rows.company_land_id + '">' + rows.company_land_name +'</option>';
                            }
                        });
                        $('#company_land_id').html(company_land);
                    } else {
                        $('#company_land_id').html('<option value="">No Company Land</option>');
                    }
                }
            });
        }
    </script>
@endsection
