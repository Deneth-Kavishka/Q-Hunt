<x-app-layout>
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-red-500/20 rounded-full flex items-center justify-center mb-4 border border-red-500/30 shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                <i class="fa-solid fa-times text-2xl text-red-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Wrong Location!</h2>
            <p class="text-gray-400 text-lg">{{ $message ?? 'This is not the correct stage for your team right now.' }}</p>
        </div>
        
        <div class="text-center">
            <a href="{{ route('participant.dashboard') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition border border-white/10">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

    </div>
</div>
</x-app-layout>
