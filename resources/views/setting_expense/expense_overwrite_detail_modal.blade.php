<!-- modal -->
<div class="modal-content">
    <div class="modal-header">
        <div class="modal-title">
            <h5>{{$company}} | Overwrite Details</h5>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body pb-0">

        <div class="row p-1">
            @foreach($overwrite_detail as $overwrite_details)
                <div class="justify-content-between d-flex mb-2 w-100">
                    <div>
                        <b>{{ json_decode($overwrite_details->overwrite_expense->setting_expense_name)->en }}</b><br>
                        <span style="font-style: italic">
                            {{ json_decode($overwrite_details->overwrite_expense->expense_category->setting_expense_category_name)->en }}
                        </span>
                    </div>
                    <div class="text-right">
                        <b>Edited By: {{ $overwrite_details->overwrite_user->user_fullname }}</b><br>
                        <span style="font-style: italic">
                            {{ $overwrite_details->setting_expense_overwrite_updated }}
                        </span>
                    </div>
                </div>

                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th class="text-center" scope="col">Setting Expenses</th>
                        <th class="text-center" scope="col">Overwrite Expenses</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Expense Type</th>
                            <td class="text-center">{{ $overwrite_details->overwrite_expense->setting_expense_type->setting_expense_type_name }}</td>
                            <td class="text-center">{{ $overwrite_details->overwrite_expense_type->setting_expense_type_name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Expense Value</th>
                            <td class="text-center">RM {{$overwrite_details->overwrite_expense->setting_expense_value }}</td>
                            <td class="text-center">RM {{$overwrite_details->setting_expense_overwrite_value}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Expense Subcon</th>
                            <td class="text-center">
                                @if($overwrite_details->overwrite_expense->is_subcon_allow == 1)
                                    Allow Subcon:
                                    <div>RM {{$overwrite_details->overwrite_expense->setting_expense_subcon }} </div>
                                @else
                                    No Subcon Allow
                                @endif
                            </td>
                            <td class="text-center">
                                @if($overwrite_details->is_subcon_allow == 1)
                                    Allow Subcon:
                                    <div>RM {{$overwrite_details->setting_expense_overwrite_subcon}}</div>
                                @else
                                    No Subcon Allow
                                @endif
                            </td>
                        </tr>
                        @if($overwrite_details->is_extra_commission == 1)
                        <tr>
                            <th scope="row">Expense Commission</th>
                            <td class="text-center">-</td>
                            <td class="text-center">RM {{$overwrite_details->setting_expense_overwrite_commission ?? '-'}}</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            @endforeach
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
