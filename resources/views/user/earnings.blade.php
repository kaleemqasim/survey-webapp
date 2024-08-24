@extends('layouts.master');
@section('content')
<style>
.timeline {
    position: relative;
    margin-top: 30px;
    padding: 10px 0;
}

.timeline-item {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
}

.timeline-item .text-muted {
    font-size: 0.85rem;
}

.timeline-item i {
    font-size: 1.2rem;
}

.timeline-item .text-success,
.timeline-item .text-danger {
    font-weight: 600;
}

/* .timeline-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
} */

.timeline-content {
    margin-left: 20px;
    padding: 10px;
    background-color: #f7f7f7;
    border-radius: 5px;
    width: 100%;
}

.timeline-title {
    color: black;
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.timeline-date {
    margin: 5px 0;
    color: #999;
}

.timeline-amount {
    font-size: 16px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3>Total Earnings: $187.00</h3>
                    <h4>Current Balance: $0.00</h4>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary mt-3" data-toggle="modal"
                        data-target="#withdrawModal">Withdraw</button>
                </div>
            </div>

            <div class="timeline">
                @foreach ($transactions as $transaction)
                <div class="timeline-item d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i
                            class="{{ $transaction['type'] == 'earning' ? 'tim-icons icon-coins text-success' : 'tim-icons icon-wallet-43 text-danger' }} mr-2"></i>
                        <div>
                            <span class="{{ $transaction['type'] == 'earning' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction['title'] }}
                            </span>
                            <small class="d-block text-muted">
                                {{ \Carbon\Carbon::parse($transaction['date'])->format('F j, Y, g:i a') }}
                            </small>
                        </div>
                    </div>
                    <div class="{{ $transaction['type'] == 'earning' ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($transaction['amount'], 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Withdraw Modal -->
            <div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="withdrawModalLabel">Confirm Withdrawal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to withdraw
                                <strong>${{ number_format($currentBalance, 2) }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form method="POST" action="{{ route('user.withdraw') }}">
                                @csrf
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
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