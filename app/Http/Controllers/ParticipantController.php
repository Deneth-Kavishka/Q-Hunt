<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        }

        $team = $user->teams()->first();

        if (!$team) {
            return view('participant.no_team');
        }

        $progressList = $team->progress()->with('currentStage')->orderBy('id', 'asc')->get();
        $progress = $progressList->last();
        $currentScore = $team->total_score;

        $history = [];
        $previousTime = $team->event->start_time;

        foreach ($progressList as $prog) {
            $startTime = $prog->currentStage->start_time ?: $previousTime;
            if (!$startTime) {
                $startTime = $prog->completed_at;
            }

            $totalSeconds = abs($startTime->diffInSeconds($prog->completed_at, false));
            $minutes = floor($totalSeconds / 60);
            $seconds = $totalSeconds % 60;
            $durationStr = $minutes . 'm ' . str_pad($seconds, 2, '0', STR_PAD_LEFT) . 's';

            $isOverdue = false;
            if ($prog->currentStage->end_time && $prog->completed_at > $prog->currentStage->end_time) {
                $isOverdue = true;
            }

            $history[] = [
                'stage_name' => $prog->currentStage->name,
                'start_time' => $startTime,
                'finish_time' => $prog->completed_at,
                'duration_string' => $durationStr,
                'is_overdue' => $isOverdue,
                'earned_points' => $prog->earned_points,
                'is_revoked' => $prog->is_revoked
            ];
            $previousTime = $prog->completed_at;
        }

        if ($progress) {
            $nextStage = \App\Models\Stage::where('event_id', $team->event_id)
                                          ->where('order', '>', $progress->currentStage->order)
                                          ->orderBy('order', 'asc')
                                          ->first();
        } else {
            $nextStage = \App\Models\Stage::where('event_id', $team->event_id)
                                          ->orderBy('order', 'asc')
                                          ->first();
        }

        $isHuntCompleted = false;
        if ($progress && $nextStage === null && \App\Models\Stage::where('event_id', $team->event_id)->exists()) {
            $isHuntCompleted = true;
        }
        
        $nextStageClue = $nextStage ? $nextStage->location_clue : null;

        return view('participant.dashboard', compact('team', 'progress', 'currentScore', 'nextStageClue', 'isHuntCompleted', 'nextStage', 'history'));
    }

    public function eventStatus()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $team = $user->teams()->first();
        if (!$team) return response()->json(['error' => 'No team'], 400);

        $event = $team->event;
        return response()->json([
            'is_published' => (bool)$event->is_published,
            'push_leaderboard_at' => $event->push_leaderboard_at ? $event->push_leaderboard_at->timestamp : null,
        ]);
    }

    public function podium()
    {
        $user = Auth::user();
        $team = $user->teams()->first();
        if (!$team) return redirect()->route('dashboard');

        $event = $team->event;
        if (!$event->is_published) {
            return redirect()->route('dashboard');
        }

        $topTeams = \App\Models\Team::where('event_id', $event->id)
            ->with('leader')
            ->orderBy('total_score', 'desc')
            ->take(3)
            ->get();

        return view('participant.podium', compact('topTeams', 'event', 'team'));
    }
}
