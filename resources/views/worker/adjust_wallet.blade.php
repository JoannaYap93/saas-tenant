@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.min.css') }}">
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Wallet {{ $title }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Wallet {{ $title }}</a>
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
                        <b>{{ @$worker->worker_name }}</b><br>
                        <i>{{ @$worker->company->company_name ?? @$worker->company_name }}</i>
                        <br/><i>{{ @$worker->worker_mobile }}</i>
                    </div>
                    <div class="card-body border-top">
                            Current Balance
                            <h4 class="">RM
                                <span id="worker_wallet">{{ @$worker->worker_wallet_amount > 0 ? number_format($worker->worker_wallet_amount, 2) : number_format(0, 2) }}</span>
                            </h4>
                    </div>
                    <div class="card-body border-top">
                        <h4 class="card-title mb-4">Wallet Details</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Wallet Action<span class="text-danger">*</span></label>
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="add" name="worker_wallet_history_action"
                                            class="custom-control-input action" value="add" {{ @$worker->worker_wallet_history_action == 'add' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="add">
                                            Add
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="deduct" name="worker_wallet_history_action"
                                            class="custom-control-input action" value="deduct" {{ @$worker->worker_wallet_history_action == 'deduct' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="deduct">
                                            Deduct
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Adjust Amount<span class="text-danger">*</span></label>
                                        <input type="number" min="0" step="any" id="wallet"
                                            name="wallet" value="{{ @$worker->wallet }}" class="form-control input-mask text-left"
                                            placeholder="0" @if (!@$worker->wallet) disabled @endif>
                                        <span id="wallet_balance_after"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="worker_wallet_description">Remark<span class="text-danger">*</span></label>
                                    <textarea id="textarea" class="form-control" name="worker_wallet_description" rows="10" maxlength="255">{{ @$worker->worker_wallet_description }}</textarea>
                                </div>
                            </div>
                            <div class="card-body border-top col-sm-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('worker_listing') }}" class="btn btn-secondary">Cancel</a>
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
            var adjust_wallet = $('#wallet').val();
            var worker_id = '{{ @$worker->worker_id }}'

            if (adjust_wallet > 0) {
                recalculate();
            }

            $('input[name=wallet]').on('keyup clickup change', function() {
                recalculate();
            });

            $('input[name=worker_wallet_history_action]').on('change', function() {
                $('#wallet').removeAttr('disabled');
                recalculate();
            });

            function recalculate() {
                var wallet = parseFloat($('input[name=wallet]').val());
                var action = $('input[name=worker_wallet_history_action]:checked').val();
                var current_wallet = parseFloat($('#worker_wallet').text());
                var wallet_balance_after;
                if (wallet) {
                    if (action == 'add') {
                        wallet_balance_after = current_wallet + wallet;
                    } else {
                        wallet_balance_after = current_wallet - wallet;
                    }
                    // console.log(wallet_balance_after);
                    $wallet_str = 'RM ';
                    if (wallet_balance_after >= 0) {
                        $('#wallet_balance_after').html('Balance after ' + action + ': ' + $wallet_str +
                            wallet_balance_after);
                    } else {
                        $('#wallet_balance_after').html('<span class="text-danger">Balance after ' + action + ': ' +
                            $wallet_str + wallet_balance_after +
                            '</span><div class="font-size-10 font-weight-bold text-danger">The Wallet Amount have reached the transfer limit.</div>'
                        );
                    }
                } else {
                    $('#wallet_balance_after').html('Balance after ' + action + ': ' + current_wallet);
                }
            }
        });
    </script>
@endsection
