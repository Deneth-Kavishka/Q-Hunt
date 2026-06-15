<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // For a single event MVP, fetch all teams ordered by score.
        // In a real app, you'd filter by event_id.
        $teams = Team::with(['leader', 'progress.currentStage'])
                     ->orderBy('total_score', 'desc')
                     ->get();
                     
        return view('leaderboard', compact('teams'));
    }

    public function partial()
    {
        $teams = Team::with(['leader', 'progress.currentStage'])
                     ->orderBy('total_score', 'desc')
                     ->get();
                     
        return view('participant.leaderboard_partial', compact('teams'));
    }
}
