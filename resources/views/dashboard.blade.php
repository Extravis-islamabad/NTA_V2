@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Time Range Filter -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-cyan-500/20 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Network Traffic Dashboard</h1>
                <p class="text-sm text-gray-400 mt-1">Real-time network analytics and monitoring</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-gray-500 hidden md:flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Updated: <span id="lastUpdated">{{ now()->format('H:i:s') }}</span>
            </span>
            <form method="GET" id="timeRangeForm" class="flex items-center gap-2">
                <span class="text-sm text-gray-400 hidden sm:inline">Time Range:</span>
                <select id="globalTimeRange" name="range" onchange="document.getElementById('timeRangeForm').submit()"
                    class="glass-input px-3 py-2 rounded-lg text-sm font-medium cursor-pointer">
                    <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                    <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                    <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                </select>
            </form>
            <button onclick="refreshAllData()" class="btn-primary inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total Devices -->
        <div class="glass-card rounded-xl p-5 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Devices</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_devices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Online Devices -->
        <div class="glass-card rounded-xl p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Online</p>
                    <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['online_devices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Offline Devices -->
        <div class="glass-card rounded-xl p-5 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Offline</p>
                    <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['offline_devices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Flows -->
        <div class="glass-card rounded-xl p-5 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-indigo-400 mt-1">{{ number_format($stats['total_flows']) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Alarms -->
        <div class="glass-card rounded-xl p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Active Alarms</p>
                    <p class="text-3xl font-bold text-amber-400 mt-1">{{ $stats['active_alarms'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center {{ $stats['active_alarms'] > 0 ? 'animate-pulse' : '' }}">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Applications and Protocol Distribution Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Top Applications Donut Chart -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Top Applications
                </h3>
            </div>
            <div class="p-4">
                @if($topApplications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-sm font-medium mb-1">No application data</p>
                        <p class="text-xs text-gray-500">Traffic will appear here once flows are collected</p>
                    </div>
                @else
                    <div id="applicationsChart" style="height: 320px;"></div>
                @endif
            </div>
        </div>

        <!-- Protocol Distribution Chart -->
        <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Protocol Distribution
                </h3>
            </div>
            <div class="p-4">
                <div id="protocolsChart" style="height: 320px;"></div>
            </div>
        </div>
    </div>

    <!-- Global Traffic Map Row -->
    <div class="glass-card rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Global Traffic Distribution
            </h3>
            <span class="text-sm text-gray-500">{{ $trafficByCountry->count() }} countries</span>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <div id="mapLoadingIndicator" class="absolute inset-0 flex items-center justify-center bg-gray-900/50 rounded-lg z-10">
                            <div class="flex items-center gap-2 text-cyan-400">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm">Loading map...</span>
                            </div>
                        </div>
                        <div id="trafficMap" class="traffic-map rounded-lg" style="height: 180px;"></div>
                    </div>
                    <div class="mt-2 flex items-center gap-4 text-xs">
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span class="text-gray-500">Traffic Origin</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-gray-500">Traffic Destination</span>
                        </span>
                    </div>
                </div>
                <div>
                    @if($trafficByCountry->isEmpty())
                        <p class="text-sm text-gray-500 text-center py-8">No geographic data available</p>
                    @else
                        <div id="countryTrafficChart" style="height: 200px;"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Device Health, Top Sources, Destinations, and Conversations Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Device Health -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    Device Health
                </h3>
            </div>
            <div class="p-4">
                <div id="deviceHealthChart" style="height: 160px;"></div>
                <div class="mt-3 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-gray-400">Online</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['link_up'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-gray-400">Offline</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['link_down'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            <span class="text-gray-400">Warning</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['unknown'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Sources -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Top Sources
                </h3>
            </div>
            <div class="p-3 max-h-72 overflow-y-auto">
                @if($topSources->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                        <svg class="w-10 h-10 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                        <p class="text-xs">No source data</p>
                    </div>
                @else
                    <div class="space-y-1">
                        @foreach($topSources as $source)
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-white/5 transition-colors cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="font-mono text-xs text-white truncate">{{ $source['ip'] }}</span>
                                @if($source['country_code'])
                                    <span class="text-[10px] text-gray-500">({{ $source['country_code'] }})</span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-blue-400 ml-2">{{ $source['formatted_bytes'] }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Destinations -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    Top Destinations
                </h3>
            </div>
            <div class="p-3 max-h-72 overflow-y-auto">
                @if($topDestinations->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                        <svg class="w-10 h-10 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <p class="text-xs">No destination data</p>
                    </div>
                @else
                    <div class="space-y-1">
                        @foreach($topDestinations as $dest)
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-white/5 transition-colors cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="font-mono text-xs text-white truncate">{{ $dest['ip'] }}</span>
                                @if($dest['country_code'])
                                    <span class="text-[10px] text-gray-500">({{ $dest['country_code'] }})</span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-emerald-400 ml-2">{{ $dest['formatted_bytes'] }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Conversations -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Top Conversations
                </h3>
            </div>
            <div class="p-3 max-h-72 overflow-y-auto">
                @if($topConversations->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                        <svg class="w-10 h-10 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-xs">No conversation data</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($topConversations as $conv)
                        <div class="p-2 rounded-lg bg-white/5 hover:bg-white/10 transition-colors cursor-pointer">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded flex items-center justify-center text-white text-[10px] font-bold" style="background-color: {{ $conv['app_color'] }}">
                                        {{ strtoupper(substr($conv['application'], 0, 2)) }}
                                    </div>
                                    <span class="text-xs font-medium text-white">{{ $conv['application'] }}</span>
                                </div>
                                <span class="text-xs font-semibold text-blue-400">{{ $conv['formatted_bytes'] }}</span>
                            </div>
                            <div class="flex items-center text-[10px] text-gray-500 gap-1">
                                <span class="font-mono truncate max-w-[70px]">{{ $conv['source'] }}</span>
                                <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <span class="font-mono truncate max-w-[70px]">{{ $conv['destination'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Device Bandwidth Table - Full Width -->
    <div class="glass-card rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                Device Bandwidth
            </h3>
            <button onclick="refreshDeviceTable()" class="text-sm text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="hidden sm:inline">Refresh</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead class="bg-space-medium/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Bandwidth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Trend</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($devices as $device)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('devices.show', $device) }}" class="text-cyan-400 hover:text-cyan-300 hover:underline font-medium transition-colors">
                                {{ $device->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">{{ $device->ip_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $device->type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                            @php
                                $bw = $deviceBandwidth->firstWhere('id', $device->id);
                            @endphp
                            {{ $bw ? ($bw['bandwidth']['total_formatted'] ?? '0 B') : '0 B' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div id="sparkline-{{ $device->id }}" class="sparkline-container" style="width: 100px;"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($device->status === 'online')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/20 text-green-400">
                                    Online
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500/20 text-red-400">
                                    Offline
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- QoS Distribution - Compact -->
    @if(!$topQoS->isEmpty())
    <div class="glass-card rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                QoS Distribution (DSCP)
            </h3>
        </div>
        <div class="p-4">
            <div id="qosChart" style="height: 200px;"></div>
        </div>
    </div>
    @else
    <div class="glass-card rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                <span class="text-sm text-gray-500">No QoS/DSCP data available.</span>
            </div>
            <a href="{{ route('settings.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition-colors">
                Configure monitoring
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    @endif

    <!-- Recent Alarms - Compact -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-6 py-3 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Recent Alarms
            </h3>
            <a href="{{ route('alarms.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 flex items-center gap-1 font-medium transition-colors">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="p-4" id="alarmsContainer">
            @if($recentAlarms->isEmpty())
                <div class="flex items-center justify-center gap-3 py-4">
                    <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-400">All Systems Operational</p>
                        <p class="text-xs text-gray-500">No active alarms detected</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($recentAlarms as $alarm)
                    <div class="border-l-4 {{ $alarm->severity === 'critical' ? 'border-red-500 bg-red-500/10' : ($alarm->severity === 'warning' ? 'border-yellow-500 bg-yellow-500/10' : 'border-blue-500 bg-blue-500/10') }} rounded-r-lg p-3 hover:bg-opacity-20 transition-colors cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $alarm->title }}</p>
                                <p class="mt-0.5 text-xs text-gray-500 truncate">{{ Str::limit($alarm->description, 40) }}</p>
                                <p class="mt-1 text-[10px] text-gray-500">{{ $alarm->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="ml-2 px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $alarm->severity === 'critical' ? 'bg-red-500/20 text-red-400' : ($alarm->severity === 'warning' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-blue-500/20 text-blue-400') }}">
                                {{ ucfirst($alarm->severity) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Dashboard data from server
const dashboardData = {
    topApplications: @json($topApplications),
    topQoS: @json($topQoS),
    topProtocols: @json($topProtocols),
    trafficByCountry: @json($trafficByCountry),
    deviceBandwidth: @json($deviceBandwidth),
    heatMapData: @json($heatMapData)
};

// Chart instances
let applicationsChart = null;
let protocolsChart = null;
let qosChart = null;
let deviceHealthChart = null;
let trafficMap = null;

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeMap();
    initializeSparklines();
});

// Initialize all ApexCharts
function initializeCharts() {
    createApplicationsChart();
    createProtocolsChart();
    createQoSChart();
    createDeviceHealthChart();
    createCountryTrafficChart();
}

// Applications Donut Chart - improved styling
function createApplicationsChart() {
    const container = document.getElementById('applicationsChart');
    if (!container || dashboardData.topApplications.length === 0) {
        return;
    }

    // Take top 6 apps for clean display
    const topApps = dashboardData.topApplications.slice(0, 6);

    // Modern gradient colors
    const chartColors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EC4899', '#06B6D4'];

    const options = {
        chart: {
            type: 'donut',
            height: 320,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
            background: 'transparent'
        },
        series: topApps.map(app => app.bytes),
        labels: topApps.map(app => app.name.length > 15 ? app.name.substring(0, 15) + '...' : app.name),
        colors: chartColors,
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '13px',
                            fontWeight: 600,
                            color: '#a78bfa'
                        },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontWeight: 700,
                            color: '#fff',
                            formatter: function(val) {
                                return formatBytes(parseInt(val));
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Traffic',
                            fontSize: '11px',
                            fontWeight: 500,
                            color: '#a78bfa',
                            formatter: function(w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return formatBytes(total);
                            }
                        }
                    }
                }
            }
        },
        stroke: {
            width: 2,
            colors: ['rgba(15, 10, 31, 0.8)']
        },
        legend: {
            position: 'bottom',
            fontSize: '11px',
            fontWeight: 500,
            horizontalAlign: 'center',
            offsetY: 0,
            itemMargin: { horizontal: 8, vertical: 4 },
            labels: { colors: '#a78bfa' },
            markers: {
                width: 10,
                height: 10,
                radius: 3
            }
        },
        dataLabels: { enabled: false },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return formatBytes(val);
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: { fontSize: '10px' }
            }
        }]
    };

    applicationsChart = new ApexCharts(container, options);
    applicationsChart.render();
}

// Protocols Bar Chart - using server-side data
function createProtocolsChart() {
    const container = document.getElementById('protocolsChart');
    const protocols = dashboardData.topProtocols;
    if (!container || !protocols || protocols.length === 0) {
        if (container) {
            container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><p>No protocol data available</p></div>';
        }
        return;
    }

    const options = {
        chart: {
            type: 'bar',
            height: 320,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
            background: 'transparent',
            foreColor: '#a78bfa',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 8,
                columnWidth: '70%',
                distributed: true
            }
        },
        colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'],
        series: [{
            name: 'Traffic',
            data: protocols.map(p => p.bytes)
        }],
        xaxis: {
            categories: protocols.map(p => p.protocol),
            labels: {
                style: {
                    colors: '#a78bfa',
                    fontSize: '11px'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: {
                    colors: '#a78bfa',
                    fontSize: '11px'
                }
            }
        },
        legend: { show: false },
        dataLabels: { enabled: false },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return formatBytes(val);
                }
            }
        },
        grid: {
            borderColor: 'rgba(139, 92, 246, 0.1)',
            strokeDashArray: 4
        }
    };

    if (protocolsChart) {
        protocolsChart.destroy();
    }
    protocolsChart = new ApexCharts(container, options);
    protocolsChart.render();
}

// QoS Pie Chart - improved
function createQoSChart() {
    const container = document.getElementById('qosChart');
    if (!container || !dashboardData.topQoS || dashboardData.topQoS.length === 0) {
        return;
    }

    const chartColors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'];

    const options = {
        chart: {
            type: 'pie',
            height: 200,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
            background: 'transparent'
        },
        series: dashboardData.topQoS.map(q => parseInt(q.bytes)),
        labels: dashboardData.topQoS.map(q => q.dscp),
        colors: chartColors,
        legend: {
            position: 'right',
            fontSize: '10px',
            horizontalAlign: 'center',
            itemMargin: { horizontal: 4, vertical: 2 },
            labels: { colors: '#a78bfa' }
        },
        stroke: {
            width: 2,
            colors: ['rgba(15, 10, 31, 0.8)']
        },
        dataLabels: { enabled: false },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return formatBytes(val);
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: { position: 'bottom', fontSize: '9px' }
            }
        }]
    };

    qosChart = new ApexCharts(container, options);
    qosChart.render();
}

// Device Health Radial Chart
function createDeviceHealthChart() {
    const container = document.getElementById('deviceHealthChart');
    if (!container) return;

    const total = dashboardData.heatMapData.link_up + dashboardData.heatMapData.link_down + dashboardData.heatMapData.unknown;
    if (total === 0) return;

    const options = {
        chart: {
            type: 'radialBar',
            height: 160,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
            background: 'transparent'
        },
        series: [
            Math.round((dashboardData.heatMapData.link_up / total) * 100),
            Math.round((dashboardData.heatMapData.link_down / total) * 100),
            Math.round((dashboardData.heatMapData.unknown / total) * 100)
        ],
        labels: ['Online', 'Offline', 'Warning'],
        colors: ['#10B981', '#EF4444', '#F59E0B'],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '40%'
                },
                track: {
                    background: 'rgba(139, 92, 246, 0.1)'
                },
                dataLabels: {
                    name: {
                        fontSize: '10px',
                        color: '#a78bfa'
                    },
                    value: {
                        fontSize: '12px',
                        color: '#fff',
                        formatter: function(val) {
                            return val + '%';
                        }
                    },
                    total: {
                        show: true,
                        label: 'Devices',
                        fontSize: '10px',
                        color: '#a78bfa',
                        formatter: function() {
                            return total;
                        }
                    }
                }
            }
        }
    };

    deviceHealthChart = new ApexCharts(container, options);
    deviceHealthChart.render();
}

// Country Traffic Horizontal Bar Chart
function createCountryTrafficChart() {
    const container = document.getElementById('countryTrafficChart');
    if (!container || !dashboardData.trafficByCountry || dashboardData.trafficByCountry.length === 0) return;

    const countries = dashboardData.trafficByCountry.slice(0, 5);
    const chartColors = ['#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#06B6D4'];

    const options = {
        chart: {
            type: 'bar',
            height: 200,
            background: 'transparent',
            foreColor: '#a78bfa',
            toolbar: { show: false },
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4,
                barHeight: '65%',
                distributed: true
            }
        },
        colors: chartColors,
        series: [{
            name: 'Traffic',
            data: countries.map(c => c.bytes)
        }],
        xaxis: {
            categories: countries.map(c => c.country_name.length > 12 ? c.country_name.substring(0, 12) + '...' : c.country_name),
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: { colors: '#a78bfa', fontSize: '9px' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#a78bfa', fontSize: '10px' }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: { show: false },
        grid: {
            borderColor: 'rgba(139, 92, 246, 0.1)',
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val, opts) {
                    const country = countries[opts.dataPointIndex];
                    return `${formatBytes(val)} (${country.flows.toLocaleString()} flows)`;
                }
            }
        }
    };

    new ApexCharts(container, options).render();
}

// Initialize World Map
function initializeMap() {
    const mapContainer = document.getElementById('trafficMap');
    const loadingIndicator = document.getElementById('mapLoadingIndicator');
    if (!mapContainer) {
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        return;
    }

    // Initialize Leaflet map with optimized settings
    trafficMap = L.map('trafficMap', {
        center: [20, 0],
        zoom: 2,
        minZoom: 1,
        maxZoom: 10,
        preferCanvas: true,
        zoomControl: true,
        attributionControl: false
    });

    // Add dark themed tile layer with loading events
    const tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OSM &copy; CARTO',
        maxZoom: 19,
        crossOrigin: true
    });

    // Hide loading indicator when tiles are loaded
    tileLayer.on('load', function() {
        if (loadingIndicator) {
            loadingIndicator.style.opacity = '0';
            setTimeout(() => { loadingIndicator.style.display = 'none'; }, 300);
        }
    });

    tileLayer.addTo(trafficMap);

    // Add traffic markers
    dashboardData.trafficByCountry.forEach(country => {
        if (country.latitude && country.longitude) {
            const radius = Math.min(25, Math.max(8, Math.log10(country.bytes) * 3));

            const marker = L.circleMarker([country.latitude, country.longitude], {
                radius: radius,
                fillColor: window.monetxColors.primary,
                color: window.monetxColors.secondary,
                weight: 2,
                opacity: 0.9,
                fillOpacity: 0.6
            }).addTo(trafficMap);

            marker.bindPopup(`
                <div class="text-sm">
                    <strong class="text-blue-600">${country.country_name}</strong><br>
                    <span class="text-gray-600">Traffic: ${country.formatted_bytes}</span><br>
                    <span class="text-gray-500">Flows: ${country.flows.toLocaleString()}</span>
                </div>
            `);
        }
    });

    // Fix map size when container is resized or tab becomes visible
    const resizeObserver = new ResizeObserver(() => {
        if (trafficMap) {
            setTimeout(() => trafficMap.invalidateSize(), 100);
        }
    });
    resizeObserver.observe(mapContainer);

    // Also invalidate on window resize
    window.addEventListener('resize', () => {
        if (trafficMap) trafficMap.invalidateSize();
    });
}

