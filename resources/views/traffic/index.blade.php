@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">Traffic Analysis</h2>
    </div>

    <!-- Device Selection -->
    <div class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('traffic.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">Select Device</label>
                <select name="device_id" class="w-full glass-input rounded-lg px-4 py-2" onchange="this.form.submit()">
                    <option value="">Choose a device...</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                            {{ $device->name }} ({{ $device->ip_address }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Time Range</label>
                <select name="range" class="glass-input rounded-lg px-4 py-2" onchange="this.form.submit()">
                    <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                    <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                    <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                </select>
            </div>
        </form>
    </div>

    @if($selectedDevice)
        <div class="text-center py-8">
            <p class="text-gray-300">Viewing traffic for: <span class="font-bold text-white">{{ $selectedDevice->name }}</span></p>
            <a href="{{ route('traffic.show', $selectedDevice) }}?range={{ $timeRange }}" class="mt-4 inline-block btn-monetx text-white px-6 py-2 rounded-lg">
                View Detailed Traffic Analysis
            </a>
        </div>
    @else
        <div class="glass-card p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-white">Select a Device</h3>
            <p class="mt-2 text-sm text-gray-400">Choose a device from the dropdown above to view its traffic analysis</p>
        </div>
    @endif
</div>
@endsection
