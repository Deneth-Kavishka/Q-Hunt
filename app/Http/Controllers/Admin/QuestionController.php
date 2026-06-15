<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Stage $stage)
    {
        $question = $stage->questions()->first();
        return view('admin.questions.create', compact('stage', 'question'));
    }

    public function store(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:text,mcq,riddle,math,image',
            'content' => 'required|string',
            'correct_answer' => 'required|string',
            'hint' => 'nullable|string',
            'time_penalty_seconds' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'max_attempts' => 'required|integer|min:1',
        ]);

        if ($validated['type'] !== 'mcq') {
            $validated['options'] = null;
        } else {
            if (!empty($validated['options'])) {
                $validated['options'] = array_values(array_filter($validated['options']));
            }
        }

        $question = $stage->questions()->first();
        
        if ($question) {
            $question->update($validated);
            $msg = 'Stage Puzzle updated successfully.';
        } else {
            $stage->questions()->create($validated);
            $msg = 'Stage Puzzle added successfully.';
        }

        return redirect()->route('admin.events.stages.index', $stage->event_id)->with('success', $msg);
    }
}
