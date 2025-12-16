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
                        },
                        secondary: {
                            50: '#F2C7FF',
                            100: '#F2C7FF',
                            200: '#E9A8FF',
                            300: '#DD7FF8',
                            400: '#D461F5',
                            500: '#C843F3',
                            600: '#9619B5',
                            700: '#7A148F',
                            800: '#5E1070',
                            900: '#420C51'
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
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }
        .gradient-secondary {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        .gradient-light {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .monetx-shadow {
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
        }
        .monetx-hover:hover {
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.25);
            transform: translateY(-2px);
        }
        .btn-monetx {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            transition: all 0.3s ease;
        }
        .btn-monetx:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        .btn-monetx-secondary {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        .btn-monetx-secondary:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
        }
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        .stat-card:hover {
            border-color: rgba(59, 130, 246, 0.25);
        }
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.03) 0%, rgba(16, 185, 129, 0.03) 100%);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 20px 5px rgba(59, 130, 246, 0.15); }
        }
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        /* Leaflet Map Styles */
        .traffic-map {
            height: 350px;
            min-height: 300px;
            border-radius: 0.5rem;
            z-index: 1;
        }
        .leaflet-container {
            background: #f1f5f9;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-10 w-auto">
                            </a>
                        </div>
                        <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('devices.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('devices.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                                Inventory
                            </a>
                            <a href="{{ route('traffic.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('traffic.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Traffic
                            </a>
                            <a href="{{ route('alarms.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('alarms.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Alarms
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('reports.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Reports
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="inline-flex items-center px-4 pt-1 border-b-2 {{ request()->routeIs('settings.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-600/30' }} text-sm font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Settings
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden lg:flex items-center gap-2 text-xs text-gray-500 whitespace-nowrap">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span id="currentTime">{{ now()->format('M d H:i') }}</span>
                        </div>

                        <!-- User Dropdown -->
                        @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-8 h-8 gradient-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
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
        <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-6 w-auto">
                        <span class="text-gray-400">|</span>
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
                        <span class="flex items-center gap-2 text-orange-600">
                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                            Collector: <a href="{{ route('settings.index') }}" class="underline">Configure in Settings</a>
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
        // MonetX brand colors for charts - using blue/green as primary
        window.monetxColors = {
            primary: '#3B82F6',
            secondary: '#10B981',
            tertiary: '#8B5CF6',
            light: '#EFF6FF',
            lightGreen: '#D1FAE5',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#06B6D4',
            gradientStart: '#3B82F6',
            gradientEnd: '#10B981'
        };

        // Default ApexCharts options with MonetX theme
        window.apexDefaultOptions = {
            chart: {
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
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
            tooltip: {
                theme: 'light',
                style: { fontSize: '12px' }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px'
            }
        };

        // Helper function to format bytes
        window.formatBytes = function(bytes) {
            if (bytes === null || bytes === undefined) return '0 B';
            bytes = parseInt(bytes);
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' B';
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
    </script>

    <script>
        // Update time every second
        setInterval(() => {
            const now = new Date();
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = now.toLocaleString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }, 1000);
    </script>

    @stack('scripts')
</body>

</html>
