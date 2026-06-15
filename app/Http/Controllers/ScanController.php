<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function handle($identifier)
    {
        $stage = Stage::where('qr_code_identifier', $identifier)->firstOrFail();
        $user = Auth::user();
        $team = $user->teams()->first();

        if (!$team) {
            return redirect()->route('dashboard')->with('error', 'You must be in a team to play.');
        }

        // Determine the next expected stage order for this team
        $progress = Progress::where('team_id', $team->id)->orderBy('id', 'desc')->first();
        
        $expectedOrder = 1;
        if ($progress) {
            $expectedOrder = $progress->currentStage->order + 1;
        }

        if ($stage->order > $expectedOrder) {
            return view('participant.wrong_location', ['message' => 'You skipped a stage! Find the previous clue first.']);
        } elseif ($stage->order < $expectedOrder) {
            return view('participant.wrong_location', ['message' => 'You already completed this stage. Keep going!']);
        }

        // It's the correct stage! Show the puzzle.
        $question = $stage->questions()->first();
        
        // If no question is set for this stage, just pass them automatically
        if (!$question) {
            return $this->autoPassStage($team, $stage);
        }

        $attemptsCount = \App\Models\Attempt::where('team_id', $team->id)
            ->where('stage_id', $stage->id)
            ->count();

        return view('participant.puzzle', compact('stage', 'question', 'team', 'attemptsCount'));
    }

    public function submitAnswer(Request $request, Stage $stage)
    {
        $request->validate([
            'answer' => 'required|string',
            'question_id' => 'required|exists:questions,id'
        ]);

        $team = Auth::user()->teams()->first();
        $question = $stage->questions()->where('id', $request->question_id)->firstOrFail();

        $isCorrect = strtolower(trim($request->answer)) === strtolower(trim($question->correct_answer));

        \App\Models\Attempt::create([
            'team_id' => $team->id,
            'stage_id' => $stage->id,
            'answer_given' => $request->answer,
            'is_correct' => $isCorrect
        ]);

        $attemptsCount = \App\Models\Attempt::where('team_id', $team->id)
            ->where('stage_id', $stage->id)
            ->count();

        $team->submissions()->create([
            'question_id' => $question->id,
            'submitted_answer' => $request->answer,
            'is_correct' => $isCorrect
        ]);

        if ($isCorrect) {
            $earnedPoints = $this->calculatePoints($stage);
            Progress::create([
                'team_id' => $team->id,
                'current_stage_id' => $stage->id,
                'completed_at' => now(),
                'earned_points' => $earnedPoints,
            ]);
            $team->increment('total_score', $earnedPoints);

            return redirect()->route('stage.success')->with('success_points', $earnedPoints);
        } else {
            $maxAttempts = $question->max_attempts ?? 3;
            if ($attemptsCount >= $maxAttempts) {
                Progress::create([
                    'team_id' => $team->id,
                    'current_stage_id' => $stage->id,
                    'completed_at' => now(),
                    'earned_points' => 0,
                ]);
                return redirect()->route('stage.success')->with('failed_stage', true);
            }

            $remaining = $maxAttempts - $attemptsCount;
            return back()->with('error_attempts', $remaining);
        }
    }

    private function autoPassStage($team, $stage)
    {
        $earnedPoints = $this->calculatePoints($stage);
        Progress::create([
            'team_id' => $team->id,
            'current_stage_id' => $stage->id,
            'completed_at' => now(),
            'earned_points' => $earnedPoints,
        ]);
        $team->increment('total_score', $earnedPoints);
        return redirect()->route('stage.success');
    }

    private function calculatePoints($stage)
    {
        $now = now();
        $basePoints = $stage->points;
        $endTime = $stage->end_time;
        
        if (!$endTime) return $basePoints;
        
        if ($now < $endTime) {
            $bonusMinutes = $now->diffInMinutes($endTime);
            return $basePoints + $bonusMinutes;
        } else {
            $penaltyMinutes = $endTime->diffInMinutes($now);
            return max(0, $basePoints - $penaltyMinutes);
        }
    }

    public function success()
    {
        $team = Auth::user()->teams()->first();
        $progress = Progress::where('team_id', $team->id)->orderBy('id', 'desc')->first();
        
        $expectedOrder = 1;
        if ($progress) {
            $expectedOrder = $progress->currentStage->order + 1;
        }

        $nextStage = \App\Models\Stage::where('event_id', $team->event_id)
                                      ->where('order', $expectedOrder)
                                      ->first();

        $isHuntCompleted = $nextStage === null && \App\Models\Stage::where('event_id', $team->event_id)->exists();
        $nextStageClue = $nextStage ? $nextStage->location_clue : null;

        return view('participant.success', compact('team', 'nextStageClue', 'isHuntCompleted', 'nextStage'));
    }
}
