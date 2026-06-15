@extends('layouts.admin')

@section('header', 'Stages for: ' . $event->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.events.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Events
    </a>
    <a href="{{ route('admin.events.stages.create', $event) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/30 transition">
        <i class="fa-solid fa-plus mr-2"></i> Add Stage
    </a>
</div>

<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-white/10 bg-white/5">
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Order</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Stage Name</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Points</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">Questions</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400">QR Code</th>
                <th class="py-4 px-6 text-sm font-medium text-gray-400 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse ($stages as $stage)
            <tr class="hover:bg-white/5 transition-colors">
                <td class="py-4 px-6">
                    <span class="w-8 h-8 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 flex items-center justify-center font-bold text-sm">
                        {{ $stage->order }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <p class="text-sm font-medium text-white">{{ $stage->name }}</p>
                </td>
                <td class="py-4 px-6 text-sm text-gray-300">{{ $stage->points }}</td>
                <td class="py-4 px-6 text-sm text-gray-300">{{ $stage->questions_count }}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <!-- Use a free API to generate QR Code instantly for the scan URL -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(url('/scan/' . $stage->qr_code_identifier)) }}" alt="QR Code" class="w-12 h-12 rounded bg-white p-1">
                        <button onclick="window.open('https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ urlencode(url('/scan/' . $stage->qr_code_identifier)) }}', '_blank')" class="text-xs text-blue-400 hover:text-blue-300 underline">
                            View/Print
                        </button>
                    </div>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.stages.edit', $stage) }}" class="text-gray-400 hover:text-blue-400 transition" title="Edit Stage">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="{{ route('admin.stages.questions.create', $stage) }}" class="text-gray-400 hover:text-emerald-400 transition" title="Manage Puzzle">
                            <i class="fa-solid fa-puzzle-piece"></i>
                        </a>
                        <form action="{{ route('admin.stages.destroy', $stage) }}" method="POST" class="inline" onsubmit="return confirm('Delete this stage?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition" title="Delete Stage">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    No stages found. Add the first stage for this event!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
