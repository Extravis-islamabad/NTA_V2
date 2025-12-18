@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">Alarms</h2>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Alarms</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white/10 rounded-full p-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Active</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-blue-500/20 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Critical</p>
                    <p class="text-3xl font-bold text-red-400">{{ $stats['critical'] }}</p>
                </div>
                <div class="bg-red-500/20 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Warning</p>
                    <p class="text-3xl font-bold text-yellow-400">{{ $stats['warning'] }}</p>
                </div>
                <div class="bg-yellow-500/20 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card mb-6 p-4">
        <form method="GET" action="{{ route('alarms.index') }}" class="flex gap-4 flex-wrap">
            <select name="status" class="glass-input rounded-lg px-4 py-2">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>

            <select name="severity" class="glass-input rounded-lg px-4 py-2">
                <option value="">All Severity</option>
                <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="info" {{ request('severity') === 'info' ? 'selected' : '' }}>Info</option>
            </select>

            <button type="submit" class="btn-monetx text-white px-4 py-2 rounded-lg">
                Apply Filters
            </button>

            <a href="{{ route('alarms.index') }}" class="bg-white/10 hover:bg-white/20 text-gray-300 px-4 py-2 rounded-lg transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Alarms List -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-purple-500/20">
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Severity</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Device</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($alarms as $alarm)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $alarm->severity === 'critical' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : ($alarm->severity === 'warning' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30') }}">
                                {{ ucfirst($alarm->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $alarm->title }}</div>
                            <div class="text-sm text-gray-400">{{ Str::limit($alarm->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            @if($alarm->device)
                                <a href="{{ route('devices.show', $alarm->device) }}" class="text-purple-400 hover:text-purple-300">
                                    {{ $alarm->device->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $alarm->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $alarm->status === 'active' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : ($alarm->status === 'acknowledged' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-green-500/20 text-green-400 border border-green-500/30') }}">
                                {{ ucfirst($alarm->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $alarm->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                @if($alarm->status === 'active')
                                    <form action="{{ route('alarms.acknowledge', $alarm) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-400 hover:text-blue-300">Acknowledge</button>
                                    </form>
                                @endif
                                @if($alarm->status !== 'resolved')
                                    <form action="{{ route('alarms.resolve', $alarm) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-400 hover:text-green-300">Resolve</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2">No alarms found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-white/10">
            {{ $alarms->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
