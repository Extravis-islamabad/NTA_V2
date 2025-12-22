@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-cyan-400 hover:text-cyan-300 flex items-center gap-2 text-sm font-medium mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reports
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-cyan-500/20 border border-cyan-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Traffic Analysis Report</h2>
                <p class="text-gray-400">Comprehensive bandwidth and protocol analysis</p>
            </div>
        </div>
    </div>

    <!-- Report Parameters -->
    <div class="glass-card mb-6">
        <div class="px-6 py-4 border-b border-white/10">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Report Parameters
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.traffic') }}" id="reportForm" class="space-y-4">
                <!-- Quick Range Buttons -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-sm text-gray-400 mr-2 self-center">Quick Select:</span>
                    <button type="button" onclick="setQuickRange('1hour')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-cyan-500/20 hover:border-cyan-500/30 hover:text-cyan-400 transition">
                        Last Hour
                    </button>
                    <button type="button" onclick="setQuickRange('6hours')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-cyan-500/20 hover:border-cyan-500/30 hover:text-cyan-400 transition">
                        Last 6 Hours
                    </button>
                    <button type="button" onclick="setQuickRange('24hours')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-cyan-500/20 hover:border-cyan-500/30 hover:text-cyan-400 transition">
                        Last 24 Hours
                    </button>
                    <button type="button" onclick="setQuickRange('7days')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-cyan-500/20 hover:border-cyan-500/30 hover:text-cyan-400 transition">
                        Last 7 Days
                    </button>
                    <button type="button" onclick="setQuickRange('30days')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:bg-cyan-500/20 hover:border-cyan-500/30 hover:text-cyan-400 transition">
                        Last 30 Days
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ request('start_date', now()->subDay()->format('Y-m-d\TH:i')) }}"
                               class="w-full glass-input rounded-lg px-4 py-2.5 text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">End Date & Time</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ request('end_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full glass-input rounded-lg px-4 py-2.5 text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Device</label>
                        <select name="device_id" class="w-full glass-input rounded-lg px-4 py-2.5 text-white">
                            <option value="">All Devices</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }} ({{ $device->ip_address }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="flex flex-wrap gap-2 w-full">
                            <button type="submit" id="generateBtn" class="flex-1 min-w-[120px] px-4 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium flex items-center justify-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="btn-text">Generate</span>
                                <svg class="w-4 h-4 animate-spin hidden loading-spinner" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('reports.export') }}?type=traffic&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                               class="px-4 py-2.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 rounded-lg font-medium flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                CSV
                            </a>
                            @if(isset($totalBytes))
                            <a href="{{ route('reports.traffic.pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                               class="px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg font-medium flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                PDF
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($totalBytes))
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Report Period</p>
                    <p class="text-lg font-bold text-white mt-1">{{ $start->format('M d') }} - {{ $end->format('M d, Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-cyan-400 mt-1">{{ number_format($totalFlows) }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Traffic</p>
                    <p class="text-3xl font-bold text-emerald-400 mt-1">
                        @php
                            if ($totalBytes >= 1099511627776) {
                                echo round($totalBytes / 1099511627776, 2) . ' TB';
                            } elseif ($totalBytes >= 1073741824) {
                                echo round($totalBytes / 1073741824, 2) . ' GB';
                            } elseif ($totalBytes >= 1048576) {
                                echo round($totalBytes / 1048576, 2) . ' MB';
                            } else {
                                echo round($totalBytes / 1024, 2) . ' KB';
                            }
                        @endphp
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Packets</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1">{{ number_format($totalPackets) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Traffic Time Series Chart -->
    @if(isset($trafficTimeSeries) && $trafficTimeSeries->isNotEmpty())
    <div class="glass-card mb-6">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                Traffic Over Time
            </h3>
            <span class="px-3 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-semibold rounded-full border border-cyan-500/30">
                {{ $trafficTimeSeries->count() }} Data Points
            </span>
        </div>
        <div class="p-6">
            <div id="trafficTimeSeriesChart" style="height: 320px;"></div>
        </div>
    </div>
    @endif

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Applications Chart -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Top Applications
                </h3>
                <span class="px-2.5 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-semibold rounded-full border border-cyan-500/30">
                    {{ $topApplications->count() }} Apps
                </span>
            </div>
            <div class="p-6">
                @if($topApplications->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No application data available</p>
                    </div>
                @else
                    <div id="applicationsChart" style="height: 260px;"></div>
                @endif
            </div>
        </div>

        <!-- Top Protocols Chart -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Protocol Distribution
                </h3>
                <span class="px-2.5 py-1 bg-purple-500/20 text-purple-400 text-xs font-semibold rounded-full border border-purple-500/30">
                    {{ $topProtocols->count() }} Protocols
                </span>
            </div>
            <div class="p-6">
                @if($topProtocols->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No protocol data available</p>
                    </div>
                @else
                    <div id="protocolsChart" style="height: 260px;"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Applications Table -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)]">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Top Applications Details
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Application</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Flows</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Traffic</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topApplications as $index => $app)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                         style="background: {{ ['#22d3ee', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#ef4444', '#14b8a6', '#f97316', '#6366f1'][$index % 10] }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="font-medium text-white">{{ $app->application }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($app->flow_count) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-white">
                                @php
                                    $bytes = $app->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($app->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-20 bg-white/10 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-cyan-500" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-12 text-right">{{ number_format($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No application data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Protocols Table -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)]">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Protocol Distribution Details
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Protocol</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Flows</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Traffic</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topProtocols as $index => $protocol)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                         style="background: {{ ['#8b5cf6', '#a855f7', '#22d3ee', '#10b981', '#f59e0b', '#ec4899', '#ef4444', '#14b8a6', '#f97316', '#6366f1'][$index % 10] }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="font-medium text-white uppercase">{{ $protocol->protocol }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($protocol->flow_count) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-white">
                                @php
                                    $bytes = $protocol->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($protocol->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-20 bg-white/10 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-purple-500" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-12 text-right">{{ number_format($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No protocol data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="glass-card p-12 text-center">
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-cyan-500/20 to-purple-500/20 rounded-full flex items-center justify-center mb-6 border border-cyan-500/30">
            <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Traffic Analysis Report</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">Select a date range and click "Generate" to analyze your network traffic patterns, applications, and protocol distribution.</p>

        <!-- Preview of what the report shows -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-2xl mx-auto mb-6">
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Total Flows</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Bandwidth</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Applications</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Protocols</p>
            </div>
        </div>

        <button onclick="document.querySelector('form').submit()" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium transition">
            Generate Report Now
        </button>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Quick range selector
function setQuickRange(range) {
    const now = new Date();
    let start = new Date();

    switch(range) {
        case '1hour':
            start.setHours(now.getHours() - 1);
            break;
        case '6hours':
            start.setHours(now.getHours() - 6);
            break;
        case '24hours':
            start.setDate(now.getDate() - 1);
            break;
        case '7days':
            start.setDate(now.getDate() - 7);
            break;
        case '30days':
            start.setDate(now.getDate() - 30);
            break;
    }

    // Format for datetime-local input
    const formatDateTime = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };

    document.getElementById('start_date').value = formatDateTime(start);
    document.getElementById('end_date').value = formatDateTime(now);
}

// Loading state for form submission
document.getElementById('reportForm').addEventListener('submit', function() {
    const btn = document.getElementById('generateBtn');
    btn.querySelector('.btn-text').textContent = 'Generating...';
    btn.querySelector('.loading-spinner').classList.remove('hidden');
    btn.disabled = true;
});

document.addEventListener('DOMContentLoaded', function() {
    function formatBytes(bytes) {
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
        if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return bytes + ' B';
    }

    // Chart theme configuration for dark mode
    const chartTheme = {
        mode: 'dark',
        palette: 'palette1',
        monochrome: { enabled: false }
    };

    @if(isset($trafficTimeSeries) && $trafficTimeSeries->isNotEmpty())
    // Traffic Time Series Chart
    const timeSeriesEl = document.getElementById('trafficTimeSeriesChart');
    if (timeSeriesEl) {
        const timeSeriesData = @json($trafficTimeSeries);
        new ApexCharts(timeSeriesEl, {
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: true, tools: { download: true, selection: true, zoom: true, zoomin: true, zoomout: true, pan: true } },
                background: 'transparent',
                foreColor: '#9ca3af'
            },
            theme: chartTheme,
            series: [{
                name: 'Traffic',
                data: timeSeriesData.map(item => item.total_bytes)
            }],
            xaxis: {
                categories: timeSeriesData.map(item => {
                    const date = new Date(item.time_bucket);
                    return date.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                }),
                labels: { style: { fontSize: '10px', colors: '#9ca3af' } },
                axisBorder: { color: 'rgba(255,255,255,0.1)' },
                axisTicks: { color: 'rgba(255,255,255,0.1)' }
            },
            yaxis: {
                labels: {
                    formatter: formatBytes,
                    style: { fontSize: '11px', colors: '#9ca3af' }
                }
            },
            colors: ['#22d3ee'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.1,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#22d3ee', opacity: 0.5 },
                        { offset: 100, color: '#22d3ee', opacity: 0.05 }
                    ]
                }
            },
            stroke: { curve: 'smooth', width: 2 },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: formatBytes },
                theme: 'dark'
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.1)',
                strokeDashArray: 4
            }
        }).render();
    }
    @endif

    @if(isset($topApplications) && $topApplications->isNotEmpty())
    // Applications Chart - Donut
    const appEl = document.getElementById('applicationsChart');
    if (appEl) {
        try {
            const appData = @json($topApplications->pluck('total_bytes')->values()->toArray());
            const appLabels = @json($topApplications->pluck('application')->values()->toArray());

            // Ensure we have valid data
            if (appData && appData.length > 0 && appLabels && appLabels.length > 0) {
                new ApexCharts(appEl, {
                    chart: {
                        type: 'donut',
                        height: 260,
                        fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                        background: 'transparent',
                        foreColor: '#9ca3af'
                    },
                    theme: chartTheme,
                    series: appData,
                    labels: appLabels,
                    colors: ['#22d3ee', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#ef4444', '#14b8a6', '#f97316', '#6366f1'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: { show: true, color: '#fff' },
                                    value: {
                                        show: true,
                                        color: '#9ca3af',
                                        formatter: formatBytes
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        color: '#9ca3af',
                                        formatter: function(w) {
                                            return formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0));
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'right',
                        fontSize: '11px',
                        labels: { colors: '#9ca3af' },
                        markers: { radius: 3 }
                    },
                    dataLabels: { enabled: false },
                    tooltip: {
                        y: { formatter: formatBytes },
                        theme: 'dark'
                    },
                    stroke: { show: false }
                }).render();
            } else {
                appEl.innerHTML = '<div class="text-center py-8 text-gray-500">No application data to display</div>';
            }
        } catch (e) {
            console.error('Applications chart error:', e);
            appEl.innerHTML = '<div class="text-center py-8 text-gray-500">Error loading chart</div>';
        }
    }
    @endif

    @if(isset($topProtocols) && $topProtocols->isNotEmpty())
    // Protocols Chart - Horizontal Bar
    const protocolEl = document.getElementById('protocolsChart');
    if (protocolEl) {
        new ApexCharts(protocolEl, {
            chart: {
                type: 'bar',
                height: 260,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#9ca3af'
            },
            theme: chartTheme,
            series: [{
                name: 'Traffic',
                data: {!! json_encode($topProtocols->pluck('total_bytes')->toArray()) !!}
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '60%',
                    distributed: true
                }
            },
            xaxis: {
                labels: {
                    formatter: formatBytes,
                    style: { fontSize: '10px', colors: '#9ca3af' }
                },
                axisBorder: { color: 'rgba(255,255,255,0.1)' },
                axisTicks: { color: 'rgba(255,255,255,0.1)' }
            },
            yaxis: {
                categories: {!! json_encode($topProtocols->pluck('protocol')->map(fn($p) => strtoupper($p))->toArray()) !!},
                labels: { style: { fontSize: '11px', colors: '#9ca3af' } }
            },
            colors: ['#8b5cf6', '#a855f7', '#c084fc', '#d8b4fe', '#e9d5ff', '#f3e8ff'],
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: formatBytes },
                theme: 'dark'
            },
            grid: {
                borderColor: 'rgba(255,255,255,0.1)',
                xaxis: { lines: { show: true } }
            },
            legend: { show: false }
        }).render();
    }
    @endif
});
</script>
@endpush
