@extends('layouts.master');
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Users</h4>
                <div class="w-auto">
                    <form action="{{ route('admin.withdrawals.withdrawal_requests') }}" method="GET">
                        <select class="form-control" name="filter" id="filter" onchange="this.form.submit()"
                            style="width: auto;">
                            <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $filter == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $filter == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">


                    <table id="datatable" class="table tablesorter">
                        <thead class="text-primary">
                            <tr>
                                <th>User Name</th>
                                <th>Amount</th>
                                <th>Requested At</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Bank Details</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->user->name }}</td>
                                <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                <td>{{ $withdrawal->created_at->format('F j, Y, g:i a') }}</td>
                                <td>{{ $withdrawal->user->contact_number }}</td>
                                <td>{{ $withdrawal->user->email }}</td>
                                <td>
                                    <span
                                        class="badge {{ $withdrawal->status == 'pending' ? 'badge-warning' : ($withdrawal->status == 'approved' ? 'badge-success' : 'badge-danger') }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>

                                <td>
                                    <a href="#" onclick="showBankDetails({{ $withdrawal->user_id }})">view</a>
                                </td>
                                <td>
                                    @if($withdrawal->status == 'pending')
                                    <form action="{{ route('admin.withdrawals.update', $withdrawal->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="status" value="approved"
                                            class="btn btn-success btn-sm">Approve</button>
                                        <button type="submit" name="status" value="rejected"
                                            class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="bankDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bank Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="bankDetailsContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showBankDetails(userId) {
    // Fetch the bank details for the user (you can implement an AJAX call if needed)
    // For now, I'm assuming the bank details are accessible from the user object
    // You can also store bank details in the withdrawal request if needed

    $.ajax({
        url: '/admin/withdrawals/' + userId + '/bank-details',
        method: 'GET',
        success: function(data) {
            $('#bankDetailsContent').html(data);
            $('#bankDetailsModal').modal('show');
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 1;

    $('#datatable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }

    });

    document.getElementById('add-question').addEventListener('click', function() {
        let questionHTML = `
            <div class="question mb-4">
                <label for="question_text">Question ${questionCount + 1}</label>
                <input type="text" name="questions[${questionCount}][question_text]" class="form-control" placeholder="Enter question" required>
                <h5>Options</h5>
                <div class="options-container">
                    <input type="text" name="questions[${questionCount}][options][]" class="form-control mb-2" placeholder="Enter option" required>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary add-option">Add Option</button>
            </div>
        `;

        document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHTML);
        questionCount++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-option')) {
            let optionsContainer = e.target.previousElementSibling;
            let optionHTML =
                `<input type="text" name="${optionsContainer.querySelector('input').name}" class="form-control mb-2" placeholder="Enter option" required>`;
            optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
        }
    });
});
</script>
@endpush