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
        $survey = Survey::create([
            'title' => $request->title,
            'image' => $request->image ? basename($request->file('image')->store('public')) : null,
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

        return redirect()->route('surveys.index')->with('success', 'Survey created successfully.');
        // return redirect()->route('admin.add_survey')->with('success', 'Survey created successfully.');
    }

    public function edit(Survey $survey) {
        return view('admin.edit_survey', compact('survey'));
    }

    public function update(Request $request, Survey $survey) {
        $survey->update([
            'title' => $request->title,
            'reward' => $request->reward,
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
        return redirect()->route('surveys.index')->with('success', 'Survey deleted successfully.');
    }

}