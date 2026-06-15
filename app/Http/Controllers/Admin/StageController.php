<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StageController extends Controller
{
    public function index(Event $event)
    {
        $stages = $event->stages()->withCount('questions')->orderBy('order')->get();
        return view('admin.stages.index', compact('event', 'stages'));
    }

    public function create(Event $event)
    {
        $nextOrder = $event->stages()->max('order') + 1;
        if (!$nextOrder) $nextOrder = 1;
        
        return view('admin.stages.create', compact('event', 'nextOrder'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_clue' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'order' => 'required|integer|min:1',
            'points' => 'required|integer|min:0'
        ]);

        $validated['event_id'] = $event->id;
        // Generate a unique, unguessable identifier for the QR code
        $validated['qr_code_identifier'] = Str::random(32);

        $event->stages()->create($validated);

        return redirect()->route('admin.events.stages.index', $event)
                         ->with('success', 'Stage created successfully. A unique QR code has been generated.');
    }

    public function edit(Stage $stage)
    {
        return view('admin.stages.edit', compact('stage'));
    }

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_clue' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'order' => 'required|integer|min:1',
            'points' => 'required|integer|min:0'
        ]);

        $stage->update($validated);

        return redirect()->route('admin.events.stages.index', $stage->event_id)
                         ->with('success', 'Stage updated successfully.');
    }

    public function destroy(Stage $stage)
    {
        $eventId = $stage->event_id;
        $stage->delete();
        return redirect()->route('admin.events.stages.index', $eventId)
                         ->with('success', 'Stage deleted successfully.');
    }
}
