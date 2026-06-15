<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('stages', 'teams')->orderBy('start_time', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'completion_message' => 'nullable|string'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'boolean',
            'completion_message' => 'nullable|string'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function publishResults(Event $event)
    {
        $event->update(['is_published' => true]);
        return redirect()->back()->with('success', 'Event finalized! The podium is now live for all participants.');
    }

    public function pushLeaderboard(Event $event)
    {
        $event->update(['push_leaderboard_at' => now()]);
        return redirect()->back()->with('success', 'Live Leaderboard pushed! Participants will see it shortly.');
    }

    public function monitor(Event $event)
    {
        $teams = \App\Models\Team::where('event_id', $event->id)
            ->with(['leader', 'progress' => function($q) {
                $q->orderBy('id', 'desc')->with('currentStage');
            }])
            ->get();

        return view('admin.events.monitor', compact('event', 'teams'));
    }

    public function revokePoints(\Illuminate\Http\Request $request, Event $event, \App\Models\Team $team, \App\Models\Progress $progress)
    {
        if ($progress->team_id !== $team->id || $team->event_id !== $event->id) {
            abort(403);
        }

        if ($progress->is_revoked) {
            return back()->with('error', 'Points already revoked for this stage.');
        }

        $team->decrement('total_score', $progress->earned_points);
        
        $progress->update([
            'is_revoked' => true,
            'earned_points' => 0
        ]);

        return back()->with('success', 'Points revoked successfully for that stage.');
    }

    public function revokeAllPoints(\Illuminate\Http\Request $request, Event $event, \App\Models\Team $team)
    {
        if ($team->event_id !== $event->id) {
            abort(403);
        }

        // Find all unrevoked progress for this team
        $progresses = \App\Models\Progress::where('team_id', $team->id)
            ->where('is_revoked', false)
            ->get();

        $pointsToDeduct = $progresses->sum('earned_points');

        $team->decrement('total_score', $pointsToDeduct);

        // Ensure score doesn't drop below 0 due to anomalies
        if ($team->total_score < 0) {
            $team->update(['total_score' => 0]);
        }

        foreach ($progresses as $progress) {
            $progress->update([
                'is_revoked' => true,
                'earned_points' => 0
            ]);
        }

        return back()->with('success', 'All points have been completely revoked for this team.');
    }
}
