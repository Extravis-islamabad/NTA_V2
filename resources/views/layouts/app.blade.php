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
                        space: {
                            darker: '#080510',
                            dark: '#0f0a1f',
                            medium: '#1a1035',
                            light: '#2d1f4e'
                        },
                        monetx: {
                            purple: '#8B5CF6',
                            magenta: '#A78BFA',
                            pink: '#EC4899',
                            lightpurple: '#C4B5FD'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-purple: #8B5CF6;
            --secondary-purple: #A78BFA;
            --accent-pink: #EC4899;
            --space-dark: #0f0a1f;
            --space-darker: #080510;
            --space-medium: #1a1035;
        }

        /* Space background */
        .space-bg {
            background: linear-gradient(135deg, #0f0a1f 0%, #1a1035 30%, #0f0a1f 70%, #080510 100%);
            min-height: 100vh;
            position: relative;
        }

        .space-bg::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/space-bg.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: 0;
            pointer-events: none;
        }

        /* Stars */
        .stars-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite ease-in-out;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.8; }
        }

        /* Glass morphism cards */
        .glass-card {
            background: rgba(15, 10, 31, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139, 92, 246, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .glass-card-light {
            background: rgba(26, 16, 53, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        /* Navigation glass */
        .nav-glass {
            background: rgba(15, 10, 31, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.15);
        }

        /* Gradient buttons */
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-purple), var(--accent-pink));
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
            transform: translateY(-2px);
        }

        /* Stat cards with glow */
        .stat-card {
            background: rgba(15, 10, 31, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139, 92, 246, 0.15);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.1);
            transform: translateY(-2px);
        }

        /* Table styling */
        .table-glass {
            background: rgba(15, 10, 31, 0.5);
        }

        .table-row-hover:hover {
            background: rgba(139, 92, 246, 0.1);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #8B5CF6, #EC4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Gradient primary */
        .gradient-primary {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
        }

        /* Pulse glow animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4); }
            50% { box-shadow: 0 0 20px 5px rgba(139, 92, 246, 0.15); }
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* Leaflet Map Styles - Dark theme */
        .traffic-map {
            height: 350px;
            min-height: 300px;
            border-radius: 0.5rem;
            z-index: 1;
        }

        .leaflet-container {
            background: #1a1035;
            border-radius: 0.5rem;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 10, 31, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.5);
        }

        /* Fix for ApexCharts dark theme */
        .apexcharts-tooltip {
            background: rgba(15, 10, 31, 0.95) !important;
            border: 1px solid rgba(139, 92, 246, 0.3) !important;
            color: #e5e7eb !important;
        }

        .apexcharts-tooltip-title {
            background: rgba(139, 92, 246, 0.2) !important;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2) !important;
        }

        .apexcharts-xaxistooltip, .apexcharts-yaxistooltip {
            background: rgba(15, 10, 31, 0.95) !important;
            border: 1px solid rgba(139, 92, 246, 0.3) !important;
            color: #e5e7eb !important;
        }

        .apexcharts-legend-text {
            color: #a78bfa !important;
        }

        .apexcharts-text tspan {
            fill: #9ca3af;
        }

        .apexcharts-gridline {
            stroke: rgba(139, 92, 246, 0.1);
        }
    </style>
</head>

<body class="space-bg font-sans antialiased text-gray-200">
    <!-- Stars background -->
    <div class="stars-container" id="stars"></div>

    <div class="min-h-screen relative z-10">
        <!-- Navigation -->
        <nav class="nav-glass sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-10 w-auto" style="filter: brightness(0) invert(1);">
                            </a>
                        </div>
                        <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-500/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('devices.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('devices.*') ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-500/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                                Inventory
                            </a>
                            <a href="{{ route('alarms.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('alarms.*') ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-500/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Alarms
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('reports.*') ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-500/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Reports
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-purple-500 text-purple-400' : 'border-transparent text-gray-400 hover:text-purple-400 hover:border-purple-500/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Settings
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden lg:flex items-center gap-2 text-xs text-purple-300/60 whitespace-nowrap">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span id="currentTime">{{ now()->format('M d H:i') }}</span>
                        </div>

                        <!-- User Dropdown -->
                        @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-purple-500/10 transition">
                                <div class="w-8 h-8 gradient-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-purple-200">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 glass-card rounded-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-purple-200 hover:bg-purple-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <hr class="my-1 border-purple-500/20">
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

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="nav-glass border-t border-purple-500/10 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center text-sm text-purple-300/60">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-6 w-auto" style="filter: brightness(0) invert(1); opacity: 0.6;">
                        <span class="text-purple-400/40">|</span>
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

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>

    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- MonetX Chart Colors and Helpers -->
    <script>
        // MonetX brand colors for charts - space theme
        window.monetxColors = {
            primary: '#8B5CF6',
            secondary: '#10B981',
            tertiary: '#EC4899',
            light: '#C4B5FD',
            lightGreen: '#6EE7B7',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#06B6D4',
            gradientStart: '#8B5CF6',
            gradientEnd: '#EC4899'
        };

        // Default ApexCharts options with dark theme
        window.apexDefaultOptions = {
            chart: {
                fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif',
                background: 'transparent',
                foreColor: '#a78bfa',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: [
                window.monetxColors.primary,
                window.monetxColors.secondary,
                window.monetxColors.tertiary,
                window.monetxColors.success,
                window.monetxColors.warning,
                window.monetxColors.info,
                window.monetxColors.danger
            ],
            theme: {
                mode: 'dark'
            },
            tooltip: {
                theme: 'dark',
                style: { fontSize: '12px' }
            },
            grid: {
                borderColor: 'rgba(139, 92, 246, 0.1)',
                strokeDashArray: 4
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                labels: {
                    colors: '#a78bfa'
                }
            }
        };

        // Helper function to format bytes - always shows MB or GB, never KB
        window.formatBytes = function(bytes) {
            if (bytes === null || bytes === undefined || bytes === 0) return '0 MB';
            bytes = parseInt(bytes);
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        };

        // Helper function to format bandwidth
        window.formatBandwidth = function(bps) {
            if (bps === null || bps === undefined) return '0 bps';
            bps = parseInt(bps);
            if (bps >= 1000000000) return (bps / 1000000000).toFixed(2) + ' Gbps';
            if (bps >= 1000000) return (bps / 1000000).toFixed(2) + ' Mbps';
            if (bps >= 1000) return (bps / 1000).toFixed(2) + ' Kbps';
            return bps + ' bps';
        };

        // Generate stars
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('stars');
            if (starsContainer) {
                for (let i = 0; i < 80; i++) {
                    const star = document.createElement('div');
                    star.className = 'star';
                    star.style.left = Math.random() * 100 + '%';
                    star.style.top = Math.random() * 100 + '%';
                    star.style.animationDelay = Math.random() * 3 + 's';
                    star.style.width = (Math.random() * 2 + 1) + 'px';
                    star.style.height = star.style.width;
                    starsContainer.appendChild(star);
                }
            }
        });
    </script>

    <script>
        // Update time every second
        setInterval(() => {
            const now = new Date();
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                const hours = now.getHours().toString().padStart(2, '0');
                const mins = now.getMinutes().toString().padStart(2, '0');
                const secs = now.getSeconds().toString().padStart(2, '0');
                timeElement.textContent = `${hours}:${mins}:${secs}`;
            }
        }, 1000);
    </script>

    @stack('scripts')
</body>

</html>
