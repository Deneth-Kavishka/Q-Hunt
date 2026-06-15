<x-app-layout>
<div class="py-12 min-h-[80vh] flex flex-col justify-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
        @if(session('failed_stage'))
            <div class="w-32 h-32 mx-auto bg-red-500/20 rounded-full flex items-center justify-center mb-8 border border-red-500/50 shadow-[0_0_40px_rgba(239,68,68,0.5)] animate-[pulse_2s_ease-in-out_infinite]">
                <i class="fa-solid fa-xmark text-6xl text-red-400"></i>
            </div>
        @else
            <div class="w-32 h-32 mx-auto bg-emerald-500/20 rounded-full flex items-center justify-center mb-8 border border-emerald-500/50 shadow-[0_0_40px_rgba(16,185,129,0.5)] animate-[pulse_2s_ease-in-out_infinite]">
                <i class="fa-solid fa-check text-6xl text-emerald-400"></i>
            </div>
        @endif
        
        @if(session('failed_stage'))
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400 mb-4">Stage Failed</h1>
            <p class="text-xl text-red-200/80 mb-2">You ran out of attempts for this puzzle.</p>
            
            <div class="mb-10">
                <span class="inline-block px-6 py-2 rounded-full bg-red-500/20 border border-red-500/50 text-red-400 font-bold text-xl shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                    0 Points Awarded
                </span>
            </div>
        @else
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400 mb-4">Stage Completed!</h1>
            <p class="text-xl text-emerald-200/80 mb-2">Excellent work! Your answer was correct.</p>
            
            @if(session('success_points'))
            <div class="mb-10">
                <span class="inline-block px-6 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 font-bold text-xl shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                    +{{ session('success_points') }} Points Awarded
                </span>
            </div>
            @else
            <div class="mb-10"></div>
            @endif
        @endif
        
        @if($isHuntCompleted)
            <canvas id="confetti-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-50"></canvas>
            
            <div class="my-6 p-8 bg-gradient-to-br from-fuchsia-500/10 to-cyan-500/10 border border-fuchsia-500/30 rounded-3xl text-center shadow-[0_0_40px_rgba(217,70,239,0.2)] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/20 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-fuchsia-500/20 rounded-full blur-[60px] pointer-events-none"></div>
                
                <div class="text-6xl mb-6 animate-[bounce_2s_ease-in-out_infinite] relative z-10">🏆</div>
                <h4 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400 mb-4 relative z-10">Treasure Found!</h4>
                <p class="text-xl text-fuchsia-100/90 relative z-10 font-medium">{{ $team->event->completion_message ?: 'You have successfully found all locations and solved all puzzles! Wait for the final results on the leaderboard.' }}</p>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3');
                    audio.volume = 0.6;
                    audio.play().catch(e => console.log('Autoplay prevented'));
                    
                    var duration = 4000;
                    var end = Date.now() + duration;

                    (function frame() {
                        confetti({ particleCount: 5, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#d946ef', '#06b6d4', '#ffffff'] });
                        confetti({ particleCount: 5, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#d946ef', '#06b6d4', '#ffffff'] });
                        if (Date.now() < end) requestAnimationFrame(frame);
                    }());
                });
            </script>
        @else
            @if($nextStageClue)
                <div class="relative bg-black/40 border border-purple-500/30 rounded-3xl p-8 mb-10 overflow-hidden shadow-[0_0_30px_rgba(168,85,247,0.15)] transform transition-transform hover:scale-[1.02]">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/20 rounded-full blur-[80px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/20 rounded-full blur-[60px] pointer-events-none"></div>
                    
                    <h3 class="relative z-10 text-lg font-bold text-purple-400 uppercase tracking-widest mb-6 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i> Your Next Clue
                    </h3>
                    <p class="relative z-10 text-3xl md:text-4xl text-white font-serif italic leading-relaxed">"{{ $nextStageClue }}"</p>
                </div>
            @endif
        @endif
        
        <a href="{{ route('participant.dashboard') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-lg rounded-xl transition transform hover:scale-105">
            <i class="fa-solid fa-house"></i> Return to Dashboard
        </a>
    </div>
</div>
</x-app-layout>
