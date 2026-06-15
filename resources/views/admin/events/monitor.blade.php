@extends('layouts.admin')

@section('header', 'Monitor Hunt: ' . $event->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.events.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Events
    </a>
    <div class="flex flex-wrap items-center gap-3">
        @if($event->is_active && !$event->is_published)
            <form action="{{ route('admin.events.push_leaderboard', $event) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600/30 text-blue-400 hover:bg-blue-600 hover:text-white text-sm font-medium rounded-lg border border-blue-500/30 transition flex items-center shadow-lg" title="Push Live Leaderboard">
                    <i class="fa-solid fa-satellite-dish mr-2"></i> Push Live
                </button>
            </form>
            <form action="{{ route('admin.events.publish', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will end the hunt and show the podium to all teams.');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-amber-600/30 text-amber-400 hover:bg-amber-600 hover:text-white text-sm font-medium rounded-lg border border-amber-500/30 transition flex items-center shadow-lg" title="Finalize Hunt">
                    <i class="fa-solid fa-flag-checkered mr-2"></i> Finalize
                </button>
            </form>
        @elseif($event->is_published)
            <span class="px-4 py-2 text-sm text-amber-400 font-bold bg-amber-400/10 border border-amber-400/20 rounded-lg flex items-center">
                <i class="fa-solid fa-flag-checkered mr-2"></i> Hunt Finalized
            </span>
        @endif
        <button onclick="window.location.reload()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/30 transition flex items-center">
            <i class="fa-solid fa-rotate-right mr-2"></i> Refresh
        </button>
    </div>
</div>

<div class="space-y-6">
    @forelse($teams as $team)
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex justify-between items-start border-b border-white/10 pb-4 mb-4 relative z-10">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $team->name }}</h3>
                    <p class="text-sm text-gray-400">Leader: {{ $team->leader->name }}</p>
                    
                    @if($team->progress->where('is_revoked', false)->count() > 0)
                    <form action="{{ route('admin.events.revoke_all_points', [$event, $team]) }}" method="POST" class="mt-2" onsubmit="return confirm('WARNING: Are you sure you want to completely wipe ALL earned points for this team? This action penalizes their entire hunt history.');">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg shadow-red-500/30 transition flex items-center">
                            <i class="fa-solid fa-skull-crossbones mr-2"></i> Revoke ALL Points
                        </button>
                    </form>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-3xl font-black text-emerald-400">{{ $team->total_score }}</div>
                    <p class="text-xs text-gray-500 uppercase tracking-widest">Total Points</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-300 mb-3 uppercase tracking-wider">Completed Stages</h4>
                @if($team->progress->isEmpty())
                    <p class="text-gray-500 italic text-sm">No stages completed yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($team->progress as $progress)
                            <div class="flex items-center justify-between p-3 rounded-lg border {{ $progress->is_revoked ? 'bg-red-500/10 border-red-500/20' : 'bg-black/20 border-white/5' }}">
                                <div>
                                    <p class="font-medium {{ $progress->is_revoked ? 'text-red-400 line-through' : 'text-gray-200' }}">Stage {{ $progress->currentStage->order }}: {{ $progress->currentStage->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        Completed: {{ $progress->completed_at->format('H:i:s') }} 
                                        &bull; Earned: <span class="{{ $progress->is_revoked ? 'text-red-400' : 'text-emerald-400' }}">{{ $progress->earned_points }} pts</span>
                                    </p>
                                </div>
                                
                                @if(!$progress->is_revoked)
                                    <form action="{{ route('admin.events.revoke_points', [$event, $team, $progress]) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke points for this stage? This will deduct the earned points from their total score.');">
                                        @csrf
                                        <button type="submit" class="text-xs px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white rounded border border-red-500/30 transition">
                                            <i class="fa-solid fa-ban mr-1"></i> Revoke Points
                                        </button>
                                    </form>
                                @elseif($progress->is_revoked)
                                    <span class="text-xs font-bold text-red-500 px-2 py-1 bg-red-500/10 rounded uppercase tracking-wider">Revoked</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center p-12 bg-white/5 border border-white/10 rounded-2xl">
            <p class="text-gray-400 text-lg">No teams found for this event.</p>
        </div>
    @endforelse
</div>
@endsection
