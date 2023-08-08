@extends('layouts.master')

@section('title')
    {{ $title }} Setting Tree Age
@endsection

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $title }} Setting Tree Age</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Setting Tree Age</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
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
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Tree Age Details</h4>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="setting_tree_age"><span style="font-size: 50x;"><b>Age</b></span><span class="text-danger">*</span></label>
                                    <input type="number" step="1" name="setting_tree_age" id="setting_tree_age_lower" class="form-control" value="{{ @$post->setting_tree_age }}" required>
                                </div>
                            </div>
                            <div class="col-1" style="align-self: center; text-align:center;">
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_pnl_sub_item_code"><span style="font-size: 50x;"><b>Item Code</b></span><span class="text-danger">*</span></label>
                                    {!! Form::select('company_pnl_sub_item_code', $company_pnl_sub_item_code_sel, @$post->company_pnl_sub_item_code, ['class' => 'form-control', 'id' => 'company_pnl_sub_item_code', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="setting_tree_age_lower_circumference"><span style="font-size: 50x;"><b>Circumference</b></span> <span style="font-size: 10x;">(lower)</span><span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="setting_tree_age_lower_circumference" id="setting_tree_age_lower_circumference" class="form-control" value="{{ @$post->setting_tree_age_lower_circumference }}" required>
                                </div>
                            </div>
                            <div class="col-1" style="align-self: center; text-align:center;">-
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="setting_tree_age_upper_circumference"><span style="font-size: 50x;"><b>Circumference</b></span> <span style="font-size: 10x;">(upper)</span><span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="setting_tree_age_upper_circumference" id="setting_tree_age_upper_circumference" class="form-control" value="{{ @$post->setting_tree_age_upper_circumference }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Setting Tree Age Pointer Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <th  style="width: 20%">Product</th>
                                            <th>Pointer Value<span class="text-danger">*</span></th>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $product)
                                                <tr>
                                                    <td>{{ $product->product_name }}</td>
                                                    <input type="hidden" name="product_id[]" id="product_id" class="form_control" value="{{ $product->product_id }}">
                                                    @if($title == "Edit" && !is_null(@$post->setting_tree_age_pointer))
                                                        @php $pointer_search = true; @endphp
                                                        @foreach (@$post->setting_tree_age_pointer as $setting_tree_age_pointer)
                                                            @if(@$setting_tree_age_pointer->product_id == $product->product_id)
                                                                <td>
                                                                    <input style="width: 50%" type="number" step="0.01" name="setting_tree_age_pointer_value[]" id="setting_tree_age_pointer_value" class="form-control" value="{{ @$setting_tree_age_pointer->setting_tree_age_pointer_value }}" required>
                                                                </td>
                                                            @else
                                                                @php $pointer_search = false; @endphp
                                                            @endif
                                                        @endforeach
                                                        @if($pointer_search = false)
                                                            <td>
                                                                <input style="width: 50%" type="number" step="0.01" name="setting_tree_age_pointer_value[]" id="setting_tree_age_pointer_value" class="form-control" value="{{ @$setting_tree_age->setting_tree_age_pointer_value }}" required>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td>
                                                            <input style="width: 50%" type="number" step="0.01" name="setting_tree_age_pointer_value[]" id="setting_tree_age_pointer_value" class="form-control" value="{{ @$setting_tree_age->setting_tree_age_pointer_value??1 }}" required>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('setting_tree_age_listing') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
