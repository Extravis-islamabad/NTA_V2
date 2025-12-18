@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Time Range Filter -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold gradient-text">Network Traffic Dashboard</h1>
            <p class="text-sm text-purple-300/60 mt-1">Real-time network analytics and monitoring</p>
        </div>
        <div class="flex items-center gap-4">
            <form method="GET" id="timeRangeForm" class="flex items-center gap-2">
                <span class="text-sm text-purple-300/70">Time Range:</span>
                <select id="globalTimeRange" name="range" onchange="document.getElementById('timeRangeForm').submit()"
                    class="px-4 py-2 border border-purple-500/30 rounded-lg shadow-sm bg-space-dark/80 text-sm font-medium text-purple-200 hover:bg-space-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 cursor-pointer">
                    <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                    <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                    <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                </select>
            </form>
            <button onclick="refreshAllData()" class="btn-gradient inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total Devices -->
        <div class="glass-card rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-300/70 font-medium">Total Devices</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_devices'] }}</p>
                </div>
                <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Online Devices -->
        <div class="glass-card rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-300/70 font-medium">Online</p>
                    <p class="text-3xl font-bold text-green-400 mt-1">{{ $stats['online_devices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Offline Devices -->
        <div class="glass-card rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-300/70 font-medium">Offline</p>
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
        <div class="glass-card rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-300/70 font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-blue-400 mt-1">{{ number_format($stats['total_flows']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Alarms -->
        <div class="glass-card rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-300/70 font-medium">Active Alarms</p>
                    <p class="text-3xl font-bold text-orange-400 mt-1">{{ $stats['active_alarms'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center {{ $stats['active_alarms'] > 0 ? 'pulse-glow' : '' }}">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- World Map and Top Applications Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- World Traffic Map -->
        <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20 bg-gradient-to-r from-purple-600 to-pink-600">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Global Traffic Distribution
                </h3>
            </div>
            <div class="p-4">
                <div id="trafficMap" class="traffic-map rounded-lg" style="height: 250px; min-height: 220px;"></div>
                <div class="mt-3 flex justify-between items-center text-sm border-b border-purple-500/20 pb-3">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                            <span class="text-purple-300/70">Traffic Origin</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-purple-300/70">Traffic Destination</span>
                        </span>
                    </div>
                    <span class="text-purple-300/60">{{ $trafficByCountry->count() }} countries</span>
                </div>
                <!-- Traffic by Country Chart -->
                <div class="mt-3">
                    @if($trafficByCountry->isEmpty())
                        <p class="text-sm text-purple-300/50 text-center py-4">No geographic data available</p>
                    @else
                        <div id="countryTrafficChart" style="height: 140px;"></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Applications Donut Chart -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20 bg-gradient-to-r from-emerald-600 to-teal-600">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Top Applications
                </h3>
            </div>
            <div class="p-4">
                @if($topApplications->isEmpty())
                    <div class="flex flex-col items-center justify-center h-64 text-purple-300/50">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-sm">No application data available</p>
                    </div>
                @else
                    <div id="applicationsChart" style="height: 280px;"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bandwidth and Traffic Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Protocols Chart -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Protocol Distribution
                </h3>
            </div>
            <div class="p-4">
                <div id="protocolsChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- QoS Distribution Chart -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    QoS Distribution (DSCP)
                </h3>
            </div>
            <div class="p-4">
                @if($topQoS->isEmpty())
                    <div class="flex flex-col items-center justify-center h-64 text-purple-300/50">
                        <p class="text-sm">No QoS data available</p>
                    </div>
                @else
                    <div id="qosChart" style="height: 300px;"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Device Status and Top Sources/Destinations Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Device Status Heatmap -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                    Device Health
                </h3>
            </div>
            <div class="p-6">
                <div id="deviceHealthChart" style="height: 200px;"></div>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-purple-300/70">Online</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['link_up'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-purple-300/70">Offline</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['link_down'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            <span class="text-purple-300/70">Warning</span>
                        </div>
                        <span class="font-semibold text-white">{{ $heatMapData['unknown'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Sources -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Top Sources
                </h3>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                @if($topSources->isEmpty())
                    <p class="text-sm text-purple-300/50 text-center py-8">No source data available</p>
                @else
                    <div class="space-y-2">
                        @foreach($topSources as $source)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-purple-500/10 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="font-mono text-sm text-purple-200 truncate">{{ $source['ip'] }}</span>
                                @if($source['country_code'])
                                    <span class="text-xs text-purple-400/60">({{ $source['country_code'] }})</span>
                                @endif
                            </div>
                            <span class="text-sm font-semibold text-blue-400">{{ $source['formatted_bytes'] }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Destinations -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    Top Destinations
                </h3>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                @if($topDestinations->isEmpty())
                    <p class="text-sm text-purple-300/50 text-center py-8">No destination data available</p>
                @else
                    <div class="space-y-2">
                        @foreach($topDestinations as $dest)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-purple-500/10 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="font-mono text-sm text-purple-200 truncate">{{ $dest['ip'] }}</span>
                                @if($dest['country_code'])
                                    <span class="text-xs text-purple-400/60">({{ $dest['country_code'] }})</span>
                                @endif
                            </div>
                            <span class="text-sm font-semibold text-emerald-400">{{ $dest['formatted_bytes'] }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Device Bandwidth with Sparklines and Conversations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Device Bandwidth Table -->
        <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Device Bandwidth
                </h3>
                <button onclick="refreshDeviceTable()" class="text-sm text-purple-400 hover:text-purple-300">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-purple-500/20">
                    <thead class="bg-space-medium/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Device</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Bandwidth</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Trend</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-500/10">
                        @foreach($devices as $device)
                        <tr class="table-row-hover transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('devices.show', $device) }}" class="text-purple-400 hover:text-purple-300 font-medium">
                                    {{ $device->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-300/70 font-mono">{{ $device->ip_address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-300/70">{{ ucfirst(str_replace('_', ' ', $device->type)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                @php
                                    $bw = $deviceBandwidth->firstWhere('id', $device->id);
                                @endphp
                                {{ $bw ? ($bw['bandwidth']['total_formatted'] ?? '0 B') : '0 B' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div id="sparkline-{{ $device->id }}" class="sparkline-container"></div>
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

        <!-- Top Conversations -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-500/20">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Top Conversations
                </h3>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                @if($topConversations->isEmpty())
                    <p class="text-sm text-purple-300/50 text-center py-8">No conversation data available</p>
                @else
                    <div class="space-y-3">
                        @foreach($topConversations as $conv)
                        <div class="p-3 rounded-lg bg-purple-500/10 hover:bg-purple-500/20 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $conv['app_color'] }}">
                                        {{ strtoupper(substr($conv['application'], 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-white">{{ $conv['application'] }}</span>
                                </div>
                                <span class="text-sm font-semibold text-blue-400">{{ $conv['formatted_bytes'] }}</span>
                            </div>
                            <div class="flex items-center text-xs text-purple-300/60 gap-1">
                                <span class="font-mono truncate max-w-[100px]">{{ $conv['source'] }}</span>
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <span class="font-mono truncate max-w-[100px]">{{ $conv['destination'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Alarms -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-purple-500/20 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Recent Alarms
            </h3>
            <a href="{{ route('alarms.index') }}" class="text-sm text-purple-400 hover:text-purple-300">View All</a>
        </div>
        <div class="p-6" id="alarmsContainer">
            @if($recentAlarms->isEmpty())
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-purple-300/60">No active alarms - All systems operational</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recentAlarms as $alarm)
                    <div class="border-l-4 {{ $alarm->severity === 'critical' ? 'border-red-500 bg-red-500/10' : ($alarm->severity === 'warning' ? 'border-yellow-500 bg-yellow-500/10' : 'border-blue-500 bg-blue-500/10') }} rounded-r-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ $alarm->title }}</p>
                                <p class="mt-1 text-sm text-purple-300/60">{{ Str::limit($alarm->description, 50) }}</p>
                                <p class="mt-1 text-xs text-purple-400/50">{{ $alarm->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $alarm->severity === 'critical' ? 'bg-red-500/20 text-red-400' : ($alarm->severity === 'warning' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-blue-500/20 text-blue-400') }}">
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
    if (dashboardData.topApplications.length === 0) {
        document.getElementById('applicationsChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>No application data</p></div>';
        return;
    }

    // Take top 6 apps for clean display
    const topApps = dashboardData.topApplications.slice(0, 6);

    // Modern gradient colors
    const chartColors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EC4899', '#06B6D4'];

    const options = {
        chart: {
            type: 'donut',
            height: 280,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
        },
        series: topApps.map(app => app.bytes),
        labels: topApps.map(app => app.name.length > 12 ? app.name.substring(0, 12) + '...' : app.name),
        colors: chartColors,
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '12px',
                            fontWeight: 600
                        },
                        value: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 700,
                            formatter: function(val) {
                                return formatBytes(parseInt(val));
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '11px',
                            fontWeight: 500,
                            color: '#6b7280',
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
            colors: ['#fff']
        },
        legend: {
            position: 'bottom',
            fontSize: '11px',
            fontWeight: 500,
            horizontalAlign: 'center',
            offsetY: 5,
            itemMargin: { horizontal: 8, vertical: 4 },
            markers: {
                width: 10,
                height: 10,
                radius: 3
            }
        },
        dataLabels: { enabled: false },
        tooltip: {
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

    applicationsChart = new ApexCharts(document.querySelector("#applicationsChart"), options);
    applicationsChart.render();
}

// Protocols Bar Chart - using server-side data
function createProtocolsChart() {
    const protocols = dashboardData.topProtocols;
    if (!protocols || protocols.length === 0) {
        document.getElementById('protocolsChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>No protocol data available</p></div>';
        return;
    }

    const options = {
        chart: {
            type: 'bar',
            height: 300,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
            toolbar: { show: true }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 6,
                columnWidth: '55%',
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
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: {
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        legend: { show: false },
        dataLabels: { enabled: false },
        tooltip: {
            y: {
                formatter: function(val) {
                    return formatBytes(val);
                }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        }
    };

    if (protocolsChart) {
        protocolsChart.destroy();
    }
    protocolsChart = new ApexCharts(document.querySelector("#protocolsChart"), options);
    protocolsChart.render();
}

// QoS Pie Chart - improved
function createQoSChart() {
    if (!dashboardData.topQoS || dashboardData.topQoS.length === 0) {
        const qosContainer = document.getElementById('qosChart');
        if (qosContainer) {
            qosContainer.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>No QoS data available</p></div>';
        }
        return;
    }

    const chartColors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'];

    const options = {
        chart: {
            type: 'pie',
            height: 300,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
        },
        series: dashboardData.topQoS.map(q => parseInt(q.bytes)),
        labels: dashboardData.topQoS.map(q => q.dscp),
        colors: chartColors,
        legend: {
            position: 'bottom',
            fontSize: '10px',
            horizontalAlign: 'center',
            itemMargin: { horizontal: 6, vertical: 2 }
        },
        dataLabels: { enabled: false },
        tooltip: {
            y: {
                formatter: function(val) {
                    return formatBytes(val);
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: { fontSize: '9px' }
            }
        }]
    };

    qosChart = new ApexCharts(document.querySelector("#qosChart"), options);
    qosChart.render();
}

// Device Health Radial Chart
function createDeviceHealthChart() {
    const total = dashboardData.heatMapData.link_up + dashboardData.heatMapData.link_down + dashboardData.heatMapData.unknown;
    if (total === 0) return;

    const options = {
        chart: {
            type: 'radialBar',
            height: 200,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
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
                dataLabels: {
                    name: {
                        fontSize: '12px'
                    },
                    value: {
                        fontSize: '14px',
                        formatter: function(val) {
                            return val + '%';
                        }
                    },
                    total: {
                        show: true,
                        label: 'Total',
                        formatter: function() {
                            return total + ' devices';
                        }
                    }
                }
            }
        }
    };

    deviceHealthChart = new ApexCharts(document.querySelector("#deviceHealthChart"), options);
    deviceHealthChart.render();
}

// Country Traffic Horizontal Bar Chart
function createCountryTrafficChart() {
    const container = document.getElementById('countryTrafficChart');
    if (!container || !dashboardData.trafficByCountry || dashboardData.trafficByCountry.length === 0) return;

    const countries = dashboardData.trafficByCountry.slice(0, 6);
    const chartColors = ['#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#06B6D4', '#EF4444'];

    const options = {
        chart: {
            type: 'bar',
            height: 140,
            background: 'transparent',
            foreColor: '#a78bfa',
            toolbar: { show: false },
            fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4,
                barHeight: '70%',
                distributed: true
            }
        },
        colors: chartColors,
        series: [{
            name: 'Traffic',
            data: countries.map(c => c.bytes)
        }],
        xaxis: {
            categories: countries.map(c => c.country_name.length > 15 ? c.country_name.substring(0, 15) + '...' : c.country_name),
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: { colors: '#9ca3af', fontSize: '10px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#a78bfa', fontSize: '11px' }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return formatBytes(val);
            },
            style: { fontSize: '10px', colors: ['#fff'] }
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
    if (!mapContainer) return;

    // Initialize Leaflet map
    trafficMap = L.map('trafficMap', {
        center: [20, 0],
        zoom: 2,
        minZoom: 1,
        maxZoom: 10
    });

    // Add dark themed tile layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>'
    }).addTo(trafficMap);

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
