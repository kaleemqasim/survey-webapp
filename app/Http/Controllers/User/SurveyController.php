<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Carbon\Carbon;
class SurveyController extends Controller
{
    public function index() {
        $today = Carbon::today();
        $userId = auth()->id();


        // $surveys = Survey::where('start_date', '<=', $today)
        // ->where('end_date', '>=', $today)
        // ->leftJoin('survey_responses', function($join) use ($userId) {
        //     $join->on('surveys.id', '=', 'survey_responses.survey_id')
        //          ->where('survey_responses.user_id', '=', $userId);
        // })
        // ->whereNull('survey_responses.id')
        // ->withCount('questions')
        // ->select('surveys.*')
        // ->get();

        $surveys = Survey::where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->whereDoesntHave('responses', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        })
                        ->withCount('questions')
                        ->get();

        return view('user.available_surveys', compact('surveys'));
    }

    public function take_survey($id) {
        $survey = Survey::with('questions.options')->findOrFail($id);

        return view('user.take_survey', compact('survey'));
    }

    public function submit_survey(Request $request, $id) {
        $survey = Survey::findOrFail($id);
        
        // Save the survey responses
        foreach ($request->answers as $questionId => $optionId) {
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'question_id' => $questionId,
                'option_id' => $optionId,
                'user_id' => auth()->id(),
            ]);
        }
    
        // Add the survey reward to the user's balance
        $user = auth()->user();
        $user->balance += $survey->reward; // Increment balance by the survey reward
        $user->save();
    
        return redirect()->route('user.available_surveys')->with('success', 'Survey completed successfully. Reward added to your balance.');
    }
    

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'reward' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'questions.*.question_text' => 'required|string',
            'questions.*.options.*' => 'required|string',
        ]);

        $survey = Survey::create([
            'title' => $request->title,
            'image' => $request->image ? basename($request->file('image')->store('public')) : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reward' => $request->reward,
        ]);

        foreach ($request->questions as $questionData) {
            $question = $survey->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['options'] as $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                ]);
            }
        }

        return redirect()->route('admin.surveys.index')->with('success', 'Survey created successfully.');
    }

    public function edit(Survey $survey) {
        return view('admin.edit_survey', compact('survey'));
    }

    public function update(Request $request, Survey $survey) {
        $request->validate([
            'title' => 'required',
            'reward' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $survey->update([
            'title' => $request->title,
            'reward' => $request->reward,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'image' => $request->image ? $request->file('image')->store('surveys') : $survey->image,
        ]);

        $survey->questions()->delete();

        foreach ($request->questions as $questionData) {
            $question = $survey->questions()->create([
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['options'] as $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                ]);
            }
        }

        return redirect()->route('admin.list_surveys')->with('success', 'Survey updated successfully.');
    }

    public function destroy(Survey $survey) {
        $survey->delete();
        return redirect()->route('admin.surveys.index')->with('success', 'Survey deleted successfully.');
    }

}