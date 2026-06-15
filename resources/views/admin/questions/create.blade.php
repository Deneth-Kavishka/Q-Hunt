@extends('layouts.admin')

@section('header', 'Manage Puzzle: ' . $stage->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.events.stages.index', $stage->event_id) }}" class="text-gray-400 hover:text-white transition flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Stages
        </a>
    </div>

    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <form action="{{ route('admin.stages.questions.store', $stage) }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-300 mb-1">Puzzle Type</label>
                    <select name="type" id="type" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" onchange="toggleMCQOptions()">
                        <option value="text" {{ old('type', $question?->type) == 'text' ? 'selected' : '' }}>Standard Text/Question</option>
                        <option value="mcq" {{ old('type', $question?->type) == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                        <option value="riddle" {{ old('type', $question?->type) == 'riddle' ? 'selected' : '' }}>Riddle</option>
                        <option value="math" {{ old('type', $question?->type) == 'math' ? 'selected' : '' }}>Math Challenge</option>
                    </select>
                </div>
                <div>
                    <label for="max_attempts" class="block text-sm font-medium text-gray-300 mb-1">Max Attempts Allowed</label>
                    <input type="number" name="max_attempts" id="max_attempts" value="{{ old('max_attempts', $question?->max_attempts ?? 3) }}" required min="1" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                </div>
            </div>
            
            <div id="mcq_options_container" class="space-y-4 p-4 border border-white/10 rounded-lg bg-black/10 {{ old('type', $question?->type) == 'mcq' ? '' : 'hidden' }}">
                <h4 class="text-sm font-bold text-gray-300">MCQ Options</h4>
                @for($i = 0; $i < 4; $i++)
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Option {{ $i + 1 }}</label>
                        <input type="text" name="options[]" value="{{ old('options.'.$i, $question?->options[$i] ?? '') }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none">
                    </div>
                @endfor
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Puzzle Content (What the team will see)</label>
                <textarea name="content" id="content" rows="4" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" placeholder="e.g., I speak without a mouth and hear without ears. I have no body, but I come alive with wind. What am I?">{{ old('content', $question?->content) }}</textarea>
            </div>
            
            <div>
                <label for="correct_answer" class="block text-sm font-medium text-gray-300 mb-1">Exact Correct Answer</label>
                <input type="text" name="correct_answer" id="correct_answer" value="{{ old('correct_answer', $question?->correct_answer) }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-emerald-400 font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition" placeholder="e.g., an echo">
                <p class="mt-1 text-xs text-gray-500">Answers are case-insensitive during verification.</p>
            </div>
            
            <div>
                <label for="hint" class="block text-sm font-medium text-gray-300 mb-1">Hint (Optional)</label>
                <textarea name="hint" id="hint" rows="2" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-yellow-200 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 outline-none transition" placeholder="Provide a hint to help struggling teams.">{{ old('hint', $question?->hint) }}</textarea>
            </div>
            
            <div class="pt-6 border-t border-white/10 flex justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg shadow-emerald-500/30 transition">
                    {{ $question ? 'Update Puzzle' : 'Save Puzzle' }}
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function toggleMCQOptions() {
    const type = document.getElementById('type').value;
    const container = document.getElementById('mcq_options_container');
    const maxAttempts = document.getElementById('max_attempts');
    if (type === 'mcq') {
        container.classList.remove('hidden');
        if (maxAttempts.value == 3 && !{{ $question ? 'true' : 'false' }}) maxAttempts.value = 1;
    } else {
        container.classList.add('hidden');
    }
}
</script>
@endsection
