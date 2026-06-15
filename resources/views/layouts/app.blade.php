<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Q-Hunt') }}</title>
        <link rel="icon" type="image/png" href="/images/logo.png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <div id="live-leaderboard-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-gray-900 border border-white/10 rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col shadow-[0_0_50px_rgba(59,130,246,0.3)] transform transition-all">
                <div class="p-4 border-b border-white/10 flex justify-between items-center bg-gray-800">
                    <h3 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">
                        <i class="fa-solid fa-satellite-dish animate-pulse mr-2 text-blue-400"></i> Live Game Update
                    </h3>
                    <button onclick="closeLiveLeaderboard()" class="text-gray-400 hover:text-white transition w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
                <div id="live-leaderboard-content" class="p-6 overflow-y-auto">
                    <!-- Loaded via AJAX -->
                    <div class="text-center text-gray-400 py-8"><i class="fa-solid fa-spinner fa-spin text-3xl"></i></div>
                </div>
                <div class="p-4 border-t border-white/10 bg-gray-800 text-center">
                    <button onclick="closeLiveLeaderboard()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg shadow-blue-500/30 transition">Resume Hunt</button>
                </div>
            </div>
        </div>

        <script>
            function closeLiveLeaderboard() {
                document.getElementById('live-leaderboard-modal').classList.add('hidden');
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Poll every 10 seconds
                setInterval(() => {
                    fetch('{{ route("participant.status") }}')
                        .then(res => res.json())
                        .then(data => {
                            if (data.is_published) {
                                if (!localStorage.getItem('podium_seen')) {
                                    localStorage.setItem('podium_seen', 'true');
                                    window.location.href = '{{ route("participant.podium") }}';
                                }
                            }

                            if (data.push_leaderboard_at) {
                                const lastSeen = localStorage.getItem('last_leaderboard_push');
                                if (!lastSeen || lastSeen < data.push_leaderboard_at) {
                                    localStorage.setItem('last_leaderboard_push', data.push_leaderboard_at);
                                    
                                    fetch('{{ route("participant.leaderboard_partial") }}')
                                        .then(res => res.text())
                                        .then(html => {
                                            document.getElementById('live-leaderboard-content').innerHTML = html;
                                            document.getElementById('live-leaderboard-modal').classList.remove('hidden');
                                        });
                                }
                            }
                        })
                        .catch(err => console.log('Poll err'));
                }, 10000);
            });
        </script>
    </body>
</html>
