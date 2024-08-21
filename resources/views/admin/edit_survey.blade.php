@extends('layouts.master');
@section('content')
<div class="row">
    <div class="container mt-5">
        <h2>Edit Survey</h2>
        <form action="{{ route('surveys.update', $survey->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Survey Title</label>
                <input type="text" name="title" class="form-control" id="title"
                    value="{{ old('title', $survey->title) }}" required>
            </div>
            <div class="form-group">
                <label for="image">Survey Image</label>
                @if($survey->image)
                <div>
                    <img src="{{ asset('storage/' . $survey->image) }}" alt="Survey Image" class="img-thumbnail mb-2"
                        width="150">
                </div>
                @endif
                <input type="file" name="image" class="form-control-file" id="image">
            </div>
            <div class="form-group">
                <label for="reward">Reward (in dollars)</label>
                <input type="number" name="reward" class="form-control" id="reward"
                    value="{{ old('reward', $survey->reward) }}" step="0.01" required>
            </div>

            <h4>Questions</h4>
            <div id="questions-container">
                @foreach($survey->questions as $index => $question)
                <div class="question mb-4">
                    <label for="question_text">Question {{ $index + 1 }}</label>
                    <input type="text" name="questions[{{ $index }}][question_text]" class="form-control"
                        value="{{ old('questions.'.$index.'.question_text', $question->question_text) }}" required>
                    <h5>Options</h5>
                    <div class="options-container">
                        @foreach($question->options as $optionIndex => $option)
                        <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]"
                            class="form-control mb-2"
                            value="{{ old('questions.'.$index.'.options.'.$optionIndex, $option->option_text) }}"
                            required>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-option">Add Option</button>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-question" class="btn btn-outline-secondary mt-3">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Update Survey</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = {
        {
            $survey - > questions - > count()
        }
    };

    document.getElementById('add-question').addEventListener('click', function() {
        let questionHTML = `
            <div class="question mb-4">
                <label for="question_text">Question ${questionCount + 1}</label>
                <input type="text" name="questions[${questionCount}][question_text]" class="form-control" placeholder="Enter question" required>
                <h5>Options</h5>
                <div class="options-container">
                    <input type="text" name="questions[${questionCount}][options][0]" class="form-control mb-2" placeholder="Enter option" required>
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
            let optionCount = optionsContainer.querySelectorAll('input').length;
            let optionHTML =
                `<input type="text" name="${optionsContainer.querySelector('input').name.replace(/\d+$/, optionCount)}" class="form-control mb-2" placeholder="Enter option" required>`;
            optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
        }
    });
});
</script>
@endpush