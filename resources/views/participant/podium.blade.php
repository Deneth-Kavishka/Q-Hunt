<x-app-layout>
<div class="py-12 min-h-[80vh] flex flex-col items-center justify-center relative overflow-hidden">
    <!-- Confetti Script -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Pixabay Award Music (Local File)
            const fanfare = new Audio('/audio/award.mp3');
            
            fanfare.volume = 0.8;
            fanfare.play().catch(e => console.log('Fanfare autoplay prevented'));
            
            // Fade out and stop after 10 seconds
            setTimeout(() => {
                let vol = fanfare.volume;
                const fadeInterval = setInterval(() => {
                    vol -= 0.05;
                    if (vol <= 0) {
                        clearInterval(fadeInterval);
                        fanfare.pause();
                    } else {
                        fanfare.volume = vol;
                    }
                }, 100);
            }, 10000);

            const duration = 5000;
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#3b82f6', '#8b5cf6', '#10b981']
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#3b82f6', '#8b5cf6', '#10b981']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        });
    </script>

    <!-- Background glow -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-tr from-blue-600/20 to-purple-600/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="z-10 text-center mb-16">
        <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-300 to-yellow-500 mb-4 drop-shadow-[0_0_20px_rgba(250,204,21,0.4)] animate-pulse">
            Hunt Completed!
        </h1>
        <p class="text-xl text-gray-300 font-medium tracking-wide uppercase">{{ $event->name }}</p>
    </div>

    <!-- Podium -->
    <div class="flex items-end justify-center gap-2 md:gap-6 w-full max-w-4xl px-4 z-10 mb-16 h-64">
        
        <!-- 2nd Place -->
        @if($topTeams->count() >= 2)
        <div class="flex flex-col items-center animate-[slideUp_1s_ease-out_0.5s_both]">
            <div class="text-center mb-4">
                <p class="text-lg font-bold text-gray-200 line-clamp-1 max-w-[100px] md:max-w-[150px]">{{ $topTeams[1]->name }}</p>
                <p class="text-emerald-400 font-black">{{ $topTeams[1]->total_score }} pts</p>
            </div>
            <div class="w-24 md:w-32 h-32 bg-gradient-to-t from-gray-700 to-gray-500 rounded-t-lg shadow-2xl relative flex items-start justify-center pt-4 border-t-4 border-gray-300">
                <span class="text-4xl font-black text-white drop-shadow-md">2</span>
            </div>
        </div>
        @endif

        <!-- 1st Place -->
        @if($topTeams->count() >= 1)
        <div class="flex flex-col items-center animate-[slideUp_1s_ease-out_1s_both] relative z-20">
            <div class="text-center mb-4 absolute -top-24 w-full">
                <i class="fa-solid fa-crown text-4xl text-yellow-400 mb-2 drop-shadow-[0_0_15px_rgba(250,204,21,0.8)] animate-bounce"></i>
                <p class="text-2xl font-black text-yellow-300 line-clamp-1">{{ $topTeams[0]->name }}</p>
                <p class="text-emerald-400 font-black text-xl">{{ $topTeams[0]->total_score }} pts</p>
            </div>
            <div class="w-28 md:w-40 h-48 bg-gradient-to-t from-yellow-700 to-yellow-500 rounded-t-lg shadow-[0_0_50px_rgba(250,204,21,0.3)] relative flex items-start justify-center pt-6 border-t-4 border-yellow-300">
                <span class="text-5xl font-black text-white drop-shadow-lg">1</span>
            </div>
        </div>
        @endif

        <!-- 3rd Place -->
        @if($topTeams->count() >= 3)
        <div class="flex flex-col items-center animate-[slideUp_1s_ease-out_0s_both]">
            <div class="text-center mb-4">
                <p class="text-lg font-bold text-gray-200 line-clamp-1 max-w-[100px] md:max-w-[150px]">{{ $topTeams[2]->name }}</p>
                <p class="text-emerald-400 font-black">{{ $topTeams[2]->total_score }} pts</p>
            </div>
            <div class="w-24 md:w-32 h-24 bg-gradient-to-t from-amber-800 to-amber-600 rounded-t-lg shadow-2xl relative flex items-start justify-center pt-3 border-t-4 border-amber-500">
                <span class="text-3xl font-black text-white drop-shadow-md">3</span>
            </div>
        </div>
        @endif

    </div>

    <div class="z-10 animate-[fadeIn_1s_ease-out_2s_both]">
        <a href="{{ route('leaderboard') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-bold text-lg shadow-[0_0_30px_rgba(255,255,255,0.1)] transition transform hover:scale-105">
            <i class="fa-solid fa-ranking-star mr-2"></i> View Full Leaderboard
        </a>
    </div>

</div>

<style>
    @keyframes slideUp {
        0% { transform: translateY(100px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
</x-app-layout>
