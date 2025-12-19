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
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Device Inventory Report</h2>
                    <p class="text-gray-400">Complete overview of all monitored network devices</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.export') }}?type=devices"
                   class="px-4 py-2.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 rounded-lg font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    CSV
                </a>
                <a href="{{ route('reports.devices.pdf') }}"
                   class="px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Devices</p>
                    <p class="text-3xl font-bold text-cyan-400 mt-1">{{ $devices->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Online Devices</p>
                    <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $devices->where('status', 'online')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                @php $onlinePercent = $devices->count() > 0 ? ($devices->where('status', 'online')->count() / $devices->count()) * 100 : 0; @endphp
                <div class="w-full bg-white/10 rounded-full h-2">
                    <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $onlinePercent }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($onlinePercent, 1) }}% availability</p>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Interfaces</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1">{{ $devices->sum('interface_count') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-amber-400 mt-1">{{ number_format($devices->sum('flow_count')) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Distribution Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Device Type Chart -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Device Types
                </h3>
            </div>
            <div class="p-6">
                @php
                    $typeCounts = $devices->groupBy('type')->map->count();
                @endphp
                @if($typeCounts->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No devices to display</p>
                    </div>
                @else
                    <div id="deviceTypeChart" style="height: 220px;"></div>
                @endif
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Status Overview
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $onlineCount = $devices->where('status', 'online')->count();
                        $offlineCount = $devices->where('status', 'offline')->count();
                        $warningCount = $devices->where('status', 'warning')->count();
                        $total = $devices->count() ?: 1;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="font-medium text-gray-300">Online</span>
                            </div>
                            <span class="text-emerald-400 font-bold">{{ $onlineCount }}</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-3">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: {{ ($onlineCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="font-medium text-gray-300">Offline</span>
                            </div>
                            <span class="text-red-400 font-bold">{{ $offlineCount }}</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-3">
                            <div class="h-3 rounded-full bg-red-500" style="width: {{ ($offlineCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span class="font-medium text-gray-300">Warning</span>
                            </div>
                            <span class="text-amber-400 font-bold">{{ $warningCount }}</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-3">
                            <div class="h-3 rounded-full bg-amber-500" style="width: {{ ($warningCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Devices by Flows -->
        <div class="glass-card">
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Top Devices by Flows
                </h3>
            </div>
            <div class="p-6">
                @if($devices->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No devices to display</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($devices->sortByDesc('flow_count')->take(5) as $device)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                     style="background: {{ ['#22d3ee', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899'][$loop->index % 5] }}">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <a href="{{ route('devices.show', $device) }}" class="font-medium text-white hover:text-cyan-400 transition text-sm">
                                        {{ $device->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 font-mono">{{ $device->ip_address }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-cyan-400">{{ number_format($device->flow_count) }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Device List Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 bg-[var(--bg-input)]">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Device Inventory Details
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Location</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Interfaces</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Flows</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($devices as $device)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $device->status === 'online' ? 'bg-emerald-500/20' : ($device->status === 'warning' ? 'bg-amber-500/20' : 'bg-red-500/20') }}">
                                    <svg class="w-5 h-5 {{ $device->status === 'online' ? 'text-emerald-400' : ($device->status === 'warning' ? 'text-amber-400' : 'text-red-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('devices.show', $device) }}" class="font-medium text-cyan-400 hover:text-cyan-300 transition">
                                        {{ $device->name }}
                                    </a>
                                    @if($device->device_group)
                                        <p class="text-xs text-gray-500">{{ $device->device_group }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm text-gray-300">{{ $device->ip_address }}</span>
                                <button onclick="copyToClipboard('{{ $device->ip_address }}')" class="p-1 hover:bg-white/10 rounded transition opacity-50 hover:opacity-100" title="Copy IP">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                {{ ucfirst(str_replace('_', ' ', $device->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            {{ $device->location ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-medium text-white">{{ $device->interface_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-bold text-purple-400">{{ number_format($device->flow_count) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($device->status === 'online')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                    Online
                                </span>
                            @elseif($device->status === 'warning')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                    Warning
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                    Offline
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('devices.show', $device) }}"
                               class="p-2 hover:bg-cyan-500/20 rounded-lg transition inline-flex" title="View Details">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 mb-4">No devices found</p>
                            <a href="{{ route('devices.create') }}" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg text-sm transition">
                                Add First Device
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 px-4 py-2 bg-emerald-500 text-white rounded-lg shadow-lg z-50';
        toast.textContent = 'IP copied to clipboard';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @php
        $typeCounts = $devices->groupBy('type')->map->count();
    @endphp

    @if($typeCounts->isNotEmpty())
    // Device Type Chart using ApexCharts
    const typeChartEl = document.getElementById('deviceTypeChart');
    if (typeChartEl) {
        new ApexCharts(typeChartEl, {
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                background: 'transparent',
                foreColor: '#9ca3af'
            },
            theme: {
                mode: 'dark',
                palette: 'palette1'
            },
            series: {!! json_encode($typeCounts->values()->toArray()) !!},
            labels: {!! json_encode($typeCounts->keys()->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))->toArray()) !!},
            colors: ['#22d3ee', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#ef4444', '#14b8a6', '#6366f1'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            name: { show: true, color: '#fff' },
                            value: { show: true, color: '#9ca3af' },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#9ca3af',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '11px',
                labels: { colors: '#9ca3af' },
                markers: { radius: 3 }
            },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark' },
            stroke: { show: false }
        }).render();
    }
    @endif
});
</script>
@endpush
