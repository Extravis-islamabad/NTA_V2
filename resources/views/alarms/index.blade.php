@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-500/20 border border-amber-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Alarms</h2>
                <p class="text-sm text-gray-400">Monitor and manage network alerts</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card p-5 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Total Alarms</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-gray-500/20 rounded-xl p-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Active</p>
                    <p class="text-3xl font-bold text-cyan-400 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-cyan-500/20 rounded-xl p-3">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Critical</p>
                    <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['critical'] }}</p>
                </div>
                <div class="bg-red-500/20 rounded-xl p-3">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Warning</p>
                    <p class="text-3xl font-bold text-amber-400 mt-1">{{ $stats['warning'] }}</p>
                </div>
                <div class="bg-amber-500/20 rounded-xl p-3">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card mb-6 p-4">
        <form method="GET" action="{{ route('alarms.index') }}" class="flex gap-4 flex-wrap items-center">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="text-sm text-gray-400">Filter by:</span>
            </div>

            <select name="status" class="glass-input rounded-lg px-4 py-2 min-w-[150px]">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>

            <select name="severity" class="glass-input rounded-lg px-4 py-2 min-w-[150px]">
                <option value="">All Severity</option>
                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="info" {{ request('severity') === 'info' ? 'selected' : '' }}>Info</option>
            </select>

            <button type="submit" class="btn-primary">
                Apply Filters
            </button>

            @if(request('status') || request('severity'))
            <a href="{{ route('alarms.index') }}" class="btn-ghost text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Alarms List -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Severity</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Device</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider bg-[var(--bg-input)]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($alarms as $alarm)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($alarm->severity === 'critical')
                                <span class="badge-danger">Critical</span>
                            @elseif($alarm->severity === 'warning')
                                <span class="badge-warning">Warning</span>
                            @else
                                <span class="badge-info">Info</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $alarm->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($alarm->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            @if($alarm->device)
                                <a href="{{ route('devices.show', $alarm->device) }}" class="text-cyan-400 hover:text-cyan-300 font-medium">
                                    {{ $alarm->device->name }}
                                </a>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $alarm->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($alarm->status === 'active')
                                <span class="badge-danger">Active</span>
                            @elseif($alarm->status === 'acknowledged')
                                <span class="badge-warning">Acknowledged</span>
                            @else
                                <span class="badge-success">Resolved</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <span title="{{ $alarm->created_at->format('M d, Y H:i:s') }}">
                                {{ $alarm->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-3">
                                @if($alarm->status === 'active')
                                    <form action="{{ route('alarms.acknowledge', $alarm) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-cyan-400 hover:text-cyan-300 font-medium text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ack
                                        </button>
                                    </form>
                                @endif
                                @if($alarm->status !== 'resolved')
                                    <form action="{{ route('alarms.resolve', $alarm) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 font-medium text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Resolve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-lg font-medium text-white mb-1">All Clear!</p>
                                <p class="text-gray-400 text-sm">No alarms match your current filters.</p>
                                @if(request('status') || request('severity'))
                                <a href="{{ route('alarms.index') }}" class="mt-4 text-cyan-400 hover:text-cyan-300 text-sm font-medium">
                                    Clear filters to see all alarms
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($alarms->hasPages())
        <div class="px-6 py-4 border-t border-white/10">
            {{ $alarms->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
