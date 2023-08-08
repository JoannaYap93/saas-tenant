@extends('layouts.master')

@section('title')
    Credit Adjustment
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Credit Adjustment</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Credit Adjustment</a>
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
        <div class="col-sm-12">
            <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                            @if($customer->company)
                                <b>{{$customer->customer_company_name}}</b><br>
                            @endif
                            <i>{{ $customer->customer_name }}</i>
                        <br/><i>{{ $customer->customer_mobile_no }}</i><br>
                        @if ($customer->customer_email)
                            <i>{{ $customer->customer_email}}</i>
                            <br>
                        @endif
                        <i>{{ $customer->customer_category->customer_category_name }}</i><br><br>
                        <b>Account Info:</b><br>
                        @if($customer->customer_acc_name)
                            <i>{{ ucfirst($customer->customer_acc_name) }}</i><br>
                            <i>{{ $customer->customer_acc_mobile_no }}</i><br>
                        @endif
                    </div>
                    <div class="card-body border-top">
                            Current Balance
                            <h4 class="">RM
                                <span id="customer_credit">{{ @$customer->customer_credit > 0 ? number_format($customer->customer_credit, 2) : number_format(0, 2) }}</span>
                            </h4>
                    </div>
                    <div class="card-body border-top">
                        <h4 class="card-title mb-4">Credit Details</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Credit Action<span class="text-danger">*</span></label>
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="add" name="customer_credit_history_action"
                                            class="custom-control-input action" value="add" {{ @$post->customer_credit_history_action == 'add' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="add">
                                            Add
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="deduct" name="customer_credit_history_action"
                                            class="custom-control-input action" value="deduct" {{ @$post->customer_credit_history_action == 'deduct' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="deduct">
                                            Deduct
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Adjust Amount<span class="text-danger">*</span></label>
                                        <input type="number" min="0" step="any" id="credit"
                                            name="credit" value="{{ @$post->credit }}" class="form-control input-mask text-left"
                                            placeholder="0" @if (@$post->credit )

                                            @else
                                                disabled
                                            @endif>
                                        <span id="credit_balance_after"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="customer_credit_remark">Remark<span class="text-danger">*</span></label>
                                    <textarea id="textarea" class="form-control" name="customer_credit_remark" rows="10" maxlength="255">{{ @$post->customer_credit_remark }}</textarea>
                                </div>
                            </div>
                            <div class="card-body border-top col-sm-12">
                                <button type="submit" name="submit"
                                class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                            <a href="{{ $cancel }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/inputmask/inputmask.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-mask.init.js') }}"></script>

    <script>
        $(document).ready(function(e) {
            $('.select2_customer').select2({
                // minimumResultsForSearch: -1
            });

            var adjust_credit = $('#credit').val();
            var customer_id = '{{ @$customer->customer_id }}'

            if (adjust_credit > 0) {
                recalculate();
            }

            $('input[name=credit]').on('keyup clickup change', function() {
                recalculate();
            });

            $('input[name=customer_credit_history_action]').on('change', function() {
                $('#credit').removeAttr('disabled');
                recalculate();
            });

            function recalculate() {
                var credit = parseFloat($('input[name=credit]').val());
                var action = $('input[name=customer_credit_history_action]:checked').val();
                var current_credit = parseFloat($('#customer_credit').text());
                var credit_balance_after;
                if (credit) {
                    if (action == 'add') {
                        credit_balance_after = current_credit + credit;
                    } else {
                        credit_balance_after = current_credit - credit;
                    }
                    $credit_str = 'RM ';
                    if (credit_balance_after >= 0) {
                        $('#credit_balance_after').html('Balance after ' + action + ': ' + $credit_str +
                            credit_balance_after);
                    } else {
                        $('#credit_balance_after').html('<span class="text-danger">Balance after ' + action + ': ' +
                            $credit_str + credit_balance_after +
                            '</span><div class="font-size-10 font-weight-bold text-danger">The Credit Amount have reached the transfer limit.</div>'
                        );
                    }
                } else {
                    $('#credit_balance_after').html('Balance after ' + action + ': ' + current_credit);
                }
            }
        });
    </script>
@endsection
