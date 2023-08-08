<div class="card">
    <h3 class="card-title mt-4 ml-4">
        <span class="text-dark">Credit History- {{ $customer->customer_name }}</span>
    </h3>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-nowrap" border="1">
                <thead class="thead-light">
                    @php
                        $count = 0;
                    @endphp
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Remark</th>
                        <th>Amount</th>
                        <th style="text-align:center;">Adjustment</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($records->isNotEmpty())
                        @foreach ($records as $record)
                            <tr>
                                <td style="width:5%; align:center;"> {{ ++$count }}</td>
                                <td> {{ date_format($record->customer_credit_history_created, 'Y-m-d H:i A') }}</td>
                                <td style="width:15% align:center;"> {{ $record->customer_credit_history_action }} </td>
                                <td>
                                    {{ $record->customer_credit_history_description }}
                                </td>
                                <td>{{ $record->customer_credit_history_remark }}</td>
                                <td>
                                    RM{{ number_format($record->customer_credit_history_value_after, 2) }}
                                </td>
                                @php

                                    if ($record->customer_credit_history_value_before == 0) {
                                        $amount_before = 0;
                                    } else {
                                        $amount_before = $record->customer_credit_history_value_before;
                                    }

                                    $total = $record->customer_credit_history_value_after - $amount_before;
                                @endphp
                                @if ($total > 0)
                                    <td style="width:20%; color:green; text-align:center;"> +{{ $total }} </td>
                                @else
                                    <td style="width:20%; color:red; text-align:center;"> {{ $total }} </td>
                                @endif

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
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
