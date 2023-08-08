<div class="card">
    <h3 class="card-title mt-4 ml-4">
        <span class="text-dark">Claim Log Detail</span>
    </h3>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-nowrap">
                <thead class="thead-light">
                    @php
                        $count = 0;
                    @endphp
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($records->isNotEmpty())
                        @foreach ($records as $key => $record)
                            <tr>
                                <td>{{ date_format($record->claim_log_created, 'Y-m-d H:i A') }}</td>
                                <td>{{ $record->claim_log_action }}</td>
                                <td>{{ $record->claim_log_description }}</td>
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
