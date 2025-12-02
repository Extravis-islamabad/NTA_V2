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
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Traffic Analysis Report</h2>
                <p class="text-gray-600">Comprehensive bandwidth and protocol analysis</p>
            </div>
        </div>
    </div>

    <!-- Report Parameters -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow mb-6">
        <div class="gradient-light px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Report Parameters
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.traffic') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" value="{{ request('start_date', now()->subDay()->format('Y-m-d\TH:i')) }}"
                               class="w-full border-gray-300 rounded-lg focus:ring-[#5548F5] focus:border-[#5548F5]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" name="end_date" value="{{ request('end_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border-gray-300 rounded-lg focus:ring-[#5548F5] focus:border-[#5548F5]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Device</label>
                        <select name="device_id" class="w-full border-gray-300 rounded-lg focus:ring-[#5548F5] focus:border-[#5548F5]">
                            <option value="">All Devices</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }} ({{ $device->ip_address }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 btn-monetx text-white rounded-lg font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Generate
                        </button>
                        <a href="{{ route('reports.export') }}?type=traffic&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                           class="px-4 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($totalBytes))
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Report Period</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $start->format('M d') }} - {{ $end->format('M d, Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-[#E4F2FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-[#5548F5] mt-1">{{ number_format($totalFlows) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#E4F2FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Traffic</p>
                    <p class="text-3xl font-bold text-[#C843F3] mt-1">
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
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 monetx-shadow monetx-hover transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Packets</p>
                    <p class="text-3xl font-bold text-[#9619B5] mt-1">{{ number_format($totalPackets) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Applications Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Top Applications
                </h3>
                <span class="px-3 py-1 bg-[#E4F2FF] text-[#5548F5] text-xs font-semibold rounded-full">
                    {{ $topApplications->count() }} Apps
                </span>
            </div>
            <div class="p-6">
                @if($topApplications->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No application data available</p>
                    </div>
                @else
                    <div style="height: 280px;">
                        <canvas id="applicationsChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Protocols Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Protocol Distribution
                </h3>
                <span class="px-3 py-1 bg-[#F2C7FF] text-[#9619B5] text-xs font-semibold rounded-full">
                    {{ $topProtocols->count() }} Protocols
                </span>
            </div>
            <div class="p-6">
                @if($topProtocols->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No protocol data available</p>
                    </div>
                @else
                    <div style="height: 280px;">
                        <canvas id="protocolsChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Applications Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#5548F5] to-[#C843F3]">
                <h3 class="text-lg font-bold text-white">Top Applications Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Application</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Flows</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Traffic</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topApplications as $index => $app)
                        <tr class="hover:bg-[#E4F2FF]/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                         style="background: {{ ['#5548F5', '#C843F3', '#9619B5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'][$index % 10] }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $app->application }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($app->flow_count) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
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
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full gradient-primary" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($percent, 1) }}%</span>
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
        <div class="bg-white rounded-xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#C843F3] to-[#9619B5]">
                <h3 class="text-lg font-bold text-white">Protocol Distribution Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Protocol</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Flows</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Traffic</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topProtocols as $index => $protocol)
                        <tr class="hover:bg-[#F2C7FF]/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                         style="background: {{ ['#C843F3', '#9619B5', '#5548F5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'][$index % 10] }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="font-medium text-gray-900 uppercase">{{ $protocol->protocol }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($protocol->flow_count) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
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
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-[#C843F3] to-[#9619B5]" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($percent, 1) }}%</span>
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
    <div class="bg-white rounded-xl shadow-lg p-12 text-center monetx-shadow">
        <div class="w-20 h-20 mx-auto bg-[#E4F2FF] rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Generate Your First Report</h3>
        <p class="text-gray-600 mb-6">Select a date range and click "Generate" to analyze your network traffic data.</p>
        <div class="flex justify-center gap-4">
            <button onclick="document.querySelector('form').submit()" class="px-6 py-2.5 btn-monetx text-white rounded-lg font-medium">
                Generate Report Now
            </button>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
@if(isset($topApplications) && $topApplications->isNotEmpty())
// Applications Chart
const appCtx = document.getElementById('applicationsChart');
if (appCtx) {
    new Chart(appCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topApplications->pluck('application')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($topApplications->pluck('total_bytes')->toArray()) !!},
                backgroundColor: ['#5548F5', '#C843F3', '#9619B5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const bytes = context.raw;
                            let size;
                            if (bytes >= 1073741824) {
                                size = (bytes / 1073741824).toFixed(2) + ' GB';
                            } else if (bytes >= 1048576) {
                                size = (bytes / 1048576).toFixed(2) + ' MB';
                            } else {
                                size = (bytes / 1024).toFixed(2) + ' KB';
                            }
                            return context.label + ': ' + size;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}
@endif

@if(isset($topProtocols) && $topProtocols->isNotEmpty())
// Protocols Chart
const protocolCtx = document.getElementById('protocolsChart');
if (protocolCtx) {
    new Chart(protocolCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProtocols->pluck('protocol')->map(fn($p) => strtoupper($p))->toArray()) !!},
            datasets: [{
                label: 'Traffic',
                data: {!! json_encode($topProtocols->pluck('total_bytes')->toArray()) !!},
                backgroundColor: '#C843F3',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const bytes = context.raw;
                            let size;
                            if (bytes >= 1073741824) {
                                size = (bytes / 1073741824).toFixed(2) + ' GB';
                            } else if (bytes >= 1048576) {
                                size = (bytes / 1048576).toFixed(2) + ' MB';
                            } else {
                                size = (bytes / 1024).toFixed(2) + ' KB';
                            }
                            return size;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1073741824) return (value / 1073741824).toFixed(1) + ' GB';
                            if (value >= 1048576) return (value / 1048576).toFixed(1) + ' MB';
                            if (value >= 1024) return (value / 1024).toFixed(1) + ' KB';
                            return value + ' B';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}
@endif
</script>
@endpush
