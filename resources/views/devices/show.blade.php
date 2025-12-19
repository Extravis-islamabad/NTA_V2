@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('devices.index') }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Devices
        </a>
    </div>

    <!-- Device Header -->
    <div class="glass-card mb-6 p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $device->name }}</h2>
                    <p class="text-sm text-purple-300/60 font-mono">{{ $device->ip_address }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" id="timeRangeForm" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <span class="text-sm text-purple-300/60">Time Range:</span>
                    <select name="range" onchange="this.form.submit()"
                        class="px-4 py-2 glass-input rounded-lg text-sm font-medium cursor-pointer text-white">
                        <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                        <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                        <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    </select>
                </form>
                <a href="{{ route('devices.edit', $device) }}" class="inline-flex items-center px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 text-purple-300 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <span class="px-3 py-1.5 text-sm font-semibold rounded-full {{ $device->status === 'online' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                    {{ ucfirst($device->status) }}
                </span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-4 pt-4 border-t border-purple-500/20">
            <div class="p-3 rounded-lg bg-purple-500/5 border border-purple-500/10">
                <p class="text-xs text-purple-300/50 uppercase tracking-wider mb-1">Type</p>
                <p class="text-lg font-semibold text-white">{{ ucfirst(str_replace('_', ' ', $device->type)) }}</p>
            </div>
            <div class="p-3 rounded-lg bg-purple-500/5 border border-purple-500/10">
                <p class="text-xs text-purple-300/50 uppercase tracking-wider mb-1">Location</p>
                <p class="text-lg font-semibold text-white">{{ $device->location ?? 'N/A' }}</p>
            </div>
            <div class="p-3 rounded-lg bg-purple-500/5 border border-purple-500/10">
                <p class="text-xs text-purple-300/50 uppercase tracking-wider mb-1">Interfaces</p>
                <p class="text-lg font-semibold text-white">{{ $device->interface_count }}</p>
            </div>
            <div class="p-3 rounded-lg bg-purple-500/5 border border-purple-500/10">
                <p class="text-xs text-purple-300/50 uppercase tracking-wider mb-1">Total Flows</p>
                <p class="text-lg font-semibold text-white">{{ number_format($device->flow_count) }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="glass-card overflow-hidden">
        <div class="border-b border-purple-500/20">
            <nav class="flex -mb-px">
                <a href="?tab=overview&range={{ $timeRange }}"
                   class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors {{ $tab === 'overview' ? 'border-b-2 border-purple-400 text-purple-400 bg-purple-500/10' : 'text-purple-300/60 hover:text-purple-300 hover:bg-purple-500/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Overview
                </a>
                <a href="?tab=flows&range={{ $timeRange }}"
                   class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors {{ $tab === 'flows' ? 'border-b-2 border-blue-400 text-blue-400 bg-blue-500/10' : 'text-purple-300/60 hover:text-purple-300 hover:bg-purple-500/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Flows
                </a>
                <a href="?tab=endpoints&range={{ $timeRange }}"
                   class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors {{ $tab === 'endpoints' ? 'border-b-2 border-emerald-400 text-emerald-400 bg-emerald-500/10' : 'text-purple-300/60 hover:text-purple-300 hover:bg-purple-500/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4l4 4m10-4l-4-4m4 4l-4 4"/>
                    </svg>
                    Endpoints
                </a>
                <a href="?tab=applications&range={{ $timeRange }}"
                   class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors {{ $tab === 'applications' ? 'border-b-2 border-cyan-400 text-cyan-400 bg-cyan-500/10' : 'text-purple-300/60 hover:text-purple-300 hover:bg-purple-500/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Applications
                </a>
                <a href="?tab=network&range={{ $timeRange }}"
                   class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors {{ $tab === 'network' ? 'border-b-2 border-amber-400 text-amber-400 bg-amber-500/10' : 'text-purple-300/60 hover:text-purple-300 hover:bg-purple-500/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    Network
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($tab === 'overview')
                @include('devices.tabs.overview')
            @elseif($tab === 'flows')
                @include('devices.tabs.flows')
            @elseif($tab === 'endpoints')
                @include('devices.tabs.endpoints')
            @elseif($tab === 'applications')
                @include('devices.tabs.applications')
            @elseif($tab === 'network')
                @include('devices.tabs.network')
            @endif
        </div>
    </div>
</div>
@endsection
