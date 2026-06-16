@extends('layouts.admin')

@section('header', 'Events Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-lg font-medium text-white">All Events</h2>
    <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/30 transition">
        <i class="fa-solid fa-plus mr-2"></i> Create Event
    </a>
</div>

<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-white/10 bg-white/5">
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Name</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Status</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Stages</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Teams</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Dates</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse ($events as $event)
            <tr class="hover:bg-white/5 transition-colors">
                <td class="py-4 px-6">
                    <p class="text-sm font-medium text-white">{{ $event->name }}</p>
                </td>
                <td class="py-4 px-6">
                    @if($event->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/20">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/20">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-sm text-gray-300">{{ $event->stages_count }}</td>
                <td class="py-4 px-6 text-sm text-gray-300">{{ $event->teams_count }}</td>
                <td class="py-4 px-6 text-sm text-gray-300">
                    <div>{{ $event->start_time->format('M d, Y H:i') }}</div>
                    <div class="text-gray-500 text-xs">to {{ $event->end_time->format('M d, Y H:i') }}</div>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-3 mb-2">
                        <a href="{{ route('admin.events.stages.index', $event) }}" class="text-gray-400 hover:text-blue-400 transition" title="Manage Stages">
                            <i class="fa-solid fa-list-check"></i>
                        </a>
                        <a href="{{ route('admin.events.edit', $event) }}" class="text-gray-400 hover:text-purple-400 transition" title="Edit Event">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition" title="Delete Event">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @if($event->is_active && !$event->is_published)
                    <div class="flex items-center justify-end gap-2 border-t border-white/5 pt-2 mt-2">
                        <form action="{{ route('admin.events.push_leaderboard', $event) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-2 py-1 bg-blue-600/30 text-blue-400 hover:bg-blue-600 hover:text-white rounded border border-blue-500/30 transition" title="Push Live Leaderboard">
                                <i class="fa-solid fa-satellite-dish"></i> Push
                            </button>
                        </form>
                        <form action="{{ route('admin.events.publish', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will end the hunt and show the podium to all teams.');">
                            @csrf
                            <button type="submit" class="text-xs px-2 py-1 bg-amber-600/30 text-amber-400 hover:bg-amber-600 hover:text-white rounded border border-amber-500/30 transition" title="Finalize Hunt">
                                <i class="fa-solid fa-flag-checkered"></i> Finalize
                            </button>
                        </form>
                        <a href="{{ route('admin.events.monitor', $event) }}" class="text-xs px-2 py-1 bg-purple-600/30 text-purple-400 hover:bg-purple-600 hover:text-white rounded border border-purple-500/30 transition" title="Monitor Hunt">
                            <i class="fa-solid fa-desktop"></i> Monitor
                        </a>
                        <a href="{{ route('leaderboard') }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-600/30 text-cyan-400 hover:bg-cyan-600 hover:text-white rounded border border-cyan-500/30 transition" title="View Live Leaderboard">
                            <i class="fa-solid fa-ranking-star"></i> Leaderboard
                        </a>
                    </div>
                    @elseif($event->is_published)
                    <div class="flex items-center justify-end gap-2 border-t border-white/5 pt-2 mt-2">
                        <span class="text-xs text-amber-400 font-bold bg-amber-400/10 px-2 py-1 rounded">
                            <i class="fa-solid fa-flag-checkered mr-1"></i> Finalized
                        </span>
                        <a href="{{ route('admin.events.monitor', $event) }}" class="text-xs px-2 py-1 bg-purple-600/30 text-purple-400 hover:bg-purple-600 hover:text-white rounded border border-purple-500/30 transition" title="View Event History">
                            <i class="fa-solid fa-clock-rotate-left mr-1"></i> History
                        </a>
                        <a href="{{ route('leaderboard') }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-600/30 text-cyan-400 hover:bg-cyan-600 hover:text-white rounded border border-cyan-500/30 transition" title="View Final Leaderboard">
                            <i class="fa-solid fa-ranking-star mr-1"></i> Leaderboard
                        </a>
                        <a href="{{ route('participant.podium') }}" target="_blank" class="text-xs px-2 py-1 bg-yellow-600/30 text-yellow-400 hover:bg-yellow-600 hover:text-white rounded border border-yellow-500/30 transition" title="View Winner Podium">
                            <i class="fa-solid fa-crown mr-1"></i> Podium
                        </a>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    No events found. Create your first treasure hunt!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-4 border-t border-white/10">
        {{ $events->links() }}
    </div>
</div>
@endsection
