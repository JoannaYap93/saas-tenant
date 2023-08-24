@extends('layouts.master')

@section('title')
    {{ $title }} Farm
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}">
    <style>
        td:last-child {
            text-align: center
        }

        .delete {
            cursor: pointer;
            font-size: 1.25rem
        }

    </style>
@endsection

@section('content')
    <!-- start page title -->
    {{-- @php
    dd($post->company_land);
@endphp --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Farm</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Farm</a>
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
                        <h4 class="card-title mb-4">Farm Details</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="company_land_category_name">Farm Name<span
                                            class="text-danger">*</span></label>
                                    <input name="company_farm_name" type="text" class="form-control"
                                        value="{{ @$post->company_farm_name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 style="font-size: 15px; margin:0;">Farm Category</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary new">
                                        Add
                                    </button>
                                </div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>#</td>
                                            <td>Name</td>
                                            <td>Action</td>
                                        </tr>
                                        @php $count = 1; @endphp
                                        @if ($title == 'Edit' && @$post->company_land_category)
                                            @foreach ($post->company_land_category as $categ)
                                                <tr id="{{ $count }}">
                                                    <td>
                                                        {{ $count }}
                                                        <input type="hidden" name="category_id[]"
                                                            value="{{ $categ->company_land_category_id }}">
                                                    </td>
                                                    <td>
                                                        {{ $categ->company_land_category_name }}
                                                        <input type="hidden" name="category_name[]" class="category_name"
                                                            value="{{ $categ->company_land_category_name }}">
                                                    </td>
                                                    <td>
                                                        <i class="mdi mdi-trash-can-outline text-danger delete"></i>
                                                    </td>
                                                </tr>
                                                @php $count++ @endphp
                                            @endforeach
                                        @else
                                        @endif
                                        <tr id="add_b4"></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('company_farm_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
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
    <script src="{{ global_asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        let c = '{{ @$post->company_land_category ? count(@$post->company_land_category) : 0 }}';

        $('.new').click(function() {
            let cat = '';
            c++;
            cat += '<tr id="' + c + '"><td>' + c + '<input type="hidden" name="category_id[]"></td>';
            cat +=
                '<td><input type="text" name="category_name[]" class="form-control category_name" required></td><td><i class="mdi mdi-trash-can-outline text-danger delete"></i></td></tr>';
            $(cat).insertBefore('#add_b4');


            $('.category_name').on('change', function() {
                let duplicate = 0;
                let current_value = this.value;

                $(".table").find('input[name="category_name[]"]').each(function() {
                    if(current_value = this.value){
                        duplicate++;
                    }
                });

                if(duplicate > 2){
                    alert('duplicate farm category name');
                    this.value = "";
                }
            });

        });

        $(document).on('click', '.delete', function() {
            $(this).parent().parent().remove();
        });
    </script>
@endsection
