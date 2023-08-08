@if (auth()->user()->company_id == 0)
<div class="col-md-3">
    <div class="form-group">
        <label for="">Company: </label>
        {!! Form::select('company_id', $companySel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
        {{-- <input type="hidden" id="company_search" name="company_search" value= ''> --}}
    </div>
</div>
@endif
<div class="col-md-3">
<label for="">Land: </label>
<select name="company_land_id" class="form-control" id="company_land_id">

</select>
{{-- {!! Form::select('company_land_id', $company_land_sel, @$search['company_land_id'], ['class' => 'form-control']) !!} --}}
</div>
@if('title' == 'do_daily_report')
<div class="col-md-3">
    <div class="form-group">
        <label for="">Order Type</label>
        {!! Form::select('order_type', $ordertype, @$search['order_type'], ['class' => 'form-control']) !!}
    </div>
</div>
@endif
<div class="col-md-3">
<label for="">User: </label>
<select name="user_id" id="user_id" class="form-control">

</select>
{{-- {!! Form::select('user_id', $user_sel, @$search['user_id'], ['class' => 'form-control']) !!} --}}
</div>

<div class="col-md-3" id="customer_category">
    <div class="form-group">
        <label for="">Customer Category</label>
        {!! Form::select('customer_category_id', $customercategorysel, @$search['customer_category_id'], ['class' => 'form-control', 'id' => 'customer_category_id']) !!}
        {{-- {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!} --}}
    </div>
</div>

<div class="col-md-3" id="customer">
    <div class="form-group">
        <label for="">Customer</label>
        <select name="customer_id" id="customer_id" class="form-control">
            @if (@$search['customer_id'])
            <option></option>
            @endif
        </select>
        {{-- {!! Form::select('customer_id', $customerSel, @$search['customer_id'], ['class' => 'form-control', 'id' => 'customer_id']) !!} --}}
    </div>
</div>
