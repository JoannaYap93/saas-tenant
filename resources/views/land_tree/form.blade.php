@extends('layouts.master')

@section('title') {{$type}} Tree  @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}">

<style>
    .form-control.is-invalid {
        background-image: none !important;
    }
</style>
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Company Land Tree - {{$type}} </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Company Land Tree </a>
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
        <form method="POST" action="{{$submit}}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-4">
                            <label>Tree No:</label>
                            <input type="text" name="company_land_tree_no" class="form-control" placeholder="Please Insert Tree No" required value="{{ @$records->company_land_tree_no }}">
                            <input type="number" hidden name="company_land_zone_id" value="{{@$records->company_land_zone_id}}">
                            <input type="number" hidden name="company_land_id" value="{{@$records->company_land_id}}">
                            <input type="number" hidden name="company_land_tree_id" value="{{@$records->company_land_tree_id}}">
                            <br>
                            <label>Tree Circumference: </label>
                            <input type="number" name="company_land_tree_circumference" id="" class="form-control" placeholder="Please Insert Tree Circumference" required value="{{ @$records->company_land_tree_circumference }}">
                            <br>
                            <label>Product: </label>
                            {!! Form::select('product_id', $product_sel, @$records->product_id, ['class' => 'form-control', 'id' => 'product_id']) !!}
                            <small id="product_null" class="text-danger">
                                Please Select A Product.
                                <br> 
                              </small>
                            <br>
                            <label>Tree Status: </label>
                            {!! Form::select('company_land_tree_status', $status_sel, @$records->company_land_tree_status, ['class' => 'form-control', 'id' => 'company_land_tree_status']) !!}
                            <br>
                            <div class="form-group d-flex">
                                <label for="customSwitches">Bear Fruit: </label>
                                <div class="custom-switch custom-control ml-2">
                                    <input type="checkbox" class="custom-control-input" id="customSwitches" name="is_bear_fruit" value="1" @if (@$records->is_bear_fruit == 1) checked @endif>
                                    <label for="customSwitches" class="custom-control-label"></label>
                                </div>
                            </div>
                            <label>Company Pnl Code: </label>
                            {!! Form::select('company_pnl_sub_item_code', $company_pnl_sub_item_code_sel, @$records->company_pnl_sub_item_code, ['class' => 'form-control']) !!}
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
    <script src="{{ global_asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <!-- Plugins js -->
    <script src="{{ global_asset('assets/js/pages/form-validation.init.js')}}"></script>

    <script src="{{ global_asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ global_asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ global_asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <script>
        $(document).ready(function(e){
            var e = document.getElementById("product_id");
            var type = '{{$type}}';
            console.log(type);
            $('#product_null').hide();

            if(type == "Fix"){
                $('#product_null').show();
                $( '#product_id' ).addClass( 'form-control is-invalid' );
                $('#product_id').on('change', function(){
                    if(e.value){
                        $('#product_null').hide();
                        $( '#product_id' ).removeClass( 'form-control is-invalid' ).addClass('form-control');
                    }else{
                        $('#product_null').show();
                        $( '#product_id' ).addClass( 'form-control is-invalid' );
                    }
                })
            }
        })

        $('#company_land_tree_status').on('change', function(){
            let status = $(this).val();

            if(status == 'alive'){
                console.info("hi");
                $('#customSwitches').prop("checked", true);
            }if(status == 'dead'){
                $('#customSwitches').prop("checked", false);
            }if(status == 'saw off'){
                $('#customSwitches').prop("checked", false);
            }
        });
    </script>
@endsection

