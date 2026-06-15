@extends('layouts.admin')

@section('header', 'Teams Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-lg font-medium text-white">All Teams</h2>
    <a href="{{ route('admin.teams.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-blue-500/30 transition">
        <i class="fa-solid fa-plus mr-2"></i> Register Team
    </a>
</div>

<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-white/10 bg-white/5">
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Team Name</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Event</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Leader</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Score</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse ($teams as $team)
            <tr class="hover:bg-white/5 transition-colors">
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-white">{{ $team->name }}</p>
                </td>
                <td class="py-4 px-6">
                    <span class="text-sm text-purple-300">{{ $team->event->name }}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-gray-500"></i>
                        <span class="text-sm text-gray-300">{{ $team->leader->name }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 text-sm font-bold text-emerald-400">{{ $team->total_score }}</td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="inline" onsubmit="return confirm('Delete this team? All progress will be lost!');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition" title="Delete Team">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-8 text-center text-gray-500">
                    No teams registered yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-4 border-t border-white/10">
        {{ $teams->links() }}
    </div>
</div>
@endsection
