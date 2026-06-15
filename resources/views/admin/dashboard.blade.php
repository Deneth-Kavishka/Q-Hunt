@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card -->
    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-purple-500/50 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/20 rounded-full blur-2xl group-hover:bg-purple-500/30 transition-all"></div>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-calendar w-5 h-5 flex items-center justify-center"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-400">Total Events</p>
                <h3 class="text-2xl font-bold text-white">{{ $totalEvents }}</h3>
            </div>
        </div>
    </div>
    
    <!-- Stat Card -->
    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-blue-500/50 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/20 rounded-full blur-2xl group-hover:bg-blue-500/30 transition-all"></div>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-users w-5 h-5 flex items-center justify-center"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-400">Total Teams</p>
                <h3 class="text-2xl font-bold text-white">{{ $totalTeams }}</h3>
            </div>
        </div>
    </div>

    <!-- Stat Card -->
    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl group-hover:bg-emerald-500/30 transition-all"></div>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-play w-5 h-5 flex items-center justify-center"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-400">Active Events</p>
                <h3 class="text-2xl font-bold text-white">{{ $activeEvents }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6">
        <h3 class="text-lg font-medium text-white mb-4">Event Activity (Last 7 Days)</h3>
        <div class="h-64 relative">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $activityDates->toJson() !!},
                datasets: [{
                    label: 'Stage Completions',
                    data: {!! $activityCounts->toJson() !!},
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#a855f7',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#a855f7'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
