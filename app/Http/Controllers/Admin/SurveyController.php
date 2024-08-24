<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
class SurveyController extends Controller
{
    public function index() {
        $surveys = Survey::withCount('questions')->get();
        return view('admin.list_surveys', compact('surveys'));
    }

    public function create() {
        return  view('admin.add_survey');
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