// Initialize Sparklines for each device
function initializeSparklines() {
    if (!dashboardData.deviceBandwidth || !Array.isArray(dashboardData.deviceBandwidth)) return;

    dashboardData.deviceBandwidth.forEach(device => {
        const container = document.querySelector(`#sparkline-${device.id}`);
        if (!container) return;

        // Ensure sparkline is an array
        const sparklineData = Array.isArray(device.sparkline) ? device.sparkline : [];
        if (sparklineData.length === 0) {
            container.innerHTML = '<span class="text-xs text-gray-400">No data</span>';
            return;
        }

        const options = {
            chart: {
                type: 'area',
                height: 40,
                sparkline: { enabled: true },
                animations: {
                    enabled: true,
                    dynamicAnimation: { speed: 500 }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.1
                }
            },
            colors: ['#3B82F6'],
            series: [{
                name: 'Bandwidth',
                data: sparklineData.map(s => (s && s.total) ? s.total : 0)
            }],
            tooltip: {
                fixed: { enabled: false },
                x: { show: false },
                y: {
                    formatter: function(val) {
                        return formatBytes(val);
                    }
                }
            }
        };

        new ApexCharts(container, options).render();
    });
}

// Refresh functions
function refreshAllData() {
    const btn = document.querySelector('button[onclick="refreshAllData()"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Refreshing...';
    }

    // Reload page to get fresh data
    location.reload();
}

function refreshDeviceTable() {
    location.reload();
}
</script>
@endpush
