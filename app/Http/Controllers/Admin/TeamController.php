<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['event', 'leader'])->withCount('members')->orderBy('id', 'desc')->paginate(10);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $events = Event::where('is_active', true)->get();
        $users = User::where('role_id', 2)->get(); // Participants only
        return view('admin.teams.create', compact('events', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'leader_user_id' => 'required|exists:users,id',
        ]);

        Team::create($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team created successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Team deleted successfully.');
    }
}
