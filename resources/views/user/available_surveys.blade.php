@extends('layouts.master');
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title"> Available Surveys </h4>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table tablesorter">
                        <thead class="text-primary">
                            <tr>
                                <th>Title</th>
                                <th>Reward</th>
                                <th>Number of Questions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $survey)
                            <tr>
                                <td>{{ $survey->title }}</td>
                                <td>${{ number_format($survey->reward, 2) }}</td>
                                <td>{{ $survey->questions_count }}</td>
                                <td>
                                    <a href="{{ route('user.take_survey', $survey->id) }}"
                                        class="btn btn-sm btn-primary">Take Survey</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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