@if (auth()->user()->user_type_id == 1)
    <div class="col-md-3">
        <div class="form-group">
            <label for="">Company: </label>
            {!! Form::select('company_id', $companySel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
        </div>
    </div>
@endif

<div class="col-md-3">
    <div class="form-group">
        <label for="">Land</label>
        <select name="company_land_id" id="company_land_id" class="form-control">
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="">Product Category</label>
        {!! Form::select('product_category_id', $productCategorySel, @$search['product_category_id'], ['class' => 'form-control', 'id' => 'product_category_id']) !!}
    </div>
</div>
<div class="col-md-3" id="show_product">
    <div class="form-group">
        <label for="">Product</label>
        <select name="product_id" id="product_id" class="form-control">
            @if (@$search['product_id'])
                <option></option>
            @endif
        </select>
    </div>
</div>

<div class="col-md-3" id="show_size">
    <div class="form-group">
        <label for="">Grade</label>
        <select name="product_size_id" id="product_size_id" class="form-control">
            @if (@$search['product_size_id'])
            <option></option>
        @endif
        </select>
    </div>
</div>

<div class="col-md-3 customer">
    <div class="form-group">
        <label for="">Customer</label>
        {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!}
    </div>
</div>
