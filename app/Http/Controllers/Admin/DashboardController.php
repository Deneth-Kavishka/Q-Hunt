<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $totalTeams = Team::count();
        $activeEvents = Event::where('is_active', true)->count();
        
        $activityDates = collect();
        $activityCounts = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = \App\Models\Progress::whereDate('completed_at', $date)->count();
            $activityDates->push(now()->subDays($i)->format('M d'));
            $activityCounts->push($count);
        }
        
        return view('admin.dashboard', compact('totalEvents', 'totalTeams', 'activeEvents', 'activityDates', 'activityCounts'));
    }
}
