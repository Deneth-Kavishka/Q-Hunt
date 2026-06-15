<div class="space-y-4">
    @foreach($teams->take(5) as $index => $team)
        <div class="flex items-center p-4 bg-white/5 border border-white/10 rounded-xl relative overflow-hidden">
            @if($index === 0)
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
            @elseif($index === 1)
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-400/10 rounded-full blur-2xl pointer-events-none"></div>
            @elseif($index === 2)
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-700/10 rounded-full blur-2xl pointer-events-none"></div>
            @endif

            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center font-black text-2xl
                @if($index === 0) text-yellow-400 @elseif($index === 1) text-gray-300 @elseif($index === 2) text-amber-600 @else text-gray-600 @endif
            ">
                #{{ $index + 1 }}
            </div>
            
            <div class="ml-4 flex-grow">
                <h4 class="text-lg font-bold text-white">{{ $team->name }}</h4>
                <p class="text-sm text-gray-400">Leader: {{ $team->leader->name }}</p>
            </div>
            
            <div class="text-right">
                <div class="text-2xl font-black text-emerald-400">{{ $team->total_score }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Points</div>
            </div>
        </div>
    @endforeach
    
    @if($teams->count() > 5)
        <div class="text-center text-sm text-gray-500 mt-4 font-medium">
            + {{ $teams->count() - 5 }} more teams playing...
        </div>
    @endif
</div>
