<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 mb-2">Live Leaderboard</h1>
            <p class="text-gray-400">See who is leading the treasure hunt in real-time!</p>
        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 overflow-hidden shadow-2xl rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 border-b border-white/10">
                        <th class="py-5 px-6 text-sm font-bold text-gray-300 uppercase tracking-wider w-20">Rank</th>
                        <th class="py-5 px-6 text-sm font-bold text-gray-300 uppercase tracking-wider">Team Name</th>
                        <th class="py-5 px-6 text-sm font-bold text-gray-300 uppercase tracking-wider text-center">Score</th>
                        <th class="py-5 px-6 text-sm font-bold text-gray-300 uppercase tracking-wider text-right">Current Stage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($teams as $index => $team)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="py-5 px-6">
                            @if($index == 0)
                                <div class="w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center border border-yellow-500/50 shadow-[0_0_15px_rgba(234,179,8,0.5)]">
                                    <i class="fa-solid fa-trophy text-yellow-400"></i>
                                </div>
                            @elseif($index == 1)
                                <div class="w-10 h-10 rounded-full bg-gray-300/20 flex items-center justify-center border border-gray-300/50">
                                    <span class="text-gray-300 font-bold">2</span>
                                </div>
                            @elseif($index == 2)
                                <div class="w-10 h-10 rounded-full bg-orange-700/20 flex items-center justify-center border border-orange-700/50">
                                    <span class="text-orange-400 font-bold">3</span>
                                </div>
                            @else
                                <div class="w-10 h-10 flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">{{ $index + 1 }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            <h3 class="text-lg font-bold text-white group-hover:text-purple-400 transition">{{ $team->name }}</h3>
                            <p class="text-xs text-gray-500">Leader: {{ $team->leader->name }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-xl font-black text-emerald-400">{{ $team->total_score }}</span>
                        </td>
                        <td class="py-5 px-6 text-right">
                            @php
                                $progress = $team->progress()->latest('completed_at')->first();
                            @endphp
                            @if($progress)
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-full text-sm font-medium">
                                    Stage {{ $progress->currentStage->order }}
                                </span>
                            @else
                                <span class="text-gray-500 text-sm italic">Not Started</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500">No teams registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Auto refresh every 5 seconds as per requirements -->
<script>
    setTimeout(function(){
        window.location.reload(1);
    }, 5000);
</script>
</x-app-layout>
