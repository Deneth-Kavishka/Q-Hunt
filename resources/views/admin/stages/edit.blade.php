@extends('layouts.admin')

@section('header', 'Edit Stage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-xl">
        <form action="{{ route('admin.stages.update', $stage) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Location / Stage Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $stage->name) }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" placeholder="e.g., Library Main Desk">
                @error('name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="location_clue" class="block text-sm font-medium text-gray-300 mb-1">Clue to Find this Location</label>
                <textarea name="location_clue" id="location_clue" rows="2" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" placeholder="e.g., Go to the place where students check out books.">{{ old('location_clue', $stage->location_clue) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">This clue is shown to teams to help them physically find the QR code for this stage.</p>
                @error('location_clue') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-300 mb-1">Task Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', $stage->start_time ? $stage->start_time->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('start_time') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-300 mb-1">Task End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', $stage->end_time ? $stage->end_time->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('end_time') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-300 mb-1">Stage Order (Sequence)</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $stage->order) }}" required min="1" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('order') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-300 mb-1">Base Points</label>
                    <input type="number" name="points" id="points" value="{{ old('points', $stage->points) }}" required min="0" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('points') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <a href="{{ route('admin.events.stages.index', $stage->event_id) }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/30 transition">
                    Update Stage
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
