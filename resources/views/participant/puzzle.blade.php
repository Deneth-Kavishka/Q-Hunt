<x-app-layout>
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        
        <style>
            @keyframes dropIn {
                0% { opacity: 0; transform: translate(-50%, -20px) scale(0.9); }
                60% { opacity: 1; transform: translate(-50%, 10px) scale(1.02); }
                100% { opacity: 1; transform: translate(-50%, 0) scale(1); }
            }
            .animate-drop-in {
                animation: dropIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }
        </style>

        @if (session('error_attempts') !== null)
            <div id="toast-error" class="fixed top-10 left-1/2 z-50 w-[90%] max-w-md p-6 rounded-2xl bg-red-950/70 backdrop-blur-xl border border-red-500/40 shadow-[0_20px_60px_rgba(220,38,38,0.4)] text-white text-center transition-all duration-500 animate-drop-in">
                <i class="fa-solid fa-triangle-exclamation text-4xl mb-3 text-red-400 block drop-shadow-[0_0_15px_rgba(239,68,68,0.6)] animate-[pulse_1s_ease-in-out_infinite]"></i> 
                <span class="text-2xl font-black uppercase tracking-widest text-white drop-shadow-md">Incorrect!</span><br>
                <span class="text-lg mt-2 block text-red-100">Only now you have <strong class="text-5xl text-white mx-2 drop-shadow-[0_0_10px_rgba(255,255,255,0.4)]">{{ session('error_attempts') }}</strong> chances to try.</span>
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-error');
                    if (toast) {
                        toast.style.transform = 'translate(-50%, -20px) scale(0.9)';
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 4000);
            </script>
        @elseif (session('error'))
            <div id="toast-error" class="fixed top-10 left-1/2 z-50 w-[90%] max-w-md p-4 rounded-xl bg-red-950/70 backdrop-blur-xl border border-red-500/40 shadow-[0_20px_60px_rgba(220,38,38,0.4)] text-red-100 font-bold text-center transition-all duration-500 animate-drop-in">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-red-400"></i> {{ session('error') }}
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-error');
                    if (toast) {
                        toast.style.transform = 'translate(-50%, -20px) scale(0.9)';
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5 z-10 relative">
                <div>
                    <h2 class="text-xl font-bold text-white">Stage {{ $stage->order }}: {{ $stage->name }}</h2>
                    <p class="text-sm text-gray-400">Base points: <span class="text-emerald-400 font-bold">{{ $stage->points }}</span></p>
                </div>
                
                @if($stage->end_time)
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Time Remaining</p>
                    <div id="countdown" class="text-2xl font-mono font-bold text-emerald-400 bg-black/40 px-3 py-1 rounded-lg border border-white/10" data-endtime="{{ $stage->end_time->toIso8601String() }}">
                        00:00
                    </div>
                    <p class="text-[10px] text-red-400/50 mt-2 font-bold uppercase tracking-widest">{{ ($question->max_attempts ?? 3) - ($attemptsCount ?? 0) }} Attempts Left</p>
                </div>
                @else
                <div class="text-right flex flex-col items-end">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center shadow-lg mb-2">
                        <i class="fa-solid fa-puzzle-piece text-xl text-white"></i>
                    </div>
                    <p class="text-[10px] text-red-400/50 font-bold uppercase tracking-widest">{{ ($question->max_attempts ?? 3) - ($attemptsCount ?? 0) }} Attempts Left</p>
                </div>
                @endif
            </div>
            
            <div class="p-8 z-10 relative">
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Your Challenge:</h3>
                    <div class="p-6 bg-black/20 rounded-xl border border-white/5 text-lg text-white">
                        {{ $question->content }}
                    </div>
                    
                    @if($question->hint)
                        <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl text-yellow-200/80 text-sm">
                            <i class="fa-solid fa-lightbulb mr-2"></i> <strong>Hint:</strong> {{ $question->hint }}
                        </div>
                    @endif
                </div>

                <form action="{{ route('stage.submit', $stage) }}" method="POST">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    
                    @if($question->type === 'mcq' && !empty($question->options))
                        <div class="mb-6 space-y-3">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Select your answer:</label>
                            @foreach($question->options as $index => $option)
                                <label class="flex items-center p-4 border border-white/10 rounded-xl bg-black/20 hover:bg-black/40 cursor-pointer transition group">
                                    <input type="radio" name="answer" value="{{ $option }}" required class="w-5 h-5 text-emerald-500 bg-black/50 border-white/30 focus:ring-emerald-500 focus:ring-offset-gray-900">
                                    <span class="ml-3 text-white text-lg group-hover:text-emerald-300 transition">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="mb-6">
                            <label for="answer" class="block text-sm font-medium text-gray-300 mb-2">Enter your answer:</label>
                            <input type="text" name="answer" id="answer" required autocomplete="off" class="w-full bg-black/40 border border-white/20 rounded-xl px-4 py-4 text-white text-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-500/50 outline-none transition shadow-inner">
                        </div>
                    @endif
                    
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition transform hover:scale-[1.02]">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Submit Answer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@if($stage->end_time)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownEl = document.getElementById('countdown');
        if (!countdownEl) return;
        
        const endTimeStr = countdownEl.getAttribute('data-endtime');
        const endTime = new Date(endTimeStr).getTime();
        
        setInterval(() => {
            const now = new Date().getTime();
            const diff = endTime - now;
            
            const isNegative = diff < 0;
            const absDiff = Math.abs(diff);
            
            const minutes = Math.floor(absDiff / (1000 * 60));
            const seconds = Math.floor((absDiff % (1000 * 60)) / 1000);
            
            const formattedMins = String(minutes).padStart(2, '0');
            const formattedSecs = String(seconds).padStart(2, '0');
            
            if (isNegative) {
                countdownEl.innerHTML = `-${formattedMins}:${formattedSecs}`;
                countdownEl.classList.remove('text-emerald-400');
                countdownEl.classList.add('text-red-500');
            } else {
                countdownEl.innerHTML = `${formattedMins}:${formattedSecs}`;
            }
        }, 1000);
    });
</script>
@endif
</x-app-layout>
