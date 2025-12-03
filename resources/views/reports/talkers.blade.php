@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-[#5548F5] hover:text-[#9619B5] flex items-center gap-2 text-sm font-medium mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reports
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-r from-[#5548F5] to-[#3B82F6] rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-7 w-auto">
                    <span class="text-gray-300 text-xl">|</span>
                    <h2 class="text-2xl font-bold text-gray-900">Top Talkers Report</h2>
                </div>
                <p class="text-gray-600 mt-1">Identify your heaviest bandwidth consumers</p>
            </div>
        </div>
    </div>

    <!-- Report Parameters -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow mb-6">
        <div class="gradient-light px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Report Parameters
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('reports.talkers') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" value="{{ request('start_date', now()->subDay()->format('Y-m-d\TH:i')) }}"
                               class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" name="end_date" value="{{ request('end_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Device</label>
                        <select name="device_id" class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3">
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
                            <button type="submit" class="flex-1 min-w-[120px] px-4 py-2.5 bg-[#5548F5] hover:bg-[#4338ca] text-white rounded-lg font-medium flex items-center justify-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Analyze
                            </button>
                            <a href="{{ route('reports.export') }}?type=talkers&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                               class="px-4 py-2.5 bg-[#9619B5] hover:bg-[#7c1497] text-white rounded-lg font-medium flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                CSV
                            </a>
                            @if(isset($topSources))
                            <a href="{{ route('reports.talkers.pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}"
                               class="px-4 py-2.5 bg-[#C843F3] hover:bg-[#a835cc] text-white rounded-lg font-medium flex items-center gap-2 transition">
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
        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
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

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
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

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
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

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Unique Sources</p>
                    <p class="text-3xl font-bold text-[#9619B5] mt-1">{{ $topSources->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Sources Chart -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                    Top Sources by Traffic
                </h3>
                <span class="px-3 py-1 bg-[#E4F2FF] text-[#5548F5] text-xs font-semibold rounded-full">
                    {{ $topSources->count() }} IPs
                </span>
            </div>
            <div class="p-6">
                @if($topSources->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No source data available</p>
                    </div>
                @else
                    <div style="height: 300px;">
                        <canvas id="sourcesChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Destinations Chart -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                    Top Destinations by Traffic
                </h3>
                <span class="px-3 py-1 bg-[#F2C7FF] text-[#9619B5] text-xs font-semibold rounded-full">
                    {{ $topDestinations->count() }} IPs
                </span>
            </div>
            <div class="p-6">
                @if($topDestinations->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No destination data available</p>
                    </div>
                @else
                    <div style="height: 300px;">
                        <canvas id="destinationsChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Sources Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 bg-gradient-to-r from-[#5548F5] to-[#C843F3]">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                    Top 20 Source IPs
                </h3>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Source IP</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Traffic</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Flows</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topSources as $index => $source)
                        <tr class="table-row-hover transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                     style="background: {{ ['#5548F5', '#C843F3', '#9619B5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'][$index % 10] }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-sm text-gray-900">{{ $source->source_ip }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
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
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($source->flow_count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($source->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-12 bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full gradient-primary" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-10 text-right">{{ number_format($percent, 1) }}%</span>
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
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 bg-gradient-to-r from-[#C843F3] to-[#9619B5]">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                    Top 20 Destination IPs
                </h3>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Destination IP</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Traffic</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Flows</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topDestinations as $index => $dest)
                        <tr class="table-row-hover transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold"
                                     style="background: {{ ['#C843F3', '#9619B5', '#5548F5', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6'][$index % 10] }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-sm text-gray-900">{{ $dest->destination_ip }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
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
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($dest->flow_count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-12 bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full gradient-secondary" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-10 text-right">{{ number_format($percent, 1) }}%</span>
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
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Top 25 Conversations
            </h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded-full">
                {{ $topConversations->count() }} Conversations
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Source IP</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Destination IP</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Protocol</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Traffic</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Packets</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Flows</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">% Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topConversations as $index => $conv)
                    <tr class="table-row-hover transition">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono text-sm text-[#5548F5]">{{ $conv->source_ip }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono text-sm text-[#C843F3]">{{ $conv->destination_ip }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if(strtoupper($conv->protocol) == 'TCP') bg-blue-100 text-blue-700
                                @elseif(strtoupper($conv->protocol) == 'UDP') bg-green-100 text-green-700
                                @elseif(strtoupper($conv->protocol) == 'ICMP') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ strtoupper($conv->protocol) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
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
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($conv->total_packets) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-600">{{ number_format($conv->flow_count) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            @php $percent = $totalBytes > 0 ? ($conv->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($percent, 2) }}%</span>
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
    <div class="bg-white rounded-2xl shadow-lg p-12 text-center monetx-shadow">
        <div class="w-24 h-24 mx-auto bg-gradient-to-r from-[#E4F2FF] to-[#F2C7FF] rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Analyze Your Top Talkers</h3>
        <p class="text-gray-600 mb-6">Select a date range and click "Analyze" to identify your heaviest bandwidth consumers.</p>
        <button onclick="document.querySelector('form').submit()" class="px-8 py-3 btn-monetx text-white rounded-xl font-semibold">
            Start Analysis
        </button>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
@if(isset($topSources) && $topSources->isNotEmpty())
// Sources Chart
const sourcesCtx = document.getElementById('sourcesChart');
if (sourcesCtx) {
    new Chart(sourcesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topSources->take(10)->pluck('source_ip')->toArray()) !!},
            datasets: [{
                label: 'Traffic',
                data: {!! json_encode($topSources->take(10)->pluck('total_bytes')->toArray()) !!},
                backgroundColor: '#5548F5',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const bytes = context.raw;
                            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
                            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
                            return (bytes / 1024).toFixed(2) + ' KB';
                        }
                    }
                }
            },
            scales: {
                x: {
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
                y: {
                    grid: { display: false },
                    ticks: { font: { family: 'monospace', size: 10 } }
                }
            }
        }
    });
}
@endif

@if(isset($topDestinations) && $topDestinations->isNotEmpty())
// Destinations Chart
const destCtx = document.getElementById('destinationsChart');
if (destCtx) {
    new Chart(destCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topDestinations->take(10)->pluck('destination_ip')->toArray()) !!},
            datasets: [{
                label: 'Traffic',
                data: {!! json_encode($topDestinations->take(10)->pluck('total_bytes')->toArray()) !!},
                backgroundColor: '#C843F3',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const bytes = context.raw;
                            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
                            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
                            return (bytes / 1024).toFixed(2) + ' KB';
                        }
                    }
                }
            },
            scales: {
                x: {
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
                y: {
                    grid: { display: false },
                    ticks: { font: { family: 'monospace', size: 10 } }
                }
            }
        }
    });
}
@endif
</script>
@endpush
