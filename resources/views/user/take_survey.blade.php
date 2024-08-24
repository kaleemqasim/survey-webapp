@extends('layouts.master');
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ $survey->title }}</h3>
            </div>
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar" id="survey-progress" role="progressbar" style="width: 0%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <form id="survey-form" action="{{ route('user.submit_survey', $survey->id) }}" method="POST">
                    @csrf
                    <div id="questions-container">
                        @foreach($survey->questions as $index => $question)
                        <div class="question mt-4" data-question-index="{{ $index }}"
                            style="{{ $index === 0 ? '' : 'display:none;' }}">
                            <h5>{{ $question->question_text }}</h5>
                            @foreach($question->options as $option)
                            <div class="form-check">
                                <input style="margin-left: 0" class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                    value="{{ $option->id }}" id="option-{{ $option->id }}">
                                <label style="margin-left: 0" class="form-check-label" for="option-{{ $option->id }}">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                            @endforeach
                            <button type="button" class="btn btn-secondary prev-question mt-4"
                                style="{{ $index === 0 ? 'display:none;' : '' }}">Previous</button>
                            <button type="button"
                                class="btn btn-primary next-question mt-4">{{ $index === $survey->questions->count() - 1 ? 'Submit' : 'Next' }}</button>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestionIndex = 0;
    const totalQuestions = {{ $survey->questions->count() }};
    const questions = document.querySelectorAll('.question');

    function updateProgressBar() {
        const progressPercentage = (currentQuestionIndex / totalQuestions) * 100;
        document.getElementById('survey-progress').style.width = `${progressPercentage}%`;
    }

    document.querySelectorAll('.next-question').forEach(button => {
        button.addEventListener('click', function() {
            if (currentQuestionIndex < totalQuestions - 1) {
                questions[currentQuestionIndex].style.display = 'none';
                currentQuestionIndex++;
                questions[currentQuestionIndex].style.display = 'block';
                updateProgressBar();
            } else {
                document.getElementById('survey-form').submit();
            }
        });
    });

    document.querySelectorAll('.prev-question').forEach(button => {
        button.addEventListener('click', function() {
            if (currentQuestionIndex > 0) {
                questions[currentQuestionIndex].style.display = 'none';
                currentQuestionIndex--;
                questions[currentQuestionIndex].style.display = 'block';
                updateProgressBar();
            }
        });
    });

    // Initialize progress bar at 0%
    updateProgressBar();
});
</script>
@endpush