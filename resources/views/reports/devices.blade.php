@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-[#5548F5] hover:text-[#9619B5] flex items-center gap-2 text-sm font-medium mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reports
        </a>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-r from-[#C843F3] to-[#9619B5] rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Device Inventory Report</h2>
                    <p class="text-gray-600">Complete overview of all monitored network devices</p>
                </div>
            </div>
            <a href="{{ route('reports.export') }}?type=devices"
               class="px-4 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Devices</p>
                    <p class="text-3xl font-bold text-[#5548F5] mt-1">{{ $devices->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-[#E4F2FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Online Devices</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $devices->where('status', 'online')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                @php $onlinePercent = $devices->count() > 0 ? ($devices->where('status', 'online')->count() / $devices->count()) * 100 : 0; @endphp
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full bg-green-500" style="width: {{ $onlinePercent }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($onlinePercent, 1) }}% availability</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Interfaces</p>
                    <p class="text-3xl font-bold text-[#C843F3] mt-1">{{ $devices->sum('interface_count') }}</p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-[#9619B5] mt-1">{{ number_format($devices->sum('flow_count')) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Type Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Device Type Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Device Types
                </h3>
            </div>
            <div class="p-6">
                <div style="height: 200px;">
                    <canvas id="deviceTypeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Online</span>
                            <span class="text-green-600 font-bold">{{ $onlineCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-green-500" style="width: {{ ($onlineCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Offline</span>
                            <span class="text-red-600 font-bold">{{ $offlineCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-red-500" style="width: {{ ($offlineCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Warning</span>
                            <span class="text-yellow-600 font-bold">{{ $warningCount }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-yellow-500" style="width: {{ ($warningCount / $total) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Devices by Flows -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Top Devices by Flows
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($devices->sortByDesc('flow_count')->take(5) as $device)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold gradient-primary">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $device->name }}</p>
                                <p class="text-xs text-gray-500">{{ $device->ip_address }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-[#5548F5]">{{ number_format($device->flow_count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Device List Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#C843F3] to-[#9619B5]">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Device Inventory Details
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Interfaces</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Flows</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($devices as $device)
                    <tr class="hover:bg-[#E4F2FF]/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $device->status === 'online' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <svg class="w-5 h-5 {{ $device->status === 'online' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('devices.show', $device) }}" class="font-medium text-[#5548F5] hover:text-[#9619B5] transition">
                                        {{ $device->name }}
                                    </a>
                                    @if($device->device_group)
                                        <p class="text-xs text-gray-500">{{ $device->device_group }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-gray-600">{{ $device->ip_address }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-[#E4F2FF] text-[#5548F5]">
                                {{ ucfirst(str_replace('_', ' ', $device->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $device->location ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-medium text-gray-900">{{ $device->interface_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-bold text-[#C843F3]">{{ number_format($device->flow_count) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($device->status === 'online')
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Online
                                </span>
                            @elseif($device->status === 'warning')
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                    Warning
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    Offline
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('devices.show', $device) }}"
                               class="text-[#5548F5] hover:text-[#9619B5] transition">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">No devices found</p>
                            <a href="{{ route('devices.create') }}" class="mt-4 inline-block px-4 py-2 btn-monetx text-white rounded-lg text-sm">
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
// Device Type Chart
const typeCtx = document.getElementById('deviceTypeChart');
if (typeCtx) {
    @php
        $typeCounts = $devices->groupBy('type')->map->count();
    @endphp
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($typeCounts->keys()->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))->toArray()) !!},
            datasets: [{
                data: {!! json_encode($typeCounts->values()->toArray()) !!},
                backgroundColor: ['#5548F5', '#C843F3', '#9619B5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: { size: 10 }
                    }
                }
            },
            cutout: '55%'
        }
    });
}
</script>
@endpush
