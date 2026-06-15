@extends('layouts.admin')

@section('header', 'Register New Team')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
        <form action="{{ route('admin.teams.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Team Name</label>
                <input type="text" name="name" id="name" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" placeholder="e.g., Code Breakers">
                @error('name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="event_id" class="block text-sm font-medium text-gray-300 mb-1">Select Event</label>
                <select name="event_id" id="event_id" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
                @error('event_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="leader_user_id" class="block text-sm font-medium text-gray-300 mb-1">Assign Team Leader (Participant User)</label>
                <select name="leader_user_id" id="leader_user_id" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    @forelse($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @empty
                        <option value="" disabled>No participant users found. Please register a user first.</option>
                    @endforelse
                </select>
                @error('leader_user_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            
            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <a href="{{ route('admin.teams.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-blue-500/30 transition">
                    Register Team
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
