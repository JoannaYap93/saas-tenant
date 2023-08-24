@extends('layouts.master')

@section('title')
    {{ $title }} Company Profit & Loss Item
@endsection

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ $title }} Company Profit & Loss Item</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Profit & Loss Item</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if (\Session::has('failed_msg'))
        <div class="alert alert-danger" role="alert">
            {{ \Session::get('failed_msg') }}
        </div>
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @enderror
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{$submit}}" id="form_pnl">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Company Profit & Loss Item Details</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company_pnl_item_name">Profit & Loss Item Name<span class="text-danger">*</span></label>
                                            <input name="company_pnl_item_name" type="text" class="form-control" value="{{ @$post->company_pnl_item_name }}" maxlength="255">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company_pnl_item_code">Profit & Loss Item Code<span class="text-danger">*</span></label>
                                            <input name="company_pnl_item_code" type="text" class="form-control" value="{{ @$post->company_pnl_item_code }}" maxlength="10">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_pnl_item_desc">Profit & Loss Item Description</label>
                                    <textarea name="company_pnl_item_desc" class="form-control" value="{{ @$post->company_pnl_item_desc }}" maxlength="255">{{ @$post->company_pnl_item_desc }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="company_pnl_item_type">Profit & Loss Item Type<span class="text-danger">*</span></label>
                                    <div class="row col-sm-12">
                                        @foreach ($company_pnl_item_type as $key => $pnl_type)
                                            <div class="custom-control custom-checkbox " style="list-style:none;display:inline-block;width:200px">
                                                <input type="radio" id="company_pnl_item_type_{{ $key }}"
                                                    name="pnl_type[]" value="{{ $key }}"
                                                    class= "check_pnl_type form-check-input"
                                                        @if(@$post->company_pnl_item_type == $key)
                                                            checked
                                                        @endif
                                                    />
                                                <label
                                                    for="company_pnl_item_type_{{ $key }}">{{ $pnl_type }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group" id="type_expenses">
                                    <label for="company_pnl_item_type_expenses">Expenses<span class="text-danger">*</span></label>
                                    <div class="row col-sm-12">
                                        @foreach ($expenses as $key => $expense)
                                            <div class="custom-control custom-checkbox" style="list-style:none;display:inline-block;width:200px">
                                                <input type="checkbox" id="expense_id_{{ $expense->setting_expense_id }}" name="setting_expense_id[]" value="{{ $expense->setting_expense_id }}" class= "form-check-input expense"
                                                    @if (in_array($expense->setting_expense_id, $post->setting_expense_id))
                                                        checked
                                                    @endif
                                                    />
                                                <label

                                                    for="expense_id_{{ $expense->setting_expense_id }}">{{ $expense->setting_expense_id .'-' .json_decode($expense->setting_expense_name)->en}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row" id="type_product_tree_category">
                                    <div class="col-sm-6" id="type_product_category">
                                        <div class="form-group">
                                            <label for="product_category_status">Product Category<span class="text-danger">*</span></label>
                                            {!! Form::select('product_category_id', $product_category_sel,  @$post->product_category_id ,['class' => 'form-control', 'id' => 'product_category_id']) !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-6" id="type_tree_category">
                                        <div class="form-group">
                                            <label for="product_category_status" class="product_category_status">Profit & Loss Sub Item</label>
                                            {!! Form::select('company_pnl_sub_item_code', $company_pnl_item_sub_sel, @$post->company_pnl_sub_item_code, ['class' => 'form-control', 'id' => 'tree_category']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="is_extra_new_tree" style="display: none">
                                    <label for="extra_new_tree_switches">Extra New Tree</label>
                                        <div class="custom-switch custom-control ml-2">
                                            <input type="checkbox" class="custom-control-input" id="customSwitches" name="is_extra_new_tree" value="1"
                                                @if (!empty($is_extra_new_tree) &&  $is_extra_new_tree == 1) checked @endif>
                                            <label for="customSwitches" class="custom-control-label"></label>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" name="submit" value="submit"
                                            class="btn btn-primary waves-effect waves-light mr-1">
                                            Submit</button>
                                        <a href="{{ route('company_pnl_item_listing', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
                                        <a href="javascript:location.reload(true)" class="btn btn-warning ml-1" id="undo" style="display:none;">Undo Changes</a>
                                    </div>
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
    <script src="{{ global_asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#type_expenses').hide();
            $('#type_product_category').hide();
            $('#type_tree_category').hide();
            $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
            $('#is_extra_new_tree').hide();

            if($('#company_pnl_item_type_expense').prop("checked")){
                $('#type_expenses').show();
                $('#type_tree_category').hide();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
                $('#type_product_category').hide();
                $('#is_extra_new_tree').hide();
            }

            if($('#company_pnl_item_type_product_category').prop("checked")){
                $('#type_expenses').hide();
                $('#type_tree_category').show();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
                $('#type_product_category').show();

                show_switch_extra_new_tree();
            }

            if($('#company_pnl_item_type_tree_category').prop("checked")){
                $('#type_expenses').hide();
                $('#type_tree_category').show();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item <span class="text-danger">*</span>';
                $('#type_product_category').hide();

                show_switch_extra_new_tree();
            }

            let title = '{{ $title }}';

            if(title == 'Edit'){
                $('input[name="pnl_type[]"]').on('click', function(){
                    $('#undo').show();
                });
            }
        });

        $('#form_pnl').submit( function(){
            let selected = $('input[name="pnl_type[]"]:checked').val();
            console.log(selected);

            if(selected == 'expense'){
                $('#product_category_id').val('');
                $('#tree_category').val('');
                $('input[name="is_extra_new_tree"]').val('');
            }

            else if(selected == 'product_category' ){
                $('.expense').val('');
                if($('#tree_category').val()){
                    $('#tree_category').val();
                    $('input[name="is_extra_new_tree"]').val();
                }
            }

            else if(selected == 'tree_category'){
                $('#product_category_id').val('');
                $('.expense').val('');
            }
        });

        $('#company_pnl_item_type_expense').change(function(){
            if($(this).prop("checked")){
                $('#type_expenses').show();
                $('#type_tree_category').hide();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
                $('#type_product_category').hide();
                $('#is_extra_new_tree').hide();
                $('#is_extra_new_tree').removeClass('d-flex');
            }
            else{
                $('#type_expenses').hide();
            }
        });

        $('#company_pnl_item_type_product_category').change(function(){
            if($(this).prop("checked")){
                $('#type_product_category').show();
                $('#type_tree_category').show();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
                $('#type_expenses').hide();

                if($('#tree_category').val()){
                    $('#tree_category').val('');
                    show_switch_extra_new_tree();
                    if($('input[name="is_extra_new_tree"]').prop("checked")){
                        $('input[name="is_extra_new_tree"]').prop("checked", false);
                    }
                }

                if($('#product_category_id').val()){
                    $('#product_category_id').val('');
                }

                $('.expense').each(function() {
					this.checked = false;
				});
            }
            else{
                $('#type_product_category').hide();
            }
        });

        $('#company_pnl_item_type_tree_category' ).change(function(){
            if($(this).prop("checked")){
                $('#type_tree_category').show();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item <span class="text-danger">*</span>';
                $('#type_product_category').hide();
                $('#type_expenses').hide();

                if($('#tree_category').val()){
                    $('#tree_category').val('');
                    show_switch_extra_new_tree();
                    if($('input[name="is_extra_new_tree"]').prop("checked")){
                        $('input[name="is_extra_new_tree"]').prop("checked", false);
                    }
                }

                $('.expense').each(function() {
					this.checked = false;
				});
            }
            else{
                $('#type_tree_category').hide();
                $('.product_category_status')[0].innerHTML = 'Profit & Loss Sub Item';
            }

        });

        $("#tree_category").change(function(){
            show_switch_extra_new_tree();
        });

        function show_switch_extra_new_tree(){
            if($('#tree_category').val()){
                $('#is_extra_new_tree').show();
                $('#is_extra_new_tree').addClass('d-flex');
            }else{
                $('#is_extra_new_tree').hide();
                $('#is_extra_new_tree').removeClass('d-flex');
            }
        }
    </script>
@endsection


