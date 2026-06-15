<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Q-Hunt') }} - Admin</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-100 bg-gray-900 bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white/5 backdrop-blur-xl border-r border-white/10 flex flex-col z-20">
            <div class="h-16 flex items-center px-6 border-b border-white/10">
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-blue-400">Hunt Admin</span>
            </div>
            <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-purple-500/20 text-purple-300' : 'text-gray-300 hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-pie w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.events.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.events.*') ? 'bg-purple-500/20 text-purple-300' : 'text-gray-300 hover:bg-white/5' }}">
                    <i class="fa-solid fa-calendar-alt w-6"></i>
                    <span class="ml-3">Events</span>
                </a>
                <a href="{{ route('admin.teams.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.teams.*') ? 'bg-purple-500/20 text-purple-300' : 'text-gray-300 hover:bg-white/5' }}">
                    <i class="fa-solid fa-users w-6"></i>
                    <span class="ml-3">Teams</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-white/5 transition">
                        <i class="fa-solid fa-sign-out-alt w-6"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <header class="h-16 bg-white/5 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6 z-10">
                <h1 class="text-xl font-semibold text-gray-100">@yield('header', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-300">{{ Auth::user()->name ?? 'Admin User' }}</span>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-6 z-10">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 backdrop-blur-sm flex items-center gap-3 shadow-lg shadow-emerald-500/10">
                        <i class="fa-solid fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
