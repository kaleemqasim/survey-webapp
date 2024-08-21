@extends('layouts.master');
@section('content')
<style>
    .custom-file-input {
        display: none; /* Hide the default file input */
    }

    .custom-file-label {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        /* width: 100%;
        text-align: center; */
        width: 20%;
    }

    .custom-file-label:hover {
        background-color: #0056b3;
    }
</style>
<div class="row">
    <div class="container mt-5">
        <h2>Create Survey</h2>
        <form action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Survey Title</label>
                        <input type="text" name="title" class="form-control" id="title" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="reward">Reward (in dollars)</label>
                        <input type="number" name="reward" class="form-control" id="reward" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="image">Survey Image</label>
                <div class="custom-file">
                    <input type="file" name="image" class="custom-file-input" id="image" accept="image/*" required>
                    <label class="custom-file-label" for="image">Choose image</label>
                </div>
                <div id="image-preview" class="mt-2" style="display: none;">
                    <img id="preview-image" src="#" alt="Survey Image Preview" class="img-thumbnail" width="150">
                </div>
            </div>
            

            <h4>Questions</h4>
            <div id="questions-container">
                <div class="question mb-4">
                    <label for="question_text">Question 1</label>
                    <input type="text" name="questions[0][question_text]" class="form-control" placeholder="Enter question" required>
                    <h5>Options</h5>
                    <div class="options-container">
                        <input type="text" name="questions[0][options][]" class="form-control mb-2" placeholder="Enter option" required>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-option">Add Option</button>
                </div>
            </div>
            <button type="button" id="add-question" class="btn btn-outline-secondary mt-3">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Create Survey</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 1;

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
            let optionHTML = `<input type="text" name="${optionsContainer.querySelector('input').name}" class="form-control mb-2" placeholder="Enter option" required>`;
            optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
        }
    });

    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        const imagePreviewContainer = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreviewContainer.style.display = 'block'; // Show the preview container
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#'; // Reset the preview
            imagePreviewContainer.style.display = 'none'; // Hide the preview container
        }
    });
});
</script>
@endpush