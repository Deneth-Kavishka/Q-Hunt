@extends('layouts.admin')

@section('header', 'Edit Event')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
        <form action="{{ route('admin.events.update', $event) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Event Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" placeholder="e.g., University Tech Hunt 2026">
                @error('name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-300 mb-1">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('start_time') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-300 mb-1">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition">
                    @error('end_time') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $event->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-white/10 bg-black/20 text-purple-600 focus:ring-purple-500">
                <label for="is_active" class="text-sm font-medium text-gray-300">Event is active</label>
            </div>
            
            <div>
                <label for="completion_message" class="block text-sm font-medium text-gray-300 mb-1">Completion Message</label>
                <textarea name="completion_message" id="completion_message" rows="3" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition" placeholder="e.g., Congratulations! You have found the final treasure! Proceed to the main hall.">{{ old('completion_message', $event->completion_message) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">This message is shown to teams when they complete the final stage.</p>
                @error('completion_message') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/30 transition">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
