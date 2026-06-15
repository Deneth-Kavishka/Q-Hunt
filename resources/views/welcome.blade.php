<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Q-Hunt') }} - The Ultimate QR Treasure Hunt</title>
        <link rel="icon" type="image/png" href="/images/logo.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,800,900" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #0f172a; /* Slate 900 */
                color: #f8fafc; /* Slate 50 */
                overflow-x: hidden;
            }
            .glass-panel {
                background: rgba(30, 41, 59, 0.4);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .glow-text {
                text-shadow: 0 0 30px rgba(56, 189, 248, 0.5);
            }
            @keyframes float {
                0% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
                100% { transform: translateY(0px) scale(1); }
            }
            .floating-logo {
                animation: float 6s ease-in-out infinite;
            }
            /* Ambient glowing orbs */
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(100px);
                z-index: -1;
                opacity: 0.6;
            }
            .orb-1 { width: 500px; height: 500px; background: #6366f1; top: -100px; left: -100px; }
            .orb-2 { width: 400px; height: 400px; background: #14b8a6; bottom: -100px; right: -50px; }
            .orb-3 { width: 300px; height: 300px; background: #a855f7; top: 40%; left: 50%; transform: translate(-50%, -50%); }
        </style>
    </head>
    <body class="antialiased min-h-screen relative flex flex-col selection:bg-cyan-500 selection:text-white">
        <!-- Ambient Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="orb orb-1 animate-pulse"></div>
            <div class="orb orb-2 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="orb orb-3 animate-[pulse_4s_ease-in-out_infinite]" style="animation-duration: 8s;"></div>
        </div>

        <!-- Navigation -->
        <nav class="w-full relative z-20 py-6 px-8 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <img src="/images/logo.png" alt="Q-Hunt Logo" class="w-10 h-10 rounded-lg shadow-lg">
                <span class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-indigo-400">Q-Hunt</span>
            </div>
            
            @if (Route::has('login'))
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-full glass-panel hover:bg-white/10 font-bold transition transform hover:scale-105 border border-cyan-500/30 text-cyan-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full font-semibold text-gray-300 hover:text-white transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 font-bold shadow-[0_0_20px_rgba(6,182,212,0.4)] transition transform hover:scale-105">
                                Start Hunting
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col items-center justify-center relative z-10 px-6 text-center">
            
            <div class="mb-10 floating-logo relative">
                <div class="absolute inset-0 bg-cyan-500/30 blur-3xl rounded-full scale-150"></div>
                <img src="/images/logo.png" alt="Q-Hunt Target" class="w-48 h-48 md:w-64 md:h-64 rounded-[2.5rem] shadow-[0_0_50px_rgba(6,182,212,0.3)] relative z-10 border border-white/10">
            </div>

            <h1 class="text-6xl md:text-8xl font-black mb-6 tracking-tighter">
                Enter the <br/>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 glow-text">Q-Hunt</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-300 max-w-2xl font-medium mb-12">
                The ultimate QR Code Treasure Hunt platform. Scan locations, crack the puzzles, and race against the clock to top the live leaderboard!
            </p>

            <div class="flex flex-col sm:flex-row gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-10 py-4 rounded-full text-xl font-black bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 shadow-[0_0_30px_rgba(6,182,212,0.5)] transition transform hover:-translate-y-1 hover:scale-105">
                        <i class="fa-solid fa-gamepad mr-2"></i> Enter Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-10 py-4 rounded-full text-xl font-black bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 shadow-[0_0_30px_rgba(6,182,212,0.5)] transition transform hover:-translate-y-1 hover:scale-105">
                        <i class="fa-solid fa-user-plus mr-2"></i> Create a Team
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-4 rounded-full text-xl font-bold glass-panel hover:bg-white/10 border border-white/20 transition transform hover:-translate-y-1 hover:scale-105">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Team Login
                    </a>
                @endauth
            </div>

        </main>

        <!-- Features Grid -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-panel p-8 rounded-3xl border-t border-l border-white/20 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-cyan-500/20 rounded-2xl flex items-center justify-center mb-6 border border-cyan-500/30">
                    <i class="fa-solid fa-qrcode text-2xl text-cyan-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Scan & Discover</h3>
                <p class="text-gray-400">Find hidden QR codes at physical locations to unlock your next challenge.</p>
            </div>
            
            <div class="glass-panel p-8 rounded-3xl border-t border-l border-white/20 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center mb-6 border border-purple-500/30">
                    <i class="fa-solid fa-brain text-2xl text-purple-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Crack Puzzles</h3>
                <p class="text-gray-400">Solve mind-bending riddles and text questions to earn massive points.</p>
            </div>

            <div class="glass-panel p-8 rounded-3xl border-t border-l border-white/20 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center mb-6 border border-emerald-500/30">
                    <i class="fa-solid fa-trophy text-2xl text-emerald-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Live Leaderboard</h3>
                <p class="text-gray-400">Monitor your team's live ranking and race against others for the grand prize!</p>
            </div>
        </div>

        <footer class="w-full text-center py-8 text-gray-500 relative z-10 border-t border-white/5 bg-black/20 backdrop-blur-md">
            &copy; {{ date('Y') }} Q-Hunt Platform. All rights reserved.
        </footer>
    </body>
</html>
