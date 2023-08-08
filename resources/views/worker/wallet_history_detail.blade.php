<div class="card">
    <h3 class="card-title mt-4 ml-4">
        <span class="text-dark">Wallet History - {{ @$worker->worker_name }}</span>
    </h3>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th style="text-align:center;">Adjustment</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($records->isNotEmpty())
                        @foreach ($records as $key => $record)
                            <tr>
                                <td style="width:5%; align:center;"> {{ ++$key }}</td>
                                <td> {{ @$record->worker_wallet_history_created }}</td>
                                <td style="width:22%"> {{ @$record->worker_wallet_history_action }} </td>
                                <td>{{ @$record->worker_wallet_history_remark }}</td>
                                <td>
                                    RM{{ number_format(@$record->worker_wallet_history_after, 2) }}
                                </td>
                                @php

                                    if (@$record->worker_wallet_history_before == 0) {
                                        $amount_before = 0;
                                    } else {
                                        $amount_before = @$record->worker_wallet_history_before;
                                    }

                                    $total = @$record->worker_wallet_history_after - $amount_before;
                                @endphp
                                @if ($total > 0)
                                    <td style="width:20%; color:green; text-align:center;"> + {{ $total }} </td>
                                @else
                                    <td style="width:20%; color:red; text-align:center;"> {{ $total }} </td>
                                @endif

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">
                                No Record!
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
