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
            <div class="w-14 h-14 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Top Talkers Report</h2>
                <p class="text-gray-400">Identify your heaviest bandwidth consumers</p>
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
            <form method="GET" action="{{ route('reports.talkers') }}" id="reportForm" class="space-y-4">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="btn-text">Analyze</span>
                                <svg class="w-4 h-4 animate-spin hidden loading-spinner" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('reports.export') }}?type=talkers&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                               class="px-4 py-2.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 rounded-lg font-medium flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                CSV
                            </a>
                            @if(isset($topSources))
                            <a href="{{ route('reports.talkers.pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
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

    @if(isset($topSources))
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
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Unique Sources</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1">{{ $topSources->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Sources Chart -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                    Top Sources by Traffic
                </h3>
                <span class="px-2.5 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-semibold rounded-full border border-cyan-500/30">
                    {{ $topSources->count() }} IPs
                </span>
            </div>
            <div class="p-6">
                @if($topSources->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No source data available</p>
                    </div>
                @else
                    <div id="sourcesChart" style="height: 300px;"></div>
                @endif
            </div>
        </div>

        <!-- Top Destinations Chart -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                    Top Destinations by Traffic
                </h3>
                <span class="px-2.5 py-1 bg-purple-500/20 text-purple-400 text-xs font-semibold rounded-full border border-purple-500/30">
                    {{ $topDestinations->count() }} IPs
                </span>
            </div>
            <div class="p-6">
                @if($topDestinations->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No destination data available</p>
                    </div>
                @else
                    <div id="destinationsChart" style="height: 300px;"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Sources Table -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)]">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                    Top 20 Source IPs
                </h3>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full">
                    <thead class="sticky top-0">
                        <tr class="border-b border-white/10">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Source IP</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Traffic</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Flows</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topSources as $index => $source)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                     style="background: {{ ['#22d3ee', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#ef4444', '#14b8a6', '#f97316', '#6366f1'][$index % 10] }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm text-cyan-400">{{ $source->source_ip }}</span>
                                    <button onclick="copyToClipboard('{{ $source->source_ip }}')" class="p-1 hover:bg-white/10 rounded transition opacity-50 hover:opacity-100" title="Copy IP">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-white">
                                @php
                                    $bytes = $source->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($source->flow_count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($source->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-16 bg-white/10 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-cyan-500" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-12 text-right">{{ number_format($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No source data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Destinations Table -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)]">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                    Top 20 Destination IPs
                </h3>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full">
                    <thead class="sticky top-0">
                        <tr class="border-b border-white/10">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Destination IP</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Traffic</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Flows</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($topDestinations as $index => $dest)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                     style="background: {{ ['#8b5cf6', '#a855f7', '#22d3ee', '#10b981', '#f59e0b', '#ec4899', '#ef4444', '#14b8a6', '#f97316', '#6366f1'][$index % 10] }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm text-purple-400">{{ $dest->destination_ip }}</span>
                                    <button onclick="copyToClipboard('{{ $dest->destination_ip }}')" class="p-1 hover:bg-white/10 rounded transition opacity-50 hover:opacity-100" title="Copy IP">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-white">
                                @php
                                    $bytes = $dest->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($dest->flow_count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-16 bg-white/10 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-purple-500" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-12 text-right">{{ number_format($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No destination data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Conversations Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)] flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Top 25 Conversations
            </h3>
            <span class="px-2.5 py-1 bg-blue-500/20 text-blue-400 text-xs font-semibold rounded-full border border-blue-500/30">
                {{ $topConversations->count() }} Conversations
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Source IP</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]"></th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Destination IP</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Protocol</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Traffic</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Packets</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">Flows</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase bg-[var(--bg-input)]">% Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topConversations as $index => $conv)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/10 text-gray-300 text-xs font-bold">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm text-cyan-400">{{ $conv->source_ip }}</span>
                                <button onclick="copyToClipboard('{{ $conv->source_ip }}')" class="p-1 hover:bg-white/10 rounded transition opacity-50 hover:opacity-100" title="Copy IP">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <svg class="w-5 h-5 text-gray-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm text-purple-400">{{ $conv->destination_ip }}</span>
                                <button onclick="copyToClipboard('{{ $conv->destination_ip }}')" class="p-1 hover:bg-white/10 rounded transition opacity-50 hover:opacity-100" title="Copy IP">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if(strtoupper($conv->protocol) == 'TCP') bg-blue-500/20 text-blue-400 border border-blue-500/30
                                @elseif(strtoupper($conv->protocol) == 'UDP') bg-green-500/20 text-green-400 border border-green-500/30
                                @elseif(strtoupper($conv->protocol) == 'ICMP') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                @else bg-gray-500/20 text-gray-400 border border-gray-500/30
                                @endif">
                                {{ strtoupper($conv->protocol) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-white">
                            @php
                                $bytes = $conv->total_bytes;
                                if ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($conv->total_packets) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-400">{{ number_format($conv->flow_count) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            @php $percent = $totalBytes > 0 ? ($conv->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 bg-white/10 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-blue-500" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-14 text-right">{{ number_format($percent, 2) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-500">No conversation data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="glass-card p-12 text-center">
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-500/20 to-cyan-500/20 rounded-full flex items-center justify-center mb-6 border border-purple-500/30">
            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Analyze Your Top Talkers</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">Select a date range and click "Analyze" to identify your heaviest bandwidth consumers and most active conversations.</p>

        <!-- Preview of what the report shows -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-2xl mx-auto mb-6">
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Top Sources</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Top Destinations</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Conversations</p>
            </div>
            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-400">Traffic Charts</p>
            </div>
        </div>

        <button onclick="document.querySelector('form').submit()" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg font-medium transition">
            Start Analysis
        </button>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show a brief toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 px-4 py-2 bg-emerald-500 text-white rounded-lg shadow-lg z-50 animate-fade-in';
        toast.textContent = 'IP copied to clipboard';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

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
    btn.querySelector('.btn-text').textContent = 'Analyzing...';
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

    const chartTheme = {
        mode: 'dark',
        palette: 'palette1',
        monochrome: { enabled: false }
    };

    @if(isset($topSources) && $topSources->isNotEmpty())
    // Sources Chart
    const sourcesEl = document.getElementById('sourcesChart');
    if (sourcesEl) {
        new ApexCharts(sourcesEl, {
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#9ca3af'
            },
            theme: chartTheme,
            series: [{
                name: 'Traffic',
                data: {!! json_encode($topSources->take(10)->pluck('total_bytes')->toArray()) !!}
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '65%',
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
                categories: {!! json_encode($topSources->take(10)->pluck('source_ip')->toArray()) !!},
                labels: {
                    style: { fontSize: '10px', fontFamily: 'monospace', colors: '#22d3ee' }
                }
            },
            colors: ['#22d3ee', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#ef4444', '#14b8a6', '#f97316', '#6366f1'],
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

    @if(isset($topDestinations) && $topDestinations->isNotEmpty())
    // Destinations Chart
    const destEl = document.getElementById('destinationsChart');
    if (destEl) {
        new ApexCharts(destEl, {
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#9ca3af'
            },
            theme: chartTheme,
            series: [{
                name: 'Traffic',
                data: {!! json_encode($topDestinations->take(10)->pluck('total_bytes')->toArray()) !!}
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '65%',
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
                categories: {!! json_encode($topDestinations->take(10)->pluck('destination_ip')->toArray()) !!},
                labels: {
                    style: { fontSize: '10px', fontFamily: 'monospace', colors: '#a855f7' }
                }
            },
            colors: ['#8b5cf6', '#a855f7', '#c084fc', '#22d3ee', '#10b981', '#f59e0b', '#ec4899', '#ef4444', '#14b8a6', '#f97316'],
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

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush
