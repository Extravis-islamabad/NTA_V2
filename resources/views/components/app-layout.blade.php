<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MonetX - NetFlow Analyzer') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        monetx: {
                            purple: '#5548F5',
                            magenta: '#C843F3',
                            lightblue: '#E4F2FF',
                            lightpink: '#F2C7FF',
                            darkmagenta: '#9619B5'
                        },
                        primary: {
                            50: '#E4F2FF',
                            100: '#E4F2FF',
                            200: '#C5E4FF',
                            300: '#9FD3FF',
                            400: '#7B6CF9',
                            500: '#5548F5',
                            600: '#4840D4',
                            700: '#3B35B3',
                            800: '#2E2A8C',
                            900: '#221F66'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .gradient-primary {
            background: linear-gradient(135deg, #5548F5 0%, #C843F3 100%);
        }
        .btn-monetx {
            background: linear-gradient(135deg, #5548F5 0%, #C843F3 100%);
            transition: all 0.3s ease;
        }
        .btn-monetx:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(85, 72, 245, 0.4);
        }
        /* Dark Glassmorphic Theme */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card {
            background: rgba(139, 92, 246, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        .glass-nav {
            background: rgba(10, 10, 30, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }
    </style>
</head>

<body class="bg-[#0a0a1a] font-sans antialiased text-white" style="background-image: url('{{ asset('monetx_bg.webp') }}'); background-size: cover; background-attachment: fixed; background-position: center;">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="glass-nav sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('MonetX_white@4x-8.png') }}" alt="MonetX" class="h-10 w-auto">
                            </a>
                        </div>
                        <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Dashboard
                            </a>
                            <a href="{{ route('devices.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('devices.*') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Inventory
                            </a>
                            <a href="{{ route('traffic.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('traffic.*') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Traffic
                            </a>
                            <a href="{{ route('alarms.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('alarms.*') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Alarms
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('reports.*') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Reports
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-[#C843F3] text-[#C843F3]' : 'border-transparent text-gray-300 hover:text-[#C843F3] hover:border-[#C843F3]/30' }} text-sm font-medium transition-all">
                                Settings
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- User Dropdown -->
                        @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition">
                                <div class="w-8 h-8 gradient-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-200">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 glass rounded-lg shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-white/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <hr class="my-1 border-white/10">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @isset($header)
        <header class="glass-nav">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="glass-nav py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center text-sm text-gray-400">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('MonetX_white@4x-8.png') }}" alt="MonetX" class="h-6 w-auto">
                        <span class="text-gray-600">|</span>
                        <span>NetFlow Traffic Analyzer v2.0</span>
                    </div>
                    @php
                        $collectorIp = \App\Models\Setting::get('collector_ip');
                        $netflowPort = \App\Models\Setting::get('netflow_port');
                    @endphp
                    <div class="flex items-center gap-4">
                        @if($collectorIp && $netflowPort)
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Collector: {{ $collectorIp }}:{{ $netflowPort }}
                        </span>
                        @else
                        <span class="flex items-center gap-2 text-orange-400">
                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                            Collector: <a href="{{ route('settings.index') }}" class="underline hover:text-orange-300">Configure in Settings</a>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
