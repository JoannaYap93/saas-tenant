@extends('layouts.master')

@section('title') Edit Tree Log  @endsection

@section('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}"> --}}
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Company Land Tree Log - Edit </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Tree Log </a>
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
        <form method="POST" action="{{ route('land_tree_log_edit', $company_land_tree_log_id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h5><b>{{@$records->company_land_tree->company_land_zone->company_land_zone_name}}</b></h5>
                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Tree No:</label><input type="text" name="company_land_tree_no"
                                     class="form-control"
                                     placeholder="Tree No"
                                     required
                                     value="{{@$records->company_land_tree->company_land_tree_no}}" disabled>
                                     <input type="number" hidden name="company_land_tree_log_id" value="{{@$records->company_land_tree_log_id}}">
                                    <input type="number" hidden name="company_land_tree_id" value="{{@$records->company_land_tree_id}}">
                                    <input type="number" hidden name="company_land_id" value="{{@$records->company_land_tree_action_id}}">
                                    <input type="number" hidden name="user_id" value="{{@$records->user_id}}">
                                    <br>

                                    <label>Date Logged: </label>
                                    <input name="company_land_tree_log_date" class="form-control input-mask" id="datepicker" value="{{ @$records->company_land_tree_log_date }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                    <br>
                                    <label>Description:</label>
                                    <textarea name="company_land_tree_log_description" id="" cols="30" rows="3" placeholder="Please Insert Description"
                                            required
                                            class="form-control">{{@$records->company_land_tree_log_description}}</textarea>
                                    <br>

                        </div>
                        <div class="col-5">
                            <label>Date Created: </label>
                            <input name="company_land_tree_log_created" class="form-control input-mask" id="datepicker" value="{{ date_format(@$records->company_land_tree_log_created, 'Y-m-d H:i A') }}" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true"data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy-mm-dd" placeholder="yyyy-mm-dd" readonly>
                            <br>
                            <label>Tree Action: </label>
                            {!! Form::select('company_land_tree_action_id', $action_sel, @$records->company_land_tree_action_id, ['class' => 'form-control', 'id' => 'company_land_tree_action_id']) !!}
                            <br>
                            <div id="company_land_tree_log_value"><label>Value(ml): </label>
                                <input type="number" class="form-control" name="company_land_tree_log_value" id="company_land_tree_log_value_input" value="{{@$records->company_land_tree_log_value}}" step="any">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary" >Cancel</a>
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

<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<script>
    $(document).ready(function(e) {
        let action_id = $('#company_land_tree_action_id').val();

        get_action_by_id(action_id)
    });
    $('#company_land_tree_action_id').on('change', function() {
        let action_id = $(this).val();
        get_action_by_id(action_id)
    });
    function get_action_by_id(action_id)
    {
        let input_val = <?php echo json_encode($records->company_land_tree_log_value); ?>;
        $.ajax({

            url: "{{ route('ajax_get_tree_action_by_id') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                action_id: action_id,
            },

            success: function(e) {
                // console.log(e.action.is_value_required);
                if (e.action.is_value_required == 1) {
                    $('#company_land_tree_log_value').show();
                    $('#company_land_tree_log_value_input').show().attr('required', true).val(input_val);
                } else {
                    $('#company_land_tree_log_value').hide();
                    $('#company_land_tree_log_value_input').hide().attr('required', false).val('');
                }
            }
        });
    }
        // $('#datepicker6').datepicker({
        //         orientation: "bottom left",
        // });
</script>


@endsection
